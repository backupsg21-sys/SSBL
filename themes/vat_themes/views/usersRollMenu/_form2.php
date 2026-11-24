<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'usersRollMenu-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
?>
<div class="formDiv">
    <fieldset>
        <legend>Update User Roll Menu Information</legend>
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
        <span id="ajaxLoaderMR" style="display: none; float: left;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></span>
            <?php echo CHtml::submitButton('Update', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay(){
        $("#ajaxLoaderMR").show();
    }
</script>
<?php $this->endWidget(); ?>
