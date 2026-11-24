<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'units-form',
            'action' => $this->createUrl('units/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>


<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Unit Info' : 'Update Unit Info'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'category_id'); ?></td>
                <td>
                <?php 
                    
                    $unitCat = Lookup::items('unit_category');
                    unset($unitCat[1], $unitCat[2]);
                ?>
                    <?php echo $form->dropDownList($model, 'category_id', $unitCat, array('prompt' => 'select')); ?>
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
       <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader2.gif" /></div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('units/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#units-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("units-grid", {
		data: $(this).serialize()
	});
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#units-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#units-form #"+key+"_em_").show();
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
