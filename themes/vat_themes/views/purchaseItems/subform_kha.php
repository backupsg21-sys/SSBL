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
$eng = false;
if(isset($_SESSION['translation_type']) && $_SESSION['translation_type']==2)
    $eng = true;
?>
<div class='printAllTableForThisReport' style="background-color: #F1F3F4; min-height: 650px;">
    <table class="reportTabCustom" style="border-collapse: collapse; empty-cells: show;">
    <colgroup>
        <col>
    </colgroup>
    <tbody style="border:5px solid #fff;">
        <tr valign="top">
            <td class="style_14">
                <table class="style_23" style="border-collapse: collapse; empty-cells: show; width: 100%;">
                    <colgroup>
                        <col style=" width: 8%;">
                            <col style=" width: 16%;">
                                <col style=" width: 10%;">
                                    <col style=" width: 10%;">
                                        <col style=" width: 15%;">
                                            <col style=" width: 10%;">
                                                <col style=" width: 10%;">
                                                    <col style=" width: 10%;">
                                                            <col style=" ">
                    </colgroup>
                    <tbody>
                        <tr style="border:1px solid #000; text-align: center">
                            <th colspan="9">
                                <?=($eng)? 'Sub-form : (For Note -':'সাবর্ফম্- খ  ( নোট'?>
                                <?php
                                if($note=='eight'){
                                    if($eng) echo '8'; else echo '৮';
                                }
                                ?>
                                <?=($eng)? ')':'এর জন্য প্রযোজ্য)'?>
                            </th>
                        </tr>
                        <tr class="style_5" valign="top" align="center" style="background-color:#f2f0f0;vertical-align: middle">
                            <th class="style_24 center">
                                <?=($eng)? 'Serial<br>No.':'ক্রমিক <br> নং'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Category':'ক্যাটাগরী'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Goods /Service Commercial Description':'পণ্য/সেবার বানিজ্যিক বর্ণনা'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Goods /<br>Service Code':'পণ্য/সেবার কোড'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Goods /<br>Service Name':'পণ্য/সেবার নাম'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Value<br>(a)':'মূল্য<br>(ক)'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'SD<br>(b)':'সম্পূরক <br> শুল্ক (খ)'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'VAT<br>(c)':'মূল্য সংযোজন <br> কর   <br> (গ)'?>
                            </th>
                            <th class="style_24 center">
                                <?=($eng)? 'Notes':'মন্তব্য'?>
                            </th>
                        </tr>
                        <?php
						$yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE));
						
                        if(!empty($note)){
                            if($note=='eight'){
                                // tax = 0
                                $condition = "vat_calculation_method=2 AND approve_count=2";
                            }                            
                            // don't select all(*), so column gets duplicate
                            /*$sql = "SELECT sales_details.*, customers.business_type_id FROM  sales_details INNER JOIN sell_items ON (sales_details.sales_id = sell_items.id)
                                            INNER JOIN customers ON (sell_items.customer_id = customers.id) WHERE sales_details.sales_date BETWEEN '$start_date' AND '$end_date' AND ($condition) ORDER BY sales_details.sales_date ASC";
*/
                            $sql = "SELECT * FROM sales_details WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND ($condition) ORDER BY sales_date ASC";
                            $data= Yii::app()->db->createCommand($sql)->queryAll();

                            if(!empty($data) && $yourCompanyInfo->business_process==3){
                                
                                $i=1;
                                $total = 0;
                                $sd = 0;
                                $vat = 0;
                                foreach($data as $d){
                                    if($d['parts_service']==1){
                                        $type = 'Parts';
                                        $info = ProdModels::model()->findByPk($d['model_id']);
                                        $name = !empty($info['model_name'])?$info['model_name']:'';
                                        $code = !empty($info['hs_code'])?$info['hs_code']:'';
                                    }else{
                                        $type = 'Service';
                                        $info = Services::model()->findByPk($d['model_id']);
                                        $name = !empty($info['name'])?$info['name']:'';
                                        $code = !empty($info['code'])?$info['code']:'';
                                    }

                                    $business_type_id = "";
                                    $customer_type = Customers::model()->findByPk($d['customer_id']);
                                    if($customer_type && !empty($customer_type['business_type_id']))
                                        $business_type_id = Lookup::item('business_type', $customer_type['business_type_id'])
                                    ?>                                  
                                    <tr class="csd">
                                      <td class="center"><?=$i?></td>
                                      <td>
                                        <?=$business_type_id?>
                                      </td>
                                      <td></td>
                                      <td><?=$code?></td>
                                      <td><?=$name?></td>
                                      <td class="right"><?=!empty($d['total'])? number_format($d['total'],2):0.00?></td>
                                      <td class="right"><?=!empty($d['sd'])? number_format($d['sd'],2):0.00?></td>
                                      <td class="right"><?=!empty($d['vat'])? number_format($d['vat'],2):0.00?></td>
                                      <td></td>
                                    </tr>                                    
                             <?php   
                             $i++;
                             $total = $total + $d['total'];
                             $sd = $sd + $d['sd'];
                             $vat = $vat + $d['vat'];
                                }?>
                                    <tr class="right bold csd" style="background-color: #fbf7f7;">
                                        <td colspan="5" style="padding-right: 5px"><?=($eng)? 'Total':'মোট'?></td>
                                        <td><?= number_format($total,2)?></td>
                                        <td><?= number_format($sd,2)?></td>
                                        <td><?= number_format($vat,2)?></td>
                                        <td></td>
                                    </tr>
                              <?php      
                              }                            
                        }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<style>
    .fieldset{
        background-color: #fbf7f7f7;
    }
    .reportTabCustom{
        font-size:12px;
        background-color:#fff;
        width: 8.3in;
        margin: auto;
        font-family: 'times new roman', arial;
        min-height: 8in;
        color:#000;
    }
    .style_5 th, .style_21 th,  .style_21 td{
        border: 1px solid #000 !important; /*#c0c0c0*/
    }
    .style_19{
        border: none;
    }
    .center_tr th{
        text-align: center;
        border: 1px solid #000;
    }
    .csd td{
        border:1px solid #000;
    }
    .left{text-align:left;}
    .center{text-align:center;}
    .right{text-align:right;}
    .bold{font-weight: bold;}
    @media print {
        .reportTabCustom{ width: 100%}
    }
</style>