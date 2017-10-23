<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>
<script src="/ckeditor/ckeditor.js"></script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    
    <div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php echo $form->textArea($model,'content', array('id'=>'editor')); ?>
    <?php echo $form->error($model,'content'); ?>
     </div>
     
     <?php Yii::app()->clientScript->registerScript('CKEDITOR', 
              "ClassicEditor.create(document.querySelector('#editor')).catch(error => {
                console.error(error);
                } )"
            , CClientScript::POS_END); ?>        

	<div class="row">
    
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->hiddenField($model,'category_id', array('id'=>'category_id')); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>
    <span id="selectedCategory"></span>
    
        <span id="categoryList">
    <?php
    
$level=0;

foreach($categories as $n=>$category)
{
	if($category->level==$level)
		echo CHtml::closeTag('li')."\n";
	else if($category->level>$level)
		echo CHtml::openTag('ul')."\n";
	else
	{
		echo CHtml::closeTag('li')."\n";

		for($i=$level-$category->level;$i;$i--)
		{
			echo CHtml::closeTag('ul')."\n";
			echo CHtml::closeTag('li')."\n";
		}
	}

	echo CHtml::openTag('li');

    echo CHtml::link($category->title, null, array('id'=>$category->id, 'onclick'=>'setCategory(this.id)'));
	$level=$category->level;
}

for($i=$level;$i;$i--)
{
	echo CHtml::closeTag('li')."\n";
	echo CHtml::closeTag('ul')."\n";
}
      
    ?>
        
</span>

<?php Yii::app()->clientScript->registerScript('registrationFormInserted',
 "function setCategory(id)
   {
    $('#category_id').val(id);
    var html = $('#'+id).html();
    $('#categoryList').hide();
    $('#selectedCategory').html(html);
   }", CClientScript::POS_END);
   ?>

	<div class="row">
	<?php
    echo $form->labelEx($model,'pub_date');
      $this->widget('zii.widgets.jui.CJuiDatePicker',array(
     'model' => $model,
     'value' => $model->pub_date,
    'name'=>'Post[pub_date]',
    'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'yy-mm-dd'
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
     ));
    echo $form->error($model,'pub_date');
    ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
   <?php echo $form->error($image,'image'); ?>
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php if(isset($jsonFiles)): ?>
<div class="flash-success">
<b> Attached images: (<?= count($jsonFiles); ?>)</b>
<br>
            <?php foreach($jsonFiles as $currentFile): ?>
                <?php echo $currentFile->id . ' : ' . $currentFile->image; ?>
                <br>
            <?php endforeach; ?> 
</div>          
<?php endif; ?>




<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>
<?php echo CHtml::activeFileField($image, 'image'); ?>
<?php echo CHtml::submitButton('Attach image'); ?>
<?php echo CHtml::endForm(); ?>
