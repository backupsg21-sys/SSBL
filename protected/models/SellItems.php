<?php

class SellItems extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return SellItems the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    const ESTABLISHED = 44;

    public $maxSONo;
    public $model_id;
    public $sales_qty;
    public $parts_service;
    public $return_challan_no;
    public $return_date;
    public $reason;


    public $start_date;
    public $end_date;
    public $approve_type;
    public $business_type_id;
    public $report_type;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sell_items';
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
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('so_no, parts_service, product_type, sales_type, export_type, payment_method, bank_id, customer_id, print_status, ship_by, dest_country_id, sent_from_store_id, sales_by_id, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('mushok_no, vehicle_id, challan_no, lc_no, vehicle_type, vehicle_no', 'length', 'max' => 255),
            array('total_sd, total_tax, total, total_vds, grand_total', 'numerical'),
            array('year, sales_date, created_datetime, updated_datetime', 'safe'),
            array('id, so_no, po_no, vehicle_id, product_type, delivery_address, customer_id, sales_date, sales_by_id, total_sd, total_tax, total, approve_count, total_vds, grand_total, challan_date, lc_date, sent_from_store_id, created_datetime, created_by, updated_datetime, updated_by, vehicle_type, vehicle_no', 'safe', 'on' => 'search'),
        );
    }

    public function approveStatus($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            if ($data->approve_count == 0) {
                echo "<span style='color:#e8c158;'>Pending</span>";
            } elseif ($data->approve_count == 1) {
                echo "<span style='color:#34A1FD;'>Processing</span>";
            } elseif ($data->approve_count == 2) {
                echo "<span style='color:green;'>Approved</span>";
            }
        }
    }

    public function updateInfo($sells_id)
    {
        $approveCount = SellItems::model()->findByPk($sells_id)->approve_count;
        if ($approveCount < 2) {
            echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . '/images/update.png'), array('/sellItems/update', 'id' => $sells_id), array('target' => '_blank'));
        } else {
            echo CHtml::image(Yii::app()->theme->baseUrl . '/images/lock.png');
        }
    }

    public function previewMushok($sells_id)
    {
        $data = SellItems::model()->findByPk($sells_id);
        $approveCount = $data['approve_count'];
        if ($approveCount > 1) {
            if ($data['print_status'] < 1) {
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . '/images/stats-view.png'), array('/sellItems/outsideMushokSixThree', 'id' => $sells_id), array('target' => '_blank', 'title' => 'View Mushak'));
            }
        }

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
            'so_no' => 'SO No',
            'mushok_no' => 'Mushak No',
            'year' => 'Year',
            'vehicle_id' => 'Vehicle',
            'parts_service' => 'Parts /Service',
            'product_type' => 'Product Type',
            'sales_type' => 'Sales Type',
            'export_type' => 'Export Type',
            'payment_method' => 'Payment Method',
            'bank_id' => 'Bank Name',
            'customer_id' => 'Customer',
            'print_status' => 'Print Status',
            'sales_date' => 'Sales Date',
            'sales_by_id' => 'Sales By',
            'total_sd' => 'Total SD',
            'total_tax' => 'Total Tax',
            'total' => 'Total',
            'total_vds' => 'Total Vds',
            'grand_total' => 'Grand Total',
            'challan_no' => 'Challan No',
            'challan_date' => 'Challan Date',
            'lc_no' => 'LC No',
            'lc_date' => 'LC Date',
            'ship_by' => 'Ship By',
            'dest_country_id' => 'Country',
            'sent_from_store_id' => 'Sent from (Store/Depot)',
            'created_datetime' => 'Created Datetime',
            'created_by' => 'Created By',
            'updated_datetime' => 'Updated Datetime',
            'updated_by' => 'Updated By',
            'vehicle_type' => 'Vehicle Type',
            'vehicle_no' => 'Vehicle No',
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
        $criteria->compare('id', $this->id, true);
        $criteria->compare('so_no', $this->so_no);
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('vehicle_id', $this->vehicle_id);
        $criteria->compare('product_type', $this->product_type);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('approve_count', $this->approve_count);
        $criteria->compare('sales_date', $this->sales_date, true);
        $criteria->compare('sales_by_id', $this->sales_by_id);
        $criteria->compare('total_sd', $this->total_sd);
        $criteria->compare('total_tax', $this->total_tax);
        $criteria->compare('total', $this->total);
        $criteria->compare('total_vds', $this->total_vds);
        $criteria->compare('grand_total', $this->grand_total);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('delivery_address', $this->delivery_address, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('vehicle_type', $this->vehicle_type, true);
        $criteria->compare('vehicle_no', $this->vehicle_no, true);

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

    public function totalSalesOfMonth($yearMonth)
    {
        $thisMonth = date("Y-m", strtotime($yearMonth));
        $criteria = new CDbCriteria();
        $criteria->select = "SUM(grand_total) as grand_total";
        $criteria->condition = "sales_date like '%$thisMonth%' AND approve_count=2";
        $criteria->order = 'sales_date ASC';
        $model = self::model()->find($criteria);

        return $model['grand_total'] > 0 ? (int)$model['grand_total'] : 0;
    }

    public function totalVatPayableOfMonth($yearMonth)
    {
        $thisMonth = date("Y-m", strtotime($yearMonth));
        $criteria = new CDbCriteria();
        $criteria->select = "SUM(total_tax) as total_tax";
        $criteria->condition = "sales_date like '%$thisMonth%' ";
        $criteria->order = 'sales_date ASC';
        $model = self::model()->find($criteria);

        return $model['total_tax'] > 0 ? (int)$model['total_tax'] : 0;
    }

    public function challanNoBySalesID($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->challan_no;
    }

    public function mushokNoBySalesID($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->mushok_no;
        }
    }
    public function firstSalesDate($model_id){

        $criteria = new CDbCriteria();
        $criteria->select = "t.sales_date";
        $criteria->join = "INNER JOIN sales_details sd ON t.id=sd.sales_id";
        $criteria->condition = "sd.model_id=$model_id";
        $criteria->order = 't.sales_date ASC';
        $data = self::model()->find($criteria);
        $date = '';
        if($data){
            $date = $data->sales_date;
        }
        return $date;
    }
    public function salePoNo($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            return $data->so_no;
        }
    }


}
