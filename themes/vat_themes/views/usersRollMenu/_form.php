<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'usersRollMenu-form',
            'action' => $this->createUrl('usersRollMenu/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));	
?>
<div class="formDiv">
    <fieldset>
        <legend>Add New Users Roll Percent</legend>
        <table>   
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
                <td><?php echo $form->labelEx($model, 'name'); ?></td>
                <td><?php echo $form->textField($model, 'name'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'name'); ?></td>
            </tr> 
            <tr>
                <td><?php echo $form->labelEx($model, 'uri_name'); ?></td>
                <td><?php echo $form->textField($model, 'uri_name'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'uri_name'); ?></td>
            </tr>            
        </table>
    </fieldset>

    <fieldset class="tblFooters">
        <div id="ajaxLoader" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
        <?php
        echo CHtml::ajaxSubmitButton('Add', CHtml::normalizeUrl(array('usersRollMenu/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#usersRollMenu-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("usersRollMenu-grid", {
							data: $(this).serialize()
						});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#usersRollMenu-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#usersRollMenu-form #"+key+"_em_").show();
                        });
                    }       
                }',
            'beforeSend' => 'function(){                        
                $("#ajaxLoader").show();
             }'
        ));
        ?>
    </fieldset>
    <div id="formResult" class="ajaxTargetDiv"></div>
    <div id="formResultError" class="ajaxTargetDivErr"></div>
</div>

<?php $this->endWidget(); ?>
