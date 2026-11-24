<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'stock-transfer-history-form',
            'enableAjaxValidation' => true,
        ));
$model->availableStock=$model->sent_qty;
echo $form->hiddenField($model, 'availableStock');
?>
<script>
    $(document).ready(function(){ 
        $("#StockTransferHistory_sent_qty").css("background-color", "#eeeeee");
        
        $("#StockTransferHistory_sent_qty").focus(function(){
            $(this).blur();             
        }); 
    });
</script>
<div class="formDiv">
    <fieldset>
        <legend>Stock Transfer Request</legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_from_store_id'); ?></td>
                <td><div class="receivedByDiv"><?php echo Stores::model()->storeName($model->sent_from_store_id); ?></div></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_qty'); ?></td>
                <td><?php echo $form->textField($model, 'sent_qty'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'sent_date'); ?></td>
                <td><div class="receivedByDiv"><?php echo $form->hiddenField($model, 'sent_date'); ?><?php echo $model->sent_date; ?></div></td>
            </tr>
             <tr>
                <td><?php echo $form->labelEx($model, 'received_to_store_id'); ?></td>
                <td><div class="receivedByDiv"><?php echo Stores::model()->storeName($model->received_to_store_id); ?></div></td>
            </tr>
            <tr>
            	<td><?php echo $form->labelEx($model, 'receive_location_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'receive_location_id', CHtml::listData(Location::model()->findAll(array('order' => 'title ASC')), 'id', 'title'), array(
                        'prompt' => 'Select',
                        //'id' => 'location_id_' . $i,
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'received_qty'); ?></td>
                <td><?php echo $form->textField($model, 'received_qty'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'received_qty'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'received_date'); ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'received_date',
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
//                        'htmlOptions' => array(
//                            'style' => 'float: left;
//                                        margin-top: 6px;
//                                        width: 85%;'
//                        ),
                    ));
                    ?>
                </td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'received_date'); ?></td>
            </tr>
        </table>
    </fieldset>
   <fieldset class="tblFooters">
       <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton('Receive', array('onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
