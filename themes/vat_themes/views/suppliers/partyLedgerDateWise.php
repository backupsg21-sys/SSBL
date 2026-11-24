<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('suppliers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="search-form">
        <?php
        $this->renderPartial('_searchDateWise', array(
            'model' => $model,
        ));
        ?>
    </div>
<fieldset style="float: left; width: 98%;">
    <legend>Result <span style=" float: right; margin-left: 8px;"><div id="ajaxLoader" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div><span></legend>
    <div id="resultDiv">
        
    </div>
</fieldset>
