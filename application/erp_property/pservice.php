<?php
class pserviceController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/pservice_m.php'; // Rename model accordingly
        $form = new form();
        
        // Adding fields to form
        
        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();
        
        $form->addElement('psvs_prop', 'Property', 'select', 'required', array(
            'options' => $propList
        ));
        
        
        $form->addElement('psvs_type', 'Service Type', 'select', 'required', array(
            'options' => array(
                1 => "Electrical",
                2 => "Plumbing",
                3 => "Painting",
                4 => "Other"
            )
        ));
        $form->addElement('psvs_complaint_no', 'Complaint No', 'text', 'required', '', array(
            'class' => 'full-select',
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_date', 'Request Date', 'text', 'required|date', '', array(
            'readonly' => true,
            'class' => 'date_picker'
        ));
        $form->addElement('psvs_srv_date', 'Service Done Date', 'text', 'required|date', '', array(
            'readonly' => true,
            'class' => 'date_picker'
        ));
               
        $form->addElement('psvs_in', 'Time In [HH:mm]', 'text', 'required', 'numeric', array('class' => 'time'));
        $form->addElement('psvs_out', 'Time Out [HH:mm]', 'text', 'required', 'numeric', array('class' => 'time'));
        

        $form->addElement('psvs_amt_mat', 'RO.', 'float', 'required', '', array());
        $form->addElement('psvs_amt_lab', 'RO.', 'float', 'required', '', array());
        $form->addElement('psvs_signed', 'Name', 'text', 'required', '', array(
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_signed_phone', 'Phone', 'text', '', '', array(
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_feedback', 'Feedback', 'select', 'required', array(
            'options' => array(
                1 => "BAD",
                2 => "AVERAGE",
                3 => "GOOD",
                4 => "VERY GOOD",
                5 => "EXCELLENT"
            )
        ));
        
        
        
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();

        $form->addElement('psvs_emp', 'Assigned Employee', 'select', '', array(
            'options' => $empList
        ));
        $form->addElement('psvs_remarks', 'Complaint', 'textarea', '', '', array(
            'autocomplete' => 'off'
        ));
        
        
        $sCount = $iCount = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $sCount = array_keys($_POST['srv_desc']);
            $iCount = array_keys($_POST['itm_desc']);
        }
        
        $form->addMultiElement('srv_desc', 'Service Description', 'textarea', 'required', '', array(
            'class' => 'full-text'
        ), $sCount); // Multi-field JSON representation
        
        $form->addMultiElement('srv_remarks', 'Service Remarks', 'textarea', '', '', array(
            'class' => 'full-text'
        ), $sCount);
        
        $form->addMultiElement('itm_desc', 'Item Description', 'textarea', '', '', array(
            'class' => 'full-text'
        ), $iCount);
        $form->addMultiElement('itm_qty', 'Quantity', 'number', 'required|numeric', '', array(
            'class' => ''
        ), $iCount);
        $form->addMultiElement('itm_amount', 'Amount', 'float', 'required|numeric', '', array(
            'class' => ''
        ), $iCount);
        
        $form->addErrorMsg('itm_qty', 'required', 'Quantity Required');
        $form->addErrorMsg('itm_amount', 'required', 'Amount Required');
        
        $mfields = array_keys($form->_elements['srv_desc']);
        $mIfields = array_keys($form->_elements['itm_desc']);
        
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        
        
        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                                
                if(!empty($valid['psvs_date'])){
                    $psvs_date_object = DateTime::createFromFormat(DF_DD, $valid['psvs_date']);
                    $psvs_date = date_format($psvs_date_object, DFS_DB);
                }
                if(!empty($valid['psvs_date'])){
                    $psvs_srv_date_object = DateTime::createFromFormat(DF_DD, $valid['psvs_srv_date']);
                    $psvs_srv_date = date_format($psvs_srv_date_object, DFS_DB);
                }

                $data = array(
                    'psvs_prop_id' => $valid['psvs_prop'], // Map property ID
                    'psvs_type' => $valid['psvs_type'], // Map service type
                    'psvs_complaint_no' => $valid['psvs_complaint_no'], // Map complaint number
                    'psvs_date' => $psvs_date, // Map formatted request date
                    'psvs_srv_date' => $psvs_srv_date, // Map formatted service done date
                    'psvs_emp' => $valid['psvs_emp'], // Map assigned employee
                    'psvs_time_in' => $valid['psvs_in'], // Map time in
                    'psvs_time_out' => $valid['psvs_out'], // Map time out
                    'psvs_service_json' => json_encode(array( // Map service description and remarks
                        'srv_desc' => $valid['srv_desc'],
                        'srv_remarks' => $valid['srv_remarks']
                    )),
                    'psvs_parts_json' => json_encode(array( // Map item description, quantity, and amount
                        'itm_desc' => $valid['itm_desc'],
                        'itm_qty' => $valid['itm_qty'],
                        'itm_amount' => $valid['itm_amount']
                    )),
                    'psvs_amt_mat' => $valid['psvs_amt_mat'], // Map total material amount
                    'psvs_amt_lab' => $valid['psvs_amt_lab'], // Map total labor amount
                    'psvs_amt_tot' => (float)$valid['psvs_amt_mat'] + (float)$valid['psvs_amt_lab'], // Calculate total amount
                    'psvs_signed' => $valid['psvs_signed'], // Map name of the person who signed
                    'psvs_signed_phone' => $valid['psvs_signed_phone'], // Map phone number
                    'psvs_feedback' => $valid['psvs_feedback'], // Map feedback
                    'psvs_remarks' => $valid['psvs_remarks'], // Map remarks
                );
                
                
                
                $pserviceObj = new pservice_m(); 
                $result = $pserviceObj->add($data);
                if ($result) {
                    
                    require_once __DIR__ . '/../admin/!model/documents.php';
                    $docs = new documets();
                    
                    $data = array(
                        'doc_type' => DOC_TYPE_PROP_SRV,
                        'doc_ref_type' => DOC_TYPE_PROP_SRV,
                        'doc_ref_id' => $result
                    );
                    $srvRpt = $docs->add($data);
                    if ($srvRpt) {
                        $upload = uploadFiles(DOC_TYPE_PROP_SRV, $srvRpt, $valid['my_files']);
                    }
                    
                    
                    $_SESSION['feedback'] = 'Property Service added successfully';
                    die(json_encode(array('feedback' => $_SESSION['feedback'])));
                }
            }
        }
        $this->view->form = $form;
        $this->view->mfields = $mfields;
        $this->view->mIfields = $mIfields;
        
    }
    

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/pservice_m.php';
        
        $form = new form();
        $decPsvsId = $this->view->decode($this->view->param['ref']);
        if (!$decPsvsId) die('tampered');
        
        $pserviceObj = new pservice_m();
        $serviceData = $pserviceObj->getDetById(['psvs_id'=>$decPsvsId]);
        
        if ($serviceData) {
            $srvJson = json_decode($serviceData['psvs_service_json'], true);
            $partsJson = json_decode($serviceData['psvs_parts_json'], true);
        }
        
        // Adding fields to form
        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();
        
        $form->addElement('psvs_prop', 'Property', 'select', 'required', array(
            'options' => $propList
        ));
        
        
        $form->addElement('psvs_type', 'Service Type', 'select', 'required', array(
            'options' => array(
                1 => "Electrical",
                2 => "Plumbing",
                3 => "Painting",
                4 => "Other"
            )
        ));
        $form->addElement('psvs_complaint_no', 'Complaint No', 'text', 'required', '', array(
            'class' => 'full-select',
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_date', 'Request Date', 'text', 'required|date', '', array(
            'readonly' => true,
            'class' => 'date_picker'
        ));
        $form->addElement('psvs_srv_date', 'Service Done Date', 'text', 'required|date', '', array(
            'readonly' => true,
            'class' => 'date_picker'
        ));
        
        $form->addElement('psvs_in', 'Time In [HH:mm]', 'text', 'required', 'numeric', array('class' => 'time'));
        $form->addElement('psvs_out', 'Time Out [HH:mm]', 'text', 'required', 'numeric', array('class' => 'time'));
        
        
        $form->addElement('psvs_amt_mat', 'RO.', 'float', 'required', '', array());
        $form->addElement('psvs_amt_lab', 'RO.', 'float', 'required', '', array());
        $form->addElement('psvs_signed', 'Name', 'text', 'required', '', array(
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_signed_phone', 'Phone', 'text', '', '', array(
            'autocomplete' => 'off'
        ));
        $form->addElement('psvs_feedback', 'Feedback', 'select', 'required', array(
            'options' => array(
                1 => "BAD",
                2 => "AVERAGE",
                3 => "GOOD",
                4 => "VERY GOOD",
                5 => "EXCELLENT"
            )
        ));
        
        
        
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        
        $form->addElement('psvs_emp', 'Assigned Employee', 'select', '', array(
            'options' => $empList
        ));
        $form->addElement('psvs_remarks', 'Complaint', 'textarea', '', '', array(
            'autocomplete' => 'off'
        ));
        
        
        if (isset($_POST) && count($_POST) > 0) {
            $sCount = array_keys($_POST['srv_desc']);
            $iCount = array_keys($_POST['itm_desc']);
        } else {
            if ($serviceData) {
                $sCount = count($srvJson['srv_desc']);
                $iCount = count($partsJson['itm_desc']);
            }
        }
        
        $form->addMultiElement('srv_desc', 'Service Description', 'textarea', 'required', '', array(
            'class' => 'full-text'
        ), $sCount); // Multi-field JSON representation
        
        $form->addMultiElement('srv_remarks', 'Service Remarks', 'textarea', '', '', array(
            'class' => 'full-text'
        ), $sCount);
        
        $form->addMultiElement('itm_desc', 'Item Description', 'textarea', '', '', array(
            'class' => 'full-text'
        ), $iCount);
        $form->addMultiElement('itm_qty', 'Quantity', 'number', 'required|numeric', '', array(
            'class' => ''
        ), $iCount);
        $form->addMultiElement('itm_amount', 'Amount', 'float', 'required|numeric', '', array(
            'class' => ''
        ), $iCount);
        
        $form->addErrorMsg('itm_qty', 'required', 'Quantity Required');
        $form->addErrorMsg('itm_amount', 'required', 'Amount Required');
        
        $mfields = array_keys($form->_elements['srv_desc']);
        $mIfields = array_keys($form->_elements['itm_desc']);
        
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        
        // Handle submission
        if (isset($_POST) && count($_POST) > 0) {
            
            $form->addFile('my_files', 'Document', array(
                'required' => false,
                'exten' => 'pdf',
                'size' => 5375000
            ));
            
            
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                
                
                if(!empty($valid['psvs_date'])){
                    $psvs_date_object = DateTime::createFromFormat(DF_DD, $valid['psvs_date']);
                    $psvs_date = date_format($psvs_date_object, DFS_DB);
                }
                if(!empty($valid['psvs_date'])){
                    $psvs_srv_date_object = DateTime::createFromFormat(DF_DD, $valid['psvs_srv_date']);
                    $psvs_srv_date = date_format($psvs_srv_date_object, DFS_DB);
                }
                
                $data = array(
                    'psvs_prop_id' => $valid['psvs_prop'], // Map property ID
                    'psvs_type' => $valid['psvs_type'], // Map service type
                    'psvs_complaint_no' => $valid['psvs_complaint_no'], // Map complaint number
                    'psvs_date' => $psvs_date, // Map formatted request date
                    'psvs_srv_date' => $psvs_srv_date, // Map formatted service done date
                    'psvs_emp' => $valid['psvs_emp'], // Map assigned employee
                    'psvs_time_in' => $valid['psvs_in'], // Map time in
                    'psvs_time_out' => $valid['psvs_out'], // Map time out
                    
                    'psvs_service_json' => json_encode(array(
                        'srv_desc' => array_values($valid['srv_desc'] ?? []), // Ensure it's an indexed array
                        'srv_remarks' => array_values($valid['srv_remarks'] ?? [])
                    )),
                    'psvs_parts_json' => json_encode(array(
                        'itm_desc' => array_values($valid['itm_desc'] ?? []),
                        'itm_qty' => array_map('floatval', array_values($valid['itm_qty'] ?? [])), // Convert to numbers
                        'itm_amount' => array_map('floatval', array_values($valid['itm_amount'] ?? []))
                    )),
                    
                    
                    'psvs_amt_mat' => $valid['psvs_amt_mat'], // Map total material amount
                    'psvs_amt_lab' => $valid['psvs_amt_lab'], // Map total labor amount
                    'psvs_amt_tot' => (float)$valid['psvs_amt_mat'] + (float)$valid['psvs_amt_lab'], // Calculate total amount
                    'psvs_signed' => $valid['psvs_signed'], // Map name of the person who signed
                    'psvs_signed_phone' => $valid['psvs_signed_phone'], // Map phone number
                    'psvs_feedback' => $valid['psvs_feedback'], // Map feedback
                    'psvs_remarks' => $valid['psvs_remarks'], // Map remarks
                );
                
                $result = $pserviceObj->modify($data, $decPsvsId);
                if ($result) {
                    
                    
                    require_once __DIR__ . '/../admin/!model/documents.php';
                    $docs = new documets();
                    $file = new files();
                    
                    
                    if ($valid['my_files']) {
                        if ($serviceData['docsid']) {
                            $docs->deleteDocument($serviceData['docsid']);
                            deleteFile($serviceData['idfile']);
                            $file->deleteFile($serviceData['docsid']);
                        }

                        $data = array(
                            'doc_type' => DOC_TYPE_PROP_SRV,
                            'doc_ref_type' => DOC_TYPE_PROP_SRV,
                            'doc_ref_id' => $decPsvsId
                        );
                        $srvRpt = $docs->add($data);
                        if ($srvRpt) {
                            $upload = uploadFiles(DOC_TYPE_PROP_SRV, $srvRpt, $valid['my_files']);
                        }
                    }
                    
                    $_SESSION['feedback'] = 'Property Service updated successfully';
                    die(json_encode(array('feedback' => $_SESSION['feedback'])));
                }
            }
        } else {
            // Prefill form values
            if ($serviceData) {
                // Map simple fields directly
                $form->psvs_prop->setValue($serviceData['psvs_prop_id']);
                $form->psvs_type->setValue($serviceData['psvs_type']);
                $form->psvs_complaint_no->setValue($serviceData['psvs_complaint_no']);
                $form->psvs_in->setValue($serviceData['psvs_time_in']);
                $form->psvs_out->setValue($serviceData['psvs_time_out']);
                $form->psvs_amt_mat->setValue($serviceData['psvs_amt_mat']);
                $form->psvs_amt_lab->setValue($serviceData['psvs_amt_lab']);
                $form->psvs_signed->setValue($serviceData['psvs_signed']);
                $form->psvs_signed_phone->setValue($serviceData['psvs_signed_phone']);
                $form->psvs_feedback->setValue($serviceData['psvs_feedback']);
                $form->psvs_remarks->setValue($serviceData['psvs_remarks']);
                $form->psvs_emp->setValue($serviceData['psvs_emp']);
                
                // Convert dates to the desired format and map
                $requestDate = DateTime::createFromFormat(DFS_DB, $serviceData['psvs_date']);
                $form->psvs_date->setValue($requestDate ? $requestDate->format(DF_DD) : '');
                
                $serviceDoneDate = DateTime::createFromFormat(DFS_DB, $serviceData['psvs_srv_date']);
                $form->psvs_srv_date->setValue($serviceDoneDate ? $serviceDoneDate->format(DF_DD) : '');
                
                // Decode and map JSON fields

                if (is_array($srvJson)) {
                    $i = 0;
                    foreach ($srvJson['srv_desc'] as $desc) {
                        $form->srv_desc[$i]->setValue($desc);
                        $form->srv_remarks[$i]->setValue($srvJson['srv_remarks'][$i]);
                        $i++;
                    }
                }
                
                if (is_array($partsJson)) {
                    $i = 0;
                    foreach ($partsJson['itm_desc'] as $desc) {
                        $form->itm_desc[$i]->setValue($desc);
                        $form->itm_qty[$i]->setValue($partsJson['itm_qty'][$i]);
                        $form->itm_amount[$i]->setValue($partsJson['itm_amount'][$i]);
                        $i++;
                    }
                }
            }
            
        }
        
        $this->view->form = $form;
        $this->view->mfields = $mfields;
        $this->view->mIfields = $mIfields;
                
        $this->view->encFileId = $this->view->encode($serviceData['fileid']);
    }
    



    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/pservice_m.php';
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/property.php';
        
        $form = new form();
        
        // Fetch dropdown options
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        $propertyModelObj = new property();
        $propertyList = $propertyModelObj->getPropetyPair();
        
        // Add form elements
        $form->addElement('f_complaint_no', 'Complaint No', 'text', '');
        $form->addElement('f_service_type', 'Service Type', 'select', '', array(
            'options' => array(
                1 => "Electrical",
                2 => "Plumbing",
                3 => "Painting",
                4 => "Other"
            )
        ));
        $form->addElement('f_employee', 'Employee', 'select', '', array(
            'options' => $empList
        ));
        $form->addElement('f_property', 'Property', 'select', '', array(
            'options' => $propertyList
        ) );
        
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        
        // Reset filters if "All" is selected
        if (isset($_GET) && isset($_GET['clear']) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }
        
        $filter_class = 'btn-primary'; // Default button class
        
        // Process filters if any are applied
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'psvs_complaint_no' => @$valid['f_complaint_no'],
                    'psvs_type' => @$valid['f_service_type'],
                    'prop_building' => @$valid['f_building'],
                    'psvs_emp' => @$valid['f_employee'],
                    'psvs_prop_id' => @$valid['f_property'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info'; // Highlight filter button if filters are applied
        }
        
        // Fetch data
        $serviceObj = new pservice_m();
        $serviceList = $serviceObj->getPropertyServicePaginate(@$where);
        $offset = $serviceObj->_voffset;
        
        // Pass data to the view
        $this->view->form = $form;
        $this->view->serviceList = $serviceList;
        $this->view->serviceObj = $serviceObj;
        $this->view->offset = $offset;
        $this->view->filter_class = $filter_class;
    }
    


    public function viewAction()
    {}
}
