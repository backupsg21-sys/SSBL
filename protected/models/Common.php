<?php

class Common extends CActiveRecord
{
    const PURCHASE = 1;
    const SALES = 2;
    const PRODUCTION = 3;
    const USAGES = 4;
    const CREDIT = 5;
    const DEBIT = 6;
    const MANUAL_OPENING = 7;
    const TRANSFER_RECEIVE = 8;

    public $stockIn;
    public $stockOut;
    public $priceIn;
    public $priceOut;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public $start_date;
    public $end_date;

    public function tableName()
    {
        return 'common';
    }

    public function rules()
    {
        return array(
            array('type, purchase_id, sales_id, production_id, model_id, service_id, parts_service, local_import, unit_id, rebatable, material_recipe_id, rm_model_id, contractual_status, created_by, updated_by, approve_count, is_opening', 'numerical', 'integerOnly' => true),
            array('unit_price, cd_percent, cd, rd_percent, rd, sd_percent, sd, ait_percent, ait, at_percent, at, vat_percent, vat, total_tax, total, net_total, rebate, stock_qty, order_qty, stock_in, stock_out, price_in, price_out, model_qty, rm_qty, waste_qty, rm_total_price', 'numerical'),
            array('date, reason, return_challan_no, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, date, type, purchase_id, sales_id, model_id, service_id, parts_service, local_import, stock_qty, order_qty, unit_id, unit_price, cd_percent, cd, rd_percent, rd, sd_percent, sd, ait_percent, ait, at_percent, at, vat_percent, vat, total_tax, total, net_total, rebatable, model_qty, rm_model_id, rm_qty, waste_qty, approve_count, created_datetime, created_by, updated_datetime, updated_by, is_opening', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function beforeSave()
    {
        if ($this->type != self::PURCHASE) {
            if ($this->effective_date == "" || $this->effective_date == NULL || $this->effective_date == "0000-00-00") {
                $this->effective_date = $this->date;
            }
        } else {
            if ($this->effective_date == "" || $this->effective_date == NULL || $this->effective_date == "0000-00-00") {
                $this->effective_date = NULL;
            }
        }
        if ($this->isNewRecord) {
            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->id;
        } else {
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->id;
        }
        $prodInfo = ProdModels::model()->findByPk($this->model_id);
        $this->item_id = $prodInfo->item_id;
        $this->brand_id = $prodInfo->brand_id;

        if($this->type || $this->item_id || $this->purchase_id || $this->is_opening){
            $serial = Common::serialGetByType($this->type, $this->item_id, $this->purchase_id, $this->is_opening);
            $this->serial = $serial;
        }

        return parent::beforeSave();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'date' => 'Date',
            'type' => 'Type',
            'purchase_id' => 'Purchase',
            'sales_id' => 'Sales',
            'model_id' => 'Model',
            'service_id' => 'Service',
            'parts_service' => 'Parts Service',
            'local_import' => 'Local Import',
            'stock_qty' => 'Stock Qty',
            'order_qty' => 'Order Qty',
            'unit_id' => 'Unit',
            'unit_price' => 'Unit Price',
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
            'net_total' => 'Net Total',
            'rebatable' => 'Rebatable',
            'model_qty' => 'Model Qty',
            'rm_model_id' => 'Rm Model',
            'rm_qty' => 'Rm Qty',
            'waste_qty' => 'Waste Qty',
            'contractual_status' => 'Contractual Status',
            'reason' => 'Reason',
            'return_challan_no' => 'Return Challan No',
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
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('sales_id', $this->sales_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('service_id', $this->service_id);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('local_import', $this->local_import);
        $criteria->compare('stock_qty', $this->stock_qty);
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
        $criteria->compare('rebatable', $this->rebatable);
        $criteria->compare('model_qty', $this->model_qty);
        $criteria->compare('rm_model_id', $this->rm_model_id);
        $criteria->compare('rm_qty', $this->rm_qty);
        $criteria->compare('waste_qty', $this->waste_qty);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public function  productionConsumptionBalanceGet($start_date, $end_date,$model_id){

        $condition = " date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND production_id is not null and type=4" ;

        $sqlProductionConsumption = "SELECT SUM(stock_out) AS stockOut FROM common WHERE $condition";
        $productionConsumptionData = Yii::app()->db->createCommand($sqlProductionConsumption)->queryRow();

        $productionConsumptionBalance = 0;
        if ($productionConsumptionData) {
            $productionConsumptionBalance = $productionConsumptionData['stockOut'];
        }
        return $productionConsumptionBalance;
    }
    public function  productionWasteQtyBalanceGet($start_date, $end_date,$model_id){

        $condition = " date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND production_id is not null and type=4" ;

        $sqlProductionwaste = "SELECT  SUM(waste_qty) AS totalwasteQy  FROM common WHERE $condition";
        $productionwasteData = Yii::app()->db->createCommand($sqlProductionwaste)->queryRow();

        $productionWasteBalance = 0;
        if ($productionwasteData) {
            $productionWasteBalance = $productionwasteData['totalwasteQy'];
        }
        return $productionWasteBalance;
    }

    public function  productionBalanceGet($start_date, $end_date,$model_id){

        $condition = " transaction_date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND production_id is not null";

        $sqlProduction = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM inventory WHERE $condition";
        $productionData = Yii::app()->db->createCommand($sqlProduction)->queryRow();

        $productionBalance = 0;
        if ($productionData) {
            $productionBalance = $productionData['stockIn'] - $productionData['stockOut'];
        }
        return $productionBalance;
    }
    public function  salesBalanceGet($start_date, $end_date,$model_id){

        $condition = " transaction_date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND sales_id is not null";

        $sqlSales = "SELECT SUM(stock_out) AS stockOut FROM inventory WHERE $condition";
        $salesData = Yii::app()->db->createCommand($sqlSales)->queryRow();

        $salesBalance = 0;
        if ($salesData) {
            $salesBalance = $salesData['stockOut'];
        }
        return $salesBalance;
    }
    public function  purchaseBalanceGet($start_date, $end_date,$model_id){

        $condition = " transaction_date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND purchase_id is not null";

        $sqlPurchase = "SELECT SUM(stock_in) AS stockIn  FROM inventory WHERE $condition";
        $purchaseData = Yii::app()->db->createCommand($sqlPurchase)->queryRow();

        $purchaseBalance = 0;
        if ($purchaseData) {
            $purchaseBalance = $purchaseData['stockIn'];
        }
        return $purchaseBalance;
    }
    public function  salesInfoGet($start_date, $end_date,$model_id){

        $condition = " date BETWEEN '$start_date' AND '$end_date' AND model_id= $model_id AND sales_id is not null and type=2";

        $sqlSales = "SELECT SUM(stock_out) AS stockOut, SUM(total) AS totalAmount, SUM(vat) AS totalVat FROM common WHERE $condition";
        $salesData = Yii::app()->db->createCommand($sqlSales)->queryRow();

        return $salesData;
    }
    public static function serialGetByType($type, $item_id, $purchase_id, $is_opening){
        if($type == 1 && empty($purchase_id)   ){ // 1 = Opening Stock,
            return 1 ;
        }else if($type == 1 && $purchase_id > 0){ // 6 = Purchase ,
            return 6 ;
        }else if($type == 2){ // 4 = Sale,
            return 4 ;
        }else if($type == 3){ // 2 = Production,
            return 2 ;
        }else if($type == 4 && $item_id == 2 ){ // 7 = Consumption,
            return 7 ;
        }else if($type == 5){  // 5 = Sale Return,
            return 5 ;
        }else if($type == 6){ // 9 = Purchase Return
            return 9 ;
        }else if($type == 7 || $type == 8 && $item_id == 1){  // 3 = FG Transfer,
            return 3 ;
        }else if($type == 7 || $type == 8 && $item_id == 2) { // 8 = RM Transfer,
            return 8 ;
        }else{
            return 0;
        }
    }



}
