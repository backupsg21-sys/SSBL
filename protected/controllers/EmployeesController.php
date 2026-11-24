<?php

class EmployeesController extends Controller
{

    public $layout = '//layouts/column1';
    public $defaultAction = 'admin';

    public function filters()
    {
        return array(
            'rights-
            JqueryEmpSearch', // perform access control for CRUD operations
        );
    }

    public function allowedActions()
    {
        return '';
    }

    public function actionSearchByAttributes()
    {
        if (isset($_POST['employee_type'], $_POST['designation_id'], $_POST['department_id'], $_POST['section_id'], $_POST['sub_section_id'], $_POST['branch_id'], $_POST['stuff_cat_id'], $_POST['stuff_sub_cat_id'])) {
            $employee_type = $_POST['employee_type'];
            $designation_id = $_POST['designation_id'];
            $department_id = $_POST['department_id'];
            $section_id = $_POST['section_id'];
            $sub_section_id = $_POST['sub_section_id'];
            $branch_id = $_POST['branch_id'];
            $stuff_cat_id = $_POST['stuff_cat_id'];
            $stuff_sub_cat_id = $_POST['stuff_sub_cat_id'];

            $condition = "is_active=" . Employees::ACTIVE;

            if ($employee_type != "") {
                $condition .= " AND employee_type=" . $employee_type;
            }
            if ($designation_id != "") {
                $condition .= " AND designation_id=" . $designation_id;
            }
            if ($department_id != "") {
                $condition .= " AND department_id=" . $department_id;
            }
            if ($section_id != "") {
                $condition .= " AND section_id=" . $section_id;
            }
            if ($sub_section_id != "") {
                $condition .= " AND sub_section_id=" . $sub_section_id;
            }
            if ($branch_id != "") {
                $condition .= " AND branch_id=" . $branch_id;
            }
            if ($stuff_cat_id != "") {
                $condition .= " AND stuff_cat_id=" . $stuff_cat_id;
            }
            if ($stuff_sub_cat_id != "") {
                $condition .= " AND stuff_sub_cat_id=" . $stuff_sub_cat_id;
            }

            $data = Employees::model()->findAll(array('condition' => $condition), 'id');
            if ($data) {
                $empList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $empList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->full_name), true);
                }
            } else {
                $empList = CHtml::tag('option', array('value' => ''), 'Select', true);
            }

            echo CJSON::encode(array(
                'empList' => $empList,
            ));
        } else {
            $data = Employees::model()->findAll();
            if ($data) {
                $empList = CHtml::tag('option', array('value' => ''), 'Select', true);
                foreach ($data as $d) {
                    $empList .= CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->full_name), true);
                }
            } else {
                $empList = CHtml::tag('option', array('value' => ''), 'Select', true);
            }
            echo CJSON::encode(array(
                'empList' => $empList,
            ));
        }
    }

    public function actionAdvanceSalarySheet()
    {
        $model = new Employees;
        $this->pageTitle = 'ADVANCE SALARY';
        $this->render('advanceSalarySheet', array(
            'model' => $model,
        ));
    }

    public function actionAdvanceSalarySheetView()
    {
        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['id'])) {
            $startDate = $_POST['Employees']['startDate'];
            $endDate = $_POST['Employees']['endDate'];
            $empId = $_POST['Employees']['id'];
            if ($startDate != "" && $endDate != "" && $empId != "") {
                $monthFetch = substr($endDate, 5, 2);
                $yearFetch = substr($endDate, 0, 4);

                $monthInfo = Months::model()->findByAttributes(array('code' => $monthFetch)); // month info
                $monthId = $monthInfo->id;
                $monthName = $monthInfo->month_name;

                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id'); // active working day info
                $working_day = 0;
                foreach ($activeWorkingDay as $awd):
                    $days_of_month = $awd->days_of_month;
                    $working_day = $awd->working_day;
                endforeach;

                $empInfo = Employees::model()->findByPk($empId); // emp info
                $empIdNo = $empInfo->emp_id_no;
                $empPunchCardNo = $empInfo->id_no;
                $empFullName = $empInfo->full_name;
                $empDesignation = Designations::model()->infoOfThis($empInfo->designation_id);
                $empDepartment = Departments::model()->nameOfThis($empInfo->department_id);

                $message = "<tr><td colspan='6'>Advance Salary Sheet (Loan Report) -" . $monthName . ", " . $yearFetch . "</td></tr>";
                $message .= "<tr>";
                $message .= "<td colspan='6' style='text-align: left;'>";
                $message .= "NAME: " . $empFullName;
                $message .= "<br>";
                $message .= "ID: " . $empIdNo;
                $message .= "<br>";
                $message .= "PUNCH CARD NO: " . $empPunchCardNo;
                $message .= "<br>";
                $message .= "DESIGNATION: " . $empDesignation;
                $message .= "<br>";
                $message .= "DEPARTMENT: " . $empDepartment;
                $message .= "</td>";
                $message .= "</tr>";

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('advanceSalarySheetView', array(
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'empId' => $empId,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionAttendanceReportSummary()
    {
        $model = new Employees;

        $this->pageTitle = 'ATTN. MONTHLY REPORT';
        $this->render('attendanceReportSummary', array(
            'model' => $model,
        ));
    }

    public function actionAttendanceReportSummaryView()
    {
        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['department_id'], $_POST['Employees']['designation_id'], $_POST['Employees']['id'])) {
            if ($_POST['Employees']['startDate'] != "" && $_POST['Employees']['endDate'] != "") {

                $startDate = $_POST['Employees']['startDate'];
                $endDate = $_POST['Employees']['endDate'];

                $monthFetch = substr($endDate, 5, 2);
                $yearFetch = substr($endDate, 0, 4);

                $monthInfo = Months::model()->findByAttributes(array('code' => $monthFetch)); // month info
                $monthId = $monthInfo->id;
                $monthName = $monthInfo->month_name;

                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id'); // active working day info
                $working_day = 0;
                $days_of_month = 0;
                foreach ($activeWorkingDay as $awd):
                    $days_of_month = $awd->days_of_month;
                    $working_day = $awd->working_day;
                endforeach;

                $condition = "is_active=" . Employees::ACTIVE . " AND id_no!=''";
                if ($_POST['Employees']['department_id'] != "") {
                    $departmentId = $_POST['Employees']['department_id'];
                    $condition .= " AND department_id=" . $departmentId;
                }
                if ($_POST['Employees']['designation_id'] != "") {
                    $designationId = $_POST['Employees']['designation_id'];
                    $condition .= " AND designation_id=" . $designationId;
                }
                if ($_POST['Employees']['id'] != "") {
                    $id = $_POST['Employees']['id'];
                    $condition .= " AND id=" . $id;
                }

                $condition .= " ORDER BY department_id ASC";
                $data = Employees::model()->findAll(array('condition' => $condition));
                $message = "Attendance Report Summary From " . $startDate . " To " . $endDate;
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('attendanceReportSummaryView', array(
                        'data' => $data,
                        'message' => $message,
                        'days_of_month' => $days_of_month,
                        'working_day' => $working_day,
                        'monthFetch' => $monthFetch,
                        'yearFetch' => $yearFetch,
                    ), true, true),
                ));
            }
        }
    }

    public function actionEmpCardNoOfThisDept()
    {
        $departmentId = $_POST['EmpAttendance']['department_id'];
        if ($departmentId != "") {
            $condition = "department_id=" . $departmentId . " ORDER BY full_name ASC";
        } else {
            $condition = "id!=0 ORDER BY full_name ASC";
        }

        $data = Employees::model()->findAll(array('condition' => $condition));
        $content = "";
        if ($data) {
            $i = 0;
            foreach ($data as $d) {
                $content .= '<div class="cardList">';
                $content .= '<input type="checkbox" id="EmpAttendance_card_no_' . $i++ . '" name="EmpAttendance[card_no][]" value="' . $d->id_no . '"/><span>' . $d->full_name . ' (' . $d->id_no . ')</span>';
                $content .= '</div>';
            }
        }
        echo CJSON::encode(array(
            'content' => $content,
        ));
    }

    public function actionEmpOfThis()
    {
        $empIdNo = $_POST['empIdNo'];
        $empPunchCardNo = $_POST['empPunchCardNo'];
        $jsPart = '';
        $empId = "";
        if ($empIdNo != "" && $empPunchCardNo == "") {
            $data = Employees::model()->findByAttributes(array('emp_id_no' => $empIdNo));
            if ($data)
                $empId = $data->id;
        } else if ($empIdNo == "" && $empPunchCardNo != "") {
            $data = Employees::model()->findByAttributes(array('id_no' => $empPunchCardNo));
            if ($data)
                $empId = $data->id;
        } else if ($empIdNo != "" && $empPunchCardNo != "") {
            $data = Employees::model()->findByAttributes(array('emp_id_no' => $empIdNo, 'id_no' => $empPunchCardNo));
            if ($data)
                $empId = $data->id;
        } else {
            $empId = "";
        }

        $jsPart .= '<script type="text/javascript">
                   $(document).ready(function(){ 
                            var employeeId="' . $empId . '";
                            $("#Employees_id").val(employeeId);
                    });
                   </script>
                   ';

        echo CJSON::encode(array(
            'jsPart' => $jsPart,
        ));
    }

    public function actionEmployeePaySlip()
    {
        $model = new Employees;

        $this->pageTitle = 'SALARY SHEET';
        $this->render('employeePaySlip', array(
            'model' => $model,
        ));
    }

    public function actionEmployeePaySlipView()
    {

        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'])) {
            if ($_POST['Employees']['startDate'] != "" && $_POST['Employees']['endDate'] != "") {

                $startDate = $_POST['Employees']['startDate'];
                $endDate = $_POST['Employees']['endDate'];

                $monthFetch = substr($endDate, 5, 2);
                $yearFetch = substr($endDate, 0, 4);

                $monthInfo = Months::model()->findByAttributes(array('code' => $monthFetch)); // month info
                $monthId = $monthInfo->id;
                $monthName = $monthInfo->month_name;

                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id'); // active working day info
                $working_day = 0;
                foreach ($activeWorkingDay as $awd):
                    $days_of_month = $awd->days_of_month;
                    $working_day = $awd->working_day;
                endforeach;
                $departmentName = "";
                $conditionEmp = 'is_active = 1';
                if ($_POST['Employees']['id'] != "") {
                    $empId = $_POST['Employees']['id'];
                    $conditionEmp .= " AND id= {$empId} ORDER BY full_name ASC"; //Updated by shohag
                    $departmentName = "";
                } else {
                    if ($_POST['Employees']['department_id'] != "") {
                        $departmentId = $_POST['Employees']['department_id'];
                        $conditionEmp .= " AND department_id=" . $departmentId . " ORDER BY full_name ASC";
                        $departmentName = Departments::model()->nameOfThis($departmentId);
                    } else {
                        $dataEmp = "";
                    }
                }

                $dataEmp = Employees::model()->findAll(array('condition' => $conditionEmp));

                $conditionEarnHeads = "ac_type=" . AhProllNormal::EARNING;
                $dataEarnHeads = AhProllNormal::model()->findAll(array('condition' => $conditionEarnHeads));
                $conditionDeductHeads = "ac_type=" . AhProllNormal::DEDUCTION;
                $dataDeductHeads = AhProllNormal::model()->findAll(array('condition' => $conditionDeductHeads));


                $message = "Salary sheet for the month of " . $monthName . ", " . $yearFetch;
                if ($departmentName != "")
                    $message .= "<br>Department: " . $departmentName;
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('employeePaySlipView', array(
                        'dataEmp' => $dataEmp,
                        'dataEarnHeads' => $dataEarnHeads,
                        'dataDeductHeads' => $dataDeductHeads,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'days_of_month' => $days_of_month,
                        'working_day' => $working_day,
                        'monthFetch' => $monthFetch,
                        'yearFetch' => $yearFetch,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionPaySlipSingle()
    {
        $model = new Employees;

        $this->pageTitle = 'PAY SLIP';
        $this->render('paySlipSingle', array(
            'model' => $model,
        ));
    }

    public function actionPaySlipSingleView()
    {
        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['id'])) {
            $startDate = $_POST['Employees']['startDate'];
            $endDate = $_POST['Employees']['endDate'];
            $empId = $_POST['Employees']['id'];
            if ($startDate != "" && $endDate != "" && $empId != "") {
                $monthFetch = substr($endDate, 5, 2);
                $yearFetch = substr($endDate, 0, 4);

                $monthInfo = Months::model()->findByAttributes(array('code' => $monthFetch)); // month info
                $monthId = $monthInfo->id;
                $monthName = $monthInfo->month_name;

                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id'); // active working day info
                $working_day = 0;
                foreach ($activeWorkingDay as $awd):
                    $days_of_month = $awd->days_of_month;
                    $working_day = $awd->working_day;
                endforeach;

                $empInfo = Employees::model()->findByPk($empId); // emp info
                $empIdNo = $empInfo->emp_id_no;
                $empPunchCardNo = $empInfo->device_id;
                $empFullName = $empInfo->full_name;
                $empDesignation = Designations::model()->infoOfThis($empInfo->designation_id);
                $empDepartment = Departments::model()->nameOfThis($empInfo->department_id);
                $empJoinDate = $empInfo->join_date;

                $criteria = new CDbCriteria();
                $criteria->select = "count(id) as sumOfPresentDay";
                $criteria->addColumnCondition(array('card_no' => $empPunchCardNo), 'AND');
                $criteria->addBetweenCondition('date', $startDate, $endDate);
                $criteria->order = "date ASC";
                $attendanceData = EmpAttendance::model()->findAll($criteria);

                $totalPresentDay = 0;
                foreach ($attendanceData as $atndncData):
                    $totalPresentDay = $atndncData->sumOfPresentDay;
                endforeach;
                $totalAbsentDay = $working_day - $totalPresentDay;


                $criteria2 = new CDbCriteria();
                $criteria2->select = "sum(day) as sumOfDay";
                $criteria2->addColumnCondition(array('employee_id' => $empId, 'is_active' => LhAmountProllNormal::ACTIVE), 'AND');
                $assignedLeaveData = LhAmountProllNormal::model()->findAll($criteria2);
                $totalAssignedLeaveDay = 0;
                foreach ($assignedLeaveData as $assgnLvData):
                    $totalAssignedLeaveDay = $assgnLvData->sumOfDay;
                endforeach;

                $criteria3 = new CDbCriteria();
                $criteria3->select = "sum(day_to_leave) as sumDay";
                $criteria3->addColumnCondition(array(
                    'emp_id' => $empId,
                    'is_approved' => EmpLeavesNormal::APPROVED,
                    'month' => $monthFetch,
                    'year' => $yearFetch,
                ), 'AND');
                $spentLvDataCurrMnth = EmpLeavesNormal::model()->findAll($criteria3);
                $approvedLeaveCurrMonth = 0;
                foreach ($spentLvDataCurrMnth as $spntLvCurrMnth):
                    $approvedLeaveCurrMonth = $spntLvCurrMnth->sumDay;
                endforeach;

                $criteria4 = new CDbCriteria();
                $criteria4->select = "sum(day_to_leave) as sumDay";
                $criteria4->addColumnCondition(array(
                    'emp_id' => $empId,
                    'is_approved' => EmpLeavesNormal::APPROVED,
                    'year' => $yearFetch,
                ), 'AND');
                $spentLvDetailsCurrYr = EmpLeavesNormal::model()->findAll($criteria4);
                $approvedLeaveCurrYr = 0;
                foreach ($spentLvDetailsCurrYr as $spntLvCurrYr):
                    $approvedLeaveCurrYr = $spntLvCurrYr->sumDay;
                endforeach;

                $unApprovedLeaveCurrMonth = $totalAbsentDay - $approvedLeaveCurrMonth;

                $message = "<tr><td colspan='4' style='font-weight:bold; font-size:14px;'>PAY SLIP  FOR THE MONTH OF - " . $monthName . ", " . $yearFetch . "</td></tr>";
                $message .= "<tr>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 20%;'>";
                $message .= "Name: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empFullName . "</td>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 20%;'>";
                $message .= "Days of the Month: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $days_of_month . " Days" . "</td>";
                $message .= "</tr><tr>";

                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "ID: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empIdNo . "</td>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Working Day(s): ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $working_day . " Days" . "</td>";
                $message .= "</tr><tr>";

                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Punch Card No.: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empPunchCardNo . "</td>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Present Day(s): ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $totalPresentDay . " Days" . "</td>";
                $message .= "</tr><tr>";

                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Designation: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empDesignation . "</td>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Absent Days(s): ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $unApprovedLeaveCurrMonth . " Days" . "</td>";
                $message .= "</tr><tr>";

                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Department: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empDepartment . "</td>";
                $message .= "<td colspan='1' style='font-weight:bold; font-size:12px; text-align: left; width: 10%;'>";
                $message .= "Join Date: ";
                $message .= "</td><td colspan='1' style='text-align: left; font-size:12px; width: 30%;'>";
                $message .= $empJoinDate . "</td>";
                $message .= "</tr></tr>";


                $conditionSalary = "employee_id=" . $empId . " AND is_active=" . AhAmounProllNormal::ACTIVE;
                $salaryData = AhAmounProllNormal::model()->findAll(array('condition' => $conditionSalary), 'id');

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('paySlipSingleView', array(
                        'salaryData' => $salaryData,
                        'totalPresentDay' => $totalPresentDay,
                        'unApprovedLeaveCurrMonth' => $unApprovedLeaveCurrMonth,
                        'working_day' => $working_day,
                        'endDate' => $endDate,
                        'empId' => $empId,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    public function actionAttendanceReport()
    {
        $model = new Employees;
        $this->render('attendanceReport', array(
            'model' => $model,
        ));
    }

    public function actionAttendanceReportView()
    {
        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['id'])) {
            $startDate = $_POST['Employees']['startDate'];
            $endDate = $_POST['Employees']['endDate'];
            $employeeId = $_POST['Employees']['id'];

            $monthFetch = substr($startDate, 5, 2);
            $yearFetch = substr($startDate, 0, 4);

            $monthCode = Months::model()->findByAttributes(array('code' => $monthFetch));
            if ($monthCode) {
                $monthId = $monthCode->id;
                $monthName = $monthCode->month_name;
                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id');
                if ($activeWorkingDay) {
                    foreach ($activeWorkingDay as $awd):
                        $days_of_month = $awd->days_of_month;
                        $working_day = $awd->working_day;
                    endforeach;

                    $empData = Employees::model()->findByPk($employeeId);

                    if ($empData) {
                        $cardNo = $empData->id_no;
                        $empId = $empData->id;
                        $shiftId = $empData->shift_id;
                        $empFullName = $empData->full_name;
                        $empIdNo = $empData->emp_id_no;
                        $empDesignation = Designations::model()->infoOfThis($empData->designation_id);
                        $empDepartment = Departments::model()->nameOfThis($empData->department_id);

                        $criteria = new CDbCriteria();
                        $criteria->addColumnCondition(array('card_no' => $cardNo), 'AND');
                        $criteria->addBetweenCondition('date', $startDate, $endDate);
                        $criteria->order = "date ASC";
                        $attendanceData = EmpAttendance::model()->findAll($criteria);
                        if ($attendanceData) {

                            $message = "<tr><td colspan='5'>Attendance Report From " . $startDate . " To " . $endDate . "</td></tr>";
                            $message .= "<tr><td colspan='4' class='textAlgnLeft' style='vertical-align: top'>Name: " . $empFullName .
                                "<br>Employee ID: " . $empIdNo .
                                "<br>Punch Card No: " . $cardNo .
                                "<br>Designation: " . $empDesignation .
                                "<br>Department: " . $empDepartment . "</td>";
                            $message .= "<td class='textAlgnRight' style='vertical-align: top'>Days Of This Month: " . $days_of_month .
                                "<br>Working Day Of This Month: " . $working_day .
                                "<br>Month/Year: " . $monthName . "/" . $yearFetch . "</td></tr>";

                            $assignedLvCond = "employee_id=" . $empId . " AND is_active=" . LhAmountProllNormal::ACTIVE;
                            $assignedLeave = LhAmountProllNormal::model()->findAll(array('condition' => $assignedLvCond), 'id');

                            echo CJSON::encode(array(
                                'content' => $this->renderPartial('attendanceReportView', array(
                                    'message' => $message,
                                    'attendanceData' => $attendanceData,
                                    'assignedLeave' => $assignedLeave,
                                    'monthFetch' => $monthFetch,
                                    'yearFetch' => $yearFetch,
                                    'empId' => $empId,
                                    'shiftId' => $shiftId,
                                ), true, true),
                            ));
                        } else {
                            echo "NO ATTENDANCE INFO FOUND WITHIN THIS TIME RANGE!";
                        }
                    } else {
                        echo "NO EMPLOYEE INFO FOUND!";
                    }
                } else {
                    echo "NO ACTIVE WORKING DAY!";
                }
            }
        }
    }

    public function actionUpload()
    {

        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $folder = 'upload/empPhoto/'; // folder for uploaded files
        $allowedExtensions = array("jpg", "jpeg", "png", "pdf"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 2 * 1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        $fileSize = filesize($folder . $result['filename']); //GETTING FILE SIZE
        $fileName = $result['filename']; //GETTING FILE NAME

        echo $return; // it's array
    }

    public function actionUploadFiles()
    {

        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $folder = 'upload/empFiles/'; // folder for uploaded files
        $allowedExtensions = array("jpg", "jpeg", "png", "pdf"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        $fileSize = filesize($folder . $result['filename']); //GETTING FILE SIZE
        $fileName = $result['filename']; //GETTING FILE NAME

        echo $return; // it's array
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('view', array('model' => $model,));
        if (!isset($_GET['ajax'])) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionView2($id)
    {
        $this->pageTitle = 'TERMINATION LETTER';
        $this->render('view2', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreateEmployeeFromOutSide()
    {
        $model = new Employees;
        $this->performAjaxValidation($model);

        if (isset($_POST['Employees'])) {
            $model->attributes = $_POST['Employees'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $data = Employees::model()->findByPk($model->id);
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'div' => "<div class='flash-notice'>New Employee successfully added</div>",
                        'value' => $data->id,
                        'label' => $data->full_name,
                    ));
                    exit;
                } else
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $resultDiv = '';
            echo CJSON::encode(array(
                'status' => 'failure',
                'resultDiv' => $resultDiv,
                'div' => $this->renderPartial('_form2', array('model' => $model), true)));
            exit;
        } else
            $this->render('create', array('model' => $model,));
    }

    public function actionCreate()
    {
        $model = new Employees;
        if (isset($_POST['Employees'])) {
            $model->attributes = $_POST['Employees'];
            $valid = $model->validate();

            if ($valid) {
                if ($model->save()) {
                    $employeeId = $model->id;
                    echo CJSON::encode(array(
                        'status' => 'success',
                    ));
                    Yii::app()->end();
                    $this->redirect(array('admin'));
                }
            }
        } else {
            $this->pageTitle = 'ADD EMPLOYEE';
            $this->render('_form', array(
                'model' => $model,
            ));
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if (isset($_POST['Employees'])) {
            $model->attributes = $_POST['Employees'];
            if ($model->save()) {
                $this->redirect(array('admin'));
            }
        }

        $this->render('_form2', array(
            'model' => $model,
        ));
    }

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


    public function actionAdmin()
    {
        $model = new Employees('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Employees']))
            $model->attributes = $_GET['Employees'];

        $this->pageTitle = 'EMPLOYEE MANAGE';
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = Employees::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'employees-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //========================== Daily AttendanceReport ======================//
    public function actionAttendanceReportDaily()
    {
        $model = new Employees;

        $this->pageTitle = 'DAILY ATTN. REPORT';
        $this->render('attendanceReportDaily', array(
            'model' => $model,
        ));
    }

    public function actionAttendanceReportDailyView()
    {
        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['department_id'], $_POST['Employees']['id'])) {
            if ($_POST['Employees']['startDate'] != "" && $_POST['Employees']['endDate'] != "") {

                $pStatus = $_POST['Employees']['p_status'];

                $startDate = $_POST['Employees']['startDate'];
                $endDate = $_POST['Employees']['endDate'];

                $monthFetch = substr($endDate, 5, 2);
                $yearFetch = substr($endDate, 0, 4);

                $monthInfo = Months::model()->findByAttributes(array('code' => $monthFetch)); // month info
                $monthId = $monthInfo->id;
                $monthName = $monthInfo->month_name;

                $condition = "month_id=" . $monthId . " AND year=" . $yearFetch;
                $activeWorkingDay = WorkingDay::model()->findAll(array('condition' => $condition), 'id'); // active working day info
                $working_day = 0;
                $days_of_month = 0;
                foreach ($activeWorkingDay as $awd):
                    $days_of_month = $awd->days_of_month;
                    $working_day = $awd->working_day;
                endforeach;

                $condition = "is_active=" . Employees::ACTIVE . " AND id_no!=''";
                if ($_POST['Employees']['department_id'] != "") {
                    $departmentId = $_POST['Employees']['department_id'];
                    $condition .= " AND department_id=" . $departmentId;
                }
                if ($_POST['Employees']['id'] != "") {
                    $id = $_POST['Employees']['id'];
                    $condition .= " AND id=" . $id;
                }

                $condition .= " ORDER BY department_id ASC";

                $data = Employees::model()->findAll(array('condition' => $condition));
                $message = "Daily Attendance Report on " . $startDate . " To " . $endDate;
                echo CJSON::encode(array(
                    'content' => $this->renderPartial('attendanceReportDailyView', array(
                        'pStatus' => $pStatus,
                        'data' => $data,
                        'message' => $message,
                        'date' => $startDate,
                        'days_of_month' => $days_of_month,
                        'working_day' => $working_day,
                        'monthFetch' => $monthFetch,
                        'yearFetch' => $yearFetch,
                    ), true, true),
                ));
            }
        }
    }

    //========================== Daily AttendanceReport End ======================//	
    //========================== PF Conribution Report ======================//
    public function actionPfContributionReport()
    {
        $model = new Employees;

        $this->pageTitle = 'PF CONTRIBUTION REPORT';
        $this->render('pfContributionReport', array(
            'model' => $model,
        ));
    }

    public function actionPfContributionReportView()
    {

        if (isset($_POST['Employees']['startDate'], $_POST['Employees']['endDate'], $_POST['Employees']['department_id'], $_POST['Employees']['id'])) {
            if ($_POST['Employees']['startDate'] != "" && $_POST['Employees']['endDate'] != "") {

                $startDate = $_POST['Employees']['startDate'];
                $endDate = $_POST['Employees']['endDate'];

                $departmentName = "";
                $empId = 0;
                $departmentId = 0;
                $dataEmp = false;
                $message = "PF Contribution Report for " . $startDate . " To " . $endDate;

                if ($_POST['Employees']['id'] != "") {
                    $empId = $_POST['Employees']['id'];
                    $conditionEmp = "id=" . $empId . " ORDER BY full_name ASC";
                    $dataEmp = Employees::model()->findAll(array('condition' => $conditionEmp));
                } elseif ($_POST['Employees']['department_id'] != "") {
                    $departmentId = $_POST['Employees']['department_id'];
                    $conditionEmp = "department_id=" . $departmentId . " ORDER BY full_name ASC";
                    $dataEmp = Employees::model()->findAll(array('condition' => $conditionEmp));

                    $departmentName = Departments::model()->nameOfThis($departmentId);
                    if ($departmentName != "") {
                        $message .= "<br>Department: " . $departmentName;
                    }
                }

                echo CJSON::encode(array(
                    'content' => $this->renderPartial('pfContributionReportView', array(
                        'dataEmp' => $dataEmp,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'empId' => $empId,
                        'departmentId' => $departmentId,
                        'message' => $message,
                    ), true, true),
                ));
            }
        }
    }

    //========================== PF Conribution Report End ======================//


    public function actionJqueryEmpSearch()
    {
        $empName = trim($_POST['empName']);
        $results = array();
        $criteria = new CDbCriteria();
        $criteria->select = 'id, full_name, id_no';
        $criteria->compare("full_name", $empName, true);
        $empModel = Employees::model()->findAll($criteria);
        if ($empModel) {
            foreach ($empModel as $emp) {
                $value = $emp->id;
                $label = $emp->full_name . '(' . $emp->id_no . ')';
                $results[] = array('value' => $value, 'label' => $label);
            }
        }

        echo json_encode($results);
    }

}
