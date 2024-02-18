<?php

class companyController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');

        $form = new form();
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();

        $compList = $compModelObj->getCompanyPair();
        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => array(
                '1' => "CR CERTIFICATE",
                '2' => "CR ID",
                '3' => "SIGNATORY",
                '4' => "ID CARD 1",
                '5' => "ID CARD 2",
                '61' => "PINK CERTIFICATE 1",
                '62' => "PINK CERTIFICATE 2",
                '63' => "PINK CERTIFICATE 3",
                '64' => "PINK CERTIFICATE 4",
                '65' => "PINK CERTIFICATE 5"
            )
        ));

        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('doi', 'Date of issue ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => true,
            'exten' => 'pdf;doc;docx;jpg;png',
            'size' => 5375000
        ));

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $checkDoc = $docs->getDocumentsByType(array(
                    "doc_type" => $valid['doctype'],
                    "doc_ref_type" => DOC_TYPE_COMP,
                    "doc_ref_id" => $valid['company']
                ));
                if (count($checkDoc) > 0) {
                    $form->doctype->setError("Selected document already added to the company");
                } else {
                    $valid = $form->vaidate($_POST, $_FILES);
                    $valid = $valid[0];
                    if ($valid == true) {

                        if ($valid['doi']) {
                            $doi = DateTime::createFromFormat(DF_DD, $valid['doi']);
                            $doi = date_format($doi, DFS_DB);
                        }

                        $doe = DateTime::createFromFormat(DF_DD, $valid['doe']);
                        $doe = date_format($doe, DFS_DB);

                        $data = array(
                            "doc_type" => $valid['doctype'],
                            "doc_ref_type" => DOC_TYPE_COMP,
                            'doc_ref_id' => $valid['company'],
                            'doc_no' => $valid['docno'],
                            'doc_expiry_date' => $doe
                        );
                        if ($valid['docdesc'])
                            $data['doc_desc'] = $valid['docdesc'];
                        if ($doi)
                            $data['doc_issue_date'] = $doi;

                        $insert = $docs->add($data);
                        if ($insert) {
                            $upload = uploadFiles(DOC_TYPE_COMP, $insert, $valid['my_files']);
                            if ($upload) {
                                $form->reset();

                                $feedback = $_SESSION['feedback'] = 'Company documents added successfully';
                                $this->view->NoViewRender = true;
                                $success = array(
                                    'feedback' => $feedback
                                );
                                $success = json_encode($success);
                                die($success);
                            }
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

        $form = new form();
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();

        $encDocId = $this->view->decode($this->view->param['ref']);

        if (! $encDocId)
            die('tampered');
        
        $docDetails = $docs->getDocumentDetails(array(
            'doc_id' => $encDocId,
            'doc_ref_type' => DOC_TYPE_COMP
        ));
        
        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();

        $compList = $compModelObj->getCompanyPair();

        $form->addElement('doctype', 'Document Type', 'select', 'required', array(
            'options' => array(
                '1' => "CR CERTIFICATE",
                '2' => "CR ID",
                '3' => "SIGNATORY",
                '4' => "ID CARD 1",
                '5' => "ID CARD 2",
                '61' => "PINK CERTIFICATE 1",
                '62' => "PINK CERTIFICATE 2",
                '63' => "PINK CERTIFICATE 3",
                '64' => "PINK CERTIFICATE 4",
                '65' => "PINK CERTIFICATE 5"
            )
        ));

        $form->addElement('company', 'Company', 'select', 'required', array(
            'options' => $compList
        ));
        $form->addElement('docno', 'Document No ', 'text', 'required');
        $form->addElement('docdesc', 'Description', 'textarea', '');
        $form->addElement('doi', 'Date of issue ', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('doe', 'Date of expiry ', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addFile('my_files', 'Document', array(
            'required' => false,
            'exten' => 'pdf;doc;docx;jpg;png',
            'size' => 5375000
        ));

        if (isset($_POST) && count($_POST) > 0) {
            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {
                $checkDoc = $docs->getDocumentsByType(array(
                    "doc_type" => $valid['doctype'],
                    "doc_ref_type" => DOC_TYPE_COMP,
                    "doc_ref_id" => $valid['company'],
                    "doc_id_exclude" => $encDocId
                ));
                if (count($checkDoc) > 0) {
                    $form->doctype->setError("Selected document already added to the company");
                } else {
                    $valid = $form->vaidate($_POST, $_FILES);
                    $valid = $valid[0];
                    if ($valid == true) {

                        if ($valid['doi']) {
                            $doi = DateTime::createFromFormat(DF_DD, $valid['doi']);
                            $doi = date_format($doi, DFS_DB);
                        }

                        $doe = DateTime::createFromFormat(DF_DD, $valid['doe']);
                        $doe = date_format($doe, DFS_DB);

                        $data = array(
                            "doc_type" => $valid['doctype'],
                            "doc_ref_type" => DOC_TYPE_COMP,
                            'doc_ref_id' => $valid['company'],
                            'doc_no' => $valid['docno'],
                            'doc_expiry_date' => $doe
                        );
                        if ($valid['docdesc'])
                            $data['doc_desc'] = $valid['docdesc'];
                        if ($doi)
                            $data['doc_issue_date'] = $doi;

                        $update = $docs->modify($data, $encDocId);

                        if ($valid['my_files']) {

                            if (! empty($docDetails['file_id'])) {
                                $file = new files();
                                deleteFile($docDetails['file_id']);
                                $file->deleteFile($docDetails['file_id']);
                            }

                            if ($update) {
                                $upload = uploadFiles(DOC_TYPE_COMP, $encDocId, $valid['my_files']);
                                $form->reset();
                                $feedback = $_SESSION['feedback'] = 'Company documents updated successfully';
                                $this->view->NoViewRender = true;
                                $success = array(
                                    'feedback' => $feedback
                                );
                                $success = json_encode($success);
                                die($success);
                            }
                        }

                        $feedback = $_SESSION['feedback'] = 'Company documents updated successfully';
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
            $form->docno->setValue($docDetails['doc_no']);

            if ($docDetails['doc_issue_date']) {
                $doi = DateTime::createFromFormat(DFS_DB, $docDetails['doc_issue_date']);
                $doi = $doi->format(DF_DD);
                $form->doi->setValue($doi);
            }

            $doe = DateTime::createFromFormat(DFS_DB, $docDetails['doc_expiry_date']);
            $doe = $doe->format(DF_DD);

            $form->doe->setValue($doe);

            $form->docdesc->setValue($docDetails['doc_no']);
            $form->company->setValue($docDetails['doc_ref_id']);
            $form->doctype->setValue($docDetails['doc_type']);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/company.php';

        $companyObj = new company();
        $compList = $companyObj->getCompanyList();
        $offset = $companyObj->_voffset;

        require_once __DIR__ . '/../admin/!model/bankaccount.php';
        $bankAccount = new bankaccount();
        $bankAccountList = $bankAccount->getAccountList();

        $bankList = array(
            '1' => "Bank Muscat",
            '2' => 'Bank Dhofar',
            "3" => "NBO",
            "4" => "OAB",
            "5" => "HSBC",
            "6" => "FAB",
            '7' => 'Bank Sohar',
            '8' => 'SBI',
            '9' => 'Bank of Baroda'
        );

        $this->view->compList=$compList;
        $this->view->companyObj=$companyObj;
        $this->view->offset=$offset;
        $this->view->bankAccountList=$bankAccountList;
        $this->view->bankList=$bankList;
    }
        
}