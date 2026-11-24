<fieldset>
    <legend>Basic Informations</legend>
    <table>
        <tr>
            <td><?php echo $form->labelEx($model, 'emp_id_no'); ?></td>
            <td colspan="2"><?php echo $form->textField($model, 'emp_id_no', array('maxlength' => 255)); ?></td>
            <td><span class="heighlightSpan">Keep Members ID blank to auto generate!</span></td>
            <td><?php //echo $form->labelEx($model, 'device_id');   ?></td>
            <td><?php //echo $form->textField($model, 'device_id');   ?></td>
            <?php echo $form->error($model, 'emp_id_no'); ?>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'full_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'full_name'); ?>
                <?php echo $form->error($model, 'full_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'dob'); ?></td>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfigDob = array(
                    'model' => $model, //Model object
                    'attribute' => 'dob', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
//                        'ampm' => true,
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'width' => '100',
                    ) // jquery plugin options
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfigDob);
                ?>
                <?php echo $form->error($model, 'dob'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'email'); ?></td>
            <td>
                <?php echo $form->textField($model, 'email'); ?>
                <?php echo $form->error($model, 'email'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'gender'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'gender', Lookup::items('gender'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'gender'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'is_active'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'is_active', Lookup::items('is_active')); ?>
                <?php echo $form->error($model, 'is_active'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'marital_status'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'marital_status', Lookup::items('marital_status'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'marital_status'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'father_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'father_name'); ?>
                <?php echo $form->error($model, 'father_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'father_occupation'); ?></td>
            <td>
                <?php echo $form->textField($model, 'father_occupation'); ?>
                <?php echo $form->error($model, 'father_occupation'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'national_id_no'); ?></td>
            <td>
                <?php echo $form->textField($model, 'national_id_no'); ?>
                <?php echo $form->error($model, 'national_id_no'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'mother_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'mother_name'); ?>
                <?php echo $form->error($model, 'mother_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'mother_occopation'); ?></td>
            <td>
                <?php echo $form->textField($model, 'mother_occopation'); ?>
                <?php echo $form->error($model, 'mother_occopation'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'p_email'); ?></td>
            <td>
                <?php echo $form->textField($model, 'p_email'); ?>
                <?php echo $form->error($model, 'p_email'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'spouse_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'spouse_name', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'spouse_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'religion'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'religion', Lookup::items('religion'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'religion'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'blood_group'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'blood_group', Lookup::items('blood_group'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'blood_group'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'spouse_details'); ?></td>
            <td style="width:24.4%">
                <?php echo $form->textArea($model, 'spouse_details'); ?>
                <?php echo $form->error($model, 'spouse_details'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'address'); ?></td>
            <td>
                <?php echo $form->textArea($model, 'address'); ?>
                <?php echo $form->error($model, 'address'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'permanent_address'); ?></td>
            <td style="width:24%">
                <?php echo $form->textArea($model, 'permanent_address'); ?>
                <?php echo $form->error($model, 'permanent_address'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'contact_no'); ?></td>
            <td>
                <?php echo $form->textField($model, 'contact_no'); ?>
                <?php echo $form->error($model, 'contact_no'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'contact_no_home'); ?></td>
            <td>
                <?php echo $form->textField($model, 'contact_no_home'); ?>
                <?php echo $form->error($model, 'contact_no_home'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'contact_no_office'); ?></td>
            <td>
                <?php echo $form->textField($model, 'contact_no_office'); ?>
                <?php echo $form->error($model, 'contact_no_office'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'tin'); ?></td>
            <td>
                <?php echo $form->textField($model, 'tin', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'tin'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'bank_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'bank_name', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'bank_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'monthly_installment'); ?></td>
            <td>
                <?php echo $form->textField($model, 'monthly_installment', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'monthly_installment'); ?>
            </td>
        </tr>
        <tr>

            <td><?php echo $form->labelEx($model, 'opening_balance'); ?></td>
            <td>
                <?php echo $form->textField($model, 'opening_balance', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'opening_balance'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'reference_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'reference_name', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'reference_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'reference_no'); ?></td>
            <td>
                <?php echo $form->textField($model, 'reference_no', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'reference_no'); ?>
            </td>
        </tr>
    </table>
</fieldset>

<style>
    select {
        width: 90%;
    }
    input[type=text] {
        width: 86%;
    }
    textarea {
        height: 63px;
        resize: none;
    }
    fieldset legend {
        padding: 3px 5px !important;
    }

</style>