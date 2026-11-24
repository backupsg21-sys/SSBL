<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'payment-receipt-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<?php
$condition = "issue_date!='0000-00-00' GROUP BY po_no";
$poData = PurchaseItems::model()->findAll(array('condition' => $condition,), 'id');
?>
<script>
    $(document).ready(function(){
        var poSuggest = [
<?php foreach ($poData as $pod): ?>
    <?php echo '"' . $pod->po_no . '"'; ?>,
<?php endforeach; ?>
        ];
        
        $( "#PaymentReceipt_order_no" ).autocomplete({
            minLength: 0,
            source: poSuggest
        });
    });
</script>
<div class="formDiv">
    <fieldset>
        <legend>Update these info</legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'supplier_id'); ?></td>
                <td>
                    <div class="receivedByDiv"><?php echo Suppliers::model()->supplierName($model->supplier_id); ?></div>
                    <?php echo $form->hiddenField($model, 'supplier_id'); ?>
                </td>
                <td></td>
                <td></td> 
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'supplier_id'); ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'order_no'); ?></td>
                <td><?php echo $form->textField($model, 'order_no'); ?></td> 
                <td>
                    <?php
                    echo CHtml::submitButton('Find', array(
                        'class' => 'findBtn',
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('paymentReceipt/amountOfThisOrder'),
                            'success' => 'function(data) {
                                            $("#cramountOfThisOrder").html(data.cramount);
                                            $("#dramountOfThisOrder").html(data.dramount);
                                        }',
                            'data' => array(
                                'order_no' => 'js:jQuery("#PaymentReceipt_order_no").val()',
                            ),
                            'beforeSend' => 'function(){
                                                   document.getElementById("cramountOfThisOrder").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #EEEEEE 100% 1px";   
                                                   document.getElementById("dramountOfThisOrder").style.background="url(' . Yii::app()->theme->baseUrl . '/images/ajax-loader.gif) no-repeat #EEEEEE 100% 1px";   
                                             }',
                            'complete' => 'function(){
                                                    document.getElementById("cramountOfThisOrder").style.background="#EEEEEE";
                                                    document.getElementById("dramountOfThisOrder").style.background="#EEEEEE";
                                             }',
                        ),
                            )
                    );
                    ?>
                </td>
                <td></td>  
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'order_no'); ?></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td>Total Cr</td>
                <td><div class="receivedByDiv" id="cramountOfThisOrder"></div></td> 
                <td>Total Dr</td>
                <td><div class="receivedByDiv" id="dramountOfThisOrder"></div></td>  
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'amount'); ?></td> 
                <td><?php echo $form->textField($model, 'amount'); ?></td>
                <td><?php echo $form->labelEx($model, 'date'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'date', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => '',
                        'options' => array(
//                        'ampm' => true,
                            'changeMonth'=>'true', 
                                'changeYear'=>'true',
                            'dateFormat' => 'yy-mm-dd',
                            'width' => '100',
                        ) // jquery plugin options
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td> 
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'amount'); ?></td>
                <td></td>
                <td><?php echo $form->error($model, 'date'); ?></td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
            <?php echo CHtml::submitButton('Add', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
