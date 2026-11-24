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
    'id' => 'print-div' //id of the print link
));
echo "</div>";

$companyName = YourCompany::activeCompanyName();
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" style="color:#000 !important; font-size:12px;">
        <thead>
            <tr>
                <td colspan="3" style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/voucher-logo.png" style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br /><br />
                    <?php echo $message; ?>
				</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; font-weight: bold; border-bottom: 1px solid #000000;">Source of fund</td>
                <td style="font-weight: bold; border-bottom: 1px solid #000000; width: 300px;">Note</td>
                <td style="text-align: right; font-weight: bold; border-bottom: 1px solid #000000;">Amount</td>
            </tr>
            <tr>
                <td style="text-align: left; font-style: italic; padding-top: 30px;">Authorized Share Capital:</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left;">300,000.00 Ordinary Share of Tk 100 Each</td>
                <td></td>
                <td style="text-align: right;">30,000,000.00</td>
            </tr>
            <tr>
                <td style="text-align: left; font-style: italic; padding-top: 30px;">Paid Up Capital:</td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $balanceCarriedFromPreviousYear = 0;
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array("start_date" => $startDate, "end_date" => $endDate), "AND", "AND");
            $balanceCarriedFromPrevYearAc = BalanceCarriedFromPrevYear::model()->findAll($criteria);
            if ($balanceCarriedFromPrevYearAc) {
                $balanceCarriedFromPreviousYear = end($balanceCarriedFromPrevYearAc)->amount;
            }
            ?>
            <tr>
                <td style="text-align: left;">PROFIT & LOSS A/C
                <?php 
				if ($balanceCarriedFromPreviousYear>0){
					echo '<b>(PROFIT)</b>';
				}else{
					echo '<b>(LOSS)</b>';	
				}
				?>
                </td>
                <td></td>
                <td style="text-align: right;"><?php echo number_format(floatval(abs($balanceCarriedFromPreviousYear)), 2); ?></td>
            </tr>
            <?php
            $totalOETotalTotal = 0;
            $OEGroupIds = ChartOfAc::model()->groupLedgers(ChartOfAc::EQUITY, ChartOfAc::PROFIT_LOSS_AC, ChartOfAc::PROFIT_LOSS_AC);
            if (!empty($OEGroupIds)) {
                for ($i = 0; $i < count($OEGroupIds); $i++) {
                    $OEChilds = array();
                    $OEChildIds = ChartOfAc::model()->noChild($OEGroupIds[$i], $OEChilds);
                    $totalOETotal = 0;
                    if (!empty($OEChildIds)) {
                        for ($j = 0; $j < count($OEChildIds); $j++) {

                            $oeChildInfo = ChartOfAc::model()->findByPk($OEChildIds[$j]);
                            $drOpening = 0;
                            $crOpening = 0;
                            if ($oeChildInfo->transaction_type == ChartOfAc::DEBIT)
                                $drOpening = $oeChildInfo->opening_amount;
                            else
                                $crOpening=$oeChildInfo->opening_amount;

                            $con1OP = "entry_date < '" . $startDate . "' AND ca_id=" . $OEChildIds[$j];
                            $data1OP = JournalEntry::model()->findAll(array("condition" => $con1OP));
                            $drAmountOEOP = $drOpening;
                            $crAmountOEOP = $crOpening;
                            if ($data1OP) {
                                foreach ($data1OP as $d1OP):
                                    if ($d1OP->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountOEOP = $drAmountOEOP + $d1OP->amount;
                                    } else {
                                        $crAmountOEOP = $crAmountOEOP + $d1OP->amount;
                                    }
                                endforeach;
                            }

                            $con1 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $OEChildIds[$j];
                            $data1 = JournalEntry::model()->findAll(array("condition" => $con1));
                            $drAmountOE = $drAmountOEOP;
                            $crAmountOE = $crAmountOEOP;
                            if ($data1) {
                                foreach ($data1 as $d1):
                                    if ($d1->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountOE = $drAmountOE + $d1->amount;
                                    } else {
                                        $crAmountOE = $crAmountOE + $d1->amount;
                                    }
                                endforeach;
                            }
                            $totalOE = $drAmountOE - $crAmountOE;
                            $totalOETotal = $totalOE + $totalOETotal;
                        }
                    }
                    if ($totalOETotal != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($OEGroupIds[$i]); ?></td>
                            <td></td>
                            <td style="text-align: right;"><?php echo number_format(floatval(abs($totalOETotal)), 2); ?></td>
                        </tr>
                        <?php
                    }
                    $totalOETotalTotal = $totalOETotal + $totalOETotalTotal;
                }
            }
			if ($balanceCarriedFromPreviousYear>0){
            	$totalOETotalTotal = $totalOETotalTotal - abs($balanceCarriedFromPreviousYear);
			}else{
				$totalOETotalTotal = $totalOETotalTotal + abs($balanceCarriedFromPreviousYear);
			}
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px; color: blue;">Total Equity</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px; color: blue;"><?php echo number_format(floatval(abs($totalOETotalTotal)), 2); ?></td>
            </tr>
            <tr>
                <td style="text-align: left;">APPLICATION OF FUND</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">A. Fixed assets at cost less depreciation</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">
                    <?php
                    $wdvTotal = JournalEntry::model()->totalDepr($startDate, $endDate);
                    echo number_format($wdvTotal);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-style: italic; padding-top: 30px;">Current Assets:</td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $totalCurrentAssetTotalTotal = 0;
            $CurrentAssetGroupIds = ChartOfAc::model()->groupLedgers2(ChartOfAc::CURRENT_ASSET);
            if (!empty($CurrentAssetGroupIds)) {
                for ($i = 0; $i < count($CurrentAssetGroupIds); $i++) {
                    $CurrentAssetChilds = array();
                    $CurrentAssetChildIds = ChartOfAc::model()->noChild($CurrentAssetGroupIds[$i], $CurrentAssetChilds);
                    $totalCurrentAssetTotal = 0;
                    if (!empty($CurrentAssetChildIds)) {
                        for ($j = 0; $j < count($CurrentAssetChildIds); $j++) {

                            $CurrentAssetChildInfo = ChartOfAc::model()->findByPk($CurrentAssetChildIds[$j]);
                            $drOpening = 0;
                            $crOpening = 0;
                            if ($CurrentAssetChildInfo->transaction_type == ChartOfAc::DEBIT)
                                $drOpening = $CurrentAssetChildInfo->opening_amount;
                            else
                                $crOpening=$CurrentAssetChildInfo->opening_amount;

                            $con1OP = "entry_date < '" . $startDate . "' AND ca_id=" . $CurrentAssetChildIds[$j];
                            $data1OP = JournalEntry::model()->findAll(array("condition" => $con1OP));
                            $drAmountCurrentAssetOP = $drOpening;
                            $crAmountCurrentAssetOP = $crOpening;
                            if ($data1OP) {
                                foreach ($data1OP as $d1OP):
                                    if ($d1OP->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountCurrentAssetOP = $drAmountCurrentAssetOP + $d1OP->amount;
                                    } else {
                                        $crAmountCurrentAssetOP = $crAmountCurrentAssetOP + $d1OP->amount;
                                    }
                                endforeach;
                            }

                            $con2 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $CurrentAssetChildIds[$j];
                            $data2 = JournalEntry::model()->findAll(array("condition" => $con2));
                            $drAmountCurrentAsset = $drAmountCurrentAssetOP;
                            $crAmountCurrentAsset = $crAmountCurrentAssetOP;
                            if ($data2) {
                                foreach ($data2 as $d2):
                                    if ($d2->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountCurrentAsset = $drAmountCurrentAsset + $d2->amount;
                                    } else {
                                        $crAmountCurrentAsset = $crAmountCurrentAsset + $d2->amount;
                                    }
                                endforeach;
                            }
                            $totalCurrentAsset = $drAmountCurrentAsset - $crAmountCurrentAsset;
                            $totalCurrentAssetTotal = $totalCurrentAsset + $totalCurrentAssetTotal;
                        }
                    }
                    if ($totalCurrentAssetTotal != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($CurrentAssetGroupIds[$i]); ?></td>
                            <td></td>
                            <td style="text-align: right;"><?php echo number_format(floatval(abs($totalCurrentAssetTotal)), 2); ?></td>
                        </tr>
                        <?php
                    }
                    $totalCurrentAssetTotalTotal = $totalCurrentAssetTotal + $totalCurrentAssetTotalTotal;
                }
            }
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">B. Total Current Asset</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px;"><?php echo number_format(floatval(abs($totalCurrentAssetTotalTotal)), 2); ?></td>
            </tr>
            <tr>
                <td style="text-align: left; font-style: italic; padding-top: 30px;">Current Liabilities:</td>
                <td></td>
                <td></td>
            </tr>
            <?php
                $provisionForIncomeTax = 0;
                $criteriaProvisionForIncomeTax = new CDbCriteria();
                $criteriaProvisionForIncomeTax->addColumnCondition(array("start_date" => $startDate, "end_date" => $endDate), "AND", "AND");
                $provisionForIncomeTaxInfo = ProvForIncomeTax::model()->findAll($criteriaProvisionForIncomeTax);
                if ($provisionForIncomeTaxInfo) {
                    $provisionForIncomeTax = end($provisionForIncomeTaxInfo)->amount;
                }
            ?>
            <tr>
                <td style="text-align: left;">PROVISION FOR INCOME TAX</td>
                <td></td>
                <td style="text-align: right;"><?php echo number_format(floatval(abs($provisionForIncomeTax)), 2); ?></td>
            </tr>
            <?php
            $totalCurrentLATotalTotal = $provisionForIncomeTax;
            $CurrentLAGroupIds = ChartOfAc::model()->groupLedgers(ChartOfAc::CURRENT_LA, ChartOfAc::PROVISION_FOR_INCOME_TAX, ChartOfAc::PROVISION_FOR_INCOME_TAX);
            if (!empty($CurrentLAGroupIds)) {
                for ($i = 0; $i < count($CurrentLAGroupIds); $i++) {
                    $CurrentLAChilds = array();
                    $CurrentLAChildIds = ChartOfAc::model()->noChild($CurrentLAGroupIds[$i], $CurrentLAChilds);
                    $totalCurrentLATotal = 0;
                    if (!empty($CurrentLAChildIds)) {
                        for ($j = 0; $j < count($CurrentLAChildIds); $j++) {

                            $CurrentLAChildInfo = ChartOfAc::model()->findByPk($CurrentLAChildIds[$j]);
                            $drOpening = 0;
                            $crOpening = 0;
                            if ($CurrentLAChildInfo->transaction_type == ChartOfAc::DEBIT)
                                $drOpening = $CurrentLAChildInfo->opening_amount;
                            else
                                $crOpening=$CurrentLAChildInfo->opening_amount;

                            $con1OP = "entry_date < '" . $startDate . "' AND ca_id=" . $CurrentLAChildIds[$j];
                            $data1OP = JournalEntry::model()->findAll(array("condition" => $con1OP));
                            $drAmountCurrentLAOP = $drOpening;
                            $crAmountCurrentLAOP = $crOpening;
                            if ($data1OP) {
                                foreach ($data1OP as $d1OP):
                                    if ($d1OP->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountCurrentLAOP = $drAmountCurrentLAOP + $d1OP->amount;
                                    } else {
                                        $crAmountCurrentLAOP = $crAmountCurrentLAOP + $d1OP->amount;
                                    }
                                endforeach;
                            }

                            $con3 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $CurrentLAChildIds[$j];
                            $data3 = JournalEntry::model()->findAll(array("condition" => $con3));
                            $drAmountCurrentLA = $drAmountCurrentLAOP;
                            $crAmountCurrentLA = $crAmountCurrentLAOP;
                            if ($data3) {
                                foreach ($data3 as $d3):
                                    if ($d3->transaction_type == JournalEntry::DEBIT) {
                                        $drAmountCurrentLA = $drAmountCurrentLA + $d3->amount;
                                    } else {
                                        $crAmountCurrentLA = $crAmountCurrentLA + $d3->amount;
                                    }
                                endforeach;
                            }
                            $totalCurrentLA = $drAmountCurrentLA - $crAmountCurrentLA;
                            $totalCurrentLATotal = $totalCurrentLA + $totalCurrentLATotal;
                        }
                    }
                    if ($totalCurrentLATotal != 0) {
                        ?>
                        <tr>
                            <td style="text-align: left;"><?php echo ChartOfAc::model()->nameOfThis($CurrentLAGroupIds[$i]); ?></td>
                            <td></td>
                            <td style="text-align: right;"><?php echo number_format(floatval(abs($totalCurrentLATotal)), 2); ?></td>
                        </tr>
                        <?php
                    }
                    $totalCurrentLATotalTotal = $totalCurrentLATotal + $totalCurrentLATotalTotal;
                }
            }
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">C. Total Current Liabilities</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px;"><?php echo number_format(floatval(abs($totalCurrentLATotalTotal)), 2); ?></td>
            </tr>
            <?php
            $totalLtLATotal = 0;
            $ltLAArr = array();
            $ltLAIds = ChartOfAc::model()->noChild(ChartOfAc::LONG_TERM_LIABILITY, $ltLAArr);
            if (!empty($ltLAIds)) {
                for ($i = 0; $i < count($ltLAIds); $i++) {

                    $ltLAChildInfo = ChartOfAc::model()->findByPk($ltLAIds[$i]);
                    $drOpening = 0;
                    $crOpening = 0;
                    if ($ltLAChildInfo->transaction_type == ChartOfAc::DEBIT)
                        $drOpening = $ltLAChildInfo->opening_amount;
                    else
                        $crOpening=$ltLAChildInfo->opening_amount;

                    $con1OP = "entry_date < '" . $startDate . "' AND ca_id=" . $ltLAIds[$i];
                    $data1OP = JournalEntry::model()->findAll(array("condition" => $con1OP));
                    $drAmountltLAOP = $drOpening;
                    $crAmountltLAOP = $crOpening;
                    if ($data1OP) {
                        foreach ($data1OP as $d1OP):
                            if ($d1OP->transaction_type == JournalEntry::DEBIT) {
                                $drAmountltLAOP = $drAmountltLAOP + $d1OP->amount;
                            } else {
                                $crAmountltLAOP = $crAmountltLAOP + $d1OP->amount;
                            }
                        endforeach;
                    }

                    $con4 = "entry_date BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND ca_id=" . $ltLAIds[$i];
                    $data4 = JournalEntry::model()->findAll(array("condition" => $con4));
                    $drAmountLtLA = $drAmountltLAOP;
                    $crAmountLtLA = $crAmountltLAOP;
                    if ($data4) {
                        foreach ($data4 as $d4):
                            if ($d4->transaction_type == JournalEntry::DEBIT) {
                                $drAmountLtLA = $drAmountLtLA + $d4->amount;
                            } else {
                                $crAmountLtLA = $crAmountLtLA + $d4->amount;
                            }
                        endforeach;
                    }
                    $totalLtLA = $drAmountLtLA - $crAmountLtLA;
                    $totalLtLATotal = $totalLtLA + $totalLtLATotal;
                }
            }
            ?>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">D. Long Term Liability</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; padding-bottom: 30px; padding-top: 30px;"><?php echo number_format(floatval(abs($totalLtLATotal)), 2); ?></td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">E. Total Liability (C+D)</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px;">
                    <?php
                    $totalLiability = abs($totalCurrentLATotalTotal) + abs($totalLtLATotal);
                    echo number_format(floatval(abs($totalLiability)), 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px;">F. Net Current Asset (B-E)</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px;">
                    <?php
                    $netCurrentAsset = abs($totalCurrentAssetTotalTotal) - abs($totalLiability);
                    echo number_format(floatval(abs($netCurrentAsset)), 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-weight: bold; padding-bottom: 30px; padding-top: 30px; color: blue;">TOTAL (A+F)</td>
                <td></td>
                <td style="text-align: right; font-weight: bold; border-top: 1px solid #000000; padding-bottom: 30px; padding-top: 30px; color: blue;">
                    <?php
                    $total = abs($wdvTotal) + abs($netCurrentAsset);
                    echo number_format(floatval(abs($total)), 2);
                    ?>
                </td>
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
