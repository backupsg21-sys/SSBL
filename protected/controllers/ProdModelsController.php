<?php

class ProdModelsController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights-
            Itembycode-
            SubCatOfThisCat-
            ProdInfoOfThisAdditionalOp-
            BrandOfThisItemFromOutside-
            ProdInfoOfThisPmodel-
            jqueryProdSearch-
            Jquery_showprodSearch-
            Jquery_showprodSearch1-
            Jquery_showprodSearchSaleq-
            Jquery_showprodSearchWithPrice-
            Jquery_showFinishedGoodsProdSearch-
            Jquery_showprodSearch3-
            Jquery_showprodSearch2',
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionUploadProductFromExcel()
    {
        $fileExist = file_exists(Yii::app()->basePath . '/../upload/tempXlFile/tempXlFile.xls');
        if ($fileExist) {
            Yii::import('ext.phpexcelreader.JPhpExcelReader');
            $data = new JPhpExcelReader('upload/tempXlFile/tempXlFile.xls');
            $datas = '';
            for ($row = 1; $row < $data->rowcount(); $row++) {
                $hsCode = $data->val($row, 1);
                $cd = $data->val($row, 3);
                $rd = $data->val($row, 4);
                $sd = $data->val($row, 5);
                $vat = $data->val($row, 6);
                $ait = $data->val($row, 7);
                $atv = $data->val($row, 8);
                $tti = $data->val($row, 9);
                $prodModels = ProdModels::model()->findByAttributes(array('hs_code' => $hsCode));
                if ($prodModels) {
                    $prodModels->cd = $cd;
                    $prodModels->rd = $rd;
                    $prodModels->sd = $sd;
                    $prodModels->vat = $vat;
                    $prodModels->ait = $ait;
                    $prodModels->atv = $atv;
                    $prodModels->tti = $tti;
                    if ($prodModels->save()) {
                        echo $row . '-HS-Code Update: ' . $prodModels->hs_code . '<br/>';
                    } else {
                        echo $row . '-HS-Code Get But Not Update: ' . $prodModels->hs_code . '<br/>';
                        var_dump($prodModels->getErrors());
                    }
                } else {
                    echo $row . '-HS-Code Not Update: ' . $hsCode . '<br/>';
                }
            }
            //unlink(Yii::app()->basePath . '/../upload/tempXlFile/tempXlFile.xls'); //delete file
            Yii::app()->user->setFlash('success', "Products data inserted into databse.");
        }
        $this->redirect(array('uploadDeveiceData'));
    }

    public function actionProcessDeviceData()
    {
        $fileExist = file_exists(Yii::app()->basePath . '/../upload/tempXlFile/tempXlFile.xls');
        if ($fileExist) {
            Yii::import('ext.phpexcelreader.JPhpExcelReader');
            $data = new JPhpExcelReader('upload/tempXlFile/tempXlFile.xls');

            for ($row = 4; $row < $data->rowcount(); $row++) {
                $modelName = $data->val($row, 1);
                $prodCode = $data->val($row, 2);
                $prodQuality = $data->val($row, 3);
                $prodUnit = $data->val($row, 4);
                $vehicleBrand = $data->val($row, 5);
                $customField2 = $data->val($row, 6);
                $yearModel = $data->val($row, 7);
                $location = $data->val($row, 8);
                $stockIn = preg_replace('#[^\w()/.%\-&]#', "", $data->val($row, 21));
                $unitPrice = preg_replace('#[^\w()/.%\-&]#', "", $data->val($row, 22));

                //=========Start Insert data to indivisual table==============//
                $prodItems = $data->val($row, 3);
                $isExist = ProdItems::model()->findByAttributes(array('item_name' => $prodItems));
                if (!$isExist) {
                    $modelProdItems = new ProdItems;
                    $modelProdItems->item_name = $prodItems;
                    $modelProdItems->save();
                }

                $customField1 = $data->val($row, 5);
                $customFieldArray = explode(",", $customField1);
                foreach ($customFieldArray as $key => $customF1Array) {
                    $isExistCF1 = CustomField1::model()->findByAttributes(array('title' => $customF1Array));
                    if (!$isExistCF1) {
                        $modelCustomField1 = new CustomField1;
                        $modelCustomField1->title = $customF1Array;
                        $modelCustomField1->save();
                    }
                }

                $customField2 = $data->val($row, 6);
                $customField2Array = explode(",", $customField2);
                foreach ($customField2Array as $key => $customF2Array) {
                    $isExistCF2 = CustomField2::model()->findByAttributes(array('title' => $customF2Array));
                    if (!$isExistCF2) {
                        $modelCustomField2 = new CustomField2;
                        $modelCustomField2->title = $customF2Array;
                        $modelCustomField2->save();
                    }
                }

                $yearmodel = $data->val($row, 7);
                $yearmodelArray = explode(",", $yearmodel);
                foreach ($yearmodelArray as $key => $ymArray) {
                    $isExistYM = YearModels::model()->findByAttributes(array('title' => $ymArray));
                    if (!$isExistYM) {
                        $modelYM = new YearModels;
                        $modelYM->title = $ymArray;
                        $modelYM->save();
                    }
                }

                $location = $data->val($row, 8);
                $locationArray = explode(",", $location);
                foreach ($locationArray as $key => $loc) {
                    $isExistLoc = Location::model()->findByAttributes(array('title' => $loc));
                    if (!$isExistLoc) {
                        $modelLOC = new Location;
                        $modelLOC->stores_id = 1;
                        $modelLOC->title = $loc;
                        $modelLOC->save();
                    }
                }
                //=========End Insert data to indivisual table==============//

                $isExist = ProdModels::model()->findByAttributes(array('code' => $prodCode));
                //if(!$isExist)
                {
                    $prodQualityInfo = ProdItems::model()->findByAttributes(array('item_name' => $prodQuality));
                    if ($prodQualityInfo) {
                        $prodQualityId = $prodQualityInfo->id;
                    } else {
                        $prodQualityId = 0;
                    }

                    $vehicleBrandInfo = CustomField1::model()->findByAttributes(array('title' => $vehicleBrand));
                    if ($vehicleBrandInfo) {
                        $vehicleBrandId = $vehicleBrandInfo->id;
                    } else {
                        $vehicleBrandId = 0;
                    }


                    $prodUnitInfo = Units::model()->findByAttributes(array('label' => $prodUnit));
                    if ($prodUnitInfo) {
                        $prodUnitId = $prodUnitInfo->id;
                    } else {
                        $prodUnitId = 0;
                    }


                    //=================Start Data Insert to  prod_models table =============//
                    $modelProd = new ProdModels;
                    $modelProd->item_id = $prodQualityId;
                    //$modelProd->brand_id = $vehicleBrandId;
                    $modelProd->model_name = $modelName;
                    $modelProd->code = $prodCode;
                    $modelProd->unit_id = $prodUnitId;
                    if ($modelProd->save()) {
                        //=================End Data Insert to  prod_models table =============//
                        //=================Start Data Insert to custom_prod_fields table =============//
                        $customField2Array = explode(",", $customField2);
                        $dataArray = array();
                        foreach ($customField2Array as $key => $cf2) {
                            $isExistFc2 = CustomField2::model()->findByAttributes(array('title' => $cf2));
                            if ($isExistFc2) {
                                $dataArray[] = $isExistFc2->id;
                            }
                        }
                        $sm1 = 0;
                        $sm2 = 0;
                        $sm3 = 0;
                        $sm4 = 0;
                        if (isset($dataArray[0])) {
                            $sm1 = $dataArray[0];
                        }
                        if (isset($dataArray[1])) {
                            $sm2 = $dataArray[1];
                        }
                        if (isset($dataArray[2])) {
                            $sm3 = $dataArray[2];
                        }
                        if (isset($dataArray[3])) {
                            $sm4 = $dataArray[3];
                        }

                        $yearModelArray = explode(",", $yearModel);
                        $dataYmArray = array();
                        foreach ($yearModelArray as $key => $ym) {
                            $isExistYM = YearModels::model()->findByAttributes(array('title' => $ym));
                            if ($isExistYM) {
                                $dataYmArray[] = $isExistYM->id;
                            }
                        }
                        $ym1 = 0;
                        $ym2 = 0;
                        $ym3 = 0;
                        $ym4 = 0;
                        if (isset($dataYmArray[0])) {
                            $ym1 = $dataYmArray[0];
                        }
                        if (isset($dataYmArray[1])) {
                            $ym2 = $dataYmArray[1];
                        }
                        if (isset($dataYmArray[2])) {
                            $ym3 = $dataYmArray[2];
                        }
                        if (isset($dataYmArray[3])) {
                            $ym4 = $dataYmArray[3];
                        }

                        $modelCustomProdField = new CustomProdFields;
                        $modelCustomProdField->prod_id = $modelProd->id;
                        $modelCustomProdField->suported_model1 = $sm1;
                        $modelCustomProdField->suported_model2 = $sm2;
                        $modelCustomProdField->suported_model3 = $sm3;
                        $modelCustomProdField->suported_model4 = $sm4;
                        $modelCustomProdField->vehicle_brand = $vehicleBrandId;
                        $modelCustomProdField->year_model1 = $ym1;
                        $modelCustomProdField->year_model2 = $ym2;
                        $modelCustomProdField->year_model3 = $ym3;
                        $modelCustomProdField->year_model4 = $ym4;
                        $modelCustomProdField->save();
                        //=================End Data Insert to custom_prod_fields atble =============//
                    }
                    //=================Start Data Insert to  inventory table =============//
                    $locationInfo = Location::model()->findByAttributes(array('title' => $location));
                    if ($locationInfo) {
                        $locationId = $locationInfo->id;
                    } else {
                        $locationId = 0;
                    }
                    $modelInventory = new Inventory;
                    $modelInventory->store_id = Stores::FG_STORE;
                    $modelInventory->location_id = $locationId;
                    $modelInventory->item_id = $prodQualityId;
                    $modelInventory->brand_id = $vehicleBrandId;
                    $modelInventory->model_id = $modelProd->id;
                    $modelInventory->code = $prodCode;
                    $modelInventory->stock_in = $stockIn;
                    $modelInventory->sell_price = $unitPrice;
                    $modelInventory->transaction_date = new CDbExpression('NOW()');
                    $modelInventory->month = date('m');
                    $modelInventory->year = date('Y');
                    $modelInventory->save();
                    //=================End Data Insert to  inventory table =============//
                    //=================Start Data Insert to  purchase_prices table =============//
                    $modelPurchasePrice = new PurchasePrices;
                    $modelPurchasePrice->model_id = $modelProd->id;
                    $modelPurchasePrice->purchase_price = $unitPrice;
                    $modelPurchasePrice->qty = $stockIn;
                    $modelPurchasePrice->month = date('m');
                    $modelPurchasePrice->year = date('Y');
                    $modelPurchasePrice->date = new CDbExpression('NOW()');
                    $modelPurchasePrice->save();
                    //=================End Data Insert to  purchase_prices table =============//
                }

                /*
                  // theory :: $data->val(row_number, column_number);
                  $prodQuality = $data->val($row, 3); // means (row_number=1, column_number=2)
                  $prodType = $data->val($row, 2); // means (row_number=1, column_number=3)
                  $prodName = $data->val($row, 3); // means (row_number=1, column_number=4)
                  $prodCode = $data->val($row, 4); // means (row_number=1, column_number=5)
                  $prodUnit = $data->val($row, 5); // means (row_number=1, column_number=6)
                  $prodBrand = $data->val($row, 6); // means (row_number=1, column_number=7)
                  $vehicleBrand = $data->val($row, 7); // means (row_number=1, column_number=8)

                  //echo $prodName."______".$prodCode."<br>";


                  $isExist=  ProdModels::model()->findByAttributes(array('code'=>$prodCode));
                  if(!$isExist){
                  $prodQualityInfo=  ProdItems::model()->findByAttributes(array('item_name'=>$prodQuality));
                  if($prodQualityInfo){
                  $prodQualityId=$prodQualityInfo->id;
                  }else{
                  $prodQualityId=0;
                  }

                  $prodTypeInfo= ProdBrands::model()->findByAttributes(array('brand_name'=>$prodType));
                  if($prodTypeInfo){
                  $prodTypeId=$prodTypeInfo->id;
                  }else{
                  $prodTypeId=0;
                  }

                  $prodUnitInfo= Units::model()->findByAttributes(array('label'=>$prodUnit));
                  if($prodUnitInfo){
                  $prodUnitId=$prodUnitInfo->id;
                  }else{
                  $prodUnitId=0;
                  }

                  $prodBrandInfo= PBrand::model()->findByAttributes(array('name'=>$prodBrand));
                  if($prodBrandInfo){
                  $prodBrandId=$prodBrandInfo->id;
                  }else{
                  $prodBrandId=0;
                  }

                  $vehicleBrandInfo= CustomField1::model()->findByAttributes(array('title'=>$vehicleBrand));
                  if($vehicleBrandInfo){
                  $vehicleBrandId=$vehicleBrandInfo->id;
                  }else{
                  $vehicleBrandId=0;
                  }
                  $model = new ProdModels;
                  $model->item_id = $prodQualityId;
                  $model->brand_id = $prodTypeId;
                  $model->model_name = $prodName;
                  $model->code = $prodCode;
                  $model->unit_id = $prodUnitId;
                  $model->pbrand = $prodBrandId;
                  $model->vehicle_brand = $vehicleBrandId;
                  $model->save();
                  }
                 */
            }
            unlink(Yii::app()->basePath . '/../upload/tempXlFile/tempXlFile.xls'); //delete file
            Yii::app()->user->setFlash('success', "Products data inserted into databse.");
        } else {
            Yii::app()->user->setFlash('error', "XL file not exist! Please upload XL data file first.");
        }
        $this->redirect(array('uploadDeveiceData'));
    }

    public function actionUploadDeveiceData()
    {
        $model = new ProdModels;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ProdModels'])) {
            $model->deviceFile = CUploadedFile::getInstance($model, 'deviceFile');

            if ($model->deviceFile) {
                if ($model->deviceFile->extensionName == "xls") {
                    //$filenameReplacingWhiteSpaceWithUnderscor = str_replace(" ", "_", $model->deviceFile);
                    $model->deviceFile->saveAs(Yii::app()->basePath . '/../upload/tempXlFile/tempXlFile.xls');
                    Yii::app()->user->setFlash('success', "XL file uploaded.");
                    $this->redirect(array('uploadDeveiceData'));
                } else {
                    Yii::app()->user->setFlash('error', "Please upload a valid .xls file!");
                    $this->redirect(array('uploadDeveiceData'));
                }
            } else {
                Yii::app()->user->setFlash('error', "Please upload a valid .xls file!");
                $this->redirect(array('uploadDeveiceData'));
            }
        }

        $this->render('uploadDeveiceData', array(
            'model' => $model,
        ));
    }

    public function actionSearchByAttributes()
    {
        if (isset($_POST['item_id'], $_POST['brand_id'])) {
            $item_id = $_POST['item_id'];
            $brand_id = $_POST['brand_id'];
            if ($item_id != "" || $brand_id != "") {
                $criteria = new CDbCriteria();
                $criteria->condition = "id!=0";
                if ($item_id != "") {
                    $criteria->condition .= " AND item_id=" . $item_id;
                }
                if ($brand_id != "") {
                    $criteria->condition .= " AND brand_id=" . $brand_id;
                }
                $data = ProdModels::model()->findAll($criteria);
                if ($data) {
                    $prodList = CHtml::tag('option', array('value' => ''), 'Select', true);
                    $prodList .= CHtml::tag('option', array('value' => '0'), 'No Parts Required', true);
                    foreach ($data as $d) {
                        $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
                    }
                } else {
                    $prodList = CHtml::tag('option', array('value' => ''), 'Select', true);
                }
            } else {
                $prodList = CHtml::tag('option', array('value' => '0'), 'No Parts Required', true);
            }
        }
        echo CJSON::encode(array(
            'prodList' => $prodList,
        ));
    }

    public function actionProdCodeAndAdditionalInfo()
    {
        $prodModelId = $_POST['modelId'];
        $activeInfos = SellPrice::model()->activeInfos($prodModelId);

        $sellPrice = 0;
        $discount = 0;
        $idealQty = 0;
        $warnQty = 0;

        if ($activeInfos) {
            $sellPrice = $activeInfos->sell_price;
            $discount = $activeInfos->discount;
            $idealQty = $activeInfos->ideal_qty;
            $warnQty = $activeInfos->warn_qty;
        }

        $prodModelInfo = ProdModels::model()->findByPk($prodModelId);
        $code = $prodModelInfo->code;
        $sz = CriteriaValues::model()->findNameOfThis($prodModelInfo->size_id);
        $clr = CriteriaValues::model()->findNameOfThis($prodModelInfo->color_id);
        $msrmnt = $prodModelInfo->measurement;
        $msrmntUnit = Units::model()->unitLabel($prodModelInfo->measurement_unit_id);
        $wght = $prodModelInfo->weight;
        $wghtUnit = Units::model()->unitLabel($prodModelInfo->weight_unit_id);

        if ($code == '')
            $code = "N/A";
        if ($sz == '')
            $sz = "N/A";
        if ($clr == '')
            $clr = "N/A";
        if ($msrmnt == '')
            $msrmnt = "N/A";
        if ($wght == '')
            $wght = "N/A";

        $prodAdditionalInfo = '<table class="prodAdditionalInfoTab">
                <tr>
                    <th>Product-Code/ Bar-Code</th>
                    <td>' . $code . '</td>
                </tr>
                <tr>
                    <th>Size</th>
                    <td>' . $sz . '</td>
                </tr>
                <tr>
                    <th>Color</th>
                    <td>' . $clr . '</td>
                </tr>
                <tr>
                    <th>Weight</th>
                    <td>' . $wght . ' ' . $wghtUnit . '</td>
                </tr>
                <tr>
                    <th>Measurement</th>
                    <td>' . $msrmnt . ' ' . $msrmntUnit . '</td>
                </tr>
              </table>
               <style>
        .receivedByDiv{
    border: 1px solid #AAAAAA;
    border-radius: 2px;
    box-shadow: 0 1px 2px #DDDDDD;
    color: #D50000;
    font-weight: bold;
    float: left;
    background-color: #FFFFFF;
}

table.prodAdditionalInfoTab{
    float: left;
    width: 100%;
    border: 1px solid #ffffff;
    border-collapse: collapse;
}

table.prodAdditionalInfoTab tr th{
    border: 1px solid #ffffff;
    text-align: right;
}

table.prodAdditionalInfoTab tr td{
    border: 1px solid #ffffff;
}  
</style>  
            ';

        if ($prodModelId != '') {
            echo CJSON::encode(array(
                'brandValue' => $prodModelInfo->brand_id,
                'itemValue' => $prodModelInfo->item_id,
                'code' => $code,
                'sell_price' => $sellPrice,
                'discount' => $discount,
                'idealQty' => $idealQty,
                'warnQty' => $warnQty,
                'prodAdditionalInfo' => $prodAdditionalInfo,
            ));
        } else {
            echo CJSON::encode(array(
                'code' => '',
                'prodAdditionalInfo' => "<div class='flash-error'>Please Select Product!</div>",
            ));
        }
    }

    public function actionSubCatOfThisCat()
    {
        $catId = $_POST['catId'];
        $subCatList = '';

        if ($catId != '') {
            $condition = 'item_id = ' . $catId . ' ORDER BY brand_name ASC';
            $data = ProdBrands::model()->findAll(array("condition" => $condition,), "id");

            if ($data) {
                $subCatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subCatList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->brand_name), true);
                }
            } else {
                $subCatList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        } else {
            $data = ProdBrands::model()->findAll(['order' => 'brand_name asc']);
            if ($data) {
                $subCatList = CHtml::tag('option', array('value' => ""), "Select", true);
                foreach ($data as $d) {
                    $subCatList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->brand_name), true);
                }
            } else {
                $subCatList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        }
        echo CJSON::encode(array(
            'subCatList' => $subCatList,
        ));
    }

    public function actionCatProdOfThisSubCat()
    {
        $idPrefix = $_POST['idPrefix'];
        $subCatId = $_POST['subCatId'];
        $jsPart = '';
        $prodList = '';

        if ($subCatId != '') {
            $condition = 'brand_id = ' . $subCatId . ' ORDER BY id DESC';
            $data = ProdModels::model()->findAll(array("condition" => $condition,), "id");
            $data2 = ProdBrands::model()->findByPk($subCatId);
            if ($data) {
                $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                var catId="' . $data2->item_id . '";
                                $("#' . $idPrefix . '_item_id").val(catId);
                        });
                       </script>
                       ';
                $prodList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
                }
            } else {
                $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_item_id").val("").selected=true;
                        });
                       </script>
                       ';
                $prodList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        } else {
            $data = ProdModels::model()->findAll();
            $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_item_id").val("").selected=true;
                        });
                       </script>
                       ';
            if ($data) {
                $prodList = CHtml::tag('option', array('value' => ""), "Select", true);
                foreach ($data as $d) {
                    $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
                }
            } else {
                $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_item_id").val("").selected=true;
                        });
                       </script>
                       ';
                $prodList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        }
        echo CJSON::encode(array(
            'jsPart' => $jsPart,
            'prodList' => $prodList,
        ));
    }

    public function actionCatSubCatOfThisProd()
    {

        $idPrefix = $_POST['idPrefix'];
        $prodId = $_POST['prodId'];

        $jsPart = '';

        if ($prodId != '') {
            $data = ProdModels::model()->findByPk($prodId);

            $activeInfos = SellPrice::model()->activeInfos($prodId);

            $sellPrice = 0;
            $discount = 0;
            $idealQty = 0;
            $warnQty = 0;

            if ($activeInfos) {
                $sellPrice = $activeInfos->sell_price;
                $discount = $activeInfos->discount;
                $idealQty = $activeInfos->ideal_qty;
                $warnQty = $activeInfos->warn_qty;
            }

            $jsPart .= '<script type="text/javascript">
                   $(document).ready(function(){ 
                            var catId="' . $data->item_id . '";
                            $("#' . $idPrefix . '_item_id").val(catId);
                            var subcatId="' . $data->brand_id . '";
                            $("#' . $idPrefix . '_brand_id").val(subcatId);
                            var prodCode="' . $data->code . '";
                            $("#' . $idPrefix . '_code").val(prodCode);
                            
                            var pmodel="' . $data->pmodel . '";
                            $("#' . $idPrefix . '_pmodel").val(pmodel);
                            var pbrand="' . $data->pbrand . '";
                            $("#' . $idPrefix . '_pbrand").val(pbrand);
                            var size_id="' . $data->size_id . '";
                            $("#' . $idPrefix . '_size_id").val(size_id);
                            var color_id="' . $data->color_id . '";
                            $("#' . $idPrefix . '_color_id").val(color_id);
                                
                            var sp="' . $sellPrice . '";
                            $("#' . $idPrefix . '_sell_price").val(sp);
                            var ds="' . $discount . '";
                            $("#' . $idPrefix . '_discount").val(ds);
                            var vat="' . $data->vat . '";
                            $("#' . $idPrefix . '_vat").val(vat);
                    });
                   </script>
                   ';
        } else {
            $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_item_id").val("").selected=true;
                                $("#' . $idPrefix . '_brand_id").val("").selected=true;
                                $("#' . $idPrefix . '_code").val("");
                        });
                       </script>
                       ';
        }
        echo CJSON::encode(array(
            'jsPart' => $jsPart,
        ));
    }

    public function actionCatSubCatProdOfThisCode()
    {
        $idPrefix = $_POST['idPrefix'];
        $prodCode = $_POST['prodCode'];
        $storeId = @$_POST['store_id'];
        $jsPart = '';

        if ($prodCode != '') {

            $data = ProdModels::model()->findByAttributes(array('code' => $prodCode));

            $activeInfos = SellPrice::model()->activeInfos($data->id);
            $closingStock = Inventory::model()->closingStock($data->id, $storeId);
            $sellPrice = 0;
            $discount = 0;
            $idealQty = 0;
            $warnQty = 0;

            if ($activeInfos) {
                $sellPrice = $activeInfos->sell_price;
                $discount = $activeInfos->discount;
                $idealQty = $activeInfos->ideal_qty;
                $warnQty = $activeInfos->warn_qty;
            }

            $jsPart .= '<script type="text/javascript">
                   $(document).ready(function(){ 
                            var catId="' . $data->item_id . '";
                            $("#' . $idPrefix . '_item_id").val(catId);
                            var subcatId="' . $data->brand_id . '";
                            $("#' . $idPrefix . '_brand_id").val(subcatId);
                            var prodName="' . $data->model_name . '";
                            $("#model_id_text").val(prodName);
                            
							var prodId="' . $data->id . '";
                            $("#' . $idPrefix . '_model_id").val(prodId);
                                
                            var pmodel="' . $data->pmodel . '";
                            $("#' . $idPrefix . '_pmodel").val(pmodel);
                            var pbrand="' . $data->pbrand . '";
                            $("#' . $idPrefix . '_pbrand").val(pbrand);
                            var size_id="' . $data->size_id . '";
                            $("#' . $idPrefix . '_size_id").val(size_id);
                            var color_id="' . $data->color_id . '";
                            $("#' . $idPrefix . '_color_id").val(color_id);
                                
                            var sp="' . $sellPrice . '";
                            $("#' . $idPrefix . '_sell_price").val(sp);
                            var ds="' . $discount . '";
                            $("#' . $idPrefix . '_discount").val(ds);
							var closingStock="' . $closingStock . '";
                            $("#' . $idPrefix . '_closingstock").val(closingStock);
                    });
                   </script>
                   ';
        } else {
            $jsPart .= '<script type="text/javascript">
                       $(document).ready(function(){ 
                                $("#' . $idPrefix . '_item_id").val("").selected=true;
                                $("#' . $idPrefix . '_brand_id").val("").selected=true;
                                $("#' . $idPrefix . '_model_id").val(0);
                        });
                       </script>
                       ';
        }
        echo CJSON::encode(array(
            'jsPart' => $jsPart,
        ));
    }

    public function actionItemThisCode()
    {
        $id = $_POST['prodId'];
        $prodCode = trim($_POST['prodCode']);
        $prodId = "0";
        $prodName = "NULL";

        if ($prodCode != "") {
            $data = ProdModels::model()->findByAttributes(array('code' => $prodCode));
            if ($data) {
                $prodId = $data->id;
                $prodName = $data->model_name;
            }
        } else {
            $data = ProdModels::model()->findByPk($id);
            if ($data) {
                $prodId = $data->id;
                $prodName = $data->model_name;
            }
        }
        echo CJSON::encode(array(
            'value' => $prodId,
            'label' => $prodName,
        ));
    }

    public function actionJquery_showprodSearch()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $item_id = $prodInfo->item_id;
                $brand_id = $prodInfo->brand_id;
                $code = $prodInfo->code;
                $country_id = $prodInfo->country_id;
                $manufacturer_id = $prodInfo->manufacturer_id;
                $warranty = $prodInfo->warranty;
                $color_id = $prodInfo->color_id;
                $pmodel = $prodInfo->pmodel;
                $closingStock = Inventory::model()->closingStock($prodInfo->id);
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'item_id' => $item_id,
                    'brand_id' => $brand_id,
                    'code' => $code,
                    'country_id' => $country_id,
                    'manufacturer_id' => $manufacturer_id,
                    'warranty' => $warranty,
                    'color_id' => $color_id,
                    'pmodel' => $pmodel,
                    'closingStock' => $closingStock,
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No parts required");
        }
        echo json_encode($results);
    }
	
	public function actionJqueryProdSearchFG()
    {
        $results = [];
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
		$criteria->condition = "item_id = 1";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = 25;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM common where model_id = '$prodInfo->id' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing)) {
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);
                }

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);
                $unitPrice = $prodInfo->mrp;
                $vat = $prodInfo->vat;
                $vatType = $prodInfo->vat_calculation_method;
                $rebatable = $prodInfo->rebatable;

                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'unitPrice' => $unitPrice,
                    'vat' => $vat,
                    'vatType' => $vatType,
                    'rebatable' => $rebatable,
                    'closingStock' => $closingStock,
                    'cd' => $prodInfo->cd,
                    'sd' => $prodInfo->sd,
                    'rd' => $prodInfo->rd,
                    'ait' => $prodInfo->ait,
                    'atv' => $prodInfo->atv,
                    'tti' => $prodInfo->tti,
                    'exd' => $prodInfo->exd,
                    'hscode' => $prodInfo->hs_code
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }
    public function actionJqueryProdSearchFGRM()
    {
        $results = [];
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->condition = "item_id != 3";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = 25;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM common where model_id = '$prodInfo->id' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing)) {
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);
                }

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);
                $unitPrice = $prodInfo->mrp;
                $vat = $prodInfo->vat;
                $vatType = $prodInfo->vat_calculation_method;
                $rebatable = $prodInfo->rebatable;

                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'unitPrice' => $unitPrice,
                    'vat' => $vat,
                    'vatType' => $vatType,
                    'rebatable' => $rebatable,
                    'closingStock' => $closingStock,
                    'cd' => $prodInfo->cd,
                    'sd' => $prodInfo->sd,
                    'rd' => $prodInfo->rd,
                    'ait' => $prodInfo->ait,
                    'atv' => $prodInfo->atv,
                    'tti' => $prodInfo->tti,
                    'exd' => $prodInfo->exd,
                    'hscode' => $prodInfo->hs_code
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }


    public function actionJqueryProdSearch()
    {
        $results = [];
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
		$criteria->condition = "item_id = 2";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = 25;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM inventory where model_id = '$prodInfo->id' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing)) {
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);
                }

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);
                $unitPrice = $prodInfo->mrp;
                $vat = $prodInfo->vat;
                $vatType = $prodInfo->vat_calculation_method;
                $rebatable = $prodInfo->rebatable;
                $base_tp = $prodInfo->base_tp;
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'unitPrice' =>  $base_tp, //$unitPrice,
                    'vat' => $vat,
                    'vatType' => $vatType,
                    'rebatable' => $rebatable,
                    'closingStock' => $closingStock,
                    'cd' => $prodInfo->cd,
                    'sd' => $prodInfo->sd,
                    'rd' => $prodInfo->rd,
                    'ait' => $prodInfo->ait,
                    'atv' => $prodInfo->atv,
                    'tti' => $prodInfo->tti,
                    'exd' => $prodInfo->exd,
                    'hscode' => $prodInfo->hs_code
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }

    public function actionJqueryProdSearchRMS()
    {
        $results = [];
        $search_prodName = trim($_POST['search_prodName']);
        $product_cat = trim($_POST['product_cat']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);

		$criteria->condition = "item_id = '$product_cat'";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = 25;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM inventory where model_id = '$prodInfo->id' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing)) {
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);
                }

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);
                $unitPrice = $prodInfo->mrp;
                $vat = $prodInfo->vat;
                $vatType = $prodInfo->vat_calculation_method;
                $rebatable = $prodInfo->rebatable;
                $base_tp = $prodInfo->base_tp;
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'unitPrice' =>  $base_tp, //$unitPrice,
                    'vat' => $vat,
                    'vatType' => $vatType,
                    'rebatable' => $rebatable,
                    'closingStock' => $closingStock,
                    'cd' => $prodInfo->cd,
                    'sd' => $prodInfo->sd,
                    'rd' => $prodInfo->rd,
                    'ait' => $prodInfo->ait,
                    'atv' => $prodInfo->atv,
                    'tti' => $prodInfo->tti,
                    'exd' => $prodInfo->exd,
                    'hscode' => $prodInfo->hs_code
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }
	
	public function actionJqueryProdSearch2()
    {
        $results = [];
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
		$criteria->condition = "item_id = 2";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = 25;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {

                // closing stock
                $sql = "SELECT SUM(stock_in) as stockIn, SUM(stock_out) as stockOut FROM inventory where model_id = '$prodInfo->id' order by id";
                $closing = Yii::app()->db->createCommand($sql)->queryRow();

                $closingStock = '';
                if (!empty($closing)) {
                    $closingStock = round($closing['stockIn'] - $closing['stockOut']);
                }

                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $unitId = $prodInfo->unit_id;
                $unitName = Units::model()->unitLabel($unitId);
                $unitPrice = $prodInfo->mrp;
                $vat = $prodInfo->vat;
                $vatType = $prodInfo->vat_calculation_method;
                $rebatable = $prodInfo->rebatable;

                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'unitName' => $unitName,
                    'unitId' => $unitId,
                    'unitPrice' => $unitPrice,
                    'vat' => $vat,
                    'vatType' => $vatType,
                    'rebatable' => $rebatable,
                    'closingStock' => $closingStock,
                    'cd' => $prodInfo->cd,
                    'sd' => $prodInfo->sd,
                    'rd' => $prodInfo->rd,
                    'ait' => $prodInfo->ait,
                    'atv' => $prodInfo->atv,
                    'tti' => $prodInfo->tti,
                    'exd' => $prodInfo->exd,
                    'hscode' => $prodInfo->hs_code
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }

    public function actionItembycode()
    {
        $itemCode = trim($_POST['itemCode']);
        $condition = "code ='{$itemCode}'";
        $prodInfos = ProdModels::model()->findAll(array("condition" => $condition, 'order' => 'model_name ASC', 'group' => 'code'));
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $price = SellPrice::model()->getSellPriceByModelId($value);
                $label = CHtml::encode($prodInfo->model_name);
                $code = CHtml::encode($prodInfo->code);
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'code' => $code,
                    'price' => $price
                );
            }
        } else {
            $results[] = array('value' => "", 'label' => "No parts required");
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodCodeSearch()
    {
        $search_prodCode = trim($_POST['search_prodCode']);
        $results = array();
        $criteria = new CDbCriteria();
        $criteria->select = 'code';
        $criteria->compare("code", $search_prodCode, true);
        $criteria->limit = 30;
        $allModelCode = ProdModels::model()->findAll($criteria);
        if ($allModelCode) {
            foreach ($allModelCode as $prodCode) {
                $label = $value = CHtml::encode($prodCode->code);
                $results[] = array('value' => $value, 'label' => $label);
            }
        }

        echo json_encode($results);
    }

    public function actionProdInfoOfThisPmodel()
    {
        $pmodel = $_POST['pmodel'];
        $pbrand = $_POST['pbrand'];
        $color_id = $_POST['color_id'];
        $size_id = $_POST['size_id'];
        $prodList = '';

        if ($pmodel != '') {
            $condition = 'pmodel = ' . $pmodel . ' ORDER BY id DESC';
            $data = ProdModels::model()->findAll(array("condition" => $condition,), "id");
            if ($data) {
                $prodList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
                }
            } else {
                $prodList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        } else {
            $data = ProdModels::model()->findAll();
            if ($data) {
                $prodList = CHtml::tag('option', array('value' => ""), "Select", true);
                foreach ($data as $d) {
                    $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
                }
            } else {
                $prodList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
            }
        }
        echo CJSON::encode(array(
            'prodList' => $prodList,
        ));
    }

    public function actionProdInfoOfThisAdditionalOp()
    {
        $pbrand = $_POST['pbrand'];
        $pmodel = $_POST['pmodel'];
        $color_id = $_POST['color_id'];
        $size_id = $_POST['size_id'];
        $prodList = '';
        $condition = "id!=0";
        if ($pbrand != '')
            $condition .= ' AND pbrand = ' . $pbrand;
        if ($pmodel != '')
            $condition .= ' AND pmodel = ' . $pmodel;
        if ($color_id != '')
            $condition .= ' AND color_id = ' . $color_id;
        if ($size_id != '')
            $condition .= ' AND size_id = ' . $size_id;
        $condition .= " ORDER BY id DESC";
        $data = ProdModels::model()->findAll(array("condition" => $condition,), "id");
        if ($data) {
            $prodList = CHtml::tag('option', array('value' => ''), 'Select', true);
            foreach ($data as $d) {
                $prodList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->model_name . " (" . Units::model()->unitLabel($d->unit_id) . ")"), true);
            }
        } else {
            $prodList = CHtml::tag('option', array('value' => ''), CHtml::encode("NULL"), true);
        }

        echo CJSON::encode(array(
            'prodList' => $prodList,
        ));
    }

    public function actionReset()
    {

        echo CJSON::encode(array(
            'modelValue' => $this->renderPartial('allProdModels', true, true),
            'brandValue' => $this->renderPartial('allProdBrands', true, true),
            'itemValue' => $this->renderPartial('allProdItems', true, true),
        ));
    }

    public function actionBrandOfThisItemFromOutside()
    {
        $item_id = $_POST['ProdModels']['item_id']; /* Works */
        if ($item_id != '') {
            $condition = 'item_id = ' . $item_id . ' ORDER by id DESC';
            $data = ProdBrands::model()->findAll(array("condition" => $condition,), "id");
            echo CHtml::tag('option', array('value' => ''), 'Select Sub-Category', true);
            foreach ($data as $d) {
                echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->brand_name), true);
            }
        } else {
            $data = ProdBrands::model()->findAll();
            echo CHtml::tag('option', array('value' => ""), "Select Sub-Category", true);
            foreach ($data as $d) {
                echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->brand_name), true);
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionCreateProdModelsFromOutSide()
    {
        $model = new ProdModels;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ProdModels'])) {
            $model->attributes = $_POST['ProdModels'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = ProdModels::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Product successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->model_name . " (" . Units::model()->unitLabel($data->unit_id) . ")",
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreateProdModelsFromOutSide2()
    {
        $model = new ProdModels();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($model));

        if (isset($_POST['ProdModels'])) {
            $model->attributes = $_POST['ProdModels'];

            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {

                    $itemName = ProdItems::model()->itemName($model->item_id);
                    $brandName = ProdBrands::model()->brandName($model->brand_id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Product successfully added</div>",
                        'value' => $model->id,
                        'label' => $model->model_name,
                        'prodCode' => $model->code,
                        'itemId' => $model->item_id,
                        'brandId' => $model->brand_id,
                        'brandName' => $brandName,
                        'itemName' => $itemName,
                    ));
                    exit;
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            echo CJSON::encode(array(
                'status' => 'failure',
                'div' => $this->renderPartial('_form3', array('model' => $model), true, true)));
            exit;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new ProdModels;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($model));

        if (isset($_POST['ProdModels'])) {
            $model->attributes = $_POST['ProdModels'];
            $qty = $_POST['ProdModels']['qty'] != '' ? $_POST['ProdModels']['qty'] : '';
            //$model->sub_vat_id = $_POST['ProdModels']['sub_vat_id'] ;
            //$model->vat = $_POST['ProdModels']['vat'] ;
            $valid = $model->validate();

            if ($valid) {
                if ($model->save()) {
                    if ($qty != '') {
                        $modelInventory = new Inventory;
                        $modelInventory->model_id = $model->id;
                        $modelInventory->unit_id = $model->unit_id;
                        $modelInventory->stock_in = $qty;
                        $modelInventory->transaction_date = new CDbExpression('NOW()');
                        $modelInventory->save();
                    }
                }

                echo CJSON::encode(array(
                    'status' => 'success',
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate(array($model));
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        } else {
            $this->render('_form', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($model));

        if (isset($_POST['ProdModels'])) {
            $model->attributes = $_POST['ProdModels'];
            $model->unit_id = $_POST['ProdModels']['unit_id'];
            if ($model->save()) {
                $this->redirect(array('admin'));
            }
        }
        $this->render('_form2', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new ProdModels('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProdModels']))
            $model->attributes = $_GET['ProdModels'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminSellPrice()
    {
        $model = new ProdModels('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProdModels']))
            $model->attributes = $_GET['ProdModels'];

        $this->render('adminSellPrice', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = ProdModels::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'prod-models-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetProductDetailsByModelName()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ASC ";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = CHtml::encode($prodInfo->model_name);
                $item_id = CHtml::encode($prodInfo->item_id);
                $brand_id = CHtml::encode($prodInfo->brand_id);
                $code = CHtml::encode($prodInfo->code);
                $sell_price = SellPrice::model()->getSellPriceByModelId($value);
                $results[] = array('value' => $value, 'label' => $label, 'item_id' => $item_id, 'brand_id' => $brand_id, 'code' => $code, 'sell_price' => $sell_price);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'item_id' => 0, 'brand_id' => 0, 'code' => 0, 'sell_price' => 0);
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodSearch1()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addCondition(" item_id =1 ");
        $criteria->order = " model_name ASC ";
        $criteria->limit = 30;
        $criteria->group = " model_name ASC ";
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $unit = '';
                $value = $prodInfo->id;
                $label = CHtml::encode($prodInfo->model_name);
                if ($prodInfo->unit_id > 0) {
                    $unit = Units::model()->unitLabel($prodInfo->unit_id);
                }
                $results[] = array('value' => $value, 'label' => $label, 'unit' => $unit);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'unit' => '');
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodSearch3()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addColumnCondition(['item_id' => 1]);
        $criteria->addCondition(" brand_id !=7 ");
        $criteria->limit = 30;
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ASC ";
        $prodInfos = ProdModels::model()->findAll($criteria);

        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $unit = '';
                $str = '';
                $value = $prodInfo->id;
                $label = CHtml::encode($prodInfo->model_name);
                /*$unitModel = Units::model()->findByPk($prodInfo->unit_id);
                if ($unitModel) {
                    $categoryId = $unitModel->category_id;
                    if ($categoryId > 0) {
                        $unitOfCategory = Units::model()->findAll(array("condition" => "category_id = '$categoryId' "));
                        $str .= "<option value='0'>Select Unit</option>";
                        foreach ($unitOfCategory as $AreaObj) {
                            $str .= CHtml::tag('option', array('value' => $AreaObj->id), $AreaObj->label, true);
                        }
                    }
                }*/
                $results[] = array('value' => $value, 'label' => $label, 'unit' => $prodInfo->unit_id);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'unit' => '');
        }
        echo json_encode($results);
    }

    public function actionJquery_showFinishedGoodsProdSearch()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addColumnCondition(['item_id' => 1]);
        $criteria->limit = 30;
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ASC ";
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $unit = '';
                $value = $prodInfo->id;
                $label = CHtml::encode($prodInfo->model_name);
                if ($prodInfo->unit_id > 0) {
                    $unit = Units::model()->unitLabel($prodInfo->unit_id);
                }
                $results[] = array('value' => $value, 'label' => $label, 'unit' => $unit);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'unit' => '');
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodSearchSaleq()
    {
        $search_prodName = $_POST['search_prodName'];

        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ASC ";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = $prodInfo->model_name;
                $item_id = $prodInfo->item_id;
                $brand_id = $prodInfo->brand_id;
                $code = $prodInfo->code;
                $unit_id = $prodInfo->unit_id;
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'item_id' => $item_id,
                    'brand_id' => $brand_id,
                    'unit_id' => $unit_id,
                    'code' => $code,
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodCodeSearchSaleq()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        $criteria->compare("code", $search_prodName, true);
        $criteria->order = " model_name ASC ";
        $criteria->group = " model_name ASC ";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = $prodInfo->code;
                $item_id = $prodInfo->item_id;
                $brand_id = $prodInfo->brand_id;
                $model_name = $prodInfo->model_name;
                $code = $prodInfo->code;
                $results[] = array(
                    'value' => $value,
                    'label' => $label,
                    'item_id' => $item_id,
                    'brand_id' => $brand_id,
                    'model_name' => $model_name,
                    'code' => $code,
                );
            }
        } else {
            $results[] = array('value' => "0", 'label' => "No product found!");
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodSearchWithPrice()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        if (isset($_POST['type'])) {
            $criteria->addCondition("item_id != 1");
        } else {
			$criteria->addCondition("item_id=2");
            $item_con = ' ';
        }
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addCondition("brand_id!=7");
        //$condition = "model_name like '%$search_prodName%' AND brand_id!=7 $item_con";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = "30";
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                //$sql = "SELECT (SUM(IFNULL(unit_price,1)) / SUM(IFNULL(order_qty,1))) AS avg_price FROM purchase_details WHERE model_id = $prodInfo->id GROUP BY model_id ORDER BY id DESC";
                $sql = "SELECT ( SUM(IFNULL(unit_price,1)) / count(id) ) AS avg_price FROM purchase_details WHERE model_id = $prodInfo->id GROUP BY model_id ORDER BY id DESC";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $data = $command->queryRow();
                if (!$data['avg_price']) {
                    $data['avg_price'] = 0;
                }
                $value = $prodInfo->id;
                $label = str_replace("''", '"', $prodInfo->model_name);
                $unitId = $prodInfo->unit_id;
                $str = '';
                $unitModel = Units::model()->findByPk($unitId);
                if ($unitModel) {
                    $categoryId = $unitModel->category_id;
                    if ($categoryId > 0) {
                        $unitOfCategory = Units::model()->findAll(array("condition" => "category_id = '$categoryId'", 'order' => 'label asc'));
                        $str .= "<option value='0'>Select Unit</option>";
                        foreach ($unitOfCategory as $AreaObj) {
                            $str .= CHtml::tag('option', array('value' => $AreaObj->id), $AreaObj->label, true);
                        }
                    }
                }
                $results[] = array('value' => $value, 'label' => $label, 'avg_price' => round($data['avg_price'], 2), 'unit_id' => $unitId, 'unit' => $str);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'avg_price' => 0);
        }
        echo json_encode($results);
    }

    public function actionJqueryProdSearchWithPrice()
    {
        $search_prodName = trim($_POST['search_prodName']);
        $criteria = new CDbCriteria();
        if (isset($_POST['type'])) {
            $criteria->addCondition("item_id != 1");
        } else {
			$criteria->addCondition("item_id=2");
            $item_con = ' ';
        }
        $criteria->compare("model_name", $search_prodName, true);
        $criteria->addCondition("brand_id!=7");
        //$condition = "model_name like '%$search_prodName%' AND brand_id!=7 $item_con";
        $criteria->order = "model_name ASC";
        $criteria->group = "model_name";
        $criteria->limit = "30";
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                //$sql = "SELECT (SUM(IFNULL(unit_price,1)) / SUM(IFNULL(order_qty,1))) AS avg_price FROM purchase_details WHERE model_id = $prodInfo->id GROUP BY model_id ORDER BY id DESC";
                $sql = "SELECT ( SUM(IFNULL(unit_price,1)) / count(id) ) AS avg_price FROM purchase_details WHERE model_id = $prodInfo->id GROUP BY model_id ORDER BY id DESC";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $data = $command->queryRow();
                if (!$data['avg_price']) {
                    $data['avg_price'] = 0;
                }
                $value = $prodInfo->id;
                $label = str_replace("''", '"', $prodInfo->model_name);
                $unitId = $prodInfo->unit_id;
                $str = '';
               /* $unitModel = Units::model()->findByPk($unitId);
                if ($unitModel) {
                    $categoryId = $unitModel->category_id;
                    if ($categoryId > 0) {
                        $unitOfCategory = Units::model()->findAll(array("condition" => "category_id = '$categoryId'", 'order' => 'label asc'));
                        $str .= "<option value='0'>Select Unit</option>";
                        foreach ($unitOfCategory as $AreaObj) {
                            $str .= CHtml::tag('option', array('value' => $AreaObj->id), $AreaObj->label, true);
                        }
                    }
                }*/
                $results[] = array('value' => $value, 'label' => $label, 'avg_price' => 0, 'unit_id' => $unitId, 'unit' => $str);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available', 'avg_price' => 0);
        }
        echo json_encode($results);
    }

    public function actionJquery_showprodSearch2()
    {
        $search_prodName = trim($_POST['search_prodCode']);
        $criteria = new CDbCriteria();
        $criteria->compare("code", $search_prodName, true);
        $criteria->order = " code ASC ";
        $criteria->group = " code ASC ";
        $criteria->limit = 30;
        $prodInfos = ProdModels::model()->findAll($criteria);
        if ($prodInfos) {
            foreach ($prodInfos as $prodInfo) {
                $value = $prodInfo->id;
                $label = $prodInfo->code;
                $results[] = array('value' => $value, 'label' => $label);
            }
        } else {
            $results[] = array('value' => 0, 'label' => ' No Product Available');
        }
        echo json_encode($results);
    }

    public function actionAdminManualPurchasePrice()
    {
        $model = new ProdModels('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProdModels']))
            $model->attributes = $_GET['ProdModels'];

        $this->render('adminManualPurchaselPrice', array(
            'model' => $model,
        ));
    }

    public function actionSubVatOfVat()
    {
        $vatId = $_POST['subVatId'];
        $subVatList = '';

        if ($vatId != '' && $vatId == 1) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_standard'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        } else if ($vatId != '' && $vatId == 2) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_reducing'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        } else if ($vatId != '' && $vatId == 3) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_fixed'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        } else if ($vatId != '' && $vatId == 4) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_non_vatable'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        } else if ($vatId != '' && $vatId == 5) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_zero'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        } else if ($vatId != '' && $vatId == 6) {

            $data = Lookup::model()->findAll(array('condition' => "type= 'vat_calculation_method_mrp'", 'order' => 'code ASC'));
            if ($data) {
                $subVatList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $subVatList .= CHtml::tag('option', array('value' => $d->code), CHtml::encode($d->name), true);
                }
            }
        }
        echo CJSON::encode(array(
            'subVatList' => $subVatList,
        ));
    }


    public function actionUploadFile()
    {
        $model = new ProdModels();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (isset($_POST['ProdModels'])) {
            $model->product_file = CUploadedFile::getInstance($model, 'product_file');
            if ($model->product_file) {
                if ($model->product_file->extensionName == "xls") {
                    $model->product_file->saveAs(Yii::app()->basePath . '/../upload/tempProduct_file/product_file.xls');
                    Yii::app()->user->setFlash('success', "Product Excel file uploaded.");
                    $this->redirect(array('UploadFile'));
                } else {
                    Yii::app()->user->setFlash('error', "Please upload a valid .xls file!");
                    $this->redirect(array('UploadFile'));
                }
            } else {
                Yii::app()->user->setFlash('error', "Please upload a valid .xls file!");
                $this->redirect(array('UploadFile'));
            }
        }

        $this->render('uploadFileData', array(
            'model' => $model,
        ));
    }

    public function actionProcessFileDataFinal()
    {

        $fileExist = file_exists(Yii::app()->basePath . '/../upload/tempProduct_file/product_file.xls');
        if ($fileExist) {

            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/tempProduct_file/product_file.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $zero_val = 0;

            $newlyInserted = 0;
            $alreadyInsertedProduct = 0;

            for ($row = 2; $row <= $highestRow; ++$row) {

                // $prodModelData = ProdModels::model()->findByAttributes(array('model_name' => trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()), 'code' => trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue()) ));

                // if ($prodModelData == '') {

                // $date1 = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                // $dateTimeObject = PHPExcel_Shared_Date::ExcelToPHPObject($date1);
                // $purchaseItems->purchase_date = $dateTimeObject->format('Y-m-d');

                $prodModel = new ProdModels();

                // category
                $prodItem = ProdItems::model()->findByAttributes(array('item_name' => trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue())));
                if (!$prodItem) {
                    $prodItem = new ProdItems();
                    $prodItem->item_name = trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $prodItem->save();
                }
                $prodModel->item_id = $prodItem->id;

                // sub category
                $prodBrand = ProdBrands::model()->findByAttributes(array('brand_name' => trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue())));
                if (!$prodBrand) {
                    $prodBrand = new ProdBrands();
                    $prodBrand->item_id = $prodItem->id;
                    $prodBrand->brand_name = trim($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $prodBrand->save(false);
                }
                $prodModel->brand_id = $prodBrand->id;

                $prodModel->model_name = trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
                $prodModel->hs_code = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
                $prodModel->code = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());

                // unit
                $unit = Units::model()->findByAttributes(array('label' => trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue())));
                if (!$unit) {
                    $unit = new Units();
                    $unit->unit_type = 0;
                    $unit->label = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $unit->save(false);
                }
                $prodModel->unit_id = $unit->id;

                // Manufacturer
                $manufacturer = GlobalManufacturers::model()->findByAttributes(array('manufacturer' => trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue())));
                if (!$manufacturer) {
                    $manufacturer = new GlobalManufacturers();
                    $manufacturer->manufacturer = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $manufacturer->save(false);
                }
                $prodModel->manufacturer_id = $manufacturer->id;

                // country
                $country = Countries::model()->findByAttributes(array('country' => trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue())));
                if (!$country) {
                    $country = new Countries();
                    $country->country = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $country->save(false);
                }
                $prodModel->country_id = $country->id;

                $prodModel->min_order_qty = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
                $prodModel->measurement = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());

                // measurement unit
                $measurement_unit = Units::model()->findByAttributes(array('label' => trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue())));
                if (!$measurement_unit) {
                    $measurement_unit = new Units();
                    $measurement_unit->unit_type = 0;
                    $measurement_unit->label = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $measurement_unit->save(false);
                }
                $prodModel->measurement_unit_id = $measurement_unit->id;

                $prodModel->weight = trim($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());

                // weight unit
                $weight_unit = Units::model()->findByAttributes(array('label' => trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue())));
                if (!$weight_unit) {
                    $weight_unit = new Units();
                    $weight_unit->unit_type = 0;
                    $weight_unit->label = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $weight_unit->save(false);
                }
                $prodModel->weight_unit_id = $weight_unit->id;

                $prodModel->warranty = trim($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
                $prodModel->mrp = trim($objWorksheet->getCellByColumnAndRow(14, $row)->getValue());

                if (trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue()) == 'FGS') {
                    $prodModel->vat_calculation_method = 1;
                    $prodModel->sub_vat_id = 81;
                    $prodModel->vat = trim($objWorksheet->getCellByColumnAndRow(15, $row)->getValue());
                }

                $prodModel->sd = trim($objWorksheet->getCellByColumnAndRow(16, $row)->getValue());
                $prodModel->cd = trim($objWorksheet->getCellByColumnAndRow(17, $row)->getValue());
                $prodModel->rd = trim($objWorksheet->getCellByColumnAndRow(18, $row)->getValue());
                $prodModel->ait = trim($objWorksheet->getCellByColumnAndRow(19, $row)->getValue());
                $prodModel->atv = trim($objWorksheet->getCellByColumnAndRow(20, $row)->getValue());
                $prodModel->tti = trim($objWorksheet->getCellByColumnAndRow(21, $row)->getValue());
                $prodModel->exd = trim($objWorksheet->getCellByColumnAndRow(22, $row)->getValue());

                // $fuelType = Lookup::model()->findByAttributes(array('type' => "fuel_type", 'name' => trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue())));
                // if (!$fuelType) {
                //     $fuelType = new Lookup();
                //     $fuelType->name = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());

                //     $sql = "SELECT MAX(code) as code FROM lookup WHERE `type` = 'fuel_type'";
                //     $data = Yii::app()->db->createCommand($sql)->queryRow();

                //     $fuelType->type = "fuel_type";
                //     $fuelType->code = $data['code'] + 1;
                //     $fuelType->save();
                // }
                // $prodModel->fuel_type = $fuelType->code;

                $prodModel->save(false);
                $newlyInserted++;


            }
            echo "Data Saved Successfully" . "<br>";
            echo "Newly Inserted Product : " . $newlyInserted . "<br>";
            // echo "Product Updated : " . $alreadyInsertedProduct . "<br>";
            unlink(Yii::app()->basePath . '/../upload/tempProduct_file/product_file.xls');

        } else {
            echo 'FILE NOT FOUND!';
        }

    }

    public function actionUpdateHsCode()
    {

    }


    public function actionUpdateProductData()
    {

        $fileExist = file_exists(Yii::app()->basePath . '/../upload/tdl_products.xls');
        if ($fileExist) {
            Yii::import('ext.phpexcel.PHPExcel', true);
            $objReader = new PHPExcel_Reader_Excel5;
            $objPHPExcel = $objReader->load("upload/tdl_products.xls");
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $newlyInserted = 0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $code = trim($objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
                $hs_code = trim($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());
                $vat_calc_method = trim($objWorksheet->getCellByColumnAndRow(5, $row)->getValue());
                $vat = trim($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
                $sd = trim($objWorksheet->getCellByColumnAndRow(7, $row)->getValue());
                $cd = trim($objWorksheet->getCellByColumnAndRow(8, $row)->getValue());
                $rd = trim($objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
                $ait = trim($objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
                $at = trim($objWorksheet->getCellByColumnAndRow(11, $row)->getValue());
                $tti = trim($objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
                $exd = trim($objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
                if ($code != "") {
                    $prodModel = ProdModels::model()->findByAttributes(array('code' => $code));
                    if ($prodModel) {
                        $vat_calc_method = $vat_calc_method != "" ? $vat_calc_method : null;
                        $vat = ($vat != "" && $vat != "EXD") ? $vat : 0;
                        $sd = $sd != "" ? $sd : 0;
                        $cd = $cd != "" ? $cd : 0;
                        $rd = $rd != "" ? $rd : 0;
                        $ait = $ait != "" ? $ait : 0;
                        $at = $at != "" ? $at : 0;
                        $tti = $tti != "" ? $tti : 0;
                        $exd = $exd == "YES" ? 1 : 0;

                        echo "row: $row, code: $code, hs_code: $hs_code, calc_method: $vat_calc_method, vat: $vat, sd: $sd, cd: $cd, rd: $rd, ait: $ait, at: $at, tti: $tti, exd: $exd";
                        echo "<br>";


                        $prodModel->hs_code = $hs_code;
                        $prodModel->vat_calculation_method = $vat_calc_method;
                        $prodModel->vat = $vat;
                        $prodModel->sd = $sd;
                        $prodModel->cd = $cd;
                        $prodModel->rd = $rd;
                        $prodModel->ait = $ait;
                        $prodModel->atv = $at;
                        $prodModel->tti = $tti;
                        $prodModel->exd = $exd;
                        $prodModel->save(false);
                    }
                }
            }
            echo "Data Saved Successfully" . "<br>";
            // echo "Newly Inserted Product : " . $newlyInserted . "<br>";
            // unlink(Yii::app()->basePath . '/../upload/tempProduct_file/product_file.xls');

        } else {
            echo 'FILE NOT FOUND!';
        }

    }


}
