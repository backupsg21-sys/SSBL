<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'users-form',
            'action' => $this->createUrl('users/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>

<?php //die($actionFromController);?>

<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New User' : 'Update User Info: ' . $model->username); ?></legend>
         <table>            
            <tr>
                <td><?php echo $form->labelEx($model, 'employee_id'); ?></td>
                <td>
                    <input type="text" id="employee_id_text"/>
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
                <td><?php echo $form->labelEx($model, 'usersroll_id'); ?></td>
                <td>
					<?php
                    echo $form->dropDownList(
                        $model, 'usersroll_id', CHtml::listData(UsersRoll::model()->findAll(array("order"=>"name ASC")), "id", "name"), array(
                        'prompt' => 'Select',
                    ));
                    ?>
                </td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'usersroll_id'); ?></td>
            </tr> 
            <tr>
                <td><?php echo $form->labelEx($model, 'username'); ?></td>
                <td><?php echo $form->textField($model, 'username', array('maxlength'=>20)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'username'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'password'); ?></td>
                <td><?php echo $form->passwordField($model, 'password', array('maxlength'=>20)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'password'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'password2'); ?></td>
                <td><?php echo $form->passwordField($model, 'password2', array('maxlength'=>20)); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'password2'); ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <div id="ajaxLoader" style="display: none;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('users/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#users-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("users-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#users-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#users-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
</div>

<?php $this->endWidget(); ?>