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
                $condition = '';
                if ($so_no > 0) {
                    $condition = "so_no=$so_no";
                    $sql = "SELECT * FROM sell_items AS si INNER JOIN customers AS c ON si.customer_id=c.id WHERE $condition ORDER BY so_no ASC";
                    $data = Yii::app()->db->createCommand($sql)->queryAll();
                } else {
                    if ($approve_type == 2)
                        $condition .= " AND approve_count=2 ";
                    else if ($approve_type == 1)
                        $condition .= " AND approve_count < 2 ";

                    if ($customer_id > 0)
                        $condition .= " AND customer_id = $customer_id ";

                    if ($business_type_id > 0)
                        $condition .= " AND c.business_type_id = $business_type_id ";

                    if ($sales_type > 0)
                        $condition .= " AND sales_type = $sales_type ";

                    if ($report_type == 2) // VDS
                        $condition .= " AND total_vds > 0 ";

                    $sql = "SELECT *, si.id AS sid FROM sell_items AS si INNER JOIN customers AS c ON si.customer_id=c.id WHERE sales_date BETWEEN '$start_date' AND '$end_date' $condition ORDER BY so_no ASC";
                    $data = Yii::app()->db->createCommand($sql)->queryAll();
                }
                ?>

                <table border="1" style="empty-cells: show;width: 100%;border-collapse:collapse;"
                       class="stickyHead data-tr">
                    <thead>
                    <tr valign="top">
                        <td style="text-align: center;" colspan="11"><b>
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
                        <td colspan="11" style=" overflow:hidden; font-size:13px; padding-left: 80px;">
                            Sales Report (Summary)
                            <span class="total-count"> Total <?= count($data) ?> rows</span>
                        </th>
                    </tr>
                    <tr style="font-weight:bold">
                        <th>SL</th>
                        <th>Date</th>
                        <th>SO No.</th>
                        <th>Cash/Bank</th>
                        <th>Customer Name</th>
                        <th>Business Type</th>
                        <th>SD</th>
                        <th>VDS</th>
                        <th>Total Amount</th>
                        <th>VAT</th>
                        <th>Net Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $sd = 0;
                    $vat = 0;
                    $vds = 0;
                    $total = 0;
                    $grand_total = 0;
                    if (!empty($data)) {
                        foreach ($data as $d) {
                            $i++;
                            ?>
                            <tr class="csd">
                                <td><?= $i ?></td>
                                <td><?= !empty($d['sales_date']) ? date('d-m-Y', strtotime($d['sales_date'])) : '' ?></td>
                                <td><?= $d['so_no'] ?></td>
                                <td><?= ($d['payment_method'] == PurchaseItems::CASH) ? 'Cash' : (($d['payment_method'] == PurchaseItems::BANK) ? 'Bank' : '') ?></td>
                                <td style="width: 180px"><?= !empty($d['customer_id']) ? Customers::model()->customerName($d['customer_id']) : '' ?></td>
                                <td>
                                    <?php
                                    if ($d['business_type_id'] > 0)
                                        echo Lookup::item('business_type', $d['business_type_id']);
                                    ?>
                                </td>
                                <td style="text-align: right"><?= !empty($d['total_sd']) ? number_format($d['total_sd'], 2) : '' ?></td>
                                <td style="text-align: right"><?= !empty($d['total_vds']) ? number_format($d['total_vds'], 2) : '' ?></td>
                                <td style="text-align: right"><?= !empty($d['total']) ? number_format($d['total'], 2) : '' ?></td>
                                <td style="text-align: right"><?= !empty($d['total_tax']) ? number_format($d['total_tax'], 2) : '' ?>
                                </td>
                                <td style="text-align: right"><?= !empty($d['grand_total']) ? number_format($d['grand_total'], 2) : '' ?></td>
                            </tr>
                            <?php
                            $sd += $d['total_sd'];
                            $vat += $d['total_tax'];
                            $vds += $d['total_vds'];
                            $total += ($d['total']);
                            $grand_total += ($d['grand_total']);
                        }
                        ?>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr class="bold csd footer-color">
                        <td style="padding-right: 5px; text-align: right" colspan="6">Total</td>
                        <td style="text-align: right"><?= number_format($sd, 2) ?></td>
                        <td style="text-align: right"><?= number_format($vds, 2) ?></td>
                        <td style="text-align: right; color: #14a614"><?= number_format($total, 2) ?></td>
                        <td style="text-align: right; color: #14a614"><?= number_format($vat, 2) ?></td>
                        <td style="text-align: right"><?= number_format($grand_total, 2) ?></td>
                    </tr>
                    </tfoot>
                </table><br>
                <table style="empty-cells: show;width: 100%;border-collapse:collapse; margin-top: 40px">
                    <tr>
                        <td ><span> Sr.Officer (VAT)<br> (Prepared by) </span></td>
                        <td style="text-align: center;"><span> Deputy Manager (VAT)</span></td>
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