<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/report.css" type="text/css" media="screen" />

<div class="search-form">
    <?php
    $this->renderPartial('_searchStockQtyForTransfer', array(
        'model' => $model,
    ));
    ?>
</div>

<fieldset style="float: left; width: 98%;">
    <legend>Stock <div id="ajaxLoader" style="display: none; float: right;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div></legend>
    <div id="resultDiv">

    </div>
</fieldset>
