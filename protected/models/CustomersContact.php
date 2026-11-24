<?php

class CustomersContact extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return CustomersContact the static model class
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
        return 'customers_contact';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_name', 'required'),
            array('customer_name, contact_one, contact_two, contact_three, remarks, district', 'length', 'max' => 255),
            array('id, customer_name, contact_one, contact_two, contact_three, remarks', 'safe', 'on' => 'search'),
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
            'district' => 'District',
            'customer_name' => 'Name',
            'contact_one' => 'Contact One',
            'contact_two' => 'Contact Two',
            'contact_three' => 'Contact Three',
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
        $criteria->compare('customer_name', $this->customer_name, true);
        $criteria->compare('contact_one', $this->contact_one, true);
        $criteria->compare('contact_two', $this->contact_two, true);
        $criteria->compare('contact_three', $this->contact_three, true);
        $criteria->compare('district', $this->district, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}
