<?php

class InventoryController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights-
            JqueryProdStockByStore-
            JqueryProdStockSearch', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionCostingPriceList()
    {
        $model = new Inventory;

        $this->render('costingPriceList', array(
            'model' => $model,
        ));
    }

    public function actionCostingPriceListView()
    {
        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $modelId = $_POST['Inventory']['model_id'];
        if ($startDate != "" && $endDate != "" && $modelId != "") {
            $data = PurchasePrices::model()->costingPriceList($startDate, $endDate, $modelId);
            $itemname = ProdModels::model()->modelName($modelId);
            $message = "Costing Price List Of-<font style='font-weight: bold;'>" . $itemname . "</font> (" . $startDate . " to " . $endDate . ") Descending Order";
        } else {
            $data = "";
            $message = "Please select time range and product!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('costingPriceListView', array(
                'data' => $data,
                'message' => $message,
            ), true, true),
        ));
    }

    public function actionPurchaseSellCombinedReport()
    {
        $model = new Inventory;

        $this->render('purchaseSellCombined', array(
            'model' => $model,
        ));
    }

    public function actionPurchaseSellCombinedView()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];

        if ($startDate != "" && $endDate != "") {
            $criteria1 = new CDbCriteria();
            $criteria1->select = 'po_no, issue_date, expected_receive_date, order_by_id, supplier_id';
            $criteria1->addBetweenCondition('issue_date', $startDate, $endDate, 'AND');
            $criteria1->addColumnCondition(array('order_status' => PurchaseItems::ESTABLISHED), 'AND', 'AND');
            $criteria1->group = 'po_no';
            $data1 = PurchaseItems::model()->findAll($criteria1);

            $criteria2 = new CDbCriteria();
            $criteria2->select = 'so_no, date_of_sell, customer_id, authorized_by';
            $criteria2->addBetweenCondition('date_of_sell', $startDate, $endDate, 'AND');
            $criteria1->addColumnCondition(array('order_status' => SellItems::ESTABLISHED), 'AND', 'AND');
            $criteria2->group = 'so_no';
            $data2 = SellItems::model()->findAll($criteria2);

            $criteria3 = new CDbCriteria();
            $criteria3->select = 'quick_sell_no, date_of_sell, initiated_by';
            $criteria3->addBetweenCondition('date_of_sell', $startDate, $endDate, 'AND');
            $criteria3->group = 'quick_sell_no';
            $data3 = QuickSell::model()->findAll($criteria3);

            $message = "Purchase-Sales Combined Report (" . $startDate . " to " . $endDate . ")";

            echo CJSON::encode(array(
                'content' => $this->renderPartial('purchaseSellCombinedView', array(
                    'data1' => $data1,
                    'data2' => $data2,
                    'data3' => $data3,
                    'message' => $message,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select time range!</div>";
        }
    }

    public function actionProfitLoss()
    {
        $model = new Inventory;

        $this->render('profitLoss', array(
            'model' => $model,
        ));
    }

    public function actionProfitLossView()
    {
        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];

        if ($startDate != "" && $endDate != "") {


            $dataPrevious = Inventory::model()->transactionReportPrevious($startDate);

            $data = Inventory::model()->transactionReport($startDate, $endDate);

            $message = "Profit-Loss Statement (" . $startDate . " to " . $endDate . ")";

            echo CJSON::encode(array(
                'content' => $this->renderPartial('profitLossView', array(
                    'dataPrevious' => $dataPrevious,
                    'data' => $data,
                    'message' => $message,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select date range!</div>";
        }
    }

    // stock transfer
    public function actionStockTransfer()
    {
        $model = new StockTransferHistory();

        $this->render('stockTransfer', array(
            'model' => $model,
        ));
    }

    public function actionStockTransferView()
    {
        $storeId = $_POST['Inventory']['store_id'];
        $modelId = $_POST['Inventory']['id'];

        if ($storeId != '') {
            $criteria = new CDbCriteria();
            $criteria->select = 'id, store_id, model_id, sum(stock_in) as tStockIn, sum(stock_out) as tStockOut';

            if ($modelId != '')
                $criteria->addColumnCondition(array('store_id' => $storeId, 'model_id' => $modelId), 'AND', 'AND');

            $criteria->group = 'model_id';
            $criteria->order = 'id ASC';
            $data = Inventory::model()->findAll($criteria);
            $storeName = Stores::model()->storeName($storeId);
            $message = "Stock in Store: " . $storeName;
        } else {
            $data = "";
            $message = "Please select a store";
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        echo CJSON::encode(array(
            'content' => $this->renderPartial('stockTransferView', array(
                'data' => $data,
                'message' => $message,
                'storeId' => $storeId,
            ), true, true),
        ));
    }

    // product total stock
    public function actionJqueryProdStockByStore()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $search_stock = trim($_POST['search_stock']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addCondition(" brand_id!=7 ");
        $criteria->order = "model_name asc";
        $criteria->group = "model_name";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos && $search_stock) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM inventory where model_id = '$prodInfo->id' AND store_id = '$search_stock' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing))
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);

                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'closingStock' => $closingStock
                );
            }
        } else {
            //$results[] = array('value' => "0", 'label' => "No parts required");
        }
        echo json_encode($results);
    }

    public function actionStockReportInAmount()
    {
        $model = new Inventory;
        $this->render('stockReportInAmount', array(
            'model' => $model,
        ));
    }

    public function actionStockReportInAmountConf()
    {
        $model = new Inventory;
        $this->render('stockReportInAmountConf', array(
            'model' => $model,
        ));
    }


    public function actionStockReportViewInAmountConf()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $storeId = $_POST['Inventory']['store_id'];
        $itemId = $_POST['Inventory']['item_id'];
        $brandId = $_POST['Inventory']['brand_id'];
        $modelId = $_POST['Inventory']['model_id'];
        $showallornot = $_POST['Inventory']['code'];
        $showallornot = $_POST['showallornot'];

        if ($startDate != "" && $endDate != "") {
            $criteriaNow = new CDbCriteria();

            $criteriaNow->select = "Sum(Case When transaction_date < '" . $startDate . "' Then stock_in Else 0 End) as prevAllStockIn, 
										Sum(Case When transaction_date < '" . $startDate . "' Then stock_out Else 0 End) as prevAllStockOut, 
										Sum(Case When transaction_date >= '" . $startDate . "' and transaction_date <= '" . $endDate . "' Then stock_in Else 0 End) as tStockIn, 
										Sum(Case When transaction_date >= '" . $startDate . "' and transaction_date <= '" . $endDate . "' Then stock_out Else 0 End) as tStockOut, 
										item_id, brand_id, model_id, code, store_id, location_id";

            $message = "Actual Stock Report from: " . $startDate . " to: " . $endDate . " ALL STORES";
            $conditionarray = '';
            if ($storeId != "") {
                $conditionarray['store_id'] = $storeId;
                $storeName = Stores::model()->storeName($storeId);
                $message = "Stock Report from: " . $startDate . " to: " . $endDate . ", STORE:- " . $storeName;
            }
            if ($itemId != "") {
                $conditionarray['item_id'] = $itemId;
            }
            if ($brandId != "") {
                $conditionarray['brand_id'] = $brandId;
            }
            if ($modelId != "") {
                $conditionarray['model_id'] = $modelId;
            }

            if ($conditionarray != '') {
                $criteriaNow->addColumnCondition($conditionarray, 'AND', 'AND');
            }

            if ($showallornot == 'Current') {
                $criteriaNow->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
                $criteriaNow->group = 'model_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow);
            } else {
                $condition = "transaction_date>='$endDate'";
                $criteriaNow->group = 'model_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow, array('condition' => $condition,));
            }

        } else {
            $dataNow = '';
            $dataNowStockQty = '';
            $message = "Please select Start & End Date!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('stockReportViewInAmountConf', array(
                'dataNow' => $dataNow,
                'showallornot' => $showallornot,
                'message' => $message,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ), true, true),
        ));
    }

    public function actionStockReportViewInAmount()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $storeId = $_POST['Inventory']['store_id'];
        $itemId = $_POST['Inventory']['item_id'];
        $brandId = $_POST['Inventory']['brand_id'];
        $modelId = $_POST['Inventory']['model_id'];
        $showallornot = $_POST['Inventory']['code'];
        $showallornot = $_POST['showallornot'];

        if ($startDate != "" && $endDate != "") {
            $criteriaNow = new CDbCriteria();

            $criteriaNow->select = "Sum(Case When transaction_date < '" . $startDate . "' Then stock_in Else 0 End) as prevAllStockIn, 
										Sum(Case When transaction_date < '" . $startDate . "' Then stock_out Else 0 End) as prevAllStockOut, 
										Sum(Case When transaction_date >= '" . $startDate . "' and transaction_date <= '" . $endDate . "' Then stock_in Else 0 End) as tStockIn, 
										Sum(Case When transaction_date >= '" . $startDate . "' and transaction_date <= '" . $endDate . "' Then stock_out Else 0 End) as tStockOut, 
										item_id, brand_id, model_id, code, store_id, location_id";

            $message = "Stock Report from: " . $startDate . " to: " . $endDate . " ALL STORES";
            $conditionarray = '';
            if ($storeId != "") {
                $conditionarray['store_id'] = $storeId;
                $storeName = Stores::model()->storeName($storeId);
                $message = "Stock Report from: " . $startDate . " to: " . $endDate . ", STORE:- " . $storeName;
            }
            if ($itemId != "") {
                $conditionarray['item_id'] = $itemId;
            }
            if ($brandId != "") {
                $conditionarray['brand_id'] = $brandId;
            }
            if ($modelId != "") {
                $conditionarray['model_id'] = $modelId;
            }

            if ($conditionarray != '') {
                $criteriaNow->addColumnCondition($conditionarray, 'AND', 'AND');
            }

            if ($showallornot == 'Current') {
                $criteriaNow->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
                $criteriaNow->group = 'model_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow);
            } else {
                $condition = "transaction_date>='$endDate'";
                $criteriaNow->group = 'model_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow, array('condition' => $condition,));
            }

        } else {
            $dataNow = '';
            $dataNowStockQty = '';
            $message = "Please select Start & End Date!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('stockReportViewInAmount', array(
                'dataNow' => $dataNow,
                'showallornot' => $showallornot,
                'message' => $message,
                'storeId' => $storeId,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ), true, true),
        ));
    }

    public function actionStockReportDateWise()
    {
        $model = new Inventory;

        $this->render('stockReportDateWise', array(
            'model' => $model,
        ));
    }

    public function actionStockReportViewDateWise()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $storeId = $_POST['Inventory']['store_id'];
        $itemId = $_POST['Inventory']['item_id'];
        $brandId = $_POST['Inventory']['brand_id'];
        $modelId = $_POST['Inventory']['model_id'];
        $code = $_POST['Inventory']['code'];

        if ($startDate != "" && $endDate != "") {
            $criteriaNow = new CDbCriteria();
            $criteriaNow->select = 'sum(stock_in) as tStockIn, sum(stock_out) as tStockOut, item_id, brand_id, model_id, code, store_id';

            if ($storeId != "") {
                $storeName = Stores::model()->storeName($storeId);
                if ($itemId != "" && $brandId != "" && $modelId != "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'model_id' => $modelId, 'store_id' => $storeId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId != "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'store_id' => $storeId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId == "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'store_id' => $storeId), 'AND', 'AND');
                } else {
                    $criteriaNow->addColumnCondition(array('store_id' => $storeId), 'AND', 'AND');
                }
                $message = "Stock Report from: " . $startDate . " to: " . $endDate . ", STORE:- " . $storeName;
            } else {
                if ($itemId != "" && $brandId != "" && $modelId != "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'model_id' => $modelId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId != "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId == "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId), 'AND', 'AND');
                } else {

                }
                $message = "Stock Report from: " . $startDate . " to: " . $endDate . " ALL STORES";
            }
            $criteriaNow->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
            $criteriaNow->group = 'model_id';
            $criteriaNow->order = 'id ASC';
            $dataNow = Inventory::model()->findAll($criteriaNow);
        } else {
            $dataNow = '';
            $dataNowStockQty = '';
            $message = "Please select Start & End Date!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('stockReportViewDateWise', array(
                'dataNow' => $dataNow,
                'message' => $message,
                'startDate' => $startDate,
            ), true, true),
        ));
    }

    //============== Min Stock Report=================//

    public function actionMinStockReport()
    {
        $model = new Inventory;

        $this->render('minStockReport', array(
            'model' => $model,
        ));
    }

    public function actionMinStockReportView()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $storeId = $_POST['Inventory']['store_id'];
        $itemId = $_POST['Inventory']['item_id'];
        $brandId = $_POST['Inventory']['brand_id'];
        $modelId = $_POST['Inventory']['model_id'];
        $code = $_POST['Inventory']['code'];

        if ($startDate != "" && $endDate != "") {
            $criteriaNow = new CDbCriteria();
            $criteriaNow->select = 'sum(stock_in) as tStockIn, sum(stock_out) as tStockOut, item_id, brand_id, model_id, code, store_id';

            if ($storeId != "") {
                $storeName = Stores::model()->storeName($storeId);
                if ($itemId != "" && $brandId != "" && $modelId != "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'model_id' => $modelId, 'store_id' => $storeId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId != "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'store_id' => $storeId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId == "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'store_id' => $storeId), 'AND', 'AND');
                } else {
                    $criteriaNow->addColumnCondition(array('store_id' => $storeId), 'AND', 'AND');
                }
                $message = "Min Stock Report from: " . $startDate . " to: " . $endDate . ", STORE:- " . $storeName;
            } else {
                if ($itemId != "" && $brandId != "" && $modelId != "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId, 'model_id' => $modelId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId != "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId, 'brand_id' => $brandId), 'AND', 'AND');
                } else if ($itemId != "" && $brandId == "" && $modelId == "") {
                    $criteriaNow->addColumnCondition(array('item_id' => $itemId), 'AND', 'AND');
                } else {

                }
                $message = "Min Stock Report from: " . $startDate . " to: " . $endDate . " ALL STORES";
            }
            $criteriaNow->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
            $criteriaNow->group = 'model_id';
            $criteriaNow->order = 'id ASC';
            $dataNow = Inventory::model()->findAll($criteriaNow);
        } else {
            $dataNow = '';
            $dataNowStockQty = '';
            $message = "Please select Start & End Date!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('minStockReportView', array(
                'dataNow' => $dataNow,
                'message' => $message,
                'startDate' => $startDate,
            ), true, true),
        ));
    }

    //============== Min Stock Report End=================//

    public function actionStockQtyOfThisStore()
    {
        $store_id = $_POST['store_id'];
        $model_id = $_POST['model_id'];
        $existStockQty = 0;
        if (!empty($store_id) && !empty($model_id)) {
            $existStockQty = Inventory::model()->inventoryInfoOfThisModel($store_id, $model_id);

            if ($model_id != "") {
                $existStockQty = $existStockQty;
            } else {
                $existStockQty = 'select product!';
            }
        }
        echo CJSON::encode(array(
            'stockQty' => $existStockQty,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

        $model = new Inventory;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Inventory'])) {
            $model->attributes = $_POST['Inventory'];
            $model->setScenario('createScenario');
            $valid = $model->validate();
            if ($valid) {

                $inTime = $_POST['Inventory']['transaction_date'];
                if($_POST['Inventory']['is_opening'] != ""){
                    $inTime = date("2022-10-31");
                }
                $tType = $_POST['Inventory']['t_type'];
                $model->month = substr($inTime, 5, 2);
                $model->year = substr($inTime, 0, 4);

                if ($tType != '') {
                    $model->t_type = $tType;
                    $stockOut = $model->stock_in;
                    $model->stock_out = $stockOut;
                    $model->stock_in = 0;
                }

                // Save To Inventory
                $model->save();

                // Save To Purchase Price
                $modelPP = new PurchasePrices;
                $modelPP->po_no = $model->id;
                $modelPP->model_id = $model->model_id;
                $modelPP->purchase_price = $model->purchase_price;
                $modelPP->qty = $model->stock_in;
                $modelPP->date = $inTime;
                $modelPP->month = $model->month;
                $modelPP->year = $model->year;
                $modelPP->save();

                // Save To Common
                $product_info = ProdModels::model()->findByPk($model->model_id);
                $common = new Common();
                $common->model_id = $model->model_id;
                $common->date = $inTime;
                $common->type = Common::PURCHASE; // 1 = Purchase OR Stock In
                $common->parts_service = 1; // 1 = Parts
                $common->local_import = 54; //54=Local
                $common->order_qty = $model->stock_in;
                $common->unit_id = $product_info ? $product_info['unit_id'] : null;
                $common->unit_price = $model->purchase_price;
                $common->total = round($model->stock_in * $model->purchase_price, 2);
                $common->net_total = $common->total;
                $common->stock_in = $common->order_qty;
                $common->price_in = $common->total;
                $common->approve_count = 2;
                $common->is_opening = $model->is_opening;
                $common->save();

                echo CJSON::encode(array(
                    'status' => 'success',
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        } else {
            $this->render('admin', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if ($model->stock_out > 0) {
            Yii::app()->user->setFlash('error', "Can not update this info (It was an automatic process)!");
            $this->redirect(array('admin'));
        } else {
            $previousData = PurchasePrices::model()->findByAttributes(array('po_no' => $id));
            $purchasePrice = 0;
            if ($previousData) {
                $purchasePriceModel = PurchasePrices::model()->findByPk($previousData->id);
                $purchasePrice = $purchasePriceModel->purchase_price;
            }
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if (isset($_POST['Inventory'])) {
                $model->attributes = $_POST['Inventory'];
                $model->setScenario('createScenario');
                if ($model->save()) {

                    if ($previousData) {
                        $modelPP = $purchasePriceModel;
                        $modelPP->model_id = $model->model_id;
                        $modelPP->purchase_price = $model->purchase_price;
                        $modelPP->qty = $model->stock_in;
                        $modelPP->date = $model->transaction_date;
                        $modelPP->month = $model->month;
                        $modelPP->year = $model->year;
                        $modelPP->save();
                    }

                    $this->redirect(array('admin'));
                }
            }

            $this->render('update', array('model' => $model, 'purchasePrice' => $purchasePrice));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        //Commented for Data Inconsistency
        /*
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        */
    }


    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Inventory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Inventory']))
            $model->attributes = $_GET['Inventory'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Inventory::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'inventory-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDamageStockReportInAmount()
    {
        $model = new Inventory;
        $this->render('damageStockReportInAmount', array(
            'model' => $model,
        ));
    }

    public function actionDamageStockReportViewInAmount()
    {

        $startDate = $_POST['Inventory']['startDate'];
        $endDate = $_POST['Inventory']['endDate'];
        $storeId = $_POST['Inventory']['store_id'];
        $itemId = $_POST['Inventory']['item_id'];
        $brandId = $_POST['Inventory']['brand_id'];
        $modelId = $_POST['Inventory']['model_id'];
        $showallornot = $_POST['Inventory']['code'];
        $showallornot = $_POST['showallornot'];

        if ($startDate != "" && $endDate != "") {
            $criteriaNow = new CDbCriteria();

            //$criteriaNow->select = "*,sum(stock_out) as total_damage";

            $message = "Damage Report from: " . $startDate . " to: " . $endDate . " ALL STORES";
            $conditionarray = '';
            if ($storeId != "") {
                $conditionarray['store_id'] = $storeId;
                $storeName = Stores::model()->storeName($storeId);
                $message = "Damage Report from: " . $startDate . " to: " . $endDate . ", STORE:- " . $storeName;
            }
            if ($itemId != "") {
                $conditionarray['item_id'] = $itemId;
            }
            if ($brandId != "") {
                $conditionarray['brand_id'] = $brandId;
            }
            if ($modelId != "") {
                $conditionarray['model_id'] = $modelId;
            }
            $conditionarray['t_type'] = 2;
            if ($conditionarray != '') {
                $criteriaNow->addColumnCondition($conditionarray, 'AND', 'AND');
            }

            if (!isset($_POST['details_report']) || $_POST['details_report'] != 1) {
                $criteriaNow->group = 'model_id,store_id';
                $criteriaNow->select = "*,sum(stock_out) as total_damage";
            } else {
                $criteriaNow->select = "*,stock_out as total_damage";
            }

            if ($showallornot == 'Current') {
                $criteriaNow->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
                //$criteriaNow->group = 'model_id,store_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow);
            } else {
                $condition = "transaction_date>='$endDate'";
                //$criteriaNow->group = 'model_id,store_id';
                $criteriaNow->order = 'id ASC';
                $dataNow = Inventory::model()->findAll($criteriaNow, array('condition' => $condition,));
            }
        } else {
            $dataNow = '';
            $dataNowStockQty = '';
            $message = "Please select Start & End Date!";
        }

        echo CJSON::encode(array(
            'content' => $this->renderPartial('damageStockReportViewInAmount', array(
                'dataNow' => $dataNow,
                'showallornot' => $showallornot,
                'message' => $message,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ), true, true),
        ));
    }

    // Stock Qty Check
    public function actionJqueryProdStockSearch()
    {
        $search_prod = trim($_POST['search_prod']);
        $search_store = trim($_POST['search_store']);
        $stock = Inventory::model()->inventoryInfoOfThisModel($search_store, $search_prod);
        $result = array();
        $result['closingStock'] = $stock;
        echo json_encode($result);
    }

    public function actionProcessFileUploadProductStock()
    {

        $fileExist = file_exists(Yii::app()->basePath . '/../upload/tdl_products_stock.xls');
        if ($fileExist) {

            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/tdl_products_stock.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $zero_val = 0;
            $newlyInserted = 0;

            for ($row = 2; $row <= $highestRow; ++$row) {
                $item_id = NULL;
                $store_id = NULL;

                $product_category = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $product_sub_category = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
                $product_name = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
                $product_code = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
                //$hs_code = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                echo $store_name = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                $location = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
                $stock_qty = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
                $unit_name = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
                $purchase_price = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
                //$sell_price = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
                $purchase_price = (!empty($purchase_price)) ? $purchase_price : 0;

                if (!$stock_qty > 0)
                    continue;
                if ($product_category == "FGS") {
                    $item_id = 1;
                }else if ($product_category == "RM") {
                    $item_id = 2;
                }else if ($product_category == "PM") {
                    $item_id = 3;
                }else if ($product_category == "AIM") {
                    $item_id = 4;
                }

                $brand_id = ProdBrands::model()->getBrandIdByName($product_sub_category);
                $model_id = ProdModels::model()->getModelIdByCode($product_code);

                if ($store_name == "Satirpara FGS Store") {
                    $store_id = 1;
                }
                if ($store_name == "Satirpara Material Store") {
                    $store_id = 2;
                }
                $unit_id = Units::model()->getUnitIdByUnitName($unit_name);
                $transection_date = date("2022-04-10");

                $inventory_data_check = Inventory::model()->findByAttributes(['model_id' => $model_id, 'transaction_date' => $transection_date, 'store_id' => $store_id, 'stock_in' => $stock_qty]);//, ['condition' => 'stock_in > :stock_in','params' => ['stock_in' => 0 ]]
                if (!$inventory_data_check) {
                    // Save To Inventory
                    $model = new Inventory;
                    $model->transaction_date = $transection_date;
                    $model->store_id = $store_id;
                    $model->item_id = $item_id;
                    $model->brand_id = $brand_id;
                    $model->model_id = $model_id;
                    $model->code = $product_code;
                    $model->stock_in = $stock_qty;
                    $model->unit_id = $unit_id > 0 ? $unit_id : null;
                    $model->sell_price = $sell_price;
                    $model->month = substr($transection_date, 5, 2);
                    $model->year = substr($transection_date, 0, 4);
                    $model->save();

                    // Save To Purchase Price
                    $modelPP = new PurchasePrices;
                    $modelPP->po_no = $model->id;
                    $modelPP->model_id = $model->model_id;
                    $modelPP->purchase_price = $purchase_price;
                    $modelPP->qty = $model->stock_in;
                    $modelPP->date = $model->transaction_date;
                    $modelPP->month = $model->month;
                    $modelPP->year = $model->year;
                    $modelPP->save();

                    // Save To Common
                    $common = new Common();
                    $common->model_id = $model->model_id;
                    $common->date = $model->transaction_date;
                    $common->type = Common::PURCHASE; // 1 = Purchase OR Stock In
                    $common->parts_service = 1; // 1 = Parts
                    $common->local_import = 54; //54=Local
                    $common->order_qty = $model->stock_in;
                    $common->unit_id = $unit_id > 0 ? $unit_id : null;
                    $common->unit_price = $purchase_price;
                    $common->total = round($model->stock_in * $purchase_price, 2);
                    $common->net_total = $common->total;
                    $common->stock_in = $common->order_qty;
                    $common->price_in = $common->total;
                    $common->approve_count = 2;
                    $common->save();
                    echo $product_name . " stock qty = " . $stock_qty . " ...$unit_name .... $unit_id ....inserted successfully<br>";
                } else {
                    echo $product_name . " stock qty = " . $stock_qty . " Already Inserted<br>";
                }

                $newlyInserted++;
            }
        } else {
            echo 'FILE NOT FOUND!';
        }
    }
	
	public function actionProcessFileDataFinal()
    {
        $fileExist = file_exists(Yii::app()->basePath . '/../upload/inventory.xls');
        if ($fileExist) {

            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/inventory.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $zero_val = 0;
            $newlyInserted = 0;
            $stockUpdated = 0;

            for ($row = 2; $row <= $highestRow; ++$row) {

                $product_category = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $product_sub_category = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
                $product_name = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
                $product_code = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
                $store_name = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                $location = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
                $stock_qty = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
                $unit_name = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
                $purchase_price = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
                $purchase_price = (!empty($purchase_price)) ? $purchase_price : 0;


                if ($stock_qty > 0) {
                    $prodInfo = ProdModels::model()->findByAttributes(array('code' => $product_code));
                    if ($prodInfo) {
                        $inventoryInfo = Inventory::model()->findByAttributes(array('model_id' => $prodInfo->id));
                        if ($inventoryInfo) {
                            $inventoryInfo->stock_in = $inventoryInfo->stock_in + $stock_qty;
                            /*
                            if (!$inventoryInfo->validate()) {
                                $errores = $inventoryInfo->getErrors();
                                print_r($errores);
                                exit();
                            }*/

                            $inventoryInfo->save(false);
                            $stockUpdated++;
                        } else {
                            // Save To Inventory
                            $modelInventory = new Inventory();
                            $modelInventory->transaction_date = date("Y-m-d");
                            $modelInventory->store_id = 2; //Raw Material Store/Material Store
                            $modelInventory->item_id = $prodInfo->item_id;
                            $modelInventory->brand_id = $prodInfo->brand_id;
                            $modelInventory->model_id = $prodInfo->id;
                            $modelInventory->code = $product_code;
                            $modelInventory->stock_in = $stock_qty;
                            $modelInventory->unit_id = $prodInfo->unit_id;
                            // $model->sell_price = $sell_price;
                            $modelInventory->month = date("m");
                            $modelInventory->year = date("Y");

                            /*
                            if (!$modelInventory->validate()) {
                                $errores = $modelInventory->getErrors();
                                print_r($errores);
                                exit();
                            }*/

                            $modelInventory->save(false);

                            $newlyInserted++;

                            $modelPP = new PurchasePrices;
                            // $modelPP->po_no = $model->id;
                            $modelPP->model_id = $prodInfo->id;
                            $modelPP->purchase_price = $purchase_price;
                            $modelPP->qty = $modelInventory->stock_in;
                            $modelPP->date = $modelInventory->transaction_date;
                            $modelPP->month = $modelInventory->month;
                            $modelPP->year = $modelInventory->year;

                            /*
                            if (!$modelPP->validate()) {
                                $errores = $modelPP->getErrors();
                                print_r($errores);
                                exit();
                            }*/

                            $modelPP->save(false);

                            // Save To Common
                            $common = new Common();
                            $common->model_id = $prodInfo->id;
                            $common->date = $modelInventory->transaction_date;
                            $common->type = Common::PURCHASE; // 1 = Purchase OR Stock In
                            $common->parts_service = 1; // 1 = Parts
                            $common->local_import = 54; //54=Local
                            $common->order_qty = $modelInventory->stock_in;
                            $common->unit_id = $prodInfo->unit_id;
                            $common->unit_price = $purchase_price;
                            $common->total = round($modelInventory->stock_in * $purchase_price, 2);
                            $common->net_total = $common->total;
                            $common->stock_in = $common->order_qty;
                            $common->price_in = $common->total;
                            $common->approve_count = 2;
                            /*
                            if (!$common->validate()) {
                                $errores = $common->getErrors();
                                print_r($errores);
                                exit();
                            }*/

                            $common->save(false);
                        }
                    }
                }
            }

            echo "Newly Inventory Started : " . $newlyInserted . "<br>";
            echo "Stock Updated : " . $stockUpdated . "<br>";
        } else {
            echo 'FILE NOT FOUND!';
        }
    }
    public function  actionMissingProductionProcess(){
        $sql = "SELECT * FROM common WHERE production_id IS NOT NULL AND type=3 ORDER BY id ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($data as $item){
            $inventoryInfo = Inventory::model()->findByAttributes(array('production_id' => $item['production_id'], 'item_id' => 1, 'model_id' => $item['model_id']));

            if (count($inventoryInfo) == 0){
                //echo "Id : ".$item['id']." ProductionID ".$item['production_id']." ".$item['date']."<br>";
                $inventory = new Inventory();
                $inventory->production_id       = $item['production_id'];
                $inventory->model_id            = $item['model_id'];
                $inventory->unit_id             = $item['unit_id'];
                $inventory->stock_in            = $item['stock_in'];
                $inventory->store_id            = 1;
                $inventory->transaction_date    = $item['date'];
                $inventory->year                = date('Y', strtotime($item['date']));
                $inventory->month               = date('m', strtotime($item['date']));
                $inventory->save();
            }
        }
        echo "Successfully saved";
    }
    public function  actionMissingProductionProcess2(){
        $sql = "SELECT * FROM common WHERE production_id IS NOT NULL AND type=4 ORDER BY id ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data as $item){
            $inventoryInfo = Inventory::model()->findByAttributes(array('production_id' => $item['production_id'], 'item_id' => 2, 'model_id' => $item['model_id']));

            if (count($inventoryInfo) == 0){
                $inventory2 = new Inventory();
                $inventory2->production_id       = $item['production_id'];
                $inventory2->model_id            = $item['model_id'];
                $inventory2->unit_id             = $item['unit_id'];
                $inventory2->stock_out           = $item['stock_out'];
                $inventory2->store_id            = 1;
                $inventory2->transaction_date    = $item['date'];
                $inventory2->year                = date('Y', strtotime($item['date']));
                $inventory2->month               = date('m', strtotime($item['date']));
                $inventory2->save();
            }
        }
        echo "Successfully saved";
    }
    public function  actionMissingPurchaseProcess(){
        $sql = "SELECT * FROM common WHERE purchase_id IS NOT NULL AND type=1 ORDER BY id ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        //echo count($data); exit();
        foreach ($data as $item){
            $inventoryInfo = Inventory::model()->findByAttributes(array('purchase_id' => $item['purchase_id'], 'item_id' => 2, 'model_id' => $item['model_id']));

            if (count($inventoryInfo) == 0) {

                $modelInventory = new Inventory;
                $modelInventory->purchase_id = $item['purchase_id'];
                $modelInventory->purchase_details_id = 0;
                $modelInventory->store_id = Stores::FG_STORE;
                $modelInventory->location_id = Location::DEFAULT_LOCATION;
                $modelInventory->unit_id  = $item['unit_id'];
                $modelInventory->model_id = $item['model_id'];
                $modelInventory->stock_in = $item['order_qty'];
                $modelInventory->transaction_date  = $item['date'];
                $modelInventory->year   = date('Y', strtotime($item['date']));
                $modelInventory->month  = date('m', strtotime($item['date']));
                $modelInventory->save();

            }else{
                echo "<pre>";
                echo "common a ace";
            }
        }
        echo "Successfully saved";
    }
    // FG INV Missing Process
    public function  actionMissingSaleProcess(){
        $sql = "SELECT * FROM common WHERE sales_id IS NOT NULL AND type=2 ORDER BY id ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data as $item){
            $inventoryInfo = Inventory::model()->findByAttributes(array('sales_id' => $item['sales_id'], 'item_id' => 1, 'model_id' => $item['model_id']));

            if (count($inventoryInfo) == 0){
                $inventory2 = new Inventory();
                $inventory2->sales_id            = $item['sales_id'];
                $inventory2->model_id            = $item['model_id'];
                $inventory2->unit_id             = $item['unit_id'];
                $inventory2->stock_out           = $item['stock_out'];
                $inventory2->store_id            = 1;
                $inventory2->transaction_date    = $item['date'];
                $inventory2->year                = date('Y', strtotime($item['date']));
                $inventory2->month               = date('m', strtotime($item['date']));
                $inventory2->save();
            }
        }
        echo "Successfully saved";
    }
    
    public function  actionMissingSaleProcess2(){
        $sql = "SELECT * FROM common WHERE sales_id IS NOT NULL AND type=5 ORDER BY id ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data as $item){
            $inventoryInfo = Inventory::model()->findByAttributes(array('item_id' => 1, 'model_id' => $item['model_id'], 'purchase_id' => NULL,'sales_id' => NULL,'production_id' => NULL, 'transaction_date' => $item['date'], 'stock_in' => $item['stock_in']));

            if (count($inventoryInfo) == 0){
               //echo "Id : ".$item['id']." Stock IN ".$item['stock_in']." ".$item['date']."<br>";
                $modelInventory = new Inventory;
                //$modelInventory->sell_details_id = $sellDetails->id;
                $modelInventory->store_id = Stores::FG_STORE;
                $modelInventory->location_id = Location::DEFAULT_LOCATION;
                $modelInventory->unit_id = $item['unit_id'];
                $modelInventory->model_id = $item['model_id'];
                $modelInventory->stock_in = $item['stock_in'];
                $modelInventory->transaction_date    = $item['date'];
                $modelInventory->year            = date('Y', strtotime($item['date']));
                $modelInventory->month           = date('m', strtotime($item['date']));
                $modelInventory->save();
        }else{
                echo "<pre>";
                echo "common a ace";
            }
        }
        echo "Successfully saved";
    }
    public function actionTypeToSerialProcess(){
        $criteria = new CDbCriteria();
        $criteria->select = "id, type, item_id, purchase_id, serial, is_opening";
        $criteria->condition = "type is not null";
        $data = Common::model()->findAll($criteria);
        foreach ($data as $item){
            $common = Common::model()->findByPk($item->id);;
            $serial = Common::serialGetByType($item->type, $item->item_id, $item->purchase_id, $item->is_opening);
            $common->serial = $serial;
            $common->save();
        }
        echo "Success";
    }
    public function actionRecipeTotalPriceProcess(){
        $criteria = new CDbCriteria();
        $criteria->select = "id,model_id, stock_in, price_in";
        $criteria->condition = "type=3 and approve_count=2";
        $data = Common::model()->findAll($criteria);
        foreach ($data as $item){
            $unitPrice = MaterialRecipe::model()->getRecipeTotalCostByProductId($item->model_id);
            $common = Common::model()->findByPk($item->id);;
            $common->price_in = ($unitPrice * $item->stock_in);
            $common->save();
        }
        echo "Success";
    }



}
