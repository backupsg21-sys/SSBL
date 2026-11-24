<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'units-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Unit Info' : 'Update Unit Info'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'category_id'); ?></td>
                <td>
                    <?php echo $form->dropDownList($model, 'category_id', Lookup::items('unit_category'), array('prompt' => 'select')); ?>
                    <?php echo $form->error($model, 'category_id'); ?>
                </td>            
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'label'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'label'); ?>
                    <?php echo $form->error($model, 'label'); ?>
                </td>            
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
