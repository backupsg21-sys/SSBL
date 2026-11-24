<?php

class PortsController extends Controller
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
            'rights-
            JqueryPortCodeSearch', // perform access control for CRUD operations
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

    public function actionCreateWeightUnitFromOutSide()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Ports::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Unit successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->label,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form5', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreateMeasurementUnitFromOutSide()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Ports::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Unit successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->label,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form6', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreateCurrUnitFromOutSide()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Ports::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Unit successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->label,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form3', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreateQtyUnitFromOutSide()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Ports::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Unit successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->label,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form4', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreateUnitFromOutSide()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Ports::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Unit successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->label,
                    ));
                    exit;
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Ports;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
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

        if (isset($_POST['Ports'])) {
            $model->attributes = $_POST['Ports'];
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
        $model = new Ports('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Ports']))
            $model->attributes = $_GET['Ports'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionJqueryPortCodeSearch()
    {
        $port_id = trim($_POST['search_portId']);
        $code = '';
        if (!empty($port_id)) {
            $data = Ports::model()->findByPk($port_id);
            $code = $data->code;
        }
        echo $code;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Ports::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ports-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}
