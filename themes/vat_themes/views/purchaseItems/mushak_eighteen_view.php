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
    .left{text-align:left}
    .right{text-align:right}
</style>
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;">        
    <table class="reportTabCustom" cellpadding="0" rules="none" border="0" style="empty-cells: show; width:8.3in; overflow: hidden; table-layout:fixed;">
    <tbody>
        <tr>
            <td valign="top">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>
                <table class="" style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;width: 8.2in;margin:auto">
                    <tbody>
                        <tr valign="top">
                           <td></td>
                           <td style=" overflow:hidden;font-size:15px;">
                              <div class="style_16" style=" text-align:center;"><b>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</b><br>জাতীয় রাজস্ব বোর্ড<br><br></div>
                           </td>
                           <td style="padding-top: 12px;text-align: right;"><span style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-১৮</span></td>
                        </tr>
                        <tr valign="top">
                            <td style="overflow: hidden; font-size: 11px;" colspan="3">
                                <b>চলতি হিসাব </b><br>
                                [ বিধি ২২(১) দ্রষ্টব্য ]
                            </td>
                        </tr>
                    </tbody>
                </table><br>
                <table style="border-collapse: collapse; empty-cells: show; width: 7.961in; overflow:hidden; table-layout:fixed;">
                    <colgroup>
                        <col style=" width: 1in;">
                            <col>
                    </colgroup>
                    <tbody>
                        <tr valign="top">
                            <td style=" overflow:hidden;">
                                নাম&nbsp; : 
                            </td>
                            <td style=" overflow:hidden;">
                                <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td style=" overflow:hidden;">
                                ঠিকানা :
                            </td>
                            <td colspan="2" style=" overflow:hidden;">
                               <?= !empty($yourCompanyInfo['road']) ? $yourCompanyInfo['road'] : '' ?>
                               <?= !empty($yourCompanyInfo['house']) ?', '. $yourCompanyInfo['house'] : '' ?>
                               <?= !empty($yourCompanyInfo['location']) ?', '. $yourCompanyInfo['location'] : '' ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td style=" overflow:hidden;">
                                টেলিফোন :
                            </td>
                            <td colspan="2" style=" overflow:hidden;">
                               <?= !empty($yourCompanyInfo['contact']) ? $yourCompanyInfo['contact'] : '' ?>
                                <br>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="style_18" style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;" id="__bookmark_5">
                    <colgroup>
                        <col style=" width: 5%;">
                            <col>
                                <col>
                                    <col>
                                        <col>
                                            <col>
                                                <col>
                                                    <col>
                                                        <col>
                                                            <col>
                    </colgroup>
                    <tbody>
                        <tr class="style_19" valign="top" align="center">
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_16_e860294a-5aac-4c87-b503-dc6595d239ec" style=" text-align:center;">ক্রমিক সংখ্যা</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_17_85b0124a-9221-4803-b42a-1fcb2fbf1381" style=" text-align:center;">তারিখ</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_18_f32ab6e2-49da-4bc6-97b0-c4521a8a483f" style=" text-align:center;">লেনদেনের বর্ণনা(১)</div>
                            </th>
                            <th class="style_20" colspan="2" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_19_c74fe6fc-5c54-4888-89ab-61e6820b437a" style=" text-align:center;">ক্রয় বা বিক্রয় হিসাব পুস্তকের</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_20_2ad39a6a-f749-433b-a957-892779ad011f" style=" text-align:center;">ট্রেজারি জমা</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_21_8bd75962-3b6a-494a-9537-6cbbc05b380d" style=" text-align:center;">রেয়াত২</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_22_7e052b30-e817-4984-a7a3-5bc3fcdac0de" style=" text-align:center;">প্রদেয়৩</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_23_daad3148-74bd-4444-a306-6313c76eda66" style=" text-align:center;">সমাপনী জের</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_24_95aafdf5-4137-43d5-8840-a7ebecc86fd8" style=" text-align:center;">মন্তব্য</div>
                            </th>
                        </tr>
                        <tr class="style_19" valign="top" align="center">
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_25_0f481acb-d0c3-4b55-b56d-9c8c25e56677" style=" text-align:center;">(১)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_26_dbf267d3-e14c-4398-ace8-f42382f8fdf3" style=" text-align:center;">(২)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_27_a80e9daf-1bf5-4883-b466-c44850fc85dd" style=" text-align:center;">(৩)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_28_7a50c298-7bf0-4d01-8438-f9db3ba19b1f" style=" text-align:center;">(৪)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_29_9e6abddb-4512-4166-87da-5c2c9a8c5ddd" style=" text-align:center;">(৫)</div>
                            </th>
                            <th class="style_22" style=" overflow:hidden;" align="center">
                                <div id="AUTOGENBOOKMARK_30_ddb423d4-986a-43ed-890e-cf5b80b2c9b0" style=" text-align:center;">(৬)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_31_cbec473f-c839-4dd9-8132-c2f2ac99a838" style=" text-align:center;">(৭)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_32_afb48ece-b851-404f-a810-7d28a9a1b68e" style=" text-align:center;">(৮)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_33_35fc7a45-7720-47c1-a3f0-54356e1e3536" style=" text-align:center;">(৯)</div>
                            </th>
                            <th class="style_20" style=" overflow:hidden;" align="left">
                                <div class="style_21" id="AUTOGENBOOKMARK_34_f36fce90-6d76-47d9-a8ae-fb9e51be12c0" style=" text-align:center;">(১০)</div>
                            </th>
                        </tr>
                        <?php
                        function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
                            $array = array(); 
                            $interval = new DateInterval('P1D'); 
                            $realEnd = new DateTime($end); 
                            $realEnd->add($interval); 
                            $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
                            foreach($period as $date) {                  
                                $array[] = $date->format($format);  
                            } 
                            return $array; 
                        }
                        $dates = [];
                        if(!empty($start_date) && !empty($end_date)){
                            $i = 1;
                            $date_range = getDatesFromRange($start_date, $end_date, $format = 'Y-m-d');
                            if(!empty($date_range)){
                                foreach($date_range as $dates){

                                    $sql1 = "SELECT * FROM purchase_items WHERE issue_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql1);
                                    $purchaseInfo = $command->queryAll();
                                    
                                    foreach($purchaseInfo as $pur){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($pur['issue_date'])?date('d-m-Y', strtotime($pur['issue_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">Purchase</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($pur['po_no'])?'PO-'.$pur['po_no']:(!empty($pur['service_no'])?'PO-'.$pur['service_no']:'')?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($pur['issue_date'])?date('d-m-Y', strtotime($pur['issue_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>
                        <?php
                        $i++;
                                    }

                                    $sql2 = "SELECT * FROM sell_items WHERE sales_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql2);
                                    $salesInfo = $command->queryAll();
                                                                        
                                    foreach($salesInfo as $sales){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($sales['sales_date'])?date('d-m-Y', strtotime($sales['sales_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">Sale</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($sales['so_no'])?'SO-'.$sales['so_no']:''?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($sales['sales_date'])?date('d-m-Y', strtotime($sales['sales_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>   
                        <?php
                        $i++;
                                    }
                                    
                                    $sql3 = "SELECT * FROM purchase_return WHERE return_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql3);
                                    $purchaseReturn = $command->queryAll();
                                                                        
                                    foreach($purchaseReturn as $pReturn){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($pReturn['return_date'])?date('d-m-Y', strtotime($pReturn['return_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">PurchaseCancellation</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($pReturn['sl_no'])?'PO/CL-'.$pReturn['sl_no']:''?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($pReturn['return_date'])?date('d-m-Y', strtotime($pReturn['return_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>   
                        <?php
                        $i++;
                                    }
                                    
                                    $sql4 = "SELECT * FROM sales_return WHERE return_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql4);
                                    $salesReturn = $command->queryAll();
                                                                        
                                    foreach($salesReturn as $sReturn){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($sReturn['return_date'])?date('d-m-Y', strtotime($sReturn['return_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">SaleCancellation</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($sReturn['sl_no'])?'SO/CL-'.$sReturn['sl_no']:''?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($sReturn['return_date'])?date('d-m-Y', strtotime($sReturn['return_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>   
                        <?php
                        $i++;
                                    }
                                    
                                    $sql5 = "SELECT * FROM vds_payment WHERE payment_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql5);
                                    $vdsPayInfo = $command->queryAll();
                                                                        
                                    foreach($vdsPayInfo as $vdsPay){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($vdsPay['payment_date'])?date('d-m-Y', strtotime($vdsPay['payment_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">Payment</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($vdsPay['sl_no'])?'VDSPV-'.$vdsPay['sl_no']:''?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($vdsPay['payment_date'])?date('d-m-Y', strtotime($vdsPay['payment_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>   
                        <?php
                        $i++;
                                    }
                                    
                                    $sql5 = "SELECT * FROM vat_payment WHERE payment_date = '$dates' ORDER BY id ASC";
                                    $connection = Yii::app()->db;
                                    $command = $connection->createCommand($sql5);
                                    $vatPayInfo = $command->queryAll();
                                                                        
                                    foreach($vatPayInfo as $vatPay){?>
                                        <tr valign="top">
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=$i?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($vatPay['payment_date'])?date('d-m-Y', strtotime($vatPay['payment_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">Payment</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <?=!empty($vatPay['sl_no'])?'VPV-'.$vatPay['sl_no']:''?>
                                                </div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;"><?=!empty($vatPay['payment_date'])?date('d-m-Y', strtotime($vatPay['payment_date'])):''?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;"><?=!empty($vatPay['amount'])?number_format($vatPay['amount'],2):'0.00'?></div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:right;">0.00</div>
                                            </td>
                                            <td class="style_23" style=" overflow:hidden;">
                                                <div style=" text-align:center;">
                                                    <div style="visibility:hidden">&nbsp;</div>
                                                </div>
                                            </td>
                                        </tr>   
                        <?php
                        $i++;
                                    }
                                    
                                    
                                    
                                    
                                    
                                    ?>

                                    
                        
                        




<?php
                                }
                            }
                        }

                        ?>
                        
                        
                        
                        
                        
                        
                        
                       
                    </tbody>
                </table>
                <div id="AUTOGENBOOKMARK_35_dd8116b9-6b36-4504-a6a4-4179f9abb525">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <div class="style_14" id="AUTOGENBOOKMARK_36_ca355257-04d0-4801-a91c-636978da91b3">১ । এই কলামে সকল প্রকার ক্রয়, বিক্রয়, ক্রয় ও বিক্রয় প্রত্যর্পণ, অর্থদণ্ড, ট্রেজারি জমা, চলতি হিসাবের জের ইত্যাদি উল্লেখ করিতে হইবে ।</div>
                <div class="style_14" id="AUTOGENBOOKMARK_37_770cc58b-e720-4a4c-9d89-fe235fd89cc2">২ । এই কলামে সকল প্রকার উপকরণ কর, বিক্রয় প্রত্যর্পণজনিত কারণে রেয়াত, চলতি হিসাবের পূর্ববর্তী মাসের জের, ট্রেজারি জমা ও অন্যান্য সকল প্রকার পাওনা বা প্রত্যর্পণ প্রযোজ্য ক্ষেত্রে অন্তর্ভুক্ত হইবে।</div>
                <div class="style_28" id="AUTOGENBOOKMARK_38_2988667f-d455-43ee-85c6-084b901a281c" style=" text-align:left;">৩ । এই কলামে সকল প্রকার প্রদেয় অর্থের পরিমাণ যথা:&nbsp; উৎপাদ কর, ক্রয়, প্রত্যর্পণজনিত কারণে বাতিলকৃত রেয়াত, অর্থদণ্ড ও অন্যান্ সকল প্রকার প্রদেয় অর্থের পরিমাণ প্রযোজ্য খেত্রে অন্তর্ভুক্ত হইবে ।</div>
                <div id="AUTOGENBOOKMARK_39_0e009b84-5527-4114-b8e7-a3f84ede6cc5">
                    <div style="visibility:hidden">&nbsp;</div>
                </div>
                <div class="style_14" id="AUTOGENBOOKMARK_40_e9531714-6ac8-4270-aef2-890f362bd1f8">১মূসক এসআরও নং-৩০৬, তারিখ: ০৭/০৬/২০০১</div>
            </td>
        </tr>
    </tbody>
</table>

</div>
<style>
    table.head-part tbody tr, table.head-part tbody tr td{
        border:none !important;
        text-align: left;
    }
</style>

<style>
    .reportTabCustom{
        background-color: #fff;
        font-size:11px;
        max-width: 10in; 
        margin:auto; 
        min-height: 9in;
        font-family: 'times new roman',arial;
    }
    .style_19, .style_20, .style_23{
        border: 1px solid #000 !important;
        background-color: #fff !important;
    }
    .csd, .csd th, .csd td{
        border:1px solid #000;
    }
    .left{
        text-align: left;
    }
    .right{
        text-align: right;
    }
    .center{
        text-align: center;
    }
</style>
