<?php
/* @var $this PostController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Create Post', 'url'=>array('create')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);

?>

<h1>Blog Posts <?php echo isset($model) ? '| ' . CHtml::encode($model->title) : null; ?> </h1>
<?php echo isset($subcategories) ? 'And subcategories: ' . CHtml::encode($subcategories) : null; ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
    //'updateSelector'=>'#updatelist',
	'itemView'=>'/post/_view'
)); ?>
