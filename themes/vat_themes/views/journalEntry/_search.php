<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>
    <table>
        <tr>
            <td><?php echo $form->label($model, 'startDate'); ?></td>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfigStartDate = array(
                    'model' => $model, //Model object
                    'attribute' => 'startDate', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'style' => 'width: 181px;'
                    ),
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfigStartDate);
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label($model, 'endDate'); ?></td>
            <td>
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfigEndDate = array(
                    'model' => $model, //Model object
                    'attribute' => 'endDate', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'style' => 'width: 181px;'
                    ),
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfigEndDate);
                ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><?php echo CHtml::submitButton('Search'); ?></td>
        </tr>	
    </table>
<?php $this->endWidget(); ?>

</div><!-- search-form -->