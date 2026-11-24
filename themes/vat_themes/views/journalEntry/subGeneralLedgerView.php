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
<style>
    .reportTab thead {
        position: sticky;
        top: 0;
    }
</style>
<div class='printAllTableForThisReport'>
    <table class="reportTab" style="color:#000 !important; font-size:12px;" id="table-1">
        <thead style="background: white;">
        <tr>
            <td colspan="6"
                style="font-size: 14px; font-weight: bold; border:none !important; text-align:center !important;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/voucher-logo.png"
                     style="margin:-10px; height: 65px; width: 465px;" title="<?= $companyName ?>"
                     alt="<?= $companyName ?>"/><br/><br/>
                <?php echo $message . ',  Account Title :' . ChartOfAc::model()->nameOfThis($caIds); ?>
            </td>
        </tr>
        <tr class="titlesTr">
            <th width="5%">SL</th>
            <th width="10%">Code</th>
            <th>Account Title</th>
            <th width="15%">Dr (Amount)</th>
            <th width="15%">Cr (Amount)</th>
            <th width="15%">Balance ('-' If Credit Balance)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $caIds;
        $noChildCAidsArr = array();
        $directExpIds = ChartOfAc::model()->noChild($caIds, $noChildCAidsArr);
        if (!empty($directExpIds)) {
            $totalDr = 0;
            $totalCr = 0;
            $serialNumber = 1;

            for ($t = 0; $t < count($directExpIds); $t++) {
                $ca_id = $directExpIds[$t];

                $caId = array();
                $drOrcrAmount = array();
                $count = 0;
                $criteria2 = new CDbCriteria();
                $criteria2->select = "sum(amount) as total_dr";
                $criteria2->addBetweenCondition('entry_date', $startDate, $endDate, 'AND', 'AND');
                $criteria2->addColumnCondition(array("ca_id" => $ca_id, 'transaction_type' => JournalEntry::DEBIT), 'AND', 'AND');
                $data2 = JournalEntry::model()->findAll($criteria2);
                $drAmount = end($data2)->total_dr;

                $criteria3 = new CDbCriteria();
                $criteria3->select = "sum(amount) as total_cr";
                $criteria3->addBetweenCondition('entry_date', $startDate, $endDate, 'AND', 'AND');
                $criteria3->addColumnCondition(array("ca_id" => $ca_id, 'transaction_type' => JournalEntry::CREDIT), 'AND', 'AND');
                $data3 = JournalEntry::model()->findAll($criteria3);
                $crAmount = end($data3)->total_cr;
                $caId[$count] = $ca_id;
                $drOrcrAmount[$count] = ($drAmount - $crAmount);
                $count++;


                for ($i = 0; $i < $count; $i++) {
                    if ($drOrcrAmount[$i] != 0) {
                        if ($drOrcrAmount[$i] > 0) {
                            $dr[$i] = $drOrcrAmount[$i];
                            $cr[$i] = 0;
                        } else if ($drOrcrAmount[$i] < 0) {
                            $dr[$i] = 0;
                            $cr[$i] = $drOrcrAmount[$i];
                        } else {
                            $dr[$i] = 0;
                            $cr[$i] = 0;
                        }
                        $totalDr = $dr[$i] + $totalDr;
                        $totalCr = $cr[$i] + $totalCr;

                        ?>
                        <tr>
                            <td>
                                <div onclick="bookLedgerPreview(this, <?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo $serialNumber++; ?>
                                </div>
                            </td>
                            <td>
                                <div onclick="bookLedgerPreview(this,<?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo ChartOfAc::model()->getCodeById($caId[$i]); ?>
                                </div>
                            </td>
                            <td class="textAlgnLeft">
                                <div onclick="bookLedgerPreview(this,<?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo ChartOfAc::model()->nameOfThis($caId[$i]); ?>
                                </div>
                            </td>
                            <td class="textAlgnRight">
                                <div onclick="bookLedgerPreview(this, <?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo number_format(floatval(abs($dr[$i])), 2); ?>
                                </div>
                            </td>
                            <td class="textAlgnRight">
                                <div onclick="bookLedgerPreview(this, <?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo number_format(floatval(abs($cr[$i])), 2); ?>
                                </div>
                            </td>
                            <td class="textAlgnRight">
                                <div onclick="bookLedgerPreview(this, <?= strtotime($startDate) ?>, <?= strtotime($endDate) ?>, <?= $ca_id ?>);">
                                    <?php echo '0.00'; ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            <tr>
                <td colspan="3" class="textAlgnRight">Total</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalDr)), 2); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval(abs($totalCr)), 2); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($totalDr + $totalCr), 2); ?></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td colspan="5">
                    <div class="flash-error">No result found!</div>
                </td>
            </tr>
            <?php
        }
        ?>
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

<script>
    function bookLedgerPreview(divObj, startDate, endDate, ca_id) {
        divObj.style.background="rgba(64,225,46,0.81)";
        $(this).css("background", "red");
        $(this).css("color", "red");
        var URL = '<?php echo Yii::app()->createUrl('accounting/journalEntry/bookReportViewDateWise'); ?>?startDate=' + startDate + '&endDate=' + endDate + '&ca_id=' + ca_id;
        var day = new Date();
        var id = day.getTime();
        var w = 900;
        var h = 600;
        var scrl = 1;
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height=' + h + ',width=' + w + ',top=' + wint + ',left=' + winl + ',scrollbars=' + scrl + ',toolbar=0,location=0,statusbar=0,menubar=0,resizable=0';
        eval("page" + id + " = window.open(URL, '" + id + "', winprops);");
        divObj.style.background="rgba(72,255,51,0)";
    }
</script>
