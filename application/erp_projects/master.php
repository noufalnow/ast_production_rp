<?php

class masterController extends mvc
{

    /*
     * =========================
     * ADD PROJECT
     * =========================
     */
    public function addAction()
    {
        $this->view->response('ajax');
        $form = new form();

        $form->addElement('project_code', 'Project Code', 'text', 'required');
        $form->addElement('project_fileno', 'File No', 'text', '');
        $form->addElement('project_name', 'Project Name', 'text', 'required');
        $form->addElement('project_remarks', 'Remarks', 'textarea', '');

        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $form->addElement('project_client_id', 'Customer', 'select', 'required', [
            'options' => $customerList
        ]);

        $form->addElement('project_category', 'Project Category', 'select', 'required', [
            'options' => [
                1 => 'Road',
                2 => 'Bridge',
                3 => 'Drainage',
                4 => 'Utilities',
                5 => 'Other'
            ]
        ]);

        $form->addElement('project_type', 'Project Type', 'select', '', [
            'options' => [
                1 => 'New Construction',
                2 => 'Rehabilitation',
                3 => 'Expansion'
            ]
        ]);

        $form->addElement('project_sector', 'Sector', 'radio', 'required', [
            'options' => [
                1 => 'Government',
                2 => 'Non-Government'
            ]
        ]);

        $form->addElement('project_contract_mode', 'Contract Mode', 'radio', 'required', [
            'options' => [
                1 => 'Main Contract',
                2 => 'Sub-Contract'
            ]
        ]);

        $form->addElement('project_budget', 'Project Budget', 'float', 'numeric');
        $form->addElement('project_duration_months', 'Duration (Months)', 'number', 'numeric');

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                require_once __DIR__ . '/../admin/!model/property.php';
                $project = new property();

                $exists = $project->getPropertyDet([
                    'prop_fileno' => $valid['project_fileno']
                ]);

                // a($_POST);

                if (! empty($exists['project_id'])) {
                    $form->project_fileno->setError('File No already exists');
                } else {
                    $data = [
                        'project_code' => $valid['project_code'],
                        'project_fileno' => $valid['project_fileno'],
                        'project_name' => $valid['project_name'],
                        'project_client_id' => $valid['project_client_id'],
                        'project_category' => $valid['project_category'],
                        'project_type' => $valid['project_type'] ?: null,
                        'project_sector' => $valid['project_sector'],
                        'project_contract_mode' => $valid['project_contract_mode'],
                        'project_budget' => $valid['project_budget'] ?: null,
                        'project_duration_months' => $valid['project_duration_months'] ?: null,
                        'project_remarks' => $valid['project_remarks']
                    ];

                    if ($project->add($data)) {
                        $this->view->feedback = 'Project added successfully';
                        $this->view->NoViewRender = true;
                        $success = array(
                            'feedback' => $this->view->feedback
                        );
                        $_SESSION['feedback'] = $this->view->feedback;
                        $success = json_encode($success);
                        die($success);
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /*
     * =========================
     * EDIT PROJECT
     * =========================
     */
    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/customer.php';

        $project = new property();
        $form = new form();

        $form->addElement('project_code', 'Project Code', 'text', 'required');
        $form->addElement('project_fileno', 'File No', 'text', '');
        $form->addElement('project_name', 'Project Name', 'text', 'required');
        $form->addElement('project_remarks', 'Remarks', 'textarea', '');

        $customerObj = new customer();
        $form->addElement('project_client_id', 'Customer', 'select', 'required', [
            'options' => $customerObj->getCustomerPair()
        ]);

        $form->addElement('project_category', 'Project Category', 'select', 'required', [
            'options' => [
                1 => 'Road',
                2 => 'Bridge',
                3 => 'Drainage',
                4 => 'Utilities',
                5 => 'Other'
            ]
        ]);

        $form->addElement('project_type', 'Project Type', 'select', '', [
            'options' => [
                1 => 'New Construction',
                2 => 'Rehabilitation',
                3 => 'Expansion'
            ]
        ]);

        $form->addElement('project_sector', 'Sector', 'radio', 'required', [
            'options' => [
                1 => 'Government',
                2 => 'Non-Government'
            ]
        ]);

        $form->addElement('project_contract_mode', 'Contract Mode', 'radio', 'required', [
            'options' => [
                1 => 'Main Contract',
                2 => 'Sub-Contract'
            ]
        ]);

        $form->addElement('project_budget', 'Project Budget', 'float', 'numeric');
        $form->addElement('project_duration_months', 'Duration (Months)', 'number', 'numeric');

        $projectId = $this->view->decode($this->view->param['ref']);
        if (! $projectId)
            die('tampered');

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $data = [
                    'project_code' => $valid['project_code'],
                    'project_fileno' => $valid['project_fileno'],
                    'project_name' => $valid['project_name'],
                    'project_client_id' => $valid['project_client_id'],
                    'project_category' => $valid['project_category'],
                    'project_type' => $valid['project_type'] ?: null,
                    'project_sector' => $valid['project_sector'],
                    'project_contract_mode' => $valid['project_contract_mode'],
                    'project_budget' => $valid['project_budget'] ?: null,
                    'project_duration_months' => $valid['project_duration_months'] ?: null,
                    'project_remarks' => $valid['project_remarks']
                ];

                if ($project->modify($data, $projectId)) {

                    $feedback = 'Project details updated successfully';

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
            $det = $project->getPropertyDetById($projectId);

            foreach ($det as $k => $v) {
                if (isset($form->$k))
                    $form->$k->setValue($v);
            }
        }

        $this->view->form = $form;
    }

    /*
     * =========================
     * DELETE PROJECT
     * =========================
     */
    public function deleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';

        $project = new property();
        $projectId = $this->view->decode($this->view->param['ref']);
        if (! $projectId)
            die('tampered');

        if ($_POST) {
            $project->deleteProperty($projectId);
            $this->view->NoViewRender = true;
            echo json_encode([
                'feedback' => 'Project deleted successfully'
            ]);
            exit();
        }

        $this->view->projectDetail = $project->getPropertyById($projectId);
    }

    /*
     * =========================
     * LIST PROJECTS
     * =========================
     */
    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/customer.php';

        $form = new form();

        $customerObj = new customer();

        $form->addElement('f_fileno', 'File No', 'text', '');
        $form->addElement('f_propno', 'Project Code', 'text', '');

        $form->addElement('f_customer', 'Customer', 'select', '', [
            'options' => $customerObj->getCustomerPair()
        ]);

        $form->addElement('f_prop_cat', 'Category', 'select', '', [
            'options' => [
                1 => 'Road',
                2 => 'Bridge',
                3 => 'Drainage',
                4 => 'Utilities',
                5 => 'Other'
            ]
        ]);

        $form->addElement('f_prop_status', 'Status', 'select', '', [
            'options' => [
                1 => 'Planning',
                2 => 'Active',
                3 => 'On Hold',
                4 => 'Completed',
                5 => 'Cancelled'
            ]
        ]);

        $projectObj = new property();
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
                    'f_fileno' => @$valid['f_fileno'],
                    'f_propno' => @$valid['f_propno'],
                    'f_customer' => @$valid['f_customer'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_status' => @$valid['f_prop_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $this->view->projectList = $projectObj->getPropertyPaginate($where);
        $this->view->form = $form;
        $this->view->projectObj = $projectObj;
        $this->view->offset = $projectObj->_voffset;
        $this->view->filter_class = $filter_class;
    }

    public function viewAction()
    {
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        
        $property = new property();
        $docs     = new documets();
        
        $decPropId = $this->view->decode($this->view->param['ref']);
        if (!$decPropId) die('tampered');
        
        $propertyDetail = $property->getPropertyDet([
            'project_id' => $decPropId
        ]);
        
        $propDocs = $docs->getTopDocumentsByRef([
            'doc_ref_type' => DOC_TYPE_PROJECT,
            'doc_ref_id'   => $decPropId
        ]);
        
        $propImage = $docs->getTopDocumentsByRef([
            'doc_ref_type' => DOC_IMG_PROJECT,
            'doc_ref_id'   => $decPropId
        ]);
        $propImage = $propImage[0] ?? null;
        
        
        if ($_POST) {
            $tab = $_POST['tab'];
            switch ($tab) {
                case 'my_files':
                    $doc_class = 'active';
                    $doc_active = 'active in';
                    break;
                case 'profile':
                    $profile_class = 'active';
                    $profile_active = 'active in';
                    break;
            }
        } else {
            $hom_class = 'active';
            $hom_active = 'active in';
        }
        $docMst = array(
            '201' => "Contract Agreement",
        );
        
        /* ENUMS */
        $this->view->projectCategory = [
            1=>'Road',2=>'Bridge',3=>'Drainage',4=>'Utilities',5=>'Other'
        ];
        $this->view->projectType = [
            1=>'New Construction',2=>'Rehabilitation',3=>'Expansion'
        ];
        $this->view->projectSector = [
            1=>'Government',2=>'Non-Government'
        ];
        $this->view->projectContractMode = [
            1=>'Main Contract',2=>'Sub Contract'
        ];
        
        /* TAB STATE */
        $this->view->hom_class = 'active';
        $this->view->hom_active = 'active in';
        
        $this->view->hom_class = $hom_class;
        $this->view->doc_class = $doc_class;
        $this->view->profile_class = $profile_class;
        $this->view->hom_active = $hom_active;
        $this->view->docMst = $docMst;
        $this->view->propertyDetail = $propertyDetail;
        $this->view->propDocs = $propDocs;
        $this->view->propImage = $propImage;
        $this->view->decPropId = $decPropId;
        $this->view->doc_active = $doc_active;
    }
    
    
    public function propdocsAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        require_once __DIR__ . '/../admin/!model/property.php';
        $property = new property();
        $form = new form();
        $docDisplay = 'display: none;';
        $imgDisplay = 'display: none;';
        $docMst = array(
            '201' => "Project Contract"
        );
        $propId = $this->view->decode($this->view->param['ref']);

        $form->addElement('doctype','Document Type','select','required',['options'=>$docMst]);
        $form->addElement('docno','Agreement No','text','required');
        $form->addElement('doi','Contract Start','text','required','',['class'=>'date_picker',''=>'readonly']);
        $form->addElement('doe','Contract End','text','required','',['class'=>'date_picker',''=>'readonly']);
        $form->addElement('agr_amount','Contract Amount','float','required|numeric',['class'=>'fig']);
        $form->addElement('agr_rent','Advance / Stage Amount','float','required|numeric',['class'=>'fig']);
        $form->addElement('agr_paydet','Payment / Milestone Details','textarea','required');
        $form->addElement('docremark','Remarks','text','alpha_space');
        $form->addElement('alert','Alert Days','number','numeric');
        
        
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $imgform = new form();
        $imgform->addFile('photo', 'Photo', array(
            'required' => true,
            'exten' => 'png;jpg',
            'size' => 4480000
        ));
        $propImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_PROJECT,
            'doc_ref_id' => $propId
        ));
        $propImage = $propImage['0'];
        $propertyDetail = $property->getPropertyById($propId);
        if (! $propId)
            die('tampered');
            if ($_POST) {
                if (! empty($_POST['image'])) {
                    $imValid = $imgform->vaidate($_POST, $_FILES);
                    $imValid = $imValid[0];
                    if ($imValid == true) {
                        $data = array(
                            'doc_type' => 5, // @todo image document type create constants
                            'doc_ref_type' => DOC_IMG_PROJECT,
                            'doc_ref_id' => $propId
                        );
                        $insert = $docs->add($data);
                        if ($insert) {
                            if (! empty($propImage['doc_id'])) {
                                $docs->deleteDocument($propImage['doc_id']);
                                if (! empty($propImage['file_id'])) {
                                    $file = new files();
                                    deleteFile($propImage['file_id']);
                                    $file->deleteFile($propImage['file_id']);
                                }
                            }
                            $upload = uploadFiles(DOC_IMG_PROJECT, $insert, $imValid['photo']);
                            
                            
                            $form->reset();
                            $this->view->url = APPURL . "erp_projects/master/propdocs/ref/" . $this->view->param['ref'];
                            $this->view->status = 11;
                            $this->view->target = "menu2";
                            
                            if ($upload) {
                                $this->view->feedback = 'Property images added successfully';
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
                            'doc_ref_type' => DOC_TYPE_PROJECT,
                            'doc_ref_id' => $propId,
                            'doc_no' => $valid['docno'],
                            'agr_tnt_id' => $valid['agr_tenant'],
                            //'agr_mobile' => $valid['agr_mobile'],
                            //'agr_tele' => $valid['agr_tele'],
                            //'agr_idno' => $valid['agr_idno'],
                            //'agr_comp' => $valid['agr_comp'],
                            //'agr_expat' => $valid['agr_expat'],
                            'agr_amount' => $valid['agr_amount'],
                            'agr_rent' => $valid['agr_rent'],
                            //'agr_crno' => $valid['agr_crno'],
                            'agr_paydet' => $valid['agr_paydet'],
                            'doc_issue_date' => $doi,
                            'doc_expiry_date' => $doe,
                            'doc_remarks' => $valid['docremark']
                        );
                        if ($valid['alert'])
                            $data['doc_alert_days'] = $valid['alert'];
                            $insert = $docs->add($data);
                            if ($insert) {
                                if ($propertyDetail['prop_building_type'] == '')
                                    $update = $property->modify(array(
                                        "project_status" => 2
                                    ), $propId);
                                    $upload = uploadFiles(DOC_TYPE_PROJECT, $insert, $valid['my_files']);
                                    $form->reset();
                                    $this->view->url = APPURL . "erp_projects/master/propdocs/ref/" . $this->view->param['ref'];
                                    $this->view->status = 11;
                                    $this->view->target = "menu2";
                                    if ($upload) {
                                        $this->view->feedback = 'Project images added successfully';
                                    } else {
                                        $this->view->feedback = 'Unable to upload file';
                                    }
                            }
                    } else {
                        $docDisplay = '';
                    }
                }
            }
            $leaseDocs = $docs->getDocuments(array(
                'doc_type' => 201,
                'doc_ref_type' => DOC_TYPE_PROJECT,
                'doc_ref_id' => $propId
            ));
            $this->view->form = $form;
            $this->view->imgDisplay = $imgDisplay;
            $this->view->imgform = $imgform;
            $this->view->docDisplay = $docDisplay;
            $this->view->propImage = $propImage;
            $this->view->leaseDocs = $leaseDocs;
            $this->view->target = "menu2";
    }
    
    
    public function propdocseditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $property = new property();
        $propDocId = $this->view->decode($this->view->param['ref']);
        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $propDocId,
            'doc_ref_type' => DOC_TYPE_PROJECT
        ));
        // s($docDetails);
        if (! $propDocId)
            die('tampered');
            $form = new form();
            
            $form->addElement('docno','Agreement No','text','required');
            $form->addElement('doi','Contract Start','text','required','',['class'=>'date_picker',''=>'readonly']);
            $form->addElement('doe','Contract End','text','required','',['class'=>'date_picker',''=>'readonly']);
            $form->addElement('agr_amount','Contract Amount','float','required|numeric',['class'=>'fig']);
            $form->addElement('agr_rent','Advance / Stage Amount','float','required|numeric',['class'=>'fig']);
            $form->addElement('agr_paydet','Payment / Milestone Details','textarea','required');
            $form->addElement('docremark','Remarks','text','alpha_space');
            $form->addElement('alert','Alert Days','number','numeric');
            
            $form->addElement('docUpdate', ' ', 'checkbox', '', array(
                'options' => array(
                    1 => " "
                )
            ));
            $form->addFile('my_files', 'Document', array(
                'required' => false,
                'exten' => 'pdf',
                'size' => 5375000
            ));
            $propertyDetail = $property->getPropertyById($docDetails['doc_ref_id']);
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
                            'agr_tnt_id' => $valid['agr_tenant'],
                            //'agr_mobile' => $valid['agr_mobile'],
                            //'agr_tele' => $valid['agr_tele'],
                            //'agr_idno' => $valid['agr_idno'],
                            //'agr_comp' => $valid['agr_comp'],
                            //'agr_expat' => $valid['agr_expat'],
                            'agr_rent' => $valid['agr_rent'],
                            'agr_amount' => $valid['agr_amount'],
                            //'agr_crno' => $valid['agr_crno'],
                            'agr_paydet' => $valid['agr_paydet'],
                            'doc_issue_date' => $doi,
                            'doc_expiry_date' => $doe,
                            'doc_remarks' => $valid['docremark']
                        );
                        if ($valid['alert'])
                            $data['doc_alert_days'] = $valid['alert'];
                            $update = $docs->modify($data, $propDocId);
                            if ($update) {
                                $this->view->feedback = 'Document details updated successfully ';
                                
                                if ($valid['docUpdate'] == 1) {
                                    if (! empty($docDetails['file_id'])) {
                                        $file = new files();
                                        deleteFile($docDetails['file_id']);
                                        $file->deleteFile($docDetails['file_id']);
                                    }
                                    if ($propertyDetail['prop_building_type'] == '')
                                        $update = $property->modify(array(
                                            "project_status" => 2
                                        ), $docDetails['doc_ref_id']);
                                        deleteFile($docDetails['file_id']);
                                        $file->deleteFile($docDetails['file_id']);
                                        $upload = uploadFiles(DOC_TYPE_PROJECT, $propDocId, $valid['my_files']);
                                        if ($upload) {
                                            $form->reset();
                                            $this->view->feedback = 'Document details modified successfully';
                                        } else {
                                            $this->view->feedback .= 'Unable to upload file';
                                        }
                                }
                                $form->reset();
                                $this->view->url = APPURL . "erp_employee/master/propdocadd/ref/" . $this->view->encode($docDetails['doc_ref_id']);
                                $this->view->status = 11;
                            }
                    }
            } else {
                $form->docno->setValue($docDetails['doc_no']);
                //$form->agr_tenant->setValue($docDetails['agr_tnt_id']);
                //$form->agr_mobile->setValue($docDetails['agr_mobile']);
                //$form->agr_tele->setValue($docDetails['agr_tele']);
                //$form->agr_idno->setValue($docDetails['agr_idno']);
                //$form->agr_comp->setValue($docDetails['agr_comp']);
                //$form->agr_expat->setValue($docDetails['agr_expat']);
                //$form->agr_crno->setValue($docDetails['agr_crno']);
                $form->agr_rent->setValue($docDetails['agr_rent']);
                $form->agr_amount->setValue($docDetails['agr_amount']);
                
                $form->agr_paydet->setValue($docDetails['agr_paydet']);
                $doi = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
                $doi = $doi->format(DF_DD);
                $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
                $doe = $doe->format(DF_DD);
                $form->doi->setValue($doi);
                $form->doe->setValue($doe);
                $form->docremark->setValue($docDetails['doc_remarks']);
                $form->alert->setValue($docDetails['doc_alert_days']);
            }
            $this->view->form = $form;
    }
    
    public function propdocsviewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documates = new documets();
        $decDocId = $this->view->decode($this->view->param['ref']);
        if (! $decDocId)
            die('tampered');
            $documatesDetail = $documates->getDocumentDetails(array(
                'doc_id' => $decDocId,
                'doc_ref_type' => DOC_TYPE_PROJECT
            ));
            $docMst = array(
                '201' => "Lease Contract"
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
