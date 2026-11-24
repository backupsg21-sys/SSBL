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
?>
<?php
//echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . "/images/pdf.png"), array('/journalEntry/balanceSheetViewPdf',
//    'startDate' => $startDate, 'endDate' => $endDate,), array('title' => 'Save as PDF'));
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab">
        <thead>
            <tr>
                <td style="font-size: 18px; font-weight: bold; padding: 10px 0px;">
                    <?php
                    $companyData = YourCompany::model()->findByAttributes(array("is_active" => YourCompany::ACTIVE));
                    if ($companyData) {
                        echo $companyData->company_name;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $message; ?></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="heightLight">ASSET</td>
            </tr>
            <tr>
                <td>
                    <span class='specialUlLeft' style="float: left; padding-left: 38px; font-weight: bold;">Account Title</span>
                    <span class='specialUlRight' style="float: right; padding-right: 38px; font-weight: bold;">Amount</span>
                </td>
            </tr>
            <?php
            $serialNo = 0;
            $assetData = ChartOfAc::model()->assets();
            if ($assetData) {
                foreach ($assetData as $assetd):
                    ?>
                    <tr class="<?php
            if ($serialNo % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                        <td class="textAlgnLeft">
                            <ul class="specialUl">
                                <li>
                                    <span class='specialUlLeft'><?php echo ChartOfAc::model()->nameOfThis($assetd->id); ?></span>
                                    <?php echo ChartOfAc::model()->subItemOfThis($assetd->id, $startDate, $endDate); ?>
                                </li>
                            </ul>
                        </td>
                    </tr> 
                    <?php
                    $serialNo++;
                endforeach;
            }
            ?>
            <?php
            $criteriaTotalAsset = new CDbCriteria();
            $criteriaTotalAsset->addColumnCondition(array('main_ca' => ChartOfAc::ASSET));
            $criteriaTotalAsset->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataTotalAsset = JournalEntry::model()->findAll($criteriaTotalAsset);
            $drAmountTotalAsset = 0;
            $crAmountTotalAsset = 0;
            if ($dataTotalAsset) {
                foreach ($dataTotalAsset as $dasset):
                    if ($dasset->transaction_type == JournalEntry::DEBIT) {
                        $drAmountTotalAsset = $drAmountTotalAsset + $dasset->amount;
                    } else {
                        $crAmountTotalAsset = $crAmountTotalAsset + $dasset->amount;
                    }
                endforeach;
            }
            $balanceTotalAsset = $drAmountTotalAsset - $crAmountTotalAsset;
            ?>
            <tr>
                <td class="heightlightedText">
                    <ul class="specialUl">
                        <li>
                            <span class='specialUlLeft'>Total Asset</span>
                            <span class='specialUlRight'>
                                <?php
                                echo " " . number_format(abs($balanceTotalAsset),2);
                                ?>
                            </span>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="heightLight">LIABILITY & OWNERS EQUITY</td>
            </tr>
            <tr>
                <td>
                    <span class='specialUlLeft' style="float: left; padding-left: 38px; font-weight: bold;">Account Title</span>
                    <span class='specialUlRight' style="float: right; padding-right: 38px; font-weight: bold;">Amount</span>
                </td>
            </tr>
            <?php
            $libilityData = ChartOfAc::model()->liabilities();
            if ($libilityData) {
                foreach ($libilityData as $liabd):
                    ?>
                    <tr class="<?php
            if ($serialNo % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                        <td class="textAlgnLeft">
                            <ul class="specialUl">
                                <li>
                                    <span class='specialUlLeft'><?php echo ChartOfAc::model()->nameOfThis($liabd->id); ?></span>
                                    <?php echo ChartOfAc::model()->subItemOfThis($liabd->id, $startDate, $endDate); ?>
                                </li>
                            </ul>
                        </td>
                    </tr> 
                    <?php
                    $serialNo++;
                endforeach;
            }
            ?>
            <?php
            $criteriaTotalLiability = new CDbCriteria();
            $criteriaTotalLiability->addColumnCondition(array('main_ca' => ChartOfAc::LIABILITY));
            $criteriaTotalLiability->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataTotalLiability = JournalEntry::model()->findAll($criteriaTotalLiability);
            $drAmountTotalLiability = 0;
            $crAmountTotalLiability = 0;
            if ($dataTotalLiability) {
                foreach ($dataTotalLiability as $dliablity):
                    if ($dliablity->transaction_type == JournalEntry::DEBIT) {
                        $drAmountTotalLiability = $drAmountTotalLiability + $dliablity->amount;
                    } else {
                        $crAmountTotalLiability = $crAmountTotalLiability + $dliablity->amount;
                    }
                endforeach;
            }
            $balanceTotalLiability = $drAmountTotalLiability - $crAmountTotalLiability;
            ?>

            <?php
            $criteriaTotalIncome = new CDbCriteria();
            $criteriaTotalIncome->addColumnCondition(array('main_ca' => ChartOfAc::INCOME));
            $criteriaTotalIncome->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataTotalIncome = JournalEntry::model()->findAll($criteriaTotalIncome);
            $drAmountTotalIncome = 0;
            $crAmountTotalIncome = 0;
            if ($dataTotalIncome) {
                foreach ($dataTotalIncome as $dti):
                    if ($dti->transaction_type == JournalEntry::DEBIT) {
                        $drAmountTotalIncome = $drAmountTotalIncome + $dti->amount;
                    } else {
                        $crAmountTotalIncome = $crAmountTotalIncome + $dti->amount;
                    }
                endforeach;
            }
            $balanceTotalIncome = $drAmountTotalIncome - $crAmountTotalIncome;

            $criteriaTotalExpense = new CDbCriteria();
            $criteriaTotalExpense->addColumnCondition(array('main_ca' => ChartOfAc::EXPENSE));
            $criteriaTotalExpense->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataTotalExpense = JournalEntry::model()->findAll($criteriaTotalExpense);
            $drAmountTotalExpense = 0;
            $crAmountTotalExpense = 0;
            if ($dataTotalExpense) {
                foreach ($dataTotalExpense as $dte):
                    if ($dte->transaction_type == JournalEntry::DEBIT) {
                        $drAmountTotalExpense = $drAmountTotalExpense + $dte->amount;
                    } else {
                        $crAmountTotalExpense = $crAmountTotalExpense + $dte->amount;
                    }
                endforeach;
            }
            $balanceTotalExpense = $drAmountTotalExpense - $crAmountTotalExpense;

            $profitLoss = abs($balanceTotalIncome) - abs($balanceTotalExpense);

            $criteriaTotalOwe = new CDbCriteria();
            $criteriaTotalOwe->addColumnCondition(array('main_ca' => ChartOfAc::EQUITY));
            $criteriaTotalOwe->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataTotalOwe = JournalEntry::model()->findAll($criteriaTotalOwe);
            $drAmountTotalOwe = 0;
            $crAmountTotalOwe = 0;
            if ($dataTotalOwe) {
                foreach ($dataTotalOwe as $dowe):
                    if ($dowe->transaction_type == JournalEntry::DEBIT) {
                        $drAmountTotalOwe = $drAmountTotalOwe + $dowe->amount;
                    } else {
                        $crAmountTotalOwe = $crAmountTotalOwe + $dowe->amount;
                    }
                endforeach;
            }
            $balanceTotalOwe = $drAmountTotalOwe - $crAmountTotalOwe;
            $totalOwnersEquity = abs($balanceTotalOwe) + abs($profitLoss);
            ?>
            <tr>
                <td>
                    <ul class="specialUl">
                        <li>
                            <span class='specialUlLeft'>Owners Equity</span>
                            <span class='specialUlRight'>
                                <?php
                                $totalOwnersEquity = abs($balanceTotalOwe) + abs($profitLoss);
                                echo " " . number_format($totalOwnersEquity,2);
                                ?>
                            </span>
                        </li>
                    </ul>
                </td>
            </tr>
            <?php
            $totalLiabilityPlusOwnerEquity = abs($balanceTotalLiability) + abs($totalOwnersEquity);
            ?>
            <tr>
                <td class="heightlightedText">
                    <ul class="specialUl">
                        <li>
                            <span class='specialUlLeft'>Liability & Owners Equity Total</span>
                            <span class='specialUlRight'>
                                <?php
                                echo " " . number_format($totalLiabilityPlusOwnerEquity,2);
                                ?>
                            </span>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>
