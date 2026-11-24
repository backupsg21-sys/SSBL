<?php

class AccountCode extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return AccountCode the static model class
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
        return 'account_code';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('office_name, code, vat_code_goods, sd_code_goods, vat_code_service, turn_over_vat_code', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, office_name, code, vat_code_goods, sd_code_goods, vat_code_service, turn_over_vat_code', 'safe', 'on' => 'search'),
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
            'office_name' => 'Office Name',
            'code' => 'Code',
            'vat_code_goods' => 'Vat Code Goods',
            'sd_code_goods' => 'Sd Code Goods',
            'vat_code_service' => 'Vat Code Service',
            'turn_over_vat_code' => 'Turn Over Vat Code',
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
        $criteria->compare('office_name', $this->office_name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('vat_code_goods', $this->vat_code_goods, true);
        $criteria->compare('sd_code_goods', $this->sd_code_goods, true);
        $criteria->compare('vat_code_service', $this->vat_code_service, true);
        $criteria->compare('turn_over_vat_code', $this->turn_over_vat_code, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getAccountWithCode()
    {
        return $this->office_name . " (" . $this->code . ")";
    }

    public function accountCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

    public function accountList()
    {
        $data = CHtml::listData(self::model()->findAll(array('order' => 'office_name asc')), 'id', 'accountWithCode');
        return $data;
    }

}