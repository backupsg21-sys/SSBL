<div class="container-fluid">

    <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'vehicle-info-file-form',
            'enableAjaxValidation' => true,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
    ?>

    <?php
        if (Yii::app()->user->hasFlash('success')) {
            echo '<div class="flash-notice">' . Yii::app()->user->getFlash('success') . '</div>';
        }
        if (Yii::app()->user->hasFlash('error')) {
            foreach(Yii::app()->user->getFlashes() as $key => $message) {
                echo '<div class="flash-error">' . $message[0] . '</div>';
            }

        }

        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            if ($key == 'counters') {
                continue;
            }
            echo "<div class='flash-{$key}'>{$message}</div>";
        }
    ?>

    <div class="panel panel-default">
    <div class="panel-heading">Upload Device File <font color="#485460">(Please upload only Excel 97-2003 Workbook (*.xls))</font></div>
    <div class="card">                
    <div class="card-body">
            <table>	
                <tr>
                    <td><?php echo $form->labelEx($model, 'product_file'); ?></td>
                    <td><?php echo $form->fileField($model, 'product_file', array('maxlength' => 255)); ?></td>
                </tr>    
                <tr>
                    <td>
                        <?php echo CHtml::submitButton("Upload"); ?>            
                    </td>
                    <td>
                        <?php echo CHtml::link('Process', array('/prodModels/processFileDataFinal'), array('class'=>'additionalBtn')); ?>

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    </div>


    <div class="panel panel-default" style="margin-top: 20px;">
        <div class="panel-heading">Excel File Must be in this Format</div>
        <div class="card">                
            <div class="card-body">
                <table border="1" style="width: 100%;">	
                    <tr>
                        <th style="padding: 4px 6px;text-align:center">Category</th>
                        <th style="padding: 4px 6px;text-align:center">Sub-Category</th>
                        <th style="padding: 4px 6px;text-align:center">Name</th>
                        <th style="padding: 4px 6px;text-align:center">HS Code</th>
                        <th style="padding: 4px 6px;text-align:center">Code</th>
                        <th style="padding: 4px 6px;text-align:center">Unit</th>
                        <th style="padding: 4px 6px;text-align:center">Manufacturer</th>
                        <th style="padding: 4px 6px;text-align:center">Country</th>
                        <th style="padding: 4px 6px;text-align:center">Min Order QTY</th>
                        <th style="padding: 4px 6px;text-align:center">Measurement</th>
                        <th style="padding: 4px 6px;text-align:center">Measurement Unit</th>
                        <th style="padding: 4px 6px;text-align:center">Weight</th>
                        <th style="padding: 4px 6px;text-align:center">Weight Unit</th>
                        <th style="padding: 4px 6px;text-align:center">Warrenty</th>
                        <th style="padding: 4px 6px;text-align:center">MRP</th>
						<th style="padding: 4px 6px;text-align:center">VAT</th>
                        <th style="padding: 4px 6px;text-align:center">SD</th>
                        <th style="padding: 4px 6px;text-align:center">CD</th>
                        <th style="padding: 4px 6px;text-align:center">RD</th>
                        <th style="padding: 4px 6px;text-align:center">AIT</th>
                        <th style="padding: 4px 6px;text-align:center">AT</th>
                        <th style="padding: 4px 6px;text-align:center">TTI</th>
                        <th style="padding: 4px 6px;text-align:center">EXD</th>
                    </tr>    
                </table>
            </div>
        </div>
    </div>


</div>


<style>
    input[type=submit], input[type=button], input[type=button], .custom-btn, a.additionalBtn {
        background: #67b0e0;
        color: white;
        font-size: 14px;
        margin-top: 5px;
        margin-bottom: 5px;
        margin-right: 6px;
        text-align: center;
        padding: 3px 10px !important;
        border: 2px solid #67b0e0;
        border-radius: 4px;
        width: 90px;
        height: 38px;
        vertical-align: middle;
        box-shadow: none;
        text-shadow: none;
    }
</style>