<?php

class CorrectionController extends Controller
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */

    public function actionEditModeSo()
    {
        $model = new Correction();

        if (isset($_POST['Correction'])) {
            $so_no      = trim($_POST['Correction']['sl_no']);
            $sales_data = SellItems::model()->findByAttributes(array('so_no' => $so_no));
            if (!empty($sales_data)) {
                try {
                    $sales_id        = $sales_data->id;
		   
		    // data delete from common table
                    $common_data     = Common::model()->findAllByAttributes(array('sales_id' => $sales_id));
                    if(!empty($common_data)){
                        Common::model()->deleteAllByAttributes(array('sales_id' => $sales_id));
                    }

                    // data delete from inventory table
                    $inventory_data    = Inventory::model()->findAllByAttributes(array('sales_id' => $sales_id));
                    if(!empty($inventory_data)){
                        Inventory::model()->deleteAllByAttributes(array('sales_id' => $sales_id));
                    }

                    // data update from sales details table
                    $criteria            = new CDbCriteria;
                    $criteria->condition = "sales_id = '$sales_id'";
                    SellDetails::model()->updateAll(array('approve_count' => 1 ), $criteria);

                    // data update from sell table
                    SellItems::model()->updateByPk($sales_id, array("approve_count" => 1));

                    $model->sl_no    =  $so_no;
                    $model->sales_id =  $sales_id;
                    $model->type     =  2;
                    $model->date     =  $sales_data->sales_date;
                    $model->save();

                    echo CJSON::encode(array(
                        'status' => 'success',
                    ));
                    Yii::app()->end();

                } catch (\Exception $e) {
                    Yii::error($e->getMessage());
                }

            }else{
                echo CJSON::encode(array(
                    'status' => 'error',
                ));
                Yii::app()->end();
            }

        } else {
            $this->render('editModeSo', array(
                'model' => $model,
            ));
        }
    }
    public function actionEditModePo()
    {
        $model = new Correction();

        if (isset($_POST['Correction'])) {
            $po_no      = trim($_POST['Correction']['sl_no']);
            $purchase_data = PurchaseItems::model()->findByAttributes(array('po_no' => $po_no));
            if (!empty($purchase_data)) {
                try {
                    $purchase_id        = $purchase_data->id;

                    // data delete from common table
                    $common_data     = Common::model()->findAllByAttributes(array('purchase_id' => $purchase_id));
                    if(!empty($common_data)){
                        Common::model()->deleteAllByAttributes(array('purchase_id' => $purchase_id));
                    }

                    // data delete from inventory table
                    $inventory_data    = Inventory::model()->findAllByAttributes(array('purchase_id' => $purchase_id));
                    if(!empty($inventory_data)){
                        Inventory::model()->deleteAllByAttributes(array('purchase_id' => $purchase_id));
                    }

                    // data update from purchase details table
                    $criteria            = new CDbCriteria;
                    $criteria->condition = "purchase_id = '$purchase_id'";
                    PurchaseDetails::model()->updateAll(array('approve_count' => 1 ), $criteria);

                    // data update from purchase table
                    PurchaseItems::model()->updateByPk($purchase_id, array("approve_count" => 1));

                    $model->sl_no       =  $po_no;
                    $model->purchase_id =  $purchase_id;
                    $model->type        =  1;
                    $model->date        =  $purchase_data->issue_date;
                    $model->save();

                    echo CJSON::encode(array(
                        'status' => 'success',
                    ));
                    Yii::app()->end();

                } catch (\Exception $e) {
                    Yii::error($e->getMessage());
                }

            }else{
                echo CJSON::encode(array(
                    'status' => 'error',
                ));
                Yii::app()->end();
            }

        } else {
            $this->render('editModePo', array(
                'model' => $model,
            ));
        }
    }

}
