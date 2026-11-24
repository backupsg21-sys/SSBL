<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'journal-entry-form',
    'action' => $this->createUrl('journalEntry/createPayRec'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<input class="amounts" type="hidden" name="amount"/>
<div class="formDiv">
    <fieldset>
        <legend>Payment Receipt Voucher</legend>
        <table style="width:80%">
            <tr>
                <td style="width: 10%">
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td style="width: 20%"><?php echo $form->labelEx($model, 'entry_date'); ?></td>
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
                                            'style' => 'width: 260px;',
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
                                    <div id="orderNo" class="receivedByDiv" style="width:253px;">Will Auto Generate
                                    </div>
                                    <?php echo $form->error($model, 'entry_no'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'voucher_type'); ?></td>
                                <td>
                                    <?php
                                    $dataarray = array(9 => 'RECEIPT', 10 => 'PAYMENT');
                                    echo $form->dropDownList($model, 'voucher_type', $dataarray, array('prompt' => 'select'));
                                    ?>
                                    <?php echo $form->error($model, 'voucher_type'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'cash_bank'); ?></td>
                                <td><?php echo $form->dropDownList($model, 'cash_bank', Lookup::items('expense_type'), array('prompt' => 'select', 'id' => 'cashBank')); ?></td>
                            </tr>
                            <tr id="bank_row">
                                <td><label>Bank</label></td>
                                <td>
                                    <input type="text" id="ca_bank_label" name="ca_bank_label" placeholder="Bank Name"/>
                                    <?php echo $form->hiddenField($model, 'ca_bank'); ?>
                                </td>
                            </tr>
                            <tr id="cash_row">
                                <td><label>Cash</label></td>
                                <td>
                                    <input type="text" id="ca_cash_label" name="ca_cash_label" placeholder="Cash Name"/>
                                    <?php echo $form->hiddenField($model, 'ca_cash'); ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td style="width: 20%"><label>Account Title</label></td>
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
                                    <?php echo $form->dropDownList($model, 'transaction_type', LookupAc::items('transaction_type'), array('style' => 'width: 42px; display: none', 'onkeypress' => 'return isNumberKey(event)')); ?>
                                    <?php echo $form->textField($model, 'amount'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $form->labelEx($model, 'narration'); ?></td>
                                <td>
                                    <?php echo $form->textArea($model, 'narration', array('maxlength' => 255, 'style' => 'width: 256px;')); ?>
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
                                    echo CHtml::ajaxSubmitButton('Save', CHtml::normalizeUrl(array('journalEntry/createPayRec', 'render' => true)), array(
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
                    <fieldset style="width: 100%; float: left; overflow: scroll; height: 400px;" class="someStyle">
                        <legend>Added List</legend>
                        <table id="tbl" class="prodAddedTab">
                            <tr>
                                <th style="width: 10px;">SL</th>
                                <th>Account Title</th>
                                <th style="width: 115px;">Amount</th>
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
            $('.amounts').val($('input.dataAmount').sumValues());
        });
        $('#crAmount' + count).bind('keyup', function () {
            $('#JournalEntry_total_cr').val($('input.totalCr').sumValues());
            nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
            $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
            $('.amounts').val($('input.dataAmount').sumValues());
        });

    }


    $("#tbl td input.drDltBtn").live("click", function () {
        var idCounter = $(this).attr("id");
        totalDr = $("#JournalEntry_total_dr").val() - $('#drAmount' + idCounter).val();
        $("#JournalEntry_total_dr").val(totalDr);
        $('.amounts').val($('input.dataAmount').sumValues());
        var srow = $(this).parent().parent();
        srow.remove();
        $("#tbl td.sno").each(function (index, element) {
            $(element).text(index + 1);
        });

        newArr[idCounter] = 0;

        nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
        $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
        $('.amounts').val($('input.dataAmount').sumValues());
    });
    $("#tbl td input.crDltBtn").live("click", function () {
        var idCounter = $(this).attr("id");
        totalCr = $("#JournalEntry_total_cr").val() - $('#crAmount' + idCounter).val();
        $("#JournalEntry_total_cr").val(totalCr);
        $('.amounts').val($('input.dataAmount').sumValues());
        var srow = $(this).parent().parent();
        srow.remove();
        $("#tbl td.sno").each(function (index, element) {
            $(element).text(index + 1);
        });

        newArr[idCounter] = 0;

        nowDrCrDiff = $('input.totalDr').sumValues() - $('input.totalCr').sumValues();
        $('#JournalEntry_drcrdiff').val(nowDrCrDiff);
        $('.amounts').val($('input.dataAmount').sumValues());
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
                $("#JournalEntry_amount").val("");
                $("#ca_label").focus();
            }
        }
    }

    function add() {
        sl++;
        var slNumber = $('#tbl tr').length;

        var appendTxt = "<tr class='cartList'>" +
            "<input type='hidden' name='JournalEntry[temp_ca_id][]' value='" + $("#JournalEntry_ca_id").val() + "'>" +
            "<input type='hidden' id='tType' name='JournalEntry[temp_transaction_type][]' value='" + $("#JournalEntry_transaction_type").val() + "'>" +
            "<td class='sno'>" +
            slNumber +
            "</td><td style='text-align: left;'>" +
            $("#ca_label").val() + "</td>";
        if ($("#JournalEntry_transaction_type").val() == 3) {
            appendTxt = appendTxt + "<td><input id='drAmount" + sl + "' class='totalDr dataAmount' type='text' name='JournalEntry[temp_amount][]' value='" + $("#JournalEntry_amount").val() + "'></td>";
        } else {
            appendTxt = appendTxt + "<td><input id='crAmount" + sl + "' class='totalCr dataAmount' type='text' name='JournalEntry[temp_amount][]' value='" + $("#JournalEntry_amount").val() + "'></td>";
        }
        appendTxt = appendTxt + "<td><input title=\"remove\" id='" + sl + "' type=\"button\" class=\"rdelete dltBtn crDltBtn\"/></td></tr>";
        $("#tbl tr:last").after(appendTxt);

        calFnc(sl);

        //var dr = $('#JournalEntry_total_dr').val($('input.totalDr').sumValues());
        // var cr = $('#JournalEntry_total_cr').val($('input.totalCr').sumValues());

        var dr = $('#JournalEntry_total_dr').val(1);
        var cr = $('#JournalEntry_total_cr').val(1);

        nowDrCrDiff = parseInt(dr - cr);
        $('#JournalEntry_drcrdiff').val(0);
        $('.amounts').val($('input.dataAmount').sumValues());

    }
