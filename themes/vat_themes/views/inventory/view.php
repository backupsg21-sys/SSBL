<fieldset>
    <legend>View Inventory Info</legend>
    <table class="detail-view">
        <tbody>
            <tr class="even">
                <th>Category</th>
                <td>
                    <?php echo ProdItems::model()->itemName($model->item_id); ?>
                </td>
            </tr>
             <tr class="even">
                <th>Sub-Category</th>
                <td>
                    <?php echo ProdBrands::model()->brandName($model->brand_id); ?>
                </td>
            </tr>
             <tr class="even">
                <th>Product Name</th>
                <td>
                    <?php echo ProdModels::model()->modelName($model->model_id); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $model,
        'cssFile' => Yii::app()->theme->baseUrl . '/css/detailview/styles.css',
        'attributes' => array(
            'code',
            'ideal_qty',
            'warn_qty',
        ),
    ));
    ?> 
</fieldset>