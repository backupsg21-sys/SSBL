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
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" style="color:#000 !important; font-size:12px;">
        <thead>
        <tr>
            <td colspan="9"
                style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/voucher-logo.png"
                     style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>"/><br/><br/>
                <?php echo $message; ?>
            </td>
        </tr>
        <tr>
            <th rowspan="2">SL</th>
            <th rowspan="2">Account Title</th>
            <th>Opening Balance</th>
            <th colspan="2">Total in Current Period</th>
            <th>Closing Balance</th>
        </tr>
        <tr class="titlesTr">
            <th>Dr / Cr</th>
            <th>Dr</th>
            <th>Cr</th>
            <th>Dr / Cr</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /*$caId;
        $noChildCAidsArr = array();
        $directExpIds = ChartOfAc::model()->noChild($caId, $noChildCAidsArr);
        if (!empty($directExpIds)) {
            for ($i = 0; $i < count($directExpIds); $i++) {
                echo ChartOfAc::model()->nameOfThis($directExpIds[$i]).'<br />';

            }
        }*/


        $criteria4 = new CDbCriteria();
        $criteria4->select = "id, title, code, opening_amount, transaction_type";
        $criteria4->addColumnCondition(array("root" => $caId), "AND", "AND");
        $criteria4->order = "id ASC";
        $data4 = ChartOfAc::model()->findAll($criteria4);
        $drAmountTotalTotal = 0;
        $crAmountTotalTotal = 0;
        $drGroundTotal = 0;
        $crGroundTotal = 0;
        $rowTotal = 0;
        if ($data4) {
            $serial = 1;
            ?>
            <?php
            foreach ($data4 as $d4) {
                $ca_id = $d4->id;
                $drOpeningAmount = 0;
                $crOpeningAmount = 0;
                $lineDrAmount = 0;
                $lineCrAmount = 0;
                if ($d4->transaction_type == ChartOfAc::DEBIT)
                    $drOpeningAmount = $d4->opening_amount;
                else
                    $crOpeningAmount = $d4->opening_amount;

                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                $criteria->addColumnCondition(array("ca_id" => $ca_id), "AND", "AND");
                $criteria->order = "entry_no ASC";
                $criteria->group = "entry_no";
                $data = JournalEntry::model()->findAll($criteria);
                ?>

                <!--                        this is where starts from ledger view-->
                <?php
                $criteria2 = new CDbCriteria();
                $criteria2->select = "sum(amount) as total_dr";
                $criteria2->condition = "entry_date<'" . $startDate . "'";
                $criteria2->addColumnCondition(array("ca_id" => $ca_id, 'transaction_type' => JournalEntry::DEBIT), 'AND', 'AND');
                $data2 = JournalEntry::model()->findAll($criteria2);
                $drAmountOP = end($data2)->total_dr + $drOpeningAmount;

                $criteria3 = new CDbCriteria();
                $criteria3->select = "sum(amount) as total_cr";
                $criteria3->condition = "entry_date<'" . $startDate . "'";
                $criteria3->addColumnCondition(array("ca_id" => $ca_id, 'transaction_type' => JournalEntry::CREDIT), 'AND', 'AND');
                $data3 = JournalEntry::model()->findAll($criteria3);
                $crAmountOP = end($data3)->total_cr + $crOpeningAmount;

                $drAmountTotal = 0;
                $crAmountTotal = 0;
                $balance = $drAmountOP - $crAmountOP;
                ?>
                <tr class="<?php if ($serial % 2 == 0) echo 'even'; else echo 'odd'; ?>">
                    <td><?php echo $serial++; ?></td>
                    <td class="textAlgnLeft"><?php echo $d4->title . " (" . $d4->code . ")"; ?></td>
                    <td class="textAlgnRight"><?php echo number_format(floatval($balance), 2); ?>
                    </td>
                    <?php
                    if ($data) {
                        $sl = 1;
                        foreach ($data as $d) {
                            $condition2 = "entry_no='" . $d->entry_no . "' AND ca_id!=" . $d->ca_id . " AND voucher_type=" . $d->voucher_type . " AND entry_date='" . $d->entry_date . "'";
                            $data2 = JournalEntry::model()->findAll(array('condition' => $condition2));
                            $data2Count = count($data2);
                            foreach ($data2 as $d2) {
                                $drAmount = 0;
                                $crAmount = 0;
                                if ($d->transaction_type == JournalEntry::DEBIT) {
                                    if ($data2Count <= 1) {
                                        $drAmount = $d->amount;
                                    } else {
                                        $drAmount = $d2->amount;
                                    }
                                } else {
                                    if ($data2Count <= 1) {
                                        $crAmount = $d->amount;
                                    } else {
                                        $crAmount = $d2->amount;
                                    }
                                }

                                $drAmountTotal = $drAmount + $drAmountTotal;
                                $crAmountTotal = $crAmount + $crAmountTotal;
                                $balance = $balance + ($drAmount - $crAmount);
                            }
                        }
                    }
                    $rowTotal += $balance;
                    ?>
                    <td class="textAlgnRight"><?php echo number_format(floatval($drAmountTotal), 2); ?></td>
                    <td class="textAlgnRight"><?php echo number_format(floatval($crAmountTotal), 2); ?></td>
                    <td class="textAlgnRight"><?php echo number_format(floatval($rowTotal), 2); ?></td>
                </tr>

                <!--                        this is where ends from ledger view-->

                <?php
                $drAmountTotalTotal = $drAmountTotal + $drAmountTotalTotal;
                $crAmountTotalTotal = $crAmountTotal + $crAmountTotalTotal;

                $drGroundTotal = $rowTotal;
            }
        }
        ?>
        <tr>
            <td colspan="3" style="font-weight: bold; padding-right: 6px;" class="textAlgnRight">Total</td>
            <td class="textAlgnRight"
                style="font-weight:bold;"><?php echo number_format(floatval($drAmountTotalTotal), 2); ?></td>
            <td class="textAlgnRight"
                style="font-weight:bold;"><?php echo number_format(floatval(abs($crAmountTotalTotal)), 2); ?></td>
            <td class="textAlgnRight"
                style="font-weight:bold;"><?php echo number_format(floatval($drGroundTotal), 2); ?></td>
        </tr>
        </tbody>
    </table>
    <table class="reportTab">
        <tr>
            <td colspan="5" style="padding-top: 60px;"></td>
        </tr>
        <tr>
            <th width="25%">A/C Manager</th>
            <th width="25%">Director</th>
            <th width="25%">Managing Director</th>
        </tr>
    </table>
</div>
