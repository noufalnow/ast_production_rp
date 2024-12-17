<?php

class servicesController extends mvc
{
    public function vhlserviceAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/service.php';
        require_once __DIR__ . '/../admin/!model/servicedet.php';
        $serviceObj = new service();
        $serviceDetObj = new servicedet();
        // $formRender = true;
        $form = new form();
        $decRefId = $this->view->decode($this->view->param['ref']);
        if (! $decRefId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        require_once __DIR__ . '/../admin/!model/item.php';
        $itemObj = new item();
        $itemList = $itemObj->getSrvItemPair(array(
            'item_type' => 2
        ));
        $form->addElement('servicedt', 'Service Date', 'text', 'date|required', '', array(
            'class' => 'date_picker',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('employee', 'Technician', 'select', 'required', array(
            'options' => $empList
        ));
        $form->addElement('status', 'Status', 'select', 'required', array(
            'options' => array(
                1 => "Major Service",
                2 => "Minor Service"
            )
        ));
        $form->addElement('nxtstatus', 'Service', 'select', 'required', array(
            'options' => array(
                1 => "Major Service",
                2 => "Minor Service"
            )
        ));
        $form->addElement('location', 'Location', 'text', 'required');
        $form->addElement('reading', 'Reading', 'number', 'required');
        $form->addElement('readingnxt', 'Reading', 'number', 'required');
        $form->addElement('wash', 'Wash', 'radio', 'required', array(
            'options' => array(
                1 => "No",
                2 => "Yes"
            )
        ));
        $form->addElement('greese', 'Greese', 'radio', 'required', array(
            'options' => array(
                1 => "No",
                2 => "Yes"
            )
        ));
        $form->addElement('nextDt', 'Next Date', 'text', 'date|required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        
        $form->addElement('labour', 'Labour', 'text', 'required', 'numeric', array(
            'class' => 'floatonly',
            
        ));
        
        
        $form->addElement('note', 'Note', 'textarea', '', '');
        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['item']);
        }
        $form->addMultiElement('item', 'Item', 'select', '', array(
            'options' => $itemList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('quantity', 'Quantity', 'float', 'numeric', '', array(
            'class' => ''
        ), $count);
        
        
        $form->addMultiElement('doneby', 'Done by', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select'
        ), $count);
        
        $form->addMultiElement('mnote', 'Note', 'text', '', '', array(
            'class' => ''
        ), $count);
        
        
        $form->addMultiElement('mprice', 'Price', 'text', 'required', 'numeric', array(
            'class' => ''
        ), $count);
        
        $form->addMultiElement('munit', 'Unit', 'text', 'required', '', array(
            'class' => ''
        ), $count);
        
        
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expModelObj = new expense();
        $expList = $expModelObj->getExpenseBillPair(['exp_mainh'=>3]);
        
        $form->addMultiElement('mbillid', 'Expense Entry', 'select', 'required', array(
            'options' => $expList
        ), array(
            'class' => 'select'
        ), $count);
        
        $form->addErrorMsg('mprice', 'required', 'Price Required');
        $form->addErrorMsg('munit', 'required', 'Unit Required');
        $form->addErrorMsg('mbillid', 'required', 'Expense Entry Required');
        
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        
        
        $mfields = array_keys($form->_elements['item']);
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $form->addErrorMsg('item', 'required', ' ');
                $form->addErrorMsg('quantity', 'required', ' ');
                $form->addErrorMsg('doneby', 'required', ' ');
                $form->addErrorMsg('mnote', 'required', ' ');
                foreach ($mfields as $i) {
                    if ($_POST['item'][$i] != '' && $_POST['quantity'][$i] == '')
                        $form->addmRules('quantity', $i, 'required');
                    if ($_POST['item'][$i] != '' && $_POST['doneby'][$i] == '')
                        $form->addmRules('doneby', $i, 'required');
                    // if ($_POST ['item'] [$i] != '' && $_POST ['mnote'] [$i] == '')
                    // $form->addmRules ( 'mnote', $i, 'required' );
                }
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if (! empty($valid['servicedt'])) {
                        $dtWef = DateTime::createFromFormat(DF_DD, $valid['servicedt']);
                        $dtWef = $dtWef->format(DFS_DB);
                    }
                    if (! empty($valid['nextDt'])) {
                        $dtnext = DateTime::createFromFormat(DF_DD, $valid['nextDt']);
                        $dtnext = $dtnext->format(DFS_DB);
                    }
                    $data = array(
                        'srv_type' => $valid['status'],
                        'srv_vhl_id' => $decRefId,
                        'srv_date_start' => $dtWef,
                        'srv_reading' => $valid['reading'],
                        'srv_done_by' => $valid['employee'],
                        'srv_wash' => $valid['wash'],
                        'srv_greese' => $valid['greese'],
                        'srv_note' => $valid['note'],
                        'srv_date_next' => $dtnext,
                        'srv_nxt_type' => $valid['nxtstatus'],
                        'srv_reading_next' => $valid['readingnxt'],
                        'srv_location' => $valid['location'],
                        'srv_labour' => $valid['labour'],
                        
                    );
                    $insert = $serviceObj->add($data);
                    if ($insert) {
                        foreach ($mfields as $i) {
                            $mdata = array();
                            if ($valid['item'][$i] != '') {
                                $mdata = array(
                                    'sdt_srv_id' => $insert,
                                    'sdt_item' => $valid['item'][$i],
                                    'sdt_qty' => $valid['quantity'][$i],
                                    'sdt_done_by' => $valid['doneby'][$i],
                                    'sdt_note' => $valid['mnote'][$i],
                                    'sdt_unit' => $valid['munit'][$i],
                                    'sdt_price' => $valid['mprice'][$i],
                                    'sdt_billid' => $valid['mbillid'][$i]
                                );
                                $serviceDetObj->add($mdata);
                            }
                        }
                        
                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();
                        
                        $fdata = array(
                            'doc_type' => DOC_TYPE_VHL_SRV,
                            'doc_ref_type' => DOC_TYPE_VHL_SRV,
                            'doc_ref_id' => $insert
                        );
                        $srvRpt = $docs->add($fdata);
                        if ($srvRpt) {
                            $upload = uploadFiles(DOC_TYPE_VHL_SRV, $srvRpt, $valid['my_files']);
                        }
                        
                        
                        
                        $feedback = ' Service Updated successfully';
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
        $this->view->formRender = $formRender;
        $this->view->mfields = $mfields;
    }

    public function vhlserviceeditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/service.php';
        require_once __DIR__ . '/../admin/!model/servicedet.php';
        $serviceObj = new service();
        $serviceDetObj = new servicedet();
        $formRender = true;
        $form = new form();
        $decRefId = $this->view->decode($this->view->param['ref']);
        if (! $decRefId)
            die('tampered');
        
        $serviceDet = $serviceObj->getDetByVehicleId(array(
            'srv_id' => $decRefId
        ));
        
        $serviceDet = $serviceDet['0'];
        
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        require_once __DIR__ . '/../admin/!model/item.php';
        $itemObj = new item();
        $itemList = $itemObj->getSrvItemPair(array(
            'item_type' => 2
        ));
        $form->addElement('servicedt', 'Service Date', 'text', 'date|required', '', array(
            'class' => 'date_picker',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('employee', 'Technician', 'select', 'required', array(
            'options' => $empList
        ));
        $form->addElement('status', 'Status', 'select', 'required', array(
            'options' => array(
                1 => "Major Service",
                2 => "Minor Service"
            )
        ));
        
        $form->addElement('labour', 'Labour', 'text', 'required', 'numeric', array(
            'class' => 'floatonly',
            
        ));
        $form->addElement('nxtstatus', 'Service', 'select', 'required', array(
            'options' => array(
                1 => "Major Service",
                2 => "Minor Service"
            )
        ));
        $form->addElement('location', 'Location', 'text', 'required');
        $form->addElement('reading', 'Reading', 'number', 'required');
        $form->addElement('readingnxt', 'Reading', 'number', 'required');
        $form->addElement('wash', 'Wash', 'radio', 'required', array(
            'options' => array(
                1 => "No",
                2 => "Yes"
            )
        ));
        $form->addElement('greese', 'Greese', 'radio', 'required', array(
            'options' => array(
                1 => "No",
                2 => "Yes"
            )
        ));
        $form->addElement('nextDt', 'Next Date', 'text', 'date|required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('note', 'Note', 'textarea', '', '');
        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['item']);
        } else {
            $serviceItemDet = $serviceDetObj->getDetByServiceId(array(
                'sdt_srv_id' => $decRefId
            ));
            $count = count($serviceItemDet) == 0 ? 1 : count($serviceItemDet);
        }
        /*
         * $serviceIds = $serviceDetObj->getDetByServiceIdPairs(array(
         * 'sdt_srv_id' => $decRefId
         * ));
         */
        $form->addMultiElement('item', 'Item', 'select', '', array(
            'options' => $itemList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('quantity', 'Quantity', 'float', 'numeric', '', array(
            'class' => 'floatonly',
        ), $count);
        $form->addMultiElement('doneby', 'Done by', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('mnote', 'Note', 'text', '', '', array(
            'class' => ''
        ), $count);
        
        
        $form->addMultiElement('mprice', 'Price', 'text', 'required|numeric', 'numeric', array(
            'class' => 'floatonly',
        ), $count);
        
        $form->addMultiElement('munit', 'Unit', 'text', 'required', '', array(
            'class' => ''
        ), $count);
        
        
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expModelObj = new expense();
        $expList = $expModelObj->getExpenseBillPair(['exp_mainh'=>3]);
        
        $form->addMultiElement('mbillid', 'Expense Entry', 'select', 'required', array(
            'options' => $expList
        ), array(
            'class' => 'select'
        ), $count);
        
        $form->addErrorMsg('mprice', 'required', 'Price Required');
        $form->addErrorMsg('munit', 'required', 'Unit Required');
        $form->addErrorMsg('mbillid', 'required', 'Expense Entry Required');
        
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        
        $mfields = array_keys($form->_elements['item']);
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $form->addErrorMsg('item', 'required', ' ');
                $form->addErrorMsg('quantity', 'required', ' ');
                $form->addErrorMsg('doneby', 'required', ' ');
                $form->addErrorMsg('mnote', 'required', ' ');
                foreach ($mfields as $i) {
                    if ($_POST['item'][$i] != '' && $_POST['quantity'][$i] == '')
                        $form->addmRules('quantity', $i, 'required');
                    if ($_POST['item'][$i] != '' && $_POST['doneby'][$i] == '')
                        $form->addmRules('doneby', $i, 'required');
                    // if ($_POST ['item'] [$i] != '' && $_POST ['mnote'] [$i] == '')
                    // $form->addmRules ( 'mnote', $i, 'required' );
                }
                
                
                $form->addFile('my_files', 'Document', array(
                    'required' => false,
                    'exten' => 'pdf',
                    'size' => 5375000
                ));
                
                
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $dtWef = DateTime::createFromFormat(DF_DD, $valid['servicedt']);
                    $dtWef = $dtWef->format(DFS_DB);
                    $dtnext = DateTime::createFromFormat(DF_DD, $valid['nextDt']);
                    $dtnext = $dtnext->format(DFS_DB);
                    $data = array(
                        'srv_type' => $valid['status'],
                        'srv_date_start' => $dtWef,
                        'srv_reading' => $valid['reading'],
                        'srv_done_by' => $valid['employee'],
                        'srv_wash' => $valid['wash'],
                        'srv_greese' => $valid['greese'],
                        'srv_note' => $valid['note'],
                        'srv_date_next' => $dtnext,
                        'srv_nxt_type' => $valid['nxtstatus'],
                        'srv_reading_next' => $valid['readingnxt'],
                        'srv_location' => $valid['location'],
                        'srv_labour' => $valid['labour'],
                    );
                    $update = $serviceObj->modify($data, $decRefId);
                    if ($update) {
                        $serviceDetObj->deleteServiceItemByserviceId(array(
                            'sdt_srv_id' => $decRefId
                        ));
                        foreach ($mfields as $i) {
                            $mdata = array();
                            if ($valid['item'][$i] != '') {
                                $mdata = array(
                                    'sdt_srv_id' => $decRefId,
                                    'sdt_item' => $valid['item'][$i],
                                    'sdt_qty' => $valid['quantity'][$i],
                                    'sdt_done_by' => $valid['doneby'][$i],
                                    'sdt_note' => $valid['mnote'][$i],
                                    'sdt_unit' => $valid['munit'][$i],
                                    'sdt_price' => $valid['mprice'][$i],
                                    'sdt_billid' => $valid['mbillid'][$i]
                                );
                                $serviceDetObj->add($mdata);
                            }
                        }
                        
                        
                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();
                        $file = new files();
                        
                        
                        if ($valid['my_files']) {
                            if ($serviceDet['docsid']) {
                                $docs->deleteDocument($serviceDet['docsid']);
                                deleteFile($serviceDet['fileid']);
                                $file->deleteFile($serviceDet['docsid']);
                            }
                            
                            $srvdata = array(
                                'doc_type' => DOC_TYPE_VHL_SRV,
                                'doc_ref_type' => DOC_TYPE_VHL_SRV,
                                'doc_ref_id' => $decRefId
                            );
                            $srvRpt = $docs->add($srvdata);
                            if ($srvRpt) {
                                $upload = uploadFiles(DOC_TYPE_VHL_SRV, $srvRpt, $valid['my_files']);
                            }
                        }
                        
                        
                        
                        $feedback = ' Service Updated successfully';
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
            
            
            $startDt = DateTime::createFromFormat(DFS_DB, $serviceDet['srv_date_start']);
            $startDt = $startDt->format(DF_DD);
            $form->servicedt->setValue($startDt);
            $nextDt = DateTime::createFromFormat(DFS_DB, $serviceDet['srv_date_next']);
            $nextDt = $nextDt->format(DF_DD);
            $form->nextDt->setValue($nextDt);
            $form->status->setValue($serviceDet['srv_type']);
            $form->reading->setValue($serviceDet['srv_reading']);
            $form->employee->setValue($serviceDet['srv_done_by']);
            $form->wash->setValue($serviceDet['srv_wash']);
            $form->greese->setValue($serviceDet['srv_greese']);
            $form->note->setValue($serviceDet['srv_note']);
            $form->nxtstatus->setValue($serviceDet['srv_nxt_type']);
            $form->readingnxt->setValue($serviceDet['srv_reading_next']);
            $form->location->setValue($serviceDet['srv_location']);
            $form->labour->setValue($serviceDet['srv_labour']);
            $i = 0;
            if (count($serviceItemDet) > 0)
                foreach ($serviceItemDet as $fields) {
                    $form->item[$i]->setValue($fields['sdt_item']);
                    $form->quantity[$i]->setValue($fields['sdt_qty']);
                    $form->doneby[$i]->setValue($fields['sdt_done_by']);
                    $form->mnote[$i]->setValue($fields['sdt_note']);
                                      
                    $form->munit[$i]->setValue($fields['sdt_unit']);
                    $form->mprice[$i]->setValue($fields['sdt_price']);
                    $form->mbillid[$i]->setValue($fields['sdt_billid']);
                    
                    $i ++;
                }
        }
        $this->view->form = $form;
        $this->view->formRender = $formRender;
        $this->view->mfields = $mfields;
        
        $this->view->encSrvFileId = $this->view->encode($serviceDet['fileid']);
        
    }
}