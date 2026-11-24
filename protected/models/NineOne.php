<?php

class NineOne extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return NineOne the static model class
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
        return 'nine_one';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('doc_type, last_status, is_trader_wholesaler, created_by, updated_by, refund_status', 'numerical', 'integerOnly' => true),
            array('closing_vat, closing_sd, vat_refund_claim, sd_refund_claim, e_remain_vat, e_remain_sd, decrease_vat, decrease_sd', 'numerical'),
            array('vat_month', 'length', 'max' => 50),
            array('year', 'length', 'max' => 4),
            array('submit_date, created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, submit_date, vat_month, year, doc_type, last_status, is_trader_wholesaler, closing_vat, closing_sd, created_by, created_datetime, updated_by, updated_datetime', 'safe', 'on' => 'search'),
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
            $this->updated_daetime = new CDbExpression('NOW()');
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
            'submit_date' => 'Submit Date',
            'vat_date' => 'VAT Date',
            'vat_month' => 'VAT Month',
            'year' => 'Year',
            'doc_type' => 'Doc Type',
            'last_status' => 'Last Status',
            'is_trader_wholesaler' => 'Trader Or WholeSaler',
            'closing_vat' => 'Closing Vat',
            'closing_sd' => 'Closing Sd',
            'e_remain_vat' => 'Remaining VAT',
            'e_remain_sd' => 'Remaining SD',
            'decrease_vat' => 'Decrease VAT',
            'decrease_sd' => 'Decrease SD',
            'vat_refund_claim' => 'VAT Refund',
            'sd_refund_claim' => 'SD Refund',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'updated_by' => 'Updated By',
            'updated_datetime' => 'Updated Datetime',
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
        $criteria->compare('submit_date', $this->submit_date, true);
        $criteria->compare('vat_month', $this->vat_month, true);
        $criteria->compare('year', $this->year, true);
        $criteria->compare('doc_type', $this->doc_type);
        $criteria->compare('last_status', $this->last_status);
        $criteria->compare('closing_vat', $this->closing_vat);
        $criteria->compare('closing_sd', $this->closing_sd);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('is_trader_wholesaler',$this->is_trader_wholesaler);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}