<?php

class SuppliersController extends Controller
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
            'rights
            -jqueryCustomerSearch', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionPartyLedgerDateWise()
    {
        $model = new Suppliers;

        $this->render('partyLedgerDateWise', array(
            'model' => $model,
        ));
    }

    public function actionPartyLedgerViewDateWise()
    {
        $model = new Suppliers;
        $supplierId = $_POST['Suppliers']['id'];
        $startDate = $_POST['Suppliers']['startDate'];
        $endDate = $_POST['Suppliers']['endDate'];

        if ($startDate != "" && $endDate != "" && $supplierId != "") {
            $supplierInfo = Suppliers::model()->findByPk($supplierId);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('partyLedgerViewDateWise', array(
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'supplierId' => $supplierId,
                    'supplierInfo' => $supplierInfo,
                    'model' => $model,
                ), true, true),
            ));

            /*$this->render('partyLedgerViewDateWise', array(
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                    'supplierId' => $supplierId,
                    'supplierName' => $supplierName,
                    'supplierContactNo' => $supplierContactNo,
                    'supplierOpeningAmount'=>$supplierOpeningAmount,
                    'model' => $model,
    ));*/
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

    public function actionCreateSupplierFromOutSide()
    {
        $model = new Suppliers;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Suppliers'])) {
            $model->attributes = $_POST['Suppliers'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Suppliers::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Supplier successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->name,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $resultDiv = '';
            echo CJSON::encode(array(
                'status' => 'failure',
                'resultDiv' => $resultDiv,
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Suppliers;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Suppliers'])) {
            $model->attributes = $_POST['Suppliers'];
            $valid = $model->validate();
            if ($valid) {
                $model->opening_amount = $_POST['Suppliers']['opening_amount'] != '' ? $_POST['Suppliers']['opening_amount'] : 0;
                $model->save();
                //do anything here

                echo CJSON::encode(array(
                    'status' => 'success',
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        } else {
            $this->render('admin', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Suppliers'])) {
            $model->attributes = $_POST['Suppliers'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    // Stop jQuery from re-initialization
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'content' => '<div class="flash-notice">successfully updated</div>',
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form2', array(
                    'model' => $model), true, true),
            ));
            exit;
        } else
            $this->render('update', array('model' => $model));
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
        $model = new Suppliers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Suppliers']))
            $model->attributes = $_GET['Suppliers'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminPaymentReceipt()
    {
        $model = new Suppliers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Suppliers']))
            $model->attributes = $_GET['Suppliers'];

        $this->render('adminPaymentReceipt', array(
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
        $model = Suppliers::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'suppliers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionJqueryCustomerSearch()
    {
        $search_customerName = trim($_POST['search_customerName']);
        $results = [];
        $criteria = new CDbCriteria();
        $criteria->compare("name", $search_customerName, true);
        $criteria->order = "name asc";
        $criteria->group = "name";
        $criteria->limit = 25;
        $prodInfos = Suppliers::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = $prodInfo->name . ' (Id-' . $prodInfo->id . ')';
                $bin = !empty($prodInfo->bin) && $prodInfo->bin != "0" ? $prodInfo->bin : "";
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'bin' => $bin
                );
            }
        } else {
            //$results[] = array('value' => "0", 'label' => "No suppliers");
        }
        echo json_encode($results);
    }


    public function actionProcessFileDataFinal()
    {
        $fileExist = file_exists(Yii::app()->basePath . '/../upload/suppliers.xls');
        if ($fileExist) {

            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/suppliers.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $zero_val = 0;
            $newlyInserted = 0;
            $stockUpdated = 0;

            for ($row = 2; $row <= $highestRow; ++$row) {

                $name = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $address = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
                $supplier_type_id = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                $business_type_id = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
                $nationality = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
                $mobile_no = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
                $bin = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());

                $model = new Suppliers();
                $model->name = $name;
                $model->address = $address;
                $model->supplier_type_id = $supplier_type_id;
                $model->business_type_id = $business_type_id;
                $model->nationality = $nationality;
                $model->mobile_no = $mobile_no;
                $model->bin = $bin;
                if($model->save(false)){
                    $newlyInserted++;
                }
                
            }

            echo "Newly Inserted : " . $newlyInserted . "<br>";
        } else {
            echo 'FILE NOT FOUND!';
        }
    }

}
