<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>



<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
    
    <?php if(Yii::app()->user->isGuest): ?>
    
    <script type="text/javascript" src="/assets/35413f7d/jquery.yiiactiveform.js"></script>

<?php

Yii::app()->clientScript->registerScript('setAuthForm',
 "function setAuthForm(){ 
 $('#ajaxAuthForm').show();
  ".CHtml::ajax(
array('url'=>CController::createUrl('site/ajaxlogin'), 'update'=>'#ajaxAuthForm')
)."}", CClientScript::POS_END);

?>

<?php

Yii::app()->clientScript->registerScript('setRegForm',
 "function setRegForm(){ 
 $('#ajaxRegForm').show();
 ".CHtml::ajax(
array('url'=>CController::createUrl('site/ajaxregistration'), 'update'=>'#ajaxRegForm')
)."}", CClientScript::POS_END);

?>
    
    
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'loginDialog',
    'options'=>array(
        'title'=>'Autorization',
        'autoOpen'=>false,
        'height'=> '350',
    ),
));

?>

<div style="display: none" id="ajaxAuthForm"><center><img src="/images/loader.gif"></center></div>

<?php Yii::app()->clientScript->registerScript('loginFormInserted',
 "insertedLogin = false;
 $('#ajaxAuthForm').bind('DOMNodeInserted',function(e){
     if(!insertedLogin) {
        jQuery('#login-form').yiiactiveform({'validateOnSubmit':true,'attributes':[{'id':'LoginForm_username','inputID':'LoginForm_username','errorID':'LoginForm_username_em_','model':'LoginForm','name':'username','enableAjaxValidation':true},{'id':'LoginForm_password','inputID':'LoginForm_password','errorID':'LoginForm_password_em_','model':'LoginForm','name':'password','enableAjaxValidation':true},{'id':'LoginForm_rememberMe','inputID':'LoginForm_rememberMe','errorID':'LoginForm_rememberMe_em_','model':'LoginForm','name':'rememberMe','enableAjaxValidation':true}],'errorCss':'error'});
     }
    insertedLogin = true;
})", CClientScript::POS_END); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'registrationDialog',
     
    'options'=>array(
        'title'=>'Registration',
        'autoOpen'=>false,
        'height'=> '650',
    ),
));

?>

<div style="display: none" id="ajaxRegForm"><center><img src="/images/loader.gif"></center></div>

<?php Yii::app()->clientScript->registerScript('registrationFormInserted',
 "insertedRegistration = false;
 $('#ajaxRegForm').bind('DOMNodeInserted',function(e){
     if(!insertedRegistration) {
       jQuery('#user-registration-form').yiiactiveform({'validateOnSubmit':true,'attributes':[{'id':'RegistrationForm_username','inputID':'RegistrationForm_username','errorID':'RegistrationForm_username_em_','model':'RegistrationForm','name':'username','enableAjaxValidation':true,'summary':true},{'id':'RegistrationForm_email','inputID':'RegistrationForm_email','errorID':'RegistrationForm_email_em_','model':'RegistrationForm','name':'email','enableAjaxValidation':true,'summary':true},{'id':'RegistrationForm_password','inputID':'RegistrationForm_password','errorID':'RegistrationForm_password_em_','model':'RegistrationForm','name':'password','enableAjaxValidation':true,'summary':true},{'id':'RegistrationForm_repeat_password','inputID':'RegistrationForm_repeat_password','errorID':'RegistrationForm_repeat_password_em_','model':'RegistrationForm','name':'repeat_password','enableAjaxValidation':true,'summary':true},{'id':'RegistrationForm_captcha','inputID':'RegistrationForm_captcha','errorID':'RegistrationForm_captcha_em_','model':'RegistrationForm','name':'captcha','enableAjaxValidation':true,'summary':true}],'summaryID':'user\x2Dregistration\x2Dform_es_','errorCss':'error'});
    }
    insertedRegistration = true;
})", CClientScript::POS_END); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php endif; ?>

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('#'), 'itemOptions'=>array('onclick'=>'$("#loginDialog").dialog("open"); setAuthForm(); return false;'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Registration', 'url'=>array('#'), 'itemOptions'=>array('onclick'=>' $("#registrationDialog").dialog("open"); setRegForm(); return false;'), 'visible'=>Yii::app()->user->isGuest), 
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Admin Panel', 'url'=>array('/post/admin'), 'visible'=>Yii::app()->user->checkAccess('admin'))
			),
		)); ?>
	</div><!-- mainmenu -->
   
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
