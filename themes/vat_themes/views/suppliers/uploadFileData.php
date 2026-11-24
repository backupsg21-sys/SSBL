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
    <div class="panel-heading">Upload File <font color="#485460">(Please upload only Excel 97-2003 Workbook (*.xls))</font></div>
    <div class="card">                
    <div class="card-body">
            <table>	
                <tr>
                    <td><?php echo $form->labelEx($model, 'supplier_file'); ?></td>
                    <td><?php echo $form->fileField($model, 'supplier_file', array('maxlength' => 255)); ?></td>
                </tr>    
                <tr>
                    <td>
                        <?php echo CHtml::submitButton("Upload"); ?>            
                    </td>
                    <td>
                        <?php echo CHtml::link('Process', array('/suppliers/processFileDataFinal'), array('class'=>'additionalBtn')); ?>

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
                        <th style="padding: 4px 6px;text-align:center">Name</th>
                        <th style="padding: 4px 6px;text-align:center">Address</th>
                        <th style="padding: 4px 6px;text-align:center">Phone</th>
                        <th style="padding: 4px 6px;text-align:center">Email</th>
                        <th style="padding: 4px 6px;text-align:center">Type(Local/Foreign)</th>
                        <th style="padding: 4px 6px;text-align:center">Web Address</th>
                        <th style="padding: 4px 6px;text-align:center">Business Type</th>
                        <th style="padding: 4px 6px;text-align:center">Code</th>
                        <th style="padding: 4px 6px;text-align:center">Pin No</th>
                        <th style="padding: 4px 6px;text-align:center">Nationality</th>
                        <th style="padding: 4px 6px;text-align:center">Mobile No</th>
                        <th style="padding: 4px 6px;text-align:center">Description</th>
                        <th style="padding: 4px 6px;text-align:center">BIN</th>
                        <th style="padding: 4px 6px;text-align:center">NID</th>
                        <th style="padding: 4px 6px;text-align:center">Bank</th>
                        <th style="padding: 4px 6px;text-align:center">Bank Acc Name</th>
                        <th style="padding: 4px 6px;text-align:center">Bank Acc No</th>
                        <th style="padding: 4px 6px;text-align:center">Opening Amount</th>
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