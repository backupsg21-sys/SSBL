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
$printInfo = SalesReturn::model()->findByPk($data[0]['id']);
$created_by = $data[0]['created_by'];
?>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD">
    <table class="reportTabCustom">
        <tbody>
        <tr>
            <td>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg"
                     style="width: 80px;position: absolute;"/>
                <table class=""
                       style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <tr valign="top">
                        <td></td>
                        <td style=" overflow:hidden;font-size:12px;width: 55%;">
                            <div class="style_16" style=" text-align:center;">
                                গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয় রাজস্ব বোর্ড<br><br>
                            </div>

                        </td>
                        <td style="padding-top: 11px;text-align: left;">
                            <?php
                            if ($printInfo['print_status'] > 0) { ?>
                                <span style="font-size: 20px;color: #423f3f;padding: 3px 10px 3px 90px; font-family: arial; position:absolute;margin-top:40px;">Duplicate</span>
                            <?php } ?>
                            <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">মূসক-৬.৭</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3"><br>
                            <b> ক্রেডিট নোট</b><br>
                            [ বিধি ৪০ এর উপবিধি&nbsp; (১) এর দফা (ছ) দ্রষ্টব্য ]
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <?php
                $customerData = Customers::model()->findByPk($salesData['customer_id']);
                $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
                ?>
                <table class="style_14"
                       style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                    <colgroup>
                        <col style=" width: 20%;">
                        <col>
                        <col style=" width: 20%;">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            <div id="AUTOGENBOOKMARK_9_d5651028-5fef-4a51-bcbd-7b47b29c3ed0">ফেরত প্রদানকারী ব্যক্তির -&nbsp;</div>
                        </td>
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;">
                            <div id="AUTOGENBOOKMARK_10_8256787e-83a3-4a28-929a-33f8673c21a7">ফেরত গ্রহনকারী ব্যক্তির -&nbsp;</div>
                        </td>
                        <td style=" overflow:hidden;"></td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            নাম
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($customerData['company_name']) ? $customerData['company_name'] : '' ?>
                        </td>
                        <td style=" overflow:hidden;">
                            নাম
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            বিআইএন
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($customerData['bin']) ? $customerData['bin'] : '' ?>
                        </td>
                        <td style=" overflow:hidden;">
                            বিআইএন
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($yourCompanyInfo['vat_regi_no']) ? $yourCompanyInfo['vat_regi_no'] : '' ?>
                            <div id="__bookmark_4">
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            মূল চালান নম্বর
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($salesData['mushok_no']) ? $salesData['mushok_no'] : '' ?>
                        </td>
                        <td style=" overflow:hidden;">
                            ক্রেডিট নোট নম্বর
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($data[0]['sl_no']) ? 'SO/CL-' . $data[0]['sl_no'] : '' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            <div>মূল চালান ইস্যুর তারিখ :&nbsp;</div>
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($salesData['sales_date']) ? $salesData['sales_date'] : '' ?>
                        </td>
                        <td style=" overflow:hidden;">
                            ইস্যুর তারিখ
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= !empty($data[0]['return_date']) ? date('d-m-Y', strtotime($data[0]['return_date'])) : '' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;">
                            ইস্যুর সময়
                        </td>
                        <td style=" overflow:hidden;">
                            :&nbsp;&nbsp;<?= date('H:i:s') ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;"></td>
                        <td style=" overflow:hidden;"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="style_16" style="text-align:center;background-color:#fff;font-size:11px;padding-top: 10px">
                    সরবরাহের বিবরন&nbsp;
                </div>
                <table class="style_17"
                       style="border-collapse: collapse; border: 1px solid #000;empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                    <colgroup>
                        <col style=" width: 5%;">
                        <col style=" width: 20%;">
                        <col style=" width: 12%;">
                        <col style=" width: 20%;">
                        <col style=" width: 20%;">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr class="style_18" valign="top" align="center">
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_21_853f392a-cbed-4c9b-a5a0-d2579502089f"
                                 style=" text-align:center;">ক্রমিক সংখ্যা
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_22_981c7689-3e98-4cea-84d7-53af198e9d59"
                                 style=" text-align:center;">ফেরতপ্রাপ্ত সরবরাহের বিবরন&nbsp;
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_23_d22826af-ceb0-49e1-811a-aafffcfaea27"
                                 style=" text-align:center;">সরবরাহের একক&nbsp;
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_24_befec9ab-804d-4735-a9ec-0d5291ee46d6"
                                 style=" text-align:center;">পরিমাণ&nbsp;
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div class="style_20" id="AUTOGENBOOKMARK_25_0d72739c-2753-4ac6-a670-c2063bb77730"
                                 style=" text-align:center;">একক মূল্য <sup>১</sup> (টাকায়)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_26_59b1f33f-4119-4bef-a0b6-5a2b776436af"
                                 style=" text-align:center;">মোট মূল্য (টাকায়)
                            </div>
                        </th>
                    </tr>
                    <tr class="style_18" valign="top" align="center">
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_27_82d3562f-4c07-48a9-9f2e-5fc90e083f9f"
                                 style=" text-align:center;">(১)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_28_86893d65-3275-45f1-98c4-3c4c364dff8e"
                                 style=" text-align:center;">(২)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_29_908debcf-2494-43ff-9299-0351b90a8174"
                                 style=" text-align:center;">(৩)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_30_df9d2755-7886-4d69-9a33-31e1d4f8610c"
                                 style=" text-align:center;">(৪)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_31_e5b71a8e-0c82-471b-a2bb-af7073676e10"
                                 style=" text-align:center;">(৫)
                            </div>
                        </th>
                        <th class="style_19" style=" overflow:hidden;" align="left">
                            <div id="AUTOGENBOOKMARK_32_b1d3ff26-c599-4d6e-bb44-9d59c709f06f"
                                 style=" text-align:center;">(৬)
                            </div>
                        </th>
                    </tr>
                    <?php
                    if (!empty($data)) {
                        $i = 1;
                        $sd = 0;
                        $vat = 0;
                        $final_price = 0;
                        $net_total_new = 0;
                        $raw_vat = 0;
                        $raw_sd = 0;
                        foreach ($data as $dt) {
                            if ($dt['parts_service'] == 1) { // 1=parts, 2=service
                                $prodInfo = ProdModels::model()->findByPk($dt['model_id']);
                                $model_name = !empty($prodInfo['model_name']) ? $prodInfo['model_name'] : '';
                            } else if ($dt['parts_service'] == 2) { // 1=parts, 2=service
                                $prodInfo = Services::model()->findByPk($dt['model_id']);
                                $model_name = !empty($prodInfo['name']) ? $prodInfo['name'] : '';
                            } else {
                                $model_name = '';
                            }
                            ?>
                            <tr valign="top">
                                <td class="style_21 center" style=" overflow:hidden;">
                                    <?= $i ?>
                                </td>
                                <td class="style_21" style=" overflow:hidden;">
                                    <?= !empty($model_name) ? $model_name : '' ?>
                                </td>
                                <td class="style_21 center" style=" overflow:hidden;">
                                    <?php
                                    if (!empty($dt['unit_id'])) {
                                        echo Units::model()->unitLabel($dt['unit_id']);
                                    }
                                    ?>
                                </td>
                                <td class="style_21 center" style=" overflow:hidden;padding-right:5px">
                                    <?= !empty($dt['qty']) ? $dt['qty'] : 0 ?>
                                </td>
                                <td class="style_21 right" style=" overflow:hidden;">
                                    <?php
                                    if ($dt['qty'] > 0) {
                                        echo number_format(round($dt['net_total_new'] / $dt['qty'], 2), 2);
                                    } else {
                                        number_format(round($dt['net_total_new'], 2), 2);
                                    }
                                    ?>
                                </td>
                                <td class="style_21 right" style=" overflow:hidden;">
                                    <?= !empty($dt['net_total_new']) ? number_format(round($dt['net_total_new'], 2), 2) : 0.00 ?>
                                </td>
                            </tr>

                            <?php
                            $i++;
                            $net_total_new += $dt['net_total_new'];
                            $raw_vat += $dt['vat_new'];
                            $raw_sd += $dt['sd_new'];
                        }
                    }
                    ?>

                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14" id="AUTOGENBOOKMARK_33_af76f376-92e4-49e4-9d24-89df9600ab17">মোট মূল্য&nbsp;</div>
                        </td>
                        <td class="style_25 right" style=" overflow:hidden;">
                            <?= number_format(round($net_total_new, 2), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14">বাদ কর্তন <sup>২</sup>
                            </div>
                        </td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_28" style=" text-align:right;">0.00</div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14" id="AUTOGENBOOKMARK_34_ece06f64-b1e0-4dae-80dc-d496c377ef01">মূসকসহ
                                মূল্য
                            </div>
                        </td>
                        <td class="style_25 right" style=" overflow:hidden;">
                            <?= number_format(round($net_total_new, 2), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14" id="AUTOGENBOOKMARK_35_02bd93af-9549-4d24-85dd-402b94195703">মূসকের
                                পরিমাণ&nbsp;
                            </div>
                        </td>
                        <td class="style_25 right" style=" overflow:hidden;">
                            <?= number_format(round($raw_vat, 2), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14" id="AUTOGENBOOKMARK_36_e4c83e52-6a1a-47be-8832-5581cfb0af4f">সম্পূরক
                                শুল্কের পরিমাণ&nbsp;
                            </div>
                        </td>
                        <td class="style_25 right" style=" overflow:hidden;">
                            <?= number_format(round($raw_sd, 2), 2) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25" style=" overflow:hidden;">
                            <div class="style_14">মোট কর <sup>৩</sup>
                            </div>
                        </td>
                        <td class="style_25 right" style=" overflow:hidden;">
                            <?= number_format(round($raw_vat + $raw_sd, 2), 2) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="style_14" style="background-color:#fff;font-size:11px;">ফেরতের কারণ&nbsp;:</div>
                <?= !empty($data[0]['reason']) ? $data[0]['reason'] : '' ?>
                <table class="style_14"
                       style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;"
                       id="AUTOGENBOOKMARK_39_0d6c9c35-2a86-4028-b31e-6860dd26eac5">
                    <colgroup>
                        <col>
                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            <div class="style_38" id="__bookmark_11">
                                <div style="visibility:hidden">&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style=" overflow:hidden;">
                            <div class="style_14">&nbsp;&nbsp;<br>দায়িত্বপ্রাপ্ত ব্যক্তির স্বাক্ষর&nbsp; <img src="<?php echo Yii::app()->baseUrl; Users::getUserSignature($created_by); ?>" style="width: 80px;position: absolute; margin-top:-5px"/><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>১
                                প্রতি একক পণ্য / সেবার মূসক ও সম্পূরক শুল্ক সহ মূল্য ।&nbsp;<br>২ ফেরত প্রদানের জন্য
                                কোনো কর্তন থাকইলে উহার পরিমাণ ।<br>৩ মূসক ও সম্পূরক শুল্কের যোগফল
                                ।&nbsp;<br><br><br><br></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
</div>
</td>
</tr>
</tbody>
</table>
<?php
if ($printInfo['print_status'] < 1) {
    $printInfo->print_status = 1;
    $printInfo->save();
}
?>
<style>
    .style_17 tr:hover, .style_101 tr:hover {
        background: #e7e7e7;
    }

    .reportTabCustom {
        font-size: 11px;
        background-color: #fff;
        max-width: 8.3in;
        margin: auto;
        font-family: 'times new roman', arial;
    }

    .style_18 tr th {
        border: 1px solid #000 !important; /*#c0c0c0*/
    }

    .style_19, .style_21 {
        border: 1px solid #000 !important;
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
</style>