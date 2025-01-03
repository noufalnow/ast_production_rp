<?php

class legalcaseController extends mvc
{

    protected $stslist = array(
        1 => 'OPEN - DRAFT',
        3 => 'OPEN - SCHEDULED FOR HEARING',
        4 => 'OPEN - UNDER REVIEW',
        5 => 'OPEN - RESOLVED',
        6 => 'PENDING',
        7 => 'CLOSED',
        8 => 'DISMISSED',
        9 => 'APPEAL FILED'
    );

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/legalcase.php';
        $form = new form();
        $legalcase = new legalcase();

        $form->addElement('lcas_type', 'Case Type', 'select', 'required', array(
            'options' => array(
                1 => 'RENT DISPUTE',
                2 => 'CHECK RETURN',
                3 => 'ACCIDENT',
                4 => 'OTHERS'
            )
        ));

        $form->addElement('lcas_party', 'Name', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="namecl"'
        ));

        $form->addElement('lcas_phone_no', 'Phone Number', 'number', 'required|numeric', "", array(
            '' => 'readonly',
            'onfocus' => "this.removeAttribute('readonly')",
            '' => 'autocomplete="nphecl"'
        ));

        $form->addElement('lcas_office', 'Office', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="offmecl"'
        ));

        $form->addElement('lcas_lawer', 'Lawer', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="lawmecl"'
        ));

        $form->addElement('lcas_email', 'Email', 'text', 'valid_email', "", array(
            '' => 'autocomplete="emailcl"'
        ));
        $form->addElement('lcas_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));

        $form->addElement('lcas_case', 'Case Details', 'textarea', 'required');

        $form->addElement('lcas_sts', 'Case Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $logdt = DateTime::createFromFormat(DF_DD, $valid['lcas_date']);
                    $logdt = date_format($logdt, DFS_DB);

                    $data = array(
                        'lcas_type' => $valid['lcas_type'],
                        'lcas_party' => $valid['lcas_party'],
                        'lcas_phone_no' => $valid['lcas_phone_no'],
                        'lcas_office' => $valid['lcas_office'],
                        'lcas_lawer' => $valid['lcas_lawer'],
                        'lcas_email' => $valid['lcas_email'],
                        'lcas_date' => $logdt,
                        'lcas_emp' => USER_ID,
                        'lcas_case' => $valid['lcas_case'],
                        'lcas_sts' => $valid['lcas_sts']
                    );

                    $legalcaseId = $legalcase->add($data);

                    if ($legalcaseId) {

                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();

                        $data = array(
                            'doc_type' => DOC_TYPE_COM_LCASE,
                            'doc_ref_type' => DOC_TYPE_COM_LCASE,
                            'doc_ref_id' => $legalcaseId
                        );
                        $lcaseDoc = $docs->add($data);
                        if ($lcaseDoc) {
                            $upload = uploadFiles(DOC_TYPE_COM_LCASE, $lcaseDoc, $valid['my_files']);
                        }

                        $feedback = 'Legal Case added successfully';
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
        include __DIR__ . '/../admin/!model/legalcase.php';
        $legalcase = new legalcase();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $legalcaseId = $this->view->decode($this->view->param['ref']);

        if (! $legalcaseId)
            die('tampered');

        $form->addElement('lcas_type', 'Case Type', 'select', 'required', array(
            'options' => array(
                1 => 'RENT DISPUTE',
                2 => 'CHECK RETURN',
                3 => 'ACCIDENT',
                4 => 'OTHERS'
            )
        ));

        $form->addElement('lcas_party', 'Name', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="namecl"'
        ));

        $form->addElement('lcas_phone_no', 'Phone Number', 'number', 'required|numeric', "", array(
            '' => 'readonly',
            'onfocus' => "this.removeAttribute('readonly')",
            '' => 'autocomplete="nphecl"'
        ));

        $form->addElement('lcas_office', 'Office', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="offmecl"'
        ));

        $form->addElement('lcas_lawer', 'Lawer', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="lawmecl"'
        ));

        $form->addElement('lcas_email', 'Email', 'text', 'valid_email', "", array(
            '' => 'autocomplete="emailcl"'
        ));
        $form->addElement('lcas_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));

        $form->addElement('lcas_case', 'Case Details', 'textarea', 'required');

        $form->addElement('lcas_sts', 'Case Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        $legalcaseDetails = $legalcase->getLegalCaseDetailsById([
            'lcas_id' => $legalcaseId
        ]);

        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $form->addFile('my_files', 'Document', array(
                    'required' => false,
                    'exten' => 'pdf',
                    'size' => 5375000
                ));

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $logdt = DateTime::createFromFormat(DF_DD, $valid['lcas_date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'lcas_type' => $valid['lcas_type'],
                        'lcas_party' => $valid['lcas_party'],
                        'lcas_phone_no' => $valid['lcas_phone_no'],
                        'lcas_office' => $valid['lcas_office'],
                        'lcas_lawer' => $valid['lcas_lawer'],
                        'lcas_email' => $valid['lcas_email'],
                        'lcas_date' => $logdtDb,
                        'lcas_case' => $valid['lcas_case'],
                        'lcas_sts' => $valid['lcas_sts']
                    );

                    $modifyLegalCase = $legalcase->modify($data, $legalcaseDetails['lcas_id']);
                    if ($modifyLegalCase) {

                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();
                        $file = new files();

                        if ($valid['my_files']) {
                            if ($legalcaseDetails['docsid']) {
                                $docs->deleteDocument($legalcaseDetails['docsid']);
                                deleteFile($legalcaseDetails['fileid']);
                                $file->deleteFile($legalcaseDetails['docsid']);
                            }

                            $data = array(
                                'doc_type' => DOC_TYPE_COM_LCASE,
                                'doc_ref_type' => DOC_TYPE_COM_LCASE,
                                'doc_ref_id' => $legalcaseId
                            );
                            $lcaseDoc = $docs->add($data);
                            if ($lcaseDoc) {
                                $upload = uploadFiles(DOC_TYPE_COM_LCASE, $lcaseDoc, $valid['my_files']);
                            }
                        }

                        $feedback = 'Call log updated successfully';
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
            // Assuming $legalcaseDetails contains the data to pre-fill the form
            if (isset($legalcaseDetails)) {

                $form->lcas_type->setValue($legalcaseDetails['lcas_type']);
                $form->lcas_party->setValue($legalcaseDetails['lcas_party']);
                $form->lcas_office->setValue($legalcaseDetails['lcas_office']);

                $form->lcas_phone_no->setValue($legalcaseDetails['lcas_phone_no']);
                $form->lcas_lawer->setValue($legalcaseDetails['lcas_lawer']);
                $form->lcas_email->setValue($legalcaseDetails['lcas_email']);
                // Convert database date format to display format (if required)
                $logDate = DateTime::createFromFormat(DFS_DB, $legalcaseDetails['lcas_date']);
                $form->lcas_date->setValue($logDate ? $logDate->format(DF_DD) : '');
                $form->lcas_case->setValue($legalcaseDetails['lcas_case']);
                $form->lcas_sts->setValue($legalcaseDetails['lcas_sts']);
                $this->view->encFileId = $this->view->encode($legalcaseDetails['fileid']);
            }
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        include __DIR__ . '/../admin/!model/legalcase.php';
        include __DIR__ . '/../admin/!model/legalcasefollow.php';

        $form = new form();

        $form->addElement('f_name', 'Party Name', 'text', 'alpha_space');
        $form->addElement('f_lawer_name', 'Lawer Name', 'text', 'alpha_space');

        $form->addElement('f_lcas_type', 'Case Type', 'select', '', array(
            'options' => array(
                1 => 'RENT DISPUTE',
                2 => 'CHECK RETURN',
                3 => 'ACCIDENT',
                4 => 'OTHERS'
            )
        ));

        $form->addElement('f_status', 'Case Status', 'select', '', array(
            'options' => $this->stslist
        ));

        $form->addElement('f_date', 'Date', 'text', '', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
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
                    'f_name' => @$valid['f_name'],
                    'f_lawer_name' => @$valid['f_lawer_name'],
                    'f_lcas_type' => @$valid['f_lcas_type'],
                    'f_status' => @$valid['f_status'],
                    'f_date' => @$valid['f_date']
                );
            }
            $filter_class = 'btn-info';
        }

        $legalcaseObj = new legalcase();
        $legalcaseList = $legalcaseObj->getLegalCasePaginate(@$where);

        $legalcasefollowObj = new legalcasefollow();

        $this->view->legalcaseObj = $legalcaseObj;
        $this->view->legalcasefollowObj = $legalcasefollowObj;

        $this->view->legalcaseList = $legalcaseList;
        $this->view->form = $form;
        $this->view->filter_class = $filter_class;
    }

    public function fupaddAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/legalcasefollow.php';
        $form = new form();
        $legalcaseFollow = new legalCaseFollow();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $legalcaseId = $this->view->decode($this->view->param['ref']);

        if (! $legalcaseId)
            die('tampered');

        include __DIR__ . '/../admin/!model/legalcase.php';
        $legalcaseObj = new legalcase();
        $legalcaseDetails = $legalcaseObj->getLegalCaseById($legalcaseId);

        // Add form elements
        $form->addElement('lcflo_update', 'Follow-Up / Updates', 'textarea', 'required', "", array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('lcflo_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('lcflo_sts', 'Current Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // Convert date format
                    $logdt = DateTime::createFromFormat(DF_DD, $valid['lcflo_date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'lcflo_lcas_id' => $legalcaseId,
                        'lcflo_update' => $valid['lcflo_update'],
                        'lcflo_date' => $logdtDb,
                        'lcflo_emp' => USER_ID,
                        'lcflo_sts' => $valid['lcflo_sts']
                    );

                    $followupId = $legalcaseFollow->add($data);

                    if ($followupId) {

                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();

                        $data = array(
                            'doc_type' => DOC_TYPE_COM_LCASE_UPD,
                            'doc_ref_type' => DOC_TYPE_COM_LCASE_UPD,
                            'doc_ref_id' => $followupId
                        );
                        $lcaseDocUpd = $docs->add($data);
                        if ($lcaseDocUpd) {
                            $upload = uploadFiles(DOC_TYPE_COM_LCASE_UPD, $lcaseDocUpd, $valid['my_files']);
                        }

                        $feedback = 'Follow-up log added successfully';
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

    public function fupeditAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/legalcasefollow.php';
        $form = new form();
        $legalcaseFollow = new legalCaseFollow();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $followupId = $this->view->decode($this->view->param['ref']);

        if (! $followupId) {
            die('tampered');
        }

        // Retrieve follow-up details
        $followupDetails = $legalcaseFollow->getFollowDetails(['lcflo_id'=>$followupId]);

        if (! $followupDetails) {
            die('Follow-up not found');
        }

        $form->addElement('lcflo_update', 'Follow-Up / Updates', 'textarea', 'required', "", array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('lcflo_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('lcflo_sts', 'Current Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf',
            'size' => 5375000
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // Convert date format
                    $logdt = DateTime::createFromFormat(DF_DD, $valid['lcflo_date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'lcflo_update' => $valid['lcflo_update'],
                        'lcflo_date' => $logdtDb,
                        'lcflo_sts' => $valid['lcflo_sts']
                    );

                    $updated = $legalcaseFollow->modify($data, $followupId);

                    if ($updated) {

                        require_once __DIR__ . '/../admin/!model/documents.php';
                        $docs = new documets();
                        $file = new files();

                        if ($valid['my_files']) {
                            if ($followupDetails['docsid']) {
                                $docs->deleteDocument($followupDetails['docsid']);
                                deleteFile($followupDetails['fileid']);
                                $file->deleteFile($followupDetails['docsid']);
                            }

                            $data = array(
                                'doc_type' => DOC_TYPE_COM_LCASE_UPD,
                                'doc_ref_type' => DOC_TYPE_COM_LCASE_UPD,
                                'doc_ref_id' => $followupId
                            );
                            $lcaseDocUpd = $docs->add($data);
                            if ($lcaseDocUpd) {
                                $upload = uploadFiles(DOC_TYPE_COM_LCASE_UPD, $lcaseDocUpd, $valid['my_files']);
                            }
                        }

                        include __DIR__ . '/../admin/!model/legalcase.php';
                        $legalcaseObj = new legalcase();

                        $feedback = 'Follow-up log updated successfully';
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
            // Pre-fill form with existing values
            $form->lcflo_update->setValue($followupDetails['lcflo_update']);
            $form->lcflo_date->setValue(DateTime::createFromFormat(DFS_DB, $followupDetails['lcflo_date'])->format(DF_DD));
            $form->lcflo_sts->setValue($followupDetails['lcflo_sts']);
            $this->view->encFileId = $this->view->encode($followupDetails['fileid']);
        }

        $this->view->form = $form;
    }
}
