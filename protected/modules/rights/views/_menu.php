<style>

</style>

<?php $this->widget('zii.widgets.CMenu', array(
	'firstItemCssClass'=>'first',
	'lastItemCssClass'=>'last',
	'htmlOptions'=>array('class'=>'actions'),
	'items'=>array(
		array(
			'label'=>Rights::t('core', 'Users'),
			// 'url'=>array('assignment/view'),
			'url'=>array('/users/admin'),
			'itemOptions'=>array('class'=>'item-assignments'),
		),
		array(
			'label'=>Rights::t('core', 'Permissions'),
			'url'=>array('authItem/permissions'),
			'itemOptions'=>array('class'=>'item-permissions'),
		),
		array(
			'label'=>Rights::t('core', 'Roles'),
			'url'=>array('/authitem/adminrole'),
			'itemOptions'=>array('class'=>'item-roles'),
		),
		array(
			'label'=>Rights::t('core', 'Tasks'),
			'url'=>array('authItem/tasks'),
			'itemOptions'=>array('class'=>'item-tasks'),
		),
		array(
			'label'=>Rights::t('core', 'Operations'),
			'url'=>array('authItem/operations'),
			'itemOptions'=>array('class'=>'item-operations'),
		),
		array(
			'label'=>Rights::t('core', 'Dashboard'),
			'url'=>array('/site/dashBoard'),
			'itemOptions'=>array('class'=>'item-operations'),
		),
		array(
			'label'=>Rights::t('core', 'Logout'),
			'url'=>array('/user/logout'),
			'itemOptions'=>array('class'=>'item-operations'),
		),
	)
));	?>

</br>
</br>
