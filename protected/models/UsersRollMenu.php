<?php

class UsersRollMenu extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'usersrollmenu';
    }

    public function rules()
    {
        return array(
            array('usersroll_id, name, uri_name', 'required'),
            array('usersroll_id, created_by', 'numerical', 'integerOnly' => true),
            array('usersroll_id, name, uri_name, created_time', 'safe'),
            array('id, usersroll_id, name, uri_name,  created_by, created_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'usersroll_id' => 'Users Roll',
            'name' => 'Name',
            'uri_name' => 'URI Name',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
        );
    }

    public function beforeSave()
    {
        $this->created_time = new CDbExpression('NOW()');
        $this->created_by = Yii::app()->user->getId();
        return parent::beforeSave();
    }

    public function search()
    {

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('usersroll_id', $this->usersroll_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('uri_name', $this->uri_name, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_time', $this->created_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'usersroll_id ASC',
            ),
        ));
    }

}