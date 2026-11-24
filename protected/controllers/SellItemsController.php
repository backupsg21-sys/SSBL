<?php

class SellItemsController extends Controller
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
            'rights-
			Jquery_showMobileSearch-
			JqueryCustomerDetails',
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionApproveSales($id)
    {
        $sModel = SellItems::model()->findByPk($id);
        $oldCount = $sModel->approve_count;

        if ($oldCount < 2) {
            $countSellItem = $oldCount + 1;

            $cirteria = new CDbCriteria();
            $cirteria->select = '*';
            $cirteria->condition = "sales_id='" . $id . "'";
            $sales_data = SellDetails::model()->findAll($cirteria);

            $i = 0;
            foreach ($sales_data as $modelSellDetails) {
                $common           = new Common();
                $common->sales_id = $modelSellDetails->sales_id;
                $common->date     = $modelSellDetails->sales_date;
                $common->type     = Common::SALES; // 1= Sales
                if ($modelSellDetails->parts_service == 1) {
                    $common->model_id = $modelSellDetails->model_id;
                } else {
                    $common->service_id = $modelSellDetails->model_id;
                }
                $common->parts_service  = $modelSellDetails->parts_service;
                $common->local_import   = $modelSellDetails->sales_type;
                $common->stock_qty      = $modelSellDetails->stock_qty;
                $common->order_qty      = $modelSellDetails->sales_qty;
                $common->unit_id        = $modelSellDetails->unit_id;
                $common->unit_price     = $modelSellDetails->unit_price;
                $common->vat_percent    = $modelSellDetails->vat_percent;
                $common->vat            = $modelSellDetails->vat;
                $common->sd_percent     = $modelSellDetails->sd_percent;
                $common->sd             = $modelSellDetails->sd;
                $common->total_tax      = $modelSellDetails->total_tax;
                $common->total          = $modelSellDetails->total;
                $common->net_total      = $modelSellDetails->net_total;
                $common->stock_out      = $modelSellDetails->sales_qty;
                $common->price_out      = $modelSellDetails->total;
                $common->approve_count  = 2;
                $common->save();

                if ($modelSellDetails->parts_service == 1) {
                    $modelInventory                   = new Inventory;
                    $modelInventory->sales_id         = $modelSellDetails->sales_id;
                    $modelInventory->sell_details_id  = $modelSellDetails->id;
                    $modelInventory->store_id         = isset($_POST['SellItems']['temp_store_id'][$i]) ? $_POST['SellItems']['temp_store_id'][$i] : 1;
                    $modelInventory->location_id      = Location::DEFAULT_LOCATION;
                    $prodModels                       = ProdModels::model()->findByPk($modelSellDetails->model_id);
                    $modelInventory->item_id          = $prodModels->item_id;
                    $modelInventory->brand_id         = $prodModels->brand_id;
                    $modelInventory->unit_id          = $prodModels->unit_id;
                    $modelInventory->model_id         = $modelSellDetails->model_id;
                    $modelInventory->code             = $prodModels->code;
                    $modelInventory->stock_out        = $modelSellDetails->sales_qty;
                    $modelInventory->transaction_date = $modelSellDetails->sales_date;
                    $modelInventory->month            = date('m');
                    $modelInventory->year             = date('Y');
                    $modelInventory->save();
                }

                $i++;
            }

            SellItems::model()->updateByPk($id, array("approve_count" => $countSellItem));
            SellDetails::model()->updateAll(array('approve_count' => $countSellItem), "sales_id= $id ");
            Vds::model()->updateAll(array('approve_count' => $countSellItem), "sales_id= $id ");
            if ($countSellItem == 2) {

                /*// To Journal
                $user_id = Yii::app()->user->getId();
                $gTotal = round($sModel->grand_total);
                $approveDate = "";
                $customer_ids = $sModel->customer_id;

                $entry_no = VoucherNoFormateAc::model()->maxValOfThisOfThisType('JournalEntry', 'entry_no', 'maxval', 7);
                //===========================for Credit COA======================//
                $JournalEntryCrData['bill_no'] = $thisSellNo = "SO-" . $sModel->so_no;
                $JournalEntryCrData['narration'] = $narration = 'Sales Order of Customer ' . Customers::model()->customerName($customer_ids);
                $JournalEntryCrData['entry_date'] = $approveDate; // $sModel->approve_date;
                $JournalEntryCrData['ca_id'] = 14;
                $JournalEntryCrData['transaction_type'] = 4;
                $JournalEntryCrData['amount'] = $gTotal;
                $JournalEntryCrData['main_ca'] = ChartOfAc::model()->findMainCAofThis(14);
                $JournalEntryCrData['voucher_type'] = 7;
                $JournalEntryCrData['is_deletable'] = JournalEntry::NOT_DELETABLE;
                $JournalEntryCrData['entry_no'] = $entry_no;
                $JournalEntryCrData['create_by'] = $user_id;
                $JournalEntryCrData['create_time'] = date('Y-m-d H:i:s');
                $JournalEntry = Yii::app()->db->createCommand()->insert('journal_entry', $JournalEntryCrData);

                //===========================for Debit COA======================//
                $customer = Customers::model()->customerAllInfo($customer_ids);
                $JournalEntryDbData['bill_no'] = $thisSellNo;
                $JournalEntryDbData['narration'] = $narration;
                $JournalEntryDbData['entry_date'] = $approveDate;
                $JournalEntryDbData['ca_id'] = 20;
                $JournalEntryDbData['transaction_type'] = 3;
                $JournalEntryDbData['amount'] = $gTotal;
                $JournalEntryDbData['main_ca'] = ChartOfAc::model()->findMainCAofThis(20);
                $JournalEntryDbData['voucher_type'] = 7;
                $JournalEntryDbData['is_deletable'] = JournalEntry::NOT_DELETABLE;
                $JournalEntryDbData['entry_no'] = $entry_no;
                $JournalEntryDbData['create_by'] = $user_id;
                $JournalEntryDbData['create_time'] = date('Y-m-d H:i:s');
                $JournalEntry = Yii::app()->db->createCommand()->insert('journal_entry', $JournalEntryDbData);
*/
                echo 'Approved';
            } else if ($countSellItem == 1)
                echo 'Processing';
            else
                echo 'Pending';
        }
        /*if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
    }

    public function actionSalesReport()
    {
        $model = new SellDetails();
        $this->render('sales_report', array(
            'model' => $model
        ));
    }

    /*
    public function actionSalesReportView() {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        $id = "";
        if(!empty($id = $_POST['model_id'])){
            $concat.= "AND model_id='$id' ";
            $sql = "SELECT date FROM common WHERE date BETWEEN '$start_date' AND '$end_date' ".$concat." AND (type=2 OR type=3 OR type=5) GROUP BY date ORDER BY date ASC";
        }else{
            $sql = "SELECT model_id FROM common WHERE date BETWEEN '$start_date' AND '$end_date' AND (type=2 OR type=3 OR type=5) GROUP BY model_id ORDER BY date ASC";
        }
        $data= Yii::app()->db->createCommand($sql)->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('sales_report_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }
    */
    public function actionSalesReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $prod_id = $_POST['model_id'];
        $condition = "item_id = 1";
        if ($prod_id > 0) {
            $condition .= " && id = $prod_id";
        }
        $prodModels = ProdModels::model()->findAll(array("condition" => $condition));

        echo CJSON::encode(array(
            'content' => $this->renderPartial('sales_report_view', array(
                'prodModels' => $prodModels,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prod_id' => $prod_id,
            ), true, true),
        ));
    }

    public function actionDueReport()
    {
        $model = new SellItems;
        $this->pageTitle = 'DUE REPORT';
        $this->render('dueReport', array('model' => $model));
    }

    public function actionDueReportView()
    {
        $to = $_POST['SellItems']['dateTo'];
        $customer_id = trim($_POST['SellItems']['customer_id']);
        $data = '';
        $condition = 'full_paid = 1';
        if ($to != "") {
            $to = date('Y-m-d H:i:s', strtotime($to) + 86399);

            $message = "Due Report From Up To " . $to;
            $condition .= ' AND date_of_sell <= "' . $to . '"';

            if ($customer_id != '') {
                $condition .= ' AND customer_id = ' . $customer_id;
            }

            $criteria = new CDbCriteria;
            $criteria->condition = $condition;
            $criteria->select = 'customer_id,so_no,date_of_sell';
            $criteria->group = 'so_no';
            $criteria->order = 'so_no DESC';
            $data = SellItems::model()->findAll($criteria);
        } else {
            $message = "<div class='flash-error'>Please select date range!</div>";
        }
        echo $this->renderPartial('dueReportView', array(
            'data1' => $data,
            'to' => $to,
            'message' => $message
        ), true, true);
        Yii::app()->end();
    }

    public function actionCustomerOfThisDepot()
    {
        $depotId = $_POST['SellItems']['depot_id'];
        if ($depotId != "") {
            $condition = "depot_id=" . $depotId;
            $data = Customers::model()->findAll(array('condition' => $condition,), 'id');
            if ($data) {
                echo CHtml::tag('option', array('value' => ""), "select", true);
                foreach ($data as $d) {
                    echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->company_name), true);
                }
            } else {
                echo CHtml::tag('option', array('value' => ""), "select", true);
            }
        } else {
            $data = Customers::model()->findAll();
            echo CHtml::tag('option', array('value' => ""), "select", true);
            foreach ($data as $d) {
                echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->company_name), true);
            }
        }
    }

    public function actionAdminMoneyReceipt()
    {
        $model = new SellItems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SellItems']))
            $model->attributes = $_GET['SellItems'];

        $this->render('adminMoneyReceipt', array(
            'model' => $model,
        ));
    }

    public function actionSellReport()
    {
        $model = new SellItems;
        $this->render('sellReport', array('model' => $model));
    }

    public function actionSellReportView()
    {
        $from = $_POST['SellItems']['dateFrom'];
        $to = $_POST['SellItems']['dateTo'];
        $storeId = $_POST['SellItems']['storeId'];
        $itemId = $_POST['SellItems']['item_id'];
        $brandId = $_POST['SellItems']['brand_id'];
        $modelId = $_POST['SellItems']['model_id'];
        $companyId = $_POST['SellItems']['company_id'];
        $customerId = $_POST['SellItems']['customer_id'];
        $message = "";

        if ($from != "" && $to != "") {
            $message = "Daily Sales Report From " . $from . " To " . $to . " (<font color='red'>Canceled</font> orders are not showing) ";
            $condition = 'date_of_sell BETWEEN "' . $from . '" AND "' . $to . '" AND order_status=' . SellItems::ESTABLISHED;
            $condition2 = 'date_of_sell BETWEEN "' . $from . '" AND "' . $to . '" AND order_status=' . SellItems::ESTABLISHED;
            if ($itemId != '') {
                $condition .= ' AND item_id = ' . $itemId;
                $condition2 .= ' AND prod_models.item_id = ' . $itemId;
                $message .= ", Category: " . CHtml::encode(ProdItems::model()->itemName($itemId));
            }
            if ($brandId != '') {
                $condition .= ' AND brand_id = ' . $brandId;
                $condition2 .= ' AND prod_models.brand_id = ' . $brandId;
                $message .= ", Sub-Category: " . CHtml::encode(ProdBrands::model()->brandName($brandId));
            }
            if ($modelId != '') {
                $condition .= ' AND model_id = ' . $modelId;
                $condition2 .= ' AND model_id = ' . $modelId;
                $message .= ", Item: " . CHtml::encode(ProdModels::model()->modelName($modelId));
            }
            if ($companyId != '') {
                $condition .= ' AND company_id = ' . $companyId;
                $condition2 .= ' AND company_id = ' . $companyId;
                $message .= ", Company: " . CHtml::encode(Lookup::item("company_name", $companyId));
            }
            if ($customerId != '') {
                $condition .= ' AND customer_id = ' . $customerId;
                $condition2 .= ' AND customer_id = ' . $customerId;
                $message .= ", Customer: " . CHtml::encode(Customers::model()->customerName($customerId));
            }
            if ($storeId != "")
                $message .= ", Store: " . CHtml::encode(Stores::model()->storeName($storeId));
            $condition .= " GROUP BY so_no ORDER BY id DESC";
            $data = SellItems::model()->findAll(array("condition" => $condition,), "id");
        } else {
            echo "<div class='flash-error'>Please select date range!</div>";
        }

        echo $this->renderPartial('sellReportView', array(
            'storeId' => $storeId,
            'data1' => $data,
            'condition2' => $condition2,
            'message' => $message
        ), true, true);
        Yii::app()->end();
    }

    public function actionSoReportOfThis()
    {
        $so = $_POST['so'];
        $models = SellItems::model()->findByAttributes(array('so_no' => $so));
        if ($models) {
            $condition = 'sales_id="' . $models->id . '"';
            $data = SellDetails::model()->findAll(array('condition' => $condition,), 'id');
            //Yii::app()->clientscript->scriptMap['jquery.js'] = false;
            echo $this->renderPartial('soReport', array('data' => $data, 'sellItemModel' => $models), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select sales order no!</div>';
        }
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

    // Sales create form
    public function actionCreate()
    {
        $model = new SellItems();
        $model->unsetAttributes();  // clear any default values

        $registration_nature = "";
        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
        if ($yourCompanyInfo)
            $registration_nature = $yourCompanyInfo->registration_nature;

        if (isset($_GET['SellItems']))
            $model->attributes = $_GET['SellItems'];
        $this->render('_form', array(
            'model' => $model,
            'reg_nature' => $registration_nature,
        ));
    }

    // sales create action
    public function actionSaveSales()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $thisPurchaseNo = VoucherNoFormate::model()->maxValOfThis('SellItems', 'so_no', 'maxSONo');
        $so_no = $thisPurchaseNo;

        $todayYear = date('Y');
        $max_sl_no = VoucherNoFormate::model()->maxValOfThisWithDateCondition("SellItems", 'so_no', 'mushok_no', 'year', $todayYear);
        $mushok_no = str_pad($max_sl_no, 5, "0", STR_PAD_LEFT) . "/" . $todayYear;

        $modelSalesItem = new SellItems();
        $modelSalesItem->so_no = $so_no;
        $modelSalesItem->mushok_no = $mushok_no;
        $modelSalesItem->year = $todayYear;
        $modelSalesItem->product_type = $_POST['SellItems']['product_type'];
        $modelSalesItem->po_no = $_POST['SellItems']['po_no'];
        $modelSalesItem->sales_type = $_POST['SellItems']['sales_type'];
        $modelSalesItem->export_type = isset($_POST['SellItems']['export_type']) ? $_POST['SellItems']['export_type'] : NULL;
        $modelSalesItem->payment_method = $_POST['SellItems']['payment_method'];
        $modelSalesItem->payment_method = $_POST['SellItems']['payment_method'];
        if ($modelSalesItem->payment_method == 134)
            $modelSalesItem->bank_id = isset($_POST['SellItems']['bank_id']) ? $_POST['SellItems']['bank_id'] : null;

        $modelSalesItem->customer_id = $_POST['SellItems']['customer_id'];
        $modelSalesItem->delivery_address = $_POST['SellItems']['delivery_address'];
        $modelSalesItem->vehicle_type = $_POST['SellItems']['vehicle_type'];
        $modelSalesItem->vehicle_no = $_POST['SellItems']['vehicle_no'];

        $modelSalesItem->sales_date = $sales_date = date('Y-m-d', strtotime($_POST['SellItems']['sales_date']));
        $modelSalesItem->sales_by_id = Yii::app()->user->getId();
        $modelSalesItem->total_sd = $_POST['SellItems']['total_sd'];
        $modelSalesItem->total_tax = $_POST['SellItems']['total_tax'];
        $modelSalesItem->total = $_POST['SellItems']['total'];
        $modelSalesItem->total_vds = $vds = isset($_POST['SellItems']['total_vds']) ? $_POST['SellItems']['total_vds'] : null;
        $modelSalesItem->grand_total = $_POST['SellItems']['grand_total'];

        $modelSalesItem->challan_no = $_POST['SellItems']['challan_no'];
        $modelSalesItem->challan_date = !empty($_POST['SellItems']['challan_date']) ? date('Y-m-d', strtotime($_POST['SellItems']['challan_date'])) : null;
        $modelSalesItem->lc_no = $_POST['SellItems']['lc_no'];
        $modelSalesItem->lc_date = !empty($_POST['SellItems']['lc_date']) ? date('Y-m-d', strtotime($_POST['SellItems']['lc_date'])) : null;
        $modelSalesItem->ship_by = $_POST['SellItems']['ship_by'];
        $modelSalesItem->dest_country_id = $_POST['SellItems']['dest_country_id'];
        $modelSalesItem->sent_from_store_id = isset($_POST['SellItems']['sent_from_store_id']) ? $_POST['SellItems']['sent_from_store_id'] : 1;
        $modelSalesItem->approve_count = 1;
        $approve_count = $modelSalesItem->approve_count;
        if ($modelSalesItem->save()) {
            if ($vds > 0) {
                $vdsrow = new Vds();
                $vdsrow->sales_id = $modelSalesItem->id;
                $vdsrow->so_no = $modelSalesItem->so_no;
                $vdsrow->vds = $modelSalesItem->total_vds;
                $vdsrow->vds_type = Vds::SALES; // 1 = Purchase, 2 = Sales
                $vdsrow->parts_service = $modelSalesItem->parts_service;
                $vdsrow->customer_id = $modelSalesItem->customer_id;
                $vdsrow->issue_date = $modelSalesItem->sales_date;
                $vdsrow->approve_count = $approve_count;
                $vdsrow->save();
            }
            $i = 0;
            foreach ($_POST['SellItems']['temp_model_id'] as $tempItemId) {
                $modelSellDetails = new SellDetails();
                $modelSellDetails->sales_id = $modelSalesItem->id;
                $modelSellDetails->model_id = $tempItemId;
                $modelSellDetails->sales_date = $sales_date;
                $modelSellDetails->parts_service = $_POST['SellItems']['temp_parts_service'][$i];
                $modelSellDetails->sales_type = $modelSalesItem->sales_type;
                $modelSellDetails->export_type = $modelSalesItem->export_type;
                $modelSellDetails->customer_id = $modelSalesItem->customer_id;
                $modelSellDetails->stock_qty = $_POST['SellItems']['temp_stock'][$i];
                $modelSellDetails->sales_qty = $sales_qty = $_POST['SellItems']['temp_sales_qty'][$i];
                $modelSellDetails->unit_id = $_POST['SellItems']['temp_unit'][$i];
                $modelSellDetails->unit_price = $unitPrice = $_POST['SellItems']['temp_purchase_price'][$i];
                $modelSellDetails->vat_percent = $_POST['SellItems']['temp_vat_percent'][$i];
                $modelSellDetails->vat = $_POST['SellItems']['temp_vat_amount'][$i];
                $modelSellDetails->sd_percent = $_POST['SellItems']['temp_sd_percent'][$i];
                $modelSellDetails->sd = $_POST['SellItems']['temp_sd'][$i];
                $modelSellDetails->total_tax = $_POST['SellItems']['temp_tax_amount'][$i];
                $modelSellDetails->total = $_POST['SellItems']['temp_total_amount'][$i];
                $modelSellDetails->net_total = $_POST['SellItems']['temp_net_total'][$i];
                $modelSellDetails->base_vat = $_POST['SellItems']['temp_base_vat'][$i];
                $modelSellDetails->base_vat_percent = $_POST['SellItems']['temp_base_vat_percent'][$i];
                $modelSellDetails->approve_count = $approve_count;

                if ($modelSellDetails->vat_percent > 0 && $modelSellDetails->vat_percent < 15)
                    $modelSellDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                else if ($modelSellDetails->vat_percent == 15)
                    $modelSellDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                else if ($modelSellDetails->vat_percent == 0 || $modelSellDetails->vat_percent == "") {
                    if ($_POST['SellItems']['temp_vat_calculation_method'][$i] == 3)
                        $modelSellDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                    else if ($_POST['SellItems']['temp_vat_calculation_method'][$i] == 4)
                        $modelSellDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                    else
                        $modelSellDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                } else
                    $modelSellDetails->vat_calculation_method = $_POST['SellItems']['temp_vat_calculation_method'][$i];

                $modelSellDetails->save();
                
                $i++;
            }
            $condition = "sales_id = {$modelSalesItem->id}";
            $data = SellDetails::model()->findAll(array('condition' => $condition));
            $sellItemsModel = SellItems::model()->findByPk($modelSalesItem->id);
            echo CJSON::encode(array(
                'status' => 'success',
                'soReportInfo' => $this->renderPartial('soReport', array('data' => $data, 'sellItemModel' => $sellItemsModel), true, true),
            ));
            Yii::app()->end();
        }
    }

    // Sales Update Action Page
    public function actionUpdate($id)
    {
        $model = SellItems::model()->findByPk($id);
        $sellDetailsModel = SellDetails::model()->findAll(array('condition' => "sales_id=$id"));
        //$this->performAjaxValidation($model);
        if (isset($_POST['SellItems'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['SellItems'];
            $model->delivery_address = $_POST['SellItems']['delivery_address'];
            $model->bank_id = null;
            if ($model->payment_method == 134)
                $model->bank_id = isset($_POST['SellItems']['bank_id']) ? $_POST['SellItems']['bank_id'] : null;
            $grandTotal = $_POST['SellItems']['grand_total'];
            //if ($grandTotal != 0) {
            $valid = $model->validate();
            if ($valid) {
                //$model->product_type = $_POST['SellItems']['product_type'];
                $model->po_no = $_POST['SellItems']['po_no'];
                $model->sales_type = $_POST['SellItems']['sales_type'];
                $model->export_type = isset($_POST['SellItems']['export_type']) ? $_POST['SellItems']['export_type'] : NULL;
                $model->customer_id = $_POST['SellItems']['customer_id'];
                $model->sales_date = $sales_date = date('Y-m-d', strtotime($_POST['SellItems']['sales_date']));
                $model->sales_by_id = Yii::app()->user->getId();
                $model->total_sd = $_POST['SellItems']['total_sd'];
                $model->total_tax = $_POST['SellItems']['total_tax'];
                $model->total = $_POST['SellItems']['total'];
                $model->total_vds = $vds = isset($_POST['SellItems']['total_vds']) ? $_POST['SellItems']['total_vds'] : null;
                $model->grand_total = $_POST['SellItems']['grand_total'];
                $model->sent_from_store_id = isset($_POST['SellItems']['sent_from_store_id']) ? $_POST['SellItems']['sent_from_store_id'] : 1;
                $approve_count = $model->approve_count;
                if ($model->save()) {
                    if ($vds > 0) {
                        $vdsrow = Vds::model()->findByAttributes(['sales_id' => $model->id]);
                        if (!$vdsrow) {
                            $vdsrow = new Vds();
                            $vdsrow->sales_id = $model->id;
                            $vdsrow->so_no = $model->so_no;
                        }
                        $vdsrow->vds = $model->total_vds;
                        $vdsrow->vds_type = Vds::SALES; // 1 = Purchase, 2 = Sales
                        $vdsrow->parts_service = $model->parts_service;
                        $vdsrow->customer_id = $model->customer_id;
                        $vdsrow->issue_date = $model->sales_date;
                        $vdsrow->approve_count = $approve_count;
                        $vdsrow->save();
                    } else {
                        Vds::model()->deleteAllByAttributes(['sales_id' => $model->id]);
                    }
                    $i = 0;
                    // delete sales details
                    SellDetails::model()->deleteAllByAttributes(['sales_id' => $model->id]);

                    foreach ($_POST['SellItems']['temp_model_id'] as $tempItemId) {
                        $modelSellDetails = new SellDetails();
                        $modelSellDetails->sales_id = $model->id;
                        $modelSellDetails->model_id = $tempItemId;
                        $modelSellDetails->sales_date = $sales_date;
                        $modelSellDetails->parts_service = $_POST['SellItems']['temp_parts_service'][$i];
                        $modelSellDetails->sales_type = $model->sales_type;
                        $modelSellDetails->export_type = $model->export_type;
                        $modelSellDetails->customer_id = $model->customer_id;
                        $modelSellDetails->stock_qty = $_POST['SellItems']['temp_stock'][$i];
                        $modelSellDetails->sales_qty = $sales_qty = $_POST['SellItems']['temp_sales_qty'][$i];
                        $modelSellDetails->unit_id = $_POST['SellItems']['temp_unit'][$i];
                        $modelSellDetails->unit_price = $unitPrice = $_POST['SellItems']['temp_purchase_price'][$i];
                        $modelSellDetails->vat_percent = $_POST['SellItems']['temp_vat_percent'][$i];
                        $modelSellDetails->vat = $_POST['SellItems']['temp_vat_amount'][$i];
                        $modelSellDetails->sd_percent = $_POST['SellItems']['temp_sd_percent'][$i];
                        $modelSellDetails->sd = $_POST['SellItems']['temp_sd'][$i];
                        $modelSellDetails->total_tax = $_POST['SellItems']['temp_tax_amount'][$i];
                        $modelSellDetails->total = $_POST['SellItems']['temp_total_amount'][$i];
                        $modelSellDetails->net_total = $_POST['SellItems']['temp_net_total'][$i];
                        $modelSellDetails->base_vat = $_POST['SellItems']['temp_base_vat'][$i];
                        $modelSellDetails->base_vat_percent = $_POST['SellItems']['temp_base_vat_percent'][$i];
                        $modelSellDetails->approve_count = $approve_count;

                        if ($modelSellDetails->vat_percent > 0 && $modelSellDetails->vat_percent < 15)
                            $modelSellDetails->vat_calculation_method = PurchaseDetails::REDUCING; // 2 = Reducing
                        else if ($modelSellDetails->vat_percent == 15)
                            $modelSellDetails->vat_calculation_method = PurchaseDetails::STANDARD; // 1=Std
                        else if ($modelSellDetails->vat_percent == 0 || $modelSellDetails->vat_percent == "") {
                            if ($_POST['SellItems']['temp_vat_calculation_method'][$i] == 3)
                                $modelSellDetails->vat_calculation_method = PurchaseDetails::FIXED; //3= Fixed
                            else if ($_POST['SellItems']['temp_vat_calculation_method'][$i] == 4)
                                $modelSellDetails->vat_calculation_method = PurchaseDetails::EXEMPTED; //4= Exempted/ Non
                            else
                                $modelSellDetails->vat_calculation_method = PurchaseDetails::ZERO_RATE; //5=0 Rate
                        } else
                            $modelSellDetails->vat_calculation_method = $_POST['SellItems']['temp_vat_calculation_method'][$i];

                        $modelSellDetails->save();
                            
                        $i++;
                    }

                    $condition = "sales_id = {$model->id}";
                    $data = SellDetails::model()->findAll(array('condition' => $condition));
                    $sellItemsModel = SellItems::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'soReportInfo' => $this->renderPartial('soReport', array('data' => $data, 'sellItemModel' => $sellItemsModel), true, true),
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
            /*} else {
                echo CJSON::encode(array(
                    'status' => 'errorBalance',
                ));
                Yii::app()->end();
            }*/
        } else {
            $this->render('_updateform', array(
                'model' => $model,
                'sellDetailsModel' => $sellDetailsModel,
            ));
        }
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
            $isTimeExist = UpdateDeleteTime::model()->existingTimeForThisOperation($model->date_of_sell, UpdateDeleteTime::SALE_ORDER, UpdateDeleteTime::DELETE_OP);
            if ($isTimeExist != "") {
                $model->delete();

                $criteria = new CDbCriteria;
                $criteria->condition = "model_id= '$model->model_id'";
                $criteria->order = "id DESC";
                $criteria->limit = "1";
                $inventoryData = Inventory::model()->find($criteria);
                if ($inventoryData) {
                    $modelInventory = new Inventory;
                    $modelInventory->store_id = $inventoryData->store_id;
                    $modelInventory->item_id = $inventoryData->item_id;
                    $modelInventory->brand_id = $inventoryData->brand_id;
                    $modelInventory->unit_id = $inventoryData->unit_id;
                    $modelInventory->model_id = $model->model_id;
                    $modelInventory->code = $inventoryData->code;
                    $modelInventory->stock_in = $model->sell_qty;
                    $modelInventory->stock_out = 0;
                    $modelInventory->sell_price = 0;
                    $modelInventory->transaction_date = date('Y-m-d');
                    $modelInventory->month = date('m');
                    $modelInventory->year = date('Y');
                    $modelInventory->save();
                }
                SellDelivery::model()->deleteAllByAttributes(array('sell_items_id' => $id));
                SoStsChngHistory::model()->deleteAllByAttributes(array('so_no' => $model->so_no));
                MoneyReceipt::model()->deleteAllByAttributes(array('order_no' => $model->so_no));

                $modelSellItem = SellItems::model()->findAll(array("condition" => "so_no = $model->so_no"));
                if ($modelSellItem) {
                    foreach ($modelSellItem as $item) {
                        SellItems::model()->updateByPk($item->id, array('full_paid' => 1));
                    }
                }
            } else {
                echo "<div class='flash-error'>Delete time exceeds!</div>"; //for ajax
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    // Add credit note search (Sales Return)
    public function actionSalesReturn()
    {
        $model = new SellItems;

        if (isset($_POST['SellItems'])) {
            $id = $_POST['SellItems']['id'];
            if (!empty($id) && $model = SellItems::model()->findByPk($id)) {
                echo $id;
            } else {
                echo "<div class='flash-error'>Please select sales order no!</div>";
            }
        } else {
            $this->render('sales_return', array(
                'model' => $model,
            ));
        }
    }

    // Add credit note form (Sales Return)
    public function actionSalesReturnForm($id)
    {
        $model = SellItems::model()->findByPk($id);
        $sellDetailsModel = SellDetails::model()->findAll(array('condition' => "sales_id=$id"));
        $this->performAjaxValidation($model);
        if (isset($_POST['SellItems'])) {
            $i = 0;
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $data = [];
            $max_sl = VoucherNoFormate::model()->maxValOfThis('SalesReturn', 'sl_no', 'sl_no');
            foreach ($_POST['SellItems']['temp_model_id'] as $tempItemId) {

                $adjustment_qty = $_POST['temp_adjustment_qty'][$i];
                if ($adjustment_qty > 0) {
                    $sellDetails = SellDetails::model()->findByAttributes(array('sales_id' => $id, 'model_id' => $tempItemId));
                    $sellReturn = new SalesReturn();
                    $sellReturn->sales_id = $model->id;
                    $sellReturn->sales_details_id = !empty($sellDetails->id) ? $sellDetails->id : '';
                    $sellReturn->so_no = $model->so_no;
                    $sellReturn->sl_no = $max_sl;
                    $sellReturn->model_id = $tempItemId;
                    $sellReturn->parts_service = $_POST['SellItems']['temp_parts_service'][$i];
                    $sellReturn->unit_id = !empty($sellDetails->unit_id) ? $sellDetails->unit_id : '';
                    $sellReturn->qty = $adjustment_qty;
                    $sellReturn->unit_price = !empty($sellDetails->unit_price) ? $sellDetails->unit_price : '';
                    $sellReturn->sd_new = $_POST['SellItems']['temp_sd_new'][$i];
                    $sellReturn->vat_new = $_POST['SellItems']['temp_vat_amount_new'][$i];
                    $sellReturn->tax_new = $_POST['SellItems']['temp_tax_amount_new'][$i];
                    $sellReturn->total_new = $_POST['SellItems']['temp_total_amount_new'][$i];
                    $sellReturn->net_total_new = $_POST['SellItems']['netTotal_new'][$i];
                    $sellReturn->return_user_id = Yii::app()->user->getId();
                    $sellReturn->return_challan_no = $_POST['SellItems']['return_challan_no'];
                    $sellReturn->return_date = date('Y-m-d', strtotime($_POST['SellItems']['return_date']));
                    $sellReturn->reason = $_POST['SellItems']['reason'];

                    if ($sellReturn->save()) {

                        $common = new Common();
                        $common->sales_id = $sellReturn->sales_id;
                        $common->date = $sellReturn->return_date;
                        $common->type = Common::CREDIT; // 5= Credit
                        if ($sellReturn->parts_service == 1) {
                            $common->model_id = $sellReturn->model_id;
                        } else {
                            $common->service_id = $sellReturn->model_id;
                        }
                        $common->parts_service = $sellReturn->parts_service;
                        $common->unit_id = $sellReturn->unit_id;
                        $common->sd = $sellReturn->sd_new;
                        $common->vat = $sellReturn->vat_new;
                        $common->total_tax = $sellReturn->tax_new;
                        $common->total = $sellReturn->total_new;
                        $common->net_total = $sellReturn->net_total_new;
                        $common->stock_in = $sellReturn->qty;
                        $common->price_in = $sellReturn->total_new;
                        $common->return_challan_no = $sellReturn->return_challan_no;
                        $common->reason = $sellReturn->reason;
                        $common->approve_count = 2;
                        $common->save();

                        if ($sellReturn->parts_service == 1) {
                            $modelInventory = new Inventory;
                            //$modelInventory->sell_details_id = $sellDetails->id;
                            $modelInventory->store_id = Stores::FG_STORE;
                            $modelInventory->location_id = Location::DEFAULT_LOCATION;
                            $prodModels = ProdModels::model()->findByPk($sellDetails->model_id);
                            $modelInventory->item_id = $prodModels->item_id;
                            $modelInventory->brand_id = $prodModels->brand_id;
                            $modelInventory->unit_id = $prodModels->unit_id;
                            $modelInventory->model_id = $sellDetails->model_id;
                            $modelInventory->code = $prodModels->code;
                            $modelInventory->stock_in = $adjustment_qty;
                            $modelInventory->transaction_date = $sellReturn->return_date;
                            $modelInventory->month = date('m');
                            $modelInventory->year = date('Y');
                            $modelInventory->save();
                        }
                    }
                }
                $i++;
            }
            $sql = "SELECT * FROM sales_return WHERE sl_no = '$max_sl' ORDER BY id ASC";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            $sales_id = $data[0]['sales_id'];
            $sellItemModel = SellItems::model()->findByPk($sales_id);
            echo CJSON::encode(array(
                'status' => 'success',
                'soReportInfo' => $this->renderPartial('//salesReturn/soReturnReport', array(
                    'data' => $data,
                    'sellItemModel' => $sellItemModel
                ), true, true),
            ));
            Yii::app()->end();
        } else {
            $this->render('sales_return_form', array(
                'model' => $model,
                'sellDetailsModel' => $sellDetailsModel,
            ));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new SellItems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SellItems']))
            $model->attributes = $_GET['SellItems'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminCancel()
    {
        $model = new SellItems('searchCancel');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SellItems']))
            $model->attributes = $_GET['SellItems'];

        $this->render('adminCancel', array(
            'model' => $model,
        ));
    }

    public function actionAdminUndo()
    {
        $model = new SellItems('searchUndoCancel');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SellItems']))
            $model->attributes = $_GET['SellItems'];

        $this->render('adminUndo', array(
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
        $model = SellItems::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sell-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionJquery_showMobileSearch()
    {
        $search_MobileNo = trim($_POST['search_mobileNo']);
        $results = array();
        $criteria = new CDbCriteria();
        $criteria->select = 'company_contact_no';
        $criteria->compare("company_contact_no", $search_MobileNo, true);
        $criteria->limit = 30;
        $allMobile = Customers::model()->findAll($criteria);
        if ($allMobile) {
            foreach ($allMobile as $mobileNo) {
                $label = $value = CHtml::encode($mobileNo->company_contact_no);
                $results[] = array('value' => $value, 'label' => $label);
            }
        }

        echo json_encode($results);
    }

    public function actionJqueryCustomerDetails()
    {
        $idPrefix = $_POST['idPrefix'];
        $mobileNo = $_POST['mobileNo'];
        $jsPart = '';
        if ($mobileNo != '') {
            $data = Customers::model()->findByAttributes(array('company_contact_no' => $mobileNo));
            $jsPart .= '<script type="text/javascript">
                   $(document).ready(function(){ 
                            var customerId="' . $data->id . '";
                            $("#' . $idPrefix . '_customer_ids").val(customerId);
                    });
                   </script>
                   ';
        } else {
            $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_customer_ids").val("").selected=true;
                        });
                       </script>
                       ';
        }
        echo CJSON::encode(array(
            'jsPart' => $jsPart,
        ));
    }

    public function actionOutsideMushokSixThree($id = NULL)
    {
        if (!empty($id)) {
            $data = SellItems::model()->findByPk($id);

            $this->render('mushak_six_three_outside', array(
                'data' => $data,
            ));
        } else {
            echo "<div class='flash-error'>Please select sales order no!</div>";
        }
    }

    public function actionMushokSixThreePrintFlag()
    {
        $id = $_POST['sales_id'];
        if (!empty($id)) {
            $salesInfo = SellItems::model()->findByPk($id);
            if ($salesInfo['print_status'] != 1) {
                $salesInfo->print_status = 1;
                $salesInfo->save();
                echo 1; // success;
            } else {
                echo 1; // success
            }
        } else {
            echo 404; // error
        }
    }


    // SALES ITEM REPORT
    public function actionSalesItemReport()
    {
        $model = new SellItems();
        $this->render('sales_item_report', array(
            'model' => $model
        ));
    }

    // SALES ITEM REPORT VIEW
    public function actionSalesItemReportView()
    {
        $so_no = '';
        $start_date = '';
        $end_date = '';
        $approve_type = '';
        $customer_id = '';
        $business_type_id = '';
        $sales_type = '';
        $report_type = '';

        if ($_POST['so_no'] != "") {
            $so_no = $_POST['so_no'];
        } else if ($_POST['start_date'] != "" && $_POST['end_date'] != "") {
            $start_date = date('Y-m-d', strtotime($_POST['start_date']));
            $end_date = date('Y-m-d', strtotime($_POST['end_date']));
            $approve_type = $_POST['SellItems']['approve_type'];
            $customer_id = $_POST['customer_id'];
            $business_type_id = $_POST['SellItems']['business_type_id'];
            $sales_type = $_POST['SellItems']['sales_type'];
            $report_type = $_POST['SellItems']['report_type'];
        } else {

        }
        echo CJSON::encode(array(
            'content' => $this->renderPartial('sales_item_report_view', array(
                'so_no' => $so_no,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'customer_id' => $customer_id,
                'approve_type' => $approve_type,
                'business_type_id' => $business_type_id,
                'sales_type' => $sales_type,
                'report_type' => $report_type
            ), true, true),
        ));
    }

    // SALES DETAILS REPORT
    public function actionSalesCustomReport()
    {
        $model = new SellDetails();
        $this->render('sales_custom_report', array(
            'model' => $model
        ));
    }

    // SALES DETAILS REPORT VIEW
    public function actionSalesCustomReportView()
    {

        if ($_POST['start_date'] != "" || $_POST['end_date'] != "") {
            $start_date = date('Y-m-d', strtotime($_POST['start_date']));
            $end_date = date('Y-m-d', strtotime($_POST['end_date']));
            echo CJSON::encode(array(
                'content' => $this->renderPartial('sales_custom_report_view', array(
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'model_id' => $_POST['model_id'],
                    'parts_service' => $_POST['SellDetails']['parts_service'],
                    'approve_type' => $_POST['SellDetails']['approve_type'],
                    'business_type_id' => $_POST['SellDetails']['business_type_id'],
                    'vat_calculation_method' => $_POST['SellDetails']['vat_calculation_method'],
                    'sales_type' => $_POST['SellDetails']['sales_type'],
                    'report_type' => $_POST['SellDetails']['report_type']
                ), true, true),
            ));
        } else {
            echo CJSON::encode(array(
                'content' => $this->renderPartial('sales_custom_report_view'),
            ));
        }
    }

    // ============    Product Wise Sales Report =============== start  //
    public function actionproductWiseSales()
    {
        $model = new SellDetails();
        $this->render('product_wise_sales', array(
            'model' => $model
        ));
    }
    public function actionproductWiseSalesView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $prod_id = $_POST['model_id'];
        $condition = "item_id = 1";
        if ($prod_id > 0) {
            $condition .= " && id = $prod_id";
        }
        $prodModels = ProdModels::model()->findAll(array("condition" => $condition));

        echo CJSON::encode(array(
            'content' => $this->renderPartial('product_wise_sales_view', array(
                'prodModels' => $prodModels,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prod_id' => $prod_id,
            ), true, true),
        ));
    }

    public function actionmonthlyStatement()
    {
        $model = new Common();
        $this->render('monthly_statement', array(
            'model' => $model
        ));
    }
    public function actionmonthlyStatementView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $prod_id = $_POST['model_id'];
        $condition = "item_id != 3";
        if ($prod_id > 0) {
            $condition .= " && id = $prod_id";
        }
        $prodModels = ProdModels::model()->findAll(array("condition" => $condition));

        echo CJSON::encode(array(
            'content' => $this->renderPartial('monthly_statement_view', array(
                'prodModels' => $prodModels,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prod_id' => $prod_id,
            ), true, true),
        ));
    }
    public function actionmonthlyStatementFg()
    {
        $model = new Common();
        $this->render('monthly_statement_fg', array(
            'model' => $model
        ));
    }
    public function actionmonthlyStatementFgView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $prod_id = $_POST['model_id'];
        $condition = "item_id = 1";
        if ($prod_id > 0) {
            $condition .= " && id = $prod_id";
        }
        $prodModels = ProdModels::model()->findAll(array("condition" => $condition));

        echo CJSON::encode(array(
            'content' => $this->renderPartial('monthly_statement_fg_view', array(
                'prodModels' => $prodModels,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prod_id' => $prod_id,
            ), true, true),
        ));
    }


}
