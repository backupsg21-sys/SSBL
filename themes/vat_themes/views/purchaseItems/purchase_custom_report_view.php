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
                        $condition .= " AND pd.approve_count=2 ";
                    else if ($approve_type == 1)
                        $condition .= " AND pd.approve_count < 2 ";

                    if ($model_id > 0)
                        $condition .= " AND pd.model_id = $model_id ";

//                    if ($supplier_type_id > 0)
//                        $condition .= " AND si.supplier_type_id = $supplier_type_id ";

                    if ($vat_calculation_method > 0)
                        $condition .= " AND pd.vat_calculation_method = $vat_calculation_method ";

                    if ($local_import > 0)
                        $condition .= " AND pi.local_import = $local_import ";

                    if ($report_type == 2) // VDS
                        $condition .= " AND pi.total_vds > 0 ";
                    else if ($report_type == 2) // REBATE
                        $condition .= " AND pd.rebate > 0 ";

                    $sql = "SELECT *, pd.total AS pd_total, pd.net_total AS pd_net_total, pi.chalan_date as chalanDate, pi.ref_no as challanNo FROM purchase_details AS pd 
                                        INNER JOIN purchase_items AS pi ON pd.purchase_id = pi.id 
                                        INNER JOIN suppliers AS si ON pi.supplier_id = si.id 
                                        WHERE pd.issue_date BETWEEN '$start_date' AND '$end_date' $condition ORDER BY pi.po_no DESC";
                    $data = Yii::app()->db->createCommand($sql)->queryAll();

                    ?>

                    <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;"
                           class="stickyHead data-tr">
                        <thead>
                        <tr valign="top">
                            <td style="text-align: center;" colspan="17"><b>
                                    <?= !empty($yourCompanyInfo['company_name']) ? $yourCompanyInfo['company_name'] : '' ?>
                                    <br></b>
                                <?= !empty($yourCompanyInfo['house']) ? ' House: ' . $yourCompanyInfo['house'] : '' ?>
                                <?= !empty($yourCompanyInfo['road']) ? ', Road: ' . $yourCompanyInfo['road'] : '' ?>
                                <?= !empty($yourCompanyInfo['location']) ? ', Location: ' . $yourCompanyInfo['location'] : '' ?>
                                <br>
                                <?= !empty($yourCompanyInfo['vat_regi_no']) ? 'Vat Regi no. - ' . $yourCompanyInfo['vat_regi_no'] : '' ?><br>
                                From Date : <?= date('d-m-Y', strtotime($start_date)) ?> To <?php echo date('d-m-Y', strtotime($end_date)) ?>
                            </td>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td colspan="17" style=" overflow:hidden; font-size:13px; padding-left: 80px;">
                                Purchase Report (Details)
                                <span class="total-count"> Total <?= count($data) ?> rows</span>
                            </th>
                        </tr>

                        <?php
                        echo $column = '
                            <tr class="csd header-color" valign="top" align="center">
                                <th class="style_24 center" rowspan="2">SL</th>
                                <th class="style_24 center" rowspan="2">PO No.</th>
                                <th class="style_24 center" rowspan="2"> Challan No  </th>
                                <th class="style_24 center" rowspan="2"> Challan  Date </th>
                                <th class="style_24 center" rowspan="2">Receive Date</th>
                                <th class="style_24 center" rowspan="2">Supplier <br> Name</th>
                                <th class="style_24 center" rowspan="2">Goods</th>
                                <th class="style_24 center" rowspan="2">Qty</th>
                                <th class="style_24 center" rowspan="2">Unit<br>Price</th>
                                <th class="style_24 center" colspan="2">SD</th>
                                <th class="style_24 center" rowspan="2">VDS</th>
                                <th class="style_24 center" rowspan="2">Rebate</th>
                                <th class="style_24 center" rowspan="2">Total<br>Amount</th>
                                <th class="style_24 center" colspan="2">VAT</th>
                                <th class="style_24 center" rowspan="2">Net<br>Total</th>
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
                        $qty = $sd = $vat = $i = $qty = $sd = $vat = $total = $net_total = $rebate = 0;
                        $UnitPrice = 0;
                        if (!empty($data)) {
                            $previous = "";
                            foreach ($data as $d) {
                                $vds_class = "po-" . $d['po_no'];
                            if ($previous != $d['po_no']){
                                ?>
                                <script>
                                    vdsArray.push("<?=$vds_class;?>");
                                    netRowTotalArray.push("<?='t-' . $vds_class;?>");
                                </script>
                            <?php
                            $previous = $d['po_no'];
                            }
                            $i++;
                            ?>
                                <tr class="csd">
                                    <td><?= $i ?></td>
                                    <td class="po merge-column1"><?= $d['po_no'] ?></td>
                                    <td class=""> <?= $d['challanNo']  ?> </td>
                                    <td class=""><?= $d['chalanDate'] ?></td>
                                    <td width="80px"
                                        class=""><?= !empty($d['issue_date']) ? date('d-m-Y', strtotime($d['issue_date'])) : '' ?>
                                    </td>
                                    <td class=""
                                        style="width: 140px"><?= !empty($d['supplier_id']) ? Suppliers::model()->supplierName($d['supplier_id']) : '' ?></td>
                                    <td><?= !empty($d['model_id']) ? ProdModels::model()->prodNameOfThis($d['model_id']) : '' ?></td>
                                    <td><?= $d['order_qty'] ?></td>
                                    <td style="text-align: right"><?= !empty($d['unit_price']) ? number_format($d['unit_price'], 2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['sd_percent']) ? number_format(round($d['sd_percent']), 2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['sd']) ? number_format(round($d['sd']), 2) : '' ?></td>
                                    <td style="text-align: right" class="vds <?= $vds_class ?>">
                                        <?= !empty($d['total_vds']) ? number_format(round($d['total_vds']), 2) : '' ?>
                                        <span class="hVds" style="display: none"><?= round($d['total_vds']) ?></span>
                                    </td>
                                    <td style="text-align: right"><?= !empty($d['rebate']) ? number_format(round($d['rebate']), 2) : '' ?></td>
                                    <td style="text-align: right" class="total">
                                        <?= !empty($d['pd_total']) ? number_format(round($d['pd_total'] + $d['cd']), 2) : '' ?>
                                        <span class="hTotal"
                                              style="display: none"><?= round($d['pd_total'] + $d['cd']) ?></span>
                                    </td>
                                    <td style="text-align: right"><?= !empty($d['vat_percent']) ? number_format(round($d['vat_percent']), 2) : '' ?></td>
                                    <td style="text-align: right"><?= !empty($d['vat']) ? number_format($d['vat'], 2) : '' ?></td>
                                    <td style="text-align: right" class="netTotal">
                                        <?= !empty($d['pd_net_total']) ? number_format(round($d['pd_net_total']), 2) : '' ?>
                                        <span class="hNetTotal"
                                              style="display: none"><?= round($d['pd_net_total']) ?></span>
                                    </td>
                                </tr>
                                <?php
                                $qty += round($d['order_qty']);
                                $UnitPrice += $d['unit_price'];
                                $sd += round($d['sd']);
                                $vat += $d['vat'];
                                $total += round($d['total']);
                                $net_total += round($d['net_total']);
                                $rebate += round($d['rebate']);
                            } ?>
                            <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>

                        <tr class="center bold csd footer-color">
                            <td style="padding-right: 5px; text-align: right" colspan="7">Total</td>
                            <td style="text-align: right"><?= $qty ?></td>
                            <td style="text-align: right"><?= number_format($UnitPrice, 2) ?></td>
                            <td colspan="3" style="text-align: right"><?= number_format($sd, 2) ?></td>
                            <td style="text-align: right"><?= number_format($rebate, 2) ?></td>
                            <td class="gTotal" style="text-align: right; color: #14a614"></td>
                            <td>  </td>
                            <td style="text-align: right; color: #14a614"><?= number_format($vat, 2) ?></td>
                            <td class="gNetTotal" style="text-align: right"></td>
                        </tr>
                        </tfoot>
                    </table><br>
                    <table style="empty-cells: show;width: 100%;border-collapse:collapse; margin-top: 40px">
                        <tr>
                            <td ><span> Sr.Officer (VAT)<br> (Prepared by) </span></td>
                            <td style="text-align: center;"><span> &nbsp; Deputy Manager (VAT)</span></td>
                            <td style="text-align: right"><span style="margin-left: 20px"> &nbsp;&nbsp; Sr.Manager (VAT)</span></td>
                        </tr>
                    </table>



                    <?php
                    // FOR ALL PRODUCT (SUMMARY)
                }
                ?>
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