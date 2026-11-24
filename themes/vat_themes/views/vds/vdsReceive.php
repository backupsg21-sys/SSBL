<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'vds-receive-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));

$challan_no = '';
$challan_date = '';
$vds_total = 0;
if($model->vds > 0){
    $vds_total = $model->vds;
}
if($model->sales_id && $salesInfo = SellItems::model()->findByPk($model->sales_id)){
    $challan_no = $salesInfo->mushok_no;
    /*if($salesInfo->challan_date)
        $challan_date = date('d-m-Y', strtotime($salesInfo->challan_date));*/
}
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'VDS Receive Form' : 'VDS Receive Form'); ?></legend>
        <span class="hint-msg">*** Reports will generate with Adjustment Date</span>
        <table>
            <tr>
                <td>Adjustment Date</td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'payment_date', //attribute name
                        'id' => 'payment_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'certificate_no'); ?></td>
                <td><?php echo $form->textField($model, 'certificate_no'); ?></td>
            </tr>
			<tr>
                <td>Certificate Date</td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig55 = array(
                        'model' => $model, //Model object
                        'attribute' => 'certificate_date', //attribute name
                        'id' => 'certificate_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig55);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'challan_no'); ?></td>
                <td><?php echo $form->textField($model, 'challan_no', ['value'=>$challan_no, 'readonly'=>true]); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'challan_date', ['readonly'=>true]); ?></td>
                <td><?php echo $form->textField($model, 'challan_date', ['value'=>$model->issue_date, 'readonly'=>true]); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'tr_challan_no'); ?></td>
                <td><?php echo $form->textField($model, 'tr_challan_no'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'tr_challan_date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'tr_challan_date', //attribute name
                        'id' => 'tr_challan_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'account_code_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                        $model, 'account_code_id', AccountCode::model()->accountList(), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'vds_total'); ?></td>
                <td><?php echo $form->textField($model, 'vds_total', ['value'=>$vds_total, 'readonly'=>true]); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'vds_remaining'); ?></td>
                <td><?php echo $form->textField($model, 'vds_remaining', ['value'=>$vds_remaining, 'readonly'=>true]); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                <td><?php echo $form->textField($model, 'amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'purpose'); ?></td>
                <td><?php echo $form->textArea($model, 'purpose',['rows'=>5, 'cols'=>50]); ?></td>
            </tr>
        </table>
    </fieldset>

  <fieldset class="tblFooters">
       <!--<span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>-->
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('id'=>'btnSubmit', 'onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $("#ajaxLoaderMR").show();
    }

    $("#btnSubmit").click(function () {

        if ($('#payment_date').val() == '') {
            alertify.alert("Please Select Adjustment Date");
            return false;
        }
        if ($('#Vds_amount').val() == '') {
            alertify.alert("Please Insert Amount");
            return false;
        }
        if ($('#Vds_amount').val() <= 0) {
            alertify.alert("VDS Amount can not be 0");
            return false;
        }
        if (parseFloat($('#Vds_vds_remaining').val())  < parseFloat($('#Vds_amount').val())) {
            alertify.alert("VDS Amount is exceeded");
            return false;
        }

        if ($('#Vds_challan_date').val() == '') {
            alertify.alert("Challan Date is not selected");
            return false;
        }

        var resultDate = parseFloat(date_diff_indays($('#Vds_challan_date').val(), $('#payment_date').val()));
        if(resultDate >= 0 && resultDate <= 180){
            //console.log('not expired');
        }else{
            //console.log('expired');
            alertify.alert("VDS only received within 6 months after Challan Date");
            return false;
        }

    });

    var date_diff_indays = function(date1, date2) {
        dt1 = new Date(date1);
        dt2 = new Date(date2);
        return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));
    }
</script>
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
</script>
<style>
    .hint-msg{
        font-size: 13px;
        color: #a0806f;
        line-height: 22px;
    }
    .formDiv input[type=password], .formDiv input[type=text], .formDiv select, .formDiv textarea  {
        width: 230px !important;
    }
</style>

<?php $this->endWidget(); ?>
