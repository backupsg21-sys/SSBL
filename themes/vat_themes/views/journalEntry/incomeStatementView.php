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
                <td colspan="4" style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/vocher-logo.png" style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br /><br />
                    <?php echo $message; ?>
				</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; font-weight: bold; border-bottom: 1px solid #000000;">Account Head</td>
                <td colspan="2" style="font-weight: bold; border-bottom: 1px solid #000000;">Amount</td>
                <td style="font-weight: bold; border-bottom: 1px solid #000000; width: 300px;">Note</td>
            </tr>
            <?php
            $salesArr = array();
            $salesIds = ChartOfAc::model()->noChild(ChartOfAc::SALE, $salesArr);
            $totalSalesTotal = 0;
            if (!empty($salesIds)) {
                for ($i = 0; $i < count($salesIds); $i++) {
                    $con1 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $salesIds[$i];
                    $data1 = JournalEntry::model()->findAll(array("condition" => $con1));
                    $drAmountSales = 0;
                    $crAmountSales = 0;
                    if ($data1) {
                        foreach ($data1 as $d1):
                            if ($d1->transaction_type == JournalEntry::DEBIT) {
                                $drAmountSales = $drAmountSales + $d1->amount;
                            } else {
                                $crAmountSales = $crAmountSales + $d1->amount;
                            }
                        endforeach;
                    }
                    $totalSales = $drAmountSales - $crAmountSales;
                    $totalSalesTotal = $totalSales + $totalSalesTotal;
                    if ($totalSales != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($salesIds[$i]); ?></td>
                            <td><?php echo number_format(floatval(abs($totalSales)), 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Gross Revenue</td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;"><?php echo number_format(floatval(abs($totalSalesTotal)), 2); ?></td>
                <td></td>
            </tr>
            <?php
            $directExpArr = array();
            $directExpIds = ChartOfAc::model()->noChild2(ChartOfAc::DIRECT_EXPENSE, $directExpArr, ChartOfAc::DEPRICIATION_CHARGE_OE, ChartOfAc::DEPRICIATION_CHARGE_OE);
            $totalDirectExpTotal = 0;
            if (!empty($directExpIds)) {
                for ($i = 0; $i < count($directExpIds); $i++) {
                    $con2 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $directExpIds[$i];
                    $data2 = JournalEntry::model()->findAll(array("condition" => $con2));
                    $drAmountDirectExp = 0;
                    $crAmountDirectExp = 0;
                    if ($data2) {
                        foreach ($data2 as $d2):
                            if ($d2->transaction_type == JournalEntry::DEBIT) {
                                $drAmountDirectExp = $drAmountDirectExp + $d2->amount;
                            } else {
                                $crAmountDirectExp = $crAmountDirectExp + $d2->amount;
                            }
                        endforeach;
                    }
                    $totalDirectExp = $drAmountDirectExp - $crAmountDirectExp;
                    $totalDirectExpTotal = $totalDirectExp + $totalDirectExpTotal;
                    ?>
                    <?php
                }
            }
            ?>
            <?php
            $deprChargesOE = 0;
            $criteriaOE = new CDbCriteria();
            $criteriaOE->addColumnCondition(array("start_date" => $startDate, "end_date" => $endDate, "depr_type" => DepreciationCharges::OE), "AND", "AND");
            $deprChargesOEInfo = DepreciationCharges::model()->findAll($criteriaOE);
            if ($deprChargesOEInfo) {
                $deprChargesOE = end($deprChargesOEInfo)->amount;
            }
            $totalDirectExpTotal = $totalDirectExpTotal + $deprChargesOE;
            ?>
            <tr>
                <td style="text-align: left;">Operating Expenses</td>
                <td>(<?php echo number_format(floatval(abs($totalDirectExpTotal)), 2); ?>)</td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $grossOperatingProfitLoss = (abs($totalSalesTotal) - abs($totalDirectExpTotal));
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Gross Operating <?php
            if ($grossOperatingProfitLoss >= 0)
                echo 'Profit'; else
                echo 'Loss';
            ?></td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;">
                    <?php
                    if ($grossOperatingProfitLoss >= 0)
                        echo number_format(floatval(abs($grossOperatingProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($grossOperatingProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <?php
            $indirectExpArr = array();
            $indirectExpIds = ChartOfAc::model()->noChild2(ChartOfAc::INDIRECT_EXPENSE, $indirectExpArr, ChartOfAc::DEPRICIATION_CHARGE_AO, ChartOfAc::DEPRICIATION_CHARGE_AO);
            $totalIndirectExpTotal = 0;
            if (!empty($indirectExpIds)) {
                for ($i = 0; $i < count($indirectExpIds); $i++) {
                    $con3 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $indirectExpIds[$i];
                    $data3 = JournalEntry::model()->findAll(array("condition" => $con3));
                    $drAmountIndirectExp = 0;
                    $crAmountIndirectExp = 0;
                    if ($data3) {
                        foreach ($data3 as $d3):
                            if ($d3->transaction_type == JournalEntry::DEBIT) {
                                $drAmountIndirectExp = $drAmountIndirectExp + $d3->amount;
                            } else {
                                $crAmountIndirectExp = $crAmountIndirectExp + $d3->amount;
                            }
                        endforeach;
                    }
                    $totalIndirectExp = $drAmountIndirectExp - $crAmountIndirectExp;
                    $totalIndirectExpTotal = $totalIndirectExp + $totalIndirectExpTotal;
                    ?>
                    <?php
                }
            }
            ?>
            <?php
            $deprChargesAO = 0;
            $criteriaAO = new CDbCriteria();
            $criteriaAO->addColumnCondition(array("start_date" => $startDate, "end_date" => $endDate, "depr_type" => DepreciationCharges::AO), "AND", "AND");
            $deprChargesAOInfo = DepreciationCharges::model()->findAll($criteriaAO);
            if ($deprChargesAOInfo) {
                $deprChargesAO = end($deprChargesAOInfo)->amount;
            }
            $totalIndirectExpTotal = $totalIndirectExpTotal + $deprChargesAO;
            ?>
            <tr>
                <td style="text-align: left;">Administrative Expenses</td>
                <td>(<?php echo number_format(floatval($totalIndirectExpTotal), 2); ?>)</td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $operatingProfitLoss = ($grossOperatingProfitLoss - $totalIndirectExpTotal);
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Operating <?php
            if ($operatingProfitLoss >= 0)
                echo 'Profit'; else
                echo 'Loss';
            ?></td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;">
                    <?php
                    if ($operatingProfitLoss >= 0)
                        echo number_format(floatval(abs($operatingProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($operatingProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <?php
            $totalOtherExpenseTotalTotal = 0;
            $otherExpenseGroupIds = ChartOfAc::model()->groupLedgers(ChartOfAc::EXPENSE, ChartOfAc::DIRECT_EXPENSE, ChartOfAc::INDIRECT_EXPENSE);
            if (!empty($otherExpenseGroupIds)) {
                for ($i = 0; $i < count($otherExpenseGroupIds); $i++) {
                    $otherExpenseChilds = array();
                    $otherExpenseChildIds = ChartOfAc::model()->noChild($otherExpenseGroupIds[$i], $otherExpenseChilds);
                    $totalOtherExpenseTotal = 0;
                    if (!empty($otherExpenseChildIds)) {
                        for ($j = 0; $j < count($otherExpenseChildIds); $j++) {
                            $con4 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $otherExpenseChildIds[$j];
                            $data4 = JournalEntry::model()->findAll(array("condition" => $con4));
                            $drAmountOtherExpense = 0;
                            $crAmountOtherExpense = 0;
                            if ($data4) {
                                foreach ($data4 as $d4):
                                    if ($d4->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountOtherExpense = $drAmountOtherExpense + $d4->amount;
                                    } else {
                                        $crAmountOtherExpense = $crAmountOtherExpense + $d4->amount;
                                    }
                                endforeach;
                            }
                            $totalOtherExpense = $drAmountOtherExpense - $crAmountOtherExpense;
                            $totalOtherExpenseTotal = $totalOtherExpense + $totalOtherExpenseTotal;
                        }
                    }
                    if ($totalOtherExpenseTotal != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($otherExpenseGroupIds[$i]); ?></td>
                            <td><?php echo number_format(floatval(abs($totalOtherExpenseTotal)), 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    $totalOtherExpenseTotalTotal = $totalOtherExpenseTotal + $totalOtherExpenseTotalTotal;
                }
            }
            ?>
            <tr>
                <td style="text-align: left;">Other Expenses</td>
                <td>(<?php echo number_format(floatval(abs($totalOtherExpenseTotalTotal)), 2); ?>)</td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $operatingProfitLoss = ($operatingProfitLoss - $totalOtherExpenseTotalTotal);
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;"></td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;">
                    <?php
                    if ($operatingProfitLoss >= 0)
                        echo number_format(floatval(abs($operatingProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($operatingProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <?php
            $totalOtherIncomeTotalTotal = 0;
            $otherIncomeGroupIds = ChartOfAc::model()->groupLedgers(ChartOfAc::INCOME, ChartOfAc::SALE, ChartOfAc::SALE);
            if (!empty($otherIncomeGroupIds)) {
                for ($i = 0; $i < count($otherIncomeGroupIds); $i++) {
                    $otherIncomeChilds = array();
                    $otherIncomeChildIds = ChartOfAc::model()->noChild($otherIncomeGroupIds[$i], $otherIncomeChilds);
                    $totalOtherIncomeTotal = 0;
                    if (!empty($otherIncomeChildIds)) {
                        for ($j = 0; $j < count($otherIncomeChildIds); $j++) {
                            $con5 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $otherIncomeChildIds[$j];
                            $data5 = JournalEntry::model()->findAll(array("condition" => $con5));
                            $drAmountOtherIncome = 0;
                            $crAmountOtherIncome = 0;
                            if ($data5) {
                                foreach ($data5 as $d5):
                                    if ($d5->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountOtherIncome = $drAmountOtherIncome + $d5->amount;
                                    } else {
                                        $crAmountOtherIncome = $crAmountOtherIncome + $d5->amount;
                                    }
                                endforeach;
                            }
                            $totalOtherIncome = $drAmountOtherIncome - $crAmountOtherIncome;
                            $totalOtherIncomeTotal = $totalOtherIncome + $totalOtherIncomeTotal;
                        }
                    }
                    if ($totalOtherIncomeTotal != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($otherIncomeGroupIds[$i]); ?></td>
                            <td><?php echo number_format(floatval(abs($totalOtherIncomeTotal)), 2); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    $totalOtherIncomeTotalTotal = $totalOtherIncomeTotal + $totalOtherIncomeTotalTotal;
                }
            }
            ?>
            <tr>
                <td style="text-align: left;">Other Income</td>
                <td><?php echo number_format(floatval(abs($totalOtherIncomeTotalTotal)), 2); ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $netProfitLoss = $operatingProfitLoss + abs($totalOtherIncomeTotalTotal);
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Net <?php
            if ($netProfitLoss >= 0)
                echo 'Profit'; else
                echo 'Loss';
            ?> Before TAX</td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;">
                    <?php
                    if ($netProfitLoss >= 0)
                        echo number_format(floatval(abs($netProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($netProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left;">Provision For TAX</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left;">37.5% of Net <?php
                    if ($netProfitLoss >= 0)
                        echo 'Profit'; else
                        echo 'Loss';
                    ?></td>
                <td>
                    <?php
                    if ($netProfitLoss >= 0)
                        $tax = ($netProfitLoss * 37.5) / 100;
                    else
                        $tax = 5000;
                    echo "(" . number_format(floatval(abs($tax)), 2) . ")";
                    ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Net <?php
                    if ($netProfitLoss >= 0)
                        echo 'Profit'; else
                        echo 'Loss';
                    ?> After TAX</td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px;">
                    <?php
                    $netProfitLoss = $netProfitLoss - $tax;
                    if ($netProfitLoss >= 0)
                        echo number_format(floatval(abs($netProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($netProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px;">Balance Carried From Previous Year</td>
                <td></td>
                <td style="font-weight: bold; padding-bottom: 30px;">
                    <?php
                    $balanceCarriedFromPreviousYear = 0;
                    $criteria = new CDbCriteria();
                    $criteria->condition = "start_date<'" . $startDate . "' ORDER BY id DESC LIMIT 0,1";
                    $balanceCarriedFromPrevYearAc = BalanceCarriedFromPrevYear::model()->findAll($criteria);
                    if ($balanceCarriedFromPrevYearAc) {
                        $balanceCarriedFromPreviousYear = end($balanceCarriedFromPrevYearAc)->amount;
                    }
                    //$balanceCarriedFromPreviousYear = JournalEntry::model()->incomeStatementPreviousDate($startDate);
                    if ($balanceCarriedFromPreviousYear >= 0)
                        echo number_format(floatval(abs($balanceCarriedFromPreviousYear)), 2);
                    else
                        echo "(" . number_format(floatval(abs($balanceCarriedFromPreviousYear)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
            <?php
            $unappropriateProfitLoss = $balanceCarriedFromPreviousYear + $netProfitLoss;
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold;">Un-appropriate <?php
            if ($unappropriateProfitLoss >= 0)
                echo 'Profit'; else
                echo 'Loss';
            ?> Carried to Balance Sheet</td>
                <td></td>
                <td style="font-weight: bold; border-top: 1px solid #000000;">
                    <?php
                    if ($unappropriateProfitLoss >= 0)
                        echo number_format(floatval(abs($unappropriateProfitLoss)), 2);
                    else
                        echo "(" . number_format(floatval(abs($unappropriateProfitLoss)), 2) . ")";
                    ?>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table class="reportTab">
        <tr>
            <td colspan="5" style="padding-top: 60px;"> </td>
        </tr>
        <tr>
            <th width="25%">A/C Manager</th>
            <th width="25%">Director</th>
            <th width="25%">Managing Director</th>
        </tr>
    </table>
</div>
<style>
    table.reportTab tr, table.reportTab tr td{
        border: none;
        color: #000000;
    }
</style>
