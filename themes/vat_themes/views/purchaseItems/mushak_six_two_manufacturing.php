<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakSixTwoManufacturingView'),
        ));
$this->pageTitle = "Mushak 6.2 (Sales Book)";
?>
<style>
    #start_date, #end_date, #model_id_text{
        width:200px !important;
    }
</style>
<fieldset>
    <legend>Mushak 6.2 (Sales Book)</legend>
    <table style="text-align: left">
        <tr>
            <td style="border: 1px solid #fff;">
                From
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
                &nbsp;&nbsp;
                To
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
                &nbsp;&nbsp;
                Product Name

                <input type="text" id="model_id_text"/>
                <input type="hidden" name="model_id" id="model_id"/>
                <script>
                    $(function () {
                        $("#model_id_text").autocomplete({
                            source: function (request, response) {
                                var search_prodName = request.term;
                                $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/jqueryProdSearchFG', {"search_prodName": search_prodName},
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
                &nbsp;&nbsp;                
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakSixTwoManufacturingView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){   
                if($("#start_date").val()=="" || $("#end_date").val()==""){
                    alertify.alert("Please select from date & to date!");
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
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
    <div id="resultDiv"></div>
</fieldset>
<?php $this->endWidget(); ?>
<style>
    table.summaryTab tr, table.summaryTab tr td {
        border: 1px solid #fff !important;
    }
    #start_date, #end_date{
        width: 200px;
    }
</style>