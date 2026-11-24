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
            <table class="reportTabCustom"  class="style_14" style="border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;">
                <tbody>
                    <tr valign="top">
                        <td class="style_15" style=" overflow:hidden;">    
                            <!--
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                            <table class="" style="text-align:center; border-collapse: collapse; empty-cells: show; overflow:hidden; table-layout:fixed;margin:auto; width:100%;">
                                <tbody>
                                    <tr valign="top">
                                       <td></td>
                                       <td style=" overflow:hidden;font-size:15px;">
                                          <div class="style_16" style=" text-align:center;"><b>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</b><br>জাতীয় রাজস্ব বোর্ড<br><br></div>
                                       </td>
                                       <td style="padding-top: 12px;text-align: right;"><span style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.২</span></td>
                                    </tr>
                                    <tr valign="top">
                                        <td style="overflow: hidden; font-size: 11px;" colspan="3"><b>
                                            <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                                            <?= !empty($yourCompanyInfo['vat_regi_no']) ? ', Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?></b>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td style="overflow: hidden; font-size: 11px;" colspan="3">
                                            <b>&nbsp;বিক্রয় হিসাব পুস্তক&nbsp; <br>
                                            (পণ্য বা সেবা প্রক্রিয়াকরণে সম্পৃক্ত এমন নিবন্ধিত বা তালিকাভুক্ত ব্যক্তির জন্য প্রযোজ্য) <br>
                                            [বিধি ৪০ এর উপদফা (১) এর দফা (খ) এবং ৪১ এর দফা (ক) দ্রষ্টব্য ]   </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <table class="style_17 page-br" style="border-collapse: collapse; empty-cells: show; width:100%; overflow:hidden; table-layout:fixed;">
                                <colgroup>
                                    <col style=" width: 3%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 8%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 10%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 3%;">
                                </colgroup>
                                <tbody>
                                    <tr class="style_18" valign="top" align="center">
                                        <th class="style_19 csd" colspan="21" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_8_bb06483c-b4f7-401e-89f9-f341b2c1732c" style=" text-align:center;">পণ্য/সেবার বিক্রয়</div>
                                        </th>
                                    </tr>
                                    <tr class="style_18 csd" valign="top" align="center">
                                        <th class="style_19" rowspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_9_19377064-0ffa-47af-af81-f9328a103e56" style=" text-align:center;">ক্রমিক সংখ্যা</div>
                                        </th>
                                        <th class="style_19" rowspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_10_6ff06e9b-f4ba-4c17-b428-23ff587c14aa" style=" text-align:center;">তারিখ</div>
                                        </th>
                                        <th class="style_19" colspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_11_68dbe3f9-3ae1-4722-846b-7fde12567bb5" style=" text-align:center;">উৎপাদিত পণ্য/ সেবার<br>প্রারম্ভিক জের</div>
                                        </th>
                                        <th class="style_19" colspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_12_238a2064-b89e-44af-a350-ff0bd976caf6" style=" text-align:center;">উৎপাদন</div>
                                        </th>
                                        <th class="style_19" colspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_12_238a2064-b89e-44af-a350-ff0bd976caf6" style=" text-align:center;">মোট উৎপাদিত <br>পণ্য/সেবা</div>
                                        </th>
                                        <th class="style_19" colspan="3" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_13_6d0941ba-173f-4f81-8e0d-a3752e9a6606" style=" text-align:center;">ক্রেতা/ সরবরাহগ্রহীতা</div>
                                        </th>
                                        <th class="style_19" colspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_14_08e84f35-a64e-4edb-8a7b-561a56e62cd4" style=" text-align:center;">চালানপত্রের বিবরণ</div>
                                        </th>
                                        <th class="style_19" colspan="5" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_15_b633a7ae-74aa-460d-8da5-6a45b19d7868" style=" text-align:center;">বিক্রিত/ সরবরাহকৃত পণ্যের বিবরণ</div>
                                        </th>
                                        <th class="style_19" colspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_16_3abe4baa-00fd-4aa4-8217-7dd980564367" style=" text-align:center;">পণ্যের প্রান্তিক<br>জের</div>
                                        </th>
                                        <th class="style_19" rowspan="2" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_17_71ac0e94-2ecc-4063-9193-6cf14671304a" style=" text-align:center;">মন্তব্য</div>
                                        </th>
                                    </tr>
                                    <tr class="style_18 csd" valign="top" align="center">
                                        <th class="style_21" style=" overflow:hidden;" align="center">
                                            <div id="AUTOGENBOOKMARK_18_63b5ba31-394c-459f-a695-eaa092491cc1">পরিমাণ (একক)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_19_cbf1e4d5-534c-4064-87b4-d329dc9396b3" style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_20_f275b6a3-ef09-40a6-8160-42fa9abcb662" style=" text-align:center;">পরিমাণ (একক)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_21_7f619be6-e43e-4932-8566-8d3a055f28d9" style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_20_f275b6a3-ef09-40a6-8160-42fa9abcb662" style=" text-align:center;">পরিমাণ (একক)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_21_7f619be6-e43e-4932-8566-8d3a055f28d9" style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_22_1ba48707-dae6-4ee9-9dda-7c7c28e70c23" style=" text-align:center;">নাম</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_23_27605761-7efe-4176-8f5f-3970e85cb9f5" style=" text-align:center;">ঠিকানা</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_24_83f6bed3-4183-4564-a40d-e1b04d0242f3" style=" text-align:center;">নিবন্ধন/তালিকাভুক্তি/ জাতীয় পরিচয়পত্র নং</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_25_39aceb3f-d6e2-4a45-91f5-2b9aeccbcc7e" style=" text-align:center;">নম্বর</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_26_df03d04c-1d2e-460a-9e38-93a9681bf2d1" style=" text-align:center;">তারিখ</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_27_69b0695f-983d-4d6b-8271-3538d3702fc5" style=" text-align:center;">বিবরণ</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_28_169a315e-0de5-4e3a-b3c6-b2646d6e59c7" style=" text-align:center;">পরিমাণ</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_29_a19169c4-4a33-438d-96aa-9c86c5be444b" style=" text-align:center;">করযোগ্য মূল্য</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_30_ec61e780-5067-4daa-b8a2-f81658200ae0" style=" text-align:center;">সম্পূরক শুল্ক<br>(যদি থাকে)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_31_47dd6b97-2415-460b-8179-ce0f30d8d7b1" style=" text-align:center;">মূসক</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_20_f275b6a3-ef09-40a6-8160-42fa9abcb662" style=" text-align:center;">পরিমাণ (একক)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_19_cbf1e4d5-534c-4064-87b4-d329dc9396b3" style=" text-align:center;">মূল্য (সকল প্রকার কর ব্যতীত)</div>
                                        </th>
                                    </tr>
                                    <tr class="style_5 csd" valign="top" align="center">
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_34_90683419-479c-4b98-8c95-5ecc0a900888" style=" text-align:center;">(১)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_35_1b2c2e2e-dc4b-4063-adca-9b7722674331" style=" text-align:center;">(২)</div>
                                        </th>
                                        <th class="style_21" style=" overflow:hidden;" align="center">
                                            <div id="AUTOGENBOOKMARK_36_db4e0995-4893-4f05-8d14-b93164bc48ef" style=" text-align:center;">(৩)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;">
                                            <div id="AUTOGENBOOKMARK_37_685e48e4-a186-4cdb-9018-454730ffb6c7">(৪)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_38_d1215e58-9db3-4dc9-ac24-c4e768ef61db" style=" text-align:center;">(৫)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_39_ed65da29-19b7-4782-9266-49044e70e808" style=" text-align:center;">(৬)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_40_eb1aeeca-f823-4bbe-8b0c-59448230a668" style=" text-align:center;">(৭)<br>=(৩+৫)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_41_2925851a-36be-48b5-b650-ae87d0e068a0" style=" text-align:center;">(৮)<br>=(৪+৬)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_42_dba2bc44-503b-4578-a28e-57c17e64dd7d" style=" text-align:center;">(৯)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_43_a3f4babc-97a0-4f68-96c6-947516cf3493" style=" text-align:center;">(১০)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;">
                                            <div id="AUTOGENBOOKMARK_44_ba0050d5-80f6-4628-b472-ad347662d7f3">(১১)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;">
                                            <div id="AUTOGENBOOKMARK_45_0ef95cb1-a3e6-45c7-86e5-0dfdcf19f642">(১২)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_46_2b7323ae-63e5-453e-b829-b6faa9056594" style=" text-align:center;">(১৩)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_47_e08f14ea-e921-4ded-8c52-4acfcbf3d78f" style=" text-align:center;">(১৪)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_48_baddac05-b183-46cf-8ced-90f5c810e5ba" style=" text-align:center;">(১৫)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_49_ec61e8ad-5dee-484a-a971-8e3df50bb548" style=" text-align:center;">(১৬)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_50_5c9464a2-c033-4f93-88fd-7d2b9d24ff74" style=" text-align:center;">(১৭)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_51_85b3b673-022c-4255-b18e-c8e87ae43553" style=" text-align:center;">(১৮)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_52_83a28b49-dcee-4c99-b35e-20182ce5aaa5" style=" text-align:center;">(১৯) <br>=(৭-১১)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_52_83a28b49-dcee-4c99-b35e-20182ce5aaa5" style=" text-align:center;">(২০) <br>=(৮-২৬)</div>
                                        </th>
                                        <th class="style_19" style=" overflow:hidden;" align="left">
                                            <div id="AUTOGENBOOKMARK_52_83a28b49-dcee-4c99-b35e-20182ce5aaa5" style=" text-align:center;">(২১)</div>
                                        </th>
                                    </tr>

                   <?php
                                if (!empty($data)) {
                                    foreach ($data as $dt) {
                                    
                                    $sales_id = $dt['id'];
                                    if ($sales_id) {
                                        $sql = "SELECT * FROM sales_details WHERE sales_id=$sales_id ORDER BY id ASC";
                                        $connection = Yii::app()->db;
                                        $command = $connection->createCommand($sql);
                                        $items = $command->queryAll();

                                        if (!empty($items)) {                                             
                                            $i = 1;
                                            $final_opening_stock = 0;
                                            $final_opening_stock_price = 0;
                                            $final_purchase_qty = 0;
                                            $final_purchase_qty_price = 0;
                                            $final_sale_qty = 0;
                                            $final_sale_qty_price = 0;
                                            $final_tax = 0;
                                            $final_closing_qty = 0;
                                            $final_closing_qty_price = 0;
                                            foreach ($items as $item) {
                                                
                                                $model_id = $item['model_id'];
                                                $parts_service = $item['parts_service'];

                                                if ($parts_service == 1) {
                                                    $sql4 = "SELECT model_name, hs_code, unit_id, mrp FROM prod_models WHERE id=$model_id LIMIT 1";
                                                    $connection = Yii::app()->db;
                                                    $command = $connection->createCommand($sql4);
                                                    $prodInfo = $command->queryRow();

                                                    $model_name = !empty($prodInfo['model_name']) ? $prodInfo['model_name'] : '';
                                                    $hs_code = !empty($prodInfo['hs_code']) ? ' - '.$prodInfo['hs_code'] : '';
                                                    $mrp = !empty($prodInfo['mrp']) ? $prodInfo['mrp'] : 0;
                                                    $unit_id = $prodInfo['unit_id'];
                                                } else if ($parts_service == 2) {
                                                    $sql5 = "SELECT name, code, unit_id FROM services WHERE id=$model_id LIMIT 1";
                                                    $connection = Yii::app()->db;
                                                    $command = $connection->createCommand($sql5);
                                                    $serInfo = $command->queryRow();

                                                    $model_name = !empty($serInfo['name']) ? $serInfo['name'] : '';
                                                    $hs_code = !empty($serInfo['code']) ? ' - '.$serInfo['code'] : '';
                                                    $unit_id = $serInfo['unit_id'];
                                                    $mrp = 0;
                                                } else { }
                                                $unit = '';
                                                if ($unit_id) {
                                                    $unit = Units::model()->unitLabel($unit_id);
                                                }                                                
                                                
                                                $sales_date = $item['sales_date'];
                                                $sales_id = $item['sales_id'];
                                                $sql5 = "SELECT so_no, mushok_no, customer_id FROM sell_items WHERE id=$sales_id LIMIT 1";
                                                $connection = Yii::app()->db;
                                                $command = $connection->createCommand($sql5);
                                                $salesData = $command->queryRow();
                                                if (!empty($salesData['customer_id'])) {
                                                    $customerInfo = Customers::model()->findByPk($salesData['customer_id']);
                                                }
                                                ?>
                                                <tr>
                                                    <td class="style_19 center"><?= $i ?></td>
                                                    <td class="style_19"><?=!empty($item['sales_date'])?date('d-m-Y', strtotime($item['sales_date'])):'' ?></td>
                                                    <?php

                                                    $opening_Stock = 0;
                                                    $sql2 = "SELECT SUM(stock_in) as tstockin, SUM(stock_out) as tstockout FROM inventory WHERE model_id=$model_id AND transaction_date<'$sales_date' group by model_id";
                                                    $connection = Yii::app()->db;
                                                    $command = $connection->createCommand($sql2);
                                                    $stockData = $command->queryRow();
                                                    $opening_Stock = $stockData['tstockin'] - $stockData['tstockout'];
                                                    
                                                    $stock_in = 0;
                                                    $opn_stk = $item['stock_qty'];
                                                    $open_amount = $item['stock_qty'] * $mrp;
                                                    $final_closing_qty = ($opn_stk + $stock_in) - $item['sales_qty'];
                                                    $final_closing_qty_price = (($stock_in + $opening_Stock) - $item['sales_qty']) * $mrp;
                                                    if ($parts_service == 2) {
                                                        $opn_stk = 0;
                                                        $open_amount = 0;
                                                        $final_closing_qty = 0;
                                                        $final_closing_qty_price = 0;
                                                    }
                                                    if ($i == 1) {
                                                        $final_opening_stock = $opn_stk;
                                                        $final_opening_stock_price = $open_amount;
                                                    }

                                                    $sql5 = "SELECT SUM(stock_in) as tstockin FROM inventory WHERE model_id=$model_id AND transaction_date = '$sales_date' group by model_id";
                                                    $connection = Yii::app()->db;
                                                    $command = $connection->createCommand($sql5);
                                                    $productionData = $command->queryRow();
                                                     // !empty($productionData['tstockin'])?$productionData['tstockin']:0
                                                    ?>                                        
                                                    <td class="style_19 center">
                                                        <?php
                                                        echo $opn_stk;
                                                        echo '<br>';
                                                        echo $unit;
                                                        ?>
                                                    </td>                                        
                                                    <td class="style_19 right"><?= number_format($open_amount, 2) ?></td> 
                                                    <td class="style_19 center"><?= $stock_in ?><br><?= $unit ?></td>
                                                    <td class="style_19 right"><?= number_format($final_pur_qty_price = ($stock_in * $mrp), 2) ?></td>
                                                    <td class="style_19 center"><?= $opn_stk + $stock_in ?><br><?= $unit ?></td>
                                                    <td class="style_19 right"><?= number_format($open_amount+$final_pur_qty_price, 2) ?></td>
                                                    <td class="style_19"><?= !empty($customerInfo['company_name']) ? $customerInfo['company_name'] : '' ?></td>
                                                    <td class="style_19"><?= !empty($customerInfo['company_address']) ? $customerInfo['company_address'] : '' ?></td>
                                                    <td class="style_19" style="word-break:break-word"><?= !empty($customerInfo['bin']) ? $customerInfo['bin'] : '' ?></td>
                                                    <td class="style_19"><?= !empty($salesData['mushok_no']) ? 'SO-' . $salesData['mushok_no'] : '' ?></td>
                                                    <td class="style_19"><?= !empty($item['sales_date']) ? $item['sales_date'] : '' ?></td>
                                                    <td class="style_19"><?= !empty($model_name) ? $prod_final_row['model_name'][] = $model_name : '' ?></td>
                                                    <td class="style_19 center"><?= !empty($item['sales_qty']) ? $item['sales_qty'] : 0 ?><br><?= $unit ?></td>
                                                    <td class="style_19 right"><?= !empty($item['total']) ? number_format($item['total'], 2) : '0.00' ?></td>
                                                    <td class="style_19 right">0.00</td>
                                                    <td class="style_19 right"><?= !empty($item['total_tax']) ? number_format($item['total_tax'], 2) : '0.00' ?></td>
                                                    <td class="style_19 center"><?= $final_closing_qty ?><br><?= $unit ?></td>
                                                    <td class="style_19 right"><?= !empty($final_closing_qty_price) ? number_format(($final_closing_qty_price), 2) : '0.00' ?></td>
                                                    <td class="style_19"></td>
                                                </tr>
                                                <?php
                                                $final_purchase_qty = $final_purchase_qty + $stock_in;
                                                $final_purchase_qty_price = $final_purchase_qty_price + $final_pur_qty_price;
                                                $final_sale_qty = $final_sale_qty + $item['sales_qty'];
                                                $final_sale_qty_price = $final_sale_qty_price + $item['total'];
                                                $final_tax = $final_tax + $item['total_tax'];
                                                $i++;
                                            }
                                        }
                                    }
                            }
                                    ?>

                                </tbody>
                            </table>      
                        <div>
                            <div style="visibility:hidden">&nbsp;</div>
                        </div>
                        <table style="border-collapse: collapse; empty-cells: show; width: 100%; overflow:hidden; table-layout:fixed;" id="AUTOGENBOOKMARK_68_df58ce78-01cf-4f91-9952-0566ce99cb1d">
                            <colgroup>
                                <col>
                            </colgroup>
                            <tbody>
                                <tr valign="top">
                                    <td style=" overflow:hidden;">
                                        <div id="AUTOGENBOOKMARK_69_06283ac4-af1a-4e17-b4f3-83e2d8a57056">
                                            <div style="visibility:hidden">&nbsp;</div>
                                        </div>
                                        <div class="style_14" id="AUTOGENBOOKMARK_70_8e981643-f48a-457f-afcd-524949cc7537">বিশেষ দ্রষ্টব্য<br>১। যে ক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য ক্রয় করা হইবে 
                                            সেই ক্ষেত্রে উক্ত ব্যক্তির পূর্ণাঙ্গ নাম, ঠিকানা ও জাতীয় পরিচয়পত্র নম্বর যথাযথভাবে সংশ্লিষ্ট কলাম [(৯), (১০) ও (১১)] এ আবশ্যিকভাবে উল্লেখ করিতে হইবে।&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php
}
?>

</div>
<style>
    table.head-part tbody tr, table.head-part tbody tr td{
        border:none !important;
        text-align: left;
    }
</style>

<style>
    .fieldset{
        background-color: #fbf7f7f7;
    }
    .reportTabCustom{
        background-color: #fff;
        font-size:11px;
        /*width: 11in;*/
        margin:auto; 
        min-height: 9in;
        font-family: 'times new roman',arial;
    }
    .style_19{
        border: 1px solid #000 !important;
    }
    .style_20{
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
    .page-br{
        page-break-after: always;
    }
</style>
