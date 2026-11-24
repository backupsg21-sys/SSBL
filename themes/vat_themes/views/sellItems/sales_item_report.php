<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/sellItems/salesItemReportView'),
));
$title = "Sales Report (Summary)";
$this->pageTitle = $title;
?>
<style>
    #start_date, #end_date, #sales_type, #customer_id_text,
    #SellItems_approve_type, #SellItems_business_type_id,
    #SellItems_sales_type,
    #SellItems_report_type{
        width:185px !important;
    }
    #start_date, #end_date, #customer_id_text {
        width:185px !important;
    }

</style>
<fieldset>
    <legend><?=$title?></legend>
    <table style="text-align: left; margin-bottom: 10px" class="search-tb">
        <tr>
            <td>SO No</td>
            <td width="200px">
                <input type="text" name="so_no" id="so_no"/>
            </td>
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
            <td></td>
        </tr>
        <tr style="padding-top: 20px; ">
            <td>Customer Name</td>
            <td>
                <input type="text" id="customer_id_text" onkeyup="clearCustomerId($(this).val())"/>
                <input type="hidden" name="customer_id" id="customer_id"/>
                <script>
                    $(function () {
                        $("#customer_id_text").autocomplete({
                            source: function (request, response) {
                                var search_customerName = request.term;
                                $.post('<?php echo Yii::app()->baseUrl ?>/customers/jquery_customerSearch', {"customerName": search_customerName},
                                    function (data) {
                                        response(data);
                                    }, "json");
                            },
                            minLength: 1,
                            select: function (event, ui) {
                                $("#customer_id_text").val(ui.item.label);
                                $("#customer_id").val(ui.item.value);
                                return false;
                            }
                        });
                    });
                </script>
            </td>
            <td>Business Type</td>
            <td>
                <?php echo $form->dropDownList($model, 'business_type_id', Lookup::items('business_type'), array('prompt' => 'select')); ?>
            </td>
            <td>Loacal/Export</td>
            <td>
                <?php
                $sales_type = array( 1 => 'Local', 2 => 'Export');
                echo $form->dropDownList($model, 'sales_type', $sales_type, ['prompt' => 'Select']);
                ?>
            </td>
            <td>Report Type</td>
            <td>
                <?php
                $report_type = array( 1 => 'All', 2 => 'VDS');
                echo $form->dropDownList($model, 'report_type', $report_type);
                ?>
            </td>
            <td>
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('SellItems/salesItemReportView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                        $("#ajaxLoader").hide(); 
                        $("#resultDiv").html(data.content);
                    }',
                    'beforeSend' => 'function(){ 
                        if($("#so_no").val()==""){
                            if($("#start_date").val()=="" || $("#end_date").val()==""){
                                alertify.alert("Please select SO or From date and To date!");
                                return false;
                            }
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
    function clearCustomerId(text){
        if(text=="")
            $("#customer_id").val('');
    }
</script>
<?php $this->endWidget(); ?>
<style>
    .search-tb td{
        /*width: 100px;*/
    }
</style>
