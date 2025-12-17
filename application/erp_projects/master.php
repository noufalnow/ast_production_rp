<?php

class masterController extends mvc
{
    /* =========================
     * ADD PROJECT
     * ========================= */
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
                
                //a($_POST);
                
                if (!empty($exists['project_id'])) {
                    $form->project_fileno->setError('File No already exists');
                } else {
                    $data = [
                        'project_code'            => $valid['project_code'],
                        'project_fileno'          => $valid['project_fileno'],
                        'project_name'            => $valid['project_name'],
                        'project_client_id'       => $valid['project_client_id'],
                        'project_category'        => $valid['project_category'],
                        'project_type'            => $valid['project_type'] ?: null,
                        'project_sector'          => $valid['project_sector'],
                        'project_contract_mode'   => $valid['project_contract_mode'],
                        'project_budget'          => $valid['project_budget'] ?: null,
                        'project_duration_months' => $valid['project_duration_months'] ?: null,
                        'project_remarks'         => $valid['project_remarks']
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
    
    /* =========================
     * EDIT PROJECT
     * ========================= */
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
        if (!$projectId) die('tampered');
        
        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $data = [
                    'project_code'            => $valid['project_code'],
                    'project_fileno'          => $valid['project_fileno'],
                    'project_name'            => $valid['project_name'],
                    'project_client_id'       => $valid['project_client_id'],
                    'project_category'        => $valid['project_category'],
                    'project_type'            => $valid['project_type'] ?: null,
                    'project_sector'          => $valid['project_sector'],
                    'project_contract_mode'   => $valid['project_contract_mode'],
                    'project_budget'          => $valid['project_budget'] ?: null,
                    'project_duration_months' => $valid['project_duration_months'] ?: null,
                    'project_remarks'         => $valid['project_remarks']
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
    
    /* =========================
     * DELETE PROJECT
     * ========================= */
    public function deleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        
        $project = new property();
        $projectId = $this->view->decode($this->view->param['ref']);
        if (!$projectId) die('tampered');
        
        if ($_POST) {
            $project->deleteProperty($projectId);
            $this->view->NoViewRender = true;
            echo json_encode(['feedback' => 'Project deleted successfully']);
            exit;
        }
        
        $this->view->projectDetail = $project->getPropertyById($projectId);
    }
    
    /* =========================
     * LIST PROJECTS
     * ========================= */
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
                    'f_fileno'      => @$valid['f_fileno'],
                    'f_propno'      => @$valid['f_propno'],
                    'f_customer'    => @$valid['f_customer'],
                    'f_prop_cat'    => @$valid['f_prop_cat'],
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
}
