<?php
$data = PurchaseItems::nineOneSummary("","",date('Y-m-01'));
?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $totalPurchaseOfThiMonth ?></h3>
                <p>Total Purchase (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-done-all"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green-active">
            <div class="inner">
                <h3><?= $totalSalesOfThiMonth ?></h3>
                <p>Total Sales (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= !empty($data) ? $data['fiftyeight'] :'' ?></h3>
                <p>Total VAT Paid (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= !empty($data)? abs($data['sixtyfive']):'' ?></h3>
                <p>Total VAT Payable (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= !empty($data) ? $data['twentyfour'] : '' ?></h3>
                <p>Total VDS Payable (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3><?= !empty($data) ? $data['fiftynine'] : '' ?></h3>
                <p>Total SD Paid (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= !empty($data) ? $data['sixtysix'] : '' ?></h3>
                <p>Total SD Payable (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?= !empty($data) ? $data['sixtyseven'] : '' ?></h3>
                <p>Total Claimed Refund (<?php echo date("M-Y", strtotime($today)) ?>)</p>
            </div>
        </div>
    </div>
    <!-- ./col -->

</div>