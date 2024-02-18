<?php

class paymentsController extends mvc
{

function addAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/expense.php';
    $formRender = true;
    $form1 = new form();
    $form2 = new form();
    $balanceConfirm = '';

    require_once __DIR__ . '/../admin/!model/vendor.php';
    $vendorObj = new vendor();
    $venderList = $vendorObj->getVendorPair();
    $form1->addElement('f_selVendor', 'Vendor', 'select', 'required', array(
        'options' => $venderList
    ));

    $form2->addElement('percb', 'Personal Cash Book', 'checkbox', '', array(
        'options' => array(
            1 => "Personnal Cash Book"
        )
    ));
    $form2->addElement('cbamount', 'CB Amount', 'float', 'numeric', '', array(
        'class' => 'fig'
    ));

    require_once __DIR__ . '/../admin/!model/cashflow.php';
    $cashFlow = new cashflow();
    $cashFlowList = $cashFlow->getCashFlowPair();
    $form2->addElement('cashFlow', 'Cash Flow', 'select', '', array(
        'options' => $cashFlowList
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
    $form2->addElement('f_selVendor', 'Vendor', 'hidden', 'required');
    $form2->addElement('confirm', 'Confirm', 'checkbox', '', array(
        'options' => array(
            1 => "OK"
        )
    ));
    $expObj = new expense();

    if ($_POST) {
        if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            die('---'); // exit script outputting json data
        } else {
            $form2->f_selVendor->setValue($_POST['f_selVendor']);

            // s($_POST);
            $valid = $form1->vaidate($_POST, $_FILES);
            $where = array(
                'f_selVendor' => @$_POST['f_selVendor'],
                'f_mode' => 2,
                'exp_pstatus' => 2
            );

            $valid = $form1->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            $excludeExp = [];
            $count=[];
            if ($valid == true) {

                require_once __DIR__ . '/../admin/!model/paymentdet.php';
                $payDet = new paymentdet();
                $excludeExp = $payDet->getPaymentDetByApproval(array(
                    'pdet_status' => 1
                ));
                if (count($excludeExp) > 0)
                    $where['exclude'] = implode(',', $excludeExp);

                $expenseList = $expObj->geExpenseReport(@$where, 'date');
                $count = $expObj->getExpenseVendorPair(@$where);
                $billAmount = $expObj->getExpenseAmountPair(@$where);

                // $count = count($expenseList);
                if (count($count) > 0) {
                    $form2->addMultiElement('check', 'Select bill', 'checkbox', '', array(
                        'options' => array(
                            1 => ""
                        )
                    ), array(
                        "" => "onClick='toggleHeight(this)'",
                        'class' => "bill-select"
                    ), $count);
                    $form2->addMultiElement('mamount', 'Payment', 'float', 'numeric', '', array(
                        'class' => 'form-control-row'
                    ), $count);
                    $form2->addMultiElement('discount', 'Discount', 'float', 'numeric', '', array(
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

                                    // $form2->addmRules( 'mamount', $i, 'max_numeric,'.$billAmount[$i]);
                                    // OR
                                    $form2->addmRules('mamount', $i, 'invalid');
                                    $form2->addErrorMsg('mamount', 'invalid', "!! is > (bill-dis) amount");
                                }

                                $pdiscount = $_POST['discount'][$i]==''? 0:$_POST['discount'][$i];
                                $pamount = $_POST['mamount'][$i]==''? 0:$_POST['mamount'][$i];
                                if (! $balanceConfirm && (($billAmount[$i] - $pdiscount) - $pamount) > 0) {
                                    // v(($billAmount [$i] - $_POST ['discount'] [$i])-$_POST ['mamount'] [$i],$_POST ['mamount'] [$i]);

                                    $balanceConfirm = true;
                                    $form2->addRules('confirm', 'required', "Please confirm !!");
                                    // $form2->addErrorMsg ( 'confirm', 'required', );
                                }
                            }
                        }
                        

                        foreach ($_POST['check'] as $k => $v)
                            $sum +=floatval($_POST['mamount'][$k]);

                        if ((string) $sum != $_POST['amount'] && $_POST['amount'] != '') {

                            /*
                             * print_r(var_dump($sum)); print_r(var_dump($_POST ['amount']));
                             * echo $sum; echo "-"; echo floatval ($_POST ['amount']);
                             * echo "========".($sum - floatval ($_POST ['amount']));
                             * echo "<br>";/*
                             * if ( ( ($sum - floatval ($_POST ['amount'])) < 0 || ($sum - floatval ($_POST ['amount'])) > 0) && $_POST ['amount'] != '') {
                             */

                            $form2->addRules('amount', 'invalid');
                            $form2->addErrorMsg('amount', 'invalid', "(" . ($sum - floatval($_POST['amount'])) . ") Mismatch with total amount (" . $sum . ")");
                        }

                        $valid = $form2->vaidate($_POST, $_FILES);
                        $valid = $valid[0];
                        if ($valid == true) {

                            require_once __DIR__ . '/../admin/!model/payment.php';
                            $paymentObj = new payment();

                            $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                            $paydate = date_format($paydate, DFS_DB);

                            // v( $valid ['payby'] ,$paydate);

                            $data = array(
                                'pay_vendor' => $valid['f_selVendor'],
                                'pay_amount' => $valid['amount'],
                                'pay_paydate' => $paydate,
                                'pay_pay_mode' => $valid['paymod'],
                                'pay_chqno' => $valid['chqno'],
                                'pay_remarks' => $valid['note']
                            );
                            if ($valid['cashFlow'])
                                $data['pay_cash_flow'] = $valid['cashFlow'];

                            // s ( $valid,$billAmount);

                            $insert = $paymentObj->add($data);

                            if ($insert) {
                                require_once __DIR__ . '/../admin/!model/paymentdet.php';
                                $paymentDetObj = new paymentdet();

                                if (count($valid['check']) > 0)
                                    foreach ($valid['check'] as $rfkey => $rData) {
                                        $prdiscount = $_POST['discount'][$rfkey]==''? 0:$_POST['discount'][$rfkey];
                                        $pramount = $_POST['mamount'][$rfkey]==''? 0:$_POST['mamount'][$rfkey];
                                        
                                        if ($rData != '') {
                                            $data = array();
                                            $data = array(
                                                'pdet_pay_id' => $insert,
                                                'pdet_exp_id' => $rfkey,
                                                'pdet_amt_topay' => $billAmount[$rfkey],
                                                'pdet_amt_paid' => $pramount,
                                                'pdet_amt_dis' => $prdiscount,
                                                'pdet_amt_bal' => (($billAmount[$rfkey] - $prdiscount) - $pramount)
                                            );
                                            $det = $paymentDetObj->add($data);
                                        }
                                    }

                                if ($valid['percb'] == 1) {
                                    require_once __DIR__ . '/../admin/!model/cashbook.php';
                                    $cashBookObj = new cashbook();

                                    $cbData = array(
                                        'cb_type' => CASH_BOOK_PER,
                                        'cb_type_ref' => USER_ID,
                                        'cb_exp_id' => $insert,
                                        'cb_exp_type' => 2,
                                        'cb_credit' => $valid['cbamount'] != '' ? $valid['cbamount'] : $valid['amount'],
                                        'cb_date' => $paydate
                                    );
                                    $cashBookObj->add($cbData);
                                }

                                $this->view->NoViewRender = true;
                                $success = array(
                                    'feedback' => 'Payment details added successfully',
                                );
                                $success = json_encode($success);
                                die($success);
                            }
                        }
                    }
                }
            }
        }
        
       
    }
    else{
        $expenseList = [];
    }
    $this->view->form2=$form2;
    $this->view->form1=$form1;
    $this->view->formRender=$formRender;
    $this->view->expenseList=$expenseList;
    $this->view->billAmount=$billAmount;
    $this->view->balanceConfirm=$balanceConfirm;

}

function editAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/expense.php';
    $formRender = true;
    $form1 = new form();
    $form2 = new form();
    $balanceConfirm = "";

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    if (! $decPayId)
        die('tampered');

    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/paymentdet.php';
    $paymentDetObj = new paymentdet();

    $payDet = $paymentObj->getPaymentDetByPaymentId(array(
        'pay_id' => $decPayId
    ));

    if ($payDet['pay_app_status'] == 0) :

        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $venderList = $vendorObj->getVendorPair();
        $form1->addElement('f_selVendor', 'Vendor', 'select', 'required', array(
            'options' => $venderList
        ));

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowList = $cashFlow->getCashFlowPair();
        $form2->addElement('cashFlow', 'Cash Flow', 'select', '', array(
            'options' => $cashFlowList
        ));

        $form2->addElement('percb', 'Personal Cash Book', 'checkbox', '', array(
            'options' => array(
                1 => "Personnal Cash Book"
            )
        ));
        $form2->addElement('cbamount', 'CB Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
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
        $form2->addElement('f_selVendor', 'Vendor', 'hidden', 'required');
        $form2->addElement('confirm', 'Confirm', 'checkbox', '', array(
            'options' => array(
                "1" => "OK"
            )
        ));
        $expObj = new expense();

        require_once __DIR__ . '/../admin/!model/paymentdet.php';
        $payDetObj = new paymentdet();
        $excludeExp = $payDetObj->getPaymentDetByApproval(array(
            'pdet_status' => 1,
            'pdet_pay_id_exclude' => $decPayId
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $formPostStatus = true;

                $valid = $form1->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $where = array(
                        'f_selVendor' => $valid['f_selVendor'],
                        'f_mode' => 2,
                        'exp_pstatus' => 2
                    );

                    if (count($excludeExp) > 0)
                        $where['exclude'] = implode(',', $excludeExp);
                    $expenseList = $expObj->geExpenseReport(@$where, 'date');
                }
            }
        } else {
            $where = array(
                'f_selVendor' => $payDet['pay_vendor'],
                'f_mode' => 2,
                'pdet_pay_id' => $decPayId,
                'exp_pstatus' => 2
            );

            if (count($excludeExp) > 0)
                $where['exclude'] = implode(',', $excludeExp);
            $expenseList = $expObj->getPaymentExpDet($where, 'date');
            unset($where['pdet_pay_id']);
        }

        if (count($expenseList)) {

            $count = $expObj->getExpenseVendorPair($where);
            $billAmount = $expObj->getExpenseAmountPair($where);

            $form2->addMultiElement('check', 'Select bill', 'checkbox', '', array(
                'options' => array(
                    1 => ""
                )
            ), array(
                "" => "onClick='toggleHeight(this)'",
                'class' => "bill-select"
            ), $count);
            $form2->addMultiElement('mamount', 'Payment', 'float', 'numeric', '', array(
                'class' => 'form-control-row'
            ), $count);
            $form2->addMultiElement('discount', 'Discount', 'float', 'numeric', '', array(
                'class' => 'form-control-row'
            ), $count);
            // $mfields = array_keys($form2->_elements['check']);

            $form2->addErrorMsg('mamount', 'required', ' ');
        }

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $form2->f_selVendor->setValue($_POST['f_selVendor']);

                $valid = $form1->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    // $count = count($expenseList);
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

                                        // $form2->addmRules( 'mamount', $i, 'max_numeric,'.$billAmount[$i]);
                                        // OR
                                        $form2->addmRules('mamount', $i, 'invalid');
                                        $form2->addErrorMsg('mamount', 'invalid', "!! is > (bill-dis) amount");
                                    }

                                    $pdiscount = $_POST['discount'][$i]==''? 0:$_POST['discount'][$i];
                                    $pamount = $_POST['mamount'][$i]==''? 0:$_POST['mamount'][$i];
                                    if (! $balanceConfirm && (($billAmount[$i] - $pdiscount) - $pamount) > 0) {
                                        
                                        // v(($billAmount [$i] - $_POST ['discount'] [$i])-$_POST ['mamount'] [$i],$_POST ['mamount'] [$i]);

                                        $balanceConfirm = true;
                                        $form2->addRules('confirm', 'required', "Please confirm !!");
                                        $form2->addErrorMsg('confirm', 'required');
                                    }
                                }
                            }

                            foreach ($_POST['check'] as $k => $v)
                                $sum += floatval($_POST['mamount'][$k]);

                            if ((string) $sum != $_POST['amount'] && $_POST['amount'] != '') {
                                $form2->addRules('amount', 'invalid');
                                // $form2->addErrorMsg ( 'amount', 'invalid', "Mismatch with invoice total amount" );
                                $form2->addErrorMsg('amount', 'invalid', "(" . ($sum - floatval($_POST['amount'])) . ") Mismatch with total amount (" . $sum . ")");
                            }

                            $valid = $form2->vaidate($_POST, $_FILES);
                            $valid = $valid[0];
                            if ($valid == true) {

                                $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                                $paydate = date_format($paydate, DFS_DB);

                                $data = array(
                                    'pay_vendor' => $valid['f_selVendor'],
                                    'pay_amount' => $valid['amount'],
                                    'pay_paydate' => $paydate,
                                    'pay_pay_mode' => $valid['paymod'],
                                    'pay_chqno' => $valid['chqno'],
                                    'pay_remarks' => $valid['note']
                                );
                                if ($valid['cashFlow'])
                                    $data['pay_cash_flow'] = $valid['cashFlow'];

                                $update = $paymentObj->modify($data, $decPayId);

                                if ($update) {

                                    $clear = $paymentDetObj->deletePayDetByExpId(array(
                                        'pdet_pay_id' => $decPayId
                                    ));

                                    if (count($_POST['check']) > 0)
                                        foreach ($_POST['check'] as $rfkey => $rData) {
                                            $prdiscount = $_POST['discount'][$rfkey]==''? 0:$_POST['discount'][$rfkey];
                                            $pramount = $_POST['mamount'][$rfkey]==''? 0:$_POST['mamount'][$rfkey];
                                            if ($rData != '') {
                                                $data = array();
                                                $data = array(
                                                    'pdet_pay_id' => $decPayId,
                                                    'pdet_exp_id' => $rfkey,
                                                    'pdet_amt_topay' => $billAmount[$rfkey],
                                                    'pdet_amt_paid' => $pramount,
                                                    'pdet_amt_dis' => $prdiscount,
                                                    'pdet_amt_bal' => (($billAmount[$rfkey] - $prdiscount) - $pramount)
                                                );
                                                $det = $paymentDetObj->add($data);
                                            }
                                        }

                                    require_once __DIR__ . '/../admin/!model/cashbook.php';
                                    $cashBookObj = new cashbook();

                                    $cbData = array(
                                        'cb_type' => CASH_BOOK_PER,
                                        'cb_type_ref' => USER_ID,
                                        'cb_exp_id' => $decPayId,
                                        'cb_exp_type' => 2,
                                        'cb_credit' => $valid['cbamount'] != '' ? $valid['cbamount'] : $valid['amount'],
                                        'cb_date' => $paydate
                                    );

                                    if ($valid['percb'] == 1 && $payDet['cb_id'] != '' && $payDet['cb_type_ref'] == USER_ID) {
                                        $cashBookObj->modify($cbData, $payDet['cb_id']);
                                    } elseif ($valid['percb'] == 1 && $payDet['cb_id'] == '') {
                                        $cashBookObj->add($cbData);
                                    }
                                    if ($valid['percb'] == '' && $payDet['cb_id'] != '' && $payDet['cb_type_ref'] == USER_ID) {
                                        $cashBookObj->deleteCashBook($payDet['cb_id']);
                                    }

                                    $this->view->NoViewRender = true;
                                    $success = array(
                                        'feedback' => 'Payment details updated successfully',
                                    );
                                    $success = json_encode($success);
                                    die($success);
                                }
                            }
                        }
                    }
                }
            }
        } else {

            $pbd = DateTime::createFromFormat(DFS_DB, $payDet['pay_paydate']);
            $pbd = $pbd->format(DF_DD);

            $form1->f_selVendor->setValue($payDet['pay_vendor']);
            $form2->f_selVendor->setValue($payDet['pay_vendor']);
            $form2->amount->setValue($payDet['pay_amount']);
            $form2->payby->setValue($pbd);
            $form2->paymod->setValue($payDet['pay_pay_mode']);
            $form2->chqno->setValue($payDet['pay_chqno']);
            $form2->note->setValue($payDet['pay_remarks']);
            $form2->cashFlow->setValue($payDet['pay_cash_flow']);

            foreach ($expenseList as $expList) {
                if ($expList['pdet_amt_paid'] + $expList['pdet_amt_dis'] > 0)
                    $form2->check[$expList['exp_id']]->setValue(1);
                $form2->discount[$expList['exp_id']]->setValue($expList['pdet_amt_dis']);
                $form2->mamount[$expList['exp_id']]->setValue($expList['pdet_amt_paid']);
            }

            if ($payDet['cb_id'] != '') {
                $form2->percb->setValue(1);
                $form2->cbamount->setValue($payDet['cb_credit']);
            }
        }
        else:
            $expenseList = [];
        endif;
        
        $this->view->form2=$form2;
        $this->view->form1=$form1;
        $this->view->formRender=$formRender;
        $this->view->expenseList=$expenseList;
        $this->view->billAmount=$billAmount;
        $this->view->balanceConfirm=$balanceConfirm;
        $this->view->formPostStatus=$formPostStatus;
}

