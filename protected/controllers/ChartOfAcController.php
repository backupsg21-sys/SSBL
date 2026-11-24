<?php

class ChartOfAcController extends Controller {

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
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return '';
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */

    // TEST Erp prod to MBW, about 12000 data
    Public function actionSamba() {

        //$googData = ProdGoogles::model()->findAll();

        $sql = "SELECT * FROM prod_googles ORDER BY id ASC";
        $googData = Yii::app()->db->createCommand($sql)->queryAll();

        echo "Total=" .count($googData);
        echo '<br>';

        $sqll = "SELECT * FROM prod_models WHERE brand_id=10 ORDER BY id ASC";
        $googDatall = Yii::app()->db->createCommand($sqll)->queryAll();

        echo "Total=" .count($googDatall);
        echo '<br>';

        /*$i = 0;
        foreach ($googData as $data){
            $i = $i+1;
            $model = new ProdModels();
            $model->item_id = 2;
            $model->brand_id = 10;
            $model->model_name = $data['model_name'];
            $model->product_image = $data['product_image'];
            $model->code = $data['code'];
            $model->unit_id = $data['unit_id'];
            $model->min_order_qty = $data['min_order_qty'];
            $model->features = $data['features'];
            $model->pbrand = $data['pbrand'];
            $model->vat_calculation_method = 5;
            $model->sub_vat_id = 141;

            if($model->save())
                echo 'Y--';
            else
                echo 'N--';

            echo $data['id'];
            echo '<br>';
            if($i==5000)
                break;
        }*/



        echo 5;
        exit;




        $model = new ChartOfAc;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ChartOfAc'])) {
            $model->attributes = $_POST['ChartOfAc'];
            //$thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
            //$model->code=$thisCodeNo;
            if ($model->save()) {
                $this->redirect(array('admin'));
            }
        }
        $this->render('admin', array(
            'model' => $model,
        ));
    }




    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionCreateCAFromOutSide() {
        $model = new ChartOfAc;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        //$thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
        if (isset($_POST['ChartOfAc'])) {
            $model->attributes = $_POST['ChartOfAc'];
            //$model->code=$thisCodeNo;
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = ChartOfAc::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New CA successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->title,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ChartOfAc;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ChartOfAc'])) {
            $model->attributes = $_POST['ChartOfAc'];
            //$thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
            //$model->code=$thisCodeNo;
            if ($model->save()) {
                $this->redirect(array('admin'));
            }
        }
        $this->render('admin', array(
            'model' => $model,
        ));
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

        if (isset($_POST['ChartOfAc'])) {
            $model->attributes = $_POST['ChartOfAc'];
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
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            if ($model->account_type == ChartOfAc::PARENT_CA) {
                echo "<div class='flash-error'>Can not delete this CA</div>"; //for ajax
            } else {
                $condition = "ca_id=" . $model->id;
                $data = JournalEntry::model()->findAll(array('condition' => $condition));
                if ($data) {
                    echo "<div class='flash-error'>Can not delete this CA, a transaction has already been performed with this CA.</div>"; //for ajax
                } else {
                    $model->delete();
                }
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionDeleteAll() {
        if (isset($_POST['chart-of-ac-grid_c0'])) {
            $del_item = $_POST['chart-of-ac-grid_c0'];
            $model_item = new ChartOfAc;
            //$model_item_all = new ChartOfAc;
            foreach ($del_item as $_id) {
                $model = $this->loadModel($_id);
                if ($model->account_type != ChartOfAc::PARENT_CA)
                    $model_item->deleteByPk($_id);
                //$model_item_all->deleteAllByAttributes(array('id' => $_id));
            }
            $this->redirect(array('admin'));
        } else {
            Yii::app()->user->setFlash('error', 'Please select at least one item to delete.');
            $this->redirect(array('admin'));
        }
    }


    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ChartOfAc('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ChartOfAc']))
            $model->attributes = $_GET['ChartOfAc'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminDepSetup() {

        $model = new ChartOfAc('searchDepSetup');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ChartOfAc']))
            $model->attributes = $_GET['ChartOfAc'];

        $this->render('adminDepSetup', array(
            'model' => $model,
        ));
    }

    public function actionUpdateDepSetup($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ChartOfAc'])) {
            $model->attributes = $_POST['ChartOfAc'];
            $model->setScenario('depSetupScenario');
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    // Stop jQuery from re-initialization
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'content' => '<div class="flash-notice">successfully saved</div>',
                    ));
                    exit;
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form3', array(
                    'model' => $model), true, true),
            ));
            exit;
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = ChartOfAc::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'chart-of-ac-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
