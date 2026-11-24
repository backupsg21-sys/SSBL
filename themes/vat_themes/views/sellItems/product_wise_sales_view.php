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
   
</style>
<div class='printAllTableForThisReport' style="background-color:#F1F3F4;">
    <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff" id="table-id-for-excel">
        <tbody>
        <tr valign="top">
            <td class="style_14" style=" overflow:hidden;">
                <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;" class="stickyHead data-tr">
                    <thead>
                        <tr valign="top">
                            <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="10"><b>
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
                            <td colspan="10" style=" overflow:hidden; font-size:13px; text-align: center;"> Product Wise Sales </td>
                        </tr>
                        <tr valign="top">
                            <td colspan="10" style=" overflow:hidden; font-size:13px; text-align: right; font-weight: 600">
                                From : <?php echo date('d-m-Y', strtotime($start_date))?> To <?php echo date('d-m-Y', strtotime($end_date))?>
                            </td>
                        </tr>

                        <tr class="csd header-color" valign="top" align="center">
                            <th class="style_24 center">
                                SL
                            </th>
                            <th class="style_24 center" >
                                Date
                            </th>
                            <th class="style_24 center" >
                                Vat ch.no
                            </th>
                            <th class="style_24 center" >
                                Name of Party
                            </th>
                            <th class="style_24 center">
                                Address
                            </th>
                            <th class="style_24 center" >
                                Qty
                            </th>
                            <th class="style_24 center" >
                                Rate (witout vat)
                            </th>
                            <th class="style_24 center" >
                                Sale(Without Vat)
                            </th>
                            <th class="style_24 center" >
                                VAT Amount
                            </th>
                            <th class="style_24 center" >
                                Total (sales with vat)
                            </th>
                        </tr>
                    </thead>
                    <tbody style="margin-top: 20px">
                    <?php
                         $grand_total_qty  = $grand_total_vat = $grand_total_with_out_vat =  $grand_total_amount_with_vat=0;
                        // All Product get
                        foreach ($prodModels as $item):
                          $model_id = $item->id;

                          // Product sale information
                          $sales_data= SellDetails::model()->productWiseSalesData($start_date, $end_date, $model_id);

                        if(!empty($sales_data)){
                        ?>
                            <tr style="font-weight: bold">
                                <td colspan="10"> <br> Item Name : <?= $item->model_name;?> </td>
                            </tr>
                            <?php
                               $i= 1;
                               $total_qty = $total_vat = $total_with_out_vat = $total_amount_with_vat= 0;
                               foreach ($sales_data as $value){
                                   ?>
                                <tr>
                                    <td class="center"><?= $i++;?></td>
                                    <td style="width: 70px"> &nbsp; <?php echo date('d-m-Y', strtotime($value['sales_date']));?></td>
                                    <td class="center" style="width: 80px"> SO-<?php echo SellItems::model()->salePoNo($value['sales_id']);?></td>
                                    <td style="width: 200px"> <?php echo Customers::model()->customerName($value['customer_id']);?></td>
                                    <td style="width: 190px"> <?php echo Customers::model()->customerAddress($value['customer_id']);?></td>
                                    <td class="center"> <?= $sales_qty =  $value['sales_qty']?></td>
                                    <td class="center"> <?= $unit_price = $value['unit_price']?></td>
                                    <td class="center">
                                        <?php
                                        $amount_with_out_vat = ($unit_price * $sales_qty);
                                        echo number_format($amount_with_out_vat, 2);
                                        ?>
                                    </td>
                                    <td class="center"> <?= $vat = $value['vat']?></td>
                                    <td class="right"> <?php
                                        $amount_with_vat = ($amount_with_out_vat + $vat);
                                        echo number_format($amount_with_vat, 2);
                                        ?>
                                        &nbsp;
                                    </td>
                                </tr>
                            <?php
                               $total_qty += $sales_qty;
                               $total_with_out_vat += $amount_with_out_vat;
                               $total_vat += $vat;
                               $total_amount_with_vat += $amount_with_vat;
                               }?>
                            <tr style="font-weight: bold">
                                <td colspan="5"> Sub Total  </td>
                                <td class="center"> <?= $total_qty?></td>
                                <td class="center"> </td>
                                <td class="center"> <?= number_format($total_with_out_vat, 2)?></td>
                                <td class="center"> <?= number_format($total_vat, 2)?></td>
                                <td class="right"> <?= number_format($total_amount_with_vat, 2)?> </td>
                            </tr>
                        <?php
                                $grand_total_qty += $total_qty;
                                $grand_total_with_out_vat += $total_with_out_vat;
                                $grand_total_vat  += $total_vat;
                                $grand_total_amount_with_vat += $total_amount_with_vat;

                            }
                        endforeach;
                        ?>
                    <tr>
                        <td style="height: 40px" colspan="10"> &nbsp;</td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="width: 385px " colspan="5"> Grand Total  </td>
                        <td class="center"> <?= $grand_total_qty?></td>
                        <td class="center"> </td>
                        <td class="center"> <?= number_format($grand_total_with_out_vat, 2)?></td>
                        <td class="center"> <?= number_format($grand_total_vat, 2)?></td>
                        <td class="right"> <?= number_format($grand_total_amount_with_vat, 2)?> </td>
                    </tr>
                    </tbody>
                </table><br>
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
        width: 10.3in;
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