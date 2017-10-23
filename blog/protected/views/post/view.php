<?php
/* @var $this PostController */
/* @var $model Post */


$this->menu=array(
	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Create Post', 'url'=>array('create')),
	array('label'=>'Update Post', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Post', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);
?>

<link rel="stylesheet" href="/fancybox/jquery.fancybox.css" />
<script src="/fancybox/jquery.fancybox.js"></script>

<hr>
<h1><?php echo CHtml::encode($model->title . '  (' . $model->pub_date . ')'); ?></h1>
<hr>
<p><?php echo $model->content; ?></p>

<?php foreach($viewImages as $curImage): ?>
      <a class="grouped_elements" rel="group1" href="/upload/<?= $curImage->image ?>"><img width="250" height="130" src="/upload/<?= $curImage->image; ?>" alt=""/></a>
 <?php endforeach; ?>
 
<?php Yii::app()->clientScript->registerScript('fancybox',
   "$('a.grouped_elements').fancybox()", CClientScript::POS_END);
   ?>

<hr>
<label>Comments</label>

<?php if(!Yii::app()->user->isGuest): ?>

<div class="form">

<?php 
    $comment=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableClientValidation'=>true,
    'clientOptions' => array(
    'validateOnSubmit' => true,
     'afterValidate' => 'js:function(form, data, hasError){ 
      if(!hasError)
      {
      $.post(
          window.location,
          {
            "Comment[content]": $("#'.$contentId.'").val()
          },
          onAjaxSuccess
          );
          
          $("#'.$contentId.'").val("");
 
      function onAjaxSuccess()
      {
        $.fn.yiiListView.update("ajaxlist");
      }
      
      //alert(form);
      }
     }'
     )
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $comment->errorSummary($modelComment); ?>

	<div class="row">
		<?php echo $comment->labelEx($modelComment,'content'); ?>
		<?php echo $comment->textArea($modelComment,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $comment->error($modelComment,'content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($modelComment->isNewRecord ? 'Send' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php else: ?>
            <center><b>To comment, register...</b></center>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_viewcomments',
    'id'=>'ajaxlist'
)); ?>
