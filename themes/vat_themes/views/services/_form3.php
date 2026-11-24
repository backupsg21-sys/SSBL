<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'services-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>

<div id="formResult" class="ajaxTargetDiv">
</div>
<div id="formResultError" class="ajaxTargetDivErr">
</div>

<div class="formDiv custro">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Services' : 'Update Services'); ?></legend>
        <table>
            <tr>
                <td style="width: 400px;">
                    <table style="width: 100%">
                        <tr>
                            <td width="120px"><?php echo $form->labelEx($model, 'name'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'name'); ?>
                                <?php echo $form->error($model, 'name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'code'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'code'); ?>
                                <?php echo $form->error($model, 'code'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'category_id'); ?></td>
                            <td>
                                <?php echo $form->dropDownList($model, 'category_id', Lookup::items('ServiceCategory'), array('prompt' => 'select')); ?>
                                <?php echo $form->error($model, 'category_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'unit_id'); ?></td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                        $model, 'unit_id', CHtml::listData(Units::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array(
                                    'prompt' => 'Select',
                                ));
                                ?>
                                <?php echo $form->error($model, 'unit_id'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'mrp'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'mrp'); ?>
                                <?php echo $form->error($model, 'mrp'); ?>
                            </td>
                        </tr>
                         <tr>
                            <td><?php echo $form->labelEx($model, 'purchase_price'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'purchase_price'); ?>
                                <?php echo $form->error($model, 'purchase_price'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sell_price'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sell_price'); ?>
                                <?php echo $form->error($model, 'sell_price'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'rebatable'); ?></td>
                            <td>
                                <?php echo $form->checkBox($model,'rebatable',array('value' => '1', 'uncheckValue'=>'0')); ?>
                                <?php echo $form->error($model, 'rebatable'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top">
                    <table style="width: 100%">
                        <tr>
                            <td><?php echo $form->labelEx($model, 'purchase_discount'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'purchase_discount'); ?>
                                <?php echo $form->error($model, 'purchase_discount'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sell_discouont'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sell_discouont'); ?>
                                <?php echo $form->error($model, 'sell_discouont'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'vat_calculation_method'); ?></td>
                            <td colspan="2">
                                <?php
                                    echo $form->dropDownList(
                                            $model, 'vat_calculation_method', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method'", 'order' => 'code ASC')), 'code', 'name'), array(
                                        //'prompt' => 'Select',
                                        'id' => 'vat_calculation_method'
                                    ));
                                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $form->labelEx($model, 'vat'); ?>
                            </td>
                            <td>
                                <?php
                                echo $form->dropDownList(
                                        $model, 'sub_vat_id', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order'=>'code ASC')), 'code', 'name'), array(
                                    'prompt' => 'Select',
                                    'id' => 'sub_vat_id',
                                ));
                                ?>
                                <?php echo $form->hiddenField($model, 'vat'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'sd'); ?></td>
                            <td>
                                <?php echo $form->textField($model, 'sd'); ?>
                                <?php echo $form->error($model, 'sd'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $form->labelEx($model, 'description'); ?></td>
                            <td>
                                <?php echo $form->textArea($model, 'description', array('maxlength' => 300, 'rows' => 6, 'cols' => 50, 'style'=>'height: 60px;')); ?>
                                <?php echo $form->error($model, 'description'); ?>
                            </td>
                        </tr>
                    </table>
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
<style>
    .custro input[type=text], .custro select, .custro textarea{
        width:250px;
        border: 1px solid #AAA;
    }
</style>
    <script>
        $("#sub_vat_id").change(function () {
            var dataVal = $("#sub_vat_id option:selected").text();
            $("#Services_vat").val(dataVal);
        });
        $("#vat_calculation_method").change(function () {
            var subVatId = $("#vat_calculation_method").val();
            $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/subVatOfVat', {"subVatId": subVatId},
                function (data) {
                    $("#sub_vat_id").html(data.subVatList);
                }, "json");
        });
    </script>
<?php $this->endWidget(); ?>