<?php

class JournalEntry extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'journal_entry';
    }

    const DEBIT = 3;
    const CREDIT = 4;

    public $maxval;
    public $total_dr;
    public $total_cr;
    public $temp_ca_id;
    public $temp_transaction_type;
    public $temp_amount;
    public $drcrdiff;
    public $startDate;
    public $endDate;
    public $payment_mode;
    public $bank;
    public $cash_bank;
    public $ca_bank;
    public $ca_head;
    public $ca_cash;

    const DELETABLE = 1;
    const NOT_DELETABLE = 0;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('entry_date, voucher_type', 'required'),
            array('is_deletable, entry_no, ca_id, opposite_ca_id, transaction_type, create_by, update_by, main_ca, voucher_type, name_by, closing_amount, is_canceled', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
//            array('narration', 'length', 'max' => 255),
            array('create_time, update_time, startDate, endDate, bill_no, narration, custom_invoice_no', 'safe'),
            array('is_deletable, id, voucher_type, narration, entry_no, entry_date, ca_id, opposite_ca_id, transaction_type, amount, closing_amount, create_by, create_time, update_by, update_time, name_by, startDate, endDate, bill_no, is_canceled, custom_invoice_no', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'voucher_type' => 'Type',
            'narration' => 'Narration',
            'entry_no' => 'Voucher No',
            'entry_date' => 'Entry Date',
            'ca_id' => 'Account Title',
            'cash_bank' => 'Cash/Bank',
            'opposite_ca_id' => 'Opposit CA',
            'transaction_type' => 'Amount',
            'amount' => 'Amount',
            'create_by' => 'Created By',
            'create_time' => 'Created Time',
            'update_by' => 'Updated By',
            'update_time' => 'Updated Time',
            'startDate' => 'Start',
            'endDate' => 'End',
            'name_by' => 'Name By',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'bill_no' => 'Bill No',
            'custom_invoice_no' => 'RMR/OMR NO',
        );
    }

    public function showIDByBillNo($bill_no)
    {
        $returnstr = 0;
        $data = Yii::app()->db->createCommand()
            ->select('id')
            ->from('journal_entry')
            ->where('bill_no=:id', array(':id' => $bill_no))
            ->queryRow();
        if ($data) {
            $returnstr = $data['id'];
        }
        return $returnstr;
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->create_time = new CDbExpression('NOW()');
            $this->create_by = Yii::app()->user->getId();
        } else {
            $this->update_time = new CDbExpression('NOW()');
            $this->update_by = Yii::app()->user->getId();
        }
        return parent::beforeSave();
    }

    public function monthCountAddOneDate($date1, $date2)
    {
        $date = new DateTime($date2);
        $date->modify('+1 day');

        $date2 = $date->format('Y-m-d');

        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $months = 0;

        while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
            $months++;

        return $months;
    }

    public function monthCount($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $months = 0;

        while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2)
            $months++;

        return $months;
    }

    public function voucherNoOfThis($entry_no, $voucher_type)
    {
        if ($voucher_type == VoucherNoFormateAc::JURNAL) { // journal voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::JURNAL);
        } else if ($voucher_type == VoucherNoFormateAc::CONTRA) { // contra voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::CONTRA);
        } else if ($voucher_type == VoucherNoFormateAc::PAYMENT) { // payment voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::PAYMENT);
        } else if ($voucher_type == VoucherNoFormateAc::CONTRA_RECEIPT) { // Contra Receipt voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::CONTRA_RECEIPT);
        } else if ($voucher_type == VoucherNoFormateAc::CONTRA_PAYMENT) { // Contra Receipt voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::CONTRA_PAYMENT);
        } else if ($voucher_type == VoucherNoFormateAc::CONTRA_BANK_TO_BANK) { // Contra Receipt voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::CONTRA_BANK_TO_BANK);
        } else { // receipt voucher
            $noType = VoucherNoFormateAc::model()->numberFormatOfThis(VoucherNoFormateAc::RECEIPT);
        }

        return $noType . $entry_no;
    }

    public function voucherTypeName($voucher_type)
    {
        if ($voucher_type == VoucherNoFormateAc::JURNAL) { // journal voucher
            $noType = "Journal";
        } else if ($voucher_type == VoucherNoFormateAc::CONTRA) { // contra voucher
            $noType = "Contra";
        } else if ($voucher_type == VoucherNoFormateAc::PAYMENT) { // payment voucher
            $noType = "Cash";
        } else { // receipt voucher
            $noType = "Bank";
        }

        return $noType;
    }

    public function transTypeColor($transaction_type, $amount)
    {
        if ($transaction_type == self::DEBIT) {
            echo "<span class='greenColor'>" . $amount . " Dr</b></span>";
        } else if ($transaction_type == self::CREDIT) {
            echo "<span class='redColor'>" . $amount . " Cr</b></span>";
        } else {
            echo "";
        }
    }

    public function incomeStatementPreviousDate($startDate)
    {
        $salesArr = array();
        $salesIds = ChartOfAc::model()->noChild(ChartOfAc::SALE, $salesArr);
        $totalSalesTotal = 0;
        if (!empty($salesIds)) {
            for ($i = 0; $i < count($salesIds); $i++) {
                $con1 = "entry_date < '" . $startDate . "' AND ca_id=" . $salesIds[$i];
                $data1 = JournalEntry::model()->findAll(array("condition" => $con1));
                $drAmountSales = 0;
                $crAmountSales = 0;
                if ($data1) {
                    foreach ($data1 as $d1):
                        if ($d1->transaction_type == JournalEntry::DEBIT) {
                            $drAmountSales = $drAmountSales + $d1->amount;
                        } else {
                            $crAmountSales = $crAmountSales + $d1->amount;
                        }
                    endforeach;
                }
                $totalSales = $drAmountSales - $crAmountSales;
                $totalSalesTotal = $totalSales + $totalSalesTotal;
            }
        }

        $directExpArr = array();
        $directExpIds = ChartOfAc::model()->noChild(ChartOfAc::DIRECT_EXPENSE, $directExpArr);
        $totalDirectExpTotal = 0;
        if (!empty($directExpIds)) {
            for ($i = 0; $i < count($directExpIds); $i++) {
                $con2 = "entry_date < '" . $startDate . "' AND ca_id=" . $directExpIds[$i];
                $data2 = JournalEntry::model()->findAll(array("condition" => $con2));
                $drAmountDirectExp = 0;
                $crAmountDirectExp = 0;
                if ($data2) {
                    foreach ($data2 as $d2):
                        if ($d2->transaction_type == JournalEntry::DEBIT) {
                            $drAmountDirectExp = $drAmountDirectExp + $d2->amount;
                        } else {
                            $crAmountDirectExp = $crAmountDirectExp + $d2->amount;
                        }
                    endforeach;
                }
                $totalDirectExp = $drAmountDirectExp - $crAmountDirectExp;
                $totalDirectExpTotal = $totalDirectExp + $totalDirectExpTotal;
            }
        }

        $grossOperatingProfitLoss = (abs($totalSalesTotal) - abs($totalDirectExpTotal));

        $indirectExpArr = array();
        $indirectExpIds = ChartOfAc::model()->noChild(ChartOfAc::INDIRECT_EXPENSE, $indirectExpArr);
        $totalIndirectExpTotal = 0;
        if (!empty($indirectExpIds)) {
            for ($i = 0; $i < count($indirectExpIds); $i++) {
                $con3 = "entry_date < '" . $startDate . "' AND ca_id=" . $indirectExpIds[$i];
                $data3 = JournalEntry::model()->findAll(array("condition" => $con3));
                $drAmountIndirectExp = 0;
                $crAmountIndirectExp = 0;
                if ($data3) {
                    foreach ($data3 as $d3):
                        if ($d3->transaction_type == JournalEntry::DEBIT) {
                            $drAmountIndirectExp = $drAmountIndirectExp + $d3->amount;
                        } else {
                            $crAmountIndirectExp = $crAmountIndirectExp + $d3->amount;
                        }
                    endforeach;
                }
                $totalIndirectExp = $drAmountIndirectExp - $crAmountIndirectExp;
                $totalIndirectExpTotal = $totalIndirectExp + $totalIndirectExpTotal;
            }
        }

        $operatingProfitLoss = ($grossOperatingProfitLoss - $totalIndirectExpTotal);

        $otherExpArr = array();
        $otherExpIds = ChartOfAc::model()->noChild2(ChartOfAc::EXPENSE, $otherExpArr, ChartOfAc::DIRECT_EXPENSE, ChartOfAc::INDIRECT_EXPENSE);
        $totalOtherExpTotal = 0;
        if (!empty($otherExpIds)) {
            for ($i = 0; $i < count($otherExpIds); $i++) {
                $con4 = "entry_date < '" . $startDate . "' AND ca_id=" . $otherExpIds[$i];
                $data4 = JournalEntry::model()->findAll(array("condition" => $con4));
                $drAmountOtherExp = 0;
                $crAmountOtherExp = 0;
                if ($data4) {
                    foreach ($data4 as $d4):
                        if ($d4->transaction_type == JournalEntry::DEBIT) {
                            $drAmountOtherExp = $drAmountOtherExp + $d4->amount;
                        } else {
                            $crAmountOtherExp = $crAmountOtherExp + $d4->amount;
                        }
                    endforeach;
                }
                $totalOtherExp = $drAmountOtherExp - $crAmountOtherExp;
                $totalOtherExpTotal = $totalOtherExp + $totalOtherExpTotal;
            }
        }

        $operatingProfitLoss = ($operatingProfitLoss - $totalOtherExpTotal);

        $otherIncomeArr = array();
        $otherIncomeIds = ChartOfAc::model()->noChild2(ChartOfAc::INCOME, $otherIncomeArr, ChartOfAc::SALE, ChartOfAc::SALE);
        $totalOtherIncomeTotal = 0;
        if (!empty($otherIncomeIds)) {
            for ($i = 0; $i < count($otherIncomeIds); $i++) {
                $con5 = "entry_date < '" . $startDate . "' AND ca_id=" . $otherIncomeIds[$i];
                $data5 = JournalEntry::model()->findAll(array("condition" => $con5));
                $drAmountOtherIncome = 0;
                $crAmountOtherIncome = 0;
                if ($data5) {
                    foreach ($data5 as $d5):
                        if ($d5->transaction_type == JournalEntry::DEBIT) {
                            $drAmountOtherIncome = $drAmountOtherIncome + $d5->amount;
                        } else {
                            $crAmountOtherIncome = $crAmountOtherIncome + $d5->amount;
                        }
                    endforeach;
                }
                $totalOtherIncome = $drAmountOtherIncome - $crAmountOtherIncome;
                $totalOtherIncomeTotal = $totalOtherIncome + $totalOtherIncomeTotal;
            }
        }

        $netProfitLoss = (abs($operatingProfitLoss) + abs($totalOtherIncomeTotal));

        $tax = ($netProfitLoss * 37.5) / 100;

        $netProfitLoss = $netProfitLoss - $tax;
        return $netProfitLoss;
    }

    public function totalDepr($startDate, $endDate)
    {
        $sl = 1;
        $wdvTotal = 0;
        $currentDepreciationTotal = 0;
        $fixedAssetArr = array();
        $fixedAssetIds = ChartOfAc::model()->noChild(ChartOfAc::FIXED_ASSET, $fixedAssetArr);
        if (!empty($fixedAssetIds)) {

            for ($i = 0; $i < count($fixedAssetIds); $i++) {
                $acInfo = ChartOfAc::model()->findByPk($fixedAssetIds[$i]);
                $acNameCode = $acInfo->title . " (" . $acInfo->code . ")";
                $depriciationRateOfAc = $acInfo->dep;
                $depreciationRate = $depriciationRateOfAc / 100;
                $depreciationRateStartDt = $acInfo->dep_start_dt;
                $openingAmountGiven = $acInfo->opening_amount;

                //if ($depriciationRateOfAc > 0 && $depreciationRateStartDt != "") {

                $date = date_create($startDate);
                date_sub($date, date_interval_create_from_date_string("1 days"));
                $endDateOP = date_format($date, "Y-m-d");

                $monthCountGivenForOP = (JournalEntry::model()->monthCount($depreciationRateStartDt, $endDateOP));
                $depreciationGivenForOP = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForOP;

                $openingCostOP = $openingAmountGiven;
                $openingDepreciationOP = $depreciationGivenForOP;

                $criteriaOP = new CDbCriteria();
                $criteriaOP->addBetweenCondition("entry_date", $depreciationRateStartDt, $endDateOP);
                $criteriaOP->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                $dataOP = JournalEntry::model()->findAll($criteriaOP);
                if ($dataOP) {
                    foreach ($dataOP as $dOP) {
                        $debitOP = 0;
                        $creditOP = 0;
                        if ($dOP->transaction_type == ChartOfAc::DEBIT) {
                            $debitOP = $dOP->amount;
                        } else {
                            $creditOP = $dOP->amount;
                        }
                        $balanceCostOP = $debitOP - $creditOP;
                        $closingCostOP = $openingCostOP + $balanceCostOP;

                        $monthCountOP = (JournalEntry::model()->monthCount($dOP->entry_date, $endDateOP));

                        $currentDepreciationOP = (($debitOP * $depreciationRate) / 12) * $monthCountOP;
                        $closingDepreciationOP = $openingDepreciationOP + $currentDepreciationOP;

                        $openingCostOP = $closingCostOP;
                        $openingDepreciationOP = $closingDepreciationOP;
                    }
                }
                $openingCost = $openingCostOP;
                $openingDepreciation = $openingDepreciationOP;
                $wdv = 0;

                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                $criteria->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                $data = JournalEntry::model()->findAll($criteria);
                if ($data) {

                    $monthCountGivenForCurrent = (JournalEntry::model()->monthCountAddOneDate($depreciationRateStartDt, $endDate));

                    $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;
                    $rowspan = count($data);
                    $rowcount = 1;

                    foreach ($data as $d) {
                        $debit = 0;
                        $credit = 0;
                        if ($d->transaction_type == ChartOfAc::DEBIT) {
                            $debit = $d->amount;
                        } else {
                            $credit = $d->amount;
                        }
                        $balanceCost = $debit - $credit;
                        $closingCost = $openingCost + $balanceCost;

                        $monthCount = (JournalEntry::model()->monthCount($d->entry_date, $endDate));

                        $currentDepreciation = ((($debit * $depreciationRate) / 12) * $monthCount) + $depreciationGivenForCurrent;
                        $depreciationGivenForCurrent = 0;
                        $closingDepreciation = $openingDepreciation + $currentDepreciation;

                        $currentDepreciationTotal = $currentDepreciation + $currentDepreciationTotal;
                        $openingCost = $closingCost;
                        $openingDepreciation = $closingDepreciation;
                        $wdv = $closingCost - $closingDepreciation;
                        $rowcount++;
                    }
                } else {
                    $monthCountGivenForCurrent = (JournalEntry::model()->monthCountAddOneDate($depreciationRateStartDt, $endDate));
                    $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;

                    $debit = 0;
                    $credit = 0;
                    $balanceCost = $debit - $credit;
                    $closingCost = $openingCost + $balanceCost;

                    $monthCount = (JournalEntry::model()->monthCountAddOneDate($startDate, $endDate));
                    $closingCost2 = $closingCost - $openingAmountGiven;
                    $currentDepreciation = ((($closingCost2 * $depreciationRate) / 12) * $monthCount) + $depreciationGivenForCurrent;

                    $closingDepreciation = $openingDepreciation + $currentDepreciation;
                    $currentDepreciationTotal = $currentDepreciation + $currentDepreciationTotal;
                    $wdv = $closingCost - $closingDepreciation;
                }
                $wdvTotal = $wdv + $wdvTotal;
                //}
            }
        }
        return $wdvTotal;
    }

    public function totalDeprPrev($startDate, $endDate)
    {
        $wdvTotal = 0;
        $currentDepreciationTotal = 0;
        $fixedAssetArr = array();
        $fixedAssetIds = ChartOfAc::model()->noChild(ChartOfAc::FIXED_ASSET, $fixedAssetArr);
        if (!empty($fixedAssetIds)) {
            for ($i = 0; $i < count($fixedAssetIds); $i++) {
                $acInfo = ChartOfAc::model()->findByPk($fixedAssetIds[$i]);
                $depriciationRateOfAc = $acInfo->dep;
                $depreciationRate = $depriciationRateOfAc / 100;
                $depreciationRateStartDt = $acInfo->dep_start_dt;
                $openingAmountGiven = $acInfo->opening_amount;

                if ($depriciationRateOfAc > 0 && $depreciationRateStartDt != "") {
                    $date = date_create($startDate);
                    date_sub($date, date_interval_create_from_date_string("1 days"));
                    $endDateOP = date_format($date, "Y-m-d");

                    $monthCountGivenForOP = (JournalEntry::model()->monthCount($depreciationRateStartDt, $endDateOP));
                    $depreciationGivenForOP = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForOP;

                    $openingCostOP = $openingAmountGiven;
                    $openingDepreciationOP = $depreciationGivenForOP;
                    $wdv = 0;
                    $criteriaOP = new CDbCriteria();
                    $criteriaOP->addBetweenCondition("entry_date", $depreciationRateStartDt, $endDateOP);
                    $criteriaOP->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                    $dataOP = JournalEntry::model()->findAll($criteriaOP);
                    if ($dataOP) {
                        foreach ($dataOP as $dOP) {
                            $debitOP = 0;
                            $creditOP = 0;
                            if ($dOP->transaction_type == ChartOfAc::DEBIT) {
                                $debitOP = $dOP->amount;
                            } else {
                                $creditOP = $dOP->amount;
                            }
                            $balanceCostOP = $debitOP - $creditOP;
                            $closingCostOP = $openingCostOP + $balanceCostOP;

                            $monthCountOP = (JournalEntry::model()->monthCount($dOP->entry_date, $endDateOP));

                            $currentDepreciationOP = (($debitOP * $depreciationRate) / 12) * $monthCountOP;
                            $closingDepreciationOP = $openingDepreciationOP + $currentDepreciationOP;

                            $openingCostOP = $closingCostOP;
                            $openingDepreciationOP = $closingDepreciationOP;
                            $wdv = $closingCostOP - $closingDepreciationOP;
                        }
                    }
                    $wdvTotal = $wdv + $wdvTotal;
                }
            }
        }
        return $wdvTotal;
    }

    public function closingBalanceOfJournal($caId, $tType, $amount)
    {
        $clossingAmount = 0;
        $sql = "SELECT closing_amount FROM journal_entry where ca_id = {$caId} order by id DESC LIMIT 1";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryRow();
        if ($data) {
            $clossingAmount = $data['closing_amount'];
        }

        $parentCAid = ChartOfAc::model()->findMainCAofThis($caId);

        if ($parentCAid == 1 || $parentCAid == 4) {
            if ($tType == 3) {
                $finalClAmount = $clossingAmount + $amount;
            } else if ($tType == 4) {
                $finalClAmount = $clossingAmount - $amount;
            }
        } else {
            if ($tType == 3) {
                $finalClAmount = $clossingAmount - $amount;
            } else if ($tType == 4) {
                $finalClAmount = $clossingAmount + $amount;
            }
        }
        return $finalClAmount;
    }

    public function totalDeprCurr($startDate, $endDate)
    {
        $wdvTotal = 0;
        $currentDepreciationTotal = 0;
        $fixedAssetArr = array();
        $fixedAssetIds = ChartOfAc::model()->noChild(ChartOfAc::FIXED_ASSET, $fixedAssetArr);
        if (!empty($fixedAssetIds)) {
            for ($i = 0; $i < count($fixedAssetIds); $i++) {
                $acInfo = ChartOfAc::model()->findByPk($fixedAssetIds[$i]);
                $depriciationRateOfAc = $acInfo->dep;
                $depreciationRate = $depriciationRateOfAc / 100;
                $depreciationRateStartDt = $acInfo->dep_start_dt;
                $openingAmountGiven = $acInfo->opening_amount;

                if ($depriciationRateOfAc > 0 && $depreciationRateStartDt != "") {
                    $openingCost = 0;
                    $openingDepreciation = 0;
                    $wdv = 0;
                    $criteria = new CDbCriteria();
                    $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                    $criteria->addColumnCondition(array("ca_id" => $fixedAssetIds[$i]), "AND", "AND");
                    $data = JournalEntry::model()->findAll($criteria);
                    if ($data) {
                        $monthCountGivenForCurrent = (JournalEntry::model()->monthCount($depreciationRateStartDt, $endDate));
                        $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;
                        foreach ($data as $d) {
                            $debit = 0;
                            $credit = 0;
                            if ($d->transaction_type == ChartOfAc::DEBIT) {
                                $debit = $d->amount;
                            } else {
                                $credit = $d->amount;
                            }
                            $balanceCost = $debit - $credit;
                            $closingCost = $openingCost + $balanceCost;
                            $monthCount = (JournalEntry::model()->monthCount($d->entry_date, $endDate));
                            $currentDepreciation = ((($debit * $depreciationRate) / 12) * $monthCount) + $depreciationGivenForCurrent;
                            $depreciationGivenForCurrent = 0;
                            $closingDepreciation = $openingDepreciation + $currentDepreciation;
                            $currentDepreciationTotal = $currentDepreciation + $currentDepreciationTotal;
                            $openingCost = $closingCost;
                            $openingDepreciation = $closingDepreciation;
                            $wdv = $closingCost - $closingDepreciation;
                        }
                    } else {
                        $monthCountGivenForCurrent = (JournalEntry::model()->monthCountAddOneDate($startDate, $endDate));
                        $depreciationGivenForCurrent = (($openingAmountGiven * $depreciationRate) / 12) * $monthCountGivenForCurrent;
                        $debit = 0;
                        $credit = 0;
                        $balanceCost = $debit - $credit;
                        $closingCost = $openingCost + $balanceCost;
                        $monthCount = (JournalEntry::model()->monthCountAddOneDate($startDate, $endDate));
                        $closingCost2 = $closingCost - $openingAmountGiven;
                        $currentDepreciation = ((($closingCost2 * $depreciationRate) / 12) * $monthCount) + $depreciationGivenForCurrent;

                        $closingDepreciation = $openingDepreciation + $currentDepreciation;
                        $wdv = $closingCost - $closingDepreciation;
                    }
                    $wdvTotal = $wdv + $wdvTotal;
                }
            }
        }
        return $wdvTotal;
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        if (strlen($this->startDate) > 0 && strlen($this->endDate) > 0) {
            $criteria->addBetweenCondition('entry_date', $this->startDate, $this->endDate);
        }

        if ($this->ca_id != "") {
            $criteria->join = "INNER JOIN chart_of_ac ON t.ca_id = chart_of_ac.id and chart_of_ac.title like '%" . $this->ca_id . "%'";
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('name_by', $this->name_by);
        $criteria->compare('voucher_type', $this->voucher_type);
        $criteria->compare('narration', $this->narration, true);
        $criteria->compare('entry_no', $this->entry_no, true);
        $criteria->compare('entry_date', $this->entry_date, true);
        $criteria->compare('t.transaction_type', $this->transaction_type);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('create_by', $this->create_by);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_by', $this->update_by);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('bill_no', $this->bill_no);
        $criteria->compare('is_canceled', $this->is_canceled);
        $criteria->compare('custom_invoice_no', $this->custom_invoice_no);
        $criteria->compare('is_deletable', $this->is_deletable);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
            'sort' => array(
                'defaultOrder' => 'entry_date desc, entry_no desc, voucher_type asc',
            ),
        ));
    }


}
