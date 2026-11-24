<?php

class StockTransferHistory extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return StockTransferHistory the static model class
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
        return 'stock_transfer_history';
    }

    const PENDING = 5;
    const RECEIVED = 6;

    public $availableStock;
    public $inventory_id;
    public $maxSlNo;
    public $stock_qty;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sent_from_store_id, sent_qty, received_to_store_id, sent_date', 'required', 'on' => 'insert'),
            array('sent_qty, received_qty, availableStock', 'numerical'),
            array('availableStock', 'isExceedStockQty'),
            array('received_qty, received_date', 'required', 'on' => 'receive_scenario'),
            array('received_to_store_id', 'isSendingSameStore'),
            array('received_qty', 'isExceedReceive', 'on' => 'receive_scenario'),
            array('sl_no, sent_from_store_id, received_to_store_id, receive_location_id, is_received, item_id, brand_id, model_id, sent_by_id, received_by_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 255),
            array('sent_date, received_date, remarks', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sl_no, sent_from_store_id, sent_qty, sent_date, received_to_store_id, receive_location_id, received_qty, received_date, is_received, item_id, brand_id, model_id, sent_by_id, received_by_id, remarks', 'safe', 'on' => 'search'),
        );
    }

    public function isExceedStockQty()
    {

        if (($this->sent_qty) > ($this->availableStock)) {
            $this->addError('sent_qty', 'Sending Qty exceeds Stock Qty!');
        }
    }

    public function isSendingSameStore()
    {

        if (($this->sent_from_store_id) === ($this->received_to_store_id)) {
            $this->addError('received_to_store_id', 'Sending to the same store!');
        }
    }

    public function isExceedReceive()
    {

        if (($this->received_qty) > ($this->sent_qty)) {
            $this->addError('received_qty', 'Received Qty exceeds Sent Qty!');
        }
        if ($this->received_date < $this->sent_date) {
            $this->addError('received_date', 'Can not receive. Sent date is a future date!');
        }
    }

    public function beforeSave()
    {

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
            'sl_no' => 'SL',
            'sent_from_store_id' => 'From (Store / Depot)',
            'sent_qty' => 'Sent Qty',
            'sent_date' => 'Sent Date',
            'received_to_store_id' => 'To (Store / Depot)',
            'receive_location_id' => 'Store Location',
            'received_qty' => 'Received Qty',
            'received_date' => 'Received Date',
            'is_received' => 'Is Received',
            'item_id' => 'Quality',
            'brand_id' => 'Goods Type',
            'model_id' => 'Goods Name',
            'code' => 'Code',
            'sent_by_id' => 'Sent By',
            'received_by_id' => 'Received By',
            'remarks' => 'Remarks',
        );
    }

    public function statusColor($status)
    {
        if ($status == self::RECEIVED) {
            echo "<span class='greenColor'>RECEIVED</b></span>";
        } else {
            echo "<span class='redColor'>PENDING</b></span>";
        }
    }

    public function imgRepresentationOfThisStatus($approvedStatus)
    {
        if ($approvedStatus == self::RECEIVED) {
            echo "<img src='" . Yii::app()->theme->baseUrl . "/images/approved.ico'/>";
        } else {
            echo "<img src='" . Yii::app()->theme->baseUrl . "/images/pending.ico'/>";
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function searchReceive()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('is_received' => self::PENDING), "AND", "AND");
        if (Rights::getAuthorizer()->isSuperuser(Yii::app()->user->getId()) === true) {

        } else {
            $assignedStores = UserStore::model()->forSearchCondition();
            $criteria->addInCondition('received_to_store_id', $assignedStores);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('sent_from_store_id', $this->sent_from_store_id);
        $criteria->compare('sent_qty', $this->sent_qty);
        $criteria->compare('sent_date', $this->sent_date, true);
        $criteria->compare('received_qty', $this->received_qty);
        $criteria->compare('received_date', $this->received_date, true);
        $criteria->compare('received_to_store_id', $this->received_to_store_id);
        $criteria->compare('receive_location_id', $this->receive_location_id);
        $criteria->compare('is_received', $this->is_received);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('code', $this->code);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public function search()
    {
        $criteria = new CDbCriteria();

        if ($this->model_id != "") {
            $criteria->join .= " INNER JOIN prod_models ON (t.model_id=prod_models.id AND prod_models.model_name like '%" . $this->model_id . "%')";
        }

        if ($this->code != "") {
            $criteria->join .= " INNER JOIN prod_models ON (t.model_id=prod_models.id AND prod_models.code like '%" . $this->code . "%')";
        }

        if ($this->receive_location_id != "") {
            $criteria->join .= " INNER JOIN location ON (t.receive_location_id=location.id AND location.title like '%" . $this->receive_location_id . "%')";
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('sent_from_store_id', $this->sent_from_store_id);
        $criteria->compare('sent_qty', $this->sent_qty);
        $criteria->compare('sent_date', $this->sent_date, true);
        $criteria->compare('received_to_store_id', $this->received_to_store_id);
        $criteria->compare('received_qty', $this->received_qty);
        $criteria->compare('received_date', $this->received_date, true);
        $criteria->compare('is_received', $this->is_received);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('brand_id', $this->brand_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}