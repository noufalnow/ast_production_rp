<?php

class masterController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        $form = new form();
        $form->addElement('propno', 'Property No ', 'text', 'required');
        $form->addElement('fileno', 'File no ', 'text', '');
        $form->addElement('propname', 'Property Name ', 'text', 'required');
        $form->addElement('remarks', 'Remarks', 'text', '');
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        require_once __DIR__ . '/../admin/!model/bankaccount.php';
        $bankAccount = new bankaccount();
        $bankAccountList = $bankAccount->getAccountDetails();
        $form->addElement('building', 'Property Building', 'select', 'required', array(
            'options' => $buildingList
        ));
        $form->addElement('prop_build', 'Building/Flat', 'checkbox', '', array(
            'options' => array(
                1 => "Building"
            )
        ));
        $form->addElement('prop_cat', 'Property Category', 'radio', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('prop_type', 'Property Type', 'select', '', array(
            'options' => array(
                1 => "1 BHK",
                2 => "2 BHK"
            )
        ));
        $form->addElement('prop_level', 'Property Level', 'select', '', array(
            'options' => array(
                1 => "Basement-2",
                2 => "Basement-1",
                3 => "Ground Floor",
                4 => "1st Floor",
                5 => "2nd Floor",
                6 => "3rd Floor",
                7 => "4th Floor",
                8 => "5th Floor",
                9 => "6th Floor",
                10 => "7th Floor",
                11 => "8th Floor",
                12 => "9th Floor",
                98 => "Leveling Floor",
                99 => "Pent House"
            )
        ));
        $form->addElement('prop_elec1', 'Meter No', 'text', 'alpha_space');
        $form->addElement('prop_elec2', 'Account No', 'text', 'alpha_space');
        $form->addElement('prop_elec3', 'Recharge No', 'text', 'alpha_space');
        $form->addElement('prop_water', 'Water meter No', 'text', 'alpha_space');
        $form->addElement('bank_account', 'Bank Account', 'select', '', array(
            'options' => $bankAccountList
        ));
        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                require_once __DIR__ . '/../admin/!model/property.php';
                $property = new property();
                $propDet = $property->getPropertyDet(array(
                    'prop_fileno' => $valid['fileno']
                ));
                if ($propDet['prop_id']) {
                    $form->fileno->setError("File No already selected");
                } else {
                    if ($valid['prop_build'] == "" && $valid['prop_cat'] == "") {
                        $form->prop_cat->setError("Property Category field is required");
                    } elseif ($valid['prop_build'] == "" && $valid['prop_level'] == "") {
                        $form->prop_level->setError("Property Level field is required");
                    } elseif ($valid['prop_cat'] == 2 && $valid['prop_type'] == "") {
                        $form->prop_type->setError("Property Type field is required");
                    } else {
                        $data = array(
                            'prop_no' => $valid['propno'],
                            'prop_fileno' => $valid['fileno'],
                            'prop_name' => $valid['propname'],
                            'prop_building' => $valid['building'],
                            'prop_remarks' => $valid['remarks'],
                            'prop_elec_meter' => $valid['prop_elec1'],
                            'prop_elec_account' => $valid['prop_elec2'],
                            'prop_elec_recharge' => $valid['prop_elec3'],
                            'prop_water' => $valid['prop_water']
                        );
                        if ($valid['prop_cat'])
                            $data['prop_cat'] = $valid['prop_cat'];
                        else
                            $data['prop_cat'] = NULL;
                        if ($valid['prop_level'])
                            $data['prop_level'] = $valid['prop_level'];
                        else
                            $data['prop_level'] = NULL;
                        if ($valid['prop_type'])
                            $data['prop_type'] = $valid['prop_type'];
                        else
                            $data['prop_type'] = NULL;
                        if ($valid['prop_build'])
                            $data['prop_building_type'] = $valid['prop_build'];
                        else
                            $data['prop_building_type'] = NULL;
                        if ($valid['bank_account'])
                            $data['prop_account'] = $valid['bank_account'];
                        else
                            $data['prop_account'] = NULL;
                        $insert = $property->add($data);
                        if ($insert) {
                            $this->view->feedback = 'Property details added successfully';
                            $this->view->NoViewRender = true;
                        }
                    }
                }
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        $form = new form();
        $property = new property();
        $form->addElement('propno', 'Property No ', 'text', 'required');
        $form->addElement('fileno', 'File no ', 'text', '');
        $form->addElement('propname', 'Property Name ', 'text', 'required');
        $form->addElement('remarks', 'Remarks', 'text', '');
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        require_once __DIR__ . '/../admin/!model/bankaccount.php';
        $bankAccount = new bankaccount();
        $bankAccountList = $bankAccount->getAccountDetails();
        $form->addElement('building', 'Property Building', 'select', 'required', array(
            'options' => $buildingList
        ));
        $form->addElement('prop_build', 'Building/Flat', 'checkbox', '', array(
            'options' => array(
                1 => "Building"
            )
        ));
        $form->addElement('prop_cat', 'Property Category', 'radio', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('prop_type', 'Property Type', 'select', '', array(
            'options' => array(
                1 => "1 BHK",
                2 => "2 BHK"
            )
        ));
        $form->addElement('prop_level', 'Property Level', 'select', '', array(
            'options' => array(
                1 => "Basement-2",
                2 => "Basement-1",
                3 => "Ground Floor",
                4 => "1st Floor",
                5 => "2nd Floor",
                6 => "3rd Floor",
                7 => "4th Floor",
                8 => "5th Floor",
                9 => "6th Floor",
                10 => "7th Floor",
                11 => "8th Floor",
                12 => "9th Floor",
                98 => "Leveling Floor",
                99 => "Pent House"
            )
        ));
        $form->addElement('prop_elec1', 'Meter No', 'text', 'alpha_space');
        $form->addElement('prop_elec2', 'Account No', 'text', 'alpha_space');
        $form->addElement('prop_elec3', 'Recharge No', 'text', 'alpha_space');
        $form->addElement('prop_water', 'Water meter No', 'text', 'alpha_space');
        $form->addElement('bank_account', 'Bank Account', 'select', '', array(
            'options' => $bankAccountList
        ));
        $propId = $this->view->decode($this->view->param['ref']);
        if (! $propId)
            die('tampered');
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    require_once __DIR__ . '/../admin/!model/property.php';
                    $property = new property();
                    $propDet = $property->getPropertyDet(array(
                        'prop_fileno' => $valid['fileno'],
                        'ex_prop_id' => $propId
                    ));
                    if ($propDet['prop_id']) {
                        $form->fileno->setError("File No already selected");
                    } else {
                        if ($valid['prop_build'] == "" && $valid['prop_cat'] == "") {
                            $form->prop_cat->setError("Property Category field is required");
                        } elseif ($valid['prop_build'] == "" && $valid['prop_level'] == "") {
                            $form->prop_level->setError("Property Level field is required");
                        } elseif ($valid['prop_cat'] == 2 && $valid['prop_type'] == "") {
                            $form->prop_type->setError("Property Type field is required");
                        } else {
                            $data = array(
                                'prop_no' => $valid['propno'],
                                'prop_fileno' => $valid['fileno'],
                                'prop_name' => $valid['propname'],
                                'prop_building' => $valid['building'],
                                'prop_remarks' => $valid['remarks'],
                                'prop_elec_meter' => $valid['prop_elec1'],
                                'prop_elec_account' => $valid['prop_elec2'],
                                'prop_elec_recharge' => $valid['prop_elec3'],
                                'prop_water' => $valid['prop_water']
                            );
                            if ($valid['prop_cat'])
                                $data['prop_cat'] = $valid['prop_cat'];
                            else
                                $data['prop_cat'] = NULL;
                            if ($valid['prop_level'])
                                $data['prop_level'] = $valid['prop_level'];
                            else
                                $data['prop_level'] = NULL;
                            if ($valid['prop_type'])
                                $data['prop_type'] = $valid['prop_type'];
                            else
                                $data['prop_type'] = NULL;
                            if ($valid['prop_build'])
                                $data['prop_building_type'] = $valid['prop_build'];
                            else
                                $data['prop_building_type'] = NULL;
                            if ($valid['bank_account'])
                                $data['prop_account'] = $valid['bank_account'];
                            else
                                $data['prop_account'] = NULL;
                            $update = $property->modify($data, $propId);
                            if ($update) {
                                $this->view->feedback = 'Property details updated successfully';
                                $this->view->NoViewRender = true;
                            }
                        }
                    }
                }
            }
        } else {
            $propDetails = $property->getPropertyDetById($propId);
            $form->prop_build->setValue($propDetails['prop_building_type']);
            $form->propno->setValue($propDetails['prop_no']);
            $form->fileno->setValue($propDetails['prop_fileno']);
            $form->propname->setValue($propDetails['prop_name']);
            $form->building->setValue($propDetails['prop_building']);
            $form->remarks->setValue($propDetails['prop_remarks']);
            $form->prop_cat->setValue($propDetails['prop_cat']);
            $form->prop_type->setValue($propDetails['prop_type']);
            $form->prop_level->setValue($propDetails['prop_level']);
            $form->prop_elec1->setValue($propDetails['prop_elec_meter']);
            $form->prop_elec2->setValue($propDetails['prop_elec_account']);
            $form->prop_elec3->setValue($propDetails['prop_elec_recharge']);
            $form->prop_water->setValue($propDetails['prop_water']);
            $form->bank_account->setValue($propDetails['prop_account']);
        }
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        $formRender = true;
        $form = new form();
        $property = new property();
        $decPropertyId = $this->view->decode($this->view->param['ref']);
        if (! $decPropertyId)
            die('tampered');
        $propertyDetail = $property->getPropertyById($decPropertyId);
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $property->deleteProperty($decPropertyId);
                if ($delete) {
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => 'The property has been deleted successfully from the system  .'
                    );
                    $_SESSION['feedback'] = 'The property has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
        $this->view->form = $form;
        $this->view->propertyDetail = $propertyDetail;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/property.php';
        $form = new form();
        $form->addElement('f_propname', 'Property', 'text', '');
        $form->addElement('f_tenant', 'Tenant', 'text', '');
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_fileno', 'File No ', 'text', '');
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat",
                3 => "Building"
            )
        ));
        $form->addElement('f_prop_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Vacant",
                2 => "Agreement",
                3 => "Maintenance"
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
                    'f_propname' => @$valid['f_propname'],
                    'f_building' => @$valid['f_building'],
                    'f_propno' => @$valid['f_propno'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_tenant' => @$valid['f_tenant'],
                    'f_prop_status' => @$valid['f_prop_status'],
                    'f_fileno' => @$valid['f_fileno']
                );
            }
            $filter_class = 'btn-info';
        }
        $propertyObj = new property();
        // s($where);
        $propertyList = $propertyObj->getPropertyPaginate(@$where);
        $offset = $propertyObj->_voffset;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->propertyObj = $propertyObj;
        $this->view->offset = $offset;
        $this->view->filter_class = $filter_class;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
    }

    public function payoptionsAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/proppayoption.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $payOptObj = new proppayoption();
        $propDocObj = new documets();
        $formRender = true;
        $form = new form();
        $decPropDocId = $this->view->decode($this->view->param['ref']);
        if (! $decPropDocId)
            die('tampered');
        $propDocDet = $propDocObj->getDocumentById($decPropDocId);
        $count = 1;
        $idKeys = $payOptObj->getPayKeyPairByDocAndProperty(array(
            'popt_prop_id' => $propDocDet['doc_ref_id'],
            'popt_doc_id' => $decPropDocId
        ));
        if (isset($_POST) && count($_POST) > 0) {
            $count = array_keys($_POST['date']);
        } else {
            $payOptionDet = $payOptObj->getPayOptiosByDocAndProperty(array(
                'popt_prop_id' => $propDocDet['doc_ref_id'],
                'popt_doc_id' => $decPropDocId
            ));
            $count = count($idKeys) <= 0 ? 1 : $idKeys;
        }
        
        $payOptionDemandDet = $payOptObj->getPayKeyPairByDocAndPropertyDemand(array(
            'popt_prop_id' => $propDocDet['doc_ref_id'],
            'popt_doc_id' => $decPropDocId
        ));
        
        $this->view->payDemand = $payOptionDemandDet;
        
        $form->addMultiElement('payselect', 'Pay Mode', 'select', '', array(
            'options' => array(
                1 => "Cash",
                2 => "Cheque",
                "3" => "Not Defined"
            )
        ), array(
            'class' => 'mod_select',
            '' => 'onchange="modaction(this);"'
        ), $count);
        $form->addMultiElement('date', 'Date', 'text', 'date', '', array(
            'class' => 'date_picker'
        ), $count);
        $form->addMultiElement('amount', 'Amount', 'float', 'numeric', '', array(
            'class' => ''
        ), $count);
        $form->addMultiElement('chequeno', 'Cheque No', 'text', '', '', array(
            'class' => 'txchqno'
        ), $count);
        $form->addMultiElement('bank', 'Bank', 'select', '', array(
            'options' => array(
                '1' => "Bank Muscat",
                '2' => 'Bank Dhofar',
                "3" => "NBO",
                "4" => "OAB",
                "5" => "HSBC",
                "6" => "FAB",
                '7' => 'Bank Sohar',
                '8' => 'SBI',
                '9' => 'Bank of Baroda',
                '10' => 'NBA',
                '11' => 'Bank Nizwa'
            )
        ), array(
            'class' => 'full-select slbank'
        ), $count);
        $mfields = array_keys($form->_elements['date']);
        // echo "<pre>";print_r($mfields);
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $form->addErrorMsg('payselect', 'required', ' ');
                $form->addErrorMsg('date', 'required', ' ');
                $form->addErrorMsg('amount', 'required', ' ');
                $form->addErrorMsg('chequeno', 'required', ' ');
                $form->addErrorMsg('bank', 'required', ' ');
                foreach ($mfields as $i) {
                    if ($_POST['payselect'][$i] == 2 && $_POST['bank'][$i] == '')
                        $form->addmRules('bank', $i, 'required');
                    if ($_POST['payselect'][$i] == 2 && $_POST['chequeno'][$i] == '')
                        $form->addmRules('chequeno', $i, 'required');
                    if ($_POST['date'][$i] != '' || $_POST['amount'][$i] != '')
                        $form->addmRules('payselect', $i, 'required');
                    if ($_POST['payselect'][$i] != '') {
                        $form->addmRules('date', $i, 'required');
                        $form->addmRules('amount', $i, 'required');
                    }
                    if ($_POST['payselect'][$i] == 3) {
                        $form->addmRules('date', $i, 'required');
                    }
                    
                    if(!empty($payOptionDemandDet[$i])){
                        $form->amount[$i]->setDisabled();
                        $form->date[$i]->setDisabled();                       
                        $form->chequeno[$i]->setDisabled();
                    }
                    
                }
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // $payOptObj->deletePayOpByDocAndPropId(array('popt_doc_id'=>$decPropDocId,'popt_prop_id'=>$propDocDet['doc_ref_id']));
                    $to_delete = (array_diff($idKeys, $mfields));
                    $to_update = (array_intersect($idKeys, $mfields));
                    $to_insert = (array_diff($mfields, $idKeys));
                    if (count($to_delete) > 0)
                        foreach ($to_delete as $del)
                            if(! isset($payOptionDemandDet[$del]))
                                $payOptObj->deletePayOptionByID($del);
                    foreach ($mfields as $i) {
                        $data = array();
                        if ($valid['payselect'][$i] != '') {
                            $dt = DateTime::createFromFormat(DF_DD, $valid['date'][$i]);
                            $dt = date_format($dt, DFS_DB);
                            $data = array(
                                'popt_doc_id' => $decPropDocId,
                                'popt_prop_id' => $propDocDet['doc_ref_id'],
                                'popt_type' => $valid['payselect'][$i],
                                'popt_date' => $dt
                            );
                            if ($valid['bank'][$i])
                                $data['popt_bank'] = $valid['bank'][$i];
                            if ($valid['amount'][$i])
                                $data['popt_amount'] = $valid['amount'][$i];
                            else
                                $data['popt_amount'] = NULL;
                            if ($valid['chequeno'][$i])
                                $data['popt_chqno'] = $valid['chequeno'][$i];
                            if (in_array($i, $to_update))
                                if(! isset($payOptionDemandDet[$i]))
                                    $cflowdet = $payOptObj->modify($data, $i);
                            if (in_array($i, $to_insert))
                                $cflowdet = $payOptObj->add($data);
                        }
                    }
                    if ($cflowdet) {
                        $feedback = 'Property pay option updated successfully';
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
            foreach ($payOptionDet as $fields) {
                $i = $fields['popt_id'];
                $Dt = DateTime::createFromFormat(DFS_DB, $fields['popt_date']);
                $Dt = $Dt->format(DF_DD);
                $form->payselect[$i]->setValue($fields['popt_type']);
                $form->date[$i]->setValue($Dt);
                $form->amount[$i]->setValue($fields['popt_amount']);
                $form->bank[$i]->setValue($fields['popt_bank']);
                $form->chequeno[$i]->setValue($fields['popt_chqno']);
                if(!empty($fields['cdet_id'])){
                    $form->amount[$i]->setDisabled();
                    $form->date[$i]->setDisabled();
                    $form->chequeno[$i]->setDisabled();
                }
            }
        }
        $this->view->form = $form;
        $this->view->propDocDet = $propDocDet;
        $this->view->mfields = $mfields;
    }

    public function propdocsAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        require_once __DIR__ . '/../admin/!model/property.php';
        $property = new property();
        require_once __DIR__ . '/../admin/!model/tenants.php';
        $tenant = new tenants();
        $form = new form();
        $docDisplay = 'display: none;';
        $imgDisplay = 'display: none;';
        $docMst = array(
            '201' => "Lease Contract"
        );
        $propId = $this->view->decode($this->view->param['ref']);
        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $docMst
        ));
        $form->addElement('docno', 'Agreement No ', 'text', 'required');
        $form->addElement('agr_tenant', 'Contract By/Tenant Name', 'select', 'required', array(
            'options' => $tenant->getTenantsPair()        ));;
        /*$form->addElement('agr_mobile', 'Mobile No ', 'number', 'required|numeric');
        $form->addElement('agr_tele', 'Telephone No ', 'text', 'numeric');
        $form->addElement('agr_idno', 'ID/Passport/Labour No', 'text', 'required|alpha_space');
        $form->addElement('agr_comp', 'Company/ Individual', 'radio', 'required', array(
            'options' => array(
                1 => "Company",
                2 => "Individual"
            )
        ));
        $form->addElement('agr_expat', 'National/Expart', 'radio', 'required', array(
            'options' => array(
                1 => "National",
                2 => "Expart"
            )
        ));
        $form->addElement('agr_crno', 'CR No ', 'text', 'alpha_space');
        */
        $form->addElement('doi', 'Agreement Start ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Agreement End ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('agr_amount', 'Agreement Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addElement('agr_rent', 'Rent Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addElement('agr_paydet', 'Payment Details', 'textarea', 'required');
        $form->addElement('docremark', 'Remarks ', 'text', 'alpha_space');
        $form->addElement('alert', 'Alert Days', 'number', 'numeric');
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
            'doc_ref_type' => DOC_IMG_PROP,
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
                        'doc_ref_type' => DOC_IMG_PROP,
                        'doc_ref_id' => $propId
                    );
                    $insert = $docs->add($data);
                    if ($insert) {
                        if (! empty($propImage['doc_id'])) {
                            $delete = $docs->deleteDocument($propImage['doc_id']);
                            if (! empty($propImage['file_id'])) {
                                $file = new files();
                                deleteFile($propImage['file_id']);
                                $file->deleteFile($propImage['file_id']);
                            }
                        }
                        $upload = uploadFiles(DOC_IMG_PROP, $insert, $imValid['photo']);
                        
                        
                        $form->reset();
                        $this->view->url = APPURL . "erp_property/master/propdocs/ref/" . $this->view->param['ref'];
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
                        'doc_ref_type' => DOC_TYPE_PROP,
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
                                "prop_status" => 2
                            ), $propId);
                        $upload = uploadFiles(DOC_TYPE_PROP, $insert, $valid['my_files']);
                        $form->reset();
                        $this->view->url = APPURL . "erp_property/master/propdocs/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";
                        if ($upload) {
                            $this->view->feedback = 'Property images added successfully';
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
            'doc_ref_type' => DOC_TYPE_PROP,
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
        require_once __DIR__ . '/../admin/!model/tenants.php';
        $tenant = new tenants();
        $propDocId = $this->view->decode($this->view->param['ref']);
        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $propDocId,
            'doc_ref_type' => DOC_TYPE_PROP
        ));
        // s($docDetails);
        if (! $propDocId)
            die('tampered');
        $form = new form();
        $form->addElement('docno', 'Agreement No ', 'text', 'required');
        $form->addElement('agr_tenant', 'Contract By/Tenant Name', 'select', 'required', array(
            'options' => $tenant->getTenantsPair()        ));;
        /*$form->addElement('agr_mobile', 'Mobile No ', 'number', 'required|numeric');
        $form->addElement('agr_tele', 'Telephone No ', 'text', 'numeric');
        $form->addElement('agr_idno', 'ID/Passport/Labour No', 'text', 'required|alpha_space');
        $form->addElement('agr_comp', 'Company/ Individual', 'radio', 'required', array(
            'options' => array(
                1 => "Company",
                2 => "Individual"
            )
        ));
        $form->addElement('agr_crno', 'CR No ', 'text', 'alpha_space');
        $form->addElement('agr_expat', 'National/Expart', 'radio', 'required', array(
            'options' => array(
                1 => "National",
                2 => "Expart"
            )
        ));
        */
        $form->addElement('doi', 'Agreement Start ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Agreement End ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('agr_amount', 'Agreement Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));
        $form->addElement('agr_rent', 'Rent Amount', 'float', 'required|numeric', '', array(
            'class' => 'fig'
        ));

        $form->addElement('agr_paydet', 'Payment Details', 'textarea', 'required');
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('alert', 'Alert Days', 'number', 'numeric');
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
                                "prop_status" => 2
                            ), $docDetails['doc_ref_id']);
                        deleteFile($docDetails['file_id']);
                        $file->deleteFile($docDetails['file_id']);
                        $upload = uploadFiles(DOC_TYPE_PROP, $propDocId, $valid['my_files']);
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
            $form->agr_tenant->setValue($docDetails['agr_tnt_id']);
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
            $form->docremark->setValue($docDetails['doc_no']);
            $form->alert->setValue($docDetails['doc_alert_days']);
        }
        $this->view->form = $form;
    }

    public function propdocsdeleteAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $document = new documets();
        $decDocumentId = $this->view->decode($this->view->param['ref']);
        if (! $decDocumentId)
            die('tampered');
        $documentDetail = $document->getDocumentDetails(array(
            'doc_id' => $decDocumentId,
            'doc_ref_type' => DOC_TYPE_PROP
        ));
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $document->deleteDocument($decDocumentId);
                if ($delete) {
                    $file = new files();
                    deleteFile($documentDetail['file_id']);
                    $file->deleteFile($documentDetail['file_id']);
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => 'The document has been deleted successfully from the system  .'
                    );
                    $_SESSION['feedback'] = 'The document has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }
        $this->view->documentDetail = $documentDetail;
    }

    public function propdocscommAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $form = new form();
        $docDisplay = 'display: none;';
        $imgDisplay = 'display: none;';
        $propId = $this->view->decode($this->view->param['ref']);
        $docMst = array(
            '202' => "Fire Safety Certificate",
            '203' => "Building Insurance"
        );
        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $docMst
        ));
        $form->addElement('docno', 'Agreement No ', 'text', 'required');
        $form->addElement('doi', 'Agreement Start ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Agreement End ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('docremark', 'Remarks ', 'text', 'alpha_space');
        $form->addElement('alert', 'Alert Days', 'number', 'numeric');
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $imgform = new form();
        $imgform->addFile('photo', 'Photo', array(
            'required' => true,
            'exten' => 'png;jpg',
            'size' => 5000000
        ));
        $propImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_PROP,
            'doc_ref_id' => $propId
        ));
        $propImage = $propImage['0'];
        if (! $propId)
            die('tampered');
        if ($_POST) {
            if (! empty($_POST['image'])) {
                $imValid = $imgform->vaidate($_POST, $_FILES);
                $imValid = $imValid[0];
                if ($imValid == true) {
                    $data = array(
                        'doc_type' => 5, // @todo image document type create constants
                        'doc_ref_type' => DOC_IMG_PROP,
                        'doc_ref_id' => $propId
                    );
                    $insert = $docs->add($data);
                    if ($insert) {
                        if (! empty($propImage['doc_id'])) {
                            $delete = $docs->deleteDocument($propImage['doc_id']);
                            if (! empty($propImage['file_id'])) {
                                $file = new files();
                                deleteFile($propImage['file_id']);
                                $file->deleteFile($propImage['file_id']);
                            }
                        }

                        $upload = uploadFiles(DOC_IMG_PROP, $insert, $imValid['photo']);
                        $form->reset();
                        $this->view->url = APPURL . "erp_property/master/propdocscomm/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";

                        if ($upload) {
                            $this->view->feedback = 'Property documents added successfully';
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
                        'doc_ref_type' => DOC_TYPE_PROP,
                        'doc_ref_id' => $propId,
                        'doc_no' => $valid['docno'],
                        'doc_issue_date' => $doi,
                        'doc_expiry_date' => $doe,
                        'doc_remarks' => $valid['docremark']
                    );
                    if ($valid['alert'])
                        $data['doc_alert_days'] = $valid['alert'];
                    $insert = $docs->add($data);
                    if ($insert) {
                        $upload = uploadFiles(DOC_TYPE_PROP, $insert, $valid['my_files']);
                        $this->view->url = APPURL . "erp_property/master/propdocscomm/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";

                        if ($upload) {
                            $this->view->feedback = 'Property documents added successfully';
                        } else {
                            $this->view->feedback = 'Unable to upload file';
                        }
                    }
                } else {
                    $docDisplay = '';
                }
            }
        }
        $fireDocs = $docs->getDocuments(array(
            'doc_type' => 202,
            'doc_ref_type' => DOC_TYPE_PROP,
            'doc_ref_id' => $propId
        ));
        $insDocs = $docs->getDocuments(array(
            'doc_type' => 203,
            'doc_ref_type' => DOC_TYPE_PROP,
            'doc_ref_id' => $propId
        ));
        $this->view->form = $form;
        $this->view->imgDisplay = $imgDisplay;
        $this->view->imgform = $imgform;
        $this->view->docDisplay = $docDisplay;
        $this->view->fireDocs = $fireDocs;
        $this->view->insDocs = $insDocs;
        $this->view->propImage = $propImage;
        $this->view->target = "menu2";
    }

    public function propdocscommeditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $propDocId = $this->view->decode($this->view->param['ref']);
        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $propDocId,
            'doc_ref_type' => DOC_TYPE_PROP
        ));
        // s($docDetails);
        if (! $propDocId)
            die('tampered');
        $form = new form();
        $docMst = array(
            '202' => "Fire Safety Certificate",
            '203' => "Building Insurance"
        );
        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $docMst
        ));
        $form->addElement('docno', 'Agreement No ', 'text', 'required');
        $form->addElement('doi', 'Agreement Start ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Agreement End ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('alert', 'Alert Days', 'number', 'numeric');
        $form->addElement('docUpdate', '', 'checkbox', '', array(
            'options' => array(
                1 => ""
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 5375000
        ));
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
                    'doc_type' => $valid['doctype'],
                    'doc_no' => $valid['docno'],
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
                        /*
                         * if ($propertyDetail['prop_building_type'] == '')
                         * $update = $property->modify(array(
                         * "prop_status" => 2
                         * ), $docDetails['doc_ref_id']);
                         */
                        $upload = uploadFiles(DOC_TYPE_PROP, $propDocId, $valid['my_files']);

                        if ($upload) {
                            $form->reset();
                            $this->view->feedback = 'Document details modified successfully';
                        } else {
                            $this->view->feedback .= 'Unable to upload file';
                        }
                    }
                    $this->view->url = APPURL . "erp_employee/master/empdocs/ref/" . $this->view->encode($docDetails['doc_ref_id']);
                    $this->view->status = 11;
                }
            }
        } else {
            $form->docno->setValue($docDetails['doc_no']);
            $form->doctype->setValue($docDetails['doc_type']);
            $doi = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
            $doi = $doi->format(DF_DD);
            $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
            $doe = $doe->format(DF_DD);
            $form->doi->setValue($doi);
            $form->doe->setValue($doe);
            $form->docremark->setValue($docDetails['doc_no']);
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
            'doc_ref_type' => DOC_TYPE_PROP
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

    public function statusAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/propstatus.php';
        require_once __DIR__ . '/../admin/!model/property.php';
        $propStatusObj = new propstatus();
        $propertyObj = new property();
        require_once __DIR__ . '/../admin/!model/property.php';
        $propModelObj = new property();
        $propList = $propModelObj->getPropetyPair();
        $formRender = true;
        $form = new form();
        $decPropId = $this->view->decode($this->view->param['ref']);
        $propertyDet = $propStatusObj->getPropertyStatusDet(array(
            'psts_prop_id' => $decPropId
        ));
        if (! $decPropId)
            die('tampered');
        $form->addElement('status', 'Status', 'select', 'required', array(
            'options' => array(
                "1" => "Vacant/Ready to Occupy",
                "2" => "Under Agreement",
                "3" => "Under Maintenance",
                "4" => "Under Other Agreement"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', 'required|alpha_space');
        $form->addElement('property', 'Property', 'select', '', array(
            'options' => $propList
        ));
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                if ($_POST['status'] == 4) {
                    $form->addRules('property', 'required');
                }
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $data = array(
                        'psts_status_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'psts_type' => $valid['status'],
                        'psts_remarks' => $valid['note'],
                        'psts_prop_id' => $decPropId
                    );
                    if ($valid['property'])
                        $data['psts_attach_prop'] = $valid['property'];
                    else
                        $data['psts_attach_prop'] = NULL;
                    $insert = $propStatusObj->add($data);
                    $update = $propertyObj->modify(array(
                        "prop_status" => $valid['status']
                    ), $decPropId);
                    if ($update) {
                        $this->view->feedback = 'Property status updated successfully';
                        $this->view->NoViewRender = true;
                    }
                }
            }
        } else {
            $form->status->setValue($propertyDet['psts_type']);
            $form->note->setValue($propertyDet['psts_remarks']);
            $form->property->setValue($propertyDet['psts_attach_prop']);
        }
        $this->view->form = $form;
        $this->view->propertyDet = $propertyDet;
    }

    public function viewAction()
    {
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $property = new property();
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            89 => "Leveling Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );
        $decPropId = $this->view->decode($this->view->param['ref']);
        if (! $decPropId)
            die('tampered');
        $propertyDetail = $property->getPropertyDet(array(
            'prop_id' => $decPropId
        ));
        $propDocs = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_TYPE_PROP,
            'doc_ref_id' => $decPropId
        ));
        $propImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_PROP,
            'doc_ref_id' => $decPropId
        ));
        $propImage = $propImage['0'];
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
            '201' => "Lease Agreement",
            '202' => "Fire Safety Certificate",
            '203' => "Building Insurance"
        );
        $vtcat = array(
            1 => "Shop",
            2 => "Flat"
        );
        $this->view->propertyDetail = $propertyDetail;
        $this->view->hom_class = $hom_class;
        $this->view->doc_class = $doc_class;
        $this->view->profile_class = $profile_class;
        $this->view->hom_active = $hom_active;
        $this->view->vtcat = $vtcat;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
        $this->view->propDocs = $propDocs;
        $this->view->propImage = $propImage;
        $this->view->docMst = $docMst;
        $this->view->decPropId = $decPropId;
        $this->view->doc_active = $doc_active;
        $this->view->profile_active = $profile_active;
    }

    public function profileAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $property = new property();
        $propType = array(
            1 => "1 BHK",
            2 => "2 BHK"
        );
        $propLevel = array(
            1 => "Basement-2",
            2 => "Basement-1",
            3 => "Ground Floor",
            4 => "1st Floor",
            5 => "2nd Floor",
            6 => "3rd Floor",
            7 => "4th Floor",
            8 => "5th Floor",
            9 => "6th Floor",
            10 => "7th Floor",
            11 => "8th Floor",
            12 => "9th Floor",
            89 => "Leveling Floor",
            98 => "Leveling Floor",
            99 => "Pent House"
        );
        $decPropId = $this->view->decode($this->view->param['ref']);
        if (! $decPropId)
            die('tampered');
        $propertyDetail = $property->getPropertyDet(array(
            'prop_id' => $decPropId
        ));
        $propDocs = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_TYPE_PROP,
            'doc_ref_id' => $decPropId
        ));
        $propImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_PROP,
            'doc_ref_id' => $decPropId
        ));
        $propImage = $propImage['0'];
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
            '201' => "Lease Agreement",
            '202' => "Fire Safety Certificate",
            '203' => "Building Insurance"
        );
        $vtcat = array(
            1 => "Shop",
            2 => "Flat"
        );
        $this->view->propertyDetail = $propertyDetail;
        $this->view->hom_class = $hom_class;
        $this->view->doc_class = $doc_class;
        $this->view->profile_class = $profile_class;
        $this->view->hom_active = $hom_active;
        $this->view->vtcat = $vtcat;
        $this->view->propType = $propType;
        $this->view->propLevel = $propLevel;
        $this->view->propDocs = $propDocs;
        $this->view->propImage = $propImage;
        $this->view->docMst = $docMst;
        $this->view->decPropId = $decPropId;
        $this->view->doc_active = $doc_active;
        $this->view->profile_active = $profile_active;
    }
}