<?php

class PaymentReceipt extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return PaymentReceipt the static model class
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
        return 'payment_receipt';
    }

    public $totalAmount;
    const CASH = 42;
    const CHECK = 43;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('supplier_id, amount, date, order_no', 'required'),
            array('year, supplier_id', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
            array('date', 'safe'),
            array('month', 'length', 'max' => 2),
            array('order_no', 'length', 'max' => 255),
            array('id, supplier_id, amount, date, month, year, order_no', 'safe', 'on' => 'search'),
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
            'supplier' => array(self::BELONGS_TO, 'Suppliers', 'supplier_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Payment Receipt No',
            'supplier_id' => 'Supplier',
            'amount' => 'Amount',
            'date' => 'Date',
            'remarks' => 'Remarks',
            'amount_type' => 'Payment Type',
            'bank_id' => 'Bank',
            'branch_id' => 'Branch',
            'account_id' => 'Account No',
            'check_no' => 'Check No',
        );
    }

    public function paymentReceiptInfoOfThisDateSupplier($date, $supplierId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "date='" . $date . "' AND supplier_id=" . $supplierId;
        $data = self::model()->findAll($criteria);
        if ($data) {
            return $data;
        }
    }

    public function paymentReceiptInfoOfThisDateSupplierOpening($startDate, $supplierId)
    {
        $dramount = 0;
        $criteria = new CDbCriteria();
        $criteria->select = "sum(amount) as totalAmount";
        $criteria->condition = "supplier_id=" . $supplierId . " AND date < '" . $startDate . "'";
        $data2 = self::model()->findAll($criteria);
        if ($data2) {
            $dramount = end($data2)->totalAmount;
        }

        return $dramount;
    }

    public function totalPaymentReceiptOfThisSupplier($supplierId)
    {
        $condition = "supplier_id=" . $supplierId;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        $totalAmount = 0;
        if ($data) {
            foreach ($data as $d):
                $totalAmount = $d->amount + $totalAmount;
            endforeach;
        }
        return $totalAmount;
    }

    public function totalPaymentReceiptOfThisOrderNo($orderNo)
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

    public function totalPaymentReceiptOfThisTimeRange($startDate, $endDate)
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

    public function totalPaymentReceiptOfThisTimeRangeTotal()
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

    public function beforeSave()
    {
        $this->month = substr($this->date, 5, 2);
        $this->year = substr($this->date, 0, 4);

        return parent::beforeSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        if (trim($this->supplier_id) != "") {
            $criteria->join .= " INNER JOIN suppliers ON t.supplier_id = suppliers.id and suppliers.company_name like '%" . trim($this->supplier_id) . "%'";
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('month', $this->month, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('order_no', $this->order_no, true);

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