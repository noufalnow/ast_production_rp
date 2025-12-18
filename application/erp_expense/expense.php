<?php

class expenseController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        $form = new form();
        require_once __DIR__ . '/../admin/!model/documents.php';
        // $docs = new documets();
        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $form->addElement('vendor', 'Add New Vendor', 'text', '');
        $venderList = $vendorObj->getVendorPairFilter();
        $venderList["-1"] = "-- Add Vendor--";
        $form->addElement('selVendor', 'Vendor', 'select', 'required', array(
            'options' => $venderList
        ));
        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $form->addElement('client', 'Clients', 'select', 'required', [
            'options' => $customerList
        ], array(
            '' => 'onchange="getJaxData($(\'#client\').val(),\'project\',\'getlive\',\'projects\',true);"'
        ));

        $form->addElement('project', 'Project', 'select', 'required', array(
            'options' => []
        ));
        $form->addElement('pCategory', 'Add New Parent Category', 'text', 'alpha_space');
        $form->addElement('sCategory', 'Add New Sub Category ', 'text', 'alpha_space');
        $form->addElement('cCategory', 'Add New Child Category ', 'text', 'alpha_space');
        $form->addElement('particulers', 'Particulers', 'textarea', 'required');
        $form->addElement('amount', 'Total Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addElement('cbamount', 'CB Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));

        $form->addElement('vatoption', 'VAT', 'checkbox', '', array(
            'options' => array(
                "1" => "Vat"
            )
        ), array(
            "" => "onClick='enableVat(this)'"
        ));
        $form->addElement('vatamount', 'VAT Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));

        $form->addElement('remarks', 'Remarks', 'text', 'alpha_space');
        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowList = $cashFlow->getCashFlowPair();
        $form->addElement('cashFlow', 'Cash Flow', 'select', '', array(
            'options' => $cashFlowList
        ));

        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehModelObj = new vehicle();
        $vehList = $vehModelObj->getVehiclePair();
        // $form->addElement ( 'employee', 'Employee', 'select','',array('options'=>$empList));
        // $form->addElement ( 'property', 'Property', 'select','',array('options'=>$propList));
        // $form->addElement ( 'vehicle', 'Vehicle', 'select','',array('options'=>$vehList));
        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));
        $pCatList["-1"] = "--Add New Parent Category--";
        $sCatList["-1"] = "--Add New Sub Category--";
        $cCatList["-1"] = "--Add New Child Category--";

        $form->addElement('pCatSelect', 'Parent Category', 'select', 'required', array(
            'options' => $pCatList
        ));
        $form->addElement('sCatSelect', 'Sub Category', 'select', 'required', array(
            'options' => $sCatList
        ));
        $form->addElement('cCatSelect', 'Child Category', 'select', 'required', array(
            'options' => $cCatList
        ));
        $form->addElement('mainhead', 'Main Head', 'select', 'required', array(
            'options' => array(
                1 => "Employee",
                3 => "Vehicle",
                4 => "Others"
            )
        ));
        $form->addElement('billdt', 'Bill Date', 'text', 'date|required', '', array(
            'class' => 'date_picker',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('paydtption', 'Date/Days', 'radio', '', array(
            'options' => array(
                1 => "Date",
                2 => "Days"
            )
        ));
        $form->addElement('paydays', 'Days', 'number', '', '', array(
            'class' => 'fig'
        ));
        $form->addElement('payby', 'Pay by Date', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('paymod', 'Cash/Credit', 'radio', 'required', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit"
            )
        ));
        $form->addElement('refno', 'Reference No ', 'text', 'required');
        $form->addElement('percb', 'Personal Cash Book', 'checkbox', '', array(
            'options' => array(
                1 => "Personnal Cash Book"
            )
        ));
        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = max(array_keys($_POST['employee']), array_keys($_POST['vehicle']));
        }
        $form->addMultiElement('employee', 'Employee', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select'
        ), $count);

        $form->addMultiElement('vehicle', 'Vehicle', 'select', '', array(
            'options' => $vehList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('mamount', 'Amount', 'float', 'numeric', '', array(
            'class' => ''
        ), $count);
        $mfields = array_keys($form->_elements['employee']);
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        if (isset($_POST) && count($_POST) > 0) {
            if ($_POST['selVendor'] == '-1')
                $form->addRules('vendor', 'required');
            if ($_POST['pCatSelect'] == '-1')
                $form->addRules('pCategory', 'required|alpha_space');
            if ($_POST['sCatSelect'] == '-1')
                $form->addRules('sCategory', 'required|alpha_space');
            if ($_POST['cCatSelect'] == '-1')
                $form->addRules('cCategory', 'required|alpha_space');
            if ($_POST['paymod'] == '2') {
                $form->addRules('paydtption', 'required');
                if ($_POST['paydtption'] == '1')
                    $form->addRules('payby', 'required');
                elseif ($_POST['paydtption'] == '2')
                    $form->addRules('paydays', 'required');
            }

            if ($_POST['vatoption'] == '1') {
                $form->addRules('vatamount', 'required');
            }

            $form->addErrorMsg('mamount', 'required', ' ');
            foreach ($mfields as $i) {
                if ($_POST['mainhead'] == 1) {
                    if ($_POST['employee'][$i] != '' && $_POST['mamount'][$i] == '')
                        $form->addmRules('mamount', $i, 'numeric|required');
                    if ($_POST['mamount'][$i] != '' && $_POST['employee'][$i] == '')
                        $form->addmRules('employee', $i, 'required');
                }
                if ($_POST['mainhead'] == 3) {
                    if ($_POST['vehicle'][$i] != '' && $_POST['mamount'][$i] == '')
                        $form->addmRules('mamount', $i, 'numeric|required');
                    if ($_POST['mamount'][$i] != '' && $_POST['vehicle'][$i] == '')
                        $form->addmRules('vehicle', $i, 'required');
                }
            }
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                // s($valid); d(lllllllllllllllllllllllllllllllllllllll);
                if ($valid['mainhead'] == 1)
                    $refData = $valid['employee'];
                else if ($valid['mainhead'] == 3)
                    $refData = $valid['vehicle'];
                require_once __DIR__ . '/../admin/!model/expense.php';
                $expenseObj = new expense();
                ;
                if ($valid['selVendor'] == - 1 && $valid['vendor'] != '') {
                    $vendorDet = $vendorObj->getVendorByName(array(
                        'ven_name' => $valid['vendor']
                    ));
                    if (! $vendorDet['ven_id']) {
                        $venData = array(
                            'ven_name' => $valid['vendor'],
                            'ven_disp_name' => $valid['vendor']
                        );
                        $vId = $vendorObj->add($venData);
                    } else
                        $vId = $vendorDet['ven_id'];
                } else
                    $vId = $valid['selVendor'];
                if ($valid['pCatSelect'] == - 1 && $valid['pCategory'] != '') {
                    $pCatDet = $catModelObj->getCategoryByName(array(
                        'cat_name' => $valid['pCategory'],
                        'cat_type' => 1
                    ));
                    if (! $pCatDet['cat_id']) {
                        $catData = array(
                            'cat_name' => $valid['pCategory'],
                            'cat_type' => 1
                        );
                        $cpId = $catModelObj->add($catData);
                    } else
                        $cpId = $pCatDet['cat_id'];
                } else
                    $cpId = $valid['pCatSelect'];
                if ($valid['sCatSelect'] == - 1 && $valid['sCategory'] != '') {
                    $sCatDet = $catModelObj->getCategoryByName(array(
                        'cat_name' => $valid['sCategory'],
                        'cat_type' => 2,
                        'cat_parent' => $cpId
                    ));
                    if (! $sCatDet['cat_id']) {
                        $catData = array(
                            'cat_name' => $valid['sCategory'],
                            'cat_type' => 2,
                            'cat_parent' => $cpId
                        );
                        $csId = $catModelObj->add($catData);
                    } else
                        $csId = $sCatDet['cat_id'];
                } else
                    $csId = $valid['sCatSelect'];
                if ($valid['cCatSelect'] == - 1 && $valid['cCategory'] != '') {
                    $cCatDet = $catModelObj->getCategoryByName(array(
                        'cat_name' => $valid['cCategory'],
                        'cat_type' => 3,
                        'cat_parent' => $csId
                    ));
                    if (! $cCatDet['cat_id']) {
                        $catData = array(
                            'cat_name' => $valid['cCategory'],
                            'cat_type' => 3,
                            'cat_parent' => $csId
                        );
                        $ccId = $catModelObj->add($catData);
                    } else
                        $ccId = $cCatDet['cat_id'];
                } else
                    $ccId = $valid['cCatSelect'];

                require_once __DIR__ . '/../admin/!model/expense.php';
                $expenseObj = new expense();
                $empVendor = $expenseObj->getExpenseByVendorAndRefNo(array(
                    'exp_vendor' => $vId,
                    'exp_refno' => $valid['refno']
                ));

                if ($empVendor['ref_count'] > 0) {
                    $form->refno->setError("Reference no already selected for the vendor");
                } else {

                    $billdt = DateTime::createFromFormat(DF_DD, $valid['billdt']);
                    $billdt = date_format($billdt, DFS_DB);

                    if ($valid['vatoption'] == 0) {
                        $valid['vatamount'] = 0;
                    }

                    $data = array(
                        'exp_vendor' => $vId,
                        'exp_refno' => $valid['refno'],
                        'exp_client_id' => $valid['client'],
                        'exp_project_id' => $valid['project'],
                        'exp_company' => 1,
                        'exp_mainh' => $valid['mainhead'],
                        'exp_pcat' => $cpId,
                        'exp_scat' => $csId,
                        'exp_ccat' => $ccId,
                        'exp_billdt' => $billdt,
                        'exp_pay_mode' => $valid['paymod'],
                        'exp_details' => $valid['particulers'],
                        'exp_amount' => $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount']),
                        'exp_novat_amt' => $valid['amount'],
                        'exp_pstatus' => $valid['paymod'],
                        'exp_oribill_amt' => $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount'])
                    );
                    if ($valid['paymod'] == 2)
                        $data['exp_credit_amt'] = $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount']);
                    else
                        $data['exp_credit_amt'] = NULL;
                    if ($valid['paydtption'] == 1) {
                        $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                        $paydate = date_format($paydate, DFS_DB);
                        $data['exp_paydate'] = $paydate;
                    } else
                        $data['exp_paydate'] = NULL;
                    if ($valid['paydtption'] == 2)
                        $data['exp_paydays'] = $valid['paydays'];
                    else
                        $data['exp_paydays'] = NULL;
                    if ($valid['cashFlow'])
                        $data['exp_cash_flow'] = $valid['cashFlow'];

                    if ($valid['vatoption'] == 1) {
                        $data['exp_vat_amt'] = $valid['vatamount'];
                        $data['exp_vat_option'] = $valid['vatoption'];
                    } else {
                        $data['exp_vat_amt'] = NULL;
                        $data['exp_vat_option'] = NULL;
                    }

                    require_once __DIR__ . '/../admin/!model/expensemhref.php';
                    $exprefObj = new expensemhref();
                    $insert = $expenseObj->add($data);
                    if ($insert) {
                        $this->view->feedback = 'Expense details added successfully';
                        $refData = array_values($refData);
                        if (count($refData) > 0)
                            foreach ($refData as $rfkey => $rData) {
                                $data = array();
                                if ($rData) {
                                    $data = array(
                                        'eref_exp_id' => $insert,
                                        'eref_main_head' => $valid['mainhead'],
                                        'eref_main_head_ref' => $rData,
                                        'eref_amount' => $valid['mamount'][$rfkey] == '' ? 0 : $valid['mamount'][$rfkey]
                                    );
                                    $det = $exprefObj->add($data);
                                }
                            }
                        // a($valid);
                        if ($valid['percb'] == 1) {
                            require_once __DIR__ . '/../admin/!model/cashbook.php';
                            $cashBookObj = new cashbook();
                            $cbData = array(
                                'cb_type' => CASH_BOOK_PER,
                                'cb_type_ref' => USER_ID,
                                'cb_exp_id' => $insert,
                                'cb_credit' => $valid['cbamount'] != '' ? $valid['cbamount'] : $valid['amount'],
                                'cb_date' => $billdt
                            );
                            $cashBookObj->add($cbData);
                        }
                        if ($valid['my_files']) {
                            $upload = uploadFiles(DOC_TYPE_EXP, $insert, $valid['my_files']);
                            if ($upload) {
                                $form->reset();
                                if ($upload) {
                                    $this->view->feedback = 'Expense details added successfully';
                                } else {
                                    $this->view->feedback = 'Expense details added successfully;Unable to upload file';
                                }
                            }
                        }
                        $this->view->NoViewRender = true;
                    }
                }
            } else {
                if (! empty($_POST['client'])) {
                    require_once __DIR__ . '/../admin/!model/property.php';
                    $propModelObj = new property();

                    $projectList = $propModelObj->getProjectsPair([
                        'project_client_id' => $_POST['client']
                    ]);
                    $form->project->setOptions($projectList);
                }
            }
        }
        $this->view->mfields = $mfields;
        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        $form = new form();
        /* EDIT EXPENSE */
        $editIDS = array(
            '47651'
        );
        $decExpId = $this->view->decode($this->view->param['ref']);
        if (! $decExpId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expenseObj = new expense();
        $expDet = $expenseObj->getExpenseDet(array(
            'exp_id' => $decExpId
        ));
        $encFileId = $this->view->encode($expDet['file_id']);
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $form->addElement('vendor', 'Add New Vendor', 'text', '');
        $venderList = $vendorObj->getVendorPairFilter();
        $venderList["-1"] = "-- Add Vendor--";
        $form->addElement('selVendor', 'Vendor', 'select', 'required', array(
            'options' => $venderList
        ));
        require_once __DIR__ . '/../admin/!model/customer.php';
        $customerObj = new customer();
        $customerList = $customerObj->getCustomerPair();
        $form->addElement('client', 'Clients', 'select', 'required', [
            'options' => $customerList
        ], array(
            '' => 'onchange="getJaxData($(\'#client\').val(),\'project\',\'getlive\',\'projects\',true);"'
        ));
        
        $form->addElement('project', 'Project', 'select', 'required', array(
            'options' => []
        ));

        // $projectsObj = new property();
        // $customerList = $projectsObj->getProjectsPair();

        $form->addElement('pCategory', 'Add New Parent Category', 'text', 'alpha_space');
        $form->addElement('sCategory', 'Add New Sub Category ', 'text', 'alpha_space');
        $form->addElement('cCategory', 'Add New Child Category ', 'text', 'alpha_space');
        $form->addElement('particulers', 'Particulers', 'textarea', 'required');
        $form->addElement('amount', 'Total Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addElement('cbamount', 'CB Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));

        $form->addElement('vatoption', 'VAT', 'checkbox', '', array(
            'options' => array(
                "1" => "Vat"
            )
        ), array(
            "" => "onClick='enableVat(this)'"
        ));
        $form->addElement('vatamount', 'VAT Amount', 'float', 'numeric', '', array(
            'class' => 'fig'
        ));

        $form->addElement('remarks', 'Remarks', 'text', 'alpha_space');
        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowList = $cashFlow->getCashFlowPair();
        $form->addElement('cashFlow', 'Cash Flow', 'select', '', array(
            'options' => $cashFlowList
        ));
        require_once __DIR__ . '/../admin/!model/employee.php';
        $empModelObj = new employee();
        $empList = $empModelObj->getEmployeePair();
        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $vehModelObj = new vehicle();
        $vehList = $vehModelObj->getVehiclePair();
        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));
        $pCatList["-1"] = "--Add New Parent Category--";
        $sCatList["-1"] = "--Add New Sub Category--";
        $cCatList["-1"] = "--Add New Child Category--";

        $form->addElement('pCatSelect', 'Parent Category', 'select', 'required', array(
            'options' => $pCatList
        ));
        $form->addElement('sCatSelect', 'Sub Category', 'select', 'required', array(
            'options' => $sCatList
        ));
        $form->addElement('cCatSelect', 'Child Category', 'select', 'required', array(
            'options' => $cCatList
        ));
        $form->addElement('mainhead', 'Main Head', 'select', 'required', array(
            'options' => array(
                1 => "Employee",
                3 => "Vehicle",
                4 => "Others"            )
        ));
        $form->addElement('billdt', 'Bill Date', 'text', 'date|required', '', array(
            'class' => 'date_picker',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('paydtption', 'Date/Days', 'radio', '', array(
            'options' => array(
                1 => "Date",
                2 => "Days"
            )
        ));
        $form->addElement('paydays', 'Days', 'number', '', '', array(
            'class' => 'fig'
        ));
        $form->addElement('payby', 'Pay by Date', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $modeOPtion = array(
            1 => array(
                1 => "Cash"
            ),
            2 => array(
                2 => "Credit"
            )
        ); // disalbed the changing of cash mode to credit and vice versa
        $form->addElement('paymod', 'Cash/Credit', 'radio', 'required', array(
            'options' => $modeOPtion[$expDet['exp_pay_mode']]
        ));
        $form->addElement('refno', 'Reference No ', 'text', 'required');
        $form->addElement('docUpdate', 'Update Document', 'checkbox', '', array(
            'options' => array(
                1 => "Update Document"
            )
        ));

        require_once __DIR__ . '/../admin/!model/expensemhref.php';
        $exprefObj = new expensemhref();
        $count = 1;
        if (isset($_POST) && count($_POST) > 0) {
            $count = max(array_keys($_POST['employee']), array_keys($_POST['property']), array_keys($_POST['vehicle']));
        } else {
            $expRefDetails = $exprefObj->getExpRefIdRef(array(
                'eref_exp_id' => $decExpId,
                'eref_status' => 1
            ));
            $expRefDetailsAmount = $exprefObj->getExpRefDetAmount(array(
                'eref_exp_id' => $decExpId,
                'eref_status' => 1
            ));
            $count = count($expRefDetails) == 0 ? 1 : count($expRefDetails);
        }
        $form->addMultiElement('employee', 'Employee', 'select', '', array(
            'options' => $empList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('property', 'Property', 'select', '', array(
            'options' => $propList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('vehicle', 'Vehicle', 'select', '', array(
            'options' => $vehList
        ), array(
            'class' => 'full-select'
        ), $count);
        $form->addMultiElement('mamount', 'Amount', 'float', 'numeric', '', array(
            'class' => ''
        ), $count);
        $mfields = array_keys($form->_elements['employee']);
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 4097152
        ));
        $form->addElement('percb', 'Personal Cash Book', 'checkbox', '', array(
            'options' => array(
                1 => "Personnal Cash Book"
            )
        ));

        if (isset($_POST) && count($_POST) > 0) {
            if ($expDet['exp_app_status'] == '' || in_array($expDet['exp_id'], $editIDS)) {
                if ($_POST['selVendor'] == '-1')
                    $form->addRules('vendor', 'required');
                if ($_POST['pCatSelect'] == '-1')
                    $form->addRules('pCategory', 'required|alpha_space');
                if ($_POST['sCatSelect'] == '-1')
                    $form->addRules('sCategory', 'required|alpha_space');
                if ($_POST['cCatSelect'] == '-1')
                    $form->addRules('cCategory', 'required|alpha_space');
                if ($_POST['paymod'] == '2') {
                    $form->addRules('paydtption', 'required');
                    if ($_POST['paydtption'] == '1')
                        $form->addRules('payby', 'required');
                    elseif ($_POST['paydtption'] == '2')
                        $form->addRules('paydays', 'required');
                }
                if ($_POST['docUpdate'] == '1')
                    $form->addFile('my_files', 'Document', array(
                        'required' => true,
                        'exten' => 'pdf',
                        'size' => 4097152
                    ));
                $form->addErrorMsg('mamount', 'required', ' ');
                foreach ($mfields as $i) {
                    if ($_POST['mainhead'] == 1) {
                        if ($_POST['employee'][$i] != '' && $_POST['mamount'][$i] == '')
                            $form->addmRules('mamount', $i, 'numeric|required');
                        if ($_POST['mamount'][$i] != '' && $_POST['employee'][$i] == '')
                            $form->addmRules('employee', $i, 'required');
                    }
                    if ($_POST['mainhead'] == 2) {
                        if ($_POST['property'][$i] != '' && $_POST['mamount'][$i] == '')
                            $form->addmRules('mamount', $i, 'numeric|required');
                        if ($_POST['mamount'][$i] != '' && $_POST['property'][$i] == '')
                            $form->addmRules('property', $i, 'required');
                    }
                    if ($_POST['mainhead'] == 3) {
                        if ($_POST['vehicle'][$i] != '' && $_POST['mamount'][$i] == '')
                            $form->addmRules('mamount', $i, 'numeric|required');
                        if ($_POST['mamount'][$i] != '' && $_POST['vehicle'][$i] == '')
                            $form->addmRules('vehicle', $i, 'required');
                    }
                }

                if ($_POST['vatoption'] == '1') {
                    $form->addRules('vatamount', 'required');
                }

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    if ($valid['mainhead'] == 1)
                        $refData = $valid['employee'];
                    else if ($valid['mainhead'] == 2)
                        $refData = $valid['property'];
                    else if ($valid['mainhead'] == 3)
                        $refData = $valid['vehicle'];
                    if ($valid['selVendor'] == - 1 && $valid['vendor'] != '') {
                        $vendorDet = $vendorObj->getVendorByName(array(
                            'ven_name' => $valid['vendor']
                        ));
                        if (! $vendorDet['ven_id']) {
                            $venData = array(
                                'ven_name' => $valid['vendor'],
                                'ven_disp_name' => $valid['vendor']
                            );
                            $vId = $vendorObj->add($venData);
                        } else
                            $vId = $vendorDet['ven_id'];
                    } else
                        $vId = $valid['selVendor'];
                    if ($valid['pCatSelect'] == - 1 && $valid['pCategory'] != '') {
                        $pCatDet = $catModelObj->getCategoryByName(array(
                            'cat_name' => $valid['pCategory'],
                            'cat_type' => 1
                        ));
                        if (! $pCatDet['cat_id']) {
                            $catData = array(
                                'cat_name' => $valid['pCategory'],
                                'cat_type' => 1
                            );
                            $cpId = $catModelObj->add($catData);
                        } else
                            $cpId = $pCatDet['cat_id'];
                    } else
                        $cpId = $valid['pCatSelect'];
                    if ($valid['sCatSelect'] == - 1 && $valid['sCategory'] != '') {
                        $sCatDet = $catModelObj->getCategoryByName(array(
                            'cat_name' => $valid['sCategory'],
                            'cat_type' => 2,
                            'cat_parent' => $cpId
                        ));
                        if (! $sCatDet['cat_id']) {
                            $catData = array(
                                'cat_name' => $valid['sCategory'],
                                'cat_type' => 2,
                                'cat_parent' => $cpId
                            );
                            $csId = $catModelObj->add($catData);
                        } else
                            $csId = $sCatDet['cat_id'];
                    } else
                        $csId = $valid['sCatSelect'];
                    if ($valid['cCatSelect'] == - 1 && $valid['cCategory'] != '') {
                        $cCatDet = $catModelObj->getCategoryByName(array(
                            'cat_name' => $valid['cCategory'],
                            'cat_type' => 3,
                            'cat_parent' => $csId
                        ));
                        if (! $cCatDet['cat_id']) {
                            $catData = array(
                                'cat_name' => $valid['cCategory'],
                                'cat_type' => 3,
                                'cat_parent' => $csId
                            );
                            $ccId = $catModelObj->add($catData);
                        } else
                            $ccId = $cCatDet['cat_id'];
                    } else
                        $ccId = $valid['cCatSelect'];

                    // require_once __DIR__ . '/../admin/!model/expense.php';
                    $expenseVenObj = new expense();
                    $empVendor = $expenseVenObj->getExpenseByVendorAndRefNo(array(
                        'exp_vendor' => $vId,
                        'exp_refno' => $valid['refno'],
                        'exclude' => $decExpId
                    ));

                    if ($empVendor['ref_count'] > 0) {
                        $form->refno->setError("Reference no already selected for the vendor");
                    } else {

                        $billdt = DateTime::createFromFormat(DF_DD, $valid['billdt']);
                        $billdt = date_format($billdt, DFS_DB);

                        if ($valid['vatoption'] == 0) {
                            $valid['vatamount'] = 0;
                        }

                        $data = array(
                            'exp_vendor' => $vId,
                            'exp_client_id' => $valid['client'],
                            'exp_project_id' => $valid['project'],
                            'exp_refno' => $valid['refno'],
                            'exp_mainh' => $valid['mainhead'],
                            'exp_pcat' => $cpId,
                            'exp_scat' => $csId,
                            'exp_ccat' => $ccId,
                            'exp_billdt' => $billdt,
                            'exp_pay_mode' => $valid['paymod'],
                            'exp_details' => $valid['particulers'],
                            'exp_amount' => $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount']),
                            'exp_novat_amt' => $valid['amount'],
                            'exp_pstatus' => $valid['paymod'],
                            'exp_oribill_amt' => $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount'])
                        );
                        if ($valid['paymod'] == 2)
                            $data['exp_credit_amt'] = $valid['amount'] + ($valid['vatamount'] == '' ? 0 : $valid['vatamount']);
                        else
                            $data['exp_credit_amt'] = NULL;
                        if (! empty($valid['payby'])) {
                            $paydate = DateTime::createFromFormat(DF_DD, $valid['payby']);
                            $paydate = date_format($paydate, DFS_DB);
                        }
                        if ($valid['paydtption'] == 1)
                            $data['exp_paydate'] = $paydate;
                        else
                            $data['exp_paydate'] = NULL;
                        if ($valid['paydtption'] == 2)
                            $data['exp_paydays'] = $valid['paydays'];
                        else
                            $data['exp_paydays'] = NULL;
                        if ($valid['cashFlow'])
                            $data['exp_cash_flow'] = $valid['cashFlow'];
                        else
                            $data['exp_cash_flow'] = NULL;

                        if ($valid['vatoption'] == 1) {
                            $data['exp_vat_amt'] = $valid['vatamount'];
                            $data['exp_vat_option'] = $valid['vatoption'];
                        } else {
                            $data['exp_vat_amt'] = NULL;
                            $data['exp_vat_option'] = NULL;
                        }

                        $update = $expenseObj->modify($data, $decExpId);
                        if ($update) {
                            $exprefObj->deleteExpRefByExpId(array(
                                'eref_exp_id' => $decExpId
                            ));
                            $refData = array_values($refData);
                            if (count($refData) > 0)
                                foreach ($refData as $rfkey => $rData) {
                                    $data = array();
                                    if ($rData) {
                                        $data = array(
                                            'eref_exp_id' => $decExpId,
                                            'eref_main_head' => $valid['mainhead'],
                                            'eref_main_head_ref' => $rData,
                                            'eref_amount' => $valid['mamount'][$rfkey]
                                        );
                                        $det = $exprefObj->add($data);
                                    }
                                }
                            require_once __DIR__ . '/../admin/!model/cashbook.php';
                            $cashBookObj = new cashbook();
                            $cbData = array(
                                'cb_type' => CASH_BOOK_PER,
                                'cb_type_ref' => USER_ID,
                                'cb_exp_id' => $decExpId,
                                'cb_credit' => $valid['cbamount'] != '' ? $valid['cbamount'] : $valid['amount'],
                                'cb_date' => $billdt
                            );
                            if ($valid['percb'] == 1 && $expDet['cb_id'] != '' && $expDet['cb_type_ref'] == USER_ID) {
                                $cashBookObj->modify($cbData, $expDet['cb_id']);
                            } elseif ($valid['percb'] == 1 && $expDet['cb_id'] == '') {
                                $cashBookObj->add($cbData);
                            }
                            if ($valid['percb'] == '' && $expDet['cb_id'] != '' && $expDet['cb_type_ref'] == USER_ID) {
                                $cashBookObj->deleteCashBook($expDet['cb_id']);
                            }

                            $this->view->feedback = 'Expense details Updated successfully';

                            if ($valid['docUpdate'] == 1) {
                                if (! empty($expDet['file_id'])) {
                                    $file = new files();
                                    deleteFile($expDet['file_id']);
                                    $file->deleteFile($expDet['file_id']);
                                }
                                $upload = uploadFiles(DOC_TYPE_EXP, $decExpId, $valid['my_files']);
                                $form->reset();
                                if ($upload) {
                                    $this->view->feedback = 'Expense details added successfully';
                                } else {
                                    $this->view->feedback = 'Expense details added successfully;Unable to upload file';
                                }
                            }
                            $this->view->NoViewRender = true;
                        }
                    }
                }else{
                    if (! empty($_POST['client'])) {
                        require_once __DIR__ . '/../admin/!model/property.php';
                        $propModelObj = new property();
                        
                        $projectList = $propModelObj->getProjectsPair([
                            'project_client_id' => $_POST['client']
                        ]);
                        $form->project->setOptions($projectList);
                    }
                }
            }
        } else {
            
            
            require_once __DIR__ . '/../admin/!model/property.php';
            $propModelObj = new property();
            
            $projectList = $propModelObj->getProjectsPair([
                'project_client_id' => $expDet['exp_client_id']
            ]);
            $form->project->setOptions($projectList);
            
            
            
            if ($expDet['exp_paydate']) {
                $pbd = DateTime::createFromFormat(DFS_DB, $expDet['exp_paydate']);
                $pbd = $pbd->format(DF_DD);
            }
            if ($expDet['exp_billdt']) {
                $billDt = DateTime::createFromFormat(DFS_DB, $expDet['exp_billdt']);
                $billDt = $billDt->format(DF_DD);
            }
            
            $form->client->setValue($expDet['exp_client_id']);
            $form->project->setValue($expDet['exp_project_id']);
            
            $form->selVendor->setValue($expDet['exp_vendor']);
            $form->refno->setValue($expDet['exp_refno']);
            $form->mainhead->setValue($expDet['exp_mainh']);
            $form->pCatSelect->setValue($expDet['exp_pcat']);
            $form->sCatSelect->setValue($expDet['exp_scat']);
            $form->cCatSelect->setValue($expDet['exp_ccat']);
            $form->particulers->setValue($expDet['exp_details']);
            $form->amount->setValue($expDet['exp_novat_amt']);
            $form->paymod->setValue($expDet['exp_pay_mode']);

            $form->vatamount->setValue($expDet['exp_vat_amt']);
            $form->vatoption->setValue($expDet['exp_vat_option']);

            $form->payby->setValue($pbd);
            $form->billdt->setValue($billDt);
            $form->paydays->setValue($expDet['exp_paydays']);
            $form->cashFlow->setValue($expDet['exp_cash_flow']);
            if ($expDet['cb_id'] != '') {
                $form->percb->setValue(1);
                $form->cbamount->setValue($expDet['cb_credit']);
            }
            if ($expDet['exp_paydate'])
                $form->paydtption->setValue(1);
            else
                $form->paydtption->setValue(2);
            $i = 0;
            foreach ($expRefDetails as $key => $fref) {
                if ($expDet['exp_mainh'] == 1)
                    $form->employee[$i]->setValue($fref);
                else if ($expDet['exp_mainh'] == 2)
                    $form->property[$i]->setValue($fref);
                else if ($expDet['exp_mainh'] == 3)
                    $form->vehicle[$i]->setValue($fref);
                $form->mamount[$i]->setValue($expRefDetailsAmount[$key]);
                $i ++;
            }
        }
        $this->view->editIDS = $editIDS;
        $this->view->encFileId = $encFileId;
        $this->view->expDet = $expDet;
        $this->view->mfields = $mfields;
        $this->view->form = $form;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/expense.php';
        $form = new form();
        $form->addElement('f_refno', 'Ref No', 'text', '');
        $form->addElement('f_particulers', 'Particulers', 'text', '');
        /* EDIT EXPENSE */
        $editIDS = array(
            '47651'
        );
        require_once __DIR__ . '/../admin/!model/vendor.php';
        $vendorObj = new vendor();
        $venderList = $vendorObj->getVendorPairFilter();
        $form->addElement('f_selVendor', 'Vendor', 'select', '', array(
            'options' => $venderList
        ));
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));
        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));
        $form->addElement('f_pCatSelect', 'Parent Cat', 'select', '', array(
            'options' => $pCatList
        ));
        $form->addElement('f_sCatSelect', 'Sub Cat', 'select', '', array(
            'options' => $sCatList
        ));
        $form->addElement('f_cCatSelect', 'Category', 'select', '', array(
            'options' => $cCatList
        ));
        $form->addElement('f_mainhead', 'Head', 'select', '', array(
            'options' => array(
                1 => "Employee",
                2 => "Property",
                3 => "Vehicle",
                4 => "Port Operation"
            )
        ));
        $form->addElement('f_mode', 'Mode', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Credit"
            )
        ));
        $form->addElement('f_status', 'Status', 'select', '', array(
            'options' => array(
                "2" => "Pending",
                1 => "Approved"
            )
        ));
        $form->addElement('f_expid', 'File No', 'number', '');
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
                    'f_refno' => @$valid['f_refno'],
                    'f_particulers' => @$valid['f_particulers'],
                    'f_selVendor' => @$valid['f_selVendor'],
                    'f_company' => @$valid['f_company'],
                    'f_mainhead' => @$valid['f_mainhead'],
                    'f_pCatSelect' => @$valid['f_pCatSelect'],
                    'f_sCatSelect' => @$valid['f_sCatSelect'],
                    'f_cCatSelect' => @$valid['f_cCatSelect'],
                    'f_mode' => @$valid['f_mode'],
                    'f_expid' => @$valid['f_expid'],
                    'f_status' => @$valid['f_status']
                );
            }
            $filter_class = 'btn-info';
        }
        $expObj = new expense();
        $expenseList = $expObj->geExpensePaginate(@$where);
        // /s($expenseList);
        $offset = $expObj->_voffset;
        $this->view->expenseList = $expenseList;
        $this->view->form = $form;
        $this->view->expObj = $expObj;
        $this->view->offset = $offset;
        $this->view->filter_class = $filter_class;
        $this->view->editIDS = $editIDS;
    }

    public function viewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/expense.php';
        require_once __DIR__ . '/../admin/!model/expenseupdate.php';
        $expenseObj = new expense();
        $expUpdate = new expupdate();
        require_once __DIR__ . '/../admin/!model/expensemhref.php';
        $exprefObj = new expensemhref();
        $decExpId = $this->view->decode($this->view->param['ref']);
        if (! $decExpId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/paymentdet.php';
        $payDet = new paymentdet();
        $paymentDetails = $payDet->getAllPaymentDetForExp(array(
            'pdet_status' => 2,
            'pdet_exp_id' => $decExpId
        ));
        $expenseDet = $expenseObj->getExpenseDetailsById(array(
            "exp_id" => $decExpId
        ));
        $expRefDetails = $exprefObj->getExpRefDetExtended(array(
            'eref_exp_id' => $decExpId,
            'eref_status' => 1
        ));
        $expUpDet = $expUpdate->getPaymentExpDet(array(
            'eup_exp_id' => $decExpId
        ));
        // s($expUpDet);
        $encFileId = $this->view->encode($expenseDet['file_id']);
        $encUpdFileId = $this->view->encode($expUpDet['file_id']);
        // s($expenseDet);
        $this->view->expenseDet = $expenseDet;
        $this->view->encFileId = $encFileId;
        $this->view->expRefDetails = $expRefDetails;
        $this->view->paymentDetails = $paymentDetails;
        $this->view->expUpDet = $expUpDet;
        $this->view->encUpdFileId = $encUpdFileId;
    }

    public function approvalAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expenseObj = new expense();
        $form = new form();
        $update = '';
        $decExpId = $this->view->decode($this->view->param['ref']);
        $expenseDet = $expenseObj->getExpenseById($decExpId);
        // s($expenseDet);
        $countDet = $expenseObj->getExpenseFileNo(array(
            'exp_pay_mode' => $expenseDet['exp_pay_mode'],
            'exp_id' => $decExpId
        ));
        $countDet = $countDet[0];
        // v("EXP" ."/". $decExpId ."/".($expenseDet['exp_pay_mode'] == 1 ? 'CH' : 'CR') ."/".$countDet ['type_count']);
        if (! $decExpId)
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
                    $expNo = "EXP" . "/" . $decExpId . "/" . ($expenseDet['exp_pay_mode'] == 1 ? 'CH' : 'CR') . "/" . $countDet['type_count'];
                    $data = array(
                        'exp_app_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'exp_app_status' => $valid['status'],
                        'exp_app_note' => $valid['note'],
                        'exp_file_no' => $expNo
                    );
                    $update = $expenseObj->modify($data, $decExpId);
                    if ($update) {
                        $expenseDet = $expenseObj->getExpenseById($decExpId);
                        /*
                         * $feedback = 'Expense status updated successfully';
                         * $success = array (
                         * 'feedback' => $feedback
                         * );
                         * $_SESSION ['feedback'] = $feedback;
                         * $success = json_encode ( $success );
                         * die ( $success );
                         */
                    }
                }
            }
        }
        /**
         * ********* if not update*********
         */
        if (! $update) {
            require_once __DIR__ . '/../admin/!model/expensemhref.php';
            $exprefObj = new expensemhref();
            require_once __DIR__ . '/../admin/!model/expenseupdate.php';
            $expUpdate = new expupdate();
            require_once __DIR__ . '/../admin/!model/paymentdet.php';
            $payDet = new paymentdet();
            $paymentDetails = $payDet->getAllPaymentDetForExp(array(
                'pdet_status' => 2,
                'pdet_exp_id' => $decExpId
            ));
            $expenseDet = $expenseObj->getExpenseDetailsById(array(
                "exp_id" => $decExpId
            ));
            $expRefDetails = $exprefObj->getExpRefDetExtended(array(
                'eref_exp_id' => $decExpId,
                'eref_status' => 1
            ));
            $expUpDet = $expUpdate->getPaymentExpDet(array(
                'eup_exp_id' => $decExpId
            ));
            // s($expUpDet);
            $encFileId = $this->view->encode($expenseDet['file_id']);
            $encUpdFileId = $this->view->encode($expUpDet['file_id']);
        }

        $this->view->update = $update;
        $this->view->form = $form;
        $this->view->paymentDetails = $paymentDetails;
        $this->view->expRefDetails = $expRefDetails;
        $this->view->encFileId = $encFileId;
        $this->view->encUpdFileId = $encUpdFileId;
        $this->view->expenseDet = $expenseDet;
        $this->view->expNo = $expNo;
        $this->view->expUpDet = $expUpDet;
    }

    public function cateditAction()
    {
        $this->view->response('ajax');
        $form = new form();
        $decExpId = $this->view->decode($this->view->param['ref']);
        if (! $decExpId)
            die('tampered');
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expenseObj = new expense();
        ;
        $expDet = $expenseObj->getExpenseDet(array(
            'exp_id' => $decExpId
        ));
        require_once __DIR__ . '/../admin/!model/category.php';
        $catModelObj = new category();
        $pCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 1
        ));
        $sCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 2
        ));
        $cCatList = $catModelObj->getCategoryPair(array(
            'cat_type' => 3
        ));
        unset($pCatList['-1']);
        $form->addElement('pCatSelect', 'Parent Category', 'select', 'required', array(
            'options' => $pCatList
        ));
        $form->addElement('sCatSelect', 'Sub Category', 'select', 'required', array(
            'options' => $sCatList
        ));
        $form->addElement('cCatSelect', 'Child Category', 'select', 'required', array(
            'options' => $cCatList
        ));
        $form->addElement('particulers', 'Particulers', 'textarea', 'required');
        require_once __DIR__ . '/../admin/!model/cashflow.php';
        $cashFlow = new cashflow();
        $cashFlowList = $cashFlow->getCashFlowPair();
        $form->addElement('cashFlow', 'Cash Flow', 'select', '', array(
            'options' => $cashFlowList
        ));
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $data = array(
                        'exp_pcat' => $valid['pCatSelect'],
                        'exp_scat' => $valid['sCatSelect'],
                        'exp_ccat' => $valid['cCatSelect'],
                        'exp_details' => $valid['particulers']
                    );
                    /*
                     * if($expDet['cf_approve']<>2){
                     * if ($valid ['cashFlow'])
                     * $data ['exp_cash_flow'] = $valid ['cashFlow'];
                     * else
                     * $data ['exp_cash_flow'] = NULL;
                     * }
                     */
                    $update = $expenseObj->modify($data, $decExpId);
                    if ($update) {
                        $feedback = 'Details Updated successfully';
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
            $form->pCatSelect->setValue($expDet['exp_pcat']);
            $form->sCatSelect->setValue($expDet['exp_scat']);
            $form->cCatSelect->setValue($expDet['exp_ccat']);
            $form->particulers->setValue($expDet['exp_details']);
            // $form->cashFlow->setValue($expDet['exp_cash_flow']);
        }
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/expense.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $expenseObj = new expense();
        require_once __DIR__ . '/../admin/!model/expensemhref.php';
        $exprefObj = new expensemhref();
        $formRender = true;
        $form = new form();
        $decExpId = $this->view->decode($this->view->param['ref']);
        $expenseDet = $expenseObj->getExpenseDetailsById(array(
            "exp_id" => $decExpId
        ));
        if (! $decExpId)
            die('tampered');
        $form->addElement('type', 'Update Type', 'select', 'required', array(
            'options' => array(
                "3" => "Cancellation",
                "4" => "Partial Return",
                "5" => "Full Return"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', 'required');
        $form->addElement('billdt', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('adjust', 'Cash Return', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $expRefDetails = $exprefObj->getExpRefDetExtended(array(
            'eref_exp_id' => $decExpId,
            'eref_status' => 1
        ));
        $expRefDetPair = $exprefObj->getExpRefDet(array(
            'eref_exp_id' => $decExpId,
            'eref_status' => 1
        ));
        $getExpRefIdRefPair = $exprefObj->getExpRefIdRef(array(
            'eref_exp_id' => $decExpId,
            'eref_status' => 1
        ));
        $form->addMultiElement('mamount', 'Amount', 'float', 'numeric', '', array(
            'class' => ''
        ), $expRefDetPair);
        $mfields = [];
        if (is_array($form->_elements['mamount']))
            $mfields = array_keys($form->_elements['mamount']);
        if (isset($_POST) && count($_POST) > 0) {
            $form->addErrorMsg('mamount', 'required', ' ');
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $date = DateTime::createFromFormat(DF_DD, $valid['billdt']);
                $date = date_format($date, DFS_DB);
                $data = array(
                    'eup_exp_id' => $decExpId,
                    'eup_type' => $valid['type'],
                    'eup_date' => $date,
                    'eup_exp_topay' => $expenseDet['exp_amount'],
                    'eup_exp_adjust' => $valid['adjust'],
                    'eup_exp_credit' => $expenseDet['exp_amount'] - $valid['adjust']
                );
                require_once __DIR__ . '/../admin/!model/expenseupdate.php';
                $expUpdate = new expupdate();
                $insert = $expUpdate->add($data);
                if ($insert) {
                    if (count($valid['mamount']) > 0) {
                        foreach ($valid['mamount'] as $key => $amt) {
                            if ($amt > 0) {
                                $refstatus['eref_status'] = 2;
                                $refUpdate = $exprefObj->modify($refstatus, $key);
                                if ($refUpdate) {
                                    $refData = array(
                                        'eref_exp_id' => $decExpId,
                                        'eref_main_head' => $expenseDet['exp_mainh'],
                                        'eref_main_head_ref' => $getExpRefIdRefPair[$key],
                                        'eref_amount' => $amt
                                    );
                                    $det = $exprefObj->add($refData);
                                }
                            }
                        }
                    }
                    $expdata['exp_update_status'] = $valid['type'];
                    $expdata['exp_amount'] = $expenseDet['exp_amount'] - $valid['adjust'];
                    if ($expenseDet['exp_pay_mode'] == 2)
                        $expdata['exp_credit_amt'] = $expenseDet['exp_amount'] - $valid['adjust'];
                    $exupdate = $expenseObj->modify($expdata, $decExpId);
                    $upload = uploadFiles(DOC_TYPE_EXP_UPD, $insert, $valid['my_files']);
                    if ($upload) {
                        $form->reset();
                        if ($exupdate) {

                            $feedback = 'Details Updated successfully';
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
        } else {}

        $this->view->form = $form;
        $this->view->expenseDet = $expenseDet;
        $this->view->mfields = $mfields;
        $this->view->expRefDetails = $expRefDetails;
    }

    public function getliveAction()
    {
        $this->view->response('ajax');
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---');
            } else {

                // s($_POST);

                require_once __DIR__ . '/../admin/!model/category.php';
                $catModelObj = new category();

                $ptype = $_POST['pType'];

                switch ($_POST['refParam']) {
                    case 'parent':

                        $sCatList = $catModelObj->getCategoryPair(array(
                            'cat_type' => 2,
                            'cat_parent' => $_POST['refId']
                        ));

                        $data[] = array(
                            'key' => '',
                            'value' => 'Select Sub Category'
                        );
                        if (! empty($ptype))
                            $sCatList["-1"] = "--Add New Sub Category--";

                        if (count($sCatList))
                            foreach ($sCatList as $key => $val)
                                $data[] = array(
                                    'key' => $key,
                                    'value' => $val
                                );
                        break;

                    case 'sub':
                        $cCatList = $catModelObj->getCategoryPair(array(
                            'cat_type' => 3,
                            'cat_parent' => $_POST['refId']
                        ));

                        $data[] = array(
                            'key' => '',
                            'value' => 'Select Child Category'
                        );
                        if (! empty($ptype))
                            $cCatList["-1"] = "--Add New Child Category--";

                        if (count($cCatList))
                            foreach ($cCatList as $key => $val)
                                $data[] = array(
                                    'key' => $key,
                                    'value' => $val
                                );
                        break;

                    case 'projects':
                        require_once __DIR__ . '/../admin/!model/property.php';
                        $propModelObj = new property();

                        $projectList = $propModelObj->getProjectsPair([
                            'project_client_id' => $_POST['refId']
                        ]);

                        $data[] = array(
                            'key' => '',
                            'value' => 'Select Project'
                        );

                        if (count($projectList))
                            foreach ($projectList as $key => $val)
                                $data[] = array(
                                    'key' => $key,
                                    'value' => $val
                                );
                        break;
                }

                $data = json_encode($data);
                die($data);
            }
        }
    }
}