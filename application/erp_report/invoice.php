<?php

class invoiceController extends mvc
{
    public function billbycustomerAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/customer.php';
        
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
        
        $custObj = new customer();
        
        // s($where);
        
        $billList = $custObj->getBillByCustomer(@$where);
        
        $this->view->filter_class=$filter_class;
        $this->view->billList=$billList;
        
    }
    
    public function billcustoutstandingAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/bill.php';
        
        
        $decCustId = $this->view->decode($this->view->param['ref']);
        
        if (! $decCustId)
            die('tampered');
            
            $billObj = new bill();
            $billList = $billObj->getBillPendingReport(array(
                'cust_id' => $decCustId
            ));
            
            $this->view->billList=$billList;
    }
    
    
    
    public function billlistingAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/customer.php';
        
        
        $form = new form();
        
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));
        
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        
        $form->addElement('f_customer', 'Customer', 'select', '', array(
            'options' => $customerList
        ));
        $form->addElement('f_refno', 'Reference No', 'text', '');
        $form->addElement('f_billno', 'Bill No', 'text', '');
        $form->addElement('f_paymode', 'Payment Type', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit",
                3 => "Paid",
                4 => "Pending"
            )
        ));
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_item', 'Item.. ', 'text', '');
        $form->addElement('f_location', 'Location', 'text', '');
        
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
                    'f_customer' => @$valid['f_customer'],
                    'f_refno' => @$valid['f_refno'],
                    'f_paymode' => @$valid['f_paymode'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_billno' => @$valid['f_billno'],
                    'f_item' => @$valid['f_item'],
                    'f_location' => @$valid['f_location']
                );
            }
            $filter_class = 'btn-info';
        }
        
        $billObj = new bill();
        
        // s($where);
        
        $billList = $billObj->getAllBillReport(@$where);

        $this->view->filter_class=$filter_class;
        $this->view->billList=$billList;
        $this->view->form=$form;
        $this->view->refno=@$valid['f_refno'];
    }
    
    public function billoutstandingAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/bill.php';
        
        
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
        
        $billObj = new bill();
        
        // s($where);
        
        $billList = $billObj->getBillPendingReport(@$where);
              
        $this->view->filter_class=$filter_class;
        $this->view->billList=$billList;
        
    }
}