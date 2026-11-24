<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/gridview/styles.css" type="text/css"
      media="screen"/>

<?php /*$this->renderPartial('_basicSetup');*/ ?>

<div style="    font-family: tahoma;
    text-align: center;
    padding: 30px;
    font-size: 20px;
    color: #01b501;">Welcome to VAT ACCOUNTING SOFTWARE</div>


<?php

$userId = Yii::app()->user->getId();

// if ($userId == 1) {//shob user er jonno dashboard open kora hoiche as per IMRAN BHAI's command
    $today = date("Y-m-d");
    $totalPurchaseOfThiMonth = $this->totalPurchaseOfThisMonth();
    $totalSalesOfThiMonth = $this->totalSalesOfThisMonth();
    $totalVdsOfThisMonth = $this->totalVdsOfMonth();
    $totalVatPaidOfMonth = $this->totalVatPaidOfMonth();
    $totalPaymentOfThisMonth = $this->totalVatPayableOfMonth();
    ?>
    <div style="width: 100%">
        <?php
        $this->renderPartial('column-widget', array(
            'totalPurchaseOfThiMonth' => $totalPurchaseOfThiMonth,
            'totalSalesOfThiMonth' => $totalSalesOfThiMonth,
            'totalVdsOfThisMonth' => $totalVdsOfThisMonth,
            'totalVatPaidOfMonth' => $totalVatPaidOfMonth,
            'totalPaymentOfThisMonth' => $totalPaymentOfThisMonth,
            'today' => $today
        ));
        ?>
    </div>
    <div style="clear:both"></div>
    <div style="width: 100%">
        <?php
        $vatPayable = $this->vatPayable();
        $vatPaidAmount = $this->vatPaidAmount();
        $totalPurchaseAmount = $this->totalPurchaseAmount();
        $totalSalesAmount = $this->totalSalesAmount();
        echo $this->renderPartial('charts', array(
            'vatPayable' => $vatPayable,
            'vatPaidAmount' => $vatPaidAmount,
            'totalPurchaseAmount' => $totalPurchaseAmount,
            'totalSalesAmount' => $totalSalesAmount,
        ));
        ?>
    </div>
    <?php
// }
?>