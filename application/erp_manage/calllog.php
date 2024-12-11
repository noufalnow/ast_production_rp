<?php

class calllogController extends mvc
{

    protected $stslist = array(
        // Positive Resolutions
        1 => 'SATISFIED',
        2 => 'RESOLVED',
        3 => 'INFORMATION PROVIDED',

        // Negative Outcomes
        4 => 'NOT AVAILABLE NOW',
        5 => 'NOT INTERESTED',
        6 => 'DISCONNECTED',
        7 => 'INCORRECT NUMBER',
        8 => 'CUSTOMER UNREACHABLE',

        // Follow-up Scenarios
        9 => 'NEED FOLLOWUP',
        10 => 'FOLLOWUP SCHEDULED',
        11 => 'FOLLOWUP COMPLETED',
        12 => 'RESCHEDULED',
        13 => 'REQUEST FOR CALLBACK',
        14 => 'CALL DROPPED',

        // Escalation Scenarios
        15 => 'ESCALATED',
        16 => 'SUPERVISOR INVOLVED',
        17 => 'ESCALATION REQUIRED',

        // Action Pending or Deferred
        18 => 'ACTION PENDING',
        19 => 'WAITING FOR APPROVAL',
        20 => 'UNDER NEGOTIATION',
        21 => 'DEFERRED',
        22 => 'PENDING RESPONSE',
        23 => 'ACTION INITIATED',
        24 => 'NO FURTHER ACTION',

        // Miscellaneous
        25 => 'NOT APPLICABLE',
        26 => 'COMPLAINT LOGGED',
        27 => 'CLOSED WITHOUT ACTION',
        28 => 'LEAD GENERATED',
        29 => 'QUERY RESOLVED',
        30 => 'CALL COMPLETED'
    );

    protected function determineClogStatus($statusCode)
    {
        $openStatuses = array(
            4, // NOT AVAILABLE NOW
            9, // NEED FOLLOWUP
            10, // FOLLOWUP SCHEDULED
            12, // RESCHEDULED
            13, // REQUEST FOR CALLBACK
            18, // ACTION PENDING
            19, // WAITING FOR APPROVAL
            20, // UNDER NEGOTIATION
            21, // DEFERRED
            22, // PENDING RESPONSE
            23 // ACTION INITIATED
        );

        return in_array($statusCode, $openStatuses) ? 1 : 2;
    }

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/calllog.php';
        $form = new form();
        $calllog = new calllog();

