<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view">
	<?php echo CHtml::encode($data->users->username . ' (' . $data->date_created . ')'); ?>
	<br />
    
	<?php echo CHtml::encode($data->content); ?>
	<br />

</div>