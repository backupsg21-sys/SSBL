<?php

class AdjustmentsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    // CREATE ADJUSTMENTS FORM
    public function actionCreate()
    {
        $model = new Adjustments;
        $this->performAjaxValidation($model);
        if (isset($_POST['Adjustments'])) {
            $model->attributes = $_POST['Adjustments'];
            $model->year = date('Y');
            $model->month = date('m');
            $model->sl_no = VoucherNoFormate::model()->maxValOfThis('Adjustments', 'sl_no', 'maxSlNo');

            if ($model->save()) {
                $data = Adjustments::model()->findByAttributes(array('id' => $model->id));
                echo CJSON::encode(array(
                    'status' => 'success',
                    'soReportInfo' => $this->renderPartial('soReport', array('data' => $data), true, true),
                ));
                Yii::app()->end();
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionChallanOfThis()
    {
        $challan = $_POST['challan'];
        $data = Adjustments::model()->findByAttributes(array('challan_no' => $challan));
        if ($data) {
            echo $this->renderPartial('soReport', array('data' => $data), true, true);
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select challan no!</div>';
        }
    }

    public function actionReport()
    {
        $model = new Adjustments();
        $this->render('adjustments_report', array(
            'model' => $model
        ));
    }

    public function actionReportView()
    {
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $concat = "";
        $id = "";
        if (!empty($nine_one_part = $_POST['nine_one_part'])) {
            $concat .= "AND nine_one_part='$nine_one_part' ";
        } else {
            $concat .= " AND (nine_one_part = 5 OR nine_one_part = 6 )";
        }
        $sql = "SELECT * FROM adjustments WHERE payment_date BETWEEN '$start_date' AND '$end_date' " . $concat . " ORDER BY payment_date ASC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        echo CJSON::encode(array(
            'content' => $this->renderPartial('adjustments_report_view', array(
                'data' => $data,
                'prod_id' => $id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'nine_one_part' => $nine_one_part,
            ), true, true),
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Adjustments'])) {
            $model->attributes = $_POST['Adjustments'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
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
    public function actionAdmin()
    {
        $model = new Adjustments('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Adjustments']))
            $model->attributes = $_GET['Adjustments'];

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
        $model = Adjustments::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'adjustments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionApprove($id)
    {
        $model = $this->loadModel($id);
        if( $model->effective_date != "" && $model->effective_date != "0000-00-00" ){
            $model->is_approved = Adjustments::APPROVED;
            $model->approved_by = Yii::app()->user->id;
            $model->approved_datetime = new CDbExpression('NOW()');
            $model->save();
        } else {
            Yii::app()->user->setFlash('error', "Effective date not set yet." );
        }

        $this->redirect(array('admin'));

    }
}
