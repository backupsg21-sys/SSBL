<?php

class PurchaseDetails extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return PurchaseDetails the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    const STANDARD = 1;
    const REDUCING = 2;
    const FIXED = 3;
    const EXEMPTED = 4;
    const ZERO_RATE = 5;

    public $start_date;
    public $end_date;
    public $supplier_type_id;
    public $approve_type;
    public $vat_calculation_method;
    public $local_import;
    public $report_type;
    public $translation_type;
    public $submit_date;
    public $purchaseAmount;
    public $cdAmount;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'purchase_details';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('purchase_id, model_id, service_id, parts_service, local_import, supplier_id, unit_id, created_by, updated_by, rebatable, vat_calculation_method, approve_count', 'numerical', 'integerOnly' => true),
            array('order_qty, unit_price, cv_rate, cd_percent, cd, rd_percent, rd, sd_percent, sd, ait_percent, ait, at_percent, at, vat_percent, vat, total_tax, total, net_total, rebate_percent, base_vat, base_vat_percent', 'numerical'),
            array('issue_date, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, purchase_id, issue_date, model_id, service_id, supplier_id, order_qty, unit_id, unit_price, cv_rate, cd_percent, cd, rd_percent, rd, sd_percent, sd, ait_percent, ait, at_percent, at, vat_percent, vat, total_tax, total, rebate_percent, rebate, approve_count, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave()
    {
        if ($this->effective_date == "" || $this->effective_date == NULL || $this->effective_date == "0000-00-00") {
            $this->effective_date = NULL;
        }
        if ($this->isNewRecord) {
            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->id;
        } else {
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->id;
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
            'purchase_id' => 'Purchase',
            'effective_date' => 'Effective Date',
            'issue_date' => 'Issue Date',
            'model_id' => 'Model',
            'service_id' => 'Service',
            'parts_service' => 'Parts/ Service',
            'local_import' => 'Local/ Import',
            'supplier_id' => 'Supplier',
            'order_qty' => 'Order Qty',
            'unit_id' => 'Unit',
            'unit_price' => 'Unit Price',
            'cv_rate' => 'CV Rate',
            'cd_percent' => 'Cd Percent',
            'cd' => 'Cd',
            'rd_percent' => 'Rd Percent',
            'rd' => 'Rd',
            'sd_percent' => 'Sd Percent',
            'sd' => 'Sd',
            'ait_percent' => 'Ait Percent',
            'ait' => 'Ait',
            'at_percent' => 'At Percent',
            'at' => 'At',
            'vat_percent' => 'Vat Percent',
            'vat' => 'Vat',
            'total_tax' => 'Total Tax',
            'total' => 'Total',
            'net_total' => 'net_total',
            'rebate_percent' => 'Rebate Percent',
            'rebate' => 'Rebate',
            'rebatable' => 'Rebatable',
            'vat_calculation_method' => 'VAT Type',
            'base_vat' => 'Base VAT',
            'base_vat_percent' => 'Base VAT Percent',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('issue_date', $this->issue_date, true);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('service_id', $this->service_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('order_qty', $this->order_qty);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('unit_price', $this->unit_price);
        $criteria->compare('cd_percent', $this->cd_percent);
        $criteria->compare('cd', $this->cd);
        $criteria->compare('rd_percent', $this->rd_percent);
        $criteria->compare('rd', $this->rd);
        $criteria->compare('sd_percent', $this->sd_percent);
        $criteria->compare('sd', $this->sd);
        $criteria->compare('ait_percent', $this->ait_percent);
        $criteria->compare('ait', $this->ait);
        $criteria->compare('at_percent', $this->at_percent);
        $criteria->compare('at', $this->at);
        $criteria->compare('vat_percent', $this->vat_percent);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('total_tax', $this->total_tax);
        $criteria->compare('total', $this->total);
        $criteria->compare('net_total', $this->net_total);
        $criteria->compare('rebate_percent', $this->rebate_percent);
        $criteria->compare('rebate', $this->rebate);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getClosingStockByOneDateSum($model_id, $entry_date)
    {
        $closingStock = 0;
        $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM purchase_details where model_id = $model_id and issue_date< '$entry_date' order by id";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        $totalStockIn = 0;
        $totalStockOut = 0;
        if ($data) {
            $totalStockIn = $data['stockIn'];
            $totalStockOut = $data['stockOut'];
        }
        $stockQty = $totalStockIn - $totalStockOut;
        return $stockQty;
    }

    public function  purchaseProductInfo($purchase_id, $model_id){
        $data = self::model()->findByAttributes(array('purchase_id' => $purchase_id, 'model_id' => $model_id));
        return $data;
    }
    public function latestRmUnitPrice($rm_model_id){

        $sql = "SELECT unit_price  FROM purchase_details where model_id = $rm_model_id order by id DESC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        if (!empty($data)){
            return $data['unit_price'];
        }else{
            return 0;
        }
    }


}
