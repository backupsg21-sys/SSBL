
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
    <table class="reportTab">
        <tr>
            <td colspan="15"><?php echo $message; ?></td>
        </tr>
        <tr>
            <td colspan="15" class="textAlgnLeft">PURCHASE ORDER INFO <font color="red">(CANCELLED)</font> ORDERS ARE NOT SHOWING <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bottomThis.png" /></td>
        </tr>
        <!--                           PURCHASE ORDER INFO STARTS-->
        <tr>
            <th>SL</th>
            <th>ORDER NO</th>
            <th>ISSUE DT</th>
            <th>EXP RCV DT</th>
            <th>REQUISITION BY</th>
            <th>SUPPLIER</th>
            <th>CAT</th>
            <th>SUB-CAT</th>
            <th>ITEM</th>
            <th>CODE</th>
            <th>PP</th>
            <th>ORDR QT</th>
            <th>RCV QT</th>
            <th>RET QT</th>
            <th></th>
        </tr>
        <?php if ($data1) { ?>
            <?php $sl = 1; ?>
            <?php $poNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER); ?>
            <?php foreach ($data1 as $d1) { ?>
                <?php
                $condition = "po_no='" . $d1->po_no . "'";
                $purchaseItemsData = PurchaseItems::model()->findAll(array('condition' => $condition,), 'id');
                $count = 0;
                $margeRow = count($purchaseItemsData);
                ?>
                <?php foreach ($purchaseItemsData as $pid) { ?>
                    <?php $count++; ?>
                    <tr class="<?php
            if ($sl % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                        <td><?php echo $sl++; ?></td>
                        <?php if ($count == 1) { ?>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo $poNoFormat . $d1->po_no; ?></td>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $d1->issue_date; ?></td>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $d1->expected_receive_date; ?></td>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo Employees::model()->fullName($d1->order_by_id); ?></td>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo Suppliers::model()->supplierName($d1->supplier_id); ?></td>
                        <?php } ?>
                        <td class="textAlgnLeft"><?php echo ProdItems::model()->itemName($pid->item_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdBrands::model()->brandName($pid->brand_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdModels::model()->modelName($pid->model_id); ?></td>
                        <td><?php echo $pid->code; ?></td>
                        <td><?php echo $pid->purchase_price; ?></td>
                        <td><?php echo $pid->order_qty; ?></td>
                        <?php
                        $totalReceivedQuantity = 0;
                        $totalReturnQuantity = 0;
                        $criteria = new CDbCriteria();
                        $criteria->select = 'sum(received_qty) as orderedQty, sum(return_qty) AS totalReturnQty';
                        $criteria->group = 'code';
                        $criteria->condition = 'purchase_id=' . $pid->id;
                        $purchaseReceiveInfo = ReceivedPurchase::model()->findAll($criteria);
                        foreach ($purchaseReceiveInfo as $sdi):
                            $totalReceivedQuantity = $sdi->orderedQty;
                            $totalReturnQuantity = $sdi->totalReturnQty;
                        endforeach;
                        ?>
                        <td><?php echo $totalReceivedQuantity; ?></td>
                        <td><?php echo $totalReturnQuantity; ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php }else { ?>
            <tr>
                <td colspan="15"><div class="flash-error">No result found!</div></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="15" class="textAlgnLeft">SALES ORDER INFO <font color="red">(CANCELLED)</font> ORDERS ARE NOT SHOWING <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bottomThis.png" /></td>
        </tr>
        <!--                           SALE ORDER INFO STARTS-->
        <tr>
            <th>SL</th>
            <th>ORDER NO</th>
            <th>SALE DT</th>
            <th>AUTHORIZED BY</th>
            <th>CUSTOMER</th>
            <th>CAT</th>
            <th>SUB-CAT</th>
            <th>ITEM</th>
            <th>CODE</th>
            <th>SP</th>
            <th>SALE QT</th>
            <th>DISC</th>
            <th>OVRL DISC</th>
            <th>DELIV QT</th>
            <th>RET QT</th>
        </tr>
        <?php if ($data2) { ?>
            <?php $sl = 1; ?>
            <?php $soNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER); ?>
            <?php foreach ($data2 as $d2) { ?>
                <?php
                $discountOfThis = 0;
                $discountData = DiscountTab::model()->findByAttributes(array('no' => $d2->so_no, 'no_type' => DiscountTab::SALE_ORDER));
                if ($discountData) {
                    $discountOfThis = $discountData->discount;
                }
                ?>
                <?php
                $condition = "so_no='" . $d2->so_no . "'";
                $sellItemsData = SellItems::model()->findAll(array('condition' => $condition,), 'id');
                $count = 0;
                $margeRow = count($sellItemsData);
                ?>
                <?php foreach ($sellItemsData as $sid) { ?>
                    <?php $count++; ?>
                    <tr class="<?php
            if ($sl % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                        <td><?php echo $sl++; ?></td>
                        <?php if ($count == 1) { ?>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo $soNoFormat . $d2->so_no; ?></td>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $d2->date_of_sell; ?></td>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo Employees::model()->fullName($d2->authorized_by); ?></td>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo Customers::model()->customerName($d2->customer_id); ?></td>
                        <?php } ?>
                        <td class="textAlgnLeft"><?php echo ProdItems::model()->itemName($sid->item_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdBrands::model()->brandName($sid->brand_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdModels::model()->modelName($sid->model_id); ?></td>
                        <td><?php echo $sid->code; ?></td>
                        <td><?php echo $sid->sell_price; ?></td>
                        <td><?php echo $sid->sell_qty; ?></td>
                        <td><?php echo $sid->discount; ?></td>
                        <?php if ($count == 1) { ?>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $discountOfThis; ?></td>
                        <?php } ?>
                        <?php
                        $criteria2 = new CDbCriteria();
                        $criteria2->select = 'sum(return_qty) AS totalReturnQty, sum(delivery_qty) AS totalDeliveryQty';
                        $criteria2->condition = 'sell_items_id=' . $sid->id;
                        $sellDeliveryInfo = SellDelivery::model()->findAll($criteria2);
                        $totalDeliveryQuantity = 0;
                        $totalReturnQuantity = 0;
                        if ($sellDeliveryInfo) {
                            foreach ($sellDeliveryInfo as $sdi):
                                $totalDeliveryQuantity = $sdi->totalDeliveryQty;
                                $totalReturnQuantity = $sdi->totalReturnQty;
                            endforeach;
                        }
                        ?>
                        <td><?php echo $totalDeliveryQuantity; ?></td>
                        <td><?php echo $totalReturnQuantity; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php }else { ?>
            <tr>
                <td colspan="15"><div class="flash-error">No result found!</div></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="15" class="textAlgnLeft">QUICK SALES (Point Of Sales/POS) INFO <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/bottomThis.png" /></td>
        </tr>
        <!--                            QUICK SALE INFO STARTS-->
        <tr>
            <th>SL</th>
            <th>INV NO</th>
            <th>SALE DT</th>
            <th>INITIATED BY</th>
            <th>CAT</th>
            <th>SUB-CAT</th>
            <th>ITEM</th>
            <th>CODE</th>
            <th>SP</th>
            <th>SALE QT</th>
            <th>DISC</th>
            <th>OVRL DISC</th>
            <th>RET QT</th>
            <th></th>
            <th></th>
        </tr>
        <?php if ($data3) { ?>
            <?php $sl = 1; ?>
            <?php $qsoNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::QUICK_SELL); ?>
            <?php foreach ($data3 as $d3) { ?>
                <?php
                $discountOfThis = 0;
                $discountData = DiscountTab::model()->findByAttributes(array('no' => $d3->quick_sell_no, 'no_type' => DiscountTab::QUICK_SALE));
                if ($discountData) {
                    $discountOfThis = $discountData->discount;
                }
                ?>
                <?php
                $condition = "quick_sell_no='" . $d3->quick_sell_no . "'";
                $qsellItemsData = QuickSell::model()->findAll(array('condition' => $condition,), 'id');
                $count = 0;
                $margeRow = count($qsellItemsData);
                ?>
                <?php foreach ($qsellItemsData as $qsid) { ?>
                    <?php $count++; ?>
                    <tr class="<?php
            if ($sl % 2 == 0)
                echo "even"; else
                echo "odd";
                    ?>">
                        <td><?php echo $sl++; ?></td>
                        <?php if ($count == 1) { ?>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo $qsoNoFormat . $d3->quick_sell_no; ?></td>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $d3->date_of_sell; ?></td>
                            <td class="merge textAlgnLeft" rowspan="<?php echo $margeRow; ?>"><?php echo Employees::model()->fullName($d3->initiated_by); ?></td>
                        <?php } ?>
                        <td class="textAlgnLeft"><?php echo ProdItems::model()->itemName($qsid->item_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdBrands::model()->brandName($qsid->brand_id); ?></td>
                        <td class="textAlgnLeft"><?php echo ProdModels::model()->modelName($qsid->model_id); ?></td>
                        <td><?php echo $qsid->code; ?></td>
                        <td><?php echo $qsid->sell_price; ?></td>
                        <td><?php echo $qsid->qty; ?></td>
                        <td><?php echo $qsid->discount; ?></td>
                        <?php if ($count == 1) { ?>
                            <td class="merge" rowspan="<?php echo $margeRow; ?>"><?php echo $discountOfThis; ?></td>
                        <?php } ?>
                        <?php
                        $criteria3 = new CDbCriteria();
                        $criteria3->select = 'sum(return_qty) AS tReturnQty';
                        $criteria3->condition = 'quick_sell_id=' . $qsid->id;
                        $sellReturnInfo = QuickSellReturn::model()->findAll($criteria3);
                        $totalReturnQuantity = 0;
                        if ($sellReturnInfo) {
                            foreach ($sellReturnInfo as $srd):
                                $totalReturnQuantity = $srd->tReturnQty;
                            endforeach;
                        }
                        ?>
                        <td><?php echo $totalReturnQuantity; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php }else { ?>
            <tr>
                <td colspan="15"><div class="flash-error">No result found!</div></td>
            </tr>
        <?php } ?>
    </table>
</div>

