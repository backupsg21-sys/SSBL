<?php

/**
 * This is the model class for table "sro_infos".
 *
 * The followings are the available columns in table 'sro_infos':
 * @property integer $id
 * @property string $sro_name
 * @property string $hs_code
 * @property string $code
 * @property double $cd
 * @property double $rd
 * @property double $sd
 * @property double $vat
 * @property double $ait
 * @property double $atv
 * @property double $tti
 * @property integer $unit_id
 * @property integer $vat_calculation_method
 * @property double $exd
 * @property integer $created_by
 * @property string $created_datetime
 * @property integer $updated_by
 * @property string $updated_datetime
 */
class SroInfos extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return SroInfos the static model class
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
        return 'sro_infos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('unit_id, vat_calculation_method, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('cd, rd, sd, vat, ait, atv, tti, exd', 'numerical'),
            array('sro_name, hs_code, code', 'length', 'max' => 255),
            array('created_datetime, updated_datetime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sro_name, hs_code, code, cd, rd, sd, vat, ait, atv, tti, unit_id, vat_calculation_method, exd, created_by, created_datetime, updated_by, updated_datetime', 'safe', 'on' => 'search'),
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
            'sro_name' => 'Sro Name',
            'hs_code' => 'Hs Code',
            'code' => 'Code',
            'cd' => 'Cd',
            'rd' => 'Rd',
            'sd' => 'Sd',
            'vat' => 'Vat',
            'ait' => 'Ait',
            'atv' => 'Atv',
            'tti' => 'Tti',
            'unit_id' => 'Unit',
            'vat_calculation_method' => 'Vat Calculation Method',
            'exd' => 'Exd',
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
        $criteria->compare('sro_name', $this->sro_name, true);
        $criteria->compare('hs_code', $this->hs_code, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('cd', $this->cd);
        $criteria->compare('rd', $this->rd);
        $criteria->compare('sd', $this->sd);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('ait', $this->ait);
        $criteria->compare('atv', $this->atv);
        $criteria->compare('tti', $this->tti);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('vat_calculation_method', $this->vat_calculation_method);
        $criteria->compare('exd', $this->exd);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_datetime', $this->created_datetime, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('updated_datetime', $this->updated_datetime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}