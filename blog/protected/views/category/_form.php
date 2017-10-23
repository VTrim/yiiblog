<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'category-form',

	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.

	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	)
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php
echo $form->errorSummary($model); ?>

	<div class="row">
		<?php
echo $form->labelEx($model, 'title'); ?>
		<?php
echo $form->textField($model, 'title', array(
	'size' => 60,
	'maxlength' => 255
)); ?>
		<?php
echo $form->error($model, 'title'); ?>
	</div>
    
    	<div class="row">
		<?php
echo $form->labelEx($model, 'root'); ?>
		<?php
echo $form->hiddenField($model, 'root', array(
	'value' => $model->root
)); ?>
		<?php
echo $form->error($model, 'root'); ?>
	</div>
     <?php

if (isset($upd)): ?>
    Current: <span id="selectedCategory"><?php
	echo $model->isRoot() ? 'Root' : $model->parent()->find()->title; ?></span>
    <hr>
    <?php
else: ?>
    <span id="selectedCategory"></span>
    <?php
endif; ?>

    <span id="categoryList">
    <?php
$level = 0;

foreach($categories as $n => $category)
{
	if ($category->level == $level) echo CHtml::closeTag('li') . "\n";
	else
	if ($category->level > $level) echo CHtml::openTag('ul') . "\n";
	else
	{
		echo CHtml::closeTag('li') . "\n";
		for ($i = $level - $category->level; $i; $i--)
		{
			echo CHtml::closeTag('ul') . "\n";
			echo CHtml::closeTag('li') . "\n";
		}
	}

	echo CHtml::openTag('li');
	echo CHtml::link($category->title, '#', array(
		'id' => $category->id,
		'onclick' => 'setCategory(this.id)'
	));
	$level = $category->level;
}

for ($i = $level; $i; $i--)
{
	echo CHtml::closeTag('li') . "\n";
	echo CHtml::closeTag('ul') . "\n";
}

?>
        
</span>

<?php
Yii::app()->clientScript->registerScript('setCategory', "function setCategory(id)
   {
    $('#Category_root').val(id);
    var html = $('#'+id).html();
    $('#categoryList').hide();
    $('#selectedCategory').html(html);
   }", CClientScript::POS_END);
?>


	<div class="row buttons">
		<?php
echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php
$this->endWidget(); ?>

</div><!-- form -->