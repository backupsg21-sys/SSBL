<div class="grid-view">
    <table class="items">
        <tr>
            <th>Incoming Stock Transfer Request Pending</th>
        </tr>
        <tr>         
            <?php
            $activeStoresOfThisUser = UserStore::model()->assignedActiveStoresOfThisLoggedInUserAllStores();
            if ($activeStoresOfThisUser) {
                $count = 0;
                foreach ($activeStoresOfThisUser as $ast):
                    $condition = 'is_received=' . StockTransferHistory::PENDING . ' AND received_to_store_id=' . $ast->store_id;
                    $reqSentStockData = StockTransferHistory::model()->findAll(array('condition' => $condition,), 'id');
                    if ($reqSentStockData) {
                        $count = count($reqSentStockData) + $count;
                    }
                endforeach;
                if ($count > 0) {
                    echo '<td><div class="flash-notice">';
                    echo CHtml::image(Yii::app()->theme->baseUrl . "/images/notification.ico", '', array('style' => 'position: relative; top: 4px; right: 4px;'));
                    echo "You have " . $count . " stock transfer request";
                    if ($count > 1)
                        echo "s";
                    echo " pending. Click ";
                    echo CHtml::link('Here', array('/stockTransferHistory/adminReqReceive'), array('style' => 'text-decoration: underline;'));
                    echo '</div></td>';
                }else {
                    echo "<td><div class='flash-error'>No Incoming Stock Requests!</div></td>";
                }
            } else {
                echo "<td><div class='flash-error'>No Incoming Stock Request!</div></td>";
            }
            ?>

        </tr>
    </table>
</div>