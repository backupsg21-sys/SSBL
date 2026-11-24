<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'your-company-form',
            'action' => $this->createUrl('yourCompany/create'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>

<div id="formResult" class="ajaxTargetDiv">

</div>
<div id="formResultError" class="ajaxTargetDivErr">

</div>
<style>
	div.billDiv{
		float: left;
		width: 45%;
		height: 200px;
		padding: 5px;
	}
	hr{
		border: 1px solid #000000;
	}
	.listtable{border-collapse: collapse;color: #000000;}
	.listtable th, .listtable td{border: 1px solid #999999; font-size:13px; font-family:Arial, Helvetica, sans-serif; padding:4px 10px; text-align:center;}
	.listtable th{ background:#D2D2D2;}
	.tabledata td, .tabledata th{ font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:normal; line-height:15px; padding:3px 5px; vertical-align:top; margin:0px;}
	.ollist { padding:0 0 0 10px; margin:0}
	.ollist li { list-style:none; font-family:Arial, Helvetica, sans-serif; font-weight:normal; line-height:15px; padding:2px 0px}
</style>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Company Info' : 'Update Company Info'); ?></legend>

        <table class="form-box">
            <tr>
                <td style="width:340px;">
                    <table>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'company_name'); ?></td>
                            <td><?php echo $form->textField($model, 'company_name', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'company_name'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'location'); ?></td>
                            <td><?php echo $form->textField($model, 'location', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'location'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'road'); ?></td>
                            <td><?php echo $form->textField($model, 'road'); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'road'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'house'); ?></td>
                            <td><?php echo $form->textField($model, 'house', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'house'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'contact'); ?></td>
                            <td><?php echo $form->textField($model, 'contact', array('maxlength' => 20)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'contact'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'email'); ?></td>
                            <td><?php echo $form->textField($model, 'email', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'email'); ?></td>
                        </tr>

                        <tr>
                            <td><?php echo $form->labelEx($model, 'web'); ?></td>
                            <td><?php echo $form->textField($model, 'web', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'web'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'vat_regi_no'); ?></td>
                            <td><?php echo $form->textField($model, 'vat_regi_no', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'vat_regi_no'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'tin'); ?></td>
                            <td><?php echo $form->textField($model, 'tin', array('maxlength' => 40)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'tin'); ?></td>
                        </tr>
                        <tr>
                            <td width="130px"><?php echo $form->labelEx($model, 'is_active'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'is_active', Lookup::items('is_active'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'is_active'); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top;width: 350px">
                    <table>
                        <tr>
                            <td>
                                <div id="ajaxLoader" style="display: none;">
                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'registration_nature'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'registration_nature', Lookup::items('registration_nature'), array('prompt' => 'select'));
                                ?>
                                <?php echo $form->error($model, 'registration_nature'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'registration_criteria'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'registration_criteria', Lookup::items('registration_criteria'), array('prompt' => 'select'));
                                ?>
                                <?php echo $form->error($model, 'registration_criteria'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'business_type'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'business_type', Lookup::items('business_type'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'business_process'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'business_process', Lookup::items('business_process'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'company_vat_type'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList($model, 'company_vat_type', Lookup::items('company_vat_type'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'opening_vat'); ?></td>
                            <td><?php echo $form->textField($model, 'opening_vat', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'opening_vat'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'opening_sd'); ?></td>
                            <td><?php echo $form->textField($model, 'opening_sd', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'opening_sd'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'previous_month_vat'); ?></td>
                            <td><?php echo $form->textField($model, 'previous_month_vat', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'previous_month_vat'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'previous_month_sd'); ?></td>
                            <td><?php echo $form->textField($model, 'previous_month_sd', array('maxlength' => 50)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'previous_month_sd'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'company_sales_vat'); ?></td>
                            <td><?php echo $form->textField($model, 'company_sales_vat', array('maxlength' => 40)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->error($model, 'company_sales_vat'); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top"></td>
            </tr>
        </table>
    </fieldset>
    <fieldset class="tblFooters">
        <?php
        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('yourCompany/create', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                    $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResultError").hide();
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#your-company-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("your-company-grid", {
		data: $(this).serialize()
	});
                    }else{
                        $("#formResultError").html("Data not saved. Please input required field.");
                        $.each(data, function(key, val) {
                            $("#your-company-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#your-company-form #"+key+"_em_").show();
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
.form-box select {
    width:190px;
}
</style>
