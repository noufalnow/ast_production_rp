<?php

class salaryController extends mvc
{

    public function emppayAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/employeepay.php';
        $empPayObj = new employeepay();

        $form = new form();
        $display = 'display: none;';

        $empId = $this->view->decode($this->view->param['ref']);

        if (! $empId)
            die('tampered');

        $empPay = $empPayObj->getPayDetailsByEmployee(array(
            'pay_emp_id' => $empId
        ));

        $payParticulers = array(
            '0' => "Starting",
            '1' => "Increment",
            '2' => "Promotion",
            '3' => "Appraisal",
            '4' => "Decrement",
            '5' => "Demotion"
        );

        $form->addElement('bp', 'Basic Pay ', 'text', 'required|numeric');
        $form->addElement('da', 'DA ', 'text', 'numeric');
        $form->addElement('hra', 'HRA', 'text', 'numeric');
        $form->addElement('ta', 'TA', 'text', 'numeric');
        $form->addElement('all1', 'Other Allowence 1 ', 'text', 'numeric');
        $form->addElement('all2', 'Other Allowence 2', 'text', 'numeric');
        $form->addElement('all3', 'Other Allowence 3', 'text', 'numeric');
        $form->addElement('wef', 'With effect from ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dor', 'Next date of revision ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('remark', 'Remarks ', 'text', 'alpha_space');

        if (count($empPay) > 0)
            $form->addElement('paytran', 'Pay particulers', 'select', 'required', array(
                'options' => $payParticulers
            ));

        $payParticulers['0'] = "Starting";

        $empSal = $empPayObj->getEmployeePay(array(
            'pay_emp_id' => $empId
        ));

        if ($_POST && $_POST['tab'] == 'sal') {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $wef = DateTime::createFromFormat(DF_DD, $valid['wef']);
                $wef = date_format($wef, DFS_DB);
                if (! empty($valid['dor'])) {
                    $dor = DateTime::createFromFormat(DF_DD, $valid['dor']);
                    $dor = date_format($dor, DFS_DB);
                }
                $data['pay_emp_id'] = $empId;
                $data['pay_basic'] = $valid['bp'];

                if ($valid['paytran'])
                    $data['pay_type'] = $valid['paytran'];

                if ($valid['da'])
                    $data['pay_da'] = $valid['da'];

                if ($valid['hra'])
                    $data['pay_hra'] = $valid['hra'];

                if ($valid['ta'])
                    $data['pay_ta'] = $valid['ta'];

                if ($valid['all1'])
                    $data['pay_allw1'] = $valid['all1'];

                if ($valid['all2'])
                    $data['pay_allw2'] = $valid['all2'];

                if ($valid['all3'])
                    $data['pay_allw3'] = $valid['all3'];

                $data['pay_total'] = $valid['bp'] + $valid['da'] + $valid['hra'] + $valid['ta'] + $valid['all1'] + $valid['all2'] + $valid['all3'];
                $data['pay_wef'] = $wef;

                if ($dor)
                    $data['pay_dor'] = $dor;

                $data['pay_remarks'] = $valid['remark'];

                $insert = $empPayObj->add($data);
                if ($insert) {
                    $form->reset();
                    $this->view->feedback = 'Employee pay details added successfully';
                    $this->view->url = APPURL . "erp_employee/salary/emppay/ref/" . $this->view->param['ref'];
                    $this->view->status = 11;
                    $this->view->target = "menu3";
                }
            } else {
                $display = '';
            }
        } else {
            if ($empSal)
                ($empSal);
            $form->bp->setValue($empSal['pay_basic']);
            $form->da->setValue($empSal['pay_da']);
            $form->hra->setValue($empSal['pay_hra']);
            $form->ta->setValue($empSal['pay_ta']);
            $form->all1->setValue($empSal['pay_allw1']);
            $form->all2->setValue($empSal['pay_allw2']);
            $form->all3->setValue($empSal['pay_allw3']);
        }

