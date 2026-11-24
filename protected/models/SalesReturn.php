<?php

/**
 * This is the model class for table "sales_return".
 *
 * The followings are the available columns in table 'sales_return':
 * @property integer $id
 * @property integer $sales_id
 * @property integer $sales_details_id
 * @property integer $model_id
 * @property double $qty
 * @property double $vat_new
 * @property double $tax_new
 * @property double $total_new
 * @property double $net_total_new
 * @property string $reason
 * @property integer $return_user_id
 * @property string $return_date
 */
class SalesReturn extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return SalesReturn the static model class
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
        return 'sales_return';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sales_id, sales_details_id, so_no, sl_no, model_id, parts_service, unit_id, print_status, return_user_id', 'numerical', 'integerOnly' => true),
            array('qty, unit_price, sd_new, vat_new, tax_new, total_new, net_total_new', 'numerical'),
            array('return_challan_no, reason, return_date, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sales_id, sales_details_id, so_no, model_id, qty, vat_new, tax_new, total_new, net_total_new, reason, return_user_id, return_challan_no, return_date', 'safe', 'on' => 'search'),
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
            'sales_id' => 'Sales',
            'sales_details_id' => 'Sales Details',
            'so_no' => 'SO No',
            'sl_no' => 'SL No',
            'model_id' => 'Model',
            'parts_service' => 'Parts/ Service',
            'unit_id' => 'Unit',
            'qty' => 'Qty',
            'unit_price' => 'Unit Price',
            'print_status' => 'Print Status',
            'vat_new' => 'Vat New',
            'tax_new' => 'Tax New',
            'total_new' => 'Total New',
            'net_total_new' => 'Net Total New',
            'reason' => 'Reason',
            'return_user_id' => 'Return User',
            'return_challan_no' => 'Return Challan No',
            'return_date' => 'Return Date',
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
        $criteria->order = "id DESC";
        $criteria->group = "sl_no";
        $criteria->compare('id', $this->id);
        $criteria->compare('sales_id', $this->sales_id);
        $criteria->compare('sales_details_id', $this->sales_details_id);
        $criteria->compare('so_no', $this->so_no);
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('vat_new', $this->vat_new);
        $criteria->compare('tax_new', $this->tax_new);
        $criteria->compare('total_new', $this->total_new);
        $criteria->compare('net_total_new', $this->net_total_new);
        $criteria->compare('reason', $this->reason, true);
        $criteria->compare('return_user_id', $this->return_user_id);
        $criteria->compare('return_date', $this->return_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}