<?php

class VatPayment extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return VatPayment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public $maxSlNo;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'vat_payment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('amount, purpose, payment_method, payment_date', 'required'), //customer_name,
            array('year, month, sl_no, purpose, payment_method, bank_id, district_id, payment_code_id, account_code_id, created_by, updated_by, vds_payment_id', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
            array('chalan_no,tr_challan_no, branch, account_name, account_number, cheque_number, bank_remarks, customer_name, address, note,payment_month', 'length', 'max' => 255),
            array('paid_by, payment_date, tr_challan_date,cheque_date, description, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, payment_date, year, month,tr_challan_date,tr_challan_no, purpose, paid_by, chalan_no, amount,payment_month, payment_method, bank_id, district_id, branch, payment_code_id, account_code_id, account_name, account_number, cheque_date, cheque_number, bank_remarks, customer_name, address, note, description, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'payment_date' => 'Payment Date',
            'year' => 'Year',
            'month' => 'Month',
            'sl_no' => 'SL No',
            'purpose' => 'Purpose',
            'paid_by' => 'Paid By',
            'chalan_no' => 'TR Challan No',
            'tr_challan_no' => 'TR Challan No',
            'tr_challan_date' => 'TR Challan Date',
            'amount' => 'VAT Amount',
            'payment_method' => 'Payment Method',
            'bank_id' => 'Bank',
            'branch' => 'Branch',
            'district_id' => 'District',
            'account_name' => 'Account Name',
            'account_number' => 'Account Number',
            'cheque_date' => 'Cheque Date',
            'cheque_number' => 'Cheque Number',
            'bank_remarks' => 'Remarks',
            'payment_code_id' => 'Payment Code',
            'account_code_id' => 'Account Code',
            'customer_name' => 'Customer Name',
            'address' => 'Address',
            'note' => 'Payment Purpose',
            'description' => 'Description',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
            'payment_month' => 'Payment Month',
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
        $criteria->compare('payment_date', $this->payment_date, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('month', $this->month);
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('purpose', $this->purpose);
        $criteria->compare('chalan_no', $this->chalan_no, true);
        $criteria->compare('tr_challan_no', $this->tr_challan_no, true);
        $criteria->compare('tr_challan_date', $this->tr_challan_date, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('payment_method', $this->payment_method);
        $criteria->compare('bank_id', $this->bank_id);
        $criteria->compare('branch', $this->branch, true);
        $criteria->compare('account_name', $this->account_name, true);
        $criteria->compare('account_number', $this->account_number, true);
        $criteria->compare('cheque_date', $this->cheque_date, true);
        $criteria->compare('cheque_number', $this->cheque_number, true);
        $criteria->compare('bank_remarks', $this->bank_remarks, true);
        $criteria->compare('customer_name', $this->customer_name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('payment_month', $this->payment_month);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function totalPayment($yearMonth)
    {
        $thisMonth = date("Y-m", strtotime($yearMonth));
        $criteria = new CDbCriteria();
        $criteria->select = "SUM(amount) as amount";
        $criteria->condition = "payment_date like '%$thisMonth%' ";
        $criteria->order = 'payment_date ASC';
        $model = self::model()->find($criteria);

        return $model['amount'] > 0 ? (int)$model['amount'] : 0;
    }

}
