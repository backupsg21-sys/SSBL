<fieldset>
    <legend>View Payment Receipt History Of Supplier: <?php echo Suppliers::model()->supplierName($supplier_id); ?></legend>

    <div class="grid-view">
        <table class="items">
            <thead>
                <tr class="odd" style="text-align: center;">
                    <th>SL</th>
                    <th>Date</th>
                    <th>Account Head</th>
                    <th>Paid Amount(Dr)</th>
                </tr>
            </thead>
            <tbody>
                <?php $noFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER); $sl=1; ?>
                <?php if (!empty($data)) { ?>
                    <?php $totalAmount = 0; ?>
                    <?php foreach ($data as $d): ?>
                        <tr class="
                            <?php
                            if ($sl % 2 == 0) {
                                echo 'even';
                            } else {
                                echo 'odd';
                            }
                            ?>
                                "> 
                            <td>
                                <?php echo $sl++; ?>
                            </td>
                            <td>
                                <?php echo $d->date; ?>
                            </td> 
                            <td>
                                <?php echo $noFormat.$d->order_no; ?>
                            </td>
                            <td>
                                <?php echo number_format(floatval($d->amount)); ?>
                            </td>      
                        </tr>
                        <?php $totalAmount = $d->amount + $totalAmount; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="font-weight: bold; color: red;">Total:</td>
                        <td><?php echo number_format(floatval($totalAmount)); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr style="text-align: center;">
                        <td colspan="4"><div class="flash-error">No result found!</div></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</fieldset>
