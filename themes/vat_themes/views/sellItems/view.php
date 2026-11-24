<?php
$lineTotalWithODGrand = 0;
foreach ($data1 as $d1):
    $subTotal = 0;
    $soGroupCount = 1;
    $condition = $condition2 . " AND so_no=" . $d1->so_no . " ORDER BY id DESC";
    $soData = SellItems::model()->findAll(array('condition' => $condition,), 'id');
    $rowspan = count($soData);
    $discountOfThis = 0;
    $discountData = DiscountTab::model()->findByAttributes(array('no' => $d1->so_no, 'no_type' => DiscountTab::SALE_ORDER));
    if ($discountData) {
        $discountOfThis = $discountData->discount;
    }

    foreach ($soData as $sod):
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

        if ($rowspan == $soGroupCount) {
            $lineTotalWithOD = DiscountTab::model()->lineTotalWithDiscount($subTotal, $discountOfThis);
            $lineTotalWithODGrand = $lineTotalWithOD + $lineTotalWithODGrand;
        }
        $soGroupCount++;
    endforeach;
endforeach;

return $lineTotalWithODGrand;
?>