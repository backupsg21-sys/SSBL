<?php

class PaymentCode extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return PaymentCode the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_code';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sl_book', 'numerical', 'integerOnly' => true),
            array('name, code', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sl_book, name, code', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sl_book' => 'Sl Book',
            'name' => 'Name',
            'code' => 'Code',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('sl_book', $this->sl_book);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getNameWithCode()
    {
        return $this->name . " (" . $this->code . ")";
    }

    public function accountCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

    public function accountList()
    {
        $data = CHtml::listData(self::model()->findAll(array('order' => 'sl_book asc')), 'id', 'nameWithCode');
        return $data;
    }

    public function getNameWithCodeById($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->name.' ('.$data->code.')';
    }
}