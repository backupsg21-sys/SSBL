<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stores-form',
    'action' => $this->createUrl('stores/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit'=>true),
));
?>
<div id="formResult" class="ajaxTargetDiv"></div>
<div id="formResultError" class="ajaxTargetDivErr"></div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Item' : 'Update Item'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'store_name'); ?></td>
                <td><?php echo $form->textField($model, 'store_name', array('maxlength'=>255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'store_name'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'location'); ?></td>
                <td><?php echo $form->textArea($model, 'location', array('rows'=>4, 'cols'=>11)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'location'); ?></td>
            </tr>
        </table>
        <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('stores/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#stores-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("stores-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#stores-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#stores-form #"+key+"_em_").show();
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
<style>
    .formDiv input[type=password], .formDiv input[type=text], .formDiv select, .formDiv textarea {
        width: 250px !important;
    }
</style>