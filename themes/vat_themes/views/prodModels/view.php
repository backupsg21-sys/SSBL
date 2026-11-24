<fieldset>
    <legend>View Product Info</legend>
    <div class="grid-view">
        <table class="items">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Sub-Category</th>
                    <th>Product</th>
                    <th>Code</th>             
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <td><?php echo ProdItems::model()->findByPk($model->item_id)->item_name; ?></td>
                    <td><?php echo ProdBrands::model()->findByPk($model->brand_id)->brand_name; ?></td>
                    <td><?php echo $model->model_name; ?></td>
                    <td><?php echo $model->code; ?></td> 
                    <td><?php //echo $model->warranty; ?></td>
                </tr>
            </tbody>
        </table>
        <table class="items" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Features</th>
                    <th></th>
                    <th>Brand</th>
                    <th>Country</th>
                    <th>Manufacturer</th>                    
                </tr>
            </thead>
            <tbody>
                <tr class="even">
                    <td><?php echo $model->features; ?></td>
                    <td><?php //echo PModel::model()->nameOfThis($model->pmodel); ?></td>
                    <td><?php echo PBrand::model()->nameOfThis($model->pbrand); ?></td>
                    <td><?php echo Countries::model()->nameOfThis($model->country_id); ?></td>
                    <td><?php echo Manufacturers::model()->nameOfThis($model->manufacturer_id); ?></td>                   
                </tr>
            </tbody>
        </table>
        <table class="items" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th rowspan="2">Vehicle Brand</th>
                    <th colspan="8">Supported Models & Years</th>
                    <th rowspan="2">CC</th>
                </tr>
                <tr>
                    <th>Model 1</th>
                    <th>Year 1</th>
                    <th>Model 2</th>
                    <th>Year 2</th>
                    <th>Model 3</th>
                    <th>Year 3</th>
                    <th>Model 4</th>
                    <th>Year 4</th>
                </tr>
            </thead>
            <tbody>
                <?php $customFieldsInfo = CustomProdFields::model()->findByAttributes(array('prod_id' => $model->id)); ?>
                <?php if ($customFieldsInfo) { ?>
                    <tr>
                        <td><?php echo CustomField1::model()->nameOfThis($customFieldsInfo->vehicle_brand); ?></td>
                        <td><?php echo CustomField2::model()->nameOfThis($customFieldsInfo->suported_model1); ?></td>
                        <td><?php echo YearModels::model()->nameOfThis($customFieldsInfo->year_model1); ?></td>
                        <td><?php echo CustomField2::model()->nameOfThis($customFieldsInfo->suported_model2); ?></td>
                        <td><?php echo YearModels::model()->nameOfThis($customFieldsInfo->year_model2); ?></td>
                        <td><?php echo CustomField2::model()->nameOfThis($customFieldsInfo->suported_model3); ?></td>
                        <td><?php echo YearModels::model()->nameOfThis($customFieldsInfo->year_model3); ?></td>
                        <td><?php echo CustomField2::model()->nameOfThis($customFieldsInfo->suported_model4); ?></td>
                        <td><?php echo YearModels::model()->nameOfThis($customFieldsInfo->year_model4); ?></td>
                        <td><?php echo $customFieldsInfo->cc; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</fieldset>