<?php

/**
 * This is the model class for table "purchase_return".
 *
 * The followings are the available columns in table 'purchase_return':
 * @property integer $id
 * @property integer $purchase_id
 * @property integer $purchase_details_id
 * @property integer $po_no
 * @property integer $sl_no
 * @property integer $model_id
 * @property integer $parts_service
 * @property integer $unit_id
 * @property double $qty
 * @property double $unit_price
 * @property double $vat_new
 * @property double $tax_new
 * @property double $total_new
 * @property double $net_total_new
 * @property string $reason
 * @property integer $return_user_id
 * @property string $return_date
 */
class PurchaseReturn extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return PurchaseReturn the static model class
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
        return 'purchase_return';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('purchase_id, purchase_details_id, po_no, sl_no, model_id, parts_service, unit_id, print_status, return_user_id', 'numerical', 'integerOnly' => true),
            array('qty, unit_price, vat_new, tax_new, total_new, net_total_new', 'numerical'),
            array('return_challan_no, reason, return_date, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, purchase_id, purchase_details_id, po_no, sl_no, model_id, parts_service, unit_id, qty, unit_price, vat_new, tax_new, total_new, net_total_new, reason, return_user_id, return_challan_no, return_date', 'safe', 'on' => 'search'),
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
            'purchase_id' => 'Purchase',
            'purchase_details_id' => 'Purchase Details',
            'po_no' => 'Po No',
            'sl_no' => 'SL No',
            'model_id' => 'Model',
            'parts_service' => 'Parts Service',
            'unit_id' => 'Unit',
            'qty' => 'Qty',
            'print_status' => 'Print Status',
            'unit_price' => 'Unit Price',
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
        $criteria->compare('purchase_id', $this->purchase_id);
        $criteria->compare('purchase_details_id', $this->purchase_details_id);
        $criteria->compare('po_no', $this->po_no);
        $criteria->compare('sl_no', $this->sl_no);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('parts_service', $this->parts_service);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('unit_price', $this->unit_price);
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