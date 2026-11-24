<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'chart-of-ac-form',
    'action' => $this->createUrl('chartOfAc/create'),
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));

$thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
?>


<style>
    .tree, .tree ul {
        margin: 0;
        padding: 0;
        list-style: none
    }

    .tree ul {
        margin-left: 2em;
        position: relative
    }

    .tree ul ul {
        margin-left: 1.5em;
    }

    .tree ul:before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        border-left: 1px solid
    }

    .tree li {
        margin: 0;
        padding: 0 1em;
        line-height: 2em;
        color: #369;
        font-weight: 700;
        position: relative
    }

    .tree ul li:before {
        content: "";
        display: block;
        width: 10px;
        height: 0;
        border-top: 1px solid;
        margin-top: -1px;
        position: absolute;
        top: 1em;
        left: 0
    }
    ul, #myUL {
        list-style-type: none;
    }

    #myUL {
        margin: 0;
        padding: 0;
    }

    .caret {
        cursor: pointer;
        -webkit-user-select: none; /* Safari 3.1+ */
        -moz-user-select: none; /* Firefox 2+ */
        -ms-user-select: none; /* IE 10+ */
        user-select: none;
    }

    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .caret-down::before {
        -ms-transform: rotate(90deg); /* IE 9 */
        -webkit-transform: rotate(90deg); /* Safari */
        transform: rotate(90deg);
    }

    .nested {
        display: none;
    }

    .active {
        display: block;
    }
</style>


<div class="formDiv">
    <fieldset>
        <legend>Add New CA</legend>
        <table style="width: 50%; float: left; clear:right;">
            <tr>
                <td><?php echo $form->labelEx($model, 'code'); ?></td>
                <td><?php echo $form->textField($model, 'code', array('maxlength' => 255, 'value' => $thisCodeNo)); ?></td>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'code'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'title'); ?></td>
                <td><?php echo $form->textField($model, 'title', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'title'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'bank_or_cash'); ?></td>
                <td><?php echo $form->checkBox($model, 'bank_or_cash'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'bank_or_cash'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'opening_amount'); ?></td>
                <td>
                    <?php echo $form->dropDownList($model, 'transaction_type', LookupAc::items('transaction_type'), array('style' => 'width: 42px;')); ?>
                    <?php echo $form->textField($model, 'opening_amount'); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'opening_amount'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'remarks'); ?></td>
                <td><?php echo $form->textField($model, 'remarks', array('maxlength' => 255)); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'remarks'); ?></td>
            </tr>
            <tr>
                <!--<td><?php //echo $form->labelEx($model, 'root'); ?></td>
                <td class="specialTd">-->
                    <?php //ChartOfAc::model()->allRootsWithSubRoots($model->root, $model->id); ?>
                <!--</td>-->
				
				<td colspan="2" class="specialTd">
                    <label style="font-weight: bold; font-size: 16px;">Root</label>

                    <div id="allChartOfAc">
                        <?php ChartOfAc::model()->allRootsWithSubRoots($model->root, $model->id); ?>
                    </div>
                    
                    <?php echo $form->error($model, 'root'); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo $form->error($model, 'root'); ?></td>
            </tr>
        </table>

    </fieldset>
    <fieldset class="tblFooters">
        <span id="ajaxLoaderMR" style="display: none; float: left;"><img
                    src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif"/></span>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update', array('onclick' => 'loadingDivDisplay();')); ?>
    </fieldset>
</div>
<script type="text/javascript">
    function loadingDivDisplay() {
        $("#ajaxLoaderMR").show();
    }
	
	$(function(){

        $("#myUL").find("ul").each(function(){
            $(this).prev('li').find('b').before("<span data='expand' class='expand'>&#x25B6;</span>")
        });

        $(".expand").live("click", function(e){
            var check = $(this).attr('data');
            $(this).parent().next(".nested").toggleClass("active");
            if(check == "expand"){
                $(this).html("&#x25BC;");
                $(this).attr('data', 'shrink');
            }else{
                $(this).html("&#x25B6;");
                $(this).attr('data', 'expand');
            }
        });

        $('input[type=radio]').change(function() {
            var $self = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl('accounting/chartOfAc/getTransactionalStatus') ?>',
                cache: false,
                type: "POST",
                data: {id: this.value},
                success: function (data) {
                    // alert(data);
                    if(data > 0){
                        alertify.alert("ChartOfAc has been found in Journal Entry");
                        $self.removeAttr('checked');
                    }
                }
            });
        });

    })
</script>


<script>
    function searchChartOfAc(){

        var company_id = $("#search_company_id").val();
        var process_id = $("#search_process_id").val();
        
        var root = "<?php echo $model->root; ?>";
        var id = "<?php echo $model->id; ?>";

        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl('/accounting/chartOfAc/allRootsWithSubRoots') ?>',
            cache: false,
            type: "POST",
            data: {
                root: root, id: id, company_id: company_id, process_id: process_id
            },
            beforeSend: function () {

                if(!$("#search_company_id").val()){
                    alertify.alert("Please Select Company");
                    return false;
                }
                if(!$("#search_process_id").val()){
                    alertify.alert("Please Select Process");
                    return false;
                }

                $("#allChartOfAc").html("Loading...");
                
            },
            success: function(data) {
                $("#allChartOfAc").html(data);
            }
        });

    }
</script>
<?php $this->endWidget(); ?>
