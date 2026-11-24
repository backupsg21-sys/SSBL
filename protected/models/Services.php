<?php

class Services extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Services the static model class
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
        return 'services';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, code, sub_vat_id', 'required'),
            array('category_id, unit_id, rebatable, vat_calculation_method, sub_vat_id', 'numerical', 'integerOnly' => true),
            array('mrp, purchase_price, sell_price, purchase_discount, sell_discouont, vat, sd', 'numerical'),
            array('name, code', 'length', 'max' => 255),
            array('description', 'safe'),
            // Please remove those attributes that should not be searched.
            array('id, name, code, description, category_id, mrp, purchase_price, sell_price, purchase_discount, sell_discouont, unit_id, vat', 'safe', 'on' => 'search'),
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

    public function prodNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->name;
    }

    public function prodCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Service Name',
            'code' => 'HS Code',
            'description' => 'Description',
            'category_id' => 'Category',
            'mrp' => 'Mrp',
            'purchase_price' => 'Purchase Price',
            'sell_price' => 'Sell Price',
            'purchase_discount' => 'Purchase Discount',
            'sell_discouont' => 'Sell Discount',
            'unit_id' => 'Unit',
            'vat' => 'VAT',
            'sd' => 'SD (%)',
            'rebatable' => 'Rebatable',
            'vat_calculation_method' => 'VAT Type',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('mrp', $this->mrp);
        $criteria->compare('purchase_price', $this->purchase_price);
        $criteria->compare('sell_price', $this->sell_price);
        $criteria->compare('purchase_discount', $this->purchase_discount);
        $criteria->compare('sell_discouont', $this->sell_discouont);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('sub_vat_id', $this->sub_vat_id);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('sd', $this->sd);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
