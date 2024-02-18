<?php

class documentsController extends mvc
{

    public function getliveAction()
    {
        $this->view->response('ajax');
        if ($_POST) {
            if ($_POST) {
                if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                    die('---');
                } else {

                    // s($_POST);

                    require_once __DIR__ . '/../admin/!model/documents.php';
                    $documentsObj = new documets();
                    $documentList = $documentsObj->getDocumentsPair(array(
                        'doc_type' => DOC_TYPE_COM_AGR,
                        'doc_ref_id' => $_POST['refId'],
                        'doc_ref_type' => DOC_TYPE_COM
                    ));

                    if (count($documentList))
                        foreach ($documentList as $key => $val)
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

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documentsObj = new documets();
        $formRender = true;
        $form = new form();

        $decRefId = $this->view->decode($this->view->param['ref']);
        $type = $this->view->decode($this->view->param['type']);

        if (! $type)
            die('tampered');

        if (! $decRefId)
            die('tampered');

        $form->addElement('refno', 'Reference No ', 'text', 'required');
        $form->addElement('fileno', 'File No', 'text', 'required');

        $form->addElement('desc', 'Description ', 'textarea', 'required');
        $form->addElement('title', 'Title ', 'text', 'required');

        $form->addElement('startDt', 'Start Date', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('endDt', 'End Date', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf;PDF',
            'size' => 3097152
        ));

        if ($_POST) {

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                $dtWef = DateTime::createFromFormat(DF_DD, $valid['startDt']);
                $dtWef = $dtWef->format(DFS_DB);

                $data = array(
                    'doc_type' => $type,
                    'doc_ref_type' => DOC_TYPE_COM,
                    'doc_ref_id' => $decRefId,
                    'doc_no' => $valid['refno'],
                    'doc_issue_date' => $dtWef,
                    'doc_remarks' => $valid['title'],
                    'doc_desc' => $valid['desc'],
                    'agr_idno' => $valid['fileno']
                );

                if ($valid['endDt']) {
                    $dtEnd = DateTime::createFromFormat(DF_DD, $valid['endDt']);
                    $dtEnd = $dtEnd->format(DFS_DB);
                    $data['doc_expiry_date'] = $dtEnd;
                }

                $insert = $documentsObj->add($data);
                if ($insert) {
                    $upload = uploadFiles($type, $insert, $valid['my_files']);
                    if ($upload) {
                        $form->reset();
                        $feedback = 'Document details added successfully';
                    } else {
                        $feedback = 'Unable to upload file';
                    }

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

        $this->view->form = $form;
    }

    public function documentsAction()
    {
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documentsObj = new documets();
        $documentList = $documentsObj->getCommonDocuments(array(
            'doc_type' => $this->view->param['type'],
            'doc_ref_id' => $this->view->param['ref']
        ));

        $this->view->documentList = $documentList;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/documents.php';
        $documentsObj = new documets();
        $formRender = true;

        $form = new form();

        $decDocId = $this->view->decode($this->view->param['ref']);
        $type = $this->view->decode($this->view->param['type']);
        $docDetails = $documentsObj->getDocumentDetails(array(
            'doc_id' => $decDocId,
            'doc_ref_type' => $type
        ));

        $form->addElement('refno', 'Reference No ', 'text', 'required');
        $form->addElement('fileno', 'File No', 'text', 'required');

        $form->addElement('desc', 'Description ', 'textarea', 'required');
        $form->addElement('title', 'Title ', 'text', 'required');

        $form->addElement('startDt', 'Start Date', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('endDt', 'End Date', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));

        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf;PDF',
            'size' => 3097152
        ));

        if ($_POST) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                $dtWef = DateTime::createFromFormat(DF_DD, $valid['startDt']);
                $dtWef = $dtWef->format(DFS_DB);

                $data = array(
                    'doc_no' => $valid['refno'],
                    'doc_issue_date' => $dtWef,
                    'doc_remarks' => $valid['title'],
                    'doc_desc' => $valid['desc'],
                    'agr_idno' => $valid['fileno']
                );

                if ($valid['endDt']) {
                    $dtEnd = DateTime::createFromFormat(DF_DD, $valid['endDt']);
                    $dtEnd = $dtEnd->format(DFS_DB);
                    $data['doc_expiry_date'] = $dtEnd;
                }

                $update = $documentsObj->modify($data, $decDocId);
                if ($update) {

                    if ($valid['my_files']) {

                        if (! empty($docDetails['file_id'])) {
                            $file = new files();
                            deleteFile($docDetails['file_id']);
                            $file->deleteFile($docDetails['file_id']);
                        }

                        $upload = uploadFiles($docDetails['doc_type'], $decDocId, $valid['my_files']);
                        if ($upload) {
                            $form->reset();
                            $feedback = 'Document details updated successfully';
                        } else {
                            $feedback = 'Unable to upload file';
                        }

                        $this->view->NoViewRender = true;
                        $success = array(
                            'feedback' => $feedback
                        );
                        $_SESSION['feedback'] = $feedback;
                        $success = json_encode($success);
                        die($success);
                    }

                    $feedback = 'Document details updated successfully';
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
            if ($docDetails['doc_issue_date']) {
                $dos = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
                $dos = $dos->format(DF_DD);
            }
            if ($docDetails['doc_expiry_date']) {
                $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
                $doe = $doe->format(DF_DD);
            }
            $form->title->setValue($docDetails['doc_remarks']);
            $form->desc->setValue($docDetails['doc_desc']);
            $form->refno->setValue($docDetails['doc_no']);
            $form->startDt->setValue($dos);
            $form->endDt->setValue($doe);
            $form->fileno->setValue($docDetails['agr_idno']);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        require_once "../headder.php";
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();

        $form = new form();

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        if (count(array_filter($_GET)) > 0) {
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
        $updateList = $updatesObj->getUpdatePaginate(@$where);
        $offset = $updatesObj->_voffset;
       
        $this->view->updateList = $updateList;
        $this->view->updatesObj = $updatesObj;
        $this->view->offset = $offset;
        
    }

    public function XXXstatusAction()
    {
        require_once "../mheadder.php";
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();

        $formRender = true;
        $form = new form();

        $decUpdId = $this->view->decode($this->view->param['ref']);

        $countDet = $countDet[0];

        if (! $decUpdId)
            die('tampered');

        $form->addElement('status', 'Status', 'checkbox', 'required', array(
            'options' => array(
                "100" => "Closed"
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
                        'upd_close_date' => date_format(new DateTime(), 'Y-m-d H:i:s'),
                        'upd_status' => $valid['status'],
                        'upd_close_note' => $valid['note'],
                        'upd_close_by' => USER_ID
                    );
                    $update = $updatesObj->modify($data, $decUpdId);
                    if ($update) {

                        $count = $updatesObj->getOpenUpdatesCount();
                        $_SESSION['upd_count'] = $count['count'];

                        $feedback = 'Update status updated successfully';

                        $formRender = false;
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
}
