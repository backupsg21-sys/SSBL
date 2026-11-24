<?php

/**
 * This is the model class for table "file_overview".
 *
 * The followings are the available columns in table 'file_overview':
 * @property integer $id
 * @property string $file_path
 * @property string $file_name
 */
class SoftwareUpdate extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return SoftwareUpdate the static model class
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
        return 'software_update';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('file_path, file_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, file_path, file_name', 'safe', 'on' => 'search'),
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
            'file_path' => 'File Path',
            'file_name' => 'File Name',
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
        $criteria->compare('file_path', $this->file_path, true);
        $criteria->compare('file_name', $this->file_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    //Software Update
    public static function updateSoftware()
    {

        $model = new SoftwareUpdate;


        /*Start Zip File Extract*/
        $extractTo = $_SERVER['DOCUMENT_ROOT'] . '/quick_vat/quick_vat/uploads';

        // $model->file_name = CUploadedFile::getInstance($model, 'file_name');
        // $filename = $model->file_name->tempName;

        $updateFiles = $_SERVER['DOCUMENT_ROOT'] . '/quick_vat/quick_vat/uploads/softwareupdate.zip';

        $zip = new ZipArchive;
        if ($zip->open($updateFiles) === TRUE) {
            $extract = $zip->extractTo($extractTo);
            if ($extract) {
                $zip->close();
                self::excelToDB();
                self::updateProcess();
            }
        } else {
            Yii::app()->user()->setFlash('fail', 'Fail to File Extract !');
        }
        /*End Zip File Extract*/

        // $this->render('admin', array(
        //     'model' => $model,
        // ));
    }


    /*Start Excel Data Save to Database*/
    protected static function excelToDB()
    {

        $fileExist = file_exists(Yii::app()->basePath . '/../uploads/softwareupdate/softwareupdate.xls');

        if ($fileExist) {

            $sql = "DELETE FROM software_update";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql)->execute();


            Yii::import('ext.phpexcelreader.JPhpExcelReader');
            $count = 0;
            $data = new JPhpExcelReader('uploads/softwareupdate/softwareupdate.xls');
            $isFirst = true;

            for ($row = 2; $row <= $data->rowcount(); $row++) {
                $modelSU = new SoftwareUpdate;
                $modelSU->file_path = $data->val($row, 1);
                $modelSU->file_name = $data->val($row, 2);
                $modelSU->save();
            }

        } else {
            Yii::app()->user->setFlash('error', "XLS file not exist! Please upload XLS data file first.");
        }
    }
    /*End Excel Data Save to Database*/


    /*Start File Update or Create*/
    protected static function updateProcess()
    {

        $items = SoftwareUpdate::model()->findAll(array('order' => 'id DESC'));

        foreach ($items as $item) {

            $fileExist = file_exists(Yii::app()->basePath . '/../uploads/softwareupdate/' . $item->file_name);
            $src = Yii::app()->basePath . '/../uploads/softwareupdate/' . $item->file_name;
            $des = Yii::app()->basePath . '/../' . $item->file_path . '/' . $item->file_name;

            if ($fileExist) {
                copy($src, $des);
                unlink($src);

            } else {
                $createdfile = fopen($des, "w") or die("Unable to open file!");
                fclose($createdfile);
            }
            Yii::app()->user->setFlash('success', "Software Update Successfully.");
        }

    }
    /*End File Update or Create*/


}