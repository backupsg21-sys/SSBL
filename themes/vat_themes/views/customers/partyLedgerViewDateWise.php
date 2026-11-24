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

$customerName = $customerInfo->company_name;
$customerAddress = $customerInfo->company_address;
$customerContactNo = "Contact No: ".$customerInfo->company_contact_no;
$customerOpeningAmount=$customerInfo->opening_amount;
?>
<div class='printAllTableForThisReport'>
        <table class="reportTab">
            <thead>
                <tr class="noborderTr">
                    <td colspan="5"><?php echo $customerName; ?></td>
                </tr>
                <tr class="noborderTr">
                    <td colspan="5"><?php echo $customerAddress; ?></td>
                </tr>
                <tr class="noborderTr">
                    <td colspan="5"><?php echo $customerContactNo; ?></td>
                </tr>
                <tr>
                    <td colspan="5">Transaction: <?php echo $startDate . " - " . $endDate; ?></td>
                </tr>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>ACCOUNT HEAD</th>
                    <th>Dr</th>
                    <th>Cr</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $soNosOpening = SellDelivery::model()->delivInfoOfThisDateCustomerOpening($startDate, $customerId);
                $mrInfoOpening = MoneyReceipt::model()->moneyReceiptInfoOfThisDateCustomerOpening($startDate, $customerId);
                // $soNosOpening -> debit
                // $mrInfoOpening -> credit
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td class="textAlgnLeft">Opening</td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($soNosOpening)+$customerOpeningAmount));// ?></td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($mrInfoOpening))); ?></td>
                </tr>
                <?php
                $noFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
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
                    $soNos = SellDelivery::model()->delivInfoOfThisDateCustomer($date, $customerId);
                    $mrInfo = MoneyReceipt::model()->moneyReceiptInfoOfThisDateCustomer($date, $customerId);

                    $credit = 0;
                    $debit = 0;
                    ?>
                    <?php
                    if ($soNos) {
                        foreach ($soNos as $sns):
                            $soNo = $sns->so_no;
                            $sellAmount = SellDelivery::model()->totalDeliveredQtyOfThisDateSo($soNo, $date);
                            $debit = $sellAmount;
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
                                <td><?php echo $date; ?></td>
                                <td class="textAlgnLeft"><?php echo $noFormat . $soNo; ?></td>
                                <td class="textAlgnRight"><?php echo number_format(floatval(abs($debit))); ?></td>
                                <td class="textAlgnRight">0</td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                            <?php
                    if ($mrInfo) {
                        foreach ($mrInfo as $mri):
                            $credit = $mri->amount;
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
                                <td><?php echo $mri->date; ?></td>
                                <td class="textAlgnLeft"><?php echo $noFormat . $mri->order_no; ?></td>
                                <td class="textAlgnRight">0</td>
                                <td class="textAlgnRight"><?php echo number_format(floatval(abs($credit))); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                    <?php
                }
                ?>
                            <?php
                            $closingDebit=$totalDebit+$soNosOpening+$customerOpeningAmount;//
                            $closingCredit=$totalCredit+$mrInfoOpening;
                            $closingBalance=$closingDebit-$closingCredit;
                        ?>
                <tr>
                    <td colspan="2"></td>
                    <td class="textAlgnLeft">Closing</td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalDebit))); ?></td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalCredit))); ?></td>
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
</div>
