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

    .style_94:hover, .style_5:hover {
        background: #e5e5e5;
    }

</style>
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;min-height:600px;">
    <?php
    $customer_info = Customers::model()->findByPk($data['customer_id']);
    $sales_id = $data['id'];
    $qty = $data['qty'];
    $sales_date = $data['issue_date'];

    $printInfo = SellItems::model()->findByPk($sales_id);

    $i = 1;
    $model_id = $data['finish_product_id'];
    $model_name = ProdModels::model()->prodNameOfThis($model_id);
    ?>
    <table class="reportTabCustom"
           style="border-collapse: collapse; empty-cells: show; width: 8.3in;  table-layout:fixed;text-align:right;">
        <tbody>
        <tr valign="top" style="background-color: #fff !important;">
            <th colspan="5">

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
                                        <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">মূসক-৬.৪</span>
                                        <?php
                                        /*                                                if($printInfo['print_status'] > 0){ */ ?><!--
                                               <div style="font-size: 20px;color: #423f3f;font-family:arial;margin-top:40px;">Duplicate</div>                                               
                                               --><?php /*} */ ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3">
                                        <b>চুক্তিভিত্তিক উৎপাদনের চালানপত্র<br>
                                            [বিধি ৪০ এর উপ-বিধি (১) এর দফা (ঘ) দ্রষ্টব্য] </b>
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
                            পণ্য গ্রহীতার নাম
                        </td>
                        <td colspan="2">
                            : <?= $customer_info['company_name'] ? $customer_info['company_name'] : '' ?>
                        </td>
                        <td>
                            চালানপত্র নম্বর
                        </td>
                        <td colspan="2">
                            : <?= $data['sl_no'] ? $data['sl_no'] : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td>
                            গ্রহীতার বিআইএন
                        </td>
                        <td colspan="2">
                            : <?= !empty($customer_info['bin']) ? $customer_info['bin'] : '' ?>
                        </td>
                        <td>
                            ইস্যুর তারিখ
                        </td>
                        <td colspan="2">
                            : <?= $data['issue_date'] ? date('d-m-Y', strtotime($data['issue_date'])) : '' ?>
                        </td>
                    </tr>
                    <tr valign="top" style="text-align:left;">
                        <td>
                            গন্তব্যস্থল
                        </td>
                        <td colspan="2">
                            : <?= $customer_info['company_address'] ? $customer_info['company_address'] : '' ?>
                        </td>
                        <td>
                            ইস্যুর সময়
                        </td>
                        <td colspan="2">
                            : <?= date('H:i:s') ?>
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
                <div style=" text-align:center;">প্রকৃতি ( উপকরণ বা উৎপাদিত পণ্য )</div>
            </th>
            <th>
                <div style=" text-align:center;">পণ্যের বিবরণ &nbsp;</div>
            </th>
            <th>
                <div style=" text-align:center;">পরিমাণ</div>
            </th>

            <th>
                <div style=" text-align:center;">মন্তব্য</div>
            </th>
        </tr>
        <tr class="style_94" valign="top">
            <th>
                <div style=" text-align:center;">(১)</div>
            </th>
            <th>
                <div style=" text-align:center;">(২)</div>
            </th>
            <th>
                <div style=" text-align:center;">(৩)</div>
            </th>
            <th>
                <div style=" text-align:center;">(৪)</div>
            </th>
            <th>
                <div style=" text-align:center;">(৫)</div>
            </th>

        </tr>
        <tr valign="top" class="style_94">
            <td>
                <div style="display: none;">1.00</div>
                <div class="style_34" style=" text-align:left;"><?= $i ?></div>
            </td>
            <td class="left">
                <?= !empty($model_name) ? $model_name : '' ?>
            </td>
            <td align="center">

            </td>
            <td align="right">
                <?= number_format($qty) ?>
            </td>
            <td align="center">

            </td>
        </tr>
        <tr valign="top">
            <td colspan="5" align="right">
                <div style=" text-align:left;">
                    <br>দায়িত্বপ্রাপ্ত কর্মকর্তার স্বাক্ষর:
                </div>
                <div style=" text-align:left;">নাম:</div>

                <br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
/*if($printInfo['print_status']<1){
    $printInfo->print_status = 1;
    $printInfo->save();
}*/
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
