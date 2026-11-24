<?php

class SellPrice extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return SellPrice the static model class
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
        return 'sell_price';
    }

    const ACTIVE = 1;
    const INACTIVE = 2;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model_id, sell_price, is_active', 'required'),
            array('model_id, is_active, ideal_qty, warn_qty, add_by, update_by', 'numerical', 'integerOnly' => true),
            array('sell_price, discount', 'numerical'),
            array('add_time, update_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, model_id, sell_price, start_date, end_date, is_active, discount, ideal_qty, warn_qty', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'prodModels' => array(self::BELONGS_TO, 'ProdModels', 'model_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'model_id' => 'Product',
            'sell_price' => 'Sell Price',
            'discount' => 'Discount(%)',
            'ideal_qty' => 'Ideal Qty',
            'warn_qty' => 'Warn Qty',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'is_active' => 'Is Active',
            'add_time' => 'Added Time',
            'update_time' => 'Updated Time',
            'add_by' => 'Added By',
            'update_by' => 'Updated By',
        );
    }

    public function activeSellpriceByProdId($prodId)
    {
        $sellPriceModel = SellPrice::model()->findByAttributes(array('model_id' => $prodId, 'is_active' => 1));
        if ($sellPriceModel != '')
            return $sellPriceModel->sell_price;
        else
            return 0;
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->add_time = new CDbExpression('NOW()');
            $this->add_by = Yii::app()->user->getId();
        } else {
            $this->update_time = new CDbExpression('NOW()');
            $this->update_by = Yii::app()->user->getId();
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->is_active == self::ACTIVE) {
            self::model()->updateAll(array('is_active' => self::INACTIVE), 'id!=:id AND model_id=:model_id', array(':id' => $this->id, ':model_id' => $this->model_id));
        }
        return parent::afterSave();
    }

    public function statusColor($status)
    {
        if ($status == self::ACTIVE) {
            echo "<span class='greenColor'>ACTIVE</b></span>";
        } else {
            echo "<span class='redColor'>INACTIVE</b></span>";
        }
    }

    public function activeSellPrice($modelId)
    {
        $data = self::model()->findByAttributes(array('model_id' => $modelId, 'is_active' => self::ACTIVE));
        if ($data) {
            $resultVar = array();
            $var = $data->sell_price;

            $array = array(
                "." => "<span class='pictNumDot'>.</span>",
                "1" => "<span class='pictNum1'></span>",
                "2" => "<span class='pictNum2'></span>",
                "3" => "<span class='pictNum3'></span>",
                "4" => "<span class='pictNum4'></span>",
                "5" => "<span class='pictNum5'></span>",
                "6" => "<span class='pictNum6'></span>",
                "7" => "<span class='pictNum7'></span>",
                "8" => "<span class='pictNum8'></span>",
                "9" => "<span class='pictNum9'></span>",
                "0" => "<span class='pictNum0'></span>");
            foreach ($array as $key => $value):
                $var = str_replace("$key", "$value", $var);
            endforeach;

            echo $var;
        } else
            echo "<div class='flash-error'>No active price!</div>";
    }

    public function activeSellPricePictRepOnly($sell_price)
    {
        $resultVar = array();
        $var = $sell_price;

        $array = array(
            "." => "<span class='pictNumDot'>.</span>",
            "1" => "<span class='pictNum1'></span>",
            "2" => "<span class='pictNum2'></span>",
            "3" => "<span class='pictNum3'></span>",
            "4" => "<span class='pictNum4'></span>",
            "5" => "<span class='pictNum5'></span>",
            "6" => "<span class='pictNum6'></span>",
            "7" => "<span class='pictNum7'></span>",
            "8" => "<span class='pictNum8'></span>",
            "9" => "<span class='pictNum9'></span>",
            "0" => "<span class='pictNum0'></span>");

        foreach ($array as $key => $value):
            $var = str_replace("$key", "$value", $var);
        endforeach;

        echo $var;
    }

    public function lubricants($sell_price)
    {
        $resultVar = array();
        $var = $sell_price;

        $array = array(
            "." => ".",
            "1" => "L",
            "2" => "U",
            "3" => "B",
            "4" => "R",
            "5" => "I",
            "6" => "C",
            "7" => "A",
            "8" => "N",
            "9" => "T",
            "0" => "S");

        foreach ($array as $key => $value):
            $var = str_replace("$key", "$value", $var);
        endforeach;

        return $var;
    }

    public function activeSellPriceOnly($modelId)
    {
        $data = self::model()->findByAttributes(array('model_id' => $modelId, 'is_active' => self::ACTIVE));
        if ($data) {
            return $data->sell_price;
        }
    }

    public function activeInfos($modelId)
    {
        $data = self::model()->findByAttributes(array('model_id' => $modelId, 'is_active' => self::ACTIVE));
        return $data;
    }

    public function activeSellPriceAndDiscount($modelId)
    {
        $data = self::model()->findByAttributes(array('model_id' => $modelId, 'is_active' => self::ACTIVE));
        if ($data) {
            echo $data->sell_price . "(" . $data->discount . "%)";
        } else {
            echo "<font style='color: red; font-weight: bold;'>No active price/discount!</font>";
        }
    }

    public function activeSellPriceWithDiscountAndIdealWarnQty($modelId)
    {
        $data = self::model()->findByAttributes(array('model_id' => $modelId, 'is_active' => self::ACTIVE));
        $activeSellPrice = 0;
        $activeDiscount = 0;
        $activeIdealQty = 0;
        $activeWarnQty = 0;
        if ($data) {
            $activeSellPrice = $data->sell_price;
            $activeDiscount = $data->discount;
            $activeIdealQty = $data->ideal_qty;
            $activeWarnQty = $data->warn_qty;
        }

        echo "Price: " . $activeSellPrice . ", Discount: " . $activeDiscount . "%<br>Ideal Qty: " . $activeIdealQty . ", Warn Qty: " . $activeWarnQty;
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
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('sell_price', $this->sell_price);
        $criteria->compare('discount', $this->discount);
        $criteria->compare('ideal_qty', $this->ideal_qty);
        $criteria->compare('warn_qty', $this->warn_qty);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('is_active', $this->is_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public function getSellPriceByModelId($model_id)
    {
        $sellPrice = 0;
        $sql = "SELECT * FROM sell_price where model_id = {$model_id} order by id desc limit 1";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        if ($data)
            $sellPrice = $data['sell_price'];
        else
            $sellPrice = 0;
        return $sellPrice;
    }

}
