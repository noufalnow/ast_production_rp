<?php

class propertyController extends mvc
{

    public function paymentcollectionAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/collection.php';

        $form = new form();
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $form->addElement('f_selCustomer', 'Customer', 'select', '', array(
            'options' => $customerList
        ));
        $form->addElement('f_type', 'Type', 'select', '', array(
            'options' => array(
                1 => "Invoice",
                2 => "Property",
                "" => "Both"
            )
        ));

        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();

        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();

        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_property', 'Property', 'select', '', array(
            'options' => $propList
        ));

        $collObj = new collection();
        $date = new DateTime();

        $where = [];
        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        } elseif ($_GET['f_monthpick'] == "") {
            $where = array(
                'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
            );
            $form->f_monthpick->setValue($date->format('m') . '/' . $date->format('Y'));
        } else {
            $date = date_create_from_format(DF_DD, '01/' . $_GET['f_monthpick']);
        }

        $filter_class = 'btn-primary';
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_selCustomer' => @$valid['f_selCustomer'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_type' => @$valid['f_type'],
                    'f_building' => @$valid['f_building'],
                    'f_property' => @$valid['f_property']
                );
            }
            $filter_class = 'btn-info';
        } else {}

        $collList = $collObj->getPaymentcollection(@$where);

        $month = date_format($date, 'F-Y');
        $title = '<b>4</b> . Payment Collection for the month - ' . $month;

        $this->view->filter_class = $filter_class;
        $this->view->collList = $collList;
        $this->view->form = $form;
        $this->view->title = $title;
    }

    public function propdocumentAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';
        $ref = $this->view->param['ref'];

        if (empty($_GET['f_monthpick']) && $ref != '') {

            if ($ref == 'past') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Contract Agreement Expiry Report - Before  ' . date_format(new DateTime(), 'F');
            } else if ($ref == 'exp') {
                $_GET['f_monthpick'] = $ref;
                $title = 'Contract Agreement Expiry Consolidated Report - Until  ' . date_format(new DateTime(), 'F');
            } else if ($ref != 'past') {
                $date = date_create_from_format(DFS_DB, $ref . '-01');
                $_GET['f_monthpick'] = date_format($date, 'm/Y');
                $month = date_format($date, 'F');
                $title = 'Contract Agreement Expiry Report - ' . $month;
            }
        } else {
            $title = 'Contract Agreement Report';
        }

        $form = new form();
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_propno', 'Property No ', 'text', 'alpha_space');
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_type', 'Type', 'select', '', array(
            'options' => array(
                1 => "1 BHK",
                2 => "2 BHK"
            )
        ));
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );

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
                    'f_propno' => @$valid['f_propno'],
                    'f_building' => @$valid['f_building'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_type' => @$valid['f_prop_type']
                );
            }
            $filter_class = 'btn-info';
        }

        $propObj = new property();
        $docMst = array(
            '201' => "Agreement",
            '202' => "Fire Safety Certificate",
            '203' => "Building Insurance"
        );
        // s($where);

        $propertyList = $propObj->getPropertyDocReport(@$where);

        // To update file name formated with file No.
        /*
         * require_once __DIR__ . '/../admin/!model/files.php';
         * $fileObj = new files();
         * $docMst1 = array('1'=>"Passport", '2'=>"ResidentID",'3'=>"Visa",'4'=>"License");
         * foreach ($propertyList as $doc)
         * {
         * $fileObj->modify(array('file_actual_name'=>$doc['prop_fileno'].'-'.$docMst1[$doc['doc_type']]),
         * $doc['file_id']);
         * }
         */

        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->title = $title;
        $this->view->docMst = $docMst;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
    }

    public function propertyAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';

        $form = new form();
        $form->addElement('f_propname', 'Property', 'text', '');
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );

        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();

        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Vacant",
                2 => "Agreement",
                3 => "Maintenance",
                4 => "Other Agreement"
            )
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
                    'f_propname' => @$valid['f_propname'],
                    'f_building' => @$valid['f_building'],
                    'f_propno' => @$valid['f_propno'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_status' => @$valid['f_prop_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $propertyObj = new property();

        $propertyList = $propertyObj->getPropertyReport(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
    }

    public function propertymeterAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';

        $form = new form();
        $form->addElement('f_propname', 'Property', 'text', '');
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );

        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();

        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Vacant",
                2 => "Agreement",
                3 => "Maintenance",
                4 => "Other Agreement"
            )
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
                    'f_propname' => @$valid['f_propname'],
                    'f_building' => @$valid['f_building'],
                    'f_propno' => @$valid['f_propno'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_status' => @$valid['f_prop_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $propertyObj = new property();

        $propertyList = $propertyObj->getPropertyMeter(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
    }



    public function propvacantAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';

        $form = new form();
        $form->addElement('f_propname', 'Property', 'text', '');
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        /*
         * $propType = array(
         * 1 => "1 BHK",
         * 2 => "2 BHK"
         * );
         */
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );

        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();

        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Vacant",
                2 => "Agreement",
                3 => "Maintenance",
                4 => "Other Agreement"
            )
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
                    'f_propname' => @$valid['f_propname'],
                    'f_building' => @$valid['f_building'],
                    'f_propno' => @$valid['f_propno'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_status' => @$valid['f_prop_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $propertyObj = new property();

        $propertyList = $propertyObj->getPropertyReport(@$where);

        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->propLevel = $propLevel;
    }
    
    public function tenantagreementsAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';
        
        $form = new form();
        $form->addElement('f_propname', 'Property', 'text', '');
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
  
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_type', 'Type', 'select', '', array(
            'options' => array(
                1 => "1 BHK",
                2 => "2 BHK"
            )
        ));
                
        require_once __DIR__ . '/../admin/!model/tenants.php';
        $tenantsObj = new tenants();
        $tenantsList = $tenantsObj->getTenantsPair();
        
        $form->addElement('f_tenants', 'Tenants', 'select', '', array(
            'options' => $tenantsList));
        
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
                    'f_propname' => @$valid['f_propname'],
                    'f_building' => @$valid['f_building'],
                    'f_propno' => @$valid['f_propno'],
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_status' => @$valid['f_prop_status'],
                    'f_tenants' => @$valid['f_tenants']
                );
            }
            $filter_class = 'btn-info';
        }
        
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );
        
        $propertyObj = new property();
        
        $propertyList = $propertyObj->getTenantsReport(@$where);
        
        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
    }
    
}
