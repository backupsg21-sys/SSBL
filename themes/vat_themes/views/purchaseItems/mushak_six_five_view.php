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
<style>
    .reportTabCustom {
        page-break-after: always;
    }
</style>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD; min-height: 600px">
    <?php
    if ($data) {
        foreach ($data as $d) {
            $info = StockTransferHistory::model()->findByPk($d['id']);
            ?>

            <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show; width:6.3in">
                <tbody>
                <tr valign="top">
                    <td class="style_14">
                        <!--
            <img src="<?php Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                        <table class=""
                               style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                            <tbody>
                            <tr valign="top">
                                <td></td>
                                <td style=" overflow:hidden;font-size:12px;">
                                    <div class="style_16" style=" text-align:center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয়
                                        রাজস্ব বোর্ড<br><br></div>
                                </td>
                                <td style="padding-top: 11px;text-align: right;"><span
                                            style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক - ৬.৫</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td style="overflow: hidden; font-size: 12px;font-weight:bold; text-align: center"
                                    colspan="3">
                                    কেন্দ্রীয় নিবন্ধিত প্রতিষ্ঠানের পণ্য স্থানান্তর চালানপত্র
                                </td>
                            </tr>
                            <tr valign="top">
                                <td style="overflow: hidden; font-size: 11px; padding-top: 7px;text-align: center"
                                    colspan="3">
                                    [ বিধি ৪০ এর উপ-বিধি (১) এর দফা (ঙ) দ্রষ্টব্য ]
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <table class="style_15 secone-head"
                               style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_1">
                            <colgroup>
                                <col style=" width: 28%;">
                                <col style=" width: 20%;">
                                <col style=" width: 20%;">
                            </colgroup>
                            <tbody>
                            <tr valign="top">
                                <td class="style_20">
                                    নিবন্ধিত ব্যক্তির নাম :
                                </td>
                                <td colspan="3"><?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?></td>
                            </tr>
                            <tr valign="top">
                                <td>
                                    নিবন্ধিত ব্যক্তির বিআইএন :
                                </td>
                                <td colspan="3"><?= !empty($yourCompanyInfo['vat_regi_no']) ? $yourCompanyInfo['vat_regi_no'] : '' ?></td>
                            </tr>
                            <tr valign="top">
                                <td style="padding-bottom: 18px">
                                    প্রেরণকারী শাখার নাম ও ঠিকানা :
                                </td>
                                <td colspan="3">
                                    <?php
                                    if ($info['sent_from_store_id']) {
                                        echo $sentName = Stores::nameOfThis($info['sent_from_store_id']);
                                        echo ', ';
                                        echo $sentName = Stores::addressOfThis($info['sent_from_store_id']);
                                    }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="style_15 secone-head"
                               style="border-collapse: collapse; empty-cells: show; width: 100%;" id="__bookmark_1">
                            <colgroup>
                                <col style=" width: 18%;">
                                <col style=" width: 44%;">
                                <col style=" width: 13%;">
                            </colgroup>
                            <tbody>
                            <tr valign="top">
                                <td>
                                    গ্রহীতা শাখার নাম :
                                </td>
                                <td>
                                    <?php
                                    if ($info['received_to_store_id']) {
                                        echo $sentName = Stores::nameOfThis($info['received_to_store_id']);
                                    }
                                    ?>
                                </td>
                                <td>
                                    চালান নম্বর :
                                </td>
                                <td><?= $info['sl_no'] ?></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="2"></td>
                                <td>
                                    ইস্যুর তারিখ :
                                </td>
                                <td>
                                    <?php
                                    if ($info['sent_date']) {
                                        $sent_date = date('d-m-Y', strtotime($info['sent_date']));
                                        if ($sent_date)
                                            echo str_replace('-', '/', $sent_date);
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td>গ্রহীতা শাখার ঠিকানা :</td>
                                <td>
                                    <?php
                                    if ($info['received_to_store_id']) {
                                        echo $sentName = Stores::addressOfThis($info['received_to_store_id']);
                                    }
                                    ?>
                                </td>
                                <td>
                                    ইস্যুর সময় :
                                </td>
                                <td><?= date('H:i:s') ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;"
                               id="__bookmark_2">
                            <colgroup>
                                <col style=" width: 7%;">
                                <col style=" width: 30%;">
                                <col style=" width: 15%;">
                                <col style=" width: 15%;">
                                <col style=" width: 15%;">
                                <col style=" width: 18%;">
                            </colgroup>
                            <tbody>
                            <tr class="style_24" valign="top" align="center">
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_15_0f14491b-ba06-43a4-b36e-53b8f4830a9f"
                                         style=" text-align:center;">ক্রমিক <br>নং
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_16_799f4fb3-62c7-41eb-801b-85589258f509"
                                         style=" text-align:center;">পণ্যের (প্রযোজ্য ক্ষেত্রে <br> সুনিদির্স্ট ব্রান্ড
                                        নামসহ) বিবরণ
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_17_aae9367f-1e06-4e01-9fad-f1ab426c7e7f"
                                         style=" text-align:center;">পরিমাণ
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_18_41f11b16-ff6d-4a83-abb2-da2e3bb51023"
                                         style=" text-align:center;">কর ব্যতীত <br> মূল্য
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_19_642bcae5-9cfc-44b5-8f12-adb12d289be3"
                                         style=" text-align:center;">প্রযোজ্য করের পরিমাণ
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_20_99a89985-7916-4bbb-b6d6-e12acca75866"
                                         style=" text-align:center;">মন্তব্য
                                    </div>
                                </th>
                            </tr>
                            <tr class="style_24" valign="top" align="center">
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_21_fed556f7-5278-4a6c-b0cf-2eae27688a99"
                                         style=" text-align:center;">(১)
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_22_d5788eae-9194-427a-81e3-e1e39bdcd4ef"
                                         style=" text-align:center;">(২)
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_23_e0ffa7d2-9f4d-4b4c-9f4a-40e87c1cac73"
                                         style=" text-align:center;">(৩)
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_24_9a210563-692b-4f3c-bac1-4331ef42fac6"
                                         style=" text-align:center;">(৪)
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_25_26a7e1b7-bf2e-4b61-95b9-562f252c0edf"
                                         style=" text-align:center;">(৫)
                                    </div>
                                </th>
                                <th class="style_25" align="left">
                                    <div id="AUTOGENBOOKMARK_26_a9455378-a81c-4523-a7c0-4e8f8465f40a"
                                         style=" text-align:center;">(৬)
                                    </div>
                                </th>
                            </tr>
                            <?php
                            $total_qty = 0;
                            $total_mrp = 0;
                            $total_vat_amount = 0;
                            ?>
                            <tr valign="top" style="text-align:left;">
                                <td class="center">1</td>
                                <td>
                                    <?php
                                    $mrp = 0;
                                    $vat = 0;
                                    $vat_amount = 0;
                                    if (!empty($info['model_id']) && $prodData = ProdModels::model()->findByPk($info['model_id'])) {
                                        echo $prodData['model_name'];
                                        $mrp = $prodData['mrp'];
                                        $vat = $prodData['vat'];
                                        $vat_calculation_method = $prodData['vat_calculation_method'];
                                        if ($vat_calculation_method == 3) { // fixed
                                            $vat_amount = $info['sent_qty'] * $vat;
                                        } else { // percent
                                            $vat_amount = ($mrp * $vat) / 100;
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="center">
                                    <?= $info['sent_qty'] ?>
                                </td>
                                <td class="right"><?= number_format(round($mrp), 2) ?></td>
                                <td class="right"><?= number_format(round($vat_amount), 2) ?></td>
                                <td><?= $info['remarks'] ?></td>
                            </tr>
                            <?php
                            $total_qty += $info['sent_qty'];
                            $total_mrp += $mrp;
                            $total_vat_amount += $vat_amount;
                            ?>
                            <tr valign="top" style=" text-align:right;font-weight: bold;">
                                <td colspan="2">
                                    মোট
                                </td>
                                <td class="center"><?= $total_qty ?></td>
                                <td class="right"><?= number_format($total_mrp, 2) ?></td>
                                <td class="right"><?= number_format($total_vat_amount, 2) ?></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr valign="top">
                    <td class="style_37" style="padding-bottom: 25px">
                        <br><br><br>
                        প্রতিষ্ঠান কর্তৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নাম <br><br><br>
                        নাম ও পদবী : ...............................................<br><br>
                        মোবাইল নম্বর : ..............................................
                    </td>
                </tr>
                </tbody>
            </table>
            <br>

            <?php
        }
    } else {
        echo "<span style='color:red;font-weight: bold;'>Sorry! No Data Available</span>";
    }
    ?>


</div>

<style>
    .reportTabCustom {
        font-size: 11px;
        font-family: 'times new roman', arial;
        background-color: #fff;
        margin: auto;
    }

    .style_23, .style_25, .style_32, .style_34, .style_36 {
        border: 1px solid #000 !important;
    }

    .style_23 td {
        border: 1px solid #000;
    }

    .right {
        text-align: right
    }

    .center {
        text-align: center
    }
</style>
<style>
    .secone-head td {
        line-height: 15px;
    }
</style>