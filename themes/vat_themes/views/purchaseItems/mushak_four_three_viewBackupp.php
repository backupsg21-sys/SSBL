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
?>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD; min-height: 600px">
    <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show; width: 100%;">
        <tbody>
        <tr valign="top">
            <td class="style_14">
                <!--
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                <table class=""
                       style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <tr valign="top">
                        <td></td>
                        <td style=" overflow:hidden;font-size:15px;width:70%;">
                            <div class="style_16" style=" text-align:center;"><b>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</b><br>জাতীয়
                                রাজস্ব বোর্ড<br><br></div>
                        </td>
                        <td style="padding-top: 12px;text-align: right;"><span
                                    style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক - ৪.৩</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                            <b>উপকরণ-উৎপাদ সহগ (Input-Output Coefficient) ঘোষণা <br>
                                (বিধি ২১ দ্রষ্টব্য) </b>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <table class="style_19"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;margin-bottom: 5px">
                    <colgroup>
                        <col style=" width: 15%;">
                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td style="font-weight:bold;">
                            প্রতিষ্ঠানের নামঃ
                        </td>
                        <td><?= !empty($yourCompanyInfo->company_name) ? $yourCompanyInfo->company_name : '' ?></td>
                    </tr>
                    <tr valign="top">
                        <td style="font-weight:bold;">
                            ঠিকানাঃ
                        </td>
                        <td><?= !empty($yourCompanyInfo->house) ? $yourCompanyInfo->house . ', ' : '' ?>
                            <?= !empty($yourCompanyInfo->road) ? $yourCompanyInfo->road . ', ' : '' ?>
                            <?= !empty($yourCompanyInfo->location) ? $yourCompanyInfo->location : '' ?></td>
                    </tr>
                    <tr valign="top">
                        <td style="font-weight:bold;">
                            বিনঃ
                        </td>
                        <td><?= !empty($yourCompanyInfo->vat_regi_no) ? $yourCompanyInfo->vat_regi_no : '' ?></td>
                    </tr>
                    <tr valign="top">
                        <td style="font-weight:bold;">
                            দাখিলের তারিখঃ
                        </td>
                        <td class="submit_date"></td>
                    </tr>
                    <tr valign="top">
                        <td style="font-weight:bold;" colspan="2">
                            ঘোষিত সহগ অনুযায়ী পন্য/সেবার প্রথম সরবরাহের তারিখঃ <span class="submit_date"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                       id="__bookmark_5">
                    <colgroup>
                        <col style=" width: 5%;">
                        <col style=" width: 8%;">
                        <col style=" width: 10%;">
                        <col style=" width: 5%;">
                        <col style=" width: 15%;">
                        <col style=" width: 8%;">
                        <col style=" width: 8%;">
                        <col style=" width: 8%;">
                        <col style=" width: 6%;">
                        <col style=" width: 15%;">
                        <col style=" width: 7%;">
                        <col style=" width: 5%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_5" valign="top">
                        <th class="style_24" rowspan="2" align="center">
                            <div id="AUTOGENBOOKMARK_13_d28d363f-4d66-4d9f-a5f0-50bc3e1cf35f"
                                 style=" text-align:center;">ক্রমিক সংখ্যা
                            </div>
                        </th>
                        <th class="style_24" rowspan="2" align="center">
                            <div id="AUTOGENBOOKMARK_14_ef4181d8-a443-4862-93a2-47a5f5533b12"
                                 style=" text-align:center;">পণ্যের এইচ এস কোড / সেবা কোড
                            </div>
                        </th>
                        <th class="style_24" rowspan="2" align="center">
                            সরবরাহতব্য পণ্য/সেবার নাম ও বর্ণনা (প্রযোজ্য ক্ষেত্রে ব্র্যান্ড নামসহ)
                        </th>
                        <th class="style_24" rowspan="2" align="center">
                            <div style=" text-align:center;">সরবরাহের একক</div>
                        </th>
                        <th class="style_24" colspan="5" align="center">
                            একক পণ্য/সেবা সরবরাহে ব্যবহার্য যাবতীয় উপকরণের/ কাঁচামালের ও প্যাকিং সামগ্রীর বিবরণ, পরিমাণ
                            ও ক্রয়মূল্য (উপকরনভিত্তিক অপচয়ের শতকরা হারসহ)
                        </th>
                        <th class="style_24" colspan="2" align="center">
                            মূল্য সংযোজনের <br> বিবরণ
                        </th>
                        <th class="style_24" rowspan="2" align="center">
                            মন্তব্য
                        </th>
                    </tr>
                    <tr class="style_5" valign="top" align="center">
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_20_4ee75c87-d86d-4fb6-8912-c7e693822865"
                                 style=" text-align:center;">বিবরণ
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_21_8ebb6810-16f4-4370-b407-1f7229ead72d"
                                 style=" text-align:center;">অপচয়সহ পরিমাণ
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_22_71590e5c-3c8d-4ca8-acb1-0a332ac271ec"
                                 style=" text-align:center;">মূল্য (টাকা)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_23_32ccc6e6-c7dc-4139-a094-283a015f8b2f"
                                 style=" text-align:center;">অপচয়ের পরিমাণ
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_24_7c2edbd9-f814-4859-86c4-bb24b6f7c115"
                                 style=" text-align:center;">শতকরা হার
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_25_b83ed771-05c9-48ef-aa48-8a1350add6eb"
                                 style=" text-align:center;">মূল্য সংযোজনের <br>খাত
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_26_806ac25f-f16c-4ef3-9cdc-5db1a9a0bf87"
                                 style=" text-align:center;">মূল্য (টাকা)
                            </div>
                        </th>
                    </tr>
                    <tr class="style_21" valign="top" align="center">
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_27_b012feae-51b6-4614-90dc-a31ae48cfb3e"
                                 style=" text-align:center;">(১)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_28_c993fbec-87c2-44b4-b1bd-339147df9201"
                                 style=" text-align:center;">(২)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_29_a2bdd6d7-ef57-48e3-bffb-801cd869e757"
                                 style=" text-align:center;">(৩)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_30_13e51093-d07b-4fbb-8e3d-9652ba176f7f"
                                 style=" text-align:center;">(৪)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_31_7bf5b009-564b-45b5-8711-2718dd2715c6"
                                 style=" text-align:center;">(৫)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_32_f2c8ecc6-94c6-4220-95a8-c74a2af8e421"
                                 style=" text-align:center;">(৬)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_33_4696cbbb-f565-4c92-b798-28f683df6853"
                                 style=" text-align:center;">(৭)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_34_9795edf1-aee4-4486-a7fc-08d877a9d779"
                                 style=" text-align:center;">(৮)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_35_1b581ab5-a0f2-4d57-8353-8ebc15fdfd92"
                                 style=" text-align:center;">(৯)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_36_da4b27a0-213d-44b4-9117-328f4d128536"
                                 style=" text-align:center;">(১০)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_37_1bb0647f-244a-446a-835a-58e03b81a740"
                                 style=" text-align:center;">(১১)
                            </div>
                        </th>
                        <th class="style_24" align="left">
                            <div id="AUTOGENBOOKMARK_38_8b76da6d-a5d5-434d-9d56-9b35021c301b"
                                 style=" text-align:center;">(১২)
                            </div>
                        </th>
                    </tr>
                    <?php
                    $remarks = '';
                    if ($data) {
                        foreach ($data as $d) {
                            ?>
                            <tr class="style_27" style="word-break: break-all;">
                                <td class="center">1</td>
                                <td style="text-align: center;"><?= !empty($d['hs_code']) ? $d['hs_code'] : '' ?></td>
                                <td style="padding-left:5px"><?= !empty($d['model_name']) ? $d['model_name'] : '' ?></td>
                                <td class="center modelQtyUnit">
                                    <?php
                                    if (!empty($d['model_qty_unit_id'])) {
                                        echo $unit = Units::model()->unitLabel($d['model_qty_unit_id']);
                                    }
                                    ?>
                                </td>
                                <td style="width: 170px"></td>
                                <td style="width: 90px"></td>
                                <td style="width: 90px"></td>
                                <td style="width: 90px"></td>
                                <td></td>
                                <td style="width: 170px"></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <?php
                            $material_name = [];
                            $material_qty_waste_qty = [];
                            $material_waste_qty = [];
                            $total_price = [];
                            $waste_percent = [];

                            ?>
                            <tr>
                                <td class="ccsd"></td>
                                <td class="ccsd"></td>
                                <td class="ccsd"></td>
                                <td class="ccsd"></td>
                                <td class="ccsd" colspan="5" style="vertical-align: top;">
                                    <?php
                                    $rmInfo = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $d['id'], 'approve_count' => 1, 'cost_type' => 1));
                                    $rmSize = count($rmInfo);

                                    $adjInfo = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $d['id'], 'approve_count' => 1, 'cost_type' => 2));
                                    $adjSize = count($adjInfo);

                                    $addRMInfo = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $d['id'], 'approve_count' => 1, 'cost_type' => 3));
                                    $addRMSize = count($addRMInfo);

                                    $total_ext_row = ($rmSize + $addRMSize) - $adjSize;
                                    ?>
                                    <table cellspacing="0" cellpadding="0" class="inner_table" style="height: 100%;">
                                        <?php
                                        $total_material_price = 0;
                                        $total_waste_qty = 0;
                                        foreach ($rmInfo as $info) {
                                            $remarks = $info['remarks'];
                                            $material_name = ProdModels::model()->prodNameOfThis($info['rm_model_id']);
                                            $qty_unit_name = Units::model()->unitLabel($info['rm_qty_unit_id']);
                                            $waste_qty_unit_name = Units::model()->unitLabel($info['waste_qty_unit_id']);

                                            $ttl = $info['rm_qty'] + $info['waste_qty'];
                                            $material_qty_waste_qty_label = $ttl . ' ' . $qty_unit_name;

                                            $material_waste_qty = $info['waste_qty'];
                                            $material_waste_qty_label = $material_waste_qty . ' ' . $waste_qty_unit_name;

                                            $total_price = round($info['total_price'] * $ttl, 4);
                                            $waste_percent = $ttl > 0 ? round(($info['waste_qty'] * 100) / $ttl, 2) : '0';
                                            ?>
                                            <tr>
                                                <td class="ccsd" style="width: 168px;">
                                                    <?= $material_name ?>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                    <?= $material_qty_waste_qty_label ?>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                    <?= $total_price ?>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                    <?= $material_waste_qty_label ?>
                                                </td>
                                                <td class="ccsd right"
                                                    style="width: 65px; border-right: 1px solid #fff">
                                                    <?= $waste_percent ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $total_material_price += $total_price;
                                            $total_waste_qty += $material_waste_qty;
                                        }
                                        if ($total_ext_row < 0) {
                                            for ($p = 0; $p < abs($total_ext_row); $p++) {
                                                echo "<tr>
                                                    <td class='ccsd'>&nbsp;</td>
                                                    <td class='ccsd'></td>
                                                    <td class='ccsd'></td>
                                                    <td class='ccsd'></td>
                                                    <td class='ccsd' style='border-right: 1px solid #fff'></td>
                                                    </tr>";
                                            }
                                        }
                                        ?>
                                    </table>

                                    <table cellspacing="0" cellpadding="0" class="inner_table">
                                        <?php
                                        if ($addRMSize > 0) {
                                            ?>
                                            <tr>
                                                <td class="ccsd" style="width: 168px">
                                                    <b>Other Raw Material</b>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                </td>
                                                <td class="ccsd right"
                                                    style="width: 65px; border-right: 1px solid #fff">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        //$total_material_price = 0;
                                        //$total_waste_qty = 0;
                                        foreach ($addRMInfo as $addInfo) {
                                            $material_name = ProdModels::model()->prodNameOfThis($addInfo['rm_model_id']);
                                            $total_price_add = round($addInfo['total_price'], 4);
                                            ?>
                                            <tr>
                                                <td class="ccsd" style="width: 168px">
                                                    <?= $material_name ?>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">

                                                </td>
                                                <td class="ccsd right" style="width: 90px">
                                                    <?= $total_price_add ?>
                                                </td>
                                                <td class="ccsd right" style="width: 90px">

                                                </td>
                                                <td class="ccsd right"
                                                    style="width: 65px; border-right: 1px solid #fff">

                                                </td>
                                            </tr>
                                            <?php
                                            $total_material_price += $total_price_add;
                                            /*$total_waste_qty += $material_waste_qty;*/
                                        }
                                        /*if($total_ext_row < 0){
                                            for($p = 0; $p < abs($total_ext_row); $p++){
                                                echo "<tr>
                                                <td class='ccsd'>&nbsp;</td>
                                                <td class='ccsd'></td>
                                                <td class='ccsd'></td>
                                                <td class='ccsd'></td>
                                                <td class='ccsd' style='border-right: 1px solid #fff'></td>
                                                </tr>";
                                            }
                                        }*/
                                        ?>
                                    </table>

                                </td>
                                <td class="ccsd" colspan="2" style="vertical-align: top">
                                    <table cellspacing="0" cellpadding="0" class="inner_table"
                                           style="height:100% !important;">
                                        <?php
                                        $total_adj_price = 0;
                                        foreach ($adjInfo as $adj) {
                                            $name_data = ChartOfAc::model()->nameOfThis($adj['rm_model_id']);
                                            $total_price_adj = $adj['total_price'];
                                            ?>
                                            <tr>
                                                <td class="ccsd" style="width: 167px;height: 15px;">
                                                    <?= $name_data ?>
                                                </td>
                                                <td class="ccsd right" style="width:72px; border-right: 1px solid #fff">
                                                    <?= $total_price_adj ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $total_adj_price += $total_price_adj;
                                        }

                                        if ($total_ext_row > 0) {
                                            for ($q = 0; $q < abs($total_ext_row); $q++) {
                                                echo "<tr>
                                                    <td class='ccsd' style='height: 15px;'>&nbsp;</td>
                                                    <td class='ccsd' style='border-right: 1px solid #fff'></td>
                                                    </tr>";
                                            }
                                        }
                                        if ($total_adj_price == 0) {
                                            ?>
                                            <tr>
                                                <td class="ccsd" style="width: 167px;height: 15px;"></td>
                                                <td class="ccsd right"
                                                    style="width:72px; border-right: 1px solid #fff"></td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                        if ($total_ext_row < 0) {
                                            for ($p = 0; $p < abs($total_ext_row); $p++) {
                                                echo '<tr>
                                                            <td class="ccsd" style="width: 168px;height: 15px;"></td>
                                                            <td class="ccsd right" style="width:72px; border-right: 1px solid #fff"></td>
                                                        </tr>';
                                            }
                                        }
                                        ?>
                                    </table>
                                </td>
                                <td class="ccsd">
                                    <?= $remarks ?>
                                </td>
                            </tr>
                            <tr class="cctr">
                                <td class="right" colspan="6">মোট</td>
                                <td class="right"
                                    style="padding-right: 5px;"><?= !empty($total_material_price) ? $total_material_price : '' ?></td>
                                <td class="right"><?//=!empty($total_waste_qty)? $total_waste_qty:''
                                    ?></td>
                                <td colspan="2"></td>
                                <td class="right"
                                    style="padding-right: 5px;"><?= !empty($total_adj_price) ? $total_adj_price : '' ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <?php
                        }
                    }

                    ?>
                    </tbody>
                </table>
                <table class="style_36"
                       style="border-collapse: collapse; empty-cells: show; width: 100%; margin-top:10px;">
                    <colgroup>
                        <col style=" width: 80%;">
                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td style="text-align: right; font-weight: bold">
                            প্রতিষ্ঠান কর্তৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নামঃ <br>
                            পদবীঃ <br>
                            সাক্ষরঃ <br>
                            সীলঃ
                        </td>
                        <td></td>
                    </tr>
                    <tr valign="top">
                        <td style="font-weight: bold">বিশেষ দ্রষ্টব্যঃ</td>
                    </tr>
                    <tr valign="top">
                        <td colspan="2" style="padding-left: 25px">
                            ১। যেকোনো পণ্য সরবরাহের নুন্যতম ১৫ দিন পূর্বে অনলাইনে মূসক কম্পিউটার সিস্টেমে বা সংশ্লিষ্ট
                            বিভাগীয় কর্মকর্তার দপ্তরে উপকরণ উৎপাদ সহগ ঘোষণা দাখিল করিতে হইবে।&nbsp;
                            <br>২। পন্যমূল্য উপকরণ/কাঁচামাল বা&nbsp; মূল্য সংযোজনের খাতের পরিমাণ বা মূল্যে ৭.৫% এর বেশী
                            পরিবর্তন হইলে নতুন ঘোষণা দাখিল করিতে হইবে।&nbsp;
                            <br>৩। পণ্য ক্রয়ের স্বপক্ষে প্রামাণিক দলিল হিসাবে বিল অব এন্ট্রি বা চালানপত্রের কপি সংযুক্ত
                            করিতে হইবে।
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <style>
        .reportTabCustom {
            font-size: 11px;
            background-color: #fff;
            max-width: 11.7in;
            margin: auto;
            font-family: 'times new roman', arial;
        }

        .style_5 th, .style_21 th, .style_27 td {
            border: 1px solid #000 !important; /*#c0c0c0*/
            font-weight: normal;
        }

        .style_5 th {
            text-align: center;
        }

        .style_19 {
            border: none;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .ccsd {
            /*border-top:1px solid #fff !important;
            border-bottom:0px solid #fff;
            border-left:1px solid #000;
            border-right:1px solid #000;*/
            border: 1px solid #000;
        }

        .cctr td {
            border: 1px solid #000 !important;
        }

        .inner_table td {
            border: 1px solid #fff;
            border-right: 1px solid #000;
            padding-left: 5px;
            padding-right: 5px;
        }

        .border-bottom-null {
            border-bottom: 1px solid #fff
        }

        .border-right-null {
            border-right: 1px solid #fff
        }
    </style>