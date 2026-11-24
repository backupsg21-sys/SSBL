<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mushak-form',
    'action' => Yii::app()->createUrl('/purchaseItems/mushakFourThreeView'),
        ));
$this->pageTitle = "Mushak 4.3 (Input-Output Coefficient)";
?>
<style>
    #ProdModels_id_text{
        width:350px !important;
    }
</style>
<fieldset>
    <legend>Mushak 4.3 (Input-Output Coefficient)</legend>
    <table style="text-align: left">
        <tr>
            <td style="border: 1px solid #fff;">
                <input type="text" id="ProdModels_id_text" style="width:200px;"/>
                <input type="hidden" name="ProdModels[id]" id="ProdModels_id"/>
                <script>
                    $(function () {
                        $("#ProdModels_id_text").autocomplete({
                            source: function (request, response) {
                                var search_prodName = request.term;
                                $.post('<?php echo Yii::app()->baseUrl ?>/prodModels/Jquery_showFinishedGoodsProdSearch', {"search_prodName": search_prodName},
                                    function (data) {
                                        response(data);
                                    }, "json");
                            },
                            minLength: 1,
                            select: function (event, ui) {
                                $("#ProdModels_id_text").val(ui.item.label);
                                $("#ProdModels_id").val(ui.item.value);
                                return false;
                            }
                        });
                    });
                </script>
                <?php
                echo CHtml::ajaxSubmitButton('Search', CHtml::normalizeUrl(array('purchaseItems/mushakFourThreeView', 'render' => true)), array(
                    'dataType' => 'json',
                    'type' => 'post',
                    'success' => 'function(data) {
                    $("#ajaxLoader").hide(); 
                    $("#resultDiv").html(data.content);
                }',
                    'beforeSend' => 'function(){
                    if($("#ProdModels_id_text").val()=="" || $("#ProdModels_id").val()==""){
                        alertify.alert("Warning! Please select Product!");
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