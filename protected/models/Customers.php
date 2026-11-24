<?php

class Customers extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Customers the static model class
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
        return 'customers';
    }

    public $activeCreditLimit;
    public $totalMoneyReceipt;
    public $totalSalesOrder;
    public $balance;
    public $month;
    public $year;
    public $startDate;
    public $endDate;
    public $company_name_address;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_name, business_type_id', 'required'),
            array('business_type_id, bank_id', 'numerical', 'integerOnly' => true),
            array('opening_amount', 'numerical'),
            array('company_name, mobile_no, code, pin_number, nationality, bin, nid, bank_ac_name, bank_ac_no', 'length', 'max' => 255),
            array('company_contact_no, company_fax', 'length', 'max' => 50),
            array('company_email, company_web', 'length', 'max' => 50),
            array('company_address, description, ca_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, company_name, company_address, company_contact_no, company_fax, company_email, company_web, opening_amount, mobile_no, business_type_id, code, pin_number, nationality, description, bin, nid, bank_id, bank_ac_name, bank_ac_no', 'safe', 'on' => 'search'),
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
            //'monyeReceiptCustomer' => array(self::HAS_MANY, 'MoneyReceipt', 'customer_id'),
            'creditLimitCustomer' => array(self::HAS_MANY, 'CreditLimit', 'customer_id'),
            'contactPersonCustomer' => array(self::HAS_MANY, 'CustomerContactPersons', 'company_id'),
        );
    }

    public function beforeSave()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->company_name = strtoupper($this->company_name);
        if ($this->isNewRecord) {
            $thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
            $model = new ChartOfAc();
            $model->code = $thisCodeNo;
            $model->title = strtoupper($this->company_name);
            $model->root = ChartOfAc::CUSTOMERS;
            $model->account_type = 1;
            $model->validate();
            $model->save();
            $this->ca_id = $model->id;

            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->getId();
        } else {
            $model = ChartOfAc::model()->findByPk($this->ca_id);
            if ($model) {
                $model->title = strtoupper($this->company_name);
                $model->save();
            }
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
            'company_name' => 'Company Name',
            'company_address' => 'Company Address',
            'company_contact_no' => 'Company Contact No',
            'company_fax' => 'Company Fax',
            'company_email' => 'Company Email',
            'company_web' => 'Company Web',
            'opening_amount' => 'Opening Amount',
            'mobile_no' => 'Mobile No',
            'business_type_id' => 'Business Type',
            'code' => 'Code',
            'pin_number' => 'Pin Number',
            'nationality' => 'Nationality',
            'description' => 'Description',
            'bin' => 'BIN/ NID',
            'nid' => 'NID',
            'bank_id' => 'Bank Name',
            'bank_ac_name' => 'Bank Ac Name',
            'bank_ac_no' => 'Bank Ac No',
            'ca_id' => 'CA ID',
        );
    }

    public function partyLedgerOrBalanceAmount($customerId)
    {

        $partyLedger = 0;
        $sMR = 0;
        $sSO = 0;
        $sMR = MoneyReceipt::model()->totalMoneyReceiptOfThisCustomer($customerId);
        $sSO = SellDelivery::model()->totalDeliveredQtyOfThisCustomer($customerId);

        $partyLedger = $sMR - $sSO;

        return $partyLedger;
    }

    public function getCustomerBalanace($customerId, $soNo)
    {
        $sql = "SELECT customer_id,so_no,date_of_sell FROM sell_items  WHERE full_paid = 1 AND customer_id = $customerId AND so_no != $soNo GROUP BY so_no ORDER BY so_no DESC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data1 = $command->queryAll();
        $gtAmount = 0;
        if ($data1) {
            foreach ($data1 as $salesItem) {
                $totalBill = SellItems::model()->totalSalePriceBySo($salesItem['so_no']);
                $criteria = new CDbCriteria();
                $criteria->select = "sum(amount) as sumPayment, sum(discount) as sumDiscount";
                $criteria->condition = "order_no=" . $salesItem['so_no'];
                $dataMoney = MoneyReceipt::model()->findAll($criteria);
                if ($dataMoney) {
                    $totalPaidAmount = floor(end($dataMoney)->sumPayment);
                    $totalDiscountAmount = floor(end($dataMoney)->sumDiscount);
                }
                $noType = DiscountTab::SALE_ORDER;

                $sql = "SELECT * FROM discount_tab where no = {$salesItem['so_no']} AND no_type = '{$noType}' order by id DESC";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $data = $command->queryRow();
                $discountTab = 0;
                if ($data['discount']) {
                    $discountTab = $data['discount'];
                }
                $dueAmounts = $totalBill - ($totalPaidAmount + $totalDiscountAmount + $discountTab);
                $totalDueAmount = number_format($dueAmounts);
                if ($dueAmounts > 0) {
                    $gtAmount += $dueAmounts;
                }
            }
        }
        return $gtAmount;
    }

    public function getCustomerLedgerClosingBalance($customerId)
    {//
        $customerInfo = Customers::model()->findByPk($customerId);
        $customerOpeningAmount = $customerInfo->opening_amount;

        $startDate = '2018-01-01';
        $endDate = date('Y-m-d'); // from 2018-01-01 to today.

        $soNosOpening = SellDelivery::model()->delivInfoOfThisDateCustomerOpening($startDate, $customerId);
        $mrInfoOpening = MoneyReceipt::model()->moneyReceiptInfoOfThisDateCustomerOpening($startDate, $customerId);


        $date1 = date_create($startDate);
        $date2 = date_create($endDate);
        $diff = date_diff($date1, $date2);
        $timeInverval = $diff->format("%a") + 1;


        $customerOpeningAmount;
        $sl = 1;
        $totalDebit = 0;
        $totalCredit = 0;
        for ($i = 0; $i < $timeInverval; $i++) {
            $date = date('Y-m-d', strtotime($startDate) + strtotime("+" . $i . " day", 0));
            $soNos = SellDelivery::model()->delivInfoOfThisDateCustomer($date, $customerId);
            $mrInfo = MoneyReceipt::model()->moneyReceiptInfoOfThisDateCustomer($date, $customerId);

            $credit = 0;
            $debit = 0;
            if ($soNos) {
                foreach ($soNos as $sns):
                    $soNo = $sns->so_no; // echo   
                    //echo "->";
                    $sellAmount = SellDelivery::model()->totalDeliveredQtyOfThisDateSo($soNo, $date);
                    $debit = $sellAmount;
                    //$tdr+=$debit;
                    $totalDebit = $totalDebit + $debit;
                endforeach;
            }
            $tcr = 0;
            if ($mrInfo) {
                foreach ($mrInfo as $mri):
                    $credit = $mri->amount;
                    $tcr += $credit;
                    $totalCredit = $totalCredit + $credit + $mri->discount;

                endforeach;
            }
        }
        //echo "||";

        $closingDebit = $totalDebit + $soNosOpening + $customerOpeningAmount;
        $closingCredit = $totalCredit + $mrInfoOpening;
        $closingBalance = $closingDebit - $closingCredit;

        return abs($closingBalance);
    }

//

    public function customerName($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->company_name;
        } else {
            return '';
        }
    }

    public function customerBin($id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = "bin";
        $data = self::model()->findByPk($id, $criteria);
        if ($data)
            return $data->bin;
    }

    public function customerAddress($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->company_address;
    }

    public function customerAllInfo($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data;
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
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('company_address', $this->company_address, true);
        $criteria->compare('company_contact_no', $this->company_contact_no, true);
        $criteria->compare('company_fax', $this->company_fax, true);
        $criteria->compare('company_email', $this->company_email, true);
        $criteria->compare('company_web', $this->company_web, true);
        $criteria->compare('opening_amount', $this->opening_amount);
        $criteria->compare('mobile_no', $this->mobile_no, true);
        $criteria->compare('business_type_id', $this->business_type_id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('pin_number', $this->pin_number, true);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bin', $this->bin, true);
        $criteria->compare('nid', $this->nid, true);
        $criteria->compare('bank_id', $this->bank_id);
        $criteria->compare('bank_ac_name', $this->bank_ac_name, true);
        $criteria->compare('bank_ac_no', $this->bank_ac_no, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
