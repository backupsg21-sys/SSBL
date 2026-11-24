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
//$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
$yourCompanyInfo = YourCompany::model()->companyInfo();
?>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD">
    <style>
        .reportTabCustom {
            page-break-after: always;
        }

        .style_17 tr:hover, .style_16 tr:hover {
            background: #e5e5e5;
        }
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
    <div class="divFooter">Print Date & Time : <?php echo date('M d, Y & h:i:s A'); ?></div>
    <?php
    function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {
        $array = array();
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach ($period as $date) {
            $array[] = $date->format($format);
        }
        return $array;
    }

    if (!empty($data)) {
        foreach ($data as $dt) {
            $model_id = $dt['model_id'];
            $sql3 = "SELECT model_name, mrp FROM prod_models WHERE id='$model_id' LIMIT 1";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql3);
            $prodInfo = $command->queryRow();
            $mrp = !empty($prodInfo['mrp']) ? $prodInfo['mrp'] : 0;
            $model_name = !empty($prodInfo['model_name']) ? $prodInfo['model_name'] : '';
            $hsCode = ProdModels::model()->prodHSCodeOfThis($model_id);
            ?>
            <table class="reportTabCustom"
                   style="border-collapse: collapse; border: none; empty-cells: show; overflow:hidden; table-layout:fixed;">
                <tbody>
                <tr valign="top">
                    <td class="style_14" style=" overflow:hidden;">
                        <!--
                         <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                        <table class=""
                               style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;margin:auto;width: 100%;">
                            <tbody>
                            <tr valign="top">
                                <td></td>
                                <td style=" overflow:hidden;font-size:12px;">
                                    <div class="style_16" style=" text-align:center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয়
                                        রাজস্ব বোর্ড<br><br></div>
                                </td>
                                <td style="padding-top: 11px;text-align: right"><span
                                            style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.২.১</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td style="overflow: hidden; font-size: 11px; font-weight: bold" colspan="3">
                                    <?= $yourCompanyInfo['company_name'] ?><br>
                                    <?= $yourCompanyInfo['address'] ?><br>
                                    <?= $yourCompanyInfo['vat_regi_no'] ? 'VAT Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td style="overflow: hidden; font-size: 11px;" colspan="3">
                                    <b>ক্রয়-বিক্রয় হিসাব <br>
                                        (পণ্য বা সেবা প্রক্রিয়াকরণে সম্পৃক্ত নয় (ব্যবসায়ী) এমন নিবন্ধিত বা তালিকাভুক্ত
                                        ব্যক্তির জন্য প্রযোজ্য) <br>
                                        [বিধি ৪০ এর উপ-বিধি (১) এর দফা (খ)ও ৪১(ক) দ্রষ্টব্য ]</b>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>

                        <div style="padding-bottom: 5px;">পণ্যের নাম : <?= !empty($model_name) ? $model_name : '' ?> (HS
                            Code: <?= !empty($hsCode) ? $hsCode : '' ?>)
                        </div>
                        <table class="style_16"
                               style="border-collapse: collapse;empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                            <colgroup>
                                <col style=" width: 3%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 5%;">
                                <col style=" width: 4%;">
                                <col style=" width: 5%;">
                                <col style=" width: 4%;">
                                <col style=" width: 5%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 3%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 5%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 3%;">
                                <col style=" width: 4%;">
                                <col style=" width: 4%;">
                                <col style=" width: 5%;">
                                <col style=" width: 3%;">
                            </colgroup>
                            <tbody>
                            <tr class="style_17 csd" valign="top" align="center">
                                <th class="style_18" colspan="26" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_8_b03b0e6b-32cb-4005-bbab-0c565d588493"
                                         style=" text-align:center;">পণ্য/সেবার বিক্রয়
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_17 csd" valign="top" align="center">
                                <th class="style_18" rowspan="3" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_9_98ac83b9-ed65-42b2-b288-c2ea309b88ad"
                                         style=" text-align:center;">ক্রমিক সংখ্যা
                                    </div>
                                </th>
                                <th class="style_18" rowspan="3" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_10_e86c509b-1817-4ce7-8287-54a9058ee7a7"
                                         style=" text-align:center;">তারিখ
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_11_619127af-8bf2-4a04-a03c-26c050fc30fb"
                                         style=" text-align:center;">বিক্রয়যোগ্য পণ্যের<br>প্রারম্ভিক জের
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_12_fbe2e10b-b7f4-4104-97b4-07aa59ad9893"
                                         style=" text-align:center;">ক্রয়
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_13_147b46d4-0180-483a-bf39-9a961da2c5fc"
                                         style=" text-align:center;">মোট পণ্য
                                    </div>
                                </th>
                                <th class="style_18" colspan="3" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_14_2a973970-168d-4065-bbfb-21f12f49b821"
                                         style=" text-align:center;">বিক্রেতার তথ্য
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_15_44be521a-100d-4b48-99e4-528b06615bc9"
                                         style=" text-align:center;">ক্রয় চালানপত্রের/বিল অব এন্ট্রির বিবরণ
                                    </div>
                                </th>
                                <th class="style_18" colspan="5" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_16_29ef4d51-5f47-4ad5-be99-f022791c04d7"
                                         style=" text-align:center;">বিক্রিত/সরবরাহকৃত পণ্যের বিবরণ
                                    </div>
                                </th>
                                <th class="style_18" colspan="3" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_17_ad667448-c392-463c-a11b-af00ce9dfce5"
                                         style=" text-align:center;">ক্রেতার তথ্য
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_18_8cc89122-af20-4b57-9465-4a153415ae38"
                                         style=" text-align:center;">বিক্রয় চালানপত্রের বিবরণ
                                    </div>
                                </th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_19_64be95c6-0c83-49ba-b07f-0afb212fcd40"
                                         style=" text-align:center;">পণ্যের প্রান্তিক<br>জের
                                    </div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_20_986a73c8-8cad-423c-9d71-0ba53611e930"
                                         style=" text-align:center;">মন্তব্য
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_17 csd" valign="top" align="center">
                                <th class="style_20" rowspan="2" style=" overflow:hidden;" align="center">
                                    <div id="AUTOGENBOOKMARK_21_79415349-f0a8-44e8-ae1c-7f13b01aec78">পরিমাণ (একক)</div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_22_9e70aa60-9a56-45d0-b4a3-607756fbbef8"
                                         style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)
                                    </div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_23_5a9be8af-6463-4aa0-8f1e-d478bf7d4eaf"
                                         style=" text-align:center;">পরিমাণ (একক)
                                    </div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_24_547f929b-ef33-457f-a91e-c20a2a93c4c4"
                                         style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)
                                    </div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_25_b4b0a1e8-a6a8-468e-bd13-53e4811c610a"
                                         style=" text-align:center;">পরিমাণ (একক)
                                    </div>
                                </th>
                                <th class="style_18" rowspan="2" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_26_49842061-3e3e-4303-92c8-cba16881996f"
                                         style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_27_e48c438c-60d9-4e9b-9558-6a65ed254687"
                                         style=" text-align:center;">নম্বর
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_28_50ecca08-1f96-43ac-a0ea-a96a0c1af344"
                                         style=" text-align:center;">তারিখ
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_29_f89c0843-8722-4f9f-a4c7-6e2d56b5acac"
                                         style=" text-align:center;">বিবরণ
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_30_675a8d45-8e77-475b-b02d-d44b137095e6"
                                         style=" text-align:center;">পরিমাণ
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_31_2e7e55a2-d864-4a9d-9216-5c1e9d6a5698"
                                         style=" text-align:center;">করযোগ্য মূল্য (সকল প্রকার কর ব্যতীত)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_32_c32fca6b-e110-407d-936a-41f7e0185dcc"
                                         style=" text-align:center;">সম্পূরক শুল্ক<br>(যদি থাকে)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_33_f6e2603b-7073-4cf1-acad-4ec81adb0911"
                                         style=" text-align:center;">মূসক
                                    </div>
                                </th>
                                <th class="style_18" colspan="3" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" colspan="2" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_34_17297831-9f6b-47c0-a664-8926495d9bf8"
                                         style=" text-align:center;">পরিমাণ
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_35_2731acdb-25b0-4f9c-8fe8-0310e45bbe08"
                                         style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_17 csd" valign="top" align="center">
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_36_fd37069f-3be4-4eef-a68a-1b1442bda7e9"
                                         style=" text-align:center;">নাম
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_37_64899bbb-c73a-4486-be52-1d828a6d6ec7"
                                         style=" text-align:center;">ঠিকানা
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_38_230044e4-06d0-4ad0-ae16-6b69fbcfdbfb"
                                         style=" text-align:center;">নিবন্ধন/তালিকাভুক্তি/ জাতীয় পরিচয়পত্র নং
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_39_34e24cc8-ece1-467d-9467-7361af7d9837"
                                         style=" text-align:center;">নাম
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_40_46263eb0-3d86-4e34-99b6-711ea5599f9d"
                                         style=" text-align:center;">ঠিকানা
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_41_c3fc203d-f81a-4eb7-a6ef-748812ee7842"
                                         style=" text-align:center;">নিবন্ধন/তালিকাভুক্তি/ জাতীয় পরিচয়পত্র নং
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_42_b2c2080c-dbd0-4569-a22e-2f44fbffddc5"
                                         style=" text-align:center;">নম্বর
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_43_596d048b-d3c5-4791-8840-07c67d3d2cb1"
                                         style=" text-align:center;">তারিখ
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                                <th class="style_18" style=" overflow:hidden;" align="left"></th>
                            </tr>
                            <tr class="style_5 csd" valign="top" align="center">
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_44_6d06bedc-537b-4184-a979-60e8ba3e0a56"
                                         style=" text-align:center;">(১)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_45_8a36af6b-4835-40bf-a48c-ff936b987f8b"
                                         style=" text-align:center;">(২)
                                    </div>
                                </th>
                                <th class="style_20" style=" overflow:hidden;" align="center">
                                    <div id="AUTOGENBOOKMARK_46_f39ef710-e815-4a6f-a9c8-23025aa0522b"
                                         style=" text-align:center;">(৩)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="center">
                                    <div id="AUTOGENBOOKMARK_47_bd706591-1340-47a0-a125-e84b8fb2e871">(৪)</div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_48_71e5e480-be05-4b4b-bb69-c221219333c6"
                                         style=" text-align:center;">(৫)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_49_bf6f387f-bb42-4a68-8569-24864301521b"
                                         style=" text-align:center;">(৬)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_50_565c66dc-8dfb-492a-bef1-e885d44cf911"
                                         style=" text-align:center;">(৭)=(৩+৫)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_51_9b3ac753-88ac-4d59-ada2-8eb9b3a7c70a"
                                         style=" text-align:center;">(৮)=(৪+৬)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_52_0fcfaf32-cf37-40f7-81ed-ba9e0a30d013"
                                         style=" text-align:center;">(৯)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_53_70aca2ac-08a0-44e5-ac8e-9b4eaaf28a2f"
                                         style=" text-align:center;">(১০)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="center">
                                    <div id="AUTOGENBOOKMARK_54_3ebeec35-5339-4769-a69f-8af5bb1eda62">(১১)</div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="center">
                                    <div id="AUTOGENBOOKMARK_55_a7b1b49b-fc44-452f-8b6d-c60c92d9b60d">(১২)</div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_56_b18ee4ca-2baf-42bd-9249-b60bee77f19f"
                                         style=" text-align:center;">(১৩)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_57_5bdab885-ca88-4060-9423-337588b1546b"
                                         style=" text-align:center;">(১৪)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_58_6b25937f-fee3-43e0-9c4f-3a94cd1ef790"
                                         style=" text-align:center;">(১৫)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_59_2ca99920-d9ac-4feb-b4bb-4f7c1ee86392"
                                         style=" text-align:center;">(১৬)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_60_4f6b68d4-d900-43d9-829b-b26a8e749248"
                                         style=" text-align:center;">(১৭)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_61_39af150c-e915-473d-ba60-b69c674df36d"
                                         style=" text-align:center;">(১৮)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_62_c8df395c-cb84-4130-9c60-6f40cfe1caad"
                                         style=" text-align:center;">(১৯)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_63_7f468f4c-0ba5-4c25-b2d3-6ef2fe472a0d"
                                         style=" text-align:center;">(২০)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_64_d4785ef3-739e-4449-aab0-2b844d2923a7"
                                         style=" text-align:center;">(২১)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_65_0544705e-f70a-4bb1-aae5-adf16df50533"
                                         style=" text-align:center;">(২২)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_66_a6868ab5-77a4-45f5-b708-7d3a3c4f4cd8"
                                         style=" text-align:center;">(২৩)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_67_38184beb-7828-4bbc-aea0-39d81856a438"
                                         style=" text-align:center;">(২৪)=(৭-১৫)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_68_c2f22111-6377-4e25-98a1-55ced7b85b5d"
                                         style=" text-align:center;">(২৫)=(৮-১৬)
                                    </div>
                                </th>
                                <th class="style_18" style=" overflow:hidden;" align="left">
                                    <div id="AUTOGENBOOKMARK_69_3160b5ee-6de5-4e52-b35d-c94514a11213"
                                         style=" text-align:center;">(২৬)
                                    </div>
                                </th>
                            </tr>
                            <?php
                            $date_range = [];
                            $date_range = getDatesFromRange($start_date, $end_date);

                            if (count($date_range) > 0) {
                                $i = 1;
                                $twentyfour = 0;
                                $twentyfive = 0;
                                foreach ($date_range as $target_date) {
                                    //$purchase_data = Common::model()->findAllByAttributes(array('date' => $target_date));
                                    // Only Sales & Purchase data
                                    $sql66 = "SELECT * FROM common WHERE model_id= $model_id AND date = '$target_date' AND (type=1 OR type=2) ORDER BY date, type ASC";
                                    $dataDetails = Yii::app()->db->createCommand($sql66)->queryAll();

                                    if (!empty($dataDetails)) {

                                        foreach ($dataDetails as $pdata) {

                                            if (!empty($pdata['purchase_id'])) {
                                                $purchaseInfo = PurchaseItems::model()->findByPk($pdata['purchase_id']);
                                                if (!empty($purchaseInfo['supplier_id'])) {
                                                    $supplierInfo = Suppliers::model()->findByPk($purchaseInfo['supplier_id']);
                                                }
                                            }

                                            if (!empty($pdata['sales_id'])) {
                                                $salesInfo = SellItems::model()->findByPk($pdata['sales_id']);
                                                if (!empty($salesInfo['customer_id'])) {
                                                    $customerInfo = Customers::model()->findByPk($salesInfo['customer_id']);
                                                }
                                            } ?>

                                            <!-- First Row = Opening Stock from query, Next Row = Opening Stock is Previous Row Closing Stock  (SAME ALSO FOR PRICE)-->
                                            <?php
                                            $opening_Stock = 0;
                                            $issue_date = $pdata['date'];
                                            $sql77 = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut, SUM(price_in) as priceIn, SUM(price_out) as priceOut FROM common 
                                                         where model_id = $model_id and date< '$issue_date' and (type=1 OR type=2) order by id";
                                            $stockData = Yii::app()->db->createCommand($sql77)->queryRow();
                                            $openingStock = $stockData['stockIn'] - $stockData['stockOut'];
                                            $stockPrice = round($stockData['priceIn'] - $stockData['priceOut']);
                                            $three = 0;
                                            $four = 0;
                                            $five = 0;
                                            $six = 0;
                                            $seven = 0;
                                            $eight = 0;
                                            $fifteen = 0;
                                            $sixteen = 0;
                                            ?>

                                            <tr class="style_30 csd" valign="top" style="word-break: break-all">
                                                <td class="style_31 center" style=" overflow:hidden;"><?= $i ?></td>
                                                <td class="style_32 center" style=" overflow:hidden;">
                                                    <?= !empty($pdata['date']) ? date('d-m-Y', strtotime($pdata['date'])) : '' ?>
                                                </td>
                                                <td class="style_32 center" style=" overflow:hidden;">
                                                    <!-- Opening(Total) Stock before Issue Date -->
                                                    <?php
                                                    if ($i == 1) {
                                                        echo $three = $openingStock;
                                                    } else {
                                                        echo $three = $twentyfour;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="style_32 right" style=" overflow:hidden;">
                                                    <!-- Opening(Total) Stock Price before Issue Date -->
                                                    <?php
                                                    $tempFour = 0;
                                                    if ($i == 1) {
                                                        echo number_format($four = $stockPrice, 2);
                                                    } else {
                                                        echo number_format($four = $twentyfive, 2);
                                                    }
                                                    $openingUnitPrice = 0;
                                                    if ($four < 0)
                                                        $four = 0;
                                                    if ($three < 0)
                                                        $three = 0;
                                                    else if ($three > 0)
                                                        $openingUnitPrice = $four / $three;
                                                    ?>
                                                </td>

                                                <?php
                                                if ($pdata['type'] == 1) {
                                                    ?>

                                                    <td class="style_31 center" style=" overflow:hidden;">
                                                        <?php
                                                        echo !empty($pdata['order_qty']) ? $five = $pdata['order_qty'] : '0';
                                                        ?>
                                                    </td>
                                                    <td class="style_31 right" style=" overflow:hidden;">
                                                        <?php
                                                        echo !empty($pdata['total']) ? number_format($six = ($pdata['total'] + $pdata['cd'] + $pdata['rd'] + $pdata['sd']), 2) : '0';
                                                        ?>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <?php
                                                }
                                                ?>
                                                <td class="style_31 center" style="overflow:hidden;">
                                                    <?php echo $seven = $three + $five ?>
                                                </td>
                                                <td class="style_32 right" style=" overflow:hidden;">
                                                    <?php echo number_format(($eight = $four + $six), 2) ?>
                                                </td>
                                                <?php
                                                if ($pdata['type'] == 1) {
                                                    ?>
                                                    <!-- Type=1, Purchase Details -->
                                                    <td class="style_31" style=" overflow:hidden;">
                                                        <?= !empty($supplierInfo['name']) ? $supplierInfo['name'] : '' ?>
                                                    </td>
                                                    <td class="style_31" style=" overflow:hidden;">
                                                        <?= !empty($supplierInfo['address']) ? $supplierInfo['address'] : '' ?>
                                                    </td>
                                                    <td class="style_31" style=" overflow:hidden;">
                                                        <?= !empty($supplierInfo['bin']) ? $supplierInfo['bin'] : '' ?>
                                                    </td>
                                                    <td class="style_32" style=" overflow:hidden;">
                                                        <? //=!empty($purchaseInfo['po_no'])?'PO-'.$purchaseInfo['po_no']:''
                                                        ?>
                                                        <?php
                                                        if ($purchaseInfo['local_import'] == 54) {
                                                            echo $purchaseInfo['ref_no'];
                                                        } else if ($purchaseInfo['local_import'] == 55) {
                                                            echo $purchaseInfo['boe_no'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="style_31" style=" overflow:hidden;">
                                                        <?= !empty($issue_date) ? date('d-m-Y', strtotime($issue_date)) : '' ?>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <?php
                                                }
                                                ?>
                                                <td class="style_32" style=" overflow:hidden;">
                                                    <?= !empty($model_name) ? $model_name : '' ?>
                                                </td>

                                                <?php
                                                if ($pdata['type'] == 2) {
                                                    ?>
                                                    <!-- Type=2, Sales/Order Details -->
                                                    <td class="style_31 center" style=" overflow:hidden;">
                                                        <?php
                                                        echo !empty($pdata['order_qty']) ? $fifteen = $pdata['order_qty'] : '0';
                                                        ?>
                                                    </td>
                                                    <td class="style_32 right" style=" overflow:hidden;">
                                                        <?= !empty($pdata['total']) ? number_format($sixteen = $pdata['total'], 2) : '0.00' ?>
                                                    </td>
                                                    <td class="style_31 right" style=" overflow:hidden;">
                                                        <?= !empty($pdata['sd']) ? number_format($pdata['sd'], 2) : '0.00' ?>
                                                    </td>
                                                    <td class="style_31 right" style=" overflow:hidden;">
                                                        <?= !empty($pdata['total_tax']) ? number_format($pdata['total_tax'], 2) : '0.00' ?>
                                                    </td>
                                                    <td class="style_31" style=" overflow:hidden;">
                                                        <?= !empty($customerInfo['company_name']) ? $customerInfo['company_name'] : '' ?>
                                                    </td>
                                                    <td class="style_32" style=" overflow:hidden;">
                                                        <?= !empty($customerInfo['company_address']) ? $customerInfo['company_address'] : '' ?>
                                                    </td>
                                                    <td class="style_32" style=" overflow:hidden;">
                                                        <?= !empty($customerInfo['bin']) ? $customerInfo['bin'] : '' ?>
                                                    </td>
                                                    <td class="style_32" style=" overflow:hidden;">
                                                        <?= !empty($salesInfo['so_no']) ? 'SO-' . $salesInfo['so_no'] : '' ?>
                                                    </td>
                                                    <td class="style_32 center" style=" overflow:hidden;">
                                                        <?= !empty($issue_date) ? date('d-m-Y', strtotime($issue_date)) : '' ?>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <td class="style_31" style=" overflow:hidden;"></td>
                                                    <?php
                                                }
                                                ?>

                                                <td class="style_32 center" style=" overflow:hidden;">
                                                    <!-- Set Row Closing Qty for use in Next Row as Opening Qty -->
                                                    <?php
                                                    echo $line_end_qty = $twentyfour = $seven - $fifteen;
                                                    ?>
                                                </td>
                                                <td class="style_31 right" style=" overflow:hidden;">
                                                    <!-- Set Row Closing Qty for use in Next Row as Opening Qty -->
                                                    <?php
                                                    echo number_format(($line_end_closing = $twentyfive = $eight - $sixteen), 2); // THIS IS BY GOVT. INSTRUCTION AS IN COLUMN TITLE
                                                    //  echo $line_end_closing = number_format($twentyfive = ($openingUnitPrice * $line_end_qty),2); // this is our calculation.
                                                    ?>
                                                </td>
                                                <td style=" overflow:hidden;"></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <div style="padding: 35px 0px;">
                            <b>বিশেষ দ্রষ্টব্য: </b><br>
                            * যেই ক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য ক্রয় বা অনিবন্ধিত ব্যক্তির নিকট পণ্য বিক্রয়
                            করা হইবে সেই ক্ষেত্রে উক্ত ব্যক্তির পূর্ণাঙ্গ নাম, ঠিকানা
                            ও জাতীয় পরিচয়পত্র নম্বর যথাযথভাবে সংশ্লিষ্ট কলাম [(৯), (১০), (১১), (১৯), (২০) ও (২১)] এ
                            আবশ্যিকভাবে উল্লেখ করিতে হইবে।
                            <br>
                            <br>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <?php
        }
    } else {
        echo '<span style="color:red;font-weight: bold;">Sorry! No Data Available</span>';
    }
    ?>
</div>

<style>
    table.head-part tbody tr {
        border: none !important;
        text-align: left;
    }

    table.head-part tbody tr td {
        border: none !important;
        text-align: left;
    }
</style>

<style>
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 11px !important;
        font-family: 'times new roman', arial;
        background-color: #fff;
        margin: auto;
    }

    .csd th, .csd td {
        border: 1px solid #000;
        font-weight: normal;
    }

    table.reportTab tr th {
        background-color: #fff;
        color: #000;
        font-size: 11px !important;
    }

    table.reportTab tr, table.reportTab tr td, table.reportTab tr th {
        border: 1px solid #C0C0C0;
        color: #000;
    }

    .style_18, .style_20 {
        background-color: #fff !important;
    }

    .left {
        text-align: left
    }

    .right {
        text-align: right
    }

    .center {
        text-align: center
    }
</style>
