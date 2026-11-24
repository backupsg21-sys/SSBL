<fieldset>
    <legend>Optional Fields</legend>
    <table style="float: left; width: 1200px;">
        <tr>                     
            <td style="width: 120px;"><?php echo $form->labelEx($model, 'pbrand'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'pbrand', CHtml::listData(PBrand::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>   
            <td><?php echo $form->labelEx($model, 'country_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'country_id', CHtml::listData(Countries::model()->findAll(array('order' => 'country ASC')), 'id', 'country'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>  
        </tr>
        <tr>                   
            <td></td>
            <td><?php echo $form->error($model, 'pbrand'); ?></td>  
            <td></td>
            <td><?php echo $form->error($model, 'country_id'); ?></td>  
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'pmodel'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'pmodel', CHtml::listData(PModel::model()->findAll(array('order' => 'id DESC')), 'id', 'name'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>
            <td><?php echo $form->labelEx($model, 'manufacturer_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'manufacturer_id', CHtml::listData(Manufacturers::model()->findAll(array('order' => 'id DESC')), 'id', 'manufacturer'), array(
                    'prompt' => 'Select',
                ));
                ?>
            </td>     
        </tr>
        <tr>  
            <td></td>
            <td><?php echo $form->error($model, 'pmodel'); ?></td> 
            <td></td>
            <td><?php echo $form->error($model, 'manufacturer_id'); ?></td>    
        </tr>
        <tr>
            <td></td>
            <td></td> 
            <td style="color: red;"><?php echo $form->labelEx($model, 'warranty'); ?></td>
            <td><?php echo $form->textField($model, 'warranty'); ?></td> 
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $form->error($model, 'warranty'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'size_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'size_id', CriteriaValues::model()->findAllSizeNames(), array(
                    'prompt' => 'Select',
                    'class' => 'unitSelectOpt',
                ));
                ?>
            </td> 
            <td><?php echo $form->labelEx($model, 'color_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'color_id', CriteriaValues::model()->findAllColorNames(), array(
                    'prompt' => 'Select',
                    'class' => 'unitSelectOpt',
                ));
                ?>
            </td> 
        </tr>
        <tr>
            <td></td>
            <td><?php echo $form->error($model, 'size_id'); ?></td>
            <td></td>
            <td><?php echo $form->error($model, 'color_id'); ?></td>
        </tr>         
        <tr>
            <td><?php echo $form->labelEx($model, 'weight'); ?></td>
            <td><?php echo $form->textField($model, 'weight'); ?></td> 
            <td><?php echo $form->labelEx($model, 'weight_unit_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'weight_unit_id', Units::model()->findAllWeightUnitNames(), array(
                    'prompt' => 'Select',
                    'class' => 'unitSelectOpt',
                ));
                ?>
            </td> 
        </tr>
        <tr>
            <td></td>
            <td><?php echo $form->error($model, 'weight'); ?></td>
            <td></td>
            <td><?php echo $form->error($model, 'weight_unit_id'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'measurement'); ?></td>
            <td><?php echo $form->textField($model, 'measurement', array('maxlength' => 255)); ?></td> 
            <td><?php echo $form->labelEx($model, 'measurement_unit_id'); ?></td>
            <td>
                <?php
                echo $form->dropDownList(
                        $model, 'measurement_unit_id', Units::model()->findAllMeasurementUnitNames(), array(
                    'prompt' => 'Select',
                    'class' => 'unitSelectOpt',
                ));
                ?>
            </td> 
        </tr>
        <tr>
            <td></td>
            <td><?php echo $form->error($model, 'measurement'); ?></td>
            <td></td>
            <td><?php echo $form->error($model, 'measurement_unit_id'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model, 'features'); ?></td>
            <td colspan="3"><?php //echo $form->textArea($model, 'features', array('rows' => 11, 'cols' => 25));        ?>
                <?php
                $this->widget('ext.editMe.widgets.ExtEditMe', array(
                    'model' => $model,
                    'attribute' => 'features',
                    'height' => '120',
                    //'width' => '120',
                    'resizeMode' => false,
                    'toolbar' => array(
                        array(
                            'BulletedList',
                        ),
                    ),
                ));
                ?>
            </td>                     
        </tr>
        <tr>
            <td></td>
            <td colspan="3"><?php echo $form->error($model, 'features'); ?></td>                    
        </tr>  
    </table>
</fieldset>