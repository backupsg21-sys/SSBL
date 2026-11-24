<?php

class ProdModels extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return ProdModels the static model class
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
        return 'prod_models';
    }

    public $maxProdId;
    public $vehicle_brand;
    public $supported_models;
    public $product_file;
    public $year_models;
    public $activePrice;
    public $deviceFile;
    public $min_order_qty;
    public $qty;

    const STANDARD_RATE = 1;
    const REDUCING_METHOD = 2;
    const FIXED_AMOUNT = 3;
    const EXEMPTED = 4;
    const ZERO_RATE = 5;
    const MRP = 6;

    const  GOODS = 1;
    const  SERVICE = 2;

    const VAT_CALCULATION_METHOD_ARR = [
        self::STANDARD_RATE => "Standard Rate (%)",
        self::REDUCING_METHOD => "Reducing Method (%)",
        self::FIXED_AMOUNT => "Fixed Amount",
        self::EXEMPTED => "Exempted",
        self::ZERO_RATE => "0 Rate",
        self::MRP => "MRP",
    ];

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_id, brand_id, model_name, mrp,unit_id, base_tp, vat_calculation_method, sub_vat_id', 'required'),
            array('item_id, brand_id, color_id, size_id, unit_id, measurement_unit_id, weight_unit_id, manufacturer_id, country_id, pbrand, pmodel, vatable, rebatable, sub_vat_id', 'numerical', 'integerOnly' => true),
            array('model_name, measurement, code, min_order_qty, hs_code', 'length', 'max' => 255),
            array('weight, warranty, base_tp, mrp, gp_percent, cd, sd, vat, ait, rd, atv, tti, exd, vat_calculation_method, purchase_discount', 'numerical'),
            array('code', 'unique', 'caseSensitive' => FALSE),
            array('features, ca_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('vehicle_brand, unit_id, min_order_qty, hs_code, cd, sd, vat, ait, rd, atv, tti, exd, base_tp, purchase_discount, mrp, gp_percent, vat_calculation_method, vatable, supported_models, year_models, id, item_id, brand_id, model_name, measurement, measurement_unit_id, weight, weight_unit_id, manufacturer_id, country_id, pbrand, pmodel, features, warranty', 'safe', 'on' => 'search'),
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

    public function beforeSave()
    {
        date_default_timezone_set('Asia/Dhaka');

        $this->model_name = strtoupper($this->model_name);
        $prodBrand = ProdBrands::model()->findByPk($this->brand_id);
        if ($this->isNewRecord) {
            $thisCodeNo = VoucherNoFormateAc::model()->maxValOfThis('ChartOfAc', 'code', 'maxval');
            $model = new ChartOfAc();
            $model->code = $thisCodeNo;
            $model->title = strtoupper($this->model_name);
            $model->root = $prodBrand->ca_id;
            $model->account_type = 1;
            $model->validate();
            $model->save();
            $this->ca_id = $model->id;

            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->id;
        } else {
            $model = ChartOfAc::model()->findByPk($this->ca_id);
            if ($model) {
                $model->root = $prodBrand->ca_id;
                $model->account_type = 1;
                $model->title = strtoupper($this->model_name);
                $model->save();
            }
            $this->updated_datetime = new CDbExpression('NOW()');
            $this->updated_by = Yii::app()->user->id;
        }
        return parent::beforeSave();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Product ID',
            'qty' => 'Stock Qty',
            'item_id' => 'Product Category',
            'brand_id' => 'Product Sub-Category',
            'model_name' => 'Product Name',
            'code' => 'Code',
            'min_order_qty' => 'Min Order QTY',
            'manufacturer_id' => 'Manufacturer',
            'country_id' => 'Country',
            'measurement' => 'Measurement (LxWxH)',
            'measurement_unit_id' => 'Unit (Measurement)',
            'weight' => 'Weight',
            'weight_unit_id' => 'Unit (Weight)',
            'features' => 'Additional Features',
            'pmodel' => 'Product Model',
            'pbrand' => 'Product Brand',
            'color_id' => 'Color',
            'size_id' => 'Size',
            'warranty' => 'Warranty (M)',
            'vehicle_brand' => 'V.Brand',
            'supported_models' => 'Supported Models',
            'year_models' => 'Year Models',
            'activePrice' => 'Price (Pictorial)',
            'unit_id' => 'Unit',
            'hs_code' => 'HS Code',
            'purchase_discount' => 'Purchase Discount',
            'base_tp' => 'Base TP',
            'mrp' => 'MRP',
            'gp_percent' => 'GP(%)',
            'vat_calculation_method' => 'VAT calculation Method',
            'vat' => 'VAT',
            'cd' => 'CD',
            'sd' => 'SD',
            'rd' => 'RD',
            'ait' => 'AIT',
            'atv' => 'AT',
            'tti' => 'TTI',
            'rebatable' => 'Rebatable',
            'ca_id' => 'CA ID',
        );
    }

    public function getNameAndUnit()
    {
        return $this->model_name . " (" . Units::model()->unitLabel($this->unit_id) . ")";
    }

    public function prodNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->model_name;
    }

    public function hsCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->hs_code;
    }

    public function unitUnitIdOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->unit_id;
    }

    public function unitUnitNameOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return Units::model()->unitLabel($data->unit_id);
    }

    public function prodCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

    public function prodHSCodeOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->hs_code;
    }

    public function minQtyOfThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->min_order_qty;
    }


    public function getCatSubCat()
    {
        $cat = ProdItems::model()->itemName($this->item_id);
        $subcat = ProdBrands::model()->brandName($this->brand_id);
        return $this->model_name . " (" . $subcat . ") - " . $cat;
    }

    public function neededInfo($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            $cat = ProdItems::model()->itemName($data->item_id);
            $subcat = ProdBrands::model()->brandName($data->brand_id);
            return $data->model_name . " (" . $subcat . ") - " . $cat;
        }
    }

    public function modelName($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->model_name;
    }

    public function infoOfThisProduct($id)
    {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data;
    }

    public $barCodeGenerator;

    public function barcodeGenerator($code)
    {
        //$link = '<form target="_blank" action="' . Yii::app()->request->baseUrl . '/myBarCodeGen/html/BCGupca.php" method="post">';
        $link = '<form target="_blank" action="' . Yii::app()->request->baseUrl . '/myBarCodeGen/html/BCGcode39.php" method="post">';
        $link .= '<input type="hidden" name="codeTobe" value="' . $code . '">';
        $link .= '<input class="barCodeBtn" type="submit" value="Generate">';
        $link .= '</form>';

        echo $link;
    }

    public function optionalInfos($modelId)
    {
        $data = self::model()->findByPk($modelId);
        if ($data) {
            $msrmnt = "";
            $msrmntUnit = "";
            $wght = "";
            $wghtUnit = "";
            $manufacturer = "";
            $country = "";

            $msrmnt = $data->measurement;
            $msrmntUnit = Units::model()->unitLabel($data->measurement_unit_id);
            $wght = $data->weight;
            $wghtUnit = Units::model()->unitLabel($data->weight_unit_id);
            $manufacturer = Manufacturers::model()->nameOfThis($data->manufacturer_id);
            $country = Countries::model()->nameOfThis($data->country_id);
            echo "<table class='prodAdditionalInfoTab'>";
            if ($msrmnt == "" && $wght == "" && $manufacturer == "" && $country == "") {
                echo "<tr><td><div class='flash-error'>No additional info!</div></td></tr>";
            } else {
                if ($msrmnt != "" || $msrmntUnit != "")
                    echo "<tr><th>MEASUREMENT</th><td>" . $msrmnt . " " . $msrmntUnit . "</td></tr>";
                if ($wght != "" || $wghtUnit != "")
                    echo "<tr><th>WEIGHT</th><td>" . $wght . " " . $wghtUnit . "</td></tr>";
                if ($manufacturer != "")
                    echo "<tr><th>MANUFACTURER</th><td>" . $manufacturer . "</td></tr>";
                if ($country != "")
                    echo "<tr><th>COUNTRY</th><td>" . $country . "</td></tr>";
            }
            echo "</table>";
        }
    }

    public function optionalImportantInfos($model_id)
    {
        $prodInfo = ProdModels::model()->findByPk($model_id);
        $pbrand = "";
        $pmodel = "";
        $size_id = "";
        $color_id = "";
        if (!empty($prodInfo->pbrand))
            $pbrand = $prodInfo->pbrand;
        if (!empty($prodInfo->pmodel))
            $pmodel = $prodInfo->pmodel;
        if (!empty($prodInfo->size_id))
            $size_id = $prodInfo->size_id;
        if (!empty($prodInfo->color_id))
            $color_id = $prodInfo->color_id;

        $pModelName = PModel::model()->nameOfThis($pmodel);
        $pBrandName = PBrand::model()->nameOfThis($pbrand);
        $sizeName = CriteriaValues::model()->findNameOfThis($size_id);
        $colorName = CriteriaValues::model()->findNameOfThis($color_id);

        echo "<table class='prodAdditionalInfoTab'>";
        if ($pModelName == "" && $pBrandName == "" && $sizeName == "" && $colorName == "") {
            echo "<tr><td><div class='flash-error'>No additional info!</div></td></tr>";
        } else {
            if ($pModelName != "")
                echo "<tr><th>Model</th><td>" . $pModelName . "</td></tr>";
            if ($pBrandName != "")
                echo "<tr><th>Brand</th><td>" . $pBrandName . "</td></tr>";
            if ($sizeName != "")
                echo "<tr><th>Size</th><td>" . $sizeName . "</td></tr>";
            if ($colorName != "")
                echo "<tr><th>Color</th><td>" . $colorName . "</td></tr>";
        }
        echo "</table>";
    }

    public function getActiveStatus()
    {
        return array('0' => 'Pending', '1' => 'Processing', '2' => 'Approved');
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
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('model_name', $this->model_name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('hs_code', $this->hs_code, true);
        $criteria->compare('min_order_qty', $this->min_order_qty, true);
        $criteria->compare('color_id', $this->color_id);
        $criteria->compare('size_id', $this->size_id);
        $criteria->compare('manufacturer_id', $this->manufacturer_id);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('features', $this->features);
        $criteria->compare('pmodel', $this->pmodel);
        $criteria->compare('pbrand', $this->pbrand);
        $criteria->compare('warranty', $this->warranty);
        $criteria->compare('unit_id', $this->unit_id);
        $criteria->compare('sub_vat_id', $this->sub_vat_id);
        $criteria->compare('vat_calculation_method', $this->vat_calculation_method);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'item_id, brand_id ASC',
            ),
        ));
    }

    public function getModelIdByCode($model_code)
    {
        $sql = 'SELECT id FROM `prod_models` WHERE `code` LIKE "' . $model_code . '%"';
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $model_id = $command->queryRow()['id'];
        if ($model_id) {
            return $model_id;
        } else {
            return NULL;
        }
    }

    public function vatCalculationMethodValue($value)
    {
        $string = "";
        if ($value == self::STANDARD_RATE || $value == self::REDUCING_METHOD || $value == self::FIXED_AMOUNT || $value == self::EXEMPTED || $value == self::ZERO_RATE || $value == self::MRP) {
            $string = ProdModels::VAT_CALCULATION_METHOD_ARR[$value];
        }
        return $string;
    }

}
