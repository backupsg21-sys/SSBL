<?php

/**
 * This is the model class for table "correction".
 *
 * The followings are the available columns in table 'correction':
 * @property integer $id
 * @property string $sl_no
 * @property integer $type
 * @property string $purchase_id
 * @property string $sales_id
 * @property integer $production_id
 * @property integer $transfer_id
 * @property string $info_details
 * @property string $date
 * @property string $created_datetime
 * @property integer $created_by
 * @property string $updated_datetime
 * @property integer $updated_by
 */
class Correction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Correction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'correction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('sl_no', 'required'),
			array('type, production_id, transfer_id, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('sl_no', 'length', 'max'=>255),
			array('purchase_id, sales_id', 'length', 'max'=>11),
			array('info_details, date, created_datetime, updated_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sl_no, type, purchase_id, sales_id, production_id, transfer_id, info_details, date, created_datetime, created_by, updated_datetime, updated_by', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sl_no' => 'Sl No',
			'type' => 'Type',
			'purchase_id' => 'Purchase',
			'sales_id' => 'Sales',
			'production_id' => 'Production',
			'transfer_id' => 'Transfer',
			'info_details' => 'Info Details',
			'date' => 'Date',
			'created_datetime' => 'Created Datetime',
			'created_by' => 'Created By',
			'updated_datetime' => 'Updated Datetime',
			'updated_by' => 'Updated By',
		);
	}
    public function beforeSave()
    {
        date_default_timezone_set('Asia/Dhaka');
        if ($this->isNewRecord) {
            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->id;
        } else {
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->id;
        }
        return parent::beforeSave();
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('sl_no',$this->sl_no,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('purchase_id',$this->purchase_id,true);
		$criteria->compare('sales_id',$this->sales_id,true);
		$criteria->compare('production_id',$this->production_id);
		$criteria->compare('transfer_id',$this->transfer_id);
		$criteria->compare('info_details',$this->info_details,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('created_datetime',$this->created_datetime,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_datetime',$this->updated_datetime,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}