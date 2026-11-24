<?php

class PaymentReceiptController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return '';
    }
    
    public function actionAmountOfThisOrder(){
        $orderNo = $_POST['order_no'];
        
        if($orderNo){
            $cramount= ReceivedPurchase::model()->totalReceivedQtyOfThisPo($orderNo);
            $dramount= PaymentReceipt::model()->totalPaymentReceiptOfThisOrderNo($orderNo);
        }
        echo CJSON::encode(array(
            'cramount' => $cramount,
            'dramount' => $dramount,
        ));
    }

    public function actionPaymentReceiptHistory($supplier_id) {
        $condition = "supplier_id=" . $supplier_id . " ORDER BY id DESC";
        $data = PaymentReceipt::model()->findAll(array('condition' => $condition,), 'id');

        $this->renderPartial('paymentReceiptHistory', array('data' => $data, 'supplier_id' => $supplier_id));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }
    
    public function actionAddPaymentReceipt($supplier_id) {

        $model = new PaymentReceipt;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['PaymentReceipt'])) {
            $model->attributes = $_POST['PaymentReceipt'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    // Stop jQuery from re-initialization
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    $data=$this->loadModel($model->id);
                    
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'content' => $this->renderPartial('pymReport', array('data' => $data), true, true),
                    ));
                    exit;
                }
                else
                    $this->redirect(array('admin'));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_addPaymentReceiptForm', array(
                    'model' => $model,
                    'supplierId' => $supplier_id,
                        ), true, true),
            ));
            exit;
        }
        else
            $this->redirect('admin');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->renderPartial('pymReport', array('data' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
//        $model = new PaymentReceipt;
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['PaymentReceipt'])) {
//            $model->attributes = $_POST['PaymentReceipt'];
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
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['PaymentReceipt'])) {
            $model->attributes = $_POST['PaymentReceipt'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    // Stop jQuery from re-initialization
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'content' => '<div class="flash-notice">successfully updated</div>',
                    ));
                    exit;
                }
                else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form', array(
                    'model' => $model), true, true),
            ));
            exit;
        }
        else
            $this->render('update', array('model' => $model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PaymentReceipt('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PaymentReceipt']))
            $model->attributes = $_GET['PaymentReceipt'];

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
        $model = PaymentReceipt::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'payment-receipt-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
