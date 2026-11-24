<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'prod-brands-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Sub-Category' : 'Update Sub-Category'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'item_id'); ?></td>
                <td>
                    <?php
                    echo $form->dropDownList(
                            $model, 'item_id', CHtml::listData(ProdItems::model()->findAll(array('order'=>'item_name ASC')), 'id', 'item_name'), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'item_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'brand_name'); ?></td>
                <td><?php echo $form->textField($model, 'brand_name', array('maxlength'=>255)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'brand_name'); ?></td>
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
