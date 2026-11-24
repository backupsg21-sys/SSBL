<?php
$criteriaSoldQ = new CDbCriteria();
$criteriaSoldQ->select = "sum(qty) as totalQty, model_id";
$criteriaSoldQ->group = "model_id";
$soldDataQ = QuickSell::model()->findAll($criteriaSoldQ);
?>
<h2>Product Sold Via POS</h2>
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
        <?php if ($soldDataQ) { ?>
            <?php foreach ($soldDataQ as $sdq) { ?>
                <?php
                $modelInfo = ProdModels::model()->infoOfThisProduct($sdq->model_id);
                $modelName = $modelInfo->model_name;
                $code = $modelInfo->code;
                $brandName = ProdBrands::model()->brandName($modelInfo->brand_id);
                $itemName = ProdItems::model()->itemName($modelInfo->item_id);
                $pModel = PModel::model()->nameOfThis($modelInfo->pmodel);
                $pBrand = PBrand::model()->nameOfThis($modelInfo->pbrand);
                $color = CriteriaValues::model()->findNameOfThis($modelInfo->color_id);
                $size = CriteriaValues::model()->findNameOfThis($modelInfo->size_id);

                $criteriaSoldQR = new CDbCriteria();
                $criteriaSoldQR->select = "sum(return_qty) as tReturnQty";
                $criteriaSoldQR->addColumnCondition(array("model_id" => $sdq->model_id), "AND", "AND");
                $soldDataQR = QuickSellReturn::model()->findAll($criteriaSoldQR);
                if ($soldDataQR) {
                    $totalReturnedQty = end($soldDataQR)->tReturnQty;
                } else {
                    $totalReturnedQty = 0;
                }
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
                    <td><?php echo number_format(floatval($sdq->totalQty)); ?></td>
                    <td><?php echo number_format(floatval($totalReturnedQty)); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
                <tr>
                    <td colspan="10"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>