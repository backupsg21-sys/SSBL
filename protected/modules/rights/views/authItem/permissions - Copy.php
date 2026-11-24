<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Permissions'),
); ?>

<div id="permissions">

	<h2><?php echo Rights::t('core', 'Permissions'); ?></h2>

	<p>
		<?php echo Rights::t('core', 'Here you can view and manage the permissions assigned to each role.'); ?><br />
		<?php echo Rights::t('core', 'Authorization items can be managed under {roleLink}, {taskLink} and {operationLink}.', array(
			'{roleLink}'=>CHtml::link(Rights::t('core', 'Roles'), array('authItem/roles')),
			'{taskLink}'=>CHtml::link(Rights::t('core', 'Tasks'), array('authItem/tasks')),
			'{operationLink}'=>CHtml::link(Rights::t('core', 'Operations'), array('authItem/operations')),
		)); ?>
	</p>

	<p><?php echo CHtml::link(Rights::t('core', 'Generate items for controller actions'), array('authItem/generate'), array(
	   	'class'=>'generator-link',
	)); ?></p>
    <style>
        thead th { position: sticky; top: 0; z-index: 2}
        tbody td { position: sticky; left: 0; z-index: 1}
    </style>
    <div>
        <input type='text' id='search' placeholder='Search Text' class="text-field" style="width: 30%;">
    </div>
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'template'=>'{items}{summary}',
		'emptyText'=>Rights::t('core', 'No authorization items found.'),
		'htmlOptions'=>array('class'=>'grid-view permission-table'),
		'columns'=>$columns,
	)); ?>

	<p class="info">*) <?php echo Rights::t('core', 'Hover to see from where the permission is inherited.'); ?></p>

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		jQuery('.inherited-item').rightsTooltip({
			title:'<?php echo Rights::t('core', 'Source'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#rights tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

        $(document).ready(function(){
            $('#search').keyup(function(){
                // Search text
                var text = $(this).val();

                text = text.replace(/\//g, '.');

                // Hide all content class element
                $('.items tbody tr').hide();

                // Search
                $('.items tbody tr:contains("'+text+'")').closest('tr').show();
            });
        });

        $.expr[":"].contains = $.expr.createPseudo(function(arg) {
            return function( elem ) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });

	</script>

</div>
