<?php

class UsersRollMenuController extends RController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    //Manages all models/
    public function actionAdmin()
    {
        $model = new UsersRollMenu('search');
        $model->unsetAttributes();
        if (isset($_GET['UsersRollMenu']))
            $model->attributes = $_GET['UsersRollMenu'];
        $this->pageTitle = 'USER ROLL MANU MANAGE';
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    //================Save data============//
    public function actionCreate()
    {
        $model = new UsersRollMenu;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['UsersRollMenu'])) {
            $model->attributes = $_POST['UsersRollMenu'];
            $valid = $model->validate();
            if ($valid) {
                $model->save();

                echo CJSON::encode(array(
                    'status' => 'success',
                ));
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
            }
            Yii::app()->end();
        } else {
            $this->render('admin', array(
                'model' => $model,
            ));
        }
    }

    //=================Update Data========//
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['UsersRollMenu'])) {
            $model->attributes = $_POST['UsersRollMenu'];
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

    //=========Delete data======/
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

    //============Load Data==========//
    public function loadModel($id)
    {
        $model = UsersRollMenu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    //==============Ajax Validation============//
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'balance-carried-from-prev-year-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
