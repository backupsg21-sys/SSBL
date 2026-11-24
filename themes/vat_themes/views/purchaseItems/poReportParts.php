<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
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
            $yourCompanyBin = $yourCompanyInfo->vat_regi_no;
        } else {
            $yourCompanyName = 'N/A';
            $yourCompanyLocation = 'N/A';
            $yourCompanyRoad = 'N/A';
            $yourCompanyHouse = 'N/A';
            $yourCompanyContact = 'N/A';
            $yourCompanyEmail = 'N/A';
            $yourCompanyWeb = 'N/A';
            $yourCompanyBin = 'N/A';
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
            hr{
                border: 1px solid #000000;
            }
            .listtable{border-collapse: collapse;color: #000000;}
            .listtable th, .listtable td{border: 1px solid #999999; font-size:11px; font-family:Arial, Helvetica, sans-serif; padding:5px 6px; text-align:center;}
            .listtable th{ background:#D2D2D2;}
            .tabledata td, .tabledata th{ font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:normal; line-height:15px; padding:2px 5px;}
            .ollist { padding:0 0 0 30px; margin:0}
            .ollist li { font-family:Arial, Helvetica, sans-serif; font-weight:normal; line-height:15px; padding:2px 0px}
            .hr{
                border: 1px solid #000000;
            }
        </style>
        <div class='printAllTableForThisReport' style='float: left;'>
            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="margin-top:15px;">
                <?php
                $thisQuickSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
                $customerInfo = Suppliers::model()->supplierAllInfo($purchaseItemModel->supplier_id);
                $loc_imp = $purchaseItemModel->local_import;
                ?>
                <tr>
                    <td width="55%" rowspan="2">
                        <b><?php echo $yourCompanyName; ?></b><br />
                        <?php echo 'H# ' . $yourCompanyHouse . ', ' . 'Road# ' . $yourCompanyRoad . ', ' . $yourCompanyLocation; ?><br />
                        <?php echo 'Mobile: ' . $yourCompanyContact ?><br>
                        <?php echo 'BIN: ' . $yourCompanyBin ?><br>
                        <?php if($loc_imp==55){?>
                            <b>BOE No:</b> <?php echo $purchaseItemModel->boe_no; ?>
                            <br><b>BOE Date:</b> <?=($purchaseItemModel->boe_date)? date('d-m-Y', strtotime($purchaseItemModel->boe_date)):'' ?>
                        <?php }?>
                    </td>
                    <td style="text-align: right; padding-bottom: 2px; vertical-align:top;">
                        <?php
                        if($loc_imp==54){ // local ?>
                            <b>Challan No:</b> <?php echo $purchaseItemModel->ref_no; ?>
                            <br><b>Challan Date:</b> <?=($purchaseItemModel->chalan_date)? date('d-m-Y', strtotime($purchaseItemModel->chalan_date)):'' ?>
                        <?php
                        }else if($loc_imp==55){ // foreign/import ?>
                            <br><b>LC No:</b> <?php echo $purchaseItemModel->lc_no; ?>
                            <br><b>LC Date:</b> <?=($purchaseItemModel->lc_date)? date('d-m-Y', strtotime($purchaseItemModel->lc_date)):'' ?>
                        <?php
                        }else{}
                        ?>
                        <br><b>Purchase By:</b> <?php echo Employees::model()->fullName($purchaseItemModel->purchase_by_id); ?>
                        <br><b>Purchase Date:</b> <?php echo date('d-m-Y',strtotime($purchaseItemModel->issue_date)) ?>
                        <br><b>Payment Method:</b>
                        <?php
                        if($purchaseItemModel->payment_method==133)
                            echo 'Cash';
                        else if($purchaseItemModel->payment_method==134)
                            echo 'Bank';
                        echo '';
                        ?>
                        <?php
                        if($loc_imp==55){ // local ?>
                            <br><b>Port: </b>
                            <?php
                            if($purchaseItemModel->port_id)
                                $port = Ports::model()->findByPk($purchaseItemModel->port_id);
                            if($port)
                                echo $port->name.' ('.$port->code.')';
                            ?>
                            <br><b>Country: </b>
                            <?php
                            if($purchaseItemModel->country_id)
                                echo Countries::model()->nameOfThis($purchaseItemModel->country_id);
                            ?>
                        <?php
                        }?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-bottom: 0px; vertical-align:top;"></td>
                </tr>
                <tr>
                    <th colspan="2" style="padding-bottom: 10px; font-size:16px; text-align: center"><u>PURCHASE INVOICE</u></th>
                </tr>
                <tr>
                    <td>
                        <b>Order No:</b> <?php echo $thisQuickSellNoFormat .'-'. $purchaseItemModel->po_no; ?>
                        (<?=($loc_imp==55)?'IMPORT':(($loc_imp==54)?'LOCAL':'')?>)
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px; vertical-align:top;">
                        <b>Supplier</b><br/><?=!empty($customerInfo->name)?$customerInfo->name:''?> (BIN/NID:<?=!empty($customerInfo->bin)?$customerInfo->bin:$customerInfo->nid?>)<br/>
                        <?=!empty($customerInfo->address)?$customerInfo->address.'<br/>':''?>
                        Mobile:<?=!empty($customerInfo->phone_no)?$customerInfo->phone_no:''?><br/>
                        Email:<?=!empty($customerInfo->email)?$customerInfo->email:''?>
                    </td>
                    <td style="padding-bottom: 10px; vertical-align:top; text-align:right">
                        <b>Vehicle Type :</b> <?= $purchaseItemModel->vehicle_type != '' ? $purchaseItemModel->vehicle_type : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ?><br>
                        <b>Vehicle No :</b> <?= $purchaseItemModel->vehicle_no != '' ? $purchaseItemModel->vehicle_no : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ?><br>
                    </td>
                </tr>
            </table>
            <table class="listtable" align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="border:1px solit #333; border-collapse: collapse;">
                <thead>
                    <tr>
                        <td colspan="<?=($loc_imp==55)? '20':'11'?>" style="text-align:center; margin:0"><b>Item Informations</b></td>
                    </tr>
                    <tr style="background-color:#ededf0">
                        <th width="35">Sl No</th>
                        <th>Item Description</th>
                        <th width="">Qty</th>
                        <th width="">Unit <br>Price</th>
                        <?php if($loc_imp==55){?><th width="">CV <br>Rate</th><?php } ?>
                        <th width=""><?=($loc_imp==55)? 'AV <br>(Total)':'Total'?></th>
                        <?php if($loc_imp==55){?><th width="">CD<br>(%)</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">CD</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">RD<br>(%)</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">RD</th><?php } ?>
                        <th width="">SD(%)</th>
                        <th width="">SD</th>
                        <th width="">VAT(%)</th>
                        <th width="">VAT</th>
                        <?php if($loc_imp==55){?><th width="">AIT<br>(%)</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">AIT</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">AT<br>(%)</th><?php } ?>
                        <?php if($loc_imp==55){?><th width="">AT</th><?php } ?>
                        <th width="">Total with<br>SD+VAT</th>
                        <th width="">Rebate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total_qty = 0;
                    $total = 0;
                    $total_sd = 0;
                    $total_vat = 0;
                    $grandTotal = 0;
                    $total_rebate = 0;
                    $total_cd = 0;
                    $total_rd = 0;
                    $total_ait = 0;
                    $total_at = 0;
                    ?>
                    <?php foreach ($data as $d): ?>
                        <tr class="<?php
                        if ($i % 2 == 0) {
                            echo 'even';
                        } else {
                            echo 'odd';
                        }
                        ?>">
                        <?php
                            $prodInfo = ProdModels::model()->findByPk($d->model_id);
                            $productName = $prodInfo->model_name;
                            $unitId = $prodInfo->unit_id;
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $i++; ?></td>
                            <td style="text-align: left;"> <?php echo $productName; ?> </td>
                            <td style="text-align: center;"><?php echo $d->order_qty . " " . Units::model()->unitLabel($unitId); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->unit_price,2); ?></td>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->cv_rate,2)?></td><?php } ?>
                            <td style="text-align:right"><?php echo number_format($d->total,2); ?></td>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->cd_percent,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->cd,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->rd_percent,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->rd,2)?></td><?php } ?>
                            <td style="text-align:right"><?php echo number_format($d->sd_percent,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->sd,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->vat_percent,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->vat,2); ?></td>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->ait_percent,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->ait,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->at_percent,2)?></td><?php } ?>
                            <?php if($loc_imp==55){?><td style="text-align:right"><?=number_format($d->at,2)?></td><?php } ?>
                            <td style="text-align:right"><?php echo number_format($d->net_total,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->rebate,2); ?></td>
                        </tr>
                    <?php
                    $total_qty += $d->order_qty;
                    $total += $d->total;
                    $total_sd += $d->sd;
                    $total_vat += $d->vat;
                    $grandTotal+=$d->net_total;
                    $total_rebate += $d->rebate;
                    $total_cd += $d->cd;
                    $total_rd += $d->rd;
                    $total_ait += $d->ait;
                    $total_at += $d->at;
                    endforeach;
                    ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right"> Total</th>
                    <th><?=$total_qty?></th>
                    <th style="text-align: right" colspan="<?=($loc_imp==55)? '3':'2'?>"><?=number_format($total,2)?></th>
                    <?php if($loc_imp==55){?><th style="text-align: right" colspan="2"><?=number_format($total_cd,2)?></th><?php } ?>
                    <?php if($loc_imp==55){?><th style="text-align: right" colspan="2"><?=number_format($total_rd,2)?></th><?php } ?>
                    <th style="text-align: right" colspan="2"><?=number_format($total_sd,2)?></th>
                    <th style="text-align: right" colspan="2"><?=number_format($total_vat,2)?></th>
                    <?php if($loc_imp==55){?><th style="text-align: right" colspan="2"><?=number_format($total_ait,2)?></th><?php } ?>
                    <?php if($loc_imp==55){?><th style="text-align: right" colspan="2"><?=number_format($total_at,2)?></th><?php } ?>
                    <th style="text-align:right"><?=number_format($grandTotal,2); ?></th>
                    <th style="text-align:right"><?=number_format($total_rebate,2); ?></th>
                </tr>
                <tr>
                    <td colspan="<?=($loc_imp==55)? '20':'11'?>" style="text-align: left">
                        Total VDS:
                        <b><?=number_format($purchaseItemModel->total_vds,2)?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="<?=($loc_imp==55)? '20':'11'?>" style="text-align:left"><b>In Words:</b>
                        <?php
                        $amountInWord = new AmountInWord();
                        echo $amountInWord->convert(intval($grandTotal)) . ' Taka Only.';
                        ?>
                    </td>
                </tr>
                </tfoot>
            </table>

            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #333; border-collapse: collapse;">

                <tr>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;"><hr />For <?php echo $yourCompanyName; ?></td>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;"><hr />Approved By</td>
                    <td width="25%" colspan="3" style="padding-top: 80px; font-weight: bold;"><hr />Received By</td>
                </tr>
                <tr>
                    <td colspan="3" >Signature (Store Manager)</td>
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
    /*table.vinfotab{
        float: left;
        width: 100%;
        border: 1px solid #000000;
        border-collapse: collapse;
    }
    table.vinfotab tr, table.vinfotab tr td, table.vinfotab tr th{
        border: 1px solid #000000;
        padding: 6px;
    }*/
    .printAllTableForThisReport{
        font-size: 11px;
    }
</style>
<script>
    $(document).ready(function(){
        $(".ui-dialog-titlebar-close").click(function () {
            window.location.replace("<?php echo Yii::app()->baseUrl; ?>/purchaseItems/adminPartsLocal");
        });
    });
</script>