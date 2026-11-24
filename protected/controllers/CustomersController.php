<?php

class CustomersController extends Controller {

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
            'rights-
            Jquery_customerSearch', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return '';
    }

    public function actionPartyLedgerDateWise() {
        $model = New Customers;

        $this->render('partyLedgerDateWise', array(
            'model' => $model,
        ));
    }

    public function actionAdminMoneyReceipt() {
        $model = new Customers('Customers');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customers']))
            $model->attributes = $_GET['Customers'];

        $this->pageTitle = 'CREATE MONEY RECEIPT';
        $this->render('adminMoneyReceipt', array(
            'model' => $model,
        ));
    }

    public function actionPosCustomerData() {
        $customerPhone = $_POST['customerPhone'];
        $condition = "company_contact_no like '$customerPhone%'";
        $customeInfo = Customers::model()->findAll(array("condition" => $condition, 'order' => 'company_name ASC', 'group' => 'company_contact_no'));
        if ($customeInfo) {
            foreach ($customeInfo as $info) {
                $customerId = $info->id;
                $label = CHtml::encode($info->company_contact_no);
                $customerName = CHtml::encode($info->company_name);
                $customerAddress = CHtml::encode($info->company_address);
                $customerEmail = CHtml::encode($info->company_email);
                $results[] = array('label' => $label, 'customerName' => $customerName, 'customerId' => $customerId, 'customerAddress' => $customerAddress, 'customerEmail' => $customerEmail);
            }
        } else {
            $results[] = array('label' => ' No Contact Available', 'customerName' => '', 'customerId' => '', 'customerAddress' => '', 'customerEmail' => '');
        }
        echo json_encode($results);
    }

    public function actionPartyLedgerViewDateWise() {
        $model = New Customers;
        $customerId = $_POST['Customers']['id'];
        $startDate = $_POST['Customers']['startDate'];
        $endDate = $_POST['Customers']['endDate'];

        if ($startDate != "" && $endDate != "" && $customerId != "") {
            $customerInfo = Customers::model()->findByPk($customerId);

            echo CJSON::encode(array(
                'content' => $this->renderPartial('partyLedgerViewDateWise', array(
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'customerId' => $customerId,
                    'customerInfo' => $customerInfo,
                    'model' => $model,
                        ), true, true),
            ));

//            $this->render('partyLedgerViewDateWise', array(
//            'startDate'=>$startDate,
//                'endDate'=>$endDate,
//                'customerId' => $customerId,
//                'customerName' => $customerName,
//                'customerContactNo' => $customerContactNo,
//                'model' => $model,
//        ));
        } else {
            
        }
    }

    public function actionAvailableBalance() {
        $customerId = $_POST['customerId'];

        /*
          theory:
         * 
         * AB= Available Balance Amount
         * SO= Sells Order Amount
         * CL= Credit Limit Amount

         * (AB) = sum(MR) - sum(SO) + CL
         * 
         * if(Runnning SO amount) <= (AB)
         * 
         * Allow to create the sells order
         * 
         * otherwise stop the sells order of that client/ customer.
         * 
         */
        $ab = 0;
        if ($customerId) {
            $sMR = MoneyReceipt::model()->totalMoneyReceiptOfThisCustomer($customerId);
            $sSO = SellDelivery::model()->totalDeliveredQtyOfThisCustomer($customerId);
            $aCL = CreditLimit::model()->activeCreditLimitOfThisCustomer($customerId);

            $ab = $sMR - $sSO + $aCL;
        }


        echo CJSON::encode(array(
            'ab' => $ab,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Customers;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Customers'])) {
            $model->attributes = $_POST['Customers'];
            $valid = $model->validate();
            if ($valid) {
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
        }else {
            $this->render('admin', array(
                'model' => $model,
            ));
        }
    }

    public function actionCreateCustomerFromOutSide() {
        $model = new Customers;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Customers'])) {
            $model->attributes = $_POST['Customers'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Customers::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Customer successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->company_name,
                    ));
                    exit;
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $resultDiv = '';
            echo CJSON::encode(array(
                'status' => 'failure',
                'resultDiv' => $resultDiv,
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        }
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

        if (isset($_POST['Customers'])) {
            $model->attributes = $_POST['Customers'];
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
        $model = new Customers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customers']))
            $model->attributes = $_GET['Customers'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminCreditLimit() {
        $model = new Customers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customers']))
            $model->attributes = $_GET['Customers'];

        $this->render('adminCreditLimit', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Customers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'customers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionJquery_customerSearch() {
        $customerName = trim($_POST['customerName']);
        $results = [];
        $criteria = new CDbCriteria();
        $criteria->compare("company_name", trim($customerName), true);
        $criteria->order = " company_name ASC ";
        $criteria->group = " company_name ASC ";
        $criteria->limit = 30;
        $info = Customers::model()->findAll($criteria);
        if ($info) {
            foreach ($info as $row) {
                $value = $row->id;
                $label = CHtml::encode($row->company_name.' (Id-'.$row->id.')');
                $bin = !empty($row->bin) && $row->bin!="0" ? $row->bin:"";
                $address = !empty($row->company_address) ? $row->company_address:"";
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'bin' => $bin,
                    'company_address' => $address
                );
            }
        } else {
            //$results[] = array('value' => "0", 'label' => "No customer required");
        }
        echo json_encode($results);
    }

    public function actionProcessFileDataFinal()
    {
        $fileExist = file_exists(Yii::app()->basePath . '/../upload/customers.xls');
        if ($fileExist) {

            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/customers.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $zero_val = 0;
            $newlyInserted = 0;
            $stockUpdated = 0;

            for ($row = 2; $row <= $highestRow; ++$row) {

                $company_name = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $mobile_no = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
                $business_type_id = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
                $nationality = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
                $company_address = trim($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
                $bin = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());

                $model = new Customers();
                $model->company_name = $company_name;
                $model->mobile_no = $mobile_no;
                $model->business_type_id = $business_type_id;
                $model->nationality = $nationality;
                $model->company_address = $company_address;
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
