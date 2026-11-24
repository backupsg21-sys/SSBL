<?php
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'treasury-purpose-form',
        'action' => $this->createUrl('treasurypurpose/create'),
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array('validateOnSubmit'=>true),
    ));
?>

<div id="formResult" class="ajaxTargetDiv"></div>
<div id="formResultError" class="ajaxTargetDivErr"></div>

<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Adjustment Purpose' : 'Update 9.1 Adjustment Settings'); ?></legend>
        <table>
            <tr>
                <td width="90px"><?php echo $form->labelEx($model, 'nine_one_part'); ?></td>
                <td>
                    <?php
                    $data = Lookup::model()->findAll(array('condition' => "type= 'nine_one_part' AND (code=5 OR code=6)"));
                    $dtArray = [];
                    foreach ($data as $dt) {
                        $dtArray[$dt->code] = $dt->name;
                    }
                    echo $form->dropDownList($model, 'nine_one_part', $dtArray, array('prompt' => 'select'));
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'nine_one_part'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'name'); ?></td>
                <td><?php echo $form->textField($model, 'name'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'name'); ?></td>
            </tr>
        </table>
       <div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader2.gif" /></div>
    </fieldset>

    <fieldset class="tblFooters">
        <?php

        echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('treasurypurpose/createPurposeFromOutSide', 'render' => true)), array(
            'dataType' => 'json',
            'type' => 'post',
            'success' => 'function(data) {
                $("#ajaxLoader").hide();  
                    if(data.status=="success"){
                        $("#formResult").fadeIn();
                        $("#formResult").html("Data saved successfully.");
                        $("#treasury-purpose-form")[0].reset();
                        $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                        $.fn.yiiGridView.update("treasury-purpose-grid", {
                            data: $(this).serialize()
                        });                                                
                    }else{
                        //$("#formResultError").html("Data not saved. Please solve the following errors.");
                        $.each(data, function(key, val) {
                            $("#treasury-purpose-form #"+key+"_em_").html(""+val+"");                                                    
                            $("#treasury-purpose-form #"+key+"_em_").show();
                        });
                    }       
                }',
        ), array('id' => 'btnSubmitPurpose'));
        ?>
    </fieldset>
</div>
<script>
    $("#btnSubmitPurpose").click(function () {
        if ($('#TreasuryPurpose_nine_one_part').val() == '') {
            alertify.alert("Please Select Part!");
            return false;
        }
        if ($('#TreasuryPurpose_name').val() == '') {
            alertify.alert("Please Enter Name!");
            return false;
        }
    });
</script>
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
</script>

<?php $this->endWidget(); ?>
