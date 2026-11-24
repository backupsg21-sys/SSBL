<?php

class VatPaymentController extends Controller
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

    public function actionSoReportOfThis()
    {
        $so = $_POST['so'];
        $models = VatPayment::model()->findByAttributes(array('so_no' => $so));
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
        $model = new VatPayment();

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['VatPayment']))
            $model->attributes = $_GET['VatPayment'];
        $this->render('_form', array(
            'model' => $model,
        ));
    }

    // sales create action
    public function actionSave()
    {
        $model = new VatPayment;
        $this->performAjaxValidation($model);
        if (isset($_POST['VatPayment'])) {
            $model->attributes = $_POST['VatPayment'];
            $model->year = date('Y');
            $model->month = date('m');
            $model->payment_month = date('Y-m', strtotime($model->payment_month));
            $model->sl_no = VoucherNoFormate::model()->maxValOfThis('VatPayment', 'sl_no', 'maxSlNo');
            if ($model->save()) {
                $data = VatPayment::model()->findByAttributes(array('id' => $model->id));
                echo CJSON::encode(array(
                    'status' => 'success',
                    'soReportInfo' => $this->renderPartial('soReport', array('data' => $data), true, true),
                ));
                Yii::app()->end();
            }
        }
    }

    public function actionChallanOfThis()
    {
        $challan = $_POST['challan'];
        $data = VatPayment::model()->findByAttributes(array('chalan_no' => $challan));
        if ($data) {
            echo $this->renderPartial('soReport', array('data' => $data), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select challan no!</div>';
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new VatPayment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['VatPayment']))
            $model->attributes = $_GET['VatPayment'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    // vat Payment Action (MENU-> Manage Vat payment)
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['VatPayment'])) {
            $model->attributes = $_POST['VatPayment'];
            $model->chalan_no = $model->tr_challan_no;
            $model->validate();
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

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = VatPayment::model()->findByPk($id);
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

}
