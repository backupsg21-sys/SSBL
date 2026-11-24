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
            div.billDiv{
                float: left;
                width: 45%;
                height: 200px;
                padding: 5px;
            }
            hr{
                border: 1px solid #000000;
            }
            .listtable{border-collapse: collapse;color: #000000;}
            .listtable th, .listtable td{border: 1px solid #999999; font-size:12px; font-family:Arial, Helvetica, sans-serif; padding:5px 6px; text-align:center;}
            .listtable th{ background:#D2D2D2;}
            .tabledata td, .tabledata th{ font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:normal; line-height:15px; padding:2px 5px;}
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
                ?>
                <tr>
                    <td width="55%" rowspan="2">
                        <b><?php echo $yourCompanyName; ?></b><br />
                        <?php echo 'H# ' . $yourCompanyHouse . ', ' . 'Road# ' . $yourCompanyRoad . ', ' . $yourCompanyLocation; ?><br />
                        <?php echo 'Mobile: ' . $yourCompanyContact ?>
                    </td>
                    <td style="text-align: right; padding-bottom: 2px; vertical-align:top;">
                        <b>Challan No:</b> <?php echo $purchaseItemModel->ref_no; ?>
                        <br><b>Purchase By:</b> <?php echo Employees::model()->fullName($purchaseItemModel->purchase_by_id); ?>
                        <br><b>Purchase Date:</b> <?php echo date('d-m-Y',strtotime($purchaseItemModel->issue_date)) ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-bottom: 0px; vertical-align:top;"></td>
                </tr>
                <tr>
                    <th colspan="2" style="padding-bottom: 10px; font-size:16px;"><u>PURCHASE INVOICE</u></th>
                </tr>
                <tr>
                    <td><b>Service No:</b> <?php echo $thisQuickSellNoFormat .'-'. $purchaseItemModel->service_no; ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px; vertical-align:top;"><b>Supplier</b><br/><?php echo $customerInfo->name; ?><br/><?php echo $customerInfo->address; ?><br/>Mobile:<?php echo $customerInfo->phone_no; ?><br/>Email:<?php echo $customerInfo->email; ?></td>
                </tr>
            </table>
            <table class="listtable" align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="border:1px solit #333; border-collapse: collapse;">
                <thead>
                    <tr>
                        <td colspan="7" style="text-align:center; margin:0"><b>Item Informations</b></td>
                    </tr>
                    <tr style="background-color:#ededf0">
                        <th width="35">Sl No</th>
                        <th>Item Description</th>
                        <th width="70">Qty</th>
                        <th width="70">Unit Price</th>
                        <th width="70">Total</th>
                        <th width="70">TAX</th>
                        <th width="70">Net Total</th>
                    </tr>
                <tbody>
                    <?php
                    $i = 1;
                    $grandTotal = 0;
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
                                $prodInfo = Services::model()->findByPk($d->service_id);
                                $productName = $prodInfo->name;
                                $unitId = $prodInfo->unit_id;
                            ?>

                        <tr>
                            <td style="text-align: center;"><?php echo $i++; ?></td>
                            <td style="text-align: left;"> <?php echo $productName; ?> </td>
                            <td><?php echo $d->order_qty . " " . Units::model()->unitLabel($unitId); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->unit_price,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->total,2); ?></td>
                            <td style="text-align:right"><?php echo number_format($d->total_tax,2); ?></td>
                            <td style="text-align:right">
                                <?php 
                                echo number_format($d->net_total,2); 
                                $grandTotal+=$d->net_total;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>    
                </thead>
                <tr>
                    <th colspan="6" style="text-align:right"> Grand Total</th>
                    <th style="text-align:right"><?php echo number_format($grandTotal,2); ?></th>
                </tr>
                <tr>
                    <td colspan="7" style="text-align:left"><b>In Words:</b> 
                        <?php
                        $amountInWord = new AmountInWord();
                        echo $amountInWord->convert(intval($grandTotal)) . ' Taka Only.';
                        ?>
                    </td>
                </tr>
            </table>

            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="border:1px solit #333; border-collapse: collapse;">            

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
    table.vinfotab{
        float: left;
        width: 100%;
        border: 1px solid #000000;
        border-collapse: collapse;
    }
    table.vinfotab tr, table.vinfotab tr td, table.vinfotab tr th{
        border: 1px solid #000000;
        padding: 6px;
    }
</style>
<script>
    $(document).ready(function(){
        $(".ui-dialog-titlebar-close").click(function () {
            window.location.replace("<?php echo Yii::app()->baseUrl; ?>/purchaseItems/adminServices");
        });
    });
</script> 