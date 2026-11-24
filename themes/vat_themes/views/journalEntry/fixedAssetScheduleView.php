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
                <td colspan="14" style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/voucher-logo.png" style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br /><br />
                    <?php echo $message; ?>
				</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th rowspan="2" style="width: 30px;">SL</th>
                <th rowspan="2">Account Title</th>
                <th rowspan="2">Depreciation Rate</th>
                <th colspan="6">Cost</th>
                <th colspan="4">Depreciation</th>
                <th rowspan="2">W.D.V</th>
            </tr>
            <tr class="odd">
                <td style="font-style: italic;">Date</td>
                <td style="font-style: italic;">Opening</td>
                <td style="font-style: italic;">Addition</td>
                <td style="font-style: italic;">Deletion</td>
                <td style="font-style: italic;">Closing</td>
                <td style="font-style: italic;">Closing Value</td>
                <td style="font-style: italic;">Opening</td>
                <td style="font-style: italic;">Current</td>
                <td style="font-style: italic;">Closing</td>
                <td style="font-style: italic;">Total Dep.</td>
            </tr>
            <?php
            $sl = 1;
            $currentDepreciationTotal = 0;
            $fixedAssetArr = array();
            $fixedAssetIds = ChartOfAc::model()->noChild(ChartOfAc::FIXED_ASSET, $fixedAssetArr);
            if (!empty($fixedAssetIds)) {
                $wdvTotal = 0;
                for ($i = 0; $i < count($fixedAssetIds); $i++) {
                    $acInfo = ChartOfAc::model()->findByPk($fixedAssetIds[$i]);
                    $acNameCode = $acInfo->title . " (" . $acInfo->code . ")";
                    $depriciationRateOfAc = $acInfo->dep;
                    $depreciationRate = $depriciationRateOfAc / 100;
                    $depreciationRateStartDt = $acInfo->dep_start_dt;
                    $openingAmountGiven = $acInfo->opening_amount;
                    
                    //if ($depriciationRateOfAc > 0 && $depreciationRateStartDt != "") {
                        ?>

                        <?php
                        $date = date_create($startDate);
                        date_sub($date, date_interval_create_from_date_string("1 days"));
                        $endDateOP = date_format($date, "Y-m-d");
                        
                        //echo $endDateOP."<br>";
                        
                        $monthCountGivenForOP = (JournalEntry::model()->monthCount($depreciationRateStartDt, $endDateOP));
                        $depreciationGivenForOP = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForOP;
                        
                        $openingCostOP = $openingAmountGiven;
                        $openingDepreciationOP = $depreciationGivenForOP;

                        $criteriaOP = new CDbCriteria();
                        $criteriaOP->addBetweenCondition("entry_date", $depreciationRateStartDt, $endDateOP);
                        $criteriaOP->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                        $dataOP = JournalEntry::model()->findAll($criteriaOP);
                        if ($dataOP) {
                            foreach ($dataOP as $dOP) {
                                $debitOP = 0;
                                $creditOP = 0;
                                if ($dOP->transaction_type == ChartOfAc::DEBIT) {
                                    $debitOP = $dOP->amount;
                                } else {
                                    $creditOP = $dOP->amount;
                                }
                                $balanceCostOP = $debitOP - $creditOP;
                                $closingCostOP = $openingCostOP + $balanceCostOP;

                                $monthCountOP = (JournalEntry::model()->monthCount($dOP->entry_date, $endDateOP));
                                
                                $currentDepreciationOP = (($debitOP * $depreciationRate) / 12) * $monthCountOP;
                                $closingDepreciationOP = $openingDepreciationOP + $currentDepreciationOP;

                                $openingCostOP = $closingCostOP;
                                $openingDepreciationOP = $closingDepreciationOP;
                            }
                        }
                        ?>

                        <?php
                        $openingCost = $openingCostOP;
                        $openingDepreciation = $openingDepreciationOP;
                        $wdv = 0;
                        
                        $criteria = new CDbCriteria();
                        $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                        $criteria->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                        $data = JournalEntry::model()->findAll($criteria);
                        if ($data) {
                            
                            $monthCountGivenForCurrent = (JournalEntry::model()->monthCountAddOneDate($depreciationRateStartDt, $endDate));
                            
                            $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;
                            $rowspan = count($data);
                            $rowcount = 1;

                            foreach ($data as $d) {
                                $debit = 0;
                                $credit = 0;
                                if ($d->transaction_type == ChartOfAc::DEBIT) {
                                    $debit = $d->amount;
                                } else {
                                    $credit = $d->amount;
                                }
                                $balanceCost = $debit - $credit;
                                $closingCost = $openingCost + $balanceCost;

                                $monthCount = (JournalEntry::model()->monthCount($d->entry_date, $endDate));

                                $currentDepreciation = ((($debit * $depreciationRate) / 12) * $monthCount)+$depreciationGivenForCurrent;
                                $depreciationGivenForCurrent=0;
                                $closingDepreciation = $openingDepreciation + $currentDepreciation;
                                ?>
                                <tr>
                                    <?php if ($rowcount == 1) { ?>
                                        <td rowspan="<?php echo $rowspan; ?>"><?php echo $sl++; ?></td>
                                        <td rowspan="<?php echo $rowspan; ?>" style="text-align: left;"><?php echo $acNameCode; ?></td>
                                        <td rowspan="<?php echo $rowspan; ?>"><?php echo $depriciationRateOfAc; ?>%</td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        echo $d->entry_date;
                                        echo " (" . date("M, Y", strtotime($d->entry_date)) . ")";
                                        ?>
                                    </td>
                                    <td><?php echo number_format($openingCost); ?></td>
                                    <td><?php echo number_format($debit); ?></td>
                                    <td><?php echo number_format($credit); ?></td>
                                    <td><?php echo number_format($closingCost); ?></td>
                                    <?php if ($rowcount == 1) { ?>
                                        <td rowspan="<?php echo $rowspan; ?>" id="costClosingMerge_<?php echo $fixedAssetIds[$i] ?>"></td>
                                    <?php } ?>
                                    <td><?php echo number_format($openingDepreciation); ?></td>
                                    <td><?php echo number_format($currentDepreciation); ?></td>
                                    <td><?php echo number_format($closingDepreciation); ?></td>
                                    <?php if ($rowcount == 1) { ?>
                                        <td rowspan="<?php echo $rowspan; ?>" id="depreciationClosingMerge_<?php echo $fixedAssetIds[$i] ?>"></td>
                                        <td rowspan="<?php echo $rowspan; ?>" id="wdv_<?php echo $fixedAssetIds[$i] ?>"></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $currentDepreciationTotal = $currentDepreciation + $currentDepreciationTotal;
                                $openingCost = $closingCost;
                                $openingDepreciation = $closingDepreciation;
                                $wdv = $closingCost - $closingDepreciation;
                                $rowcount++;
                            }
                            ?>
                        <script>
                            $("#costClosingMerge_<?php echo $fixedAssetIds[$i] ?>").html("<?php echo number_format($closingCost); ?>");
                            $("#depreciationClosingMerge_<?php echo $fixedAssetIds[$i] ?>").html("<?php echo number_format($closingDepreciation); ?>");
                            $("#wdv_<?php echo $fixedAssetIds[$i] ?>").html("<?php echo number_format($wdv); ?>");
                        </script>
                        <?php
                    } else {
                        $monthCountGivenForCurrent = (JournalEntry::model()->monthCountAddOneDate($depreciationRateStartDt, $endDate));
                        $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;
                        
                        $debit = 0;
                        $credit = 0;
                        $balanceCost = $debit - $credit;
                        $closingCost = $openingCost + $balanceCost;

                        
                        $monthCount = (JournalEntry::model()->monthCountAddOneDate($startDate, $endDate));
                        $closingCost2=$closingCost-$openingAmountGiven;
                        $currentDepreciation = ((($closingCost2 * $depreciationRate) / 12) * $monthCount)+$depreciationGivenForCurrent;

                        $closingDepreciation = $openingDepreciation + $currentDepreciation;
                        $currentDepreciationTotal = $currentDepreciation + $currentDepreciationTotal;
                        $wdv = $closingCost - $closingDepreciation;
                        ?>
                        <tr>
                            <td><?php echo $sl++; ?></td>
                            <td style="text-align: left;"><?php echo $acNameCode; ?></td>
                            <td><?php echo $depriciationRateOfAc; ?>%</td>
                            <td><?php //echo $monthCount." Months";  ?></td>
                            <td><?php echo number_format($openingCost); ?></td>
                            <td><?php echo number_format($debit); ?></td>
                            <td><?php echo number_format($credit); ?></td>
                            <td><?php echo number_format($closingCost); ?></td>
                            <td><?php echo number_format($closingCost); ?></td>
                            <td><?php echo number_format($openingDepreciation); ?></td>
                            <td><?php echo number_format($currentDepreciation); ?></td>
                            <td><?php echo number_format($closingDepreciation); ?></td>
                            <td><?php echo number_format($closingDepreciation); ?></td>
                            <td><?php echo number_format($wdv); ?></td>
                        </tr>
                        <?php
                    }
                    $wdvTotal = $wdv + $wdvTotal;
                //}
            }
            ?>
            <tr>
                <td colspan="10" style="padding-right: 6px; font-weight: bold; text-align: right;">Total</td>
                <td><?php echo number_format($currentDepreciationTotal); ?></td>
                <td></td>
                <td></td>
                <td><?php echo number_format($wdvTotal); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>