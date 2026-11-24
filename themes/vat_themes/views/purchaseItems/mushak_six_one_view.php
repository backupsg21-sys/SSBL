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
<style>
    .reportTabCustom {
        page-break-after: always;
    }

    .data-tr tr:hover {
        background: #e5e5e5;
    }
</style>
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;">
    <!--<table class="reportTab">-->
    <?php
    if (!empty($data)) {
        $groudTotal = 0;
        foreach ($data as $d) {
            $_t_order_qty = [];
            $_t_order_price = [];
            $_t_sd = [];
            $_t_vat = [];
            $_t_15 = [];
            $_t_16 = [];
            $_t_17 = [];
            $_t_18 = [];
            $_t_20 = [];
            $groudTotal = 0;
            $line_end_date = "";
            $line_end_qty = 0;
            $line_end_closing = 0;
            $criteria = '';
            $openingCriteria = '';
            if ($d['parts_service'] == 1) {  // 1 = parts, 2 = service
                $model_id = $d['model_id'];
                $criteria = "AND model_id='$model_id'";
                $model_name = ProdModels::model()->prodNameOfThis($model_id);
                $hsCode = ProdModels::model()->prodHSCodeOfThis($model_id);
            } else if ($d['parts_service'] == 2) {
                $model_id = $d['service_id'];
                $criteria = "AND service_id='$model_id' ";
                $model_name = Services::model()->prodNameOfThis($model_id);
                $hsCode = Services::model()->prodCodeOfThis($model_id);
            } else {
            }

            $sql = "SELECT * FROM common WHERE (date BETWEEN '$start_date' AND '$end_date') " . $criteria . " AND type in(1,4,6,7,8) AND approve_count=2 ORDER BY date, type ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $items = $command->queryAll();


            //opening calculation
            $opProdModels = ProdModels::model()->findByPk($model_id);
            $opUnit = Units::model()->findByPk($opProdModels->unit_id);
            $op_unit_name = $opUnit ? $opUnit->label : '';
            $op_unit_category = $opUnit ? $opUnit->category_id : '';

            $criteriaOP = new CDbCriteria();
            $criteriaOP->select = " SUM(stock_in) as stockIn, SUM(stock_out) as stockOut, SUM(price_in) as priceIn, SUM(price_out) as priceOut, unit_id ";
            $criteriaOP->addColumnCondition(['model_id' => $model_id]);
            $criteriaOP->addCondition(" date < '$start_date' ");
            $criteriaOP->addCondition(" type in(1,4,6,7,8) AND approve_count = 2 AND item_id=2 ");
            $criteriaOP->group = 'unit_id';
            $opClosing = Common::model()->findAll($criteriaOP);
            // new query as per habib vai instruction

            $op_stock_in = $op_stock_out = $stockPrice = $closingStock = 0;
            if (!empty($opClosing)) {
                foreach ($opClosing as $cl) {
                    $op_stock_in += Units::model()->ConvertUnit($cl->stockIn, $cl->unit_id, $opProdModels->unit_id, $op_unit_category);
                    $op_stock_out += Units::model()->ConvertUnit($cl->stockOut, $cl->unit_id, $opProdModels->unit_id, $op_unit_category);
                    $stockPrice += round($cl->priceIn - $cl->priceOut);
                }
                $closingStock = round(($op_stock_in - $op_stock_out), 2);

            }
            if(!empty($items) || $closingStock > 0){
                ?>
                <table class="reportTabCustom">
                    <tbody>
                    <tr valign="top">
                        <td class="style_14" style=" overflow:hidden;">
                            <!--
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                            <table class=""
                                   style="text-align:center; border-collapse: collapse; empty-cells: show; width:100%;overflow:hidden; table-layout:fixed;margin:auto">
                                <tbody>
                                <tr valign="top">
                                    <td></td>
                                    <td style=" overflow:hidden;font-size:12px;">
                                        <div class="style_16" style=" text-align:center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয়
                                            রাজস্ব বোর্ড<br><br></div>
                                    </td>
                                    <td style="padding-top: 11px;text-align: right;"><span
                                            style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.১</span>
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
                                        <b>ক্রয় হিসাব পুস্তক<br>
                                            (পণ্য বা সেবা প্রক্রিয়াকরণে সম্পৃক্ত এমন নিবন্ধিত বা তালিকাভুক্ত ব্যক্তির জন্য
                                            প্রযোজ্য)
                                            <br>[বিধি ৪০(১) এর দফা (ক) এবং ৪১ এর দফা (ক) দ্রষ্টব্য ] </b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <div style="padding-bottom: 5px;">পণ্যের/সেবার নাম
                                : <?= !empty($model_name) ? $model_name : '' ?> (HS
                                Code: <?= !empty($hsCode) ? $hsCode : '' ?>)
                            </div>
                            <table style="border-collapse: collapse; empty-cells: show;width: 100%;" class="data-tr">
                                <colgroup>
                                    <col style=" width: 3%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 3%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 10%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" ">
                                </colgroup>
                                <tbody>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th colspan="21">
                                        পণ্য/সেবার উপকরণ ক্রয়
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th rowspan="3">
                                        ক্রমিক সংখ্যা
                                    </th>
                                    <th rowspan="3">
                                        তারিখ
                                    </th>
                                    <th colspan="2">
                                        মজুদ উপকরণের প্রারম্ভিক জের
                                    </th>
                                    <th colspan="14">
                                        ক্রয়কৃত উপকরণ
                                    </th>
                                    <th colspan="2">
                                        উপকরণের প্রান্তিক জের
                                    </th>
                                    <th class="style_21" rowspan="3">
                                        মন্তব্য
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th rowspan="2">
                                        পরিমাণ <br>(একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল <br>প্রকার কর ব্যতীত)
                                    </th>
                                    <th rowspan="2">
                                        চালানপত্র/ <br> বিল অব এন্ট্রি <br>নম্বর
                                    </th>
                                    <th rowspan="2">
                                        তারিখ
                                    </th>
                                    <th colspan="3">
                                        বিক্রেতা/ সরবরাহকারী
                                    </th>
                                    <th rowspan="2">
                                        বিবরণ
                                    </th>
                                    <th rowspan="2">
                                        পরিমাণ <br>(একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল প্রকার কর ব্যতীত)
                                    </th>
                                    <th rowspan="2">
                                        সম্পূরক শুল্ক (যদি থাকে)
                                    </th>
                                    <th rowspan="2">মূসক</th>
                                    <th colspan="2">
                                        মোট <br>উপকরণের <br>পরিমাণ
                                    </th>
                                    <th colspan="2">
                                        পণ্য প্রস্তুত/<br>প্রক্রিয়াকরণে <br> উপকরণের <br> ব্যবহার
                                    </th>
                                    <th rowspan="2">
                                        পরিমাণ (একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল প্রকার কর ব্যতীত)
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th>নাম</th>
                                    <th>ঠিকানা</th>
                                    <th>নিবন্ধন/ <br>তালিকা <br>ভুক্তি/ জাতীয় <br>পরিচয়পত্র নং</th>
                                    <th>পরিমাণ (একক)</th>
                                    <th>মূল্য <br>(সকল প্রকার কর ব্যতীত)</th>
                                    <th>পরিমাণ (একক)</th>
                                    <th>মূল্য <br>(সকল প্রকার কর ব্যতীত)</th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th style="vertical-align: middle;">(১)</th>
                                    <th style="vertical-align: middle;">(২)</th>
                                    <th style="vertical-align: middle;">(৩)</th>
                                    <th style="vertical-align: middle;">(৪)</th>
                                    <th style="vertical-align: middle;">(৫)</th>
                                    <th style="vertical-align: middle;">(৬)</th>
                                    <th style="vertical-align: middle;">(৭)</th>
                                    <th style="vertical-align: middle;">(৮)</th>
                                    <th style="vertical-align: middle;">(৯)</th>
                                    <th style="vertical-align: middle;">(১০)</th>
                                    <th style="vertical-align: middle;">(১১)</th>
                                    <th style="vertical-align: middle;">(১২)</th>
                                    <th style="vertical-align: middle;">(১৩)</th>
                                    <th style="vertical-align: middle;">(১৪)</th>
                                    <th style="vertical-align: middle;">(১৫)<br>=(৩+১১)</th>
                                    <th style="vertical-align: middle;">(১৬)<br>=(৪+১২)</th>
                                    <th style="vertical-align: middle;">(১৭)</th>
                                    <th style="vertical-align: middle;">(১৮)</th>
                                    <th style="vertical-align: middle;">(১৯)<br>=(১৫-১৭)</th>
                                    <th style="vertical-align: middle;">(২০)<br>=(১৬-১৮)</th>
                                    <th style="vertical-align: middle;">(২১)</th>
                                </tr>
                                <?php
                                if (!empty($items)) {
                                    $i = 1;

                                    foreach ($items as $item) {
                                        $issue_date = $item['date'];

                                        $transferInfo = [];
                                        $storeInfo = [];
                                        /* $customerInfo = [];
                                         if (!empty($sales_id)) {
                                             $sql5 = "SELECT so_no, mushok_no, customer_id FROM sell_items WHERE id=$sales_id LIMIT 1";
                                             $connection = Yii::app()->db;
                                             $command = $connection->createCommand($sql5);
                                             $salesData = $command->queryRow();
                                             if (!empty($salesData['customer_id'])) {
                                                 $customerInfo = Customers::model()->findByPk($salesData['customer_id']);
                                             }
                                         }*/
                                        if($item['transfer_id'] > 0){
                                            $transferInfo = StockTransferHistory::model()->findByPk($item['transfer_id']);
                                        }
                                        if($transferInfo){
                                            $storeInfo = Stores::model()->findByPk($transferInfo->received_to_store_id);
                                        }


                                        if ($line_end_date == $issue_date) {
                                            $add_with_opening = true;
                                        } else {
                                            $add_with_opening = false;
                                        }
                                        $row_12 = $row_15 = $row_16 = $row_17 = $row_18 = 0;
                                        ?>
                                        <tr valign="top"
                                            style="font-size: 11px; word-break: break-all; page-break-inside: avoid">
                                            <td style=" overflow:hidden;text-align: center"><?= $i ?></td>
                                            <td style=" overflow:hidden;"><?= !empty($issue_date) ? date('d-m-Y', strtotime($line_end_date = $issue_date)) : '' ?></td>
                                            <td style="text-align:center;">
                                                <!-- First Row = Opening Stock from query, Next Row = Opening Stock is Previous Row Closing Stock  (SAME ALSO FOR PRICE)-->
                                                <!-- Opening(Total) Stock before Issue Date -->
                                                <?php
                                                $avrPrice = 0;
                                                $prodModels = ProdModels::model()->findByPk($item['model_id']);
                                                $unit = Units::model()->findByPk($prodModels->unit_id);
                                                $unit_name = $unit ? $unit->label : '';
                                                $unit_category = $unit ? $unit->category_id : '';
                                                ?>
                                                <?php

                                                if ($i == 1) {

                                                    echo $closingStock;
                                                } else {
                                                    echo $closingStock = $line_end_qty;
                                                }

                                                ?><br> <?= !empty($unit_name) ? $unit_name : '' ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <!-- Opening(Total) Stock Price before Issue Date -->
                                                <?php
                                                if ($i == 1) {
                                                    echo number_format($stockPrice, 2);
                                                } else {
                                                    echo number_format($stockPrice = $line_end_closing, 2);
                                                }
                                                ?>
                                            </td>
                                            <td style=" width: 3%;text-align: center">
                                                <!-- Local (Ref No) OR Foreign (BOE No) -->
                                                <?php
                                                $purchaseInfo = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                if ($purchaseInfo) {
                                                    if ($item['local_import'] == 54) {
                                                        echo $purchaseInfo['ref_no'];
                                                    } else if ($item['local_import'] == 55) {
                                                        echo $purchaseInfo['boe_no'];
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td style=" overflow:hidden;">
                                                <!-- Local (Ref/Challan Date OR Foreign (BOE Date) -->
                                                <?php
                                                if ($purchaseInfo) {
                                                    if ($item['local_import'] == 54) {
                                                        echo !empty($purchaseInfo['chalan_date']) ? date('d-m-Y', strtotime($purchaseInfo['chalan_date'])) : '';
                                                    } else if ($item['local_import'] == 55) {
                                                        echo !empty($purchaseInfo['boe_date']) ? date('d-m-Y', strtotime($purchaseInfo['boe_date'])) : '';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            $supplier_name = '';
                                            $supplier_address = '';
                                            $supplier_bin = '';
                                            if ($item['purchase_id'] > 0) {
                                                $purchase_info = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                if (!empty($purchase_info)) {
                                                    if (!empty($purchase_info['supplier_id'])) {
                                                        $supplier_info = Suppliers::model()->findByPk($purchase_info['supplier_id']);
                                                        if ($supplier_info['name'])
                                                            $supplier_name = $supplier_info['name'];

                                                        if ($supplier_info['address'])
                                                            $supplier_address = $supplier_info['address'];

                                                        if ($supplier_info['bin'])
                                                            $supplier_bin = $supplier_info['bin'];
                                                    }
                                                }
                                            } else {
                                                $supplier_name = '';
                                                $supplier_address = '';
                                                $supplier_bin = '';
                                            }
                                            ?>
                                            <?php
                                            if ($item['type'] == 1 || $item['type'] == 6) {
                                                ?>
                                                <td style="text-align: left">
                                                    <?= $supplier_name ?>
                                                </td>
                                                <td style="text-align: left;padding-bottom: 3px;">
                                                    <?= $supplier_address ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= $supplier_bin ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?php
                                            }
                                            ?>
                                            <td style="text-align: left">
                                                <?= !empty($model_name) ? $model_name : '' ?>
                                            </td>
                                            <td style=" text-align:center">
                                                <!-- Order/Purchase Qty -->
                                                <?php
                                                if ($item['type'] == 1 || $item['type'] == 8) {
                                                    if(!empty($item['order_qty'])){
                                                        $_t_order_qty[] = $item['order_qty'];
                                                        echo $item['order_qty'].'<br/>';
                                                        echo !empty($unit_name) ? $unit_name : '';
                                                    }
                                                }else{
                                                    $item['order_qty'] = 0;
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            if ($item['type'] == 1 || $item['type'] == 8) {
                                                ?>
                                                <td style="text-align: right;">
                                                    <?= !empty($item['total']) ? number_format($_t_order_price[] = $row_12 = $item['total'] + $item['cd'] + $item['rd'], 2) : 0 ?>
                                                </td>
                                                <td style="text-align: right">
                                                    <?= !empty($item['sd']) ? number_format($_t_sd[] = $item['sd'], 2) : '0.00' ?>
                                                </td>
                                                <td style="text-align: right">
                                                    <?= !empty($item['vat']) ? number_format($_t_vat[] = $item['vat'], 2) : '0.00' ?>
                                                </td>
                                                <td style=" text-align:center">
                                                    <?= $_t_15[] = $row_15 = $closingStock + $item['order_qty'] ?>
                                                    <br><?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= number_format($_t_16[] = $row_16 = $stockPrice + $row_12, 2) ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td><?php $row_12 = 0; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:center;">
                                                    <?php echo $_t_15[] = $row_15 = $closingStock + $item['order_qty'] ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= number_format($_t_16[] = $row_16 = $stockPrice + $row_12,2) ?>
                                                </td>
                                                <?php
                                            } ?>
                                            <?php
                                            if ($item['type'] == 4) {
                                                ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0 ' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?php // echo !empty($item['rm_total_price']) ? number_format( $_t_18[] = $row_18 = ($item['rm_total_price']*$avrPrice),2) : '0.00'
                                                    ?>
                                                    <?php
                                                    echo number_format($_t_18[] = $row_18 = ($row_15 > 0) ? ($row_16 / $row_15) * $row_17 : 0, 2);
                                                    ?>
                                                </td>
                                                <?php
                                            } else if ($item['type'] == 6) { ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= !empty($item['price_out']) ? number_format($_t_18[] = $row_18 = $item['price_out'], 2) : '0.00' ?>
                                                </td>
                                                <?php
                                            } else if ($item['type'] == 7) { ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?php
                                                    $latestRmUnitPrice = PurchaseDetails::model()->latestRmUnitPrice($item['model_id']);
                                                    //echo !empty($item['price_out']) ? number_format($_t_18[] = $row_18 = $item['price_out'], 2) : '0.00'
                                                    echo !empty($latestRmUnitPrice) ? number_format($_t_18[] = $row_18 = $latestRmUnitPrice * $item['stock_out'], 2) : '0.00'
                                                    ?>
                                                </td>
                                                <?php
                                            } else { ?>
                                                <td></td>
                                                <td></td>
                                                <?php
                                            } ?>
                                            <td style="text-align: center;">
                                                <!-- Set Row Closing Qty for use in Next Row as Opening Qty -->
                                                <?= $line_end_qty = (($closingStock + $item['order_qty'] - $row_17)); ?>
                                                <br><?= !empty($unit_name) ? $unit_name : '' ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <!-- Set Row Closing Price for use in Next Row as Opening Price -->
                                                <?php
                                                echo number_format($_t_20[]  =$line_end_closing = ($row_16) - $row_18, 2);
                                                ?>
                                            </td>
                                            <td style=" overflow:hidden;text-align:center;">
                                                <?php
                                                if ($item['type'] == 6){
                                                    echo 'P. Return';
                                                }else{
                                                    echo $item['reason'];
                                                }
                                                ?>
                                            </td>
                                        </tr>

                                        <?php
                                        $i++;
                                    }
                                }else {
                                    ?>
                                    <tr>
                                        <td class="style_19 center">1</td>
                                        <td class="style_19"><?= !empty($start_date) ? date('d-m-Y', strtotime($start_date)) : '' ?></td>
                                        <td class="style_19 center">
                                            <?php
                                            echo $closingStock;
                                            ?>
                                        </td>
                                        <td class="style_19 center">

                                            <?php
                                            echo number_format($stockPrice, 2);
                                            ?>
                                        </td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19 center"></td>
                                        <td class="style_19 center ">&nbsp</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;  <?= $_t_15[] = $row_15 = $closingStock + 0 ?> <?= !empty($unit_name) ? $unit_name : '' ?></td>
                                        <td class="style_19">&nbsp;<?= number_format($_t_16[] = $row_16 = $stockPrice + 0, 2) ?> </td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19 center">&nbsp; <?= $row_15 ?></td>
                                        <td class="style_19 center"> <?= $row_16 ?></td>
                                        <td class="style_19">&nbsp;</td>
                                    </tr>
                                <?php } ?>
                                <tr style="font-size:11px;">
                                    <td colspan="10" style="text-align: right">সর্বমোট &nbsp;
                                    </td>
                                    <td style="text-align: center">
                                        <?php echo array_sum($_t_order_qty); ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_order_price), 2); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_sd), 2); ?>
                                    </td>
                                    <td style=" text-align: right;">
                                        <?php echo number_format(array_sum($_t_vat), 2); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php // echo array_sum($_t_15)  ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_16),2); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo array_sum($_t_17) ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_18), 2); ?>
                                    </td>
                                    <td style=" overflow:hidden;"></td>
                                    <td style=" overflow:hidden; text-align: right"><?php echo number_format(array_sum($_t_20), 2); ?> </td>
                                    <td style=" overflow:hidden;"></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><br><br>
                            <b>বিশেষ দ্রষ্টব্য : </b><br>
                            ১। অর্থনৈতিক কার্যক্রম সংশ্লিষ্ট সকল প্রকার ক্রয়ের তথ্য এই ফরমে অন্তর্ভুক্ত করিতে হইবে। <br>
                            ২। যে ক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য ক্রয় করা হইবে সেই ক্ষেত্রে উক্ত ব্যক্তির
                            পূর্ণাঙ্গ নাম, ঠিকানা ও জাতীয় পরিচয়পত্র নম্বর যথাযথ ভাবে সংশ্লিষ্ট কলাম [(৭), (৮), (৯)] এ
                            আবশ্যিকভাবে উল্লেখ করিতে হইবে। <br>
                            ৩। উপকরণ ক্রয়ের স্বপক্ষে প্রামাণিক দলিল হিসাবে বিল অব এন্ট্রি বা চালানপত্রের কপি
                            সংরক্ষণ করিতে হইবে।
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>

                <?php
            }
        }
    } else {
        echo '<span style="color:red;font-weight: bold;">Sorry! No Data Available</span>';
    }
    ?>
    <!--</table>-->
</div>

<style>
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 11px;
        margin: auto;
        font-family: 'times new roman', arial;
        background-color: #fff;
        /* word-break: break-all;
         width: 10in;*/
    }

    .data-tr th, .data-tr td {
        border: 1px solid #000;
        font-weight: normal;
    }
</style><?php
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
<style>
    .reportTabCustom {
        page-break-after: always;
    }

    .data-tr tr:hover {
        background: #e5e5e5;
    }
</style>
<div class='printAllTableForThisReport' style="background-color:#F9FCFD;">
    <!--<table class="reportTab">-->
    <?php
    if (!empty($data)) {
        $groudTotal = 0;
        foreach ($data as $d) {
            $_t_order_qty = [];
            $_t_order_price = [];
            $_t_sd = [];
            $_t_vat = [];
            $_t_15 = [];
            $_t_16 = [];
            $_t_17 = [];
            $_t_18 = [];
            $_t_20 = [];
            $groudTotal = 0;
            $line_end_date = "";
            $line_end_qty = 0;
            $line_end_closing = 0;
            $criteria = '';
            $openingCriteria = '';
            if ($d['parts_service'] == 1) {  // 1 = parts, 2 = service
                $model_id = $d['model_id'];
                $criteria = "AND model_id='$model_id'";
                $model_name = ProdModels::model()->prodNameOfThis($model_id);
                $hsCode = ProdModels::model()->prodHSCodeOfThis($model_id);
            } else if ($d['parts_service'] == 2) {
                $model_id = $d['service_id'];
                $criteria = "AND service_id='$model_id' ";
                $model_name = Services::model()->prodNameOfThis($model_id);
                $hsCode = Services::model()->prodCodeOfThis($model_id);
            } else {
            }



            $sql = "SELECT * FROM common WHERE (date BETWEEN '$start_date' AND '$end_date') " . $criteria . " AND type in(1,4,6,7,8) AND approve_count=2 ORDER BY date, type ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $items = $command->queryAll();


            //opening calculation
            $opProdModels = ProdModels::model()->findByPk($model_id);
            $opUnit = Units::model()->findByPk($opProdModels->unit_id);
            $op_unit_name = $opUnit ? $opUnit->label : '';
            $op_unit_category = $opUnit ? $opUnit->category_id : '';

            $criteriaOP = new CDbCriteria();
            $criteriaOP->select = " SUM(stock_in) as stockIn, SUM(stock_out) as stockOut, SUM(price_in) as priceIn, SUM(price_out) as priceOut, unit_id ";
            $criteriaOP->addColumnCondition(['model_id' => $model_id]);
            $criteriaOP->addCondition(" date < '$start_date' ");
            $criteriaOP->addCondition(" type in(1,4,6,7,8) AND approve_count = 2 AND item_id=2 $openingCriteria");
            $criteriaOP->group = 'unit_id';
            $opClosing = Common::model()->findAll($criteriaOP);
            // new query as per habib vai instruction

            $op_stock_in = $op_stock_out = $stockPrice = $closingStock = 0;
            if (!empty($opClosing)) {
                foreach ($opClosing as $cl) {
                    $op_stock_in += Units::model()->ConvertUnit($cl->stockIn, $cl->unit_id, $opProdModels->unit_id, $op_unit_category);
                    $op_stock_out += Units::model()->ConvertUnit($cl->stockOut, $cl->unit_id, $opProdModels->unit_id, $op_unit_category);
                    $stockPrice += round($cl->priceIn - $cl->priceOut);
                }
                $closingStock = round(($op_stock_in - $op_stock_out), 2);

            }
            if(!empty($items) || $closingStock > 0){
                ?>
                <table class="reportTabCustom table2excel table2excel_with_colors">
                    <tbody>
                    <tr valign="top">
                        <td class="style_14" style=" overflow:hidden;">
                            <!--
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bdlogo.jpg" style="width: 80px;position: absolute;"/>-->
                            <table class=""
                                   style="text-align:center; border-collapse: collapse; empty-cells: show; width:100%;overflow:hidden; table-layout:fixed;margin:auto">
                                <tbody>
                                <tr valign="top">
                                    <td></td>
                                    <td style=" overflow:hidden;font-size:12px;">
                                        <div class="style_16" style=" text-align:center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br>জাতীয়
                                            রাজস্ব বোর্ড<br><br></div>
                                    </td>
                                    <td style="padding-top: 11px;text-align: right;"><span
                                            style="font-weight: bold;padding: 3px;border: 1px solid #000;">মূসক-৬.১</span>
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
                                        <b>ক্রয় হিসাব পুস্তক<br>
                                            (পণ্য বা সেবা প্রক্রিয়াকরণে সম্পৃক্ত এমন নিবন্ধিত বা তালিকাভুক্ত ব্যক্তির জন্য
                                            প্রযোজ্য)
                                            <br>[বিধি ৪০(১) এর দফা (ক) এবং ৪১ এর দফা (ক) দ্রষ্টব্য ] </b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <div style="padding-bottom: 5px;">পণ্যের/সেবার নাম
                                : <?= !empty($model_name) ? $model_name : '' ?> (HS
                                Code: <?= !empty($hsCode) ? $hsCode : '' ?>)
                            </div>
                            <table style="border-collapse: collapse; empty-cells: show;width: 100%;" class="data-tr">
                                <colgroup>
                                    <col style=" width: 3%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 3%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 10%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 6%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" width: 4%;">
                                    <col style=" ">
                                </colgroup>
                                <tbody>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th colspan="21">
                                        পণ্য/সেবার উপকরণ ক্রয়
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th rowspan="3">
                                        ক্রমিক সংখ্যা
                                    </th>
                                    <th rowspan="3">
                                        তারিখ
                                    </th>
                                    <th colspan="2">
                                        মজুদ উপকরণের প্রারম্ভিক জের
                                    </th>
                                    <th colspan="14">
                                        ক্রয়কৃত উপকরণ
                                    </th>
                                    <th colspan="2">
                                        উপকরণের প্রান্তিক জের
                                    </th>
                                    <th class="style_21" rowspan="3">
                                        মন্তব্য
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th rowspan="2">
                                        পরিমাণ <br>(একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল <br>প্রকার কর ব্যতীত)
                                    </th>
                                    <th rowspan="2">
                                        চালানপত্র/ <br> বিল অব এন্ট্রি <br>নম্বর
                                    </th>
                                    <th rowspan="2">
                                        তারিখ
                                    </th>
                                    <th colspan="3">
                                        বিক্রেতা/ সরবরাহকারী
                                    </th>
                                    <th rowspan="2">
                                        বিবরণ
                                    </th>
                                    <th rowspan="2">
                                        পরিমাণ <br>(একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল প্রকার কর ব্যতীত)
                                    </th>
                                    <th rowspan="2">
                                        সম্পূরক শুল্ক (যদি থাকে)
                                    </th>
                                    <th rowspan="2">মূসক</th>
                                    <th colspan="2">
                                        মোট <br>উপকরণের <br>পরিমাণ
                                    </th>
                                    <th colspan="2">
                                        পণ্য প্রস্তুত/<br>প্রক্রিয়াকরণে <br> উপকরণের <br> ব্যবহার
                                    </th>
                                    <th rowspan="2">
                                        পরিমাণ (একক)
                                    </th>
                                    <th rowspan="2">
                                        মূল্য <br>(সকল প্রকার কর ব্যতীত)
                                    </th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th>নাম</th>
                                    <th>ঠিকানা</th>
                                    <th>নিবন্ধন/ <br>তালিকা <br>ভুক্তি/ জাতীয় <br>পরিচয়পত্র নং</th>
                                    <th>পরিমাণ (একক)</th>
                                    <th>মূল্য <br>(সকল প্রকার কর ব্যতীত)</th>
                                    <th>পরিমাণ (একক)</th>
                                    <th>মূল্য <br>(সকল প্রকার কর ব্যতীত)</th>
                                </tr>
                                <tr style="font-size:12px;" valign="top" align="center">
                                    <th style="vertical-align: middle;">(১)</th>
                                    <th style="vertical-align: middle;">(২)</th>
                                    <th style="vertical-align: middle;">(৩)</th>
                                    <th style="vertical-align: middle;">(৪)</th>
                                    <th style="vertical-align: middle;">(৫)</th>
                                    <th style="vertical-align: middle;">(৬)</th>
                                    <th style="vertical-align: middle;">(৭)</th>
                                    <th style="vertical-align: middle;">(৮)</th>
                                    <th style="vertical-align: middle;">(৯)</th>
                                    <th style="vertical-align: middle;">(১০)</th>
                                    <th style="vertical-align: middle;">(১১)</th>
                                    <th style="vertical-align: middle;">(১২)</th>
                                    <th style="vertical-align: middle;">(১৩)</th>
                                    <th style="vertical-align: middle;">(১৪)</th>
                                    <th style="vertical-align: middle;">(১৫)<br>=(৩+১১)</th>
                                    <th style="vertical-align: middle;">(১৬)<br>=(৪+১২)</th>
                                    <th style="vertical-align: middle;">(১৭)</th>
                                    <th style="vertical-align: middle;">(১৮)</th>
                                    <th style="vertical-align: middle;">(১৯)<br>=(১৫-১৭)</th>
                                    <th style="vertical-align: middle;">(২০)<br>=(১৬-১৮)</th>
                                    <th style="vertical-align: middle;">(২১)</th>
                                </tr>
                                <?php
                                if (!empty($items)) {
                                    $i = 1;

                                    foreach ($items as $item) {
                                        $issue_date = $item['date'];

                                        $transferInfo = [];
                                        $storeInfo = [];
                                        /* $customerInfo = [];
                                         if (!empty($sales_id)) {
                                             $sql5 = "SELECT so_no, mushok_no, customer_id FROM sell_items WHERE id=$sales_id LIMIT 1";
                                             $connection = Yii::app()->db;
                                             $command = $connection->createCommand($sql5);
                                             $salesData = $command->queryRow();
                                             if (!empty($salesData['customer_id'])) {
                                                 $customerInfo = Customers::model()->findByPk($salesData['customer_id']);
                                             }
                                         }*/
                                        if($item['transfer_id'] > 0){
                                            $transferInfo = StockTransferHistory::model()->findByPk($item['transfer_id']);
                                        }
                                        if($transferInfo){
                                            $storeInfo = Stores::model()->findByPk($transferInfo->received_to_store_id);
                                        }


                                        if ($line_end_date == $issue_date) {
                                            $add_with_opening = true;
                                        } else {
                                            $add_with_opening = false;
                                        }
                                        $row_12 = $row_15 = $row_16 = $row_17 = $row_18 = 0;
                                        ?>
                                        <tr valign="top"
                                            style="font-size: 11px; word-break: break-all; page-break-inside: avoid">
                                            <td style=" overflow:hidden;text-align: center"><?= $i ?></td>
                                            <td style=" overflow:hidden;"><?= !empty($issue_date) ? date('d-m-Y', strtotime($line_end_date = $issue_date)) : '' ?></td>
                                            <td style="text-align:center;">
                                                <!-- First Row = Opening Stock from query, Next Row = Opening Stock is Previous Row Closing Stock  (SAME ALSO FOR PRICE)-->
                                                <!-- Opening(Total) Stock before Issue Date -->
                                                <?php
                                                $avrPrice = 0;
                                                $prodModels = ProdModels::model()->findByPk($item['model_id']);
                                                $unit = Units::model()->findByPk($prodModels->unit_id);
                                                $unit_name = $unit ? $unit->label : '';
                                                $unit_category = $unit ? $unit->category_id : '';
                                                ?>
                                                <?php

                                                if ($i == 1) {

                                                    echo $closingStock;
                                                } else {
                                                    echo $closingStock = $line_end_qty;
                                                }

                                                ?><br> <?= !empty($unit_name) ? $unit_name : '' ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <!-- Opening(Total) Stock Price before Issue Date -->
                                                <?php
                                                if ($i == 1) {
                                                    echo number_format($stockPrice, 2);
                                                } else {
                                                    echo number_format($stockPrice = $line_end_closing, 2);
                                                }
                                                ?>
                                            </td>
                                            <td style=" width: 3%;text-align: center">
                                                <!-- Local (Ref No) OR Foreign (BOE No) -->
                                                <?php
                                                $purchaseInfo = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                if ($purchaseInfo) {
                                                    if ($item['local_import'] == 54) {
                                                        echo $purchaseInfo['ref_no'];
                                                    } else if ($item['local_import'] == 55) {
                                                        echo $purchaseInfo['boe_no'];
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td style=" overflow:hidden;">
                                                <!-- Local (Ref/Challan Date OR Foreign (BOE Date) -->
                                                <?php
                                                if ($purchaseInfo) {
                                                    if ($item['local_import'] == 54) {
                                                        echo !empty($purchaseInfo['chalan_date']) ? date('d-m-Y', strtotime($purchaseInfo['chalan_date'])) : '';
                                                    } else if ($item['local_import'] == 55) {
                                                        echo !empty($purchaseInfo['boe_date']) ? date('d-m-Y', strtotime($purchaseInfo['boe_date'])) : '';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            $supplier_name = '';
                                            $supplier_address = '';
                                            $supplier_bin = '';
                                            if ($item['purchase_id'] > 0) {
                                                $purchase_info = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                if (!empty($purchase_info)) {
                                                    if (!empty($purchase_info['supplier_id'])) {
                                                        $supplier_info = Suppliers::model()->findByPk($purchase_info['supplier_id']);
                                                        if ($supplier_info['name'])
                                                            $supplier_name = $supplier_info['name'];

                                                        if ($supplier_info['address'])
                                                            $supplier_address = $supplier_info['address'];

                                                        if ($supplier_info['bin'])
                                                            $supplier_bin = $supplier_info['bin'];
                                                    }
                                                }
                                            } else {
                                                $supplier_name = '';
                                                $supplier_address = '';
                                                $supplier_bin = '';
                                            }
                                            ?>
                                            <?php
                                            if ($item['type'] == 1 || $item['type'] == 6) {
                                                ?>
                                                <td style="text-align: left">
                                                    <?= $supplier_name ?>
                                                </td>
                                                <td style="text-align: left;padding-bottom: 3px;">
                                                    <?= $supplier_address ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= $supplier_bin ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?php
                                            }
                                            ?>
                                            <td style="text-align: left">
                                                <?= !empty($model_name) ? $model_name : '' ?>
                                            </td>
                                            <td style=" text-align:center">
                                                <!-- Order/Purchase Qty -->
                                                <?php
                                                if ($item['type'] == 1 || $item['type'] == 8) {
                                                    if(!empty($item['order_qty'])){
                                                        $_t_order_qty[] = $item['order_qty'];
                                                        echo $item['order_qty'].'<br/>';
                                                        echo !empty($unit_name) ? $unit_name : '';
                                                    }
                                                }else{
                                                    $item['order_qty'] = 0;
                                                }
                                                ?>
                                            </td>
                                            <?php
                                            if ($item['type'] == 1 || $item['type'] == 8) {
                                                ?>
                                                <td style="text-align: right;">
                                                    <?= !empty($item['total']) ? number_format($_t_order_price[] = $row_12 = $item['total'] + $item['cd'] + $item['rd'], 2) : 0 ?>
                                                </td>
                                                <td style="text-align: right">
                                                    <?= !empty($item['sd']) ? number_format($_t_sd[] = $item['sd'], 2) : '0.00' ?>
                                                </td>
                                                <td style="text-align: right">
                                                    <?= !empty($item['vat']) ? number_format($_t_vat[] = $item['vat'], 2) : '0.00' ?>
                                                </td>
                                                <td style=" text-align:center">
                                                    <?= $_t_15[] = $row_15 = $closingStock + $item['order_qty'] ?>
                                                    <br><?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= number_format($_t_16[] = $row_16 = $stockPrice + $row_12, 2) ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td><?php $row_12 = 0; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:center;">
                                                    <?php echo $_t_15[] = $row_15 = $closingStock + $item['order_qty'] ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= number_format($_t_16[] = $row_16 = $stockPrice + $row_12,2) ?>
                                                </td>
                                                <?php
                                            } ?>
                                            <?php
                                            if ($item['type'] == 4) {
                                                ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0 ' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                <?= !empty($item['price_out']) ? number_format($_t_18[] = $row_18 = $item['price_out'], 2) : '0.00' ?>
                                                </td>
                                                <?php
                                            } else if ($item['type'] == 6) { ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?= !empty($item['price_out']) ? number_format($_t_18[] = $row_18 = $item['price_out'], 2) : '0.00' ?>
                                                </td>
                                                <?php
                                            } else if ($item['type'] == 7) { ?>
                                                <td style=" overflow:hidden;text-align:center;">
                                                    <?= !empty($item['stock_out']) ? $_t_17[] = $row_17 = $item['stock_out'] : '0' ?>
                                                    <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td style=" overflow:hidden;text-align:right;">
                                                    <?php
                                                    $latestRmUnitPrice = PurchaseDetails::model()->latestRmUnitPrice($item['model_id']);
                                                    //echo !empty($item['price_out']) ? number_format($_t_18[] = $row_18 = $item['price_out'], 2) : '0.00'
                                                    echo !empty($latestRmUnitPrice) ? number_format($_t_18[] = $row_18 = $latestRmUnitPrice * $item['stock_out'], 2) : '0.00'
                                                    ?>
                                                </td>
                                                <?php
                                            } else { ?>
                                                <td></td>
                                                <td></td>
                                                <?php
                                            } ?>
                                            <td style="text-align: center;">
                                                <!-- Set Row Closing Qty for use in Next Row as Opening Qty -->
                                                <?= $line_end_qty = (($closingStock + $item['order_qty'] - $row_17)); ?>
                                                <br><?= !empty($unit_name) ? $unit_name : '' ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <!-- Set Row Closing Price for use in Next Row as Opening Price -->
                                                <?php
                                                echo number_format($_t_20[]  =$line_end_closing = ($row_16) - $row_18, 2);
                                                ?>
                                            </td>
                                            <td style=" overflow:hidden;text-align:center;">
                                                <?php
                                                if ($item['type'] == 6){
                                                    echo 'P. Return';
                                                }else{
                                                    echo $item['reason'];
                                                }
                                                ?>
                                            </td>
                                        </tr>

                                        <?php
                                        $i++;
                                    }
                                }else {
                                    ?>
                                    <tr>
                                        <td class="style_19 center">1</td>
                                        <td class="style_19"><?= !empty($start_date) ? date('d-m-Y', strtotime($start_date)) : '' ?></td>
                                        <td class="style_19 center">
                                            <?php
                                            echo $closingStock;
                                            ?>
                                        </td>
                                        <td class="style_19 center">

                                            <?php
                                            echo number_format($stockPrice, 2);
                                            ?>
                                        </td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19 center"></td>
                                        <td class="style_19 center ">&nbsp</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;  <?= $_t_15[] = $row_15 = $closingStock + 0 ?> <?= !empty($unit_name) ? $unit_name : '' ?></td>
                                        <td class="style_19">&nbsp;<?= number_format($_t_16[] = $row_16 = $stockPrice + 0, 2) ?> </td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19">&nbsp;</td>
                                        <td class="style_19 center">&nbsp; <?= $row_15 ?></td>
                                        <td class="style_19 center"> <?= $row_16 ?></td>
                                        <td class="style_19">&nbsp;</td>
                                    </tr>
                                <?php } ?>
                                <tr style="font-size:11px;">
                                    <td colspan="10" style="text-align: right">সর্বমোট &nbsp;
                                    </td>
                                    <td style="text-align: center">
                                        <?php echo array_sum($_t_order_qty); ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_order_price), 2); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_sd), 2); ?>
                                    </td>
                                    <td style=" text-align: right;">
                                        <?php echo number_format(array_sum($_t_vat), 2); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php // echo array_sum($_t_15)  ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_16),2); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo array_sum($_t_17) ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo number_format(array_sum($_t_18), 2); ?>
                                    </td>
                                    <td style=" overflow:hidden;"></td>
                                    <td style=" overflow:hidden; text-align: right"><?php echo number_format(array_sum($_t_20), 2); ?> </td>
                                    <td style=" overflow:hidden;"></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><br><br>
                            <b>বিশেষ দ্রষ্টব্য : </b><br>
                            ১। অর্থনৈতিক কার্যক্রম সংশ্লিষ্ট সকল প্রকার ক্রয়ের তথ্য এই ফরমে অন্তর্ভুক্ত করিতে হইবে। <br>
                            ২। যে ক্ষেত্রে অনিবন্ধিত ব্যক্তির নিকট হইতে পণ্য ক্রয় করা হইবে সেই ক্ষেত্রে উক্ত ব্যক্তির
                            পূর্ণাঙ্গ নাম, ঠিকানা ও জাতীয় পরিচয়পত্র নম্বর যথাযথ ভাবে সংশ্লিষ্ট কলাম [(৭), (৮), (৯)] এ
                            আবশ্যিকভাবে উল্লেখ করিতে হইবে। <br>
                            ৩। উপকরণ ক্রয়ের স্বপক্ষে প্রামাণিক দলিল হিসাবে বিল অব এন্ট্রি বা চালানপত্রের কপি
                            সংরক্ষণ করিতে হইবে।
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>

                <?php
            }
        }
    } else {
        echo '<span style="color:red;font-weight: bold;">Sorry! No Data Available</span>';
    }
    ?>
    <!--</table>-->
</div>

<style>
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 11px;
        margin: auto;
        font-family: 'times new roman', arial;
        background-color: #fff;
        /* word-break: break-all;
         width: 10in;*/
    }

    .data-tr th, .data-tr td {
        border: 1px solid #000;
        font-weight: normal;
    }
</style>
