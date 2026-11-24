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
    <table class="reportTab" id="table-1" style="color:#000 !important; font-size:12px;">
        <thead>
            <tr>
                <td colspan="5" style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/voucher-logo.png" style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br /><br />
                    <?php echo $message; ?>
				</td>
            </tr>
            <tr class="titlesTr">
                <th style="width: 50px;">SL</th>
                <th style="width: 70px;">Code</th>
                <th style="width: 500px;">Account Title</th>
                <th>Dr (Amount)</th>
                <th>Cr (Amount)</th>
            </tr>
        </thead>
        <tbody>
            <?php
				$criteria = new CDbCriteria();
                $criteria->select="ca_id, main_ca";
				$criteria->join = "INNER JOIN chart_of_ac ON (t.ca_id = chart_of_ac.id)";
                $criteria->addBetweenCondition('entry_date', $startDate, $endDate);
                $criteria->group = "ca_id";
                $criteria->order = "main_ca, chart_of_ac.code ASC";
                $data = JournalEntry::model()->findAll($criteria);
                
                if($data){
                    $caId=array();
                    $drOrcrAmount=array();
                    $count=0;
                    foreach($data as $d){
                        $criteria2=new CDbCriteria();
                        $criteria2->select="sum(amount) as total_dr";
                        $criteria2->addBetweenCondition('entry_date', $startDate, $endDate, 'AND', 'AND');
                        $criteria2->addColumnCondition(array("ca_id"=>$d->ca_id, 'transaction_type'=>  JournalEntry::DEBIT), 'AND', 'AND');
                        $data2=JournalEntry::model()->findAll($criteria2);
                        $drAmount=end($data2)->total_dr;
                        
                        $criteria3=new CDbCriteria();
                        $criteria3->select="sum(amount) as total_cr";
                        $criteria3->addBetweenCondition('entry_date', $startDate, $endDate, 'AND', 'AND');
                        $criteria3->addColumnCondition(array("ca_id"=>$d->ca_id, 'transaction_type'=>  JournalEntry::CREDIT), 'AND', 'AND');
                        $data3=JournalEntry::model()->findAll($criteria3);
                        $crAmount=end($data3)->total_cr;
                        $caId[$count]=$d->ca_id;
                        $drOrcrAmount[$count]=($drAmount-$crAmount);
                        $count++;
                    }
                    $totalDr=0;
                    $totalCr=0;
                    $serialNumber=1;
                    for($i=0; $i<$count; $i++){
						if ($drOrcrAmount[$i] != 0){
                        ?>
                        <tr class="<?php
                            if ($serialNumber % 2 == 0)
                                echo "even"; else
                                echo "odd";
                            ?>">
                            <?php
                                if($drOrcrAmount[$i]>0){
                                    $dr[$i]=$drOrcrAmount[$i];
                                    $cr[$i]=0;
                                }else if($drOrcrAmount[$i]<0){
                                    $dr[$i]=0;
                                    $cr[$i]=$drOrcrAmount[$i];
                                }else{
                                    $dr[$i]=0;
                                    $cr[$i]=0;
                                }
                                $totalDr=$dr[$i]+$totalDr;
                                $totalCr=$cr[$i]+$totalCr;
                            ?>
                            <td><?php echo $serialNumber++; ?></td>
                            <td><?php echo ChartOfAc::model()->getCodeById($caId[$i]); ?></td>
                            <td class="textAlgnLeft"><?php echo ChartOfAc::model()->nameOfThis($caId[$i]); ?></td>
                            <td class="textAlgnRight"><?php echo number_format(floatval(abs($dr[$i])), 2); ?></td>
                            <td class="textAlgnRight"><?php echo number_format(floatval(abs($cr[$i])), 2); ?></td>
                        </tr>
                        <?php
						}
                    }
                    ?>
                            <td colspan="3" class="textAlgnRight">Total</td>
                            <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalDr)), 2); ?></td>
                            <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalCr)), 2); ?></td>
                    <?php
				}else{
                    ?>
                    <tr>
                        <td colspan="5"><div class="flash-error">No result found!</div></td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
</div>
