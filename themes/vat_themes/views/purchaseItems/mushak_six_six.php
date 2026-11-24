<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakSixSixView'),
        ));
?>
<style>
    #PurchaseDetails_issue_date{
        width:200px !important;
    }
</style>
<fieldset>
    <legend>Mushak 6.6 (VDS Certificate)</legend>
    <table style="text-align: left">
        <tr>
            <td style="border: 1px solid #fff;">
                Select month:
                <?php
                $this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
                    'model' => $model,
                    'attribute' => 'issue_date',
                    'options' => array(
                        'yearRange' => '-5:+15',
                        'dateFormat' => 'M-yy',
                    ),
                ));
                ?>
                &nbsp;&nbsp;OR&nbsp;&nbsp;
                SL :
                <?php
                echo $form->dropDownList(
                    $model, 'id', CHtml::listData(VdsPayment::model()->findAll(['condition'=>'vds_type = 1', 'order'=>'sl_no desc']), 'id', 'mushok_no'), array(
                    'prompt' => 'Select',
                    'style' => 'width:250px',
                ));?>
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakSixSixView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){  
                        $("#resultDiv").html("");
                        var idate = $("#PurchaseDetails_issue_date").val();
                        var ipo = $("#PurchaseDetails_id").val();

                        if(idate=="" && ipo==""){
                            alertify.alert("Warning! Please select month or PO no!");
                            return false;
                        }else if(idate!="" && ipo!=""){
                            alertify.alert("Warning! Please select one thing at a time!");
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