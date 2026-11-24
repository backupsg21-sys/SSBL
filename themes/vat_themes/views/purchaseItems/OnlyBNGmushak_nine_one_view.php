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

$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
if($yourCompanyInfo->company_vat_type==1)
    $standard = true;
else
    $standard = false;
?>

<?php
$twentyfour = $thirtyeight = $thirtynine = $fourty = $fourtyone = $fifty = $fiftyone = $fiftysix = $fiftyeight = $fiftynine = $sixty = $sixtyone = 0;
$sixtytwo = $sixtythree = $sixtyfour = $sixtyfive = $sixtysix = $fiftytwo = $fiftythree = $fiftyseven = $fiftyfour = $fiftyfive = $sixtyfive = $sixtysix = $sixtyseven = $sixtyeight = 0;

$this_month = date('Y-m', strtotime($start_date))."-01";
$sqlOpening = "SELECT * FROM nine_one WHERE vat_date < '$this_month' ORDER BY id DESC LIMIT 1";
$lastOpening= Yii::app()->db->createCommand($sqlOpening)->queryRow();
if($lastOpening){
    $fiftytwo = $lastOpening['closing_vat'];
    $fiftythree = $lastOpening['closing_sd'];

    $fiftyfour = $lastOpening['e_remain_vat'] - $lastOpening['decrease_vat'];
    $fiftyfive = $lastOpening['e_remain_sd']- $lastOpening['decrease_sd'];

}else{
    if($yourCompanyInfo['previous_month_vat'])
        $fiftytwo = $yourCompanyInfo['previous_month_vat'];
    if($yourCompanyInfo['previous_month_sd'])
        $fiftythree = $yourCompanyInfo['previous_month_sd'];


    if($yourCompanyInfo['opening_vat'])
        $fiftyfour = $yourCompanyInfo['opening_vat'];
    if($yourCompanyInfo['opening_sd'])
        $fiftyfive = $yourCompanyInfo['opening_sd'];
}

