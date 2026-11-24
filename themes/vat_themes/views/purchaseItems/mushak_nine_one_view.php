<?php
echo "<div class='printBtn'>";
$this->widget('ext.mPrint.mPrint', array(
    'title' => ' ', //the title of the document. Defaults to the HTML title
    'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
    'text' => '', //text which will appear beside the print icon. Defaults to NULL
    'element' => '.printAllTableForThisReport', //the element to be printed.
    'exceptions' => array( //the element/s which will be ignored
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

// VAT Calculation Method = From Products/Parts

// ENG CONVERSION
if ($translation_type == 2) { //2=English
    $eng = true;
    $_SESSION["translation_type"] = 2;
} else {
    $eng = false;
    $_SESSION["translation_type"] = '';
}

if ($eng) {
    $ka_tag = $kha_tag = $ga_tag = $gha_tag = $umo_tag = $co_tag = $cho_tag = $sub_form_twenty_seven = $sub_form_thirty_two = 'Sub-form';
} else {
    $ka_tag = 'সাবফর্ম-ক';
    $kha_tag = 'সাবফর্ম-খ';
    $ga_tag = 'সাবফর্ম-গ';
    $gha_tag = 'সাবফর্ম-ঘ';
    $umo_tag = 'সাবফর্ম-ঙ';
    $co_tag = 'সাবফর্ম-চ';
    $cho_tag = 'সাবফর্ম-ছ';
    $sub_form_twenty_seven = 'সাবফর্ম';
    $sub_form_thirty_two = 'সাবফর্ম';
}

// APPROVE COUNT - ENABLE/DISABLE (FOR PREVIEW(ALL) OR EXACT(APPROVED) REPORT)
$approve_txt_one = 'AND approve_count=2';
$approve_txt_two = 'AND pd.approve_count=2';
if ($preview == 1) {
    $approve_txt_one = $approve_txt_two = '';
}

$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD)
    $standard = true;
else
    $standard = false;
?>

<?php
$twentyfour = $thirtyeight = $thirtynine = $fourty = $fourtyone = $fifty = $fiftyone = $fiftysix = $fiftyeight = $fiftynine = $sixty = $sixtyone = 0;
$sixtytwo = $sixtythree = $sixtyfour = $sixtyfive = $sixtysix = $fiftytwo = $fiftythree = $fiftyseven = $fiftyfour = $fiftyfive = $sixtyfive = $sixtysix = $sixtyseven = $sixtyeight = 0;

$this_month = date('Y-m', strtotime($start_date)) . "-01";
$sqlOpening = "SELECT * FROM nine_one WHERE vat_date < '$this_month' ORDER BY id DESC LIMIT 1";
$lastOpening = Yii::app()->db->createCommand($sqlOpening)->queryRow();

if ($lastOpening) {
    $fiftytwo = round($lastOpening['closing_vat']) - round($lastOpening['vat_refund_claim']);
    $fiftythree = round($lastOpening['closing_sd']) - round($lastOpening['sd_refund_claim']);;

    $fiftyfour = round($lastOpening['e_remain_vat'] - $lastOpening['decrease_vat']);
    $fiftyfive = round($lastOpening['e_remain_sd'] - $lastOpening['decrease_sd']);
} else {
    if ($yourCompanyInfo['previous_month_vat'])
        $fiftytwo = round($yourCompanyInfo['previous_month_vat']);
    if ($yourCompanyInfo['previous_month_sd'])
        $fiftythree = round($yourCompanyInfo['previous_month_sd']);


    if ($yourCompanyInfo['opening_vat'])
        $fiftyfour = round($yourCompanyInfo['opening_vat']);
    if ($yourCompanyInfo['opening_sd'])
        $fiftyfive = round($yourCompanyInfo['opening_sd']);
}

$submissionDate = "";
$refundstatus = "";
$doc_type = "";
$last_status = "";
$is_trader_wholesaler = "";
$current_month = date('Y-m', strtotime($start_date));
$sqlRefund = "SELECT * FROM nine_one WHERE vat_date like '$current_month%' ORDER BY id DESC LIMIT 1";
$currentRefund = Yii::app()->db->createCommand($sqlRefund)->queryRow();
if ($currentRefund) {
    $submissionDate = date('d-m-Y', strtotime($currentRefund['submit_date']));
    $sixtyseven = round($currentRefund['vat_refund_claim']);
    $sixtyeight = round($currentRefund['sd_refund_claim']);
    $refundstatus = $currentRefund['refund_status'];
    $last_status = $currentRefund['last_status'];
    $is_trader_wholesaler = $currentRefund['is_trader_wholesaler'];
    $doc_type = $currentRefund['doc_type'];

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
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg"
                     style="width: 80px;position: absolute;"/>
                <table class=""
                       style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <tr valign="top">
                        <td></td>
                        <td style=" overflow:hidden;font-size:12px;width: 55%;">
                            <div class="style_16" style=" text-align:center;font-weight: bold">
                                <?= ($eng) ? 'GOVERNMENT OF THE PEOPLE’S REPUBLIC OF BANGLADESH<br>NATIONAL BOARD OF REVENUE<br><br>' : '“গণপ্রজাতন্ত্রী বাংলাদেশ সরকার”<br>জাতীয় রাজস্ব বোর্ড<br><br>' ?>
                            </div>

                        </td>
                        <td style="padding-top: 12px;text-align: left;">
                                    <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">
                                        <?= ($eng) ? 'Mushok-9.1' : 'মূসক-৯.১' ?>
                                    </span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                            <?= ($eng) ? '<b>VALUE ADDED TAX RETURN FORM<br>[See rule 47(1)]</b>' : '<b> মূল্য সংযোজন কর দাখিলপত্র<br>[বিধি ৪৭ এর উপ-বিধি (১) দ্রষ্টব্য]</b>' ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_1">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-1: TAXPAYER’S INFORMATION' : 'অংশ-১: করদাতার তথ্য' ?>
                            </div>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_16">
                                <?= ($eng) ? '1. BIN' : '(১) ব্যবসায় সনাক্তকরণ সংখ্যা' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= !empty($yourCompanyInfo['vat_regi_no']) ? $yourCompanyInfo['vat_regi_no'] : '' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29">
                                <?= ($eng) ? '2. Name of Taxpayer' : '(২) করদাতার নাম' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? '3. Address of Taxpayer' : '(৩) করদাতার ঠিকানা' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <div>
                                <?= !empty($yourCompanyInfo->house) ? $yourCompanyInfo->house . ', ' : '' ?>
                                <?= !empty($yourCompanyInfo->road) ? $yourCompanyInfo->road . ', ' : '' ?>
                                <?= !empty($yourCompanyInfo->location) ? $yourCompanyInfo->location : '' ?>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? '4. Type of Ownership' : '(৪) ব্যবসার প্রকৃতি' ?>
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
                            <?= ($eng) ? '5. Economic Activity' : '(৫) অর্থনৈতিক কার্যক্রম এর প্রকৃতি' ?>
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
                <div class="style_22" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_2">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_30 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-2: RETURN SUBMISSION DATA' : 'অংশ-২: দাখিলপত্র জমার তথ্য' ?>
                            </div>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? '1. Tax Period' : '(১) কর মেয়াদ' ?>
                            </div>
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                                <tbody>
                                <tr class="style_1" valign="top">
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
                            <div class="style_29">
                                <?= ($eng) ? '2. Type of Return [Please select your desired option]' : '(২) দাখিলপত্রের প্রকার[ অনুগ্রহ করিয়া প্রযোজ্যটিতে টিক দিন ]' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_34"
                                   style="border-collapse: collapse; empty-cells: show; width: 100%;line-height:21px;">
                                <tbody>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px">
                                        <?= ($eng) ? 'A)&nbsp;&nbsp; Main/Original Return (Section 64)' : '(ক)&nbsp;&nbsp; মূল দাখিলপত্র (ধারা ৬৪)' ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($doc_type == 1) {
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px">
                                        <?= ($eng) ? 'B)&nbsp;&nbsp; Late Return (Section 65)' : '(খ)&nbsp;&nbsp; সংশোধিত দাখিলপত্র (ধারা ৬৬)' ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($doc_type == 2) {
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px">
                                        <?= ($eng) ? 'C)&nbsp;&nbsp; Amended Return (Section 66)' : '(গ)&nbsp;&nbsp; পূর্ণ,  অতিরিক্ত বা বিকল্প  দাখিলপত্র (ধারা ৬৭)' ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($doc_type == 3) {
                                            echo "<div style=\"border: 1px solid; width: 55px; text-align: center\">&#10003</div>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px">
                                        <?= ($eng) ? 'D)&nbsp;&nbsp; Full or Additional or Alternative Return (Section 67)' : '' ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($doc_type == 3) {
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
                            <div class="style_29">
                                <?=($eng)? '2a. Economic Activity is Trader or approved wholesaler and want to pay VAT at the rate of 5 % or 1.5 %.[If Selected "Yes" Please Fill up the simplified version of Mushak-9.1] [If " No " is selected , please fill up the full Mushak - 9.1 form ]':'২ক. অর্থনৈতিক কার্যকলাপ হল ব্যবসায়ী বা অনুমোদিত পাইকার এবং ৫% বা ১.৫% হারে ভ্যাট দিতে চান। পূর্ণ মুশাক - ৯.১ রূপ ]'?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_34" style="border-collapse: collapse; empty-cells: show; width: 100%;line-height:21px;">
                                <tbody>
                                <tr valign="top">
                                    <td style=" width: 60%;padding-left: 20px; text-align: center;">
                                        <div style="width: 300px; margin:auto">
                                            <div style="height: 23px; width: 30px; border: 1px solid black; float:left;">
                                                Yes
                                            </div>
                                            <div style="height: 23px; width: 30px; border: 1px solid black; float:left; border-left: none">
                                                <?php echo $is_trader_wholesaler == 1 ? "&check;" : ""; ?>
                                            </div>
                                            <div style="margin-left: 50px; height: 23px; width: 30px; border: 1px solid black; float:left;">
                                                No
                                            </div>
                                            <div style="height: 23px; width: 30px; border: 1px solid black; float:left; border-left: none">
                                                <?php echo $is_trader_wholesaler == 2 ? "&check;" : ""; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29">
                                <?= ($eng) ? '3. Any activities in this Tax Period?' : '(৩) বিগত কর মেয়াদে কোনো কার্যক্রম সম্পাদিত হইয়াছে কি?' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_34" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                                   id="__bookmark_4">
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
                                        <div style="border: 1px solid; width: 55px; text-align: center">
                                            
                                            <?php
                                                if ($last_status == 1) {
                                                    echo ($eng) ? 'Yes':'হ্যাঁ';
                                                    echo '&nbsp;'; 
                                                } else if ($last_status == 2) {
                                                    echo ($eng) ? 'No':'না';
                                                    echo '&nbsp;'; 
                                                }
                                            ?>

                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div style=" text-align:center;">
                                        
                                <?php
                                    if($eng){
                                        echo '[If Selected “No” Please Fill Only the relevant Part]';
                                    }else{
                                        echo '[যদি “না” হয় তাহা হইলে অংশ-১,৩ এবং ১১ পূরণ করুন]';
                                    
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? '4. Date of Submission' : '(৪) পেশের তারিখ' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">:</div>
                        </td>
                        <td class="style_28" align="left">
                            <table class="style_23 date-tb"
                                   style="border-collapse: collapse; empty-cells: show; width: 100%;" id="">
                                <tbody>
                                <tr class="style_1" valign="top">
                                    <th class="style_38" style="text-align: center">
                                        <div style=" text-align:center;"><?= substr($submissionDate, 0, 1) ?></div>
                                    </th>
                                    <th align="left">
                                        <div style=" text-align:center;"><?= substr($submissionDate, 1, 1) ?></div>
                                    </th>
                                    <th style="text-align: center">
                                        /
                                    </th>
                                    <th class="style_39" style="text-align: center">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 3, 1) ?></div>
                                    </th>
                                    <th class="style_39" style="text-align: center">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 4, 1) ?></div>
                                    </th>
                                    <th style="text-align: center">
                                        /
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 6, 1) ?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 7, 1) ?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 8, 1) ?></div>
                                    </th>
                                    <th class="style_39" align="left">
                                        <div class="style_32"
                                             style=" text-align:center;"><?= substr($submissionDate, 9, 1) ?></div>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_6">
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
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-3: SUPPLY - OUTPUT TAX' : 'অংশ-৩: সরবরাহ প্রদান - প্রদেয় কর' ?>
                            </div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27" colspan="2">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'Nature of Supply:' : 'সরবরাহের প্রকৃতি' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'Note' : 'নোট' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'Value (a)' : 'মূল্য (ক)' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'SD (b)' : 'এসডি (খ)' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'VAT (c)' : 'মূসক (গ)' ?>
                            </div>
                        </td>
                        <td class="style_40" align="center">
                            <?= ($eng) ? 'Sub-form' : 'সাব ফর্ম' ?>
                        </td>
                    </tr>
                    <?php
                    // Sales Type = 2(Export), Export Type = 2(Direct)


                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND ((sales_type=2 AND export_type=2 )) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div class="style_16">
                                <?= ($eng) ? 'Zero Rated<br>Goods/Service' : 'শূন্যহার বিশিষ্ট পণ্য/সেবা' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div>
                                <?= ($eng) ? 'Direct Export' : 'সরাসরি রপ্তানি' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '1' : '১' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'one', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Sales Type = 2(Export), Export Type = 1(Deemed)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND ((sales_type=2 AND export_type=1)) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_28" align="left">
                            <div class="style_29">
                                <?= ($eng) ? 'Deemed Export' : 'প্রচ্ছন্ন রপ্তানি' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '2' : '২' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'two', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 4(Exempted) OR (VAT Calculation Method = 5(Zero) AND Sales Type = 1(Local))

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND ((vat_calculation_method = 4) OR (vat_calculation_method = 5 AND sales_type=1)) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Exempted Goods/Service' : 'অব্যাহতিপ্রাপ্ত পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '3' : '৩' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php echo !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'three', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 1(NBR-15%)  AND Sales Type = 1(Local)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND (vat_calculation_method=1) AND sales_type=1 ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Standard Rated Goods/Service' : 'আদর্শ হারের পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '4' : '৪' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'four', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 6(MRP)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND (vat_calculation_method=6) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Goods Based on MRP' : 'সর্বোচ্চ খুচরা মূল্যভিত্তিক পণ্য' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '5' : '৫' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'five', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 3(Fixed VAT)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND (vat_calculation_method = 3) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Goods/Service Based on Specific VAT' : 'সুনির্দিষ্ট কর ভিত্তিক পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '6' : '৬' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ga_tag, array('purchaseItems/formga/', 'note' => 'six', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 2(Reducing Method %), Business Process Not 3(Manufacturing/Service)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND (vat_calculation_method=2) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Goods/Service Other than Standard Rate' : 'আদর্শ করহার ব্যতীত ভিন্ন করহার ভিত্তিক পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '7' : '৭' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($yourCompanyInfo->business_process != 3) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else { ?>
                                0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($yourCompanyInfo->business_process != 3) { ?>
                                <?= !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                            <?php } else { ?>
                                0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($yourCompanyInfo->business_process != 3) { ?>
                                <?= !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                            <?php } else { ?>
                                0.00
                            <?php } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formka/', 'note' => 'seven', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 2(Reducing Method %), Business Process = 3(Trading)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat FROM sales_details 
                                WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND (vat_calculation_method=2 $approve_txt_one) ORDER BY sales_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" colspan="2">
                            <div><?= ($eng) ? 'Retail/Wholesale/Trade Based Supply' : 'খুচরা/পাইকারি/ব্যবসায়ী ভিত্তিক সরবরাহ' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '8' : '৮' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if ($yourCompanyInfo->business_process == 3) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_three_total[] = $note1['subtotal'], 2) : '0.00' ?>
                                <?php
                            } else {
                                echo '0.00';
                            }
                            ?>

                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if ($yourCompanyInfo->business_process == 3) { ?>
                                <?= !empty($note1['subsd']) ? number_format($part_three_sd[] = $note1['subsd'], 2) : '0.00' ?>
                                <?php
                            } else {
                                echo '0.00';
                            }
                            ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if ($yourCompanyInfo->business_process == 3) { ?>
                                <?= !empty($note1['subvat']) ? number_format($part_three_vat[] = $note1['subvat'], 2) : '0.00' ?>
                                <?php
                            } else {
                                echo '0.00';
                            }
                            ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($kha_tag, array('purchaseItems/formkha/', 'note' => 'eight', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $nine_ka = $nine_kha = $nine_ga = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27" colspan="2">
                            <div class="style_56">
                                <?= ($eng) ? 'Total Sales Value & Total Payable Taxes' : 'মোট বিক্রয়মূল্য ও মোট প্রদেয় কর' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" style=" text-align:center;"><?= ($eng) ? '9' : '৯' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_ka = array_sum($part_three_total), 2) ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_kha = array_sum($part_three_sd), 2) ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($nine_ga = array_sum($part_three_vat), 2) ?></div>
                        </td>
                        <td class="style_60" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_68_895d9ecf-26da-4ac8-b634-cff81afbb3fb"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_7">
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
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-4: PURCHASE - INPUT TAX' : 'অংশ-৪: ক্রয় - উপকরণ কর' ?>
                            </div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <?php if ($eng) { ?>
                        <tr valign="top">
                            <td class="style_27" colspan="6" style="font-weight: bold; padding: 0 5px 0 5px">
                                1) If all the products/services you supply are standard rated, fill up note 10-20.<br>
                                2) All the products/services you supply are not standard rated or input tax credit not
                                taken within
                                stipulated time period under section 46, fill up note 21-22.<br>
                                3) If the products/services you supply consist of both standard rated and non-standard
                                rated, then fill
                                up note 10-20 for the raw materials that were used to produce/supply standard rated
                                goods/services
                                and fill up note 21-22 for the raw materials that were used to produce/supply
                                non-standard rated
                                goods/services and show the value proportionately in note 10-22 as applicable.
                            </td>
                        </tr>
                    <?php } ?>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27" colspan="2">
                            <div style=" text-align:center;">
                                <?= ($eng) ? 'Nature of Purchase' : 'ক্রয়ের প্রকৃতি' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Value (a)' : 'মূল্য (ক)' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'VAT (b)' : 'মূসক (খ)' ?></div>
                        </td>
                        <td class="style_40" align="center"><?= ($eng) ? 'Sub-form' : 'সাব ফর্ম' ?></td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 5(Zero), Local=54, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 5 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div><?= ($eng) ? 'Zero Rated Goods/Service' : 'শুন্যহার বিশিষ্ট পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '10' : '১০' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'ten', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 5(Zero), Import=55, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 5 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_28" align="left">
                            <div class="style_29"><?= ($eng) ? 'Import' : 'আমদানি' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '11' : '১১' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'eleven', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 4(Exempted), Local=54, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 4 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div><?= ($eng) ? 'Exempted Goods/Service' : 'অব্যাহতিপ্রাপ্ত পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_27">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '12' : '১২' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'twelve', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 4(Exempted), Import=55, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 4 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29"><?= ($eng) ? 'Import' : 'আমদানি' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '13' : '১৩' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'thirteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 1(NBR-15%), Local=54, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate, 
                             SUM(CASE WHEN rebate > 0 THEN total  ELSE 0 END) AS subtotal_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN sd  ELSE 0 END)  AS subsd_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN vat ELSE 0 END)  AS subvat_with_rebate
                             FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                             WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 1 AND pd.local_import=54 AND si.supplier_type_id = 1) 
                             ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div><?= ($eng) ? 'Standard Rated<br>Goods/Service' : 'আদর্শ হারের পণ্য/সেবা' ?></div>
                        </td>
                        <td class="style_27">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '14' : '১৪' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal_with_rebate']) ? number_format($part_four_total[] = $note1['subtotal_with_rebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'fourteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 1(NBR-15%), Import=55, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate, ROUND(SUM(cd)) AS subcd, ROUND(SUM(rd)) AS subrd, 
                             SUM(CASE WHEN rebate > 0 THEN total  ELSE 0 END) AS subtotal_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN cd  ELSE 0 END) AS subcd_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN rd  ELSE 0 END) AS subrd_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN sd  ELSE 0 END)  AS subsd_with_rebate,
                             SUM(CASE WHEN rebate > 0 THEN vat ELSE 0 END)  AS subvat_with_rebate
                             FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                             WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 1 AND pd.local_import=55 AND si.supplier_type_id = 1) 
                             ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29"><?= ($eng) ? 'Import' : 'আমদানি' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '15' : '১৫' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal_with_rebate']) ? number_format($part_four_total[] = $note1['subtotal_with_rebate'] + $note1['subcd_with_rebate'] + $note1['subrd_with_rebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'fifteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 2(Reducing Method), Local=54, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(pd.total)) AS subtotal, ROUND(SUM(pd.sd)) AS subsd, ROUND(SUM(pd.vat)) AS subvat, ROUND(SUM(pd.rebate)) AS subrebate FROM purchase_details AS pd 
                    INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                    INNER JOIN purchase_items as pi ON pi.id = pd.purchase_id 
                    WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND pd.rebate > 0 AND (pd.vat_calculation_method = 2 AND pd.local_import=54 AND si.supplier_type_id = 1) AND (pi.total_vds = 0 OR pi.total_vds IS NULL) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div>
                                <?= ($eng) ? 'Goods/Service Other than<br>Standard Rate' : 'আদর্শ হার ব্যতীত অন্যান্য হারের পণ্য/সেবা' ?>
                            </div>
                        </td>
                        <td class="style_27">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '16' : '১৬' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'sixteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 2(Reducing Method), Import=55, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate, ROUND(SUM(cd)) AS subcd, ROUND(SUM(rd)) AS subrd FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 2 AND pd.local_import=55 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29"><?= ($eng) ? 'Import' : 'আমদানি' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '17' : '১৭' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'] + $note1['subcd'] + $note1['subrd'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'seventeen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // VAT Calculation Method = 3(Fixed VAT), Local=54, Supplier Type = 1(Registered)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.vat_calculation_method = 3 AND pd.local_import=54 AND si.supplier_type_id = 1) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29">
                                <?= ($eng) ? 'Goods/Service Based on<br>Specific VAT' : 'সুনির্দিষ্ট কর ভিত্তিক পণ্য/সেবা' ?>
                            </div>
                        </td>
                        <td class="style_27">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '18' : '১৮' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ga_tag, array('purchaseItems/formga/', 'note' => 'eighteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Supplier Type = 3(Turnover)

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, ROUND(SUM(rebate)) AS subrebate FROM purchase_details AS pd INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                                WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (si.supplier_type_id = 3) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div>
                                <?= ($eng) ? 'Goods/Service Not<br>Admissible for Credit (Local<br>Purchase)' : 'রেয়াতযোগ্য নয় এরূপ পণ্য/সেবা(স্থানীয় ক্রয়)' ?>
                            </div>
                        </td>
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'From Turnover Tax<br>Units' : 'টার্ণওভার প্রতিষ্ঠান হইতে' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '19' : '১৯' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'nineteen', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Supplier Type = 2(Un registered)

                    $sql1 = "SELECT ROUND(SUM(pd.total)) AS subtotal, ROUND(SUM(pd.sd)) AS subsd, ROUND(SUM(pd.vat)) AS subvat, ROUND(SUM(pd.rebate)) AS subrebate FROM purchase_details AS pd 
                    INNER JOIN suppliers AS si ON pd.supplier_id = si.id 
                    INNER JOIN purchase_items pi on pd.purchase_id = pi.id
                    WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (si.supplier_type_id = 2) AND (pi.total_vds = 0 OR pi.total_vds IS NULL) ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29">
                                <?= ($eng) ? 'From Unregistered<br>Entities' : 'অনিবন্ধিত প্রতিষ্ঠান হইতে' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '20' : '২০' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subtotal']) ? number_format($part_four_total[] = $note1['subtotal'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php if ($standard) { ?>
                                <?= !empty($note1['subrebate']) ? number_format($part_four_vat[] = $note1['subrebate'], 2) : '0.00' ?>
                            <?php } else {
                                echo '0.00';
                            } ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'twenty', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Local = 54

                    $sql1 = "SELECT ROUND(SUM(pd.total)) AS subtotal, ROUND(SUM(pd.sd)) AS subsd, ROUND(SUM(pd.vat)) AS subvat, 
                             SUM(CASE WHEN pd.rebate = 0 THEN pd.total  ELSE 0 END) AS subtotal_without_rebate,
                             SUM(CASE WHEN pd.rebate = 0 THEN pd.sd  ELSE 0 END)  AS subsd_without_rebate,
                             SUM(CASE WHEN pd.rebate = 0 THEN pd.vat ELSE 0 END)  AS subvat_without_rebate
                             FROM purchase_details pd
                            INNER JOIN purchase_items pi on pd.purchase_id = pi.id
                            WHERE pd.effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_two AND (pd.local_import=54)  AND (pi.total_vds = 0 OR pi.total_vds IS NULL)  ORDER BY pd.effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27" rowspan="2">
                            <div>
                                <?= ($eng) ? 'Goods/Service Not
                                    Admissible for Credit
                                    (Taxpayers who sell only
                                    Exempted/Specific VAT and
                                    Goods/Service Other than
                                    Standard Rate/ Credits not
                                    taken within stipulated time)' : 'রেয়াতযোগ্য নয় এরূপ পণ্য/সেবা(যেসকল করদাতা শুধুমাত্র অব্যাহতিপ্রাপ্ত/
                                    সুনির্দিষ্ট কর/আদর্শ হার ব্যতীত অন্যান্য হারের পন্য/সেবা সরবরাহ করেন)' ?>
                            </div>
                        </td>
                        <td class="style_27">
                            <div><?= ($eng) ? 'Local Purchase' : 'স্থানীয় ক্রয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style="text-align:center;"><?= ($eng) ? '21' : '২১' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            //if (!$standard) {
                            ?>
                            <?= !empty($note1['subtotal_without_rebate']) ? number_format($part_four_total[] = $note1['subtotal_without_rebate'], 2) : '0.00' ?>
                            <?php
                            /*} else {
                                echo '0.00';
                            } */
                            ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            //                            if (!$standard) {
                            ?>
                            <?= !empty($note1['subvat']) ? number_format($part_four_vat[] = 0, 2) : '0.00' ?>
                            <?php
                            /* } else {
                                 echo '0.00';
                             } */
                            ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'twentyone', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Import = 55

                    $sql1 = "SELECT ROUND(SUM(total)) AS subtotal, ROUND(SUM(sd)) AS subsd, ROUND(SUM(vat)) AS subvat, 
                             SUM(CASE WHEN rebate = 0 THEN total  ELSE 0 END) AS subtotal_without_rebate,
                             SUM(CASE WHEN rebate = 0 THEN sd  ELSE 0 END)  AS subsd_without_rebate,
                             SUM(CASE WHEN rebate = 0 THEN vat ELSE 0 END)  AS subvat_without_rebate
                             FROM purchase_details 
                             WHERE effective_date BETWEEN '$start_date' AND '$end_date' $approve_txt_one AND (local_import=55) ORDER BY effective_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29"><?= ($eng) ? 'Import' : 'আমদানি' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '22' : '২২' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            //                            if (!$standard) {
                            ?>
                            <?= !empty($note1['subtotal_without_rebate']) ? number_format($part_four_total[] = $note1['subtotal_without_rebate'], 2) : '0.00' ?>
                            <?php
                            /*} else {
                                echo '0.00';
                            } */
                            ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            //                            if (!$standard) {
                            ?>
                            <?= !empty($note1['subvat']) ? number_format($part_four_vat[] = 0, 2) : '0.00' ?>
                            <?php
                            /* } else {
                                 echo '0.00';
                             }*/
                            ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($ka_tag, array('purchaseItems/formkapurchase/', 'note' => 'twentytwo', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $twentythree_ka = 0;
                    $twentythree_kha = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27" colspan="2">
                            <div class="style_1"><?= ($eng) ? 'Total Input Tax Credit' : 'মোট উপকরণ কর রেয়াত' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" style=" text-align:center;"><?= ($eng) ? '23' : '২৩' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($twentythree_ka = array_sum($part_four_total), 2) ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:right;"><?= number_format($twentythree_kha = array_sum($part_four_vat), 2) ?></div>
                        </td>
                        <td class="style_74" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right; page-break-after: always">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_8">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-5: INCREASING ADJUSTMENTS (VAT)' : 'অংশ-৫:বৃদ্ধিকারী সমন্বয় (মূল্য সংযোজন কর)' ?>
                            </div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Adjustment Details' : 'সমন্বয় ঘটনা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'VAT Amount' : 'পরিমাণ' ?></div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    // VDS Type = 1(Purchase)
                    $sql1 = "SELECT ROUND(SUM(vds)) AS subamount FROM vds INNER JOIN purchase_items ON purchase_items.id = vds.purchase_id WHERE purchase_items.effective_date BETWEEN '$start_date' AND '$end_date' AND (vds_type = 1 AND vds.approve_count = 2)";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <?php
                    $note28 = [];
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Due to VAT Deducted at Source by the supply receiver' : 'সরবরাহ গ্রহীতা কর্তৃক উৎসে কর্তনের জন্য' ?>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '24' : '২৪' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subamount']) ? number_format($note28[] = $twentyfour = $note1['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($gha_tag, array('purchaseItems/formgha/', 'note' => 'twentyfour', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Payment Method = 133(Cash), Grand Total > 100000

                    $sql25 = "SELECT ROUND(SUM(total_tax)) AS subtax FROM purchase_items WHERE payment_method=133 AND grand_total > 100000 AND effective_date BETWEEN '$start_date' AND '$end_date' ORDER BY effective_date ASC";
                    $note25 = Yii::app()->db->createCommand($sql25)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Payment Not Made Through Banking Channel' : 'ব্যাংকিং চ্যানেলে মূল্য পরিশোধিত হয় নাই এমন সরবরাহের জন্য' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '25' : '২৫' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            if ($standard) {
                                echo !empty($note25['subtax']) ? number_format($note28[] = $note25['subtax'], 2) : '0.00';
                            }
                            ?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    // Purchase Return (Debit)

                    $sqlDebit = "SELECT ROUND(SUM(vat_new)) AS subvatnew, ROUND(SUM(sd_new)) AS subsdnew FROM purchase_return WHERE return_date BETWEEN '$start_date' AND '$end_date'";
                    $note26 = Yii::app()->db->createCommand($sqlDebit)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Issuance of Debit Note' : 'ডেবিট নোটের জন্য' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '26' : '২৬' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note26['subvatnew']) ? number_format($note28[] = $note26['subvatnew'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    // Nine One Part = 5 (অংশ-৫:বৃদ্ধিকারী সমন্বয়)

                    $sql27 = "SELECT ROUND(SUM(amount)) AS subamount FROM adjustments WHERE nine_one_part = 5 AND effective_date BETWEEN '$start_date' AND '$end_date'";
                    $note27 = Yii::app()->db->createCommand($sql27)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'Any Other Adjustments (please specify below)' : 'অন্যকোনো সমন্বয় ঘটনার জন্য (নিচে বর্ণনা করুন)' ?>
                            </div>
                            <?php
                            $sql27sub = "SELECT treasury_purpose, ROUND(amount) FROM adjustments WHERE nine_one_part = 5 AND effective_date BETWEEN '$start_date' AND '$end_date'";
                            $note27sub = Yii::app()->db->createCommand($sql27sub)->queryAll();
                            if ($note27sub) { ?>
                                <div style="border: 1px solid #afacac;margin: 1px;padding: 2px 15px;">
                                    <?php
                                    foreach ($note27sub as $n27s) {
                                        echo TreasuryPurpose::nameOfAdjustmentPurpose($n27s['treasury_purpose']) . '<br> ';
                                    } ?>
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
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '27' : '২৭' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note27['subamount']) ? number_format($note28[] = $note27['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="left">
                             <?php echo CHtml::link($sub_form_twenty_seven,array('purchaseItems/subFormTwentySeven/','note'=>'twenty seven','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>

                    <?php
                    $twentyeight = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27">
                            <div class="style_1"><?= ($eng) ? 'Total Increasing Adjustment' : 'মোট বৃদ্ধিকারী সমন্বয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" style=" text-align:center;"><?= ($eng) ? '28' : '২৮' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note28) ? number_format($twentyeight = array_sum($note28), 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_25_c74511cb-b926-4424-9ab4-6883ebd9e49b"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_10">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                        <col style=" width: 10%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26" style=" text-align:center;">
                                <?= ($eng) ? 'Part-6: DECREASING ADJUSTMENTS (VAT)' : 'অংশ-৬:হ্রাসকারী সমন্বয় (মূল্য সংযোজন কর)' ?>
                            </div>
                        </th>
                        <th class="style_31" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Adjustment Details' : 'সমন্বয় ঘটনা' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'VAT Amount' : 'পরিমাণ' ?></div>
                        </td>
                        <td class="style_40" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    // VDS Type = 2(Sales)

                    $sql1 = "SELECT ROUND(SUM(amount)) AS subamount FROM vds_payment WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND (vds_type=2) ORDER BY payment_date ASC";
                    $note1 = Yii::app()->db->createCommand($sql1)->queryRow();
                    ?>
                    <?php
                    $note33 = [];
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Due to VAT Deducted at Source from the supplies delivered' : 'প্রদত্ত সরবরাহ হইতে উৎসে কর্তনের জন্য' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '29' : '২৯' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note1['subamount']) ? number_format($note33[] = $note1['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($umo_tag, array('purchaseItems/formumo/', 'note' => 'twentynine', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Import = 55, AT(Purchase Import) > 0

                    $sql30 = "SELECT ROUND(SUM(at)) AS subat FROM purchase_details WHERE effective_date BETWEEN '$start_date' AND '$end_date' AND (local_import=55 AND at > 0) ORDER BY effective_date ASC";
                    $note30 = Yii::app()->db->createCommand($sql30)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Advance Tax Paid at Import Stage' : 'আমদানি পর্যায়ে প্রদেয় আগাম কর' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '30' : '৩০' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note30['subat']) ? number_format($note33[] = $note30['subat'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($co_tag, array('purchaseItems/formco/', 'note' => 'thirty', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Sales Return (Credit)

                    $sqlCredit = "SELECT ROUND(SUM(vat_new)) AS subvatnew, ROUND(SUM(sd_new)) AS subsdnew FROM sales_return WHERE return_date BETWEEN '$start_date' AND '$end_date'";
                    $note31 = Yii::app()->db->createCommand($sqlCredit)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Issuance of Credit Note' : 'ক্রেডিট নোট ইস্যুর জন্য' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '31' : '৩১' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note31['subvatnew']) ? number_format($note33[] = $note31['subvatnew'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <?php
                    // Nine One Part = 6 (অংশ-৬:হ্রাসকারী সমন্বয়)

                    $sql32 = "SELECT ROUND(SUM(amount)) AS subamount FROM adjustments WHERE nine_one_part = 6 AND effective_date BETWEEN '$start_date' AND '$end_date'";
                    $note32 = Yii::app()->db->createCommand($sql32)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Any Other Adjustments (please specify below)' : 'অন্যকোনো সমন্বয় ঘটনার জন্য (নিচে বর্ণনা করুন)' ?>
                            <?php
                            $sql32sub = "SELECT treasury_purpose, amount FROM adjustments WHERE nine_one_part = 6 AND effective_date BETWEEN '$start_date' AND '$end_date'";
                            $note32sub = Yii::app()->db->createCommand($sql32sub)->queryAll();
                            if ($note32sub) { ?>
                                <div style="border: 1px solid #afacac;margin: 1px;padding: 2px 15px;">
                                    <?php
                                    foreach ($note32sub as $n32s) {
                                        echo TreasuryPurpose::nameOfAdjustmentPurpose($n32s['treasury_purpose']) . '<br>';
                                    } ?>
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
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '32' : '৩২' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note32['subamount']) ? number_format($note33[] = $note32['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($sub_form_thirty_two,array('purchaseItems/subFormThirtyTwo/','note'=>'thirty two','start_date'=>$start_date,'end_date'=>$end_date),['class'=>'btn sub-frm-btn', 'target'=>'_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    $thirtythree = 0;
                    ?>
                    <tr valign="top" class="row_total">
                        <td class="style_27">
                            <div class="style_1"><?= ($eng) ? 'Total Decreasing Adjustment' : 'মোট হ্রাসকারী সমন্বয়' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_5" style=" text-align:center;"><?= ($eng) ? '33' : '৩৩' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note33) ? number_format($thirtythree = array_sum($note33), 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_44_0330f7b9-53f0-48d7-b684-bd1aa3972757"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_12">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 35%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="left">
                            <div class="style_26"
                                 style=" text-align:center;"><?= ($eng) ? 'Part-7: NET TAX CALCULATION' : 'অংশ-৭: নীট কর হিসাব' ?></div>
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Items' : 'আইটেম' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Amount' : 'পরিমাণ' ?></div>
                        </td>
                    </tr>
                    <?php
                    $thirtyfour = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Net Payable VAT for the Tax Period (Section- 45)
                        ( 9C-23B+28-33)' : 'বর্তমান করমেয়াদে প্রদেয় মোট মূসক (সমাপনী জের এর সহিত সমন্বয় ব্যতীত) [ধারা-৪৫]  (৯গ-২৩খ+২৮-৩৩)' ?>

                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '34' : '৩৪' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($thirtyfour = ($nine_ga - $twentythree_kha + $twentyeight - $thirtythree)), 2) ?>
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
                            <?= ($eng) ? 'Net Payable VAT for the Tax Period after Adjustment with
                        Closing Balance and balance of form 18.6 [34-(52+56)]' : 'সমাপনী জের এবং মূসক-১৮.৬ হতে প্রাপ্ত জের এর সহিত সমন্বয়ের পর বর্তমান করমেয়াদে প্রদেয় মোট মূসক (৩৪-(৫২+৫৬))' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '35' : '৩৫' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($thirtyfive = ($thirtyfour - ($fiftytwo + $fiftysix))), 2) ?>
                        </td>
                    </tr>
                    <?php
                    $thirtysix = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Net Payable Supplementary Duty for the Tax Period (Before
                        adjustment with Closing Balance) [9B+38-(39+40)]' : 'বর্তমান করমেয়াদে প্রদেয় মোট সম্পূরক শুল্ক (সমাপনী জের এর সহিত সমন্বয় ব্যতীত) (৯খ+৩৮-৩৯-৪০)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '36' : '৩৬' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($thirtysix = $nine_kha + $thirtyeight - $thirtynine - $fourty), 2) ?>
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
                            <?= ($eng) ? 'Net Payable Supplementary Duty for the Tax Period after
                        Adjusted with Closing Balance and balance of form 18.6
                        [36-(53+57)]' : 'সমাপনী জের এর সহিত সমন্বয়ের পর বর্তমান করমেয়াদে প্রদেয় মোট সম্পূরক শুল্ক <br> [৩৬-(৫৩+৫৭)]' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '37' : '৩৭' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($thirtyseven = ($thirtysix - ($fiftythree + $fiftyseven))), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'Supplementary Duty Against Issuance of Debit Note' : 'ডেবিট নোট ইস্যুর জন্য সমন্বয়কৃত সম্পূরক শুল্ক' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '38' : '৩৮' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note26['subsdnew']) ? number_format($thirtyeight = $note26['subsdnew'], 2) : '0.00' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'Supplementary Duty Against Issuance of Credit Note' : 'ক্রেডিট নোট ইস্যুর জন্য সমন্বয়কৃত সম্পূরক শুল্ক' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '39' : '৩৯' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note31['subsdnew']) ? number_format($thirtynine = $note31['subsdnew'], 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    // Sales Type = 2(Export), Only SD value in here

                    $sql41 = "SELECT ROUND(SUM(sd)) AS subsd FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND sales_type=2";
                    $note41 = Yii::app()->db->createCommand($sql41)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Supplementary Duty Paid on Inputs Against Exports' : 'রপ্তানীপণ্য উৎপাদনে ব্যবহৃত কাঁচামালের বিপরীতে প্রদেয় সম্পূরক শুল্ক' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '40' : '৪০' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note41['subsd']) ? number_format($fourty = $note41['subsd'], 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    // Note 41-49 are coming from Adjustment Table by Code Key

                    $note41 = ROUND(Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 1));
                    $fourtyone = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Interest on Overdue VAT (Based on note 35)' : 'অপরিশোধিত মূসকের জন্য সুদ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '41' : '৪১' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note41) ? number_format($fourtyone = $note41, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note42 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 2);
                    $fourtytwo = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Interest on Overdue SD (Based on note 37)' : 'অপরিশোধিত সম্পূরক শুল্ক এর জন্য সুদ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '42' : '৪২' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note42) ? number_format($fourtytwo = $note42, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note43 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 12);
                    $fourtythree = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Fine/Penalty for Non-submission of Return' : 'সময়মত দাখিলপত্র জমা না দেওয়ার জন্য অর্থদন্ড ও জরিমানা' ?>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '43' : '৪৩' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note43) ? number_format($fourtythree = $note43, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note44 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 3);
                    $fourtyfour = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Other Fine/Penalty/Interest' : 'অন্যন্য জরিমানা/অর্থদন্ড/সুদ' ?>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '44' : '৪৪' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note44) ? number_format($fourtyfour = $note44, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note45 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 4);
                    $fourtyfive = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Payable Excise Duty' : 'আবগারি শুল্ক' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '45' : '৪৫' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note45) ? number_format($fourtyfive = $note45, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note46 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 5);
                    $fourtysix = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Payable Development Surcharge' : 'উন্নয়ন সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '46' : '৪৬' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note46) ? number_format($fourtysix = $note46, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note47 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 6);
                    $fourtyseven = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Payable ICT Development Surcharge' : 'তথ্য প্রযুক্তি উন্নয়ন সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '47' : '৪৭' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note47) ? number_format($fourtyseven = $note47, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note48 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 7);
                    $fourtyeight = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Payable Health Care Surcharge' : 'স্বাস্থ্য সুরক্ষা সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '48' : '৪৮' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note48) ? number_format($fourtyeight = $note48, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <?php
                    $note49 = Adjustments::getSevenEightSubNoteAmount($start_date, $end_date, $code_key = 8);
                    $fourtynine = 0;
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Payable Environmental Protection Surcharge' : 'পরিবেশ সুরক্ষা সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '49' : '৪৯' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note49) ? number_format($fourtynine = $note49, 2) : '0.00' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Net payable VAT for treasury deposit (35+41+43+44)' : 'ট্রেজারী জমার জন্য নীট প্রদেয় মূল্য সংযোজন কর (৩৫+৪১+৪৩+৪৪)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '50' : '৫০' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($fifty = ($thirtyfive + $fourtyone + $fourtythree + $fourtyfour)), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Net payable SD for treasury deposit (37+42)' : 'ট্রেজারী জমার জন্য নীট প্রদেয় সম্পূরক শুল্ক (৩৭+৪২)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '51' : '৫১' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format(round($fiftyone = ($thirtyseven + $fourtytwo)), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'Closing Balance of Last Tax Period (VAT)' : 'শেষ করমেয়াদের সমাপনী জের (মূল্য সংযোজন কর)' ?>
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '52' : '৫২' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftytwo, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Closing Balance of Last Tax Period (SD)' : 'শেষ করমেয়াদের সমাপনী জের (সম্পূরক শুল্ক)' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div class="style_32" style=" text-align:center;"><?= ($eng) ? '53' : '৫৩' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftythree, 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right;page-break-after: always">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px"
                       id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            <?= ($eng) ? 'Part-8: ADJUSTMENT FOR OLD ACCOUNT CURRENT BALANCE' : 'অংশ-৮ : পূর্বের চলতি হিসাবের জেরের জন্য সমন্বয়' ?>
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Items' : 'আইটেম' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Amount' : 'পরিমাণ' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Remaining Balance (VAT) from Mushak-18.6, [ Rule 118(5)]' : 'মূসক-১৮.৬ হতে অবশিষ্ট জের (মূসক) [বিধি-১১৮(৫)]' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '54' : '৫৪' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftyfour, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Remaining Balance (SD) from Mushak-18.6, [ Rule 118(5)]' : 'মূসক-১৮.৬ হতে অবশিষ্ট জের (সম্পূরক শুল্ক) [বিধি-১১৮(৫)]' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '55' : '৫৫' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftyfive, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Decreasing Adjustment for Note 54 (up to 10% of Note 34)' : 'নোট ৫৪ হতে হ্রাসকারী সমন্বয় (নোট ৩৪ এর ১০% পর্যন্ত)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '56' : '৫৬' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftysix, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Decreasing Adjustment for Note 55 (up to 10% of Note 36)' : 'নোট ৫৫ হতে হ্রাসকারী সমন্বয় (নোট ৩৬ এর ১০% পর্যন্ত)' ?>

                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '57' : '৫৭' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftyseven, 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_13">
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
                            <?= ($eng) ? 'Part-9: ACCOUNTS CODE WISE PAYMENT SCHEDULE (TREASURY DEPOSIT)' : 'অংশ-৯ : কর পরিশোধের তফসিল (ট্রেজারি জমা)' ?>
                        </th>
                        <th class="style_25" align="left"></th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Items' : 'আইটেম' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Account Code' : 'অর্থনৈতিক কোড' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Amount' : 'পরিমাণ' ?></div>
                        </td>
                        <td class="style_28" align="center">সাব ফর্ম</td>
                    </tr>
                    <?php
                    // Purpose = 1(VAT)

                    $month = date('Y-m', strtotime($start_date));
                    $sql52 = "SELECT ROUND(SUM(amount)) AS subamount FROM vat_payment WHERE payment_month = '$month' AND (purpose = 1) ORDER BY payment_date ASC";
                    $note52 = Yii::app()->db->createCommand($sql52)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'VAT Deposit for the Current Tax Period' : 'বর্তমান কর মেয়াদে পরিশোধিত মোট মূসক' ?>
                            </div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '58' : '৫৮' ?>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= ($eng) ? '১/১১৩৩/০০০০/০৩১১' : '১/১১৩৩/০০০০/০৩১১' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note52['subamount']) ? number_format($fiftyeight = $note52['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'fiftyeight', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <?php
                    // Purpose = 2(SD)

                    $sql53 = "SELECT SUM(amount) AS subamount FROM vat_payment WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND (purpose = 2) ORDER BY payment_date ASC";
                    $note53 = Yii::app()->db->createCommand($sql53)->queryRow();
                    ?>
                    <tr valign="top">
                        <td class="style_27">
                            <div>
                                <?= ($eng) ? 'SD Deposit for the Current Tax Period' : 'বর্তমান করমেয়াদে পরিশোধিত মোট সম্পূরক শুল্ক' ?>
                            </div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '59' : '৫৯' ?>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= ($eng) ? '১/১১৩৩/০০০০/০৭১১-০৭২১' : '১/১১৩৩/০০০০/০৭১১-০৭২১' ?></div>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note53['subamount']) ? number_format($fiftynine = $note53['subamount'], 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'fiftynine', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Excise Duty' : 'আবগারি শুল্ক' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '60' : '৬০' ?>
                        </td>
                        <td class="style_28" align="left">
                            <?= Adjustments::getSevenEightSubNoteCode($code_key = 4) ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note45) ? number_format($sixty = $note45, 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'sixty', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Development Surcharge' : 'উন্নয়ন সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '61' : '৬১' ?>
                        </td>
                        <td class="style_28" align="left">
                            <?= Adjustments::getSevenEightSubNoteCode($code_key = 5) ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note46) ? number_format($sixtyone = $note46, 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'sixtyone', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'ICT Development Surcharge' : 'তথ্য প্রযুক্তি উন্নয়ন সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '62' : '৬২' ?>
                        </td>
                        <td class="style_28" align="left">
                            <?= Adjustments::getSevenEightSubNoteCode($code_key = 6) ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note47) ? number_format($sixtytwo = $note47, 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'sixtytwo', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Health Care Surcharge' : 'স্বাস্থ্য সুরক্ষা সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '63' : '৬৩' ?>
                        </td>
                        <td class="style_28" align="left">
                            <?= Adjustments::getSevenEightSubNoteCode($code_key = 7) ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note48) ? number_format($sixtythree = $note48, 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'sixtythree', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Environmental Protection Surcharge' : 'পরিবেশ সুরক্ষা সারচার্জ' ?></div>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '64' : '৬৪' ?>
                        </td>
                        <td class="style_28" align="left">
                            <?= Adjustments::getSevenEightSubNoteCode($code_key = 8) ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= !empty($note49) ? number_format($sixtyfour = $note49, 2) : '0.00' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?php echo CHtml::link($cho_tag, array('purchaseItems/formcho/', 'note' => 'sixtyfour', 'start_date' => $start_date, 'end_date' => $end_date), ['class' => 'btn sub-frm-btn', 'target' => '_blank']); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_43_5ba29b89-570a-4385-832c-6f4ccba1908b"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px"
                       id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 60%;">
                        <col style=" width: 5%;">
                        <col style=" width: 25%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            <?= ($eng) ? 'Part-10: CLOSING BALANCE' : 'অংশ-১০: সমাপনী জের (পরবর্তী কর মেয়াদের প্রারম্ভিক জের)' ?>
                        </th>
                    </tr>
                    <tr class="style_1 bold tr-sub-head" valign="top">
                        <td class="style_27">
                            <div style=" text-align:center;"><?= ($eng) ? 'Items' : 'আইটেম' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Amount' : 'পরিমাণ' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Closing Balance (VAT) [58 - (50 + 67) + The refund amount
                            not approved]' : 'সমাপনী জের (মূল্য সংযোজন কর) ৫৮-(৫০+৬৭)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '65' : '৬৫' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?php
                            // -----------------------------THIS IS HARD CODED --------------------------------

                            if ($fifty < 0)
                                //echo '-';
                            ?>
                            <?php
                            //echo "$sixtyfive = $fiftyeight - ($fifty + $sixtyseven)";
                            echo number_format((round($sixtyfive = $fiftyeight - ($fifty + $sixtyseven))), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <?= ($eng) ? 'Closing Balance (SD) [59 - (51 + 68) + The refund amount
                            not approved]' : 'সমাপনী জের (সম্পূরক শুল্ক) ৫৯-(৫১+৬৮)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '66' : '৬৬' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format((round($sixtysix = $fiftynine - ($fiftyone + $sixtyeight))), 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_23"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px"
                       id="__bookmark_14">
                    <colgroup>
                        <col style=" width: 40%;">
                        <col style=" width: 35%;">
                        <col style=" width: 5%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="4" align="center">
                            <?= ($eng) ? 'Part-11: REFUND' : 'অংশ-১১  : ফেরত' ?>
                        </th>
                    </tr>
                    <tr class="style_1" valign="top">
                        <td class="style_27" rowspan="3">
                            <?= ($eng) ? 'I am interested to get refund of my Closing Balance' : 'আমি সমাপনী জেরে উল্লিখিত অর্থ ফেরত গ্রহনে ইচ্ছুক' ?>
                        </td>
                        <td class="style_27">
                            <div style=" text-align:center;font-weight: bold; background-color: #D8E4BC"><?= ($eng) ? 'Items' : 'আইটেম' ?></div>
                        </td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;font-weight: bold; background-color: #D8E4BC"><?= ($eng) ? 'Note' : 'নোট' ?></div>
                        </td>
                        <td class="style_28" align="center" style="font-weight: bold">

                            <?php
                                if ($refundstatus == 1) {
                                    echo ($eng) ? 'Yes':'হ্যাঁ';
                                    echo '&nbsp;'; 
                                } else if ($refundstatus == 2) {
                                    echo ($eng) ? 'No':'না';
                                    echo '&nbsp;'; 
                                }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_28">
                            <?= ($eng) ? 'Requested Amount for
                        Refund (VAT)' : 'দাবীকৃত ফেরতের পরিমাণ (মূসক)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '67' : '৬৭' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($sixtyseven, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_28">
                            <?= ($eng) ? 'Requested Amount for
                        Refund (SD)' : 'দাবীকৃত ফেরতের পরিমাণ (সম্পূরক শুল্ক)' ?>
                        </td>
                        <td class="style_28" align="center">
                            <?= ($eng) ? '68' : '৬৮' ?>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($sixtyeight, 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <table class="style_101" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_15">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 35%;">
                        <col style=" width: 35%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24 td_head" valign="top" align="center">
                        <th class="style_25" colspan="3" align="center">
                            <?= ($eng) ? 'Part-12: DECLARATION' : 'অংশ-১২  : ঘোষণা' ?>
                        </th>
                    </tr>
                    <tr class="style_1" valign="top">
                        <td class="style_27" colspan="3">
                            <?= ($eng) ? 'I hereby declare that all information provided in this Return Form are complete, true & accurate. In
                            case of any untrue/incomplete statement, I may be subjected to penal action under The Value Added
                            Tax and Supplementary Duty Act, 2012 or any other applicable Act prevailing at present.' : 'আমি ঘোষণা করিতেছি যে, এই দাখিলপত্রে প্রদত্ত তথ্য সর্বোতভাবে সম্পূর্ন, সত্য ও র্নিভূল । কোন অসম্পূর্ণ বা অসত্য তথ্যেও জন্য মূল্য সংযোজন কর ও সম্পূরক
                            শুল্ক আইন-২০১২ অথবা আপাতত বলবৎ এ সংক্রান্ত যেকোন আইনের আওতায় দন্ডনীয় অপবাধে অপরাধী হব।' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Name' : 'নাম' ?></div>
                        </td>
                        <td class="style_28" colspan="2" align="left">
                            <div>
                                <div style="visibility:hidden">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Designation' : 'পদবী' ?></div>
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
                            <div><?= ($eng) ? 'Mobile Number ' : 'মোবাইল নম্বর' ?></div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'National ID/Passport Number' : 'জাতীয় পরিচয়পত্র নম্বর' ?></div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div><?= ($eng) ? 'Email' : 'ইমেইল' ?></div>
                        </td>
                        <td class="style_28" align="left"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_103" colspan="2"></td>
                        <td class="style_28" align="left">
                            <div style=" text-align:center;"><?= ($eng) ? 'Signature' : 'স্বাক্ষর' ?></div>
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
    .style_23 tr:hover, .style_101 tr:hover {
        background: #e7e7e7;
    }

    .printAllTableForThisReport {
        background-color: #F9FCFD;
        font-size: 11px;
        color: #000;
    }

    .reportTabCustom {
        font-size: 12px !important;
        background-color: #fff;
        width: 8.3in;
        margin: auto;
        font-family: 'times new roman', arial;
    }

    .td_head {
        background-color: #DAEEF3
    }

    .tr-sub-head {
        background-color: #D8E4BC
    }

    .style_25,
    .style_27,
    .style_28,
    .style_40,
    .style_31,
    .style_60,
    .style_74,
    .style_103 {
        border: 1px solid #000 !important;
    }

    .row_total {
        font-weight: bold;
    }

    .mini-box {
        border: 1px solid;
        width: 18px;
        height: 16px;
        text-align: center
    }

    .date-tb div {
        border: 1px solid #ccc;
        color: #353535;
    }

    .sub-frm-btn {
        border: 1px solid #000;
        margin-left: 5px;
        color: #1818EE;
        padding: 0px 3px;
    }

    .bold {
        font-weight: bold;
    }

    .reportTabCustom td {
        vertical-align: middle;
    }
</style>