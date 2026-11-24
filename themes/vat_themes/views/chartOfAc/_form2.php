<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'chart-of-ac-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New CA' : 'Update CA Info'); ?></legend>
        <table>      
            <tr>
                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                <td><?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'code'); ?></td>   
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'title'); ?></td>
                <td><?php echo $form->textField($model, 'title', array('maxlength' => 255)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'title'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'bank_or_cash'); ?></td>
                <td><?php echo $form->checkBox($model, 'bank_or_cash'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'bank_or_cash'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'opening_amount'); ?></td>
                <td>
                    <?php echo $form->dropDownList($model, 'transaction_type', LookupAc::items('transaction_type'), array('style'=>'width: 42px;')); ?>
                    <?php echo $form->textField($model, 'opening_amount'); ?>
                </td>    
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'opening_amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dep'); ?></td>
                <td><?php echo $form->textField($model, 'dep'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'dep'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dep_start_dt'); ?></td>
                <td>
                    <?php
                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                    $dateTimePickerConfig1 = array(
                        'model' => $model, //Model object
                        'attribute' => 'dep_start_dt', //attribute name
                        'mode' => 'date', //use "time","date" or "datetime" (default)
                        'language' => 'en-AU',
                        'options' => array(
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 181px;'
                        ),
                    );
                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                    ?>
                </td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'dep_start_dt'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'remarks'); ?></td>
                <td><?php echo $form->textField($model, 'remarks', array('maxlength' => 255)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'remarks'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'root'); ?></td>
                <td class="specialTd">
                    <?php ChartOfAc::model()->allRootsWithSubRoots($model->root, $model->id); ?>
                </td>          
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'root'); ?></td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
       <span class="ajaxLoaderMR" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $(".ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
