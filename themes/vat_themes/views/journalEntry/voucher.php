<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen"/>
<?php if ($data) { ?>
    <?php
    echo "<div class='printBtn' style='width: unset;'>";
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
        'visible' => Yii::app()->user->checkAccess('print'), //should this be visible to the current user?
        'alt' => 'print', //text which will appear if image can't be loaded
        'debug' => FALSE, //enable the debugger to see what you will get
        'id' => 'print-div'         //id of the print link
    ));
    echo "</div>";

    $companyName = YourCompany::activeCompanyName();
}
?>
<div class='printAllTableForThisReport'>
    <style>
        table.reportTab, table.reportTab tr, table.reportTab tr td, table.reportTab tr th {
            border: none;
            font-size: 12px;
        }
    </style>
    <table class="reportTab" style="color:#000 !important;">
        <thead>
        <tr>
            <td colspan="4" style="font-weight: bold;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/voucher-logo.png"
                     style="height: 60px; width: 465px;" title="<?= $companyName ?>"
                     alt="<?= $companyName ?>"/>
                <br/>
                <div style="font-size: 14px; color:#000; margin-top: 10px;"><?php echo JournalEntry::model()->voucherTypeName(end($data)->voucher_type); ?>
                    Voucher</div>
                <hr/>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="textAlgnLeft" style="padding: 0px 6px; font-size:12px;"><b>Voucher
                    No:</b> <?php echo JournalEntry::model()->voucherNoOfThis(end($data)->entry_no, end($data)->voucher_type); ?>
            </td>
            <td colspan="2" class="textAlgnRight" style="padding: 0px 6px; font-size:12px;">
                <b>Date:</b> <?php echo end($data)->entry_date; ?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr class="titlesTr" style="border: 1px solid;">
            <th style="width: 50px; margin-top:10px; font-size: 12px;">SL</th>
            <th style="text-align: left; font-size: 12px;">Account Title</th>
            <th style="text-align: right; font-size: 12px;">Dr (Amount)</th>
            <th style="text-align: right; font-size: 12px;">Cr (Amount)</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($data) { ?>
            <?php
            $sl = 1;
            $totalDrAmount = 0;
            $totalCrAmount = 0;
            ?>
            <?php foreach ($data as $d) { ?>
                <tr class="<?php
                if ($sl % 2 == 0)
                    echo "even"; else
                    echo "odd";
                ?>">
                    <td style="font-size: 12px;"><?php echo $sl++; ?></td>
                    <td class="textAlgnLeft"
                        style="font-size: 12px;"><?php echo ChartOfAc::model()->nameOfThis($d->ca_id); ?></td>
                    <td class="textAlgnRight" style="font-size: 12px;">
                        <?php
                        if ($d->transaction_type == JournalEntry::DEBIT) {
                            echo number_format(floatval($d->amount), 2);
                            $totalDrAmount = $totalDrAmount + $d->amount;
                        }
                        ?>
                    </td>
                    <td class="textAlgnRight" style="font-size: 12px;">
                        <?php
                        if ($d->transaction_type == JournalEntry::CREDIT) {
                            echo number_format(floatval($d->amount), 2);
                            $totalCrAmount = $totalCrAmount + $d->amount;
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="4">
                    <hr/>
                </td>
            </tr>
            <tr>
                <td class="textAlgnLeft" colspan="2" style="font-size: 12px;"><b>Total:</b></td>
                <td class="textAlgnRight" style="font-size: 12px;">
                    <b><?php echo number_format(floatval($totalDrAmount), 2); ?></b></td>
                <td class="textAlgnRight" style="font-size: 12px;">
                    <b><?php echo number_format(floatval($totalCrAmount), 2); ?></b></td>
            </tr>
            <tr>
                <td class="textAlgnLeft" colspan="4" style="font-size: 12px;"><b>In Words:</b>
                    <?php
                    $amountInWord = new AmountInWord();
                    echo $amountInWord->convert(intval($totalDrAmount)) . " Only.";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="textAlgnLeft" colspan="4" style="font-size: 12px;"><b>Narration: </b>Being The Amount
                    <?php
                    echo end($data)->narration;
                    ?>
                </td>
            </tr>
        <?php } else { ?>
            <tr>
                <td colspan="4">
                    <div class="flash-error">No result found!</div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <table class="reportTab">
        <tr>
            <td colspan="5" style="padding-top: 60px;"></td>
        </tr>
        <tr>
            <th>Received By</th>
            <th>Prepared By</th>
            <th>Accounts</th>
            <th>Approved By</th>
            <th>Audited By</th>
        </tr>
    </table>
</div>