<?php

class Inventory extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Inventory the static model class
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
        return 'inventory';
    }

    public $startDate;
    public $endDate;
    public $tStockInLast;
    public $tStockOutLast;
    public $tStockIn;
    public $tStockOut;
    public $tStockOutWithSellPrice;
    public $particular;
    public $prevAllStockIn;
    public $prevAllStockOut;

    const STOCKIN = 1;
    const STOCKOUT = 2;

    public $purchase_price;
    public $size_id;
    public $color_id;
    public $pmodel;
    public $pbrand;
    public $t_type;
    public $total_damage;
    public $vat;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('store_id, transaction_date, is_opening', 'required'),
            array('purchase_price, stock_in', 'required', 'on' => 'createScenario'),
            array('item_id, brand_id, unit_id, model_id, store_id, location_id,
                size_id, color_id, pmodel, pbrand, is_agree, purchase_id, purchase_details_id, sales_id, sell_details_id, production_id, is_opening', 'numerical', 'integerOnly' => true),
            array('location_id, stock_in, stock_out, purchase_price, sell_price', 'numerical'),
            array('code', 'length', 'max' => 255),
            // array('stock_in', 'isZeroStockQty'),
            array('transaction_date, month, year', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, brand_id, unit_id, model_id, code, purchase_details_id, sell_details_id, transaction_date, store_id, location_id, startDate, endDate, is_agree, is_opening', 'safe', 'on' => 'search'),
        );
    }

    public function isZeroStockQty()
    {

        if (($this->stock_in) <= 0) {
            $this->addError('stock_in', 'Stock In Qty must not be zero!');
        }
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
            'id' => 'Inventory ID',
            'store_id' => 'Store',
            'location_id' => 'Location',
            'item_id' => 'Quality',
            'brand_id' => 'Product Type',
            'unit_id' => 'Unit',
            'model_id' => 'Goods Name',
            'code' => 'Part No.',
            'vat' => 'VAT Rate (%)',
            'stock_in' => 'Stock Qty In',
            'stock_out' => 'Stock Qty Out',
            'transaction_date' => 'Transaction Date',
            'purchase_price' => 'Purchase Price',
            'month' => 'Month',
            'year' => 'Year',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'particular' => 'Particular',
            'is_agree' => '<font color="red">?</font> Agreed',
            'pmodel' => 'Product Model',
            'pbrand' => 'Product Brand',
            'color_id' => 'Color',
            'size_id' => 'Size',
            'sent_from_store_id' => 'Sent From Store',
            'received_to_store_id' => 'Received To Store',
        );
    }

    public function transactionReportPrevious($startDate)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut, sum(stock_out*sell_price) as tStockOutWithSellPrice, model_id, store_id, location_id";
        $criteria->condition = "transaction_date < '" . $startDate . "'";
        $criteria->group = "model_id";
        $data = self::model()->findAll($criteria);

        return $data;
    }

    public function transactionReport($startDate, $endDate)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut, sum(stock_out*sell_price) as tStockOutWithSellPrice, model_id, store_id, location_id";
        $criteria->addBetweenCondition('transaction_date', $startDate, $endDate, 'AND');
        //$criteria->addColumnCondition(array('model_id'=>572), "AND", "AND");
        $criteria->group = "model_id";
        $data = self::model()->findAll($criteria);

        return $data;
    }

    public function inventoryInfoOfThis($store_id, $item_id, $brand_id, $model_id, $code)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut";
        $criteria->condition = "item_id=" . $item_id . " AND " . "brand_id=" . $brand_id . " AND " . "model_id=" . $model_id . " AND " . "code='" . $code . "' AND " . "store_id=" . $store_id;
        $data = self::model()->findAll($criteria);

        return $data;
    }

    public function inventoryInfoOfThisModel($store_id, $model_id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut";
        $criteria->condition = "model_id=" . $model_id . " AND " . "store_id=" . $store_id;
        $data = self::model()->findAll($criteria);
        $totalStockIn = 0;
        $totalStockOut = 0;
        if ($data) {
            foreach ($data as $d):
                $totalStockIn = $d->tStockIn;
                $totalStockOut = $d->tStockOut;
            endforeach;
        }
        $stockQty = $totalStockIn - $totalStockOut;
        return $stockQty;
    }

    public function stockQtyOfThis($model_id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut";
        $criteria->condition = "model_id=" . $model_id;
        $data = self::model()->findAll($criteria);
        $totalStockIn = 0;
        $totalStockOut = 0;
        if ($data) {
            foreach ($data as $d):
                $totalStockIn = $d->tStockIn;
                $totalStockOut = $d->tStockOut;
            endforeach;
        }
        $stockQty = $totalStockIn - $totalStockOut;
        return $stockQty;
    }

    public function getTotals($keys, $type)
    {
        $infos = self::model()->findAllByPk($keys);
        $qtys = 0;
        foreach ($infos as $info):
            if ($type === self::STOCKIN)
                $qtys += $info->stock_in;
            else
                $qtys += $info->stock_out;
        endforeach;

        return $qtys;
    }

    public function getMarge($keys)
    {
        $infos = self::model()->findAllByPk($keys);
        $qtysIn = 0;
        $qtysOut = 0;
        foreach ($infos as $info):
            $qtysIn += $info->stock_in;
            $qtysOut += $info->stock_out;
        endforeach;
        $qtys = ($qtysIn - $qtysOut);
        return $qtys;
    }

    public function isStockOutZero($sellPrice, $stockOutQty)
    {
        if ($stockOutQty <= 0) {
            echo "0";
        } else {
            echo number_format(floatval($sellPrice));
        }
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {

            $prodModel = ProdModels::model()->findByPk($this->model_id);
            if ($prodModel) {
                $this->item_id = $prodModel->item_id;
                $this->brand_id = $prodModel->brand_id;
                $this->code = $prodModel->code;
            }
        } else {
            $this->is_agree = 1;
        }
        return parent::beforeSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function searchEmpty()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->addCondition('1 = 0');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 1,
            ),
        ));
    }

    public function closingStock($model_id, $store_id = NULL)
    {

        if ($store_id != NULL || $store_id != '') {
            $sql = "SELECT sum(stock_in) as tStockIn, sum(stock_out) as tStockOut FROM inventory where model_id = '$model_id' AND store_id= $store_id";
        } else {
            $sql = "SELECT sum(stock_in) as tStockIn, sum(stock_out) as tStockOut FROM inventory where model_id = '$model_id'";
        }

        //$sql = "SELECT sum(stock_in) as tStockIn, sum(stock_out) as tStockOut FROM inventory where model_id = '$model_id' AND store_id= $store_id";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        $totalStockIn = 0;
        $totalStockOut = 0;
        if ($data) {
            $totalStockIn = $data['tStockIn'];
            $totalStockOut = $data['tStockOut'];
        }
        $stockQty = $totalStockIn - $totalStockOut;
        return $stockQty;
    }

    public function searchStockAmount()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('store_id', $this->store_id);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('code', $this->code, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 500,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        // if (Rights::getAuthorizer()->isSuperuser(Yii::app()->user->getId()) === true) {

        // } else {
        //     $assignedStores = UserStore::model()->forSearchCondition();
        //     $criteria->addInCondition('store_id', $assignedStores);
        // }
        $criteria->select = "t.*";
        if ($this->model_id != "") {
            $criteria->join = " INNER JOIN prod_models ON t.model_id = prod_models.id ";
            $criteria->compare("prod_models.model_name", trim($this->model_id), true);
        }

        $criteria->compare('t.is_agree', $this->is_agree);
        $criteria->compare('t.store_id', $this->store_id);
        $criteria->compare('t.location_id', $this->location_id);
        $criteria->compare('t.item_id', $this->item_id);
        $criteria->compare('t.brand_id', $this->brand_id);
        $criteria->compare('t.unit_id', $this->unit_id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('t.stock_in', $this->stock_in);
        $criteria->compare('t.stock_out', $this->stock_out);
        $criteria->compare('t.transaction_date', $this->transaction_date);
        $criteria->compare('t.month', $this->month);
        $criteria->compare('t.year', $this->year);
        $criteria->compare('t.is_opening', $this->is_opening);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
        ));
    }

    /**
     * --------------------------------------
     * @param integer $model_id
     * @param integer $return_unit_id
     * @return float
     */
    public function stockQtyByUnit($model_id, $return_unit_id)
    {

        if ($return_unit_id == '' || $return_unit_id <= 0) return 0;

        $criteria = new CDbCriteria();
        $criteria->select = "sum(stock_in) as tStockIn, sum(stock_out) as tStockOut, unit_id";
        $criteria->group = "unit_id";
        $criteria->condition = "model_id= '$model_id' ";
        $data = self::model()->findAll($criteria);

        $returnObj = Units::model()->findByPk($return_unit_id);

        if ($returnObj && ($returnObj->category_id == Units::MASS || $returnObj->category_id == Units::VOLUME)) {
            $to = strtolower($returnObj->label);
            $stock_in = $stock_out = 0;

            foreach ($data as $row) {

                $unitObj = Units::model()->findByPk($row->unit_id);

                if (!$unitObj) continue;

                $from = strtolower($unitObj->label);
                $category_id = $unitObj->category_id;
                $stock_in += Units::model()->ConvertUnit($row->tStockIn, $from, $to, $category_id);
                $stock_out += Units::model()->ConvertUnit($row->tStockOut, $from, $to, $category_id);

            }

            return $stock_in - $stock_out;

        } else {
            $stock_in = $stock_out = 0;
            foreach ($data as $row) {
                $stock_in += $row->tStockIn;
                $stock_out += $row->tStockOut;
            }
            return $stock_in - $stock_out;
        }

    }
    public function  openingStockGet($start_date, $model_id){

        $condition = " transaction_date < '$start_date' AND model_id= $model_id";

        $sqlOpening = "SELECT SUM(stock_in) AS stockIn, SUM(stock_out) AS stockOut FROM inventory WHERE $condition";
        $openingData = Yii::app()->db->createCommand($sqlOpening)->queryRow();

        $openingStock = 0;
        if ($openingData) {
            $openingStock = $openingData['stockIn'] - $openingData['stockOut'];
        }
        return $openingStock;
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


}
