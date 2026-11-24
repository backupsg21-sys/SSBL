<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'other-req-dr-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend>Return Form</legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'r_qty'); ?></td>
                <td><?php echo $form->textField($model, 'r_qty'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'r_qty'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'r_date'); ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'r_date',
                        'options' => array(
                            'showAnim' => 'fold',
                            'showOn' => 'button',
                            'buttonText' => 'Date',
                            'buttonImageOnly' => true,
                            'buttonImage' => Yii::app()->theme->baseUrl . '/images/calendar.png',
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'htmlOptions' => array(
                            'style' => 'float: left;
                                        margin-top: 6px;
                                        width: 61%;'
                        ),
                    ));
                    ?>
                </td>               
            </tr>
             <tr>
                <td></td>
                <td><?php echo $form->error($model, 'r_date'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'return_password'); ?></td>
                <td><?php echo $form->passwordField($model, 'return_password', array('maxlength' => 255, 'style'=>'width:250px; color: #000')); ?></td>            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>
                	<?php echo $form->error($model, 'return_password'); ?>                    
                    
                </td>
            </tr>
        </table>
    </fieldset>

  <fieldset class="tblFooters">
       <span id="ajaxLoaderMR" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
        <?php echo CHtml::submitButton('Return', array('onclick'=>'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
       $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
