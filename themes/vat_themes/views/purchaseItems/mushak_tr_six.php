<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakTRSixView'),
        ));
?>
<style>
    #PurchaseDetails_issue_date{
        width:200px !important;
    }
</style>
<fieldset>
    <legend>TR 6 (Treasury Challan)</legend>
    <table style="text-align: left">
        <tr>
            <td style="width:20%;border: 1px solid #fff;">
                VAT PV VPV-
                <?php
                    echo $form->dropDownList(
                        $model, 'id', CHtml::listData(VatPayment::model()->findAll(array('order'=>'sl_no desc')), 'id', 'sl_no','' ,'VPV-'), array(
                        'prompt' => 'Select',
                        'style' => 'width:250px',
                        'id' => 'vat_id',
                        'name'=> 'vat',
                ));?>
            </td>
     
            <td>
                <?php
                        echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakTRSixView', 'render' => true)), array(
                            'dataType' => 'json',
                            'type' => 'post',
                            'success' => 'function(data) {
                            $("#ajaxLoader").hide(); 
                            $("#resultDiv").html(data.content);
                        }',
                            'beforeSend' => 'function(){ 
                                $("#resultDiv").html("");
                                var vat_id = $("#vat_id").val();
                                var vds_id = $("#vds_id").val();

                            if(vat_id==""){
                                alertify.alert("Warning! Please Select VAT Serial!");
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