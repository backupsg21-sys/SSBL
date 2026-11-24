<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('material-recipe-grid', {
                data: $(this).serialize()
    });
    return false;
});
");
?>
<div style="float: left; width: 100%;">
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <?php echo "<div class='flash-error'>" . Yii::app()->user->getFlash('error') . '</div>'; ?>
    <?php endif; ?>
</div>
<?php echo CHtml::button('Create', array('submit' => array('materialRecipe/create'), 'style' => 'margin-left: 13px;padding: 3px 25px !important;')); ?>

<fieldset style="float: left; width: 98%;">
    <legend>Manage Product Config</legend>
    <?php
    $this->widget('ext.groupgridview.GroupGridView', array(
        'id' => 'material-recipe-grid',
        'dataProvider' => $model->search(),
        'mergeColumns' => array('sl_no', 'model_qty', 'model_id', 'config_date', 'cost_type'),
        'mergeType' => 'nested',
        'extraRowColumns' => array('sl_no'),
        /*'extraRowTotals' => function ($data, $row, &$totals) {
            if (!isset($totals['count'])) $totals['count'] = 0;
            $totals['count']++;

            if (!isset($totals['sum_age'])) $totals['sum_age'] = 0;
            $totals['sum_age'] += $data['total_price'];
        },*/
        'extraRowExpression' => '"<div class=\"subtotal\" style=\"text-align:right; padding-right: 415px;color:#B842E2;\"><b>
            TOTAL PRICE = ".CHtml::decode(number_format(MaterialRecipe::model()->getRecipeTotalCost($data->sl_no),4))."</b></div>"',
        'extraRowPos' => 'below',
        'filter' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview/styles.css',
        'template' => "<span class='caption'>Click on titles to re-arrange order (Ascending/Descending)</span>{pager}{summary}{items}{pager}",
        'columns' => array(
            array(
                'name' => 'model_id',
                'value' => 'ProdModels::model()->modelName($data->model_id)',
            ),
            array(
                'name' => 'model_qty',
                'filter' => false,
            ),
            'config_date',
            array(
                'name' => 'cost_type',
                'filter' => MaterialRecipe::model()->getMaterialType(),
                'value' => 'MaterialRecipe::model()->costType($data->cost_type)',
                'type' => 'raw'
            ),
            array(
                'name' => 'rm_model_id',
                'value' => 'MaterialRecipe::model()->getMaterialName($data->rm_model_id, $data->cost_type)',
            ),
            array(
                'name' => 'rm_qty',
                'value' => '$data->rm_qty == 0 ? "" : $data->rm_qty',
                'filter' => false,
            ),
            array(
                'name' => 'rm_qty_unit_id',
                'value' => 'Units::model()->unitLabel($data->rm_qty_unit_id)',
                'filter' => CHtml::listData(Units::model()->findAll(['order' => 'label']), 'id', 'label'),
            ),
            array(
                'name' => 'waste_qty',
                'value' => '$data->waste_qty == 0 ? "" : $data->waste_qty',
                'filter' => false,
            ),
            array(
                'name' => 'waste_qty_unit_id',
                'value' => 'Units::model()->unitLabel($data->waste_qty_unit_id)',
                'filter' => CHtml::listData(Units::model()->findAll(['order' => 'label']), 'id', 'label'),
            ),
            array(
                'name' => 'total_price',
                'filter' => false,
            ),
            // array(
            //     'header' => 'Price',
            //     //'value' => '($data->rm_qty+$data->waste_qty)>0?($data->rm_qty+$data->waste_qty)*$data->total_price:$data->total_price',
            //     'value' => 'MaterialRecipe::model()->getMaterialLineTotal($data->rm_qty+$data->waste_qty, $data->total_price)',
            // ),
            array(
                'name' => 'created_by',
                'value' => 'Users::model()->userNameOfThis($data->created_by)',
                'filter' => CHtml::listData(Users::model()->findAll(['order' => 'username']), "id", "username"),
            ),
            array(
                'name' => 'approve_count',
                'header' => 'Status',
                'value' => 'MaterialRecipe::model()->statusFlag($data->approve_count)',
                'filter' => '',
            ),
            array(
                'header' => 'Edit',
                'template' => '{update}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'update' => array(
                        'visible' => 'MaterialRecipe::model()->checkDeletable($data->sl_no, $data->config_date)',
                        'value' => 'MaterialRecipe::model()->updateRecipe($data->id)',
                    )
                )
            ),
            array(
                'header' => 'Delete',
                'template' => '{delete}',
                'class' => 'CButtonColumn',
                'buttons' => array(
                    'delete' => array(
                        'visible' => 'MaterialRecipe::model()->checkDeletable($data->sl_no, $data->config_date)'
                    )
                )
            ),
        )
    ));
    ?>
</fieldset>
