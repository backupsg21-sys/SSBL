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
<style>
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
        margin: 12mm 12mm 20mm 12mm;
    }
</style>
<div class='printAllTableForThisReport' style="background-color: #F9FCFD">
    <div class="divFooter">Print Date & Time : <?php echo date('M d, Y & h:i:s A'); ?></div>
    <table class="reportTabCustom">
        <tbody>
        <tr>
            <td>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>
                <table class="" style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 100%;margin:auto">
                    <tbody>
                    <tr valign="top">
                        <td></td>
                        <td style=" overflow:hidden;font-size:12px;width: 55%;">
                            <div class="style_16" style=" text-align:center;">
                                <sup>১</sup> [গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয় রাজস্ব বোর্ড<br><br>
                            </div>

                        </td>
                        <td style="padding-top: 11px;text-align: left;">
                            <?php
                            if($printInfo['print_status'] > 0){ ?>
                                <span style="font-size: 20px;color: #423f3f;padding: 3px 10px 3px 90px; font-family: arial; position:absolute;margin-top:40px;">Duplicate</span>
                            <?php } ?>
                            <span style="font-weight: bold;padding: 3px;border: 1px solid #000;float: right;">মূসক-৬.৭</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="overflow: hidden; font-size: 11px;text-align: center" colspan="3"><br>
                            <b> ক্রেডিট নোট</b><br>
                            [ বিধি ৪০ এর উপবিধি&nbsp; (১) এর দফা (ছ) দ্রষ্টব্য  ]
                        </td>
                    </tr>
                    </tbody>
                </table><br>
                <?php
                $customerData = Customers::model()->findByPk($salesData['customer_id']);
                $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
                ?>
                <table class="style_14" style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                    <colgroup>
                        <col style=" width: 55%;">

                        <col style=" width: 45%;">

                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td>    </td>
                        <td>  ক্রেডিট নোট নাম্বার  :  <?=!empty($data[0]['sl_no'])?'SO/CL-'.$data[0]['sl_no']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td>    </td>
                        <td>  ইস্যুর তারিখ  :  <?=  $data[0]['return_date']; ?>  </td>
                    </tr>
                    <tr valign="top">
                        <td>     </td>
                        <td>  ইস্যুর সময় :  <?=!empty($data[0]['created_datetime'])? date('h:s:a', strtotime($data[0]['created_datetime'])) :''?> </td>
                    </tr>
                    </tbody>
                </table>
                <table class="style_14" style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                    <colgroup>
                        <col style=" width: 15%;">

                        <col style=" width: 60%;">

                    </colgroup>
                    <tbody>
                    <tr valign="top">
                        <td> নিবন্ধিত ব্যাক্তির নাম  :   </td>
                        <td>   <?=!empty($yourCompanyInfo['company_name'])?$yourCompanyInfo['company_name']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td> নিবন্ধিত ব্যাক্তির বিআইএন  : </td>
                        <td>   <?=!empty($yourCompanyInfo['vat_regi_no'])?$yourCompanyInfo['vat_regi_no']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td> নিবন্ধিত ব্যাক্তির ঠিকানা : </td>
                        <td>   House: <?=!empty($yourCompanyInfo['house'])?$yourCompanyInfo['house']:''; ?> Road: <?=!empty($yourCompanyInfo['road'])?$yourCompanyInfo['road']:''?>, <?=!empty($yourCompanyInfo['location'])?$yourCompanyInfo['location']:''?> </td>
                    </tr>

                    <tr valign="top">
                        <td> ক্রেতা/ গ্রহীতার নাম  : </td>
                        <td>   <?=!empty($customerData['company_name'])?$customerData['company_name']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td> ক্রেতা / গ্রহীতার বিআইএন  : </td>
                        <td>   <?=!empty($customerData['bin'])?$customerData['bin']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td> ঠিকানা  : </td>
                        <td>   <?=!empty($customerData['company_address'])?$customerData['company_address']:''?> </td>
                    </tr>
                    <tr valign="top">
                        <td> যানবাহনের প্রকৃতি ও নম্বর  : </td>
                        <td>  <?=!empty($data[0]['vehicle_type_no'])?'SO/CL-'.$data[0]['vehicle_type_no']:''?>    </td>
                    </tr>

                    </tbody>
                </table>

                <table class="style_17" border="1" style="border-collapse: collapse; border: 1px solid #000;empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">
                    <thead>
                    <tr>
                        <td rowspan="2" style="text-align:center; "> ক্রমিক নং  </td>
                        <td rowspan="2" style="text-align:center; "> কর চালান পত্রের নম্বর ও তারিখ  </td>
                        <td rowspan="2" style="text-align:center; "> ক্রেডিট নোট ইস্যুর কারণ  </td>
                        <td colspan="4" style="text-align:center; "> চালানপত্রের উল্লিখিত সরবরাহের  </td>
                        <td colspan="4" style="text-align:center; "> হ্রাসকারী সমন্বয়ের সহিত সংশ্লিষ্ট  </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"> মূল্য</td>
                        <td style="text-align:center;"> পরিমাণ  </td>
                        <td style="text-align:center;">মূল্য সংযোজনের পরিমান</td>
                        <td style="text-align:center;">সম্পুরক শুল্কের পরিমান</td>
                        <td style="text-align:center;"> মূল্য</td>
                        <td style="text-align:center;"> পরিমাণ  </td>
                        <td style="text-align:center;">মূল্য সংযোজনের পরিমান</td>
                        <td style="text-align:center;">সমন্বয়যোগ্য সম্পুরক শুল্কের পরিমান</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">(১)</td>
                        <td style="text-align:center;">(২)</td>
                        <td style="text-align:center;">(৩)</td>
                        <td style="text-align:center;">(৪)</td>
                        <td style="text-align:center;">(৫)</td>
                        <td style="text-align:center;">(৬)</td>
                        <td style="text-align:center;">(৭)</td>
                        <td style="text-align:center;">(৮)</td>
                        <td style="text-align:center;">(৯)</td>
                        <td style="text-align:center;">(১০)</td>
                        <td style="text-align:center;">(১১)</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;

                    $sales_qty_total = 0;
                    $net_total_amount = 0;
                    $vat_total = 0;

                    $total_new_total = 0;
                    $total_new_qty = 0;
                    $total_vat_new = 0;

                    foreach($data as $dt){
                        $saleDetails = SellDetails::saleProductInfo($dt['sales_id'], $dt['model_id']);
                        ?>
                        <tr>
                            <td class="center">  <?=$i?> </td>
 			    <td> <?=!empty($salesData['mushok_no'])? $salesData['mushok_no']:''?> <br> <?=!empty($salesData['sales_date'])?$salesData['sales_date']:''?> </td>
                            <td>   <?=$dt['reason']?> </td>
                            <td style="text-align: center">   <?= $saleDetails->net_total?> </td>
                            <td style="text-align: center">   <?= $saleDetails->sales_qty?> </td>
                            <td style="text-align: center">   <?= $saleDetails->vat?> </td>
                            <td style="text-align: center">   <?= $saleDetails->sd?> </td>
                            <td style="text-align: center">   <?=$dt['total_new']?> </td>
                            <td style="text-align: center">   <?=$dt['qty']?> </td>
                            <td style="text-align: center">   <?=$dt['vat_new']?> </td>
                            <td style="text-align: center">   <?=$dt['sd_new']?> </td>
                        </tr>
                        <?php
                        $i++;
                        $sales_qty_total += $saleDetails->sales_qty;
                        $net_total_amount += $saleDetails->net_total;
                        $vat_total += $saleDetails->vat;

                        $total_new_total += $dt['total_new'];
                        $total_new_qty += $dt['qty'];
                        $total_vat_new += $dt['vat_new'];
                    }?>
                    <tr valign="top">
                        <td colspan="3" class="style_25 right" style=" overflow:hidden;">সর্বমোট  &nbsp;</td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?= number_format($net_total_amount,2)?>
                        </td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?=$sales_qty_total?>
                        </td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?= number_format($vat_total,2)?>
                        </td>
                        <td class="style_25" style=" overflow:hidden;"></td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?= number_format($total_new_total,2)?>
                        </td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?= number_format($total_new_qty,2)?>
                        </td>
                        <td class="style_25 center" style=" overflow:hidden;">
                            <?= number_format($total_vat_new,2)?>
                        </td>

                        <td class="style_25" style=" overflow:hidden;"></td>
                    </tr>

                    </tbody>
                </table>
                <br>    <br>
              <div  style="width: 400px; float: right; margin-top: 30px">
                  <p> দায়িত্বপ্রাপ্ত ব্যাক্তির নাম  : </p>
                  <p> পদবী  : </p>
                  <p> স্বাক্ষর  : &nbsp; <img src="<?php echo Yii::app()->baseUrl; Users::getUserSignature($created_by); ?>" style="width: 80px;position: absolute; margin-top:-20px"/>  </p>
                  <p> সীল  : </p>
              </div>
                <table class="style_14" style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;">


                </table>
                <div class="style_14" style="background-color:#fff;font-size:11px;"> <sup> ১২</sup>[সকল প্রকার কর ব্যতীত মূল্য] </div>


</div>
</td>
</tr>
</tbody>
</table>
<?php
if($printInfo['print_status']<1){
    $printInfo->print_status = 1;
    $printInfo->save();
}
?>
<style>
    .reportTabCustom{
        font-size:11px;
        background-color:#fff;
        max-width: 8.3in;
        margin: auto;
        font-family: 'times new roman', arial;
    }
    .style_18 tr th{
        border: 1px solid #000 !important; /*#c0c0c0*/
    }
    .style_19, .style_21{
        border: 1px solid #000 !important;
    }
    .left{text-align:left;}
    .center{text-align:center;}
    .right{text-align:right;}
</style>