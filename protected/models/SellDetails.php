<?php

class SellDetails extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return SellDetails the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public $start_date;
    public $end_date;
    public $approve_type;
    public $business_type_id;
    public $report_type;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sales_details';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sales_id, model_id, parts_service, sales_type, unit_id, export_type, customer_id, created_by, updated_by, vat_calculation_method, approve_count', 'numerical', 'integerOnly' => true),
            array('stock_qty, sales_qty, unit_price, vat_percent, vat, total_tax, total, net_total, base_vat, base_vat_percent', 'numerical'),
            array('created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sales_id, model_id, customer_id, stock_qty, sales_date, sales_qty, unit_id, unit_price, vat_percent, vat, total_tax, total, net_total, approve_count, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->getId();
        } else {
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->getId();
        }
        return parent::beforeSave();
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
            'sales_id' => 'Sales',
            'model_id' => 'Model',
            'parts_service' => 'Parts/Service',
            'sales_type' => 'Sales Type',
            'customer_id' => 'Customer',
            'stock_qty' => 'Stock Qty',
            'sales_qty' => 'Sales Qty',
            'unit_id' => 'Unit',
            'unit_price' => 'Unit Price',
            'vat_percent' => 'Vat Percent',
            'sales_date' => 'Sales Date',
            'vat' => 'Vat',
            'total_tax' => 'Total Tax',
            'total' => 'Total',
            'net_total' => 'Net Total',
            'base_vat' => 'Base Vat',
            'base_vat_percent' => 'Base VAT Percent',
            'vat_calculation_method' => 'VAT Type',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
        );
    }

    public function getSellPrice($modelId)
    {
        $sql = "SELECT MAX(unit_price) as sellPrice FROM sales_details where model_id = $modelId";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        if ($data) {
            return $data['sellPrice'];
        } else {
            return 0;
        }
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
        $criteria->compare('sales_id', $this->sales_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('stock_qty', $this->stock_qty);
        $criteria->compare('sales_qty', $this->sales_qty);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('unit_price', $this->unit_price);
        $criteria->compare('vat_percent', $this->vat_percent);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('total_tax', $this->total_tax);
        $criteria->compare('total', $this->total);
        $criteria->compare('net_total', $this->net_total);
        $criteria->compare('sales_date', $this->sales_date, true);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public function  saleProductInfo($sales_id, $model_id){
        $data = self::model()->findByAttributes(array('sales_id' => $sales_id, 'model_id' => $model_id));
        return $data;
    }
    public  function productWiseSalesData($start_date, $end_date, $model_id=null){

        $criteria = "";
        if ($model_id > 0) {
            $criteria .= " AND model_id = $model_id ";
        }

        $sql = "SELECT * FROM sales_details WHERE (sales_date BETWEEN '$start_date' AND '$end_date') " . $criteria . "  AND approve_count=2 ORDER BY sales_date ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $items = $command->queryAll();
        return $items;
    }


}
