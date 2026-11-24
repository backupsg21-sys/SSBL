<?php

class Vds extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'vds';
    }

    const PURCHASE = 1;
    const SALES = 2;

    public $bin;
    public $ref_no;
    public $challan_no;
    public $challan_date;
    public $tr_challan_no;
    public $tr_challan_date;
    public $amount;
    public $purpose;
    public $vds_total;
    public $vds_remaining;
    public $payment_date;
    public $vds_payment;
    public $certificate_no;
    public $certificate_date;
    public $account_code_id;

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vds', 'required'),
            array('purchase_id, po_no, sl_no_po, vds_type, sales_id, so_no, parts_service, supplier_id, customer_id, is_paid, created_by, updated_by, approve_count', 'numerical', 'integerOnly' => true),
            array('vds', 'numerical'),
            array('issue_date, created_datetime, updated_datetime, bin, payment_challan_no, payment_challan_date, remarks', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, purchase_id, bin, po_no, vds, vds_type, sales_id, so_no, parts_service, supplier_id, customer_id, issue_date, is_paid, created_datetime, created_by, updated_datetime, updated_by, approve_count', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'purchase_id' => 'Purchase',
            'po_no' => 'PO No',
            'vds' => 'VDS Amount',
            'vds_total' => 'VDS Total',
            'vds_remaining' => 'VDS Remaining',
            'sales_id' => 'Sales',
            'so_no' => 'So No',
            'tr_challan_no' => 'TR Challan No',
            'tr_challan_date' => 'TR Challan Date',
            'parts_service' => 'Parts Service',
            'supplier_id' => 'Supplier',
            'customer_id' => 'Customer',
            'issue_date' => 'Issue Date',
            'purpose' => 'Remarks',
            'payment_challan_no' => 'Payment Challan No',
            'payment_challan_date' => 'Payment Challan Date',
            'remarks' => 'Remarks',
            'is_paid' => 'Due/Paid',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
        );
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

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('vds', $this->vds);
        $criteria->compare('vds_type', $this->vds_type);
        $criteria->compare('sales_id', $this->sales_id);
        $criteria->compare('so_no', $this->so_no);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('issue_date', $this->issue_date, true);
        $criteria->compare('is_paid', $this->is_paid);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchAdminPurchaseVds()
    {
        $criteria = new CDbCriteria;
        if ($this->bin != "") {
            $criteria->join = " INNER JOIN suppliers ON t.supplier_id = suppliers.id and suppliers.bin like '%" . $this->bin . "%'";
        }

        $criteria->condition = 'vds_type = 1 AND parts_service = 1 AND is_paid!=1 AND approve_count=2';
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('vds', $this->vds);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('issue_date', $this->issue_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'issue_date DESC, id DESC',
            ),
        ));
    }

    public function searchAdminServiceVds()
    {
        $criteria = new CDbCriteria;
        if ($this->bin != "") {
            $criteria->join = " INNER JOIN suppliers ON t.supplier_id = suppliers.id and suppliers.bin like '%" . $this->bin . "%'";
        }

        $criteria->condition = 'vds_type =1 AND parts_service = 2 AND is_paid!=1 AND approve_count=2';
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('vds', $this->vds);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('issue_date', $this->issue_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'issue_date DESC, id DESC',
            ),
        ));
    }

    public function searchAdminSalesVds()
    {
        $criteria = new CDbCriteria;
        if ($this->bin != "") {
            $criteria->join = " INNER JOIN customers ON t.customer_id = customers.id and customers.bin like '%" . $this->bin . "%'";
        }

        $criteria->condition = 'vds_type = 2 AND (is_paid is NULL OR is_paid < 1) AND approve_count=2';
        $criteria->compare('so_no', $this->so_no);
        $criteria->compare('vds', $this->vds);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('issue_date', $this->issue_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'issue_date DESC, id DESC',
            ),
        ));
    }

    public function totalVdsOfMonth($yearMonth)
    {
        $thisMonth = date("Y-m", strtotime($yearMonth));
        $criteria = new CDbCriteria();
        $criteria->select = "SUM(vds) as vds";
        $criteria->condition = "issue_date like '%$thisMonth%' ";
        $criteria->order = 'issue_date ASC';
        $model = self::model()->find($criteria);

        return $model['vds'] > 0 ? (int)$model['vds'] : 0;
    }

}
