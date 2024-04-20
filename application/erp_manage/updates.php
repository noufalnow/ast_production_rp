<?php

class updatesController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();
        

        $form = new form();

        $decRefId = $this->view->decode($this->view->param['ref']);
        $type = $this->view->decode($this->view->param['type']);

        if (! $type)
            die('tampered');

        if (! $decRefId)
            die('tampered');

        $form->addElement('priority', 'Priority', 'select', 'required', array(
            'options' => array(
                1 => "Low",
                2 => "Medium",
                3 => "High",
                4 => "Urgent"
            )
        ));
        $form->addElement('desc', 'Description ', 'textarea', 'required');
        $form->addElement('title', 'Title ', 'text', 'required');

        $form->addElement('startDt', 'Update Date', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('endDt', 'Activity End', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        
        $form->addElement('addRemainder', 'Add remainder', 'checkbox', '', array(
            'options' => array(
                1 => "Add to remainder"
            )
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $dtWef = DateTime::createFromFormat(DF_DD, $valid['startDt']);
                    $dtWef = $dtWef->format(DFS_DB);

                    if ($valid['endDt']) {
                        $dtEnd = DateTime::createFromFormat(DF_DD, $valid['endDt']);
                        $dtEnd = $dtEnd->format(DFS_DB);
                    }

                    $data = array(
                        'upd_priority' => $valid['priority'],
                        'upd_reported' => USER_ID,
                        'upd_assign' => USER_ID,
                        'upd_dttime' => $dtWef,
                        'upd_enddttime' => $dtEnd,
                        'upd_note' => $valid['desc'],
                        'upd_title' => $valid['title'],
                        'upd_type' => $type,
                        'upd_type_refid' => $decRefId,
                        'upd_remainder' => $valid ['addRemainder'] ==''? NULL : $valid ['addRemainder'],
                    );
                    $insert = $updatesObj->add($data);
                    if ($insert) {

                        $count = $updatesObj->getOpenUpdatesCount();
                        $_SESSION['upd_count'] = $count['count'];

                        $feedback = 'Updates marked successfully';

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
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();
        

        $form = new form();

        $decUpdId = $this->view->decode($this->view->param['ref']);

        if (! $decUpdId)
            die('tampered');

        $updDetails = $updatesObj->getUpdateById($decUpdId);

        $form->addElement('priority', 'Priority', 'select', 'required', array(
            'options' => array(
                1 => "Low",
                2 => "Medium",
                3 => "High",
                4 => "Urgent"
            )
        ));
        $form->addElement('desc', 'Description ', 'textarea', 'required');
        $form->addElement('title', 'Title ', 'text', 'required');

        $form->addElement('startDt', 'Update Date', 'text', 'required', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        $form->addElement('endDt', 'Activity End', 'text', '', '', array(
            '' => 'readonly',
            'class' => 'date_picker'
        ));
        
        $form->addElement('addRemainder', 'Add remainder', 'checkbox', '', array(
            'options' => array(
                1 => "Add to remainder"
            )
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $dtWef = DateTime::createFromFormat(DF_DD, $valid['startDt']);
                    $dtWef = $dtWef->format(DFS_DB);

                    if ($valid['endDt']) {
                        $dtEnd = DateTime::createFromFormat(DF_DD, $valid['endDt']);
                        $dtEnd = $dtEnd->format(DFS_DB);
                    }

                    $data = array(
                        'upd_priority' => $valid['priority'],
                        'upd_dttime' => $dtWef,
                        'upd_enddttime' => $dtEnd,
                        'upd_note' => $valid['desc'],
                        'upd_title' => $valid['title'],
                        'upd_remainder' => $valid ['addRemainder'] ==''? NULL : $valid ['addRemainder'],
                    );
                    $insert = $updatesObj->modify($data, $decUpdId);
                    if ($insert) {

                        $feedback = 'Updates modified successfully';

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

            $form->priority->setValue($updDetails['upd_priority']);
            $form->desc->setValue($updDetails['upd_note']);
            $form->title->setValue($updDetails['upd_title']);

            $dtWef = DateTime::createFromFormat(DF_DB, $updDetails['upd_dttime']);
            $dtWef = $dtWef->format(DF_DD);

            if ($updDetails['upd_enddttime']) {
                $dtEnd = DateTime::createFromFormat(DF_DB, $updDetails['upd_enddttime']);
                $dtEnd = $dtEnd->format(DF_DD);
            }

            $form->startDt->setValue($dtWef);
            $form->endDt->setValue($dtEnd);
            $form->addRemainder->setValue($updDetails['upd_remainder']);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
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

        $this->view->updateList=$updateList;
        $this->view->updatesObj=$updatesObj;
        $this->view->offset=$offset;
    }

    public function reportAction()
    {
        $this->view->response('window');

        require_once __DIR__ . '/../admin/!model/updates.php';
        // require_once __DIR__ . '/../admin/!model/ticketssteps.php';

        $updateObj = new updates();
        // $stepsObj = new ticketssteps();

        $updList['1'] = $updateObj->getPendingUpdatesByUser(array(
            'upd_assign' => 1
        ));
        $updList['46'] = $updateObj->getPendingUpdatesByUser(array(
            'upd_assign' => 1
        ));
        $updList['47'] = $updateObj->getPendingUpdatesByUser(array(
            'upd_assign' => 1
        ));
        // $stepsList = $stepsObj->getTktAndStepsByUser(array('user_id'=>1));

        $this->view->updList = $updList;
    }

    public function statusAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();

        
        $form = new form();

        $decUpdId = $this->view->decode($this->view->param['ref']);

        if (! $decUpdId)
            die('tampered');

        $form->addElement('status', 'Status', 'checkbox', 'required', array(
            'options' => array(
                "100" => "Closed"
            )
        ));
        $form->addElement('note', 'Note ', 'textarea', 'required');

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

    public function updatesAction()
    {
        require_once __DIR__ . '/../admin/!model/updates.php';
        $updatesObj = new updates();
        $updateList = $updatesObj->getUpdateListByTypeAndRef(array(
            'upd_type' => $this->view->param['type'],
            'upd_type_refid' => $this->view->param['ref']
        ));

        $this->view->updateList = $updateList;

    }
}
