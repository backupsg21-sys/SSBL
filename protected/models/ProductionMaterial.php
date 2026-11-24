<?php

/**
 * This is the model class for table "production_material".
 *
 * The followings are the available columns in table 'production_material':
 * @property integer $id
 * @property integer $production_id
 * @property integer $model_id
 * @property double $qty
 * @property double $waste_qty
 * @property double $total_price
 */
class ProductionMaterial extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return ProductionMaterial the static model class
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
        return 'production_material';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('production_id, model_id, rm_unit_id, waste_unit_id', 'numerical', 'integerOnly' => true),
            array('qty, waste_qty, total_price', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, production_id, model_id, rm_unit_id, qty, waste_qty, waste_unit_id, total_price', 'safe', 'on' => 'search'),
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
            'production_id' => 'Production',
            'model_id' => 'RM',
            'rm_unit_id' => 'RM Unit',
            'qty' => 'Qty',
            'waste_qty' => 'Waste Qty',
            'waste_qty' => 'Waste Unit',
            'total_price' => 'Total Price',
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
        $criteria->compare('production_id', $this->production_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('waste_qty', $this->waste_qty);
        $criteria->compare('total_price', $this->total_price);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}