        $form->addElement('phone_no', 'Phone Number', 'number', 'required|numeric', "", array(
            '' => 'readonly',
            'onfocus' => "this.removeAttribute('readonly')",
            '' => 'autocomplete="nphecl"'
        ));
        $form->addElement('name', 'Name', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="namecl"'
        ));
        $form->addElement('email', 'Email', 'text', 'valid_email', "", array(
            '' => 'autocomplete="emailcl"'
        ));
        $form->addElement('date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('time', 'Time  [HH:mm]', 'text', 'required', "", array(
            'class' => 'time',
            '' => 'autocomplete="timecl"'
        ));
        $form->addElement('logtype', 'Log Subject', 'select', 'required', array(
            'options' => array(
                1 => 'FLAT',
                2 => 'SHOP',
                3 => 'EQUIPMENTS'
            )
        ));

        $form->addElement('log', 'Log', 'textarea', 'required');

        $form->addElement('clog_sts_for', 'Action Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $logdt = DateTime::createFromFormat(DF_DD, $valid['date']);
                    $logdt = date_format($logdt, DFS_DB);

                    $data = array(
                        'clog_type' => $valid['logtype'],
                        'clog_phone_no' => $valid['phone_no'],
                        'clog_name' => $valid['name'],
                        'clog_email' => $valid['email'],
                        'clog_date' => $logdt,
                        'clog_time' => $valid['time'] . ":00",
                        'clog_emp' => USER_ID,
                        'clog_log' => $valid['log'],
                        'clog_sts_for' => $valid['clog_sts_for'],
                        'clog_sts' => $this->determineClogStatus($valid['clog_sts_for']),
                        'clog_sts_cur' => $valid['clog_sts_for']
                    );

                    $calllogId = $calllog->add($data);

                    if ($calllogId) {
                        $feedback = 'Call log added successfully';
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
        include __DIR__ . '/../admin/!model/calllog.php';
        $calllog = new calllog();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $calllogId = $this->view->decode($this->view->param['ref']);

        if (! $calllogId)
            die('tampered');

        $form->addElement('phone_no', 'Phone Number', 'number', 'required|numeric', "", array(
            '' => 'readonly',
            'onfocus' => "this.removeAttribute('readonly')",
            '' => 'autocomplete="nphecl"'
        ));
        $form->addElement('name', 'Name', 'text', 'required|alpha_space', "", array(
            '' => 'autocomplete="namecl"'
        ));
        $form->addElement('email', 'Email', 'text', 'valid_email', "", array(
            '' => 'autocomplete="emailcl"'
        ));
        $form->addElement('date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('time', 'Time  [HH:mm]', 'text', 'required', "", array(
            'class' => 'time',
            '' => 'autocomplete="timecl"'
        ));
        $form->addElement('logtype', 'Log Subject', 'select', 'required', array(
            'options' => array(
                1 => 'FLAT',
                2 => 'SHOP',
                3 => 'EQUIPMENTS'
            )
        ));

        $form->addElement('log', 'Log', 'textarea', 'required');

        $form->addElement('clog_sts_for', 'Action Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        $calllogDetails = $calllog->getCallLogById($calllogId);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $logdt = DateTime::createFromFormat(DF_DD, $valid['date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'clog_type' => $valid['logtype'],
                        'clog_phone_no' => $valid['phone_no'],
                        'clog_name' => $valid['name'],
                        'clog_email' => $valid['email'],
                        'clog_date' => $logdtDb,
                        'clog_time' => $valid['time'] . ":00",
                        // 'clog_emp' => USER_ID,
                        'clog_log' => $valid['log'],
                        'clog_sts_for' => $valid['clog_sts_for'],
                        'clog_sts' => $this->determineClogStatus($valid['clog_sts_for']),
                        'clog_sts_cur' => $valid['clog_sts_for']
                    );

                    $modifyCallLog = $calllog->modify($data, $calllogDetails['clog_id']);

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
        } else {
            // Assuming $calllogDetails contains the data to pre-fill the form
            if (isset($calllogDetails)) {
                $form->phone_no->setValue($calllogDetails['clog_phone_no']);
                $form->name->setValue($calllogDetails['clog_name']);
                $form->email->setValue($calllogDetails['clog_email']);

                // Convert database date format to display format (if required)
                $logDate = DateTime::createFromFormat(DFS_DB, $calllogDetails['clog_date']);
                $form->date->setValue($logDate ? $logDate->format(DF_DD) : '');

                // Set time (strip seconds if unnecessary)
                $logTime = substr($calllogDetails['clog_time'], 0, 5);
                $form->time->setValue($logTime);

                $form->logtype->setValue($calllogDetails['clog_type']);
                $form->log->setValue($calllogDetails['clog_log']);
                $form->clog_sts_for->setValue($calllogDetails['clog_sts_for']);
            }
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        include __DIR__ . '/../admin/!model/calllog.php';
        include __DIR__ . '/../admin/!model/calllogfollow.php';

        $form = new form();

        $form->addElement('f_phone_no', 'Phone Number', 'text', 'numeric');
        $form->addElement('f_name', 'Name', 'text', 'alpha_space');

        $form->addElement('f_logtype', 'Log Type', 'select', '', array(
            'options' => array(
                1 => 'FLAT',
                2 => 'SHOP',
                3 => 'EQUIPMENTS'
            )
        ));

        $form->addElement('f_action', 'Action Status', 'select', '', array(
            'options' => $this->stslist));
        
        $form->addElement('f_status', 'Log Status', 'select', '', array(
            'options' => array(
                1 => 'OPEN',
                2 => 'CLOSED',
            )));
        
        $form->addElement('f_date', 'Date', 'text', '', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        
        $form->addElement('f_month', 'Month', 'text', '', "", array(
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
                    'f_phone_no'    => @$valid['f_phone_no'],
                    'f_name'        => @$valid['f_name'],
                    'f_logtype'     => @$valid['f_logtype'],
                    'f_action'      => @$valid['f_action'],
                    'f_status'   => @$valid['f_status'],
                    'f_date'     => @$valid['f_date'],
                    'f_month'     => @$valid['f_month'],
                );
            }
            $filter_class = 'btn-info';
        }

        $calllogObj = new calllog();
        $calllogsList = $calllogObj->getCallLogsPaginate(@$where);

        $calllogfollowObj = new calllogfollow();

        $this->view->calllogObj = $calllogObj;
        $this->view->calllogfollowObj = $calllogfollowObj;

        $this->view->calllogsList = $calllogsList;
        $this->view->form = $form;
        $this->view->filter_class = $filter_class;
    }

    public function fupaddAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/calllogfollow.php';
        $form = new form();
        $calllogFollow = new callLogFollow();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $calllogId = $this->view->decode($this->view->param['ref']);

        if (! $calllogId)
            die('tampered');

        include __DIR__ . '/../admin/!model/calllog.php';
        $calllogObj = new calllog();
        $calllogDetails = $calllogObj->getCallLogById($calllogId);

        // Add form elements
        $form->addElement('cflo_log', 'Follow-Up Log', 'textarea', 'required', "", array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('cflo_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('cflo_time', 'Time [HH:mm]', 'text', 'required', "", array(
            'class' => 'time',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('cflo_sts', 'Current Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // Convert date format
                    $logdt = DateTime::createFromFormat(DF_DD, $valid['cflo_date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'cflo_clog_id' => $calllogId,
                        'cflo_log' => $valid['cflo_log'],
                        'cflo_date' => $logdtDb,
                        'cflo_time' => $valid['cflo_time'] . ":00",
                        'cflo_emp' => USER_ID,
                        'cflo_sts' => $valid['cflo_sts'],
                        'cflo_prv_sts' => $calllogDetails['clog_sts_cur']
                    );

                    $followupId = $calllogFollow->add($data);

                    if ($followupId) {

                        $calllogObj->modify([
                            'clog_sts_cur' => $valid['cflo_sts'],
                            'clog_sts' => $this->determineClogStatus($valid['cflo_sts'])
                        ], $calllogId);

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
        include __DIR__ . '/../admin/!model/calllogfollow.php';
        $form = new form();
        $calllogFollow = new callLogFollow();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);
        $followupId = $this->view->decode($this->view->param['ref']);

        if (! $followupId) {
            die('tampered');
        }

        // Retrieve follow-up details
        $followupDetails = $calllogFollow->getFollowById($followupId);

        if (! $followupDetails) {
            die('Follow-up not found');
        }

        // Add form elements
        $form->addElement('cflo_log', 'Follow-Up Log', 'textarea', 'required', "", array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('cflo_date', 'Date', 'text', 'required', "", array(
            '' => 'autocomplete="off"',
            'class' => 'date_picker'
        ));
        $form->addElement('cflo_time', 'Time [HH:mm]', 'text', 'required', "", array(
            'class' => 'time',
            '' => 'autocomplete="off"'
        ));
        $form->addElement('cflo_sts', 'Current Status', 'select', 'required', array(
            'options' => $this->stslist
        ));

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {
                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {
                    // Convert date format
                    $logdt = DateTime::createFromFormat(DF_DD, $valid['cflo_date']);
                    $logdtDb = date_format($logdt, DFS_DB);

                    $data = array(
                        'cflo_log' => $valid['cflo_log'],
                        'cflo_date' => $logdtDb,
                        'cflo_time' => $valid['cflo_time'] . ":00",
                        'cflo_sts' => $valid['cflo_sts']
                    );

                    $updated = $calllogFollow->modify($data,$followupId);

                    if ($updated) {
                        include __DIR__ . '/../admin/!model/calllog.php';
                        $calllogObj = new calllog();

                        $calllogObj->modify([
                            'clog_sts_cur' => $valid['cflo_sts'],
                            'clog_sts' => $this->determineClogStatus($valid['cflo_sts'])
                        ], $followupDetails['cflo_clog_id']);

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
            $form->cflo_log->setValue($followupDetails['cflo_log']);
            $form->cflo_date->setValue(DateTime::createFromFormat(DFS_DB, $followupDetails['cflo_date'])->format(DF_DD));
            $form->cflo_time->setValue(substr($followupDetails['cflo_time'], 0, 5)); // Remove seconds
            $form->cflo_sts->setValue($followupDetails['cflo_sts']);
        }

        $this->view->form = $form;
    }
}
