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
echo "  <img class='excl-export' id='btnExport' onclick='fnExcelReport();' src='" . Yii::app()->theme->baseUrl . "/images/excel.png' style='padding:5px;' title='EXPORT TO EXCEL'>";
echo "</div>";
$fiftytwo = $thirtyfour = $twentythree_kha = $twentyeight = $thirtythree = $thirtyfive = $fiftysix = $fiftyeight = $sixtyfive = $sixtyseven = 0;
$data = PurchaseItems::nineOneSummary($start_date, $end_date);
if ($data) {
    $fiftytwo = round($data['fiftytwo']);
    $thirtyfour = round($data['thirtyfour']);
    $twentythree_kha = round($data['twentythree_kha']);
    $twentyeight = round($data['twentyeight']);
    $thirtythree = round($data['thirtythree']);
    $thirtyfive = round($data['thirtyfive']);

    $fiftysix = round($data['fiftysix']);
    $fiftyeight = round($data['fiftyeight']);
    $sixtyfive = round($data['sixtyfive']);
    $sixtyseven = round($data['sixtyseven']);
}

$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
if ($yourCompanyInfo->company_vat_type == YourCompany::STANDARD)
    $standard = true;
else
    $standard = false;
?>

<div class='printAllTableForThisReport'>
    <table class="style_14 reportTabCustom" style="border-collapse: collapse; empty-cells: show;"
           id="table-id-for-excel">
        <colgroup>
            <col>
        </colgroup>
        <tbody>
        <tr valign="top">
            <td class="style_15">
                <table border="1" class="style_23 openingTb"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_1">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr style="background-color: #EEF7E2; border: 1px solid #000;">
                        <td colspan="3"
                            style="text-align: center; font-weight: bold; padding: 3px; text-transform: uppercase;">
                            9.1 Summary Report<br>
                            <span style="font-size: 11px">From Date : <?= date('d-m-Y', strtotime($start_date)) ?> To <?php echo date('d-m-Y', strtotime($end_date)) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_16" id="AUTOGENBOOKMARK_10_a074aeab-7c67-4d0d-b979-5c4b289d2bb6">(১)
                                ব্যবসায় সনাক্তকরণ সংখ্যা
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_11_60b371af-af0a-4dea-9e59-681e09a7d10c"
                                 style=" text-align:center;">:
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= !empty($yourCompanyInfo['vat_regi_no']) ? $yourCompanyInfo['vat_regi_no'] : '' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_12_27259c5f-420a-4e36-bba5-140d600260de">(২)
                                করদাতার নাম
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_13_f2423012-15b5-4a54-832c-e11fcfcf911c"
                                 style=" text-align:center;">:
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div><?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_14_dd831f44-5958-40bc-8278-20a6a00ba028">(৩) করদাতার ঠিকানা</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_15_5b0d4c42-147f-43f3-9d7e-1e7762781b03"
                                 style=" text-align:center;">:
                            </div>
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
                <div class="style_22" id="AUTOGENBOOKMARK_20_b0119be7-e694-4be0-a896-c70f7a5b4c32"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>


                <table border="1" class="style_23 openingTb"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;">
                    <colgroup>
                        <col style=" width: 30%;">
                        <col style=" width: 3%;">
                        <col style=" width: 60%;">
                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_16" id="AUTOGENBOOKMARK_10_a074aeab-7c67-4d0d-b979-5c4b289d2bb6">Opening
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_11_60b371af-af0a-4dea-9e59-681e09a7d10c"
                                 style=" text-align:center;">:
                            </div>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftytwo, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div class="style_29" id="AUTOGENBOOKMARK_12_27259c5f-420a-4e36-bba5-140d600260de">VAT
                                Payable
                            </div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_13_f2423012-15b5-4a54-832c-e11fcfcf911c"
                                 style=" text-align:center;">:
                            </div>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($thirtyfour, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            <div id="AUTOGENBOOKMARK_14_dd831f44-5958-40bc-8278-20a6a00ba028">Rebate</div>
                        </td>
                        <td class="style_28" align="left">
                            <div id="AUTOGENBOOKMARK_15_5b0d4c42-147f-43f3-9d7e-1e7762781b03"
                                 style=" text-align:center;">:
                            </div>
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($twentythree_kha, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Increasing Adjust.
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($twentyeight, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Decreasing Adjust.
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($thirtythree, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Net Payable
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($thirtyfive, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Adjust. With Previous Balance
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftysix, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Treasury Deposit
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($fiftyeight, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Closing Balance
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($sixtyfive, 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_27">
                            Refund claim
                        </td>
                        <td class="style_28" align="center">
                            :
                        </td>
                        <td class="style_28" align="right">
                            <?= number_format($sixtyseven, 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_22" id="AUTOGENBOOKMARK_20_b0119be7-e694-4be0-a896-c70f7a5b4c32"
                     style=" text-align:right;">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>

            </td>
        </tr>
        </tbody>
    </table>
</div>

<style>
    .style_23 tr:hover {
        background: #e7e7e7;
    }

    .printAllTableForThisReport {
        background-color: #F9FCFD;
        font-size: 11px;
    }

    .reportTabCustom {
        font-size: 12px !important;
        background-color: #fff;
        width: 100%;
        max-width: 10in;
        margin: auto;
        font-family: 'times new roman', arial;
    }

    .td_head {
        background-color: #DAEEF3
    }

    .tr-sub-head {
        background-color: #D8E4BC
    }

    .style_25, .style_27, .style_28, .style_40, .style_31, .style_60, .style_74, .style_103 {
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

    .openingTb td {
        padding: 2px 3px 2px 3px;
    }
</style>