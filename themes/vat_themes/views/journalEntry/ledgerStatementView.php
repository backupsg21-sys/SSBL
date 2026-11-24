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
                <td colspan="9" style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/voucher-logo.png" style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br /><br />
                    <?php echo $message; ?>
				</td>
            </tr>
            <tr class="titlesTr">
                <th width="3%">SL</th>
                <th width="6%">Date</th>
                <th width="6%">Voucher No</th>
                <th width="32%">Narration</th>
                <th>Account Title</th>
                <th width="7%">Dr (Amount)</th>
                <th width="7%">Cr (Amount)</th>
                <th width="7%">Dr (Balance)</th>
                <th width="7%">Cr (Balance)</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $criteria2=new CDbCriteria();
                $criteria2->select="sum(amount) as total_dr";
                $criteria2->condition="entry_date<'".$startDate."'";
                $criteria2->addColumnCondition(array("ca_id"=>$ca_id, 'transaction_type'=>  JournalEntry::DEBIT), 'AND', 'AND');
                $data2=JournalEntry::model()->findAll($criteria2);
                $drAmountOP=end($data2)->total_dr+$drOpeningAmount;

                $criteria3=new CDbCriteria();
                $criteria3->select="sum(amount) as total_cr";
                $criteria3->condition="entry_date<'".$startDate."'";
                $criteria3->addColumnCondition(array("ca_id"=>$ca_id, 'transaction_type'=>  JournalEntry::CREDIT), 'AND', 'AND');
                $data3=JournalEntry::model()->findAll($criteria3);
                $crAmountOP=end($data3)->total_cr+$crOpeningAmount;
                
                $drAmountTotal=$drAmountOP;
                $crAmountTotal=$crAmountOP;
                $balance=$drAmountTotal-$crAmountTotal;
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="textAlgnLeft heightLight">Opening Balance</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($drAmountTotal),2); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($crAmountTotal),2); ?></td>
                <td class="textAlgnRight heightLight"><?php if($balance>=0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
                <td class="textAlgnRight heightLight"><?php if($balance<0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
            </tr>
            <?php
            if ($data) {
                ?>
                <?php
                $sl=1;
               
                foreach ($data as $d) {
                    ?>
                    <?php
                    $condition2 = "entry_no='" . $d->entry_no . "' AND ca_id!=" . $d->ca_id." AND voucher_type=".$d->voucher_type." AND entry_date='".$d->entry_date."'";
                    $data2 = JournalEntry::model()->findAll(array('condition' => $condition2));
                    $data2Count=count($data2);
                    foreach ($data2 as $d2){
                        $drAmount=0;
                        $crAmount=0;
                        if($d->transaction_type==JournalEntry::DEBIT){
                            if($data2Count<=1){
                                $drAmount=$d->amount;
                            }else{
                                $drAmount=$d2->amount;
                            }
                        }else{
                            if($data2Count<=1){
                                $crAmount=$d->amount;
                            }else{
                                $crAmount=$d2->amount;
                            }
                        }
                        
                        $balance=$balance+($drAmount-$crAmount);
                        
                        $drAmountTotal=$drAmount+$drAmountTotal;
                        $crAmountTotal=$crAmount+$crAmountTotal;
                        
                        $caInfo=  ChartOfAc::model()->findByPk($d2->ca_id);
                        ?>
            <tr class="<?php if($sl%2==0) echo 'even'; else echo 'odd'; ?>">
                <td><?php echo $sl++; ?></td>
                <td><?php echo $d2->entry_date; ?></td>
                <td class="textAlgnLeft"><?php echo JournalEntry::model()->voucherNoOfThis($d2->entry_no, $d2->voucher_type); ?></td>
                <td class="textAlgnLeft"><?php echo $d2->narration; ?></td>
                <td class="textAlgnLeft"><?php echo $caInfo->title." (".$caInfo->code.")"; ?></td>
                <td class="textAlgnRight"><?php echo number_format(floatval($drAmount),2); ?></td>
                <td class="textAlgnRight"><?php echo number_format(floatval($crAmount),2); ?></td>
                <td class="textAlgnRight"><?php if($balance>=0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
                <td class="textAlgnRight"><?php if($balance<0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
            </tr>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9"><div class="flash-error">No result found within this date range !</div></td>
                </tr>
            <?php } ?>
                <tr>
                    <td colspan="7" class="textAlgnRight heightLight" style="font-weight:bold;">Closing Balance</td>
                    <!--<td class="textAlgnRight heightLight"><?php //echo number_format(floatval($drAmountTotal),2); ?></td>
                    <td class="textAlgnRight heightLight"><?php //echo number_format(floatval($crAmountTotal),2); ?></td>-->
                    <td class="textAlgnRight heightLight" style="font-weight:bold;"><?php if($balance>=0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
                    <td class="textAlgnRight heightLight" style="font-weight:bold;"><?php if($balance<0) echo number_format(floatval(abs($balance)),2); else echo "0.00"; ?></td>
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
