<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen"/>
<div style="float: left; width: 100%; border:1px;">
    <?php
    if ($data) {
        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
        if ($yourCompanyInfo) {
            $yourCompanyName = $yourCompanyInfo->company_name;
            $yourCompanyLocation = $yourCompanyInfo->location;
            $yourCompanyRoad = $yourCompanyInfo->road;
            $yourCompanyHouse = $yourCompanyInfo->house;
            $yourCompanyContact = $yourCompanyInfo->contact;
            $yourCompanyEmail = $yourCompanyInfo->email;
            $yourCompanyWeb = $yourCompanyInfo->web;
        } else {
            $yourCompanyName = 'N/A';
            $yourCompanyLocation = 'N/A';
            $yourCompanyRoad = 'N/A';
            $yourCompanyHouse = 'N/A';
            $yourCompanyContact = 'N/A';
            $yourCompanyEmail = 'N/A';
            $yourCompanyWeb = 'N/A';
        }
        ?>
        <?php
        echo "<div class='printBtn'>";
        $this->widget('ext.mPrint.mPrint', array(
            'title' => ' ', //the title of the document. Defaults to the HTML title
            'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
            'text' => '', //text which will appear beside the print icon. Defaults to NULL
            'element' => '.printAllTableForThisReport', //the element to be printed.
            'exceptions' => array(//the element/s which will be ignored
                '#inwordinput',
            ),
            'publishCss' => TRUE, //publish the CSS for the whole page?
            'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
            'alt' => 'print', //text which will appear if image can't be loaded
            'debug' => FALSE, //enable the debugger to see what you will get
            'id' => 'print-div'         //id of the print link
        ));
        echo "</div>";
        ?>

        <style>
            div.billDiv {
                float: left;
                width: 45%;
                height: 200px;
                padding: 5px;
            }

            hr {
                border: 1px solid #000000;
            }

            .listtable {
                border-collapse: collapse;
                color: #000000;
            }

            .listtable th, .listtable td {
                border: 1px solid #999999;
                font-size: 11px;
                font-family: Arial, Helvetica, sans-serif;
                padding: 5px 6px;
                text-align: center;
            }

            .listtable th {
                background: #D2D2D2;
            }

            .tabledata td, .tabledata th {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                font-weight: normal;
                line-height: 15px;
                padding: 2px 5px;
            }

            .ollist {
                padding: 0 0 0 30px;
                margin: 0
            }

            .ollist li {
                font-family: Arial, Helvetica, sans-serif;
                font-weight: normal;
                line-height: 15px;
                padding: 2px 0px
            }

            .hr {
                border: 1px solid #000000;
            }
        </style>
        <div class='printAllTableForThisReport' style='float: left;'>
            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="margin-top:15px;">
                <?php
                $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                if ($sellItemModel->customer_id)
                    $customerInfo = Customers::model()->findByPk($sellItemModel->customer_id);
                $customerName = $customerAddress = "";
                if ($customerInfo) {
                    $customerName = $customerInfo['company_name'];
                    $customerAddress = $customerInfo['company_address'];
                }
                $sales_type = $sellItemModel->sales_type;
                ?>
                <tr>
                    <td width="55%" rowspan="2" style="vertical-align: top">
                        <b><?php echo $yourCompanyName; ?></b><br/>
                        <?php echo 'H# ' . $yourCompanyHouse . ', ' . 'Road# ' . $yourCompanyRoad . ', ' . $yourCompanyLocation; ?>
                        <br/>
                        <?php echo 'Mobile: ' . $yourCompanyContact ?>
                    </td>
                    <td style="text-align: right; padding-bottom: 2px; vertical-align:top;">
                        <?php
                        if ($sales_type == 2) { // Export
                            ?>
                            <b>Challan No:</b> <?php echo $sellItemModel->challan_no; ?>
                            <br><b>Challan
                                Date:</b> <?= ($sellItemModel->challan_date) ? date('d-m-Y', strtotime($sellItemModel->challan_date)) : '' ?>
                            <br>
                            <b>LC No:</b> <?php echo $sellItemModel->lc_no; ?>
                            <br><b>LC
                                Date:</b> <?= ($sellItemModel->lc_date) ? date('d-m-Y', strtotime($sellItemModel->lc_date)) : '' ?>
                            <br><b>Country: </b>
                            <?php
                            if ($sellItemModel->dest_country_id)
                                echo Countries::model()->nameOfThis($sellItemModel->dest_country_id); ?>
                            <?php
                        }
                        ?>
                        <br><b>Sales Date:</b> <?php echo date('d-m-Y', strtotime($sellItemModel->sales_date)) ?>
                        <br><b>Sales By:</b> <?php echo Employees::model()->fullName($sellItemModel->sales_by_id); ?>
                        <br><b>Payment Method:</b>
                        <?php
                        if ($sellItemModel->payment_method == PurchaseItems::CASH)
                            echo 'Cash';
                        else if ($sellItemModel->payment_method == PurchaseItems::BANK)
                            echo 'Bank';
                        echo '';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-bottom: 0px; vertical-align:top;"></td>
                </tr>
                <tr>
                    <th colspan="2" style="padding-bottom: 10px; font-size:16px; text-align: center"><u>SALES
                            INVOICE <?= ($sellItemModel->sales_type == 1) ? '(LOCAL)' : (($sellItemModel->sales_type == 2) ? '(EXPORT)' : ''); ?></u>
                    </th>
                </tr>
                <tr>
                    <td>
                        <b>Order No:</b> <?php echo $thisQuickSellNoFormat . '-' . $sellItemModel->so_no; ?>
                        <?= ($sellItemModel->export_type == 1) ? '(Deemed)' : (($sellItemModel->export_type == 2) ? '(Direct)' : ''); ?>
                        <br>
                        <b>PO No:</b> <?= $sellItemModel->po_no ?>
                        <br>
                        <b>Customer Name:</b> <?= $customerName ?>
                        (BIN/NID:<?= !empty($customerInfo->bin) ? $customerInfo->bin : $customerInfo->nid ?>)<br>
                        <b>Customer Address:</b> <?= $customerInfo->company_address; ?><br>
                        <b>Delivery Address:</b> <?= $sellItemModel->delivery_address ?><br>
                        <b>Vehicle Type:</b> <?= $sellItemModel->vehicle_type ?><br>
                        <b>Vehicle No:</b> <?= $sellItemModel->vehicle_no ?><br>
                    </td>
                </tr>
            </table>
            <table class="listtable" align="center" width="805" border="0" cellspacing="0" cellpadding="5"
                   style="border:1px solid #000; border-collapse: collapse;">
                <thead>
                <tr>
                    <td colspan="12" style="text-align:center; margin:0"><b>Item Informations</b></td>
                </tr>
                <tr style="background-color:#ededf0">
                    <th width="35">SL No</th>
                    <th width="70">Type</th>
                    <th>Item Description</th>
                    <th width="70">Qty</th>
                    <th width="70">Unit Price</th>
                    <th width="70">Total</th>
                    <th width="70">SD(%)</th>
                    <th width="70">SD</th>
                    <th width="70">VAT(%)</th>
                    <th width="70">VAT</th>
                    <th width="70">Total Tax</th>
                    <th width="70">Total with<br>SD+VAT</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $total_qty = 0;
                $total = 0;
                $total_sd = 0;
                $total_vat = 0;
                $total_total_tax = 0;
                $grandTotal = 0;
                ?>
                <?php
                foreach ($data as $d): ?>
                    <tr class="<?php
                    if ($i % 2 == 0) {
                        echo 'even';
                    } else {
                        echo 'odd';
                    }
                    ?>">

                        <?php
                        if ($d['parts_service'] == ProdModels::GOODS) { //1=Parts, 2=Service
                            $prodInfo = ProdModels::model()->findByPk($d->model_id);
                            $model_name = $prodInfo->model_name;
                        } else if ($d['parts_service'] == ProdModels::SERVICE) {
                            $prodInfo = Services::model()->findByPk($d->model_id);
                            $model_name = $prodInfo->name;
                        } ?>

                    <tr>
                        <td style="text-align: center;"><?php echo $i++; ?></td>
                        <td style="text-align: left;"><?= ($d->parts_service == 1) ? 'Goods' : 'Service' ?></td>
                        <td style="text-align: left;"><?= !empty($model_name) ? $model_name : '' ?></td>
                        <td><?php echo $d->sales_qty . " " . Units::model()->unitLabel($prodInfo->unit_id); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->unit_price, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->total, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->sd_percent, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->sd, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->vat_percent, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->vat, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->total_tax, 4); ?></td>
                        <td style="text-align:right"><?php echo number_format($d->net_total, 4); ?></td>
                    </tr>
                    <?php
                    $total_qty += $d->sales_qty;
                    $total += $d->total;
                    $total_sd += $d->sd;
                    $total_vat += $d->vat;
                    $total_total_tax += $d->total_tax;
                    $grandTotal += $d->net_total;
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right"> Total</th>
                    <th><?= $total_qty ?></th>
                    <th style="text-align: right" colspan="2"><?= number_format($total, 4) ?></th>
                    <th style="text-align: right" colspan="2"><?= number_format($total_sd, 4) ?></th>
                    <th style="text-align: right"></th>
                    <th style="text-align: right" colspan="2"><?= number_format($total_total_tax, 4) ?></th>
                    <th style="text-align:right"><?php echo number_format($grandTotal, 4); ?></th>
                </tr>
                <tr>
                    <td colspan="12" style="text-align:left"><b>In Words:</b>
                        <?php
                        $amountInWord = new AmountInWord();
                        $inword = $amountInWord->convert(intval($grandTotal));
                        if ($amountInWord->convertFloat($grandTotal)) {
                            $inword .= $amountInWord->convertFloat($grandTotal);
                        } else {
                            $inword .= ' Only';
                        }
                        echo $inword;
                        ?>
                    </td>
                </tr>
                </tfoot>
            </table>

            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5"
                   style="border-collapse: collapse;">
                <tr>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;">
                        <hr/>
                        For <?php echo $yourCompanyName; ?></td>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;">
                        <hr/>
                        Approved By
                    </td>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;">
                        <hr/>
                        Received By
                    </td>
                </tr>
                <tr>
                    <td colspan="3">Signature (Store Manager)</td>
                    <td colspan="3">Signature:</td>
                    <td colspan="3">Signature:</td>
                </tr>
            </table>
        </div>
        <?php
    } else {

    }
    ?>
</div>
<style>
    .printAllTableForThisReport {
        font-size: 11px;
    }
</style>
<script>
    $(document).ready(function () {
        $(".ui-dialog-titlebar-close").click(function () {
            window.location.replace("<?php echo Yii::app()->baseUrl; ?>/sellItems/admin");
        });
    });
</script>