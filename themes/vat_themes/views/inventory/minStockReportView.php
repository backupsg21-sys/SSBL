<?php
echo "<div class='printBtn'>";
$this->widget('ext.mPrint.mPrint', array(
    'title' => ' ', //the title of the document. Defaults to the HTML title
    'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
    'text' => '', //text which will appear beside the print icon. Defaults to NULL
    'element' => '.printAllTableForThisReport', //the element to be printed.
    'exceptions' => array(//the element/s which will be ignored
        '.summary',
        '.search-form',
    ),
    'publishCss' => FALSE, //publish the CSS for the whole page?
    'visible' => !Yii::app()->user->isGuest, //should this be visible to the current user?
    'alt' => 'print', //text which will appear if image can't be loaded
    'debug' => FALSE, //enable the debugger to see what you will get
    'id' => 'print-div'         //id of the print link
));
echo "</div>";
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr class="noborderTr">
                <td colspan="11"><?php echo $message; ?></td>
            </tr>
            <tr style="width:98%" class="titlesTr">
                <th width="3%">SL</th>
                <th width="5%">QUALITY</th>
                <th width="15%">PARTS TYPE</th>
                <th>PARTS NAME</th>
                <th width="15%">CODE</th>
                <th width="8%">P. BRAND</th>
                <th width="7%">P. MODEL</th>
                <th width="5%">OPENING STOCK</th>
                <th width="5%">STOCK IN</th>
                <th width="5%">STOCK OUT</th>
                <th width="5%">CLOSING STOCK</th>
                <th width="5%">MINIMUM QTY</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataNow) { ?>
                <?php
                $i = 1;
                foreach ($dataNow as $dNow):

                    $tStockIn = $dNow->tStockIn;
                    $tStockOut = $dNow->tStockOut;
                    $itemId = $dNow->item_id;
                    $brandId = $dNow->brand_id;
                    $modelId = $dNow->model_id;
                    $prodCode = $dNow->code;
                    $storId = $dNow->store_id;

                    $pbrandNm = '';
                    $pmodelNm = '';
                    $modelAllInfo = ProdModels::model()->findByPk($modelId);
                    $pbrandNm = CHtml::encode(PBrand::model()->nameOfThis($modelAllInfo->pbrand));
					$pmodelNm = CHtml::encode(PModel::model()->nameOfThis($modelAllInfo->pmodel));
                    $itemName = CHtml::encode(ProdItems::model()->itemName($dNow->item_id));
                    $brandName = CHtml::encode(ProdBrands::model()->brandName($dNow->brand_id));
                    $modelName = CHtml::encode(ProdModels::model()->modelName($dNow->model_id));
					$minQty = CHtml::encode(ProdModels::model()->minQtyOfThis($dNow->model_id));

					
					
                    $cirteriaOpeningStock = new CDbCriteria();
                    $cirteriaOpeningStock->select = 'sum(stock_in) as tStockInLast, sum(stock_out) as tStockOutLast';
                    $cirteriaOpeningStock->condition = "store_id=" . $storId . " AND transaction_date < '" . $startDate . "' AND item_id=" . $itemId . " AND brand_id=" . $brandId . " AND model_id=" . $modelId;

                    $dataOpeningStock = Inventory::model()->findAll($cirteriaOpeningStock);

                    $closingStock = 0;
                    $tStockInLast = 0;
                    $tStockOutLast = 0;
                    foreach ($dataOpeningStock as $dos):
                        $tStockInLast = $dos->tStockInLast;
                        $tStockOutLast = $dos->tStockOutLast;
                        $id = $dos->id;
                    endforeach;
                    $openingStock = ($tStockInLast - $tStockOutLast);

                    $closingStock = ($openingStock + $tStockIn - $tStockOut);
                    ?>
                    <?php if ($minQty > $closingStock) {?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="textAlgnLeft"><?php echo $itemName; ?></td>
                        <td class="textAlgnLeft"><?php echo $brandName; ?></td>
                        <td class="textAlgnLeft"><?php echo $modelName; ?></td>
                        <td class="textAlgnLeft"><?php echo $prodCode; ?></td>
                        <td class="textAlgnLeft"><?php echo $pbrandNm; ?></td>
                        <td class="textAlgnLeft"><?php echo $pmodelNm; ?></td>
                        <td class="textAlgnRight"><?php echo floatval($openingStock); ?></td>
                        <td class="textAlgnRight"><?php echo floatval($tStockIn); ?></td>
                        <td class="textAlgnRight"><?php echo floatval($tStockOut); ?></td>
                        <td class="textAlgnRight"><?php echo floatval($closingStock); ?></td>
                        <td class="textAlgnRight"><?php echo floatval($minQty); ?></td>
                    </tr>
                    <?php
					}
                endforeach;
                ?>
            <?php }else { ?>
                <tr>
                    <td colspan="11"><div class="flash-error">No result found!</div></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <table id="header-fixed"></table>
</div>

<script>
    var tableOffset = $("#table-1 thead tr.titlesTr").offset().top;
    var $header = $("#table-1 > thead tr.titlesTr").clone();
    var $fixedHeader = $("#header-fixed").append($header);

    $(window).bind("scroll", function() {
        var offset = $(this).scrollTop();
    
        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        }
        else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });

</script>

<style>
	table.reportTab tr.odd{
		background-color: #F37169;
	}
	table.reportTab tr.even{
		background-color: #FFFFFF;
	}
</style>
