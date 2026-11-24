<?php

class StockTransferHistoryController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
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

    public function actionStockTransferReqReceive()
    {
        $model = new StockTransferHistory();

        if (isset($_POST['StockTransferHistory'])) {
            $model->attributes = $_POST['StockTransferHistory'];

            if (Yii::app()->request->isAjaxRequest) {

                // Stop jQuery from re-initialization
                Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                $thisSLNo = VoucherNoFormate::model()->maxValOfThis('StockTransferHistory', 'sl_no', 'maxSlNo');

                $i = 0;
                foreach ($_POST['StockTransferHistory']['temp_model_id'] as $tempItemId) {
                    $issue_date = date('Y-m-d', strtotime($_POST['StockTransferHistory']['sent_date']));

                    $prodInfo = ProdModels::model()->findByPk($tempItemId);
                    $itemId = $prodInfo->item_id;
                    $brandId = $prodInfo->brand_id;
                    $code = $prodInfo->code;

                    // Transfer History Save
                    $modelTransfer = new StockTransferHistory();
                    $modelTransfer->availableStock = $_POST['StockTransferHistory']['temp_stock_qty'][$i];
                    $modelTransfer->sl_no = $thisSLNo;
                    $modelTransfer->sent_from_store_id = $_POST['StockTransferHistory']['sent_from_store_id'];
                    $modelTransfer->received_to_store_id = $_POST['StockTransferHistory']['received_to_store_id'];
                    $modelTransfer->sent_qty = $_POST['StockTransferHistory']['temp_sent_qty'][$i];
                    //$modelTransfer->received_qty = $_POST['StockTransferHistory']['temp_sent_qty'][$i];
                    $modelTransfer->sent_date = $issue_date;
                    //$modelTransfer->received_date = $issue_date;             
                    $modelTransfer->is_received = StockTransferHistory::RECEIVED;
                    $modelTransfer->model_id = $tempItemId;
                    $modelTransfer->item_id = $itemId;
                    $modelTransfer->brand_id = $brandId;
                    $modelTransfer->code = $code;
                    $modelTransfer->remarks = $_POST['StockTransferHistory']['remarks'];
                    $modelTransfer->sent_by_id = Yii::app()->user->getId();
                    $modelTransfer->save();


                    // Stock Out From Inventory - Store One
                    $model2 = new Inventory;
                    $model2->store_id = $modelTransfer->sent_from_store_id;
                    $model2->item_id = $modelTransfer->item_id;
                    $model2->brand_id = $modelTransfer->brand_id;
                    $model2->unit_id = ProdModels::model()->unitUnitIdOfThis($modelTransfer->model_id);
                    $model2->model_id = $modelTransfer->model_id;
                    $model2->code = $modelTransfer->code;
                    $model2->stock_out = $modelTransfer->sent_qty;
                    $model2->purchase_price = 0;
                    $sellPrice = 0;
                    $discount = 0;
                    $prodPriceInfo = SellPrice::model()->activeInfos($modelTransfer->model_id);
                    if ($prodPriceInfo) {
                        $sellPrice = $prodPriceInfo->sell_price;
                        $discount = $prodPriceInfo->discount;
                    }
                    $price = ($sellPrice - (($sellPrice * $discount) / 100));
                    $model2->sell_price = $price;
                    $model2->transaction_date = $modelTransfer->sent_date;
                    $inTime = $modelTransfer->sent_date;
                    $model2->month = substr($inTime, 5, 2);
                    $model2->year = substr($inTime, 0, 4);
                    $model2->save();

                    $totalPrice = $price * $modelTransfer->sent_qty;
                    $closingStock = Inventory::model()->closingStock($modelTransfer->model_id, $modelTransfer->sent_from_store_id);

                    $common = new Common();
                    $common->transfer_id = $itemId;
                    $common->store_id = $modelTransfer->sent_from_store_id;
                    $common->date = $modelTransfer->sent_date;
                    $common->type = Common::MANUAL_OPENING; // 7= Transfer
                    $common->model_id = $modelTransfer->model_id;
                    $common->parts_service = 1;
                    $common->local_import = 1;
                    $common->stock_qty = $closingStock;
                    $common->order_qty = $modelTransfer->sent_qty;
                    $common->unit_id = ProdModels::model()->unitUnitIdOfThis($modelTransfer->model_id);
                    $common->unit_price = $price;
                    $common->vat_percent = 0;
                    $common->vat = 0;
                    $common->sd_percent = 0;
                    $common->sd = 0;
                    $common->total_tax = 0;
                    $common->total = $totalPrice;
                    $common->net_total = $totalPrice;
                    $common->stock_out = $modelTransfer->sent_qty;
                    $common->price_out = $totalPrice;
                    $common->approve_count = 2;
                    $common->save();


                    // Stock In/ Received To Inventory - Another Store
                    $model3 = new Inventory;
                    $model3->store_id = $modelTransfer->received_to_store_id;
                    $model3->item_id = $modelTransfer->item_id;
                    $model3->brand_id = $modelTransfer->brand_id;
                    $model3->unit_id = ProdModels::model()->unitUnitIdOfThis($modelTransfer->model_id);
                    $model3->model_id = $modelTransfer->model_id;
                    $model3->code = $modelTransfer->code;
                    $model3->stock_in = $modelTransfer->sent_qty;
                    $model3->purchase_price = 0;
                    $sellPrice = 0;
                    $discount = 0;
                    $prodPriceInfo = SellPrice::model()->activeInfos($modelTransfer->model_id);
                    if ($prodPriceInfo) {
                        $sellPrice = $prodPriceInfo->sell_price;
                        $discount = $prodPriceInfo->discount;
                    }
                    $price = ($sellPrice - (($sellPrice * $discount) / 100));
                    $model3->sell_price = $price;
                    $model3->transaction_date = $modelTransfer->sent_date;
                    $inTime = $modelTransfer->sent_date;
                    $model3->month = substr($inTime, 5, 2);
                    $model3->year = substr($inTime, 0, 4);
                    $model3->save();

                    $common2 = new Common();
                    $common2->transfer_id = $modelTransfer->item_id;
                    $common2->store_id = $model3->store_id;
                    $common2->parts_service = 1;
                    $common2->local_import = 1;
                    $common2->date = $modelTransfer->sent_date;
                    $common2->type = Common::TRANSFER_RECEIVE; // 8= Transfer Receive
                    $common2->model_id = $modelTransfer->model_id;
                    $common2->model_qty =  $modelTransfer->sent_qty;
                    $common2->vat_percent = 0;
                    $common2->vat = 0;
                    $common2->sd_percent = 0;
                    $common2->sd = 0;
                    $common2->total_tax = 0;
                    //$common2->contractual_status = $model->contractual_status;
                    $common2->rm_total_price = round($totalPrice, 4);
                    $common2->stock_in = $modelTransfer->sent_qty;;
                    $common2->price_in = $totalPrice;
                    $common2->unit_id = ProdModels::model()->unitUnitIdOfThis($modelTransfer->model_id);
                    $common2->approve_count = 2;
                    $common2->save();

                }

                echo CJSON::encode(array(
                    'status' => 'success',
                    'content' => '<div class="flash-notice">Stock sent!</div>',
                    'availableStock' => '',
                ));
                exit;

            }
        }
    }

    public function actionStockTransferReqSent($id, $model_id, $store_id)
    {

        $model = new StockTransferHistory;
        $inventoryInfo = Inventory::model()->findByPk($id);
        $inventoryId = $inventoryInfo->id;

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(stock_in) as tStockIn, sum(stock_out) as tStockOut';
        $criteria->addColumnCondition(array('model_id' => $model_id, 'store_id' => $store_id), 'AND', 'AND');
        $criteria->group = 'model_id';
        $criteria->order = 'id ASC';
        $data = Inventory::model()->findAll($criteria);
        $availableStock = end($data)->tStockIn - end($data)->tStockOut;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['StockTransferHistory'])) {
            $model->attributes = $_POST['StockTransferHistory'];
            $remarks = $model->remarks;
            $valid = $model->validate();

            // SL NO
            $sl_no = VoucherNoFormate::model()->maxValOfThis('StockTransferHistory', 'sl_no', 'maxSlNo');

            if ($valid) {
                if (Yii::app()->request->isAjaxRequest) {
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                    $stockQty = $availableStock;
                    $itemId = $inventoryInfo->item_id;
                    $brandId = $inventoryInfo->brand_id;
                    $modelId = $inventoryInfo->model_id;
                    $code = $inventoryInfo->code;
                    $storeId = $inventoryInfo->store_id;

                    $inventoryId = $inventoryId;
                    $sentFromStoreId = $storeId;
                    $receivedToStoreId = $_POST['StockTransferHistory']['received_to_store_id'];
                    $sentQty = $_POST['StockTransferHistory']['sent_qty'];
                    $sentDate = $_POST['StockTransferHistory']['sent_date'];

                    if ($sentFromStoreId === $receivedToStoreId) {
                        echo CJSON::encode(array(
                            'status' => 'failure',
                            'content' => '<div class="flash-error">Can not transfer to the same store!</div>',
                        ));
                        exit;
                    } else if ($sentQty > $stockQty) {
                        echo CJSON::encode(array(
                            'status' => 'failure',
                            'content' => '<div class="flash-error">Can not transfer quantity larger then stocked quantity!</div>',
                        ));
                        exit;
                    } else {

                        $model = new StockTransferHistory;
                        $model->availableStock = $availableStock;
                        $model->sent_from_store_id = $sentFromStoreId;
                        $model->received_to_store_id = $receivedToStoreId;
                        $model->sent_qty = $sentQty;
                        $model->sent_date = $sentDate;
                        $model->item_id = $itemId;
                        $model->brand_id = $brandId;
                        $model->model_id = $modelId;
                        $model->code = $code;
                        $model->remarks = $remarks;
                        $model->sl_no = $sl_no;
                        $model->sent_by_id = Yii::app()->user->getId();
                        $model->save();

                        $model2 = new Inventory;
                        $model2->store_id = $model->sent_from_store_id;
                        $model2->item_id = $model->item_id;
                        $model2->brand_id = $model->brand_id;
                        $model2->unit_id = ProdModels::model()->unitUnitIdOfThis($model->model_id);
                        $model2->model_id = $model->model_id;
                        $model2->code = $model->code;
                        $model2->stock_out = $model->sent_qty;
                        $model2->purchase_price = 0;
                        $sellPrice = 0;
                        $discount = 0;
                        $prodPriceInfo = SellPrice::model()->activeInfos($model->model_id);
                        if ($prodPriceInfo) {
                            $sellPrice = $prodPriceInfo->sell_price;
                            $discount = $prodPriceInfo->discount;
                        }
                        $price = ($sellPrice - (($sellPrice * $discount) / 100));
                        $model2->sell_price = $price;
                        $model2->transaction_date = $model->sent_date;
                        $inTime = $model->sent_date;
                        $model2->month = substr($inTime, 5, 2);
                        $model2->year = substr($inTime, 0, 4);
                        $model2->save();
                        $availableStock = $availableStock - $sentQty;


                        $model3 = new Inventory;
                        $model3->store_id = $receivedToStoreId;
                        $model3->item_id = $model->item_id;
                        $model3->brand_id = $model->brand_id;
                        $model3->unit_id = ProdModels::model()->unitUnitIdOfThis($model->model_id);
                        $model3->model_id = $model->model_id;
                        $model3->code = $model->code;
                        $model3->stock_in = $model->sent_qty;
                        $model3->purchase_price = 0;
                        $sellPrice = 0;
                        $discount = 0;
                        $prodPriceInfo = SellPrice::model()->activeInfos($model->model_id);
                        if ($prodPriceInfo) {
                            $sellPrice = $prodPriceInfo->sell_price;
                            $discount = $prodPriceInfo->discount;
                        }
                        $price = ($sellPrice - (($sellPrice * $discount) / 100));
                        $model3->sell_price = $price;
                        $model3->transaction_date = $model->sent_date;
                        $inTime = $model->sent_date;
                        $model3->month = substr($inTime, 5, 2);
                        $model3->year = substr($inTime, 0, 4);
                        $model3->save();


                        echo CJSON::encode(array(
                            'status' => 'success',
                            'content' => '<div class="flash-notice">Stock sent!</div>',
                            'availableStock' => $availableStock,
                        ));
                        exit;
                    }
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_stockTransferForm', array(
                    'model' => $model,
                    'inventoryInfo' => $inventoryInfo,
                    'availableStock' => $availableStock,
                ), true, true),
            ));
            exit;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
