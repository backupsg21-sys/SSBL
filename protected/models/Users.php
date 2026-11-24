<?php

class Users extends CActiveRecord
{

    public $password2;
    public $userLevel;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array(
            array('username, password, password2', 'required'),
            array('username', 'unique', 'caseSensitive' => FALSE, 'message' => 'Username already exist! Please choose another.'),
            array('id, employee_id, usersroll_id, status', 'numerical', 'integerOnly' => true),
            array('username, password, password2', 'length', 'max' => 20, 'min' => 1),
            array('password', 'compare', 'compareAttribute' => 'password2'),
            array('id, employee_id, usersroll_id, username, password, create_by, create_time, update_by, update_time, status', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function validatePassword($password)
    {
        return $this->hashPassword($password) === $this->password;
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    public function beforeSave()
    {

        $this->password = md5($this->password);
        if ($this->isNewRecord) {
            $this->create_time = new CDbExpression('NOW()');
            $this->create_by = Yii::app()->user->getName();
        } else {
            $this->update_time = new CDbExpression('NOW()');
            $this->update_by = Yii::app()->user->getName();
        }
        return parent::beforeSave();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userLevel' => 'User Level',
            'employee_id' => 'Employee',
            'usersroll_id' => 'Users Roll',
            'username' => 'User Name',
            'password' => 'Password',
            'password2' => 'Repeat Password',
            'create_by' => 'Create By',
            'create_time' => 'Create Time',
            'update_by' => 'Update By',
            'update_time' => 'Update Time',
        );
    }

    public function fullEmpInfoOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            $empInfo = Employees::model()->findByPk($data->employee_id);
            if ($empInfo)
                return $empInfo;
        }
    }

    public function fullNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            $empInfo = Employees::model()->findByPk($data->employee_id);
            if ($empInfo) {
                return $empInfo->full_name;
            } else {
                return $data->username;
            }
        }
    }

    public function designationOfThisUser($id)
    {
        $data = self::model()->findByPk($id);
        $designation = '';
        if ($data) {
            $empInfo = Employees::model()->findByPk($data->employee_id);
            if ($empInfo) {
                $designation = Designations::model()->infoOfThis($empInfo->designation_id);
            }
        }
        return $designation;
    }

    public function userNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->username;
        }
    }

    public function usersRollOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->usersroll_id;
        }
    }

    public function nameOfUser($id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "username";
        $data = self::model()->findByPk($id, $criteria);
        if ($data)
            return $data->username;
    }


    public function findAllRolesOfThisUser($id)
    {
        echo "
            <style>
                span.userLevelSpan{
                    color: #FFFFFF;
                    display: block;
                    float: left;
                    height: 100%;
                    padding: 2px;
                    width: 98%;
                }
            </style>
        ";
        if (Rights::getAuthorizer()->isSuperuser($id) === true) {
            $roles = Rights::getAssignedRoles($id);
            foreach ($roles as $role):
                echo "<span class='userLevelSpan' style='background-color: red;'>" . $role->name . "</span><br>";
            endforeach;
        } else {
            $roles = Rights::getAssignedRoles($id);
            foreach ($roles as $role):
                echo "<span class='userLevelSpan' style='background-color: seagreen;'>" . $role->name . "</span><br>";
            endforeach;
        }
    }

    public function allAvailableRoles()
    {
        Yii::import("application.modules.rights.components.dataproviders.RAuthItemDataProvider");
        $all_roles = new RAuthItemDataProvider('roles', array(
            'type' => 2, // type 2 means all roles;
        ));
        $data = $all_roles->fetchData();
        return CHtml::dropDownList("Type", '', CHtml::listData($data, 'name', 'name'), array('prompt' => ''));
    }

    public function allUsersOfAParticularRole($roleName)
    {
        $data_entry_users = Yii::app()->getAuthManager()->getAssignmentsByItemName($roleName);
        $data_entry_users_id = array();
        foreach ($data_entry_users as $id => $assignment):
            $data_entry_users_id[] = $id;
        endforeach;

        return $data_entry_users_id;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('employee_id', $this->employee_id);
        $criteria->compare('usersroll_id', $this->usersroll_id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('create_by', $this->create_by, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_by', $this->update_by, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }
    public function  getUserSignature($user_id){
        $user_info = Users::model()->findByPk($user_id);
        if($user_info){
            $signature = $user_info->signature;
            if (!empty($signature)){
                echo  "/upload/signature/".$signature;
            }else{
                echo  "/upload/signature/default.png";
            }
        }else{
            echo  "/upload/signature/default.png";
        }
    }

}