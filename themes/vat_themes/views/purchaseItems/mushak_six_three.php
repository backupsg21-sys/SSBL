<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakSixThreeView'),
        ));
$this->pageTitle = "Mushak 6.3 (Tax Challan)";
?>
<fieldset>
    <legend>Mushak 6.3 (Tax Challan)</legend>
    <table style="text-align: left">
        <tr>
            <td style="border: 1px solid #fff;">
				SO:
                <?php
                echo $form->dropDownList(
                    $model, 'id', CHtml::listData(SellItems::model()->findAll(["condition"=>"approve_count=2",  'order'=>'so_no desc']), 'id', 'so_no','','SO-'), array(
                    'prompt' => 'Select',
                    'style' => 'width:200px',
                ));?>
                &nbsp;&nbsp;
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakSixThreeView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){   
                if($("#PurchaseItems_id").val()==""){
                    alertify.alert("Warning! Please select Sales Order No!");
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