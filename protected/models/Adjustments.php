<?php

class Adjustments extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'adjustments';
    }

    public $nine_one_part;
    public $treasury_purpose_code;
    public $maxSlNo;
    public $start_date;
    public $end_date;

    const PENDING = 1;
    const APPROVED = 2;
    const DENIED = 3;

    public function rules()
    {

        return array(
            array('amount, nine_one_part, treasury_purpose, payment_method, payment_date, effective_date', 'required'),
            array('sl_no, year, month, treasury_purpose, payment_method, bank_id, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('value, amount, is_approved', 'numerical'),
            array('challan_no, branch, account_name, account_number, cheque_number, bank_remarks, customer_name, address, note', 'length', 'max' => 255),
            array('payment_date, effective_date, cheque_date, description, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sl_no, is_approved, payment_date, year, month, treasury_purpose, challan_no, value, amount, payment_method, bank_id, branch, account_name, account_number, cheque_date, cheque_number, bank_remarks, customer_name, address, note, description, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
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
            $this->created_by = Yii::app()->user->id;
        } else {
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->id;
        }
        return parent::beforeSave();
    }

    public function statusValue($value)
    {
        if ($value == self::DENIED)
            $string = "Denied";
        elseif ($value == self::APPROVED)
            $string = "Approved";
        else
            $string = "Pending";
        return $string;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sl_no' => 'Sl No',
            'payment_date' => 'Payment Date',
			'effective_date' => 'Effective Date',
            'year' => 'Year',
            'month' => 'Month',
            'nine_one_part' => 'Part',
            'treasury_purpose' => 'Purpose',
            'challan_no' => 'Challan No',
            'value' => 'Value',
            'is_approved' => 'Approved?',
            'amount' => 'VAT Amount',
            'payment_method' => 'Payment Method',
            'bank_id' => 'Bank',
            'branch' => 'Branch',
            'account_name' => 'Account Name',
            'account_number' => 'Account Number',
            'cheque_date' => 'Cheque Date',
            'cheque_number' => 'Cheque Number',
            'bank_remarks' => 'Bank Remarks',
            'customer_name' => 'Customer Name',
            'address' => 'Customer Address',
            'note' => 'Note',
            'treasury_purpose_code' => 'Purpose Code',
            'description' => 'Description',
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
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('payment_date', $this->payment_date, true);
		$criteria->compare('effective_date', $this->effective_date, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('month', $this->month);
        $criteria->compare('treasury_purpose', $this->treasury_purpose);
        $criteria->compare('challan_no', $this->challan_no, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('value',$this->value);
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
        $criteria->compare('is_approved', $this->is_approved);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getSevenEightSubNoteAmount($start_date = NULL, $end_date = NULL, $code_key = NULL)
    {
        if ($start_date && $end_date && $code_key) {
            $sql = "SELECT SUM(amount) AS subamount FROM adjustments WHERE effective_date BETWEEN '$start_date' AND '$end_date' AND treasury_purpose = $code_key";
            $data = Yii::app()->db->createCommand($sql)->queryRow();
            if ($data)
                return ROUND($data['subamount']);
        }
    }

    public static function getSevenEightSubNoteCode($code_key = NULL)
    {
        if ($code_key) {
            $criteria = new CDbCriteria();
            $criteria->select = "code";
            $data = TreasuryPurpose::model()->findByAttributes(['code_key' => $code_key], $criteria);
            if ($data)
                return $data->code;
        }
    }


}