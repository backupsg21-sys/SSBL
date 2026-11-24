<?php

class PurchaseReturnController extends Controller
{
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionDebitList()
    {
        $model = new PurchaseReturn('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PurchaseReturn']))
            $model->attributes = $_GET['PurchaseReturn'];

        $this->render('debit_note_list', array(
            'model' => $model,
        ));
    }

    public function actionDebitReport()
    {
        $model = new PurchaseDetails;
        $this->render('purchase_return_report', array(
            'model' => $model
        ));
    }

    public function actionDebitReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        $id = "";
        if (!empty($id = $_POST['model_id'])) {
            $concat .= "AND model_id='$id' ";
        }
        $sql = "SELECT * FROM common WHERE date BETWEEN '$start_date' AND '$end_date' " . $concat . " AND type=6 ORDER BY date ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('purchase_return_report_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'purchase-return-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPreview($id)
    {

        $sql = "SELECT * FROM purchase_return WHERE sl_no = $id ORDER BY id ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        $purchase_id = $data[0]['purchase_id'];
        $purchaseItemModel = PurchaseItems::model()->findByPk($purchase_id);

        $this->renderPartial('poReturnReport', array('data' => $data, 'purchaseItemModel' => $purchaseItemModel));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

}
