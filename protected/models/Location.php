<?php

/**
 * This is the model class for table "location".
 *
 * The followings are the available columns in table 'location':
 * @property integer $id
 * @property string $title
 * @property string $details
 */
class Location extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Location the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    const DEFAULT_LOCATION = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'location';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {

        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('stores_id, title', 'required'),
            array('stores_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('details', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('stores_id, id, title, details', 'safe', 'on' => 'search'),
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

    public function dropdownLocation($fieldname = 'title', $selectedvalue = 0)
    {

        $data = self::model()->findAll(array('order' => $fieldname . ' ASC'));
        $list = "<option value=\"0\">Select</option>";
        if ($data) {
            foreach ($data as $onerow) {
                $id = $onerow->id;
                $label = stripslashes(trim($onerow->$fieldname));
                $selected = '';
                if ($selectedvalue == $id) {
                    $selected = ' selected';
                }
                $list .= "<option value=\"$id\">$label</option>";
            }
        }

        return $list;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'stores_id' => 'Store',
            'title' => 'Location Name',
            'details' => 'Details',
        );
    }

    public function nameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->title;
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
        $criteria->compare('stores_id', $this->stores_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('details', $this->details, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
            'sort' => array(
                'defaultOrder' => 'title ASC',
            ),
        ));
    }

}