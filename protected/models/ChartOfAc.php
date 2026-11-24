<?php

class ChartOfAc extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public $returnArray;
    public $opening_acc_dep;
    public $opening_amount_date;
    public $tdr;
    public $division_id;
	
	public $head_type;
	public $is_ignore_report;

    public function init()
    {
        parent::init();
        $this->returnArray = array();
    }

    public function tableName()
    {
        return 'chart_of_ac';
    }

    const PARENT_CA = 1;
    const CHILD_CA = 2;
    const DEBIT = 3;
    const CREDIT = 4;
    const MAIN_ROOT = 0;

    const DOLLAR = 1;
    const EURO = 2;
    const TAKA = 3;
    const YUAN = 3;

    public $maxval;

    const ASSET = 1;
    const LIABILITY = 2;
    const INCOME = 3;
    const EXPENSE = 4;
    const EQUITY = 5;
    const FIXED_ASSET = 6;
    const CURRENT_ASSET = 7;
    const INVESTMENT = 8;
    const CAPITAL_AC = 9;
    const CURRENT_LA = 10;
    const LONG_TERM_LIABILITY = 11;
    const DIRECT_INCOME = 12;
    const INDIRECT_INCOME = 13;
    const DIRECT_EXPENSE = 15;
    const INDIRECT_EXPENSE = 16;
    const SALE = 14;
    const PURCHASE = 703;
    const ACCOUNT_RECEIVABLE = 20;
    const ACCOUNT_PAYABLE = 19;
    const BANK = 157;
    const CASH = 156;
    const PROFIT_LOSS_AC = 255;
    const PROVISION_FOR_INCOME_TAX = 420;
    const DEPRICIATION_CHARGE_OE = 463;
    const DEPRICIATION_CHARGE_AO = 556;
    const FINANCIAL_EXPENSE = 252;


    const BANK_CASH_AC = 1;
    const CASH_IN_HAND = 2865;

    const BANK_ROOT = 157;

    const SUPPLIERS = 19;
    const CUSTOMERS = 577;
    const INVENTORY_HEADS = 266;
    const ADVANCE_FROM_CUSTOMER = 595;
    const SHORT_TERM_LOAN = 832;
    const SHARE_MONEY_DEPOSIT = 1443;
    const MARKETING_SELLING_DIST = 251;
    const ADMINISTRATIVE_EXPENSE = 251;
    const PROPERTY_PLANT_EQUIPMENT = 1043;
    const CASH_IN_HAND_ALL_SC = 2955;
    const CASH_AND_EQUIVALENT = 2864;

    public function rules() {
        return array(
            array('title', 'required'),
            array('dep, dep_start_dt', 'required', 'on' => 'depSetupScenario'),
            array('root', 'isEmpty'),
            array('root, account_type, transaction_type, create_by, update_by, bank_or_cash, code, head_type, is_ignore_report', 'numerical', 'integerOnly' => true),
            array('opening_amount, dep, opening_acc_dep, tdr', 'numerical'),
            array('title, remarks', 'length', 'max' => 255),
            array('create_time, update_time, dep_start_dt, opening_amount_date, tdr', 'safe'),
            array('id, code, opening_acc_dep, title, opening_amount_date, tdr, remarks, root, bank_or_cash, account_type, opening_amount, transaction_type, dep, dep_start_dt, create_by, create_time, update_by, update_time, head_type', 'safe', 'on' => 'search'),
        );
    }

    public function isEmpty() {
        if ($this->root === self::MAIN_ROOT) {
            $this->addError('root', 'Root cannot be blank.');
        }
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'title' => 'Title',
            'remarks' => 'Remarks',
            'root' => 'Root',
            'bank_or_cash' => 'Account Type (Bank/Cash)',
            'account_type' => 'Delete/Editable',
            'opening_amount' => 'Opening Amount',
            'transaction_type' => 'Opening Amount',
            'head_type' => 'Head Type',
            'tdr' => 'Tax Depreciation Rate',
            'create_by' => 'Created By',
            'create_time' => 'Created Time',
            'update_by' => 'Updated By',
            'update_time' => 'Updated Time',
            'dep' => 'Depreciation(%)',
            'opening_acc_dep' => 'Opening Account Depreciation',
            'dep_start_dt' => 'Dep. Start<br>(Entry Must Be After This Date)',
        );
    }

    public function getParentGroup() {
        $val = self::model()->nameOfThis($this->root);
        return $val;
    }

    public function beforeSave()
    {
        //$this->account_type = self::CHILD_CA;
        $this->title = strtoupper($this->title);
        if ($this->isNewRecord) {
            $this->create_time = new CDbExpression('NOW()');
            $this->create_by = Yii::app()->user->getId();
        } else {
            $this->update_time = new CDbExpression('NOW()');
            $this->update_by = Yii::app()->user->getId();
        }
        return parent::beforeSave();
    }

    public static function showchildaclistbyroot($id) {
        $showchildacarray = array();
        $condition = "root=" . $id;
        $divisionId = Users::model()->getDivisionId();
        $divisionCon = "";
        if($divisionId>0){
            $divisionCon = " and division_id = '$divisionId' ";
        }

        $data = self::model()->findAll(array('condition' => $condition.$divisionCon), 'id');
        if ($data) {
            foreach ($data as $d) {
                $showchildacarray[$d->id] = $d->title;
                //$showchildacarray[$d->id] = $d->id;
            }
        }

        return $showchildacarray;
    }

    public function findMainCAofThis($id)
    {
        $data = self::model()->findByPk($id);
        if ($data) {
            if ($data->root == self::MAIN_ROOT) {
                return $data->id;
            } else {
                return self::model()->findMainCAofThis($data->root);
            }
        }
    }

    public function transTypeColor($transaction_type, $opening_amount) {
        if ($transaction_type == self::DEBIT && $opening_amount != 0) {
            echo "<span class='greenColor'>" . $opening_amount . " Dr</b></span>";
        } else if ($transaction_type == self::CREDIT && $opening_amount != 0) {
            echo "<span class='redColor'>" . $opening_amount . " Cr</b></span>";
        } else {
            echo "0";
        }
    }

    public function nameOfThis($id) {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->title;
    }

    public function idOfThisCode($code) {
        $data = self::model()->findByAttributes(array('code' => $code));
        if ($data)
            return $data->id;
    }

    public function getCodeById($id) {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->code;
    }

    public function subItemOfThis($id, $startDate, $endDate) {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        if ($data) {
            echo "<ul class='specialUl'>";
            foreach ($data as $d):
                echo "<li>";
                echo "<span class='specialUlLeft'>" . $d->title . "</span>";
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2,), 'id');
                if ($data2) {
                    self::model()->subItemOfThis($d->id, $startDate, $endDate);
                } else {
                    $criteriaJournal = new CDbCriteria();
                    $criteriaJournal->select = "amount, transaction_type";
                    $criteriaJournal->addColumnCondition(array('ca_id' => $d->id), 'AND', 'AND');
                    $criteriaJournal->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
                    $dataJournal = JournalEntry::model()->findAll($criteriaJournal);
                    $drAmount = 0;
                    $crAmount = 0;
                    if ($dataJournal) {
                        foreach ($dataJournal as $dj):
                            if ($dj->transaction_type == JournalEntry::DEBIT) {
                                $drAmount = $drAmount + $dj->amount;
                            } else {
                                $crAmount = $crAmount + $dj->amount;
                            }
                        endforeach;
                    }
                    $balance = $drAmount - $crAmount;
                    echo "<span class='specialUlRight'>";
                    echo " " . number_format(abs($balance), 2);
                    echo "</span>";
                }
                echo "</li>";
            endforeach;
            echo "</ul>";
        } else {
            echo "<span class='specialUlRight'>";
            $criteriaJournal2 = new CDbCriteria();
            $criteriaJournal2->select = "amount, transaction_type";
            $criteriaJournal2->addColumnCondition(array('ca_id' => $id), 'AND', 'AND');
            $criteriaJournal2->addBetweenCondition('entry_date', $startDate, $endDate, 'AND');
            $dataJournal2 = JournalEntry::model()->findAll($criteriaJournal2);
            $drAmount2 = 0;
            $crAmount2 = 0;
            if ($dataJournal2) {
                foreach ($dataJournal2 as $dj2):
                    if ($dj2->transaction_type == JournalEntry::DEBIT) {
                        $drAmount2 = $drAmount2 + $dj2->amount;
                    } else {
                        $crAmount2 = $crAmount2 + $dj2->amount;
                    }
                endforeach;
            }
            $balance2 = $drAmount2 - $crAmount2;
            echo " " . number_format(abs($balance2), 2);
            echo "</span>";
        }
    }

    public function isTheSameItem($id) {
        $data = self::model()->findByPk($id);
        if ($data)
            return $data->id;
    }

    public static function childDropdownList($root_id) {
        $condition = "root=$root_id";
        $divisionId = Users::model()->getDivisionId();
        $divisionCon = "";
        if($divisionId>0){
            $divisionCon = " and division_id = '$divisionId' ";

        }

        $data = CHtml::listData(self::model()->findAll(array('condition' => "$condition.$divisionCon", 'order' => 'title asc')), 'id', 'title');
        if ($data)
            return $data;
    }

    public static function divisionIdOfThis($id)
    {
        $data = self::model()->findByPk($id);
        $divisionId = 0;
        if ($data) {
            $divisionId = $data->division_id;
        }
        return $divisionId;
    }

    public function incomes() {
        $condition = "root=" . ChartOfAc::INCOME;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data)
            return $data;
    }

    public function expenses() {
        $condition = "root=" . ChartOfAc::EXPENSE;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data)
            return $data;
    }

    public function ownersEquety() {
        $condition = "root=" . ChartOfAc::EQUITY;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data)
            return $data;
    }

    public function assets() {
        $condition = "root=" . ChartOfAc::ASSET;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data)
            return $data;
    }

    public function liabilities() {
        $condition = "root=" . ChartOfAc::LIABILITY;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data)
            return $data;
    }

    public function allRootsWithSubRoots($itemID, $id)
    {
        $isTheSameItem = self::isTheSameItem($id);
        $condition = "root=" . self::MAIN_ROOT;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        echo "<ul id='myUL' class='tree'>";
        foreach ($data as $d):
            if ($itemID != "") {
                if ($d->id == $itemID) {
                    echo '<li><input checked="checked" name="ChartOfAc[root]" type="radio" value="' . $d->id . '"> '. " (" . $d->code . ") " . $d->title . '</li>';
                } else if ($d->id == $isTheSameItem) {
                    echo '<li><input style="display: none;" type="radio"><span class="disabledItem"></span>' . " (" . $d->code . ") " . $d->title  . '</li>';
                } else {
                    echo '<li><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . " (" . $d->code . ") " . $d->title  . '</li>';
                }
            } else {
                if($d->id == 1 || $d->id == 2 || $d->id == 3){
                    echo '<li><b> </b>' . " (" . $d->code . ") " . $d->title  . '</li>';
                }
                else{
                    echo '<li><b><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '"></b>'. " (" . $d->code . ") " . $d->title  . '</li>';
                }
            }
            self::model()->recursiveDropDownListFunc($d->id, $itemID, $isTheSameItem);
        endforeach;
        echo "</ul>";
    }

    public function recursiveDropDownListFunc($id, $itemID, $isTheSameItem)
    {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        if ($data) {
            echo "<ul class='nested tree'>";
            foreach ($data as $d):
                if ($itemID != "") {
                    if ($d->id == $itemID) {
                        echo '<li><input checked="checked" name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . " (" . $d->code . ") " . $d->title  . '</li>';
                    } else if ($d->id == $isTheSameItem) {
                        echo '<li><input style="display: none;" type="radio"><span class="disabledItem"></span>' . " (" . $d->code . ") " . $d->title . '</li>';
                    } else {
                        echo '<li><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . " (" . $d->code . ") ". $d->title  . '</li>';
                    }
                } else {
                    // if( substr($d->code, 0, 1) == 1 || substr($d->code, 0, 1) == 2 || substr($d->code, 0, 1) == 3){
                    //     echo '<li><b> </b>' . $d->title . " (" . $d->code . ") " . '</li>';
                    // }
                    // else{
                    echo '<li><b><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '"></b>' . " (" . $d->code . ") " . $d->title  . '</li>';
                    // }
                }
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2,), 'id');
                if ($data2) {
                    self::model()->recursiveDropDownListFunc($d->id, $itemID, $isTheSameItem);
                }
            endforeach;
            echo "</ul>";
        }
    }

    public function recursiveDropDownListFunc2($id, $itemID, $isTheSameItem,$company_id,$process_id)
    {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        if ($data) {
            echo "<ul class='root0'>";
            foreach ($data as $d):
                if ($itemID != "") {
                    if ($d->id == $itemID) {
                        echo '<li class="for_search" id="account_type_'.$d->account_type.'"><input checked="checked" name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . $d->title . " (" . $d->code . ") "  . " <span style='opacity:0'>" . $d->company_id.",".$d->process_id. "</span>" . '</li>';
                    } else if ($d->id == $isTheSameItem) {
                        echo '<li class="for_search" id="account_type_'.$d->account_type.'"><input style="display: none;" type="radio"><span class="disabledItem"></span>' . $d->title . " (" . $d->code . ") " . " <span style='opacity:0'>" . $d->company_id.",".$d->process_id . "</span>" . '</li>';
                    } else {
                        echo '<li class="for_search" id="account_type_'.$d->account_type.'"><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . $d->title . " (" . $d->code . ") " . "<span style='opacity:0'>" . $d->company_id.",".$d->process_id . "</span>" . '</li>';
                    }
                } else {
                    echo '<li class="for_search" id="account_type_'.$d->account_type.'"><input name="ChartOfAc[root]" type="radio" value="' . $d->id . '">' . $d->title . " (" . $d->code . ") " . "<span style='opacity:0'>" . $d->company_id.",".$d->process_id . "</span> " . '</li>';
                }
                $condition2 = "root=" . $d->id;
                // $condition2 = "root=" . $d->id . " AND process_id = $process_id AND company_id = $company_id";
                $data2 = self::model()->findAll(array('condition' => $condition2,), 'id');
                if ($data2) {
                    self::model()->recursiveDropDownListFunc2($d->id, $itemID, $isTheSameItem,$company_id,$process_id);
                }
            endforeach;
            echo "</ul>";
        }
    }

    public function caWithNoChild() {
        $values = array();
        $divisionId = Users::model()->getDivisionId();
        $divisionCon = "";
        if($divisionId>0){
            $divisionCon = " and division_id = '$divisionId' ";
        }

        $allIds = self::model()->findAll(array('condition' => 'root!=' . self::MAIN_ROOT.$divisionCon));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'root!=' . self::MAIN_ROOT;
            $criteria->order = "id ASC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data)
                return $data;
        }
    }

    public function caExpenseWithNoChild($expenseHead) {
        $values = array();
        $allIds = self::model()->findAll(array('condition' => 'root!=' . self::MAIN_ROOT));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'root='. $expenseHead . ' and root!='.  self::MAIN_ROOT;
            $criteria->order = "id ASC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data)
                return $data;
        }
    }

    public function caExpenseEntry() {
        $values = array();
        $allIds = self::model()->findAll(array('condition' => 'root!=' . self::MAIN_ROOT));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'root=' . self::MAIN_ROOT;
            $criteria->order = "id ASC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data)
                return $data;
        }
    }

    public function caWithNoChildsRoots() {
        $values = array();
        $values2 = array();
        $allIds = self::model()->findAll(array('condition' => 'root!=' . self::MAIN_ROOT));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'root!=' . self::MAIN_ROOT;
            $criteria->order = "id ASC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data) {
                foreach ($data as $d):
                    $values2[] = $d->root;
                endforeach;

                $criteria2 = new CDbCriteria();
                $criteria2->order = "title ASC";
                $criteria2->addInCondition('id', $values2, 'AND');
                $data2 = self::model()->findAll($criteria2);
                if ($data2)
                    return $data2;
            }
        }
    }

    public function allParentsAndSubParents()
    {
        $values = array();
        $values2 = array();
        $allIds = self::model()->findAll(array('condition' => 'root!=' . self::MAIN_ROOT));
        if ($allIds) {
            foreach ($allIds as $ids) :
                $sql = "SELECT * FROM chart_of_ac WHERE root = $ids->root";
                $connection = Yii::app()->db;
                $command = $connection->createCommand($sql);
                $info = $command->queryRow();
                if($info){
                    continue;
                }
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'root!=' . self::MAIN_ROOT;
            $criteria->order = "id ASC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data) {
                foreach ($data as $d) :
                    $values2[] = $d->root;
                endforeach;
                $criteria2 = new CDbCriteria();
                $criteria2->order = "title ASC";
                $criteria2->addInCondition('id', $values2, 'AND');
                $data2 = self::model()->findAll($criteria2);
                if ($data2)
                    return $data2;
            }
        }
    }

    public function bankCashAC() {
        $emptyArray = array();
        $values = array();
        $condition = "bank_or_cash=" . self::BANK_CASH_AC . " AND root!=" . self::MAIN_ROOT;
        $allIds = self::model()->findAll(array('condition' => $condition));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'bank_or_cash=' . self::BANK_CASH_AC . ' AND root!=' . self::MAIN_ROOT;
            $criteria->order = "id DESC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data)
                return $data;
            else
                return $emptyArray;
        } else
            return $emptyArray;
    }

    public function notBankCashAC() {
        $values = array();
        $condition = "bank_or_cash!=" . self::BANK_CASH_AC . " AND root!=" . self::MAIN_ROOT;
        $allIds = self::model()->findAll(array('condition' => $condition));
        if ($allIds) {
            foreach ($allIds as $ids):
                $values[] = $ids->root;
            endforeach;
            $criteria = new CDbCriteria();
            $criteria->condition = 'bank_or_cash!=' . self::BANK_CASH_AC . ' AND root!=' . self::MAIN_ROOT;
            $criteria->order = "id DESC";
            $criteria->addNotInCondition('id', $values, 'AND');
            $data = self::model()->findAll($criteria);
            if ($data)
                return $data;
        }
    }

    public function showBankListBySalesOffice($salesOfficeId = null)
    {
        $showchildacarray = array();
        $condition = "root= 157";
        if ($salesOfficeId > 0) {
            $condition .= " and sales_office_id = '$salesOfficeId' ";
        }
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $showchildacarray[$d->id] = $d->title;
                //$showchildacarray[$d->id] = $d->id;
            }
        }

        return $showchildacarray;
    }


    public function showBankName($id, $bank_id)
    {
        $bankname = '';
        $data = self::model()->findByAttributes(array('root' => $id, 'id' => $bank_id));
        if ($data) {
            $bankname = $data->title;
        }
        return $bankname;
    }


    public function isBankOrCashAC($bank_or_cash) {
        if ($bank_or_cash == self::BANK_CASH_AC) {
            echo "<font style='color: red;'>BANK/CASH AC</font>";
        } else {
            echo "";
        }
    }

    public function isEditDeletable($account_type) {
        if ($account_type == self::PARENT_CA) {
            echo "<font style='color: red;'>NO</font>";
        } else {
            echo "<font style='color: green;'>YES</font>";
        }
    }

    public function noChild($id, &$noChildCAidsArr) {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2), 'id');
                if ($data2) {
                    foreach ($data2 as $d2) {
                        self::model()->noChild($d2->id, $noChildCAidsArr);
                    }
                } else {
                    $noChildCAidsArr[] = $d->id;
                }
            }
        } else {
            $noChildCAidsArr[] = $id;
        }

        return $noChildCAidsArr;
    }

    public function noChild2($id, &$noChildCAidsArr2, $notequalTo, $notequalTo2, $notequalTo3 = null)
    {
        $condition = "root=" . $id . " AND root!=" . $notequalTo . " AND root!=" . $notequalTo2 . " AND id!=" . $notequalTo . " AND id!=" . $notequalTo2;
        if ($notequalTo3 != null){
            $condition .=  " AND id!=" . $notequalTo3;
        }
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $condition2 = "root=" . $d->id . " AND root!=" . $notequalTo . " AND id!=" . $notequalTo;
                $data2 = self::model()->findAll(array('condition' => $condition2), 'id');
                if ($data2) {
                    foreach ($data2 as $d2) {
                        self::model()->noChild2($d2->id, $noChildCAidsArr2, $notequalTo, $notequalTo2);
                    }
                } else {
                    $noChildCAidsArr2[] = $d->id;
                }
            }
        } else {
            $noChildCAidsArr2[] = $id;
        }

        return $noChildCAidsArr2;
    }

    public function groupLedgers($id, $notequalTo, $notequalTo2, $notequalTo3 = null)
    {
        $groupLedgerIds = array();
        $condition = "root=" . $id . " AND id!=" . $notequalTo . " AND id!=" . $notequalTo2;
        if ($notequalTo3 != null){
            $condition .= " AND id!=" . $notequalTo3;
        }
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $groupLedgerIds[] = $d->id;
            }
        }

        return $groupLedgerIds;
    }

    public function groupLedgers2($id) {
        $groupLedgerIds = array();
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $groupLedgerIds[] = $d->id;
            }
        }

        return $groupLedgerIds;
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('bank_or_cash', $this->bank_or_cash);
        $criteria->compare('remarks', $this->remarks, true);
        $criteria->compare('root', $this->root);
        $criteria->compare('account_type', $this->account_type);
        $criteria->compare('opening_amount', $this->opening_amount);
        $criteria->compare('transaction_type', $this->transaction_type);
        //$criteria->compare('head_type', $this->head_type);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('dep', $this->dep);
        $criteria->compare('dep_start_dt', $this->dep_start_dt, true);
        $criteria->compare('opening_acc_dep', $this->opening_acc_dep, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'root ASC',
            ),
        ));
    }

    public function searchDepSetup() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $fixedAssetArr = array();
        $fixedAssetIds = self::model()->noChild(self::FIXED_ASSET, $fixedAssetArr);
        $criteria->addInCondition('id', $fixedAssetIds);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('opening_amount', $this->opening_amount);
        $criteria->compare('transaction_type', $this->transaction_type);
        $criteria->compare('head_type', $this->head_type);
        $criteria->compare('dep', $this->dep);
        $criteria->compare('dep_start_dt', $this->dep_start_dt, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'title ASC',
            ),
        ));
    }


    public function allCaIdUnderThisHead($ca_id)
    {
        $model = ChartOfAc::model()->findAll(array("condition" => "root = $ca_id", 'order' => 'id asc'));
        if ($model) {
            foreach ($model as $dt) {
                $returnArray = self::allCaIdUnderThisHead($dt->id);
                $returnArray[] = $dt->id;
            }
        }
        $returnArray [] = $ca_id;
        $returnArray = array_unique($returnArray); //remove if there is duplicate
        sort($returnArray); //sorting the array

        return $returnArray;
    }


    public function getAllRootsWithSubRootId($itemID)
    {
        $result[] = $itemID;
        $condition = "root=" . $itemID;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        foreach ($data as $d):
            $result [] = $d->id;
            $result[] = self::recursiveData($d->id);
        endforeach;
        $newResult =self::array_flatten($result);
        return $newResult;
    }

    public function recursiveData($itemID)
    {
        $result = [];
        $condition = "root=" . $itemID;
        $data = self::model()->findAll(array('condition' => $condition,), 'id');
        if ($data) {
            foreach ($data as $d):
                $result[] = $d->id;
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2,), 'id');
                if ($data2) {
                    self::recursiveData($d->id);
                }
            endforeach;
        }
        return $result;
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }
    public function noChildIdAndTitle($id, &$noChildCAidsArr) {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2), 'id');
                if ($data2) {
                    foreach ($data2 as $d2) {
                        self::model()->noChildIdAndTitle($d2->id, $noChildCAidsArr);
                    }
                } else {
                    $arr = array('label'=>$d->title,'value'=>$d->id);
                    $noChildCAidsArr[] = $arr;
                }
            }
        } else {
            $selfData = self::model()->findByPk($id);
            $arr = array('label'=>$selfData->title,'value'=>$id);
            $noChildCAidsArr[] = $arr;
        }
        return $noChildCAidsArr;
    }
    public static function noChildIdAndTitleForDropDown($id, &$noChildCAidsArr) {
        $condition = "root=" . $id;
        $data = self::model()->findAll(array('condition' => $condition), 'id');
        if ($data) {
            foreach ($data as $d) {
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2), 'id');
                if ($data2) {
                    foreach ($data2 as $d2) {
                        self::model()->noChildIdAndTitle($d2->id, $noChildCAidsArr);
                    }
                } else {
                    $arr = array('label'=>$d->title,'value'=>$d->id);
                    $noChildCAidsArr[] = $arr;
                }
            }
        } else {
            $selfData = self::model()->findByPk($id);
            $arr = array('label'=>$selfData->title,'value'=>$id);
            $noChildCAidsArr[] = $arr;
        }
        return array_column($noChildCAidsArr,'label','value');
    }
    public function cashListUnderCashInHand()
    {
        $root = ChartOfAc::CASH_IN_HAND; // Cash
        $allIds = ChartOfAc::model()->findAll(array('condition' => "root=$root", 'order' => 'title ASC'));
        if (count($allIds) >= 1) {
            if (count($allIds) >= 1) {
                $data = CHtml::listData($allIds, 'id', 'title');
            } else {
                $resultBanks = self::model()->nameOfThis($root);
                $data = [$root => $resultBanks];
            }
            return $data;
        }
    }

    public function bankDropdownList()
    {
        $root = ChartOfAc::BANK_ROOT; // bank
        $allIds = ChartOfAc::model()->findAll(array('condition' => 'root=' . $root, 'order' => 'title ASC'));
        if (count($allIds) >= 1) {
            $data = CHtml::listData($allIds, 'id', 'title');
        } else {
            $resultBanks = self::model()->nameOfThis($root);
            $data = [$root => $resultBanks];
        }
        return $data;
    }

    // public static function childDropdownList($root_id)
    // {
    //     $condition = "root=$root_id";
    //     $data = CHtml::listData(self::model()->findAll(array('condition' => "$condition", 'order' => 'title asc')), 'id', 'title');
    //     if ($data)
    //         return $data;
    // }

    public function hasChild($ca_id){
        $criteria = new CDbCriteria();
        $criteria->condition= "root = $ca_id";
        $data = self::model()->findAll($criteria);
        if(count($data) > 0){
            return true;
        }
        return false;
    }
    public function getAllChildByParentId($ca_id, &$expensedHeadList)
    {
        $condition = "root=" . $ca_id;
        $data = self::model()->findAll(array('condition' => $condition));
        if ($data) {
            foreach ($data as $d){
                $condition2 = "root=" . $d->id;
                $data2 = self::model()->findAll(array('condition' => $condition2));
                if ($data2) {
                    foreach ($data2 as $d2) {
                        self::model()->getAllChildByParentId($d2->id, $expensedHeadList);
                    }
                }else{
                    $expensedHeadList[$d->id] = $d->title;
                }
            }
        }else{
            $ca_data = ChartOfAc::model()->findByPk($ca_id);
            $expensedHeadList[$ca_data->id] = $ca_data->title;
        }
        return $expensedHeadList;
    }

}