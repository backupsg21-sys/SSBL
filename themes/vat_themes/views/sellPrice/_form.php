<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'sell-price-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<script>
                $(document).ready(function(){ 
    
                    $("#SellPrice_model_id").css("background-color", "#EEEEEE");
                    $("#SellPrice_model_id").focus(function(){
                        $(this).blur();
                    });
                });
            </script>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add Sell Price' : 'Add Sell Price'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'model_id'); ?></td>
                <td>
                    <div class="receivedByDiv">
                        <?php echo $form->hiddenField($model, 'model_id');
                        $modelName = CHtml::encode(ProdModels::model()->modelName($model->model_id));
                        echo $modelName; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'model_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'sell_price'); ?></td>
                <td><?php echo $form->textField($model, 'sell_price'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'sell_price'); ?></td>
            </tr>
             <tr>
                <td><?php echo $form->labelEx($model, 'discount'); ?></td>
                <td><?php echo $form->textField($model, 'discount'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'discount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'is_active'); ?></td>
                <td><?php echo $form->dropDownList($model, 'is_active', Lookup::items('is_active')); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'is_active'); ?></td>
            </tr>
        </table>
    </fieldset>

  <fieldset class="tblFooters">
       <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
