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
                        <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="8"><b>
                                <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                                <br></b>
                            <?= !empty($yourCompanyInfo['house']) ? ' House: ' . $yourCompanyInfo['house'] : '' ?>
                            <?= !empty($yourCompanyInfo['road']) ? ', Road: ' . $yourCompanyInfo['road'] : '' ?>
                            <?= !empty($yourCompanyInfo['location']) ? ', Location: ' . $yourCompanyInfo['location'] : '' ?>
                            <br>
                            <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?><br>
                                    From Date : <?= date('d-m-Y', strtotime($start_date)) ?> To <?php echo date('d-m-Y', strtotime($end_date)) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td colspan="8" style=" overflow:hidden; font-size:13px; text-align: center;">
                            FG Stock Report (Details) - <?= ProdModels::model()->prodNameOfThis($prod_id) ?>

                        </th>
                    </tr>
                    <tr class="csd header-color" valign="top" align="center">
                        <th class="style_24 center">
                            SL
                        </th>
                        <th class="style_24 center" >
                            Product
                        </th>
                        <th class="style_24 center" >
                            Code
                        </th>
                        <th class="style_24 center">
                            Opening Stock Qty
                        </th>
                        <th class="style_24 center" >
                            Stock In Qty
                        </th>
                        <th class="style_24 center" >
                            Return Qty
                        </th>
                        <th class="style_24 center" >
                            Stock Out
                        </th>
                        <th class="style_24 center" >
                            Closing Stock
                        </th>
                    </tr>

                    <tbody>
                    <?php
                    if ($prodModels && count($prodModels) > 0) {
                        $i = 1;
                        $gtOppenningQty = 0;
                        $gtStockInQty = 0;
                        $gtReturnInQty = 0;
                        $gtStockOutQty = 0;
                        $gtCloseingStock = 0;

                        foreach ($prodModels as $dt) {
                            $openingStock = $stockIn = $stockOut = 0;
                            //Start Inventory Data (Opening Stock)
                            $openingStock = Inventory::openingStockGet($start_date, $dt->id);

                            //Start Inventory Data (Date Range Stock)
                            $sqlDateWise = "SELECT SUM(CASE WHEN type!=5 THEN stock_in ELSE 0 END) as stockIn, SUM(CASE WHEN type!=5 THEN stock_out ELSE 0 END) as stockOut, SUM(CASE WHEN type=5 THEN stock_in ELSE 0 END) as return_qty FROM common WHERE date between '$start_date' AND '$end_date' AND model_id= $dt->id";
                            $sqlBetData = Yii::app()->db->createCommand($sqlDateWise)->queryRow();

                            if ($sqlBetData) {
                                $stockIn = $sqlBetData['stockIn'];
                                $stockOut = $sqlBetData['stockOut'];
                                $stockReturnQty = $sqlBetData['return_qty'];
                            }
                            $closingStock = ($openingStock + $stockIn + $stockReturnQty) - $stockOut;

                            $gtOppenningQty += $openingStock;
                            $gtStockInQty += $stockIn;
                            $gtReturnInQty   += $stockReturnQty;
                            $gtStockOutQty += $stockOut;
                            $gtCloseingStock += $closingStock;

                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td class="center"><?= ProdModels::model()->prodNameOfThis($dt->id) ?></td>
                                <td class="center"><?= ProdModels::model()->hsCodeOfThis($dt->id) ?></td>
                                <td class="center"><?= $openingStock; ?></td>
                                <td class="center"><?= $stockIn; ?></td>
                                <td class="center"><?= $stockReturnQty; ?></td>
                                <td class="center"><?= $stockOut; ?></td>
                                <td class="center"><?= $closingStock; ?></td>
                            </tr>
                        <?php } ?>
                        <tr style="font-weight: bold;">
                            <td  colspan="3" style="text-align: right;">Ground Total &nbsp;</td>
                            <td  style="text-align: right;"><?= number_format($gtOppenningQty, 2) ?></td>
                            <td  style="text-align: right;"><?= number_format($gtStockInQty, 2) ?></td>
                            <td  style="text-align: right;"><?= number_format($gtReturnInQty, 2) ?></td>
                            <td  style="text-align: right;"><?= number_format($gtStockOutQty,2) ?></td>
                            <td  style="text-align: right;"><?= number_format($gtCloseingStock,2) ?></td>
                        </tr>
                    <?php  }else{?>
                        <tr  style="font-weight: bold; text-align: center">
                            <td colspan="8">  Not Available FG Stock   </td>
                        </tr>

                    <?php }?>
                    </tbody>
                </table>
                <br>
                <table style="empty-cells: show;width: 100%;border-collapse:collapse; margin-top: 40px">
                    <tr>
                        <td ><span> Sr.Officer (VAT)<br> (Prepared by) </span></td>
                        <td style="text-align: center;"><span> &nbsp; Deputy Manager (VAT)</span></td>
                        <td style="text-align: right"><span style="margin-left: 20px"> &nbsp;&nbsp; Sr.Manager (VAT)</span></td>
                    </tr>
                </table>

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