<?php

class VdsPayment extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return VdsPayment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public $maxSlNo;
    public $parts_service;
    public $chalan_no;
    public $challan_date;
    public $vds_id;
    public $vds_total;
    public $vds_remaining;
    public $maxMushokNo;
    public $customer_bin;
    public $customer_name;
    public $customer_address;
    public $payment_code_id;
    public $district_id;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'vds_payment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('po_no, payment_date, amount, tr_challan_no,tr_challan_date', 'required'),
            array('po_no, account_code_id, purchase_id, sales_id, vds_id, vds_type, sl_no, parts_service, supplier_id, customer_id, payment_method, year, month, bank_id, debit_account_id, credit_account_id, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
            array('tr_challan_no, tr_challan_date, chalan_no, mushok_no, purpose, branch, account_name, account_number, cheque_number, bank_remarks', 'length', 'max' => 255),
            array('mushok_no, challan_date, payment_date, certificate_no, certificate_date, cheque_date, description, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, po_no, purchase_id, sales_id, parts_service, mushok_no, payment_date, supplier_id, customer_id, tr_challan_no, tr_challan_date, chalan_no, payment_method, year, month, purpose, amount, bank_id, branch, account_name, account_number, cheque_date, cheque_number, bank_remarks, description, debit_account_id, credit_account_id, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
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
            'po_no' => 'PO No',
            'sl_no' => 'SL No',
            'purchase_id' => 'Purchase ID',
            'sales_id' => 'Sales ID',
            'payment_date' => 'Adjustment Date',
            'parts_service' => 'Goods/Service',
            'supplier_id' => 'Supplier',
            'customer_id' => 'Customer',
            'vds_id' => 'VDS ID',
            'vds_type' => 'VDS Type',
            'vds_total' => 'VDS Total',
            'vds_remaining' => 'VDS Remaining',
            'tr_challan_no' => 'TR Challan No',
            'tr_challan_date' => 'TR Challan Date',
            'account_code_id' => 'Account Code',
            'chalan_no' => 'Challan No',
            'challan_date' => 'Challan Date',
            'customer_bin' => 'Customer NID/BIN',
            'customer_name' => 'Customer Name',
            'challan_address' => 'Customer Address',
            'certificate_no' => 'Certificate No',
            'certificate_date' => 'Certificate Date',
            'payment_method' => 'Payment Method',
            'year' => 'Year',
            'month' => 'Month',
            'purpose' => 'Remarks',
            'amount' => 'VDS Amount',
            'bank_id' => 'Bank',
            'branch' => 'Branch',
            'district_id' => 'District',
            'account_name' => 'Account Name',
            'account_number' => 'Account Number',
            'cheque_date' => 'Cheque Date',
            'cheque_number' => 'Cheque Number',
            'bank_remarks' => 'Bank Remarks',
            'description' => 'Description',
            'debit_account_id' => 'Debit Account',
            'credit_account_id' => 'Credit Account',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
            'parts_service' => 'Goods / Service',
            'account_code_id' => 'Account Code',
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

        $criteria->condition = 'vds_type = 1';
        $criteria->compare('id', $this->id);
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('mushok_no', $this->mushok_no);
        $criteria->compare('payment_date', $this->payment_date, true);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('chalan_no', $this->chalan_no, true);
        $criteria->compare('tr_challan_no', $this->tr_challan_no, true);
        $criteria->compare('tr_challan_date', $this->tr_challan_date, true);
        $criteria->compare('tr_challan_date', $this->tr_challan_date, true);
        $criteria->compare('payment_method', $this->payment_method);
        $criteria->compare('certificate_no', $this->certificate_no);
        $criteria->compare('certificate_date', $this->certificate_date, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('month', $this->month);
        $criteria->compare('purpose', $this->purpose, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('bank_id', $this->bank_id);
        $criteria->compare('branch', $this->branch, true);
        $criteria->compare('account_name', $this->account_name, true);
        $criteria->compare('account_number', $this->account_number, true);
        $criteria->compare('cheque_date', $this->cheque_date, true);
        $criteria->compare('cheque_number', $this->cheque_number, true);
        $criteria->compare('bank_remarks', $this->bank_remarks, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('debit_account_id', $this->debit_account_id);
        $criteria->compare('credit_account_id', $this->credit_account_id);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('account_code_id', $this->account_code_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    // VDS Payment List
    public function searchAdminReceive()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->condition = 'vds_type = 2';
        $criteria->compare('id', $this->id);
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('mushok_no', $this->mushok_no);
        $criteria->compare('payment_date', $this->payment_date, true);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('chalan_no', $this->chalan_no, true);
        $criteria->compare('tr_challan_no', $this->tr_challan_no, true);
        $criteria->compare('tr_challan_date', $this->tr_challan_date, true);
        $criteria->compare('payment_method', $this->payment_method);
        $criteria->compare('year', $this->year);
        $criteria->compare('month', $this->month);
        $criteria->compare('purpose', $this->purpose, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('bank_id', $this->bank_id);
        $criteria->compare('branch', $this->branch, true);
        $criteria->compare('account_name', $this->account_name, true);
        $criteria->compare('account_number', $this->account_number, true);
        $criteria->compare('cheque_date', $this->cheque_date, true);
        $criteria->compare('cheque_number', $this->cheque_number, true);
        $criteria->compare('bank_remarks', $this->bank_remarks, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('debit_account_id', $this->debit_account_id);
        $criteria->compare('credit_account_id', $this->credit_account_id);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('account_code_id', $this->account_code_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}
