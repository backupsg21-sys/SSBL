<?php

class MoneyReceipt extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return MoneyReceipt the static model class
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
        return 'money_receipt';
    }

    public $totalAmount;
    public $sumPayment;
    public $sumDiscount;
    public $maxSINo;
    public $sumOfPaidAmount;
    public $sumOfDiscount;
    public $remaining_amount;
    public $lot;
    public $receive_type_lot;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, amount, date', 'required'),
            array('year, customer_id, collection_type, received_type, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('amount, discount', 'numerical'),
            array('order_no', 'length', 'max' => 255),
            array('month', 'length', 'max' => 2),
            //array('order_no', 'isExist'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, order_no, mr_no,  customer_id, amount, discount, collection_type, received_type, date, month, year, created_by, updated_by, created_datetime', 'safe', 'on' => 'search'),
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


    public function isExist()
    {
        $data = SellItems::model()->findByAttributes(array('so_no' => $this->order_no));

        if (!$data) {
            $this->addError('order_no', 'This order no does not exist /No delivery was executed!');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'customer' => array(self::BELONGS_TO, 'Customers', 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => 'Customer',
            'order_no' => 'SO No',
            'amount' => 'Receive Amount',
            'discount' => 'Discount Amount',
            'date' => 'Receive Date',
            'month' => 'Month',
            'year' => 'Year',
        );
    }

    public function totalMoneyReceiptOfThisCustomer($customerId)
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $criteria->condition = "customer_id=" . $customerId;
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
    }

    public function totalMrAmountOfThisSo($so_no, $customer_id = NULL)
    {

        $totalAmount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as sumOfPaidAmount, sum(discount) as sumOfDiscount";
        if ($so_no == 'ob') {
            $criteria->addColumnCondition(array("order_no" => $so_no, 'customer_id' => $customer_id), "AND", "AND");
        } else {
            $criteria->addColumnCondition(array("order_no" => $so_no), "AND", "AND");
        }

        $data = self::model()->findAll($criteria);
        if ($data) {
            $totalAmount = end($data)->sumOfPaidAmount + end($data)->sumOfDiscount;
        }

        return $totalAmount;
    }

    public function moneyReceiptInfoOfThisDateCustomer($date, $customerId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "date='" . $date . "' AND customer_id=" . $customerId;
        $data = self::model()->findAll($criteria);
        if ($data) {
            return $data;
        }
    }

    public function moneyReceiptInfoOfThisDateCustomerOpening($startDate, $customerId)
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $criteria->condition = "customer_id=" . $customerId . " AND date < '" . $startDate . "'";
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
    }

    public function totalMoneyReceiptOfThisOrderNo($orderNo)
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $criteria->condition = "order_no='" . $orderNo . "'";
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
    }

    public function totalMoneyReceiptOfThisTimeRange($startDate, $endDate)
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $criteria->addBetweenCondition('date', $startDate, $endDate, 'AND');
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
    }

    public function totalMoneyReceiptOfThisTimeRangeTotal()
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
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
        $criteria->compare('order_no', $this->order_no, true);
        $criteria->compare('mr_no', $this->mr_no, true);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('month', $this->month, true);
        $criteria->compare('year', $this->year);

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