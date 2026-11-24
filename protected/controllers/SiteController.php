<?php

class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionAllSummary($whichPage)
    {
        $poNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::PURCHASE_ORDER);
        $soNoFormat = VoucherNoFormate::model()->numberFormatOfThis(VoucherNoFormate::SELL_ORDER);
        $today = date('Y-m-d');
        echo $this->renderPartial($whichPage, array('today' => $today, 'poNoFormat' => $poNoFormat, 'soNoFormat' => $soNoFormat), true, true);

        Yii::app()->end();
    }

    public function actionAllSummaryReset()
    {
        echo "";

        Yii::app()->end();
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {

//        $todayDate = date("Y-m-d");
//
//        if(self::lastUpdateDate() == $todayDate) {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('site/login'));
        } else {
            $this->redirect(Yii::app()->createUrl('site/dashBoard'));
        }

//        } else {
//
//            if(self::getSoftwareUpdate() == true) {
//                $this->redirect(Yii::app()->createUrl('site/index'));
//            }
//        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionProductStock()
    {
        $model = new ProdModels;
        $title = '';
        $modelId = $_POST['ProdModels']['id'];

        if ($modelId != "") {
            echo CJSON::encode(array(
                'content' => $this->renderPartial('productStockView', array(
                    'modelId' => $modelId,
                    'model' => $model,
                ), true, true),
            ));
        }
    }

    public static function getDataByAPI($path)
    {
        //echo "need update </br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $output = json_decode($output);
        // print_r($output);
        return $output;
        curl_close($ch);
    }

    public static function updateClientInfo($path)
    {
        //echo "user status updated </br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        //print_r ($response);
    }


    public static function getSoftwareUpdate()
    {

        $clientId = 2;

        $result = self::getDataByAPI("http://182.163.102.201:8086/project_management/project_management/index.php/api/clients/" . $clientId);

        if (isset($result)) {

            if (($result->client_id == NULL) || ($result->client_id != $clientId)) {
                echo "You are not our client....";
                exit;

            } else if (($result->is_active == NULL) || ($result->is_active != 1)) {
                echo "You are not our active member....";
                exit;

            } else if ($result->client_id == $clientId && $result->is_active == 1 && $result->update_status == 1) {

                $files = self::getDataByAPI("http://182.163.102.201:8086/project_management/project_management/index.php/api/files");

                $file = end($files);

                if ($file->status == 1 && $file->status != NULL) {

                    //Download updating files from server
                    $source_path = "http://182.163.102.201:8086/project_management/uploads/" . $file->file_name;
                    $destination_path = $_SERVER['DOCUMENT_ROOT'] . '/quick_vat/quick_vat/uploads/softwareupdate.zip';

                    copy($source_path, $destination_path);

                    //Software updateting
                    SoftwareUpdate::updateSoftware();

                    //Client info updating
                    self::updateClientInfo("http://182.163.102.201:8086/project_management/project_management/index.php/api/clients/" . $clientId);

                    //Client touchpad date updating
                    $todayDate = date("Y-m-d");
                    $sql = "UPDATE touch_pad set software_update_date = '$todayDate'";
                    $connection = Yii::app()->db;
                    $command = $connection->createCommand($sql)->execute();

                    echo CHtml::link('Download Update Instruction File', array('site/download'), array('class' => 'softwareUpdateBtn'));
                    echo CHtml::link('Continue to Login', array('site/login'), array('class' => 'softwareUpdateBtn'));
                }
            }

        } else {
            $todayDate = date("Y-m-d");
            $sql = "UPDATE touch_pad set software_update_date = '$todayDate'";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql)->execute();
            return true;
        }
    }


    public static function downloadUpdateInstruction($fullpath)
    {
        if (!empty($fullpath)) {
            header("Content-type:application/pdf");
            header('Content-Disposition: attachment; filename="' . basename($fullpath) . '"');
            header('Content-Length: ' . filesize($fullpath));
            readfile($fullpath);
            Yii::app()->end();
        }
    }


    public static function actionDownload()
    {
        $instructionFile = Yii::getPathOfAlias('webroot') . "/uploads/softwareupdate/updateinstruction.pdf";
        self::downloadUpdateInstruction($instructionFile);
    }


    public static function lastUpdateDate()
    {
        $sql = "SELECT software_update_date FROM touch_pad ORDER BY software_update_date DESC LIMIT 1";

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        foreach ($data as $key => $value) {
            return $lastUpdateDate = $value['software_update_date'];
        }
    }


    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {

//        $todayDate = date("Y-m-d");
//
//        if(self::lastUpdateDate() == $todayDate) {

        $this->layout = 'login';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {

                $usersroll_id = Users::model()->usersRollOfThis(Yii::app()->user->getId());
                $uriPermissionArray = array();
                $usersRollMenuData = Yii::app()->db->createCommand()
                    ->select('uri_name')
                    ->from('usersrollmenu')
                    ->where('usersroll_id=:urid', array(':urid' => $usersroll_id))
                    ->queryAll();
                if ($usersRollMenuData) {
                    foreach ($usersRollMenuData as $key => $value) {
                        $uriPermissionArray[] = $value['uri_name'];
                    }
                }
                $session = new CHttpSession;               //add this line
                $session->open();
                $session->add('uriNames', $uriPermissionArray);
                $this->redirect(Yii::app()->createUrl('site/dashBoard'));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));

