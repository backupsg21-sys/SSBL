<?php

class TreasuryPurposeController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights-'
            . 'JqueryPurposeCodeSearch-'
            . 'JqueryPurposeByPart', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionCreate()
    {
        $model = new TreasuryPurpose;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['TreasuryPurpose'])) {
            $model->attributes = $_POST['TreasuryPurpose'];
            $model->code_key = VoucherNoFormate::model()->maxValOfThis('TreasuryPurpose', 'code_key', 'maxSlNo');
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

        if (isset($_POST['TreasuryPurpose'])) {
            $model->attributes = $_POST['TreasuryPurpose'];
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


    // CREATE PURPOSE FROM OUTSIDE
    public function actionCreatePurposeFromOutSide()
    {
        $model = new TreasuryPurpose();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['TreasuryPurpose'])) {
            $model->attributes = $_POST['TreasuryPurpose'];
            $model->code_key = VoucherNoFormate::model()->maxValOfThis('TreasuryPurpose', 'code_key', 'maxSlNo');
            $valid = $model->validate();

            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {

                    $data = TreasuryPurpose::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Purpose successfully added</div>",
                        'value' => $data->code_key,
                        'label' => $data->name,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        } else {
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_formOutside', array('model' => $model), true)));
            exit;
        }


        /*else {
            $this->renderPartial('_formOutside', array(
                'model' => $model,
            ));
        }*/

        /*
                if (Yii::app()->request->isAjaxRequest) {
                    $resultDiv = '';
                    echo CJSON::encode(array(
                        'status' => 'failure',
                        'resultDiv' => $resultDiv,
                        'div' => $this->renderPartial('_formOutside', array('model' => $model), true)));
                    exit;
                }
                else
                    $this->renderPartial('_formOutside', array('model' => $model,));*/
    }


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
        $model = new TreasuryPurpose('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['TreasuryPurpose']))
            $model->attributes = $_GET['TreasuryPurpose'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionJqueryPurposeCodeSearch()
    {
        $purpose_key = trim($_POST['purpose_key']);
        $code = '';
        if (!empty($purpose_key)) {
            $data = TreasuryPurpose::model()->findByAttributes(array('code_key' => $purpose_key));
            if (!empty($data)) {
                $code = $data->code;
            }
        }
        echo $code;
    }

    public function actionJqueryPurposeByPart()
    {
        $partId = trim($_POST['nine_one_part']);
        $subPurposeList = '';
        if ($partId != '') {
            $data = TreasuryPurpose::model()->findAll(array('condition' => "nine_one_part= '$partId'", 'order' => 'id ASC'));
            if ($data) {
                $subPurposeList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subPurposeList .= CHtml::tag('option', array('value' => $d->code_key), CHtml::encode($d->name), true);
                }
            }
        }
        echo CJSON::encode(array(
            'subPurposeList' => $subPurposeList,
        ));
    }

    public function loadModel($id)
    {
        $model = TreasuryPurpose::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'treasury-purpose-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}
