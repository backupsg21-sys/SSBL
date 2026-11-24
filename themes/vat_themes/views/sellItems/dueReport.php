<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'sell-items-form',
        ));
?>
<fieldset style="float: left; width: 98%;">
    <legend>Search Conditions (Due Report)</legend>
    <table class="summaryTab"> 
        <tr>
            <td align="right"><label>Up To : </label></td>
            <td align="left">
                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $dateTimePickerConfig2 = array(
                    'model' => $model,
                    'attribute' => 'dateTo',
                    'mode' => 'date',
                    'language' => 'en-AU',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'dateFormat' => 'yy-mm-dd',
                        'width' => '100',
                        'id' => 'dateTo',
                    )
                );
                $this->widget('CJuiDateTimePicker', $dateTimePickerConfig2);
                ?>
            </td>

            <td align="right"><label>Customer Name : </label></td>
            <td align="left">


                <input type="text" id="customer_id_text" style="width: 120px;"/>
                <?php echo $form->hiddenField($model, 'customer_id'); ?>
                <script>
                    $(function () {
                        $("#customer_id_text").autocomplete({
                            source: function (request, response) {
                                var customerName = request.term;
                                $.post('<?php echo Yii::app()->baseUrl ?>/customers/jquery_customerSearch', {"customerName": customerName},
                                    function (data) {
                                        response(data);
                                    }, "json");
                            },
                            minLength: 1,
                            select: function (event, ui) {
                                $("#customer_id_text").val(ui.item.label);
                                $("#SellItems_customer_id").val(ui.item.value);
                                return false;
                            }
                        });
                    });
                </script>	
            </td>

            <td>
                <?php
                echo CHtml::submitButton('Search', array(
                    'ajax' => array(
                        'type' => 'POST',
                        'url' => CController::createUrl('SellItems/dueReportView'),
                        'beforeSend' => 'function(){
                                if($("#SellItems_dateTo").val()==""){
                                    alertify.alert("Warning! Please select date range!");
                                    return false;
                                }else{
                                    $(".ajaxLoaderResultView").show();
                                }
                            }',
                        'complete' => 'function(){
                                $(".ajaxLoaderResultView").hide();
                            }',
                        'update' => '#resultDiv',
                    ),
                    'class' => 'custom_check_button',
                        )
                );
                ?>
            </td>
        </tr>
    </table>
</fieldset>
<?php $this->endWidget(); ?>

<fieldset>
    <legend>Due Report <div class="ajaxLoaderResultView" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv">
    </div>
</fieldset>
<div id="jsPart"></div>