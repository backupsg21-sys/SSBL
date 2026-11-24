<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vds-receive-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit'=>true),
));
?>
<form id="vds-receive-form" method="post">
    <fieldset class="formDiv">
        <legend>VDS Receive Form</legend>
        <span class="hint-msg">*** Reports will generate with Adjustment Date</span>
        <table>
            <tr>
                <td width="150px">Adjustment Date</td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig2 = array(
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
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Challan No</td>
                <td><?php echo $form->textField($model, 'mushok_no'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'challan_date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig3 = array(
                        'model' => $model, //Model object
                        'attribute' => 'challan_date', //attribute name
                        'id' => 'challan_date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig3);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'customer_name'); ?></td>
                <td>
                    <input type="text" id="VdsPayment_customer_id_text"/>
                    <?php echo $form->hiddenField($model, 'customer_id'); ?>
                    <script>
                        $(function () {
                            $("#VdsPayment_customer_bin").val('');
                            $("#VdsPayment_customer_address").val('');
                            $("#VdsPayment_customer_id_text").autocomplete({
                                source: function (request, response) {
                                    var search_customerName = request.term;
                                    $.post('<?php echo Yii::app()->baseUrl ?>/customers/jquery_customerSearch', {"customerName": search_customerName},
                                        function (data) {
                                            response(data);
                                        }, "json");
                                },
                                minLength: 1,
                                select: function (event, ui) {
                                    $("#VdsPayment_customer_id_text").val(ui.item.label);
                                    $("#VdsPayment_customer_id").val(ui.item.value);
                                    $("#VdsPayment_customer_bin").val(ui.item.bin);
                                    $("#VdsPayment_customer_address").val(ui.item.company_address);
                                    return false;
                                }
                            });
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'customer_bin'); ?></td>
                <td><?php echo $form->textField($model, 'customer_bin', ['readonly'=>true, 'style'=>'background-color: #fdfbf9']); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'customer_address'); ?></td>
                <td><?php echo $form->textArea($model, 'customer_address', ['readonly'=>true, 'style'=>'background-color: #fdfbf9']); ?></td>
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
                <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                <td><?php echo $form->textField($model, 'amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'purpose'); ?></td>
                <td><?php echo $form->textArea($model, 'purpose', ['rows'=>4, 'cols'=>50]); ?></td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <!--<span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>-->
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('id'=>'btnSubmit', 'onclick'=>'loadingDivDisplay();', 'style'=>'float: left;')); ?>
    </fieldset>
</form>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }

    $("#btnSubmit").click(function () {


        if ($('#payment_date').val() == '') {
            alertify.alert("Please Select Adjustment Date");
            return false;
        }
        if ($('#challan_date').val() == '') {
            alertify.alert("Please Select Challan Date");
            return false;
        }
        if ($('#VdsPayment_customer_id_text').val() == '' || $('#VdsPayment_customer_id').val() == '') {
            alertify.alert("Please Enter Customer Name");
            return false;
        }
        if ($('#VdsPayment_certificate_no').val() == '') {
            alertify.alert("Please Enter Certificate No");
            return false;
        }
        if ($('#VdsPayment_account_code_id').val() == '') {
            alertify.alert("Please Select Account Code");
            return false;
        }
        if ($('#Vds_amount').val() == '') {
            alertify.alert("Please Enter Amount");
            return false;
        }

        var resultDate = parseFloat(date_diff_indays($('#challan_date').val(), $('#payment_date').val()));
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
</script><script>
    $('input').attr('autocomplete','off');
</script>
<style>
    .formDiv input[type=password], .formDiv input[type=text], .formDiv select, .formDiv textarea  {
        width: 270px !important;
    }
</style>

<?php $this->endWidget(); ?>
