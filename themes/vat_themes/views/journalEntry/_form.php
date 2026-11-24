<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'journal-entry-form',
    'action' => $this->createUrl('journalEntry/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>

<div class="formDiv">
    <fieldset>
        <legend>Adjustment Voucher</legend>
        <table style="width:80%">
            <tr>
                <td style="width: 10%">
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td><?php echo $form->labelEx($model, 'entry_date'); ?></td>
                                <td>
                                    <?php
                                    Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                                    $dateTimePickerConfig1 = array(
                                        'model' => $model, //Model object
                                        'attribute' => 'entry_date', //attribute name
                                        'mode' => 'date', //use "time","date" or "datetime" (default)
                                        'language' => 'en-AU',
                                        'options' => array(
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd',
                                        ),
                                        'htmlOptions' => array(
                                            'value' => date('Y-m-d'),
                                        ),
                                    );
                                    $this->widget('CJuiDateTimePicker', $dateTimePickerConfig1);
                                    ?>
                                    <?php echo $form->error($model, 'entry_date'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'entry_no'); ?></td>
                                <td>
                                    <div id="orderNo" class="receivedByDiv" style="width:252px;">Will Auto Generate
                                    </div>
                                    <?php echo $form->error($model, 'entry_no'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><?php echo $form->labelEx($model, 'voucher_type'); ?></td>
                                <td>
                                    <?php
                                    $dataarray = array(7 => 'JOURNAL', 8 => 'CONTRA', 9 => 'RECEIPT', 10 => 'PAYMENT', 11 => 'CONTRA RECEIPT', 12 => 'CONTRA PAYMENT', 13 => 'CONTRA (BANK TO BANK)');
                                    echo $form->dropDownList($model, 'voucher_type', $dataarray, array('prompt' => 'select'));
                                    ?>
                                    <?php echo $form->error($model, 'voucher_type'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td><?php echo $form->labelEx($model, 'ca_id'); ?></td>
                                <td>
                                    <input type="text" id="ca_label" name="ca_label"/>
                                    <?php echo $form->hiddenField($model, 'ca_id'); ?>
                                    <?php
                                    $local_source = "";
                                    $caWithNoChild = ChartOfAc::model()->caWithNoChild();
                                    if ($caWithNoChild) {
                                        $count = count($caWithNoChild);
                                        $i = 1;
                                        foreach ($caWithNoChild as $cwnc) {
                                            $value = $cwnc->id;
                                            $label = $cwnc->title;
                                            $local_source .= '{value: ' . $value . ', label: "' . $label . '"}';
                                            if ($i == $count)
                                                $local_source .= '';
                                            else
                                                $local_source .= ',';
                                            $i++;
                                        }
                                    }
                                    ?>
                                    <script>
                                        var local_source = [<?php echo $local_source; ?>];


                                        $(function () {
                                            $("#ca_label").autocomplete({
                                                source: local_source,
                                                minLength: 1,
                                                select: function (event, ui) {
                                                    $("#ca_label").val(ui.item.label);
                                                    $("#JournalEntry_ca_id").val(ui.item.value);
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <?php echo $form->error($model, 'ca_id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'amount'); ?></td>
                                <td>
                                    <?php echo $form->dropDownList($model, 'transaction_type', LookupAc::items('transaction_type'), array('style' => 'width: 42px;')); ?>
                                    <?php echo $form->textField($model, 'amount', array('style' => 'width:215px', 'onkeypress' => 'return isNumberKey(event);')); ?>
                                    <?php echo $form->error($model, 'transaction_type'); ?>
                                    <?php echo $form->error($model, 'amount', array()); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><?php echo $form->labelEx($model, 'narration'); ?></td>
                                <td>
                                    <?php echo $form->textArea($model, 'narration', array('style' => 'width: 256px;')); ?>
                                    <?php echo $form->error($model, 'narration'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="text-align: left;">Total</td>
                                            <td><?php echo $form->textField($model, 'total_dr', array('style' => 'width: 124px; background: -moz-linear-gradient(center top , #3F653F, #1A1A1A) repeat scroll 0 0 rgba(0, 0, 0, 0); color: #000; font-weight: bold; text-align: center;')); ?></td>
                                            <td><?php echo $form->textField($model, 'total_cr', array('style' => 'width: 124px; background: -moz-linear-gradient(center top , #3F653F, #1A1A1A) repeat scroll 0 0 rgba(0, 0, 0, 0); color: #000; font-weight: bold; text-align: center;')); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Difference</td>
                                            <td colspan="2"><?php echo $form->textField($model, 'drcrdiff', array('style' => 'width: 256px; background: -moz-linear-gradient(center top , #3F653F, #1A1A1A) repeat scroll 0 0 rgba(0, 0, 0, 0); color: #000; font-weight: bold; text-align: center;')); ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <?php
                                    echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('journalEntry/create', 'render' => true)), array(
                                        'dataType' => 'json',
                                        'type' => 'post',
                                        'success' => 'function(data) {
                                        $("#ajaxLoader").hide();  
                                        if(data.status=="success"){
                                            $("#JournalEntry_total_dr").val(0);
                                            $("#JournalEntry_total_cr").val(0);
                                            newArr.length=0;
                                            $("#tbl tr.cartList").remove();
                                            sl=0;
                                            $("#formResultError").hide();
                                            $("#formResult").fadeIn();
                                            $("#formResult").html("Data saved successfully.");
                                            $("#journal-entry-form")[0].reset();
                                            $("#formResult").animate({opacity:1.0},1000).fadeOut("slow");
                                            
                                            $("#ajaxLoaderReport").show(); 
                                            $("#poReportDialogBox").dialog("open");
                                            $("#AjFlashReportPo").html(data.voucher).show();
                                            $("#ajaxLoaderReport").hide();

                                            //$.fn.yiiGridView.update("journal-entry-grid", {
                                              //  data: $(this).serialize()
                                            //});
                                        }else if(data.status=="errorBalance"){
                                            $("#formResultError").show();
                                            $("#formResultError").html("Debit & Credit Balance is not equal! Or check if you added journal entries to the list!");
                                        }else{
                                            $("#formResult").show();
                                            $("#formResultError").html("Data not saved. Please solve the above errors!");
                                            $.each(data, function(key, val) {
                                                $("#journal-entry-form #"+key+"_em_").html(""+val+"");                                                    
                                                $("#journal-entry-form #"+key+"_em_").show();
                                            });
                                        }       
                                    }',
                                        'beforeSend' => 'function(){
                                        if($("#JournalEntry_drcrdiff").val()!=0){
                                                alertify.alert("Debit & Credit Balance is not equal!");
                                                return false;
                                            }else if($("#JournalEntry_total_dr").val()==0 || $("#JournalEntry_total_dr").val()==0){
                                                 alertify.alert("Please add journal entries to the list!");
                                                 return false;
                                            }else{
                                                 
                                            }
                                            $("#ajaxLoader").show();
                                    }'
                                    ), array('class' => 'add-more-btn'));
                                    ?>
                                </td>
                            </tr>

                        </table>
                    </fieldset>
                </td>
                <td style="width: 2%">
                    <table>
                        <tr>
                            <td>
                                <input class="addRight" title="Add" type="button" value="" onclick="AddNew()"/>
                            </td>
                        </tr>

                    </table>
                </td>
                <td style="width: 50%">
                    <fieldset style="width: 800px; float: left; overflow: scroll; height: 400px;" class="someStyle">
                        <legend>Added List</legend>
                        <table id="tbl" class="prodAddedTab">
                            <tr>
                                <th style="width: 10px;">SL</th>
                                <th>Account Title</th>
                                <th style="width: 115px;">Dr (Amount)</th>
                                <th style="width: 115px;">Cr (Amount)</th>
                                <th style="width: 32px;">ACTION</th>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="2">
                    <div id="ajaxLoader" style="display: none; float: left;"><img
                                src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader-bar.gif"/></div>
                    <div id="jsPart"></div>
                    <div id="formResult" class="ajaxTargetDiv" style="color: white; font-weight: bold;"></div>
                    <div id="formResultError" class="ajaxTargetDivErr" style="color: white; font-weight: bold;"></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset class="tblFooters">
        <div id="ajaxLoaderReport" style="display: none;"><img
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></div>
        <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'poReportDialogBox',
            'options' => array(
                'title' => "Voucher",
                'autoOpen' => false,
                'modal' => true,
                'width' => 1030,
                'resizable' => false,
            ),
        ));
        ?>
        <div id='AjFlashReportPo' style="display:none;"></div>
        <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
    </fieldset>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#JournalEntry_total_dr").css("background-color", "#D7D7D7");
        $("#JournalEntry_total_dr").focus(function () {
            $(this).blur();
        });
        $("#JournalEntry_total_cr").css("background-color", "#D7D7D7");
        $("#JournalEntry_total_cr").focus(function () {
            $(this).blur();
        });
        $("#JournalEntry_drcrdiff").css("background-color", "#D7D7D7");
        $("#JournalEntry_drcrdiff").focus(function () {
            $(this).blur();
        });
    });
    var newArr = [];
    var totalDr = 0;
    var totalCr = 0;
    var nowDrCrDiff = 0;
    var sl = 0;

    $('#JournalEntry_total_dr').val(totalDr);
    $('#JournalEntry_total_cr').val(totalCr);
    $('#JournalEntry_drcrdiff').val(nowDrCrDiff);

    $.fn.sumValues = function () {
        var sum = 0;
        this.each(function () {
            if ($(this).is(':input')) {
                var val = $(this).val();
            } else {
                var val = $(this).text();
            }
            sum += parseFloat(('0' + val).replace(/[^0-9-\.]/g, ''), 10);
        });
        return sum;
    };

    function calFnc(count) {
        $('#drAmount' + count).bind('keyup', function () {
            $('#JournalEntry_total_dr').val($('input.totalDr').sumValues());
            nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
            $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
        });
        $('#crAmount' + count).bind('keyup', function () {
            $('#JournalEntry_total_cr').val($('input.totalCr').sumValues());
            nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
            $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
        });

    }


    $("#tbl td input.drDltBtn").live("click", function () {
        var idCounter = $(this).attr("id");
        totalDr = $("#JournalEntry_total_dr").val() - $('#drAmount' + idCounter).val();
        $("#JournalEntry_total_dr").val(totalDr);

        var srow = $(this).parent().parent();
        srow.remove();
        $("#tbl td.sno").each(function (index, element) {
            $(element).text(index + 1);
        });

        newArr[idCounter] = 0;

        nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
        $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
    });
    $("#tbl td input.crDltBtn").live("click", function () {
        var idCounter = $(this).attr("id");
        totalCr = $("#JournalEntry_total_cr").val() - $('#crAmount' + idCounter).val();
        $("#JournalEntry_total_cr").val(totalCr);

        var srow = $(this).parent().parent();
        srow.remove();
        $("#tbl td.sno").each(function (index, element) {
            $(element).text(index + 1);
        });

        newArr[idCounter] = 0;

        nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
        $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
    });

    function AddNew() {
        if ($("#JournalEntry_ca_id").val() == "") {
            alertify.alert("Please select an account title!");
        } else {
            if ($.inArray($("#JournalEntry_ca_id").val(), newArr) > -1) {
                alertify.alert("Already added!");
            } else {
                add();
                newArr[sl] = $("#JournalEntry_ca_id").val();
                $("#ca_label").val("");
                $("#JournalEntry_transaction_type").val(4);
                $("#ca_label").focus();
            }
        }
    }

    function add() {
        sl++;
        var slNumber = $('#tbl tr').length;

        var appendTxt = "<tr class='cartList'>" +
            "<input type='hidden' name='JournalEntry[temp_ca_id][]' value='" + $("#JournalEntry_ca_id").val() + "'>" +
            "<input type='hidden' name='JournalEntry[temp_transaction_type][]' value='" + $("#JournalEntry_transaction_type").val() + "'>" +
            "<td class='sno'>" +
            slNumber +
            "</td><td style='text-align: left;'>" +
            $("#ca_label").val() + "</td>";
        if ($("#JournalEntry_transaction_type").val() == 3) {
            appendTxt = appendTxt + "<td><input id='drAmount" + sl + "' class='totalDr' type='text' name='JournalEntry[temp_amount][]' value='" + $("#JournalEntry_amount").val() + "'></td><td></td>";
            appendTxt = appendTxt + "<td><input title=\"remove\" id='" + sl + "' type=\"button\" class=\"rdelete dltBtn drDltBtn\"/></td></tr>";
        } else {
            appendTxt = appendTxt + "<td></td><td><input id='crAmount" + sl + "' class='totalCr' type='text' name='JournalEntry[temp_amount][]' value='" + $("#JournalEntry_amount").val() + "'></td>";
            appendTxt = appendTxt + "<td><input title=\"remove\" id='" + sl + "' type=\"button\" class=\"rdelete dltBtn crDltBtn\"/></td></tr>";
        }
        $("#tbl tr:last").after(appendTxt);

        calFnc(sl);

        $('#JournalEntry_total_dr').val($('input.totalDr').sumValues());
        $('#JournalEntry_total_cr').val($('input.totalCr').sumValues());

        nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
        $('#JournalEntry_drcrdiff').val(nowDrCrDiff);

    }
</script>


<?php $this->endWidget(); ?>

<style>
    .receivedByDiv {
        min-width: 252px;
    }

    select {
        width: 261px;
    }

    input[type=text] {
        width: 260px;
    }
</style>

<script>
    $(document).keyup(function (e) {
        if (e.keyCode === 45)
            AddNew();
    });

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
