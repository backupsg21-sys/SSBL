<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'prod-correction-form',
            'action' => $this->createUrl('correction/editModePo'),
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
        <legend><?php echo ($model->isNewRecord ? 'Purchase Order Edit Mode' : 'Purchase Order Edit Mode'); ?></legend>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model, 'Po No'); ?></td>
                <td><?php echo $form->textField($model, 'sl_no', array('maxlength'=>255)); ?></td>
                <td> <?php
                    echo CHtml::ajaxSubmitButton('On', CHtml::normalizeUrl(array('correction/editModePo', 'render' => true)), array(
                        'dataType' => 'json',
                        'type' => 'post',
                        'success' => 'function(data) {
                        $("#ajaxLoader").hide();  
                                if(data.status=="success"){
                                    $("#formResult").fadeIn();
                                    $("#formResult").html("Edit Mode On Successfully.");
                                    $("#prod-correction-form")[0].reset();
                                    $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                                    $.fn.yiiGridView.update("prod-correction-grid", {
                                    data: $(this).serialize()
                                });
                                }else{
                                     $("#formResultError").html("Data Not Found. Please Enter Correct Po No");
                                 
                                }       
                            }',
                                    'beforeSend' => 'function(){                        
                            $("#ajaxLoader").show();
                         }'
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'sl_no'); ?></td>
            </tr>
        </table>
       <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    </fieldset>

</div>

<?php $this->endWidget(); ?>
