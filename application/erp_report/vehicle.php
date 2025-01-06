<?php

class vehicleController extends mvc
{

    public function vehicleAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vehicle.php';

        $form = new form();
        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
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
                    'f_model' => @$valid['f_model'],
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_company' => @$valid['f_company'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info';
        }

        $vehicleObj = new vehicle();

        $vehicleList = $vehicleObj->getVehicleReport(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->vehicleList = $vehicleList;
        $this->view->form = $form;
    }

    public function vehiclecontractAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/empcontract.php';

        require_once __DIR__ . '/../admin/!model/customer.php';

        $form = new form();

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();

        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
        ));
        $form->addElement('f_name', 'Name ', 'text', '');
        $form->addElement('f_customer', 'Customer', 'select', '', array(
            'options' => $customerList
        ));
        $form->addElement('f_status', 'Status', 'select', '', array(
            'options' => array(
                '1' => 'Ongoing',
                '2' => 'Completed'
            )
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
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_type' => @$valid['f_type'],
                    'f_name' => @$valid['f_name'],
                    'f_customer' => @$valid['f_customer'],
                    'f_status' => @$valid['f_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $vehicleContract = new empcontract();

        $contractList = $vehicleContract->getVehicleContractReport(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->contractList = $contractList;
        $this->view->form = $form;
    }

    /*
     * public function verify()
     * {
     *
     *
     *
     * require_once __DIR__ . '/../admin/!model/property.php';
     * $propertyObj = new property();
     * require_once __DIR__ . '/../admin/!model/documents.php';
     * $docsObj = new documets();
     *
     * $propertyVerifyList = $propertyObj->getPropertyList(array(
     * 's_verify' => 1
     * ));
     * $docsVerifyList = $docsObj->getDocumentsList(array(
     * 's_verify' => 1,
     * 'doc_ref_type' => DOC_TYPE_COM
     * ));
     *
     * }
     */
    public function vhldocumentAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $ref = $this->view->param['ref'];

        if (empty($_GET['f_monthpick']) && $ref != '') {
            if ($ref == 'past') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Document Expiry Report - Before  ' . date_format(new DateTime(), 'F');
            } else if ($ref == 'exp') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Document Expiry Consolidated Report - Until  ' . date_format(new DateTime(), 'F');
            } else if ($ref != 'past') {
                $date = date_create_from_format(DFS_DB, $ref . '-01');
                $_GET['f_monthpick'] = date_format($date, 'm/Y');
                $month = date_format($date, 'F');
                $title = 'Document Expiry Report - ' . $month;
            }
        } else {
            $title = 'Document Report';
        }

        $form = new form();

        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
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
                    'f_model' => @$valid['f_model'],
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_company' => @$valid['f_company'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info';
        }

        $vehicleObj = new vehicle();

        $vehicleList = $vehicleObj->getVehicleDocReport(@$where);

        $docMst = array(
            '301' => "Mulkia",
            '302' => "PDO",
            '303' => "Fitness",
            '304' => "IVMS",
            '305' => "Insurance",
            '306' => "Municipality Certificate"
        );

        $this->view->filter_class = $filter_class;
        $this->view->vehicleList = $vehicleList;
        $this->view->docMst = $docMst;
        $this->view->title = $title;
        $this->view->form = $form;
    }

    public function vhlexpenseAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vehicle.php';

        $form = new form();
        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_cat', 'Category', 'select', '', array(
            'options' => array(
                '1' => 'Non Commercial',
                '2' => 'Commercial'
            )
        ));
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
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
                    'f_model' => @$valid['f_model'],
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_company' => @$valid['f_company'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_pCatSelect' => @$valid['f_pCatSelect'],
                    'f_sCatSelect' => @$valid['f_sCatSelect'],
                    'f_cCatSelect' => @$valid['f_cCatSelect'],
                    'f_cat' => @$valid['f_cat']
                );
            }
            $filter_class = 'btn-info';
        }

        $vehicleObj = new vehicle();

        $vehicleList = $vehicleObj->getVehicleExpReport(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->vehicleList = $vehicleList;
        $this->view->form = $form;
    }

    public function commvehAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        //

        $form = new form();
        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
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
                    'f_model' => @$valid['f_model'],
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_company' => @$valid['f_company'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info';
        }

        $vehicleObj = new vehicle();

        $where['vhl_comm_status'] = 2; // for commercial vehicle
        $vehicleList = $vehicleObj->getVehicleReport(@$where);

        require_once __DIR__ . '/../admin/!model/vehicleman.php';
        $vhlManModelObj = new vehicleman();
        $manList = $vhlManModelObj->getVManPair();

        $this->view->filter_class = $filter_class;
        $this->view->vehicleList = $vehicleList;
        $this->view->form = $form;
        $this->view->man = $manList;
    }

    
    public function vehicleserviceAction()
    {
        $this->view->response('window');
        
        require_once __DIR__ . '/../admin/!model/service.php';
        $serviceObj = new service();
        //$serviceDetObj = new servicedet();
        
        
        $form = new form();

        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehModelObj = new vehicle();
        $vehList = $vehModelObj->getVehiclePair();
        
        $form->addElement('f_vhlno', 'Vehice No', 'select', '', array(
            'options' => $vehList
        ));
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        
        $form->addElement('f_period', 'Month/Period', 'radio', '', array(
            'options' => array(
                1 => "Month",
                2 => "Period"
            )
        ));
        
        $form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        
        
        $form->addElement('f_type', 'Service Type', 'select', '', array(
            'options' => array(
                1 => "Major Service",
                2 => "Minor Service"
            )
        ));
        
                
        $form->addElement('f_category', 'Service Type', 'select', '', array(
            'options' => array(
                1 => "Maintanance Service",
                2 => "Accident"
            )
        ));
        
        if (isset($_GET) && isset($_GET['clear']) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }
        
        $filter_class = 'btn-primary'; // Default button class
        
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_category' => @$valid['f_category'],
                );
            }
            $filter_class = 'btn-info'; // Highlight filter button if filters are applied
        }
        
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
        
        $vhlService = $serviceObj->getServiceReport($where);
        
        $this->view->serviceDetObj = $serviceDetObj;
        $this->view->vhlService = $vhlService;
        $this->view->form = $form;
        $this->view->filter_class = $filter_class;
        
    }
    
}