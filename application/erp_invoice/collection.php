<?php

class collectionController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $formRender = true;
        $form1 = new form();
        $form2 = new form();
        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $customerList['-1'] = 'PROPERTIES MANAGED BY ASTGlobal';
        $form1->addElement('f_selCustomer', 'Customer', 'select', 'required', array(
            'options' => $customerList
        ));
        $form2->addElement('payby', 'Pay Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form2->addElement('paymod', 'Cash/Cheque', 'radio', 'required', array(
            'options' => array(
                1 => "Cash",
                2 => "Cheque"
            )
        ));
        $form2->addElement('amount', 'Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form2->addElement('chqno', 'Cheque No ', 'text', '');
        $form2->addElement('note', 'Note', 'textarea', '');
        $form2->addElement('f_selCustomer', 'Customer', 'hidden', 'required');
        $form2->addElement('confirm', 'Confirm', 'checkbox', '', array(
            'options' => array(
                1 => "OK"
            )
        ));
        $form2->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $billObj = new bill();
        if ($_POST) {
            $form2->f_selCustomer->setValue($_POST['f_selCustomer']);
            // s($_POST);
            $valid = $form1->vaidate($_POST, $_FILES);
            $where = array(
                'f_selCustomer' => @$_POST['f_selCustomer'],
                'bill_mode' => 2,
                'bill_pstatus' => 2,
                'bill_app_status' => 1
            );
            $valid = $form1->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                require_once __DIR__ . '/../admin/!model/collectiondet.php';
                $collectionDetObj = new collectiondet();
                if ($valid['f_selCustomer'] != - 1) {
                    $excludeBills = $collectionDetObj->getCollectionDetByApproval(array(
                        'cdet_status' => 1
                    ));
                    if (count($excludeBills) > 0)
                        $where['exclude'] = implode(',', $excludeBills);
                    $billList = $billObj->getBillReport(@$where);
                    $count = $billObj->getBillCustomerPair(@$where);
                    $billAmount = $billObj->getBillAmountPair(@$where);
                }
                if ($valid['f_selCustomer'] == - 1) {
                    require_once __DIR__ . '/../admin/!model/cashdemand.php';
                    $cashDmdObj = new cashdemand();
                    $demandList = $cashDmdObj->getPendingDemandList(array(
                        'cdet_src_type' => 2,
                        'cdmd_pstatus' => 2
                    ));
                    $count = $cashDmdObj->getPendingDemandIdList(array(
                        'cdet_src_type' => 2,
                        'cdmd_pstatus' => 2
                    ));
                    $billAmount = $cashDmdObj->getPendingDemandAmountList(array(
                        'cdet_src_type' => 2,
                        'cdmd_pstatus' => 2
                    ));
                }
                // $count = count($billList);

                // check
                // payby

                // a($_POST['check'],$valid);

                if ($valid['f_selCustomer'] == - 1) {

                    if (is_array($_POST['check']) && count($_POST['check']) > 0) {
                        $selectedId = array_keys($_POST['check']);
                        $demandSelectedList = $cashDmdObj->getPendingDemandList(array(
                            'cdet_src_type' => 2,
                            'cdmd_pstatus' => 2,
                            'demand_ids' => implode(',', $selectedId)
                        ));

                        if (is_array($demandSelectedList)) {

                            $baseDate = DateTime::createFromFormat('d/m/Y', $_POST['payby']);
                            $baseMonth = $baseDate->format('m'); // Extract the month as a string

                            foreach ($demandSelectedList as $dateToCheck) {

                                $dateObj = DateTime::createFromFormat('Y-m-d', $dateToCheck['cdmd_date']);

                                // Extract the month from this date
                                $checkMonth = $dateObj->format('m');

                                // Compare the months
                                if ($baseMonth !== $checkMonth) {

                                    $form2->addRules('payby', 'invalid', "The collection date must be in the same month as the demand date.");
                                }
                            }
                        }

                        // v($_POST['payby']); a($demadDates);
                    }
                }

                if (count($count) > 0) {
                    $form2->addMultiElement('check', 'Select bill', 'checkbox', '', array(
                        'options' => array(
                            1 => ""
                        )
                    ), array(
                        "" => "onClick='toggleHeight(this)'",
                        'class' => "bill-select"
                    ), $count);
                    $form2->addMultiElement('mamount', 'Collection', 'float', 'numeric', '', array(
                        'class' => 'form-control-row'
                    ), $count);
                    $form2->addMultiElement('discount', 'Adjustment', 'float', 'numeric', '', array(
                        'class' => 'form-control-row'
                    ), $count);
                    $mfields = array_keys($form2->_elements['check']);
                    $form2->addErrorMsg('mamount', 'required', ' ');
                    if ($_POST['paymod'] == '2')
                        $form2->addRules('chqno', 'required');
                    if (is_array($_POST['check']) && count($_POST['check']) > 0) {
                        foreach ($mfields as $i) {
                            if ($_POST['check'][$i] != '') {
                                if (/*$_POST ['mamount'] [$i] == 0 ||*/ $_POST['mamount'][$i] == '') {
                                    // $form2->addmRules ( 'mamount', $i, 'numeric|required' );
                                    $form2->addmRules('mamount', $i, 'invalid');
                                    $form2->addErrorMsg('mamount', 'invalid', " ");
                                }
                                if (bcsub($billAmount[$i], $_POST['discount'][$i], 3) < $_POST['mamount'][$i]) {
                                    // if (($billAmount [$i] - $_POST ['discount'] [$i]) < $_POST ['mamount'] [$i]) {
                                    // $form2->addmRules( 'mamount', $i, 'max_numeric,'.$billAmount[$i]);
                                    // OR
                                    $form2->addmRules('mamount', $i, 'invalid');
                                    $form2->addErrorMsg('mamount', 'invalid', "!! is > (bill-dis) amount");
                                }

                                $pdiscount = $_POST['discount'][$i] == '' ? 0 : $_POST['discount'][$i];
                                $pamount = $_POST['mamount'][$i] == '' ? 0 : $_POST['mamount'][$i];
                                if (! $balanceConfirm && (($billAmount[$i] - $pdiscount) - $pamount) > 0) {
                                    // v(($billAmount [$i] - $_POST ['discount'] [$i])-$_POST ['mamount'] [$i],$_POST ['mamount'] [$i]);
                                    $balanceConfirm = true;
                                    $form2->addRules('confirm', 'required', "Please confirm !!");
                                    // $form2->addErrorMsg ( 'confirm', 'required', );
                                }
                            }
                        }
                        foreach ($_POST['check'] as $k => $v)
                            $sum += $_POST['mamount'][$k];
                        if (! empty($_POST['amount']) && bccomp($sum, floatval($_POST['amount']), 3)) {
                            $form2->addRules('amount', 'invalid');
                            $form2->addErrorMsg('amount', 'invalid', "Mismatch with bill total amount");
                        }

                        $valid = $form2->vaidate($_POST, $_FILES);
                        $valid = $valid[0];
                        if ($valid == true) {
                            require_once __DIR__ . '/../admin/!model/collection.php';
                            $collectionObj = new collection();
                            $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                            $paydate = date_format($paydate, DFS_DB);
                            // v( $valid ['payby'] ,$paydate);
                            $data = array(
                                'coll_cust' => $valid['f_selCustomer'],
                                'coll_amount' => $valid['amount'],
                                'coll_paydate' => $paydate,
                                'coll_coll_mode' => $valid['paymod'],
                                'coll_chqno' => $valid['chqno'],
                                'coll_remarks' => $valid['note']
                            );
                            if ($valid['f_selCustomer'] == - 1)
                                $data['coll_src_type'] = 2;
                            $insert = $collectionObj->add($data);
                            if ($insert) {
                                $feedback = $_SESSION['feedback'] = 'Bill details updated successfully';
                                if (count($valid['check']) > 0)
                                    foreach ($valid['check'] as $rfkey => $rData) {
                                        $vdiscount = $valid['discount'][$rfkey] == '' ? 0 : $valid['discount'][$rfkey];
                                        $vmamount = $valid['mamount'][$rfkey] == '' ? 0 : $valid['mamount'][$rfkey];
                                        if ($rData != '') {
                                            $data = array();
                                            $data = array(
                                                'cdet_coll_id' => $insert,
                                                'cdet_bill_id' => $rfkey,
                                                'cdet_amt_topay' => $billAmount[$rfkey],
                                                'cdet_amt_paid' => $valid['mamount'][$rfkey],
                                                'cdet_amt_dis' => ($valid['discount'][$rfkey] == "" ? NULL : $valid['discount'][$rfkey]),
                                                'cdet_amt_bal' => (($billAmount[$rfkey] - $vdiscount) - $vmamount)
                                            );
                                            if ($valid['f_selCustomer'] == - 1)
                                                $data['cdet_src_type'] = 2;
                                            $det = $collectionDetObj->add($data);
                                        }
                                    }
                                if ($valid['my_files']) {
                                    $upload = uploadFiles(DOC_TYPE_COLL, $insert, $valid['my_files']);
                                    if ($upload) {
                                        $form2->reset();

                                        $this->view->NoViewRender = true;
                                        $success = array(
                                            'feedback' => 'Collection details added successfully'
                                        );
                                        $success = json_encode($success);
                                        die($success);
                                    }
                                }
                                $form2->reset();
                                $this->view->NoViewRender = true;
                                $success = array(
                                    'feedback' => 'Collection details added successfully'
                                );
                                $success = json_encode($success);
                                die($success);
                            }
                        }
                    }
                }
            }
        }
        $this->view->form2 = $form2;
        $this->view->form1 = $form1;
        $this->view->formRender = $formRender;
        $this->view->billList = $billList;
        $this->view->billAmount = $billAmount;
        $this->view->demandList = $demandList;
        $this->view->balanceConfirm = $balanceConfirm;
    }

    public function approvalAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $formRender = true;
        $form = new form();
        $decCollId = $this->view->decode($this->view->param['ref']);
        if (! $decCollId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collObj = new collection();
        require_once __DIR__ . '/../admin/!model/collectiondet.php';
        $collectionDetObj = new collectiondet();
        $collDet = $collObj->getCollectionDetByPayId(array(
            'coll_id' => $decCollId
        ));
        if ($collDet['coll_app_status'] == 0) :
            if ($collDet['coll_src_type'] == 1) {
                $where = array(
                    'f_selCustomer' => $collDet['coll_cust'],
                    'bill_mode' => 2,
                    'cdet_coll_id' => $decCollId,
                    'bill_pstatus' => 2
                );
                $billObj = new bill();
                $billList = $billObj->getCollectionBillDet($where);
            }
            if ($collDet['coll_src_type'] == 2) {
                require_once __DIR__ . '/../admin/!model/cashdemand.php';
                $cashDmdObj = new cashdemand();
                $demandList = $cashDmdObj->getPendingDemandList(array(
                    'cdet_coll_id' => $decCollId,
                    'cdet_src_type' => 2
                ));
            }
            $form->addElement('status', 'Status', 'checkbox', 'required', array(
                'options' => array(
                    "1" => "Approved"
                )
            ));
            $form->addElement('note', 'Note ', 'textarea', 'required|alpha_space');
            if ($_POST) {
                if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                    die('---'); // exit script outputting json data
                } else {
                    $valid = $form->vaidate($_POST, $_FILES);
                    $valid = $valid[0];
                    if ($valid == true) {
                        $data = array(
                            'coll_app_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                            'coll_app_status' => $valid['status'],
                            'coll_app_note' => $valid['note'],
                            'coll_file_no' => "AST/REC" . "/" . $decCollId,
                            'coll_app_by' => USER_ID
                        );
                        $update = $collObj->modify($data, $decCollId);
                        if ($update) {
                            if ($collDet['coll_src_type'] == 1) {
                                foreach ($billList as $credit) {
                                    $billdata = array();
                                    if ($credit['cdet_id']) {
                                        if ($credit['cdet_amt_bal'] <= 0)
                                            $billdata['bill_pstatus'] = 1;
                                        $billdata['bill_credit_amt'] = $credit['cdet_amt_bal'];
                                        $billupdate = $billObj->modify($billdata, $credit['cdet_bill_id']);
                                    }
                                }
                            }
                            if ($collDet['coll_src_type'] == 2) {
                                foreach ($demandList as $demand) {
                                    $dmddata = array();
                                    if ($demand['cdet_id']) {
                                        if ($demand['cdet_amt_bal'] <= 0)
                                            $dmddata['cdmd_pstatus'] = 1;
                                        $dmddata['cdmd_credit_amt'] = $demand['cdet_amt_bal'];
                                        $billupdate = $cashDmdObj->modify($dmddata, $demand['cdet_bill_id']);
                                    }
                                }
                            }
                            $collDetData['cdet_status'] = 2;
                            $collectionDetObj->modify($collDetData, array(
                                'cdet_coll_id' => $decCollId
                            ));
                            $feedback = 'Collection status updated successfully';
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
    endif;

        $this->view->form = $form;
        $this->view->formRender = $formRender;
        $this->view->billList = $billList;
        $this->view->demandList = $demandList;
        $this->view->collDet = $collDet;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $formRender = true;
        $form1 = new form();
        $form2 = new form();
        $decCollId = $this->view->decode($this->view->param['ref']);
        if (! $decCollId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collObj = new collection();
        require_once __DIR__ . '/../admin/!model/collectiondet.php';
        $collectionDetObj = new collectiondet();
        $collDet = $collObj->getCollectionDetByPayId(array(
            'coll_id' => $decCollId
        ));
        if ($collDet['coll_app_status'] == 0) :
            require_once __DIR__ . '/../admin/!model/customer.php';
            $customerObj = new customer();
            $customerList = $customerObj->getCustomerPair();
            $customerList['-1'] = 'PROPERTIES MANAGED BY ASTGlobal';
            $form1->addElement('f_selCustomer', 'Customer', 'select', 'required', array(
                'options' => $customerList
            ));
            $form2->addElement('payby', 'Coll Date', 'text', 'date|required', '', array(
                'class' => 'date_picker'
            ));
            $form2->addElement('paymod', 'Cash/Cheque', 'radio', 'required', array(
                'options' => array(
                    1 => "Cash",
                    2 => "Cheque"
                )
            ));
            $form2->addElement('amount', 'Amount', 'float', 'required|numeric', '', array(
                'class' => 'fig'
            ));
            $form2->addElement('chqno', 'Cheque No ', 'text', '');
            $form2->addElement('note', 'Note', 'textarea', '');
            $form2->addElement('f_selCustomer', 'Customer', 'hidden', 'required');
            $form2->addElement('confirm', 'Confirm', 'checkbox', '', array(
                'options' => array(
                    1 => "OK"
                )
            ));
            $form2->addFile('my_files', 'Document', array(
                'required' => false,
                'exten' => 'pdf',
                'size' => 5375000
            ));
            $billObj = new bill();
            require_once __DIR__ . '/../admin/!model/collectiondet.php';
            $collectionDetObj = new collectiondet();
            $excludeBills = $collectionDetObj->getCollectionDetByApproval(array(
                'cdet_status' => 1,
                'cdet_coll_id_exclude' => $decCollId
            ));
            if (count($excludeBills) > 0)
                $where['exclude'] = implode(',', $excludeBills);

            $excludeExp = [];
            $demandList = [];
            $billList = [];
            if ($_POST) {
                $formPostStatus = true;
                $valid = $form1->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if ($valid['f_selCustomer'] != - 1) {
                        $where = array(
                            'f_selCustomer' => @$_POST['f_selCustomer'],
                            'bill_mode' => 2,
                            'bill_pstatus' => 2,
                            'bill_app_status' => 1
                        );
                        if (count($excludeExp) > 0)
                            $where['exclude'] = implode(',', $excludeExp);
                        $billList = $billObj->getBillReport(@$where);
                    }
                    if ($valid['f_selCustomer'] == - 1) {
                        require_once __DIR__ . '/../admin/!model/cashdemand.php';
                        $cashDmdObj = new cashdemand();
                        $demandList = $cashDmdObj->getPendingDemandList(array(
                            'cdet_coll_id' => $decCollId,
                            'cdet_src_type' => 2,
                            'cdmd_pstatus' => 2
                        ));
                        // s ( $demandList );
                    }
                }
            } else {
                if ($collDet['coll_cust'] != - 1) {
                    $where = array(
                        'f_selCustomer' => $collDet['coll_cust'],
                        'bill_mode' => 2,
                        'cdet_coll_id' => $decCollId,
                        'bill_pstatus' => 2,
                        'bill_app_status' => 1
                    );
                    if (count($excludeExp) > 0)
                        $where['exclude'] = implode(',', $excludeExp);
                    $billList = $billObj->getCollectionBillDet($where);
                    unset($where['cdet_coll_id']);
                }
                if ($collDet['coll_cust'] == - 1) {
                    require_once __DIR__ . '/../admin/!model/cashdemand.php';
                    $cashDmdObj = new cashdemand();
                    $demandList = $cashDmdObj->getPendingDemandList(array(
                        'cdet_coll_id' => $decCollId,
                        'cdet_src_type' => 2,
                        'cdmd_pstatus' => 2
                    ));
                }
            }
            if (count($billList)) {
                $count = $billObj->getBillCustomerPair(@$where);
                $billAmount = $billObj->getBillAmountPair(@$where);
            } else if (count($demandList)) {
                $count = $cashDmdObj->getPendingDemandIdList(array(
                    'cdet_coll_id' => $decCollId,
                    'cdet_src_type' => 2,
                    'cdmd_pstatus' => 2
                ));
                $billAmount = $cashDmdObj->getPendingDemandAmountList(array(
                    'cdet_coll_id' => $decCollId,
                    'cdet_src_type' => 2,
                    'cdmd_pstatus' => 2
                ));
            }
            if ($count) {
                $form2->addMultiElement('check', 'Select bill', 'checkbox', '', array(
                    'options' => array(
                        1 => ""
                    )
                ), array(
                    "" => "onClick='toggleHeight(this)'",
                    'class' => "bill-select"
                ), $count);
                $form2->addMultiElement('mamount', 'Collection', 'float', 'numeric', '', array(
                    'class' => 'form-control-row'
                ), $count);
                $form2->addMultiElement('discount', 'Adjustment', 'float', 'numeric', '', array(
                    'class' => 'form-control-row'
                ), $count);
                $mfields = array_keys($form2->_elements['check']);
                $form2->addErrorMsg('mamount', 'required', ' ');
            }
            if ($_POST) {
                $form2->f_selCustomer->setValue($_POST['f_selCustomer']);
                $valid = $form1->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // $count = count($billList);
                    if (is_array($count) && count($count) > 0) {
                        if ($_POST['paymod'] == '2')
                            $form2->addRules('chqno', 'required');
                        if (is_array($_POST['check']) && count($_POST['check']) > 0) {
                            foreach ($_POST['check'] as $i => $key) {
                                if ($_POST['check'][$i] != '') {
                                    if (/*$_POST ['mamount'] [$i] == 0 ||*/ $_POST['mamount'][$i] == '') {
                                        // $form2->addmRules ( 'mamount', $i, 'numeric|required' );
                                        $form2->addmRules('mamount', $i, 'invalid');
                                        $form2->addErrorMsg('mamount', 'invalid', " ");
                                    }
                                    if (bcsub($billAmount[$i], $_POST['discount'][$i], 3) < $_POST['mamount'][$i]) {
                                        // if (($billAmount [$i] - $_POST ['discount'] [$i]) < $_POST ['mamount'] [$i]) {
                                        // $form2->addmRules( 'mamount', $i, 'max_numeric,'.$billAmount[$i]);
                                        // OR
                                        $form2->addmRules('mamount', $i, 'invalid');
                                        $form2->addErrorMsg('mamount', 'invalid', "!! is > (bill-dis) amount");
                                    }

                                    $pdiscount = $_POST['discount'][$i] == '' ? 0 : $_POST['discount'][$i];
                                    $pamount = $_POST['mamount'][$i] == '' ? 0 : $_POST['mamount'][$i];

                                    if (! $balanceConfirm && (($billAmount[$i] - $pdiscount) - $pamount) > 0) {
                                        // v(($billAmount [$i] - $_POST ['discount'] [$i])-$_POST ['mamount'] [$i],$_POST ['mamount'] [$i]);
                                        $balanceConfirm = true;
                                        $form2->addRules('confirm', 'required', "Please confirm !!");
                                        $form2->addErrorMsg('confirm', 'required');
                                    }
                                }
                            }
                            $sum = 0;
                            foreach ($_POST['check'] as $k => $v)
                                $sum += $_POST['mamount'][$k] == '' ? 0 : $_POST['mamount'][$k];
                            if (! empty($_POST['amount']) && bccomp($sum, floatval($_POST['amount']), 3)) {
                                $form2->addRules('amount', 'invalid');
                                $form2->addErrorMsg('amount', 'invalid', "Mismatch with bill total amount");
                            }
                            $valid = $form2->vaidate($_POST, $_FILES);
                            $valid = $valid[0];
                            if ($valid == true) {
                                $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                                $paydate = date_format($paydate, DFS_DB);
                                $data = array(
                                    'coll_cust' => $valid['f_selCustomer'],
                                    'coll_amount' => $valid['amount'],
                                    'coll_paydate' => $paydate,
                                    'coll_coll_mode' => $valid['paymod'],
                                    'coll_chqno' => $valid['chqno'],
                                    'coll_remarks' => $valid['note']
                                );
                                if ($valid['f_selCustomer'] == - 1)
                                    $data['coll_src_type'] = 2;
                                else
                                    $data['coll_src_type'] = 1;
                                $update = $collObj->modify($data, $decCollId);
                                if ($update) {
                                    $feedback = $_SESSION['feedback'] = 'Collection details updated successfully';
                                    $clear = $collectionDetObj->deleteCollDetByExpId(array(
                                        'cdet_coll_id' => $decCollId
                                    ));
                                    if (count($_POST['check']) > 0)
                                        foreach ($_POST['check'] as $rfkey => $rData) {
                                            $vdiscount = $valid['discount'][$rfkey] == '' ? 0 : $valid['discount'][$rfkey];
                                            $vmamount = $valid['mamount'][$rfkey] == '' ? 0 : $valid['mamount'][$rfkey];
                                            if ($rData != '') {
                                                $data = array();
                                                $data = array(
                                                    'cdet_coll_id' => $decCollId,
                                                    'cdet_bill_id' => $rfkey,
                                                    'cdet_amt_topay' => $billAmount[$rfkey],
                                                    'cdet_amt_paid' => $valid['mamount'][$rfkey],
                                                    'cdet_amt_dis' => ($valid['discount'][$rfkey] == "" ? NULL : $valid['discount'][$rfkey]),
                                                    'cdet_amt_bal' => (($billAmount[$rfkey] - $vdiscount) - $vmamount)
                                                );
                                                if ($valid['f_selCustomer'] == - 1)
                                                    $data['cdet_src_type'] = 2;
                                                $det = $collectionDetObj->add($data);
                                            }
                                        }
                                    if ($valid['my_files']) {
                                        if (! empty($collDet['file_id'])) {
                                            $file = new files();
                                            deleteFile($collDet['file_id']);
                                            $file->deleteFile($collDet['file_id']);
                                        }
                                        $upload = uploadFiles(DOC_TYPE_COLL, $decCollId, $valid['my_files']);
                                        if ($upload) {
                                            $form2->reset();
                                            $this->view->NoViewRender = true;
                                            $success = array(
                                                'feedback' => 'Collection details updated successfully'
                                            );
                                            $success = json_encode($success);
                                            die($success);
                                        }
                                    }
                                    $this->view->NoViewRender = true;
                                    $success = array(
                                        'feedback' => 'Collection details updated successfully'
                                    );
                                    $success = json_encode($success);
                                    die($success);
                                }
                            }
                        }
                    }
                }
            } else {
                $pbd = DateTime::createFromFormat(DFS_DB, $collDet['coll_paydate']);
                $pbd = $pbd->format(DF_DD);
                $form1->f_selCustomer->setValue($collDet['coll_cust']);
                $form2->f_selCustomer->setValue($collDet['coll_cust']);
                $form2->amount->setValue($collDet['coll_amount']);
                $form2->payby->setValue($pbd);
                $form2->paymod->setValue($collDet['coll_coll_mode']);
                $form2->chqno->setValue($collDet['coll_chqno']);
                $form2->note->setValue($collDet['coll_remarks']);
                if (count($billList) > 0)
                    foreach ($billList as $expList) {
                        if ($expList['cdet_amt_paid'] + $expList['cdet_amt_dis'] > 0)
                            $form2->check[$expList['bill_id']]->setValue(1);
                        $form2->discount[$expList['bill_id']]->setValue($expList['cdet_amt_dis']);
                        $form2->mamount[$expList['bill_id']]->setValue($expList['cdet_amt_paid']);
                    }
                if (count($demandList) > 0)
                    foreach ($demandList as $dmList) {
                        if ($dmList['cdet_amt_paid'] + $dmList['cdet_amt_dis'] > 0)
                            $form2->check[$dmList['cdmd_id']]->setValue(1);
                        $form2->discount[$dmList['cdmd_id']]->setValue($dmList['cdet_amt_dis']);
                        $form2->mamount[$dmList['cdmd_id']]->setValue($dmList['cdet_amt_paid']);
                    }
            }
            
            endif;

        $this->view->form2 = $form2;
        $this->view->form1 = $form1;
        $this->view->formRender = $formRender;
        $this->view->billList = $billList;
        $this->view->billAmount = $billAmount;
        $this->view->demandList = $demandList;
        $this->view->balanceConfirm = $balanceConfirm;
        $this->view->collDet = $collDet;
        $this->view->formPostStatus = $formPostStatus;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collectionObj = new collection();
        $form = new form();
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
                    'f_selCustomer' => @$valid['f_selCustomer'],
                    'f_type' => @$valid['f_type'],
                    'f_building' => @$valid['f_building'],
                    'f_property' => @$valid['f_property']
                    /* 'f_price'=>@$valid['f_price'], */
                );
            }
            $filter_class = 'btn-info';
        }
        // s($where);
        $collectionList = $collectionObj->getCollectionPaginate(@$where);
        // s($collectionList);
        $offset = $collectionObj->_voffset;
        $this->view->collectionList = $collectionList;
        $this->view->collectionObj = $collectionObj;
        $this->view->offset = $offset;
        $this->view->form = $form;
        $this->view->filter_class = $filter_class;
    }

    public function viewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/bill.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $decCollId = $this->view->decode($this->view->param['ref']);
        if (! $decCollId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collObj = new collection();
        $collDet = $collObj->getCollectionDetByPayId(array(
            'coll_id' => $decCollId
        ));

        $billDet = $collObj->getCollectionBillDetByCollId(array(
            'coll_id' => $decCollId
        ));

        // s($billDet);

        $countIds = [];

        foreach ($billDet as $bd) {
            $countIds[] = $bd['bdet_id'];
            $billPairList[$bd['bill_id']] = 'AST/' . $bd['bill_id'];
            $billIdList[$bd['bdet_id']] = $bd['bill_id'];
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

        require_once __DIR__ . '/../admin/!model/collectionrev.php';
        $collRevObj = new collectionrev();

        $count = 1;

        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['mbillno']);
        } else {
            $type2RvenueListPair = $collRevObj->getRevenueListPairType2([
                'rev_coll_id' => $decCollId
            ]);
            if (is_array($type2RvenueListPair) && count($type2RvenueListPair) > 0)
                $count = array_keys($type2RvenueListPair);
            else {
                require_once __DIR__ . '/../admin/!model/billrev.php';
                $billRevObj = new billrev();
                $type2RvenueListPair = $billRevObj->getRevenueListPairType2([
                    'brev_bill_id_in' => $billIdList
                ]);
                
                //a($type2RvenueListPair);
                
                
                if (is_array($type2RvenueListPair) && count($type2RvenueListPair) > 0)
                    $count = array_keys($type2RvenueListPair);
            }
        }

        $form->addMultiElement('mbillno', 'Bill No.', 'select', '', array(
            'options' => $billPairList
        ), array(
            'class' => 'full-select'
        ), $count);

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

        $mfields = array_keys($form->_elements['mbillno']);

        if ($collDet['coll_src_type'] == 1) {
            $where = array(
                'f_selCustomer' => $collDet['coll_cust'],
                'cdet_coll_id' => $decCollId
            );
            $billObj = new bill();
            $billList = $billObj->getCollectedBillDet($where);
        }
        if ($collDet['coll_src_type'] == 2) {
            require_once __DIR__ . '/../admin/!model/cashdemand.php';
            $cashDmdObj = new cashdemand();
            $demandList = $cashDmdObj->getPendingDemandList(array(
                'cdet_coll_id' => $decCollId,
                'cdet_src_type' => 2
            ));
        }

        $revenueTotal = 0;

        if (isset($_POST) && count($_POST) > 0) {

            $form->addErrorMsg('revenue', 'required', 'Field is required');

            if (is_array(array_filter($_POST['mbillno'])) && count(array_filter($_POST['mbillno'])) > 0) {

                $form->addErrorMsg('mvehicle', 'required', 'Field is required');
                $form->addErrorMsg('mremarks', 'required', 'Field is required');
                $form->addErrorMsg('mextshare', 'required', 'Field is required');

                foreach ($_POST['mbillno'] as $pbkey => $pbill) {

                    $form->addmRules("mvehicle", $pbkey, "required");
                    $form->addmRules("mremarks", $pbkey, "required");
                    $form->addmRules("mextshare", $pbkey, "required");

                    $revenueTotal += $_POST['mbillno'][$pbill];
                }
            }

            foreach ($_POST['revenue'] as $rpkey => $rpval) {

                $revenueTotal += (float) $rpval;
            }

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];

            $tolerance = 0.0001; // Define acceptable precision
            if (($revenueTotal - $collDet['coll_amount']) > $tolerance) {
                $this->view->errorStatus = "Total Revenue Share ( $revenueTotal ) cannot be greater than total Collection Amount ( " . $collDet['coll_amount'] . " ).";
            } else if ($valid == true && ! $hasNullCompDispName) {

                $collRevObj->deleteByCollectionId([
                    'rev_coll_id' => $decCollId
                ]);

                foreach ($valid['revenue'] as $rkey => $rrev) {
                    $data = [];
                    $data['rev_coll_id'] = $decCollId;
                    $data['rev_type'] = 1;
                    $data['rev_group_id'] = $rkey;
                    $data['rev_bill_id'] = $billIdList[$rkey];
                    $data['rev_vhl_id'] = $vhlIdList[$rkey];
                    $data['rev_remarks'] = '';
                    $data['rev_revenue'] = $rrev;

                    $collRevObj->add($data);
                }

                if (is_array(array_filter($valid['mbillno'])) && count(array_filter($valid['mbillno'])) > 0) {

                    foreach ($valid['mbillno'] as $bkey => $bbill) {
                        $data = [];
                        $data['rev_coll_id'] = $decCollId;
                        $data['rev_type'] = 2;
                        $data['rev_group_id'] = $bkey;
                        $data['rev_bill_id'] = $bbill;
                        $data['rev_vhl_id'] = $valid['mvehicle'][$bkey];
                        $data['rev_remarks'] = $valid['mremarks'][$bkey];
                        $data['rev_revenue'] = $valid['mextshare'][$bkey];

                        $collRevObj->add($data);
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
            $preRevList = $collRevObj->getRevenuePairType1([
                'rev_coll_id' => $decCollId
            ]);
            if (is_array($preRevList) && count($preRevList) > 0) {

                foreach ($preRevList as $pl => $pval)
                    $form->revenue[$pl]->setValue($pval);

                $preRevListExtra = $collRevObj->getRevenueListType2([
                    'rev_coll_id' => $decCollId
                ]);

                if (is_array($preRevListExtra) && count($preRevListExtra) > 0) {

                    foreach ($preRevListExtra as $plex) {
                        $form->mbillno[$plex['rev_group_id']]->setValue($plex['rev_bill_id']);
                        $form->mvehicle[$plex['rev_group_id']]->setValue($plex['rev_vhl_id']);
                        $form->mremarks[$plex['rev_group_id']]->setValue($plex['rev_remarks']);
                        $form->mextshare[$plex['rev_group_id']]->setValue($plex['rev_revenue']);
                    }
                }
            } else {
                foreach ($billDet as $bd)
                    $form->revenue[$bd['bdet_id']]->setValue($bd['brev_revenue']);

                //require_once __DIR__ . '/../admin/!model/billrev.php';
                //$billRevObj = new billrev();
                $preRevListExtra = $billRevObj->getRevenueListType2([
                    'brev_bill_id_in' => $billIdList
                ]);

                if (is_array($preRevListExtra) && count($preRevListExtra) > 0) {

                    foreach ($preRevListExtra as $plex) {
                        $form->mbillno[$plex['brev_id']]->setValue($plex['brev_bill_id']);
                        $form->mvehicle[$plex['brev_id']]->setValue($plex['brev_vhl_id']);
                        $form->mremarks[$plex['brev_id']]->setValue($plex['brev_remarks']);
                        $form->mextshare[$plex['brev_id']]->setValue($plex['brev_revenue']);
                    }
                }
            }
        }

        $this->view->colltype = $collDet['coll_src_type'];
        $this->view->mfields = $mfields;
        $this->view->form = $form;
        $this->view->billDet = $billDet;
        $this->view->billList = $billList;
        $this->view->demandList = $demandList;
        $this->view->collDet = $collDet;
    }
}