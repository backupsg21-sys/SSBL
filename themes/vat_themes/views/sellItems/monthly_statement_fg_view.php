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
                                Monthly Statement

                            </td>
                        </tr>
                        <tr valign="top">
                            <td colspan="6" style=" overflow:hidden; font-size:13px; text-align: left;">
                                For The Month of  <?php echo date('M-Y', strtotime($start_date))?>
                            </td>
                        </tr>

                        <tr class="csd header-color" valign="top" align="center">
                            <th rowspan="2" class="style_24 center">
                                Name of Product
                            </th>
                            <th colspan="2" class="style_24 center" >
                                Quantity
                            </th>
                            <th rowspan="2" class="style_24 center" >
                                Value
                            </th>
                            <th rowspan="2" class="style_24 center">
                                Value Added Tax
                            </th>
                            <th rowspan="2" class="style_24 center" >
                                Total Amount
                            </th>
                        </tr>
                    <tr class="csd header-color" valign="top" align="center">
                        <th>Production</th>
                        <th>Delivery</th>
                    </tr>
                    </thead>
                    <tbody style="margin-top: 20px">
                    <?php
                        $totalProductionQty = $totalSaleQty = $totalValue = $totalValueAdded = $totalAmount = 0;
                        foreach ($prodModels as $item):
                            $model_id = $item->id;
                            $productionQty = Inventory::productionBalanceGet($start_date, $end_date, $model_id);
                            $salesData     = Common::salesInfoGet($start_date, $end_date, $model_id);

                            if($productionQty > 0 || $salesData['stockOut'] > 0){
                            ?>
                           <tr>
                               <td> &nbsp;<?= $item->model_name?></td>
                               <td class="center"> <?php echo $productionQty?></td>
                               <td  class="center"> <?php echo $salesData['stockOut']?> &nbsp;</td>
                               <td class="right"> <?php echo $salesData['totalAmount']?> &nbsp;</td>
                               <td class="right"> <?php echo $salesData['totalVat']?> &nbsp;</td>
                               <td class="right"> <?php echo $salesData['totalAmount'] + $salesData['totalVat']?> &nbsp;</td>
                           </tr>
                        <?php
                            }
                            $totalProductionQty += $productionQty;
                            $totalSaleQty       += $salesData['stockOut'];
                            $totalValue         += $salesData['totalAmount'];
                            $totalValueAdded    += $salesData['totalVat'];
                            $totalAmount        += ($salesData['totalAmount']+$salesData['totalVat']);

                        endforeach;

                    ?>
                    </tbody>
                    <?php if ($totalProductionQty > 0 || $totalSaleQty > 0) {?>
                    <tr style="font-weight: bold; ">
                        <td> &nbsp;Sub Total</td>
                        <td class="center"><?= $totalProductionQty?> &nbsp;</td>
                        <td class="center"><?= $totalSaleQty?> &nbsp;</td>
                        <td class="right"><?= $totalValue?> &nbsp;</td>
                        <td class="right"><?= $totalValueAdded?> &nbsp;</td>
                        <td class="right"><?= $totalAmount?> &nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6"> &nbsp;</td>
                    </tr>
                    <tfoot>
                    <tr style="font-weight: bold">
                        <th> &nbsp;Grand Total</th>
                        <td class="center"><?= $totalProductionQty?> &nbsp;</td>
                        <td class="center"><?= $totalSaleQty?> &nbsp;</td>
                        <td class="right"><?= $totalValue?> &nbsp;</td>
                        <td class="right"><?= $totalValueAdded?> &nbsp;</td>
                        <td class="right"><?= $totalAmount?> &nbsp;</td>
                    </tr>
                    </tfoot>
                    <?php }else{?>
                        <tr>
                            <td colspan="6"><h6 style="text-align: center; color: red; font-weight: bold"> Not Found</h6></td>
                        </tr>
                    <?php }?>
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