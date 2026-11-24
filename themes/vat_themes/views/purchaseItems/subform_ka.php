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
                        <col style=" width: 5%;">
                        <?php if($note=='one' || $note=='two'){?>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        <?php } ?>
                        <col>
                        <col>
                        <col>
                        <?php if($note=='one' || $note=='two'){?>
                            <col>
                            <col>
                        <?php } ?>
                        <col>
                        <col>
                        <?php if($note=='one' || $note=='two'){?>
                            <col>
                        <?php } ?>
                        <col>
                    </colgroup>
                    <tbody>
                    <tr style="border:1px solid #000;text-align: center">
                        <th colspan="<?= ($note=='one' || $note=='two') ? '15' : '8' ?>">
                            <?=($eng)? 'Sub-form for' : '' ?> <?= ($eng) && ($note=='one' || $note=='two') ? 'Export/Import' : 'local supply' ?> <?= ($eng)? '(For Note -':'সাবর্ফম্- ক  ( নোট'?>
                            <?php
                            if($note=='one'){
                                if($eng) echo '1'; else echo '১';
                            }else if($note=='two'){
                                if($eng) echo '2'; else echo '২';
                            }else if($note=='three'){
                                if($eng) echo '3'; else echo '৩';
                            }else if($note=='four'){
                                if($eng) echo '4'; else echo '৪';
                            }else if($note=='five'){
                                if($eng) echo '5'; else echo '৫';
                            }else if($note=='seven'){
                                if($eng) echo '7'; else echo '৭';
                            }
                            ?>
                            <?=($eng)? ')':'এর জন্য প্রযোজ্য)'?>
                        </th>
                    </tr>
                    <tr class="style_5" valign="top" align="center" style="background-color:#eae8e8;vertical-align: middle">
                        <th class="style_24 center">
                            <?=($eng)? 'Serial<br>No.':'ক্রমিক <br> নং'?>
                        </th>
                        <?php if($note=='one' || $note=='two'){ ?>

                            <th class="style_24 center">
                                <?=($eng)? 'Invoice/B/E No':''?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Date':''?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Office Code':''?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Item No':''?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'CPC<br>(Optional)':''?>
                            </th>
                       
                        <?php } ?>
                        <th class="style_24 center">
                            <?=($eng)? 'Goods / Service<br>Commercial<br>Description':'পণ্য/সেবার  <br>বানিজ্যিক <br> বর্ণনা'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Goods /<br>Service Code':'পণ্য/সেবার কোড'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'Goods /<br>Service Name':'পণ্য/সেবার নাম'?>
                        </th>
                        <?php if($note=='one' || $note=='two'){?>

                            <th class="style_24 center">
                                <?=($eng)? 'Asseassable<br>value':''?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Base value of<br>VAT':''?>
                            </th>

                        <?php } ?>
                        <?php if($note=='three' || $note=='four' || $note =='five' || $note =='seven'){?>

                            <th class="style_24 center">
                                <?=($eng)? 'Value <br>(a)':'মূল্য<br>(ক)'?>
                            </th>

                        <?php } ?>
                        
                        <th class="style_24 center">
                            <?=($eng)? 'SD<br>(b)':'সম্পূরক <br> শুল্ক (খ)'?>
                        </th>
                        <th class="style_24 center">
                            <?=($eng)? 'VAT<br>(c)':'মূল্য সংযোজন <br> কর   <br> (গ)'?>
                        </th>
                        <?php if($note=='one' || $note=='two'){ ?>

                            <th class="style_24 center">
                                <?=($eng)? 'AT':''?>
                            </th>

                        <?php } ?>
                        <th class="style_24 center">
                            <?=($eng)? 'Notes':'মন্তব্য'?>
                        </th>
                    </tr>
                    <?php if($note=='one' || $note=='two'){ ?>

                        <tr class="style_5" valign="top" align="center" style="background-color:#eae8e8;vertical-align: middle">
                            <th class="style_24 center">
                                <?=($eng)? '1':'১'?>
                            </th>

                            <th class="style_24 center">
                                <?=($eng)? '2':'২'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '3':'৩'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '4':'৪'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '5':'৫'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '6':'৬'?>
                            </th>
                        
                            <th class="style_24 center">
                                <?=($eng)? '7':'৭'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '8':'৮'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '9':'৯'?>
                            </th>

                            <th class="style_24 center">
                                <?=($eng)? '10':'১০'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '11':'১১'?>
                            </th>

                            
                            <th class="style_24 center">
                                <?=($eng)? '12':'১২'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? '13':'১৩'?>
                            </th>

                            <th class="style_24 center">
                                <?=($eng)? '14':'১৪'?>
                            </th>

                            <th class="style_24 center">
                                <?=($eng)? '15':'১৫'?>
                            </th>
                        </tr>
                    <?php } ?>
                    <?php
                    $do_zero = false;
                    if(!empty($note)){
                        if($note=='one'){
                            $condition = "(sales_type=2 AND export_type=2)";
                        }else if($note=='two'){
                            $condition = "(sales_type=2 AND export_type=1)";
                        }else if($note=='three'){
                            // non vatable = 0
                            $condition = "(vat_calculation_method = 4) OR (vat_calculation_method = 5 AND sales_type=1)";
                        }else if($note=='four'){
                            $condition = "vat_calculation_method=1";
                        }else if($note=='five'){
                            $condition = "vat_calculation_method = 6";
                        }else if($note=='seven'){
                            $condition = "vat_calculation_method=2";
                            if($yourCompanyInfo->business_process==3)
                                $do_zero = true;
                        }
                        $sql = "SELECT * FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND ($condition) ORDER BY sales_date ASC";
                        $data= Yii::app()->db->createCommand($sql)->queryAll();

                        if($do_zero)
                            $data = '';

                        if(!empty($data)){
                            $i=1;
                            $total = 0;
                            $sd = 0;
                            $vat = 0;
                            foreach($data as $d){
                                $sales_id = $d['sales_id'];
                                $challan_no = SellItems::model()->mushokNoBySalesID($sales_id); // mushok_no is challan no in mushak : 6.3
                                if($d['parts_service']==1){
                                    $type = 'Goods';
                                    $info = ProdModels::model()->findByPk($d['model_id']);
                                    $name = !empty($info['model_name'])?$info['model_name']:'';
                                    $code = !empty($info['hs_code'])?$info['hs_code']:'';
                                }else{
                                    $type = 'Service';
                                    $info = Services::model()->findByPk($d['model_id']);
                                    $name = !empty($info['name'])?$info['name']:'';
                                    $code = !empty($info['code'])?$info['code']:'';
                                }
                                ?>
                                <tr class="csd">
                                    <td class="center"><?=$i?></td>
                                    <?php if($note=='one' || $note=='two'){ ?>
                                        <td class="center"> <?=''?></td>
                                        <td class="center"> <?=''?></td>
                                        <td class="center"> <?=''?></td>
                                        <td class="center"> <?=''?></td>
                                        <td class="center"> <?=''?></td>
                                    <?php } ?>
                                    <td class="center"> <?=$type?></td>
                                    <td class="left" style="padding-left: 5px"> <?=($code)?$code:''?></td>
                                    <td class="left" style="padding-left: 5px"> <?=$name?></td>
                                    
                                    <?php if($note!='one' || $note!='two'){ ?>

                                        <td class="right"> <?=!empty($d['total'])?number_format($d['total'],2):'0.00'?></td>

                                    <?php } ?>
                                    <?php if($note=='one' || $note=='two'){ ?>
                                        <td class="right"><?=''?></td>
                                        <td class="right"><?=''?></td>
                                    <?php } ?>
                                    <td class="right"><?=!empty($d['sd'])?number_format($d['sd'],2):'0.00'?></td>
                                    <td class="right"> <?=!empty($d['vat'])?number_format($d['vat'],2):'0.00'?></td>
                                    <?php if($note=='one' || $note=='two'){ ?>
                                        <td><?= number_format(0,2)?></td>
                                        <?php } ?>
                                    <td class="right"> <?= $challan_no; ?></td>
                                </tr>
                                <?php
                                $i++;
                                $total = $total + $d['total'];
                                $sd = $sd + $d['sd'];
                                $vat = $vat + $d['vat'];
                            }?>
                            <tr class="right bold csd" style="background-color: #fbf7f7;">
                                <td><?= ''?></td>
                                <?php if($note=='one' || $note=='two'){ ?>
                                    <td><?= ''?></td>
                                    <td><?= ''?></td>
                                    <td><?= ''?></td>
                                    <td><?= ''?></td>
                                    <td><?= ''?></td>
                                <?php } ?>
                                <td colspan="3" style="padding-right: 5px"><?=($eng)? 'Total':'মোট'?></td>
                                <?php if($note=='one' || $note=='two'){ ?>
                                    <td><?= number_format(0,2)?></td>
                                    <td><?= number_format(0,2)?></td>
                                <?php } ?>
                                <?php if($note!='one' || $note!='two'){ ?>
                                    <td><?= number_format($total,2)?></td>
                                <?php } ?>
                                <td><?= number_format($sd,2)?></td>
                                <td><?= number_format($vat,2)?></td>
                                <?php if($note=='one' || $note=='two'){ ?>
                                    <td><?= number_format(0,2)?></td>
                                <?php } ?>
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