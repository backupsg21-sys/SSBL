<fieldset>
    <legend>Basic Informations</legend>
    <table>
        <tr>
            <td><?php echo $form->labelEx($model, 'emp_id_no'); ?></td>
            <td colspan="2"><?php echo $form->textField($model, 'emp_id_no', array('maxlength' => 255, 'style' => 'width: 99%;')); ?></td>
            <td><span class="heighlightSpan">Keep Members ID blank to auto generate!</span></td>
            <td><?php //echo $form->labelEx($model, 'device_id');   
                ?></td>
            <td><?php //echo $form->textField($model, 'device_id');   
                ?></td>
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
                        // 'ampm' => true,
                        'minDate' => '1900-01-01',
                        'dateFormat' => 'yy-mm-dd',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'width' => '100',
						'yearRange'=>'1900',
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
            <td><?php echo $form->labelEx($model, 'national_id_no'); ?></td>
            <td>
                <?php echo $form->textField($model, 'national_id_no'); ?>
                <?php echo $form->error($model, 'national_id_no'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'tin'); ?></td>
            <td>
                <?php echo $form->textField($model, 'tin', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'tin'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'mother_name'); ?></td>
            <td>
                <?php echo $form->textField($model, 'mother_name'); ?>
                <?php echo $form->error($model, 'mother_name'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'p_email'); ?></td>
            <td>
                <?php echo $form->textField($model, 'p_email'); ?>
                <?php echo $form->error($model, 'p_email'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'religion'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'religion', Lookup::items('religion'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'religion'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'address'); ?></td>
            <td>
                <?php echo $form->textArea($model, 'address'); ?>
                <?php echo $form->error($model, 'address'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'permanent_address'); ?></td>
            <td>
                <?php echo $form->textArea($model, 'permanent_address'); ?>
                <?php echo $form->error($model, 'permanent_address'); ?>
            </td>
            <td><?php echo $form->labelEx($model, 'blood_group'); ?></td>
            <td>
                <?php echo $form->dropDownList($model, 'blood_group', Lookup::items('blood_group'), array('prompt' => 'select')); ?>
                <?php echo $form->error($model, 'blood_group'); ?>
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
    </table>
</fieldset>