//        $model = new StockTransferHistory;
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['StockTransferHistory'])) {
//            $model->attributes = $_POST['StockTransferHistory'];
//            if ($model->save())
//                $this->redirect(array('view', 'id' => $model->id));
//        }
//
//        $this->render('create', array(
//            'model' => $model,
//        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
//        $model = $this->loadModel($id);
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['StockTransferHistory'])) {
//            $model->attributes = $_POST['StockTransferHistory'];
//            if ($model->save())
//                $this->redirect(array('view', 'id' => $model->id));
//        }
//
//        $this->render('update', array(
//            'model' => $model,
//        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            if ($model->is_received == StockTransferHistory::PENDING)
                $model->delete();
            else
                echo "<div class='flash-error'>You can not delete an already RECEIVED transaction data!</div>"; //for ajax


// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new StockTransferHistory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransferHistory']))
            $model->attributes = $_GET['StockTransferHistory'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminReqReceive()
    {
//        $condition = 'is_received=' . StockTransferHistory::PENDING;
//        $data = StockTransferHistory::model()->findAll(array('condition' => $condition,), 'id');
//        $this->render('receivedStockRequests', array(
//            'data' => $data,
//        ));
        $model = new StockTransferHistory('searchReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StockTransferHistory']))
            $model->attributes = $_GET['StockTransferHistory'];

        $this->render('receivedStockRequests', array(
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
        $model = StockTransferHistory::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-transfer-history-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
