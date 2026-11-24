<?php
$criteriaSaleDelayed = new CDbCriteria();
$criteriaSaleDelayed->select = "so_no, count(so_no) as totalQty, date_of_sell, expected_delivery_date";
$criteriaSaleDelayed->condition = "expected_delivery_date < '" . $today . "' AND order_status =" . SellItems::ESTABLISHED . " AND is_all_delivered =" . SellItems::ALL_NOT_DELIVERED;
$criteriaSaleDelayed->group = "so_no";
$soDataDelayed = SellItems::model()->findAll($criteriaSaleDelayed);
$countSoDataDelayed = count($soDataDelayed);
?>
<h2>Delayed Sale Order</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>Total</th>
            <th>Order Numbers</th>
            <th>Ordered Date</th>
            <th>Expected Delivery Date</th>
        </tr>
        <?php if ($countSoDataDelayed > 0) { ?>
            <?php $margeCount = 1; ?>
            <?php foreach ($soDataDelayed as $sodd) { ?>
                <tr>
                    <?php if ($margeCount == 1) { ?>
                        <td class="merge" rowspan="<?php echo $countSoDataDelayed; ?>"><?php echo number_format(floatval($countSoDataDelayed)); ?></td>
                    <?php } ?>
                    <?php $margeCount++; ?>
                    <td><?php echo $soNoFormat . $sodd->so_no; ?></td>
                    <td><?php echo $sodd->date_of_sell; ?></td>
                    <td><?php echo $sodd->expected_delivery_date; ?></td>
                </tr>
            <?php } ?>
         <?php } else { ?>
                <tr>
                    <td colspan="4"><div class='flash-error'>No result found!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>