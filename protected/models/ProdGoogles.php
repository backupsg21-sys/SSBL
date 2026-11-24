<?php

/**
 * This is the model class for table "prod_googles".
 *
 * The followings are the available columns in table 'prod_googles':
 * @property integer $id
 * @property integer $item_id
 * @property integer $brand_id
 * @property string $model_name
 * @property string $product_image
 * @property string $foreign_name
 * @property string $code
 * @property integer $unit_id
 * @property integer $manufacturer_id
 * @property integer $country_id
 * @property integer $size_id
 * @property integer $color_id
 * @property integer $min_order_qty
 * @property string $measurement
 * @property integer $measurement_unit_id
 * @property double $weight
 * @property integer $weight_unit_id
 * @property string $features
 * @property integer $pmodel
 * @property integer $pbrand
 * @property double $warranty
 * @property integer $vatable
 * @property integer $rebatable
 * @property double $purchase_discount
 * @property double $base_tp
 * @property double $mrp
 * @property double $gp_percent
 * @property integer $vat_calculation_method
 * @property double $cd
 * @property double $sd
 * @property integer $sub_vat_id
 * @property double $vat
 * @property double $ait
 * @property double $rd
 * @property double $atv
 * @property double $tti
 * @property double $exd
 * @property string $ca_id
 */
class ProdGoogles extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return ProdGoogles the static model class
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
        return 'prod_googles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_id, brand_id, unit_id, manufacturer_id, country_id, size_id, color_id, min_order_qty, measurement_unit_id, weight_unit_id, pmodel, pbrand, vatable, rebatable, vat_calculation_method, sub_vat_id', 'numerical', 'integerOnly' => true),
            array('weight, warranty, purchase_discount, base_tp, mrp, gp_percent, cd, sd, vat, ait, rd, atv, tti, exd', 'numerical'),
            array('model_name, product_image, code, measurement, ca_id', 'length', 'max' => 255),
            array('foreign_name, features', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, item_id, brand_id, model_name, product_image, foreign_name, code, unit_id, manufacturer_id, country_id, size_id, color_id, min_order_qty, measurement, measurement_unit_id, weight, weight_unit_id, features, pmodel, pbrand, warranty, vatable, rebatable, purchase_discount, base_tp, mrp, gp_percent, vat_calculation_method, cd, sd, sub_vat_id, vat, ait, rd, atv, tti, exd, ca_id', 'safe', 'on' => 'search'),
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
            'item_id' => 'Item',
            'brand_id' => 'Brand',
            'model_name' => 'Model Name',
            'product_image' => 'Product Image',
            'foreign_name' => 'Foreign Name',
            'code' => 'Code',
            'unit_id' => 'Unit',
            'manufacturer_id' => 'Manufacturer',
            'country_id' => 'Country',
            'size_id' => 'Size',
            'color_id' => 'Color',
            'min_order_qty' => 'Min Order Qty',
            'measurement' => 'Measurement',
            'measurement_unit_id' => 'Measurement Unit',
            'weight' => 'Weight',
            'weight_unit_id' => 'Weight Unit',
            'features' => 'Features',
            'pmodel' => 'Pmodel',
            'pbrand' => 'Pbrand',
            'warranty' => 'Warranty',
            'vatable' => 'Vatable',
            'rebatable' => 'Rebatable',
            'purchase_discount' => 'Purchase Discount',
            'base_tp' => 'Base Tp',
            'mrp' => 'Mrp',
            'gp_percent' => 'Gp Percent',
            'vat_calculation_method' => 'Vat Calculation Method',
            'cd' => 'Cd',
            'sd' => 'Sd',
            'sub_vat_id' => 'Sub Vat',
            'vat' => 'Vat',
            'ait' => 'Ait',
            'rd' => 'Rd',
            'atv' => 'Atv',
            'tti' => 'Tti',
            'exd' => 'Exd',
            'ca_id' => 'Ca',
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
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('model_name', $this->model_name, true);
        $criteria->compare('product_image', $this->product_image, true);
        $criteria->compare('foreign_name', $this->foreign_name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('manufacturer_id', $this->manufacturer_id);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('size_id', $this->size_id);
        $criteria->compare('color_id', $this->color_id);
        $criteria->compare('min_order_qty', $this->min_order_qty);
        $criteria->compare('measurement', $this->measurement, true);
        $criteria->compare('measurement_unit_id', $this->measurement_unit_id);
        $criteria->compare('weight', $this->weight);
        $criteria->compare('weight_unit_id', $this->weight_unit_id);
        $criteria->compare('features', $this->features, true);
        $criteria->compare('pmodel', $this->pmodel);
        $criteria->compare('pbrand', $this->pbrand);
        $criteria->compare('warranty', $this->warranty);
        $criteria->compare('vatable', $this->vatable);
        $criteria->compare('rebatable', $this->rebatable);
        $criteria->compare('purchase_discount', $this->purchase_discount);
        $criteria->compare('base_tp', $this->base_tp);
        $criteria->compare('mrp', $this->mrp);
        $criteria->compare('gp_percent', $this->gp_percent);
        $criteria->compare('vat_calculation_method', $this->vat_calculation_method);
        $criteria->compare('cd', $this->cd);
        $criteria->compare('sd', $this->sd);
        $criteria->compare('sub_vat_id', $this->sub_vat_id);
        $criteria->compare('vat', $this->vat);
        $criteria->compare('ait', $this->ait);
        $criteria->compare('rd', $this->rd);
        $criteria->compare('atv', $this->atv);
        $criteria->compare('tti', $this->tti);
        $criteria->compare('exd', $this->exd);
        $criteria->compare('ca_id', $this->ca_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}