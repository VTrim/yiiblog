<?php
/* @var $this PostController */
/* @var $data Post */
?>

<div class="view">
	<?php echo CHtml::link(CHtml::encode($data->title . ' (' . $data->pub_date . ')'), array('post/view', 'id'=>$data->id)); ?>
	<br />
    <label>Category:</label>
    <?php echo CHtml::encode($data->categories->title); ?>
	<br />


</div>