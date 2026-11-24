<?php

$type = 0;
$fifoData = PpSelectionType::model()->findByPk(PpSelectionType::FIFO);
$lifoData = PpSelectionType::model()->findByPk(PpSelectionType::LIFO);
if ($fifoData->is_active == PpSelectionType::ACTIVE) {
    $type = 1; // FIFO ACTIVE
}
if ($lifoData->is_active == PpSelectionType::ACTIVE) {
    $type = 2; // LIFO ACTIVE
}
$year = date('Y');
//$year = 2015;
$type = PpSelectionType::model()->activeMethod();

$profitLossTotalPrevious = 0;
$prodProfitLossTotalPrevious = 0;
$sellAmountTotalPrevious = 0;
$costingAmountTotalPrevious = 0;
$costingAmountPrevious = 0;
$sellAmountPrevious = 0;
$qsellAmountPrevious = 0;
$startDate = $year . "-01-01";

$dataPrevious = Inventory::model()->transactionReportPrevious($startDate);

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
<?php

$totalCost = array();
$totalRevenue = array();
$totalProfitLoss = array();
$i = 1;
$totalSA = 0;
$totalCA = 0;
?>
<?php for ($month = 1; $month <= 12; $month++) { ?>
    <?php

    $startDate = $year . "-" . $month . "-01";
    $endDate = $year . "-" . $month . "-31";

    $data = Inventory::model()->transactionReport($startDate, $endDate);

    $sellAmountTotal = 0;
    $costingAmountTotal = 0;
    $sellAmount = 0;
    $costingAmount = 0;
    $qsellAmount = 0;
    if ($data) {
        foreach ($data as $d) {
            $qsellAmount = QuickSellReturn::model()->totalQtyOfThisItem($d->model_id, $startDate, $endDate);
            $qsellQty = QuickSellReturn::model()->totalQtyOfThisItemQty($d->model_id, $startDate, $endDate);
            $sellAmount = SellDelivery::model()->totalDeliveryAndReturnOfThisItem($d->model_id, $startDate, $endDate);
            $sellQty = SellDelivery::model()->totalDeliveryAndReturnOfThisItemQty($d->model_id, $startDate, $endDate);
            $totalSellQty = $qsellQty + $sellQty;
            $totalSellAmount = $qsellAmount + $sellAmount;
            $costingAmount = PurchasePrices::model()->purchasePriceOfThis($d->model_id, $totalSellQty, $type, $startDate, $endDate);
            $sellAmountTotal = $sellAmountTotal + $totalSellAmount;
            $costingAmountTotal = $costingAmountTotal + $costingAmount;
        }
    }

    $totalCost[] = $costingAmountTotal;
    $totalRevenue[] = $sellAmountTotal;
    $totalProfitLoss[] = ($sellAmountTotal - $costingAmountTotal);
    $totalSA = $sellAmountTotal + $totalSA;
    $totalCA = $costingAmountTotal + $totalCA;
    ?>

<?php } ?>

<?php

$total = ($totalSA - $totalCA);
echo "<hr><div style='color: #FFFFFF; border: 1px solid; background-color: #FF0000; padding: 4px 0px;'>TOTAL PROFIT/LOSS: (PREVIOUS YEARS) <font style='color: #FFFF00; font-weight: bold; font-size: 16px;'>" . number_format(floatval($prodProfitLossTotalPrevious)) . " TK</font></div>";

$this->Widget('ext.highcharts.HighchartsWidget', array(
    'options' => array(
        'title' => array('text' => 'PROFIT/LOSS CHART- ' . $year),
        'xAxis' => array(
            'categories' => array('JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC')
        ),
        'yAxis' => array(
            'title' => array('text' => 'AMOUNT'),
        ),
        'series' => array(
            array('name' => 'PROFIT/LOSS', 'data' => $totalProfitLoss),
            array('name' => 'REVENUE', 'data' => $totalRevenue),
            array('name' => 'COST', 'data' => $totalCost),
        ),
        'tooltip' => array(
            'valueSuffix' => ' TK'
        ),
    ),
    'htmlOptions' => array(
    ),
    // register additional scripts
    'scripts' => array('highcharts-more', 'modules/exporting', 'adapters/mootools-adapter'),
    // register themes
    'themes' => array('dark-green'),
));

echo "<hr><div style='float: left; width: 100%; color: #FFFFFF; border: 1px solid; background-color: #FF0000; padding: 4px 0px;'>TOTAL PROFIT/LOSS: <font style='color: #FFFF00; font-weight: bold; font-size: 16px;'>" . number_format(floatval($total + $prodProfitLossTotalPrevious)) . " TK</font></div>";
?>
