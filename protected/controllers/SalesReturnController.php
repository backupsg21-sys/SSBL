<?php

class SalesReturnController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionCreditList()
    {
        $model = new SalesReturn('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SalesReturn']))
            $model->attributes = $_GET['SalesReturn'];

        $this->render('credit_note_list', array(
            'model' => $model,
        ));
    }

    public function actionCreditReport()
    {
        $model = new SellDetails();
        $this->render('sales_return_report', array(
            'model' => $model
        ));
    }

    public function actionCreditReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        $id = "";
        if (!empty($id = $_POST['model_id'])) {
            $concat .= "AND model_id='$id' ";
        }
        $sql = "SELECT * FROM common WHERE date BETWEEN '$start_date' AND '$end_date' " . $concat . " AND type=5 ORDER BY date ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('sales_return_report_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ), true, true),
        ));
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sales-return-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPreview($id)
    {

        $sql = "SELECT * FROM sales_return WHERE sl_no = $id ORDER BY id ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        $sales_id = $data[0]['sales_id'];
        $sellItemModel = SellItems::model()->findByPk($sales_id);

        $this->renderPartial('soReturnReport', array('data' => $data, 'sellItemModel' => $sellItemModel));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }
}