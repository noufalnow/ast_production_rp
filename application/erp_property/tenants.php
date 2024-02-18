<?php

class tenantsController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/tenants.php';
        $form = new form();
        $tenants = new tenants();

        $form->addElement('tnt_full_name', 'Tenants Name', 'text', 'required|alpha_space');
        $form->addElement('tnt_phone', 'No. ', 'text', 'required|alpha_space');
        $form->addElement('tnt_comp_name', 'Company Name', 'text', 'required|alpha_space');
        $form->addElement('tnt_tele', 'Mobile', 'text', 'required|alpha_space');
        $form->addElement('tnt_id_no', 'Id. No.', 'text', 'required|alpha_space');
        $form->addElement('tnt_crno', 'CR. No.', 'text', 'required|alpha_space');
        $form->addElement('tnt_agr_type', 'Company/ Individual', 'radio', 'required', array(
            'options' => array(
                1 => "Company",
                2 => "Individual"
            )
        ));
        $form->addElement('tnt_expat', 'National/Expart', 'radio', 'required', array(
            'options' => array(
                1 => "National",
                2 => "Expart"
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $form->addFile('my_files2', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if (isset($_POST) && count($_POST) > 0) {

                if ($_POST['tnt_agr_type'] == '2') {

                    $form->addElement('tnt_comp_name', 'Company Name', 'text', 'alpha_space');
                    $form->addElement('tnt_phone', 'No. ', 'text', 'alpha_space');
                    $form->addElement('tnt_crno', 'CR. No.', 'text', 'alpha_space');
                    $form->addFile('my_files2', 'Document', array(
                        'required' => false,
                        'exten' => 'pdf',
                        'size' => 5375000
                    ));
                }

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'tnt_full_name' => $valid['tnt_full_name'],
                        'tnt_phone' => $valid['tnt_phone'],
                        'tnt_comp_name' => $valid['tnt_comp_name'],
                        'tnt_tele' => $valid['tnt_tele'],
                        'tnt_id_no' => $valid['tnt_id_no'],
                        'tnt_crno' => $valid['tnt_crno'],
                        'tnt_expat' => $valid['tnt_expat'],
                        'tnt_agr_type' => $valid['tnt_agr_type']
                    );
                    $tenantsId = $tenants->add($data);

                    if ($tenantsId) {
                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();

                        $data = array(
                            'doc_type' => DOC_TYPE_TNT_ID,
                            'doc_ref_type' => DOC_TYPE_TNT,
                            'doc_ref_id' => $tenantsId
                        );
                        $idCopy = $docs->add($data);
                        if ($idCopy) {
                            $upload = uploadFiles(DOC_TYPE_TNT, $idCopy, $valid['my_files']);
                        }

                        $data = array(
                            'doc_type' => DOC_TYPE_TNT_CR,
                            'doc_ref_type' => DOC_TYPE_TNT,
                            'doc_ref_id' => $tenantsId
                        );
                        $crCopy = $docs->add($data);
                        if ($crCopy) {
                            $upload = uploadFiles(DOC_TYPE_TNT, $crCopy, $valid['my_files2']);
                        }

                        $feedback = 'Tenants details added successfully';
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

        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/tenants.php';
        $tenants = new tenants();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);

        $tenantsId = $this->view->decode($this->view->param['ref']);

        if (! $tenantsId)
            die('tampered');

        $form->addElement('tnt_full_name', 'Tenants Name', 'text', 'required|alpha_space');
        $form->addElement('tnt_phone', 'No. ', 'text', 'required|alpha_space');
        $form->addElement('tnt_comp_name', 'Company Name', 'text', 'required|alpha_space');
        $form->addElement('tnt_tele', 'Mobile', 'text', 'required|alpha_space');
        $form->addElement('tnt_id_no', 'Id. No.', 'text', 'required|alpha_space');
        $form->addElement('tnt_crno', 'CR. No.', 'text', 'required|alpha_space');
        $form->addElement('tnt_agr_type', 'Company/ Individual', 'radio', 'required', array(
            'options' => array(
                1 => "Company",
                2 => "Individual"
            )
        ));
        $form->addElement('tnt_expat', 'National/Expart', 'radio', 'required', array(
            'options' => array(
                1 => "National",
                2 => "Expart"
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $form->addFile('my_files2', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        $tenantsDetails = $tenants->getTenantsDet(['tnt_id'=>$tenantsId]);

        if (isset($_POST) && count($_POST) > 0) {
            //print_r($form);

            if ($_POST['tnt_agr_type'] == '2') {

                $form->addElement('tnt_comp_name', 'Company Name', 'text', 'alpha_space');
                $form->addElement('tnt_phone', 'No. ', 'text', 'alpha_space');
                $form->addElement('tnt_crno', 'CR. No.', 'text', 'alpha_space');
            }

            $form->addFile('my_files', 'Document', array(
                'required' => false,
                'exten' => 'pdf',
                'size' => 5375000
            ));
            $form->addFile('my_files2', 'Document', array(
                'required' => false,
                'exten' => 'pdf',
                'size' => 5375000
            ));

            $valid = $form->vaidate($_POST, $_FILES);
            
            $valid = $valid[0];
            if ($valid == true) {

                $data = array(
                    'tnt_full_name' => $valid['tnt_full_name'],
                    'tnt_phone' => $valid['tnt_phone'],
                    'tnt_comp_name' => $valid['tnt_comp_name'],
                    'tnt_tele' => $valid['tnt_tele'],
                    'tnt_id_no' => $valid['tnt_id_no'],
                    'tnt_crno' => $valid['tnt_crno'],
                    'tnt_expat' => $valid['tnt_expat'],
                    'tnt_agr_type' => $valid['tnt_agr_type']
                );

                $modifyTenants = $tenants->modify($data, $tenantsDetails['tnt_id']);

                // re upload files
                if ($modifyTenants) {
                    require_once __DIR__ . '/../admin/!model/documents.php';
                    $docs = new documets();
                    $file = new files();
                    
                    
                    if ($valid['my_files']) {
                        
                        if($tenantsDetails['docsid']){
                            $docs->deleteDocument($tenantsDetails['docsid']);
                            deleteFile($tenantsDetails['idfile']);
                            $file->deleteFile($tenantsDetails['docsid']);
                        }
                        
                        $data = array(
                            'doc_type' => DOC_TYPE_TNT_ID,
                            'doc_ref_type' => DOC_TYPE_TNT,
                            'doc_ref_id' => $tenantsId
                        );
                        $idCopy = $docs->add($data);
                        if ($idCopy) {
                            $upload = uploadFiles(DOC_TYPE_TNT, $idCopy, $valid['my_files']);
                        }
                    }

                    if ($valid['my_files2']) {
                        if($tenantsDetails['docscr']){
                            $docs->deleteDocument($tenantsDetails['docscr']);
                            deleteFile($tenantsDetails['crfile']);
                            $file->deleteFile($tenantsDetails['crfile']);
                        }   
                        $data = array(
                            'doc_type' => DOC_TYPE_TNT_CR,
                            'doc_ref_type' => DOC_TYPE_TNT,
                            'doc_ref_id' => $tenantsId
                        );
                        $crCopy = $docs->add($data);
                        if ($crCopy) {
                            $upload = uploadFiles(DOC_TYPE_TNT, $crCopy, $valid['my_files2']);
                        }
                    }

                }

                $feedback = 'Tenants details Updated successfully';
                $this->view->NoViewRender = true;
                $success = array(
                    'feedback' => $feedback
                );
                $_SESSION['feedback'] = $feedback;
                $success = json_encode($success);
                die($success);
            } else {
                //echo "invalid"; print_r($form);
            }
        } else {
            
            //echo "not post ";
            $form->tnt_full_name->setValue($tenantsDetails['tnt_full_name']);
            $form->tnt_phone->setValue($tenantsDetails['tnt_phone']);
            $form->tnt_comp_name->setValue($tenantsDetails['tnt_comp_name']);
            $form->tnt_tele->setValue($tenantsDetails['tnt_tele']);
            $form->tnt_id_no->setValue($tenantsDetails['tnt_id_no']);
            $form->tnt_crno->setValue($tenantsDetails['tnt_crno']);
            $form->tnt_expat->setValue($tenantsDetails['tnt_expat_id']);
            $form->tnt_agr_type->setValue($tenantsDetails['tnt_agr_type_id']);
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
                $delete = $customer->deleteTenants($decCustId);
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
        include __DIR__ . '/../admin/!model/tenants.php';

        $form = new form();

        $form->addElement('tnt_full_name', 'Tenants Name', 'text', '');
        $form->addElement('tnt_phone', 'No. ', 'text', '');
        $form->addElement('tnt_comp_name', 'Company Name', 'text', '');
        $form->addElement('tnt_tele', 'Mobile', 'text', '');
        $form->addElement('tnt_id_no', 'Id. No.', 'text', '');
        $form->addElement('tnt_crno', 'CR. No.', 'text', '');

        $form->addElement('tnt_agr_type', 'Agreement By', 'select', '', array(
            'options' => array(
                1 => "Company",
                2 => "Individual"
            )
        ));
        $form->addElement('tnt_expat', 'Type', 'select', '', array(
            'options' => array(
                1 => "National",
                2 => "Expart"
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
                    'tnt_full_name' => @$valid['tnt_full_name'],
                    'tnt_comp_name' => @$valid['tnt_comp_name'],
                    'tnt_phone' => @$valid['tnt_phone'],
                    'tnt_tele' => @$valid['tnt_tele'],
                    'tnt_id_no' => @$valid['tnt_id_no'],
                    'tnt_crno' => @$valid['tnt_crno'],
                    'tnt_expat' => @$valid['tnt_expat'],
                    'tnt_agr_type' => @$valid['tnt_agr_type']
                );
            }
            $filter_class = 'btn-info';
        }

        $tenantsObj = new tenants();

        $this->view->TenantsList = $tenantsObj->getTenantsPaginate(@$where);
        $this->view->filter_class = $filter_class;
        $this->view->form = $form;
        $this->view->tenantsObj = $tenantsObj;
    }

    public function viewAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/tenants.php';

        $tenantsObj = new tenants();

        $decTenantsId = $this->view->decode($this->view->param['ref']);

        if (! $decTenantsId)
            die('tampered');

        $tenantDetail = $tenantsObj->getTenantsDet(['tnt_id'=>$decTenantsId]);

        $this->view->tenantDetail = $tenantDetail;
    }
    
    public function tenant_agrAction(){
        $this->view->response('ajax');
        
        $decTenantsId = $this->view->decode($this->view->param['ref']);
        
        if (! $decTenantsId)
            die('tampered');
        
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $leaseDocs = $docs->getDocuments(array(
            'doc_type' => 201,
            'doc_ref_type' => DOC_TYPE_PROP,
            'agr_tnt_id' => $decTenantsId
        ));
        
        $this->view->leaseDocs = $leaseDocs;
        
    }
    
    public function tenant_paymentsAction(){
        $this->view->response('ajax');
        
        $decDocId = $this->view->decode($this->view->param['ref']);
        
        if (! $decDocId)
            die('tampered');
            
            require_once __DIR__ . '/../admin/!model/proppayoption.php';
            $payopObj = new proppayoption();
            $tenPayment = $payopObj->getTenantPayments(array(
                'popt_doc_id' => $decDocId
            ));
            
            //print_r($tenPayment);
            
            $this->view->tenPayment = $tenPayment;
            
    }
}
