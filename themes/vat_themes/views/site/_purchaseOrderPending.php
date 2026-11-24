<?php
$criteriaPurchasePending = new CDbCriteria();
$criteriaPurchasePending->select = "po_no, count(po_no) as totalQty, issue_date, expected_receive_date";
$criteriaPurchasePending->condition = "is_all_received =" . PurchaseItems::ALLNOTRECEIVED . " AND order_status =" . PurchaseItems::ESTABLISHED;
$criteriaPurchasePending->group = "po_no";
$poDataPending = PurchaseItems::model()->findAll($criteriaPurchasePending);
$countPoDataPending = count($poDataPending);
?>
<h2>Pending Purchase Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Issued Date</th>
            <th>Expected Receive Date</th>
        </tr>
        <?php if ($countPoDataPending > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($poDataPending as $podp) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countPoDataPending; ?>"><?php echo number_format(floatval($countPoDataPending)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $poNoFormat . $podp->po_no; ?></td>
                    <td><?php echo $podp->issue_date; ?></td>
                    <td><?php echo $podp->expected_receive_date; ?></td>
                </tr>
            <?php } ?>
         <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>