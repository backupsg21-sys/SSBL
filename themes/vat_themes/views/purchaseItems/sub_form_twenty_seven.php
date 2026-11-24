<?php
echo "<div class='printBtn'>";
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
    'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
    'alt' => 'print', //text which will appear if image can't be loaded
    'debug' => FALSE, //enable the debugger to see what you will get
    'id' => 'print-div'         //id of the print link
));
echo "</div>";
$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE));

$eng = false;
if(isset($_SESSION['translation_type']) && $_SESSION['translation_type']==2)
    $eng = true;
?>
<div class='printAllTableForThisReport' style="background-color: #F1F3F4; min-height: 650px;">
    <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show;">
        <colgroup>
            <col>
        </colgroup>
        <tbody style="border:5px solid #fff;">
        <tr valign="top">
            <td class="style_14">
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                    <colgroup>
                        <col style=" width: 8%;">
                        <col style=" width: 18%;">
                        <col style=" width: 10%;">
                        <col style=" width: 18%;">
                        <col style=" width: 12%;">
                        <col style=" width: 12%;">
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000;text-align: center">
                        <th colspan="6">
                            <?=($eng)? 'Sub-form : (For Note - 27)':'সাবর্ফম্- (নোট - ২৭)'?>
                        </th>
                    </tr>
                    <tr class="style_5" valign="top" align="center" style="background-color:#eae8e8;vertical-align: middle">
                        <th class="style_24 center">
                            <?=($eng)? 'Serial No.':'ক্রমিক নং'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Challan No':'চালান নং'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Date':'তারিখ'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Value':'মূল্য'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'VAT':'মূল্য সংযোজন কর'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Notes':'মন্তব্য'?>
                        </th>
                    </tr>
                    <?php
                    if(!empty($note)){
                        $sql = "SELECT * FROM adjustments WHERE nine_one_part = 5 AND payment_date BETWEEN '$start_date' AND '$end_date' ORDER BY payment_date ASC"; // 5 for increasing vat adjustment
                        $data= Yii::app()->db->createCommand($sql)->queryAll();

                        if(count($data) > 0){
                            $i=1;
                            $totalValue = 0;
                            $totalVat = 0;
                            foreach($data as $d){
                                ?>
                                <tr class="csd">
                                    <td class="center"><?= $i ?></td>
                                    <td class="center"> <?= $d['challan_no'] ?></td>
                                    <td class="center" style="padding-left: 5px"> <?= $d['payment_date'] ?></td>
                                    <td class="right"> <?= !empty($d['value'])?number_format($d['value'],2):'0.00' ?></td>
                                    <td class="right"> <?= !empty($d['amount'])?number_format($d['amount'],2):'0.00' ?></td>
                                    <td class="left" style="padding-left: 5px"> <?= $d['note'] ?></td>
                                </tr>
                                <?php
                                $i++;
                                $totalValue += $d['value'];
                                $totalVat += $d['amount'];
                            }?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td colspan="3" style="padding-right: 5px"><?=($eng)? 'Total':'মোট'?></td>
                                <td><?= number_format(round($totalValue),2)?></td>
                                <td><?= number_format(round($totalVat),2)?></td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <style>
        .fieldset{
            background-color: #fbf7f7f7;
        }
        .reportTabCustom{
            font-size:12px;
            background-color:#fff;
            width: 100%;
            width: 8.3in;
            margin: auto;
            font-family: 'times new roman', arial;
            min-height: 8in;
            color:#000;
        }
        .style_5 th, .style_21 th,  .style_21 td{
            border: 1px solid #000 !important; /*#c0c0c0*/
        }
        .style_19{
            border: none;
        }
        .center_tr th{
            text-align: center;
            border: 1px solid #000;
        }
        .csd td{
            border:1px solid #000;
        }
        .left{text-align:left;}
        .center{text-align:center;}
        .right{text-align:right;}
        .bold{font-weight: bold;}
        @media print {
            .reportTabCustom{ width: 100%}
        }
    </style>