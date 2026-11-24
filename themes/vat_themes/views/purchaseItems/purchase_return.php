<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'debit-search-form',
    //'action' => Yii::app()->createUrl('/purchaseItems/purchaseReturn'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<fieldset>
    <legend>Search PO</legend>
    <table class="summaryTab" style="text-align: left">
        <tr>
            <td>
			PO : 
                <?php
                echo $form->dropDownList(
                    $model, 'id', CHtml::listData(PurchaseItems::model()->findAll(['condition' => 'approve_count =2 AND (po_no is not null OR po_no > 0)  ','order'=>'po_no desc']), 'id', 'po_no'), array(
                    'prompt' => 'Select',
                    'style' => 'width:250px',
                ));?>

                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/purchaseReturn', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide();
                    window.location.replace("../purchaseItems/purchaseReturnForm/"+data);
                    //$("#resultDiv").html(data.content);
                }',
                'beforeSend' => 'function(){
                    if($("#PurchaseItems_id").val()==""){
                        alertify.alert("Warning! Please select Purchase Order No!");
                        return false;
                    }else{
                        $("#ajaxLoader").show();
                    }
                }',
                ));
                ?>
                <br>
                <br>
                <!--<b style="color:red;">This list is only for approved PO. You can not return after approved.</b>-->
            </td>
        </tr>
    </table>
</fieldset>

<?php $this->endWidget(); ?>

<div id="resultDiv"></div>
<style>
.table.summaryTab tr, table.summaryTab tr td {
    border: 1px solid #fff;
}
</style>