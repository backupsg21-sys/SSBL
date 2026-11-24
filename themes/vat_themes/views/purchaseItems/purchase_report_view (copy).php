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
echo "  <img class='excl-export' id='btnExport' onclick='fnExcelReport();' src='" . Yii::app()->theme->baseUrl . "/images/excel.png' style='padding:5px;' title='EXPORT TO EXCEL'>";
echo "</div>";
$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
?>
<style>
    .reportTabCustom {
        page-break-after: always;
    }

    .printAllTableForThisReport .stickyHead .csd th {
        position: sticky;
        top: 0;
        background-color: #DADADA;
        color: #000000;
        border: 1px solid #C0C0C0;
        text-decoration: none;
    }

    .data-tr tr:hover {
        background: #e7e7e7;
    }

</style>
<div class='printAllTableForThisReport' style="background-color:#F1F3F4;">
    <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff" id="table-id-for-excel">
        <tbody>
        <tr valign="top">
            <td class="style_14" style=" overflow:hidden;">
                <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;"
                       class="stickyHead data-tr">
                    <tr valign="top">
                        <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="13"><b>
                                <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                                <br></b>
                            <?= !empty($yourCompanyInfo['house']) ? ' House: ' . $yourCompanyInfo['house'] : '' ?>
                            <?= !empty($yourCompanyInfo['road']) ? ', Road: ' . $yourCompanyInfo['road'] : '' ?>
                            <?= !empty($yourCompanyInfo['location']) ? ', Location: ' . $yourCompanyInfo['location'] : '' ?>
                            <br>
                            <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="13" style=" overflow:hidden; font-size:13px; text-align: center;">
                            Purchase Stock Report (Details) - <?= ProdModels::model()->prodNameOfThis($prod_id) ?>
                        </td>
                    </tr>
                    <tr class="csd header-color" valign="top" align="center">
                        <th class="style_24 center" rowspan="2">
                            SL
                        </th>
                        <th class="style_24 center" rowspan="2">
                            Product
                        </th>
                        <th class="style_24 center" rowspan="2">
                            Code
                        </th>
                        <th class="style_24 center" colspan="2">
                            Opening
                        </th>
                        <th class="style_24 center" colspan="2">
                            Purchase
                        </th>
                        <th class="style_24 center" colspan="2">
                            Balance
                        </th>
                        <th class="style_24 center" colspan="2">
                            Consumption
                        </th>
                        <th class="style_24 center" colspan="2">
                            Closing
                        </th>
                    </tr>
                    <tr class="csd header-color" valign="top" align="center">
                        <th class="style_24 center">
                            Qty
                        </th>
                        <th class="style_24 center">
                            Amount
                        </th>
                        <th class="style_24 center">
                            Qty
                        </th>
                        <th class="style_24 center">
                            Amount
                        </th>
                        <th class="style_24 center">
                            Qty
                        </th>
                        <th class="style_24 center">
                            Amount
                        </th>
                        <th class="style_24 center">
                            Qty
                        </th>
                        <th class="style_24 center">
                            Amount
                        </th>
                        <th class="style_24 center">
                            Qty
                        </th>
                        <th class="style_24 center">
                            Amount
                        </th>
                    </tr>
                    <tbody>
                    <?php
                    if ($prodModels) {
                        $i = 1;
                        $gtOppenningVal = 0;
                        $gtPurchasevalVal = 0;
                        $gtBalance = 0;
                        $gtConsumptionAmount = 0;
                        $gtClosingBalance = 0;
                        foreach ($prodModels as $dt) {
                            //Start Inventory Data
                            $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM inventory WHERE transaction_date < '$start_date' AND model_id= $dt->id ";
                            $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();
                            if ($openingData)
                                $openingStock = $openingData['stockIn'] - $openingData['stockOut'];
                            //End Inventory Data
                            //Start Purchase Data
                            $sqlLocal = "SELECT SUM(order_qty) AS purchaseLocalQty, SUM(order_qty * unit_price *cv_rate) AS purchaseAmount,SUM(cd) AS cdAmount FROM purchase_details WHERE issue_date between '$start_date' AND '$end_date' AND model_id=$dt->id";
                            $orderQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                            if ($orderQtyLocalData) {
                                $orderQtyLocal = $orderQtyLocalData['purchaseLocalQty'];
                                $orderValLocal = $orderQtyLocalData['purchaseAmount'] + $orderQtyLocalData['cdAmount'];
                            }
                            //End Purchase Data
                            //Start Consumption Data
                            $sqlConsumption = "SELECT SUM(production_material.qty+production_material.waste_qty) AS usedQty FROM production_material INNER JOIN production ON production_material.production_id = production.id WHERE production.issue_date between '$start_date' AND '$end_date' AND production_material.model_id=$dt->id";
                            $consumptionData = Yii::app()->db->createCommand($sqlConsumption)->queryRow();
                            if ($consumptionData) {
                                $consumptionQty = $consumptionData['usedQty'];
                            }
                            //End Consumption Data
                            if ($openingStock > 0 || $orderQtyLocal > 0 || $consumptionQty > 0) {
                                $purchasePrice = PurchasePrices::model()->prodAveragePrice($dt->id);

                                $openingAmount = $purchasePrice * $openingStock;
                                $gtOppenningVal += $openingAmount;

                                $gtPurchasevalVal += $orderValLocal;

                                $balanceAMOUNT = $openingAmount + $orderValLocal;
                                $gtBalance += $balanceAMOUNT;

                                $consAmount = $purchasePrice * $consumptionQty;
                                $gtConsumptionAmount += $consAmount;

                                $closingBalance = ($openingAmount + $orderValLocal) - $consAmount;
                                $gtClosingBalance += $closingBalance;
                                ?>
                                <tr>
                                    <td class="center"><?= $i++ ?></td>
                                    <td class="center"><?= ProdModels::model()->prodNameOfThis($dt->id) ?></td>
                                    <td class="center"><?= ProdModels::model()->hsCodeOfThis($dt->id) ?></td>
                                    <td class="center"><?= round($openingStock); ?></td>
                                    <td class="center"><?= $openingAmount; ?></td>

                                    <td class="center"><?= round($orderQtyLocal); ?></td>
                                    <td class="center"><?= number_format($orderValLocal, 2); ?></td>

                                    <td class="center"><?= round($openingStock + $orderQtyLocal); ?></td>
                                    <td class="center"><?= number_format($balanceAMOUNT, 2); ?></td>

                                    <td class="center"><?= round($consumptionQty); ?></td>
                                    <td class="center"><?= $consAmount; ?></td>

                                    <td class="center"><?= round(($openingStock + $orderQtyLocal) - $consumptionQty); ?></td>
                                    <td class="center"><?= number_format($closingBalance, 2); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                    <tr style="font-weight: bold;">
                        <td colspan="3" style="text-align: right;">Ground Total</td>
                        <td colspan="2" style="text-align: right;"><?= number_format($gtOppenningVal) ?></td>
                        <td colspan="2" style="text-align: right;"><?= number_format($gtPurchasevalVal) ?></td>
                        <td colspan="2" style="text-align: right;"><?= number_format($gtBalance) ?></td>
                        <td colspan="2" style="text-align: right;"><?= number_format($gtConsumptionAmount) ?></td>
                        <td colspan="2" style="text-align: right;"><?= number_format($gtClosingBalance) ?></td>
                    </tr>
                    </tbody>
                </table>
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

    .data-tr th, .data-tr td {
        border: 1px solid #000;
        vertical-align: middle;
        line-height: 15px;
    }

    .csd td, .csdtr td {
        border: 1px solid #000;
    }

    .csdtr td {
        font-weight: bold;
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

    .bold {
        font-weight: bold;
    }

    .uppercase {
        text-transform: uppercase
    }

    .header-color {
        background-color: #eef7e2;
    }

    .footer-color {
        background-color: #fcfff9;
    }
</style>