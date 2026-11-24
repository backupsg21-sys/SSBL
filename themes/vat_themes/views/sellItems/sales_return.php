<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'debit-search-form',
    //'action' => Yii::app()->createUrl('/sellItems/salesReturn'),
    ));
?>
<fieldset>
    <legend>Search SO</legend>
    <table class="summaryTab" style="text-align: left">
        <tr>
            <td>SO-
                <?php
                echo $form->dropDownList(
                    $model, 'id', CHtml::listData(SellItems::model()->findAll(['condition' => 'approve_count = 2 ','order'=>'so_no desc']), 'id', 'so_no','','SO-'), array(
                    'prompt' => 'Select',
                    'style' => 'width:250px',
                ));?>
                &nbsp;&nbsp;
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('sellItems/salesReturn', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide();
                    window.location.replace("../sellItems/salesReturnForm/"+data);
                    //$("#resultDiv").html(data.content);
                }',
                'beforeSend' => 'function(){
                    if($("#SellItems_id").val()==""){
                        alertify.alert("Warning! Please select Sales Order No!");
                        return false;
                    }else{
                        $("#ajaxLoader").show();
                    }
                }',
                ));
                ?>
                <br>
                <br>
                <!--<b style="color:red;">This list is only for Un-approved SO. You can not return after approved.</b>-->
            </td>
        </tr>
    </table>
</fieldset>

<?php $this->endWidget(); ?>

<div id="resultDiv"></div>
<style>
.table.summaryTab tr, table.summaryTab tr td {
    border: 1px solid #fff;
}
</style>