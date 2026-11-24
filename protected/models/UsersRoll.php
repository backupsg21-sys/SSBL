<?php

class UsersRoll extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'usersroll';
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
            array('created_by', 'numerical', 'integerOnly' => true),
            array('name, created_by, created_time', 'safe'),
            array('id, name,  created_by, created_time', 'safe', 'on' => 'search'),
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
            'name' => 'Name',
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

    public function userRollNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->name;
        }
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_time', $this->created_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ),
        ));
    }

}