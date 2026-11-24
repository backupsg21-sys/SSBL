<?php
$criteriaUserWithStore = new CDbCriteria();
$criteriaUserWithStore->select = "user_id";
$criteriaUserWithStore->group = "user_id";
$dataUserWithStore = UserStore::model()->findAll($criteriaUserWithStore);

$superUserData = Users::model()->allUsersOfAParticularRole("SuperAdmin");
?>
<h2>User Transactions</h2>
<div class="grid-view">
    <table class="items">
        <tr>
            <th>User (Employee Name)</th>
            <th>User Level</th>
            <th>Total Purchase Order Established</th>
            <th>Total Purchase Order Canceled</th>
            <th>Total Sale Order Established</th>
            <th>Total Sale Order Canceled</th>
            <th>Total POS</th>
        </tr>
        <?php if ($superUserData) { ?>
            <?php foreach ($superUserData as $sud) { ?>
                <?php
                // purchase order
                $criteriaPEUSU = new CDbCriteria();
                $criteriaPEUSU->condition = "initiated_by=" . $sud . " AND order_status =" . PurchaseItems::ESTABLISHED;
                $dataPEUSU = PurchaseItems::model()->findAll($criteriaPEUSU);
                $countPEUSU = count($dataPEUSU);

                $criteriaPCUSU = new CDbCriteria();
                $criteriaPCUSU->condition = "initiated_by=" . $sud . " AND order_status =" . PurchaseItems::CANCELED;
                $dataPCUSU = PurchaseItems::model()->findAll($criteriaPCUSU);
                $countPCUSU = count($dataPCUSU);

                $criteriaSEUSU = new CDbCriteria();
                $criteriaSEUSU->condition = "initiated_by=" . $sud . " AND order_status =" . SellItems::ESTABLISHED;
                $dataSEUSU = SellItems::model()->findAll($criteriaSEUSU);
                $countSEUSU = count($dataSEUSU);

                $criteriaSCUSU = new CDbCriteria();
                $criteriaSCUSU->condition = "initiated_by=" . $sud . " AND order_status =" . SellItems::CANCELED;
                $dataSCUSU = SellItems::model()->findAll($criteriaSCUSU);
                $countSCUSU = count($dataSCUSU);

                $criteriaQSEUSU = new CDbCriteria();
                $criteriaQSEUSU->condition = "initiated_by=" . $sud;
                $dataQSEUSU = QuickSell::model()->findAll($criteriaQSEUSU);
                $countQSEUSU = count($dataQSEUSU);

                $empNameSU = Users::model()->fullNameOfThis($sud);
                $roleNameSU = "SuperAdmin";
                ?>
                <tr>
                    <td><?php echo $empNameSU; ?></td>
                    <td><?php echo "<span class='userLevelSpan' style='background-color: red;'>" . $roleNameSU . "</span>"; ?></td>
                    <td><?php echo number_format(floatval($countPEUSU)); ?></td>
                    <td><?php echo number_format(floatval($countPCUSU)); ?></td>
                    <td><?php echo number_format(floatval($countSEUSU)); ?></td>
                    <td><?php echo number_format(floatval($countSCUSU)); ?></td>
                    <td><?php echo number_format(floatval($countQSEUSU)); ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        <?php if ($dataUserWithStore) { ?>
            <?php foreach ($dataUserWithStore as $duws) { ?>
                <?php
                // purchase order
                $criteriaPEU = new CDbCriteria();
                $criteriaPEU->condition = "initiated_by=" . $duws->user_id . " AND order_status =" . PurchaseItems::ESTABLISHED;
                $dataPEU = PurchaseItems::model()->findAll($criteriaPEU);
                $countPEU = count($dataPEU);

                $criteriaPCU = new CDbCriteria();
                $criteriaPCU->condition = "initiated_by=" . $duws->user_id . " AND order_status =" . PurchaseItems::CANCELED;
                $dataPCU = PurchaseItems::model()->findAll($criteriaPCU);
                $countPCU = count($dataPCU);

                $criteriaSEU = new CDbCriteria();
                $criteriaSEU->condition = "initiated_by=" . $duws->user_id . " AND order_status =" . SellItems::ESTABLISHED;
                $dataSEU = SellItems::model()->findAll($criteriaSEU);
                $countSEU = count($dataSEU);

                $criteriaSCU = new CDbCriteria();
                $criteriaSCU->condition = "initiated_by=" . $duws->user_id . " AND order_status =" . SellItems::CANCELED;
                $dataSCU = SellItems::model()->findAll($criteriaSCU);
                $countSCU = count($dataSCU);

                $criteriaQSEU = new CDbCriteria();
                $criteriaQSEU->condition = "initiated_by=" . $duws->user_id;
                $dataQSEU = QuickSell::model()->findAll($criteriaQSEU);
                $countQSEU = count($dataQSEU);

                $empName = Users::model()->fullNameOfThis($duws->user_id);
                ?>
                <tr>
                    <td><?php echo $empName; ?></td>
                    <td><?php Users::model()->findAllRolesOfThisUser($duws->user_id); ?></td>
                    <td><?php echo number_format(floatval($countPEU)); ?></td>
                    <td><?php echo number_format(floatval($countPCU)); ?></td>
                    <td><?php echo number_format(floatval($countSEU)); ?></td>
                    <td><?php echo number_format(floatval($countSCU)); ?></td>
                    <td><?php echo number_format(floatval($countQSEU)); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
                <tr>
                    <td colspan="7"><div class='flash-error'>No store assigned to any user!</div></td>
                </tr>
        <?php } ?>
    </table>   
</div>
