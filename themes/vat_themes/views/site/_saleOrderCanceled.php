<?php
$criteriaSaleCanceled = new CDbCriteria();
$criteriaSaleCanceled->select = "so_no, count(so_no) as totalQty, date_of_sell, expected_delivery_date";
$criteriaSaleCanceled->condition = "order_status =" . SellItems::CANCELED;
$criteriaSaleCanceled->group = "so_no";
$soDataCanceled = SellItems::model()->findAll($criteriaSaleCanceled);
$countSoDataCanceled = count($soDataCanceled);
?>
<h2>Canceled Sale Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Ordered Date</th>
            <th>Expected Delivery Date</th>
        </tr>
        <?php if ($countSoDataCanceled > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($soDataCanceled as $sodc) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countSoDataCanceled; ?>"><?php echo number_format(floatval($countSoDataCanceled)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $soNoFormat . $sodc->po_no; ?></td>
                    <td><?php echo $sodc->date_of_sell; ?></td>
                    <td><?php echo $sodc->expected_delivery_date; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>