<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'treasury-purpose-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit'=>true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend><?php echo ($model->isNewRecord ? 'Add New Treasury Purpose Info' : 'Update Treasury Purpose Info'); ?></legend>
        <table>
            <tr>
                <td width="90px"><?php echo $form->labelEx($model, 'nine_one_part'); ?></td>
                <td>
                    <?php
                    $data = Lookup::model()->findAll(array('condition' => "type= 'nine_one_part' "));
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
            <tr>
                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                <td><?php echo $form->textField($model, 'code'); ?></td>            
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'code'); ?></td>
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
<script type="text/javascript">
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey;
</script>

<?php $this->endWidget(); ?>
