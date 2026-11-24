<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'employees-form',
    'action' => $this->createUrl('employees/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<?php
if(isset($errores))
    print_r($errores);
?>
<div class="formDiv">
    <fieldset>
        <?php
        $this->widget('CTabView', array(
            'tabs' => array(
                'tab1' => array(
                    'title' => 'Basic',
                    'view' => '_employeeBasic',
                    'data' => array(
                        'model' => $model,
                        'form' => $form,
                    ),
                ),
                'tab2' => array(
                    'title' => 'Official',
                    'view' => '_employeeOfficial',
                    'data' => array(
                        'model' => $model,
                        'form' => $form,
                    ),
                ),
                'tab7' => array(
                    'title' => 'Photo',
                    'view' => '_employeePhoto',
                    'data' => array(
                        'model' => $model,
                        'form' => $form,
                    ),
                ),
            ),
        ));
        ?>
    </fieldset>
    <fieldset class="tblFooters">
        <div id="ajaxLoader" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('employees/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    if(data.status=="success"){
                        $("#formResult").html("Data saved successfully.");
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
						location.reload();
                    }
                    else{
                        $("#formResultError").html("Data not saved. Please solve the above errors.");
                        $.each(data, function(key, val) {
                            $("#employees-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#employees-form #"+key+"_em_").show();
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
<div id="formResult" class="ajaxTargetDiv"></div>
<div id="formResultError" class="ajaxTargetDivErr"></div>
<style>
    .yiiTab ul.tabs {
        font: 12px Verdana,sans-serif;
    }
    fieldset{
        border: none;
    }
    textarea{
        width: 190px;
    }
</style>
<?php $this->endWidget(); ?>
