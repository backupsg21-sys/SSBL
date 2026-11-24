<?php

class YourCompany extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return YourCompany the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    const STANDARD = 1;
    const BELLOW_STANDARD = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'your_company';
    }

    const ACTIVE = 1;
    const INACTIVE = 2;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_name, location, road, contact, business_type, business_process, registration_nature, registration_criteria, company_vat_type', 'required'),
            array('is_active, business_type, business_process, registration_nature, registration_criteria, company_vat_type', 'numerical', 'integerOnly' => true),
            array('company_name, location, email, web, house', 'length', 'max' => 50),
            array('road, contact, vat_regi_no, tin', 'length', 'max' => 40),
            array('email', 'email'),
            array('web', 'url', 'defaultScheme' => 'http'),
            array('opening_vat, opening_sd, previous_month_vat, previous_month_sd, company_sales_vat', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, company_name, location, road, house, contact, email, web, is_active, vat_regi_no, tin, registration_nature, registration_criteria, business_type, business_process, company_vat_type', 'safe', 'on' => 'search'),
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
            'company_name' => 'Company Name',
            'location' => 'Location',
            'road' => 'Road',
            'house' => 'House',
            'contact' => 'Contact',
            'email' => 'Email',
            'web' => 'Web',
            'vat_regi_no' => 'BIN',
            'tin' => 'TIN',
            'opening_vat' => '18.6(CA) Opening VAT',
            'opening_sd' => '18.6(CA) Opening SD',
            'previous_month_vat' => 'Prev Month VAT',
            'previous_month_sd' => 'Prev Month SD',
            'registration_nature' => 'Registration Nature',
            'registration_criteria' => 'Registration Criteria',
            'is_active' => 'Is Active',
            'business_type' => 'Business Type',
            'business_process' => 'Business Process',
            'company_vat_type' => 'VAT Payment Type',
            'company_sales_vat' => 'Company Sales VAT(%)',
        );
    }

    public function afterSave()
    {
        if ($this->is_active == self::ACTIVE) {
            self::model()->updateAll(array('is_active' => self::INACTIVE), 'id!=:id', array(':id' => $this->id));
        }
        return parent::afterSave();
    }

    public function activeInfo()
    {
        $data = self::model()->findByAttributes(array('is_active' => self::ACTIVE));
        if ($data)
            return $data;
    }

    public function statusColor($status)
    {
        if ($status == self::ACTIVE) {
            echo "<span class='greenColor'>ACTIVE</b></span>";
        } else {
            echo "<span class='redColor'>INACTIVE</b></span>";
        }
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
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('road', $this->road, true);
        $criteria->compare('house', $this->house, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('web', $this->web, true);
        $criteria->compare('company_vat_type', $this->company_vat_type);
        $criteria->compare('is_active', $this->is_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public function companyVatType()
    {
        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
        if (!empty($yourCompanyInfo))
            return $yourCompanyInfo->company_vat_type;
        else
            return '';
    }

    public function companyInfo()
    {
        $data = [];
        $data['company_name'] = "";
        $data['address'] = "";
        $data['contact'] = "";
        $data['email'] = "";
        $data['web'] = "";
        $str = '';
        $yourCompanyInfo = YourCompany::model()->findByAttributes(array('is_active' => YourCompany::ACTIVE,));
        if ($yourCompanyInfo) {
            $data['company_name'] = $yourCompanyInfo->company_name;
            if ($yourCompanyInfo->house)
                $data['address'] .= $yourCompanyInfo->house . ', ';
            if ($yourCompanyInfo->road)
                $data['address'] .= $yourCompanyInfo->road . ', ';
            if ($yourCompanyInfo->location)
                $data['address'] .= $yourCompanyInfo->location;

            $data['contact'] = $yourCompanyInfo->contact;
            $data['email'] = $yourCompanyInfo->email;
            $data['web'] = $yourCompanyInfo->web;
            $data['vat_regi_no'] = $yourCompanyInfo->vat_regi_no;
        }
        return $data;
    }

    public static function activeCompanyName()
    {
        $data = self::model()->findByAttributes(array('is_active' => self::ACTIVE));
        if ($data)
            return $data->company_name;
    }

}