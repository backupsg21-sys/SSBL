
<table class="reportTab">
    <thead>
        <tr>
            <td colspan="7"><?php echo $message; ?></td>
        </tr>
        <tr>
            <th>CATEGORY</th>
            <th>SUB-CATEGORY</th>
            <th>ITEM</th>
            <th>CODE</th>
            <th>ADDITIONAL INFO</th>
            <th>STOCK QTY</th>
            <th>LOCATION</th>
            <th>TRANSFER</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($data) {
            $i = 0;
            foreach ($data as $d):
                $i++;
                $item = CHtml::encode(ProdItems::model()->itemName($d->item_id));
                $brand = CHtml::encode(ProdBrands::model()->brandName($d->brand_id));
                $model = CHtml::encode(ProdModels::model()->modelName($d->model_id));
                 $code = ProdModels::model()->prodCodeOfThis($d->model_id);
				$locationId = $d->location_id;
				//$locationName = Location::model()->nameOfThis($locationId);
                $totalStockIn = floatval($d->tStockIn);
                $totalStockOut = floatval($d->tStockOut);
                $availableStock = floatval(($totalStockIn - $totalStockOut));


                ?>
                <tr class='
                <?php
                if ($i % 2 == 0)
                    echo "even";
                else
                    echo "odd";
                ?>
                    '>
                    <td class="textAlgnLeft"><?php echo $item; ?></td>
                    <td class="textAlgnLeft"><?php echo $brand; ?></td>
                    <td class="textAlgnLeft"><?php echo $model; ?></td>
                    <td><?php echo $code; ?></td>
                    <td class="textAlgnLeft"><?php ProdModels::model()->optionalImportantInfos($d->model_id); ?></td>
                    <td id="availableStockTd"><?php echo $availableStock; ?></td>
                    <td id="textAlgnLeft"><?php //echo $locationName; ?></td>
                    <td>
                        <input style="background-color: #FFA500;" class="add-more-btn" type="button" value="TRANSFER" onclick="addSections_<?php echo $d->id; ?>(); $('#dialogSections').dialog('destroy'); $('#dialogSections').dialog({ autoOpen: false, resizable: false, title: 'Stock Transfer', width: 'auto', modal: true }); $('#dialogSections').dialog('open');" />

                        <script type="text/javascript">
                            // here is the magic
                            function addSections_<?php echo $d->id; ?>(){
        <?php
        echo CHtml::ajax(array(
            'url' => array('StockTransferHistory/stockTransferReqSent', 'id' => $d->id, 'model_id' => $d->model_id, 'store_id' => $storeId),
            'data' => 'js:$(this).serialize()',
            'type' => 'post',
            'dataType' => 'json',
            'beforeSend' => "function(){
        $('.ajaxLoaderFormLoad').show();
        }",
            'complete' => "function(){
        $('.ajaxLoaderFormLoad').hide();
        }",
            'success' => "function(data){
        if (data.status == 'failure'){
            $('#dialogSections div.divForForm').html(data.content);
            $('#dialogSections div.divForForm form').submit(addSections_" . $d->id . ");
        }else{
            $('#dialogSections div.divForForm').html(data.content);
             $('#availableStockTd').html(data.availableStock);
             setTimeout( \"$( '#dialogSections' ).dialog( 'close' );\", 1000 );
        }
        }",
        ))
        ?>;
        return false;
        }
                        </script>

                    </td>
                </tr>
                <?php
            endforeach;
        }else {
            ?>
            <tr>
                <td colspan="7">
                    <div class="flash-error">No result found!</div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
    'id' => 'dialogSections',
    'options' => array(
        'title' => 'Stock Transfer',
        'autoOpen' => false,
        'modal' => true,
        'width' => 'auto',
        'resizable' => false,
        'left' => 100,
    ),
));
?>
<div class="divForForm">
    <div class="ajaxLoaderFormLoad" style="display: none;"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ajax-loader.gif" /></div>
</div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>