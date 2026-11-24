<?php
$criteriaSalePending = new CDbCriteria();
$criteriaSalePending->select = "so_no, count(so_no) as totalQty, date_of_sell, expected_delivery_date";
$criteriaSalePending->condition = "is_all_delivered =" . SellItems::ALL_NOT_DELIVERED . " AND order_status =" . SellItems::ESTABLISHED;
$criteriaSalePending->group = "so_no";
$soDataPending = SellItems::model()->findAll($criteriaSalePending);
$countSoDataPending = count($soDataPending);
?>
<h2>Pending Sale Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Ordered Date</th>
            <th>Expected Delivery Date</th>
        </tr>
        <?php if ($countSoDataPending > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($soDataPending as $sodp) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countSoDataPending; ?>"><?php echo number_format(floatval($countSoDataPending)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $soNoFormat . $sodp->po_no; ?></td>
                    <td><?php echo $sodp->date_of_sell; ?></td>
                    <td><?php echo $sodp->expected_delivery_date; ?></td>
                </tr>
            <?php } ?>
         <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>