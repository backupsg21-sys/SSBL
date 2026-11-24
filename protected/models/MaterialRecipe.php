<?php

class MaterialRecipe extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @return MaterialRecipe the static model class
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
        return 'material_recipe';
    }

    public $type;
    public $adjustment_cost;
    public $createdBy;
    public $temp_rm_qty_unit_id;
    public $temp_waste_qty_unit_id;

    const MATERIALS_COST = 1;
    const ADDITIONAL_COST = 2;
    const ADD_MAT_COST = 3;

    const ACTIVE = 1;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model_id, config_date', 'required'),
            array('model_id, model_qty, model_qty_unit_id, rm_model_id, rm_qty_unit_id, waste_qty_unit_id, sl_no, cost_type, approve_count', 'numerical', 'integerOnly' => true),
            array('rm_qty, waste_qty, total_price, net_price', 'numerical'),
            array('config_date, remarks', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, model_id, model_qty, rm_model_id, rm_qty, rm_qty_unit_id, waste_qty, waste_qty_unit_id, total_price, net_price, config_date, approve_count, created_by, created_datetime,  updated_by, updated_datetime', 'safe', 'on' => 'search'),
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
        if ($this->isNewRecord) {
            $this->created_datetime = new CDbExpression('NOW()');
            $this->created_by = Yii::app()->user->id;
        } else {
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
            'id' => 'ID',
            'model_id' => 'Finished Good',
            'cost_type' => 'Cost Type',
            'model_qty' => 'Production Qty (FG)',
            'model_qty_unit_id' => 'Unit',
            'rm_model_id' => 'Raw Material',
            'rm_qty_unit_id' => 'Unit',
            'rm_qty' => 'RM Qty',
            'waste_qty' => 'Waste Qty',
            'waste_qty_unit_id' => 'Unit',
            'total_price' => 'Unit Price',
            'net_price' => 'Net Price',
            'approve_count' => 'Approve',
            'config_date' => 'Config Date',
            'remarks' => 'Remarks',
        );
    }

    public function updateRecipe($recipe_id)
    {
        echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . '/images/update.png'), array('/materialRecipe/updateRecipe', 'id' => $recipe_id));
    }

    public function statusFlag($status)
    {
        if ($status == 1)
            echo '<span style="color:#03b703;"><b>ACTIVE</b></span>';
        else
            echo '<span style="color:red;"><b>INACTIVE</b></span>';
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
        $criteria->select = "t.*";
        $criteria->join = "";
        if ($this->model_id != "") {
            $criteria->join = " INNER JOIN prod_models pm ON t.model_id = pm.id ";
            $criteria->compare("pm.model_name", trim($this->model_id), true);
        }

        if ($this->rm_model_id != "") {
            if ($this->cost_type == self::MATERIALS_COST || $this->cost_type == self::ADD_MAT_COST) {
                $criteria->join .= " INNER JOIN prod_models  pm2 ON t.rm_model_id = pm2.id ";
                $criteria->compare("pm2.model_name", trim($this->rm_model_id), true);
            } else {
                $criteria->join .= "INNER JOIN chart_of_ac ca ON t.rm_model_id = ca.id ";
                $criteria->compare("ca.title", trim($this->rm_model_id), true);
            }
        }

        $criteria->compare('t.id', $this->id);
        //$criteria->compare('model_id', $this->model_id);
        $criteria->compare('t.model_qty', $this->model_qty);
        //$criteria->compare('rm_model_id', $this->rm_model_id, true);
        $criteria->compare('t.cost_type', $this->cost_type);
        $criteria->compare('t.rm_qty', $this->rm_qty);
        $criteria->compare('t.waste_qty', $this->waste_qty);
        $criteria->compare('t.total_price', $this->total_price);
        $criteria->compare('t.config_date', $this->config_date);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 150,
            ),
            'sort' => array(
                'defaultOrder' => 't.sl_no DESC, t.cost_type ASC',
            ),
        ));


    }

    public function getMaterialName($id, $type)
    {
        $name = '';
        if ($type == self::MATERIALS_COST) {
            return $name = ProdModels::model()->modelName($id);
        } else if ($type == self::ADDITIONAL_COST || $type == self::ADD_MAT_COST) {
            $name_data = ChartOfAc::model()->findByAttributes(array('id' => $id));
            if ($name_data)
                $name = $name_data['title'];
            return $name;
        }
    }

    public function getRecipeTotalCost($sl_no)
    {
        $amount = 0;
        if (!empty($sl_no) & $data = MaterialRecipe::model()->findAllByAttributes(array('sl_no' => $sl_no))) {
            foreach ($data as $d) {
                $caseZero = $d['rm_qty'] + $d['waste_qty'];
                if ($caseZero > 0) {
                    // $amount += ($d['rm_qty'] + $d['waste_qty']) * $d['total_price'];
                    $amount += $d['total_price'];
                } else
                    $amount += $d['total_price'];
            }
            return $amount;
        }
    }
	
	
    public function getRecipeTotalCostByProductId($model_id)
    {
        $amount = 0;

        if (!empty($model_id) & $data = MaterialRecipe::model()->findAllByAttributes(array('model_id' => $model_id,'approve_count'=>1))) {
            foreach ($data as $d) {
                $caseZero = $d['rm_qty'] + $d['waste_qty'];
                if ($caseZero > 0) {
                    // $amount += ($d['rm_qty'] + $d['waste_qty']) * $d['total_price'];
                    $amount += $d['total_price'];
                } else
                    $amount += $d['total_price'];
            }
            return $amount;
        }
    }

    public function getRecipeTotalCostFromSave($sl_no)
    {
        $amount = 0;
        if (!empty($sl_no) & $data = MaterialRecipe::model()->findAllByAttributes(array('sl_no' => $sl_no))) {
            foreach ($data as $d) {
                $amount += $d['total_price'];
            }
            return $amount;
        }
    }

    public function costType($type)
    {
        if ($type == self::MATERIALS_COST)
            return 'Material';
        else if ($type == self::ADDITIONAL_COST)
            return 'Additional';
        else if ($type == self::ADD_MAT_COST)
            return 'Additional Indirect Material';
        else
            return '';
    }

    public function getMaterialType()
    {
        return array(self::MATERIALS_COST => 'Material', self::ADDITIONAL_COST => 'Additional', self::ADD_MAT_COST => 'Additional Material Cost');
    }

    public function getMaterialLineTotal($qty, $unit_price)
    {
        $result = $qty > 0 ? $qty * $unit_price : $unit_price;
        return number_format($result, 2);
    }
    public function checkDeletable($sl_no, $config_date){

        $cirteria = new CDbCriteria();
        $cirteria->select = '*';
        $cirteria->condition = "recipe_sl_no='" . $sl_no . "' AND issue_date >= '" . $config_date . "'";
        $model = Production::model()->find($cirteria);
        if($model){
            return false;
        }
        return true;
    }


}
