<?php

class UsersController extends RController {

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';
    private $_model;

    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return '';
    }

    public function actionView() {
        $model=$this->loadModel();
        $this->renderPartial('view', array('model'=>$model,));
        if(!isset($_GET['ajax'])){
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }


    public function actionCreate() {
        $model = new Users;
        $this->performAjaxValidation($model);

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $valid = $model->validate();
            if ($valid) {
                $model->save();
                //do anything here
                echo CJSON::encode(array(
                    'status' => 'success'
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
   
    public function actionUpdate() {
        $model = $this->loadModel();
        $oldpassword = $model->password;
        //$this->performAjaxValidation($model);
 
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if(isset($_POST['Users']['password']) && ($_POST['Users']['password'] != null)) {
                $password = Users::hashPassword($_POST['Users']['password']);
            }
            else{
                $password = $oldpassword;
            }
                $update = Users::model()->updateByPk($model->id, array('password' => $password, 'status'=>$_POST['Users']['status']));
                if($update)
                {
                    if (Yii::app()->request->isAjaxRequest) {
                        // Stop jQuery from re-initialization
                        Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                        echo CJSON::encode(array(
                            'status' => 'success',
                            'content' => '<div class="flash-notice">successfully updated. pass update</div>',
                        ));
                        exit;
                    }
                    else
                        $this->redirect(array('admin'));
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
        else
            $this->render('update', array('model' => $model));
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            //Yii::app()->user->isSuperuser;  OR Rights::getAuthorizer()->isSuperuser(Yii::app()->user->getId());
            if(Rights::getAuthorizer()->isSuperuser($id)===true ){
                echo "<div class='flash-error'>Can not delete Super User! You can only change the info.</div>"; //for ajax
            }else{
                $this->loadModel()->delete();
            }
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    public function actionAdmin() {
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Users'])) {
            $model->attributes = $_GET['Users'];
        }
        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    public function actionAdminAssignStore(){
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Users'])) {
            $model->attributes = $_GET['Users'];
        }
        $this->render('adminAssignStore', array(
            'model' => $model,
        ));
    }

    public function loadModel() {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = Users::model()->findbyPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionDbBackup() {
        $this->backup_Database1('localhost', 'root', '7RA/1nqQpw]J]*]v', 'ssbl_qv');
    }

    function backup_Database1($hostName, $userName, $password, $DbName) {
        // CONNECT TO THE DATABASE
        $con = mysql_connect($hostName, $userName, $password) or die(mysql_error());
        mysql_select_db($DbName, $con) or die(mysql_error());
        $tables = '*';
        // GET ALL TABLES
        if ($tables == '*') {
            $tables = array();
            $result = mysql_query('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $data = '';
        foreach ($tables as $table) {
            $result = mysql_query('SELECT * FROM ' . $table) or die(mysql_error());
            $num_fields = mysql_num_fields($result) or die(mysql_error());
            $data .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
            $data .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $data .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($x = 0; $x < $num_fields; $x++) {
                        $row[$x] = addslashes($row[$x]);
                        $row[$x] = $this->clean($row[$x]);
                        if (isset($row[$x])) {
                            $data .= '"' . $row[$x] . '"';
                        } else {
                            $data .= '""';
                        }
                        if ($x < ($num_fields - 1)) {
                            $data .= ',';
                        }
                    }
                    $data .= ");\n";
                }
            }
            $data .= "\n\n\n";
        }
        date_default_timezone_set("Asia/Dhaka");
        $backup_name = 'db_backup_ssbl_' . date('Y-m-d @ h-i-s') . '.sql';
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
        echo $data;
    }

    function clean($str) {
        if (@isset($str)) {
            $str = @trim($str);
            if (get_magic_quotes_gpc()) {
                $str = stripslashes($str);
            }
            return mysql_real_escape_string($str);
        } else {
            return 'NULL';
        }
    }

}
