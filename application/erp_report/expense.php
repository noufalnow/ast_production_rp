<?php

class expenseController extends mvc
{

    public function expenseAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/expense.php';

        $form = new form();

        $form->addElement('f_refno', 'Ref No', 'text', '');
        $form->addElement('f_particulers', 'Particulers', 'text', '');

        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $venderList = $vendorObj->getVendorPairFilter();
        $form->addElement('f_selVendor', 'Vendor', 'select', '', array(
            'options' => $venderList
        ));
        
        $form->addElement('f_status', 'Status', 'select', '', array(
            'options' => array(
                "2" => "Pending",
                1 => "Approved"
            )
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));

        $form->addElement('f_pCatSelect', 'Parent Cat', 'select', '', array(
            'options' => $pCatList
        ));
        $form->addElement('f_sCatSelect', 'Sub Cat', 'select', '', array(
            'options' => $sCatList
        ));
        $form->addElement('f_cCatSelect', 'Category', 'select', '', array(
            'options' => $cCatList
        ));
        $form->addElement('f_mainhead', 'Head', 'select', '', array(
            'options' => array(
                1 => "Employee",
                2 => "Property",
                3 => "Vehicle",
                4 => "Port Operation"
            )
        ));
        $form->addElement('f_amount', 'Total Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));

        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();

        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();

        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehModelObj = new vehicle();
        $vehList = $vehModelObj->getVehiclePair();

        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();

        $form->addElement('f_employee', 'Employee', 'multiselect', '', array(
            'options' => $empList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Employees"
        ));
        $form->addElement('f_property', 'Property', 'multiselect', '', array(
            'options' => $propList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Properties"
        ));
        $form->addElement('f_vehicle', 'Vehicle', 'multiselect', '', array(
            'options' => $vehList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Vehicles"
        ));

        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));

        $form->addElement('f_mode', 'Mode', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit",
                3 => "Pending"
            )
        ));
        $form->addElement('f_period', 'Month/Period', 'radio', '', array(
            'options' => array(
                1 => "Month",
                2 => "Period"
            )
        ));
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $where = [];
        $filter_class = 'btn-primary';
        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        } else if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {

                $where = array(
                    'f_refno' => @$valid['f_refno'],
                    'f_particulers' => @$valid['f_particulers'],
                    'f_selVendor' => @$valid['f_selVendor'],
                    'f_company' => @$valid['f_company'],
                    'f_mainhead' => @$valid['f_mainhead'],
                    'f_pCatSelect' => @$valid['f_pCatSelect'],
                    'f_sCatSelect' => @$valid['f_sCatSelect'],
                    'f_cCatSelect' => @$valid['f_cCatSelect'],
                    'f_mode' => @$valid['f_mode'],
                    'f_amount' => @$valid['f_amount'],
                    'f_building' => @$valid['f_building'],
                    'f_status' => @$valid['f_status'],
                );

                if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
                    $billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
                    $billdtfrom = date_format($billdtfrom, DFS_DB);
                    $where['f_dtfrom'] = $billdtfrom;
                }
                if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
                    $billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
                    $billdtto = date_format($billdtto, DFS_DB);
                    $where['f_dtto'] = $billdtto;
                }

                if (! empty($valid['f_monthpick']) && $valid['f_period'] == 1) {
                    $where['f_monthpick'] = $valid['f_monthpick'];
                }

                if (! empty($valid['f_employee'])) {
                    $where['f_mrefs'] = $valid['f_employee'];
                } elseif (! empty($valid['f_property'])) {
                    $where['f_mrefs'] = $valid['f_property'];
                } elseif (! empty($valid['f_vehicle'])) {
                    $where['f_mrefs'] = $valid['f_vehicle'];
                }
            }
            $filter_class = 'btn-info';
        } else if (! isset($_GET)) {
            $form->f_mode->setValue(3);

            $where = array(
                'f_mode' => 3
            );
            $filter_class = 'btn btn-info';
        }else {
            if (empty($_GET)) {

                $form->f_mode->setValue(3);
                
                $where = array(
                    'f_mode' => 3
                );

                $currentMonth = date('Y-m'); // Format: YYYY-MM (e.g., 2025-11)
                
                
                $where['f_monthpick'] = date('m/Y');
                //$where['f_period'] = 1;
                
                $form->f_monthpick->setValue($currentMonth);
                $form->f_period->setValue(1);
                
                $filter_class = 'btn btn-info';
                
                
            }
        }

        $expObj = new expense();
        
        $expObj->_pagelimit  = 1000; 
        $expenseList = $expObj->geExpenseReport(@$where);
        $expenseSummery = $expObj->geExpenseReportSummary(@$where);
        $this->view->expObj= $expObj;

        $this->view->expenseSummery = $expenseSummery;
        $this->view->expenseList = $expenseList;
        $this->view->filter_class = $filter_class;
        $this->view->form = $form;
    }
    
    
    public function expensevatAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/expense.php';
        
        $form = new form();
        
        $form->addElement('f_refno', 'Ref No', 'text', '');
        $form->addElement('f_particulers', 'Particulers', 'text', '');
        
        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $venderList = $vendorObj->getVendorPairFilter();
        $form->addElement('f_selVendor', 'Vendor', 'select', '', array(
            'options' => $venderList
        ));
        
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));
        
        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));
        
        $form->addElement('f_pCatSelect', 'Parent Cat', 'select', '', array(
            'options' => $pCatList
        ));
        $form->addElement('f_sCatSelect', 'Sub Cat', 'select', '', array(
            'options' => $sCatList
        ));
        $form->addElement('f_cCatSelect', 'Category', 'select', '', array(
            'options' => $cCatList
        ));
        $form->addElement('f_mainhead', 'Head', 'select', '', array(
            'options' => array(
                1 => "Employee",
                2 => "Property",
                3 => "Vehicle",
                4 => "Port Operation"
            )
        ));
        $form->addElement('f_amount', 'Total Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));
        
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        
        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();
        
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehModelObj = new vehicle();
        $vehList = $vehModelObj->getVehiclePair();
        
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        
        $form->addElement('f_employee', 'Employee', 'multiselect', '', array(
            'options' => $empList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Employees"
        ));
        $form->addElement('f_property', 'Property', 'multiselect', '', array(
            'options' => $propList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Properties"
        ));
        $form->addElement('f_vehicle', 'Vehicle', 'multiselect', '', array(
            'options' => $vehList
        ), array(
            'multiple' => "multiple",
            "class" => "form-control",
            "data-placeholder" => "Choose Vehicles"
        ));
        
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        
        $form->addElement('f_mode', 'Mode', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit",
                3 => "Pending"
            )
        ));
        $form->addElement('f_period', 'Month/Period', 'radio', '', array(
            'options' => array(
                1 => "Month",
                2 => "Period"
            )
        ));
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $where = [];
        $filter_class = 'btn-primary';
        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        } else if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                
                $where = array(
                    'f_refno' => @$valid['f_refno'],
                    'f_particulers' => @$valid['f_particulers'],
                    'f_selVendor' => @$valid['f_selVendor'],
                    'f_company' => @$valid['f_company'],
                    'f_mainhead' => @$valid['f_mainhead'],
                    'f_pCatSelect' => @$valid['f_pCatSelect'],
                    'f_sCatSelect' => @$valid['f_sCatSelect'],
                    'f_cCatSelect' => @$valid['f_cCatSelect'],
                    'f_mode' => @$valid['f_mode'],
                    'f_amount' => @$valid['f_amount'],
                    'f_building' => @$valid['f_building']
                );
                
                if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
                    $billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
                    $billdtfrom = date_format($billdtfrom, DFS_DB);
                    $where['f_dtfrom'] = $billdtfrom;
                }
                if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
                    $billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
                    $billdtto = date_format($billdtto, DFS_DB);
                    $where['f_dtto'] = $billdtto;
                }
                
                if (! empty($valid['f_monthpick']) && $valid['f_period'] == 1) {
                    $where['f_monthpick'] = $valid['f_monthpick'];
                }
                
                if (! empty($valid['f_employee'])) {
                    $where['f_mrefs'] = $valid['f_employee'];
                } elseif (! empty($valid['f_property'])) {
                    $where['f_mrefs'] = $valid['f_property'];
                } elseif (! empty($valid['f_vehicle'])) {
                    $where['f_mrefs'] = $valid['f_vehicle'];
                }
            }
            $filter_class = 'btn-info';
        } else if (! isset($_GET)) {
            $form->f_mode->setValue(3);
            
            $where = array(
                'f_mode' => 3
            );
            $filter_class = 'btn btn-info';
        }
        
        $where['exp_vat_option'] = 1;
        
        $expObj = new expense();
        $expObj->_pagelimit  = 1000; 
        
        $expenseList = $expObj->geExpenseReport(@$where);

        $this->view->expObj= $expObj;
        
        $this->view->expenseList = $expenseList;
        $this->view->filter_class = $filter_class;
        $this->view->form = $form;
    }
    

    public function expensecategorywiseAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/expense.php';
        $form = new form();

        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));

        $form->addElement('f_pCatSelect', 'Parent Cat', 'select', '', array(
            'options' => $pCatList
        ));
        $form->addElement('f_sCatSelect', 'Sub Cat', 'select', '', array(
            'options' => $sCatList
        ));
        $form->addElement('f_cCatSelect', 'Category', 'select', '', array(
            'options' => $cCatList
        ));
        $form->addElement('f_mainhead', 'Head', 'select', '', array(
            'options' => array(
                1 => "Employee",
                2 => "Property",
                3 => "Vehicle",
                4 => "Port Operation"
            )
        ));

        $form->addElement('f_mode', 'Mode', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit",
                3 => "Pending"
            )
        ));
        $form->addElement('f_period', 'Month/Period', 'radio', '', array(
            'options' => array(
                1 => "Month",
                2 => "Period"
            )
        ));
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $where = [];
        $filter_class = 'btn-primary';
        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        } else if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {

                $where = array(
                    'f_mainhead' => @$valid['f_mainhead'],
                    'f_pCatSelect' => @$valid['f_pCatSelect'],
                    'f_sCatSelect' => @$valid['f_sCatSelect'],
                    'f_cCatSelect' => @$valid['f_cCatSelect'],
                    'f_mode' => @$valid['f_mode']
                );

                if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
                    $billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
                    $billdtfrom = date_format($billdtfrom, DFS_DB);
                    $where['f_dtfrom'] = $billdtfrom;
                }
                if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
                    $billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
                    $billdtto = date_format($billdtto, DFS_DB);
                    $where['f_dtto'] = $billdtto;
                }

                if (! empty($valid['f_monthpick']) && $valid['f_period'] == 1) {
                    $where['f_monthpick'] = $valid['f_monthpick'];
                    $date = date_create_from_format(DF_DD, '01/' . $_GET['f_monthpick']);

                    $month = date_format($date, 'F-Y');
                    $title = ' (' . $month . ")";
                }
            }
            $filter_class = 'btn-info';
        }

        $modeNo['1'] = '<b>1</b> . ';
        $modeNo['2'] = '<b>2</b> . ';

        $mode['1'] = ' Cash ';
        $mode['2'] = ' Credit ';

        $expObj = new expense();

        $expenseList = $expObj->geExpenseCatWiseReportSummary(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->expenseList = $expenseList;
        $this->view->form = $form;
        $this->view->title = $title;
        $this->view->modeNo = $modeNo;
        $this->view->f_mode = @$valid['f_mode'];
        $this->view->mode = $mode;
    }

    public function expvendoroutstandingAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/expense.php';

        $decVenId = $this->view->decode($this->view->param['ref']);

        if (! $decVenId)
            die('tampered');

        $expenseObj = new expense();
        $expenseList = $expenseObj->getExpenseVendorReport(array(
            'ven_id' => $decVenId
        ));

        $this->view->expenseList = $expenseList;
    }

    public function expvensummaryAction()
    {
        $this->view->response('window');
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vendor.php';

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
        $where = [];
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

        $vendObj = new vendor();

        // s($where);

        $expList = $vendObj->getBillByVendor(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->expList = $expList;
    }
    
    public function paymentsAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/payment.php';
        
        $form = new form();
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $vendorList = $vendorObj->getVendorPair();
        $form->addElement('f_selVendor', 'Vendor', 'select', '', array(
            'options' => $vendorList
        ));
        
        $date = new DateTime();
        if ($_GET['f_monthpick'] == "") {
            $where = array(
                'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
            );
            $form->f_monthpick->setValue($date->format('m') . '/' . $date->format('Y'));
        } else {
            $date = date_create_from_format(DF_DD, '01/' . $_GET['f_monthpick']);
        }
        
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
                    'f_selVendor' => @$valid['f_selVendor'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info';
        } else {}
        
        $payObj = new payment();


        $payList = $payObj->getPaymentReport(@$where);
        
        $month = date_format($date, 'F-Y');
        $title = '<b>3</b> . Credit Payments done for the month - ' . $month;
              
        
        $this->view->filter_class = $filter_class;
        $this->view->payList = $payList;
        $this->view->form = $form;
        $this->view->title = $title;

    }
}
