<?php

class rentController extends mvc
{
    public function propertypayAction()
    {
        $this->view->response('window');
        $where = [];
        require_once __DIR__ . '/../admin/!model/property.php';

        $form = new form();
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));
        $form->addElement('f_date', 'Pay Date', 'text', 'date', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('f_propno', 'Property No ', 'text', '');
        $form->addElement('f_tenant', 'Tenant', 'text', '');
        require_once __DIR__ . '/../admin/!model/building.php';
        $buildingObj = new building();
        $buildingList = $buildingObj->getBuildingPair();
        $form->addElement('f_building', 'Building', 'select', '', array(
            'options' => $buildingList
        ));
        $form->addElement('f_prop_cat', 'Category', 'select', '', array(
            'options' => array(
                1 => "Shop",
                2 => "Flat"
            )
        ));
        $form->addElement('f_prop_type', 'Type', 'select', '', array(
            'options' => array(
                1 => "1 BHK",
                2 => "2 BHK"
            )
        ));
        $form->addElement('f_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Paid",
                2 => "Others"
            )
        ));

        $filter_class = 'btn-primary';
        $date = new DateTime();
        $title = 'Payment Schedule';

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        } else if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {

                $where = array(
                    'f_monthpick' => @$valid['f_monthpick'],
                    'f_propno' => @$valid['f_propno'],
                    'f_building' => @$valid['f_building'],
                    'f_prop_cat' => @$valid['f_prop_cat'],
                    'f_prop_type' => @$valid['f_prop_type'],
                    'f_tenant' => @$valid['f_tenant'],
                    'f_tenant' => @$valid['f_tenant']
                );
                if (! empty($valid['f_date'])) {
                    $fdate = DateTime::createFromFormat(DF_DD, $valid['f_date']);
                    $fdate = date_format($fdate, DFS_DB);
                    $where['f_date'] = $fdate;
                }
                if (! empty($valid['f_status'])) {
                    $where['f_status'] = @$valid['f_status'];
                }
            }
            $filter_class = 'btn-info';

            
            if(!empty($_GET['f_monthpick'])){
                $date = date_create_from_format(DF_DD, '01/' . $_GET['f_monthpick']);
                $month = date_format($date, 'F-Y');
                $title = 'Payment Schedule for the month - ' . $month;
            }
            
            
        } else if ($_GET['f_monthpick'] == "") {
            $where = array(
                'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
            );
            $form->f_monthpick->setValue($date->format('m') . '/' . $date->format('Y'));
            
            $month = date_format($date, 'F-Y');
            $title = 'Payment Schedule for the month - ' . $month;
            
            
        } else {
            $date = date_create_from_format(DF_DD, '01/' . $_GET['f_monthpick']);
            
            $month = date_format($date, 'F-Y');
            $title = 'Payment Schedule for the month - ' . $month;
        }


        $propObj = new property();

        $propertyList = $propObj->getPropertyPayReport(@$where);

        // s($propertyList);

        // To update file name formated with file No.
        /*
         * require_once __DIR__ . '/../admin/!model/files.php';
         * $fileObj = new files();
         * $docMst1 = array('1'=>"Passport", '2'=>"ResidentID",'3'=>"Visa",'4'=>"License");
         * foreach ($propertyList as $doc)
         * {
         * $fileObj->modify(array('file_actual_name'=>$doc['prop_fileno'].'-'.$docMst1[$doc['doc_type']]),
         * $doc['file_id']);
         * }
         */
        $this->view->filter_class = $filter_class;
        $this->view->propertyList = $propertyList;
        $this->view->form = $form;
        $this->view->title = $title;
    }
    
    public function adddemandAction()
    {
        $this->view->response('ajax');
        $form = new form();
        
        $decRefd = $this->view->decode($this->view->param['ref']);
        
        if (! $decRefd)
            die('tampered');
            
            require_once __DIR__ . '/../admin/!model/proppayoption.php';
            $payOptionObj = new proppayoption();
            $form->addElement('note', 'Demand Note', 'textarea', 'required');
            $payoptionDet = $payOptionObj->getPayOptionDetById(array(
                'popt_id' => $decRefd
            ));
            
            require_once __DIR__ . '/../admin/!model/cashdemand.php';
            $cashDmdObj = new cashdemand();
            
            $casDmdDet = $cashDmdObj->getContactByRefId(array(
                'cdmd_type' => CASHDMD_TYP_PROP,
                'cdmd_ref_id' => $payoptionDet['popt_id']
            ));
            if ($_POST) {
                if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                    die('---'); // exit script outputting json data
                } else {
                    
                    $valid = $form->vaidate($_POST, $_FILES);
                    
                    $valid = $valid[0];
                    if ($valid == true) {
                        
                        $data = array(
                            'cdmd_type' => CASHDMD_TYP_PROP,
                            'cdmd_ref_id' => $payoptionDet['popt_id'],
                            'cdmd_oth_id' => $payoptionDet['popt_doc_id'],
                            'cdmd_narration' => $payoptionDet['prop_fileno'] . "|" . $payoptionDet['bld_name'] . "|" . $payoptionDet['doc_no'] . "|" . $payoptionDet['agr_tenant'] . "|" . $payoptionDet['doc_issue_date'] . "|" . $payoptionDet['doc_expiry_date'] . "|" . $payoptionDet['agr_paydet'] . "|" . $payoptionDet['popt_type_txt'] . "|" . $payoptionDet['popt_bank_det'],
                            'cdmd_mode' => $payoptionDet['popt_type'],
                            'cdmd_total' => $payoptionDet['popt_amount'],
                            'cdmd_date' => $payoptionDet['popt_date'],
                            'cdmd_month' => $payoptionDet['popt_date'],
                            'cdmd_credit_amt' => $payoptionDet['popt_amount'],
                            'cdmd_orig_amt' => $payoptionDet['popt_amount'],
                            'cdmd_note' => $valid['note']
                        );
                        
                        if ($casDmdDet)
                            $demand = $cashDmdObj->modify(array(
                                'cdmd_note' => $valid['note'],
                                'cdmd_narration' => $payoptionDet['prop_fileno'] . "|" . $payoptionDet['bld_name'] . "|" . $payoptionDet['doc_no'] . "|" . $payoptionDet['agr_tenant'] . "|" . $payoptionDet['doc_issue_date'] . "|" . $payoptionDet['doc_expiry_date'] . "|" . $payoptionDet['agr_paydet'] . "|" . $payoptionDet['popt_type_txt'] . "|" . $payoptionDet['popt_bank_det'],
                                'cdmd_total' =>  $payoptionDet['popt_amount'],
                                'cdmd_credit_amt' =>  $payoptionDet['popt_amount'],
                                'cdmd_orig_amt' =>  $payoptionDet['popt_amount'],
                            ), $casDmdDet['cdmd_id']);
                            else
                                $demand = $cashDmdObj->add($data);
                                
                                if ($demand) {
                                    $feedback = 'Property details updated successfully';
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
                $form->note->setValue($casDmdDet['cdmd_note']);
            }
            
            $this->view ->form =  $form;
            $this->view->payoptionDet = $payoptionDet;
    }
    
    
    public function deletedemandAction() {
        $this->view->response('ajax');
                
        require_once __DIR__ . '/../admin/!model/cashdemand.php';
        $cashDmdObj = new cashdemand();
        
        $decRefd = $this->view->decode($this->view->param['ref']);
        
        if (! $decRefd)
            die('tampered');
                    
            $this->view->casDmdDetDetail= $casDmdDet = $cashDmdObj->getCashDmdById($decRefd);
            
            if ($_POST) {
                if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                    die('---'); // exit script outputting json data
                } else {
                    $delete = $cashDmdObj->deleteDemand ( $decRefd );
                    if ($delete) {
                        $this->view->NoViewRender = true;
                        $success = array (
                            'feedback' => 'The demand has been deleted from the system  .'
                        );
                        $_SESSION ['feedback'] = 'The demand has been deleted from the system';
                        $success = json_encode ( $success );
                        die ( $success );
                    }
                }
            }
            else{
                //die("no post");
            }
            
            $this->view->form  = $form;
    }
    
}