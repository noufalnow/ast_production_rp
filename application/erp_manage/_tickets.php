<?php

function tkt_list()
{
    
    require_once '../model/tickets.php';
    $form = new form();
    

    // $customerObj =new customer();
    // $customerList = $customerObj->getCustomerPair();

    $form->addElement('f_customer', 'Customer', 'select', '', array(
        'options' => $customerList
    ));
    $form->addElement('f_company', 'Company', 'select', '', array(
        'options' => $customerList
    ));
    $form->addElement('f_ticketno', 'Ticket No', 'text', '');
    $form->addElement('f_paymode', 'Payment Type', 'select', '', array(
        'options' => array(
            1 => "Cash",
            2 => "Credit"
        )
    ));
    $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
        '' => 'readonly'
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
                'f_company' => @$valid['f_company'],
                'f_customer' => @$valid['f_customer'],
                'f_ticketno' => @$valid['f_ticketno'],
                'f_paymode' => @$valid['f_paymode'],
                'f_monthpick' => @$valid['f_monthpick']
            );
        }
        $filter_class = 'btn-info';
    }

    $ticketsObj = new tickets();

    $ticketsList = $ticketsObj->getTicketsPaginate(@$where);
    $offset = $ticketsObj->_voffset;
  
    return array(
        'form' => $form,
        'ticketsList' => $ticketsList,
        'ticketsObj' => $ticketsObj,
        'filter_class' => $filter_class,
        'offset' => $offset
    );
}

