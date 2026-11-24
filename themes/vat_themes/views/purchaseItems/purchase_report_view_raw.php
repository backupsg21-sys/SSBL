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
.reportTabCustom{
    page-break-after: always;
}
</style>
<div class='printAllTableForThisReport'  style="background-color:#F9FCFD;">
   <!--<table class="reportTab">-->

            <table class="reportTabCustom">                
                <tbody>
                    <tr valign="top">
                        <td class="style_14" style=" overflow:hidden;">
                            <table style="border-collapse: collapse; empty-cells: show;width: 100%;" class="data-tr">
                                <colgroup>
                                    <col style=" width: 4%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 12%;">
                                    <col style=" width: 14%;">
                                    <col style=" width: 10%;">
                                    <col style=" width: 9%;">
                                    <col style=" width: 5%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 7%;">
                                    <col style=" width: 7%;">
                                </colgroup>
                                <tbody>
                                    <tr style="font-size:12px;" valign="top" align="center">
                                        <th> ক্রমিক সংখ্যা </th>
                                        <th> তারিখ </th>
                                        <th> বিল অব এন্ট্রি নম্বর </th>
                                        <th> বিল অব এন্ট্রি তারিখ </th>
                                        <th> নাম </th>
                                        <th> ঠিকানা </th>
                                        <th> নিবন্ধন/তালিকা <br>ভুক্তি/ জাতীয় <br>পরিচয়পত্র নং</th>
                                        <th> বিবরণ </th>
                                        <th> পরিমাণ <br>(একক)</th>
                                        <th> মূল্য (সকল প্রকার কর ব্যতীত)</th>
                                        <th> সম্পূরক শুল্ক <br>(যদি থাকে) </th>
                                        <th> মূসক </th>
                                    </tr>

                                    <?php
                                    if (!empty($data)) {
                                        $i = 1;
                                        $_t_order_qty = [];
                                        $_t_order_price = [];
                                        $_t_sd = [];
                                        $_t_vat = [];
                                        foreach ($data as $item) {
                                            if($item['parts_service']==1){  // 1 = parts, 2 = service
                                                $model_id = $item['model_id'];
                                                $model_name = ProdModels::model()->prodNameOfThis($model_id);
                                                $hsCode = ProdModels::model()->prodHSCodeOfThis($model_id);
                                            }else if($item['parts_service']==2){
                                                $model_id = $item['service_id'];
                                                $model_name = Services::model()->prodNameOfThis($model_id);
                                                $hsCode = Services::model()->prodCodeOfThis($model_id);
                                            }else{}
                                            $issue_date = $item['date'];
                                            ?>
                                            <tr valign="top" style="font-size: 11px; word-break: break-all">
                                                <td style=" overflow:hidden;text-align: center"><?= $i ?></td>
                                                <td style=" overflow:hidden;"><?= !empty($issue_date) ? date('d-m-Y', strtotime($issue_date)) : '' ?></td>

                                                <td  style=" width: 3%;text-align: center">
                                                    <?php
                                                    $purchaseInfo = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                    if($purchaseInfo){
                                                        if($item['local_import']==54){
                                                            echo $purchaseInfo['ref_no'];
                                                        }else if($item['local_import']==55){
                                                            echo $purchaseInfo['boe_no'];
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td  style=" overflow:hidden;">
                                                    <?php
                                                    if($purchaseInfo){
                                                        if($item['local_import']==54){
                                                            echo !empty($purchaseInfo['chalan_date']) ? date('d-m-Y', strtotime($purchaseInfo['chalan_date'])) : '';
                                                        }else if($item['local_import']==55){
                                                            echo !empty($purchaseInfo['boe_date']) ? date('d-m-Y', strtotime($purchaseInfo['boe_date'])) : '';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                                $purchase_info = PurchaseItems::model()->findByPk($item['purchase_id']);
                                                if (!empty($purchase_info)) {
                                                    if (!empty($purchase_info['supplier_id'])) {
                                                        $supplier_info = Suppliers::model()->findByPk($purchase_info['supplier_id']);
                                                    }
                                                }
                                                ?>
                                                <td style="text-align: left">
                                                    <?= !empty($supplier_info['name']) ? $supplier_info['name'] : '' ?>
                                                </td>
                                                <td style="text-align: left;padding-bottom: 3px;">
                                                    <?= !empty($supplier_info['address']) ? $supplier_info['address'] : '' ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <?= !empty($supplier_info['bin']) && $supplier_info['bin']!="0" ? $supplier_info['bin'] : '' ?>
                                                </td>
                                                <td style="text-align: left">
                                                    <?= !empty($model_name) ? $model_name : '' ?>
                                                </td>
                                                <td  style=" text-align:center">
                                                    <?= $_t_order_qty[] = !empty($item['order_qty']) ? $item['order_qty'] : '' ?><br> <?= !empty($unit_name) ? $unit_name : '' ?>
                                                </td>
                                                <td  style="text-align: right;">
                                                    <?= !empty($item['total']) ? number_format($_t_order_price[] = $item['total'], 2) : 0 ?>
                                                </td>
                                                <td  style="text-align: right">
                                                    <?= !empty($item['sd']) ? number_format($_t_sd[] = $item['sd'],2) : '0.00' ?>
                                                </td>
                                                <td  style="text-align: right">
                                                    <?= !empty($item['vat']) ? number_format($_t_vat[] = $item['vat'],2) : '0.00' ?>
                                                </td>
                                            </tr>

                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                    <tr style="font-size:11px;">
                                        <td colspan="8" style="text-align: right">সর্বমোট    &nbsp;
                                        </td>
                                        <td style="text-align: center">
                                            <?php echo array_sum($_t_order_qty); ?> <?= !empty($unit_name) ? $unit_name : '' ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php echo number_format(array_sum($_t_order_price),2); ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php echo number_format(array_sum($_t_sd),2); ?>
                                        </td>
                                        <td style=" text-align: right;">
                                            <?php echo number_format(array_sum($_t_vat),2); ?>
                                        </td>
                                    </tr>
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
       /* word-break: break-all;*/
        width: 11in;
    }
    .data-tr th, .data-tr td{
        border: 1px solid #000;
        font-weight: normal;
    }
</style>