</script>
<?php $this->endWidget(); ?>

<script>

    $("#JournalEntry_voucher_type").change(function () {
        var paymentType = $(this).val();
        if (paymentType == 10) {
            $('#JournalEntry_transaction_type').val(3);
        }
        if (paymentType == 9) {
            $('#JournalEntry_transaction_type').val(4);
        } else {
            $('#JournalEntry_transaction_type').val("");
        }
    });

    /*$("#cashBank").change(function () {
    var value = this.value;
    // console.log(value);
    if (value == 2) { // if cheque
    //$("#ca_bank_label").prop('readonly', false);
    } else {
    $("#ca_bank_label").val("");
    $("#JournalEntry_ca_bank").val("");
    //$("#ca_bank_label").prop('readonly', true);
    }
    });*/

    $("#ca_label").keyup(function () {
        var cash_bank = parseInt($("#cashBank").val());
        if (isNaN(cash_bank) || cash_bank < 0) {
            $('#cashBank').focus().select()
            alertify.alert("Please Select Cash / Cheque");
            $("#ca_label").val("");
            if (cash_bank != 2) {
                $(".table tbody tr.caBankHead").hide();
            }
        }
    });
    /*
    $(function () {
        $("#ca_bank_label").autocomplete({
            source: function (request, response) {
                var search_ca = request.term;
                $.post('/dhaka_thai_ac/dhaka_thai_ac/accounting/chartOfAc/caIdBanksSearch', {
                        "search_ca": search_ca,
                    },
                    function (data) {
                        response(data);
                    }, "json");
            },
            minLength: 1,
            select: function (event, ui) {
                $("#ca_bank_label").val(ui.item.label);
                $("#JournalEntry_ca_bank").val(ui.item.value).change();
                return false;
            }
        });
    });

    $(function () {
        $("#ca_cash_label").autocomplete({
            source: function (request, response) {
                var search_ca = request.term;
                $.post('/dhaka_thai_ac/dhaka_thai_ac/accounting/chartOfAc/caIdPettycashSearch', {
                        "search_ca": search_ca,
                    },
                    function (data) {
                        response(data);
                    }, "json");
            },
            minLength: 1,
            select: function (event, ui) {
                $("#ca_cash_label").val(ui.item.label);
                $("#JournalEntry_ca_cash").val(ui.item.value).change();
                return false;
            }
        });
    });
*/
    $('#bank_row').hide();
    $('#cash_row').hide();

    $('#cashBank').change(function () {
        if ($(this).val() == 2) {
            $('#bank_row').show();
            $('#cash_row').hide();
            $('#cash_row').val('');
        } else if ($(this).val() == 1) {
            $('#bank_row').hide();
            $('#bank_row').val('');
            $('#cash_row').show();
        } else {
            $('#bank_row').hide();
            $('#cash_row').hide();
            $('#bank_row').val('');
            $('#cash_row').val('');
        }
    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

</script>
<style>
    .receivedByDiv {
        min -width: 252px;
    }

    select {
        width: 261px;
    }

    input[type = text] {
        width: 260px;
    }
</style>

<script>
    $(function () {
        <?php
        $resultBanks = [];
        $root = ChartOfAc::BANK_ROOT; // bank
        $allIds = ChartOfAc::model()->findAll(array('condition' => 'root=' . $root, 'order' => 'title ASC'));
        if ($allIds) {
            foreach ($allIds as $caInfo) {
                $value = $caInfo->id;
                $label = $caInfo->title;
                $resultBanks[] = array(
                    'value' => $value,
                    'label' => $label,
                );
            }
        }

        $resultsCash = [];
        $rootCashInHand = ChartOfAc::CASH_IN_HAND; // Cash
        $allIds = ChartOfAc::model()->findAll(array('condition' => 'root=' . $rootCashInHand, 'order' => 'title ASC'));
        $totalCashInHandCount = count($allIds);
        if (count($allIds) >= 1) {
            foreach ($allIds as $caInfo) {
                $value = $caInfo->id;
                $label = $caInfo->title;
                $resultsCash[] = array(
                    'value' => $value,
                    'label' => $label,
                );
            }
        }
        ?>
        var suggestedBanks = <?= json_encode($resultBanks); ?>;
        var suggestedCashInHand = <?= json_encode($resultsCash); ?>;
        $('#cashBank').change(function () {
            $("#JournalEntry_ca_bank").val("").change();
            var rootCashInHand = <?=$rootCashInHand?>;
            var totalCashCount = <?=$totalCashInHandCount?>;
            var cashBank = this.value;
            if (cashBank == 1) { //cash
                console.log(totalCashCount);
                if (totalCashCount == 0) {
                    console.log('only one cash in hand!');
                    console.log(rootCashInHand);
                    $('#cash_row').hide();
                    $("#JournalEntry_ca_cash").val(rootCashInHand).change();
                } else {
                    $("#JournalEntry_ca_cash").val("").change();
                    console.log('cash more than one!')
                    $("#ca_cash_label").autocomplete({
                        source: suggestedCashInHand,
                        minLength: 0,
                        delay: 500,
                        // position: { my : "right top", at: "right bottom" },
                        search: function( event, ui ) {
                            console.log('x');
                        },
                        focus: function (event, ui) {
                            event.preventDefault();
                            $("#ca_cash_label").val(ui.item.label);
                        },
                        select: function (event, ui) {
                            event.preventDefault();
                            $("#JournalEntry_ca_cash").val(ui.item.value);
                            $("#ca_cash_label").val(ui.item.label);
                        }
                    });
                }
            } else { //bank
                $("#JournalEntry_ca_cash").val("").change();
                $("#ca_bank_label").autocomplete({
                    source: suggestedBanks,
                    minLength: 0,
                    delay: 500,
                    // position: { my : "right top", at: "right bottom" },
                    search: function( event, ui ) {
                        console.log('x');
                    },
                    focus: function (event, ui) {
                        event.preventDefault();
                        $("#ca_bank_label").val(ui.item.label);
                    },
                    select: function (event, ui) {
                        event.preventDefault();
                        $("#JournalEntry_ca_bank").val(ui.item.value);
                        $("#ca_bank_label").val(ui.item.label);
                    }
                });
            }
        });
    });
</script>