<?php

class Manufacturers extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'manufacturers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 5]
        ];
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'manufacturer' => 'Manufacturer',
            'code' => 'Code',
        );
    }
}
