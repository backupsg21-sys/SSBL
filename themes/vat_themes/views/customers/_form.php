<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customers-form',
            'action' => $this->createUrl('customers/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div id="formResult" class="ajaxTargetDiv"></div>
<div id="formResultError" class="ajaxTargetDivErr"></div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Customer Info' : 'Update Customer Info'); ?></legend>
        <table style="width:100%">
            <tr>
                <td style="width: 460px">
                    <table style="width:100%">
                        <tr>
                            <td width="140px"><?php echo $form->labelEx($model, 'company_name'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'company_name', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'company_name'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td> <?php echo $form->labelEx($model, 'company_address'); ?></td>
                            <td>
                                <?php echo $form->textArea($model, 'company_address', array('rows' => 6, 'cols' => 50)); ?>
                                <?php echo $form->error($model, 'company_address'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'company_contact_no'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'company_contact_no', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'company_contact_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'mobile_no'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'mobile_no', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'mobile_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'company_email'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'company_email', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'company_email'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'company_web'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'company_web', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'company_web'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'business_type_id'); ?></td>
                            <td>
                                <?php echo $form->dropDownList($model, 'business_type_id', Lookup::items('business_type'), array('prompt' => 'select')); ?>
                                <?php echo $form->error($model, 'business_type_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'code'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'code', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'code'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top">
                    <table style="width:100%">
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'pin_number'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'pin_number', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'pin_number'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="120px"> <?php echo $form->labelEx($model, 'nationality'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'nationality', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'nationality'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'description'); ?></td>
                            <td>
                                <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
                                <?php echo $form->error($model, 'description'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'bin'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'bin', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'bin'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'bank_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                    $model, 'bank_id', CHtml::listData(ChartOfAc::model()->findAll(['condition' => 'root = 157','order'=>'title asc']), 'id', 'title'), array(
                                    'prompt' => 'Select',
                                ));
                                ?>
                                <?php echo $form->error($model, 'bank_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'bank_ac_name'); ?></td>
                            <td>
                               <?php echo $form->textField($model, 'bank_ac_name', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'bank_ac_name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td> <?php echo $form->labelEx($model, 'bank_ac_no'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'bank_ac_no', array('maxlength' => 255)); ?>
                                <?php echo $form->error($model, 'bank_ac_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'opening_amount'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'opening_amount'); ?>
                                <?php echo $form->error($model, 'opening_amount'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
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
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('customers/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#customers-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("customers-grid", {
                                data: $(this).serialize()
                        });
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#customers-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#customers-form #"+key+"_em_").show();
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
<style>
    .formDiv input[type=text]{
        width:260px;
    }
    .formDiv textarea{
        width:260px;
        border:1px solid #AAA;
    }
    .formDiv select{
        width:260px;
    }
</style>

<?php $this->endWidget(); ?>
