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
                <td colspan="7" style="font-size: 14px; font-weight: bold; padding: 10px 0px; border:none !important;">
                    <img src="<?php echo Yii::app()->theme->baseUrl;?>/images/voucher-logo.png" style="margin-top:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>" alt="<?= $companyName ?>" /><br />
					<?php echo $message; ?>
                </td>
            </tr>
            <tr>
                <td colspan="7"></td>
            </tr>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Voucher No</th>
                <th>Narration</th>
                <th>AC Title</th>
                <th>Dr Amount</th>
                <th>Cr Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if($data){ ?>
            <?php $sl=1; ?>
            <?php $totalDrAmount=0; $totalCrAmount=0 ?>
            <?php foreach($data as $d){ ?>
            <tr class="<?php
            if ($sl % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                <td><?php echo $sl++; ?></td>
                <td><?php echo $d->entry_date; ?></td>
                <td class="textAlgnLeft">
                <?php 
                    echo JournalEntry::model()->voucherNoOfThis($d->entry_no, $d->voucher_type);
                ?>
                </td>
                <td class="textAlgnLeft"><?php echo $d->narration; ?></td>
                <td class="textAlgnLeft"><?php echo ChartOfAc::model()->nameOfThis($d->ca_id); ?></td>
                <td style="text-align:right;">
                    <?php
                        if($d->transaction_type==JournalEntry::DEBIT){
                            echo number_format(floatval($d->amount),2);
                            $totalDrAmount=$totalDrAmount+$d->amount;
                        }
                    ?>
                </td>
                <td style="text-align:right;">
                    <?php
                        if($d->transaction_type==JournalEntry::CREDIT){
                            echo number_format(floatval($d->amount),2);
                            $totalCrAmount=$totalCrAmount+$d->amount;
                        }
                    ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td style="text-align: right; padding-right: 6px; font-weight:bold;" colspan="5">Total</td>
                <td style="text-align:right; font-weight:bold;"><?php echo number_format(floatval($totalDrAmount),2); ?></td>
                <td style="text-align:right; font-weight:bold;"><?php echo number_format(floatval($totalCrAmount),2); ?></td>
            </tr>
            <?php }else{ ?>
                <tr>
                    <td colspan="7"><div class="errorMessage">No result found !</div></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
