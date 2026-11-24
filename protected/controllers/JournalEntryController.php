<?php

class JournalEntryController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionFixedAssetSchedule()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'FIXED ASSET SCHEDULE';
        $this->render('fixedAssetSchedule', array(
            'model' => $model,
        ));
    }

    public function actionFixedAssetScheduleView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {
                $monthCount = (JournalEntry::model()->monthCount($startDate, $endDate));
                $message = "Fixed Asset Schedule- From <b>" . $startDate . " (" . date("M, Y", strtotime($startDate)) . ")" . "</b> To <b>" . $endDate . " (" . date("M, Y", strtotime($endDate)) . ")</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('fixedAssetScheduleView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionVoucher()
    {
        $voucherNo = $_POST['voucherNo'];
        $voucherType = $_POST['voucherType'];
        if ($voucherNo != "" && $voucherType != "") {
            $condition = 'entry_no="' . $voucherNo . '" AND voucher_type=' . $voucherType;
            $data = JournalEntry::model()->findAll(array('condition' => $condition,), 'id');
            if ($data)
                echo $this->renderPartial('voucher', array('data' => $data), true, true);
            else
                echo '<div class="flash-error">No result found !</div>';
            Yii::app()->end();
        } else {
            echo '<div class="flash-error">Please select voucher no & type !</div>';
        }
    }

    public function actionSubGeneralLedger()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'SUB-GENERAL LEDGER';
        $this->render('subGeneralLedger', array(
            'model' => $model,
        ));
    }

    public function actionSubGeneralLedgerView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate']) && isset($_POST['JournalEntry']['ca_id'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            $caId = $_POST['JournalEntry']['ca_id'];

            if ($startDate != "" && $endDate != "") {
                $message = "Sub-General Ledger- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('subGeneralLedgerView', array(
                        'startDate' => $startDate,
                        'caIds' => $caId,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionTrialBalance()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'TRIAL BALANCE';
        $this->render('trialBalance', array(
            'model' => $model,
        ));
    }

    public function actionTrialBalanceView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {
                $message = "Trial Balance- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('trialBalanceView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionCashStatementReport()
    {
        $model = new JournalEntry;
        $this->pageTitle = 'CASH STATEMENT';
        $this->render('cashStatementReport', array(
            'model' => $model,
        ));
    }

    public function actionCashStatementReportView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {
                $message = "Cash Statement Report- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('cashStatementReportView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionGroupLedgerStatement()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'GROUP LEDGER';
        $this->render('groupLedgerStatement', array(
            'model' => $model,
        ));
    }

    public function actionGroupLedgerStatementView()
    {

        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate']) && isset($_POST['JournalEntry']['ca_id'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            $caId = $_POST['JournalEntry']['ca_id'];

            if ($startDate != "" && $endDate != "" && $caId != "") {
                $message = "Group Ledger- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b> A/C: <b>" . ChartOfAc::model()->nameOfThis($caId) . "</b>";

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('groupLedgerStatementView', array(
                        'message' => $message,
                        'caId' => $caId,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                    ), true, true),
                ));
            }
        }
    }

    public function actionLedgerStatement()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'SUBSIDIARY LEDGER';
        $this->render('ledgerStatement', array(
            'model' => $model,
        ));
    }

    public function actionLedgerStatementView()
    {
        if (isset($_POST['JournalEntry']['ca_id']) && isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $ca_id = $_POST['JournalEntry']['ca_id'];
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($ca_id != "" && $startDate != "" && $endDate != "") {
                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                $criteria->addColumnCondition(array("ca_id" => $ca_id), "AND", "AND");
                $criteria->order = "entry_date, entry_no ASC";
                $criteria->group = "entry_no, voucher_type";
                $data = JournalEntry::model()->findAll($criteria);
                $caInfo = ChartOfAc::model()->findByPk($ca_id);
                $drOpeningAmount = 0;
                $crOpeningAmount = 0;
                if ($caInfo->transaction_type == ChartOfAc::DEBIT)
                    $drOpeningAmount = $caInfo->opening_amount;
                else
                    $crOpeningAmount = $caInfo->opening_amount;

                $message = "Subsidiary Ledger- <b> from " . $startDate . " to " . $endDate . ", A/C: " . $caInfo->title . " (" . $caInfo->code . ")" . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('ledgerStatementView', array(
                        'drOpeningAmount' => $drOpeningAmount,
                        'crOpeningAmount' => $crOpeningAmount,
                        'ca_id' => $ca_id,
                        'data' => $data,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    //===================== Book Reports =====================//

    public function actionBookReport()
    {
        $model = new JournalEntry;
        $this->pageTitle = 'BOOK LEDGER';
        $this->render('bookReport', array(
            'model' => $model,
        ));
    }

    public function actionBookReportView()
    {
        if (isset($_POST['JournalEntry']['ca_id']) && isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $ca_id = $_POST['JournalEntry']['ca_id'];
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($ca_id != "" && $startDate != "" && $endDate != "") {
                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                $criteria->addColumnCondition(array("ca_id" => $ca_id), "AND", "AND");
                $criteria->order = "entry_date, entry_no ASC";
                $criteria->group = "entry_no, voucher_type";
                $data = JournalEntry::model()->findAll($criteria);
                $caInfo = ChartOfAc::model()->findByPk($ca_id);
                $drOpeningAmount = 0;
                $crOpeningAmount = 0;
                if ($caInfo->transaction_type == ChartOfAc::DEBIT)
                    $drOpeningAmount = $caInfo->opening_amount;
                else
                    $crOpeningAmount = $caInfo->opening_amount;

                $message = "<b> Book Ledger- from " . $startDate . " to " . $endDate . ", A/C: " . $caInfo->title . " (" . $caInfo->code . ")" . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('bookReportView', array(
                        'drOpeningAmount' => $drOpeningAmount,
                        'crOpeningAmount' => $crOpeningAmount,
                        'ca_id' => $ca_id,
                        'data' => $data,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    //===================== Book Reports End=====================//

    public function actionPrintEntries()
    {
        $model = new JournalEntry;
        $this->render('printEntries', array(
            'model' => $model,
        ));
    }

    public function actionPrintEntriesView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            $voucherType = $_POST['JournalEntry']['voucher_type'];
            if ($startDate != "" && $endDate != "") {
                $message = "Entries- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
                if ($voucherType != "") {
                    $message .= ", Voucher Type: " . LookupAc::item("voucher_type", $voucherType);
                    $criteria->addColumnCondition(array("voucher_type" => $voucherType), "AND", "AND");
                }
                $data = JournalEntry::model()->findAll($criteria);
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('printEntriesView', array(
                        'voucherType' => $voucherType,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'data' => $data,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionBalanceSheet()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'BALANCE SHEET';
        $this->render('balanceSheet', array(
            'model' => $model,
        ));
    }

    public function actionBalanceSheetView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {
                $message = "Balance Sheet- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('balanceSheetView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionOwnersEquetyStatement()
    {
        $model = new JournalEntry;
        $this->render('ownersEquetyStatement', array(
            'model' => $model,
        ));
    }

    public function actionOwnersEquetyStatementView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {

                $message = "Owners Equity Statement- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('ownersEquetyStatementView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionIncomeStatement()
    {
        $model = new JournalEntry;

        $this->pageTitle = 'INCOME STATEMENT';
        $this->render('incomeStatement', array(
            'model' => $model,
        ));
    }

    public function actionIncomeStatementView()
    {
        if (isset($_POST['JournalEntry']['startDate']) && isset($_POST['JournalEntry']['endDate'])) {
            $startDate = $_POST['JournalEntry']['startDate'];
            $endDate = $_POST['JournalEntry']['endDate'];
            if ($startDate != "" && $endDate != "") {

                $message = "Income Statement- From <b>" . $startDate . "</b> To <b>" . $endDate . "</b>";
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('incomeStatementView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionCreate()
    {
        $model = new JournalEntry;
        $this->performAjaxValidation($model);

        if (isset($_POST['JournalEntry'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['JournalEntry'];
            $drcrDiff = $_POST['JournalEntry']['drcrdiff'];
            if ($drcrDiff == 0) {
                $valid = $model->validate();
                if ($valid) {
                    $i = 0;
                    $countDR = 0;
                    $countCR = 0;

                    $thisEntryNo = VoucherNoFormateAc::model()->maxValOfThisOfThisType('JournalEntry', 'entry_no', 'maxval', $_POST['JournalEntry']['voucher_type']);

                    foreach ($_POST['JournalEntry']['temp_ca_id'] as $tempCAid) {
                        $model = new JournalEntry;
                        $model->narration = $_POST['JournalEntry']['narration'];
                        $model->entry_no = $thisEntryNo;
                        $model->entry_date = $_POST['JournalEntry']['entry_date'];
                        $model->ca_id = $tempCAid;
                        $model->transaction_type = $tType = $_POST['JournalEntry']['temp_transaction_type'][$i];
                        $model->amount = $amount = $_POST['JournalEntry']['temp_amount'][$i];
                        $model->main_ca = ChartOfAc::model()->findMainCAofThis($tempCAid);
                        $model->voucher_type = $_POST['JournalEntry']['voucher_type'];
                        if ($tType == 3) {
                            $countDR++;
                        }
                        if ($tType == 4) {
                            $countCR++;
                        }

                        $clossingAmount = 0;
                        $sql = "SELECT closing_amount FROM journal_entry where ca_id = {$tempCAid} order by id DESC LIMIT 1";
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand($sql);
                        $data = $command->queryRow();
                        if ($data) {
                            $clossingAmount = $data['closing_amount'];
                        }

                        $parentCAid = ChartOfAc::model()->findMainCAofThis($tempCAid);

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

                        $model->closing_amount = $finalClAmount;
                        $model->save();
                        $i++;
                    }

                    //Entry Count
                    /*
                      $sql = "SELECT count(id) as entryCount FROM journal_entry WHERE entry_no = $model->entry_no AND voucher_type = $model->voucher_type ORDER BY id ASC";
                      $connection = Yii::app()->db;
                      $command = $connection->createCommand($sql);
                      $data = $command->queryRow();
                      $countEntry = $data['entryCount'];

                      if ($countEntry <= 2){
                      $journalEntry = $this->UpdateJournalCredit($thisEntryNo);
                      }
                     */
                    if ($countDR == 1 && $countCR == 1) {
                        $singleJounal = JournalEntry::model()->findAll(array("condition" => "entry_no='$thisEntryNo'"));
                        foreach ($singleJounal as $je) {
                            $jeId = $je->id;
                            $ca_id = $je->ca_id;
                            $sql = "SELECT * FROM journal_entry where entry_no = $thisEntryNo AND id != $jeId";
                            $connection = Yii::app()->db;
                            $command = $connection->createCommand($sql);
                            $dt = $command->queryRow();
                            if ($dt) {
                                $caId = $dt['ca_id'];
                            }
                            JournalEntry::model()->updateByPk($jeId, array('opposite_ca_id' => $caId));
                        }
                    } else {
                        $journalEntry = $this->UpdateJournalCredit($thisEntryNo);
                    }
                    $condition = 'entry_no="' . $thisEntryNo . '" AND voucher_type=' . $model->voucher_type;
                    $data = JournalEntry::model()->findAll(array('condition' => $condition,), 'id');
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'voucher' => $this->renderPartial('voucher', array('data' => $data), true, true),
                    ));
                    Yii::app()->end();
                } else {
                    $error = CActiveForm::validate($model);
                    if ($error != '[]')
                        echo $error;
                    Yii::app()->end();
                }
            } else {
                echo CJSON::encode(array(
                    'status' => 'errorBalance',
                ));
                Yii::app()->end();
            }
        } else {
            $this->pageTitle = 'ADJUSTMENT VOUCHER ENTRY';
            $this->render('_form', array(
                'model' => $model,
            ));
        }
    }

    public function UpdateJournalCredit($entryNo)
    {
        $sql = "SELECT * FROM journal_entry where entry_no = $entryNo group by entry_no ORDER BY entry_no ASC";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        if ($data) {
            foreach ($data as $dt) {
                $entryNo = $dt['entry_no'];
                $voucher_type = $dt['voucher_type'];
                $sql2 = "SELECT count(id) as entryCount FROM journal_entry WHERE entry_no = $entryNo AND voucher_type = $voucher_type ORDER BY id ASC";
                $connection2 = Yii::app()->db;
                $command2 = $connection->createCommand($sql2);
                $data2 = $command2->queryRow();
                $countEntry = $data2['entryCount'];

                if ($countEntry > 2) {

                    $entryNo = $dt['entry_no'];
                    $ca_id = $dt['ca_id'];
                    $id = $dt['id'];

                    $countCR = JournalEntry::model()->countByAttributes(array('entry_no' => $entryNo, 'transaction_type' => 4));
                    if ($countCR > 1) {
                        $dr_cr = 4;
                        $deleteRow = 3;
                    } else {
                        $dr_cr = 3;
                        $deleteRow = 4;
                    }

                    $dataModelSingleRow = JournalEntry::model()->findByAttributes(array('entry_no' => $entryNo, 'transaction_type' => $deleteRow));

                    if ($dataModelSingleRow) {
                        $caIdCR = $dataModelSingleRow->ca_id;
                        $CrId = $dataModelSingleRow->id;
                        $dataModelDR = JournalEntry::model()->findAll(array("condition" => "entry_no= $entryNo AND transaction_type = $dr_cr"));
                        if ($dataModelDR) {
                            foreach ($dataModelDR as $dr) {
                                JournalEntry::model()->updateByPk($dr->id, array('opposite_ca_id' => $caIdCR));
                                $modelJE = new JournalEntry;
                                $modelJE->entry_no = $dr->entry_no;
                                $modelJE->entry_date = $dr->entry_date;
                                $modelJE->ca_id = $caIdCR;
                                $modelJE->transaction_type = $deleteRow;
                                $modelJE->closing_amount = $dr->closing_amount;
                                $modelJE->narration = $dataModelSingleRow->narration;
                                $modelJE->amount = $dr->amount;
                                $modelJE->opposite_ca_id = $dr->ca_id;
                                $modelJE->create_by = $dr->create_by;
                                $modelJE->create_time = $dr->create_time;
                                $modelJE->main_ca = $dataModelSingleRow->main_ca;
                                $modelJE->voucher_type = $dr->voucher_type;
                                $modelJE->save();
                            }
                        }
                        JournalEntry::model()->deleteAll("id =$CrId");
                        return true;
                    }
                }
            }
        }
    }

    public function actionCreatePayRec()
    {
        $model = new JournalEntry;
        $this->performAjaxValidation($model);

        if (isset($_POST['JournalEntry'])) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            $model->attributes = $_POST['JournalEntry'];
            $drcrDiff = $_POST['JournalEntry']['drcrdiff'];
            if ($drcrDiff == 0) {
                $valid = $model->validate();
                if ($valid) {
                    $i = 0;
                    $thisEntryNo = VoucherNoFormateAc::model()->maxValOfThisOfThisType('JournalEntry', 'entry_no', 'maxval', $_POST['JournalEntry']['voucher_type']);

                    $modelJ = new JournalEntry;
                    $modelJ->entry_no = $thisEntryNo;
                    $modelJ->entry_date = $_POST['JournalEntry']['entry_date'];
                    $modelJ->ca_id = $caIds = $_POST['JournalEntry']['ca_cash'] != '' ? $_POST['JournalEntry']['ca_cash'] : $_POST['JournalEntry']['ca_bank'];
                    $tType = $_POST['JournalEntry']['transaction_type'];
                    if ($tType == 3) {
                        $modelJ->transaction_type = $tType = 4;
                    } else {
                        $modelJ->transaction_type = $tType = 3;
                    }
                    $modelJ->amount = $amount = $_POST['amount'];
                    $closing_amount = JournalEntry::model()->closingBalanceOfJournal($caIds, $tType, $amount);
                    $modelJ->closing_amount = $closing_amount;
                    $modelJ->main_ca = ChartOfAc::model()->findMainCAofThis($caIds);
                    $modelJ->voucher_type = $_POST['JournalEntry']['voucher_type'];
                    $modelJ->save();

                    foreach ($_POST['JournalEntry']['temp_ca_id'] as $tempCAid) {
                        $model = new JournalEntry;
                        $model->narration = $_POST['JournalEntry']['narration'];
                        $model->entry_no = $thisEntryNo;
                        $model->entry_date = $_POST['JournalEntry']['entry_date'];
                        $model->ca_id = $tempCAid;
                        $model->transaction_type = $tType = $_POST['JournalEntry']['temp_transaction_type'][$i];
                        $model->amount = $amount = $_POST['JournalEntry']['temp_amount'][$i];
                        $model->main_ca = ChartOfAc::model()->findMainCAofThis($tempCAid);
                        $model->voucher_type = $_POST['JournalEntry']['voucher_type'];
                        $finalClAmount = JournalEntry::model()->closingBalanceOfJournal($tempCAid, $tType, $amount);
                        $model->closing_amount = $finalClAmount;
                        $model->save();
                        $i++;
                    }

                    if ($i == 1) {
                        $singleJounal = JournalEntry::model()->findAll(array("condition" => "entry_no='$thisEntryNo'"));
                        foreach ($singleJounal as $je) {
                            $jeId = $je->id;
                            $sql = "SELECT * FROM journal_entry where entry_no = $thisEntryNo AND id != $jeId";
                            $connection = Yii::app()->db;
                            $command = $connection->createCommand($sql);
                            $dt = $command->queryRow();
                            if ($dt) {
                                $caId = $dt['ca_id'];
                                $amount = $dt['amount'];
                            }
                            JournalEntry::model()->updateByPk($jeId, array('opposite_ca_id' => $caId));
                        }
                    } else {
                        $journalEntry = $this->UpdateJournalCredit($thisEntryNo);
                    }

                    $condition = 'entry_no="' . $thisEntryNo . '" AND voucher_type=' . $model->voucher_type;
                    $data = JournalEntry::model()->findAll(array('condition' => $condition,), 'id');
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'voucher' => $this->renderPartial('voucher', array('data' => $data), true, true),
                    ));
                    Yii::app()->end();
                } else {
                    $error = CActiveForm::validate($model);
                    if ($error != '[]')
                        echo $error;
                    Yii::app()->end();
                }
            } else {
                echo CJSON::encode(array(
                    'status' => 'errorBalance',
                ));
                Yii::app()->end();
            }
        } else {
            $this->pageTitle = 'PAYMENT/RECEIPT ENTRY';
            $this->render('_formPayRec', array(
                'model' => $model,
            ));
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if (isset($_POST['JournalEntry'])) {
            $model->attributes = $_POST['JournalEntry'];
            $model->main_ca = ChartOfAc::model()->findMainCAofThis($model->ca_id);

            $voucherNo = $model->entry_no;
            $voucher_type = $model->voucher_type;
            if ($model->save()) {
                JournalEntry::model()->updateAll(
                    array('narration' => $model->narration, 'entry_date' => $model->entry_date),
                    "entry_no = '$voucherNo' and voucher_type = $voucher_type"
                );
                if (Yii::app()->request->isAjaxRequest) {
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                    echo CJSON::encode(array(
                        'status' => 'success',
                        'content' => '<div class="flash-notice">successfully updated</div>',
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            // Stop jQuery from re-initialization
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

            echo CJSON::encode(array(
                'status' => 'failure',
                'content' => $this->renderPartial('_form2', array(
                    'model' => $model), true, true),
            ));
            exit;
        } else
            $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionDeleteAll()
    {
        if (isset($_POST['journal-entry-grid_c0'])) {
            $del_item = $_POST['journal-entry-grid_c0'];
            $model_item = new JournalEntry;
            //$model_item_all = new JournalEntry;
            foreach ($del_item as $_id) {
                $model_item->deleteByPk($_id);
                //$model_item_all->deleteAllByAttributes(array('id' => $_id));
            }
            $this->redirect(array('admin'));
        } else {
            Yii::app()->user->setFlash('error', 'Please select at least one item to delete.');
            $this->redirect(array('admin'));
        }
    }

    public function actionAdmin()
    {
        $model = new JournalEntry('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['JournalEntry']))
            $model->attributes = $_GET['JournalEntry'];

        $this->pageTitle = 'ENTRY MANAGE';
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = JournalEntry::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'journal-entry-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //===================== Book Reports =====================//
    public function actionBookReportViewDateWise()
    {
        $data = '';
        $condition = '';
        $drOpeningAmount = $crOpeningAmount = $ca_id = '';
        $startDate = Yii::app()->getRequest()->getQuery('startDate');
        $endDate = Yii::app()->getRequest()->getQuery('endDate');
        $ca_id = Yii::app()->getRequest()->getQuery('ca_id');
        if ($startDate != "" && $endDate != "" && $ca_id != "") {
            $startDate = date("Y-m-d", strtotime('+0 day', $startDate));
            $endDate = date("Y-m-d", strtotime('+0 day', $endDate));
            $ca_id = $_REQUEST['ca_id'];
        } else {
            $message = "<div class='flash-error'>Please select date range!</div>";
        }
        if ($ca_id != "" && $startDate != "" && $endDate != "") {
            $criteria = new CDbCriteria();
            $criteria->addBetweenCondition("entry_date", $startDate, $endDate);
            $criteria->addColumnCondition(array("ca_id" => $ca_id), "AND", "AND");
            $criteria->order = "entry_date, entry_no ASC";
            $criteria->group = "entry_no, voucher_type";
            $data = JournalEntry::model()->findAll($criteria);
            $caInfo = ChartOfAc::model()->findByPk($ca_id);
            $drOpeningAmount = 0;
            $crOpeningAmount = 0;
            if ($caInfo->transaction_type == ChartOfAc::DEBIT)
                $drOpeningAmount = $caInfo->opening_amount;
            else
                $crOpeningAmount = $caInfo->opening_amount;

            $message = "<b> Book Ledger- from " . $startDate . " to " . $endDate . ", A/C: " . $caInfo->title . " (" . $caInfo->code . ")" . "</b>";

            echo $this->renderPartial('bookReportView', array(
                'drOpeningAmount' => $drOpeningAmount,
                'crOpeningAmount' => $crOpeningAmount,
                'ca_id' => $ca_id,
                'data' => $data,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'message' => $message,
            ), true, true);
            Yii::app()->end();
        }

    }

}
