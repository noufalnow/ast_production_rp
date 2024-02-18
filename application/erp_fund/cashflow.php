<?php

class cashflowController extends mvc
{

    public function billviewAction()
    {
        $this->view->response('ajax');

        $decRefd = $this->view->decode($this->view->param['ref']);

        if (! $decRefd)
            die('tampered');

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowDet = $cashFlow->getCashFlowReferenceCasbookId(array(
            'cf_id' => $decRefd
        ));

        $this->view->cashFlowDet = $cashFlowDet;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();
        $form = new form();

        $form->addElement('f_code', 'Code', 'text', 'alpha_space');
        $form->addElement('f_name', 'Name', 'text', 'alpha_space');
        $form->addElement('f_remarks', 'Description', 'text', 'alpha_space');
        $form->addElement('f_price', 'Price', 'text', 'alpha_space');

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_code' => @$valid['f_code'],
                    'f_name' => @$valid['f_name'],
                    'f_remarks' => @$valid['f_remarks'],
                    'f_price' => @$valid['f_price']
                );
            }
        }

        // s($where);
        $where['cb_type_ref'] = 1999;

        $cashBookList = $cashBookObj->getCashBooksPaginate(@$where);

        // s($cashBookList);

        $offset = $cashBookObj->_voffset;

        $this->view->cashBookList = $cashBookList;
        $this->view->cashBookObj = $cashBookObj;
        $this->view->offset = $offset;
    }

    public function cashfreportAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();
        $form = new form();

        $form->addElement('f_code', 'Code', 'text', 'alpha_space');
        $form->addElement('f_name', 'Name', 'text', 'alpha_space');
        $form->addElement('f_remarks', 'Description', 'text', 'alpha_space');
        $form->addElement('f_price', 'Price', 'text', 'alpha_space');

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_code' => @$valid['f_code'],
                    'f_name' => @$valid['f_name'],
                    'f_remarks' => @$valid['f_remarks'],
                    'f_price' => @$valid['f_price']
                );
            }
        }
        $where['cb_type_ref'] = 1999;
        $cashBookList = $cashBookObj->getCashBooksReport(@$where);

        $this->view->cashBookList = $cashBookList;
    }

    public function feditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/collection.php';

        require_once __DIR__ . '/../admin/!model/collection.php';

        
        $form = new form();

        $decId = $this->view->decode($this->view->param['ref']);

        if (! $decId)
            die('tampered');

        $collObj = new collection();
        $collList = $collObj->getCollectionPair();

        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();

        $form->addElement('debitdt', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('totamount', 'Debit', 'float', 'required|numeric');
        $form->addElement('srcnote', 'Note', 'textarea', 'required', '', array(
            'class' => 'arabic_right'
        ));
        $form->addElement('srctype', 'Source Type', 'radio', 'required', array(
            'options' => array(
                1 => "Income",
                2 => "Owners Fund",
                3 => "Loan"
            )
        ), array(
            '' => 'onchange="toggleRadio(this);"'
        ));
        $form->addElement('src_income', 'Source', 'select', '', array(
            'options' => $collList
        ));
        $form->addElement('src_details', 'Source Details', 'text', '');

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();

        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();

        $count = 1;
        $idKeys = $cashFlow->getCashFlowKeyPair(array(
            'cf_cb_id' => $decId
        ));
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['date']);
        } else {
            $cashFlowDet = $cashFlow->getCashFlowByCasbookId(array(
                'cf_cb_id' => $decId
            ));
            $count = $idKeys;
        }

        $cashBookDet = $cashBookObj->getCashBookDetById($decId);
        $cashFlowDet = $cashFlow->getCashFlowByCasbookId(array(
            'cf_cb_id' => $decId
        ));
        $approvedKeyPair = $cashFlow->getApprovedKeyPair(array(
            'cf_cb_id' => $decId
        ));

        $form->addMultiElement('date', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ), $count);
        $form->addMultiElement('note', 'Note', 'text', 'required', '', array(
            'class' => ''
        ), $count);
        $form->addMultiElement('amount', 'Amount', 'amount', 'required|integer', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('baisa', 'Baisa', 'baisa', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('assign', 'Assigned', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select slbank'
        ), $count);
        $mfields = array_keys($form->_elements['date']);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $form->addErrorMsg('date', 'required', ' ');
                $form->addErrorMsg('date', 'date', ' ');
                $form->addErrorMsg('note', 'required', ' ');
                $form->addErrorMsg('amount', 'required', ' ');
                $form->addErrorMsg('baisa', 'required', ' ');

                if ($_POST['srctype'] == '1')
                    $form->addRules('src_income', 'required');

                if ($_POST['srctype'] == '2' || $_POST['srctype'] == '3')
                    $form->addRules('src_details', 'required');

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    // s($mfields);
                    // goto axd;

                    $billdt = DateTime::createFromFormat(DF_DD, $valid['debitdt']);
                    $billdt = date_format($billdt, DFS_DB);

                    $cbData = array(
                        'cb_type' => CASH_BOOK_COMP,
                        'cb_debit' => $valid['totamount'],
                        'cb_date' => $billdt,
                        'cb_debit_note' => $valid['srcnote'],
                        'cb_src' => $valid['srctype']
                    );

                    if ($valid['srctype'] == 1)
                        $cbData['cb_src_inc'] = $valid['src_income'];

                    if ($valid['srctype'] == 2 || $valid['srctype'] == 3)
                        $cbData['cb_src_det'] = $valid['src_details'];

                    if (count($approvedKeyPair) <= 0 || USER_GROUP == 2) // No entries should not be approved if so the user should be owner group to modify
                        $debitUpdate = $cashBookObj->modify($cbData, $decId);
                    else
                        $debitUpdate = true;

                    if ($debitUpdate) {

                        // $cashFlow->deleteByCashBook(array('cf_cb_id'=>$decId));

                        $to_delete = (array_diff($idKeys, $mfields));
                        $to_update = (array_intersect($idKeys, $mfields));
                        $to_insert = (array_diff($mfields, $idKeys));

                        if (count($to_delete) > 0)
                            foreach ($to_delete as $del)
                                if (! in_array($del, $approvedKeyPair))
                                    $cashFlow->deleteById($del);

                        foreach ($mfields as $i) {
                            $data = array();

                            if ($valid['date'][$i] != '') {

                                $dt = DateTime::createFromFormat(DF_DD, $valid['date'][$i]);
                                $dt = date_format($dt, DFS_DB);

                                $data = array(
                                    'cf_cb_id' => $decId,
                                    'cf_amount' => $valid['amount'][$i] . '.' . $valid['baisa'][$i],
                                    'cf_note' => $valid['note'][$i],
                                    'cf_dttime' => $dt
                                );

                                if ($valid['assign'][$i])
                                    $data['cf_assigned'] = $valid['assign'][$i];
                                else
                                    $data['cf_assigned'] = NULL;

                                if (in_array($i, $to_update) && ! in_array($i, $approvedKeyPair)) {
                                    $cflowdet = $cashFlow->modify($data, $i);
                                    $totalCredit += (float) $valid['amount'][$i] . '.' . $valid['baisa'][$i];
                                }

                                if (in_array($i, $to_insert)) {
                                    $cflowdet = $cashFlow->add($data);
                                    $totalCredit += (float) $valid['amount'][$i] . '.' . $valid['baisa'][$i];
                                }
                            }
                        }

                        $debitUpdate = $cashBookObj->modify(array(
                            'cb_credit' => $totalCredit
                        ), $decId);

                        if ($debitUpdate) {
                            $feedback = 'Debit details added successfully';
                            $this->view->NoViewRender = true;
                            $success = array(
                                'feedback' => $feedback
                            );
                            $_SESSION['feedback'] = $feedback;
                            $success = json_encode($success);
                            die($success);
                        }
                    }

                    axd:
                }
            }
        } else {

            $billdt = DateTime::createFromFormat(DFS_DB, $cashBookDet['cb_date']);
            $billdt = $billdt->format(DF_DD);

            $form->debitdt->setValue($billdt);
            $form->srcnote->setValue($cashBookDet['cb_debit_note']);
            $form->srctype->setValue($cashBookDet['cb_src']);
            $form->totamount->setValue($cashBookDet['cb_debit']);
            $form->src_income->setValue($cashBookDet['cb_src_inc']);
            $form->src_details->setValue($cashBookDet['cb_src_det']);

            foreach ($cashFlowDet as $fields) {
                $i = $fields['cf_id'];
                $Dt = DateTime::createFromFormat(DF_DB, $fields['cf_dttime']);
                $Dt = $Dt->format(DF_DD);
                $form->date[$i]->setValue($Dt);
                $intpart = explode('.', $fields['cf_amount']);
                $total += $fields['cf_amount'];
                $form->amount[$i]->setValue($intpart[0]);
                $form->baisa[$i]->setValue($intpart[1]);
                $form->note[$i]->setValue($fields['cf_note']);
                $form->assign[$i]->setValue($fields['cf_assigned']);

                if ($fields['cf_approve'] == 2) {

                    /*
                     * $form->debitdt->setDisabled();
                     * $form->totamount->setDisabled();
                     * $form->srcnote->setDisabled();
                     * $form->srctype->setDisabled();
                     * $form->src_income->setDisabled();
                     * $form->src_details->setDisabled();
                     */

                    $form->date[$i]->setDisabled();
                    $form->amount[$i]->setDisabled();
                    $form->baisa[$i]->setDisabled();
                    $form->note[$i]->setDisabled();
                    $form->assign[$i]->setDisabled();
                }
            }
        }

         $this->view ->form =  $form;
         $this->view ->mfields  = $mfields;
        $this->view->cashFlowDet = $cashFlowDet;
        $this->view->approvedKeyPair = $approvedKeyPair;
        $this->view->cashBookDet = $cashBookDet;
    }

    public function flowAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/collection.php';

        require_once __DIR__ . '/../admin/!model/collection.php';

        
        $form = new form();

        $collObj = new collection();
        $collList = $collObj->getCollectionPair();

        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();

        $form->addElement('debitdt', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('totamount', 'Debit', 'float', 'required|numeric');
        $form->addElement('srcnote', 'Note', 'textarea', 'required');
        $form->addElement('srctype', 'Source Type', 'radio', 'required', array(
            'options' => array(
                1 => "Income",
                2 => "Owners Fund",
                3 => "Loan"
            )
        ), array(
            '' => 'onchange="toggleRadio(this);"'
        ));
        $form->addElement('src_income', 'Source', 'select', '', array(
            'options' => $collList
        ));
        $form->addElement('src_details', 'Source Details', 'text', '');

        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['date']);
        } else {
            /*
             * $payOptionDet = $payOptObj->getPayOptiosByDocAndProperty( array (
             * 'popt_prop_id' => $propDocDet['doc_ref_id'],'popt_doc_id' => $decPropDocId,
             * ) );
             */
            //$count = count($payOptionDet) == 0 ? 1 : count($payOptionDet);
            $count = 1;
        }

        $form->addMultiElement('date', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ), $count);
        $form->addMultiElement('note', 'Note', 'text', 'required', '', array(
            'class' => ''
        ), $count);
        $form->addMultiElement('amount', 'Amount', 'amount', 'required|integer', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('baisa', 'Baisa', 'baisa', 'required|numeric', '', array(
            'class' => 'fig'
        ), $count);
        $form->addMultiElement('assign', 'Assigned', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select slbank'
        ), $count);
        $mfields = array_keys($form->_elements['date']);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $form->addErrorMsg('date', 'required', ' ');
                $form->addErrorMsg('date', 'date', ' ');
                $form->addErrorMsg('note', 'required', ' ');
                $form->addErrorMsg('amount', 'required', ' ');
                $form->addErrorMsg('baisa', 'required', ' ');

                if ($_POST['srctype'] == '1')
                    $form->addRules('src_income', 'required');

                if ($_POST['srctype'] == '2' || $_POST['srctype'] == '3')
                    $form->addRules('src_details', 'required');

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $billdt = DateTime::createFromFormat(DF_DD, $valid['debitdt']);
                    $billdt = date_format($billdt, DFS_DB);

                    require_once __DIR__ . '/../admin/!model/cashbook.php';
                    $cashBookObj = new cashbook();
                    $cbData = array(
                        'cb_type' => CASH_BOOK_COMP,
                        'cb_type_ref' => 1999,
                        'cb_debit' => $valid['totamount'],
                        'cb_date' => $billdt,
                        'cb_debit_note' => $valid['srcnote'],
                        'cb_src' => $valid['srctype']
                    );

                    if ($valid['srctype'] == 1)
                        $cbData['cb_src_inc'] = $valid['src_income'];

                    if ($valid['srctype'] == 2 || $valid['srctype'] == 3)
                        $cbData['cb_src_det'] = $valid['src_details'];

                    $debit = $cashBookObj->add($cbData);

                    if ($debit) {

                        require_once __DIR__ . '/../admin/!model/cashflow.php';
                        $cashFlow = new cashflow();

                        $cashFlow->deleteByCashBook(array(
                            'cf_cb_id' => $debit
                        ));

                        foreach ($mfields as $i) {
                            $data = array();

                            if ($valid['date'][$i] != '') {

                                $dt = DateTime::createFromFormat(DF_DD, $valid['date'][$i]);
                                $dt = date_format($dt, DFS_DB);

                                $data = array(
                                    'cf_cb_id' => $debit,
                                    'cf_amount' => $valid['amount'][$i] . '.' . $valid['baisa'][$i],
                                    'cf_note' => $valid['note'][$i],
                                    'cf_dttime' => $dt
                                );

                                if ($valid['assign'][$i])
                                    $data['cf_assigned'] = $valid['assign'][$i];

                                $cflowdet = $cashFlow->add($data);

                                $totalCredit += (float) $valid['amount'][$i] . '.' . $valid['baisa'][$i];
                            }
                        }

                        $debitUpdate = $cashBookObj->modify(array(
                            'cb_credit' => $totalCredit
                        ), $debit);

                        if ($debitUpdate) {
                            $feedback = 'Debit details added successfully';
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
        }

         $this->view ->form =  $form;
         $this->view ->mfields  = $mfields;
    }

    public function fviewAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/collection.php';

        $decId = $this->view->decode($this->view->param['ref']);

        if (! $decId)
            die('tampered');

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();

        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();
        $cashBookDet = $cashBookObj->getCashBookDetByCbId(array(
            'cb_id' => $decId
        ));
        $cashFlowDet = $cashFlow->getCashFlowByCasbookId(array(
            'cf_cb_id' => $decId
        ));

        $this->view->cashBookDet = $cashBookDet;
        $this->view->cashFlowDet = $cashFlowDet;
    }

    public function fviewminiAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/item.php';
        require_once __DIR__ . '/../admin/!model/collection.php';

        $decId = $this->view->decode($this->view->param['ref']);

        if (! $decId)
            die('tampered');

        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();

        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();
        $cashBookDet = $cashBookObj->getCashBookDetByCbId(array(
            'cb_id' => $decId
        ));
        $cashFlowDet = $cashFlow->getCashFlowByCasbookId(array(
            'cf_cb_id' => $decId
        ));

        $encCbId = $this->view->encode($decId);

        $this->view->encCbId = $encCbId;
        $this->view->cashBookDet = $cashBookDet;
        $this->view->cashFlowDet = $cashFlowDet;
    }


    public function toggleAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        

        $form = new form();

        $decCfId = $this->view->decode($this->view->param['ref']);

        $cashFlowDet = $cashFlow->getCashFlowById($decCfId);

        if (! $decCfId)
            die('tampered');
        $form->addElement('status', 'Status', 'radio', 'required', array(
            'options' => array(
                "1" => "No",
                "2" => "Yes"
            )
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'cf_approve_time' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'cf_approve' => $valid['status'],
                        'cf_approve_by' => USER_ID
                    );
                    $update = $cashFlow->modify($data, $decCfId);
                    if ($update) {
                        $feedback = 'Debit details added successfully';
                        $this->view->NoViewRender = true;
                        $success = array(
                            'feedback' => $feedback
                        );
                        $success = json_encode($success);
                        die($success);
                    }
                }
            }
        } else {
            $form->status->setValue($cashFlowDet['cf_approve']);
        }

        $cashFlowDetB = $cashFlow->getCashFlowReferenceCasbookId(array(
            'cf_id' => $decCfId
        ));

        $this->view->cashFlowDetB = $cashFlowDetB;
        $this->view->cashFlowDet = $cashFlowDet;
        $this->view ->form =  $form;
    }
}
