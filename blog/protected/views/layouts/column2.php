<?php
$currentPage = in_array(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id, array(
	'site/index',
	'post/view',
	'category/view'
));
$cportletTitle = $currentPage ? 'Categories' : 'Operations';
?>
<?php
$this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php
echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
$this->beginWidget('zii.widgets.CPortlet', array(
	'title' => $cportletTitle,
));
$tree_data = array(
	array(
		'text' => CHtml::link('First Root Category', array(
			'category/view',
			'id' => 1
		)) ,
		'children' => array(
			array(
				'text' => CHtml::link('First (child) category', array(
					'category/view',
					'id' => 2
				)) ,
				'children' => array(
					array(
						'text' => CHtml::link('First (child)(child) category', array(
							'category/view',
							'id' => 6
						)) ,
					) ,
				) ,
			) ,
		)
	) ,
	array(
		'text' => CHtml::link('Second Root Category', array(
			'category/view',
			'id' => 3
		)) ,
		'children' => array(
			array(
				'text' => CHtml::link('Second (child) category', array(
					'category/view',
					'id' => 4
				)) ,
			) ,
		)
	) ,
	array(
		'text' => CHtml::link('Third Root Category', array(
			'category/view',
			'id' => 5
		)) ,
	)
);

if ($currentPage) $this->widget('CTreeView', array(
	'data' => $tree_data
));
else $this->widget('zii.widgets.CMenu', array(
	'items' => $this->menu,
	'htmlOptions' => array(
		'class' => 'operations'
	) ,
));
$this->endWidget();
?>
	</div><!-- sidebar -->
</div>
<?php
$this->endContent(); ?>