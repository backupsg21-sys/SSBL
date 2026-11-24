<?php

class VdsController extends Controller {

    public $layout = '//layouts/column2';
    public $defaultAction = 'admin';

    public function filters() {
        return array(
            'rights-
            JqueryVDSPOList-
            JqueryVDSDetailsByPO', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionJqueryVDSPOList() {
        $parts_service = trim($_POST['parts_service']);
        $subVDSPOList = '';
        if ($parts_service != '') {
            $data = Vds::model()->findAll(array('condition' => "parts_service= '$parts_service' AND po_no IS NOT NULL AND approve_count=2 ", 'order'=>'po_no ASC'));
            if ($data) {
                $subVDSPOList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVDSPOList .= CHtml::tag('option', array('value' => $d->sl_no_po), CHtml::encode('PO-'.$d->po_no), true);
                }
            }
        }
        echo CJSON::encode(array(
            'subVDSPOList' => $subVDSPOList,
        ));
    }

    public function actionJqueryVDSDetailsByPO(){
        $sl_no_po = trim($_POST['sl_no_po']);
        $supplierName = "";
        $vds_total = 0;
        $vds_paid = 0;
        $vds_remaining = 0;
        if($sl_no_po){
            $vdsInfo = Vds::model()->findByAttributes(array('sl_no_po' => $sl_no_po));
            if($vdsInfo)
                $poInfo = PurchaseItems::model()->findByPk($vdsInfo['purchase_id']);

            if ($vdsInfo) {

                $vds_total = $vdsInfo->vds;
                $supplierId = $vdsInfo->supplier_id;
                $vds_id = $vdsInfo->id;
                $purchase_id = $vdsInfo->purchase_id;

                if($supplierId)
                    $supplierName = Suppliers::model()->supplierName($supplierId);

                $sqlVdsPaid = "SELECT SUM(amount) AS subamount FROM vds_payment WHERE vds_id = $vdsInfo->id";
                $sqlVdsPaid= Yii::app()->db->createCommand($sqlVdsPaid)->queryRow();
                if($sqlVdsPaid)
                    $vds_paid = $sqlVdsPaid['subamount'];

                $vds_remaining = $vds_total - $vds_paid;

                $results = array(
                    'supplierId' => $supplierId,
                    'supplierName' => $supplierName,
                    'vds_total' => $vds_total,
                    'vds_remaining' => $vds_remaining,
                    'vds_id' => $vds_id,
                    'challan_no' => $poInfo->ref_no,
                    'challan_date' => date('Y-m-d', strtotime($poInfo->chalan_date)),
                    'purchaseId' => $purchase_id
                );
            } else {
                $results = array('value' => "0", 'label' => "----No Data Available----");
            }
            echo json_encode($results);
        }
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new Vds;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Vds'])) {
            $model->attributes = $_POST['Vds'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }


    // VDS Receive Action (MENU-> Sales VDS Certificate Entry)
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Vds'])) {
            $vds_payment_model = new VdsPayment();
            $vds_payment_model->attributes = $_POST['Vds'];
            $vds_payment_model->mushok_no = $_POST['Vds']['challan_no'];
            $vds_payment_model->vds_id = $model->id;
            $vds_payment_model->sales_id = $sales_id = $model->sales_id;
            $vds_payment_model->customer_id = $model->customer_id;
            $vds_payment_model->vds_type = 2; // 2 = Sales;
            $vds_payment_model->sl_no = VoucherNoFormate::model()->maxValOfThis('VdsPayment', 'sl_no', 'maxSlNo');
            if($vds_payment_model->save()){
                SellItems::model()->updateAll(array('approve_count'=>2),
                    "id= $sales_id "
                );
                echo CJSON::encode(array(
                    'status' => 'success',
                    'content' => '<div class="flash-notice">successfully updated</div>',
                ));
                exit;
            }

        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            $vds_total = 0;
            $vds_remaining = 0;
            $vds_paid = 0;
            if($model->vds > 0)
                $vds_total = $model->vds;
            $sqlVdsPaid = "SELECT SUM(amount) AS subamount FROM vds_payment WHERE vds_id = $model->id";
            $sqlVdsPaid= Yii::app()->db->createCommand($sqlVdsPaid)->queryRow();
            if($sqlVdsPaid)
                $vds_paid = $sqlVdsPaid['subamount'];

            $vds_remaining = $vds_total - $vds_paid;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('vdsReceive', array(
                    'model' => $model,
                    'vds_remaining' => $vds_remaining
                    ), true, true),
            ));
            exit;
        } else
            $this->render('update', array('model' => $model));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Vds('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Vds']))
            $model->attributes = $_GET['Vds'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Vds::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'vds-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAdminPurchaseVds() {
        $model = new Vds('searchAdminPurchaseVds');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Vds']))
            $model->attributes = $_GET['Vds'];

        $this->render('adminPurchaseVds', array(
            'model' => $model,
        ));
    }

    public function actionAdminServiceVds() {
        $model = new Vds('searchAdminServiceVds');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Vds']))
            $model->attributes = $_GET['Vds'];

        $this->render('adminServiceVds', array(
            'model' => $model,
        ));
    }

    public function actionAdminSalesVds() {
        $model = new Vds('searchAdminSalesVds');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Vds']))
            $model->attributes = $_GET['Vds'];

        $this->render('adminSalesVds', array(
            'model' => $model,
        ));
    }

}
