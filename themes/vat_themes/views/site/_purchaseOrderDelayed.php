<?php
$criteriaPurchaseDelayed = new CDbCriteria();
$criteriaPurchaseDelayed->select = "po_no, count(po_no) as totalQty, issue_date, expected_receive_date";
$criteriaPurchaseDelayed->condition = "expected_receive_date < '" . $today . "' AND order_status =" . PurchaseItems::ESTABLISHED . " AND is_all_received =" . PurchaseItems::ALLNOTRECEIVED;
$criteriaPurchaseDelayed->group = "po_no";
$poDataDelayed = PurchaseItems::model()->findAll($criteriaPurchaseDelayed);
$countPoDataDelayed = count($poDataDelayed);
?>
<h2>Delayed Purchase Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Issued Date</th>
            <th>Expected Receive Date</th>
        </tr>
        <?php if ($countPoDataDelayed > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($poDataDelayed as $podd) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countPoDataDelayed; ?>"><?php echo number_format(floatval($countPoDataDelayed)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $poNoFormat . $podd->po_no; ?></td>
                    <td><?php echo $podd->issue_date; ?></td>
                    <td><?php echo $podd->expected_receive_date; ?></td>
                </tr>
            <?php } ?>
         <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>