<?php

class Stores extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Stores the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    const FG_STORE = 1;
    const RM_STORE = 2;
    const CHEMICAL_STORE = 3;
    const OTHER_RM_STORE = 4;
    const MACHINE_PARTS_STORE = 5;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'stores';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('store_name', 'required'),
            array('store_name, incharge_name', 'length', 'max' => 255),
            array('location', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, store_name, safe', 'safe', 'on' => 'search'),
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

    public function nameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->store_name;
    }

    public function addressOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->location;
    }

    public function dropdownStore($fieldname = 'store_name', $selectedvalue = 0)
    {

        $data = self::model()->findAll(array('order' => $fieldname . ' ASC'));
        $list = "<option value=\"0\">Select</option>";
        if ($data) {
            foreach ($data as $onerow) {
                $id = $onerow->id;
                $store_name = stripslashes(trim($onerow->$fieldname));

                $selected = '';
                if ($selectedvalue == $id) {
                    $selected = ' selected';
                }
                $list .= "<option value=\"$id\">$store_name</option>";
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
            'store_name' => 'Store/ Depot Name',
            'location' => 'Address',
            'incharge_name' => 'Incharge Name',
        );
    }

    public function storeName($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->store_name;
    }

    public function preFormattedAddr($addr)
    {
        echo "<pre>" . $addr . "</pre>";
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
        $criteria->compare('store_name', $this->store_name, true);
        $criteria->compare('location', $this->location, true);

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

}