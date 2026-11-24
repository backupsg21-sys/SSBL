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
    table.reportTabCustom {
        border-collapse: collapse;
        font-size: 12px;
        margin: auto;
        font-family: arial;
        background-color: #fff;
        width: 100%;
        color: #1d1d1d;
    }

    .data-tr th, .data-tr td {
        border: 1px solid #ccc;
        vertical-align: middle;
        line-height: 20px;
        text-align: center;
    }

    .data-tr th {
        font-weight: normal;
        color: #cc6cd5;
        text-decoration: underline;
        font-weight: bold;
    }

    .data-tr td {
        color: #1d1d1d;
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

    /*.header-color{background-color:#797D82;}*/
    .footer-color {
        background-color: #fcfff9;
    }

    .total-count {
        float: right;
        color: #4c4b4b;
        font-weight: normal;
        font-size: 11px;
        font-style: italic;
        padding-right: 5px;
    }
    
    @media print {
        .data-tr th, .data-tr td {
            color: #000;
            border: 1px solid #000;
        }

        .data-tr th {
            font-weight: bold
        }
    
    }

    .printAllTableForThisReport .stickyHead thead th {
        position: sticky;
        top: 0;
        background-color: #DADADA;
        color: #000000;
        border: 1px solid #C0C0C0;
        text-decoration: none;
    }
</style>

<script>
    var vdsArray = [];
    var netRowTotalArray = [];
</script>
<div class='printAllTableForThisReport' style="background-color:#F1F3F4;">
    <!--<table class="reportTab">-->

    <table class="reportTabCustom" style="border: 2px solid #fff" id="table-id-for-excel">
        <tbody>
        <tr valign="top">
            <td class="style_14" style=" overflow:hidden;">
                <?php
                // FOR SINGLE PRODUCT
                if (!empty($start_date) && !empty($end_date)) {

                    $condition = '';

                    if ($approve_type == 2)
                        $condition .= " AND ssd.approve_count=2 ";
                    else if ($approve_type == 1)
                        $condition .= " AND ssd.approve_count < 2 ";

                    if ($parts_service > 0)
                        $condition .= " AND ssd.parts_service = $parts_service ";

                    if ($model_id > 0)
                        $condition .= " AND ssd.model_id = $model_id ";

                    if ($business_type_id > 0)
                        $condition .= " AND si.business_type_id = $business_type_id ";

                    if ($vat_calculation_method > 0)
                        $condition .= " AND ssd.vat_calculation_method = $vat_calculation_method ";

                    /* if($local_import > 0)
                      $condition.= " AND pi.local_imsort = $local_imsort "; */

                    if ($report_type == 2) // VDS
                        $condition .= " AND si.total_vds > 0 ";

                    $sql = "SELECT *, ssd.total AS sde_total, ssd.net_total AS sd_net_total, ssd.parts_service AS prod_type FROM sales_details AS ssd 
                                        INNER JOIN sell_items AS si ON ssd.sales_id = si.id 
                                        INNER JOIN customers AS ci ON si.customer_id = ci.id 
                                        WHERE ssd.sales_date BETWEEN '$start_date' AND '$end_date' $condition ORDER BY si.so_no ASC";
                    $data = Yii::app()->db->createCommand($sql)->queryAll();
                    ?>

                    <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;"
                           class="stickyHead data-tr">
                        <thead>
                        <tr valign="top">
                            <td style="overflow: hidden; font-size: 11px;text-align: center;" colspan="15"><b>
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
                            <td colspan="14" style=" overflow:hidden; font-size:13px; padding-left: 80px;">
                                Sales Report (Details)
                                <span class="total-count"> Total <?= count($data) ?> rows</span>
                            </td>
                        </tr>

                        <?php
                        echo $column = '
                                <tr class="csd header-color" valign="top" align="center">
                                <th rowspan="2">SL</th>
                                <th rowspan="2">SO No.</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2" style="width: 20%">Customer Name</th>
                                <!--<th rowspan="2">Business Type</th>-->
                                <th rowspan="2">Product</th>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">Unit<br>Price</th>
                                <th colspan="2">SD</th>
                                <th rowspan="2">VDS</th>
                                <th rowspan="2">Total<br>Amount</th>
                                <th colspan="2">VAT</th>
                                <th rowspan="2">Net<br>Total</th>
                                <!--
                                <th rowspan="2">VAT/SO</th>-->
                            </tr>
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center" width="40px">
                                    %
                                </th>
                                <th class="style_24 center" width="60px">
                                    Amount
                                </th>
                                <th class="style_24 center" width="40px">
                                    %
                                </th>
                                <th class="style_24 center" width="60px">
                                    Amount
                                </th>
                            </tr>
                            ';
                        ?>

                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $qty = 0;
                        $unit_price = 0;
                        $sd = 0;
                        $vat = 0;
                        $total = 0;
                        $net_total = 0;
                        if (!empty($data)) {
                            $previous = "";
                            foreach ($data as $d) {
                                $vds_class = "so-" . $d['so_no'];
                            if ($previous != $d['so_no']) {
                                ?>
                                <script>
                                    vdsArray.push("<?= $vds_class; ?>");
                                    netRowTotalArray.push("<?= 't-' . $vds_class; ?>");
                                </script>
                            <?php
                            $previous = $d['so_no'];
                            }
                            $i++;
                            ?>
                                <tr class="csd" style="page-break-inside: avoid">
                                    <td><?= $i ?></td>
                                    <td class="so merge-column1"><?= $d['so_no'] ?></td>
                                    <td class="merge-column3" width="80px"
                                        class=""><?= !empty($d['sales_date']) ? date('d-m-Y', strtotime($d['sales_date'])) : '' ?></td>
                                    <td class=""
                                        style="width: 140px; text-align: left"><?= !empty($d['customer_id']) ? Customers::model()->customerName($d['customer_id']) : '' ?></td>
                                    <!--<td>
                                            <?php
                                    //echo Lookup::item('business_type', $d['business_type_id']);
                                    ?>
                                        </td>-->
                                    <td style="text-align: left">
                                        <?php
                                        if ($d['prod_type'] == 1 && $d['model_id'] > 0) {
                                            echo ProdModels::model()->prodNameOfThis($d['model_id']);
                                        } else if ($d['prod_type'] == 2 && $d['model_id'] > 0) {
                                            echo Services::model()->prodNameOfThis($d['model_id']);
                                        }
                                        ?>
                                    </td>
                                    <td><?= $d['sales_qty'] ?></td>
                                    <td style="text-align: right"><?= !empty($d['unit_price']) ? round($d['unit_price'],2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['sd_percent']) ? number_format(round($d['sd_percent']), 2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['sd']) ? number_format(round($d['sd']), 2) : '' ?></td>
                                    <td style="text-align: right" class="vds <?= $vds_class ?>">
                                        <?= !empty($d['total_vds']) ? number_format(round($d['total_vds']), 2) : '' ?>
                                        <span class="hVds" style="display: none"><?= round($d['total_vds']) ?></span>
                                    </td>
                                    <td style="text-align: right" class="total">
                                        <?= !empty($d['sde_total']) ? number_format(round($d['sde_total'],2), 2) : '' ?>
                                        <span class="hTotal" style="display: none"><?= round($d['sde_total'],2) ?></span>
                                    </td>
                                    <td style="text-align: right"><?= !empty($d['vat_percent']) ? number_format(round($d['vat_percent'],2), 2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['vat']) ? number_format($d['vat'], 2) : '' ?></td>
                                    <td style="text-align: right" class="netTotal">
                                        <?= !empty($d['sd_net_total']) ? number_format($d['sd_net_total'], 2) : '' ?>
                                        <span class="hNetTotal" style="display: none"><?= $d['sd_net_total'] ?></span>
                                    </td>
                                    <!--
                                        <td style="text-align: right" class="t-<?= $vds_class ?>">
                                        <?= !empty($d['grand_total']) ? number_format($d['grand_total'], 2) : '' ?>
                                            <span class="hNetTotalRow" style="display: none"><?= $d['grand_total'] ?></span>
                                        </td>-->
                                </tr>
                                <?php
                                $qty += $d['sales_qty'];
                                $unit_price += $d['unit_price'];
                                $sd += $d['sd'];
                                $vat += $d['vat'];
                                $total += $d['total'];
                                $net_total += $d['net_total'];
                            }
                            ?>
                            <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="font-weight:bold; font-size:12px;">
                            <td style="padding-right: 5px; text-align: right" colspan=5>Total</td>
                            <td><?= $qty ?></td>
                            <td><?= number_format($unit_price, 2) ?></td>
                            <td colspan="2" style="text-align: right"><?= number_format($sd, 2) ?></td>
                            <td class="vdsGTotal" style="text-align: right"></td>
                            <td colspan="1" class="gTotal" style="text-align: right; color: #14a614"></td>
                            <td colspan="2"
                                style="text-align: right; color: #14a614"><?= number_format($vat, 2) ?></td>
                            <td class="gNetTotal" style="text-align: right"></td>
                            <!--
                            <td class="gNetTotalRow" style="text-align: right"></td>-->
                        </tr>
                        </tfoot>
                    </table>
                    <?php
                    // FOR ALL PRODUCT (SUMMARY)
                }
                ?><br>
                <table style="empty-cells: show;width: 100%;border-collapse:collapse; margin-top: 40px">
                    <tr>
                        <td><span> Sr.Officer (VAT)<br> (Prepared by) </span></td>
                        <td style="text-align: center;"><span> &nbsp; Deputy Manager (VAT)</span></td>
                        <td style="text-align: right"><span style="margin-left: 20px"> &nbsp;&nbsp; Sr.Manager (VAT)</span></td>
                    </tr>
                </table>
                
            </td>
        </tr>
        </tbody>
    </table>
    <br>

    <!--</table>-->
</div>

<script>
    $(document).ready(function () {
        var n = 5;
        for (var i = 1; i <= n; i++) {
            var topMatchTd;
            var previousValue = "";
            var rowSpan = 1;

            $('.merge-column' + i).each(function () {
                if ($(this).text() == previousValue) {
                    rowSpan++;
                    $(topMatchTd).attr('rowspan', rowSpan);
                    $(this).remove();
                } else {
                    topMatchTd = $(this);
                    rowSpan = 1;
                }
                previousValue = $(this).text();
            });
        }

        vdsArray.forEach(function (entry) {
            var topMatchTd;
            var previousValue = "";
            var rowSpan = 1;
            //console.log(entry);
            $('.' + entry).each(function () {
                if ($(this).text() == previousValue) {
                    rowSpan++;
                    $(topMatchTd).attr('rowspan', rowSpan);
                    $(this).remove();
                } else {
                    topMatchTd = $(this);
                    rowSpan = 1;
                }

                previousValue = $(this).text();
            });
        });

        netRowTotalArray.forEach(function (entry) {
            var topMatchTd;
            var previousValue = "";
            var rowSpan = 1;
            //console.log(entry);
            $('.' + entry).each(function () {
                if ($(this).text() == previousValue) {
                    rowSpan++;
                    $(topMatchTd).attr('rowspan', rowSpan);
                    $(this).remove();
                } else {
                    topMatchTd = $(this);
                    rowSpan = 1;
                }

                previousValue = $(this).text();
            });
        });

        var sumVDS = 0;
        $(".hVds").each(function () {
            var vdsTotal = $(this).html();
            if (isNaN(vdsTotal) || vdsTotal == '' || vdsTotal < 0)
                vdsTotal = 0;
            sumVDS += parseFloat(vdsTotal);

        });
        var sumTotal = 0;
        $(".hTotal").each(function () {
            var valueTotal = $(this).html();
            if (isNaN(valueTotal) || valueTotal == '' || valueTotal < 0)
                valueTotal = 0;
            sumTotal += parseFloat(valueTotal);

        });
        var sumNetTotal = 0;
        $(".hNetTotal").each(function () {
            var valueNetTotal = $(this).html();
            if (isNaN(valueNetTotal) || valueNetTotal == '' || valueNetTotal < 0)
                valueNetTotal = 0;
            sumNetTotal += parseFloat(valueNetTotal);

        });

        /*var sumNetTotalRow = 0;
         $(".hNetTotalRow").each(function () {
         var valueNetTotalRow = $(this).html();
         if (isNaN(valueNetTotalRow) || valueNetTotalRow=='' || valueNetTotalRow < 0)
         valueNetTotalRow = 0;
         sumNetTotalRow += parseFloat(valueNetTotalRow);
         });*/

        if (sumVDS != '')
            $(".vdsGTotal").html(numberWithCommas(sumVDS.toFixed(2)));
        if (sumTotal != '')
            $(".gTotal").html(numberWithCommas(sumTotal.toFixed(2)));
        if (sumNetTotal != '')
            $(".gNetTotal").html(numberWithCommas(sumNetTotal.toFixed(2)));
        /*if(sumNetTotalRow!='')
         $(".gNetTotalRow").html(numberWithCommas(sumNetTotalRow.toFixed(2)));*/

    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>