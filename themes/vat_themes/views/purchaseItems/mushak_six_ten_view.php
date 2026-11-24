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
?>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD; min-height: 650px">
    <?php
    if(!empty($purchaseData) || !empty($salesData)){?>

        <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show; width: 8.3in;">
            <colgroup>
                <col>
            </colgroup>
            <tbody>
            <tr valign="top">
                <td class="style_14">

                    <!--
             <img src="<?php Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                    <table class="" style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                        <tbody>
                        <tr valign="top">
                            <td></td>
                            <td style=" overflow:hidden;font-size:12px;">
                                <div class="style_16" style=" text-align:center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয় রাজস্ব বোর্ড<br><br></div>
                            </td>
                            <td style="padding-top: 11px;text-align: right;"><span style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.১০</span></td>
                        </tr>
                        <tr valign="top">
                            <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                                <b style="font-size:12px;"> ২(দুই) লক্ষ টাকার অধিক মূল্যমানের ক্রয়-বিক্রয় চালানপত্রের তথ্য<br>
                                    [বিধি ৪২ এর উপ-বিধি (১) দ্রষ্টব্য]   </b>
                            </td>
                        </tr>
                        </tbody>
                    </table><br>
                    <?php
                    $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
                    ?>
                    <table class="style_15" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_1">
                        <colgroup>
                            <col style=" width: 5%;">
                            <col style=" width: 25%;">
                            <col style=" width: 30%;">
                            <col style=" width: 10%;">
                            <col style=" width: 25%;">
                            <col style=" width: 5%;">
                        </colgroup>
                        <tbody>
                        <tr valign="top">
                            <td class="style_20" colspan="3"><br>
                                নিবন্ধিত/তালিকাভুক্ত ব্যক্তির নাম: <span style="margin-left:5px;border-bottom: 1px dotted #000;padding: 0px 10px 2px 5px"><?=!empty($yourCompanyInfo['company_name'])?$yourCompanyInfo['company_name']:''?></span>
                            </td>
                            <td class="style_20" colspan="3"><br>
                                বিআইএন : <span style="margin-left:5px;border-bottom: 1px dotted #000;padding: 0px 10px 2px 5px"><?=!empty($yourCompanyInfo['vat_regi_no'])?$yourCompanyInfo['vat_regi_no']:''?></span>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <div style="padding-bottom:3px;"><b>অংশ - কঃ ক্রয় হিসাব তথ্য    </b></div>
                    <table class="style_26" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_3">
                        <colgroup>
                            <col style=" width: 7%;">
                            <col style=" width: 13%;">
                            <col style=" width: 12%;">
                            <col style=" width: 15%;">
                            <col style=" width: 18%;">
                            <col style=" width: 20%;">
                            <col style=" width: 15%;">
                        </colgroup>
                        <tbody>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" rowspan="2" align="left">
                                <div id="AUTOGENBOOKMARK_10_927fcf38-bedb-46f4-83e4-2cb2642195f4" style=" text-align:center;">ক্রমিক সংখ্যা</div>
                            </th>
                            <th class="style_28" colspan="6" align="left">
                                <div id="AUTOGENBOOKMARK_11_1e16e327-c50b-4a7d-9d2f-5dfb4a9b2150" style=" text-align:center;">ক্রয়</div>
                            </th>
                        </tr>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_12_b4011acc-67c1-4be2-a2e6-41dbc7958c83" style=" text-align:center;">চালানপত্র নং</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_13_263d8481-c4fc-420a-b2ea-0950411fda6c" style=" text-align:center;">ইস্যুর তারিখ</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_14_0f6c95a9-e5cc-4819-a317-bb5a5ec50372" style=" text-align:center;">মূল্য</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_15_7f1099b8-e8a1-4eb5-98e6-3b054349105c" style=" text-align:center;">বিক্রেতার নাম</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_16_6ee6b623-5e80-498d-822f-26545d673ed8" style=" text-align:center;">বিক্রেতার ঠিকানা</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_17_bd04d4e4-3468-4f73-8789-c7e5985b2a75" style=" text-align:center;">বিক্রেতার বিআইএন / জাতীয় পরিচয়পত্র নং *</div>
                            </th>
                        </tr>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_18_4103196e-346e-414d-be89-1b00e6f62dc6" style=" text-align:center;">(১)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_19_039c9267-0f18-466b-aaa1-905864d500e0" style=" text-align:center;">(২)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_20_8af84343-03ef-45bb-bea9-17e4993fb047" style=" text-align:center;">(৩)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_21_44a65398-d7c4-437f-9359-fd0b02a8da02" style=" text-align:center;">(৪)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_22_1c8c24da-02e8-493c-9244-f51f66f4a4b0" style=" text-align:center;">(৫)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_23_e01ceb74-5a31-4b18-898a-0ba25f4aef1f" style=" text-align:center;">(৬)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_24_5241e18c-337e-4e62-91a1-b74d375b207e" style=" text-align:center;">(৭)</div>
                            </th>
                        </tr>
                        <?php
                        if(!empty($purchaseData)){
                            $i = 1;
                            $final_purchase = 0;
                            foreach($purchaseData as $pdata){
                                $supplierInfo = Suppliers::model()->findByPk($pdata['supplier_id']);
                                ?>
                                <tr class="center">
                                    <td class="style_28"><?=$i?></td>
                                    <td class="style_28"><?=!empty($pdata["ref_no"])? $pdata["ref_no"]:''?></td>
                                    <td class="style_28"><?=!empty($pdata["effective_date"])?date('d-m-Y',strtotime($pdata["effective_date"])):''?></td>
                                    <td class="style_28 right"><?=!empty($pdata["grand_total"])?number_format(round($pdata["grand_total"]),2):''?></td>
                                    <td class="style_28"><?=!empty($supplierInfo["name"])?$supplierInfo["name"]:''?></td>
                                    <td class="style_28"><?=!empty($supplierInfo["address"])?$supplierInfo["address"]:''?></td>
                                    <td class="style_28"><?=!empty($supplierInfo["bin"])?$supplierInfo["bin"]:(!empty($supplierInfo["nid"])?:'')?></td>
                                </tr>
                                <?php
                                $i++;
                                $final_purchase = $final_purchase + $pdata["grand_total"];
                            }
                        }
                        ?>
                        <tr valign="top" style="font-weight:bold;">
                            <td class="style_28" colspan="3" style=" text-align:right;">
                                সর্বমোট
                            </td>
                            <td class="style_28 right"><?=!empty($final_purchase)?number_format(round($final_purchase),2):''?></td>
                            <td class="style_28"></td>
                            <td class="style_28"></td>
                            <td class="style_28"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div style="padding-bottom:3px;padding-top: 10px"><b>অংশ - খঃ বিক্রয় হিসাব তথ্য    </b></div>
                    <table class="style_26" style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_4">
                        <colgroup>
                            <col style=" width: 7%;">
                            <col style=" width: 13%;">
                            <col style=" width: 12%;">
                            <col style=" width: 15%;">
                            <col style=" width: 18%;">
                            <col style=" width: 20%;">
                            <col style=" width: 15%;">
                        </colgroup>
                        <tbody>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" rowspan="2" align="left">
                                <div id="AUTOGENBOOKMARK_27_724f63e1-9796-42d0-ab53-6e7d97f8e1d3" style=" text-align:center;">ক্রমিক সংখ্যা</div>
                            </th>
                            <th class="style_28" colspan="6" align="left">
                                <div id="AUTOGENBOOKMARK_28_db3df454-1dd0-45d4-b53b-e66ad877af08" style=" text-align:center;">বিক্রয়</div>
                            </th>
                        </tr>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_29_1eceef8d-f7d3-4e49-915f-b5ca037db356" style=" text-align:center;">চালানপত্র নং</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_30_78949231-f234-416c-8f85-eb79abaa07d6" style=" text-align:center;">ইস্যুর তারিখ</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_31_21ea1006-a117-46a3-9cee-4a985a0bb103" style=" text-align:center;">মূল্য</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_32_3ad3955c-c9d8-4772-97f3-bf8bf82a5587" style=" text-align:center;">ক্রেতার নাম</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_33_7a395709-021f-437a-933d-0e7cc15a5ecf" style=" text-align:center;">ক্রেতার ঠিকানা</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_34_3dc02c2f-fc35-4493-b0f5-a16d7719cb62" style=" text-align:center;">ক্রেতার বিআইএন / জাতীয় পরিচয়পত্র নং *</div>
                            </th>
                        </tr>
                        <tr class="style_27" valign="top" align="center">
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_35_06f69463-e24d-46d8-9e2a-efcd0ad7d301" style=" text-align:center;">(১)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_36_a233389c-e2a4-464a-900c-8b7c0a49bf4a" style=" text-align:center;">(২)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_37_2dd9f69c-1d4c-49ab-b12f-b1d7f6b083ba" style=" text-align:center;">(৩)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_38_e7cf2d5c-579a-4762-b465-0bf107b71cd1" style=" text-align:center;">(৪)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_39_51da9532-57a4-4cb8-b0e6-485efbf12ed2" style=" text-align:center;">(৫)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_40_e003cc82-0874-41bb-ba92-f7f432abf6e3" style=" text-align:center;">(৬)</div>
                            </th>
                            <th class="style_28" align="left">
                                <div id="AUTOGENBOOKMARK_41_91d77560-5772-4016-a9ce-ea2e9f0945e6" style=" text-align:center;">(৭)</div>
                            </th>
                        </tr>
                        <?php
                        if(!empty($salesData)){
                            $i=0;
                            $j = 1;
                            $final_sale = 0;
                            foreach($salesData as $sdata){
                                $customerInfo = Customers::model()->findByPk($sdata['customer_id']);
                                ?>
                                <tr class="center">
                                    <td class="style_28"><?=$j?></td>
                                    <td class="style_28"><?=!empty($sdata["mushok_no"])? $sdata["mushok_no"]:''?></td>
                                    <td class="style_28"><?=!empty($sdata["sales_date"])?date('d-m-Y',strtotime($sdata["sales_date"])):''?></td>
                                    <td class="style_28 right"><?=!empty($sdata["grand_total"])?number_format(round($sdata["grand_total"]),2):''?></td>
                                    <td class="style_28"><?=!empty($customerInfo["company_name"])?$customerInfo["company_name"]:''?></td>
                                    <td class="style_28"><?=!empty($customerInfo["company_address"])?$customerInfo["company_address"]:''?></td>
                                    <td class="style_28"><?=!empty($customerInfo["bin"])?$customerInfo["bin"]:(!empty($customerInfo["nid"])?:'')?></td>
                                </tr>
                                <?php
                                $j++;
                                $final_sale = $final_sale + $sdata["grand_total"];
                            }
                        }
                        ?>
                        <tr valign="top" style="font-weight:bold;">
                            <td class="style_28" colspan="3" style=" text-align:right;">
                                সর্বমোট
                            </td>
                            <td class="style_28 right"><?=!empty($final_sale)?number_format(round($final_sale),2):''?></td>
                            <td class="style_28"></td>
                            <td class="style_28"></td>
                            <td class="style_28"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div><br><br><br><br>দায়িত্বপ্রাপ্ত ব্যক্তির স্বাক্ষরঃ</div><br>
                    <div>নামঃ</div><br>
                    <div>তারিখঃ</div>
                </td>
            </tr>
            <tr valign="top">
                <td class="style_38" style="font-weight:bold;padding-top: 10px">
                    ** যেই ক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য/সেবা ক্রয় করা হইবে বা অনিবন্ধিত ব্যক্তির নিকট পণ্য/সেবা বিক্রয় করা হইবে সেই ক্ষেত্রে উক্ত ব্যক্তির পূর্ণাঙ্গ নাম, ঠিকানা ও জাতীয় পরিচয়পত্র নম্বর যথাযথভাবে সংশ্লিষ্ট কলাম [(৭), (৮) ও (৯)] এ আবশ্যিকভাবে উল্লেখ করিতে হইবে।
                </td>
            </tr>
            </tbody>
        </table>

    <?php
    }else{
        echo '<span style="color:red;font-weight: bold;">Sorry! No Data Available</span>';
    }
    ?>
</div>

<style>
    .reportTabCustom{
        font-size:11px !important;
        background-color:#fff;
        font-family:'times new roman', arial;        
        margin: auto;
        background-color: #fff;
    }
    .style_28{
        border: 1px solid #000 !important;
        font-weight: normal;
    }
    .left{text-align: left}
    .center{text-align: center}
    .right{text-align: right}
</style>