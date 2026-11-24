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
    .left {
        text-align: left
    }

    .right {
        text-align: right
    }
</style>
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;min-height:600px;">
    <?php
    $customer_info = Customers::model()->findByPk($data['customer_id']);
    $sales_id = $data['id'];
    $sales_date = $data['sales_date'];

    $printInfo = SellItems::model()->findByPk($sales_id);
    ?>
    <table class="reportTabCustom"
           style="border-collapse: collapse; empty-cells: show; width: 8.3in;  table-layout:fixed;text-align:right;">
        <tbody>
        <tr valign="top" style="background-color: #fff !important;">
            <th colspan="10">

                <table class="head-part"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;  table-layout:fixed;font-weight: normal">
                    <tbody>
                    <tr valign="top">
                        <td colspan="6">
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
                                    <td style="padding-top: 11px;text-align: right;">
                                        <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">মূসক-৬.৩</span>
                                        <?php
                                        if ($printInfo['print_status'] > 0) { ?>
                                            <div style="font-size: 20px;color: #423f3f;font-family:arial;margin-top:40px;">
                                                Duplicate
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                                        <b>কর চালানপত্র <br>
                                            [বিধি ৪০ এর উপ-বিধি (১) এর দফা (গ) ও দফা (চ) দ্রষ্টব্য] </b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td colspan="2"></td>
                        <td>
                            <br>
                            নিবন্ধিত ব্যক্তির নাম
                        </td>
                        <td colspan="2">
                            <br>
                            : <?= !empty($yourCompanyInfo->company_name) ? $yourCompanyInfo->company_name : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td colspan="2"></td>
                        <td>
                            নিবন্ধিত ব্যক্তির বিআইএন
                        </td>
                        <td colspan="2">
                            : <?= !empty($yourCompanyInfo->vat_regi_no) ? $yourCompanyInfo->vat_regi_no : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td colspan="2"></td>
                        <td>
                            চালানপত্র ইস্যুর ঠিকানা
                        </td>
                        <td colspan="2">
                            : <?= !empty($yourCompanyInfo->house) ? $yourCompanyInfo->house . ', ' : '' ?>
                            <?= !empty($yourCompanyInfo->road) ? $yourCompanyInfo->road . ', ' : '' ?>
                            <?= !empty($yourCompanyInfo->location) ? $yourCompanyInfo->location : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td>
                            ক্রেতার নাম
                        </td>
                        <td colspan="2">
                            : <?= $customer_info['company_name'] ? $customer_info['company_name'] : '' ?>
                        </td>
                        <td>
                            চালানপত্র নম্বর
                        </td>
                        <td colspan="2">
                            : <?= $data['mushok_no'] ? $data['mushok_no'] : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td>
                            ক্রেতার বিআইএন
                        </td>
                        <td colspan="2">
                            : <?= !empty($customer_info['bin']) ? $customer_info['bin'] : '' ?>
                        </td>
                        <td>
                            ইস্যুর তারিখ
                        </td>
                        <td colspan="2">
                            : <?= $data['sales_date'] ? date('d-m-Y', strtotime($data['sales_date'])) : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td>
                            সরবরাহের গন্তব্যস্থল
                        </td>
                        <td colspan="2">
                            : <?= $printInfo['delivery_address'] ? $printInfo['delivery_address'] : '' ?>
                        </td>
                        <td>
                            ইস্যুর সময়
                        </td>
                        <td colspan="2">
                            : <?= $data['created_datetime'] ? date('H:i', strtotime($data['created_datetime'])) : '' ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </th>
        </tr>
        <tr class="style_94" valign="top">
            <th>
                <div style=" text-align:center;">ক্রমিক নং</div>
            </th>
            <th>
                <div style=" text-align:center;">পণ্য বা সেবার বর্ণনা (প্রযোজ্য ক্ষেত্রে ব্র্যান্ড নামসহ)</div>
            </th>
            <th>
                <div style=" text-align:center;">সরবরাহের একক&nbsp;</div>
            </th>
            <th>
                <div style=" text-align:center;">পরিমাণ</div>
            </th>
            <th align="center">
                <span style=" text-align:center;">একক মূল্য</span>
                <span style=" text-align:center;">১</span>
                <div style=" text-align:center;">(টাকায়)</div>
            </th>
            <th align="center">
                <span style=" text-align:center;">মোট মূল্য</span>
                <span style=" text-align:center;">১</span>
                <div style=" text-align:center;">(টাকায়)</div>
            </th>
            <th align="center">
                <div style=" text-align:center;">সম্পূরক শুল্কের পরিমাণ (টাকায়)</div>
            </th>
            <th align="center">
                <div style=" text-align:center;">মূল্য সংযোজন করের হার/সুনির্দিষ্ট কর</div>
            </th>
            <th class="style_106">
                <div style=" text-align:center;">মূল্য সংযোজন কর/সুনির্দিষ্ট কর এর পরিমাণ (টাকায়)</div>
            </th>
            <th align="center">
                <div>সকল প্রকার শুল্ক ও করসহ মূল্য</div>
            </th>
        </tr>
        <?php
        $sql = "SELECT * FROM sales_details WHERE sales_id = $sales_id AND sales_date = '$sales_date' ORDER BY id DESC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $items = $command->queryAll();
        $i = 1;
        $_total_price = [];
        $_total_sd = [];
        $_total_vat = [];
        $_total_net_total = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $model_id = $item['model_id'];
                $parts_service = $item['parts_service'];

                if ($parts_service == 1) {
                    $model_name = ProdModels::model()->prodNameOfThis($model_id);
                } else if ($parts_service == 2) {
                    $model_name = Services::model()->prodNameOfThis($model_id);
                } else {
                }
                $unit_name = Units::model()->unitLabel($item['unit_id']);
                ?>
                <tr valign="top" class="style_94">
                    <td>
                        <div style="display: none;">1.00</div>
                        <div class="style_34" style=" text-align:center;"><?= $i ?></div>
                    </td>
                    <td class="left">
                        <?= !empty($model_name) ? $model_name : '' ?>
                    </td>
                    <td align="center">
                        <?= !empty($unit_name) ? $unit_name : '' ?>
                    </td>
                    <td align="center">
                        <?= !empty($item['sales_qty']) ? $item['sales_qty'] : '0' ?>
                    </td>
                    <td class="right">
                        <?= !empty($item['unit_price']) ? $item['unit_price'] : '0' ?>
                    </td>
                    <td align="right">
                        <?= !empty($item['total']) ? number_format($_total_price[] = $item['total'], 2) : '0.00' ?>
                    </td>
                    <td align="right">
                        <?= !empty($item['sd']) ? number_format($_total_sd[] = $item['sd'], 2) : '0.00' ?>
                    </td>
                    <td>
                        <?= !empty($item['vat_percent']) ? $item['vat_percent'] : '0' ?> %
                    </td>
                    <td>
                        <?= !empty($item['vat']) ? number_format($_total_vat[] = $item['vat'], 2) : '0.00' ?>
                    </td>
                    <td>
                        <?= !empty($item['net_total']) ? number_format($_total_net_total[] = $item['net_total'], 2) : '0.00' ?>
                    </td>
                </tr>

                <?php
                $i++;
            }
        }
        ?>
        <tr valign="top" style="font-weight:bold" class="style_94">
            <td colspan="5">
                <div style=" text-align:right;">সর্বমোট</div>
            </td>
            <td>
                <?php echo number_format(array_sum($_total_price), 2) ?>
            </td>
            <td>
                <?php echo number_format(array_sum($_total_sd), 2) ?>
            </td>
            <td></td>
            <td>
                <?php echo number_format(array_sum($_total_vat), 2) ?>
            </td>
            <td>
                <?php echo number_format(array_sum($_total_net_total), 2) ?>
            </td>
        </tr>
        <tr valign="top">
            <td colspan="10" align="right">
                <div style=" text-align:left;">
                    <br>প্রতিষ্ঠান কর্তৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নামঃ
                </div>
                <div style=" text-align:left;">পদবীঃ</div>
                <div style=" text-align:left;">স্বাক্ষরঃ</div>
                <div style=" text-align:left;">সীলঃ</div>
                <div style=" text-align:left;">
                    <br>* উৎসে কর্তনযোগ্য সরবরাহের ক্ষেত্রে ফর্মটি সমন্বিত কর চালানপত্র ও উৎসে কর কর্তন সনদপত্র হিসেবে
                    বিবেচিত হইবে এবং উৎসে কর্তনযোগ্য সরবরাহের ক্ষেত্রে প্রযোজ্য হবে।&nbsp;
                </div>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
if ($printInfo['print_status'] < 1) {
    $printInfo->print_status = 1;
    $printInfo->save();
}
?>
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
        color: #000;
        font-family: 'times new roman', arial;
        background-color: #fff;
        margin: auto;
    }

    .style_94 th, .style_94 td {
        border: 1px solid #000 !important;
        font-weight: normal;
    }

    table.reportTab tr th {
        background-color: #fff;
        color: #000;
        font-size: 11px !important;
        font-weight: normal;
    }

    table.reportTab tr, table.reportTab tr td, table.reportTab tr th {
        border: 1px solid #000;
    }
</style>
