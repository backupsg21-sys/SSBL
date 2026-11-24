<?php
$criteriaSold = new CDbCriteria();
$criteriaSold->select = "sum(delivery_qty) as totalDeliveryQty, sum(return_qty) as totalReturnQty, model_id";
$criteriaSold->group = "model_id";
$soldData = SellDelivery::model()->findAll($criteriaSold);
?>
<h2>Product Sold Via Sale Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th colspan="8">Item</th>
            <th rowspan="2">Total Sold</th>
            <th rowspan="2">Total Returned</th>
        </tr>
        <tr>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Item Name</th>
            <th>Code</th>
            <th>Model</th>
            <th>Brand</th>
            <th>Color</th>
            <th>Size</th>
        </tr>
        <?php if ($soldData) { ?>
            <?php foreach ($soldData as $sd) { ?>
                <?php
                $modelInfo = ProdModels::model()->infoOfThisProduct($sd->model_id);
                $modelName = $modelInfo->model_name;
                $code = $modelInfo->code;
                $brandName = ProdBrands::model()->brandName($modelInfo->brand_id);
                $itemName = ProdItems::model()->itemName($modelInfo->item_id);
                $pModel = PModel::model()->nameOfThis($modelInfo->pmodel);
                $pBrand = PBrand::model()->nameOfThis($modelInfo->pbrand);
                $color = CriteriaValues::model()->findNameOfThis($modelInfo->color_id);
                $size = CriteriaValues::model()->findNameOfThis($modelInfo->size_id);
                ?>
                <tr>
                    <td><?php echo $itemName; ?></td>
                    <td><?php echo $brandName; ?></td>
                    <td><?php echo $modelName; ?></td>
                    <td><?php echo $code; ?></td>
                    <td><?php echo $pModel; ?></td>
                    <td><?php echo $pBrand; ?></td>
                    <td><?php echo $color; ?></td>
                    <td><?php echo $size; ?></td>
                    <td><?php echo number_format(floatval($sd->totalDeliveryQty)); ?></td>
                    <td><?php echo number_format(floatval($sd->totalReturnQty)); ?></td>
                </tr>
            <?php } ?>
         <?php } else { ?>
                <tr>
                    <td colspan="10"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>