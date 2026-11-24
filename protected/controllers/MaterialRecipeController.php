<?php

class MaterialRecipeController extends Controller
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
            -getMaterialOfThis', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model = new MaterialRecipe;

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MaterialRecipe']))
            $model->attributes = $_GET['MaterialRecipe'];
        $this->render('create', array(
            'model' => $model,
        ));
    }

    // Create
    public function actionSave()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        if (isset($_POST['MaterialRecipe'])) {
            if ($_POST['MaterialRecipe']['model_id'] != '' && count($_POST['MaterialRecipe']['temp_rm_model_id']) > 0) {
                $thisSLNo = VoucherNoFormate::model()->maxValOfThis('MaterialRecipe', 'sl_no', 'sl_no');
                $i = 0;
                $transaction = Yii::app()->db->beginTransaction();
                $model_qty_unit_id = $_POST['MaterialRecipe']['model_qty_unit_id'];

                try {
                    foreach ($_POST['MaterialRecipe']['temp_rm_model_id'] as $tempRmModelId) {
                        $rmQty = 0;
                        $wasteQty = 0;
                        $unitPrice = 0;
                        $rm_qty_unit_id = $_POST['MaterialRecipe']['temp_rm_qty_unit_id'][$i];
                        $waste_qty_unit_id = $_POST['MaterialRecipe']['temp_waste_qty_unit_id'][$i];
                        
                        $model = new MaterialRecipe();
                        $model->sl_no = $thisSLNo;
                        $model->model_id            = $_POST['MaterialRecipe']['model_id'];
                        $model->cost_type           = $_POST['MaterialRecipe']['temp_cost_type'][$i];
                        $model->model_qty           = $_POST['MaterialRecipe']['temp_model_qty'][$i];
                        $model->model_qty_unit_id   = $model_qty_unit_id; // Finished goods ID
                        $model->rm_model_id         = $tempRmModelId;
                        $model->rm_qty = $rmQty     = round($_POST['MaterialRecipe']['temp_rm_qty'][$i], 4);
                        $model->rm_qty_unit_id      = $rm_qty_unit_id > 0 ? $rm_qty_unit_id : NULL;
                        $model->waste_qty = $wasteQty = round($_POST['MaterialRecipe']['temp_waste_qty'][$i], 4);
                        $model->waste_qty_unit_id   = $waste_qty_unit_id > 0 ? $waste_qty_unit_id : NULL;
                        $model->total_price = $unitPrice = $_POST['MaterialRecipe']['temp_total_price'][$i];
                        $model->net_price           = (($rmQty + $wasteQty) * $unitPrice);
                        $model->approve_count       = MaterialRecipe::ACTIVE; // 1=Active
                        $model->config_date         = date('Y-m-d', strtotime($_POST['MaterialRecipe']['config_date']));
                        $model->remarks             = $_POST['MaterialRecipe']['remarks'];
                        
                        if (!$model->save()) {
                            var_dump($model->getErrors());
                        }
                        $i++;
                    }
                    $sql = "UPDATE material_recipe SET approve_count = 0 WHERE model_id = $model->model_id AND sl_no !=$model->sl_no";
                    $connection = Yii::app()->db->createCommand($sql)->execute();
                    $transaction->commit();
                } catch (Exception $e) {
                    Yii::app()->user->setFlash('error', $e->getMessage());
                    $this->redirect(Yii::app()->createUrl('materialRecipe/create'));
                }

                $this->redirect(Yii::app()->createUrl('materialRecipe/admin'));
            } else {
                $this->redirect(Yii::app()->createUrl('materialRecipe/create'));
            }
        }
    }

    // Update recipe
    public function actionUpdateRecipe($id)
    {
        $model = MaterialRecipe::model()->findByPk($id);
        if (isset($_POST['MaterialRecipe'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $transaction = Yii::app()->db->beginTransaction();

            if ($_POST['MaterialRecipe']['model_id'] != '' && count($_POST['MaterialRecipe']['temp_rm_model_id']) > 0) {

                $sl_no = $_POST['MaterialRecipe']['sl_no'];
                /*$mtrDetails = MaterialRecipe::model()->findAll(array('condition' => "sl_no=$sl_no"));
                if (!empty($mtrDetails)) {
                    foreach ($mtrDetails as $mtrDetail) {
                        Notch::model()->deleteAllByAttributes(['material_recipe_id' => $mtrDetail->id]);
                    }
                }*/
                MaterialRecipe::model()->deleteAllByAttributes(['sl_no' => $sl_no]);
                $thisSLNo = VoucherNoFormate::model()->maxValOfThis('MaterialRecipe', 'sl_no', 'sl_no');

                $i = 0;

                $model_qty = $_POST['MaterialRecipe']['model_qty'];
                $model_qty_unit_id = $_POST['MaterialRecipe']['model_qty_unit_id'];
                try {
                    foreach ($_POST['MaterialRecipe']['temp_rm_model_id'] as $tempRmModelId) {
                        $rmQty = 0;
                        $wasteQty = 0;
                        $unitPrice = 0;
                        $rm_qty_unit_id = $_POST['MaterialRecipe']['temp_rm_qty_unit_id'][$i];
                        $temp_waste_qty_unit_id = $_POST['MaterialRecipe']['temp_waste_qty_unit_id'][$i];
                        $model = new MaterialRecipe();
                        $model->sl_no = $thisSLNo;
                        $model->model_id = $_POST['MaterialRecipe']['model_id'];
                        $model->cost_type = $_POST['MaterialRecipe']['temp_cost_type'][$i];

                        /*$model->model_qty = $_POST['MaterialRecipe']['temp_model_qty'][$i];
                        $model->model_qty_unit_id = $_POST['MaterialRecipe']['temp_model_qty_unit_id'][$i];*/

                        $model->model_qty = $model_qty;
                        $model->model_qty_unit_id = $model_qty_unit_id;
                        $model->rm_model_id = $tempRmModelId;
                        $model->rm_qty = $rmQty = round($_POST['MaterialRecipe']['temp_rm_qty'][$i], 4);
                        $model->rm_qty_unit_id = $rm_qty_unit_id > 0 ? $rm_qty_unit_id : NULL;
                        $model->waste_qty = $wasteQty = round($_POST['MaterialRecipe']['temp_waste_qty'][$i], 4);
                        $model->waste_qty_unit_id = $temp_waste_qty_unit_id > 0 ? $temp_waste_qty_unit_id : NULL;
                        $model->total_price = $unitPrice = $_POST['MaterialRecipe']['temp_total_price'][$i];
                        $model->net_price = (($rmQty + $wasteQty) * $unitPrice);
                        $model->approve_count = MaterialRecipe::ACTIVE; // 1=ACTIVE
                        $model->config_date = date('Y-m-d', strtotime($_POST['MaterialRecipe']['config_date']));
                        $model->remarks = $_POST['MaterialRecipe']['remarks'];

                        $model->save();
                        $i++;
                    }
                    $sql = "UPDATE material_recipe SET approve_count = 0 WHERE model_id = $model->model_id AND sl_no !='$model->sl_no'";
                    $connection = Yii::app()->db->createCommand($sql)->execute();
                    $transaction->commit();
                } catch (Exception $e) {
                    Yii::app()->user->setFlash('error', $e->getMessage());
                    $this->redirect(Yii::app()->createUrl('materialRecipe/create'));
                }
                $this->redirect(Yii::app()->createUrl('materialRecipe/admin'));
            } else {
                $this->redirect(Yii::app()->createUrl('materialRecipe/create'));
            }
        } else {
            $this->render('update', array(
                'model' => $model,
            ));
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['MaterialRecipe'])) {
            $model->attributes = $_POST['MaterialRecipe'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            Common::model()->deleteAllByAttributes(['material_recipe_id' => $id]);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    public function actionAdmin()
    {
        $model = new MaterialRecipe('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MaterialRecipe']))
            $model->attributes = $_GET['MaterialRecipe'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionGetMaterialOfThis()
    {

        $finish_product_id = trim($_POST['finish_product_id']);
        // Fetch all cost types (1=Materials, 2=Additional, 3=Additional Indirect Material)
        $rawRecipe = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $finish_product_id, 'approve_count' => 1), array('order' => 'cost_type ASC, id ASC'));

        $str = '';
        $sl = 0;
        $additionalCostSum = 0; // Sum of Additional + Additional Indirect Material prices
        $str .=
            '<tr>
                <th style="width:20px;">SL</th>
                <th>ITEM</th>
				<th>Current Stock</th>
                <th>RM Qty</th>
                <th>Waste Qty</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>';
        if (!empty($rawRecipe)) {
            $recipe_sl_no = "";
            foreach ($rawRecipe as $dt) {

                $recipe_sl_no = $dt->sl_no;

                // For cost_type 2 and 3, just accumulate the price - don't render rows
                if ($dt->cost_type == 2 || $dt->cost_type == 3) {
                    $additionalCostSum += floatval($dt->total_price);
                    continue;
                }

                // Only cost_type 1 (Materials) below this point
                $modelName = ProdModels::model()->prodNameOfThis($dt->rm_model_id);

                $stockQty = 0;
                if ($dt->rm_qty_unit_id > 0) {
                    $stockQty = Inventory::model()->stockQtyByUnit($dt->rm_model_id, $dt->rm_qty_unit_id);
                }

                $rm_unit = Units::model()->findByPk($dt->rm_qty_unit_id);
                $rm_unit = ($rm_unit) ? $rm_unit->label : '';
                $rm_wast_unit = Units::model()->findByPk($dt->waste_qty_unit_id);
                $rm_wast_unit = ($rm_wast_unit) ? $rm_wast_unit->label : '';

                $isExceeded = $dt->rm_qty > $stockQty ? 1 : 0;
                $color = $isExceeded == 1 ? "red" : "white";
                $slData = $sl++;
                $slData += 1;
                $str .=
                    '<tr class="cartList">
                            <td class="sno">' . $slData . '</td>
                            <td style="text-align: left;width: 15%;">' . $modelName . ' <input type="hidden" class="modelId" name=Production[temp_model_id][]  value="' . $dt->rm_model_id . '"></td>
                            <td style="text-align:center">'.$stockQty.' '.$rm_unit.'</td>
						    <td>
                                <input type="hidden" value="' . $stockQty . '" class="stockQty closingStock' . $slData . '">
                                <input style="text-align: center; background: ' . $color . '" class="rmQty' . $slData . '" type="text" name="Production[temp_rm_qty][]" value="' . $dt->rm_qty . '" readonly>
                                <input type="hidden" class="rmQtyOrg" value="' . $dt->rm_qty . '">
                                <span style="display: inline-block; width: 40px;">' . $rm_unit . '</span>
                            </td>
                            <td>
                                <input style="text-align: center;" class="wasteQty' . $slData . '" type="text" name="Production[temp_waste_qty][]" value="' . $dt->waste_qty . '" readonly>
                                <input type="hidden" class="wasteQtyOrg" value="' . $dt->waste_qty . '" readonly>
                                <span style="display: inline-block; width: 40px;">' . $rm_wast_unit . '</span>
                            </td>

                            <td>
                                <input style="text-align: center;" class="unitPrice" type="text" name="Production[temp_unit_price][]" value="' . ($dt->total_price) . '" readonly>
                                <input type="hidden" class="totalPriceOrg" value="' . $dt->total_price . '">
                                <input type="hidden" name="Production[temp_cost_type][]" value="' . $dt->cost_type . '">
                                <input type="hidden" value="' . $isExceeded . '" class="isExceeded isExceededRm' . $slData . '">
                            </td>
                            <td>
                                <input style="text-align: center;" class="totPrice totalPrice' . $slData . '" type="text" name="Production[temp_total_price][]" value="' . ($dt->total_price) . '" readonly>
                            </td>
                        </tr>';
                $str .= '
                <script>
                    $("input[type=text]").attr("autocomplete","off");
                </script>';
            }

            // Add a hidden input for additional costs sum so calcTotal() includes them
            if ($additionalCostSum > 0) {
                $str .= '<input type="hidden" class="totPrice" id="additionalCostSum" value="' . round($additionalCostSum, 4) . '">';
            }

            $result                     = array();
            $result['rawdata']          = $str;
            $result['countdata']        = $slData;
            $result['recipeslno']       = $recipe_sl_no;
            $result['model_unit_id']    = (isset($rawRecipe[0]->model_qty_unit_id)) ? $rawRecipe[0]->model_qty_unit_id : 0;
            $result['model_unit_name']    = (isset($rawRecipe[0]->model_qty_unit_id)) ? Units::model()->unitLabel($rawRecipe[0]->model_qty_unit_id) : "";
        } else {
            $result['rawdata']          = '';
            $result['countdata']        = 0;
            $result['recipe_sl_no']     = '';
            $result['model_unit_id']    = '';
        }
        header('Content-Type:Application/Json');
        echo json_encode($result);
    }

    public function loadModel($id)
    {
        $model = MaterialRecipe::model()->findByPk($id);
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

}
