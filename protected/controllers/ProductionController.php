<?php

class ProductionController extends Controller
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


    public function actionCreate()
    {

        $model = new Production;

        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Production'])) $model->attributes = $_GET['Production'];

        $this->render('create', array(
            'model' => $model,
        ));

    }

    // Create parts local - action
    public function actionSave()
    {

        Yii::app()->clientScript->scriptMap['jquery.js'] = false;

        if (isset($_POST['Production'])) {

            if ($_POST['Production']['finish_product_id'] != '' && count($_POST['Production']['temp_model_id']) > 0) {

                $fg_id = $_POST['Production']['finish_product_id'];
                $recipe_sl_no = $_POST['recipe_sl_no'];
                $fg_unit = null;
                
                /**
                 * --------------------------------------------
                 * Get Materils Units From Materials Recipe
                 * --------------------------------------------
                */
                if (!empty($recipe_sl_no) && $recipeData = MaterialRecipe::model()->findByAttributes(array('sl_no' => $recipe_sl_no))) {

                    if ($recipeData) $fg_unit = $recipeData['model_qty_unit_id'];

                }

                $date = date('Y-m-d', strtotime($_POST['Production']['issue_date']));
                $thisProductionNo = VoucherNoFormate::model()->maxValOfThis('Production', 'max_sl_no', 'maxSlNo');
                
                /**
                 * -------------------------------------
                 * Data Insert Into Production
                 * -------------------------------------
                */
                $model = new Production();
                $model->issue_date          = $date;
                $model->finish_product_id   = $fg_id;
                $model->fg_unit_id          = $fg_unit;
                $model->qty                 = $_POST['Production']['qty'];
                $model->adjustment_value    = isset($_POST['Production']['adjustment_value']) ? $_POST['Production']['adjustment_value'] : 0;
                $fg_price                   = MaterialRecipe::model()->getRecipeTotalCostByProductId($model->finish_product_id);
                $model->total_price         = ($fg_price + $model->adjustment_value) * $model->qty;
                $model->customer_id         = $_POST['Production']['customer_id'];
                $model->max_sl_no           = $thisProductionNo;
                $model->sl_no               = $thisProductionNo . "/" . date("Y", strtotime($date));
                $model->contractual_status  = $_POST['Production']['contractual_status'];
                $model->recipe_sl_no        = $recipe_sl_no;
                $model->is_approved         = 1;


                if ($model->save()) {

                    $i = 0;

                    foreach ($_POST['Production']['temp_model_id'] as $tempModelId) {

                        $rm_unit_id = null;
                        $waste_unit_id = null;

                        if (!empty($recipe_sl_no) && $recipeData = MaterialRecipe::model()->findByAttributes(array('sl_no' => $recipe_sl_no, 'rm_model_id' => $tempModelId))) {

                            if ($recipeData) {

                                $rm_unit_id = $recipeData['rm_qty_unit_id'];
                                $waste_unit_id = $recipeData['waste_qty_unit_id'];

                            }
                        }
                        /**
                         * ------------------------------------------------------------
                         * Insert into ProductionMaterial(Production Details Table)
                         * ------------------------------------------------------------
                        */
                        $modelProd = new ProductionMaterial();
                        $modelProd->production_id   = $model->id;
                        $modelProd->model_id        = $tempModelId;
                        $modelProd->rm_unit_id      = $rm_unit_id;
                        $modelProd->qty             = $_POST['Production']['temp_rm_qty'][$i];
                        $modelProd->waste_qty       = $_POST['Production']['temp_waste_qty'][$i];
                        $modelProd->waste_unit_id   = $waste_unit_id;
                        $modelProd->total_price     = round($_POST['Production']['temp_total_price'][$i], 4);

                        if ($modelProd->save()) {

                            /**
                             * ----------------------------------------------------------------
                             * :: Stock Out :: 
                             * Stock Out for Materials that are used for prodction 
                             * ----------------------------------------------------------------
                            */
                            $inventory = new Inventory();
                            $inventory->production_id       = $model->id;
                            $inventory->model_id            = $modelProd->model_id;
                            $inventory->unit_id             = $modelProd->rm_unit_id;
                            $inventory->stock_out           = $_POST['Production']['temp_rm_qty'][$i] + $_POST['Production']['temp_waste_qty'][$i];
                            $inventory->store_id            = 1;
                            $inventory->transaction_date    = $model->issue_date;
                            $inventory->year                = date('Y', strtotime($model->issue_date));
                            $inventory->month               = date('m', strtotime($model->issue_date));
                            $inventory->save();

                            if ($_POST['Production']['temp_cost_type'][$i] == 1) {

                                $common = new Common();
                                $common->production_id = $model->id;
                                $common->parts_service = 1;
                                $common->date = $model->issue_date;
                                $common->type = Common::USAGES; // 4= Usage
                                $common->model_id = $modelProd->model_id;
                                $common->rm_qty = $modelProd->qty;
                                $common->waste_qty = $modelProd->waste_qty;
                                 $common->unit_price     = round($_POST['Production']['temp_unit_price'][$i], 4);
                                $common->rm_total_price = $modelProd->total_price;
                                $common->stock_out = $modelProd->qty + $modelProd->waste_qty;
                                $common->price_out = $modelProd->total_price;
                                $common->unit_id = $modelProd->rm_unit_id;
                                $common->approve_count = 2;
                                $common->save();

                            }
                        }
                        $i++;
                    }

                    /**
                     * ----------------------------------------------
                     * :: Stock In ::
                     * Stock In for Finished good
                     * ----------------------------------------------
                    */
                    $inventory = new Inventory();
                    $inventory->production_id       = $model->id;
                    $inventory->model_id            = $model->finish_product_id;
                    $inventory->unit_id             = $model->fg_unit_id;
                    $inventory->stock_in            = $model->qty;
                    $inventory->store_id            = 1;
                    $inventory->transaction_date    = $model->issue_date;
                    $inventory->year                = date('Y', strtotime($model->issue_date));
                    $inventory->month               = date('m', strtotime($model->issue_date));
                    $inventory->save();

                    $common = new Common();
                    $common->production_id = $model->id;
                    $common->parts_service = 1;
                    $common->date = $model->issue_date;
                    $common->type = Common::PRODUCTION; // 3= Production
                    $common->model_id = $model->finish_product_id;
                    $common->model_qty = $model->qty;
                    $common->contractual_status = $model->contractual_status;
                    $common->rm_total_price = round($model->total_price, 4);
                    $common->stock_in = $model->qty;
                    $fg_price = MaterialRecipe::model()->getRecipeTotalCostByProductId($model->finish_product_id);
                    $common->price_in = ($fg_price + $model->adjustment_value) * $model->qty;
                    $common->unit_id = $model->fg_unit_id;
                    $common->approve_count = 2;
                    $common->save();

                }

                $this->redirect(Yii::app()->createUrl('production/admin'));

            } else {

                $this->redirect(Yii::app()->createUrl('production/create'));
            }
        }
    }

    /*
    // Update recipe
    public function actionUpdateRecipe($id) {
        $model = Production::model()->findByPk($id);
        if (isset($_POST['Production'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            if($_POST['Production']['model_id']!='' && count($_POST['Production']['temp_rm_model_id'])>0){

                $sl_no = $_POST['Production']['sl_no'];
                Production::model()->deleteAllByAttributes(['sl_no' => $sl_no]);
                $thisSLNo = VoucherNoFormate::model()->maxValOfThis('Production', 'sl_no', 'sl_no');

                $i = 0;
                foreach ($_POST['Production']['temp_rm_model_id'] as $tempRmModelId) {
                    $model = new Production();
                    $model->sl_no = $thisSLNo;
                    $model->model_id = $_POST['Production']['model_id'];
                    $model->model_qty = $_POST['Production']['temp_model_qty'][$i];
                    $model->rm_model_id = $tempRmModelId;
                    $model->rm_qty = $_POST['Production']['temp_rm_qty'][$i];
                    $model->waste_qty = $_POST['Production']['temp_waste_qty'][$i];
                    $model->total_price = $_POST['Production']['temp_total_price'][$i];
                    $model->config_date = date('Y-m-d', strtotime($_POST['Production']['config_date']));

                    if($model->save()){
                        $common = new Common();
                        $common->model_id = $model->model_id;
                        $common->parts_service = 1;
                        $common->date = $model->config_date;
                        $common->type = 3; // 1= Production
                        $common->model_qty = $model->model_qty;
                        $common->material_recipe_id = $model->id;
                        $common->rm_model_id = $model->rm_model_id;
                        $common->rm_qty = $model->rm_qty;
                        $common->waste_qty = $model->waste_qty;
                        $common->total = $model->total_price;
                        $common->save();
                    }
                    $i++;
                }
                $this->redirect(Yii::app()->createUrl('materialRecipe/admin'));
            }else{
                $this->redirect(Yii::app()->createUrl('materialRecipe/create'));
            }
        } else {
            $this->render('update', array(
                'model' => $model,
            ));
        }
    }*/

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Production'])) {
            $model->attributes = $_POST['Production'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
            ProductionMaterial::model()->deleteAllByAttributes(['production_id' => $id]);
            Inventory::model()->deleteAllByAttributes(['production_id' => $id]);
            Common::model()->deleteAllByAttributes(['production_id' => $id]);

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
        $model = new Production('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Production']))
            $model->attributes = $_GET['Production'];

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
        $model = Production::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'material-recipe-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionApprove($id){
        
        if($id){
            $model = Production::model()->findByPk($id);
            $model->is_approved = 1;
            $model->save();
        }
        
        $this->redirect(Yii::app()->createUrl('production/admin'));
    }

    // production preview
    public function actionPreview($id)
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;

        $sql = "SELECT * FROM production WHERE id = $id ORDER BY id ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        $inventory_data = Inventory::model()->findAll(array('condition' => "production_id =  $id and item_id=2", 'order' => 'id desc'));

        $this->renderPartial('poProductionReport', array('data' => $data, 'inventory_data' => $inventory_data));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }
}
