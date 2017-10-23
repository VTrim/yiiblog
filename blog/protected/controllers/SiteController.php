<?php
class SiteController extends Controller

{
	/**
	 * Declares class-based actions.
	 */
    public $layout = '//layouts/column2';
     
	public function actions()
	{
		return array(

			// captcha action renders the CAPTCHA image displayed on the contact page

			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
                'testLimit' => 100
			) ,

			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName

			'page' => array(
				'class' => 'CViewAction',
			) ,
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
	    $criteria = new CDbCriteria;
		$criteria->select = array('c.id', 'p.id', 'p.title', 'c.title', 'p.pub_date', 'c.status');
		$criteria->alias = 'p';
		$criteria->join = 'LEFT JOIN categories c ON p.category_id = c.id';
		$criteria->condition = 'p.status = "true" AND c.status = "true" AND p.pub_date <= :currentDate';
		$criteria->params = array(
			':currentDate' => date('Y-m-d')
		);
		$criteria->with = array(
			'categories'
		);
        
		$criteria->order = 'p.id DESC, p.pub_date DESC';

		$dataProvider = new CActiveDataProvider('Post', array(
			'criteria' => $criteria
		));
		$this->render('/post/index', array(
			'dataProvider' => $dataProvider,
			'pagination' => array(
				'pageSize' => 10,
			) ,
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error)
		{
			if (Yii::app()->request->isAjaxRequest) echo $error['message'];
			else $this->render('error', $error);
		}
	}

	public function actionAjaxlogin()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$model = new LoginForm();
			$this->renderPartial('login', array(
				'model' => $model
			));
		}
		else
		{
			throw new CHttpException(404, 'Page not found');
		}
	}

	public function actionAjaxregistration()
	{
		//if (Yii::app()->request->isAjaxRequest)
		//{
			$model = new RegistrationForm();
			$this->renderPartial('registration', array(
				'model' => $model
			));
		//}
		//else
		//{
		//	throw new CHttpException(404, 'Page not found');
	//	}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model = new ContactForm;
		if (isset($_POST['ContactForm']))
		{
			$model->attributes = $_POST['ContactForm'];
			if ($model->validate())
			{
				$name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
				$headers = "From: $name <{$model->email}>\r\n" . "Reply-To: {$model->email}\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/plain; charset=UTF-8";
				mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
				Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}

		$this->render('contact', array(
			'model' => $model
		));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		// if it is ajax validation request

		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			// validate user input and redirect to the previous page if valid

			if ($model->validate() && $model->login()) $this->redirect(Yii::app()->user->returnUrl);
		}

		// display the login form

		$this->render('login', array(
			'model' => $model
		));
	}

	public function actionRegistration()
	{
		$model = new RegistrationForm;
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-registration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['RegistrationForm']))
		{
			$model->attributes = $_POST['RegistrationForm'];
			if ($model->validate())
			{
				$user = new User;
				$user->username = $model->username;
				$user->email = $model->email;
				$user->status = '';
				$user->role = 'user';
				$user->password = CPasswordHelper::hashPassword($model->password);
				$user->password_salt = uniqid();
                $user->datetime_registration = date('Y-m-d H:i:s');
				if ($user->save())
				{
					$auth = new UserIdentity($model->username, $model->password);
					$auth->authenticate();
					Yii::app()->user->login($auth, 3600 * 24 * 30);
					$this->redirect(Yii::app()->user->returnUrl);
				}

				return;
			}
		}

		$this->render('registration', array(
			'model' => $model
		));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}