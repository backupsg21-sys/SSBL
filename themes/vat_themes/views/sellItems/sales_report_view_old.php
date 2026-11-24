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
.reportTabCustom{
    page-break-after: always;
}
 .printAllTableForThisReport .stickyHead .csd th{
        position: sticky;
        top: 0;
        background-color: #DADADA;
        color: #000000;
        border: 1px solid #C0C0C0;
        text-decoration: none;
    }
</style>
<div class='printAllTableForThisReport'  style="background-color:#F1F3F4;">
   <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff">
        <tbody>
            <tr valign="top">
                <td class="style_14" style=" overflow:hidden;">
                    <?php
                    // FOR SINGLE PRODUCT
                    if($prod_id){ ?>

                        <table style="empty-cells: show;width: 100%;" class="stickyHead data-tr">
                            
                            <tbody>
                            <tr valign="top">
                                  <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="17"><b>
                                                  <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?><br></b>
                                                  <?= !empty($yourCompanyInfo['house']) ? ' House: '.$yourCompanyInfo['house'] : '' ?>
                                                  <?= !empty($yourCompanyInfo['road']) ? ', Road: '.$yourCompanyInfo['road'] : '' ?>
                                                  <?= !empty($yourCompanyInfo['location']) ? ', Location: '.$yourCompanyInfo['location'] : '' ?>
                                                  <br>
                                                  <?= !empty($yourCompanyInfo['vat_regi_no'] ) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                                  </td>
                            </tr>
                            <tr valign="top">
                                    <td colspan="17" style=" overflow:hidden; font-size:13px; text-align: center;">
                                           Sales Report (Details) - <?=ProdModels::model()->prodNameOfThis($prod_id)?>
                                    </th>
                            </tr>
 
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center" rowspan="2">
                                    SL
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Date
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Opening
                                </th>
                                <th class="style_24 center" colspan="2">
                                    Production
                                </th>
                                <th class="style_24 center" colspan="3">
                                    Sales
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Closing
                                </th>
                            </tr>
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center">
                                    Own
                                </th>
                                <th class="style_24 center">
                                    Contractual
                                </th>
                                <th class="style_24 center">
                                    Local Value
                                </th>
                                <th class="style_24 center">
                                    Local VAT
                                </th>
                                <th class="style_24 center">
                                    Export Value
                                </th>
                            </tr>
                            <?php
                            if(!empty($data)){

                                $i=1;
                                $line_closing = 0;
                                $total_opening = 0;
                                $total_production_qty_own = 0;
                                $total_production_qty_contract = 0;
                                $total_sales_local = 0;
                                $total_sales_export = 0;
                                $total_sales_local_vat = 0;
                                $total_closing = 0;

                                foreach($data as $date_data){
                                    $opening = $productionQtyOwn = $productionQtyContract = $productionQty = 0;
                                    ?>
                                    <tr class="csd">
                                <?php
                                    $date_single = $date_data['date'];

                                    $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM common WHERE date < '$date_single' AND model_id='$prod_id' AND (type=2 OR type=3 OR type=5)";
                                    $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();
                                    if($openingData)
                                        $opening = $openingData['stockIn'] - $openingData['stockOut'];

                                    $sqlLocal = "SELECT SUM(total) AS salesLocalTotal, SUM(vat) AS salesLocalVat, SUM(order_qty) AS salesLocalQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=2 AND local_import=1";
                                    $salesQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                                    if($salesQtyLocalData){
                                        $salesQtyLocal = $salesQtyLocalData['salesLocalQty'];
                                        $salesLocalTotal = $salesQtyLocalData['salesLocalTotal'];
                                        $salesLocalVat = $salesQtyLocalData['salesLocalVat'];
                                    }

                                    $sqlExport = "SELECT SUM(total) AS salesExportTotal, SUM(order_qty) AS purchaseImportQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=2 AND local_import=2";
                                    $orderQtyExportData = Yii::app()->db->createCommand($sqlExport)->queryRow();

                                    if($orderQtyExportData)
                                        $orderQtyImport = $orderQtyExportData['purchaseImportQty'];
                                        $salesExportTotal = $orderQtyExportData['salesExportTotal'];

                                    $sqlProductionOwn = "SELECT SUM(model_qty) AS productionQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=3 AND (contractual_status = 0 OR contractual_status is null)";
                                    $productionDataOwn = Yii::app()->db->createCommand($sqlProductionOwn)->queryRow();
                                    if($productionDataOwn)
                                        $productionQtyOwn = $productionDataOwn['productionQty'];

                                    $sqlProductionContract = "SELECT SUM(model_qty) AS productionQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=3 AND (contractual_status = 1)";
                                    $productionDataContract = Yii::app()->db->createCommand($sqlProductionContract)->queryRow();
                                    if($productionDataContract)
                                        $productionQtyContract = $productionDataContract['productionQty'];
                                    ?>

                                        <td class="center"><?=$i?></td>
                                        <td class="center"><?=!empty($date_single)? date('d-m-Y', strtotime($date_single)):''?></td>
                                        <td class="center">
                                            <?php
                                            if($i==1)
                                                echo $total_opening = $opening;
                                            else
                                                echo $opening = $line_closing;
                                            ?>
                                        </td>
                                        <td class="center"><?=!empty($productionQtyOwn)? $productionQtyOwn:0?></td>
                                        <td class="center"><?=!empty($productionQtyContract)? $productionQtyContract:0?></td>
                                        <td class="right"><?=!empty($salesLocalTotal)? number_format($salesLocalTotal,2):'0.00'?></td>
                                        <td class="right"><?=!empty($salesLocalVat)? number_format($salesLocalVat,2):'0.00'?></td>
                                        <td class="right"><?=!empty($salesExportTotal)? number_format($salesExportTotal,2):'0.00'?></td>
                                        <td class="center"><?= $total_closing = $line_closing = ($opening + $productionQtyOwn + $productionQtyContract)?></td>
                                    </tr>
                                <?php
                                    $total_production_qty_own += $productionQtyOwn;
                                    $total_production_qty_contract += $productionQtyContract;
                                    $total_sales_local += $salesLocalTotal;
                                    $total_sales_export += $salesExportTotal;
                                    $total_sales_local_vat += $salesLocalVat;
                                    $i++;
                                }?>
                                <tr class="center bold csd footer-color">
                                    <td colspan="3" style="padding-right: 5px; text-align: right">Total</td>
                                    <td><?=$total_production_qty_own?></td>
                                    <td><?=$total_production_qty_contract?></td>
                                    <td style="text-align: right"><?=!empty($total_sales_local)? number_format($total_sales_local,2):'0.00'?></td>
                                    <td style="text-align: right"><?=!empty($total_sales_local_vat)? number_format($total_sales_local_vat,2):'0.00'?></td>
                                    <td style="text-align: right"><?=!empty($total_sales_export)? number_format($total_sales_export,2):'0.00'?></td>
                                    <td><?=$total_closing?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                    <?php
                        // FOR ALL PRODUCT (SUMMARY)
                    }else{ ?>

                        <table style="empty-cells: show;width: 100%;" class="stickyHead data-tr">
                            <tr valign="top">
                                  <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="17"><b>
                                                  <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?><br></b>
                                                  <?= !empty($yourCompanyInfo['house']) ? ' House: '.$yourCompanyInfo['house'] : '' ?>
                                                  <?= !empty($yourCompanyInfo['road']) ? ', Road: '.$yourCompanyInfo['road'] : '' ?>
                                                  <?= !empty($yourCompanyInfo['location']) ? ', Location: '.$yourCompanyInfo['location'] : '' ?>
                                                  <br>
                                                  <?= !empty($yourCompanyInfo['vat_regi_no'] ) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                                  </td>
                            </tr>
                            <tr valign="top">
                                    <td colspan="17" style=" overflow:hidden; font-size:13px; text-align: center;">
                                            Sales Stock Report
                                    </th>
                            </tr>
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center" rowspan="2">
                                    SL
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Goods
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Opening
                                </th>
                                <th class="style_24 center" colspan="2">
                                    Production
                                </th>
                                <th class="style_24 center" colspan="3">
                                    Sales
                                </th>
                                <th class="style_24 center" rowspan="2">
                                    Closing
                                </th>
                            </tr>
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center">
                                    Own
                                </th>
                                <th class="style_24 center">
                                    Contractual
                                </th>
                                <th class="style_24 center">
                                    Local Value
                                </th>
                                <th class="style_24 center">
                                    Local VAT
                                </th>
                                <th class="style_24 center">
                                    Export Value
                                </th>
                            </tr>
                             <tbody>
                            <?php
                            if(!empty($data)){

                                $i=1;
                                $total_opening = 0;
                                $total_sales_local = 0;
                                $total_sales_export = 0;
                                $total_consumption = 0;
                                $total_wastage = 0;
                                $total_closing = 0;

                                foreach($data as $date_data){
                                    $opening = $line_closing = $salesQtyLocal = $orderQtyImport = $consumptionQty = $wasteQty = 0;
                                    ?>
                                    <tr class="csd">
                                        <?php
                                        $prod_id = $date_data['model_id'];

                                        $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM common WHERE date < '$start_date' AND model_id='$prod_id' AND (type=1 OR type=4 OR type=6)";
                                        $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();
                                        if($openingData)
                                            $opening = $openingData['stockIn'] - $openingData['stockOut'];

                                        $sqlLocal = "SELECT SUM(order_qty) AS salesLocalQty FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=1 AND local_import=54";
                                        $salesQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                                        if($salesQtyLocalData)
                                            $salesQtyLocal = $salesQtyLocalData['salesLocalQty'];

                                        $sqlImport = "SELECT SUM(order_qty) AS purchaseImportQty FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=1 AND local_import=55";
                                        $orderQtyImportData = Yii::app()->db->createCommand($sqlImport)->queryRow();
                                        if($orderQtyImportData)
                                            $orderQtyImport = $orderQtyImportData['purchaseImportQty'];

                                        $sqlProduction = "SELECT SUM(rm_qty) AS consumptionQty, SUM(waste_qty) AS wasteQty FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=4";
                                        $productionData = Yii::app()->db->createCommand($sqlProduction)->queryRow();
                                        if($productionData)
                                            $consumptionQty = $productionData['consumptionQty'];
                                        $wasteQty = $productionData['wasteQty'];
                                        ?>

                                        <td class="center"><?=$i?></td>
                                        <td class="center"><?=ProdModels::model()->prodNameOfThis($prod_id)?></td>
                                        <td class="center">
                                            <?php
                                            if($i==1)
                                                echo $total_opening = $opening;
                                            else
                                                echo $opening = $line_closing;
                                            ?>
                                        </td>
                                        <td class="center"><?=!empty($salesQtyLocal)? $salesQtyLocal:0?></td>
                                        <td class="center"><?=!empty($orderQtyImport)? $orderQtyImport:0?></td>
                                        <td class="center">0.00</td>
                                        <td class="center">0.00</td>
                                        <td class="center"><?=!empty($consumptionQty)? $consumptionQty:0?></td>
                                        <td class="center"><?= $total_closing = $line_closing = ($opening + $salesQtyLocal + $orderQtyImport) - ($consumptionQty + $wasteQty)?></td>
                                    </tr>
                                    <?php
                                    $total_sales_local = $total_sales_local + $salesQtyLocal;
                                    $total_sales_export = $total_sales_export + $orderQtyImport;
                                    $total_consumption = $total_consumption + $consumptionQty;
                                    $total_wastage = $total_wastage + $wasteQty;
                                    $i++;
                                }?>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    <!--</table>-->
</div>

<style>
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 11px;
        margin: auto;
        font-family: 'times new roman', arial;
        background-color: #fff;
        width: 8.3in;
    }
    .data-tr th, .data-tr td{
        border: 1px solid #000;
        vertical-align: middle;
        line-height: 15px;
    }
    .csd td, .csdtr td{
        border:1px solid #000;
    }
    .csdtr td{
        font-weight:bold;
    }
    .left{text-align:left;}
    .center{text-align:center;}
    .right{text-align:right;}
    .bold{font-weight: bold;}
    .uppercase{text-transform: uppercase}
    .header-color{background-color:#eef7e2;}
    .footer-color{background-color:#fcfff9;}
</style>