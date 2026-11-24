
<?php
$formSearch = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
        ));
?>
<div class="formDiv">
    <table class="summaryTab">
        <tr>
            <th>Model</th>
            <th>Brand</th>
            <th>Color</th>
            <th>Size</th>
        </tr>
        <tr>
            <td>
                <?php
                echo $formSearch->dropDownList(
                        $model, 'pmodel', CHtml::listData(PModel::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
            <td>
                <?php
                echo $formSearch->dropDownList(
                        $model, 'pbrand', CHtml::listData(PBrand::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
            <td>
                <?php
                echo $formSearch->dropDownList(
                        $model, 'color_id', CriteriaValues::model()->findAllColorNames(), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
            <td>
                <?php
                echo $formSearch->dropDownList(
                        $model, 'size_id', CriteriaValues::model()->findAllSizeNames(), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="4"><?php echo CHtml::submitButton('Search'); ?></td>
        </tr>
    </table>
</div>
<?php $this->endWidget(); ?>