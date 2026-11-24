<?php
$criteriaPurchaseCanceled = new CDbCriteria();
$criteriaPurchaseCanceled->select = "po_no, count(po_no) as totalQty, issue_date, expected_receive_date";
$criteriaPurchaseCanceled->condition = "order_status =" . PurchaseItems::CANCELED;
$criteriaPurchaseCanceled->group = "po_no";
$poDataCanceled = PurchaseItems::model()->findAll($criteriaPurchaseCanceled);
$countPoDataCanceled = count($poDataCanceled);
?>
<h2>Canceled Purchase Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Issued Date</th>
            <th>Expected Receive Date</th>
        </tr>
        <?php if ($countPoDataCanceled > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($poDataCanceled as $podc) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countPoDataCanceled; ?>"><?php echo number_format(floatval($countPoDataCanceled)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $poNoFormat . $podc->po_no; ?></td>
                    <td><?php echo $podc->issue_date; ?></td>
                    <td><?php echo $podc->expected_receive_date; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
                
    </table>   
</div>