function listAction()
{
    
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();

    $form = new form();
    

    require_once __DIR__ . '/../admin/!model/vendor.php';
    $vendorObj = new vendor();
    $venderList = $vendorObj->getVendorPair();
    $form->addElement('f_selVendor', 'Vendor', 'select', '', array(
        'options' => $venderList
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
                'f_selVendor' => @$valid['f_selVendor']
                /*
             * 'f_name'=>@$valid['f_name'],
             * 'f_remarks'=>@$valid['f_remarks'],
             * 'f_price'=>@$valid['f_price'],
             */
            );
        }
        $filter_class = 'btn-info';
    }

    // s($where);

    $paymentList = $paymentObj->getPaymentPaginate(@$where);

    // s($paymentList);

    $this->view->offset = $paymentObj->_voffset;
    $this->view->paymentList=$paymentList;
    $this->view->paymentObj=$paymentObj;
    $this->view->form=$form;
    $this->view->filter_class=$filter_class;

}

function viewAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/paymentdet.php';
    $paymentDetObj = new paymentdet();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    $payDet = $paymentObj->getPaymentDetByPayId(array(
        'pay_id' => $decPayId
    ));

    $paymentList = $paymentDetObj->getPaymentExpDet(array(
        'pdet_pay_id' => $decPayId
    ));

    if (! $decPayId)
        die('tampered');
    
    $this->view->paymentList = $paymentList;
    $this->view->payDet = $payDet;
}

function approvalAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/paymentdet.php';
    $paymentDetObj = new paymentdet();
    require_once __DIR__ . '/../admin/!model/expense.php';
    $expObj = new expense();

    $formRender = true;
    $form = new form();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    $payDet = $paymentObj->getPaymentDetById($decPayId);

    if ($payDet['pay_app_status'] == 0) :

        $where = array(
            'f_selVendor' => $payDet['pay_vendor'],
            'f_mode' => 2,
            'pdet_pay_id' => $decPayId,
            'exp_pstatus' => 2
        );
        $paymentList = $expObj->getPaymentExpDet($where);

        if (! $decPayId)
            die('tampered');

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
                        'pay_app_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'pay_app_status' => $valid['status'],
                        'pay_app_note' => $valid['note'],
                        'pay_file_no' => "PAY" . "/" . $decPayId,
                        'pay_app_by' => $_SESSION['user_id']
                    );

                    $update = $paymentObj->modify($data, $decPayId);
                    if ($update) {

                        require_once __DIR__ . '/../admin/!model/expense.php';
                        $expenseObj = new expense();

                        foreach ($paymentList as $credit) {
                            $expdata = array();
                            if ($credit['pdet_id']) {

                                if ($credit['pdet_amt_bal'] <= 0)
                                    $expdata['exp_pstatus'] = 1;

                                $expdata['exp_credit_amt'] = $credit['pdet_amt_bal'];
                                $exupdate = $expenseObj->modify($expdata, $credit['pdet_exp_id']);
                            }
                        }

                        $paytDetData['pdet_status'] = 2;
                        $paymentDetObj->modify($paytDetData, array(
                            'pdet_pay_id' => $decPayId
                        ));

                        $feedback = 'Payments status updated successfully';
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

    $this->view->form=$form;
    $this->view->formRender=$formRender;
    $this->view->paymentList=$paymentList;
    $this->view->payDet=$payDet;
}

function closeAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/expense.php';
    $expObj = new expense();
    require_once __DIR__ . '/../admin/!model/documents.php';

    $formRender = true;
    $form = new form();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    if (! $decPayId)
        die('tampered');

    $payDet = $paymentObj->getPaymentDetByPayId(array(
        'pay_id' => $decPayId
    ));

    if ($payDet['pay_pay_status'] == 1) {
        $form->addElement('note', 'Note ', 'textarea', '');

        $where = array(
            'f_selVendor' => $payDet['pay_vendor'],
            'f_mode' => 2,
            'pdet_pay_id' => $decPayId,
                /*'exp_pstatus' => 2,*/
                'pdet_status' => 2
        );
        $paymentList = $expObj->getPaymentExpDet($where);

        // s($payDet,$paymentList);

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $data = array(
                    'pay_pay_status' => 2,
                    'pay_pay_note' => $valid['note'],
                    'pay_pay_app_date' => date_format(new DateTime(), 'Y-m-d H:i:s')
                );
                $update = $paymentObj->modify($data, $payDet['pay_id']);
                if ($update) {

                    $form->reset();
                    $feedback = 'Payments status updated successfully';
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

            $form->note->setValue($payDet['pay_pay_note']);
        }
    } else
         $this->view->NoViewRender = true;
         $this->view->form=$form;
         $this->view->paymentList=$paymentList;
         $this->view->payDet=$payDet;
}

function editoptionAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/expense.php';
    $formRender = true;

    $form2 = new form();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    if (! $decPayId)
        die('tampered');

    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();

    require_once __DIR__ . '/../admin/!model/paymentdet.php';
    $paymentDetObj = new paymentdet();

    $payDet = $paymentObj->getPaymentDetByPaymentId(array(
        'pay_id' => $decPayId
    ));
    $paymentList = $paymentDetObj->getPaymentExpDet(array(
        'pdet_pay_id' => $decPayId
    ));

    if ($payDet['pay_app_status'] == 1) :

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowList = $cashFlow->getCashFlowPair();
        $form2->addElement('cashFlow', 'Cash Flow', 'select', '', array(
            'options' => $cashFlowList
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form2->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    if ($valid['cashFlow'])
                        $data['pay_cash_flow'] = $valid['cashFlow'];

                    $update = $paymentObj->modify($data, $decPayId);

                    if ($update) {

                        $feedback = 'Item details added successfully';
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

            $form2->cashFlow->setValue($payDet['pay_cash_flow']);
        }
    endif;

    $this->view->form2=$form2;
    $this->view->paymentList=$paymentList;
    $this->view->payDet=$payDet;
}

function remittanceAction()
{
    $this->view->response('window');
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/expense.php';
    $expObj = new expense();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    $payDet = $paymentObj->getPaymentDetByPayId(array(
        'pay_id' => $decPayId
    ));

    $paymentList = $expObj->getPaymentExpDet(array(
        'pdet_pay_id' => $payDet['pay_id'],
        'f_selVendor' => $payDet['pay_vendor'],
        'exp_pay_mode' => 2
    ));

    if (! $decPayId)
        die('tampered');

        $this->view->paymentList = $paymentList;
        $this->view->payDet = $payDet;
}

function voucherAction()
{
    $this->view->response('ajax');
    require_once __DIR__ . '/../admin/!model/payment.php';
    $paymentObj = new payment();
    require_once __DIR__ . '/../admin/!model/expense.php';
    $expObj = new expense();
    require_once __DIR__ . '/../admin/!model/documents.php';

    $formRender = true;
    $form = new form();

    
    $decPayId = $this->view->decode($this->view->param['ref']);

    if (! $decPayId)
        die('tampered');

    $payDet = $paymentObj->getPaymentDetByPayId(array(
        'pay_id' => $decPayId
    ));

    if ($payDet['pay_app_status'] == 1) {
        $form->addElement('note', 'Note ', 'textarea', '');
        $form->addElement('paydate', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        $where = array(
            'f_selVendor' => $payDet['pay_vendor'],
            'f_mode' => 2,
            'pdet_pay_id' => $decPayId,
            /*'exp_pstatus' => 2,*/
            'pdet_status' => 2
        );
        $paymentList = $expObj->getPaymentExpDet($where);

        // s($payDet,$paymentList);

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $date = DateTime::createFromFormat(DF_DD, $valid['paydate']);
                $date = date_format($date, DFS_DB);
                $data = array(
                    'pay_pay_status' => 1,
                    'pay_pay_note' => $valid['note'],
                    'pay_pay_date' => $date
                );
                $update = $paymentObj->modify($data, $payDet['pay_id']);
                if ($update) {

                    if (! empty($payDet['file_id'])) {
                        $file = new files();
                        deleteFile($payDet['file_id']);
                        $file->deleteFile($payDet['file_id']);
                    }

                    $upload = uploadFiles(DOC_TYPE_PAY, $payDet['pay_id'], $valid['my_files']);
                    if ($upload) {
                        $form->reset();
                       
                        $feedback = 'Payment status updated successfully';
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

            if ($payDet['pay_pay_date']) {
                $payDt = DateTime::createFromFormat(DFS_DB, $payDet['pay_pay_date']);
                $payDt = $payDt->format(DF_DD);
            }

            $form->note->setValue($payDet['pay_pay_note']);
            $form->paydate->setValue($payDt);
        }
    } else
         $this->view->NoViewRender = true;

         $this->view->form=$form;
         $this->view->formRender=$formRender;
         $this->view->paymentList=$paymentList;
         $this->view->payDet=$payDet;
}

}