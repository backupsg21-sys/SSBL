<?php
echo "<div class='printBtn'>";
$this->widget('ext.mPrint.mPrint', array(
    'title' => ' ', //the title of the document. Defaults to the HTML title
    'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
    'text' => '', //text which will appear beside the print icon. Defaults to NULL
    'element' => '.printAllTableForThisReport', //the element to be printed.
    'exceptions' => array(//the element/s which will be ignored
        '.summary',
        '.search-form',
    ),
    'publishCss' => FALSE, //publish the CSS for the whole page?
    'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
    'alt' => 'print', //text which will appear if image can't be loaded
    'debug' => FALSE, //enable the debugger to see what you will get
    'id' => 'print-div'         //id of the print link
));
echo "</div>";

$supplierName = $supplierInfo->company_name;
$supplierAddress = $supplierInfo->company_address;
$supplierContactNo = "Contact No: " . $supplierInfo->company_contact_no;
$supplierOpeningAmount = $supplierInfo->opening_amount;
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr class="noborderTr">
                <td colspan="5"><?php echo $supplierName; ?></td>
            </tr>
            <tr class="noborderTr">
                <td colspan="5"><?php echo $supplierAddress; ?></td>
            </tr>
            <tr class="noborderTr">
                <td colspan="5"><?php echo $supplierContactNo; ?></td>
            </tr>
            <tr>
                <td colspan="5">Transaction: <?php echo $startDate . " - " . $endDate; ?></td>
            </tr>
            <tr style="width:98%" class="titlesTr">
                <th width="5%">SL</th>
                <th width="10%">Date</th>
                <th>ACCOUNT HEAD</th>
                <th width="15%">Dr</th>
                <th width="15%">Cr</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $poNopopening = ReceivedPurchase::model()->recvdInfoOfThisDateSupplierOpening($startDate, $supplierId);
            $mrInfoOpening = PaymentReceipt::model()->paymentReceiptInfoOfThisDateSupplierOpening($startDate, $supplierId);

            // $poNopopening -> credit // liability increase
            // $mrInfoOpening -> debit // liability decrease
            ?>
            <tr>
                <td colspan="2"></td>
                <td class="textAlgnLeft">Opening</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($mrInfoOpening))); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($poNopopening))); ?></td> 
            </tr>
            <?php
            $noFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
            $date1 = date_create($startDate);
            $date2 = date_create($endDate);
            $diff = date_diff($date1, $date2);
            $timeInverval = $diff->format("%a") + 1;

            $customerOpeningAmount;
            $sl = 1;
            $totalDebit=0;
            $totalCredit=0;
            for ($i = 0; $i < $timeInverval; $i++) {
                $date = date('Y-m-d', strtotime($startDate) + strtotime("+" . $i . " day", 0));
                $poNos = ReceivedPurchase::model()->recvdInfoOfThisDateSupplier($date, $supplierId);
                $mrInfo = PaymentReceipt::model()->paymentReceiptInfoOfThisDateSupplier($date, $supplierId);

                $credit = 0;
                $debit = 0;
                ?>
                <?php
                if ($poNos) {
                    foreach ($poNos as $sns):
                        $poNo = $sns->po_no;
                        $sellAmount = ReceivedPurchase::model()->totalRcvdQtyOfThisDatePo($poNo, $date);
                        $credit = $sellAmount;
                        $totalCredit=$totalCredit+$credit;
                        ?>
                        <tr class="
                        <?php
                        if ($sl % 2 == 0) {
                            echo 'even';
                        } else {
                            echo 'odd';
                        }
                        ?>
                            ">
                            <td><?php echo $sl++; ?></td>
                            <td><?php echo $date; ?></td>
                            <td class="textAlgnLeft"><?php echo $noFormat . ' - ' .$poNo; ?></td>
                            <td class="textAlgnRight"></td>
                            <td class="textAlgnRight"><?php echo number_format(floatval(abs($credit))); ?></td>
                        </tr>
                        <?php
                    endforeach;
                }
                ?>
                <?php
                if ($mrInfo) {
                    foreach ($mrInfo as $mri):
                        $debit = $mri->amount;
                        $totalDebit=$totalDebit+$debit;
                        ?>
                        <tr class="
                        <?php
                        if ($sl % 2 == 0) {
                            echo 'even';
                        } else {
                            echo 'odd';
                        }
                        ?>
                            ">
                            <td><?php echo $sl++; ?></td>
                            <td><?php echo $mri->date; ?></td>
                            <td class="textAlgnLeft"><?php echo $noFormat . $mri->order_no; ?></td>
                            <td class="textAlgnRight"><?php echo number_format(floatval(abs($debit))); ?></td>
                            <td class="textAlgnRight"></td>
                        </tr>
                        <?php
                    endforeach;
                }
                ?>
    <?php
}
?>
                        <?php
                            $closingDebit=$totalDebit+$mrInfoOpening;
                            $closingCredit=$totalCredit+$poNopopening;
                            $closingBalance=$closingDebit-$closingCredit;
                        ?>
            <tr>
                <td colspan="2"></td>
                <td class="textAlgnLeft">Closing</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($closingDebit))); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($closingCredit))); ?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="textAlgnLeft">Balance</td>
                <td class="textAlgnRight heightLight">
                <?php
                    if($closingBalance>0 || $closingBalance==0)
                        echo number_format(floatval(abs($closingBalance)));
                ?>
                </td>
                <td class="textAlgnRight heightLight">
                <?php
                    if($closingBalance<0 || $closingBalance==0)
                        echo number_format(floatval(abs($closingBalance)));
                ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table id="header-fixed"></table>
</div>
<script>
    var tableOffset = $("#table-1 thead tr.titlesTr").offset().top;
    var $header = $("#table-1 > thead tr.titlesTr").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function() {
        var offset = $(this).scrollTop();
    
        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        }
        else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });

</script>

