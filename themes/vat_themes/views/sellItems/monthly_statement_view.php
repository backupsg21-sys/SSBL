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
    table { page-break-inside:auto }
    tr { page-break-inside:avoid; page-break-after:auto }
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
<div class='printAllTableForThisReport' style="background-color:#F1F3F4;">
    <div class="divFooter">
        <span> Sr.Officer (VAT)<br> (Prepared by) </span>
         <span style="margin-left: 160px"> Deputy Manager (VAT)</span>
         <span style="margin-left: 170px"> Sr.Manager (VAT)</span>
    </div>

    <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff" id="table-id-for-excel">
        <tbody>
        <tr valign="top">
            <td class="style_14" style=" overflow:hidden;">
                <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;" class="stickyHead data-tr">
                    <thead>
                        <tr valign="top">
                            <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="6"><b>
                                 <h4>   <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?></h4>
                                <?= !empty($yourCompanyInfo['house']) ? ' House: ' . $yourCompanyInfo['house'] ." , ": '' ?>
                                <?= !empty($yourCompanyInfo['road']) ? ' Road: ' . $yourCompanyInfo['road'] : '' ?>
                                <?= !empty($yourCompanyInfo['location']) ? ', Location: ' . $yourCompanyInfo['location'] : '' ?>
                                <br>
                                <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?><br>
                                    From Date : <?= date('d-m-Y', strtotime($start_date)) ?> To <?php echo date('d-m-Y', strtotime($end_date)) ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td colspan="6" style=" overflow:hidden; font-size:13px; text-align: center;">
                                Monthly Statement <br>
                                For The Month of  <?php echo date('M-Y', strtotime($start_date))?>

                            </td>
                        </tr>

                        <tr class="csd header-color" valign="top" align="center">
                            <th class="style_24 center">
                                Product Name
                            </th>
                            <th class="style_24 center" >
                                Opening Balance
                            </th>
                            <th class="style_24 center" >
                                Production
                            </th>
                            <th class="style_24 center" >
                                Delivery
                            </th>
                            <th class="style_24 center">
                               Closing Balance
                            </th>
                            <th class="style_24 center" >
                                Remarks
                            </th>
                        </tr>
                    </thead>
                    <tbody style="margin-top: 20px">
                    <?php
                        $totalOpeningBalance = $totalProductionBalance = $totalSalesBalance = $totalColsingBalance = 0;
                        // All Product get
                        foreach ($prodModels as $item):
                          $model_id = $item->id;
                          $item_id = $item->item_id;
                          $openingBalance    = Inventory::openingStockGet($start_date, $model_id);
                          $productionBalance = Inventory::productionBalanceGet($start_date, $end_date, $model_id);
                          $salesBalance      = Inventory::salesBalanceGet($start_date, $end_date, $model_id);
                          $colsingBalance = ($openingBalance + $productionBalance) - $salesBalance;
                            ?>
                    <?php if($item_id == 1 && $colsingBalance > 0){ ?>
                        <tr>
                            <td>&nbsp;<?= $item->model_name;?></td>
                            <td class="center">  <?php echo $openingBalance; ?></td>
                            <td class="center">  <?php echo $productionBalance; ?></td>
                            <td class="center">  <?php echo $salesBalance; ?></td>
                            <td class="right">  <?php echo $colsingBalance; ?> &nbsp;</td>
                            <td>  </td>
                        </tr><br>
                    <?php
                            $totalOpeningBalance += $openingBalance;
                            $totalProductionBalance += $productionBalance;
                            $totalSalesBalance += $salesBalance;
                            $totalColsingBalance += $colsingBalance;
                    }
                    endforeach;
                    ?>
                    <tr style="font-weight: bold">
                        <td> &nbsp;Total</td>
                        <td class="center"><?php echo $totalOpeningBalance ?></td>
                        <td class="center"><?php echo $totalProductionBalance ?></td>
                        <td class="center"><?php echo $totalSalesBalance ?></td>
                        <td class="right"><?php echo $totalColsingBalance ?> &nbsp;</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;" class="stickyHead data-tr">
                    <thead>

                    <tr class="csd header-color" valign="top" align="center">
                        <th class="style_24 center">
                            Product Name
                        </th>
                        <th class="style_24 center" >
                            Opening Balance
                        </th>
                        <th class="style_24 center" >
                            Received
                        </th>
                        <th colspan="2" class="style_24 center" >
                            Total Used
                        </th>
                        <th class="style_24 center">
                            Closing Balance
                        </th>
                    </tr>
                    <tr style="font-weight: bold">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center">Production</td>
                        <td class="center">Loss</td>
                        <td></td>
                    </tr>

                    </thead>
                    <tbody>
                    <?php

                    $totalOpeningBalance = $totalPurchaseBalance = $totalProductionConsumptionBalance = $totalProductionWasteQtyBalanceGet = $totalColsingBalanceRm = 0;

                    // All Product get
                    foreach ($prodModels as $item):
                        $model_id = $item->id;
                        $item_id = $item->item_id;
                        $openingBalance               = Inventory::openingStockGet($start_date, $model_id);
                        $purchaseBalance              = Inventory::purchaseBalanceGet($start_date, $end_date, $model_id);
                        $productionConsumptionBalance = Common::productionConsumptionBalanceGet($start_date, $end_date, $model_id);
                        $productionWasteQtyBalanceGet = Common::productionWasteQtyBalanceGet($start_date, $end_date, $model_id);
                        $colsingBalanceRm = ($openingBalance + $purchaseBalance) - $productionConsumptionBalance;
                        ?>
                    <?php if($item_id == 2 || $item_id == 4  && $colsingBalanceRm > 0 ){ ?>
                        <tr>
                            <td>&nbsp;<?= $item->model_name;?></td>
                            <td class="center">  <?php echo $openingBalance; ?></td>
                            <td class="center">  <?php echo $purchaseBalance; ?></td>
                            <td class="center">  <?php echo $productionConsumptionBalance - $productionWasteQtyBalanceGet; ?></td>
                            <td class="center">  <?php echo $productionWasteQtyBalanceGet; ?></td>
                            <td class="right">  <?php echo $colsingBalanceRm; ?> &nbsp;</td>
                        </tr><br>
                    <?php
                        $totalOpeningBalance +=  $openingBalance;
                        $totalPurchaseBalance +=  $purchaseBalance;
                        $totalProductionConsumptionBalance +=  $productionConsumptionBalance-$productionWasteQtyBalanceGet;
                        $totalProductionWasteQtyBalanceGet +=  $productionWasteQtyBalanceGet;
                        $totalColsingBalanceRm +=  $colsingBalanceRm;
                    }
                    endforeach;
                    ?>
                    <tr style="font-weight: bold">
                        <td> &nbsp;Total</td>
                        <td class="center"><?php echo $totalOpeningBalance ?></td>
                        <td class="center"><?php echo $totalPurchaseBalance ?></td>
                        <td class="center"><?php echo $totalProductionConsumptionBalance ?></td>
                        <td class="center"><?php echo $totalProductionWasteQtyBalanceGet ?></td>
                        <td class="right"><?php echo $totalColsingBalanceRm ?> &nbsp;</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table><br>
                <!--</table>-->
</div>

<style>
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 11px;
        margin: auto;
        font-family: 'times new roman', arial;
        background-color: #fff;
        width: 7.3in;
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