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
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;">
    <table class="reportTabCustom"
           style="border-collapse: collapse; empty-cells: show; width: 8.3in;margin: auto;background-color:#fff;">
        <tbody>
        <tr valign="top">
            <td class="style_14">
                <table class=""
                       style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <!--
                        <tr valign="top">
                           <td></td>
                           <td style=" overflow:hidden;font-size:15px;">
                              <div class="style_16" style=" text-align:center;"><b>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</b><br>জাতীয় রাজস্ব বোর্ড<br><br></div>
                           </td><td></td>
                        </tr>-->
                    <tr valign="top">
                        <td style="overflow: hidden; text-align: center" colspan="3">
                            <span style="font-size: 20px; font-weight: bold">চালান ফরম</span><br>
                            <span style="font-size: 12px; font-weight: bold">টি, আর ফরম নং ৬ ( এস, আর ৩৭ দ্রষ্টব্য)</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="style_15" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                    <tbody>
                    <tr valign="top">
                        <td align="center" style="padding-top: 15px;padding-bottom: 4px">
                            চালান নং : <span
                                    style="border-bottom:1px dashed #000;padding: 0px 60px 2px 10px;"><?= !empty($data['chalan_no']) ? $data['chalan_no'] : '' ?></span>
                            &nbsp;&nbsp;&nbsp;
                            তারিখ : <span style="border-bottom:1px dashed #000;padding: 0px 50px 2px 10px;">
                             <?php
                             if ($data['payment_date']) {
                                 $pay_date = date('d-m-Y', strtotime($data['payment_date']));
                                 echo str_replace('-', '/', $pay_date);
                             }
                             ?>
                         </span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="left" style="padding-top: 4px;">
                            বাংলাদেশ ব্যাংক/ সোনালী ব্যাংক লিমিটেডের <span
                                    style="border-bottom:1px dashed #000;padding: 0px 35px 2px 10px;">
                             <?= !empty($data['district_id']) ? District::model()->nameOfThis($data['district_id']) : '' ?>
                         </span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            জেলার <span style="border-bottom:1px dashed #000;width:500px;padding: 0px 40px 2px 10px;">
                             <?= $data['branch'] ?>
                         </span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            শাখায় টাকা জমা দেওয়ার চালান ।
                        </td>
                    </tr>
                    <tr valign="top">
                        <td align="left" style="padding-top: 4px;padding-bottom: 10px">
                            কোড নং <span
                                    style="border-bottom:1px dashed #000;width:500px;padding: 0px 60px 2px 30px;font-weight: bold;font-size: 13px;">
                             <?php
                             $pay_code = '';
                             $acc_code = '';
                             $code_set = '';
                             if ($data['payment_code_id'])
                                 $pay_code = PaymentCode::model()->accountCodeOfThis($data['payment_code_id']);
                             if ($data['account_code_id'])
                                 $acc_code = AccountCode::model()->accountCodeOfThis($data['account_code_id']);
                             if ($acc_code) {
                                 $code_set = str_replace("/০০০০/", "/$acc_code/", $pay_code);
                                 echo str_replace("/", "-", $code_set);
                             }
                             /*
                                                          ১-১১৩৩-০০৩০-০৩১১*/
                             ?>
                         </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="style_23"
                       style="border-collapse: collapse; empty-cells: show; width: 100%;margin-top: 5px;">
                    <colgroup>
                        <col style=" width: 15%;">
                        <col style=" width: 25%;">
                        <col style=" width: 15%;">
                        <col style=" width: 15%;">
                        <col style=" width: 8%;">
                        <col style=" width: 6%;">
                    </colgroup>
                    <tbody>
                    <tr class="style_24" valign="top" style="text-align:center;">
                        <th class="style_25" colspan="4">
                            জমা প্রদানকারী কর্তৃক পূরণ করিতে হইবে
                        </th>
                        <th class="style_25" colspan="2">
                            টাকার অংক
                        </th>
                        <th class="style_25" rowspan="2">
                            বিভাগের নাম ও চালানের পৃষ্ঠাঙ্কনকারী কর্মকর্তার নাম, পদবী ও দপ্তর । *
                        </th>
                    </tr>
                    <tr class="style_24" valign="top" style="text-align:center;">
                        <th class="style_25">
                            যাহার মারফত প্রদত্ত হইল তাহার নাম ও ঠিকানা
                        </th>
                        <th class="style_25">
                            যে বাক্তির / প্রতিষ্ঠানের পক্ষ হইতে টাকা প্রদত্ত হইল তাহার নাম, পদবী ও ঠিকানা
                        </th>
                        <th class="style_25">
                            কি বাবদ জমা দেওয়া হইল তাহার বিবরণ
                        </th>
                        <th class="style_25">
                            মুদ্রা ও নোটের বিবরণ / ড্রাফ্‌ট, পে-অর্ডার ও চেকের বিবরণ ।
                        </th>
                        <th class="style_25">
                            টাকা
                        </th>
                        <th class="style_25">
                            পয়সা
                        </th>
                    </tr>
                    <tr class="style_24" valign="top">
                        <th class="style_25">
                            (১)
                        </th>
                        <th class="style_25">
                            (২)
                        </th>
                        <th class="style_25">
                            (৩)
                        </th>
                        <th class="style_25">
                            (৪)
                        </th>
                        <th class="style_25">
                            (৫)
                        </th>
                        <th class="style_25">
                            (৬)
                        </th>
                        <th class="style_25">
                            (৭)
                        </th>
                    </tr>
                    <?php
                    $name = "";
                    $address = "";
                    $note = "";
                    $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
                    if ($type == 'vat') {
                        $name = $data['customer_name'];
                        $address = $data['address'];
                        $note = 'VAT Payment';
                    } else if ($type == 'vds') {
                        $supplierData = Suppliers::model()->findByPk($data['supplier_id']);
                        $name = $supplierData['name'];
                        $address = $supplierData['address'];
                        $note = 'VDS Payment';
                    } else {
                    }


                    ?>

                    <tr valign="top">
                        <?php
                        $name = '';
                        $address = '';
                        $phone_no = '';
                        $note = '';
                        if ($data['vds_payment_id'] > 0) { // VDS
                            $vds_info = VdsPayment::model()->findByPk($data['vds_payment_id']);
                            if ($vds_info) {
                                $note = $vds_info['purpose'];
                                $purchase_id = $vds_info['purchase_id'];
                                if ($purchase_id)
                                    $purchase_info = PurchaseItems::model()->findByPk($purchase_id);
                                if ($purchase_info) {
                                    $supplier_info = Suppliers::model()->findByPk($purchase_info['supplier_id']);
                                    if ($supplier_info) {
                                        $name = $supplier_info['name'];
                                        $address = $supplier_info['address'];
                                        $phone_no = $supplier_info['phone_no'];
                                    }
                                }
                            }
                            ?>
                            <td class="style_32">
                                <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>,
                                <br>
                                <?= !empty($yourCompanyInfo['house']) ? $yourCompanyInfo['house'] : '' ?>
                                <?= !empty($yourCompanyInfo['road']) ? ', ' . $yourCompanyInfo['road'] : '' ?>
                                <?= !empty($yourCompanyInfo['location']) ? ', ' . $yourCompanyInfo['location'] : '' ?>. <br>
                                <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'VAT No- ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                                , <br>
                                <?= !empty($yourCompanyInfo['contact']) ? 'Mobile No- ' . $yourCompanyInfo['contact'] : '' ?>
                                .
                            </td>
                            <td class="style_32">
                                <?= $name ?>, <br>
                                <?= $address ?>, <br>
                                <?= $phone_no ?>.
                            </td>
                            <?php
                        } else { // VAT?>
                            <td class="style_32">
                                <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>,
                                <br>
                                <?= !empty($yourCompanyInfo['house']) ? $yourCompanyInfo['house'] : '' ?>
                                <?= !empty($yourCompanyInfo['road']) ? ', ' . $yourCompanyInfo['road'] : '' ?>
                                <?= !empty($yourCompanyInfo['location']) ? ', ' . $yourCompanyInfo['location'] : '' ?>. <br>
                                <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'VAT No- ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                                , <br>
                                <?= !empty($yourCompanyInfo['contact']) ? 'Mobile No- ' . $yourCompanyInfo['contact'] : '' ?>
                                .
                            </td>
                            <td class="style_32">
                                <?= !empty($data['paid_by']) ? $data['paid_by'] : '' ?>
                            </td>
                            <?php
                        }
                        ?>

                        <td class="style_32">
                            <?php
                            echo $note = $data['note'];
                            ?>
                        </td>
                        <td class="style_32">
                            <?php if ($data['bank_id'] != "") { ?>
                                Bank Name:
                                <?= !empty($data['bank_id']) ? ChartOfAc::model()->nameOfThis($data['bank_id']) : '' ?>
                                <br/>Cheque No. <?= !empty($data['cheque_number']) ? $data['cheque_number'] : '' ?>
                                <br/>Date: <?= !empty($data['cheque_date']) ? $data['cheque_date'] : '' ?>
                            <?php } else {
                                echo 'CASH';
                            } ?>
                        </td>
                        <td class="style_32 right"><?= !empty($data['amount']) ? number_format(round($data['amount'])) : '00' ?></td>
                        <td class="style_32 center">00</td>
                        <td class="style_32"> Deputy Commissioner of Custom, Excise & VAT, Rangamati Division </td>
                    </tr>

                    <tr valign="top">
                        <td class="style_32" colspan="4" style=" text-align:right;">
                            মোট টাকা &nbsp;
                        </td>
                        <td class="style_32 right"
                            style="border-right: 1px solid #fff !important;"><?= !empty($data['amount']) ? number_format(round($data['amount']), 2) : '00' ?></td>
                        <td class="style_32" colspan="2"></td>
                    </tr>
                    <tr valign="top">
                        <td class="style_32" colspan="4">
                            টাকা (কথায়) : &nbsp;&nbsp;
                            <?php
                            $amountInWord = new AmountInWord();
                            if (!empty($data['amount'])) {
                                echo $amountInWord->convert(intval(round($data['amount']))) . ' Taka Only.';
                            }
                            ?>
                        </td>
                        <td class="style_32" colspan="3"
                            style="border-bottom:1px solid #fff !Important;text-align:center;">

                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_32" colspan="4">
                            টাকা পাওয়া গেল :
                        </td>
                        <td class="style_32" colspan="3" rowspan="2" style="text-align:center;">
                            <br>
                            ম্যানেজার <br>
                            বাংলাদেশ ব্যাংক / সোনালী ব্যাংক লিমিটেড
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="style_32" colspan="4" style="border-top: 1px solid #fff !important;">
                            তারিখ : <span style="border-bottom:1px dashed #000;width:500px;padding: 0px 50px 2px 10px;">

                             <?php
                             $cur_date = date('d-m-Y', strtotime(date('d-m-Y')));
                             echo str_replace('-', '/', $cur_date);
                             ?>
                         </span>
                        </td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr valign="top">
                        <td colspan="7" style="border: 1px solid #fff;padding-top: 30px">
                            <b>নোট :</b> ১। সংশ্লিষ্ট দপ্তরের সহিত যোগাযোগ করিয়া সঠিক কোড নম্বর জানিয়া লইবেন । <br>
                            ২। *যে সকল ক্ষেত্রে কর্মকর্তা কর্তৃক পৃষ্ঠাঙ্কন প্রয়োজন , সে সকল ক্ষেত্রে প্রযোজ্য হইবে ।
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
    .reportTabCustom {
        font-size: 11px;
        min-height: 7.5in;
        font-family: 'times new roman', arial;
    }

    .style_23, .style_25, .style_32, .style_34, .style_36 {
        border: 1px solid #000 !important;
    }

    .left {
        text-align: left;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }
</style>