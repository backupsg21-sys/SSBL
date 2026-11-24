<?php if ($data1) { ?>
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
        'visible' => Yii::app()->user->checkAccess('print'), //should this be visible to the current user?
        'alt' => 'print', //text which will appear if image can't be loaded
        'debug' => FALSE, //enable the debugger to see what you will get
        'id' => 'print-div'         //id of the print link
    ));
    echo "</div>";
    ?>
<?php } ?>

<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr>
                <td colspan="6"><?php echo $message; ?></td>
                <td colspan="5" class="textAlgnLeft">
                    <ol>
                        <li>OQ- Ordered Quantity</li>
                        <li>DQ- Delivered Quantity</li>
                        <li>RQ- Returned Quantity</li>
                        <li>SP- Sale Price</li>
                        <li>D- Discount</li>
                    </ol>
                </td>
                <td colspan="4" class="textAlgnLeft">
                    <ol>
                        <li>LA- Line Amount</li>
                        <li>MA- Merge Amount</li>
                        <li>OD- Overall Discount</li>
                        <li>TA- Total Amount</li>
                    </ol>
                </td>
            </tr>
            <tr class="titlesTr">
                <th style="width: 20px;">SL</th>
                <th style="width: 60px;">Order No</th>
                <th style="width: 70px;">Order Date</th>
                <th style="width: 130px;">Customer</th>
                <th style="width: 140px;">Item</th>
                <th style="width: 100px;">Code</th>
                <th style="width: 100px;">Delivered From</th>
                <th style="width: 50px;">Qty</th>
                <th style="width: 50px;">ASP</th>
                <th style="width: 50px;">SP</th>
                <th style="width: 50px;">D</th>
                <th style="width: 50px;">LA ($)</th>
                <th style="width: 50px;">P/L</th>
                <th style="width: 50px;">MA ($)</th>
                <th style="width: 50px;">OD</th>
                <th style="width: 50px;">TA ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$data1) { ?>
                <tr>
                    <td colspan="16"><div class="flash-error">No result found!</div></td>
                </tr>
            <?php } else { ?>  
                <?php
                $thisSellNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
                $sl = 1;
                $i = 1;
                $lineTotalWithODGrand = 0;
				$plGroundTotal = 0;
                ?>   
                <?php foreach ($data1 as $d1): ?>
                    <?php
                    $subTotal = 0;
                    $soGroupCount = 1;
//                    $condition = $condition2 . " AND so_no=" . $d1->so_no . " ORDER BY id DESC";
//                    $soData = SellItems::model()->findAll(array("select"=>"*,prod_models.code as prod_code", "condition" => $condition,"join"=>"inner join sell_items on sell_items.model_id=prod_models.id "), "id");
                    //SellItems::model()->findAll(array("select"=>"*,prod_models.code as prod_code", "condition" => $condition,"join"=>"sell_items on sell_items.model_id=prod_models.id "), "id")

                    $criteria = new CDbCriteria;
                    $criteria->select = 't.*,prod_models.code as prod_code,prod_models.item_id as prod_item_id,prod_models.brand_id as prod_brand_id';
                    $criteria->join = ' inner JOIN `prod_models` on t.model_id=prod_models.id ';
                    $criteria->condition = $condition2 . " AND so_no=" . $d1->so_no . " ORDER BY id DESC";
                    $soData = SellItems::model()->findAll($criteria);

                    $rowspan = count($soData);
                    $discountOfThis = 0;
                    $discountData = DiscountTab::model()->findByAttributes(array('no' => $d1->so_no, 'no_type' => DiscountTab::SALE_ORDER));
                    if ($discountData) {
                        $discountOfThis = $discountData->discount;
                    }
                    ?>
                    <?php foreach ($soData as $sod): ?>
                        <?php
                        if ($storeId == "") {
                            $deliveFromStore = SellDelivery::model()->findByAttributes(array('sell_items_id' => $sod->id));
                            ?>
                            <?php
                            $criteria2 = new CDbCriteria();
                            $criteria2->select = 'sum(return_qty) AS totalReturnQty, sum(delivery_qty) AS totalDeliveryQty';
                            $criteria2->condition = 'sell_items_id=' . $sod->id;
                            $sellDeliveryInfo = SellDelivery::model()->findAll($criteria2);
                            $totalDeliveryQuantity = 0;
                            $totalReturnQuantity = 0;
                            if ($sellDeliveryInfo) {
                                foreach ($sellDeliveryInfo as $sdi):
                                    $totalDeliveryQuantity = $sdi->totalDeliveryQty;
                                    $totalReturnQuantity = $sdi->totalReturnQty;
                                endforeach;
                            }
                            $totalQty = ($totalDeliveryQuantity - $totalReturnQuantity);
                            $lineTotalOne = SellItems::model()->lineTotalWithDiscount($totalQty, $sod->sell_price, $sod->discount);
                            $subTotal = $lineTotalOne + $subTotal;
							
							$qty = $totalDeliveryQuantity - $totalReturnQuantity;
							
							$actualSellPrice = SellPrice::model()->activeSellpriceByProdId($sod->model_id);
							$pL = $sod->sell_price - $actualSellPrice;
                            ?>
                            <tr class="<?php
                            if ($i % 2 == 0) {
                                echo 'even';
                            } else {
                                echo 'odd';
                            }
                            ?>">
                                    <?php $i++; ?>
                                    <?php if ($soGroupCount == 1) { ?>
                                    <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $sl++; ?></td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $thisSellNoFormat . $d1->so_no; ?></td>
                                    <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->date_of_sell; ?></td>
                                    <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo CHtml::encode(Customers::model()->customerName($d1->customer_id)); ?></td>
                                <?php } ?>
                                <td class="textAlgnLeft"><?php echo CHtml::encode(ProdModels::model()->modelName($sod->model_id)); ?></td>
                                <td><?php echo $sod->prod_code; ?></td>
                                <td class="textAlgnLeft">
                                    <?php
                                    if ($deliveFromStore) {
                                        echo CHtml::encode(Stores::model()->storeName($deliveFromStore->store_id));
                                    } else {
                                        echo "<font style='color: red; font-weight: bold;'>Not Delivered Yet!</font>";
                                    }
                                    ?>
                                </td>
                                <?php /*
                                  <td><?php echo number_format(floatval($sod->sell_qty)); ?></td>
                                  <td><?php echo number_format(floatval($totalDeliveryQuantity)); ?></td>
                                  <td><?php echo number_format(floatval($totalReturnQuantity)); ?></td>
                                 */ ?>
                                <td><?php echo number_format(floatval($qty)); ?></td>
                                <td><?php echo number_format(floatval($actualSellPrice)); ?></td>
                                <td><?php echo number_format(floatval($sod->sell_price)); ?></td>
                                <td><?php echo number_format(floatval($sod->discount)); ?></td>
                                <td><?php echo number_format(floatval($lineTotalOne)); ?></td>
                                <td><?php echo number_format(floatval($pL * $qty)); ?></td>
                                <?php if ($soGroupCount == 1) { ?>
                                    <td class="merge" id="subTotal_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                    <td class="merge" id="overAllDsc_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                    <td class="merge textAlgnRight" id="totalAmount_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                <?php } ?>
                                <?php
                                if ($rowspan == $soGroupCount) {
									$totalPl = $pL * $qty;
									$plGroundTotal += $totalPl;
                                    $lineTotalWithOD = DiscountTab::model()->lineTotalWithDiscount($subTotal, $discountOfThis);
                                    $lineTotalWithODGrand = $lineTotalWithOD + $lineTotalWithODGrand;
                                    ?>
                            <script>
                                var subtotal = parseFloat(<?php echo $subTotal; ?>);
                                var overalldsc = parseFloat(<?php echo $discountOfThis; ?>);
                                var totalamount = parseFloat(<?php echo $lineTotalWithOD; ?>);
                                $("#subTotal_<?php echo $sl; ?>").html(subtotal);
                                $("#overAllDsc_<?php echo $sl; ?>").html(overalldsc);
                                $("#totalAmount_<?php echo $sl; ?>").html(totalamount);
                            </script>
                        <?php } ?>
                        <?php
                        $soGroupCount++;
                        ?>

                        </tr>
                    <?php } else { ?> <!-- Store Wise Search -->
                        <?php
                        $deliveFromStore = SellDelivery::model()->findByAttributes(array('sell_items_id' => $sod->id));
                        if ($deliveFromStore) {
                            if ($deliveFromStore->store_id === $storeId) {
                                ?>
                                <?php
                                $criteria2 = new CDbCriteria();
                                $criteria2->select = 'sum(return_qty) AS totalReturnQty, sum(delivery_qty) AS totalDeliveryQty';
                                $criteria2->condition = 'sell_items_id=' . $sod->id;
                                $sellDeliveryInfo = SellDelivery::model()->findAll($criteria2);
                                $totalDeliveryQuantity = 0;
                                $totalReturnQuantity = 0;
                                if ($sellDeliveryInfo) {
                                    foreach ($sellDeliveryInfo as $sdi):
                                        $totalDeliveryQuantity = $sdi->totalDeliveryQty;
                                        $totalReturnQuantity = $sdi->totalReturnQty;
                                    endforeach;
                                }
                                $totalQty = ($totalDeliveryQuantity - $totalReturnQuantity);
                                $lineTotalOne = SellItems::model()->lineTotalWithDiscount($totalQty, $sod->sell_price, $sod->discount);
                                $subTotal = $lineTotalOne + $subTotal;
                                ?>
                                <tr class="<?php
                                if ($i % 2 == 0) {
                                    echo 'even';
                                } else {
                                    echo 'odd';
                                }
                                ?>">
                                        <?php $i++; ?>
                                        <?php if ($soGroupCount == 1) { ?>
                                        <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $sl++; ?></td>
                                        <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo $thisSellNoFormat . $d1->so_no; ?></td>
                                        <td class="merge" rowspan="<?php echo $rowspan; ?>"><?php echo $d1->date_of_sell; ?></td>
                                        <td class="merge textAlgnLeft" rowspan="<?php echo $rowspan; ?>"><?php echo CHtml::encode(Customers::model()->customerName($d1->customer_id)); ?></td>
                                    <?php } ?>
                                    <td class="textAlgnLeft"><?php echo CHtml::encode(ProdModels::model()->modelName($sod->model_id)); ?></td>
                                    <td><?php echo $sod->prod_code; ?></td>
                                    <td class="textAlgnLeft">
                                        <?php
                                        if ($deliveFromStore) {
                                            echo CHtml::encode(Stores::model()->storeName($deliveFromStore->store_id));
                                        } else {
                                            echo "<font style='color: red; font-weight: bold;'>Not Delivered Yet!</font>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo number_format(floatval($sod->sell_qty)); ?></td>
                                    <td><?php echo number_format(floatval($totalDeliveryQuantity)); ?></td>
                                    <td><?php echo number_format(floatval($totalReturnQuantity)); ?></td>
                                    <td><?php echo number_format(floatval($sod->sell_price)); ?></td>
                                    <td><?php echo number_format(floatval($sod->discount)); ?></td>
                                    <td><?php echo number_format(floatval($lineTotalOne)); ?></td>
                                    <?php if ($soGroupCount == 1) { ?>
                                        <td class="merge" id="subTotal_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                        <td class="merge" id="overAllDsc_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                        <td class="merge textAlgnRight" id="totalAmount_<?php echo $sl; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                                    <?php } ?>
                                    <?php
                                    if ($rowspan == $soGroupCount) {
                                        $lineTotalWithOD = DiscountTab::model()->lineTotalWithDiscount($subTotal, $discountOfThis);
                                        $lineTotalWithODGrand = $lineTotalWithOD + $lineTotalWithODGrand;
                                        ?>
                                    <script>
                                        var subtotal = parseFloat(<?php echo $subTotal; ?>);
                                        var overalldsc = parseFloat(<?php echo $discountOfThis; ?>);
                                        var totalamount = parseFloat(<?php echo $lineTotalWithOD; ?>);
                                        $("#subTotal_<?php echo $sl; ?>").html(subtotal);
                                        $("#overAllDsc_<?php echo $sl; ?>").html(overalldsc);
                                        $("#totalAmount_<?php echo $sl; ?>").html(totalamount);
                                    </script>
                                <?php } ?>
                                <?php
                                $soGroupCount++;
                                ?>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr class="odd">
                <td colspan="12" class="textAlgnRight">Total PL</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($plGroundTotal)); ?></td>
				<td></td>
                <td class="textAlgnRight">Total</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($lineTotalWithODGrand)); ?></td>
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
