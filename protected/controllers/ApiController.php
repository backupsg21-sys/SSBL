<?php

class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers
     */
    const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array();
    }

    public function actionList()
    {
        $this->_checkAuth();
        // Get the respective model instance
        switch ($_GET['model']) {
            case 'product':
                $models = ProdModels::model()->findAll();
                break;
            default:
                // Model not implemented error
                $this->_sendResponse(501, sprintf(
                    'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
        // Did we get some results?
        if (empty($models)) {
            // No
            $this->_sendResponse(200,
                sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        } else {
            // Prepare response
            $rows = array();
            foreach ($models as $model)
                $rows[] = $model->attributes;
            // Send the response
            $this->_sendResponse(200, CJSON::encode($rows));
        }
    }

    public function actionView()
    {
        // Check if id was submitted via GET
        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        switch ($_GET['model']) {
            // Find respective model
            case 'product':
                $model = ProdModels::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf(
                    'Mode <b>view</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if (is_null($model))
            $this->_sendResponse(404, 'No Item found with id ' . $_GET['id']);
        else
            $this->_sendResponse(200, CJSON::encode($model));
    }

    public function actionCreate()
    {
        switch ($_GET['model']) {
            // Get an instance of the respective model
            case 'product':
                $model = new ProdModels();
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Try to assign POST values to attributes
        foreach ($_POST as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
        }
        // Try to save the model
        if ($model->save())
            $this->_sendResponse(200, CJSON::encode($model));
        else {
            // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    public function actionUpdate()
    {
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $put_vars = CJSON::decode($json, true);    //true means use associative array

        switch ($_GET['model']) {
            // Find respective model
            case 'product':
                $model = ProdModels::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if ($model === null)
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']));

        // Try to assign PUT parameters to attributes
        foreach ($put_vars as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                        $var, $_GET['model']));
            }
        }
        // Try to save the model
        if ($model->save())
            $this->_sendResponse(200, CJSON::encode($model));
        else
            // prepare the error $msg
            // see actionCreate
            // ...
            $msg = '500';
        $this->_sendResponse(500, $msg);
    }

    public function actionDelete()
    {
        switch ($_GET['model']) {
            // Load the respective model
            case 'product':
                $model = ProdModels::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
        // Was a model found? If not, raise an error
        if ($model === null)
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']));

        // Delete the model
        $num = $model->delete();
        if ($num > 0)
            $this->_sendResponse(200, $num);    //this is the only way to work with backbone
        else
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $_GET['id']));
    }


    public function actionSalesCreate()
    {

        $this->_checkAuth();
        $model = new SellItems();
        if (!isset($_POST['product'])) {
            $this->_sendResponse(500, 'Please Provide Product list');
        }

        $thisPurchaseNo = VoucherNoFormate::model()->maxValOfThis('SellItems', 'so_no', 'maxSONo');
        $so_no = $thisPurchaseNo;

        $todayYear = date('Y');
        $max_sl_no = VoucherNoFormate::model()->maxValOfThisWithDateCondition("SellItems", 'so_no', 'mushok_no', 'year', $todayYear);
        $mushok_no = str_pad($max_sl_no, 5, "0", STR_PAD_LEFT) . "/" . $todayYear;

        // Try to assign POST values to attributes
        foreach ($_POST['order'] as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model', $var));
        }
        $api_customer_id = $_POST['order']['customer_id'];
        $customer = Customers::model()->findByAttributes(['customer_id' => $api_customer_id]);
        if (!$customer) {
            $this->_sendResponse(500, "Customer not found");
        }

        $model = new SellItems();
        $model->so_no = $so_no;
        $model->mushok_no = $mushok_no;
        $model->year = $todayYear;
//        $model->product_type = $_POST['order']['product_type'];
        $model->product_type = 2; //finished_goods
        $model->sales_type = $_POST['order']['sales_type'];
        $model->export_type = isset($_POST['order']['export_type']) ? $_POST['order']['export_type'] : NULL;
        $model->payment_method = $_POST['order']['payment_method'];
        $model->payment_method = $_POST['order']['payment_method'];
        if ($model->payment_method == 134)
            $model->bank_id = isset($_POST['order']['bank_id']) ? $_POST['order']['bank_id'] : null;
        $model->customer_id = $customer->id;
        $model->delivery_address = $_POST['order']['delivery_address'];

        $model->sales_date = $sales_date = date('Y-m-d', strtotime($_POST['order']['sales_date']));
        $model->sales_by_id = Yii::app()->user->getId();
        $model->total_sd = $_POST['order']['total_sd'];
        $model->total_tax = $_POST['order']['total_tax'];
        $model->total = $_POST['order']['total'];
        $model->total_vds = $vds = isset($_POST['order']['total_vds']) ? $_POST['order']['total_vds'] : null;
        $model->grand_total = $_POST['order']['grand_total'];

        $model->challan_no = $_POST['order']['challan_no'];
        $model->challan_date = !empty($_POST['order']['challan_date']) ? date('Y-m-d', strtotime($_POST['order']['challan_date'])) : null;
        $model->lc_no = $_POST['order']['lc_no'];
        $model->lc_date = !empty($_POST['order']['lc_date']) ? date('Y-m-d', strtotime($_POST['order']['lc_date'])) : null;
        $model->ship_by = $_POST['order']['ship_by'];
        $model->dest_country_id = $_POST['order']['dest_country_id'];
        $model->sent_from_store_id = isset($_POST['order']['sent_from_store_id']) ? $_POST['order']['sent_from_store_id'] : 1;
        $model->approve_count = 1;
        $approve_count = $model->approve_count;

        // Try to save the model
        if ($model->save()) {
            if ($vds > 0) {
                $vdsrow = new Vds();
                $vdsrow->sales_id = $model->id;
                $vdsrow->so_no = $model->so_no;
                $vdsrow->vds = $model->total_vds;
                $vdsrow->vds_type = 2; // 1 = Purchase, 2 = Sales
                $vdsrow->parts_service = $model->parts_service;
                $vdsrow->customer_id = $model->customer_id;
                $vdsrow->issue_date = $model->sales_date;
                $vdsrow->approve_count = $approve_count;
                if (!$vdsrow->save()) {
                    $msg = "<h1>Error</h1>";
                    $msg .= "Couldn't create sales order! Please try again!";
                    $msg .= "<ul>";
                    foreach ($vdsrow->errors as $attribute => $attr_errors) {
                        $msg .= "<li>Attribute: $attribute</li>";
                        $msg .= "<ul>";
                        foreach ($attr_errors as $attr_error)
                            $msg .= "<li>$attr_error</li>";
                        $msg .= "</ul>";
                    }
                    $msg .= "</ul>";
                    $this->_sendResponse(500, $msg);
                }
            }
            $i = 0;
            foreach ($_POST['product']['model_id'] as $tempItemId) {

                $prodModels = ProdModels::model()->findByAttributes(['product_id' => $tempItemId]);
                $modelSellDetails = new SellDetails();
                $modelSellDetails->sales_id = $model->id;
                $modelSellDetails->model_id = $prodModels->id;
                $modelSellDetails->sales_date = $sales_date;
                $modelSellDetails->parts_service = $_POST['product']['parts_service'][$i];
                $modelSellDetails->sales_type = $model->sales_type;
                $modelSellDetails->export_type = $model->export_type;
                $modelSellDetails->customer_id = $model->customer_id;
                $modelSellDetails->stock_qty = $_POST['product']['stock_qty'][$i];
                $modelSellDetails->sales_qty = $sales_qty = $_POST['product']['sales_qty'][$i];
                $modelSellDetails->unit_id = $prodModels->unit_id;
                $modelSellDetails->unit_price = $unitPrice = $_POST['product']['unit_price'][$i];
                $modelSellDetails->vat_percent = $_POST['product']['vat_percent'][$i];
                $modelSellDetails->vat = $_POST['product']['vat'][$i];
                $modelSellDetails->sd_percent = $_POST['product']['sd_percent'][$i];
                $modelSellDetails->sd = $_POST['product']['sd'][$i];
                $modelSellDetails->total_tax = $_POST['product']['total_tax'][$i];
                $modelSellDetails->total = $_POST['product']['total'][$i];
                $modelSellDetails->net_total = $_POST['product']['net_total'][$i];
                $modelSellDetails->base_vat = $_POST['product']['base_vat'][$i];
                $modelSellDetails->base_vat_percent = $_POST['product']['base_vat_percent'][$i];
                $modelSellDetails->approve_count = $approve_count;

                if ($modelSellDetails->vat_percent > 0 && $modelSellDetails->vat_percent < 15)
                    $modelSellDetails->vat_calculation_method = 2; // 2 = Reducing
                else if ($modelSellDetails->vat_percent == 15)
                    $modelSellDetails->vat_calculation_method = 1; // 1=Std
                else if ($modelSellDetails->vat_percent == 0 || $modelSellDetails->vat_percent == "") {
                    if ($_POST['product']['vat_calculation_method'][$i] == 3)
                        $modelSellDetails->vat_calculation_method = 3; //3= Fixed
                    else if ($_POST['product']['vat_calculation_method'][$i] == 4)
                        $modelSellDetails->vat_calculation_method = 4; //4= Exempted/ Non
                    else
                        $modelSellDetails->vat_calculation_method = 5; //5=0 Rate
                } else
                    $modelSellDetails->vat_calculation_method = $_POST['product']['vat_calculation_method'][$i];

                if ($modelSellDetails->save()) {

                    $common = new Common();
                    $common->sales_id = $modelSellDetails->sales_id;
                    $common->date = $modelSellDetails->sales_date;
                    $common->type = Common::SALES;
                    if ($modelSellDetails->parts_service == 1) {
                        $common->model_id = $modelSellDetails->model_id;
                    } else {
                        $common->service_id = $modelSellDetails->model_id;
                    }
                    $common->parts_service = $modelSellDetails->parts_service;
                    $common->local_import = $modelSellDetails->sales_type;
                    $common->stock_qty = $modelSellDetails->stock_qty;
                    $common->order_qty = $modelSellDetails->sales_qty;
                    $common->unit_id = $modelSellDetails->unit_id;
                    $common->unit_price = $modelSellDetails->unit_price;
                    $common->vat_percent = $modelSellDetails->vat_percent;
                    $common->vat = $modelSellDetails->vat;
                    $common->sd_percent = $modelSellDetails->sd_percent;
                    $common->sd = $modelSellDetails->sd;
                    $common->total_tax = $modelSellDetails->total_tax;
                    $common->total = $modelSellDetails->total;
                    $common->net_total = $modelSellDetails->net_total;
                    $common->stock_out = $modelSellDetails->sales_qty;
                    $common->price_out = $modelSellDetails->total;
                    $common->approve_count = $approve_count;
                    if (!$common->save()) {
                        // Errors occurred
                        $msg = "<h1>Error</h1>";
                        $msg .= "Couldn't create sales order! Please try again!";
                        $msg .= "<ul>";
                        foreach ($common->errors as $attribute => $attr_errors) {
                            $msg .= "<li>Attribute: $attribute</li>";
                            $msg .= "<ul>";
                            foreach ($attr_errors as $attr_error)
                                $msg .= "<li>$attr_error</li>";
                            $msg .= "</ul>";
                        }
                        $msg .= "</ul>";
                        $this->_sendResponse(500, $msg);
                    }

                    if ($modelSellDetails->parts_service == 1) {
                        $modelInventory = new Inventory;
                        $modelInventory->sales_id = $modelSellDetails->sales_id;
                        $modelInventory->sell_details_id = $modelSellDetails->id;
                        $modelInventory->store_id = $model->sent_from_store_id;
                        $modelInventory->location_id = Location::DEFAULT_LOCATION;
                        $modelInventory->item_id = $prodModels->item_id;
                        $modelInventory->brand_id = $prodModels->brand_id;
                        $modelInventory->model_id = $modelSellDetails->model_id;
                        $modelInventory->code = $prodModels->code;
                        $modelInventory->stock_out = $sales_qty;
                        $modelInventory->transaction_date = $sales_date;
                        $modelInventory->month = date('m');
                        $modelInventory->year = date('Y');
                        if (!$modelInventory->save()) {
                            // Errors occurred
                            $msg = "<h1>Error</h1>";
                            $msg .= "Couldn't create sales order! Please try again!";
                            $msg .= "<ul>";
                            foreach ($modelInventory->errors as $attribute => $attr_errors) {
                                $msg .= "<li>Attribute: $attribute</li>";
                                $msg .= "<ul>";
                                foreach ($attr_errors as $attr_error)
                                    $msg .= "<li>$attr_error</li>";
                                $msg .= "</ul>";
                            }
                            $msg .= "</ul>";
                            $this->_sendResponse(500, $msg);
                        }
                    }
                } else {
                    // Errors occurred
                    $msg = "<h1>Error</h1>";
                    $msg .= "Couldn't create sales order! Please try again!";
                    $msg .= "<ul>";
                    foreach ($modelSellDetails->errors as $attribute => $attr_errors) {
                        $msg .= "<li>Attribute: $attribute</li>";
                        $msg .= "<ul>";
                        foreach ($attr_errors as $attr_error)
                            $msg .= "<li>$attr_error</li>";
                        $msg .= "</ul>";
                    }
                    $msg .= "</ul>";
                    $this->_sendResponse(500, $msg);
                }
                $i++;
            }
            $feedback = [
                'status' => 'success',
                'message' => 'Order saved successfully.'
            ];
//            $this->_sendResponse(200, CJSON::encode($model));
            $this->_sendResponse(200, CJSON::encode($feedback));
        } else {
            // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= "Couldn't create sales order! Please try again!";
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }


    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
            $this->sendResponse($error->code, $error->message);
    }


    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
        } // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }


    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }


    private function _checkAuth()
    {

        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if (!(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: Unauthorized.');
        }
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        // Find the user
        $user = Users::model()->find('LOWER(username)=?', array(strtolower($username)));

        if ($user === null) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: User Name is invalid');
        } else if (!$user->validatePassword($password)) {
            // Error: Unauthorized
            $this->_sendResponse(401, 'Error: User Password is invalid');
        } else {
            return true;
        }
    }
}