//        } else {
//            if(self::getSoftwareUpdate() == true) {
//                $this->redirect(Yii::app()->createUrl('site/index'));
//            }
//
//        }
    }

    public function actionProfitLossChart()
    {
        $this->render('dashBoard', array('chartViewPermission' => 'yes'));
    }

    public function actionDashBoard()
    {
        if (!Yii::app()->user->isGuest) {
            $this->layout = 'column1';
            $currDate = date('Y-m-d', time());

            $this->render('dashBoard');
        } else {
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
    }

    public function actionHelp()
    {
        if (!Yii::app()->user->isGuest) {
            $this->render('help');
        } else {
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionEvent()
    {
        $this->layout = 'column1';
        $this->render('event');
    }

    public function totalPurchaseOfThisMonth()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        return PurchaseItems::model()->totalPurchaseOfMonth(date("Y-m-d"));
    }

    public function totalSalesOfThisMonth()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        return SellItems::model()->totalSalesOfMonth(date("Y-m-d"));
    }


    public function totalVdsOfMonth()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        return Vds::model()->totalVdsOfMonth(date("Y-m-d"));
    }

    public function totalVatPayableOfMonth()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        $purchaseVatPayable = PurchaseItems::model()->totalVatPayableOfMonth(date("Y-m-d"));
        $salesVatPayable = SellItems::model()->totalVatPayableOfMonth(date("Y-m-d"));
        return $purchaseVatPayable + $salesVatPayable;

    }

    public function totalVatPaidOfMonth()
    {
        date_default_timezone_set('Asia/Dhaka');
        $date = date("Y-m");
        $sql = "SELECT SUM(amount) AS subamount FROM vat_payment WHERE payment_date LIKE '$date%' AND (purpose = 1) ORDER BY payment_date ASC";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data)
            return $data['subamount'];
    }

    // Total VAT Payable Amount Six Month
    public function vatPayable()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // Start date
        $date = date('Y-m-d', strtotime(date('Y-m') . " -6 month"));
        // End date
        $end_date = date('Y-m-t');

        $result = array();
        while (strtotime($date) <= strtotime($end_date)) {
            $thisMonth = $date;
            //$totalVatPayablePurchase = PurchaseItems::totalVatPayableOfMonth($thisMonth);
            //$totalVatPayableSales = SellItems::totalVatPayableOfMonth($thisMonth);
            //$total = $totalVatPayablePurchase + $totalVatPayableSales;

            $data = PurchaseItems::nineOneSummary("", "", $thisMonth);
            if ($data)
                $total = round($data['sixtyfive']);

            //$result[$thisMonth] = $total > 0 ?  $total : 0;
            $result[date('M-Y', strtotime($thisMonth))] = $total >= 0 ? $total : abs($total);
            $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
        }
        return json_encode($result);
    }

    // Total VAT Paid Amount Six Month
    public function vatPaidAmount()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // Start date
        $date = date('Y-m-d', strtotime(date('Y-m') . " -6 month"));
        // End date
        $end_date = date('Y-m-t');

        $result = array();
        while (strtotime($date) <= strtotime($end_date)) {
            $thisMonth = $date;
            //$totalPaid = VatPayment::model()->totalPayment($thisMonth);

            $data = PurchaseItems::nineOneSummary("", "", $thisMonth);
            if ($data)
                $totalPaid = round($data['fiftyeight']);

            //$result[$thisMonth] = $totalPaid > 0 ?  $totalPaid : 0;
            $result[date('M-Y', strtotime($thisMonth))] = $totalPaid;
            $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
        }
        return json_encode($result);
    }

    // Total Purchase Amount Six Month
    public function totalPurchaseAmount()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // Start date
        $date = date('Y-m-d', strtotime(date('Y-m') . " -6 month"));
        // End date
        $end_date = date('Y-m-t');

        $result = array();
        while (strtotime($date) <= strtotime($end_date)) {
            $thisMonth = $date;
            //$totalPaid = VatPayment::model()->totalPayment($thisMonth);

            $data = PurchaseItems::nineOneSummary("", "", $thisMonth);
            if ($data)
                $totalPurchase = round($data['twentythree_ka']);

            //$result[$thisMonth] = $totalPurchase > 0 ?  $totalPurchase : 0;
            $result[date('M-Y', strtotime($thisMonth))] = $totalPurchase;
            $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
        }
        return json_encode($result);
    }

    // Total Sales Amount Six Month
    public function totalSalesAmount()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // Start date
        $date = date('Y-m-d', strtotime(date('Y-m') . " -6 month"));
        // End date
        $end_date = date('Y-m-t');

        $result = array();
        while (strtotime($date) <= strtotime($end_date)) {
            $thisMonth = $date;
            //$totalPaid = VatPayment::model()->totalPayment($thisMonth);

            $data = PurchaseItems::nineOneSummary("", "", $thisMonth);
            if ($data)
                $totalSales = round($data['nine_ka']);

            //$result[$thisMonth] = $totalSales > 0 ?  $totalSales : 0;
            $result[date('M-Y', strtotime($thisMonth))] = $totalSales;
            $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
        }
        return json_encode($result);
    }
}

?>


<style type="text/css">
    .softwareUpdateBtn {
        display: inline-block;
        padding: 10px;
        background: green;
        color: #FFF;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        margin-right: 5px;
    }
</style>