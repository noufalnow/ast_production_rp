<?php

class masterController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/company.php';
        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        require_once __DIR__ . '/../admin/!model/customer.php';
        require_once __DIR__ . '/../admin/!model/employee.php';
        $form = new form();
        $form->addElement('vhlno', 'Vehicle No ', 'text', 'required|alpha_space');
        $form->addElement('fileno', 'File no ', 'text', 'required|alpha_space');
        $form->addElement('model', 'Model(Year) ', 'text', 'required|numeric');
        $form->addElement('remarks', 'Remarks', 'text', 'alpha_space');
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();
        $form->addElement('type', 'Vehice Type', 'select', 'required', array(
            'options' => $typeList
        ));
        $form->addElement('cat', 'Vahicle Category', 'radio', 'required', array(
            'options' => array(
                1 => "Non Commercial",
                2 => "Commercial"
            )
        ));
        
        
        require_once __DIR__ . '/../admin/!model/vehicleman.php';
        $vhlManModelObj = new vehicleman();
        $manList = $vhlManModelObj->getVManPair();
        
        $form->addElement('man', 'Manufacturer', 'select', '', array(
            'options' => $manList
        ));
        $form->addElement('rate1', 'Hour Rate', 'float', 'numeric');
        $form->addElement('rate2', 'Day Rate', 'float', 'numeric');
        $form->addElement('rate3', 'Month Rate', 'float', 'numeric');
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        $form->addElement('employed', 'Employed', 'select', '', array(
            'options' => $empList
        ));
        $form->addElement('customer', 'Vendor', 'select', '', array(
            'options' => $customerList
        ));
        $form->addElement('location', 'Location', 'text', '');
        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                require_once __DIR__ . '/../admin/!model/vehicle.php';
                $vehicle = new vehicle();
                $data = array(
                    'vhl_no' => $valid['vhlno'],
                    'vhl_fileno' => $valid['fileno'],
                    'vhl_type' => $valid['type'],
                    'vhl_model' => $valid['model'],
                    'vhl_company' => $valid['company'],
                    'vhl_remarks' => $valid['remarks'],
                    'vhl_comm_status' => $valid['cat']
                );
                if ($valid['rate1'])
                    $data['vhl_rate_hour'] = $valid['rate1'];
                if ($valid['rate2'])
                    $data['vhl_rate_day'] = $valid['rate2'];
                if ($valid['rate3'])
                    $data['vhl_rate_month'] = $valid['rate3'];
                if ($valid['man'])
                    $data['vhl_man'] = $valid['man'];
                if ($valid['employed'])
                    $data['vhl_employed'] = $valid['employed'];
                if ($valid['customer'])
                    $data['vhl_vendor'] = $valid['customer'];
                if ($valid['location'])
                    $data['vhl_site'] = $valid['location'];
                $insert = $vehicle->add($data);
                if ($insert) {
                    $this->view->feedback = 'Vehicle details added successfully';
                    $this->view->NoViewRender = true;
                }
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $formRender = true;
        $vehicle = new vehicle();
        $form = new form();
        $form->addElement('vhlno', 'Vehicle No ', 'text', 'required|alpha_space');
        $form->addElement('fileno', 'File no ', 'text', 'required|alpha_space');
        $form->addElement('model', 'Model(Year) ', 'text', 'required|numeric');
        $form->addElement('remarks', 'Remarks', 'text', 'required|alpha_space');
        $vhlId = $this->view->decode($this->view->param['ref']);
        if (! $vhlId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();
        
        require_once __DIR__ . '/../admin/!model/vehicleman.php';
        $vhlManModelObj = new vehicleman();
        $manList = $vhlManModelObj->getVManPair();
        
        
        $form->addElement('type', 'Vehice Type', 'select', 'required', array(
            'options' => $typeList
        ));
        $form->addElement('cat', 'Vahicle Category', 'radio', 'required', array(
            'options' => array(
                1 => "Non Commercial",
                2 => "Commercial"
            )
        ));
        $form->addElement('man', 'Manufacturer', 'select', '', array(
            'options' => $manList
        ));
        $form->addElement('rate1', 'Hour Rate', 'float', 'numeric');
        $form->addElement('rate2', 'Day Rate', 'float', 'numeric');
        $form->addElement('rate3', 'Month Rate', 'float', 'numeric');
        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        $form->addElement('employed', 'Employed', 'select', '', array(
            'options' => $empList
        ));
        $form->addElement('customer', 'Vendor', 'select', '', array(
            'options' => $customerList
        ));
        $form->addElement('location', 'Location', 'text', '');
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $data = array(
                        'vhl_no' => $valid['vhlno'],
                        'vhl_fileno' => $valid['fileno'],
                        'vhl_type' => $valid['type'],
                        'vhl_model' => $valid['model'],
                        'vhl_company' => $valid['company'],
                        'vhl_remarks' => $valid['remarks'],
                        'vhl_comm_status' => $valid['cat']
                    );
                    if ($valid['rate1'])
                        $data['vhl_rate_hour'] = $valid['rate1'];
                    if ($valid['rate2'])
                        $data['vhl_rate_day'] = $valid['rate2'];
                    if ($valid['rate3'])
                        $data['vhl_rate_month'] = $valid['rate3'];
                    if ($valid['man'])
                        $data['vhl_man'] = $valid['man'];
                    if ($valid['employed'])
                        $data['vhl_employed'] = $valid['employed'];
                    if ($valid['customer'])
                        $data['vhl_vendor'] = $valid['customer'];
                    if ($valid['location'])
                        $data['vhl_site'] = $valid['location'];
                    $update = $vehicle->modify($data, $vhlId);
                    if ($update) {
                        $feedback = 'Vehicle details updated successfully';
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
        } else {
            $vhlDetails = $vehicle->getVehicleById($vhlId);
            $form->vhlno->setValue($vhlDetails['vhl_no']);
            $form->fileno->setValue($vhlDetails['vhl_fileno']);
            $form->type->setValue($vhlDetails['vhl_type']);
            $form->model->setValue($vhlDetails['vhl_model']);
            $form->company->setValue($vhlDetails['vhl_company']);
            $form->remarks->setValue($vhlDetails['vhl_remarks']);
            $form->cat->setValue($vhlDetails['vhl_comm_status']);
            $form->rate1->setValue($vhlDetails['vhl_rate_hour']);
            $form->rate2->setValue($vhlDetails['vhl_rate_day']);
            $form->rate3->setValue($vhlDetails['vhl_rate_month']);
            $form->man->setValue($vhlDetails['vhl_man']);
            $form->employed->setValue($vhlDetails['vhl_employed']);
            $form->customer->setValue($vhlDetails['vhl_vendor']);
            $form->location->setValue($vhlDetails['vhl_site']);
        }
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $formRender = true;
        $form = new form();
        $vehicle = new vehicle();
        $decVehicleId = $this->view->decode($this->view->param['ref']);
        if (! $decVehicleId)
            die('tampered');
        $vehicleDetail = $vehicle->getVehicleById($decVehicleId);
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $vehicle->deleteVehicle($decVehicleId);
                if ($delete) {
                     $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => 'The vehicle has been deleted successfully from the system  .'
                    );
                    $_SESSION['feedback'] = 'The vehicle has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
        $this->view->form = $form;
        $this->view->vehicleDetail = $vehicleDetail;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $form = new form();
        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));
        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();
        
        require_once __DIR__ . '/../admin/!model/vehicleman.php';
        $vhlManModelObj = new vehicleman();
        $manList = $vhlManModelObj->getVManPair();
        
        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
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
                    'f_model' => @$valid['f_model'],
                    'f_type' => @$valid['f_type'],
                    'f_vhlno' => @$valid['f_vhlno'],
                    'f_company' => @$valid['f_company']
                );
            }
            $filter_class = 'btn-info';
        }
        $vehicleObj = new vehicle();
        // s($where);
        $vehicleList = $vehicleObj->getVehiclePaginate(@$where);
        $offset = $vehicleObj->_voffset;
        $this->view->form = $form;
        $this->view->man = $manList;
        $this->view->vehicleList = $vehicleList;
        $this->view->filter_class = $filter_class;
        $this->view->offset = $offset;
        $this->view->vehicleObj = $vehicleObj;
    }

    public function viewAction()
    {
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $vehicle = new vehicle();
        $decVhlId = $this->view->decode($this->view->param['ref']);
        if (! $decVhlId)
            die('tampered');
        $vehicleDetail = $vehicle->getVehicleDet(array(
            'vhl_id' => $decVhlId
        ));
        $vhlDocs = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $decVhlId
        ));
        $vhlImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_VHL,
            'doc_ref_id' => $decVhlId
        ));
        $vhlImage = $vhlImage['0'];
        if ($_POST) {
            $tab = $_POST['tab'];
            switch ($tab) {
                case 'doc':
                    $doc_class = 'active';
                    $doc_active = 'active in';
                    break;
            }
        } else {
            $hom_class = 'active';
            $hom_active = 'active in';
        }
        $docMst = array(
            '301' => "Mulkia",
            '302' => "PDO",
            '303' => "Fitness",
            '304' => "IVMS",
            '305' => "Insurance",
            '306' => "Municipality Certificate"
        );
        
        require_once __DIR__ . '/../admin/!model/vehicleman.php';
        $vhlManModelObj = new vehicleman();
        $manList = $vhlManModelObj->getVManPair();
        
        require_once __DIR__ . '/../admin/!model/empcontract.php';
        $vehicleContract = new empcontract();
        $contractList = $vehicleContract->getVehicleContractReport(array(
            'vhl_id' => $decVhlId
        ));
        require_once __DIR__ . '/../admin/!model/service.php';
        require_once __DIR__ . '/../admin/!model/servicedet.php';
        $serviceObj = new service();
        $serviceDetObj = new servicedet();
        $vhlService = $serviceObj->getDetByVehicleId(array(
            'srv_vhl_id' => $vehicleDetail['vhl_id']
        ));
        
        //print_r($vhlService);
        //die();
        
        
        return array(
            $this->view->vehicleDetail = $vehicleDetail,
            $this->view->vhlDocs = $vhlDocs,
            $this->view->doc_class = $doc_class,
            $this->view->doc_active = $doc_active,
            $this->view->hom_class = $hom_class,
            $this->view->hom_active = $hom_active,
            $this->view->man = $manList,
            $this->view->contractList = $contractList,
            $this->view->serviceDetObj = $serviceDetObj,
            $this->view->vhlService = $vhlService,
            $this->view->docMst = $docMst,
            $this->view->decVhlId = $decVhlId,
            $this->view->vhlImage = $vhlImage,
        );
    }

    public function vhldocsAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $decVehicleId = $this->view->decode($this->view->param['ref']);
        if (! $decVehicleId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehicle = new vehicle();
        $vehicleDetail = $vehicle->getVehicleById($decVehicleId);
        $docMst = array(
            '301' => "Mulkia",
            '302' => "PDO",
            '303' => "Fitness",
            '304' => "IVMS",
            '305' => "Insurance",
            '306' => "Municipality Certificate"
        );
        $form = new form();
        $docDisplay = 'display: none;';
        $imgDisplay = 'display: none;';
        $vhlId = $this->view->decode($this->view->param['ref']);
        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $docMst
        ));
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('auth', 'Issue Authority ', 'text', 'required');
        $form->addElement('doa', 'Date of apply ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doi', 'Date of issue ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 4194304
        ));
        $form->addElement('alert', 'Alert Days', 'text', 'required|numeric');
        $imgform = new form();
        $imgform->addFile('photo', 'Photo', array(
            'required' => true,
            'exten' => 'png;jpg',
            'size' => 1000000
        ));
        $vhlImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_VHL,
            'doc_ref_id' => $decVehicleId
        ));
        $vhlImage = $vhlImage['0'];
        if (! $vhlId)
            die('tampered');
        if ($_POST) {
            if (! empty($_POST['image'])) {
                $imValid = $imgform->vaidate($_POST, $_FILES);
                $imValid = $imValid[0];
                if ($imValid == true) {
                    $data = array(
                        'doc_type' => 5, // @todo image document type create constants
                        'doc_ref_type' => DOC_IMG_VHL,
                        'doc_ref_id' => $vhlId
                    );
                    $insert = $docs->add($data);
                    if ($insert) {
                        if (! empty($vhlImage['doc_id'])) {
                            $delete = $docs->deleteDocument($vhlImage['doc_id']);
                            if (! empty($vhlImage['file_id'])) {
                                $file = new files();
                                deleteFile($vhlImage['file_id']);
                                $file->deleteFile($vhlImage['file_id']);
                            }
                        }
                        $upload = uploadFiles(DOC_IMG_VHL, $insert, $imValid['photo']);
                        $imgform->reset();
                        $this->view->url = APPURL . "erp_vehicle/master/vhldocs/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";
                        if ($upload) {
                            $this->view->feedback = 'Vehicle images added successfully';
                        } else {
                            $this->view->feedback = 'Unable to upload file';
                        }
                    }
                } else {
                    $imgDisplay = '';
                }
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if (! empty($valid['doa'])) {
                        $doa = DateTime::createFromFormat(DF_DD, $valid['doa']);
                        $doa = date_format($doa, DFS_DB);
                    }
                    if (! empty($valid['doi'])) {
                        $doi = DateTime::createFromFormat(DF_DD, $valid['doi']);
                        $doi = date_format($doi, DFS_DB);
                    }
                    if (! empty($valid['doe'])) {
                        $doe = DateTime::createFromFormat(DF_DD, $valid['doe']);
                        $doe = date_format($doe, DFS_DB);
                    }
                    $data = array(
                        'doc_type' => $valid['doctype'],
                        'doc_ref_type' => DOC_TYPE_VHCL,
                        'doc_ref_id' => $vhlId,
                        'doc_no' => $valid['docno'],
                        'doc_desc' => $valid['docdesc'],
                        'doc_remarks' => $valid['docremark'],
                        'doc_issue_auth' => $valid['auth'],
                        'doc_issue_date' => $doi,
                        'doc_expiry_date' => $doe,
                        'doc_alert_days' => $valid['alert']
                    );
                    if ($doa)
                        $data['doc_apply_date'] = $doa;
                    $insert = $docs->add($data);
                    if ($insert) {

                        $upload = uploadFiles(DOC_TYPE_VHCL, $insert, $valid['my_files']);
                        $form->reset();
                        $this->view->url = APPURL . "erp_vehicle/master/vhldocs/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";
                        if ($upload) {
                            $this->view->feedback = 'Vehicle documents added successfully';
                        } else {
                            $this->view->feedback = 'Unable to upload file';
                        }
                    }
                } else {
                    $docDisplay = '';
                }
            }
        }
        $mulkiyaDocs = $docs->getDocuments(array(
            'doc_type' => 301,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $pdoDocs = $docs->getDocuments(array(
            'doc_type' => 302,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $fitnessDocs = $docs->getDocuments(array(
            'doc_type' => 303,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $IVMSDocs = $docs->getDocuments(array(
            'doc_type' => 304,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $InsuranceDocs = $docs->getDocuments(array(
            'doc_type' => 305,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $mncDocs = $docs->getDocuments(array(
            'doc_type' => 306,
            'doc_ref_type' => DOC_TYPE_VHCL,
            'doc_ref_id' => $vhlId
        ));
        $this->view->form = $form;
        $this->view->vehicleDetail = $vehicleDetail;
        $this->view->vhlImage = $vhlImage;
        $this->view->docDisplay = $docDisplay;
        $this->view->imgDisplay = $imgDisplay;
        $this->view->decVehicleId = $decVehicleId;
        $this->view->mulkiyaDocs = $mulkiyaDocs;
        $this->view->pdoDocs = $pdoDocs;
        $this->view->fitnessDocs = $fitnessDocs;
        $this->view->IVMSDocs = $IVMSDocs;
        $this->view->InsuranceDocs = $InsuranceDocs;
        $this->view->mncDocs = $mncDocs;
        $this->view->imgform = $imgform;
        $this->view->reload = $reload;
    }

    public function vhldocseditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $form = new form();
        $vhlDocId = $this->view->decode($this->view->param['ref']);
        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $vhlDocId,
            'doc_ref_type' => DOC_TYPE_VHCL
        ));
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('auth', 'Issue Authority ', 'text', '');
        $form->addElement('doa', 'Date of apply ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doi', 'Date of issue ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('docUpdate', '', 'checkbox', '', array(
            'options' => array(
                1 => ""
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 4194304
        ));
        $form->addElement('alert', 'Alert Days', 'text', 'required|numeric');
        if (isset($_POST) && count($_POST) > 0) {
            if ($_POST['docUpdate'] == '1')
                $form->addFile('my_files', 'Document', array(
                    'required' => true,
                    'exten' => 'pdf',
                    'size' => 5375000
                ));
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                if (! empty($valid['doa'])) {
                    $doa = DateTime::createFromFormat(DF_DD, $valid['doa']);
                    $doa = date_format($doa, DFS_DB);
                }
                if (! empty($valid['doi'])) {
                    $doi = DateTime::createFromFormat(DF_DD, $valid['doi']);
                    $doi = date_format($doi, DFS_DB);
                }
                if (! empty($valid['doe'])) {
                    $doe = DateTime::createFromFormat(DF_DD, $valid['doe']);
                    $doe = date_format($doe, DFS_DB);
                }
                $data = array(
                    'doc_no' => $valid['docno'],
                    'doc_expiry_date' => $doe
                );
                if ($valid['docdesc'])
                    $data['doc_desc'] = $valid['docdesc'];
                if ($valid['docremark'])
                    $data['doc_remarks'] = $valid['docremark'];
                if ($valid['auth'])
                    $data['doc_issue_auth'] = $valid['auth'];
                if ($doa)
                    $data['doc_apply_date'] = $doa;
                if ($doi)
                    $data['doc_issue_date'] = $doi;
                if ($valid['alert'])
                    $data['doc_alert_days'] = $valid['alert'];
                $update = $docs->modify($data, $vhlDocId);
                if ($update) {
                    $this->view->feedback = 'Document details updated successfully ';
                    if ($valid['docUpdate'] == 1) {
                        if (! empty($docDetails['file_id'])) {
                            $file = new files();
                            deleteFile($docDetails['file_id']);
                            $file->deleteFile($docDetails['file_id']);
                        }
                        $upload = uploadFiles(DOC_TYPE_VHCL, $vhlDocId, $valid['my_files']);
                        if ($upload) {
                            if ($upload) {
                                $form->reset();
                                $this->view->feedback = 'Document details modified successfully --';
                            } else {
                                $this->view->feedback .= 'Unable to upload file';
                            }
                        }
                    }
                    $form->reset();
                    $this->view->url = APPURL . "erp_vehicle/master/vhldocsedit/ref/" . $this->view->encode($docDetails['doc_ref_id']);
                    $this->view->status = 11;
                }
            } else {
                // $docDisplay = '';
            }
        } else {
            $form->docno->setValue($docDetails['doc_no']);
            $doi = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
            $doi = $doi->format(DF_DD);
            $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
            $doe = $doe->format(DF_DD);
            $form->doi->setValue($doi);
            $form->doe->setValue($doe);
            $form->docremark->setValue($docDetails['doc_no']);
            $form->alert->setValue($docDetails['doc_alert_days']);
        }
        $this->view->form = $form;
    }

    public function vhldocsdeleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $formRender = true;
        $document = new documets();
        $decDocumentId = $this->view->decode($this->view->param['ref']);
        if (! $decDocumentId)
            die('tampered');
        $documentDetail = $document->getDocumentDetails(array(
            'doc_id' => $decDocumentId,
            'doc_ref_type' => DOC_TYPE_VHCL
        ));
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $document->deleteDocument($decDocumentId);
                if ($delete) {
                    $file = new files();
                    deleteFile($documentDetail['file_id']);
                    $file->deleteFile($documentDetail['file_id']);
                     $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => 'The document has been deleted successfully from the system  .'
                    );
                    $_SESSION['feedback'] = 'The document has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
        $this->view->documentDetail = $documentDetail;
    }

    public function vhldocsviewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documates = new documets();
        $decDocId = $this->view->decode($this->view->param['ref']);
        if (! $decDocId)
            die('tampered');
        $documatesDetail = $documates->getDocumentDetails(array(
            'doc_id' => $decDocId,
            'doc_ref_type' => DOC_TYPE_VHCL
        ));
        $docMst = array(
            '301' => "Mulkia",
            '302' => "PDO",
            '303' => "Fitness",
            '304' => "IVMS",
            '305' => "Insurance",
            '306' => "Municipality Certificate"
        );
        if ($documatesDetail['doc_apply_date'] != '') {
            $doa = DateTime::createFromFormat(DFS_DB, $documatesDetail['doc_apply_date']);
            $doa = $doa->format(DF_DD);
        }
        if ($documatesDetail['doc_issue_date'] != '') {
            $doi = DateTime::createFromFormat(DFS_DB, $documatesDetail['doc_issue_date']);
            $doi = $doi->format(DF_DD);
        }
        $doe = DateTime::createFromFormat(DFS_DB, $documatesDetail['doc_expiry_date']);
        $doe = $doe->format(DF_DD);
        $encFileId = $this->view->encode($documatesDetail['file_id']);
        $this->view->docMst = $docMst;
        $this->view->documatesDetail = $documatesDetail;
        $this->view->doa = $doa;
        $this->view->doi = $doi;
        $this->view->doe = $doe;
        $this->view->encFileId = $encFileId;
    }
}