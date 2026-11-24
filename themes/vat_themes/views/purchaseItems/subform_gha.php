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
                    <?php if($eng){ ?>
                    <colgroup>
                        <col style=" width: 4%;">
                            <col style=" width: 9%;">
                                <col style=" width: 8%;">
                                    <col style=" width: 10%;">
                                        <col style=" width: 7%;">
                                            <col style=" width: 7%;">
                                                <col style=" width: 7%;">
                                                    <col style=" width: 7%;">
                                                        <col style=" width: 7%;">
                                                            <col style=" width: 7%;">
                                                                <col style=" width: 7%;">
                                                                    <col style=" width: 7%;">
                                                                        <col style=" width: 7%;">
                                                                            <col style=" ">
                    </colgroup>
                    <?php }else{ ?>
                        <colgroup>
                            <col style=" width: 8%;">
                            <col style=" width: 12%;">
                            <col style=" width: 10%;">
                            <col style=" width: 16%;">
                            <col style=" width: 9%;">
                            <col style=" width: 9%;">
                            <col style=" width: 9%;">
                            <col style=" width: 9%;">
                            <col style=" width: 9%;">
                            <col style=" ">
                        </colgroup>
                    <?php } ?>
                    <tbody>
                        <tr style="border:1px solid #000; text-align: center">
                            <th colspan="<?=($eng)? '14':'10'?>">
                                <?=($eng)? 'Sub-form (For Note - 24)':'সাবর্ফম্- ঘ  ( নোট ২৪ “সরবরাহগ্রহীতা কতৃক উৎসে কর্তনের জন্য প্রযোজ্য”)'?>
                            </th>
                        </tr>
                        <tr class="style_5" valign="top" align="center" style="background-color:#f2f0f0;vertical-align: middle">
                            <th class="style_24 center">
                                <?=($eng)? 'Serial<br>No.':'ক্রমিক <br> নং'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Supplier’s<br>BIN':'সরবরাহকারীর বিআইএন <br> নম্বর'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Supplier’s<br>Name':'সরবরাহকারীর নাম'?>
                            </th> 
                            <th class="style_24 center">
                                <?=($eng)? 'Supplier’s<br>Address':'সরবরাহকারীর ঠিকানা'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Value':'সরবরাহ মূল্য'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Deducted<br>VAT':'কর্তিত<br>মূল্য সংযোজন কর'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Invoice No./Challan / Bill No. etc.)':'ইনভয়েস নং <br>(চালান পত্র / বিল নম্বর ইত্যাদি)'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Invoice/ Challan/ Bill Date':'ইনভয়েস/ বিলের তারিখ'?>
                            </th>
                            <?php if($eng){ ?>
                                <th class="style_24 center">
                                    VAT Deduction at source, Certificate No
                                </th>
                                <th class="style_24 center">
                                    VAT Deduction at source, Certificate Date
                                </th>
                            <?php } ?>
                            <th class="style_24 center">
                                <?=($eng)? 'Tax deposit Account Code':'একাউন্ট কোডে কর পরিশোধিত'?>
                            </th>
                            <?php if($eng){ ?>
                                <th class="style_24 center">
                                    Tax deposit serial number of Book transfer
                                </th>
                                <th class="style_24 center">
                                    Tax deposit date
                                </th>
                            <?php } ?>
                            <th class="style_24 center">
                                <?=($eng)? 'Notes':'মন্তব্য'?>
                            </th>
                        </tr>
                        <?php
                        $total = 0;
                        $amount = 0;
                        if(!empty($note)){
                            if($note=='twentyfour'){
                                $condition = "vds_type=1 AND approve_count=2";
                                $sql = "SELECT vds.* FROM vds INNER JOIN purchase_items ON purchase_items.id = vds.purchase_id WHERE purchase_items.effective_date BETWEEN '$start_date' AND '$end_date' AND (vds_type = 1 AND vds.approve_count = 2)";
                            }
                            $data= Yii::app()->db->createCommand($sql)->queryAll();

                            if(!empty($data)){
                            $i=1;
                            foreach($data as $d){

                            $supplierInfo = Suppliers::model()->findByPk($d['supplier_id']);
                            $purchaseInfo = PurchaseItems::model()->findByPk($d['purchase_id']);
                            ?>
                            <tr class="csd">
                                <td class="center"><?=$i?></td>
                                <td class="center"><?=!empty($supplierInfo['bin'])?$supplierInfo['bin']:''?></td>
                                <td class="center"><?=!empty($supplierInfo['name'])?$supplierInfo['name']:''?></td>
                                <td class="center"><?=!empty($supplierInfo['address'])?$supplierInfo['address']:''?></td>
                                <td class="right"><?=!empty($purchaseInfo['total'])? number_format($purchaseInfo['total'],2):'0.00'?></td>
                                <td class="right"><?=!empty($d['vds'])? number_format($d['vds'],2):'0.00'?></td>
                                <td class="center"><?=!empty($d['mushok_no'])? $d['mushok_no']:''?></td>
                                <td class="center"><?=!empty($purchaseInfo['chalan_date'])? date('d-m-Y', strtotime($purchaseInfo['chalan_date'])):'N/A'?></td>
                                <?php if($eng){ ?>
                                    <td class="center"></td>
                                    <td class="center"></td>
                                <?php } ?>
                                <td class="center"><?=!empty($d['tr_chllan_no'])?$d['tr_chllan_no']:''?></td>
                                <?php if($eng){ ?>
                                    <td class="center"></td>
                                    <td class="center"></td>
                                <?php } ?>
                                <td class="center"><?=!empty($d['purpose'])?$d['purpose']:''?></td>
                            </tr>
                            <?php
                            $i++;
                            $total = $total + $purchaseInfo['total'];
                            $amount += $d['vds'];
                            }?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td colspan="4" style="padding-right: 5px"><?=($eng)? 'Total':'মোট'?></td>
                                <td><?= number_format($total,2)?></td>
                                <td><?= number_format($amount,2)?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php if($eng){ ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
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
        width: <?=($eng)? 11.5 : 9?>in;
        margin: auto;
        font-family: 'times new roman', arial;
        min-height: 8in;
        color: #000;
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
</div>