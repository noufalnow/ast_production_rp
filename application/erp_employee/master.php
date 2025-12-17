<?php

class masterController extends mvc
{

    protected $doctype = array(
        '1' => "Passport",
        '2' => "Resident ID",
        '3' => "Visa",
        '4' => "License",
        '5' => "Insurance",
        '6' => "PDO License",
        '7' => "PDO Passport",
        '8' => "H2S Card",
        '9' => "OXY Passport",
        '10' => "OXY License",
        '11' => "OXY H2S",
        '12' => "Work Contract",
        '13' => "Third party Insurance",
        '14' => "Fitness Medical Report",
        '15' => "Opal Medical",
        '16' => "Opal LC",
        '17' => "Opal Passport",
        '18' => "Opal Safety Certificate"
    );

    public function addAction()
    {
        $this->view->response('ajax');
        $form = new form();
        $form->addElement('empno', 'Employee no ', 'text', 'required|numeric');
        $form->addElement('fileno', 'File no ', 'text', 'required');
        $form->addElement('fname', 'First name ', 'text', 'required|alpha_space');
        $form->addElement('mname', 'Middle name ', 'text', 'alpha_space');
        $form->addElement('lname', 'Last name ', 'text', 'alpha_space');
        $form->addElement('nation', 'Nationality', 'select', 'required', array(
            'options' => array(
                1 => "Oman",
                2 => "India",
                3 => "Pakistan",
                4 => "Bangladesh"
            )
        ));
        $form->addElement('dob', 'Date of birth ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doj', 'Date of joining ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('mobile', 'Mobile No ', 'number', 'required|numeric');
        $form->addElement('nativeno', 'Native Mobile No ', 'text', 'numeric');

        $form->addElement('bank', 'Bank Name ', 'text', 'alpha_space');
        $form->addElement('branch', 'Branch Name ', 'text', 'alpha_space');
        $form->addElement('accno', 'Accont No ', 'text', 'alpha_space');

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();

        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/comp_department.php';
        $compDeptModelObj = new comp_department();
        $compDeptList = $compDeptModelObj->getCompDeptPair();

        $form->addElement('dept', 'Department', 'select', 'required', array(
            'options' => $compDeptList
        ));

        require_once __DIR__ . '/../admin/!model/designation.php';
        $desigModelObj = new designation();
        $desigList = $desigModelObj->getDesigPair();

        $form->addElement('desig', 'Designation', 'select', 'required', array(
            'options' => $desigList
        ));

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                require_once __DIR__ . '/../admin/!model/employee.php';
                $employee = new employee();
                $emp = $employee->getEmployee(array(
                    'emp_no' => $valid['empno']
                ));
                if ($emp['emp_id']) {
                    $form->empno->setError("Employee no already selected for other user");
                } else {
                    $dob = DateTime::createFromFormat(DF_DD, $valid['dob']);
                    $dob = date_format($dob, DFS_DB);

                    if (! empty($valid['doj'])) {
                        $doj = DateTime::createFromFormat(DF_DD, $valid['doj']);
                        $doj = date_format($doj, DFS_DB);
                    }

                    $data = array(
                        'emp_no' => $valid['empno'],
                        'emp_fileno' => $valid['fileno'],
                        'emp_fname' => $valid['fname'],
                        'emp_mname' => $valid['mname'],
                        'emp_lname' => $valid['lname'],
                        'emp_nationality' => $valid['nation'],
                        'emp_dob' => $dob,
                        'emp_comp_dept' => $valid['dept'],
                        'emp_desig' => $valid['desig'],
                        'emp_mobileno' => $valid['mobile'],
                        'emp_nativeno' => $valid['nativeno'],
                        'emp_bank' => $valid['bank'],
                        'emp_branch' => $valid['branch'],
                        'emp_accountno' => $valid['accno']
                    );
                    if ($doj) {
                        $data['emp_doj'] = $doj;
                    }

                    $insert = $employee->add($data);
                    if ($insert) {
                        $feedback = 'Employee details added successfully';

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

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/employee.php';
        $this->view->NoViewRender = false;

        $form = new form();
        $empObj = new employee();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        if (! $decEmpId)
            die('tampered');

        $form->addElement('empno', 'Employee no ', 'text', 'required|numeric');
        $form->addElement('fileno', 'File no ', 'text', 'required');
        $form->addElement('fname', 'First name ', 'text', 'required|alpha_space');
        $form->addElement('mname', 'Middle name ', 'text', 'alpha_space');
        $form->addElement('lname', 'Last name ', 'text', 'alpha_space');
        $form->addElement('nation', 'Nationality', 'select', 'required', array(
            'options' => array(
                1 => "Oman",
                2 => "India",
                3 => "Pakistan",
                4 => "Bangladesh"
            )
        ));
        $form->addElement('dob', 'Date of birth ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doj', 'Date of joining ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('mobile', 'Mobile No ', 'text', 'required|numeric');
        $form->addElement('nativeno', 'Native Mobile No ', 'text', 'numeric');

        $form->addElement('bank', 'Bank Name ', 'text', 'alpha_space');
        $form->addElement('branch', 'Branch Name ', 'text', 'alpha_space');
        $form->addElement('accno', 'Accont No ', 'text', 'alpha_space');

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();

        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/comp_department.php';
        $compDeptModelObj = new comp_department();
        $compDeptList = $compDeptModelObj->getCompDeptPair();

        $form->addElement('dept', 'Department', 'select', 'required', array(
            'options' => $compDeptList
        ));

        require_once __DIR__ . '/../admin/!model/designation.php';
        $desigModelObj = new designation();
        $desigList = $desigModelObj->getDesigPair();

        $form->addElement('desig', 'Designation', 'select', 'required', array(
            'options' => $desigList
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    require_once __DIR__ . '/../admin/!model/employee.php';
                    $emp = $empObj->getEmployee(array(
                        'emp_no' => $valid['empno'],
                        'ex_emp_id' => $decEmpId
                    ));
                    if ($emp['emp_id']) {
                        $form->empno->setError("Employee no already selected for other user");
                    } else {
                        $dob = DateTime::createFromFormat(DF_DD, $valid['dob']);
                        $dob = $dob->format(DFS_DB);

                        $data = array(
                            'emp_no' => $valid['empno'],
                            'emp_fileno' => $valid['fileno'],
                            'emp_fname' => $valid['fname'],
                            'emp_mname' => $valid['mname'],
                            'emp_lname' => $valid['lname'],
                            'emp_nationality' => $valid['nation'],
                            'emp_dob' => $dob,
                            'emp_comp_dept' => $valid['dept'],
                            'emp_desig' => $valid['desig'],
                            'emp_mobileno' => $valid['mobile'],
                            'emp_nativeno' => $valid['nativeno'],
                            'emp_bank' => $valid['bank'],
                            'emp_branch' => $valid['branch'],
                            'emp_accountno' => $valid['accno']
                        );

                        if ($valid['doj']) {
                            $doj = DateTime::createFromFormat(DF_DD, $valid['doj']);
                            $doj = $doj->format(DFS_DB);
                            $data['emp_doj'] = $doj;
                        }

                        $update = $empObj->modify($data, $decEmpId);
                        if ($update) {
                            $feedback = 'User details updated successfully';

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
        } else {
            $empDetails = $empObj->getEmployee(array(
                'emp_id' => $decEmpId
            ));

            $dob = DateTime::createFromFormat(DFS_DB, $empDetails['emp_dob']);
            $dob = $dob->format(DF_DD);

            if ($empDetails['emp_doj'] != '') {
                $doj = DateTime::createFromFormat(DFS_DB, $empDetails['emp_doj']);
                $doj = $doj->format(DF_DD);
            }

            $form->empno->setValue($empDetails['emp_no']);
            $form->fname->setValue($empDetails['emp_fname']);
            $form->mname->setValue($empDetails['emp_mname']);
            $form->lname->setValue($empDetails['emp_lname']);
            $form->nation->setValue($empDetails['emp_nationality']);
            $form->dob->setValue($dob);
            $form->doj->setValue($doj);
            $form->company->setValue($empDetails['cmpdept_comp_id']);
            $form->dept->setValue($empDetails['emp_comp_dept']);
            $form->desig->setValue($empDetails['emp_desig']);
            $form->fileno->setValue($empDetails['emp_fileno']);
            $form->mobile->setValue($empDetails['emp_mobileno']);
            $form->nativeno->setValue($empDetails['emp_nativeno']);
            $form->bank->setValue($empDetails['emp_bank']);
            $form->branch->setValue($empDetails['emp_branch']);
            $form->accno->setValue($empDetails['emp_accountno']);
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        require_once __DIR__ . '/../admin/!model/employee.php';
        $this->view->NoViewRender = false;

        $form = new form();
        $employee = new employee();

        $decEmployeeId = $this->view->decode($this->view->param['ref']);

        if (! $decEmployeeId)
            die('tampered');

        $employeeDetail = $employee->getEmployeeById($decEmployeeId);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $delete = $employee->deleteEmployee($decEmployeeId);
                if ($delete) {
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => 'The employee has been deleted successfully from the system  .'
                    );
                    $_SESSION['feedback'] = 'The employee has been deleted successfully from the system ';
                    $success = json_encode($success);
                    die($success);
                }
            }
        }

        return array(
            'form' => $form,
            'formRender' => $this->view->formRender,
            'employeeDetail' => $employeeDetail
        );
    }

    public function empcontactAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/contacts.php';
        $empContObj = new contacts();

        $form = new form();
        $display = 'display: none;';

        $empId = $this->view->decode($this->view->param['ref']);

        $nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );
        if (! $empId)
            die('tampered');

        $empContacts = $empContObj->getContacts(array(
            'con_ref_type' => CONT_TYPE_EMP,
            'con_ref_id' => $empId
        ));

        // s($empContacts);

        $conType = array(
            '1' => "Residence",
            '2' => "Overseas",
            '3' => "Currespondance"
        );

        $form->addElement('contype', 'Contact Type', 'select', 'required', array(
            'options' => $conType
        ));
        $form->addElement('name', 'Name(C/o) ', 'text', 'alpha_space');
        $form->addElement('house', 'House Name /No ', 'text', 'required');
        $form->addElement('street1', 'Street Address 1', 'text', 'required');
        $form->addElement('street2', 'Street Address 2', 'text', '');
        $form->addElement('place', 'Place Name ', 'text', 'required');
        $form->addElement('locality', 'Locality', 'text', '');
        $form->addElement('region', 'Region', 'text', '');
        $form->addElement('country', 'Country', 'select', 'required', array(
            'options' => array(
                1 => "Oman",
                2 => "India",
                3 => "Pakistan",
                4 => "Bangladesh"
            )
        ));
        $form->addElement('zip', 'Zip Code', 'text', '', '');
        $form->addElement('phone', 'Phone No', 'text', 'alpha_space');
        $form->addElement('mobile', 'Mobile No', 'text', 'alpha_space');

        if ($_POST && $_POST['tab'] == 'contact') {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                $data = array(
                    'con_type' => $valid['contype'],
                    'con_ref_type' => CONT_TYPE_EMP,
                    'con_ref_id' => $empId,
                    'con_name' => $valid['name'],
                    'con_house' => $valid['house'],
                    'con_street1' => $valid['street1'],
                    'con_street2' => $valid['street2'],
                    'con_place' => $valid['place'],
                    'con_locality' => $valid['locality'],
                    'con_region' => $valid['region'],
                    'con_country' => $valid['country'],
                    'con_zip_code' => $valid['zip'],
                    'con_phone' => $valid['phone'],
                    'con_mobile' => $valid['mobile']
                );
                $insert = $empContObj->add($data);
                if ($insert) {
                    $form->reset();
                    $this->view->feedback = 'Employee contact details added successfully';
                    $this->view->url = APPURL . "erp_employee/master/empcontact/ref/" . $this->view->param['ref'];
                    $this->view->status = 11;
                    $this->view->target = "menu1";
                }
            } else {
                $display = '';
            }
        }
        // echo $this->view->response;

        $this->view->form = $form;
        $this->view->display = $display;
        $this->view->empContacts = $empContacts;
        $this->view->conType = $conType;
        $this->view->nation = $nation;
        // $this->view->reload= $reload;
    }

    public function empcontacteditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/contacts.php';
        $this->view->NoViewRender = false;
        $form = new form();
        $contact = new contacts();

        $conType = array(
            '1' => "Residence",
            '2' => "Overseas",
            '3' => "Currespondance"
        );

        $form->addElement('contype', 'Contact Type', 'select', 'required', array(
            'options' => $conType
        ));
        $form->addElement('name', 'Name(C/o) ', 'text', 'alpha_space');
        $form->addElement('house', 'House Name /No ', 'text', 'required');
        $form->addElement('street1', 'Street Address 1', 'text', 'required');
        $form->addElement('street2', 'Street Address 2', 'text', '');
        $form->addElement('place', 'Place Name ', 'text', 'required');
        $form->addElement('locality', 'Locality', 'text', '');
        $form->addElement('region', 'Region', 'text', '');
        $form->addElement('country', 'Country', 'select', 'required', array(
            'options' => array(
                1 => "Oman",
                2 => "India",
                3 => "Pakistan",
                4 => "Bangladesh"
            )
        ));
        $form->addElement('zip', 'Zip Code', 'text', '', '');
        $form->addElement('phone', 'Phone No', 'text', 'alpha_space');
        $form->addElement('mobile', 'Mobile No', 'text', 'alpha_space');

        $conId = $this->view->decode($this->view->param['ref']);

        if (! $conId)
            die('tampered');

        $contactDetails = $contact->getContactById($conId);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    $data = array(
                        'con_name' => $valid['name'],
                        'con_house' => $valid['house'],
                        'con_street1' => $valid['street1'],
                        'con_street2' => $valid['street2'],
                        'con_place' => $valid['place'],
                        'con_locality' => $valid['locality'],
                        'con_region' => $valid['region'],
                        'con_country' => $valid['country'],
                        'con_zip_code' => $valid['zip'],
                        'con_phone' => $valid['phone'],
                        'con_mobile' => $valid['mobile']
                    );
                    $update = $contact->modify($data, $conId);
                    if ($update) {
                        $this->view->feedback = 'Contact details updated successfully';
                        $this->view->url = APPURL . "erp_employee/master/empcontact/ref/" . $this->view->encode($contactDetails['con_ref_id']);
                        $this->view->status = 11;
                        $this->view->target = "menu1";
                    }
                }
            }
        } else {

            $form->contype->setValue($contactDetails['con_type']);
            $form->name->setValue($contactDetails['con_name']);
            $form->house->setValue($contactDetails['con_house']);
            $form->street1->setValue($contactDetails['con_street1']);
            $form->street2->setValue($contactDetails['con_street2']);
            $form->place->setValue($contactDetails['con_place']);
            $form->locality->setValue($contactDetails['con_locality']);
            $form->region->setValue($contactDetails['con_region']);
            $form->country->setValue($contactDetails['con_country']);
            $form->zip->setValue($contactDetails['con_zip_code']);
            $form->phone->setValue($contactDetails['con_phone']);
            $form->mobile->setValue($contactDetails['con_mobile']);
        }

        $this->view->form = $form;
    }

    public function empcontractAction()
    {}

    public function empcontracteditAction()
    {}

    public function empcontractupdateAction()
    {}

    public function empdocsAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();

        $form = new form();
        $docDisplay = 'display: none;';
        $imgDisplay = 'display: none;';

        $empId = $this->view->decode($this->view->param['ref']);

        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $this->doctype
        ));
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('auth', 'Issue Authority ', 'text', '');
        $form->addElement('doa', 'Date of apply ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doi', 'Date of issue ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addFile('my_files', 'my_filesument', array(
            'required' => true,
            'exten' => 'pdf;doc;docx;jpg;png',
            'size' => 3375000
        ));
        $form->addElement('alert', 'Alert Days', 'text', 'numeric');

        $imgform = new form();
        $imgform->addFile('photo', 'Photo', array(
            'required' => true,
            'exten' => 'png;jpg',
            'size' => 1000000
        ));

        $empImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_EMP,
            'doc_ref_id' => $empId
        ));
        $empImage = $empImage['0'];

        if (! $empId)
            die('tampered');
        if ($_POST && $_POST['tab'] == 'doc') {
            if (! empty($_POST['image'])) {

                $imValid = $imgform->vaidate($_POST, $_FILES);
                $imValid = $imValid[0];
                if ($imValid == true) {

                    $data = array(
                        'doc_type' => 5, // @todo image document type create constants
                        'doc_ref_type' => DOC_IMG_EMP,
                        'doc_ref_id' => $empId
                    );

                    $insert = $docs->add($data);
                    if ($insert) {
                        if (! empty($empImage['doc_id'])) {
                            $delete = $docs->deleteDocument($empImage['doc_id']);
                            if (! empty($empImage['file_id'])) {
                                $file = new files();
                                deleteFile($empImage['file_id']);
                                $file->deleteFile($empImage['file_id']);
                            }
                        }
                        $upload = uploadFiles(DOC_IMG_EMP, $insert, $imValid['photo']);

                        $form->reset();
                        $this->view->url = APPURL . "erp_employee/master/empdocs/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";

                        if ($upload) {
                            $this->view->feedback = 'Employee images added successfully';
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
                        'doc_ref_type' => DOC_TYPE_EMP,
                        'doc_ref_id' => $empId,
                        'doc_no' => $valid['docno'],
                        'doc_expiry_date' => $doe
                    );
                    if ($valid['docdesc'])
                        $data['doc_desc'] = $valid['docdesc'];
                    if ($valid['docremark'])
                        $data['doc_remarks'] = $valid['docremark'];
                    if ($valid['auth'])
                        $data['doc_issue_auth'] = $valid['auth'];
                    if ($doa)
                        $data['doc_apply_date'] = $doa;
                    if ($doi)
                        $data['doc_issue_date'] = $doi;
                    if ($valid['alert'])
                        $data['doc_alert_days'] = $valid['alert'];

                    $insert = $docs->add($data);
                    if ($insert) {
                        $upload = uploadFiles(DOC_TYPE_EMP, $insert, $valid['my_files']);

                        $form->reset();
                        $this->view->url = APPURL . "erp_employee/master/empdocs/ref/" . $this->view->param['ref'];
                        $this->view->status = 11;
                        $this->view->target = "menu2";

                        if ($upload) {
                            $this->view->feedback = 'Employee documents added successfully';
                        } else {
                            $this->view->feedback = 'Employee documents added successfully;Unable to upload file';
                        }
                    }
                } else {
                    $docDisplay = '';
                }
            }
        }

        foreach ($this->doctype as $dtkey => $dlabel) {
            $tdocs = $docs->getDocuments(array(
                'doc_type' => $dtkey,
                'doc_ref_type' => DOC_TYPE_EMP,
                'doc_ref_id' => $empId
            ));

            if (is_array($tdocs) && count($tdocs) > 0) {
                $docList[$dtkey] = $tdocs;
            }

            $tdocs = [];
        }

        $this->view->docList = $docList;
        $this->view->doctype = $this->doctype;

        $ppDocs = $docs->getDocuments(array(
            'doc_type' => 1,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $wpDocs = $docs->getDocuments(array(
            'doc_type' => 2,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $visaDocs = $docs->getDocuments(array(
            'doc_type' => 3,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $dlDocs = $docs->getDocuments(array(
            'doc_type' => 4,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));

        $insDocs = $docs->getDocuments(array(
            'doc_type' => 5,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $pdodlDocs = $docs->getDocuments(array(
            'doc_type' => 6,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $pdoppDocs = $docs->getDocuments(array(
            'doc_type' => 7,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $h2sDocs = $docs->getDocuments(array(
            'doc_type' => 8,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));

        $oxyppDocs = $docs->getDocuments(array(
            'doc_type' => 9,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $oxyLicDocs = $docs->getDocuments(array(
            'doc_type' => 10,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));
        $oxyH2sDocs = $docs->getDocuments(array(
            'doc_type' => 11,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));

        $workContrDocs = $docs->getDocuments(array(
            'doc_type' => 12,
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $empId
        ));

        // var_dump($empId);

        $this->view->form = $form;
        $this->view->imgform = $imgform;
        $this->view->docDisplay = $docDisplay;
        $this->view->imgDisplay = $imgDisplay;
        $this->view->reload = $reload;
        $this->view->empImage = $empImage;
        $this->view->workContrDocs = $workContrDocs;
        $this->view->oxyH2sDocs = $oxyH2sDocs;
        $this->view->oxyLicDocs = $oxyLicDocs;
        $this->view->oxyppDocs = $oxyppDocs;
        $this->view->h2sDocs = $h2sDocs;
        $this->view->pdoppDocs = $pdoppDocs;
        $this->view->pdodlDocs = $pdodlDocs;
        $this->view->insDocs = $insDocs;
        $this->view->ppDocs = $ppDocs;
        $this->view->visaDocs = $visaDocs;
        $this->view->wpDocs = $wpDocs;
        $this->view->dlDocs = $dlDocs;
        $this->view->target = "menu2";
    }

    public function empdocseditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $form = new form();
        $docs = new documets();

        $empDocId = $this->view->decode($this->view->param['ref']);

        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $empDocId,
            'doc_ref_type' => DOC_TYPE_EMP
        ));

        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => $this->doctype
        ));
        $this->view->response('ajax');
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('docremark', 'Remarks ', 'text', '');
        $form->addElement('auth', 'Issue Authority ', 'text', '');
        $form->addElement('doa', 'Date of apply ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doi', 'Date of issue ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('docUpdate', 'Update Document', 'checkbox', '', array(
            'options' => array(
                1 => "Update Document"
            )
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 5375000
        ));
        $form->addElement('alert', 'Alert Days', 'text', 'numeric');

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
                    'doc_expiry_date' => $doe
                );
                if ($valid['docdesc'])
                    $data['doc_desc'] = $valid['docdesc'];
                if ($valid['docremark'])
                    $data['doc_remarks'] = $valid['docremark'];
                if ($valid['auth'])
                    $data['doc_issue_auth'] = $valid['auth'];
                if ($doa)
                    $data['doc_apply_date'] = $doa;
                if ($doi)
                    $data['doc_issue_date'] = $doi;
                if ($valid['alert'])
                    $data['doc_alert_days'] = $valid['alert'];

                $update = $docs->modify($data, $empDocId);

                if ($update) {
                    $this->view->feedback = 'Document details updated successfully ';

                    if ($valid['docUpdate'] == 1) {
                        if (! empty($docDetails['file_id'])) {
                            $file = new files();
                            deleteFile($docDetails['file_id']);
                            $file->deleteFile($docDetails['file_id']);
                        }

                        $upload = uploadFiles(DOC_TYPE_EMP, $empDocId, $valid['my_files']);

                        if ($upload) {
                            $form->reset();
                            $this->view->feedback = 'Document details modified successfully';
                        } else {
                            $this->view->feedback .= 'Unable to upload file';
                        }
                    }

                    $this->view->url = APPURL . "erp_employee/master/empdocs/ref/" . $this->view->encode($docDetails['doc_ref_id']);
                    $this->view->status = 11;
                    // $this->view->target = "menu2";
                }
            } else {
                $docDisplay = '';
            }
        } else {

            $form->doctype->setValue($docDetails['doc_type']);
            $form->docno->setValue($docDetails['doc_no']);

            if ($docDetails['doc_issue_date']) {
                $doi = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
                $doi = $doi->format(DF_DD);
            }
            $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
            $doe = $doe->format(DF_DD);

            $form->doi->setValue($doi);
            $form->doe->setValue($doe);

            $form->docremark->setValue($docDetails['doc_no']);
            $form->alert->setValue($docDetails['doc_alert_days']);
        }

        $this->view->form = $form;
    }

    public function empdocviewAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';

        $documates = new documets();

        $decDocId = $this->view->decode($this->view->param['ref']);

        if (! $decDocId)
            die('tampered');

        $documatesDetail = $documates->getDocumentDetails(array(
            'doc_id' => $decDocId,
            'doc_ref_type' => DOC_TYPE_EMP
        ));

        $docMst = $this->doctype;

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

    public function empdocdeleteAction()
    {
        require_once __DIR__ . '/../admin/!model/documents.php';
        $this->view->response('ajax');
        $document = new documets();

        $decDocumentId = $this->view->decode($this->view->param['ref']);

        if (! $decDocumentId)
            die('tampered');

        $documentDetail = $document->getDocumentDetails(array(
            'doc_id' => $decDocumentId,
            'doc_ref_type' => DOC_TYPE_EMP
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

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/employee.php';
        $form = new form();
        $form->addElement('f_name', 'Name ', 'text', '');
        $form->addElement('f_fileno', 'File No ', 'text', '');

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $compList = $compModelObj->getCompanyPair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/comp_department.php';
        $compDeptModelObj = new comp_department();
        $compDeptList = $compDeptModelObj->getCompDeptPair();
        $form->addElement('f_dept', 'Department', 'select', '', array(
            'options' => $compDeptList
        ));

        require_once __DIR__ . '/../admin/!model/designation.php';
        $desigModelObj = new designation();
        $desigList = $desigModelObj->getDesigPair();
        $form->addElement('f_desig', 'Designation', 'select', '', array(
            'options' => $desigList
        ));

        $nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );
        $form->addElement('f_natonality', 'Nationality', 'select', '', array(
            'options' => $nation
        ));
        $form->addElement('f_status', 'Status', 'select', '', array(
            'options' => array(
                1 => "Leave",
                3 => "Resigned",
                4 => "Terminated"
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
                    'f_fileno' => @$valid['f_fileno'],
                    'f_name' => @$valid['f_name'],
                    'f_company' => @$valid['f_company'],
                    'f_dept' => @$valid['f_dept'],
                    'f_desig' => @$valid['f_desig'],
                    'f_natonality' => @$valid['f_natonality'],
                    'f_status' => @$valid['f_status']
                );
            }
            $filter_class = 'btn-info';
        }

        $empObj = new employee();
        $employeeList = $empObj->getEmployeesPaginate(@$where);

        $this->view->employeeList = $employeeList;
        $this->view->form = $form;
        $this->view->empObj = $empObj;
        $this->view->filter_class = $filter_class;
    }

    public function profileAction()
    {
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/employeepay.php';
        require_once __DIR__ . '/../admin/!model/contacts.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $empPayObj = new employeepay();

        $employee = new Employee();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        // echo "______________ ".$crypt->semiencode(2637);

        if (! $decEmpId)
            die('tampered');

        $employeeDetail = $employee->getEmployee(array(
            "emp_id" => $decEmpId
        ));

        $dob = DateTime::createFromFormat(DFS_DB, $employeeDetail['emp_dob']);
        $dob = $dob->format(DF_DD);

        if ($employeeDetail['emp_doj']) {
            $doj = DateTime::createFromFormat(DFS_DB, $employeeDetail['emp_doj']);
            $doj = $doj->format(DF_DD);
        }

        $this->view->desig = array(
            1 => "Admin",
            2 => "Manager",
            3 => "Employee"
        );
        $this->view->dept = array(
            1 => "Head Office",
            2 => "Construction"
        );
        $this->view->status = array(
            1 => "Enable",
            2 => "Disable"
        );
        $this->view->nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );

        $this->view->empDocs = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $decEmpId
        ));
        $this->view->empSal = $empPayObj->getEmployeePay(array(
            'pay_emp_id' => $decEmpId
        ));
        // $empContact= $empContObj->getContactsByRef(array('con_ref_type'=>CONT_TYPE_EMP,'con_ref_id'=>$decEmpId));
        $empImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_EMP,
            'doc_ref_id' => $decEmpId
        ));
        $empImage = $empImage['0'];

        if ($_POST) {
            $tab = $_POST['tab'];
            switch ($tab) {
                case 'doc':
                    $this->view->doc_class = 'active';
                    $this->view->doc_active = 'active in';
                    break;
                case 'sal':
                    $this->view->sal_class = 'active';
                    $this->view->sal_active = 'active in';
                    break;
                case 'contact':
                    $this->view->contact_class = 'active';
                    $this->view->contact_active = 'active in';
                    break;
                case 'profile':
                    $this->view->profile_class = 'active';
                    $this->view->profile_active = 'active in';
                    break;
            }
        } else {
            $this->view->hom_class = 'active';
            $this->view->hom_active = 'active in';
        }
        $this->view->docMst = $this->doctype;
        $this->view->payParticulers = array(
            '0' => "Starting",
            '1' => "Increment",
            '2' => "Promotion",
            '3' => "Appraisal",
            '4' => "Decrement",
            '5' => "Demotion"
        );
        $this->view->contactType = array(
            '1' => "Residence",
            '2' => "Overseas",
            '3' => "Currespondance"
        );

        require_once __DIR__ . '/../admin/!model/empstatus.php';
        $empStsObj = new empstatus();
        $this->view->empStatus = $empStsObj->getEmpStatusByEmpId(array(
            'sts_emp_id' => $employeeDetail['emp_id']
        ));


        $payParticulers = array(
            '0' => "Starting",
            '1' => "Increment",
            '2' => "Promotion",
            '3' => "Appraisal",
            '4' => "Decrement",
            '5' => "Demotion"
        );

        $this->view->employeeDetail = $employeeDetail;
        $this->view->decEmpId = $decEmpId;
        $this->view->empImage = $empImage;
        $this->view->response('ajax');
        $this->view->payParticulers = $payParticulers;
    }

    public function statusAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/empstatus.php';
        require_once __DIR__ . '/../admin/!model/employee.php';
        $this->view->NoViewRender = false;

        $form = new form();
        $empStsObj = new empstatus();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        if (! $decEmpId)
            die('tampered');

        $form->addElement('statusType', 'Type', 'select', 'required', array(
            'options' => array(
                1 => "Leave",
                2 => "Re Join",
                3 => "Resigned",
                4 => "Terminated"
            )
        ), array(
            '' => 'onchange="toggle_field(this);"'
        ));
        $form->addElement('desc', 'Description ', 'textarea', 'required');

        $form->addElement('dtRequest', 'Date of Request ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dtApproval', 'Date of Approval ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addElement('dtWif', 'With Effect From ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dtEnd', 'Leave End Date', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addFile('my_files', 'Leave Documents', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                if ($_POST['statusType'] == 1)
                    $form->addRules("dtEnd", 'required');

                if ($_POST['statusType'] != 1)
                    $form->addFile('my_files', 'Leave Documents', array(
                        'required' => false,
                        'exten' => 'pdf',
                        'size' => 5375000
                    ));

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    // s($valid);

                    if ($valid['dtRequest']) {
                        $dtReq = DateTime::createFromFormat(DF_DD, $valid['dtRequest']);
                        $dtReq = $dtReq->format(DFS_DB);
                    }

                    if ($valid['dtApproval']) {
                        $dtAppr = DateTime::createFromFormat(DF_DD, $valid['dtApproval']);
                        $dtAppr = $dtAppr->format(DFS_DB);
                    }

                    $dtWef = DateTime::createFromFormat(DF_DD, $valid['dtWif']);
                    $dtWef = $dtWef->format(DFS_DB);

                    if ($valid['dtEnd']) {
                        $dtEnd = DateTime::createFromFormat(DF_DD, $valid['dtEnd']);
                        $dtEnd = $dtEnd->format(DFS_DB);
                    }

                    $data = array(
                        'sts_type' => $valid['statusType'],
                        'sts_emp_id' => $decEmpId,
                        'sts_remarks' => $valid['desc'],
                        'sts_apply_date' => $dtReq,
                        'sts_approval_date' => $dtAppr,
                        'sts_start_date' => $dtWef,
                        'sts_end_date' => $dtEnd
                    );
                    $insert = $empStsObj->add($data);
                    if ($insert) {

                        $empStatus = '';
                        if ($valid['statusType'] == 3 || $valid['statusType'] == 4)
                            $empStatus = 2;
                        else if ($valid['statusType'] == 2)
                            $empStatus = 1;
                        if ($empStatus) {
                            $empObj = new employee();
                            $empObj->modify(array(
                                'emp_status' => $empStatus
                            ), $decEmpId);
                        }

                        if ($valid['my_files']) {

                            if ($valid['statusType'] == 1)
                                $documentType = DOC_TYPE_EMP_LVE;
                            else if ($valid['statusType'] == 2)
                                $documentType = DOC_TYPE_EMP_LVER;

                            require_once __DIR__ . '/../admin/!model/documents.php';
                            $docs = new documets();

                            $fdata = array(
                                'doc_type' => $documentType,
                                'doc_ref_type' => $documentType,
                                'doc_ref_id' => $insert
                            );
                            $leaveRpt = $docs->add($fdata);
                            if ($leaveRpt) {
                                $upload = uploadFiles($documentType, $leaveRpt, $valid['my_files']);
                            }
                        }

                        $feedback = 'Employee status updated successfully';

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
            /*
             * $empDetails = $empStsObj->getEmployee(array('emp_id'=>$decEmpId));
             *
             * $dob = DateTime::createFromFormat(DFS_DB,$empDetails['emp_dob']);
             * $dob = $dob->format(DF_DD);
             *
             * if($empDetails['emp_doj']!=''){
             * $doj = DateTime::createFromFormat(DFS_DB,$empDetails['emp_doj']);
             * $doj = $doj->format(DF_DD);
             * }
             *
             *
             * //$form->empno->setValue($empDetails['emp_no']);
             * //$form->dob->setValue($dob);
             * //$form->doj->setValue($doj);
             *
             */
        }

        $this->view->form = $form;
    }

    public function statuseditAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/empstatus.php';
        require_once __DIR__ . '/../admin/!model/employee.php';
        $this->view->NoViewRender = false;

        $form = new form();
        $empStsObj = new empstatus();

        $decStsId = $this->view->decode($this->view->param['ref']);

        if (! $decStsId)
            die('tampered');

        $stsDetails = $empStsObj->getStatusByStatusId([
            'sts_id' => $decStsId
        ]);

        $form->addElement('statusType', 'Type', 'select', 'required', array(
            'options' => array(
                1 => "Leave",
                2 => "Re Join",
                3 => "Resigned",
                4 => "Terminated"
            )
        ), array(
            '' => 'onchange="toggle_field(this);"'
        ));
        $form->addElement('desc', 'Description ', 'textarea', 'required');

        $form->addElement('dtRequest', 'Date of Request ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dtApproval', 'Date of Approval ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addElement('dtWif', 'With Effect From ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('dtEnd', 'Leave End Date', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addFile('my_files', 'Leave Documents', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                if ($_POST['statusType'] == 1)
                    $form->addRules("dtEnd", 'required');

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    // s($valid);

                    if ($valid['dtRequest']) {
                        $dtReq = DateTime::createFromFormat(DF_DD, $valid['dtRequest']);
                        $dtReq = $dtReq->format(DFS_DB);
                    } else
                        $dtReq = NULL;

                    if ($valid['dtApproval']) {
                        $dtAppr = DateTime::createFromFormat(DF_DD, $valid['dtApproval']);
                        $dtAppr = $dtAppr->format(DFS_DB);
                    } else
                        $dtAppr = NULL;

                    $dtWef = DateTime::createFromFormat(DF_DD, $valid['dtWif']);
                    $dtWef = $dtWef->format(DFS_DB);

                    if ($valid['dtEnd']) {
                        $dtEnd = DateTime::createFromFormat(DF_DD, $valid['dtEnd']);
                        $dtEnd = $dtEnd->format(DFS_DB);
                    } else
                        $dtEnd = NULL;

                    $data = array(
                        'sts_type' => $valid['statusType'],
                        'sts_remarks' => $valid['desc'],
                        'sts_apply_date' => $dtReq,
                        'sts_approval_date' => $dtAppr,
                        'sts_start_date' => $dtWef,
                        'sts_end_date' => $dtEnd
                    );
                    $update = $empStsObj->modify($data, $decStsId);
                    if ($update) {

                        $empStatus = '';
                        if ($valid['statusType'] == 3 || $valid['statusType'] == 4)
                            $empStatus = 2;
                        else if ($valid['statusType'] == 2)
                            $empStatus = 1;
                        if ($empStatus) {
                            $empObj = new employee();
                            $empObj->modify(array(
                                'emp_status' => $empStatus
                            ), $stsDetails['sts_emp_id']);
                        }

                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();
                        $file = new files();

                        if ($valid['my_files']) {
                            if ($stsDetails['docsid']) {
                                $docs->deleteDocument($stsDetails['docsid']);
                                deleteFile($stsDetails['fileid']);
                                $file->deleteFile($stsDetails['docsid']);
                            }

                            if ($valid['statusType'] == 1)
                                $documentType = DOC_TYPE_EMP_LVE;
                            else if ($valid['statusType'] == 2)
                                $documentType = DOC_TYPE_EMP_LVER;

                            require_once __DIR__ . '/../admin/!model/documents.php';
                            $docs = new documets();

                            $fdata = array(
                                'doc_type' => $documentType,
                                'doc_ref_type' => $documentType,
                                'doc_ref_id' => $decStsId
                            );
                            $leaveRpt = $docs->add($fdata);
                            if ($leaveRpt) {
                                $upload = uploadFiles($documentType, $leaveRpt, $valid['my_files']);
                            }
                        }

                        $feedback = 'Employee status modified successfully';

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

            $wef = DateTime::createFromFormat(DFS_DB, $stsDetails['sts_start_date']);
            $wef = $wef->format(DF_DD);

            if ($stsDetails['sts_apply_date'] != '') {
                $doa = DateTime::createFromFormat(DFS_DB, $stsDetails['sts_apply_date']);
                $doa = $doa->format(DF_DD);
            }
            if ($stsDetails['sts_approval_date'] != '') {
                $doap = DateTime::createFromFormat(DFS_DB, $stsDetails['sts_approval_date']);
                $doap = $doap->format(DF_DD);
            }
            if ($stsDetails['sts_end_date'] != '') {
                $doe = DateTime::createFromFormat(DFS_DB, $stsDetails['sts_end_date']);
                $doe = $doe->format(DF_DD);
            }
        }

        $form->statusType->setValue($stsDetails['sts_type']);
        $form->desc->setValue($stsDetails['sts_remarks']);
        $form->dtRequest->setValue($doa);
        $form->dtApproval->setValue($doap);
        $form->dtWif->setValue($wef);
        $form->dtEnd->setValue($doe);

        $this->view->form = $form;
    }

    public function leavestatusAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/empstatus.php';

        $empStsObj = new empstatus();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        if (! $decEmpId)
            die('tampered');

        $leaveDetail = $empStsObj->getLeaveSummary(array(
            'sts_emp_id' => $decEmpId
        ));
        $this->view->leaveDetail = $leaveDetail;
        // s($leaveDetail);
    }

    public function vehicleregAction()
    {
        $this->view->response('ajax');

        $form = new form();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        if (! $decEmpId)
            die('tampered');

        require_once __DIR__ . '/../admin/!model/employee.php';
        $employeeObj = new employee();
        $this->view->NoViewRender = false;

        $empDet = $employeeObj->getEmployeeById($decEmpId);

        $form->addElement('mulkia', 'Mulkia', 'text', 'required');
        $form->addElement('chasis', 'Chassis', 'text', 'required');
        $form->addElement('refno', 'Ref No', 'text', 'required');
        $form->addElement('remarks', 'Remarks', 'text', '');

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'emp_reg_mulkia' => $valid['mulkia'],
                        'emp_reg_chassis' => $valid['chasis'],
                        'emp_reg_refno' => $valid['refno'],
                        'emp_reg_remarks' => $valid['remarks']
                    );

                    $update = $employeeObj->modify($data, $decEmpId);

                    if ($update) {
                        $this->view->NoViewRender = true;
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
            $form->mulkia->setValue($empDet['emp_reg_mulkia']);
            $form->chasis->setValue($empDet['emp_reg_chassis']);
            $form->refno->setValue($empDet['emp_reg_refno']);
            $form->remarks->setValue($empDet['emp_reg_remarks']);
        }

        $this->view->form = $form;
    }

    public function viewAction()
    {
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/employeepay.php';
        require_once __DIR__ . '/../admin/!model/contacts.php';
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();
        $empPayObj = new employeepay();

        $employee = new Employee();

        $decEmpId = $this->view->decode($this->view->param['ref']);

        // echo "______________ ".$crypt->semiencode(2637);

        if (! $decEmpId)
            die('tampered');

        $employeeDetail = $employee->getEmployee(array(
            "emp_id" => $decEmpId
        ));

        $dob = DateTime::createFromFormat(DFS_DB, $employeeDetail['emp_dob']);
        $dob = $dob->format(DF_DD);

        if ($employeeDetail['emp_doj']) {
            $doj = DateTime::createFromFormat(DFS_DB, $employeeDetail['emp_doj']);
            $doj = $doj->format(DF_DD);
        }

        $this->view->desig = array(
            1 => "Admin",
            2 => "Manager",
            3 => "Employee"
        );
        $this->view->dept = array(
            1 => "Head Office",
            2 => "Construction"
        );
        $this->view->status = array(
            1 => "Enable",
            2 => "Disable"
        );
        $this->view->nation = array(
            1 => "Oman",
            2 => "India",
            3 => "Pakistan",
            4 => "Bangladesh"
        );

        $this->view->empDocs = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_TYPE_EMP,
            'doc_ref_id' => $decEmpId
        ));
        $this->view->empSal = $empPayObj->getEmployeePay(array(
            'pay_emp_id' => $decEmpId
        ));
        // $empContact= $empContObj->getContactsByRef(array('con_ref_type'=>CONT_TYPE_EMP,'con_ref_id'=>$decEmpId));
        $empImage = $docs->getTopDocumentsByRef(array(
            'doc_ref_type' => DOC_IMG_EMP,
            'doc_ref_id' => $decEmpId
        ));
        $empImage = $empImage['0'];

        if ($_POST) {
            $tab = $_POST['tab'];
            switch ($tab) {
                case 'doc':
                    $this->view->doc_class = 'active';
                    $this->view->doc_active = 'active in';
                    break;
                case 'sal':
                    $this->view->sal_class = 'active';
                    $this->view->sal_active = 'active in';
                    break;
                case 'contact':
                    $this->view->contact_class = 'active';
                    $this->view->contact_active = 'active in';
                    break;
                case 'profile':
                    $this->view->profile_class = 'active';
                    $this->view->profile_active = 'active in';
                    break;
            }
        } else {
            $this->view->hom_class = 'active';
            $this->view->hom_active = 'active in';
        }
        $this->view->docMst = $this->doctype;
        $this->view->payParticulers = array(
            '0' => "Starting",
            '1' => "Increment",
            '2' => "Promotion",
            '3' => "Appraisal",
            '4' => "Decrement",
            '5' => "Demotion"
        );
        $this->view->contactType = array(
            '1' => "Residence",
            '2' => "Overseas",
            '3' => "Currespondance"
        );

        require_once __DIR__ . '/../admin/!model/empstatus.php';
        $empStsObj = new empstatus();
        $this->view->empStatus = $empStsObj->getEmpStatusByEmpId(array(
            'sts_emp_id' => $employeeDetail['emp_id']
        ));


        $this->view->employeeDetail = $employeeDetail;
        $this->view->decEmpId = $decEmpId;
        $this->view->empImage = $empImage;
    }

    public function getliveAction()
    {
        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---');
            } else {
                require_once __DIR__ . '/../admin/!model/comp_department.php';
                $compDeptModelObj = new comp_department();
                $compDeptList = $compDeptModelObj->getCompDeptPair(array(
                    'cmpdept_comp_id' => $_POST['refId']
                ));

                if (count($compDeptList))
                    foreach ($compDeptList as $key => $val)
                        $data[] = array(
                            'key' => $key,
                            'value' => $val
                        );
                $data = json_encode($data);
                die($data);
            }
        }
    }
}