function tkt_add()
{
    require_once "../popheadder.php";

$form = new form();
    require_once '../model/documents.php';
    $docs = new documets();

    $form->addElement('category', 'Add New Category', 'text', 'alpha_space');
    $form->addElement('particulers', 'Ticket Details', 'textarea', 'required');
    $form->addElement('amount', 'Budget Amount', 'float', 'numeric', '', array(
        'class' => 'fig'
    ));

    require_once '../model/company.php';
    $compModelObj = new company();
    $compList = $compModelObj->getCompanyPair();

    require_once '../model/employee.php';
    $empModelObj = new employee();
    $empList = $empModelObj->getEmployeePair($cond);

    require_once '../model/property.php';
    $propModelObj = new property();
    $propList = $propModelObj->getPropetyPair();

    require_once '../model/vehicle.php';
    $vehModelObj = new vehicle();
    $vehList = $vehModelObj->getVehiclePair();

    require_once '../model/ticketscat.php';
    $catModelObj = new ticketscat();
    $catList = $catModelObj->getCategoryPair();

    $catList["-1"] = "--Add New Category--";

    $form->addElement('company', 'Company', 'select', 'required', array(
        'options' => $compList
    ));
    $form->addElement('catSelect', 'Category', 'select', 'required', array(
        'options' => $catList
    ));
    $form->addElement('priority', 'Priority', 'select', 'required', array(
        'options' => array(
            1 => "Low",
            2 => "Medium",
            3 => "High",
            4 => "Emergency"
        )
    ));
    $form->addElement('mainhead', 'Main Head', 'select', 'required', array(
        'options' => array(
            1 => "Employee",
            2 => "Property",
            3 => "Vehicle"
        )
    ));
    $form->addElement('datetime', 'Event Date/Time', 'text', 'required', '', array(
        '' => 'readonly',
        'class' => 'date_time_picker'
    ));
    $form->addElement('completeby', 'Expected Time', 'text', '', '', array(
        '' => 'readonly',
        'class' => 'date_time_picker'
    ));
    $form->addElement('reportedby', 'Reported By', 'text', 'alpha_space|required');
    $form->addElement('mobile', 'Mobile No', 'number', 'required');
    $form->addElement('altmobile', 'Alternate No', 'number', '');
    $form->addElement('atntime1', 'Time From', 'text', '');
    $form->addElement('atntime2', 'Time To', 'text', '');
    $form->addElement('assignedto', 'Assigned To', 'select', 'required', array(
        'options' => $empList
    ));
    $count = 1;
    if (isset($_POST) && count($_POST) > 0) {
        $count = max(array_keys($_POST['employee']), array_keys($_POST['property']), array_keys($_POST['vehicle']));
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

    $form->addMultiElement('mdesc', 'Description', 'text', '', '', array(
        'class' => ''
    ), $count);

    if ($_POST['mainhead'] == 1)
        $mfields = array_keys($form->_elements['employee']);
    elseif ($_POST['mainhead'] == 2)
        $mfields = array_keys($form->_elements['property']);
    if ($_POST['mainhead'] == 3)
        $mfields = array_keys($form->_elements['vehicle']);
    else
        $mfields = array_keys($form->_elements['employee']);

    $scount = 1;
    if (isset($_POST) && count($_POST) > 0)
        $scount = array_keys($_POST['msteps']);

    $form->addMultiElement('msteps', 'Steps', 'text', '', '', array(
        'class' => ''
    ), $scount);
    $form->addMultiElement('mdate', 'Date', 'text', '', '', array(
        'class' => 'date_picker',
        '' => 'readonly'
    ), $scount);
    $form->addMultiElement('mexeby', 'Executed by', 'select', '', array(
        'options' => $empList
    ), array(
        'class' => 'full-select'
    ), $scount);
    $smfields = array_keys($form->_elements['msteps']);

    $form->addFile('img1', 'Document', array(
        'required' => false,
        'exten' => 'pdf;doc;docx;jpg;png',
        'size' => 5375000
    ));

    if (isset($_POST) && count($_POST) > 0) {

        if ($_POST['catSelect'] == '-1')
            $form->addRules('category', 'required|alpha_space');

        $form->addErrorMsg('mdesc', 'required', ' ');

        $form->addErrorMsg('msteps', 'required', ' ');
        $form->addErrorMsg('mdate', 'required', ' ');
        $form->addErrorMsg('mexeby', 'required', ' ');

        $form->addErrorMsg('employee', 'required', ' ');
        $form->addErrorMsg('property', 'required', ' ');
        $form->addErrorMsg('vehicle', 'required', ' ');

        foreach ($mfields as $i) {
            if ($_POST['mainhead'] == 1) {
                // if ($_POST ['employee'] [$i] != '' && $_POST ['mdesc'] [$i] == '')
                // $form->addmRules ( 'mdesc', $i, 'numeric|required' );

                if ($_POST['mdesc'][$i] != '' && $_POST['employee'][$i] == '')
                    $form->addmRules('employee', $i, 'required');
            }
            if ($_POST['mainhead'] == 2) {
                // if ($_POST ['property'] [$i] != '' && $_POST ['mdesc'] [$i] == '')
                // $form->addmRules ( 'mdesc', $i, 'numeric|required' );

                if ($_POST['mdesc'][$i] != '' && $_POST['property'][$i] == '')
                    $form->addmRules('property', $i, 'required');
            }
            if ($_POST['mainhead'] == 3) {
                // if ($_POST ['vehicle'] [$i] != '' && $_POST ['mdesc'] [$i] == '')
                // $form->addmRules ( 'mdesc', $i, 'numeric|required' );

                if ($_POST['mdesc'][$i] != '' && $_POST['vehicle'][$i] == '')
                    $form->addmRules('vehicle', $i, 'required');
            }
        }

        $sfields = array_keys($form->_elements['msteps']);
        foreach ($sfields as $i) {
            if ($_POST['msteps'][$i] != '' && $_POST['mdate'][$i] == '')
                $form->addmRules('mdate', $i, 'required');
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

            require_once '../model/tickets.php';
            $ticketsObj = new tickets();
            ;

            if ($valid['catSelect'] == - 1 && $valid['category'] != '') {
                $pCatDet = $catModelObj->getCategoryByName(array(
                    'tcat_name' => $valid['category']
                ));
                if (! $pCatDet['cat_id']) {
                    $catData = array(
                        'tcat_name' => $valid['category']
                    );
                    $catId = $catModelObj->add($catData);
                } else
                    $catId = $pCatDet['cat_id'];
            } else
                $catId = $valid['catSelect'];

            $datetime = DateTime::createFromFormat(DTF_DD, $valid['datetime']);
            $datetime = date_format($datetime, DF_DB);

            $data = array(
                'tkt_company' => $valid['company'],
                'tkt_priority' => $valid['priority'],
                'tkt_reported' => $valid['reportedby'],
                'tkt_mob1' => $valid['mobile'],
                'tkt_mob2' => $valid['altmobile'],
                'tkt_cat' => $catId,
                'tkt_dttime_strt' => $datetime,
                'tkt_details' => $valid['particulers'],
                'tkt_assign' => $valid['assignedto'],
                'tkt_mainhead' => $valid['mainhead']
            );

            if ($valid['completeby']) {
                $datetimef = DateTime::createFromFormat(DTF_DD, $valid['completeby']);
                $datetimef = date_format($datetimef, DF_DB);
                $data['tkt_dttime_end'] = $datetimef;
            }

            if ($valid['amount'])
                $data['tkt_budjet'] = $valid['amount'];

            if ($valid['atntime1'])
                $data['tkt_vtime_srt'] = $valid['atntime1'];

            if ($valid['tkt_budjet'])
                $data['tkt_vtime_end'] = $valid['atntime2'];

            require_once '../model/ticketsmhref.php';
            $ticketRefObj = new ticketsmhref();

            $insert = $ticketsObj->add($data);
            if ($insert) {

                $refData = array_values($refData);
                if (count($refData) > 0)
                    foreach ($refData as $rfkey => $rData) {
                        $data = array();
                        if ($rData) {
                            $data = array(
                                'tref_tkt_id' => $insert,
                                'tref_main_head' => $valid['mainhead'],
                                'tref_main_head_ref' => $rData,
                                'tref_note' => $valid['mdesc'][$rfkey]
                            );
                            $det = $ticketRefObj->add($data);
                        }
                    }

                require_once '../model/ticketssteps.php';
                $ticketStepObj = new ticketssteps();

                $stepsData = $valid['msteps'];

                $stepsData = array_values($stepsData);
                if (count($stepsData) > 0)
                    foreach ($stepsData as $rfkey => $rData) {
                        $data = array();
                        if ($rData) {

                            $datetStep = null;

                            $data = array(
                                'stp_ticket_id' => $insert,
                                'stp_steps' => $valid['msteps'][$rfkey]
                            );

                            if ($valid['mexeby'][$rfkey]) {
                                $data['stp_by'] = $valid['mexeby'][$rfkey];
                            }

                            if ($valid['mdate'][$rfkey]) {
                                $datetStep = DateTime::createFromFormat(DF_DD, $valid['mdate'][$rfkey]);
                                $datetStep = date_format($datetStep, DF_DB);
                                $data['stp_dttime'] = $datetStep;
                            }

                            $det = $ticketStepObj->add($data);
                        }
                    }

                if ($valid['img1']) {
                    $upload = uploadFiles(DOC_TYPE_TKT, $insert, $valid['img1']);
                    if ($upload) {
                        $form->reset();
                        $_SESSION['feedback'] = 'Ticket details added successfully';
                        echo ("<script LANGUAGE='JavaScript'>
		    				window.location.href='" . APPURL . 'popupclose.php' . "';
		    				</script>");
                    } else {
                        $_SESSION['feedback'] = 'Unable to upload file';
                        echo ("<script LANGUAGE='JavaScript'>
		    				window.location.href='" . APPURL . 'popupclose.php' . "';
		    				</script>");
                    }
                }
                $form->reset();
                $_SESSION['feedback'] = 'Ticket details added successfully';
                echo ("<script LANGUAGE='JavaScript'>
		    				window.location.href='" . APPURL . 'popupclose.php' . "';
		    				</script>");
            }
        }
    } else {
        $form->priority->setValue(2);
        $form->company->setValue(1);
    }
        
    return array(
        'form' => $form,
        'mfields' => $mfields,
        'smfields' => $smfields,
    );
    
}