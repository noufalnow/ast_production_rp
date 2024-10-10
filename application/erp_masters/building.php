<?php

class buildingController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/building.php';
        require_once __DIR__ . '/../admin/!model/company.php';
        $formRender = true;
        $form = new form();
        $building = new building();

        $form->addElement('bld_name', 'Building Name', 'text', 'required|alpha_space');
        $form->addElement('bld_no', 'No. ', 'text', 'required|alpha_space');
        $form->addElement('bld_area', 'Area', 'text', 'required|alpha_space');
        $form->addElement('bld_block_no', 'Block', 'text', 'required|alpha_space');
        $form->addElement('bld_plot_no', 'Plot', 'text', 'required|alpha_space');
        $form->addElement('bld_way', 'Way', 'text', 'required|alpha_space');
        $form->addElement('bld_street', 'Street', 'text', 'required|alpha_space');
        $form->addElement('bld_block', 'Block', 'text', 'required|alpha_space');
        
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'bld_name' => $valid['bld_name'],
                        'bld_no' => $valid['bld_no'],
                        'bld_area' => $valid['bld_area'],
                        'bld_block_no' => $valid['bld_block_no'],
                        'bld_plot_no' => $valid['bld_plot_no'],
                        'bld_way' => $valid['bld_way'],
                        'bld_street' => $valid['bld_street'],
                        'bld_block' => $valid['bld_block'],
                        'bld_block' => $valid['company'],
                    );
                    $buildingId = $building->add($data);

                    if ($buildingId) {

                        $feedback = 'Building details added successfully';
                        $this->view->NoViewRender = true;
                        $success = array(
                            'feedback' => $feedback
                        );
                        $_SESSION['feedback'] = $feedback;
                        $success = json_encode($success);
                        die($success);
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/building.php';
        require_once __DIR__ . '/../admin/!model/company.php';
        $building = new building();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);

        $decBuildingId = $this->view->decode($this->view->param['ref']);

        if (! $decBuildingId)
            die('tampered');

        $form->addElement('bld_name', 'Building Name', 'text', 'required|alpha_space');
        $form->addElement('bld_no', 'No. ', 'text', 'required|alpha_space');
        $form->addElement('bld_area', 'Area', 'text', 'required|alpha_space');
        $form->addElement('bld_block_no', 'Block', 'text', 'required|alpha_space');
        $form->addElement('bld_plot_no', 'Plot', 'text', 'required|alpha_space');
        $form->addElement('bld_way', 'Way', 'text', 'required|alpha_space');
        $form->addElement('bld_street', 'Street', 'text', 'required|alpha_space');
        $form->addElement('bld_block', 'Block', 'text', 'required|alpha_space');
        
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));

        $buildingDetails = $building->getBuildingDetById(['bld_id'=>$decBuildingId]);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'bld_name' => $valid['bld_name'],
                        'bld_no' => $valid['bld_no'],
                        'bld_area' => $valid['bld_area'],
                        'bld_block_no' => $valid['bld_block_no'],
                        'bld_plot_no' => $valid['bld_plot_no'],
                        'bld_way' => $valid['bld_way'],
                        'bld_street' => $valid['bld_street'],
                        'bld_block' => $valid['bld_block'],
                        'bld_comp' => $valid['company'],
                    );

                    $modifyBuilding = $building->modify($data, $buildingDetails['bld_id']);

                    $feedback = 'Building details Updated successfully';
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => $feedback
                    );
                    $_SESSION['feedback'] = $feedback;
                    $success = json_encode($success);
                    die($success);
                }
            }
        } else {
            $form->bld_name->setValue($buildingDetails['bld_name']);
            $form->bld_no->setValue($buildingDetails['bld_no']);
            $form->bld_area->setValue($buildingDetails['bld_area']);
            $form->bld_block_no->setValue($buildingDetails['bld_block_no']);
            $form->bld_plot_no->setValue($buildingDetails['bld_plot_no']);
            $form->bld_way->setValue($buildingDetails['bld_way']);
            $form->bld_street->setValue($buildingDetails['bld_street']);
            $form->bld_block->setValue($buildingDetails['bld_block']);
            $form->company->setValue($buildingDetails['bld_comp']);
            
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/customer.php';
        $formRender = true;

        $customer = new customer();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);

        $decCustId = $this->view->decode($this->view->param['ref']);

        if (! $decCustId)
            die('tampered');

        $customerDetail = $customer->getCustomerDet(array(
            'cust_id' => $decCustId
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $customer->deleteBuilding($decCustId);
                if ($delete) {

                    $_SESSION['feedback'] = 'The customer has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
    }

    public function listAction()
    {
        include __DIR__ . '/../admin/!model/building.php';

        $form = new form();

        $form->addElement('f_bld_name', 'Name', 'text', 'alpha_space');
        $form->addElement('f_bld_no', 'No.', 'text', 'alpha_space');

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
                    'f_bld_name' => @$valid['f_bld_name'],
                    'f_bld_no' => @$valid['f_bld_no']
                );
            }
            $filter_class = 'btn-info';
        }

        $buildingObj = new building();

        $this->view->BuildingList = $buildingObj->getBuildingPaginate(@$where);
        ;
        $this->view->form = $form;
        $this->view->buildingObj = $buildingObj;
        $this->view->filter_class = $filter_class;
    }

    public function dashAction()
    {
        
        $form = new form();
        
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_build_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat",
            )
        ));
        
        $where = [];
                
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
                    'f_building' => @$valid['f_building'],
                    'f_prop_cat' => @$valid['f_build_cat']
                );
            }
            $filter_class = 'btn-info';
        }
        
        
        require_once __DIR__ . '/../admin/!model/property.php';
        $propertyObj = new property();
        $propertyList = $propertyObj->getPropertyReport($where);
        
        $statusList['Vacant'] = [];
        $statusList['Agreement'] = [];
        $statusList['Maintenance'] = [];
        

        foreach ($propertyList as $build) {

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

            $property_status = $build['property_status'] == 'Under Other Agreement' ? 'Agreement' : $build['property_status'];

            $propList[$build['prop_building']][$propLevel[$build['prop_level']]][$build['prop_fileno']] = $property_status;

            $buildList[$build['prop_building']] = $build['bld_name'];
            $statusList[$property_status][] = $property_status;
        }
        //

        $sCount['Vacant'] = count($statusList['Vacant']);
        $sCount['Agreement'] = count($statusList['Agreement']);
        $sCount['Maintenance'] = count($statusList['Maintenance']);
        $sCount['Total'] = $sCount['Vacant'] + $sCount['Agreement'] + $sCount['Maintenance'];

        $sCount['Vacant%'] = round($sCount['Vacant'] / $sCount['Total'] * 100, 2);
        $sCount['Agreement%'] = round($sCount['Agreement'] / $sCount['Total'] * 100, 2);
        $sCount['Maintenance%'] = round($sCount['Maintenance'] / $sCount['Total'] * 100, 2);

        // echo "<pre>"; print_r($sCount);die();

        // s($propertyList);
        $this->view->form = $form;
        $this->view->propList = $propList;
        $this->view->buildList = $buildList;
        $this->view->filter_class = $filter_class;
        $this->view->sCount = $sCount;
    }

    public function viewAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/building.php';

        $buildingObj = new building();

        $decBuildingId = $this->view->decode($this->view->param['ref']);

        if (! $decBuildingId)
            die('tampered');

            $buildingDetail = $buildingObj->getBuildingDetById(['bld_id'=>$decBuildingId]);

        $this->view->buildingDetail = $buildingDetail;
    }
}
