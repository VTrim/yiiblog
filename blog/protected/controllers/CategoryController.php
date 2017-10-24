<?php
class PostController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array(
					'index',
					'view',
					'ajax'
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{      
        $modelComment = new Comment;

		$this->performAjaxValidation($modelComment);

		if(!Yii::app()->user->isGuest && isset($_POST['Comment']))
		{
			$modelComment->attributes=$_POST['Comment'];
            $modelComment->post_id = $id;
            $modelComment->user_id = Yii::app()->user->getId();
            $modelComment->date_created = date('Y-m-d');
            $modelComment->status = 'true';     
			$modelComment->save();
		}
        
        
		$criteria = new CDbCriteria;
        $criteria->select = array('c.content', 'c.date_created');
        $criteria->alias = 'c';
        $criteria->join = 'LEFT JOIN users u ON c.user_id = u.id';
		$criteria->condition = 'post_id = :post_id';
		$criteria->params = array(
			':post_id' => $id
		);
        $criteria->with = array('users'=>array('select'=>array('username')));
        $criteria->order = 'c.date_created, c.id DESC';
		      
        $dataProvider=new CActiveDataProvider('Comment', array(
        
            'criteria' => $criteria
        
        )); 
        
       $viewImages = PostImage::model()->findAll('post_id=:postID', array(':postID'=>$id));
       $contentId = CHtml::activeId($modelComment,'content');       
        
        $this->render('view', array(
			'model' => $this->loadModel($id) ,
            'viewImages' => $viewImages,
            'dataProvider'=>$dataProvider,
            'modelComment' => $modelComment,
            'contentId' => $contentId 
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Post;
		$image = new PostImage;
		$this->performAjaxValidation($model);
		if (isset($_POST['PostImage']))
		{
			$image->image = CUploadedFile::getInstance($image, 'image');
			$image->post_id = 0;
			if ($image->validate())
			{
				$fileName = uniqid() . '.' . $image->image->getExtensionName();
				$filePath = Yii::getPathOfAlias('webroot.upload') . DIRECTORY_SEPARATOR . $fileName;
				$image->image->saveAs($filePath);
				$image->image = $fileName;
				$image->save();
				$files = [];
				if (Yii::app()->session['addImages'])
				{
					$arrayOfFiles = json_decode(Yii::app()->session['addImages']);
				}

				$arrayOfFiles[] = ['id' => $image->id, 'image' => $image->image];
				$jsonFiles = json_encode($arrayOfFiles);
				Yii::app()->session['addImages'] = $jsonFiles;
			}
		}

		if (Yii::app()->session['addImages'])
		{
			$jsonFiles = json_decode(Yii::app()->session['addImages']);
		}

		if (isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			$model->status = 'true';
			if ($model->save())
			{
				if (isset($jsonFiles))
				{
					$ids = null;
					foreach($jsonFiles as $currentFile) $ids.= $currentFile->id . ',';
					PostImage::model()->updateAll(array(
						'post_id' => $model->id
					) , 'id IN (' . trim($ids, ',') . ')');
					Yii::app()->session->remove('addImages');
				}

				$this->redirect(array(
					'view',
					'id' => $model->id
				));
			}
		}

        $categories = $this->loadCategories();
		$this->render('create', array(
			'model' => $model,
            'categories' => $categories,
			'image' => $image,
			'jsonFiles' => $jsonFiles ?? null
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
        $image = new PostImage;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Post']))
		{
			$model->attributes = $_POST['Post'];
			if ($model->save()) $this->redirect(array(
				'view',
				'id' => $model->id
			));
		}

        $categories = $this->loadCategories();
		$this->render('update', array(
			'model' => $model,
            'categories' => $categories,
            'image' => $image
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        $this->loadModel($id)->delete();
        $deleteFiles = PostImage::model()->findAll('post_id = :postID', array(':postID' => $id));
        foreach($deleteFiles as $curFile)
        {
            unlink(Yii::getPathOfAlias('webroot.upload') . DIRECTORY_SEPARATOR . $curFile->image);
        }
        PostImage::model()->deleteAll('post_id = :postID', array(':postID' => $id));

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

		if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
			'admin'
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Post('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Post'])) $model->attributes = $_GET['Post'];
		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Post the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Post::model()->findByPk($id);
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

	/**
	 * Performs the AJAX validation.
	 * @param Post $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