$refundstatus = "";
$submitdate = "";
$doc_type = "";
$last_status = "";
$sqlRefund = "SELECT * FROM nine_one WHERE vat_date = '$this_month' ORDER BY id DESC LIMIT 1";
$currentRefund= Yii::app()->db->createCommand($sqlRefund)->queryRow();
if($currentRefund){
    $sixtyseven = $currentRefund['vat_refund_claim'];
    $sixtyeight = $currentRefund['sd_refund_claim'];
    $refundstatus = $currentRefund['refund_status'];
    $last_status = $currentRefund['last_status'];
    $doc_type = $currentRefund['doc_type'];
    $submitdate = date('d-m-Y', strtotime($currentRefund['submit_date']));
}
?>
<div class='printAllTableForThisReport'>
    <table class="style_14 reportTabCustom" style="border-collapse: collapse; empty-cells: show;">
        <colgroup>
            <col>
        </colgroup>
        <tbody>
        <tr valign="top">
            <td class="style_15">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>
                <table class="" style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <tr valign="top">
                        <td></td>
                        <td style=" overflow:hidden;font-size:12px;width: 55%;">
                            <div class="style_16" style=" text-align:center;">
                                “গণপ্রজাতন্ত্রী বাংলাদেশ সরকার”<br>জাতীয় রাজস্ব বোর্ড<br><br>
                            </div>

                        </td>
                        <td style="padding-top: 12px;text-align: left;">
                            <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">মূসক-৯.১</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                            <b> মূল্য সংযোজন কর দাখিলপত্র<br>
                                [বিধি ৪৭ এর উপ-বিধি (১) দ্রষ্টব্য]    </b>
                        </td>
                    </tr>
                    </tbody>
                </table><br>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_1">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_9_aafc40f9-392b-4cc3-afec-a3858a91df25" style=" text-align:center;">অংশ-১: করদাতার তথ্য</div>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_16" id="AUTOGENBOOKMARK_10_a074aeab-7c67-4d0d-b979-5c4b289d2bb6">(১) ব্যবসায় সনাক্তকরণ সংখ্যা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_11_60b371af-af0a-4dea-9e59-681e09a7d10c" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?=!empty($yourCompanyInfo['vat_regi_no'])?$yourCompanyInfo['vat_regi_no']:''?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_12_27259c5f-420a-4e36-bba5-140d600260de">(২) করদাতার নাম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_13_f2423012-15b5-4a54-832c-e11fcfcf911c" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?=!empty($yourCompanyInfo['company_name'])?$yourCompanyInfo['company_name']:''?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_14_dd831f44-5958-40bc-8278-20a6a00ba028">(৩) করদাতার ঠিকানা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_15_5b0d4c42-147f-43f3-9d7e-1e7762781b03" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div>
                                <?=!empty($yourCompanyInfo->house)?$yourCompanyInfo->house.', ':''?>
                                <?=!empty($yourCompanyInfo->road)?$yourCompanyInfo->road.', ':''?>
                                <?=!empty($yourCompanyInfo->location)?$yourCompanyInfo->location:''?>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            (৪) ব্যবসার প্রকৃতি
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="left">
                            <?php echo Lookup::item("business_type", $yourCompanyInfo->business_type); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            (৫) অর্থনৈতিক কার্যক্রম এর প্রকৃতি
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="left">
                            <?php echo Lookup::item("business_process", $yourCompanyInfo->business_process); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_20_b0119be7-e694-4be0-a896-c70f7a5b4c32" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_2">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_30 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_21_f429e001-140f-4330-a422-72832d750fa5" style=" text-align:center;">অংশ-২: দাখিলপত্র জমার তথ্য</div>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_22_f938c8f8-6ceb-491e-8348-78e5134a1295">(১) কর মেয়াদ</div>
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                                <tbody>
                                <tr class="style_1" valign="top" >
                                    <th style="width: 48%; text-align: center">
                                        <?php
                                        echo date('F', strtotime($start_date));
                                        ?>
                                    </th>
                                    <th style="width: 4%; text-align: center">
                                        /
                                    </th>
                                    <th style="width: 48%; text-align: center">
                                        <?php
                                        echo date('Y', strtotime($start_date));
                                        ?>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_25_e1a44cc2-df18-4441-b74a-100650a174dd">
                                (২) দাখিলপত্রের প্রকার
                                [ অনুগ্রহ করিয়া প্রযোজ্যটিতে টিক দিন ]
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_26_9523d0a1-33f6-4c09-ba49-a54d5873f9cd" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_34" style="border-collapse: collapse; empty-cells: show; width: 100%;line-height:21px;">
                                <tbody>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px">
                                        (ক)&nbsp;&nbsp; মূল দাখিলপত্র (ধারা ৬৪)
                                    </td>
                                    <td>
                                        <?php
                                        if($doc_type==1){
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td  style=" width: 60%;padding-left: 20px">(খ)&nbsp;&nbsp; সংশোধিত দাখিলপত্র (ধারা ৬৬)</td>
                                    <td>
                                        <?php
                                        if($doc_type==2){
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td  style=" width: 60%;padding-left: 20px">(গ)&nbsp;&nbsp; পূর্ণ,  অতিরিক্ত বা বিকল্প  দাখিলপত্র (ধারা ৬৭)</td>
                                    <td>
                                        <?php
                                        if($doc_type==3){
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_25_e1a44cc2-df18-4441-b74a-100650a174dd">(৩) বিগত কর মেয়াদে কোনো কার্যক্রম সম্পাদিত হইয়াছে কি?</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_26_9523d0a1-33f6-4c09-ba49-a54d5873f9cd" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_34" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_4">
                                <colgroup>
                                    <col style=" width: 10%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 40%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 40%;">
                                </colgroup>
                                <tbody>
                                <tr valign="top">
                                    <td class="style_35" align="center">
                                        <?php
                                        if($last_status==1){
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">হ্যাঁ&nbsp;</div>";
                                        }else if($last_status==2){
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">না</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="AUTOGENBOOKMARK_29_626a82a6-6dde-4041-959b-9a5107ca824c" style=" text-align:center;">[যদি 'না' হয় তাহা হইলে অংশ-১,৩ এবং ১১ পূরণ করুন]</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_30_4c47de6a-36ef-4e92-ac48-1466ca983f08">(৪) পেশের তারিখ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_31_eedd7a2a-b076-4f10-af3c-79d3be25fd8b" style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_23 date-tb" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="">
                                <?php
                                $date = date('d-m-Y');
                                ?>
                                <tbody>
                                <tr class="style_1" valign="top">
                                    <th class="style_38"  style="text-align: center">
                                        <div  style=" text-align:center;"><?=substr($submitdate,0,1)?></div>
                                    </th>
                                    <th align="left">
                                        <div style=" text-align:center;"><?=substr($submitdate,1,1)?></div>
                                    </th>
                                    <th style="text-align: center">
                                        /
                                    </th>
                                    <th class="style_39"  style="text-align: center">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,3,1)?></div>
                                    </th>
                                    <th class="style_39"  style="text-align: center">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,4,1)?></div>
                                    </th>
                                    <th  style="text-align: center">
                                        /
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,6,1)?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,7,1)?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,8,1)?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32" style=" text-align:center;"><?=substr($submitdate,9,1)?></div>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_34_7c1e7fa1-8a4c-4597-97d6-4d5045de539d" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_6">
                    <colgroup>
                        <col style=" width: 20%;">
                        <col style=" width: 20%;">
                        <col style=" width: 5%;">
                        <col style=" width: 15%;">
                        <col style=" width: 15%;">
                        <col style=" width: 15%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <?php
                    $part_three_total = [];
                    $part_three_sd = [];
                    $part_three_vat = [];
                    ?>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="6" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_35_cb3b60fd-7599-4aa8-8c29-ddafa8091c8e" style=" text-align:center;">অংশ-৩: সরবরাহ প্রদান - প্রদেয় কর</div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_36_00ba4606-14df-4717-8b34-64b845b97813" style=" text-align:center;">সরবরাহের প্রকৃতি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_37_d8855914-4340-44b3-ac04-f7551f8f50f7" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_38_b5933ba0-8f2b-4507-992b-3156a27ffe5d" style=" text-align:center;">মূল্য (ক)</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_39_01ae9539-4c27-4794-b2b0-30fbbe01cc53" style=" text-align:center;">এসডি (খ)</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_40_320ca1f9-e1c4-4b70-8321-512236bb44c2" style=" text-align:center;">মূসক (গ)</div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND ((sales_type=2 AND export_type=2 )) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div class="style_16" id="AUTOGENBOOKMARK_41_d20f9d07-d4f9-4ff7-acce-b3177689ddbd">শূন্যহার বিশিষ্ট পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_42_6e76655c-6ffb-4980-8a97-554d85c8f821">সরাসরি রপ্তানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_43_d946e1ca-3cbe-4551-9f18-65a91db4ebd0" style=" text-align:center;">১</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'one','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND ((sales_type=2 AND export_type=1)) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_28" align="left">
                            <div class="style_29" id="AUTOGENBOOKMARK_45_f4c36b91-62dc-4aaf-9116-4ad08bb3dc7f">প্রচ্ছন্ন রপ্তানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_46_9f184009-9d30-450e-a7a7-7bab06603b2a" style=" text-align:center;">২</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'two','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND ((vat_calculation_method = 4) OR (vat_calculation_method = 5 AND sales_type=1)) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_48_d3edaec7-7c8e-43fd-9735-44f0487aca85">অব্যাহতিপ্রাপ্ত পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_49_0cb33e4f-8a08-4d11-b66b-d0f5ccd49001" style=" text-align:center;">৩</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="right">
                            <?php /*!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'*/?>0.00
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'three','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (vat_calculation_method=1) AND sales_type=1 ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_51_3d36a63d-5795-4f0b-942e-4d04f8f3ff17">আদর্শ হারের পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_52_9fbda0bb-1faf-48d7-a623-c03edbeaa77d" style=" text-align:center;">৪</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'four','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (vat_calculation_method=6) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_54_6b72ecfc-c915-4809-ba96-9e0a28036cd2">সর্বোচ্চ খুচরা মূল্যভিত্তিক পণ্য</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_55_e5a2a948-abc8-4f0f-9227-ec01184cf594" style=" text-align:center;">৫</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'five','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (vat_calculation_method = 3) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_57_14bef434-506e-487a-8b87-e2abced98960">সুনির্দিষ্ট কর ভিত্তিক পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_58_022ab235-8735-499d-b8af-010cda470327" style=" text-align:center;">৬</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-  গ',array('purchaseItems/formga/','note'=>'six','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (vat_calculation_method=2) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_60_f7d2f589-4a5a-4c31-ad6b-6caa4a22fd78">আদর্শ করহার ব্যতীত ভিন্ন করহার ভিত্তিক পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_61_a9e26942-34ce-4a7c-8671-0d88c22511b5" style=" text-align:center;">৭</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($yourCompanyInfo->business_process!=3){?>
                            <?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{ ?>
                                0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($yourCompanyInfo->business_process!=3){?>
                            <?=!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'?>
                            <?php }else{ ?>
                                0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($yourCompanyInfo->business_process!=3){?>
                            <?=!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'?>
                            <?php }else{ ?>
                            0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formka/','note'=>'seven','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details 
                                WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND (vat_calculation_method=2 AND approve_count=2) ORDER BY sales_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_63_7b6b5941-1010-42ec-a31d-b9ce37b48470">খুচরা/পাইকারি/ব্যবসায়ী ভিত্তিক সরবরাহ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_64_6e57420e-5c92-40f7-af71-832a9bb3db1d" style=" text-align:center;">৮</div>
                        </td>
                        <td class="style_28" align="right">
						<?php
						if($yourCompanyInfo->business_process==3){?>
							<?=!empty($note1['subtotal'])? number_format($part_three_total[] = $note1['subtotal'],2):'0.00'?>
						<?php
						}else{
							echo '0.00';
						}
						?>
                            
                        </td>
                        <td class="style_28" align="right">
						<?php
						if($yourCompanyInfo->business_process==3){?>
							<?=!empty($note1['subsd'])? number_format($part_three_sd[] = $note1['subsd'],2):'0.00'?>
						<?php
						}else{
							echo '0.00';
						}
						?>
                        </td>
                        <td class="style_28" align="right">
						<?php
						if($yourCompanyInfo->business_process==3){?>
							<?=!empty($note1['subvat'])? number_format($part_three_vat[] = $note1['subvat'],2):'0.00'?>
						<?php
						}else{
							echo '0.00';
						}
						?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-  খ',array('purchaseItems/formkha/','note'=>'eight','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $nine_ka = $nine_kha = $nine_ga = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27" colspan="2">
                            <div class="style_56" id="AUTOGENBOOKMARK_66_e44125c7-b496-4436-8b92-6c4c3a56bb79">মোট বিক্রয়মূল্য ও মোট প্রদেয় কর</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" id="AUTOGENBOOKMARK_67_44524425-f419-41e3-823c-388c63e33cb7" style=" text-align:center;">৯</div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_ka = array_sum($part_three_total),2)?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_kha = array_sum($part_three_sd),2)?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_ga = array_sum($part_three_vat),2)?></div>
                        </td>
                        <td class="style_60" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_68_895d9ecf-26da-4ac8-b634-cff81afbb3fb" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_7">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 20%;">
                        <col style=" width: 5%;">
                        <col style=" width: 20%;">
                        <col style=" width: 15%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <?php
                    $part_four_total = [];
                    $part_four_vat = [];
                    ?>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="5" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_69_546353ea-904f-440c-ac6d-965c1f0e7c9f" style=" text-align:center;">অংশ-৪: ক্রয় - উপকরণ কর</div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27" colspan="2">
                            <div id="AUTOGENBOOKMARK_70_fbd65432-b096-463f-baab-5c081356b3de" style=" text-align:center;">ক্রয়ের প্রকৃতি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_71_efa911ed-5073-43fe-9fe7-7fb9164f98d5" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_72_2de6b371-ee20-476b-9fa2-ca8ab32d8db3" style=" text-align:center;">মূল্য (ক)</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_73_bddfe48c-685a-435c-8f1b-b823cac2fc28" style=" text-align:center;">মূসক (খ)</div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 5 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_74_7a3ebf87-d66e-44fe-a80b-f91bd34439d8">শুন্যহার বিশিষ্ট পণ্য/সেবা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_75_e76a19d0-e365-44eb-bce8-501671ea3408">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_76_64b95f19-a654-4fea-a3fc-ba258ce5191e" style=" text-align:center;">১০</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'ten','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 5 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_28" align="left">
                            <div class="style_29" id="AUTOGENBOOKMARK_78_ecad55a3-0714-4f58-83a6-0900817c333b">আমদানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_79_319219ef-2946-483a-9098-32929dc59724" style=" text-align:center;">১১</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'eleven','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 4 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_81_f6716a78-32ba-44e4-ae97-e3f644af64c9">অব্যাহতিপ্রাপ্ত পণ্য/সেবা</div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_82_ebe8917a-2013-4827-adfb-2ba37d098280">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_83_b496d8a7-caba-45a4-9387-e6133d025638" style=" text-align:center;">১২</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            }?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'twelve','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 4 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_85_719d324b-2992-416d-9f9b-2fb8512ad62c">আমদানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_86_98aef501-3a5c-469d-abc7-85467dbae64b" style=" text-align:center;">১৩</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            }?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'thirteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 1 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_88_72014a60-5369-4a11-a1d2-7feaa9c746ac">আদর্শ হারের পণ্য/সেবা</div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_89_9da80512-6a56-4eb7-9517-2a96c7b33ead">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_90_1ed368ab-6c99-4788-848b-4a4bd160fb60" style=" text-align:center;">১৪</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'fourteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 1 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_92_6b668279-10a0-4a16-9df2-668e0271fdfb">আমদানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_93_db9d593c-0712-49c4-bc58-b7f1e96e4793" style=" text-align:center;">১৫</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'fifteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 2 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_95_468333f3-9e4b-4c95-b552-e13b5e4bec7b">আদর্শ হার ব্যতীত অন্যান্য হারের পণ্য/সেবা</div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_96_14fc8b9e-1dd5-451a-b08a-a927659c8a8a">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_97_6998ab59-a190-4686-9b54-c3b30e7438fb" style=" text-align:center;">১৬</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'sixteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 2 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_99_fd39fedd-0bf5-4563-86f1-e0e8db11d1c9">আমদানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_100_f384afe4-73e6-4c15-8723-39fbb9b59f3f" style=" text-align:center;">১৭</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'seventeen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (pd.vat_calculation_method = 3 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_102_0f110cb1-e0d2-4f1f-a6e5-1bcd89554bd8">সুনির্দিষ্ট কর ভিত্তিক পণ্য/সেবা</div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_103_b3330c23-cf6e-4ff1-a3f5-d5a98c6070b9">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_104_20ffe9b3-4ff3-4859-9f67-9a529c08c144" style=" text-align:center;">১৮</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-  গ',array('purchaseItems/formga/','note'=>'eighteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (si.supplier_type_id = 3) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_106_d217b8b4-ed26-4738-91cb-4c18eef57495">রেয়াতযোগ্য নয় এরূপ পণ্য/সেবা(স্থানীয় ক্রয়)</div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_107_c5e61f9a-451b-44cd-b4e8-5100bdee75d3">টার্ণওভার প্রতিষ্ঠান হইতে</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_108_d82f1cc3-d362-4837-9108-0eef630a0300" style=" text-align:center;">১৯</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'nineteen','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' AND pd.approve_count=2 AND (si.supplier_type_id = 2) ORDER BY pd.issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_110_6df7c03d-cc4d-4014-a278-83299b34dc0f">অনিবন্ধিত প্রতিষ্ঠান হইতে</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_111_540d4ada-3048-49df-abac-e381cb3eb56b" style=" text-align:center;">২০</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if($standard){ ?>
                                <?=!empty($note1['subrebate'])? number_format($part_four_vat[] = $note1['subrebate'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'twenty','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM purchase_details 
                                            WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (local_import=54) ORDER BY issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div id="AUTOGENBOOKMARK_113_fe10e531-b306-4df0-b141-7ef9002396bb">রেয়াতযোগ্য নয় এরূপ পণ্য/সেবা(যেসকল করদাতা শুধুমাত্র অব্যাহতিপ্রাপ্ত/
                                সুনির্দিষ্ট কর/আদর্শ হার ব্যতীত অন্যান্য হারের পন্য/সেবা সরবরাহ করেন) </div>
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_114_a14ae55a-5450-4ce2-bed9-3688a7a061de">স্থানীয় ক্রয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_115_4d107689-40b2-40d8-926a-dcf75476d75a" style="text-align:center;">২১</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if(!$standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            0.00
                            <?php /*if($standard){ ?>
                                <?=!empty($note1['subvat'])? number_format($part_four_vat[] = $note1['subvat'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } */?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'twentyone','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM purchase_details 
                                            WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 AND (local_import=55) ORDER BY issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_4_1814255e-8841-4f24-b65a-a75f92b5737c">আমদানি</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_5_1643ff5d-c7a3-4f56-af1a-d1f8940e7e0c" style=" text-align:center;">২২</div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if(!$standard){ ?>
                                <?=!empty($note1['subtotal'])? number_format($part_four_total[] = $note1['subtotal'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            0.00
                            <?php /*if($standard){ ?>
                                <?=!empty($note1['subvat'])? number_format($part_four_vat[] = $note1['subvat'],2):'0.00'?>
                            <?php }else{
                                echo '0.00';
                            } */?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ক',array('purchaseItems/formkapurchase/','note'=>'twentytwo','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $twentythree_ka = 0;
                    $twentythree_kha = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27" colspan="2">
                            <div class="style_1" id="AUTOGENBOOKMARK_7_0a6cb45b-6eb1-4be4-a19f-f398ceae1ee0">মোট উপকরণ কর রেয়াত</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" id="AUTOGENBOOKMARK_8_c65c8368-1348-4752-98e9-ed2fdd60e18b" style=" text-align:center;">২৩</div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($twentythree_ka = array_sum($part_four_total),2)?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($twentythree_kha = array_sum($part_four_vat),2)?></div>
                        </td>
                        <td class="style_74" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right; page-break-after: always">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_8">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_10_03256a87-8b14-4b4d-9f23-f831b4ca3ce5" style=" text-align:center;">অংশ-৫:বৃদ্ধিকারী সমন্বয় (মূল্য সংযোজন কর)</div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_11_241af341-1c89-44f7-99f6-dadec5ec38ec" style=" text-align:center;">সমন্বয় ঘটনা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_12_001779f7-10d5-4a89-aa55-3017d628ec57" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_13_c957d310-3e9e-444b-9ebe-95216a8de692" style=" text-align:center;">পরিমাণ</div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(vds)) AS subamount FROM vds WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND (vds_type=1 AND approve_count=2) ORDER BY issue_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <?php
                    $note28 = [];
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            সরবরাহ গ্রহীতা কর্তৃক উৎসে কর্তনের জন্য
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;">২৪</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subamount'])? number_format($note28[] = $twentyfour = $note1['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ঘ',array('purchaseItems/formgha/','note'=>'twentyfour','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql25 = "SELECT ROUND(SUM(total_tax)) AS subtax FROM purchase_items WHERE payment_method=133 AND grand_total > 100000 AND issue_date BETWEEN '$start_date' AND '$end_date' ORDER BY issue_date ASC";
                    $note25= Yii::app()->db->createCommand($sql25)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            ব্যাংকিং চ্যানেলে মূল্য পরিশোধিত হয় নাই এমন সরবরাহের জন্য
                        </td>
                        <td class="style_28" align="center">
                            ২৫
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if($standard){
                                echo !empty($note25['subtax'])? number_format($note28[] = $note25['subtax'],2):'0.00';
                            }
                            ?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    $sqlDebit = "SELECT ROUND(SUM(vat_new)) AS subvatnew, ROUND(SUM(sd_new)) AS subsdnew FROM purchase_return WHERE return_date BETWEEN '$start_date' AND '$end_date'";
                    $note26= Yii::app()->db->createCommand($sqlDebit)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            ডেবিট নোটের জন্য
                        </td>
                        <td class="style_28" align="center">
                            ২৬
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note26['subvatnew'])? number_format($note28[] = $note26['subvatnew'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    $sql27 = "SELECT ROUND(SUM(amount)) AS subamount FROM adjustments WHERE nine_one_part = 5 AND payment_date BETWEEN '$start_date' AND '$end_date'";
                    $note27= Yii::app()->db->createCommand($sql27)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="">অন্যকোনো সমন্বয় ঘটনার জন্য (নিচে বর্ণনা করুন)</div>
                            <?php
                            $sql27sub = "SELECT treasury_purpose, ROUND(amount) FROM adjustments WHERE nine_one_part = 5 AND payment_date BETWEEN '$start_date' AND '$end_date'";
                            $note27sub= Yii::app()->db->createCommand($sql27sub)->queryAll();
                            if($note27sub){?>
                                <div style="border: 1px solid #afacac;margin: 1px;padding: 2px 15px;">
                                    <?php
                                    foreach($note27sub as $n27s){
                                        echo TreasuryPurpose::nameOfAdjustmentPurpose($n27s['treasury_purpose']).', ';
                                    }?>
                                </div>
                                <?php
                            }
                            ?>
                            <!--
                           <table class="style_23">
                              <colgroup>
                                 <col>
                              </colgroup>
                              <tbody>
                                 <tr valign="top">
                                    <td class="style_35"></td>
                                 </tr>
                              </tbody>
                           </table>-->
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_22_a24dfa59-58e7-42fa-89da-86541c1a977d" style=" text-align:center;">২৭</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note27['subamount'])? number_format($note28[] = $note27['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    $twentyeight = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27">
                            <div class="style_1" id="AUTOGENBOOKMARK_23_2da94204-257f-4b3d-87a7-1b815333829d">মোট বৃদ্ধিকারী সমন্বয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" id="AUTOGENBOOKMARK_24_2544c9b9-08e5-4696-ae24-96a1419be9b6" style=" text-align:center;">২৮</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note28)? number_format($twentyeight = array_sum($note28),2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_25_c74511cb-b926-4424-9ab4-6883ebd9e49b" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_10">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_26_a3f8bd38-0d7e-4df5-99b2-8b5609c5db5a" style=" text-align:center;">অংশ-৬:হ্রাসকারী সমন্বয় (মূল্য সংযোজন কর)</div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_27_8a217915-870d-490f-8a3a-05798c9bdba3" style=" text-align:center;">সমন্বয় ঘটনা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_28_7a728e9b-9dd5-4978-93bb-5ad707dcc294" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_29_b5c58a4e-31b2-456f-bb3b-2471f4f7c080" style=" text-align:center;">পরিমাণ</div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    $sql1 = "SELECT ROUND(SUM(amount)) AS subamount FROM vds_payment WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND (vds_type=2) ORDER BY payment_date ASC";
                    $note1= Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <?php
                    $note33 = [];
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_30_aaefe59d-4598-4f17-8624-c653c8d590cb">প্রদত্ত সরবরাহ হইতে উৎসে কর্তনের জন্য</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_31_3e14693d-9eb3-465d-a580-9575f346a544" style=" text-align:center;">২৯</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note1['subamount'])? number_format($note33[] = $note1['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-ঙ',array('purchaseItems/formumo/','note'=>'twentynine','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql30 = "SELECT ROUND(SUM(at)) AS subat FROM purchase_details WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND (local_import=55 AND at > 0) ORDER BY issue_date ASC";
                    $note30= Yii::app()->db->createCommand($sql30)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_33_2bdc525e-5d8d-4ee4-b8c8-2616d6585e96">আমদানি পর্যায়ে প্রদেয় আগাম কর</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_34_a3bcfb91-e062-4c37-8ceb-0f043d258090" style=" text-align:center;">৩০</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note30['subat'])? number_format($note33[] = $note30['subat'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম-চ',array('purchaseItems/formco/','note'=>'thirty','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sqlCredit = "SELECT ROUND(SUM(vat_new)) AS subvatnew, ROUND(SUM(sd_new)) AS subsdnew FROM sales_return WHERE return_date BETWEEN '$start_date' AND '$end_date'";
                    $note31= Yii::app()->db->createCommand($sqlCredit)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_38_f1e08b46-2bfe-42a3-9a42-9e63622b02a4">ক্রেডিট নোট ইস্যুর জন্য</div>
                        </td>
                        <td class="style_28" align="center">
                            ৩১
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note31['subvatnew'])? number_format($note33[] = $note31['subvatnew'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    $sql32 = "SELECT ROUND(SUM(amount)) AS subamount FROM adjustments WHERE nine_one_part = 6 AND payment_date BETWEEN '$start_date' AND '$end_date'";
                    $note32= Yii::app()->db->createCommand($sql32)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            অন্যকোনো সমন্বয় ঘটনার জন্য (নিচে বর্ণনা করুন)
                            <?php
                            $sql32sub = "SELECT treasury_purpose, amount FROM adjustments WHERE nine_one_part = 6 AND payment_date BETWEEN '$start_date' AND '$end_date'";
                            $note32sub= Yii::app()->db->createCommand($sql32sub)->queryAll();
                            if($note32sub){?>
                                <div style="border: 1px solid #afacac;margin: 1px;padding: 2px 15px;">
                                    <?php
                                    foreach($note32sub as $n32s){
                                        echo TreasuryPurpose::nameOfAdjustmentPurpose($n32s['treasury_purpose']).', ';
                                    }?>
                                </div>
                                <?php
                            }
                            ?>
                            <!--
                           <table class="style_23">
                              <colgroup>
                                 <col>
                              </colgroup>
                              <tbody>
                                 <tr valign="top">
                                    <td class="style_35"></td>
                                 </tr>
                              </tbody>
                           </table>-->
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_41_5541df89-2365-4355-8dd5-5574c5931a6c" style=" text-align:center;">৩২</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note32['subamount'])? number_format($note33[] = $note32['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    $thirtythree = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27">
                            <div class="style_1" id="AUTOGENBOOKMARK_42_4909166c-5003-412b-820e-bd58ec2c2914">মোট হ্রাসকারী সমন্বয়</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" id="AUTOGENBOOKMARK_43_aee2ce78-3b8b-4fb7-a718-fa60a8ebf9da" style=" text-align:center;">৩৩</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note33)? number_format($thirtythree = array_sum($note33),2):'0.00'?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_44_0330f7b9-53f0-48d7-b684-bd1aa3972757" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_12">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 35%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" id="AUTOGENBOOKMARK_45_cbfad203-3f82-4e39-8f58-39a9439b26fc" style=" text-align:center;">অংশ-৭: নীট কর হিসাব</div>
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_46_e55aadb8-eb02-45f1-8d8a-793190b1143e" style=" text-align:center;">আইটেম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_47_9c3fa1a5-436e-4907-beff-fbc016d2559a" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_48_f0a073ba-7ada-43cd-b0d7-461b02edfb42" style=" text-align:center;">পরিমাণ</div>
                        </td>
                    </tr>
                    <?php
                    $thirtyfour = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            বর্তমান করমেয়াদে প্রদেয় মোট মূসক (সমাপনী জের এর সহিত সমন্বয় ব্যতীত) [ধারা-৪৫]  (৯গ-২৩খ+২৮-৩৩)
                        </td>
                        <td class="style_28" align="center">
                            ৩৪
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($thirtyfour = ($nine_ga - $twentythree_kha + $twentyeight - $thirtythree),2)?>
                        </td>
                    </tr>
                    <?php
                    $thirtyfour_sub = 0;
                    if ($thirtyfour > 0) {
                        $thirtyfour_sub = ($thirtyfour * 10) / 100;
                        if ($thirtyfour_sub < $fiftyfour) {
                            $fiftysix = $thirtyfour_sub;
                        } else {
                            $fiftysix = $fiftyfour;
                        }
                    }
                    ?>
                    <?php
                    $thirtyfive = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            সমাপনী জের এবং মূসক-১৮.৬ হতে প্রাপ্ত জের এর সহিত সমন্বয়ের পর বর্তমান করমেয়াদে প্রদেয় মোট মূসক (৩৪-(৫২+৫৬))
                        </td>
                        <td class="style_28" align="center">
                            ৩৫
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($thirtyfive = ($thirtyfour - ($fiftytwo + $fiftysix)),2)?>
                        </td>
                    </tr>
                    <?php
                    $thirtysix = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            বর্তমান করমেয়াদে প্রদেয় মোট সম্পূরক শুল্ক (সমাপনী জের এর সহিত সমন্বয় ব্যতীত) (৯খ+৩৮-৩৯-৪০)
                        </td>
                        <td class="style_28" align="center">
                            ৩৬
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($thirtysix = $nine_kha + $thirtyeight - $thirtynine - $fourty,2)?>
                        </td>
                    </tr>
                    <?php
                    $thirtysix_sub = 0;
                    if ($thirtysix > 0) {
                        $thirtysix_sub = ($thirtysix * 10) / 100;
                        if ($thirtysix_sub < $fiftyfive) {
                            $fiftyseven = ROUND($thirtysix_sub);
                        } else {
                            $fiftyseven = ROUND($fiftyfive);
                        }
                    }
                    ?>
                    <?php
                    $thirtyseven = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            সমাপনী জের এর সহিত সমন্বয়ের পর বর্তমান করমেয়াদে প্রদেয় মোট সম্পূরক শুল্ক <br> [৩৬-(৫৩+৫৭)]
                        </td>
                        <td class="style_28" align="center">
                            ৩৭
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($thirtyseven = ($thirtysix - ($fiftythree + $fiftyseven)),2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_57_1042079d-b5b5-446e-bdc9-78fd859b4db1">ডেবিট নোট ইস্যুর জন্য সমন্বয়কৃত সম্পূরক শুল্ক</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_58_7808c809-0ca9-45c9-b739-924766c460a7" style=" text-align:center;">৩৮</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note26['subsdnew'])? number_format($thirtyeight = $note26['subsdnew'],2):'0.00'?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_59_207c66d4-605b-4ce7-9d03-054ceacdc48c">ক্রেডিট নোট ইস্যুর জন্য সমন্বয়কৃত সম্পূরক শুল্ক</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_60_dcd3d216-19b1-44f8-92a8-f523a9c7f7ba" style=" text-align:center;">৩৯</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note31['subsdnew'])? number_format($thirtynine = $note31['subsdnew'],2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $sql41 = "SELECT ROUND(SUM(sd)) AS subsd FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND sales_type=2";
                    $note41= Yii::app()->db->createCommand($sql41)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            রপ্তানীপণ্য উৎপাদনে ব্যবহৃত কাঁচামালের বিপরীতে প্রদেয় সম্পূরক শুল্ক
                        </td>
                        <td class="style_28" align="center">
                            ৪০
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note41['subsd'])? number_format($fourty = $note41['subsd'],2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note41 = ROUND(Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 1));
                    $fourtyone = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_63_5081de90-8122-4d05-aae4-4a99c6ca14ec">অপরিশোধিত মূসকের জন্য সুদ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৪১
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note41)? number_format($fourtyone = $note41,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note42 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 2);
                    $fourtytwo = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_65_e28f57fa-894f-4ee2-9db0-0c1c17ea1f96">অপরিশোধিত সম্পূরক শুল্ক এর জন্য সুদ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৪২
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note42)? number_format($fourtytwo = $note42,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note43 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 12);
                    $fourtythree = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            সময়মত দাখিলপত্র জমা না দেওয়ার জন্য অর্থদন্ড ও জরিমানা
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_68_e71415d9-5cf9-4fee-997b-aa4462940559" style=" text-align:center;">৪৩</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note43)? number_format($fourtythree = $note43,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note44 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 3);
                    $fourtyfour = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            অন্যন্য জরিমানা/অর্থদন্ড/সুদ
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_68_e71415d9-5cf9-4fee-997b-aa4462940559" style=" text-align:center;">৪৪</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note44)? number_format($fourtyfour = $note44,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note45 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 4);
                    $fourtyfive = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_69_7e7204f6-ab2a-4bcd-a7c1-fc695f2f9199">আবগারি শুল্ক</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_70_1afd7c67-b532-48de-9864-6f44b18f79f2" style=" text-align:center;">৪৫</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note45)? number_format($fourtyfive = $note45,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note46 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 5);
                    $fourtysix = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_71_0361b44e-9c1f-48cb-b955-5be148f066bc">উন্নয়ন সারচার্জ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_72_6da12caf-e440-4650-b4df-5ca25366f99d" style=" text-align:center;">৪৬</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note46)? number_format($fourtysix = $note46,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note47 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 6);
                    $fourtyseven = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_73_a4852795-657b-46d5-ab28-f6f539e95d16">তথ্য প্রযুক্তি উন্নয়ন সারচার্জ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_74_5715ba05-47d3-443c-a780-5c0a25a40a85" style=" text-align:center;">৪৭</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note47)? number_format($fourtyseven = $note47,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note48 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 7);
                    $fourtyeight = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_75_a6bcb260-9d75-47b6-8bc3-3c8587004956">স্বাস্থ্য সুরক্ষা সারচার্জ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_76_c52e7a5a-d391-4dc2-be46-045ea5a9a679" style=" text-align:center;">৪৮</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note48)? number_format($fourtyeight = $note48,2):'0.00'?>
                        </td>
                    </tr>
                    <?php
                    $note49 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 8);
                    $fourtynine = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_77_2f19438b-3998-433f-9865-1705015ae3b0">পরিবেশ সুরক্ষা সারচার্জ</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_78_8c2a75ce-d1b2-4b83-8f34-5b1b355aa91e" style=" text-align:center;">৪৯</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note49)? number_format($fourtynine = $note49,2):'0.00'?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            ট্রেজারী জমার জন্য নীট প্রদেয় মূল্য সংযোজন কর (৩৫+৪১+৪৩+৪৪)
                        </td>
                        <td class="style_28" align="center">
                            ৫০
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fifty = ($thirtyfive + $fourtyone + $fourtythree + $fourtyfour),2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            ট্রেজারী জমার জন্য নীট প্রদেয় সম্পূরক শুল্ক (৩৭+৪২)
                        </td>
                        <td class="style_28" align="center">
                            ৫১
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftyone = ($thirtyseven + $fourtytwo),2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_79_70010643-9109-4121-aec9-886059779c38">শেষ করমেয়াদের সমাপনী জের (মূল্য সংযোজন কর)</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_80_9359b0bf-d6e0-456f-8f63-70b31155351e" style=" text-align:center;">৫২</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftytwo,2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_81_cc2f2308-908f-4e56-8bc1-1e7f9823b5c0">শেষ করমেয়াদের সমাপনী জের (সম্পূরক শুল্ক)</div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" id="AUTOGENBOOKMARK_82_84d54a61-ee4f-43e2-8490-c2677d332a81" style=" text-align:center;">৫৩</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftythree,2)?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right;page-break-after: always">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px" id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            অংশ-৮ : পূর্বের চলতি হিসাবের জেরের জন্য সমন্বয়
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_36_f2e61320-3a62-4332-b49a-2ca58a3b4ef4" style=" text-align:center;">আইটেম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_37_325c398d-a5f7-4d84-a9b4-81ddf9cd0488" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_38_bbfb3555-5e02-449b-ba1d-3aa2761f7c77" style=" text-align:center;">পরিমাণ</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            মূসক-১৮.৬ হতে অবশিষ্ট জের (মূসক) [বিধি-১১৮(৫)]
                        </td>
                        <td class="style_28" align="center">
                            ৫৪
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftyfour,2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            মূসক-১৮.৬ হতে অবশিষ্ট জের (সম্পূরক শুল্ক) [বিধি-১১৮(৫)]
                        </td>
                        <td class="style_28" align="center">
                            ৫৫
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftyfive,2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            নোট ৫৪ হতে হ্রাসকারী সমন্বয় (নোট ৩৪ এর ১০% পর্যন্ত)
                        </td>
                        <td class="style_28" align="center">
                            ৫৬
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftysix,2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            নোট ৫৫ হতে হ্রাসকারী সমন্বয় (নোট ৩৬ এর ১০% পর্যন্ত)
                        </td>
                        <td class="style_28" align="center">
                            ৫৭
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($fiftyseven,2)?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_13">
                    <colgroup>
                        <col style=" width: 40%;">
                        <col style=" width: 5%;">
                        <col style=" width: 35%;">
                        <col style=" width: 10%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="4" align="center">
                            অংশ-৯ : কর পরিশোধের তফসিল (ট্রেজারি জমা)
                        </th>
                        <th class="style_25" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;">আইটেম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">অর্থনৈতিক কোড</div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">পরিমাণ</div>
                        </td>
                        <td class="style_28" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    $sql52 = "SELECT ROUND(SUM(amount)) AS subamount FROM vat_payment WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND (purpose = 1) ORDER BY payment_date ASC";
                    $note52= Yii::app()->db->createCommand($sql52)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_89_e5efbd0d-9534-475e-acf2-48c66e681201">বর্তমান কর মেয়াদে পরিশোধিত মোট মূসক</div>
                        </td>
                        <td class="style_28" align="center">
                            ৫৮
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_91_ea0ab217-3b58-43c9-b915-a954ca78ef23">১/১১৩৩/০০০০/০৩১১</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note52['subamount'])? number_format($fiftyeight = $note52['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'fiftytwo','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $sql53 = "SELECT SUM(amount) AS subamount FROM vat_payment WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND (purpose = 2) ORDER BY payment_date ASC";
                    $note53= Yii::app()->db->createCommand($sql53)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_93_94799532-347e-4933-97ee-89db50a1b107">বর্তমান করমেয়াদে পরিশোধিত মোট সম্পূরক শুল্ক</div>
                        </td>
                        <td class="style_28" align="center">
                            ৫৯
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_95_5374193d-a4bd-4ba7-b5b1-1fcf4c6f090b">১/১১৩৩/০০০০/০৭১১-০৭২১</div>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note53['subamount'])? number_format($fiftynine = $note53['subamount'],2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'fiftythree','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_15_6c354ddf-c873-49c2-aa6d-24f69477d8f9">আবগারি শুল্ক</div>
                        </td>
                        <td class="style_28" align="center">
                            ৬০
                        </td>
                        <td class="style_28" align="left">
                                <?=Adjustments::getSevenEightSubNoteCode($code_key = 4)?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note45)? number_format($sixty = $note45,2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'fiftyseven','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_19_f12f5e0e-5784-4bf8-9dbb-5f759ab78a9f">উন্নয়ন সারচার্জ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৬১
                        </td>
                        <td class="style_28" align="left">
                            <?=Adjustments::getSevenEightSubNoteCode($code_key = 5)?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note46)? number_format($sixtyone = $note46,2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'fiftyeight','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_23_cfac5433-b7c5-45fb-9510-03a8c04366a0">তথ্য প্রযুক্তি উন্নয়ন সারচার্জ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৬২
                        </td>
                        <td class="style_28" align="left">
                            <?=Adjustments::getSevenEightSubNoteCode($code_key = 6)?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note47)? number_format($sixtytwo = $note47,2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'fiftynine','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_27_d981835d-37a5-4d6b-a8ec-70ccc9cfe047">স্বাস্থ্য সুরক্ষা সারচার্জ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৬৩
                        </td>
                        <td class="style_28" align="left">
                            <?=Adjustments::getSevenEightSubNoteCode($code_key = 7)?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note48)? number_format($sixtythree = $note48,2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'sixty','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_31_18db2e8f-d8d6-4149-b36b-c2da152808b5">পরিবেশ সুরক্ষা সারচার্জ</div>
                        </td>
                        <td class="style_28" align="center">
                            ৬৪
                        </td>
                        <td class="style_28" align="left">
                            <?=Adjustments::getSevenEightSubNoteCode($code_key = 8)?>
                        </td>
                        <td class="style_28" align="right">
                            <?=!empty($note49)? number_format($sixtyfour = $note49,2):'0.00'?>
                        </td>
                        <td class="style_28" align="left">
                            <?php echo CHtml::link('সাবফর্ম - ছ',array('purchaseItems/formcho/','note'=>'sixtyone','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px" id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            অংশ-১০: সমাপনী জের (পরবর্তী কর মেয়াদের প্রারম্ভিক জের)
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_36_f2e61320-3a62-4332-b49a-2ca58a3b4ef4" style=" text-align:center;">আইটেম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_37_325c398d-a5f7-4d84-a9b4-81ddf9cd0488" style=" text-align:center;">নোট</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_38_bbfb3555-5e02-449b-ba1d-3aa2761f7c77" style=" text-align:center;">পরিমাণ</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            সমাপনী জের (মূল্য সংযোজন কর) ৫৮-(৫০+৬৭)
                        </td>
                        <td class="style_28" align="center">
                            ৬৫
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if($fifty < 0)
                                echo '-';
                            ?>
                            <?=number_format(($sixtyfive = $fiftyeight - ($fifty + $sixtyseven)),2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            সমাপনী জের (সম্পূরক শুল্ক) ৫৯-(৫১+৬৮)
                        </td>
                        <td class="style_28" align="center">
                            ৬৬
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format(($sixtysix = $fiftynine - ($fiftyone + $sixtyeight)),2)?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px" id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 40%;">
                        <col style=" width: 35%;">
                        <col style=" width: 5%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="4" align="center">
                            অংশ-১১  : ফেরত
                        </th>
                    </tr>
                    <tr class="style_1" valign="top">
                        <td class="style_27" rowspan="3">
                            আমি সমাপনী জেরে উল্লিখিত অর্থ ফেরত গ্রহনে ইচ্ছুক
                        </td>
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_36_f2e61320-3a62-4332-b49a-2ca58a3b4ef4" style=" text-align:center;font-weight: bold; background-color: #D8E4BC">আইটেম</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_37_325c398d-a5f7-4d84-a9b4-81ddf9cd0488" style=" text-align:center;font-weight: bold; background-color: #D8E4BC">নোট</div>
                        </td>
                        <td class="style_28" align="center" style="font-weight: bold">
                            <?php
                            if($refundstatus==1){
                                echo "হ্যাঁ";
                            }else{
                                echo "না";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_28">
                            দাবীকৃত ফেরতের পরিমাণ (মূসক)
                        </td>
                        <td class="style_28" align="center">
                            ৬৭
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($sixtyseven,2)?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_28">
                            দাবীকৃত ফেরতের পরিমাণ (সম্পূরক শুল্ক)
                        </td>
                        <td class="style_28" align="center">
                            ৬৮
                        </td>
                        <td class="style_28" align="right">
                            <?=number_format($sixtyeight,2)?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_101" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_15">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 35%;">
                        <col style=" width: 35%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            অংশ-১২  : ঘোষণা
                        </th>
                    </tr>
                    <tr class="style_1" valign="top">
                        <td class="style_27" colspan="3">
                            আমি ঘোষণা করিতেছি যে, এই দাখিলপত্রে প্রদত্ত তথ্য সর্বোতভাবে সম্পূর্ন, সত্য ও র্নিভূল । কোন অসম্পূর্ণ বা অসত্য তথ্যেও জন্য মূল্য সংযোজন কর ও সম্পূরক
                            শুল্ক আইন-২০১২ অথবা আপাতত বলবৎ এ সংক্রান্ত যেকোন আইনের আওতায় দন্ডনীয় অপবাধে অপরাধী হব।
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_46_e645de1e-5841-4035-af1e-02a207e14229">নাম</div>
                        </td>
                        <td class="style_28" colspan="2" align="left">
                            <div>
                                <div style="visibility:hidden">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_47_05a4f967-62c4-456c-9b7e-4cc91a9aa47c">পদবী</div>
                        </td>
                        <td class="style_28" align="left">
                            <div>
                                <div style="visibility:hidden">&nbsp;</div>
                            </div>
                        </td>
                        <td class="style_28" rowspan="4" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_48_60fe2190-7142-42e8-aff1-3973be1806c9">মোবাইল নম্বর</div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_49_04828c6a-46e9-45ab-bb36-2e55c9fa8bd9">জাতীয় পরিচয়পত্র নম্বর</div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_50_0b78a189-82b0-43cf-968b-8842afd3bc81">ইমেইল</div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_103" colspan="2"></td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_51_8bdb9490-8ade-4ff5-b574-104fcd196355" style=" text-align:center;">স্বাক্ষর</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

</div>


<style>
    .printAllTableForThisReport{
        background-color: #F9FCFD;
        font-size:11px;
    }
    .reportTabCustom{
        font-size:12px !important;
        background-color:#fff;
        width:8.3in;
        margin:auto;
        font-family: 'times new roman', arial;
    }
    .td_head{background-color: #DAEEF3}
    .tr-sub-head{background-color: #D8E4BC}
    .style_25, .style_27, .style_28, .style_40, .style_31, .style_60, .style_74, .style_103{
        border: 1px solid #000 !important;
    }
    .row_total{
        font-weight: bold;
    }
    .mini-box{
        border: 1px solid;
        width:18px;
        height:16px;
        text-align: center
    }
    .date-tb div{
        border:1px solid #ccc;
        color:#353535;
    }
    .sub-frm-btn{
        border:1px solid #000;
        margin-left: 5px;
        color: #1818EE;
        padding:0px 3px;
    }
    .bold{
        font-weight: bold;
    }
    .reportTabCustom td{
        vertical-align: middle;
    }
</style>