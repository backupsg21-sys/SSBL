<?php

class HsCode extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return HsCode the static model class
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
        return 'hs_code';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('hs_code, country_id, cd, ait', 'required'),
            array('country_id, cd, sd, vat, ait, rd, atv, tti, exd', 'numerical'),
            array('hs_code', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, hs_code, description, country_id, cd, sd, vat, ait, rd, atv, tti, exd', 'safe', 'on' => 'search'),
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
            'hs_code' => 'Hs Code',
            'description' => 'Description',
            'country_id' => 'Country',
            'cd' => 'CD',
            'sd' => 'SD',
            'vat' => 'VAT',
            'ait' => 'AIT',
            'rd' => 'RD',
            'atv' => 'ATV',
            'tti' => 'TTI',
            'exd' => 'EXD',
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
        $criteria->compare('hs_code', $this->hs_code, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('cd', $this->cd);
        $criteria->compare('sd', $this->sd);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('ait', $this->ait);
        $criteria->compare('rd', $this->rd);
        $criteria->compare('atv', $this->atv);
        $criteria->compare('tti', $this->tti);
        $criteria->compare('exd', $this->exd);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}