        $this->view->form = $form;
        $this->view->display = $display;
        $this->view->empPay = $empPay;
        $this->view->payParticulers = $payParticulers;
    }

    public function emppayeditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/employeepay.php';
        $this->view->NoViewRender = false;
        $form = new form();
        $emppay = new employeepay();

        $payParticulers = array(
            '0' => "Starting",
            '1' => "Increment",
            '2' => "Promotion",
            '3' => "Appraisal",
            '4' => "Decrement",
            '5' => "Demotion"
        );
        $payParticulers['0'] = "Starting";

        $form->addElement('bp', 'Basic Pay ', 'text', 'required|numeric');
        $form->addElement('da', 'DA ', 'text', 'numeric');
        $form->addElement('hra', 'HRA', 'text', 'numeric');
        $form->addElement('ta', 'TA', 'text', 'numeric');
        $form->addElement('all1', 'Other Allowence 1 ', 'text', 'numeric');
        $form->addElement('all2', 'Other Allowence 2', 'text', 'numeric');
        $form->addElement('all3', 'Other Allowence 3', 'text', 'numeric');
        $form->addElement('wef_e', 'With effect from ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dor_e', 'Next date of revision ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('remark', 'Remarks ', 'text', 'alpha_space');
        $form->addElement('paytran', 'Pay particulers', 'select', 'required', array(
            'options' => $payParticulers
        ));

        $payId = $this->view->decode($this->view->param['ref']);
        $empPayDetails = $emppay->getEmployeePayById($payId);
        if (! $payId)
            die('tampered');

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if (! empty($valid['wef_e'])) {
                        $wef_e = DateTime::createFromFormat(DF_DD, $valid['wef_e']);
                        $wef_e = date_format($wef_e, DFS_DB);
                    }
                    if (! empty($valid['dor_e'])) {
                        $dor_e = DateTime::createFromFormat(DF_DD, $valid['dor_e']);
                        $dor_e = date_format($dor_e, DFS_DB);
                    }

                    $data['pay_basic'] = $valid['bp'];

                    if ($valid['paytran'])
                        $data['pay_type'] = $valid['paytran'];

                    if ($valid['da'])
                        $data['pay_da'] = $valid['da'];

                    if ($valid['hra'])
                        $data['pay_hra'] = $valid['hra'];

                    if ($valid['ta'])
                        $data['pay_ta'] = $valid['ta'];

                    if ($valid['all1'])
                        $data['pay_allw1'] = $valid['all1'];

                    if ($valid['all2'])
                        $data['pay_allw2'] = $valid['all2'];

                    if ($valid['all3'])
                        $data['pay_allw3'] = $valid['all3'];

                    $data['pay_total'] = $valid['bp'] + $valid['da'] + $valid['hra'] + $valid['ta'] + $valid['all1'] + $valid['all2'] + $valid['all3'];
                    $data['pay_wef'] = $wef_e;

                    if ($dor_e)
                        $data['pay_dor'] = $dor_e;

                    $data['pay_remarks'] = $valid['remark'];

                    $update = $emppay->modify($data, $payId);
                    if ($update) {
                        $this->view->feedback = 'Pay details updated successfully';
                        $this->view->url = APPURL . "erp_employee/salary/emppay/ref/" . $this->view->encode($empPayDetails['pay_emp_id']);
                        $this->view->status = 11;
                        $this->view->target = "menu3";
                    }
                }
            }
        } else {

            if (! empty($empPayDetails['pay_wef'])) {
                $wef_e = DateTime::createFromFormat(DFS_DB, $empPayDetails['pay_wef']);
                $wef_e = $wef_e->format(DF_DD);
            }
            if (! empty($empPayDetails['pay_dor'])) {
                $dor_e = DateTime::createFromFormat(DFS_DB, $empPayDetails['pay_dor']);
                $dor_e = $dor_e->format(DF_DD);
            }

            $form->paytran->setValue($empPayDetails['pay_type']);
            $form->bp->setValue($empPayDetails['pay_basic']);
            $form->da->setValue($empPayDetails['pay_da']);
            $form->hra->setValue($empPayDetails['pay_hra']);
            $form->ta->setValue($empPayDetails['pay_ta']);
            $form->all1->setValue($empPayDetails['pay_allw1']);
            $form->all2->setValue($empPayDetails['pay_allw2']);
            $form->all3->setValue($empPayDetails['pay_allw3']);
            $form->remark->setValue($empPayDetails['pay_remarks']);
            $form->dor_e->setValue($dor_e);
            $form->wef_e->setValue($wef_e);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/salary.php';

        $form = new form();

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_code' => @$valid['f_code'],
                    'f_name' => @$valid['f_name'],
                    'f_remarks' => @$valid['f_remarks'],
                    'f_price' => @$valid['f_price']
                );
            }
        }

        $salaryObj = new salary();
        $salarysList = $salaryObj->getSalaryPaginate(@$where);
        $offset = $salaryObj->_voffset;

        $this->view->salarysList = $salarysList;
        $this->view->salaryObj = $salaryObj;
        $this->view->offset = $offset;
    }

    public function reportAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/salary.php';

        $salary = new salary();

        $decSalId = $this->view->decode($this->view->param['ref']);

        $salDet = $salary->getSalaryDet(array(
            'sal_id' => $decSalId
        ));

        $date = date_create_from_format(DFS_DB, $salDet['sal_paydate']);
        $this->view->month = date_format($date, 'F-Y');

        if (! $decSalId)
            die('tampered');

        $this->view->decSalId = $decSalId;

        $empObj = new employee();
        $where['sdet_sal_id'] = $decSalId;
        $where['sdet_group_exclude'] = 10;
        $this->view->employeeList = $empObj->getEmployeesSalaryReport($where);

        $this->view->childGrp = array(
            '1' => "ABDULLAH SALEM TRADING",
            '2' => 'FAISAL ABDULLAH SALEM TRADING',

            '3' => 'ABDULLAH SALEM TRADING',
            '4' => 'ALAWI ABDULLAH SALEM TRADING',

            '5' => 'ABDULLAH SALEM TRADING',
            '6' => 'FAISAL ABDULLAH SALEM TRADING',
            '7' => 'ADIL ABDULLAH SALEM TRADING',

            '8' => "ABDULLAH SALEM TRADING",
            '9' => 'FAISAL ABDULLAH SALEM TRADING',
            '10' => 'EXCLUDED FROM SALARY',
            '11' => 'SEPARATE GROUP SALARY',
            '12' => 'TEMPORARY STAFF SALARY'
        );

        $this->view->bankS = array(
            '1' => "BANK MUSCAT",
            '2' => "BANK DHOFAR"
        );

        $this->view->bankK = array(
            '1' => "1",
            '2' => "1",

            '3' => '2',
            '4' => '2',

            '5' => "1",
            '6' => "1",
            '7' => "1",

            '8' => '2',
            '9' => '2'
        );

        $this->view->nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );
        $this->view->category = array(
            1 => "LABOURS",
            2 => "DRIVERS"
        );
    }

    public function salaryAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/salary.php';
        require_once __DIR__ . '/../admin/!model/salarydet.php';

        $salary = new salary();
        $salaryDet = new salarydet();
        $empCount = '';

        $decSalId = $this->view->decode($this->view->param['ref']);

        if (! $decSalId)
            die('tampered');

        $form = new form();
        $form->addElement('f_name', 'Name ', 'text', '');
        $form->addElement('f_empno', 'Emp No ', 'text', '');

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/comp_department.php';
        $compDeptModelObj = new comp_department();
        $compDeptList = $compDeptModelObj->getCompDeptPair();
        $form->addElement('f_dept', 'Department', 'select', '', array(
            'options' => $compDeptList
        ));

        require_once __DIR__ . '/../admin/!model/designation.php';
        $desigModelObj = new designation();
        $desigList = $desigModelObj->getDesigPair();
        $form->addElement('f_desig', 'Designation', 'select', '', array(
            'options' => $desigList
        ));

        $nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );
        $form->addElement('f_natonality', 'Nationality', 'select', '', array(
            'options' => $nation
        ));

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        $filter_class = 'btn-primary';
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_company' => @$valid['f_company'],
                    'f_dept' => @$valid['f_dept'],
                    'f_desig' => @$valid['f_desig'],
                    'f_natonality' => @$valid['f_natonality']
                );
            }
            $filter_class = 'btn-info';
        }

        $salDet = $salary->getSalaryDet(array(
            'sal_id' => $decSalId
        ));

        if ($salDet['sal_status'] == 2)
            die('tampered');

        $empObj = new employee();

        $where['sdet_sal_id'] = $decSalId;

        /*
         * $employeeList = $empObj->getEmployeesSalaryList(@$where);
         * if (! count($employeeList) > 0) {
         * if ($decSalId > 1) {
         * $where['sdet_sal_id'] = $decSalId - 1;
         * } else
         * $where['sdet_sal_id'] = 1;
         *
         * $employeeList = $empObj->getEmployeesSalaryList(@$where);
         * }
         */

        if ($salDet['sal_empcount'] == 0)
            $where['sdet_sal_id'] = $salDet['sal_id'] - 1;
        $employeeList = $empObj->getEmployeesSalaryList(@$where);

        // echo "<pre>";
        // print_r($employeeList);

        // s($employeeList);
        // if new employees manually add to emp details table

        $employeesIds = $empObj->getEmployeesReportPair(@$where);
        $employeesPays = $empObj->getEmployeesReportPayPair(@$where);

        $empSalIds = $salaryDet->getEmpSalIdPair(array(
            'sdet_sal_id' => $decSalId
        ));

        $form->addMultiElement('group', 'Group', 'select', 'required', array(
            'options' => array(
                1 => "Group1",
                2 => "Group2",
                "3" => "Group3",
                "4" => "Group4",
                "5" => "Group5",
                "6" => "Group6",
                "7" => "Group7",
                "8" => "Group8",
                "9" => "Group9",
                "10" => "Not Included",
                "11" => "Other Group",
                '12' => 'TEMPORARY STAFF'
            )
        ), array(
            'class' => 'mod_select',
            '' => ''
        ), $employeesIds);
        $form->addMultiElement('deduction', 'Deduction', 'float', 'numeric', '', array(
            'class' => 'txchqno'
        ), $employeesIds);
        $form->addMultiElement('addition', 'Addition', 'float', 'numeric', '', array(
            'class' => 'txchqno'
        ), $employeesIds);
        $form->addMultiElement('remarks', 'Remarks', 'textarea', '', '', array(
            'class' => 'txchqno'
        ), $employeesIds);

        $form->addElement('status', 'Status', 'checkbox', '', array(
            'options' => array(
                "1" => "Close this month salary and Open for next month"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', '');

        if ($_POST['status'] == 1)
            $form->addRules('note', 'required');

        if (count(array_filter($_POST)) > 0) {

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                /*
                 * $salData ['sal_id'] = $decSalId;
                 * $salDet = $salary->getSalaryDet ( array (
                 * 'sal_id' => $decSalId
                 * ) );
                 * if ($salDet ['sal_id']) {
                 * $salId = $salDet ['sal_id'];
                 * $update = $salary->modify ( $salData,$salId);
                 * } else {
                 * $salId = $salary->add ( $salData );
                 * }
                 */

                // print_r($valid); die();

                foreach ($valid['group'] as $gkey => $gval) {
                    $salDetData = array(
                        'sdet_group' => $gval,
                        'sdet_amt_total' => $employeesPays[$gkey],
                        'sdet_amt_deduct' => ($valid['deduction'][$gkey] == '' ? NULL : $valid['deduction'][$gkey]),
                        'sdet_amt_addition' => ($valid['addition'][$gkey] == '' ? NULL : $valid['addition'][$gkey]),
                        'sdet_amt_net' => ($gval != 10 ? (((float) $employeesPays[$gkey] + (float) $valid['addition'][$gkey]) - (float) $valid['deduction'][$gkey]) : NULL),
                        'sdet_remarks' => $valid['remarks'][$gkey],
                        'sdet_category' => $gval <= 4 ? 1 : ($gval <= 9 ? 2 : NULL)
                    );

                    if ($empSalIds[$gkey]) {
                        $salaryDet->modify($salDetData, array(
                            'sdet_sal_id' => $decSalId,
                            'sdet_emp_id' => $gkey
                        ));
                    } else {

                        $salDetData['sdet_sal_id'] = $decSalId;
                        $salDetData['sdet_emp_id'] = $gkey;

                        $salaryDet->add($salDetData);
                    }

                    if ($gval != 10) {
                        $adddition += (float) $valid['addition'][$gkey];
                        $deduction += (float) $valid['deduction'][$gkey];
                        $net += ($gval != 10 ? (((float) $employeesPays[$gkey] + (float) $valid['addition'][$gkey]) - (float) $valid['deduction'][$gkey]) : 0);
                        $empCount ++;
                    }
                }

                $update = $salary->modify(array(
                    'sal_addition' => $adddition,
                    'sal_deduction' => $deduction,
                    'sal_net' => $net,
                    'sal_empcount' => $empCount
                ), $decSalId);

                // **************** update status *******************//
                if ($valid['status'] == 1)
                    if ($valid['note'] != '') {
                        $salSupd = $salary->modify(array(
                            'sal_status' => 2
                        ), $decSalId);
                        if ($salSupd) {
                            $salary->add(array(
                                'sal_period' => ((int) ($salDet['sal_period'] + 1)),
                                'sal_paydate' => date('Y-m-d', strtotime($salDet['sal_paydate'] . " +1 month")),
                                'sal_status' => 1
                            ));
                        }
                    }

                $form->reset();
                //$this->view->NoViewRender = true;
                //$_SESSION['feedback'] = 'Salary details updated successfully';
                $this->view->feedback = 'Salary details updated successfully';

                /*echo ("<script LANGUAGE='JavaScript'>
		    				//winobjM.close();
                            open(location, '_self').close();
                            window.opener.location.reload(false);
		    				</script>");*/
            }
            // s($_POST);
        } //else {

            $where['sdet_sal_id'] = $decSalId;
            // $salaryDetList = $salaryDet->getSalaryDetList($where);
            // $salaryDetList = $empObj->getSalaryDetList($where);

            /*
             * if (! count($salaryDetList) > 0) {
             * if ($decSalId > 1) {
             * $where['sdet_sal_id'] = $decSalId - 1;
             * } else
             * $where['sdet_sal_id'] = 1;
             *
             * //$salaryDetList = $salaryDet->getSalaryDetList($where);
             * $salaryDetList = $empObj->getSalaryDetList($where);
             *
             * // s($salaryDetList);
             * // s($employeesIds);
             * }
             */
            if ($salDet['sal_empcount'] == 0)
                $where['sdet_sal_id'] = $salDet['sal_id'] - 1;

            $salaryDetList = $empObj->getSalaryDetList($where);

            foreach ($salaryDetList as $slDet) {

                if ($employeesIds[$slDet['sdet_emp_id']]) {
                    $form->group[$slDet['sdet_emp_id']]->setValue($slDet['sdet_group']);
                    $form->deduction[$slDet['sdet_emp_id']]->setValue($slDet['sdet_amt_deduct']);
                    $form->addition[$slDet['sdet_emp_id']]->setValue($slDet['sdet_amt_addition']);
                    $form->remarks[$slDet['sdet_emp_id']]->setValue($slDet['sdet_remarks']);
                }
            }
        //}

        // $mfields = array_keys($form->_elements['group']);
        // $offset = $empObj->_voffset;

        $childGrp = array(
            '1' => "ABDULLAH SALEM TRADING",
            '2' => 'FAISAL ABDULLAH SALEM TRADING',

            '3' => 'ABDULLAH SALEM TRADING',
            '4' => 'ALAWI ABDULLAH SALEM TRADING',

            '5' => 'ABDULLAH SALEM TRADING',
            '6' => 'FAISAL ABDULLAH SALEM TRADING',
            '7' => 'ADIL ABDULLAH SALEM TRADING',

            '8' => "ABDULLAH SALEM TRADING",
            '9' => 'FAISAL ABDULLAH SALEM TRADING',
            '10' => 'EXCLUDED FROM SALARY',
            '11' => 'SEPARATE GROUP SALARY',
            '12' => 'TEMPORARY STAFF SALARY'
        );

        $bankS = array(
            '1' => "BANK MUSCAT",
            '2' => "BANK DHOFAR"
        );

        $bankK = array(
            '1' => "1",
            '2' => "1",

            '3' => '2',
            '4' => '2',

            '5' => "1",
            '6' => "1",
            '7' => "1",

            '8' => '2',
            '9' => '2'
        );

        $category = array(
            1 => "LABOURS",
            2 => "DRIVERS"
        );
        $nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );

        $this->view->form = $form;
        $this->view->employeeList = $employeeList;
        $this->view->childGrp = $childGrp;
        $this->view->bankS = $bankS;
        $this->view->bankK = $bankK;
        $this->view->category = $category;
        $this->view->decSalId = $decSalId;
        $this->view->enbleNew = ($salDet['sal_empcount'] == 0 ? 0 : 1);
    }
}
?>