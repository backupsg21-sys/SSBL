
<?php if ($data) { ?>
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
<?php } ?>

<div class='printAllTableForThisReport'>
    <table class="reportTab">
        <thead>
            <tr class="noborderTr">
                <td colspan="11"><?php echo $message; ?></td>
            </tr>
            <tr>
                <th style="width: 20px;">S.L</th>
                <th>Item</th>
                <th>Cost</th>
                <th>Revenue</th>
                <th>Profit/Loss</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $type = PpSelectionType::model()->activeMethod();
            $profitLossTotalPrevious = 0;
            $prodProfitLossTotalPrevious = 0;
            $sellAmountTotalPrevious = 0;
            $costingAmountTotalPrevious = 0;
            $costingAmountPrevious = 0;
            $sellAmountPrevious = 0;
            if ($dataPrevious) {

                foreach ($dataPrevious as $d) {
                    $qsellAmountPrevious = QuickSellReturn::model()->totalQtyOfThisItemPreviousDate($d->model_id, $startDate);
                    $qsellQtyPrevious = QuickSellReturn::model()->totalQtyOfThisItemPreviousDateQty($d->model_id, $startDate);
                    $sellAmountPrevious = SellDelivery::model()->totalDeliveryAndReturnOfThisItemPreviousDate($d->model_id, $startDate);
                    $sellQtyPrevious = SellDelivery::model()->totalDeliveryAndReturnOfThisItemPreviousDateQty($d->model_id, $startDate);

                    $sellAmountTotalPrevious = $sellAmountTotalPrevious + $sellAmountPrevious + $qsellAmountPrevious;
                    $sellQtyTotalPrevious = $sellQtyPrevious + $qsellQtyPrevious;
                    $costingAmountPrevious = PurchasePrices::model()->purchasePriceOfThisPreviousDate($d->model_id, $sellQtyTotalPrevious, $type, $startDate);
                    $costingAmountTotalPrevious = $costingAmountTotalPrevious + $costingAmountPrevious;
                }
            }

            $prodProfitLossTotalPrevious = ($sellAmountTotalPrevious - $costingAmountTotalPrevious);
            ?>
            <tr class="even">
                <td colspan="2" class="textAlgnRight">Previous</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($costingAmountTotalPrevious)); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($sellAmountTotalPrevious)); ?></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($prodProfitLossTotalPrevious)); ?></td>
            </tr>
            <?php /**/ ?>
            <?php
            $i = 1;
            $profitLossTotal = 0;
            $sellAmountTotal = 0;
            $costingAmountTotal = 0;
            $qsellAmount = 0;
            ?>

            <?php if ($data) { ?>
                <?php foreach ($data as $d) { ?>
                    <?php $qsellAmount = QuickSellReturn::model()->totalQtyOfThisItem($d->model_id, $startDate, $endDate); ?>
                    <?php $qsellQty = QuickSellReturn::model()->totalQtyOfThisItemQty($d->model_id, $startDate, $endDate); ?>
                    <?php $sellAmount = SellDelivery::model()->totalDeliveryAndReturnOfThisItem($d->model_id, $startDate, $endDate); ?>
                    <?php $sellQty = SellDelivery::model()->totalDeliveryAndReturnOfThisItemQty($d->model_id, $startDate, $endDate); ?>
                    <?php $totalSellQty = $qsellQty + $sellQty; ?>
                    <?php $totalSellAmount = $qsellAmount + $sellAmount; ?>
                    <?php $costingAmount = PurchasePrices::model()->purchasePriceOfThis($d->model_id, $totalSellQty, $type, $startDate, $endDate); ?>
                    <tr class="
                    <?php
                    if ($i % 2 == 0)
                        echo 'even';
                    else
                        echo 'odd';
                    ?>
                        ">
                        <td><?php echo $i++; ?></td>
                        <td class="textAlgnLeft"><?php echo "Product: " . ProdModels::model()->modelName($d->model_id); ?></td>
                        <td class="textAlgnRight"><?php echo number_format(floatval($costingAmount)); ?></td>
                        <td class="textAlgnRight"><?php echo number_format(floatval($totalSellAmount)); ?></td>
                        <td class="textAlgnRight">
                            <?php
                            $profitLoss = $totalSellAmount - $costingAmount;
                            echo number_format(floatval($profitLoss));
                            ?>
                        </td>
                    </tr>
                    <?php $sellAmountTotal = $sellAmountTotal + $totalSellAmount; ?>
                    <?php $costingAmountTotal = $costingAmountTotal + $costingAmount; ?>
                    <?php $profitLossTotal = $profitLossTotal + $profitLoss; ?>
                <?php } ?>
            <?php } ?>

            <tr class="<?php
            if ($i % 2 == 0)
                echo "even"; else
                echo "odd";
            ?>">
                <td colspan="2" class="textAlgnRight">Total Cost</td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($costingAmountTotal)); ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="<?php
                $i++;
                if ($i % 2 == 0)
                    echo "even"; else
                    echo "odd";
            ?>">
                <td colspan="2" class="textAlgnRight">Total Revenue</td>
                <td></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($sellAmountTotal)); ?></td>
                <td></td>
            </tr>
            <tr class="<?php
                $i++;
                if ($i % 2 == 0)
                    echo "even"; else
                    echo "odd";
            ?>">
                <td colspan="2" class="textAlgnRight">Total Profit/Loss</td>
                <td></td>
                <td></td>
                <td class="textAlgnRight heightLight"><?php echo number_format(floatval($profitLossTotal)); ?></td>
            </tr>
            <tr class="<?php
                $i++;
                if ($i % 2 == 0)
                    echo "even"; else
                    echo "odd";
            ?>">
                <td colspan="2" class="textAlgnRight">Net</td>
                <td></td>
                <td></td>
                <td class="textAlgnRight heightLight">
                    <?php
                    $gorssProfitLoss = $profitLossTotal + $prodProfitLossTotalPrevious;
                    echo number_format(floatval($gorssProfitLoss));
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

