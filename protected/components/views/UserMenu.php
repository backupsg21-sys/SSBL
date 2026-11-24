<style>
    .portlet {
        font-size: 13.3px;
    }

    #nav a {
        line-height: 30px;
    }

    #nav ul {
        width: 250px;
    }

    #nav ul span, #nav ul li.last li span {
        padding: 8px 15px;
    }
</style>
<?php
$this->widget('application.extensions.mbmenu.MbMenu', array(
    'items' => array(
        array('label' => 'Dashboard', 'url' => array('/site/index')),
        array('label' => 'Software Configuration',
            'items' => array(
                array('label' => 'Company Informations', 'url' => array('/yourCompany/admin'), 'visible' => Yii::app()->user->checkAccess('YourCompany.Admin')),
                array('label' => 'Manage Users', 'url' => array('users/admin'), 'visible' => Yii::app()->user->checkAccess('Users.Admin')),
                array('label' => 'Manage Employees', 'url' => array('employees/admin'), 'visible' => Yii::app()->user->checkAccess('Employees.Admin')),
                array('label' => 'Add Employees', 'url' => array('employees/create'), 'visible' => Yii::app()->user->checkAccess('Employees.Create')),
                array('label' => 'DB BACKUP', 'url' => array('users/dbBackup'), 'visible' => Yii::app()->user->checkAccess('Users.DbBackup')),
                array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
            ),
        ),
        array('label' => 'VAT Management',
            'items' => array(
                array('label' => 'Product Category', 'url' => array('prodItems/admin'), 'visible' => Yii::app()->user->checkAccess('ProdItems.Admin')),
                array('label' => 'Product Sub-Category', 'url' => array('prodBrands/admin'), 'visible' => Yii::app()->user->checkAccess('ProdBrands.Admin')),
                array('label' => 'Add Products', 'url' => array('prodModels/create'), 'visible' => Yii::app()->user->checkAccess('ProdModels.Create')),
                array('label' => 'Manage Products', 'url' => array('prodModels/admin'), 'visible' => Yii::app()->user->checkAccess('ProdModels.Admin')),
                array('label' => 'Product Config (Input-Output Coefficient)', 'url' => array('materialRecipe/admin'), 'visible' => Yii::app()->user->checkAccess('MaterialRecipe.Admin')),
                //array('label' => 'Services', 'url' => array('services/admin'), 'visible' => Yii::app()->user->checkAccess('Services.Admin')),
                array('label' => 'Ports', 'url' => array('ports/admin'), 'visible' => Yii::app()->user->checkAccess('Ports.Admin')),
                array('label' => 'Units', 'url' => array('units/admin'), 'visible' => Yii::app()->user->checkAccess('Units.Admin')),
                array('label' => 'Treasury Purpose', 'url' => array('treasuryPurpose/admin'), 'visible' => Yii::app()->user->checkAccess('TreasuryPurpose.Admin')),
                array('label' => 'Suppliers', 'url' => array('suppliers/admin'), 'visible' => Yii::app()->user->checkAccess('Suppliers.Admin')),
                array('label' => 'Customers', 'url' => array('customers/admin'), 'visible' => Yii::app()->user->checkAccess('Customers.Admin')),
                array('label' => 'Store/Depot Setup', 'url' => array('stores/admin'), 'visible' => Yii::app()->user->checkAccess('Stores.Admin')),
                array('label' => 'Chart of Accounts', 'url' => array('chartOfAc/admin'), 'visible' => Yii::app()->user->checkAccess('ChartOfAc.Admin')),
            ),
        ),
        array('label' => 'Purchase',
            'items' => array(
                array('label' => 'Purchase (Local)', 'url' => array('purchaseItems/createPartsLocal'), 'visible' => Yii::app()->user->checkAccess('purchaseItems.CreatePartsLocal')),
                array('label' => 'Purchase (Import)', 'url' => array('purchaseItems/createPartsForeign'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.CreatePartsForeign')),
                array('label' => 'Manage Purchases', 'url' => array('purchaseItems/adminPartsLocal'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.AdminPartsLocal')),
                array('label' => 'Purchase VDS Follow Up', 'url' => array('vds/adminPurchaseVds'), 'visible' => Yii::app()->user->checkAccess('Vds.AdminPurchaseVds')),
            ),
        ),
        array('label' => 'Production',
            'items' => array(
                array('label' => 'Production Entry', 'url' => array('production/create'), 'visible' => Yii::app()->user->checkAccess('Production.Create')),
                array('label' => 'Production Manage', 'url' => array('production/admin'), 'visible' => Yii::app()->user->checkAccess('Production.Admin')),
            ),
        ),
        array('label' => 'Sales',
            'items' => array(
                array('label' => 'Sale Order Create', 'url' => array('sellItems/create'), 'visible' => Yii::app()->user->checkAccess('SellItems.Create')),
                array('label' => 'Manage Sale Order', 'url' => array('sellItems/admin'), 'visible' => Yii::app()->user->checkAccess('sellItems.Admin')),
            ),
        ),
        array('label' => 'Payment',
            'items' => array(
                array('label' => 'Create VAT Payment', 'url' => array('vatPayment/create'), 'visible' => Yii::app()->user->checkAccess('VatPayment.Create')),
                array('label' => 'Manage VAT Payment', 'url' => array('vatPayment/admin'), 'visible' => Yii::app()->user->checkAccess('VatPayment.Admin')),
                array('label' => 'Create VDS Certificate', 'url' => array('vdsPayment/create'), 'visible' => Yii::app()->user->checkAccess('VdsPayment.Create')),
                array('label' => 'Manage VDS Certificate', 'url' => array('vdsPayment/admin'), 'visible' => Yii::app()->user->checkAccess('VdsPaymentAdmin')),
                array('label' => 'Sales VDS Certificate Entry', 'url' => array('vds/adminSalesVds'), 'visible' => Yii::app()->user->checkAccess('Vds.AdminSalesVds')),
                array('label' => 'Open VDS Certificate(Sales) Entry', 'url' => array('vdsPayment/openVdsReceive'), 'visible' => Yii::app()->user->checkAccess('VdsPayment.OpenVdsReceive')),
                array('label' => 'Sales VDS Certificate Manage', 'url' => array('vdsPayment/adminReceive'), 'visible' => Yii::app()->user->checkAccess('VdsPayment.AdminReceive')),
            ),
        ),
        array('label' => 'Adjustment',
            'items' => array(
                array('label' => 'Add Credit Note (Increasing)', 'url' => array('sellItems/salesReturn'), 'visible' => Yii::app()->user->checkAccess('SellItems.SalesReturn')),
                array('label' => 'Credit Note Manage', 'url' => array('salesReturn/creditList'), 'visible' => Yii::app()->user->checkAccess('SalesReturn.CreditList')),
                array('label' => 'Add Debit Note (Decreasing)', 'url' => array('purchaseItems/purchaseReturn'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.PurchaseReturn')),
                array('label' => 'Debit Note Manage', 'url' => array('purchaseReturn/debitList'), 'visible' => Yii::app()->user->checkAccess('PurchaseReturn.DebitList')),
                array('label' => 'Trasfer Challan Entry', 'url' => array('inventory/stockTransfer'), 'visible' => Yii::app()->user->checkAccess('Inventory.StockTransfer')),
                array('label' => 'Trasfer Challan Manage', 'url' => array('stockTransferHistory/admin'), 'visible' => Yii::app()->user->checkAccess('StockTransferHistory.Admin')),
                array('label' => 'Inventory Stock', 'url' => array('inventory/admin'), 'visible' => Yii::app()->user->checkAccess('Inventory.admin')),
                array('label' => 'Adjustment Entry', 'url' => array('adjustments/create'), 'visible' => Yii::app()->user->checkAccess('Adjustments.Create')),
                array('label' => 'Manage Adjustment', 'url' => array('adjustments/admin'), 'visible' => Yii::app()->user->checkAccess('Adjustments.Admin')),
            ),
        ),
        array('label' => 'Reports',
            'items' => array(
                array('label' => 'Mushak',
                    'items' => array(
                        array('label' => 'Mushak 4.3 (Input-Output Coefficient)', 'url' => array('purchaseItems/mushakFourThree'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakFourThree')),
                        array('label' => 'Mushak 6.1 (Purchase Book)', 'url' => array('purchaseItems/mushakSixOne'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixOne')),
                        array('label' => 'Mushak 6.2 (Sales Book)', 'url' => array('purchaseItems/mushakSixTwoManufacturing'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixTwoManufacturing')),
                        array('label' => 'Mushak 6.2.1 (Sale-Purchase Book)', 'url' => array('purchaseItems/mushakSixTwoOne'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixTwoOne')),
                        array('label' => 'Mushak 6.3 (Tax Challan)', 'url' => array('purchaseItems/mushakSixThree'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixThree')),
                        array('label' => 'Mushak 6.4 (Contractual Production Challan)', 'url' => array('purchaseItems/mushakSixFour'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixFour')),
                        array('label' => 'Mushak 6.5 (Transfer Challan)', 'url' => array('purchaseItems/mushakSixFive'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixFive')),
                        array('label' => 'Mushak 6.6 (VDS Certificate)', 'url' => array('purchaseItems/mushakSixSix'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixSix')),
                        array('label' => 'Mushak 6.7 (Credit Note)', 'url' => array('purchaseItems/mushakSixSeven'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixSeven')),
                        array('label' => 'Mushak 6.8 (Debit Note)', 'url' => array('purchaseItems/mushakSixEight'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakSixEight')),
                        //array('label' => 'Mushak 6.9 (Turnover Challan)', 'url' => array('purchaseitems/mushakSixEight'), 'visible' => Yii::app()->user->checkAccess('Purchaseitems.MushakSixEight')),
                        array('label' => 'Mushak 6.10 (Sale-Purchase of Over 2 Lac)', 'url' => array('purchaseItems/mushakSixTen'), 'visible' => Yii::app()->user->checkAccess('Purchaseitems.MushakSixTen')),
                        array('label' => 'Mushak 9.1 (VAT Return)', 'url' => array('purchaseItems/mushakNineOne'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakNineOne')),
                        array('label' => 'Mushak 9.1 (VAT Return Save)', 'url' => array('purchaseItems/mushakNineOneCreate'), 'visible' => Yii::app()->user->checkAccess('Purchaseitems.MushakNineOneCreate')),
                        array('label' => 'TR-6 Challan', 'url' => array('purchaseItems/mushakTRSix'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.MushakNineOneCreate')),
                    ),
                ),
                array('label' => 'Reports',
                    'items' => array(
                        //array('label' => 'Mushak 18', 'url' => array('purchaseitems/mushakEighteen'), 'visible' => Yii::app()->user->checkAccess('Purchaseitems.MushakEighteen')),
                        array('label' => 'Purchase Report', 'url' => array('purchaseItems/purchaseItemReport'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.PurchaseItemReport')),
                        array('label' => 'Sales Report', 'url' => array('sellItems/salesItemReport'), 'visible' => Yii::app()->user->checkAccess('SellItems.SalesItemReport')),
                        array('label' => 'Purchase Details Report', 'url' => array('purchaseItems/purchaseCustomReport'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.PurchaseCustomReport')),
                        array('label' => 'Sales Details Report', 'url' => array('sellItems/salesCustomReport'), 'visible' => Yii::app()->user->checkAccess('SellItems.SalesCustomReport')),
                        array('label' => 'RM Stock Report', 'url' => array('purchaseItems/purchaseReport'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.PurchaseReport')),
                        array('label' => 'FG Stock Report', 'url' => array('sellItems/salesReport'), 'visible' => Yii::app()->user->checkAccess('SellItems.SalesReport')),
                        array('label' => 'Debit Note Issue Report', 'url' => array('purchaseReturn/debitReport'), 'visible' => Yii::app()->user->checkAccess('PurchaseReturn.DebitReport')),
                        array('label' => 'Credit Note Issue Report', 'url' => array('salesReturn/creditReport'), 'visible' => Yii::app()->user->checkAccess('SalesReturn.CreditReport')),
                        array('label' => 'Adjustment Report', 'url' => array('adjustments/report'), 'visible' => Yii::app()->user->checkAccess('Adjustments.Report')),
                        //array('label' => 'Current Acc. Adjustment Report', 'url' => array('purchaseItems/currentAccAdjReport'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.CurrentAccAdjReport')),
                        array('label' => '9.1 Summary Report', 'url' => array('purchaseItems/nineOneSummary'), 'visible' => Yii::app()->user->checkAccess('PurchaseItems.NineOneSummary')),
                        array('label' => 'Product Wise Sales', 'url' => array('sellItems/productWiseSales'), 'visible' => Yii::app()->user->checkAccess('SellItems.productWiseSales')),
                        array('label' => 'Monthly Statement Report', 'url' => array('sellItems/monthlyStatement'), 'visible' => Yii::app()->user->checkAccess('SellItems.monthlyStatement')),
                        array('label' => 'Monthly Statement Report(Finished Goods)', 'url' => array('sellItems/monthlyStatementFg'), 'visible' => Yii::app()->user->checkAccess('SellItems.monthlyStatementFg')),
                    ),
                ),
            ),
        ),
        //array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout')),
    ),
));
?>