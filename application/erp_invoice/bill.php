<?php

class billController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/billdet.php';
        require_once __DIR__ . '/../admin/!model/customer.php';
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $form = new form();
        $itemObj = new item();
        $customerObj = new customer();
        $itemList = $itemObj->getItemPair(array(
            'item_type' => 1
        ));
        $customerList = $customerObj->getCustomerPair();
        $opt = array(
            1 => "Oman",
            2 => "India Kerala",
            3 => "Pakistan",
            4 => "Bangladesh"
        );
        $customer = $form->addElement('customer', 'Customer', 'select', 'required', array(
            'options' => $customerList
        ), array(
            'class' => 'full-select'
        ));
        $form->addElement('mode', 'Payemnt Mode', 'radio', 'required', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit"
            )
        ));
        // $form->addElement ( 'refno', 'Reference No ', 'text','','',array(''=>'autocomplete="off"') );
        $form->addElement('note', 'Note/ Remarks ', 'text', '', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('billdt', 'Bill Date', 'text', 'date|required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('billmonth', 'Bill Month', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('revdt', 'Review Date', 'text', 'date', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('wonote', 'WO Remarks ', 'text', '', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('location', 'Location ', 'text', 'required', '', array(
            '' => 'autocomplete="off"'
        ));
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        $form->company->setValue(1);
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documentsObj = new documets();
        $documentList = $documentsObj->getDocumentsPair(array(
            'doc_type' => DOC_TYPE_COM_AGR,
            'doc_ref_id' => $_POST['refId'],
            'doc_ref_type' => DOC_TYPE_COM
        ));
        $form->addElement('wolpo', 'Work Order/LPO', 'select', 'required', array(
            'options' => $documentList
        ));
        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['particuler']);
        }
        $form->addMultiElement('particuler', 'Particuler', 'select', 'required', array(
            'options' => $itemList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('remarks', 'Remarks', 'text', '', '', array(
            'class' => ''
        ), $count);
        $form->addMultiElement('qty', '', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('amount', '', 'amount', 'required|integer', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('baisa', '', 'baisa', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addErrorMsg('particuler', 'required', ' ');
        $form->addErrorMsg('qty', 'required', ' ');
        $form->addErrorMsg('amount', 'required', ' ');
        $form->addErrorMsg('baisa', 'required', ' ');
        $form->addErrorMsg('qty', 'numeric', ' ');
        $form->addErrorMsg('amount', 'integer', ' ');
        $form->addErrorMsg('baisa', 'integer', ' ');
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 3375000
        ));
        $mfields = array_keys($form->_elements['particuler']);
        if (isset($_POST) && count($_POST) > 0) {
            // a($_POST);
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $billdt = DateTime::createFromFormat(DF_DD, $valid['billdt']);
                $billdt = date_format($billdt, DFS_DB);
                if ($valid['billmonth']) {
                    $monthDt = DateTime::createFromFormat(DF_DD, '01/' . $valid['billmonth']);
                    $monthDt = date_format($monthDt, DFS_DB);
                }
                if ($valid['revdt']) {
                    $revdt = DateTime::createFromFormat(DF_DD, $valid['revdt']);
                    $revdt = date_format($revdt, DFS_DB);
                }
                // update po number
                $poDet = $documentsObj->getDocumentById($valid['wolpo']);
                $bill = new bill();
                $data = array(
                    'bill_company' => $valid['company'],
                    // 'bill_refno'=>$valid['refno'],
                    'bill_refno' => $poDet['doc_no'],
                    'bill_customer_id' => $valid['customer'],
                    'bill_mode' => $valid['mode'],
                    'bill_remarks' => $valid['note'],
                    'bill_total' => 0,
                    'bill_date' => $billdt,
                    'bill_month' => $monthDt != '' ? $monthDt : NULL,
                    'bill_rev_date' => $revdt != '' ? $revdt : NULL,
                    'bill_pstatus' => $valid['mode'],
                    'bill_wo' => $valid['wolpo'],
                    'bill_wo_note' => $valid['wonote'] != '' ? $valid['wonote'] : NULL,
                    'bill_location' => $valid['location']
                );
                $billId = $bill->add($data);
                $total = 0;
                if ($billId) {
                    $billDet = new billdet();
                    foreach ($mfields as $i) {
                        $data = array();
                        $data = array(
                            'bdet_bill_id' => $billId,
                            'bdet_item' => $valid['particuler'][$i],
                            'bdet_qty' => $valid['qty'][$i],
                            'bdet_amt' => $valid['amount'][$i] . '.' . $valid['baisa'][$i],
                            'bdet_remarks' => $valid['remarks'][$i]
                        );
                        $total += $valid['qty'][$i] * (float) ($valid['amount'][$i] . '.' . $valid['baisa'][$i]);
                        $det = $billDet->add($data);
                    }
                    $udata['bill_oribill_amt'] = $total;
                    $udata['bill_total'] = $total;
                    if ($valid['mode'] == 2)
                        $udata['bill_credit_amt'] = $total;
                    else
                        $udata['bill_credit_amt'] = NULL;
                    $bill->modify($udata, $billId);
                    $upload = uploadFiles(DOC_TYPE_BILL, $billId, $valid['my_files']);
                    $form->reset();
                    $feedback=$_SESSION['feedback'] = 'Bill details added successfully';
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => $feedback
                    );
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
        $this->view->form = $form;
        $this->view->mfields = $mfields;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/billdet.php';
        require_once __DIR__ . '/../admin/!model/customer.php';
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $decBillId = $this->view->decode($this->view->param['ref']);
        if (! $decBillId)
            die('tampered');
        $form = new form();
        $itemObj = new item();
        $customerObj = new customer();
        $itemList = $itemObj->getItemPair(array(
            'item_type' => 1
        ));
        $customerList = $customerObj->getCustomerPair();
        // $opt= array(1=>"Oman", 2=>"India Kerala",3=>"Pakistan",4=>"Bangladesh");
        $form->addElement('customer', 'Customer', 'select', 'required', array(
            'options' => $customerList
        ), array(
            'class' => 'full-select'
        ));
        $form->addElement('mode', 'Payemnt Mode', 'radio', 'required', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit"
            )
        ));
        // $form->addElement ( 'refno', 'Reference No ', 'text','','',array(''=>'autocomplete="off"') );
        $form->addElement('note', 'Note/ Remarks ', 'text', '', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('billdt', 'Bill Date', 'text', 'date|required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('billmonth', 'Bill Month', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('revdt', 'Review Date', 'text', 'date', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('wonote', 'WO Remarks ', 'text', '', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('location', 'Location ', 'text', 'required', '', array(
            '' => 'autocomplete="off"'
        ));
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        $form->company->setValue(1);
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documentsObj = new documets();
        $documentList = $documentsObj->getDocumentsPair(array(
            'doc_type' => DOC_TYPE_COM_AGR,
            'doc_ref_id' => $_POST['refId'],
            'doc_ref_type' => DOC_TYPE_COM
        ));
        $form->addElement('wolpo', 'Work Order/LPO', 'select', 'required', array(
            'options' => $documentList
        ));
        $billObj = new bill();
        $billDetObj = new billdet();
        $maxBillDetails = $billDetObj->getMaxBillDet(array(
            'bdet_bill_id' => $decBillId
        ));
        $count = 1;
        $billInfo = $billObj->getBillInfo(array(
            'bill_id' => $decBillId
        ));
        $encFileId = $this->view->encode($billInfo['file_id']);
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['particuler']);
        } else {
            if ($billInfo) {
                $billDetails = $billDetObj->getBillDet(array(
                    'bdet_bill_id' => $decBillId,
                    'bdet_update_sts' => $maxBillDetails['max_update']
                ));
            }
            $count = count($billDetails);
        }
        // a($billInfo);
        $form->addMultiElement('particuler', 'Particuler', 'select', 'required', array(
            'options' => $itemList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('remarks', 'Remarks', 'text', '', '', array(
            'class' => ''
        ), $count);
        $form->addMultiElement('qty', 'Quantity', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('amount', 'Amount', 'amount', 'required|integer', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('baisa', 'Baisa', 'baisa', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addErrorMsg('particuler', 'required', ' ');
        $form->addErrorMsg('qty', 'required', ' ');
        $form->addErrorMsg('amount', 'required', ' ');
        $form->addErrorMsg('baisa', 'required', ' ');
        $form->addErrorMsg('qty', 'numeric', ' ');
        $form->addErrorMsg('amount', 'integer', ' ');
        $form->addErrorMsg('baisa', 'integer', ' ');
        $form->addElement('docUpdate', 'Update Document', 'checkbox', '', array(
            'options' => array(
                1 => "Update Document"
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 3375000
        ));
        $mfields = array_keys($form->_elements['particuler']);
        if (isset($_POST) && count($_POST) > 0) {
            if ($_POST['docUpdate'] == '1')
                $form->addFile('my_files', 'Document', array(
                    'required' => true,
                    'exten' => 'pdf',
                    'size' => 3375000
                ));
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $billdt = DateTime::createFromFormat(DF_DD, $valid['billdt']);
                $billdt = date_format($billdt, DFS_DB);
                if ($valid['billmonth']) {
                    $monthDt = DateTime::createFromFormat(DF_DD, '01/' . $valid['billmonth']);
                    $monthDt = date_format($monthDt, DFS_DB);
                }
                if ($valid['revdt']) {
                    $revdt = DateTime::createFromFormat(DF_DD, $valid['revdt']);
                    $revdt = date_format($revdt, DFS_DB);
                }
                // update po number
                $poDet = $documentsObj->getDocumentById($valid['wolpo']);
                $data = array(
                    'bill_company' => $valid['company'],
                    // 'bill_refno'=>$valid['refno'],
                    'bill_refno' => $poDet['doc_no'],
                    'bill_customer_id' => $valid['customer'],
                    'bill_mode' => $valid['mode'],
                    'bill_remarks' => $valid['note'],
                    'bill_date' => $billdt,
                    'bill_month' => $monthDt != '' ? $monthDt : NULL,
                    'bill_rev_date' => $revdt != '' ? $revdt : NULL,
                    'bill_pstatus' => $valid['mode'],
                    'bill_wo' => $valid['wolpo'],
                    'bill_wo_note' => $valid['wonote'] != '' ? $valid['wonote'] : NULL,
                    'bill_location' => $valid['location']
                );
                $update = $billObj->modify($data, $decBillId);
                $total = 0;
                if ($update) {
                    $feedback = $_SESSION['feedback'] = 'Bill details updated successfully';
                    foreach ($mfields as $i) {
                        $data = array();
                        $data = array(
                            'bdet_bill_id' => $decBillId,
                            'bdet_item' => $valid['particuler'][$i],
                            'bdet_qty' => $valid['qty'][$i],
                            'bdet_amt' => $valid['amount'][$i] . '.' . $valid['baisa'][$i],
                            'bdet_remarks' => $valid['remarks'][$i],
                            'bdet_update_sts' => $maxBillDetails['max_update'] + 1
                        );
                        $total += $valid['qty'][$i] * (float) ($valid['amount'][$i] . '.' . $valid['baisa'][$i]);
                        $billDetObj->add($data);
                    }
                    $udata['bill_oribill_amt'] = $total;
                    $udata['bill_total'] = $total;
                    if ($valid['mode'] == 2)
                        $udata['bill_credit_amt'] = $total;
                    else
                        $udata['bill_credit_amt'] = NULL;
                    $billObj->modify($udata, $decBillId);
                    if ($valid['docUpdate'] == 1) {
                        if (! empty($billInfo['file_id'])) {
                            $file = new files();
                            deleteFile($billInfo['file_id']);
                            $file->deleteFile($billInfo['file_id']);
                        }
                        $upload = uploadFiles(DOC_TYPE_BILL, $decBillId, $valid['my_files']);
                        if ($upload) {
                            $form->reset();
                            $feedback = $_SESSION['feedback']  = 'Bill details added successfully';
   
                        } else {
                            $feedback = $_SESSION['feedback']  = 'Unable to upload file';

                        }
                    }
                    $form->reset();
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => $feedback
                    );
                    $success = json_encode($success);
                    die($success);
                }
            }
        } else {
            $form->company->setValue($billInfo['bill_company']);
            // $form->refno->setValue($billInfo['bill_refno']);
            $form->customer->setValue($billInfo['bill_customer_id']);
            $form->mode->setValue($billInfo['bill_mode']);
            $form->note->setValue($billInfo['bill_remarks']);
            $form->wolpo->setValue($billInfo['bill_wo']);
            $form->wonote->setValue($billInfo['bill_wo_note']);
            $form->location->setValue($billInfo['bill_location']);
            if ($billInfo['bill_date'] != '') {
                $billDt = DateTime::createFromFormat(DFS_DB, $billInfo['bill_date']);
                $billDt = $billDt->format(DF_DD);
                $form->billdt->setValue($billDt);
            }
            if ($billInfo['bill_month'] != '') {
                $billMth = DateTime::createFromFormat(DFS_DB, $billInfo['bill_month']);
                $billMth = $billMth->format(DF_DD);
                $form->billmonth->setValue(substr($billMth, 3, 7));
            }
            if ($billInfo['bill_rev_date'] != '') {
                $revDt = DateTime::createFromFormat(DFS_DB, $billInfo['bill_rev_date']);
                $revDt = $revDt->format(DF_DD);
                $form->revdt->setValue($revDt);
            }
            $i = 0;
            foreach ($billDetails as $fields) {
                $amout = '';
                $form->particuler[$i]->setValue($fields['bdet_item']);
                $form->qty[$i]->setValue($fields['bdet_qty']);
                $amout = explode('.', $fields['bdet_amt']);
                $form->amount[$i]->setValue($amout[0]);
                $form->baisa[$i]->setValue($amout[1]);
                $form->remarks[$i]->setValue($fields['bdet_remarks']);
                $i ++;
            }
        }
        $this->view->form = $form;
        $this->view->mfields = $mfields;
        $this->view->file = $encFileId;
        $this->view->billinfo = $billInfo;
    }

    public function approvalAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/billdet.php';
        require_once __DIR__ . '/../admin/!model/company.php';
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collectionObj = new collection();
        $decBillId = $this->view->decode($this->view->param['ref']);
        if (! $decBillId)
            die('tampered');
        $billObj = new bill();
        $billInfo = $billObj->getBillInfo(array(
            'bill_id' => $decBillId
        ));
        if ($billInfo) {
            $billDetObj = new billdet();
            $maxBillDetails = $billDetObj->getMaxBillDet(array(
                'bdet_bill_id' => $decBillId
            ));
            $billDetails = $billDetObj->getBillDet(array(
                'bdet_bill_id' => $decBillId,
                'bdet_update_sts' => $maxBillDetails['max_update']
            ));
        }
        $formRender = true;
        $form = new form();
        $decBillId = $this->view->decode($this->view->param['ref']);
        $billDet = $billObj->getBillById($decBillId);
        // $countDet = $billObj->getBillFileNo(array('bill_pay_mode'=>$billDet['bill_pay_mode'],'bill_id'=>$decBillId));
        // $countDet =$countDet [0];
        // v("EXP" ."/". $decBillId ."/".($billDet['bill_pay_mode'] == 1 ? 'CH' : 'CR') ."/".$countDet ['type_count']);
        if (! $decBillId)
            die('tampered');
        $form->addElement('status', 'Status', 'checkbox', 'required', array(
            'options' => array(
                "1" => "Approved"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', 'required|alpha_space');
        $form->addElement('revdt', 'Review Date', 'text', 'date', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if ($valid['revdt']) {
                        $revdt = DateTime::createFromFormat(DF_DD, $valid['revdt']);
                        $revdt = date_format($revdt, DFS_DB);
                    }
                    $data = array(
                        'bill_app_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'bill_app_status' => $valid['status'],
                        'bill_app_note' => $valid['note'],
                        'bill_rev_date' => $revdt != '' ? $revdt : NULL
                    );
                    $update = $billObj->modify($data, $decBillId);
                    if ($update) {
                        $feedback = 'Bill status updated successfully';
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
        $encFileId = $this->view->encode($billInfo['file_id']);
        $encDocFileId = $this->view->encode($billInfo['agr_file']);
        $this->view->form = $form;
        $this->view->billDet = $billDet;
        $this->view->billInfo = $billInfo;
        $this->view->encFileId = $encFileId;
        $this->view->encDocFileId = $encDocFileId;
        $this->view->billDetails = $billDetails;
    }

    public function editenableAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        $formRender = true;
        $form = new form();
        $decBillId = $this->view->decode($this->view->param['ref']);
        if (! $decBillId)
            die('tampered');
        $form->addElement('eedit', 'Status', 'radio', 'required', array(
            'options' => array(
                "1" => "Enable Edit",
                "2" => "Cancel Bill"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', 'required');
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $billObj = new bill();
                    $billInfo = $billObj->getBillInfo(array(
                        'bill_id' => $decBillId
                    ));
                    if ($valid['eedit'] == 1) {
                        $action = " Enable Edit  ";
                        $data['bill_app_status'] = NULL;
                    }
                    if ($valid['eedit'] == 2) {
                        $data['bill_cancellation_status'] = 1;
                        $action = " Cancel ";
                    }
                    $data['bill_eedit_note'] = $action . $billInfo['bill_eedit_note'] . "<br>" . date_format(new DateTime(), 'Y-m-d H:i:s') . " Note : " . $valid['note'];
                    $update = $billObj->modify($data, $decBillId);
                    if ($update) {
                        $feedback = 'Bill status updated successfully';
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

    public function listAction()
    {
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
                3 => "Paid"
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
        $billList = $billObj->getBillPaginate(@$where);
        $offset = $billObj->_voffset;
        $this->view->form = $form;
        $this->view->billList = $billList;
        $this->view->billObj = $billObj;
        $this->view->offset = $offset;
        $this->view->filter_class = $filter_class;
        $this->view->f_refno = $valid['f_refno'];
    }

    public function miniviewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/billdet.php';
        require_once __DIR__ . '/../admin/!model/company.php';
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collectionObj = new collection();
        $decBillId = $this->view->decode($this->view->param['ref']);
        if (! $decBillId)
            die('tampered');
        $collection = $collectionObj->getPaymentcollectionByInvoice(array(
            'cdet_bill_id' => $decBillId
        ));
        $billObj = new bill();
        $billInfo = $billObj->getBillInfo(array(
            'bill_id' => $decBillId
        ));

        // revenue share //

        $billDet = $billObj->getInvoiceDetByBilllId(array(
            'bill_id' => $decBillId
        ));

        $countIds = [];

        foreach ($billDet as $bd) {
            $countIds[] = $bd['bdet_id'];
            //$billPairList[$bd['bill_id']] = 'AST/' . $bd['bill_id'];
            //$billIdList[$bd['bdet_id']] = $bd['bill_id'];
            $vhlIdList[$bd['bdet_id']] = $bd['vhl_id'];

            if (is_null($bd['comp_disp_name'])) {
                $hasNullCompDispName = true;
                $this->view->errorStatus = "Company name related to vehicle in Item is still empty";
            }
        }

        $form = new form();
        $form->addMultiElement('revenue', 'Revenue', 'float', 'required|numeric', '', array(
            'class' => 'input-right'
        ), $countIds);

        require_once __DIR__ . '/../admin/!model/billrev.php';
        $billRevObj = new billrev();

        $count = 1;

        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['mvehicle']);
        } else {
            $type2RvenueListPair = $billRevObj->getRevenueListPairType2([
                'brev_bill_id' => $decBillId
            ]);
            
            //a($type2RvenueListPair);
            
            
            if (is_array($type2RvenueListPair) && count($type2RvenueListPair) > 0)
                $count = array_keys($type2RvenueListPair);
        }

        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vhlObj = new vehicle();
        $vehicleList = $vhlObj->getVehicleCompanyPair();

        $form->addMultiElement('mvehicle', 'Vehicle No.', 'select', '', array(
            'options' => $vehicleList
        ), array(
            'class' => 'full-select'
        ), $count);

        $form->addMultiElement('mremarks', 'Remarks', 'text', '', '', array(
            'class' => ''
        ), $count);

        $form->addMultiElement('mextshare', 'Revenue', 'float', 'numeric', '', array(
            'class' => 'input-right'
        ), $count);

        $mfields = array_keys($form->_elements['mvehicle']);

        // <<

        if ($billInfo) {
            $billDetObj = new billdet();
            $maxBillDetails = $billDetObj->getMaxBillDet(array(
                'bdet_bill_id' => $decBillId
            ));
            $billDetails = $billDetObj->getBillDet(array(
                'bdet_bill_id' => $decBillId,
                'bdet_update_sts' => $maxBillDetails['max_update']
            ));
        }

        // revenue share //
        $revenueTotal = 0;

        if (isset($_POST) && count($_POST) > 0) {

            $form->addErrorMsg('revenue', 'required', 'Field is required');

            if (is_array(array_filter($_POST['mvehicle'])) && count(array_filter($_POST['mvehicle'])) > 0) {

                $form->addErrorMsg('mvehicle', 'required', 'Field is required');
                $form->addErrorMsg('mremarks', 'required', 'Field is required');
                $form->addErrorMsg('mextshare', 'required', 'Field is required');

                foreach ($_POST['mvehicle'] as $pbkey => $pbill) {

                    $form->addmRules("mvehicle", $pbkey, "required");
                    $form->addmRules("mremarks", $pbkey, "required");
                    $form->addmRules("mextshare", $pbkey, "required");

                    $revenueTotal += $_POST['mvehicle'][$pbill];
                }
            }

            foreach ($_POST['revenue'] as $rpkey => $rpval) {

                $revenueTotal += (float) $rpval;
            }

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];

            $tolerance = 0.0001; // Define acceptable precision
            if (($revenueTotal - $billInfo['bill_oribill_amt']) > $tolerance) {
                $this->view->errorStatus = "Total Revenue Share ( $revenueTotal ) cannot be greater than total Collection Amount ( " . $billInfo['bill_oribill_amt'] . " ).";
            } else if ($valid == true && ! $hasNullCompDispName) {

                $billRevObj->deleteByCollectionId([
                    'brev_bill_id' => $decBillId
                ]);

                foreach ($valid['revenue'] as $rkey => $rrev) {
                    $data = [];
                    $data['brev_bill_id'] = $decBillId;
                    $data['brev_type'] = 1;
                    $data['brev_group_id'] = $rkey;
                    $data['brev_vhl_id'] = $vhlIdList[$rkey];
                    $data['brev_remarks'] = '';
                    $data['brev_revenue'] = $rrev;

                    $billRevObj->add($data);
                }
                
                if (is_array(array_filter($valid['mvehicle'])) && count(array_filter($valid['mvehicle'])) > 0) {

                    foreach ($valid['mvehicle'] as $bkey => $bbill) {
                        $data = [];
                        $data['brev_bill_id'] = $decBillId;
                        $data['brev_type'] = 2;
                        $data['brev_group_id'] = $bkey;
                        $data['brev_vhl_id'] = $valid['mvehicle'][$bkey];
                        $data['brev_remarks'] = $valid['mremarks'][$bkey];
                        $data['brev_revenue'] = $valid['mextshare'][$bkey];

                        $billRevObj->add($data);
                    }
                }

                $feedback = $_SESSION['feedback'] = 'Revenue share details updated successfully.';
                $form->reset();
                $this->view->NoViewRender = true;
                $success = array(
                    'feedback' => $feedback
                );
                $success = json_encode($success);
                die($success);
            } else {
                $this->view->errorStatus = "Data error in form submission " . $this->view->errorStatus;
            }
        } else {
            $preRevList = $billRevObj->getRevenuePairType1([
                'brev_bill_id' => $decBillId
            ]);
            if (is_array($preRevList) && count($preRevList) > 0) {
                foreach ($preRevList as $pl => $pval)
                    $form->revenue[$pl]->setValue($pval);

                $preRevListExtra = $billRevObj->getRevenueListType2([
                    'brev_bill_id' => $decBillId
                ]);

                if (is_array($preRevListExtra) && count($preRevListExtra) > 0) {

                    foreach ($preRevListExtra as $plex) {
                        $form->mvehicle[$plex['brev_id']]->setValue($plex['brev_vhl_id']);
                        $form->mremarks[$plex['brev_id']]->setValue($plex['brev_remarks']);
                        $form->mextshare[$plex['brev_id']]->setValue($plex['brev_revenue']);
                    }
                }
            } else {
                foreach ($billDet as $bd)
                    $form->revenue[$bd['bdet_id']]->setValue($bd['total_amt']);
            }


        }

        $this->view->mfields = $mfields;
        $this->view->form = $form;
        $this->view->billDet = $billDet;

        $this->view->collection = $collection;
        $this->view->billInfo = $billInfo;
        $this->view->billDetails = $billDetails;
    }

    public function viewAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/billdet.php';
        require_once __DIR__ . '/../admin/!model/company.php';
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collectionObj = new collection();
        $decBillId = $this->view->decode($this->view->param['ref']);
        if (! $decBillId)
            die('tampered');
        $collection = $collectionObj->getPaymentcollectionByInvoice(array(
            'cdet_bill_id' => $decBillId
        ));
        // s($collection);
        $billObj = new bill();
        $billInfo = $billObj->getBillInfo(array(
            'bill_id' => $decBillId
        ));
        if ($billInfo) {
            $billDetObj = new billdet();
            $maxBillDetails = $billDetObj->getMaxBillDet(array(
                'bdet_bill_id' => $decBillId
            ));
            $billDetails = $billDetObj->getBillDet(array(
                'bdet_bill_id' => $decBillId,
                'bdet_update_sts' => $maxBillDetails['max_update']
            ));
        }
        $this->view->collection = $collection;
        $this->view->billInfo = $billInfo;
        $this->view->collection = $collection;
        $this->view->billDetails = $billDetails;
        $this->view->decBillId = $decBillId;
    }
}
