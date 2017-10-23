<?php
class CategoryController extends Controller
{
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public

	function accessRules()
	{
		return array(
			array(
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array(
					'index',
					'view'
				) ,
				'users' => array(
					'*'
				) ,
			) ,
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
					'create',
					'update'
				) ,
				'users' => array(
					'admin'
				) ,
			) ,
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(
					'admin',
					'delete',
                    'create',
				) ,
				'users' => array(
					'admin'
				) ,
			) ,
			array(
				'deny', // deny all users
				'users' => array(
					'*'
				) ,
			) ,
		);
	}

	public function actionView($id)
	{
        $ids = null;
        $subcategories = null;
        $model = $this->loadModel($id);
        $ids .= $model->id . ',';
        
        $descendants = $model->descendants()->findAll();
		foreach($descendants as $category)
        {
            $ids .= $category->id . ',';
            $subcategories .= $category->title . ', ';
        }
    
	    $criteria = new CDbCriteria;
		$criteria->select = array('c.id', 'p.id', 'p.title', 'c.title as category_title', 'p.pub_date', 'c.status');
		$criteria->alias = 'p';
		$criteria->join = 'LEFT JOIN categories c ON  c.id = p.category_id';
		$criteria->condition = 'p.category_id IN('.trim($ids, ',').') AND p.status = "true" AND c.status = "true" AND p.pub_date <= :currentDate';
		$criteria->params = array(
			':currentDate' => date('Y-m-d')
		);
		$criteria->with = array(
	      'categories'
		);
        
		$criteria->order = 'p.pub_date DESC';
        
		$dataProvider = new CActiveDataProvider('Post', array(
			'criteria' => $criteria
		));

		$this->render('/post/index', array(
            'model' => $model,
            'subcategories' => $subcategories,
			'dataProvider' => $dataProvider,
			'pagination' => array(
				'pageSize' => 10,
			) ,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Category;
		if (isset($_POST['Category']))
		{
			$model->attributes = $_POST['Category'];
			if ($model->validate())
			{
				$model->status = 'true';
				if (!empty($_POST['Category']['root']))
				{
					$parentCategory = $this->loadModel($_POST['Category']['root']);
					$model->appendTo($parentCategory);
				}
				else
				{
					$model->saveNode();
				}

				$this->redirect(array(
					'category/admin'
				));
			}
		}

		$categories = $this->loadCategories();
        $this->render('create', array(
			'model' => $model,
            'categories' => $categories
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$this->performAjaxValidation($model);
		if (isset($_POST['Category']))
		{
			$model->attributes = $_POST['Category'];
			Category::model()->updateByPk($id, array(
				'title' => $model->title                  
			));
            
            if($model->root != $id)
            {
			$new_category = $this->loadModel($_POST['Category']['root']);
			$model->moveAsFirst($new_category);
            }
		}
        
        $categories = $this->loadCategories();

		$this->render('update', array(
			'model' => $model,
            'categories' => $categories,
			'upd' => true
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id);
		$categoryIds = null;
		$postIds = null;
		$imageIds = null;
		$descendants = $this->loadModel($id)->descendants()->findAll();
		foreach($descendants as $category)
		{
			$categoryIds.= $category->id . ',';
			$categoryPosts = Post::model()->findAll('category_id = :categoryID', array(
				':categoryID' => $category->id
			));
			foreach($categoryPosts as $post)
			{
				$postIds.= $post->id . ',';
				$postImages = PostImage::model()->findAll('post_id = :postID', array(
					':postID' => $post->id
				));
				foreach($postImages as $image)
				{
					$imageIds.= $image->id . ',';
				}
			}
		}

		Category::model()->deleteByPk($id, array() , array());
		if (!empty($categoryIds))
		{
			Category::model()->deleteAll('id IN (' . trim($categoryIds, ',') . ')', array());
		}

		if (!empty($postIds))
		{
			Post::model()->deleteAll('id IN (' . trim($postIds, ',') . ')', array());
		}

		if (!empty($imageIds))
		{
			PostImage::model()->deleteAll('id IN (' . trim($imageIds, ',') . ')', array());
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

		if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
			'admin'
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Category');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Category('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Category'])) $model->attributes = $_GET['Category'];
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function loadModel($id)
	{
		$model = Category::model()->findByPk($id);
		if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
    
    public function loadCategories()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.root, t.lft';
        $categories = Category::model()->findAll($criteria);
        return $categories;
    }

	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}