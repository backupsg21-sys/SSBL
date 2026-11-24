<?php

class Production extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Production the static model class
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
        return 'production';
    }

    public $maxSlNo;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('finish_product_id, issue_date, qty', 'required'),
            array('finish_product_id, fg_unit_id, created_by, updated_by, max_sl_no, contractual_status, customer_id', 'numerical', 'integerOnly' => true),
            array('qty, total_price, adjustment_value', 'numerical'),
            array('issue_date, created_datetime, updated_datetime, sl_no', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, issue_date, finish_product_id, fg_unit_id, qty, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on' => 'search'),
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

    public function getSellPrice($modelId)
    {
        $sql = "SELECT MAX(total_price) as sellPrice FROM production where finish_product_id = $modelId";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        if ($data) {
            return $data['sellPrice'];
        } else {
            return 0;
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'issue_date' => 'Production Date',
            'finish_product_id' => 'Finished Good',
            'fg_unit_id' => 'FG Unit',
            'customer_id' => 'Customer Name',
            'contractual_status' => 'Contractual Status',
            'qty' => 'Production Qty',
            'adjustment_value' => 'Adjustment Value',
            'total_price' => 'Total Price',
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
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->select = " t.* ";
        if (trim($this->finish_product_id) != "") {
            $criteria->join = " INNER JOIN prod_models pm on t.finish_product_id = pm.id ";
            $criteria2 = new CDbCriteria;
            $criteria2->compare('pm.model_name', trim($this->finish_product_id), true);
            $criteria2->compare('pm.code', trim($this->finish_product_id), true, "OR");
            $criteria->mergeWith($criteria2);
        }
        $criteria->compare('t.id', $this->id);
        $criteria->compare('issue_date', $this->issue_date, true);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
        ));

    }

}
