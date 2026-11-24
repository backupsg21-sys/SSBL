<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'softwareUpdateForm',
            'enableAjaxValidation' => true,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>

<?php
    if (Yii::app()->user->hasFlash('success')) {
        echo '<div class="flash-notice">' . Yii::app()->user->getFlash('success') . '</div>';
    }
    if (Yii::app()->user->hasFlash('error')) {
        echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
    }
?>

<div class="formDiv">
    <fieldset>
        <legend>Upload ZIP File for Products <font color="red">(Please upload only ZIP File)</font></legend>
        <table>	

            <tr>
                <td><?php echo $form->labelEx($model, 'file_path'); ?></td>
                <td><?php echo $form->fileField($model, 'file_name', array('maxlength' => 255)); ?></td>
                <td>
                    <?php echo CHtml::submitButton("Update Software"); ?>
                </td>
            </tr>

        </table>
    </fieldset>
</div>
<?php $this->endWidget(); ?>
