<?php

class VdsPaymentController extends Controller
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

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    // VDS Payment create + certificate form (MENU-> Create VDS Certificate)
    public function actionCreate()
    {
        $model = new VdsPayment;
        //$model->unsetAttributes();
        $this->performAjaxValidation($model);
        if (isset($_POST['VdsPayment'])) {
            if (isset($_POST['temp_po_no'])) {
                $year = date('Y');
                $month = date('m');
                $vds_type = 1; //1=purchase
                $todayYear = date('Y');

                $dynamicFields = $_POST['temp_po_no'];
                foreach ($dynamicFields as $key => $value) {
                    $sl_no = VoucherNoFormate::model()->maxValOfThis('VdsPayment', 'sl_no', 'maxSlNo');
                    $max_mushok_no = VoucherNoFormate::model()->maxValOfThisWithDateCondition("VdsPayment", 'sl_no', 'maxMushokNo', 'year', $todayYear);
                    $mushok_no = str_pad($max_mushok_no, 5, "0", STR_PAD_LEFT) . "/" . $todayYear;
                    $mushok_no = $mushok_no;

                    $dyModel = new VdsPayment();
                    $dyModel->attributes = $_POST['VdsPayment'];
                    $dyModel->year = $year;
                    $dyModel->month = $month;
                    $dyModel->vds_type = $vds_type;
                    $dyModel->sl_no = $sl_no;
                    $dyModel->mushok_no = $mushok_no;
                    //$dyModel->payment_method = 133;
                    //dynamic portion
                    $dyModel->po_no = $value;
                    $dyModel->supplier_id = $_POST['temp_supplier_id'][$key];
                    $dyModel->purchase_id = $_POST['temp_purchase_id'][$key];
                    $dyModel->chalan_no = $_POST['temp_chalan_no'][$key];
                    $dyModel->challan_date = $_POST['temp_challan_date'][$key];
                    $dyModel->vds_total = $_POST['temp_vds_total'][$key];
                    $dyModel->vds_remaining = $_POST['temp_vds_remaining'][$key];
                    $dyModel->amount = $_POST['temp_amount'][$key];
                    $dyModel->vds_id = $_POST['temp_vds_id'][$key];
                    //$dyModel->tr_challan_no = $_POST['temp_vds_id'][$key];
                    //$dyModel->tr_challan_date = $_POST['temp_vds_id'][$key];

//                    $dyModel->validate();
//                    print_r($dyModel->errors);

                    if ($dyModel->save()) {
                        if (round($dyModel->vds_remaining, 3) == round($dyModel->amount, 3)) {
                            Vds::model()->updateAll(array('is_paid' => 1), "purchase_id=$dyModel->purchase_id");
                        }
                        if (isset($dyModel->tr_challan_no)) {

                            $vatPayment = new VatPayment();
                            $vatPayment->payment_month = date('Y-m', strtotime($dyModel->payment_date));
                            $vatPayment->sl_no = VoucherNoFormate::model()->maxValOfThis('VatPayment', 'sl_no', 'maxSlNo');
                            $vatPayment->vds_payment_id = $dyModel->id;
                            $vatPayment->payment_date = $dyModel->payment_date;
                            $vatPayment->year = date("Y", strtotime($dyModel->payment_date));
                            $vatPayment->month = date("m", strtotime($dyModel->payment_date));
                            $vatPayment->purpose = 1; //vat = 1
                            $vatPayment->chalan_no = $dyModel->tr_challan_no;
                            $vatPayment->tr_challan_no = $dyModel->tr_challan_no;
                            $vatPayment->tr_challan_date = $dyModel->tr_challan_date;
                            $vatPayment->amount = $dyModel->amount;
                            $vatPayment->payment_method = 134; // Bank
                            $vatPayment->bank_id = $dyModel->bank_id;
                            $vatPayment->note = $_POST['VdsPayment']['purpose'];
                            $vatPayment->district_id = $_POST['VdsPayment']['district_id'];
                            $vatPayment->branch = $dyModel->branch;
                            $vatPayment->payment_code_id = 3;
                            $vatPayment->account_code_id = $dyModel->account_code_id;
                            $vatPayment->cheque_date = $_POST['VdsPayment']['cheque_date'];
                            $vatPayment->cheque_number = $_POST['VdsPayment']['cheque_number'];
                            $vatPayment->validate();
                            //print_r($vatPayment->errors);
                            $vatPayment->save();

                        }
                    }
                }
                $criteria = new CDbCriteria();
                $criteria->condition = "sl_no=$dyModel->sl_no";
                $data = VdsPayment::model()->findAll($criteria);
                echo CJSON::encode(array(
                    'status' => 'success',
                    'soReportInfo' => $this->renderPartial('soReport', array('data' => $data), true, true),
                ));
                Yii::app()->end();
            }
        } else {
            $this->render('_form', array(
                'model' => $model,
            ));
        }
    }

    /*
    // VDS Payment create + certificate form (MENU-> Create VDS Certificate)
    public function actionCreate() {
        $model = new VdsPayment;
        //$model->unsetAttributes();
        $this->performAjaxValidation($model);
        if (isset($_POST['VdsPayment'])) {
            $model->attributes = $_POST['VdsPayment'];
            $model->year = date('Y');
            $model->month = date('m');
            $model->vds_type = 1; //1=purchase
            $model->sl_no = VoucherNoFormate::model()->maxValOfThis('VdsPayment', 'sl_no', 'maxSlNo');

            $todayYear = date('Y');
            $max_mushok_no = VoucherNoFormate::model()->maxValOfThisWithDateCondition("VdsPayment", 'sl_no', 'maxMushokNo', 'year', $todayYear);
            $mushok_no = str_pad($max_mushok_no, 5, "0", STR_PAD_LEFT) . "/" . $todayYear;
            $model->mushok_no = $mushok_no;

            if ($model->save()) {

                if (round($_POST['VdsPayment']['vds_remaining'], 3) == round($_POST['VdsPayment']['amount'], 3)) {
                    Vds::model()->updateAll(array('is_paid' => 1), "purchase_id=$model->purchase_id"
                    );
                }
                //PurchaseItems::model()->updateAll(array('approve_count' => 2), "id=$model->purchase_id");

                $vatPayment = new VatPayment();
                $vatPayment->sl_no = VoucherNoFormate::model()->maxValOfThis('VatPayment', 'sl_no', 'maxSlNo');

                if(isset($model->tr_challan_no) && $model->tr_challan_no > 0){
                    $vatPayment->vds_payment_id = $model->id;
					$vatPayment->tr_challan_no = $model->tr_challan_no;
					$vatPayment->tr_challan_date = $model->tr_challan_date;
                    $vatPayment->payment_date = $model->tr_challan_date;
                    $vatPayment->year = date("Y", strtotime($model->tr_challan_date));
                    $vatPayment->month = date("m", strtotime($model->tr_challan_date));
                    $vatPayment->purpose = 1; //vat = 1
                    $vatPayment->chalan_no = $model->tr_challan_no;
                    $vatPayment->amount = $model->amount;
                    $vatPayment->payment_method = 134; // Bank
                     $vatPayment->bank_id = $model->bank_id;
                    $vatPayment->note = $_POST['VdsPayment']['purpose'];
                    $vatPayment->district_id = $_POST['VdsPayment']['district_id'];
                    $vatPayment->branch = $model->branch;
                    $vatPayment->payment_code_id = 3;
                    $vatPayment->account_code_id = $model->account_code_id;
                    $vatPayment->cheque_date = $_POST['VdsPayment']['cheque_date'];
                    $vatPayment->cheque_number = $_POST['VdsPayment']['cheque_number'];
                    //$vatPayment->validate();
                    $vatPayment->save();
                }

                $data = VdsPayment::model()->findByAttributes(array('id' => $model->id));
                echo CJSON::encode(array(
                    'status' => 'success',
                    'soReportInfo' => $this->renderPartial('soReport', array('data' => $data), true, true),
                ));
                Yii::app()->end();
            }
        } else {
            $this->render('_form', array(
                'model' => $model,
            ));
        }
    }
     * 
     */

    public function actionAdmin()
    {
        $model = new VdsPayment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['VdsPayment']))
            $model->attributes = $_GET['VdsPayment'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    // VDS Payment Action (MENU-> Manage VDS Certificate)
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['VdsPayment'])) {
            $model->attributes = $_POST['VdsPayment'];
            if ($model->save()) {
                $vatPayment = VatPayment::model()->findByAttributes(array('vds_payment_id' => $model->id));

                if (!$vatPayment) {
                    $vatPayment = new VatPayment();
                    $vatPayment->sl_no = VoucherNoFormate::model()->maxValOfThis('VatPayment', 'sl_no', 'maxSlNo');
                }
                $vatPayment->vds_payment_id = $model->id;
                $vatPayment->tr_challan_no = $model->tr_challan_no;
                $vatPayment->tr_challan_date = $model->tr_challan_date;
                $vatPayment->payment_date = $model->payment_date;
                $vatPayment->year = date("Y", strtotime($model->payment_date));
                $vatPayment->month = date("m", strtotime($model->payment_date));
                $vatPayment->payment_month = date('Y-m', strtotime($model->payment_date));
                $vatPayment->purpose = 1; //vat = 1
                $vatPayment->chalan_no = $model->tr_challan_no;
                $vatPayment->amount = $model->amount;
                $vatPayment->payment_method = 134; // Bank
                $vatPayment->bank_id = $model->bank_id;
                $vatPayment->validate();
                $vatPayment->save();
                //var_dump($vatPayment->getErrors());
            }
            echo CJSON::encode(array(
                'status' => 'success',
                'content' => '<div class="flash-notice">Successfully updated</div>',
            ));
            exit;

        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form2', array(
                    'model' => $model,
                ), true, true),
            ));
            exit;
        } else
            $this->render('update', array('model' => $model));
    }

    // VDS Receive Action (MENU-> Sales VDS certificate Manage)
    public function actionVdsReceiveMoney($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['VdsPayment'])) {
            $model->attributes = $_POST['VdsPayment'];
            $model->save();
            echo CJSON::encode(array(
                'status' => 'success',
                'content' => '<div class="flash-notice">Successfully updated</div>',
            ));
            exit;

        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form3', array(
                    'model' => $model,
                ), true, true),
            ));
            exit;
        } else
            $this->render('update', array('model' => $model));
    }

    // Open VDS Certificate Receive Form & Action (MENU-> Open VDS Certificate(sales) Entry)
    public function actionOpenVdsReceive()
    {
        $model = new VdsPayment;
        $model->unsetAttributes();
        //$this->performAjaxValidation($model);
        if (isset($_POST['VdsPayment'])) {
            $model->attributes = $_POST['VdsPayment'];
            $model->year = date('Y');
            $model->month = date('m');
            $model->vds_type = 2; //2=sales
            $model->sl_no = VoucherNoFormate::model()->maxValOfThis('VdsPayment', 'sl_no', 'maxSlNo');

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "VDS received successfully");
            } else {
                Yii::app()->user->setFlash('error', "VDS not received!");
            }
            $this->redirect(array('vdsPayment/openVdsReceive'));
            Yii::app()->end();
        } else {
            $this->render('vdsOpenReceive', array(
                'model' => $model,
            ));
        }
    }

    public function actionAdminReceive()
    {
        $model = new VdsPayment('searchAdminReceive');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['VdsPayment']))
            $model->attributes = $_GET['VdsPayment'];

        $this->render('adminReceive', array(
            'model' => $model,
        ));
    }

    public function actionChallanOfThis()
    {
        $challan = $_POST['challan'];
        $data = VdsPayment::model()->findByAttributes(array('chalan_no' => $challan));
        if ($data) {
            echo $this->renderPartial('soReport', array('data' => $data), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select challan no!</div>';
        }
    }

    public function actionVdsPaymentByMushok()
    {
        $mushok_no = $_POST['mushok'];
        $mushok_no = trim($mushok_no, " ");
        $data = VdsPayment::model()->findAllByAttributes(array('mushok_no' => $mushok_no));
        if ($data) {
            echo $this->renderPartial('soReport', array('data' => $data), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select mushok no!</div>';
        }
    }

    public function loadModel($id)
    {
        $model = VdsPayment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'vds-payment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
