
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'users-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New User' : 'Update User Info: ' . $model->username); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'employee_id'); ?></td>
                <td>
                    <input type="text" id="employee_id_text" value="<?php if($model->employee_id>0){echo Users::model()->fullNameOfThis($model->id);}?>"/>
					<?php echo $form->hiddenField($model, 'employee_id'); ?>
                    <?php
                    $empInfoArry = '';
                    $empInfos = Employees::model()->findAll(array('order' => 'full_name ASC'));
                    if ($empInfos) {
                        $empcount = count($empInfos);
                        $i = 1;
                        foreach ($empInfos as $empInfo) {
                            $value = $empInfo->id;
                            $label = CHtml::encode($empInfo->full_name);
                            $empInfoArry.='{value: ' . $value . ', label: "' . $label . '"}';
                            if ($i == $empcount)
                                $empInfoArry.='';
                            else
                                $empInfoArry.=',';
                            $i++;
                        }
                    }
                    ?>
                    <script>
                        var empInfoArry = [<?php echo $empInfoArry; ?>];
                        $(function () {
                            $( "#employee_id_text" ).autocomplete({
                                source: empInfoArry,
                                minLength: 1,
                                select: function(event, ui) {
                                    $("#employee_id_text").val(ui.item.label);
                                    $("#Users_employee_id").val(ui.item.value);
                                    return false;
                                }
                            });
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'employee_id'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'username'); ?></td>
                <td><?php echo $form->textField($model, 'username', array('maxlength' => 20)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'username'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'password'); ?></td>
                <td><?php echo $form->passwordField($model, 'password', array('maxlength' => 20, 'value' => '')); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'password'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'password2'); ?></td>
                <td><?php echo $form->passwordField($model, 'password2', array('maxlength' => 20, 'value' => '')); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'password2'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'status'); ?></td>
                <td>
                    <?php $activeStatus = array(1 => 'Active', 0 => 'Inactive'); ?>
                    <?php echo $form->dropDownList($model, 'status', $activeStatus) ?> 
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