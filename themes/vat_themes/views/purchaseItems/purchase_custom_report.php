<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/purchaseCustomReportView'),
));
$title = "Purchase Report (Details)";
$this->pageTitle = $title;
?>
<style>
    #start_date, #end_date, #local_import, #model_id_text,
    #PurchaseDetails_approve_type, #PurchaseDetails_supplier_type_id,
    #PurchaseDetails_vat_calculation_method, #PurchaseDetails_local_import,
    #PurchaseDetails_report_type{
        width:185px !important;
    }
    #start_date, #end_date, #model_id_text {
        width: 185px !important;
    }
</style>
<fieldset>
    <legend><?=$title?></legend>
    <table style="text-align: left; margin-bottom: 10px" class="search-tb">

        <tr>
            <td width="90px">From</td>
            <td width="200px">
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig1 = array(
                    'model' => $model, //Model object
                    'attribute' => 'start_date', //attribute name
                    'name' => 'start_date', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'dd-mm-yy',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                ?>
            </td>
            <td>To</td>
            <td width="200px">
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig2 = array(
                    'model' => $model, //Model object
                    'attribute' => 'end_date', //attribute name
                    'name' => 'end_date', //attribute name
                    'mode' => 'date', //use "time","date" or "datetime" (default)
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'dd-mm-yy',
                        'width' => '100',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>
            </td>
            <td>Staus</td>
            <td width="200px">
                <?php
                $approve_type = array( 2 => 'Approved', 1 => 'Not Approved');
                echo $form->dropDownList($model, 'approve_type', $approve_type, array('prompt' => 'Select'));
                ?>
            </td>
            <td>Goods Name</td>
            <td width="200px">
                <input type="text" id="model_id_text" onkeyup="clearModelId($(this).val())"/>
                <input type="hidden" name="model_id" id="model_id"/>
                <script>
                    $(function () {
                        $("#model_id_text").autocomplete({
                            source: function (request, response) {
                                var search_prodName = request.term;
                                $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jqueryProdSearch', {"search_prodName": search_prodName},
                                    function (data) {
                                        response(data);
                                    }, "json");
                            },
                            minLength: 1,
                            select: function (event, ui) {
                                $("#model_id_text").val(ui.item.label);
                                $("#model_id").val(ui.item.value);
                                return false;
                            }
                        });
                    });
                </script>
            </td>
            <td></td>
        </tr>
        <tr style="padding-top: 20px; ">
<!--            <td>Supplier Type</td>-->
<!--            <td>-->
<!--                --><?php //echo $form->dropDownList($model, 'supplier_type_id', Lookup::items('supplier_type'), array('prompt' => 'select')); ?>
<!--            </td>-->
            <td>VAT Type</td>
            <td>
                <?php echo $form->dropDownList($model, 'vat_calculation_method', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method'", 'order' => 'code ASC')), 'code', 'name'), array(
                    'prompt' => 'Select',
                ))
                ?>
            </td>
            <td>Purchase Type</td>
            <td>
                <?php echo $form->dropDownList($model, 'local_import', CHtml::listData(Lookup::model()->findAll(array('condition' => "type= 'local_import'", 'order' => 'code ASC')), 'code', 'name'), array(
                    'prompt' => 'Select',
                ))
                ?>
            </td>
            <td>Report Type</td>
            <td>
                <?php
                $report_type = array( 1 => 'All', 2 => 'VDS', 3 => 'Rebate');
                echo $form->dropDownList($model, 'report_type', $report_type);
                ?>
            </td>
            <td>
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/purchaseCustomReportView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){   
                if($("#start_date").val()=="" || $("#end_date").val()==""){
                    alertify.alert("Please select from date and to date!");
                    return false;
                }else{
                    $("#ajaxLoader").show();
                }
             }',
                ));
                ?>
            </td>
        </tr>
    </table>
    <div id="ajaxLoader" style="display: none;">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" />
    </div>
    <div id="resultDiv"></div>
</fieldset>
<script>
    function clearModelId(text){
        if(text=="")
            $("#model_id").val('');
    }
</script>
<?php $this->endWidget(); ?>
<style>
    .search-tb td{
        /*width: 100px;*/
    }
</style>
