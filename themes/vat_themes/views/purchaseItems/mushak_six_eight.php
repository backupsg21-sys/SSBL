<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakSixEightView'),
        ));
$this->pageTitle = "Mushak 6.8 (Debit Note)";
?>
<style>
    #PurchaseDetails_issue_date{
        width:200px !important;
    }
</style>
<fieldset>
    <legend>Mushak 6.8 (Debit Note)</legend>
    <table style="text-align: left">
        <tr>
            <td style="border: 1px solid #fff;">
                PO/CL : 
                <?php
                echo $form->dropDownList(
                    $model, 'id', CHtml::listData(PurchaseReturn::model()->findAll(['order'=>'sl_no desc']), 'sl_no', 'sl_no','','PO/CL-'), array(
                    'prompt' => 'Select',
                    'style' => 'width:250px',
                ));?>
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakSixEightView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){   
                 if($("#PurchaseDetails_id").val()==""){
                    alertify.alert("Warning! Please select PO/CL no!");
                    return false;
                }else{
                    $("#ajaxLoader").show();
                }
             }',
                ));
                ?>
            </td>
        </tr>
    </table>
    
    <div id="ajaxLoader" style="display: none;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    <div id="resultDiv"></div>
</fieldset>
<?php $this->endWidget(); ?>