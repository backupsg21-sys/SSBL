<?php

class Suppliers extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Suppliers the static model class
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
        return 'suppliers';
    }

    const REGISTERED = 1;
    const UNREGISTERED = 2;
    const TURNOVER = 3;
    public $totalPaymentReceipt;
    public $totalPurchaseOrder;
    public $startDate;
    public $endDate;
    public $month;
    public $year;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, supplier_type_id, business_type_id', 'required'),
            array('supplier_type_id, business_type_id, bank_id', 'numerical', 'integerOnly' => true),
            array('opening_amount', 'numerical'),
            array('name, phone_no, code, pin_number, nationality, mobile_no, bin, nid, bank_ac_name, bank_ac_no', 'length', 'max' => 255),
            array('email, web_address', 'length', 'max' => 50),
            array('address, description, ca_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, address, phone_no, email, web_address, business_type_id, code, pin_number, nationality, mobile_no, description, bin, nid, bank_id, bank_ac_name, bank_ac_no, opening_amount', 'safe', 'on' => 'search'),
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
            'payReceiptCustomer' => array(self::HAS_MANY, 'PaymentReceipt', 'supplier_id'),
            'contactPersonSupplier' => array(self::HAS_MANY, 'SupplierContactPersons', 'company_id'),
        );
    }

    public function beforeSave()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->name = strtoupper($this->name);
        if ($this->isNewRecord) {
            $thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
            $model = new ChartOfAc();
            $model->code = $thisCodeNo;
            $model->title = strtoupper($this->name);
            $model->root = ChartOfAc::SUPPLIERS;
            $model->account_type = 1;
            $model->validate();
            $model->save();
            $this->ca_id = $model->id;

            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->getId();
        } else {
            $model = ChartOfAc::model()->findByPk($this->ca_id);
            if ($model) {
                $model->title = strtoupper($this->name);
                $model->save();
            }

            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->getId();
        }
        return parent::beforeSave();
    }

    public function supplierName($id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "name";
        $data = self::model()->findByPk($id, $criteria);
        if ($data)
            return $data->name;
    }

    public function supplierBin($id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "bin";
        $data = self::model()->findByPk($id, $criteria);
        if ($data)
            return $data->bin;
    }

    public function supplierAllInfo($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'phone_no' => 'Phone No',
            'email' => 'Email',
            'supplier_type_id' => 'Supplier Type',
            'web_address' => 'Web Address',
            'business_type_id' => 'Business Type',
            'code' => 'Code',
            'pin_number' => 'Pin Number',
            'nationality' => 'Nationality',
            'mobile_no' => 'Mobile No',
            'description' => 'Description',
            'bin' => 'BIN/ NID',
            'nid' => 'NID',
            'bank_id' => 'Bank',
            'bank_ac_name' => 'Bank Ac Name',
            'bank_ac_no' => 'Bank Ac No',
            'opening_amount' => 'Opening Amount',
            'ca_id' => 'CA ID',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone_no', $this->phone_no, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('supplier_type_id', $this->supplier_type_id, true);
        $criteria->compare('web_address', $this->web_address, true);
        $criteria->compare('business_type_id', $this->business_type_id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('pin_number', $this->pin_number, true);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('mobile_no', $this->mobile_no, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bin', $this->bin, true);
        $criteria->compare('nid', $this->nid, true);
        $criteria->compare('bank_id', $this->bank_id);
        $criteria->compare('bank_ac_name', $this->bank_ac_name, true);
        $criteria->compare('bank_ac_no', $this->bank_ac_no, true);
        $criteria->compare('opening_amount', $this->opening_amount);

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
