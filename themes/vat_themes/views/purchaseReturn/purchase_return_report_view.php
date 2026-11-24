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
                        <td style=" text-align: center; overflow: hidden; font-size: 11px;" colspan="9"><b>
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
                        <td colspan="9" style=" overflow:hidden; font-size:13px; text-align: center;">
                            Debit Note Issue Report
                        </th>
                    </tr>

                    <tr class="csd header-color" valign="top" align="center">
                        <th class="style_24 center">
                            SL
                        </th>
                        <th class="style_24 center">
                            Supplier Name
                        </th>
                        <th class="style_24 center">
                            Challan No
                        </th>
                        <th class="style_24 center">
                            Challan Date
                        </th>
                        <th class="style_24 center">
                            Product Name
                        </th>
                        <th class="style_24 center">
                            Purchase Qty
                        </th>
                        <th class="style_24 center">
                            Return Qty
                        </th>
                        <th class="style_24 center">
                            Return <br> Challan No
                        </th>
                        <th class="style_24 center">
                            Return <br> Challan Date
                        </th>
                    </tr>
                    <tbody>
                    <?php
                    if (!empty($data)) {
                        $i = 1;
                        foreach ($data as $d) {
                            $po_data = PurchaseItems::model()->findByPk($d['purchase_id']);
                            $po_details = PurchaseDetails::model()->findByAttributes(array('purchase_id' => $d['purchase_id']));
                            if (!empty($po_data))
                                $supplier_name = Suppliers::model()->supplierName($po_data['supplier_id']);
                            ?>
                            <tr class="csd">
                                <td class="center"><?= $i ?></td>
                                <td class="center"><?= !empty($supplier_name) ? $supplier_name : '' ?></td>
                                <td class="center"><?= !empty($po_data['ref_no']) ? $po_data['ref_no'] : '' ?></td>
                                <td class="center"><?= !empty($po_data['issue_date']) ? date('d-m-Y', strtotime($po_data['issue_date'])) : '' ?></td>
                                <td class="center"><?= !empty($d['model_id']) ? ProdModels::model()->prodNameOfThis($d['model_id']) : '' ?></td>
                                <td class="center"><?= !empty($po_details['order_qty']) ? $po_details['order_qty'] : '' ?></td>
                                <td class="center"><?= !empty($d['stock_out']) ? $d['stock_out'] : '' ?></td>
                                <td class="center"><?= !empty($d['return_challan_no']) ? $d['return_challan_no'] : '' ?></td>
                                <td class="center"><?= !empty($d['date']) ? date('d-m-Y', strtotime($d['date'])) : '' ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?>
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
        background-color: # #fcfff9;
    }
</style>