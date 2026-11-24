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
</style>
<div class='printAllTableForThisReport' style="background-color:#F1F3F4;">
    <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff">
        <tbody>
        <tr valign="top">
            <td class="style_14" style=" overflow:hidden;">
                <?php
                // FOR SINGLE PRODUCT
                if ($prod_id) {
                    ?>

                    <table style="empty-cells: show;width: 100%;" class="stickyHead data-tr">

                        <tr valign="top">
                            <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="10"><b>
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
                            <td colspan="10" style=" overflow:hidden; font-size:13px; text-align: center;">
                                Purchase Report (Details) - <?= ProdModels::model()->prodNameOfThis($prod_id) ?>
                                <!--<span class="total-count"> Total <?= count($data) ?> rows</span>-->
                            </th>
                        </tr>
                        <tr class="csd header-color" valign="top" align="center">
                            <th class="style_24 center" rowspan="2">
                                SL
                            </th>
                            <th class="style_24 center" rowspan="2">
                                Date
                            </th>
                            <th class="style_24 center" colspan="2">
                                Opening
                            </th>
                            <th class="style_24 center" colspan="2">
                                Purchase
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
                        </tr>

                        <tbody>
                        <?php
                        if (!empty($data)) {

                            $i = 1;
                            $line_closing = 0;
                            $total_opening = 0;
                            $total_purchase_local = 0;
                            $total_purchase_import = 0;
                            $total_consumption = 0;
                            $total_wastage = 0;
                            $total_closing = 0;

                            foreach ($data as $date_data) {
                                $opening = $orderQtyLocal = $orderQtyImport = $consumptionQty = $wasteQty = 0;
                                ?>
                                <tr class="csd">
                                    <?php
                                    $date_single = $date_data['date'];

                                    $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM common WHERE date < '$date_single' AND model_id='$prod_id' AND (type=1 OR type=4 OR type=6)";
                                    $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();
                                    if ($openingData)
                                        $opening = $openingData['stockIn'] - $openingData['stockOut'];

                                    $sqlLocal = "SELECT SUM(order_qty) AS purchaseLocalQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=1 AND local_import=54";
                                    $orderQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                                    if ($orderQtyLocalData)
                                        $orderQtyLocal = $orderQtyLocalData['purchaseLocalQty'];

                                    $sqlImport = "SELECT SUM(order_qty) AS purchaseImportQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=1 AND local_import=55";
                                    $orderQtyImportData = Yii::app()->db->createCommand($sqlImport)->queryRow();
                                    if ($orderQtyImportData)
                                        $orderQtyImport = $orderQtyImportData['purchaseImportQty'];

                                    $sqlCons = "SELECT SUM(rm_qty) AS consumptionQty, SUM(waste_qty) AS wasteQty FROM common WHERE date LIKE '$date_single%' AND model_id='$prod_id' AND type=4";
                                    $consumptionData = Yii::app()->db->createCommand($sqlCons)->queryRow();
                                    if ($consumptionData)
                                        $consumptionQty = $consumptionData['consumptionQty'];
                                    $wasteQty = $consumptionData['wasteQty'];
                                    ?>

                                    <td class="center"><?= $i ?></td>
                                    <td class="center"><?= !empty($date_single) ? date('d-m-Y', strtotime($date_single)) : '' ?></td>
                                    <td class="center">
                                        OP Qty
                                        <?php
                                        //                                                if ($i == 1)
                                        //                                                    echo $total_opening = $opening;
                                        //                                                else
                                        //                                                    echo $opening = $line_closing;
                                        ?>
                                    </td>
                                    <td>
                                        OP Amount
                                    </td>
                                    <td class="center">
                                        pur:qty
                                        <?php //echo !empty($orderQtyLocal) ? $orderQtyLocal : 0 ?>
                                    </td>
                                    <td class="center">
                                        amount
                                        <?php //echo !empty($orderQtyLocal) ? $orderQtyLocal : 0 ?>
                                    </td>

                                    <td class="center">
                                        qty
                                        <?php //echo !empty($orderQtyImport) ? $orderQtyImport : 0 ?>
                                    </td>
                                    <td class="center">
                                        amont
                                        <?php //echo !empty($consumptionQty) ? $consumptionQty : 0 ?>
                                    </td>

                                    <td class="center">
                                        qty
                                        <?php //echo !empty($wasteQty) ? $wasteQty : 0 ?>
                                    </td>
                                    <td class="center">
                                        amont
                                        <?php //echo $total_closing = $line_closing = ($opening + $orderQtyLocal + $orderQtyImport) - ($consumptionQty + $wasteQty) ?>
                                    </td>
                                    <td>qty</td>
                                    <td>amount</td>
                                </tr>
                                <?php
                                $total_purchase_local = $total_purchase_local + $orderQtyLocal;
                                $total_purchase_import = $total_purchase_import + $orderQtyImport;
                                $total_consumption = $total_consumption + $consumptionQty;
                                $total_wastage = $total_wastage + $wasteQty;
                                $i++;
                            }
                            ?>
                            <tr class="center bold csd footer-color">
                                <td colspan="2" style="padding-right: 5px; text-align: right">Total</td>
                                <td><?= $total_opening ?></td>
                                <td><?= $total_purchase_local ?></td>
                                <td><?= $total_purchase_import ?></td>
                                <td><?= $total_consumption ?></td>
                                <td><?= $total_wastage ?></td>
                                <td><?= $total_closing ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                    <?php
                    // FOR ALL PRODUCT (SUMMARY)
                } else {
                    ?>

                    <table style="empty-cells: show;width: 100%;" class="stickyHead data-tr">
                        <colgroup>
                            <col style=" width: 6%;">
                            <col style=" width: 18%;">
                            <col style=" width: 12%;">
                            <col style=" width: 12%;">
                            <col style=" width: 12%;">
                            <col style=" width: 12%;">
                            <col style=" width: 12%;">
                            <col style=" width: 12%;">
                        </colgroup>
                        <tbody>
                        <tr valign="top">
                            <td style="overflow: hidden; font-size: 11px;text-align: center;" colspan="17"><b>
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
                            <td colspan="17" style=" text-align: center;overflow:hidden; font-size:13px;">
                                Purchase Stock Report
                                <!--<span class="total-count"> Total <?= count($data) ?> rows</span>-->
                            </th>
                        </tr>

                        <tr class="csd header-color" valign="top" align="center">
                            <th class="style_24 center" rowspan="2">
                                SL
                            </th>
                            <th class="style_24 center" rowspan="2">
                                Date
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
                        <?php
                        if (!empty($data)) {

                            $i = 1;
                            $total_opening = 0;
                            $total_purchase_local = 0;
                            $total_purchase_import = 0;
                            $total_consumption = 0;
                            $total_wastage = 0;
                            $total_closing = 0;
                            $gopening_val = 0;
                            $gpurchaseVal = 0;
                            $gbalaceAmount = 0;
                            $gconsumptionAmount = 0;
                            $gtotal_closing_val = 0;
                            foreach ($data as $date_data) {
                                $opening = $line_closing = $orderQtyLocal = $orderQtyImport = $consumptionQty = $wasteQty = 0;
                                ?>
                                <tr class="csd">
                                    <?php
                                    $prod_id = $date_data['model_id'];

                                    $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut, SUM(price_in) AS price_in, SUM(price_out) AS price_out FROM common WHERE date < '$start_date' AND model_id='$prod_id' AND (type=1 OR type=4 OR type=6)";
                                    $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();
                                    if ($openingData)
                                        $opening = $openingData['stockIn'] - $openingData['stockOut'];
                                    $opening_val = $openingData['price_in'] - $openingData['price_out'];

                                    $unitPrice = ($opening_val / $opening);
                                    $opening_val = ($opening * $unitPrice);

                                    /*$sqlLocal = "SELECT SUM(order_qty) AS purchaseLocalQty, SUM(price_in) as price_in FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=1 AND local_import=54";
                                    $orderQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                                    if ($orderQtyLocalData)
                                        $orderQtyLocal = $orderQtyLocalData['purchaseLocalQty'];
                                         $orderValLocal = $orderQtyLocalData['price_in'];
                                     *
                                     $sqlImport = "SELECT SUM(order_qty) AS purchaseImportQty, SUM(price_in) as price_in FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=1 AND local_import=55";
                                    $orderQtyImportData = Yii::app()->db->createCommand($sqlImport)->queryRow();
                                    if ($orderQtyImportData)
                                        $orderQtyImport = $orderQtyImportData['purchaseImportQty'];
                                        echo $orderValImport = $orderQtyImportData['price_in'];
                                     */

                                    $sqlLocal = "SELECT SUM(order_qty) AS purchaseLocalQty, SUM(order_qty * unit_price *cv_rate) AS purchaseAmount,SUM(cd) AS cdAmount  FROM purchase_details WHERE issue_date between '$start_date' AND '$end_date' AND model_id='$prod_id'";
                                    $orderQtyLocalData = Yii::app()->db->createCommand($sqlLocal)->queryRow();
                                    if ($orderQtyLocalData)
                                        $orderQtyLocal = $orderQtyLocalData['purchaseLocalQty'];
                                    $orderValLocal = $orderQtyLocalData['purchaseAmount'] + $orderQtyLocalData['cdAmount'];


                                    $sqlCons = "SELECT SUM(rm_qty) AS consumptionQty, SUM(waste_qty) AS wasteQty FROM common WHERE date between '$start_date' AND '$end_date' AND model_id='$prod_id' AND type=4";
                                    $consumptionData = Yii::app()->db->createCommand($sqlCons)->queryRow();
                                    if ($consumptionData)
                                        $consumptionQty = $consumptionData['consumptionQty'] + $consumptionData['wasteQty'];

                                    if ($i == 1) {
                                        $opening = $opening;
                                    } else {
                                        $opening = $line_closing;
                                    }

                                    $purchaseQty = $orderQtyLocal;
                                    $purchaseVal = $orderValLocal;
                                    $balaceQty = $purchaseQty + $opening;
                                    $balaceAmount = $purchaseVal + $opening_val;

                                    if ($balaceQty > 0)
                                        $balaceAvrUnitPrice = ($balaceAmount / $balaceQty);
                                    $consumptionAmount = ($balaceAvrUnitPrice * $consumptionQty);

                                    $total_closing = $balaceQty - $consumptionQty;
                                    $total_closing_val = $balaceAmount - $consumptionAmount;
                                    ?>

                                    <td class="center"><?= $i ?></td>
                                    <td class="center"><?= ProdModels::model()->prodNameOfThis($prod_id) ?></td>
                                    <td class="center" style="text-align: right">
                                        <?php echo number_format($opening); ?>
                                    </td>
                                    <td class="center" style="text-align: right">
                                        <?php echo number_format($opening_val); ?>
                                    </td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($purchaseQty) ? number_format($purchaseQty) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($purchaseVal) ? number_format($purchaseVal, 2) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($balaceQty) ? number_format($balaceQty) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($balaceAmount) ? number_format($balaceAmount) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($consumptionQty) ? number_format($consumptionQty) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($consumptionAmount) ? number_format($consumptionAmount) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($total_closing) ? number_format($total_closing) : 0 ?></td>
                                    <td class="center"
                                        style="text-align: right"><?= !empty($total_closing_val) ? number_format($total_closing_val) : 0 ?></td>
                                </tr>
                                <?php
                                $gopening_val = $gopening_val + $opening_val;
                                $gpurchaseVal = $gpurchaseVal + $purchaseVal;
                                $gbalaceAmount = $gbalaceAmount + $balaceAmount;
                                $gconsumptionAmount = $gconsumptionAmount + $consumptionAmount;
                                $gtotal_closing_val = $gtotal_closing_val + $total_closing_val;
                                $i++;
                            }
                            ?>
                            <tr>
                                <td colspan="2" style="text-align: right;">Ground Total</td>
                                <td colspan="2" style="text-align: right;"><?= number_format($gopening_val) ?></td>
                                <td colspan="2" style="text-align: right;"><?= number_format($gpurchaseVal) ?></td>
                                <td colspan="2" style="text-align: right;"><?= number_format($gbalaceAmount) ?></td>
                                <td colspan="2"
                                    style="text-align: right;"><?= number_format($gconsumptionAmount) ?></td>
                                <td colspan="2"
                                    style="text-align: right;"><?= number_format($gtotal_closing_val) ?></td>
                            </tr>
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