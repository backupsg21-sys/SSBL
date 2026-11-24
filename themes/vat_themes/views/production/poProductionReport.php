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
        /*echo "<div class='printBtn'>";
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
            'id' => 'print-div',         //id of the print link
            'onClick'=> 'window.print()'
        ));
        echo "</div>";*/
        ?>
        <input type="button" onclick="printDiv('print-content')" value="Print" style="float:left; background-color: #cc7226"/>
        
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
        <div class='printAllTableForThisReport' style='float: left;'  id="print-content">
            <table align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="margin-top:15px;">
                <tr>
                    <td width="55%" rowspan="2">
                        <b><?php echo $yourCompanyName; ?></b><br />
                        <?php echo 'H# ' . $yourCompanyHouse . ', ' . 'Road# ' . $yourCompanyRoad . ', ' . $yourCompanyLocation; ?><br />
                        <?php echo 'Mobile: ' . $yourCompanyContact ?>
                    </td>
                    <td style="text-align: right; padding-bottom: 2px; vertical-align:top;">
                        <b>Production  No:</b> <?php  echo $data['sl_no']?>  <br>
                        <b>Production  Date:</b>  <?php  echo date("d-m-Y", strtotime($data['issue_date']))?>  <br>
                        <b>Created By:</b>   <?php echo Users::model()->userNameOfThis($data['created_by'])?><br>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-bottom: 0px; vertical-align:top;"></td>
                </tr>
                <tr>
                    <th colspan="2" style="padding-bottom: 10px; font-size:16px; text-align: center"><u> PRODUCTION VOUCHER </u></th>
                </tr>
                <tr>
                    <td>
                        <b> Product Name:</b>  <?php echo ProdModels::model()->prodNameOfThis($data['finish_product_id']) ?> <br>
                        <b> Production Qty::</b> <?php  echo $data['qty']?> <br>
                        <b> Unit:</b> <?php echo Units::model()->unitLabel($data['fg_unit_id']) ?>

                    </td>
                </tr>

            </table>
            <table class="listtable" align="center" width="805" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #333; border-collapse: collapse;">
                <thead>
                <tr>
                    <td colspan="5" style="text-align:center; margin:0"><b> Raw Material Consumption Information </b></td>
                </tr>
                <tr style="background:#ededf0">
                    <th >Sl No</th>
                    <th>Raw Material Name</th>
                    <th>RM Qty </th>
                    <th>Unit </th>
                    <th> Wastage Qty </th>
                </tr>
                <tbody>
                <?php
                $i = 0;
                $grandTotal = 0;
                foreach ($inventory_data as $d): ?>
                    <tr class="<?php
                    if ($i % 2 == 0) {
                        echo 'even';
                    } else {
                        echo 'odd';
                    }
                    ?>">
                        <?php
                        $production_id = $data['id'];
                        $model_id = $d['model_id'];
                        $waste_qty_data = ProductionMaterial::model()->find(array('condition' => "production_id =  $production_id and model_id = $model_id"));

                        ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $i+1; ?></td>
                        <td style="text-align: left;"> <?php echo ProdModels::model()->prodNameOfThis($d['model_id']); ?> </td>
                        <td><?php echo $d['stock_out']?></td>
                        <td><?php echo Units::model()->unitLabel($d['unit_id']); ?></td>
                        <td><?php echo $waste_qty_data['waste_qty']?></td>
                    </tr>
                    <?php
                    //$grandTotal += $d['net_total_new'];
                    $i++;
                endforeach;
                ?>

                </tbody>
                </thead>

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
            window.location.replace("<?php echo Yii::app()->baseUrl; ?>/production/admin");
        });
    });

</script>
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        w=window.open();
        w.document.write(printContents);
        w.print();
        w.close();
    }
</script>
