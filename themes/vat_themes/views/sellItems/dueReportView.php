<?php
if ($data1) {
    ?>
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
<?php }
?>
<div class='printAllTableForThisReport'>
    <table class="reportTab" id="table-1">
        <thead>
            <tr>
                <td colspan="21" style="font-size:16px; font-weight:bold; padding:10px 0px 10px; color:#000;"><?php echo $message; ?></td>
            </tr>
            <tr class="titlesTr">
                <th style="width: 2%;">Sl</th>
                <th style="width: 2%;">SO No.</th>
                <th style="width: 2%;">Date</th>
                <th style="width: 3%;">Acount Holder</th>
                <th style="width: 3%;">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
//                echo "<pre>";
//                var_dump($data1);
//                exit;
            ?>
            <?php if (!$data1) { ?>
                <tr>
                    <td colspan="16"><div class="flash-error">No result found!</div></td>
                </tr>
                <?php
            } else {


                $gtAmount = 0;

                $i = 1;
                foreach ($data1 as $salesItem) {
                    $totalBill = SellItems::model()->totalSalePriceBySo($salesItem->so_no);

                    $criteria = new CDbCriteria();
                    $criteria->select = "sum(amount) as sumPayment, sum(discount) as sumDiscount";
                    $criteria->condition = "order_no=" . $salesItem->so_no;
                    $dataMoney = MoneyReceipt::model()->findAll($criteria);
                    if ($dataMoney) {
                        $totalPaidAmount = floor(end($dataMoney)->sumPayment);
                        $totalDiscountAmount = floor(end($dataMoney)->sumDiscount);
                    }
                    $noType = DiscountTab::SALE_ORDER;

                    $sql = "SELECT * FROM discount_tab where no = '{$salesItem->so_no}' AND no_type = '{$noType}' order by id DESC";
                    $connection = Yii::app()->db;
                    $command = $connection->createCommand($sql);
                    $data = $command->queryRow();
                    $discountTab = 0;
                    if ($data['discount']) {
                        $discountTab = $data['discount'];
                    }

                    $dueAmounts = $totalBill - ($totalPaidAmount + $totalDiscountAmount + $discountTab);
                    $totalDueAmount = number_format($dueAmounts);

                    if ($dueAmounts > 0) {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $salesItem->so_no; ?></td>
                            <td><?php echo $salesItem->date_of_sell; ?></td>
                            <td><?php echo CHtml::encode(Customers::model()->customerName($salesItem->customer_id)) . '--' . $salesItem->customer_id; ?></td>
                            <td>
            <?php
            $gtAmount += $dueAmounts;
            echo $totalDueAmount;
            ?>
                            </td>
                        </tr>
            <?php
        }
        $i++;
    }
    ?>
                <tr>
                    <td colspan="4" style="text-align: right;"><b>Total Dues</b></td>
                    <td><b><?php echo number_format($gtAmount); ?></b></td>
                </tr>
    <?php
}
?>
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
