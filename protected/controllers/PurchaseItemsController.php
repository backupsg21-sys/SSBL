<?php

class PurchaseItemsController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

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

    public function actionApprovePurchaseLocal($id)
    {
        $pModel = PurchaseItems::model()->findByPk($id);
        if ($pModel) {
            $oldCount = $pModel->approve_count;
            $effective_date = $pModel->effective_date;
            if ($effective_date == "" || $effective_date == NULL) {
                echo CJSON::encode(array(
                    'status' => 'failed',
                    'message' => 'No effective date found! Please enter effective date on update form.',
                ));
                Yii::app()->end();
            } else if ($oldCount < 2) {
                $countPurchaseItem = $oldCount + 1;
                
                $cirteria = new CDbCriteria();
                $cirteria->select = '*';
                $cirteria->condition = "purchase_id='" . $id . "'";
                $purchase_data = PurchaseDetails::model()->findAll($cirteria);
                 // delete inventory details
                 Inventory::model()->deleteAllByAttributes(['purchase_id' => $id]);
                 // delete from (purchase data)
                 Common::model()->deleteAllByAttributes(['purchase_id' => $id]);


                $i = 0;
                foreach ($purchase_data as $modelPurchaseDetails) {

                    if ($pModel->local_import == 54) { // Purchase Local
                        $common                = new Common();
                        $common->purchase_id   = $modelPurchaseDetails->purchase_id;
                        $common->model_id      = $modelPurchaseDetails->model_id;
                        $common->date          = $modelPurchaseDetails->issue_date;
 			$common->effective_date= $modelPurchaseDetails->effective_date;
                        $common->type          = Common::PURCHASE; // 1= Purchase
                        $common->parts_service = $modelPurchaseDetails->parts_service;
                        $common->local_import  = $modelPurchaseDetails->local_import;
                        $common->order_qty     = $modelPurchaseDetails->order_qty;
                        $common->unit_id       = $modelPurchaseDetails->unit_id;
                        $common->unit_price    = $modelPurchaseDetails->unit_price;
                        $common->vat_percent   = $modelPurchaseDetails->vat_percent;
                        $common->vat           = $modelPurchaseDetails->vat;
                        $common->sd_percent    = $modelPurchaseDetails->sd_percent;
                        $common->sd            = $modelPurchaseDetails->sd;
                        $common->total_tax     = $modelPurchaseDetails->total_tax;
                        $common->total         = $modelPurchaseDetails->total;
                        $common->net_total     = $modelPurchaseDetails->net_total;
                        $common->rebate        = $modelPurchaseDetails->rebate;
                        $common->rebatable     = $modelPurchaseDetails->rebatable;
                        $common->stock_in      = $modelPurchaseDetails->order_qty;
                        $common->price_in      = $modelPurchaseDetails->total;
                        $common->approve_count = 2;

                        $common->save();

                        $modelInventory                      = new Inventory;
                        $modelInventory->purchase_id         = $modelPurchaseDetails->purchase_id;
                        $modelInventory->purchase_details_id = $modelPurchaseDetails->id;
                        $modelInventory->store_id            = Stores::FG_STORE;
                        $modelInventory->location_id         = Location::DEFAULT_LOCATION;
                        $prodModels                          = ProdModels::model()->findByPk($modelPurchaseDetails->model_id);
                        $modelInventory->item_id             = $prodModels->item_id;
                        $modelInventory->brand_id            = $prodModels->brand_id;
                        $modelInventory->unit_id             = $prodModels->unit_id;
                        $modelInventory->model_id            = $modelPurchaseDetails->model_id;
                        $modelInventory->code                = $prodModels->code;
                        $modelInventory->stock_in            = $modelPurchaseDetails->order_qty;
                        $modelInventory->transaction_date    = $modelPurchaseDetails->issue_date;
                        $modelInventory->month               = date('m');
                        $modelInventory->year                = date('Y');
                        $modelInventory->save();
                    }

                    if ($pModel->local_import == 55) { // Purchase Import
                        $common                = new Common();
                        $common->purchase_id   = $modelPurchaseDetails->purchase_id;
                        $common->model_id      = $modelPurchaseDetails->model_id;
                        $common->date          = $modelPurchaseDetails->issue_date;
 			$common->effective_date= $modelPurchaseDetails->effective_date;
                        $common->type          = Common::PURCHASE; // 1= Purchase
                        $common->parts_service = $modelPurchaseDetails->parts_service;
                        $common->local_import  = $modelPurchaseDetails->local_import;
                        $common->order_qty     = $modelPurchaseDetails->order_qty;
                        $common->unit_id       = $modelPurchaseDetails->unit_id;
                        $common->unit_price    = $modelPurchaseDetails->unit_price;
                        $common->vat_percent   = $modelPurchaseDetails->vat_percent;
                        $common->vat           = $modelPurchaseDetails->vat;
                        $common->total_tax     = $modelPurchaseDetails->total_tax;
                        $common->total         = $modelPurchaseDetails->total;
                        $common->net_total     = $modelPurchaseDetails->net_total;
                        $common->rebate        = $modelPurchaseDetails->rebate;
                        $common->rebatable     = $modelPurchaseDetails->rebatable;
                        $common->cd_percent    = $modelPurchaseDetails->cd_percent;
                        $common->cd            = $modelPurchaseDetails->cd;
                        $common->rd_percent    = $modelPurchaseDetails->rd_percent;
                        $common->rd            = $modelPurchaseDetails->rd;
                        $common->sd_percent    = $modelPurchaseDetails->sd_percent;
                        $common->sd            = $modelPurchaseDetails->sd;
                        $common->ait_percent   = $modelPurchaseDetails->ait_percent;
                        $common->ait           = $modelPurchaseDetails->ait;
                        $common->at_percent    = $modelPurchaseDetails->at_percent;
                        $common->at            = $modelPurchaseDetails->at;
                        $common->stock_in      = $modelPurchaseDetails->order_qty;
                        $common->price_in      = $modelPurchaseDetails->total;
                        $common->approve_count = 2;
                        $common->save();

                        $modelInventory                      = new Inventory;
                        $modelInventory->purchase_id         = $modelPurchaseDetails->purchase_id;
                        $modelInventory->purchase_details_id = $modelPurchaseDetails->id;
                        $modelInventory->store_id            = Stores::FG_STORE;
                        $modelInventory->location_id         = Location::DEFAULT_LOCATION;
                        $prodModels                          = ProdModels::model()->findByPk($modelPurchaseDetails->model_id);
                        $modelInventory->item_id             = $prodModels->item_id;
                        $modelInventory->brand_id            = $prodModels->brand_id;
                        $modelInventory->unit_id             = $prodModels->unit_id;
                        $modelInventory->model_id            = $modelPurchaseDetails->model_id;
                        $modelInventory->code                = $prodModels->code;
                        $modelInventory->stock_in            = $modelPurchaseDetails->order_qty;
                        $modelInventory->transaction_date    = $modelPurchaseDetails->issue_date;
                        $modelInventory->month               = date('m');
                        $modelInventory->year                = date('Y');
                        $modelInventory->save();
                    }

                    $i++;
                }


                PurchaseItems::model()->updateByPk($id, array("approve_count" => $countPurchaseItem));
                PurchaseDetails::model()->updateAll(array('approve_count' => $countPurchaseItem, 'effective_date' => $effective_date), "purchase_id= '$id' ");
                Vds::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= '$id' ");
                
                if ($countPurchaseItem == 2) {

                    /* // To Journal
                      $today = date('Y-m-d');
                      $supplier = "";
                      $po_string = $pModel->po_no;
                      $issue_date = "";
                      $entry_no = VoucherNoFormateAc::model()->maxValOfThisOfThisType('JournalEntry', 'entry_no', 'maxval', 7);
                      $user_id = Yii::app()->user->getId();
                      $supplier_id = $pModel->supplier_id;
                      $supplier = Suppliers::model()->supplierAllInfo($supplier_id);
                      $gTotal = round($pModel->grand_total);

                      //===========================Start Debit COA======================//
                      $narration = "Purchase Order From Supplier $supplier->name";
                      $JournalEntryDbData['bill_no'] = $po_string;
                      $JournalEntryDbData['narration'] = $narration;
                      $JournalEntryDbData['entry_date'] = $issue_date;
                      $JournalEntryDbData['ca_id'] = 1194;
                      $JournalEntryDbData['transaction_type'] = 3;
                      $JournalEntryDbData['amount'] = $gTotal;
                      $JournalEntryDbData['main_ca'] = ChartOfAc::model()->findMainCAofThis(1194);
                      $JournalEntryDbData['voucher_type'] = 7;
                      $JournalEntryDbData['is_deletable'] = JournalEntry::NOT_DELETABLE;
                      $JournalEntryDbData['entry_no'] = $entry_no;
                      $JournalEntryDbData['create_by'] = $user_id;
                      $JournalEntryDbData['create_time'] = date('Y-m-d H:i:s');
                      $JournalEntry = Yii::app()->db->createCommand()->insert('journal_entry', $JournalEntryDbData);
                      //===========================End Debit COA======================//


                      //===========================Start Credit COA======================//
                      $narration = "Purchase Order From Supplier $supplier->name";
                      $JournalEntryCrData['bill_no'] = $po_string;
                      $JournalEntryCrData['narration'] = $narration;
                      $JournalEntryCrData['entry_date'] = $issue_date;
                      $JournalEntryCrData['ca_id'] = 19;
                      $JournalEntryCrData['transaction_type'] = 4;
                      $JournalEntryCrData['amount'] = $gTotal;
                      $JournalEntryCrData['main_ca'] = ChartOfAc::model()->findMainCAofThis(19);
                      $JournalEntryCrData['voucher_type'] = 7;
                      $JournalEntryCrData['is_deletable'] = JournalEntry::NOT_DELETABLE;
                      $JournalEntryCrData['entry_no'] = $entry_no;
                      $JournalEntryCrData['create_by'] = $user_id;
                      $JournalEntryCrData['create_time'] = date('Y-m-d H:i:s');
                      $JournalEntry = Yii::app()->db->createCommand()->insert('journal_entry', $JournalEntryCrData);
                      //===========================End Credit COA======================//
                     */
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'message' => 'Approved',
                    ));
                    Yii::app()->end();
                } else if ($countPurchaseItem == 1) {
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'message' => 'Processing',
                    ));
                    Yii::app()->end();
                } else {
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'message' => 'Pending',
                    ));
                    Yii::app()->end();
                }
            } else {
                echo CJSON::encode(array(
                    'status' => 'failed',
                    'message' => 'No data found!',
                ));
                Yii::app()->end();
            }
        }

        /* if (!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminPartsLocal')); */
    }

    public function actionApprovePurchaseServices($id)
    {
        $oldCount = PurchaseItems::model()->findByPk($id)->approve_count;
        if ($oldCount < 2) {
            $countPurchaseItem = $oldCount + 1;
            PurchaseItems::model()->updateByPk($id, array("approve_count" => $countPurchaseItem));
            PurchaseDetails::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= $id ");
            Common::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= $id ");
            Vds::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= $id ");
            if ($oldCount == 2)
                echo 'Approved';
            else if ($oldCount == 1)
                echo 'Processing';
            else
                echo 'Pending';
        }
        /* if (!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminServices')); */
    }

    public function actionApprovePurchaseForeicn($id)
    {
        $oldCount = PurchaseItems::model()->findByPk($id)->approve_count;
        if ($oldCount < 2) {
            $countPurchaseItem = $oldCount + 1;
            PurchaseItems::model()->updateByPk($id, array("approve_count" => $countPurchaseItem));
            PurchaseDetails::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= $id ");
            Common::model()->updateAll(array('approve_count' => $countPurchaseItem), "purchase_id= $id ");
        }
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminPartsForeign'));
    }

    /*
      public function actionPurchaseReport() {
      $model = new PurchaseItems;
      $this->render('purchaseReport', array('model' => $model));
      } */

    public function actionPoReportOfThis()
    {
        $po = trim($_POST['po']);
        $models = PurchaseItems::model()->findByAttributes(array('po_no' => $po));
        if ($models) {
            $condition = 'purchase_id="' . $models->id . '"';
            $data = PurchaseDetails::model()->findAll(array('condition' => $condition,), 'id');
            //Yii::app()->clientscript->scriptMap['jquery.js'] = false;
            echo $this->renderPartial('poReportParts', array('data' => $data, 'purchaseItemModel' => $models), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select purchase order no!</div>';
        }
    }

    public function actionPoReportOfThisForeign()
    {
        $po = $_POST['po'];
        $models = PurchaseItems::model()->findByAttributes(array('po_no' => $po));
        if ($models) {
            $condition = 'purchase_id="' . $models->id . '"';
            $data = PurchaseDetails::model()->findAll(array('condition' => $condition,), 'id');
            //Yii::app()->clientscript->scriptMap['jquery.js'] = false;
            echo $this->renderPartial('poReportPartsForeign', array('data' => $data, 'purchaseItemModel' => $models), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select purchase order no!</div>';
        }
    }

    public function actionServiceReportOfThis()
    {
        $serviceNo = $_POST['serviceNo'];
        $models = PurchaseItems::model()->findByAttributes(array('service_no' => $serviceNo));
        if ($models) {
            $condition = 'purchase_id="' . $models->id . '"';
            $data = PurchaseDetails::model()->findAll(array('condition' => $condition,), 'id');
            //Yii::app()->clientscript->scriptMap['jquery.js'] = false;
            echo $this->renderPartial('serviceReport', array('data' => $data, 'purchaseItemModel' => $models), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select purchase order no!</div>';
        }
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    // Create parts local
    public function actionCreatePartsLocal()
    {
        $model = new PurchaseItems;

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];
        $this->render('_formPartsLocal', array(
            'model' => $model,
        ));
    }

    // Create parts local - action
    public function actionSavePartsLocal()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $thisPurchaseNo = VoucherNoFormate::model()->maxValOfThis('PurchaseItems', 'po_no', 'maxPINo');
        $rebate = 0;
        $transaction = Yii::app()->db->beginTransaction();
        try {

            $model = new PurchaseItems();
            $model->ref_no = $_POST['PurchaseItems']['ref_no'];
            $model->po_no = $thisPurchaseNo;
            $model->order_by_id = Yii::app()->user->getId();
            $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
            $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
            $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
			$effective_date = $_POST['PurchaseItems']['effective_date'] != "" ? date('Y-m-d', strtotime($_POST['PurchaseItems']['effective_date'])) : NULL;
            $effective_date = ($effective_date != "0000-00-00") ? $effective_date : NULL;
			$model->effective_date = $effective_date;
            $model->product_type = $_POST['PurchaseItems']['product_type'];
            $model->chalan_date = !empty($_POST['PurchaseItems']['chalan_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['chalan_date'])) : null;
            $model->payment_method = $_POST['PurchaseItems']['payment_method'];
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $model->is_received = 6;
            $model->is_all_received = 37;
            $model->is_approved = 16;
            $model->local_import = PurchaseItems::LOCAL; //54=Local
            $model->purchase_by_id = Yii::app()->user->id;
            $model->total_sd = $_POST['PurchaseItems']['total_sd'];
            $model->total_tax = $_POST['PurchaseItems']['total_tax'];
            $model->total = $_POST['PurchaseItems']['total'];
            $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
            $model->total_vds = $vds = $_POST['PurchaseItems']['total_vds'];
            $model->grand_total = $_POST['PurchaseItems']['grand_total'];
            $model->approve_count = 1;
            $approve_count = $model->approve_count;
            if ($model->save()) {
                if ($vds > 0) {
                    $max_sl = VoucherNoFormate::model()->maxValOfThis('Vds', 'sl_no_po', 'sl_no_po');
                    $vdsrow = new Vds();
                    $vdsrow->purchase_id = $model->id;
                    $vdsrow->po_no = $model->po_no;
                    $vdsrow->sl_no_po = $max_sl;
                    $vdsrow->vds = $model->total_vds;
                    $vdsrow->vds_type = Vds::PURCHASE; // 1 = Purchase, 2 = Sales
                    $vdsrow->parts_service = 1;
                    $vdsrow->supplier_id = $model->supplier_id;
                    //$vdsrow->issue_date = $model->issue_date;
                    $vdsrow->issue_date = NULL;
                    $vdsrow->approve_count = $approve_count;
                    $vdsrow->save();
                }
                $i = 0;
                foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                    $modelPurchaseDetails = new PurchaseDetails();
                    $modelPurchaseDetails->purchase_id = $model->id;
                    $modelPurchaseDetails->model_id = $tempItemId;
                    $modelPurchaseDetails->issue_date = $issueDate;
                    $modelPurchaseDetails->parts_service = 1;
                    $modelPurchaseDetails->local_import = PurchaseItems::LOCAL; //54=Local
                    $modelPurchaseDetails->supplier_id = $model->supplier_id;
                    $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                    $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                    $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                    $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                    $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                    $modelPurchaseDetails->sd_percent = $_POST['PurchaseItems']['temp_sd_percent'][$i];
                    $modelPurchaseDetails->sd = $_POST['PurchaseItems']['temp_sd'][$i];
                    $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                    $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                    $modelPurchaseDetails->net_total = $_POST['PurchaseItems']['temp_net_total'][$i];
                    $modelPurchaseDetails->approve_count = $approve_count;

                    if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                        $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                        $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                    }
                    $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                    $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];

                    if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                    else if ($modelPurchaseDetails->vat_percent == 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                    else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                        if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 3)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                        else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 4)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                        else
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                    } else
                        $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                    $modelPurchaseDetails->save();

                    $i++;
                }
                $transaction->commit();

                $condition = "purchase_id = {$model->id}";
                $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);
                echo CJSON::encode(array(
                    'status' => 'success',
                    'poReportInfo' => $this->renderPartial('poReportParts', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                ));
                Yii::app()->end();
            }
        } catch (Exception $e) {
            echo CJSON::encode(array(
                'status' => 'errorBalance',
                'message' => $e->getMessage(),
            ));
            Yii::app()->end();
        }
    }

    // Parts Purchase Local - Update Page
    public function actionUpdateInfo($id)
    {
        $model = PurchaseItems::model()->findByPk($id);
        $purchaseDetailsModel = PurchaseDetails::model()->findAll(array('condition' => "purchase_id='$id'"));
        $rebate = 0;
        $this->performAjaxValidation($model);
        if (isset($_POST['PurchaseItems'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['PurchaseItems'];
            $model->bank_id = null;
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $grandTotal = $_POST['PurchaseItems']['grand_total'];
            $effective_date = $_POST['PurchaseItems']['effective_date'] != "" ? date('Y-m-d', strtotime($_POST['PurchaseItems']['effective_date'])) : NULL;
            $effective_date = ($effective_date != "0000-00-00") ? $effective_date : NULL;
            //if ($grandTotal != 0) {
            $valid = $model->validate();
            if ($valid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->order_by_id = Yii::app()->user->id;
                    $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
                    $model->ref_no = $_POST['PurchaseItems']['ref_no'];
                    $model->chalan_date = !empty($_POST['PurchaseItems']['chalan_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['chalan_date'])) : null;
                    $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
                    $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
                    $model->total_sd = $_POST['PurchaseItems']['total_sd'];
                    $model->total_tax = $_POST['PurchaseItems']['total_tax'];
                    $model->total = $_POST['PurchaseItems']['total'];
                    $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
                    $model->total_vds = $vds = $_POST['PurchaseItems']['total_vds'];
                    $model->grand_total = $_POST['PurchaseItems']['grand_total'];
                    $model->effective_date = $effective_date;
                    $approve_count = $model->approve_count;
                    if ($model->save()) {
                        $vdsrow = Vds::model()->findByAttributes(['purchase_id' => $model->id]);
                        if ($vds > 0) {
                            if (!$vdsrow) {
                                $max_sl = VoucherNoFormate::model()->maxValOfThis('Vds', 'sl_no_po', 'sl_no_po');
                                $vdsrow = new Vds();
                                $vdsrow->purchase_id = $model->id;
                                $vdsrow->po_no = $model->po_no;
                                $vdsrow->sl_no_po = $max_sl;
                            }
                            $vdsrow->vds = $model->total_vds;
                            $vdsrow->vds_type = Vds::PURCHASE; // 1 = Purchase, 2 = Sales
                            $vdsrow->parts_service = 1;
                            $vdsrow->supplier_id = $model->supplier_id;
                            $vdsrow->issue_date = $effective_date;
                            $vdsrow->approve_count = $approve_count;
                            $vdsrow->save();
                        } else if ($vdsrow) {
                            Vds::model()->deleteAllByAttributes(['purchase_id' => $model->id]);
                        }
                        $i = 0;
                        // delete purchase details
                        PurchaseDetails::model()->deleteAllByAttributes(['purchase_id' => $model->id]);
                        // delete inventory details
                        Inventory::model()->deleteAllByAttributes(['purchase_id' => $model->id]);
                        // delete from (purchase data)
                        Common::model()->deleteAllByAttributes(['purchase_id' => $model->id]);

                        foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                            $modelPurchaseDetails = new PurchaseDetails();
                            $modelPurchaseDetails->purchase_id = $model->id;
                            $modelPurchaseDetails->effective_date = $effective_date;
                            $modelPurchaseDetails->model_id = $tempItemId;
                            $modelPurchaseDetails->issue_date = $issueDate;
                            $modelPurchaseDetails->effective_date = $model->effective_date;
                            $modelPurchaseDetails->parts_service = 1;
                            $modelPurchaseDetails->local_import = PurchaseItems::LOCAL; //54=Local
                            $modelPurchaseDetails->supplier_id = $model->supplier_id;
                            $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                            $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                            $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                            $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                            $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                            $modelPurchaseDetails->sd_percent = $_POST['PurchaseItems']['temp_sd_percent'][$i];
                            $modelPurchaseDetails->sd = $_POST['PurchaseItems']['temp_sd'][$i];
                            $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                            $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                            $modelPurchaseDetails->net_total = $_POST['PurchaseItems']['temp_net_total'][$i];
                            if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                                $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                                $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                            } else {
                                $modelPurchaseDetails->rebate = null;
                                $modelPurchaseDetails->rebatable = null;
                            }
                            $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                            $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];
                            $modelPurchaseDetails->approve_count = $approve_count;

                            if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                            else if ($modelPurchaseDetails->vat_percent == 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                            else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                                if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 3)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                                else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 4)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                                else
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                            } else
                                $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                            $modelPurchaseDetails->save();
                            
                            $i++;
                        }

                        $transaction->commit();
                        $condition = "purchase_id = {$model->id}";
                        $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                        $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);
                        echo CJSON::encode(array(
                            'status' => 'success',
                            'poReportInfo' => $this->renderPartial('poReportParts', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                        ));
                        Yii::app()->end();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    echo CJSON::encode(array(
                        'status' => 'errorBalance',
                        'message' => $e->getMessage(),
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
            /* } else {
              echo CJSON::encode(array(
              'status' => 'errorBalance',
              ));
              Yii::app()->end();
              } */
        } else {
            $this->render('_formPartsLocalUpdate', array(
                'model' => $model,
                'purchaseDetailsModel' => $purchaseDetailsModel,
            ));
        }
    }

    // Parts Purchase (FOREIGN) - Form
    public function actionCreatePartsForeign()
    {
        $model = new PurchaseItems;
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];
        $this->render('_formPartsForeign', array(
            'model' => $model,
        ));
    }

    // Parts Purchase (FOREIGN) - action page
    public function actionSavePartsForeign()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $thisPurchaseNo = VoucherNoFormate::model()->maxValOfThis('PurchaseItems', 'po_no', 'maxPINo');
        $rebate = 0;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $model = new PurchaseItems();
            $model->po_no = $thisPurchaseNo;
            $model->order_by_id = Yii::app()->user->getId();
            $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
            $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
            $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
			$effective_date = $_POST['PurchaseItems']['effective_date'] != "" ? date('Y-m-d', strtotime($_POST['PurchaseItems']['effective_date'])) : NULL;
            $effective_date = ($effective_date != "0000-00-00") ? $effective_date : NULL;
			$model->effective_date = $effective_date;
            $model->product_type = $_POST['PurchaseItems']['product_type'];
            $model->payment_method = $_POST['PurchaseItems']['payment_method'];
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $model->boe_no = $_POST['PurchaseItems']['boe_no'];
            $model->boe_date = !empty($_POST['PurchaseItems']['boe_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['boe_date'])) : null;
            $model->lc_no = $_POST['PurchaseItems']['lc_no'];
            $model->lc_date = !empty($_POST['PurchaseItems']['lc_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['lc_date'])) : null;
            $model->port_id = $_POST['PurchaseItems']['port_id'];
            $model->port_code = $_POST['PurchaseItems']['port_code'];
            $model->country_id = $_POST['PurchaseItems']['country_id'];
            $model->is_received = 6;
            $model->is_all_received = 37;
            $model->is_approved = 16;
            $model->local_import = 55; //55=import
            $model->purchase_by_id = Yii::app()->user->getId();
            $model->total_tax = $_POST['PurchaseItems']['total_tax'];
            $model->total_sd = $_POST['PurchaseItems']['total_sd'];
            $model->total = $_POST['PurchaseItems']['total'];
            $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
            //$model->total_vds = $_POST['PurchaseItems']['total_vds'];
            $model->grand_total = $_POST['PurchaseItems']['grand_total'];
            $model->approve_count = 1;
            $approve_count = $model->approve_count;
            if ($model->save()) {
                $i = 0;
                foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                    $modelPurchaseDetails = new PurchaseDetails();
                    $modelPurchaseDetails->purchase_id = $model->id;
                    $modelPurchaseDetails->model_id = $tempItemId;
                    $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                    $modelPurchaseDetails->issue_date = $issueDate;
                    $modelPurchaseDetails->parts_service = 1;
                    $modelPurchaseDetails->local_import = PurchaseItems::IMPORT; //55=import
                    $modelPurchaseDetails->supplier_id = $model->supplier_id;

                    $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                    $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                    $modelPurchaseDetails->cv_rate = $_POST['PurchaseItems']['temp_cv_rate'][$i];
                    $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                    $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                    $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                    $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                    $modelPurchaseDetails->net_total = round(($_POST['PurchaseItems']['temp_total_amount'][$i] + $_POST['PurchaseItems']['temp_tax_amount'][$i]), 2);

                    if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                        $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                        $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                    }
                    $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                    $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];

                    if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                    else if ($modelPurchaseDetails->vat_percent == 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                    else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                        if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == PurchaseDetails::FIXED)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                        else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == PurchaseDetails::EXEMPTED)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                        else
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                    } else
                        $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                    $modelPurchaseDetails->cd_percent = $_POST['PurchaseItems']['temp_cd_percent'][$i];
                    $modelPurchaseDetails->cd = $_POST['PurchaseItems']['temp_cd'][$i];
                    $modelPurchaseDetails->rd_percent = $_POST['PurchaseItems']['temp_rd_percent'][$i];
                    $modelPurchaseDetails->rd = $_POST['PurchaseItems']['temp_rd'][$i];
                    $modelPurchaseDetails->sd_percent = $_POST['PurchaseItems']['temp_sd_percent'][$i];
                    $modelPurchaseDetails->sd = $_POST['PurchaseItems']['temp_sd'][$i];
                    $modelPurchaseDetails->ait_percent = $_POST['PurchaseItems']['temp_ait_percent'][$i];
                    $modelPurchaseDetails->ait = $_POST['PurchaseItems']['temp_ait'][$i];
                    $modelPurchaseDetails->at_percent = $_POST['PurchaseItems']['temp_at_percent'][$i];
                    $modelPurchaseDetails->at = $_POST['PurchaseItems']['temp_at'][$i];
                    $modelPurchaseDetails->approve_count = $approve_count;

                    if ($modelPurchaseDetails->save()) {

                        $common = new Common();
                        $common->purchase_id = $modelPurchaseDetails->purchase_id;
                        $common->model_id = $modelPurchaseDetails->model_id;
                        $common->date = $modelPurchaseDetails->issue_date;
                        $common->type = Common::PURCHASE; // 1= Purchase
                        $common->parts_service = $modelPurchaseDetails->parts_service;
                        $common->local_import = $modelPurchaseDetails->local_import;
                        $common->order_qty = $modelPurchaseDetails->order_qty;
                        $common->unit_id = $modelPurchaseDetails->unit_id;
                        $common->unit_price = $modelPurchaseDetails->unit_price;
                        $common->vat_percent = $modelPurchaseDetails->vat_percent;
                        $common->vat = $modelPurchaseDetails->vat;
                        $common->total_tax = $modelPurchaseDetails->total_tax;
                        $common->total = $modelPurchaseDetails->total;
                        $common->net_total = $modelPurchaseDetails->net_total;
                        $common->rebate = $modelPurchaseDetails->rebate;
                        $common->rebatable = $modelPurchaseDetails->rebatable;
                        $common->cd_percent = $modelPurchaseDetails->cd_percent;
                        $common->cd = $modelPurchaseDetails->cd;
                        $common->rd_percent = $modelPurchaseDetails->rd_percent;
                        $common->rd = $modelPurchaseDetails->rd;
                        $common->sd_percent = $modelPurchaseDetails->sd_percent;
                        $common->sd = $modelPurchaseDetails->sd;
                        $common->ait_percent = $modelPurchaseDetails->ait_percent;
                        $common->ait = $modelPurchaseDetails->ait;
                        $common->at_percent = $modelPurchaseDetails->at_percent;
                        $common->at = $modelPurchaseDetails->at;
                        $common->stock_in = $modelPurchaseDetails->order_qty;
                        $common->price_in = $modelPurchaseDetails->total;
                        $common->approve_count = $approve_count;
                        $common->save();

                        $modelInventory = new Inventory;
                        $modelInventory->purchase_id = $modelPurchaseDetails->purchase_id;
                        $modelInventory->purchase_details_id = $modelPurchaseDetails->id;
                        $modelInventory->store_id = Stores::FG_STORE;
                        $modelInventory->location_id = Location::DEFAULT_LOCATION;
                        $prodModels = ProdModels::model()->findByPk($modelPurchaseDetails->model_id);
                        $modelInventory->item_id = $prodModels->item_id;
                        $modelInventory->brand_id = $prodModels->brand_id;
                        $modelInventory->unit_id = $prodModels->unit_id;
                        $modelInventory->model_id = $modelPurchaseDetails->model_id;
                        $modelInventory->code = $prodModels->code;
                        $modelInventory->stock_in = $order_qty;
                        $modelInventory->transaction_date = $issueDate;
                        $modelInventory->month = date('m');
                        $modelInventory->year = date('Y');
                        $modelInventory->save();
                    }
                    $i++;
                }

                $transaction->commit();
                $condition = "purchase_id = {$model->id}";
                $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);
                echo CJSON::encode(array(
                    'status' => 'success',
                    'poReportInfo' => $this->renderPartial('poReportParts', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                ));
                Yii::app()->end();
            }
        } catch (Exception $e) {
            $transaction->rollback();
            echo CJSON::encode(array(
                'status' => 'errorBalance',
                'message' => $e->getMessage(),
            ));
            Yii::app()->end();
        }
    }

    // Parts Purchase Foreign - Update Page
    public function actionUpdateInfoForeign($id)
    {
        $model = PurchaseItems::model()->findByPk($id);
        $purchaseDetailsModel = PurchaseDetails::model()->findAll(array('condition' => "purchase_id=$id"));
        $rebate = 0;
        $this->performAjaxValidation($model);
        if (isset($_POST['PurchaseItems'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['PurchaseItems'];
            $model->bank_id = null;
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $grandTotal = $_POST['PurchaseItems']['grand_total'];
            $effective_date = $_POST['PurchaseItems']['effective_date'] != "" ? date('Y-m-d', strtotime($_POST['PurchaseItems']['effective_date'])) : NULL;
            $effective_date = ($effective_date != "0000-00-00") ? $effective_date : NULL;
            //if ($grandTotal != 0) {
            $valid = $model->validate();
            if ($valid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->order_by_id = Yii::app()->user->getId();
                    $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
                    $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
                    $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
                    $model->product_type = $_POST['PurchaseItems']['product_type'];
                    $model->boe_no = $_POST['PurchaseItems']['boe_no'];
                    $model->boe_date = !empty($_POST['PurchaseItems']['boe_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['boe_date'])) : null;
                    $model->lc_no = $_POST['PurchaseItems']['lc_no'];
                    $model->lc_date = !empty($_POST['PurchaseItems']['lc_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['lc_date'])) : null;
                    $model->port_id = $_POST['PurchaseItems']['port_id'];
                    $model->port_code = $_POST['PurchaseItems']['port_code'];
                    $model->country_id = $_POST['PurchaseItems']['country_id'];
                    $model->purchase_by_id = Yii::app()->user->getId();
                    $model->total_tax = $_POST['PurchaseItems']['total_tax'];
                    $model->total_sd = $_POST['PurchaseItems']['total_sd'];
                    $model->total = $_POST['PurchaseItems']['total'];
                    $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
                    $model->grand_total = $_POST['PurchaseItems']['grand_total'];
                    $model->effective_date = $effective_date;
                    $approve_count = $model->approve_count;
                    if ($model->save()) {
                        $i = 0;
                        // delete purchase details
                        PurchaseDetails::model()->deleteAllByAttributes(['purchase_id' => $model->id]);

                        foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                            $modelPurchaseDetails = new PurchaseDetails;
                            $modelPurchaseDetails->purchase_id = $model->id;
                            $modelPurchaseDetails->model_id = $tempItemId;
                            $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                            $modelPurchaseDetails->issue_date = $issueDate;
                            $modelPurchaseDetails->parts_service = 1;
                            $modelPurchaseDetails->local_import = PurchaseItems::IMPORT; //55=import
                            $modelPurchaseDetails->supplier_id = $model->supplier_id;
                            $modelPurchaseDetails->effective_date = $model->effective_date;
                            $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                            $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                            $modelPurchaseDetails->cv_rate = $_POST['PurchaseItems']['temp_cv_rate'][$i];
                            $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                            $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                            $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                            $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                            $modelPurchaseDetails->net_total = round(($_POST['PurchaseItems']['temp_total_amount'][$i] + $_POST['PurchaseItems']['temp_tax_amount'][$i]), 2);

                            if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                                $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                                $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                            } else {
                                $modelPurchaseDetails->rebate = null;
                                $modelPurchaseDetails->rebatable = null;
                            }
                            $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                            $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];
                            $modelPurchaseDetails->approve_count = $approve_count;

                            if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                            else if ($modelPurchaseDetails->vat_percent == 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                            else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                                if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == PurchaseDetails::FIXED)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                                else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == PurchaseDetails::EXEMPTED)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                                else
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                            } else
                                $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                            $modelPurchaseDetails->cd_percent = $_POST['PurchaseItems']['temp_cd_percent'][$i];
                            $modelPurchaseDetails->cd = $_POST['PurchaseItems']['temp_cd'][$i];
                            $modelPurchaseDetails->rd_percent = $_POST['PurchaseItems']['temp_rd_percent'][$i];
                            $modelPurchaseDetails->rd = $_POST['PurchaseItems']['temp_rd'][$i];
                            $modelPurchaseDetails->sd_percent = $_POST['PurchaseItems']['temp_sd_percent'][$i];
                            $modelPurchaseDetails->sd = $_POST['PurchaseItems']['temp_sd'][$i];
                            $modelPurchaseDetails->ait_percent = $_POST['PurchaseItems']['temp_ait_percent'][$i];
                            $modelPurchaseDetails->ait = $_POST['PurchaseItems']['temp_ait'][$i];
                            $modelPurchaseDetails->at_percent = $_POST['PurchaseItems']['temp_at_percent'][$i];
                            $modelPurchaseDetails->at = $_POST['PurchaseItems']['temp_at'][$i];

                            $modelPurchaseDetails->save();

                            $i++;
                        }
                        $transaction->commit();
                        $condition = "purchase_id = {$model->id}";
                        $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                        $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);
                        echo CJSON::encode(array(
                            'status' => 'success',
                            'poReportInfo' => $this->renderPartial('poReportParts', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                        ));
                        Yii::app()->end();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    echo CJSON::encode(array(
                        'status' => 'errorBalance',
                        'message' => $e->getMessage(),
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
            /* } else {
              echo CJSON::encode(array(
              'status' => 'errorBalance',
              ));
              Yii::app()->end();
              } */
        } else {
            $this->render('_formPartsForeignUpdate', array(
                'model' => $model,
                'purchaseDetailsModel' => $purchaseDetailsModel,
            ));
        }
    }

    // Service create page
    public function actionCreateServicePurchaseLocal()
    {
        $model = new PurchaseItems;
        $model->unsetAttributes();
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];
        $this->render('_formServicePurchaseLocal', array(
            'model' => $model,
        ));
    }

    // service action page
    public function actionSaveServicePurchaseLocal()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $thisServiceNo = VoucherNoFormate::model()->maxValOfThis('PurchaseItems', 'service_no', 'maxSINo');
        $rebate = 0;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $model = new PurchaseItems();
            $model->service_no = $thisServiceNo;
            $model->order_by_id = Yii::app()->user->getId();
            $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
            $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
            $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
            $model->ref_no = $_POST['PurchaseItems']['ref_no'];
            $model->chalan_date = !empty($_POST['PurchaseItems']['chalan_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['chalan_date'])) : null;
            $model->payment_method = $_POST['PurchaseItems']['payment_method'];
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $model->purchase_by_id = Yii::app()->user->getId();
            $model->total_tax = $_POST['PurchaseItems']['total_tax'];
            $model->total = $_POST['PurchaseItems']['total'];
            $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
            $model->total_vds = $vds = $_POST['PurchaseItems']['total_vds'];
            $model->grand_total = $_POST['PurchaseItems']['grand_total'];
            $model->approve_count = 1;
            $approve_count = $model->approve_count;
            if ($model->save()) {
                if ($vds > 0) {
                    $max_sl = VoucherNoFormate::model()->maxValOfThis('Vds', 'sl_no_po', 'sl_no_po');
                    $vdsrow = new Vds();
                    $vdsrow->purchase_id = $model->id;
                    $vdsrow->po_no = $model->service_no;
                    $vdsrow->sl_no_po = $max_sl;
                    $vdsrow->vds = $model->total_vds;
                    $vdsrow->vds_type = Vds::PURCHASE; // 1 = Purchase, 2 = Sales
                    $vdsrow->parts_service = 2;
                    $vdsrow->supplier_id = $model->supplier_id;
                    $vdsrow->issue_date = $model->issue_date;
                    $vdsrow->approve_count = $approve_count;
                    $vdsrow->save();
                }
                $i = 0;
                foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                    $modelPurchaseDetails = new PurchaseDetails();
                    $modelPurchaseDetails->purchase_id = $model->id;
                    $modelPurchaseDetails->issue_date = $issueDate;
                    $modelPurchaseDetails->parts_service = 2; // 2=Service
                    $modelPurchaseDetails->service_id = $tempItemId;
                    $modelPurchaseDetails->supplier_id = $model->supplier_id;
                    $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                    $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                    $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                    $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                    $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                    $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                    $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                    $modelPurchaseDetails->net_total = $_POST['PurchaseItems']['temp_net_total'][$i];
                    $modelPurchaseDetails->approve_count = $approve_count;

                    if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                        $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                        $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                    }
                    $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                    $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];

                    if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                    else if ($modelPurchaseDetails->vat_percent == 15)
                        $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                    else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                        if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 3)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                        else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 4)
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                        else
                            $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                    } else
                        $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                    if ($modelPurchaseDetails->save()) {

                        $common = new Common();
                        $common->purchase_id = $modelPurchaseDetails->purchase_id;
                        $common->service_id = $modelPurchaseDetails->service_id;
                        $common->date = $modelPurchaseDetails->issue_date;
                        $common->type = Common::PURCHASE; // 1= Purchase
                        $common->parts_service = $modelPurchaseDetails->parts_service;
                        $common->order_qty = $modelPurchaseDetails->order_qty;
                        $common->unit_id = $modelPurchaseDetails->unit_id;
                        $common->unit_price = $modelPurchaseDetails->unit_price;
                        $common->vat_percent = $modelPurchaseDetails->vat_percent;
                        $common->vat = $modelPurchaseDetails->vat;
                        $common->total_tax = $modelPurchaseDetails->total_tax;
                        $common->total = $modelPurchaseDetails->total;
                        $common->net_total = $modelPurchaseDetails->net_total;
                        $common->rebate = $modelPurchaseDetails->rebate;
                        $common->rebatable = $modelPurchaseDetails->rebatable;
                        $common->stock_in = $modelPurchaseDetails->order_qty;
                        $common->price_in = $modelPurchaseDetails->total;
                        $common->approve_count = $approve_count;
                        $common->save();
                    }
                    $i++;
                }

                $transaction->commit();
                $condition = "purchase_id = {$model->id}";
                $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);

                echo CJSON::encode(array(
                    'status' => 'success',
                    'poReportInfo' => $this->renderPartial('serviceReport', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                ));
                Yii::app()->end();
            }
        } catch (Exception $e) {
            $transaction->rollback();
            echo CJSON::encode(array(
                'status' => 'errorBalance',
                'message' => $e->getMessage(),
            ));
            Yii::app()->end();
        }
    }

    // purchase (Service) - Update
    public function actionUpdateInfoServices($id)
    {
        $model = PurchaseItems::model()->findByPk($id);
        $purchaseDetailsModel = PurchaseDetails::model()->findAll(array('condition' => "purchase_id=$id"));
        $rebate = 0;
        $this->performAjaxValidation($model);
        if (isset($_POST['PurchaseItems'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['PurchaseItems'];
            $model->bank_id = null;
            if ($model->payment_method == PurchaseItems::BANK)
                $model->bank_id = isset($_POST['PurchaseItems']['bank_id']) ? $_POST['PurchaseItems']['bank_id'] : null;
            $grandTotal = $_POST['PurchaseItems']['grand_total'];
            //if ($grandTotal != 0) {
            $valid = $model->validate();
            if ($valid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->order_by_id = Yii::app()->user->getId();
                    $model->purchase_by_id = Yii::app()->user->getId();
                    $model->supplier_id = $supplierId = $_POST['PurchaseItems']['supplier_id'];
                    $model->supplier_type_id = Suppliers::model()->findByPk($supplierId)->supplier_type_id;
                    $model->issue_date = $issueDate = date('Y-m-d', strtotime($_POST['PurchaseItems']['issue_date']));
                    $model->ref_no = $_POST['PurchaseItems']['ref_no'];
                    $model->chalan_date = !empty($_POST['PurchaseItems']['chalan_date']) ? date('Y-m-d', strtotime($_POST['PurchaseItems']['chalan_date'])) : null;
                    $model->total_tax = $_POST['PurchaseItems']['total_tax'];
                    $model->total = $_POST['PurchaseItems']['total'];
                    $model->total_rebate = isset($_POST['PurchaseItems']['total_rebate']) ? $_POST['PurchaseItems']['total_rebate'] : null;
                    $model->total_vds = $vds = $_POST['PurchaseItems']['total_vds'];
                    $model->grand_total = $_POST['PurchaseItems']['grand_total'];
                    $approve_count = $model->approve_count;
                    if ($model->save()) {
                        if ($vds > 0) {
                            $vdsrow = Vds::model()->findByAttributes(['purchase_id' => $model->id]);
                            if (!$vdsrow) {
                                $max_sl = VoucherNoFormate::model()->maxValOfThis('Vds', 'sl_no_po', 'sl_no_po');
                                $vdsrow = new Vds();
                                $vdsrow->purchase_id = $model->id;
                                $vdsrow->po_no = $model->service_no;
                                $vdsrow->sl_no_po = $max_sl;
                            }
                            $vdsrow->vds = $model->total_vds;
                            $vdsrow->vds_type = Vds::PURCHASE; // 1 = Purchase, 2 = Sales
                            $vdsrow->parts_service = 2;
                            $vdsrow->supplier_id = $model->supplier_id;
                            $vdsrow->issue_date = $model->issue_date;
                            $vdsrow->approve_count = $approve_count;
                            $vdsrow->save();
                        } else {
                            Vds::model()->deleteAllByAttributes(['purchase_id' => $model->id]);
                        }
                        $i = 0;
                        // delete purchase details
                        PurchaseDetails::model()->deleteAllByAttributes(['purchase_id' => $model->id]);
                        // delete from (purchase data)
                        Common::model()->deleteAllByAttributes(['purchase_id' => $model->id]);

                        foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {
                            $modelPurchaseDetails = new PurchaseDetails();
                            $modelPurchaseDetails->purchase_id = $model->id;
                            $modelPurchaseDetails->issue_date = $issueDate;
                            $modelPurchaseDetails->parts_service = 2;  // 2=Service
                            $modelPurchaseDetails->service_id = $tempItemId;
                            $modelPurchaseDetails->supplier_id = $model->supplier_id;
                            $modelPurchaseDetails->order_qty = $order_qty = $_POST['PurchaseItems']['temp_order_qty'][$i];
                            $modelPurchaseDetails->unit_id = $_POST['PurchaseItems']['temp_unit'][$i];
                            $modelPurchaseDetails->unit_price = $unitPrice = $_POST['PurchaseItems']['temp_purchase_price'][$i];
                            $modelPurchaseDetails->vat_percent = $_POST['PurchaseItems']['temp_vat_percent'][$i];
                            $modelPurchaseDetails->vat = $_POST['PurchaseItems']['temp_vat_amount'][$i];
                            $modelPurchaseDetails->total_tax = $_POST['PurchaseItems']['temp_tax_amount'][$i];
                            $modelPurchaseDetails->total = $_POST['PurchaseItems']['temp_total_amount'][$i];
                            $modelPurchaseDetails->net_total = $_POST['PurchaseItems']['temp_net_total'][$i];
                            if (YourCompany::model()->companyVatType() == YourCompany::STANDARD) {
                                $modelPurchaseDetails->rebate = $rebate = $_POST['PurchaseItems']['temp_rebate_amount'][$i];
                                $modelPurchaseDetails->rebatable = ($rebate > 0) ? 1 : null;
                            } else {
                                $modelPurchaseDetails->rebate = null;
                                $modelPurchaseDetails->rebatable = null;
                            }
                            $modelPurchaseDetails->base_vat = $_POST['PurchaseItems']['temp_base_vat'][$i];
                            $modelPurchaseDetails->base_vat_percent = $_POST['PurchaseItems']['temp_base_vat_percent'][$i];
                            $modelPurchaseDetails->approve_count = $approve_count;

                            if ($modelPurchaseDetails->vat_percent > 0 && $modelPurchaseDetails->vat_percent < 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                            else if ($modelPurchaseDetails->vat_percent == 15)
                                $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                            else if ($modelPurchaseDetails->vat_percent == 0 || $modelPurchaseDetails->vat_percent == "") {
                                if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 3)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                                else if ($_POST['PurchaseItems']['temp_vat_calculation_method'][$i] == 4)
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                                else
                                    $modelPurchaseDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                            } else
                                $modelPurchaseDetails->vat_calculation_method = $_POST['PurchaseItems']['temp_vat_calculation_method'][$i];

                            if ($modelPurchaseDetails->save()) {

                                $common = new Common();
                                $common->purchase_id = $modelPurchaseDetails->purchase_id;
                                $common->service_id = $modelPurchaseDetails->service_id;
                                $common->date = $modelPurchaseDetails->issue_date;
                                $common->type = Common::PURCHASE; // 1= Purchase
                                $common->parts_service = $modelPurchaseDetails->parts_service;
                                $common->order_qty = $modelPurchaseDetails->order_qty;
                                $common->unit_id = $modelPurchaseDetails->unit_id;
                                $common->unit_price = $modelPurchaseDetails->unit_price;
                                $common->vat_percent = $modelPurchaseDetails->vat_percent;
                                $common->vat = $modelPurchaseDetails->vat;
                                $common->total_tax = $modelPurchaseDetails->total_tax;
                                $common->total = $modelPurchaseDetails->total;
                                $common->net_total = $modelPurchaseDetails->net_total;
                                $common->rebate = $modelPurchaseDetails->rebate;
                                $common->rebatable = $modelPurchaseDetails->rebatable;
                                $common->stock_in = $modelPurchaseDetails->order_qty;
                                $common->price_in = $modelPurchaseDetails->total;
                                $common->approve_count = $approve_count;
                                $common->save();
                            }
                            $i++;
                        }

                        $transaction->commit();
                        $condition = "purchase_id = {$model->id}";
                        $data = PurchaseDetails::model()->findAll(array('condition' => $condition));
                        $purchaseItemModel = PurchaseItems::model()->findByPk($model->id);
                        echo CJSON::encode(array(
                            'status' => 'success',
                            'poReportInfo' => $this->renderPartial('serviceReport', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel), true, true),
                        ));
                        Yii::app()->end();
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    echo CJSON::encode(array(
                        'status' => 'errorBalance',
                        'message' => $e->getMessage(),
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
            /* } else {
              echo CJSON::encode(array(
              'status' => 'errorBalance',
              ));
              Yii::app()->end();
              } */
        } else {
            $this->render('_formUpdateServices', array(
                'model' => $model,
                'purchaseDetailsModel' => $purchaseDetailsModel,
            ));
        }
    }

    public function actionPurchaseReturn()
    {
        $model = new PurchaseItems;
        if (isset($_POST['PurchaseItems'])) {
            $id = $_POST['PurchaseItems']['id'];
            if (!empty($id) && $model = PurchaseItems::model()->findByPk($id)) {
                echo $id;
            } else {
                echo "<div class='flash-error'>Please select purchase order no!</div>";
            }
        } else {
            $this->render('purchase_return', array(
                'model' => $model,
            ));
        }
    }

    // Add debit note form (Purchase Return)
    public function actionPurchaseReturnForm($id)
    {
        $model = PurchaseItems::model()->findByPk($id);
        $purchaseDetailsModel = PurchaseDetails::model()->findAll(array('condition' => "purchase_id=$id"));
        $this->performAjaxValidation($model);
        if (isset($_POST['PurchaseItems'])) {
            $i = 0;
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $max_sl = VoucherNoFormate::model()->maxValOfThis('PurchaseReturn', 'sl_no', 'sl_no');
            $transaction = Yii::app()->db->beginTransaction();
            try {
                foreach ($_POST['PurchaseItems']['temp_model_id'] as $tempItemId) {

                    $adjustment_qty = $_POST['temp_adjustment_qty'][$i];
                    if ($adjustment_qty > 0) {
                        $purchaseDetails = PurchaseDetails::model()->findByAttributes(array('purchase_id' => $id, 'model_id' => $tempItemId));
                        $purchaseReturn = new PurchaseReturn();
                        $purchaseReturn->purchase_id = $model->id;
                        $purchaseReturn->purchase_details_id = !empty($purchaseDetails->id) ? $purchaseDetails->id : '';
                        $purchaseReturn->po_no = !empty($model->po_no) ? $model->po_no : '';
                        $purchaseReturn->sl_no = $max_sl;
                        $purchaseReturn->model_id = $tempItemId;
                        $purchaseReturn->parts_service = $_POST['PurchaseItems']['temp_parts_service'][$i];
                        $purchaseReturn->unit_id = !empty($purchaseDetails->unit_id) ? $purchaseDetails->unit_id : '';
                        $purchaseReturn->qty = $adjustment_qty;
                        $purchaseReturn->unit_price = !empty($purchaseDetails->unit_price) ? $purchaseDetails->unit_price : '';
                        $purchaseReturn->sd_new = $_POST['PurchaseItems']['temp_sd_new'][$i];
                        $purchaseReturn->vat_new = $_POST['PurchaseItems']['temp_vat_amount_new'][$i];
                        $purchaseReturn->tax_new = $_POST['PurchaseItems']['temp_tax_amount_new'][$i];
                        $purchaseReturn->total_new = $_POST['PurchaseItems']['temp_total_amount_new'][$i];
                        $purchaseReturn->net_total_new = $_POST['PurchaseItems']['netTotal_new'][$i];
                        $purchaseReturn->return_user_id = Yii::app()->user->getId();
                        $purchaseReturn->return_challan_no = $_POST['PurchaseItems']['return_challan_no'];
                        $purchaseReturn->return_date = date('Y-m-d', strtotime($_POST['PurchaseItems']['return_date']));
                        $purchaseReturn->reason = $_POST['PurchaseItems']['reason'];
                        if ($purchaseReturn->save()) {

                            $common = new Common();
                            $common->purchase_id = $purchaseReturn->purchase_id;
                            $common->date = $purchaseReturn->return_date;
                            $common->type = Common::DEBIT; // 6= Debit
                            if ($purchaseReturn->parts_service == 1) {
                                $common->model_id = $purchaseReturn->model_id;
                            } else {
                                $common->service_id = $purchaseReturn->model_id;
                            }
                            $common->parts_service = $purchaseReturn->parts_service;
                            $common->unit_id = $purchaseReturn->unit_id;
                            $common->sd = $purchaseReturn->sd_new;
                            $common->vat = $purchaseReturn->vat_new;
                            $common->total_tax = $purchaseReturn->tax_new;
                            $common->total = $purchaseReturn->total_new;
                            $common->net_total = $purchaseReturn->net_total_new;
                            $common->stock_out = $purchaseReturn->qty;
                            $common->price_out = $purchaseReturn->total_new;
                            $common->return_challan_no = $purchaseReturn->return_challan_no;
                            $common->reason = $purchaseReturn->reason;
                            $common->approve_count = 2;
                            $common->save();

                            if ($purchaseReturn->parts_service == 1) {
                                $modelInventory = new Inventory;
                                //$modelInventory->purchase_details_id = $purchaseReturn->purchase_details_id;
                                $modelInventory->store_id = Stores::FG_STORE;
                                $modelInventory->location_id = Location::DEFAULT_LOCATION;
                                $prodModels = ProdModels::model()->findByPk($purchaseDetails->model_id);
                                $modelInventory->item_id = $prodModels->item_id;
                                $modelInventory->brand_id = $prodModels->brand_id;
                                $modelInventory->unit_id = $prodModels->unit_id;
                                $modelInventory->model_id = $purchaseDetails->model_id;
                                $modelInventory->code = $prodModels->code;
                                $modelInventory->stock_out = $adjustment_qty;
                                $modelInventory->transaction_date = $purchaseReturn->return_date;
                                $modelInventory->month = date('m');
                                $modelInventory->year = date('Y');
                                $modelInventory->save();
                            }
                        }
                    }
                    $i++;
                }

                $transaction->commit();
                $sql = "SELECT * FROM purchase_return WHERE sl_no = $max_sl ORDER BY id ASC";
                $data = Yii::app()->db->createCommand($sql)->queryAll();
                $purchase_id = $data[0]['purchase_id'];
                $purchaseItemModel = PurchaseItems::model()->findByPk($purchase_id);
                echo CJSON::encode(array(
                    'status' => 'success',
                    'poReportInfo' => $this->renderPartial('//purchaseReturn/poReturnReport', array(
                        'data' => $data,
                        'purchaseItemModel' => $purchaseItemModel
                    ), true, true),
                ));
                Yii::app()->end();
            } catch (Exception $e) {
                $transaction->rollback();
                echo CJSON::encode(array(
                    'status' => 'errorBalance',
                    'message' => $e->getMessage(),
                ));
                Yii::app()->end();
            }
        } else {
            $this->render('purchase_return_form_local', array(
                'model' => $model,
                'purchaseDetailsModel' => $purchaseDetailsModel,
            ));
        }
    }

    public function actionMushakFourThree()
    {
        $model = new ProdModels;
        $this->render('mushak_four_three', array(
            'model' => $model,
        ));
    }

    public function actionMushakFourThreeView()
    {
        $model_id = $_POST['ProdModels']['id'];
        if (!empty($model_id)) {
            $sql = "SELECT prod_models.*, material_recipe.created_by as createdBy, material_recipe.model_qty_unit_id as model_qty_unit_id, material_recipe.config_date FROM prod_models INNER JOIN material_recipe ON prod_models.id = material_recipe.model_id WHERE prod_models.id =$model_id GROUP BY prod_models.id ORDER BY prod_models.model_name ASC";
        } else {
            $sql = "SELECT * FROM prod_models ORDER BY model_name ASC";
        }
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        echo CJSON::encode(array(
            'content' => $this->renderPartial('mushak_four_three_view', array(
                'data' => $data,
            ), true, true),
        ));
    }

    public function actionMushakSixOne()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_one', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixOneView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        if (!empty($id = $_POST['model_id'])) {
            $concat .= "AND model_id='$id'";
        }
        $sql = "SELECT model_id, service_id, parts_service FROM common WHERE type in(1,4,6,7,8) AND item_id !=1 " . $concat . " GROUP BY model_id, service_id ORDER BY parts_service ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('mushak_six_one_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    public function actionMushakSixTwoManufacturing()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_two_manufacturing', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixTwoManufacturingView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = " AND item_id=1 ";//data fetch only finishgoods
        if (!empty($id = $_POST['model_id'])) {
            $concat .= "AND model_id='$id' ";
        }
        $sql = "SELECT model_id, service_id, parts_service FROM common WHERE type in(1,2,3,5,7,8) AND item_id =1 " . $concat . " GROUP BY model_id, service_id ORDER BY parts_service ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('mushak_six_two_manufacturing_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    public function actionMushakSixTwoService()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_two_service', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixTwoServiceView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $sql = "SELECT id, so_no, mushok_no, sales_date FROM sell_items WHERE sales_date BETWEEN '$start_date' AND '$end_date' AND approve_count=2 ORDER BY so_no asc";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
        } else {
            echo "<div class='flash-error'>Please select from date & to date!</div>";
        }
        echo CJSON::encode(array(
            'content' => $this->renderPartial('mushak_six_two_service_view', array(
                'data' => $data,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    public function actionMushakSixTwoOne()
    {
        $model = new PurchaseDetails();
        $this->render('mushak_six_two_one', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixTwoOneView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        if (!empty($id = $_POST['model_id'])) {
            $concat = "AND model_id='$id' ";
        }
        $sql = "SELECT model_id, service_id FROM common WHERE date BETWEEN '$start_date' AND '$end_date' " . $concat . " AND parts_service=1 AND (type=1 OR type=2) AND approve_count=2 GROUP BY model_id ORDER BY id ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('mushak_six_two_one_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'purchaseInfo' => null,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    public function actionMushakSixThree()
    {
        $model = new PurchaseItems;
        $this->render('mushak_six_three', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixThreeView()
    {

        $id = $_POST['PurchaseItems']['id'];
        if (!empty($id)) {
            $data = SellItems::model()->findByPk($id);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_three_view', array(
                    'data' => $data,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select sales order no!</div>";
        }
    }

    public function actionMushakSixFour()
    {
        $model = new PurchaseItems;
        $this->render('mushak_six_four', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixFourView()
    {

        $id = $_POST['PurchaseItems']['id'];
        if (!empty($id)) {
            $data = Production::model()->findByPk($id);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_four_view', array(
                    'data' => $data,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select Production Challan no!</div>";
        }
    }

    public function actionMushakSixFive()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_five', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixFiveView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));

        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $sql = "SELECT id FROM stock_transfer_history WHERE sent_date BETWEEN '$start_date' AND '$end_date' ORDER BY sent_date ASC";
            $data = Yii::app()->db->createCommand($sql)->queryAll();

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_five_view', array(
                    'data' => $data,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select date!</div>";
        }
    }

    public function actionMushakSixSix()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_six', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixSixView()
    {
        $issue_date = $_POST['PurchaseDetails']['issue_date'];
        $vds_payment_id = $_POST['PurchaseDetails']['id'];
        $issue_month = date('Y-m', strtotime($issue_date));

        if (!empty($issue_date) && $vds_payment_id == "") {
            $sql = "SELECT * FROM vds_payment WHERE created_datetime LIKE '$issue_month%' AND vds_type = 1 ORDER BY created_datetime ASC";
        } else if ($vds_payment_id > 0 && empty($issue_date)) {
            $sql = "SELECT * FROM vds_payment WHERE id=$vds_payment_id ORDER BY created_datetime ASC";
        } else {
            echo "<div class='flash-error'>Please select month!</div>";
        }

        if (!empty($sql)) {
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_six_view', array(
                    'data' => $data,
                ), true, true),
            ));
        }
    }

    public function actionMushakSixSeven()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_seven', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixSevenView()
    {
        $sl_no = $_POST['PurchaseDetails']['id'];
        if (!empty($sl_no)) {
            $sql = "SELECT * FROM sales_return WHERE sl_no=$sl_no ORDER BY model_id ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

          if ($data && ($data[0]['sales_id'] > 0))
                $salesData = SellItems::model()->findByPk($data[0]['sales_id']);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_seven_view', array(
                    'data' => $data,
                    'salesData' => $salesData,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select SO/CL no!</div>";
        }
    }

    public function actionMushakSixEight()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_eight', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixEightView()
    {
        $sl_no = $_POST['PurchaseDetails']['id'];
        if (!empty($sl_no)) {
            $sql = "SELECT * FROM purchase_return WHERE sl_no=$sl_no ORDER BY model_id ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

            if ($data && ($data[0]['purchase_id'] > 0))
                $purchaseData = PurchaseItems::model()->findByPk($data[0]['purchase_id']);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_eight_view', array(
                    'data' => $data,
                    'purchaseData' => $purchaseData,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select PO/CL no!</div>";
        }
    }

    public function actionMushakSixTen()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_six_ten', array(
            'model' => $model,
        ));
    }

    public function actionMushakSixTenView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {

            $sql = "SELECT * FROM purchase_items WHERE grand_total >=200000 AND approve_count=2 AND (effective_date BETWEEN '$start_date' AND '$end_date') ORDER BY effective_date ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $purchaseData = $command->queryAll();

            $sql2 = "SELECT * FROM sell_items WHERE grand_total >=200000 AND approve_count=2 AND (sales_date BETWEEN '$start_date' AND '$end_date') ORDER BY sales_date ASC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql2);
            $salesData = $command->queryAll();

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_six_ten_view', array(
                    'purchaseData' => $purchaseData,
                    'salesData' => $salesData,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select month!</div>";
        }
    }

    public function actionMushakNineOne()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_nine_one', array(
            'model' => $model,
        ));
    }

    public function actionMushakNineOneView()
    {
        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        }
        $preview = '';
        if ($_POST['preview'] == 1)
            $preview = 1;
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $translation_type = $_POST['translation_type'];
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            if ($_POST['start_date'] < $_POST['end_date']) {
                $sql = "SELECT model_id FROM purchase_details WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND model_id > 0 GROUP BY model_id ORDER BY model_id DESC";
                $data = Yii::app()->db->createCommand($sql)->queryAll();

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('mushak_nine_one_view', array(
                        'data' => $data,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'translation_type' => $translation_type,
                        'preview' => $preview
                    ), true, true),
                ));
            } else {
                echo CJSON::encode(array(
                    'content' => "<div class='flash-error'>Please select proper date!</div>",
                ));
            }
        } else {
            echo "<div class='flash-error'>Please select from date & to date!</div>";
        }
    }

    public function actionMushakNineOneCreate()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_nine_one_create', array(
            'model' => $model,
        ));
    }

    public function actionMushakNineOneSave()
    {

        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $translation_type = $_POST['PurchaseDetails']['translation_type'];
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $sql = "SELECT model_id FROM purchase_details WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND model_id >0 GROUP BY model_id ORDER BY model_id DESC";
            $data = Yii::app()->db->createCommand($sql)->queryAll();

            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_nine_one_view_create', array(
                    'data' => $data,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'translation_type' => $translation_type
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select from date & to date!</div>";
        }
    }

    public function actionNineOneSummary()
    {
        $model = new PurchaseDetails;
        $this->render('nine_one_summary_report', array(
            'model' => $model,
        ));
    }

    public function actionNineOneSummaryView()
    {

        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $sql = "SELECT model_id FROM purchase_details WHERE issue_date BETWEEN '$start_date' AND '$end_date' AND model_id >0 GROUP BY model_id ORDER BY model_id DESC";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

            echo CJSON::encode(array(
                'content' => $this->renderPartial('nine_one_summary_report_view', array(
                    'data' => $data,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select from date & to date!</div>";
        }
    }

    public function actionMushakTRSix()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_tr_six', array(
            'model' => $model,
        ));
    }

    public function actionMushakTRSixView()
    {
        $vat = $_POST['vat'];

        if (!empty($vat)) {
            $data = VatPayment::model()->findByPk($vat);
            $type = 'vat';
        } else {
            echo "<div class='flash-error'>Please select month!</div>";
        }

        if (!empty($data)) {
            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_tr_six_view', array(
                    'data' => $data,
                    'type' => $type,
                ), true, true),
            ));
        }
    }

    public function actionMushakEighteen()
    {
        $model = new PurchaseDetails;
        $this->render('mushak_eighteen', array(
            'model' => $model,
        ));
    }

    public function actionMushakEighteenView()
    {

        $start_date = $_POST['start_date'];
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = $_POST['end_date'];
        $end_date = date('Y-m-d', strtotime($end_date));
        if ($start_date != "" && $end_date != "") {
            echo CJSON::encode(array(
                'content' => $this->renderPartial('mushak_eighteen_view', array(
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ), true, true),
            ));
        } else {
            echo "<div class='flash-error'>Please select start date & end date!</div>";
        }
    }

    // MUSOK SUB FORM
    public function actionFormKa($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_ka', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormGa($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_ga', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormKha($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_kha', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormKaPurchase($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_ka_purchase', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormGha($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_gha', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormUmo($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_umo', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormCo($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_co', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionFormCho($note = null, $start_date = null, $end_date = null)
    {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('subform_cho', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionPurchaseReport()
    {
        $model = new PurchaseDetails;
        $this->render('purchase_report', array(
            'model' => $model
        ));
    }

    public function actionPurchaseReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $prod_id = $_POST['model_id'];

        $condition = "item_id = 2 ";
        if ($prod_id > 0) {
            $condition .= " && id = $prod_id";
        }
        $prodModels = ProdModels::model()->findAll(array("condition" => $condition));

        echo CJSON::encode(array(
            'content' => $this->renderPartial('purchase_report_view', array(
                'prodModels' => $prodModels,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prod_id' => $prod_id,
            ), true, true),
        ));
    }

    // PURCHASE ITEM REPORT
    public function actionPurchaseItemReport()
    {
        $model = new PurchaseItems();
        $this->render('purchase_item_report', array(
            'model' => $model
        ));
    }

    // PURCHASE ITEM REPORT VIEW
    public function actionPurchaseItemReportView()
    {
        $po_no = '';
        $start_date = '';
        $end_date = '';
        $approve_type = '';
        $supplier_id = '';
        $supplier_type_id = '';
        $local_import = '';
        $report_type = '';

        if ($_POST['po_no'] != "") {
            $po_no = $_POST['po_no'];
        } else if ($_POST['start_date'] != "" && $_POST['end_date'] != "") {
            $start_date = date('Y-m-d', strtotime($_POST['start_date']));
            $end_date = date('Y-m-d', strtotime($_POST['end_date']));
            $approve_type = $_POST['PurchaseItems']['approve_type'];
            $supplier_id = $_POST['supplier_id'];
            $supplier_type_id = $_POST['PurchaseItems']['supplier_type_id'];
            $local_import = $_POST['PurchaseItems']['local_import'];
            $report_type = $_POST['PurchaseItems']['report_type'];
        } else {

        }
        echo CJSON::encode(array(
            'content' => $this->renderPartial('purchase_item_report_view', array(
                'po_no' => $po_no,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'supplier_id' => $supplier_id,
                'approve_type' => $approve_type,
                'supplier_type_id' => $supplier_type_id,
                'local_import' => $local_import,
                'report_type' => $report_type
            ), true, true),
        ));
    }

    // PURCHASE DETAILS REPORT
    public function actionPurchaseCustomReport()
    {
        $model = new PurchaseDetails;
        $this->render('purchase_custom_report', array(
            'model' => $model
        ));
    }

    // PURCHASE DETAILS REPORT VIEW
    public function actionPurchaseCustomReportView()
    {
        if ($_POST['start_date'] != "" || $_POST['end_date'] != "") {
            $start_date = date('Y-m-d', strtotime($_POST['start_date']));
            $end_date = date('Y-m-d', strtotime($_POST['end_date']));
            echo CJSON::encode(array(
                'content' => $this->renderPartial('purchase_custom_report_view', array(
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'model_id' => $_POST['model_id'],
                    'approve_type' => $_POST['PurchaseDetails']['approve_type'],
                    //'supplier_type_id' => $_POST['PurchaseDetails']['supplier_type_id'],
                    'vat_calculation_method' => $_POST['PurchaseDetails']['vat_calculation_method'],
                    'local_import' => $_POST['PurchaseDetails']['local_import'],
                    'report_type' => $_POST['PurchaseDetails']['report_type']
                ), true, true),
            ));
        } else {
            echo CJSON::encode(array(
                'content' => $this->renderPartial('purchase_custom_report_view'),
            ));
        }
    }

    /*
      public function actionPurchaseReport($type = NULL) {
      $model = new PurchaseDetails;
      $this->render('purchase_report', array(
      'model' => $model,
      'type' => $type,
      ));
      }

      public function actionPurchaseReportView() {
      $start_date = date('Y-m-d', strtotime($_POST['start_date']));
      $end_date = date('Y-m-d', strtotime($_POST['end_date']));
      $concat = "";
      if(!empty($id = $_POST['model_id'])){
      $concat.= "AND model_id='$id' ";
      }
      if(!empty($type = $_POST['type'])){
      $concat.= " AND local_import=$type ";
      }
      $sql = "SELECT * FROM common WHERE date BETWEEN '$start_date' AND '$end_date' ".$concat." AND type=1 ORDER BY date,id ASC";
      $connection = Yii::app()->db;
      $command = $connection->createCommand($sql);
      $data = $command->queryAll();
      echo CJSON::encode(array(
      'content' => $this->renderPartial('purchase_report_view', array(
      'data' => $data,
      'prod_id' => $id,
      'start_date' => $start_date,
      'end_date' => $end_date,
      ), true, true),
      ));
      } */

    public function actionAdminPartsLocal()
    {
        $model = new PurchaseItems('searchPartsLocal');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];

        $this->render('adminPartsLocal', array(
            'model' => $model,
        ));
    }

    public function actionAdminPartsForeign()
    {
        $model = new PurchaseItems('searchPartsForeign');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];

        $this->render('adminPartsForeign', array(
            'model' => $model,
        ));
    }

    public function actionAdminServices()
    {
        $model = new PurchaseItems('searchServices');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseItems']))
            $model->attributes = $_GET['PurchaseItems'];

        $this->render('admin_services', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = PurchaseItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'purchase-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionCurrentAccAdjReport()
    {
        $model = new Adjustments();
        $this->render('currentAccAdjReport', array(
            'model' => $model
        ));
    }

    public function actionCurrentAccAdjReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        echo CJSON::encode(array(
            'content' => $this->renderPartial('currentAccAdjReportView', array(
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    public function actionSubFormTwentySeven($note=null, $start_date=null, $end_date=null) {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('sub_form_twenty_seven', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }

    public function actionSubFormThirtyTwo($note=null, $start_date=null, $end_date=null) {
        $note = $_REQUEST['note'];
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $model = new PurchaseDetails;
        $this->render('sub_form_thirty_two', array(
            'model' => $model,
            'note' => $note,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ));
    }


    /*
    public function actionAnalyzeData(){
        $sql = "select * from common where type = 3";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $items = $command->queryAll();
        foreach($items as $item){
            $fg_price = MaterialRecipe::model()->getRecipeTotalCostByProductId($item['model_id']);
            Common::model()->updateByPk($item['id'], array('price_in' => $item['stock_in'] * $fg_price));
            echo "Updating... <br>";
        }
    }
    */

}
