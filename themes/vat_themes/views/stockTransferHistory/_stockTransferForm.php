<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'stock-transfer-history-form',
            'enableAjaxValidation' => true,
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend>Stock Transfer Request</legend>
        <table>
            <?php
            $model->sent_from_store_id = $inventoryInfo->store_id;
            echo $form->hiddenField($model, 'sent_from_store_id');

            $model->availableStock = $availableStock;
            echo $form->hiddenField($model, 'availableStock');
            ?>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_from_store_id'); ?></td>
                <td><div class="receivedByDiv"><?php echo Stores::model()->storeName($inventoryInfo->store_id); ?></div></td>
            </tr>
            <tr>
                <td>Stocked Quantity</td>
                <td>
                    <div class="receivedByDiv"><?php echo $availableStock; ?></div>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'received_to_store_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'received_to_store_id', CHtml::listData(Stores::model()->findAll(), 'id', 'store_name'), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'received_to_store_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_qty'); ?></td>
                <td><?php echo $form->textField($model, 'sent_qty'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'sent_qty'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_date'); ?></td>
                <td>
                     <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'sent_date',
                        'options' => array(
                            'changeMonth'=>'true',
                                'changeYear'=>'true',
                            'showAnim' => 'fold',
                            //'showOn' => 'button',
                            //'buttonText' => 'Date',
                            //'buttonImageOnly'=>true,
                            //'buttonImage'=>Yii::app()->theme->baseUrl.'/images/calendar.png',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                                'autocomplete' => 'off'
//                            'style' => 'float: left;
//                                        margin-top: 6px;
//                                        width: 85%;'
                       ),
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'sent_date'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'remarks'); ?></td>
                <td><?php echo $form->textArea($model, 'remarks'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'remarks'); ?></td>
            </tr>
        </table>
    </fieldset>
    <fieldset class="tblFooters">
        <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
            <?php echo CHtml::submitButton('Send', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
