<?php

/**
 * This is the model class for table "units".
 *
 * The followings are the available columns in table 'units':
 * @property integer $id
 * @property integer $unit_type
 * @property string $label
 * @property double $value
 */
class Units extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return Units the static model class
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
        return 'units';
    }

    const CURR_UNIT = 7;
    const QTY_UNIT = 8;
    const MEASUREMENT = 17;
    const WEIGHT = 18;

    const MASS = 1;
    const VOLUME = 2;
    const AREA = 3;
    const OTHERS = 4;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('label, category_id', 'required'),
            array('unit_type', 'numerical', 'integerOnly' => true),
            array('value', 'numerical'),
            array('label', 'length', 'max' => 50),
            array('remarks', 'safe'),
            array('label', 'unique', 'caseSensitive' => FALSE),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, unit_type, label, value', 'safe', 'on' => 'search'),
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
            'unit_type' => 'Unit Type',
            'label' => 'Unit Name',
            'value' => 'Value',
            'remarks' => 'Remarks',
            'category_id' => 'Category',
        );
    }

    public function unitValue($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->value;
    }

    public function unitLabel($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->label;
    }

    public function findAllCurrUnitNames()
    {
        $condition = 'unit_type=' . self::CURR_UNIT . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');

        if ($data)
            return CHtml::listData($data, 'id', 'label');
    }

    public function dropdownUnits($fieldname, $selectedvalue = '')
    {
        if ($fieldname != '') {
            $models = Units::model()->findAll(array('order' => 'label ASC'));
            $list = CHtml::listData($models, 'id', 'label');
            return CHtml::dropDownList($fieldname, $selectedvalue, $list, array('empty' => '(Select Unit)'));
        } else {
            return '';
        }
    }

    public function dropdownOptions($selectedvalue = '')
    {
        $models = Units::model()->findAll(array('order' => 'label ASC'));
        $list = CHtml::listData($models, 'id', 'label');
        $returnstr = '';
        if ($list) {
            foreach ($list as $value => $label) {
                $selected = '';
                if ($value == $selectedvalue) {
                    $selected = ' selected="selected"';
                }
                $returnstr .= "<option$selected value=\"$value\">$label</option>";
            }
        }
        return $returnstr;
    }

    public function findAllQtyUnitNames()
    {
        $condition = 'unit_type=' . self::QTY_UNIT . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        if ($data)
            return CHtml::listData($data, 'id', 'label');
    }

    public function findAllMeasurementUnitNames()
    {
        $condition = 'unit_type=' . self::MEASUREMENT . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');

        if ($data)
            return CHtml::listData($data, 'id', 'label');
    }

    public function findAllWeightUnitNames()
    {
        $condition = 'unit_type=' . self::WEIGHT . ' ORDER BY id DESC';
        $data = self::model()->findAll(array('condition' => $condition,), 'id');

        if ($data)
            return CHtml::listData($data, 'id', 'label');
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
        $criteria->compare('unit_type', $this->unit_type);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('value', $this->value);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('remarks', $this->remarks, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'category_id, label ASC',
            ),
        ));
    }

    public function getUnitIdByUnitName($unit_name)
    {
        $unit_name = (!empty($unit_name)) ? trim($unit_name) : "0";
        $unit = Units::model()->findByAttributes(['label' => $unit_name]);
        if ($unit) {
            return $unit->id;
        } else {
            return NULL;
        }
    }


    public function getUnitListByProductCategory($product_id)
    {
        $prodModel = ProdModels::model()->findByPk($product_id);
        if ($prodModel) {
            $unit = Units::model()->findByPk($prodModel->unit_id);
            if ($unit)
                return CHtml::listData(Units::model()->findAll(array("condition" => "category_id = '$unit->category_id' ", 'order' => 'label ASC')), 'id', 'label');
            else
                return [];
        } else {
            return [];
        }
    }

    public function getUnitListByUnitCategory($id)
    {
        $unit = Units::model()->findByPk($id);
        if ($unit)
            return CHtml::listData(Units::model()->findAll(array("condition" => "category_id = '$unit->category_id' ", 'order' => 'label ASC')), 'id', 'label');
        else
            return [];

    }

    /**
     * the value array that are relaited to base unit
     * here base unit is gram
     */
    public $massBaseTo = array(
        'ton' => '1000000',
        'kg' => '1000',
        'gm' => '1',
        'mg' => '0.001',
        'microgram' => '0.000001',
        'nanogram' => '0.000000001'
    );

    /**
     * Base unit is gram
     * */
    public $massBaseFrom = array(
        'ton' => '0.000001',
        'kg' => '0.001',
        'gm' => '1',
        'mg' => '1000',
        'microgram' => '1000000',
        'nanogram' => '1000000000'
    );

    /**
     * the value array that are relaited to base unit
     * here base unit is Liter
     */
    public $volumeBaseTo = array(
        'ltr' => 1,
        'ml' => 0.001,
        'microliter' => 0.000001,
        'nanoliter' => 0.000000001
    );

    /**
     * Base unit is Liter
     * */
    public $volumeBaseFrom = array(
        'ltr' => 1,
        'ml' => 1000,
        'microliter' => 1000000,
        'nanoliter' => 1000000000
    );

    /**
     * @param float $value double value, that will be converted to
     * @param integer $from string, the unit will be conterted from
     * @param integer $to string the unit will be conterted to
     * @param integer $category_id
     * @return float
     * units: ton, kg, gm, mg, microgram, nanogram
     * Base unit is gram
     */
    public function ConvertUnit($value, $from, $to, $category_id)
    {

        if (!is_numeric($value)) return 0;
		
		// If From unit_id
        if( is_numeric($from) ){
            $unit = Units::model()->findByPk($from);
            $from = $unit->label;
        }
        // If To unit_id
        if( is_numeric($to) ){
            $unit = Units::model()->findByPk($to);
            $to = $unit->label;
        }

        $from = strtolower($from);
        $to = strtolower($to);
        //if base conversion unit is same then return the value without calculation
        if ($from == $to) {
            return $value;
        }else{
            return 0;
        }

        $convertedValue = 0;


        if ($category_id == self::MASS) {
            // mass
            if (!isset($this->massBaseTo[$from]) && !isset($this->massBaseFrom[$to])) return 0;

            $baseValue = $from != "" ? $this->massBaseTo[$from] * $value : 0;
            $convertedValue = $to != "" ? $this->massBaseFrom[$to] * $baseValue : 0;

        } else if ($category_id == self::VOLUME) {
            // Volume
            if (!isset($this->volumeBaseTo[$from]) && !isset($this->volumeBaseTo[$to])) return 0;

            $baseValue = $from != "" ? $this->volumeBaseTo[$from] * $value : 0;
            $convertedValue = $to != "" ? $this->volumeBaseFrom[$to] * $baseValue : 0;

        } else {
            // Others
            $convertedValue = $value;
        }

        return $convertedValue;

    }

}