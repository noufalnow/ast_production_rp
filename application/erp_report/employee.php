<?php

class employeeController extends mvc
{

    public function empcontractAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/employee.php';

        $empObj = new employee();

        $employeeList = $empObj->getEmpContractReport();

        $this->view->employeeList = $employeeList;
    }

    public function empdocumentAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/employee.php';
        $where = [];

        $ref = $this->view->param['ref'];

        if (empty($_GET['f_monthpick']) && $ref != '') {

            if ($ref == 'past') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Employee Document Expiry Report - Before  ' . date_format(new DateTime(), 'F');
            } else if ($ref == 'exp') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Employee Document Expiry Consolidated Report - Until  ' . date_format(new DateTime(), 'F');
            } else if ($ref != 'past' && $ref != 'exp') {
                $date = date_create_from_format(DFS_DB, $ref . '-01');
                $_GET['f_monthpick'] = date_format($date, 'm/Y');
                $month = date_format($date, 'F');
                $title = 'Employee Document Expiry Report - ' . $month;
            }
        } else {
            $title = 'Employee Document Report';
        }

        $form = new form();
        $form->addElement('f_name', 'Name ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

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

                // a($_GET);

                $where = array(
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_company' => @$valid['f_company'],
                    'f_dept' => @$valid['f_dept'],
                    'f_desig' => @$valid['f_desig'],
                    'f_natonality' => @$valid['f_natonality']
                );
            }
            $filter_class = 'btn-info';
        }

        $empObj = new employee();
        $docMst = array(
            '1' => "Passport",
            '2' => "Resident ID",
            '3' => "Visa",
            '4' => "License",
            '5' => "Insurance",
            '6' => "PDO License",
            '7' => "PDO Passport",
            '8' => "H2S Card",
            '9' => "OXY Passport",
            '10' => "OXY License",
            '11' => "OXY H2S",
            '12' => "Work Contract",
        );
        // s($where);

        $employeeList = $empObj->getEmployeesDocReport(@$where);

        $this->view->employeeList = $employeeList;
        $this->view->form = $form;
        $this->view->filter_class = $filter_class;
        $this->view->title = $title;
        $this->view->docMst = $docMst;
    }

    public function employeeAction()
    {
        $this->view->response('window');
        $where = [];

        require_once __DIR__ . '/../admin/!model/employee.php';

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

        $empObj = new employee();

        $employeeList = $empObj->getEmployeesReport(@$where);

        $this->view->employeeList = $employeeList;
        $this->view->filter_class = $filter_class;
        $this->view->form = $form;
        $this->view->nation = $nation;
    }

    /*
     * public function consolidated()
     * {
     * $this->view->response('ajax');
     * require_once __DIR__ . '/../admin/!model/employee.php';
     * require_once __DIR__ . '/../admin/!model/employeepay.php';
     * require_once __DIR__ . '/../admin/!model/contacts.php';
     * require_once __DIR__ . '/../admin/!model/documents.php';
     * $docs = new documets();
     * $empPayObj = new employeepay();
     * $empContObj = new contacts();
     *
     *
     *
     * $status = array(
     * 1 => "Enable",
     * 2 => "Disable"
     * );
     * $nation = array(
     * 1 => "Omani",
     * 2 => "Indian",
     * 3 => "Pakistani",
     * 4 => "Bangladeshi"
     * );
     * $docMst = array(
     * '1' => "Passport",
     * '2' => "Resident ID",
     * '3' => "Visa",
     * '4' => "License",
     * '5' => "Insurance",
     * '6' => "PDO License",
     * '7' => "PDO Passport",
     * '8' => "H2S Card"
     * );
     * $payParticulers = array(
     * '0' => "Starting",
     * '1' => "Increment",
     * '2' => "Promotion",
     * '3' => "Appraisal",
     * '4' => "Decrement",
     * '5' => "Demotion"
     * );
     * $contactType = array(
     * '1' => "Residence",
     * '2' => "Overseas",
     * '3' => "Currespondance"
     * );
     *
     * $employee = new Employee();
     *
     * $employeeList = $employee->getEmployeesReport();
     *
     * return array(
     * 'filter_class' => $filter_class,
     * 'reportlist' => $employeeList,
     * 'crypt' => $crypt
     * );
     * }
     *
     * public function consolidatedvhl()
     * {
     * $this->view->response('ajax');
     * require_once __DIR__ . '/../admin/!model/vehicle.php';
     * require_once __DIR__ . '/../admin/!model/documents.php';
     * $docs = new documets();
     *
     *
     * $status = array(
     * 1 => "Enable",
     * 2 => "Disable"
     * );
     * $nation = array(
     * 1 => "Omani",
     * 2 => "Indian",
     * 3 => "Pakistani",
     * 4 => "Bangladeshi"
     * );
     * $docMst = array(
     * '301' => "Mulkia",
     * '302' => "PDO",
     * '303' => "Fitness",
     * '304' => "IVMS",
     * '305' => "Insurance"
     * );
     *
     * $vehicle = new Vehicle();
     *
     * $vehicleList = $vehicle->getVehicleReport();
     *
     * return array(
     * 'filter_class' => $filter_class,
     * 'reportlist' => $vehicleList,
     * 'crypt' => $crypt
     * );
     * }
     */
}
