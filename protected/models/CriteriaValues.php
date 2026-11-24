<?php

/**
 * This is the model class for table "criteria_values".
 *
 * The followings are the available columns in table 'criteria_values':
 * @property integer $id
 * @property integer $criteria_id
 * @property string $label
 * @property double $value
 * @property string $remarks
 *
 * The followings are the available model relations:
 * @property Criterias $criteria
 */
class CriteriaValues extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return CriteriaValues the static model class
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
        return 'criteria_values';
    }

    const COLOR = 15;
    const SIZE = 16;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('color_or_size, value', 'required'),
            array('color_or_size', 'numerical', 'integerOnly' => true),
            array('value', 'length', 'max' => 255),
            array('remarks', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, color_or_size, value, remarks', 'safe', 'on' => 'search'),
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
            'color_or_size' => 'Color/ Size',
            'value' => 'Value',
            'remarks' => 'Remarks',
        );
    }

    public function findAllSizeNames()
    {
        $condition = 'color_or_size=' . self::SIZE . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        return CHtml::listData($data, 'id', 'value');
    }

    public function findAllColorNames()
    {
        $condition = 'color_or_size=' . self::COLOR . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        return CHtml::listData($data, 'id', 'value');
    }

    public function findNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->value;
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
        $criteria->compare('color_or_size', $this->color_or_size);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('remarks', $this->remarks, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'color_or_size, id DESC',
            ),
        ));
    }

}