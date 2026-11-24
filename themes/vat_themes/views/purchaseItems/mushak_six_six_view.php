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
$created_by = $data[0]['created_by'];
?>
<style>
    table { page-break-inside:auto }
    tr { page-break-inside:avoid; page-break-after:auto }
    @media screen {
        div.divFooter {
            display: none;
        }
    }
    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        }
    }
    @page  
    { 
        size: auto;   /* auto is the initial value */ 
        margin: 12mm 12mm 15mm 12mm;  
    }
</style>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD;">
<div class="divFooter">Print Date & Time : <?php echo date('M d, Y & h:i:s A'); ?></div>
    <?php
    if (!empty($data)) {
        $i = 1;
        foreach ($data as $vds_payment) {
            $purchaseInfo = PurchaseItems::model()->findByPk($vds_payment['purchase_id']);
            ?>
            <table class="reportTabCustom" cellpadding="0" rules="none" border="0"
                   style="background-color:#fff;width:8.3in; margin:auto;empty-cells: show;overflow: hidden; table-layout:fixed;">
                <colgroup>
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <td valign="top">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg"
                             style="width: 80px;position: absolute;"/>
                        <table class=""
                               style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                            <tbody>
                            <tr valign="top">
                                <td></td>
                                <td style=" overflow:hidden;font-size:15px;">
                                    <div class="style_16" style=" text-align:center;"><b>গণপ্রজাতন্ত্রী বাংলাদেশ
                                            সরকার</b><br>জাতীয় রাজস্ব বোর্ড<br><br></div>
                                </td>
                                <td style="padding-top: 12px;text-align: right;"><span
                                            style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.৬</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                                    <b>উৎসে কর কর্তন সনদপত্র<br>
                                        [বিধি ৪০ এর উপ-বিধি&nbsp; (১) এর দফা (চ) দ্রষ্টব্য] </b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <table class="style_14"
                               style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                            <colgroup>
                                <col style=" width: 20%;">
                                <col style=" width: 20%;">
                                <col style=" width: 10%;">
                                <col style=" width: 20%;">
                            </colgroup>
                            <tbody>
                            <tr valign="top" style="text-align: left; height:25px;">
                                <td colspan="4" style=" overflow:hidden;">
                                    <div style="line-height: 20px;">
                                        উৎসে কর কর্তনকারী সত্তার নাম
                                        : <?= !empty($yourCompanyInfo->company_name) ? $yourCompanyInfo->company_name : '' ?>
                                        <br>

                                        উৎসে কর কর্তনকারী সত্তার ঠিকানা
                                        :
                                        <?= !empty($yourCompanyInfo->house) ? $yourCompanyInfo->house . ', ' : '' ?>
                                        <?= !empty($yourCompanyInfo->road) ? $yourCompanyInfo->road . ', ' : '' ?>
                                        <?= !empty($yourCompanyInfo->location) ? $yourCompanyInfo->location : '' ?><br>
                                        উৎসে কর কর্তনকারী সত্তার বিআইএন (প্রযোজ্য ক্ষেত্রে )
                                        : <?= !empty($yourCompanyInfo->vat_regi_no) ? $yourCompanyInfo->vat_regi_no : '' ?>
                                        <br>
                                        উৎসে কর কর্তন সনদপত্র নং: <?= $vds_payment['mushok_no'] ?>
                                        <span style="float: right">জারির&nbsp; তারিখ :&nbsp; <?= date("M-d-y H:i:s:a")?></span>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td colspan="4"
                                    style=" overflow:hidden;text-align:left; padding-top: 30px;padding-bottom: 15px;line-height: 18px">
                                    এই মর্মে প্রত্যয়ন করা যাইতেছে যে , আইনের ধারা ৪৯ অনুযায়ী উৎসে কর কর্তনযোগ্য সরবরাহ
                                    হইতে প্রযোজ্য মূল্য সংযোজন কর বাবদ উৎসে কর কর্তন করা হইল । কর্তনকৃত মূল্য সংযোজন
                                    কর্মের অর্থ বুক ট্রান্সফার/ট্রেজারি চালান/দাখিলপর্ত্রে বৃদ্ধিকারী সমন্বয়ের মাধ্যমে
                                    সরকারী কোষাগারে প্রদান করা হইয়াছে । কপি এতদসঙ্গে সংযুক্ত করা হইল (প্রযোজ্য ক্ষেত্রে)
                                    ।
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="style_18"
                               style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;"
                               id="__bookmark_3">
                            <colgroup>
                                <col style=" width: 6%;">
                                <col style=" width: 12%;">
                                <col style=" width: 14%;">
                                <col style=" width: 12%;">
                                <col style=" width: 14%;">
                                <col style=" width: 14%;">
                                <col style=" width: 14%;">
                                <col style=" width: 14%;">
                            </colgroup>
                            <tbody>
                            <tr class="style_19" valign="top" align="center">
                                <th class="style_20" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_15_ef8857ca-510d-47e8-882e-a52e6b0cf6e7"
                                         style=" text-align:center;">ক্রমিক সংখ্যা
                                    </div>
                                </th>
                                <th class="style_20" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_16_9540f292-bdd7-4941-92b6-70d9a362eafd"
                                         style=" text-align:center;">সরবরাহকারীর&nbsp;
                                    </div>
                                </th>
                                <th class="style_20" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_17_776bc007-f522-4e07-90b1-95f9f58eb2a8"
                                         style=" text-align:center;">সংশ্লিষ্ট কর চালানপত্র
                                    </div>
                                </th>
                                <th class="style_20" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_18_23183036-0871-48cc-acf4-f0964a1a79d6"
                                         style=" text-align:center;">মোট সরবরাহ মূল্য১&nbsp; (টাকা)
                                    </div>
                                </th>
                                <th class="style_20" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_19_c78fe690-5d7b-4555-917c-ecc694e7dbcf"
                                         style=" text-align:center;">মুসকের পরিমাণ&nbsp; (টাকা)
                                    </div>
                                </th>
                                <th class="style_20" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_20_0958df4c-efb0-4617-a4b3-caca44c5199b"
                                         style=" text-align:center;">&nbsp;উৎসে কর্তনকৃত মুসকের পরিমাণ (টাকা)
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_19" valign="top" align="center">
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_21_005c4698-bd19-42ee-87f3-62cfe56b7819"
                                         style=" text-align:center;">নাম&nbsp;
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_22_4a571798-2038-491e-b037-a45e62d4e82f"
                                         style=" text-align:center;">বিআইএন
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_23_040f7cca-030b-453b-91a1-5542c048216a"
                                         style=" text-align:center;">&nbsp;নম্বর&nbsp;
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div class="style_21" id="AUTOGENBOOKMARK_24_71763129-a634-4095-8c63-0969386dff2a"
                                         style=" text-align:center;">ইস্যুর তারিখ&nbsp;
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_19" valign="top" align="center">
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_25_06f5215c-0a8c-4cf7-b48d-b97db81fb33c"
                                         style=" text-align:center;">(১)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_26_3d5a6341-d076-4379-b9b7-4d6bcde145b1"
                                         style=" text-align:center;">(২)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_27_30928f24-e88b-436c-9b86-6b3968fc0d0e"
                                         style=" text-align:center;">(৩)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_28_d46c3b55-5a0b-4319-9157-7bdad8ef6fe2"
                                         style=" text-align:center;">(৪)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_29_384caa2c-533e-43a3-9ccc-b66e1b37ae2d"
                                         style=" text-align:center;">(৫)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_30_3b248377-cd5a-4a60-aaa7-e06594fdf54a"
                                         style=" text-align:center;">(৬)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_31_4f99e0ab-13fd-4e81-bea1-7ace9fdf61d1"
                                         style=" text-align:center;">(৭ )
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_32_ed339c12-2133-48b6-954e-4cada36982aa"
                                         style=" text-align:center;">(৮)
                                    </div>
                                </th>
                            </tr>
                            <?php
                            $purchase_id = $vds_payment['purchase_id'];
                            $supplier_id = $vds_payment['supplier_id'];
                            /*$sql = "SELECT * FROM vds_payment WHERE purchase_id = $purchase_id LIMIT 1";
                            $connection = Yii::app()->db;
                            $command = $connection->createCommand($sql);
                            $vdsPaymentInfo = $command->queryRow();*/
                            ?>
                            <tr valign="top">
                                <td class="style_20 center"><?= $i ?></td>
                                <td class="style_20"><?php echo Suppliers::model()->supplierName($supplier_id);
                                    '' ?></td>
                                <td class="style_20"><?php echo Suppliers::model()->supplierBin($supplier_id);
                                    '' ?></td>
                                <td class="style_20 center"><?= !empty($purchaseInfo['ref_no']) ? $purchaseInfo['ref_no'] : '' ?></td>
                                <td class="style_20 center"><?= !empty($purchaseInfo['chalan_date']) ? date('d-m-Y', strtotime($purchaseInfo['chalan_date'])) : '' ?></td>
                                <td class="style_20 right"><?= !empty($purchaseInfo['grand_total']) ? number_format(round($purchaseInfo['grand_total']), 2) : '0.00' ?></td>
                                <td class="style_20 right"><?= !empty($purchaseInfo['total_tax']) ? number_format(round($purchaseInfo['total_tax']), 2) : '0.00' ?></td>
                                <td class="style_20 right"><?= !empty($vds_payment['amount']) ? number_format(round($vds_payment['amount']), 2) : '0.00' ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <div style="visibility:hidden">&nbsp;</div>
                        </div>
                        <table class="style_14"
                               style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                            <colgroup>
                                <col>
                            </colgroup>
                            <tbody>
                            <tr valign="top">
                                <td style=" overflow:hidden;"></td>
                            </tr>
                            <tr valign="top">
                                <td style=" overflow:hidden;">
                                    &nbsp;&nbsp;<br> <br>ক্ষমতাপ্রাপ্ত কর্মকর্তার -&nbsp; 
                                    <br>&nbsp;<br>&nbsp;<br>স্বাক্ষর:&nbsp; <img src="<?php echo Yii::app()->baseUrl; Users::model()->getUserSignature($created_by); ?>" style="width: 60px;position: absolute; margin-top:-10px"/>
                                    <br><br>নাম: <?=Users::model()->fullNameOfThis($created_by)?><br><br><br>১ মূসক ও সম্পূরক শুল্ক (যদি থাকে) সহ মূল্য ।&nbsp;<br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
            //$i++;
        }
    } else {
        echo '<span style="color:red;font-weight: bold;">Sorry! No Data Available</span>';
    }
    ?>
</div>

<style>
    .reportTabCustom {
        font-size: 11px;
        font-family: 'times new roman', arial;
    }

    .style_20 {
        border: 1px solid #000 !important;
        background-color: #fff !important;
    }

    .style_18 tr th {
        border: 1px solid #000 !important; /*#c0c0c0*/
    }

    .reportTabCustom {
        page-break-after: always;
        min-height: 9in;
    }

    .left {
        text-align: left
    }

    .center {
        text-align: center
    }

    .right {
        text-align: right
    }
</style>