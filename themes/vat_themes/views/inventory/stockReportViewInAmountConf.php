<?php
echo "<div class='printBtn'>";
$this->widget('ext.mPrint.mPrint', array(
    'title' => ' ', //the title of the document. Defaults to the HTML title
    'tooltip' => 'Print', //tooltip message of the print icon. Defaults to 'print'
    'text' => '', //text which will appear beside the print icon.
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
<?php
$type = PpSelectionType::model()->activeMethod();
if ($type == PpSelectionType::FIFO)
    $typeName = "FIFO";
else if ($type == PpSelectionType::LIFO)
    $typeName = "LIFO";
else
    $typeName = "AVERAGE";
?>

<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr class="noborderTr">
                <td colspan="10"><?php echo $message . " (" . $typeName . ")"; ?></td>
            </tr>
            <tr style="width:98%" class="titlesTr">
                <th width="3%">SL</th>
                <th>ITEM DESC.</th>
                <th width="8%">PART NO.</th>
                <th width="3%">QUALITY</th>
                <th width="5%">OPENING STOCK</th>
                <th width="5%">STOCK IN</th>
                <th width="5%">STOCK OUT</th>
                <th width="5%">CLOSING STOCK</th>
                <th width="5%">UNIT Purchase Price</th>
                <th width="8%">TOTAL PRICE</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataNow) { ?>
                <?php
                $i = 1;
                $totalcostingAmount = 0;

                $monthStart = substr($startDate, 5, 2);
                $monthEnd = substr($endDate, 5, 2);
                $year = substr($startDate, 0, 4);
                $costingAmount = 0;
                foreach ($dataNow as $dNow):

                    $totalOpeningStock = round($dNow->prevAllStockIn - $dNow->prevAllStockOut, 2);

                    $tStockIn = $dNow->tStockIn;
                    $tStockOut = $dNow->tStockOut;
                    $brandId = $dNow->brand_id;
                    $storId = $dNow->store_id;
                    $locationId = $dNow->location_id;

                    //=====================Data collection====================//
                    $modelId = $dNow->model_id;

                    $prodCode = $dNow->code;

                    $itemId = $dNow->item_id;
                    $itemName = CHtml::encode(ProdItems::model()->itemName($itemId));

                    $modelName = '';
                    $pbrandNm = '';
                    $vbrandNm = '';
                    $vModelNm = '';
                    $yearModel = '';
                    $ccNm = '';
                    $pmodelNm = '';
                    $modelAllInfo = ProdModels::model()->findByPk($modelId);
                    if ($modelAllInfo) {
                        $modelName = $modelAllInfo->model_name;
                        $pbrandNm = CHtml::encode(PBrand::model()->nameOfThis($modelAllInfo->pbrand));
                        $vbrandNm = ProdModels::model()->vBrand($modelAllInfo->id);
                        ;
                        $vModelNm = ProdModels::model()->returnvModels($modelAllInfo->id);
                        $yearModel = ProdModels::model()->returnyearModels($modelAllInfo->id);
                        $ccNm = ProdModels::model()->cc($modelAllInfo->id);
                        if ($modelAllInfo->pmodel != '' && $modelAllInfo->pmodel > 0) {
                            $pmodelNm = CHtml::encode(PModel::model()->nameOfThis($modelAllInfo->pmodel));
                        }
                    } else {
                        continue;
                    }

                    $locationName = Location::model()->nameOfThis($locationId);

                    $brandName = CHtml::encode(ProdBrands::model()->brandName($dNow->brand_id));
                    $openingStock = $totalOpeningStock;

                    $closingStock = round($openingStock + $tStockIn - $tStockOut, 2);
                    /*
                      if($showallornot=='Current'){
                      $costingAmount = PurchasePrices::model()->costingBetweenDate($modelId, $startDate, $endDate);
                      }
                      else{
                      $costingAmount = PurchasePrices::model()->costing($modelId, $endDate);
                      }
                     */
                    if ($modelId > 0)
                        $purchase_unit_price = PurchasePricesManual::model()->activePurchasePrice($modelId);
                    //$costingAmount = SellPrice::model()->getSellPriceByModelId($modelId);



                    if ($closingStock > 0) {
                        $rowTotal = $purchase_unit_price * $closingStock;
                        $totalcostingAmount = $rowTotal + $totalcostingAmount;
                        ?>
                        <tr class="<?php
                        if ($i % 2 == 0)
                            echo 'even';
                        else
                            echo 'odd';
                        ?>">
                            <td><?php echo $i++; ?></td>

                            <!--New Added fields Start-->
                            <td class="textAlgnLeft"><?php echo $modelName; ?></td>
                            <td class="textAlgnLeft"><?php echo $prodCode; ?></td>
                            <td class="textAlgnLeft"><?php echo $itemName; ?></td>
                            <!--New Added fields End-->
            <?php /* ?><td class="textAlgnLeft"><?php echo $pmodelNm; ?></td><?php */ ?>
                            <td class="textAlgnRight"><?php echo floatval($openingStock); ?></td>
                            <td class="textAlgnRight"><?php echo floatval($tStockIn); ?></td>
                            <td class="textAlgnRight"><?php echo floatval($tStockOut); ?></td>
                            <td class="textAlgnRight"><?php echo floatval($closingStock); ?></td>
                            <td class="textAlgnRight"><?php echo $purchase_unit_price ?></td>

                            <td class="textAlgnRight"><?php echo number_format(floatval($rowTotal), 2); ?></td>
                        </tr>
            <?php
        }
    endforeach;
    ?>
                <tr>
                    <td colspan="9" class="textAlgnRight">Total</td>
                    <td class="textAlgnRight heightLight"><?php echo number_format(floatval($totalcostingAmount), 2); ?></td>
                </tr> 
<?php }else { ?>
                <tr>
                    <td colspan="9"><div class="flash-error">No result found!</div></td>
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

    $(window).bind("scroll", function () {
        var offset = $(this).scrollTop();

        if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
            $fixedHeader.show();
        } else if (offset < tableOffset) {
            $fixedHeader.hide();
        }
    });

</script>

