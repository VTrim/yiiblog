<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-registration-form',
	'action'=>CHtml::normalizeUrl(array('site/registration')),
	'enableAjaxValidation'=>true,
    'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
     <?php $model = new RegistrationForm; ?>

	<div class="row">
    
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->emailField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'repeat_password'); ?>
		<?php echo $form->passwordField($model,'repeat_password'); ?>
		<?php echo $form->error($model,'repeat_password'); ?>
	</div>
    
    	<?php if(CCaptcha::checkRequirements()): ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'captcha'); ?>
		<div>
		<?php $this->widget('CCaptcha', array('clickableImage'=>true, 'showRefreshButton'=>false)); ?>
        <br>
		<?php echo $form->textField($model,'captcha'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'captcha'); ?> 
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Registration'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->