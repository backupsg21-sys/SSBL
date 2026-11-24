<?php

/**
 * This is the model class for table "ports".
 *
 * The followings are the available columns in table 'ports':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $status
 */
class TreasuryPurpose extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Ports the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public $maxSlNo;

    public function tableName()
    {
        return 'treasury_purpose';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, nine_one_part', 'required'),
            array('status, nine_one_part, code_key', 'numerical', 'integerOnly' => true),
            array('name, code', 'length', 'max' => 255),
            array('name', 'unique'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, code, status', 'safe', 'on' => 'search'),
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'code_key' => 'Code Key',
            'nine_one_part' => 'Part',
            'name' => 'Name',
            'code' => 'Code',
            'status' => 'Status',
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
        $criteria->compare('nine_one_part', $this->nine_one_part, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('status', $this->status);
        $criteria->order = "nine_one_part DESC";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            )
        ));

    }

    public static function nameOfAdjustmentPurpose($id)
    {
        $data = self::model()->findByAttributes(array('code_key' => $id));
        if ($data)
            return $data->name;
    }

    public function codeOfAdjustmentPurpose($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

}
