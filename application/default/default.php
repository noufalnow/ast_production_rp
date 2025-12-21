<?php

class defaultController extends mvc
{

    public function loginAction()
    {
        if (isset($_SESSION['user_name'])) {
            // self::logoutAction ();
        }
        $this->view->response('login');
        require_once __DIR__ . '/../admin/!model/user.php';
        require_once __DIR__ . '/../admin/!model/loginlog.php';

        $form = new form();

        $form->addElement('uname', 'User Name ', 'text', 'required|alpha_numeric', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('password', 'Password', 'password', 'required', '', array(
            '' => 'autocomplete="off"'
        ));

        if (isset($_POST) && count($_POST) > 0) {

            $valid = $form->vaidate($_POST);
            if ($valid[0]) {
                $valid = $valid[0];
                $user = new user();
                $userDet = $user->getUser(array(
                    'user_uname' => $valid['uname']
                ));
                if ('127.0.0.1' != $_SERVER['REMOTE_ADDR'] && 'localhost' != $_SERVER['SERVER_ADDR'] && '192.168.100.51' != $_SERVER['SERVER_ADDR']) {
                    $secretKey = "6LfP-OkUAAAAANkKk2a8tzfz19UfyD7WW8PyDPsj";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $captcha = $_POST['g-recaptcha-response'];

                    // post request to server

                    $url = 'http://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
                    $response = file_get_contents($url);
                    $responseKeys = json_decode($response, true);

                    if ($responseKeys['success'] == 1)
                        $captcha = 1;

                    // print_r($responseKeys);
                } else {
                    $captcha = 1;
                }

                // a($userDet);

                if ($captcha == 1 && $userDet) {

                    if (password_verify($valid['password'], $userDet['user_password'])) {
                        $_SESSION['user_name'] = $userDet['user_uname'];
                        $_SESSION['user_id'] = $userDet['user_id'];
                        $_SESSION['user_type'] = $userDet['user_desig'];
                        $_SESSION['user_dip_name'] = $userDet['user_fname'] . " " . $userDet['user_lname'];

                        if ($userDet['ubr_branch'] && $userDet['user_desig'] == 4) {
                            $_SESSION['ubr_branch'] = $userDet['ubr_branch'];
                        } elseif ($userDet['user_desig'] == 3) {
                            $_SESSION['ubr_branch'] = $userDet['member_div_id'];
                        }

                        define('USER_ID', $_SESSION['user_id']);
                        $loginObg = new loginlog();
                        $logId = $loginObg->add(array(
                            'log_remote_addr' => $_SERVER['REMOTE_ADDR'],
                            'log_http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'log_remote_port' => $_SERVER['REMOTE_PORT']
                        ));
                        $_SESSION['user_log_id'] = $logId;

                        /*
                         * require_once __DIR__ . "/../admin/!model/Aclactions.php";
                         * $actionAccessModelObj = new Aclactions ();
                         * $cond ['aacc_role_id'] = $_SESSION ['user_id'];
                         * $cond ['aacc_role_type'] = 2;
                         * $actionAccessList = $actionAccessModelObj->getAllPermissionForTheRoll ( $cond );
                         * // a($actionAccessList);
                         *
                         * $_SESSION ['user_acl'] = $actionAccessList;
                         */

                        setcookie("menu", "show", time() + (86400 * 30), '/');
                        header("Location:" . APPURL . "default/default/dashboard");
                    } else {
                        $form->password->setError("Wrong password");
                    }
                } else {
                    $form->uname->setError("Invalid user name");
                    $form->password->setError("Wrong password");
                }
            }
        }

        $this->view->form = $form;
    }

    public function indexAction()
    {
        if (isset($_SESSION['user_name'])) {
            // self::logoutAction ();
        }
        $this->view->response('login');
        require_once __DIR__ . '/../admin/!model/user.php';
        require_once __DIR__ . '/../admin/!model/loginlog.php';

        $form = new form();

        $form->addElement('uname', 'User Name ', 'text', 'required|alpha_numeric', '', array(
            '' => 'autocomplete="off"'
        ));
        $form->addElement('password', 'Password', 'password', 'required', '', array(
            '' => 'autocomplete="off"'
        ));

        if (isset($_POST) && count($_POST) > 0) {

            $valid = $form->vaidate($_POST);
            if ($valid[0]) {
                $valid = $valid[0];
                $user = new user();
                $userDet = $user->getUser(array(
                    'user_uname' => $valid['uname']
                ));
                if (false) {
                    $secretKey = "6LfP-OkUAAAAANkKk2a8tzfz19UfyD7WW8PyDPsj";
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $captcha = $_POST['g-recaptcha-response'];

                    // post request to server

                    $url = 'http://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
                    $response = file_get_contents($url);
                    $responseKeys = json_decode($response, true);

                    if ($responseKeys['success'] == 1)
                        $captcha = 1;

                    // print_r($responseKeys);
                } else {
                    $captcha = 1;
                }

                // a($userDet);
                $captcha = 1;

                if ($captcha == 1 && $userDet) {

                    if (password_verify($valid['password'], $userDet['user_password'])) {

                        $roll = array(
                            1 => "Admin",
                            2 => "Director",
                            3 => "Manager",
                            4 => "Accountant",
                            5 => "Sales",
                            6 => "Purchase",
                            7 => "Maintenance",
                            8 => "Office Assistant"
                        );

                        // if (true) {
                        $_SESSION['user_name'] = $userDet['user_uname'];
                        $_SESSION['user_id'] = $userDet['user_id'];
                        $_SESSION['user_type'] = $userDet['user_desig'];
                        $_SESSION['user_dip_name'] = $userDet['user_fname'] . " " . $userDet['user_lname'];
                        $_SESSION['user_emp_id'] = $userDet['user_emp_id'];
                        $_SESSION['user_role'] = $roll[$userDet['user_desig']];

                        if ($userDet['ubr_branch'] && $userDet['user_desig'] == 4) {
                            $_SESSION['ubr_branch'] = $userDet['ubr_branch'];
                        } elseif ($userDet['user_desig'] == 3) {
                            $_SESSION['ubr_branch'] = $userDet['member_div_id'];
                        }

                        define('USER_ID', $_SESSION['user_id']);
                        $loginObg = new loginlog();
                        $logId = $loginObg->add(array(
                            'log_remote_addr' => $_SERVER['REMOTE_ADDR'],
                            'log_http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'log_remote_port' => $_SERVER['REMOTE_PORT']
                        ));
                        $_SESSION['user_log_id'] = $logId;

                        require_once __DIR__ . '/../admin/!model/company.php';
                        $compModelObj = new company();
                        $compList = $compModelObj->getCompanyPair();
                        if (! empty($compList['2'])) {
                            $_SESSION['COMP_LABL2'] = 'VENPALAVATTOM';
                        }

                        require_once __DIR__ . "/../admin/!model/Aclactions.php";
                        $actionAccessModelObj = new Aclactions();
                        $cond['aacc_role_id'] = $_SESSION['user_id'];
                        $cond['aacc_role_type'] = 2;
                        $actionAccessList = $actionAccessModelObj->getAllPermissionForTheRoll($cond);
                        // a($actionAccessList);

                        $_SESSION['user_acl'] = $actionAccessList;

                        require_once __DIR__ . '/../admin/!model/updates.php';
                        $updatesObj = new updates();
                        $count = $updatesObj->getOpenUpdatesCount();
                        $_SESSION['upd_count'] = $count['count'];

                        setcookie("menu", "show", time() + (86400 * 30), '/');
                        header("Location:" . APPURL . "default/default/dashboard");
                    } else {
                        $form->password->setError("Wrong password");
                    }
                } else {
                    $form->uname->setError("Invalid user name");
                    $form->password->setError("Wrong password");
                }
            }
        }

        $this->view->form = $form;
    }

    public function dashboardAction()
    {

        
        require_once __DIR__ . '/../admin/!model/expense.php';
        
        $expObj = new expense();
        $pivotList = $expObj->expensePivotTable();
        
        /* ---------- COLLECT HEAD / CATEGORY / ATOMIC ---------- */
        
        $columnGroups = [];   // [HEAD => ['categories'=>[], 'particulars'=>[]]]
        $dates = [];
        $columnMeta = [];     // key => HEAD | CATEGORY | ATOMIC
        $columnHead = [];     // key => HEAD
        $columnLabel = [];    // key => DISPLAY NAME
        $currentHead = null;
        
        foreach ($pivotList as $r) {
            
            $dates[$r['date']] = true;
            
            if ($r['level'] === 'HEAD') {
                
                $currentHead = $r['name'];
                
                if (!isset($columnGroups[$currentHead])) {
                    $columnGroups[$currentHead] = [
                        'categories'  => [],
                        'particulars' => []
                    ];
                }
                continue;
            }
            
            // DETAIL
            if ($r['ref_type'] === 'CATEGORY') {
                $key = $currentHead . '||CAT||' . $r['name'];
                $columnGroups[$currentHead]['categories'][$key] = $r['name'];
            } else {
                $key = $currentHead . '||ATOMIC||' . $r['name'];
                $columnGroups[$currentHead]['particulars'][$key] = $r['name'];
            }
        }
        
        /* ---------- ORDERED COLUMNS ---------- */
        
        $orderedColumns = [];
        
        foreach ($columnGroups as $head => $grp) {
            
            // HEAD
            $orderedColumns[] = $head;
            $columnMeta[$head]  = 'HEAD';
            $columnHead[$head]  = $head;
            $columnLabel[$head] = $head;
            
            // CATEGORY
            foreach ($grp['categories'] as $key => $label) {
                $orderedColumns[] = $key;
                $columnMeta[$key]  = 'CATEGORY';
                $columnHead[$key]  = $head;
                $columnLabel[$key] = $label;
            }
            
            // ATOMIC
            foreach ($grp['particulars'] as $key => $label) {
                $orderedColumns[] = $key;
                $columnMeta[$key]  = 'ATOMIC';
                $columnHead[$key]  = $head;
                $columnLabel[$key] = $label;
            }
        }
        
        /* ---------- INIT PIVOT ---------- */
        
        $dates = array_keys($dates);
        $pivot = [];
        
        foreach ($dates as $date) {
            foreach ($orderedColumns as $col) {
                $pivot[$date][$col] = 0;
            }
        }
        
        /* ---------- FILL VALUES (CRITICAL FIX) ---------- */
        
        /* ---------- FILL VALUES (FIXED) ---------- */
        
        $currentHead = null;
        
        foreach ($pivotList as $r) {
            
            if ($r['level'] === 'HEAD') {
                $currentHead = $r['name'];
                $key = $currentHead;
                
            } else {
                
                if ($r['ref_type'] === 'CATEGORY') {
                    $key = $currentHead . '||CAT||' . $r['name'];
                } else {
                    $key = $currentHead . '||ATOMIC||' . $r['name'];
                }
            }
            
            // safety check (important during debugging)
            if (isset($pivot[$r['date']][$key])) {
                $pivot[$r['date']][$key] += (float)$r['amount'];
            }
        }
        
        
        /* ---------- TOTALS (NO DOUBLE COUNT) ---------- */
        
        $rowTotals    = [];
        $columnTotals = array_fill_keys($orderedColumns, 0);
        $rawColumnTotals = $columnTotals;
        $grandTotal = 0;
        
        foreach ($pivot as $date => $cols) {
            
            $rowTotals[$date] = 0;
            $othersCounted = false;
            $hasAtomic = false;
            
            // detect atomic presence
            foreach ($cols as $col => $val) {
                $rawColumnTotals[$col] += $val;
                
                if ($columnMeta[$col] === 'ATOMIC' && $val > 0) {
                    $hasAtomic = true;
                }
            }
            
            foreach ($cols as $col => $val) {
                
                // RULE 1: ATOMIC
                if ($columnMeta[$col] === 'ATOMIC') {
                    $rowTotals[$date] += $val;
                    $columnTotals[$col] += $val;
                    $grandTotal += $val;
                    continue;
                }
                
                // RULE 2: [OTHERS] (only once if no atomic)
                if (
                    !$hasAtomic &&
                    !$othersCounted &&
                    $columnMeta[$col] === 'HEAD' &&
                    $col === '[OTHERS]'
                    ) {
                        $rowTotals[$date] += $val;
                        $columnTotals[$col] += $val;
                        $grandTotal += $val;
                        $othersCounted = true;
                    }
            }
        }
        
        /* ---------- SEND TO VIEW ---------- */
        
        $this->view->columns          = $orderedColumns;
        $this->view->pivot            = $pivot;
        $this->view->rowTotals        = $rowTotals;
        $this->view->columnTotals     = $columnTotals;
        $this->view->columnMeta       = $columnMeta;
        $this->view->columnLabel      = $columnLabel;
        $this->view->rawColumnTotals  = $rawColumnTotals;
        $this->view->grandTotal       = $grandTotal;
        
        



        require_once __DIR__ . '/../admin/!model/updates.php';

        $updateObj = new updates();
        $updList = $updateObj->getPendingUpdatesByUser(array(
            'upd_assign' => USER_ID
        ));

        $this->view->updList = $updList;
    }

    public function dashboardgraphAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/employee.php';
        require_once __DIR__ . '/../admin/!model/property.php';
        require_once __DIR__ . '/../admin/!model/vehicle.php';
        $empObj = new employee();
        $propObj = new property();
        $vehObj = new vehicle();
        $date = new DateTime();

        $param = [];
        $plotData = $propObj->getPlotOptions();

        foreach ($plotData as $data) :
            $bldname[$data['bld_name']] = $data['bld_name'];
            if ($data['prop_cat'] == 1) {
                $param[$data['bld_name']]['shop']['vacant'] = 0;
                $param[$data['bld_name']]['shop']['agreement'] = 0;
                $param[$data['bld_name']]['shop_t'] = 0;
            } else {
                $param[$data['bld_name']]['shop']['vacant'] = 0;
                $param[$data['bld_name']]['shop']['agreement'] = 0;
                $param[$data['bld_name']]['shop_t'] = 0;
            }

            if ($data['prop_cat'] == 2) {
                $param[$data['bld_name']]['flat']['vacant'] = 0;
                $param[$data['bld_name']]['flat']['agreement'] = 0;
                $param[$data['bld_name']]['flat_t'] = 0;
            } else {
                $param[$data['bld_name']]['flat']['vacant'] = 0;
                $param[$data['bld_name']]['flat']['agreement'] = 0;
                $param[$data['bld_name']]['flat_t'] = 0;
            }
        endforeach
        ;
        foreach ($plotData as $data) :
            $bldname[$data['bld_name']] = $data['bld_name'];
            if ($data['prop_cat'] == 1) {
                $param[$data['bld_name']]['shop']['vacant'] = $data['vacant'];
                $param[$data['bld_name']]['shop']['agreement'] = $data['agreement'];
                $param[$data['bld_name']]['shop_t'] = $data['vacant'] + $data['agreement'];
            }
            if ($data['prop_cat'] == 2) {
                $param[$data['bld_name']]['flat']['vacant'] = $data['vacant'];
                $param[$data['bld_name']]['flat']['agreement'] = $data['agreement'];
                $param[$data['bld_name']]['flat_t'] = $data['vacant'] + $data['agreement'];
            }
        endforeach
        ;
        /**
         * **************** property plot end*******************************
         */

        $expCout = $empObj->getEmployeesDocExpiryReport(array(
            'f_monthpick' => 'past'
        ));

        $propExpCount = $propObj->getPropDocExpiryReport(array(
            'f_monthpick' => 'past'
        ));
        $vehExpCount = $vehObj->getVehDocExpiryReport(array(
            'f_monthpick' => 'past'
        ));

        foreach ($expCout as $expTempParam) {
            $expList['Past'][$expTempParam['doc_type']] = $expTempParam['count'];
        }

        $propExpList['Past'] = array(
            $propExpCount[0]['doc_type'] => $propExpCount[0]['count'],
            $propExpCount[1]['doc_type'] => $propExpCount[1]['count'],
            $propExpCount[2]['doc_type'] => $propExpCount[2]['count']
        );
        $VehExpList['Past'] = array(
            $vehExpCount[0]['doc_type'] => $vehExpCount[0]['count'],
            $vehExpCount[1]['doc_type'] => $vehExpCount[1]['count'],
            $vehExpCount[2]['doc_type'] => $vehExpCount[2]['count'],
            $vehExpCount[3]['doc_type'] => $vehExpCount[3]['count'],
            $vehExpCount[4]['doc_type'] => $vehExpCount[4]['count'],
            $vehExpCount[5]['doc_type'] => $vehExpCount[5]['count']
        );

        $link['Past'] = 'past';

        $expCout = $empObj->getEmployeesDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));

        $propExpCount = $propObj->getPropDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));
        $vehExpCount = $vehObj->getVehDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));

        foreach ($expCout as $expTempParam) {
            $expList[$date->format('F')][$expTempParam['doc_type']] = $expTempParam['count'];
        }

        // a($expList);

        $propExpList[$date->format('F')] = array(
            $propExpCount[0]['doc_type'] => $propExpCount[0]['count'],
            $propExpCount[1]['doc_type'] => $propExpCount[1]['count'],
            $propExpCount[2]['doc_type'] => $propExpCount[2]['count']
        );
        $VehExpList[$date->format('F')] = array(
            $vehExpCount[0]['doc_type'] => $vehExpCount[0]['count'],
            $vehExpCount[1]['doc_type'] => $vehExpCount[1]['count'],
            $vehExpCount[2]['doc_type'] => $vehExpCount[2]['count'],
            $vehExpCount[3]['doc_type'] => $vehExpCount[3]['count'],
            $vehExpCount[4]['doc_type'] => $vehExpCount[4]['count'],
            $vehExpCount[5]['doc_type'] => $vehExpCount[5]['count']
        );

        $link[$date->format('F')] = $date->format('Y') . '-' . $date->format('m');

        $date->modify('+1 month');
        $expCout = $empObj->getEmployeesDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));
        $propExpCount = $propObj->getPropDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));
        $vehExpCount = $vehObj->getVehDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));

        foreach ($expCout as $expTempParam) {
            $expList[$date->format('F')][$expTempParam['doc_type']] = $expTempParam['count'];
        }

        $propExpList[$date->format('F')] = array(
            $propExpCount[0]['doc_type'] => $propExpCount[0]['count'],
            $propExpCount[1]['doc_type'] => $propExpCount[1]['count'],
            $propExpCount[2]['doc_type'] => $propExpCount[2]['count']
        );

        $VehExpList[$date->format('F')] = array(
            $vehExpCount[0]['doc_type'] => $vehExpCount[0]['count'],
            $vehExpCount[1]['doc_type'] => $vehExpCount[1]['count'],
            $vehExpCount[2]['doc_type'] => $vehExpCount[2]['count'],
            $vehExpCount[3]['doc_type'] => $vehExpCount[3]['count'],
            $vehExpCount[4]['doc_type'] => $vehExpCount[4]['count'],
            $vehExpCount[5]['doc_type'] => $vehExpCount[5]['count']
        );

        $link[$date->format('F')] = $date->format('Y') . '-' . $date->format('m');

        $date->modify('+1 month');
        $expCout = $empObj->getEmployeesDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));
        $propExpCount = $propObj->getPropDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));
        $vehExpCount = $vehObj->getVehDocExpiryReport(array(
            'f_monthpick' => $date->format('m') . '/' . $date->format('Y')
        ));

        foreach ($expCout as $expTempParam) {
            $expList[$date->format('F')][$expTempParam['doc_type']] = $expTempParam['count'];
        }

        $propExpList[$date->format('F')] = array(
            $propExpCount[0]['doc_type'] => $propExpCount[0]['count'],
            $propExpCount[1]['doc_type'] => $propExpCount[1]['count'],
            $propExpCount[2]['doc_type'] => $propExpCount[2]['count']
        );
        $VehExpList[$date->format('F')] = array(
            $vehExpCount[0]['doc_type'] => $vehExpCount[0]['count'],
            $vehExpCount[1]['doc_type'] => $vehExpCount[1]['count'],
            $vehExpCount[2]['doc_type'] => $vehExpCount[2]['count'],
            $vehExpCount[3]['doc_type'] => $vehExpCount[3]['count'],
            $vehExpCount[4]['doc_type'] => $vehExpCount[4]['count'],
            $vehExpCount[5]['doc_type'] => $vehExpCount[5]['count']
        );

        $link[$date->format('F')] = $date->format('Y') . '-' . $date->format('m');

        // a($expList);

        foreach ($propExpList as $tkey => $temp) {
            $propExpList[$tkey] = array_filter($temp);

            if (! isset($temp['Agreement']))
                $propExpList[$tkey]['Agreement'] = 0;

            if (! isset($temp['Fire']))
                $propExpList[$tkey]['Fire'] = 0;

            if (! isset($temp['Insurance']))
                $propExpList[$tkey]['Insurance'] = 0;
        }

        foreach ($VehExpList as $tkey => $temp) {
            $VehExpList[$tkey] = array_filter($temp);

            if (! isset($temp['Mulkia']))
                $VehExpList[$tkey]['Mulkia'] = 0;

            if (! isset($temp['PDO']))
                $VehExpList[$tkey]['PDO'] = 0;

            if (! isset($temp['Fitness']))
                $VehExpList[$tkey]['Fitness'] = 0;

            if (! isset($temp['IVMS']))
                $VehExpList[$tkey]['IVMS'] = 0;

            if (! isset($temp['Insurance']))
                $VehExpList[$tkey]['Insurance'] = 0;

            if (! isset($temp['Mun.Certificate']))
                $VehExpList[$tkey]['Mun.Certificate'] = 0;
        }

        $this->view->link = $link;
        $this->view->expList = $expList;
        $this->view->propExpList = $propExpList;
        $this->view->VehExpList = $VehExpList;
        $this->view->bldname = $bldname;
        $this->view->param = $param;
    }

    public function logoutAction()
    {
        session_start();
        unset($_SESSION['user_name']);
        session_destroy();
        // @todo // check the user type and redirect accordigly
        header("Location:" . APPURL . "default/default/index");
        exit();
    }

    public function downloadAction()
    {
        $this->view->NoViewRender = true;
        require_once __DIR__ . '/../admin/!model/files.php';
        $fileObj = new files();
        $fileId = $this->view->decode($this->view->param['ref']);
        $fileDet = $fileObj->getFileById($fileId);
        if (! empty($fileDet['file_id']) /*&& $fileDet['file_type'] != DOC_TYPE_COMP*/) {
            $filename = $this->view->semiencode($fileDet['file_id']);
            $path = $fileDet['file_type'] == DOC_TYPE_EXP ? 'uploads/expense' : 'uploads';
            $filename = realpath(dirname(__FILE__) . "/../../" . $path . "/" . $filename);
            if (! file_exists($filename))
                exit();
            $realName = $fileDet['file_actual_name'] . '.' . $fileDet['file_exten'];
            if ($fileDet['file_exten'] == 'pdf') {
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $realName . '"');
            } else {
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Type: application/force-download");
                header('Content-Disposition: attachment;filename="' . $realName . '"');
            }

            // header("Content-Type: application/zip");
            // header("Content-Transfer-Encoding: binary");
            @readfile($filename);
            exit();

            header("Content-Description: File Transfer");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename='" . $realName . "'");
            @readfile($source);
        }
    }

    public function protectedAction()
    {
        app_session();
        $form = new form();
        $form->addElement('pass', 'Password', 'password', 'required');

        if (USER_GROUP != 2)
            $displayForm = true;
        else {
            $displayForm = false;
            $_POST['pass'] = 'anssmz';
        }

        if (isset($_POST) && count($_POST) > 0) {

            if ($_POST['pass'] != 'anssmz') {
                $form->addRules('pass', 'invalid', "Invalid Key !!");
            }
            $valid = $form->vaidate($_POST);
            $valid = $valid[0];
            if ($valid == true) {

                require_once __DIR__ . '/../lib/db_table.php';
                require_once __DIR__ . '/../admin/!model/files.php';
                // error_reporting(E_ALL);

                $displayForm = false;

                $fileObj = new files();

                $fileId = $this->view->decode($this->view->param['ref']);

                $fileDet = $fileObj->getFileById($fileId);

                if (! empty($fileDet['file_id'])) {
                    $filename = $this->view->semiencode($fileDet['file_id']);
                    $path = $fileDet['file_type'] == DOC_TYPE_EXP ? 'uploads/expense' : 'uploads';
                    $filename = realpath(dirname(__FILE__) . '/../' . $path) . '/' . $filename;

                    if (! file_exists($filename))
                        die();

                    $realName = $fileDet['file_actual_name'] . '.' . $fileDet['file_exten'];

                    // die($filename);

                    if ($fileDet['file_exten'] == 'pdf') {
                        header('Content-type: application/pdf');
                        header('Content-Disposition: inline; filename="' . $realName . '"');
                    } else {
                        header("Cache-Control: public");
                        header("Content-Description: File Transfer");
                        header("Content-Type: application/force-download");
                        header('Content-Disposition: attachment;filename="' . $realName . '"');
                    }

                    // header("Content-Type: application/zip");
                    // header("Content-Transfer-Encoding: binary");
                    @readfile($filename);
                    exit();

                    header("Content-Description: File Transfer");
                    header("Content-Type: application/force-download");
                    header("Content-Disposition: attachment; filename='" . $realName . "'");
                    // @readfile ( $source );
                }
            }
        }

        app_popheadder();

        return array(
            'displayForm' => $displayForm,
            'form' => $form,
            'ref' => $ref
        );
    }

    public function displayimgAction()
    {
        $this->view->NoViewRender = true;
        require_once __DIR__ . '/../admin/!model/files.php';
        $fileObj = new files();
        $fileId = $this->view->decode($this->view->param['ref']);
        $fileDet = $fileObj->getFileById($fileId);

        if (! empty($fileDet['file_id'])) {
            $filename = $this->view->semiencode($fileDet['file_id']);

            $filename = realpath(dirname(__FILE__) . "/../../uploads/" . $filename);

            if (! file_exists($filename))
                die();

            $realName = $fileDet['file_actual_name'] . '.' . $fileDet['file_exten'];

            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-Disposition: inline;filename="' . $realName . '"');
            header('Content-type: image/' . $fileDet['file_exten']);

            @readfile($filename);
            exit();

            header("Content-Description: File Transfer");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename='" . $realName . "'");
            @readfile($source);
        }
    }

    public function popupcloseAction()
    {
        $this->view->response('window');
    }

    public function analysisAction()
    {}

    public function logsAction()
    {}

    public function backupAction()
    {
        $this->view->NoViewRender = true;
        require_once __DIR__ . '/../admin/!model/files.php';
        $fileObj = new files();

        $dbhost = $fileObj->_dbArray['db_host'];
        $dbuser = $fileObj->_dbArray['db_user'];
        $dbpwd = $fileObj->_dbArray['db_pwd'];
        $dbname = $fileObj->_dbArray['db_name'];

        $dumpfile = "db_backup.backup";
        putenv('PGPASSWORD=' . $dbpwd);
        putenv('PGUSER=' . $dbuser);
        putenv('PGHOST=' . $dbhost);
        putenv('PGPORT=5432');
        putenv('PGDATABASE=' . $dbname);

        /*
         * //plain sql backup
         * exec('"C:/Program Files/PostgreSQL/9.1/bin/pg_dump" db_csol_ast > "' . $dumpfile . '"', $out);
         * // $cmd = '/usr/bin/pg_dump --host localhost --port 5432 --username "postgres" --role "dbadmin" --no-password --format plain --data-only --verbose --file "vv2.sql" "db_csol_test"';
         * $zip = new ZipArchive();
         * rename($dumpfile, 'D:/cSolApp/uploads/db_backup/' . $dumpfile);
         */

        /*
         * $files = scandir ( 'uploads' );
         * foreach ( $files as $file )
         * if ($file != '.' && $file != '..')
         * copydt('uploads/'.$file, 'D:/Backup/uploads/'.$file);
         *
         * $files = scandir ( 'uploads/expense' );
         * foreach ( $files as $file )
         * if ($file != '.' && $file != '..')
         * copydt('uploads/expense/'.$file, 'D:/Backup/uploads/expense/'.$file);
         *
         *
         * function copydt($pathSource, $pathDest) {
         * copy($pathSource, $pathDest);
         * $dt = filemtime($pathSource);
         * if ($dt === FALSE) return FALSE;
         * return touch($pathDest, $dt);
         * }
         */

        exec('"C:/Program Files/PostgreSQL/9.1/bin\pg_dump.exe" --host localhost --port 5432 --username "ws2019@usr" --role "ws2019@usr"  --format custom --blobs --encoding UTF8 --no-privileges --verbose --file "D:/cSolAppV2/uploads/db_backup/' . $dumpfile . '" "db_csol_ast"    2>>"D:/cSolAppV2/uploads/backup_log.txt" ');

        /* to restore : C:/Program Files/PostgreSQL/9.1/bin\pg_restore.exe --host localhost --port 5432 --username "ws2019@usr" --dbname "db_csol_ast" --role "ws2019@usr" --no-owner --no-privileges --clean --verbose "X:\SOFTWARES\bkp.backup" */

        $feedback = 'Backup created successfully';
        $this->view->NoViewRender = true;
        $success = array(
            'feedback' => $feedback
        );
        $_SESSION['feedback'] = $feedback;
        $success = json_encode($success);
        die($success);

        exit(0);

        /*
         * $mime = "application/text";
         * header ( "Content-Type: " . $mime );
         * //date_default_timezone_set ( 'Asia/Muscat' );
         * $dumpfile = $dbname . "_" . date ( "Y-m-d_H-i-s" ) . ".bkp";
         * header ( 'Content-Disposition: attachment; filename="' . $dumpfile . '"' );
         * $cmd = "C:/xampp/mysql/bin/mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname";
         * passthru ( $cmd );
         * exit ( 0 );
         */

        /*
         * //Zip for Maheen
         * $dumpfile = $dbname . "_" . date("Y-m-d_H-i-s") . ".sql";
         * $dumpZip = "backup.zip";
         * $dumpZipName = $dbname . "_" . date("Y-m-d_H-i-s") . ".zip";
         * unlink($dumpZip);
         * $cmd = "C:/xampp/mysql/bin/mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname >$dumpfile";
         * passthru($cmd);
         * $zip = new ZipArchive();
         * if ($zip->open($dumpZip, ZipArchive::CREATE) !== TRUE)
         * exit("cannot open <$filename>\n");
         * $zip->addFile($dumpfile);
         * $files = scandir('target');
         * foreach ($files as $file)
         * if ($file != '.' && $file != '..')
         * $zip->addFile('target\\' . $file);
         * $zip->close();
         * unlink($dumpfile);
         *
         * $mime = "application/x-gzip";
         * header("Cache-Control: public");
         * header("Content-Description: File Transfer");
         * header("Content-Type: " . $mime);
         * header('Content-Disposition: attachment;filename="' . $dumpZipName . '"');
         * @readfile($dumpZip);
         *
         * exit(0);
         *
         */
    }

    function notifyAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/updates.php';
        require_once __DIR__ . '/../admin/!model/ticketssteps.php';

        $updateObj = new updates();
        $updList = $updateObj->getPendingUpdatesByUser(array(
            'upd_assign' => USER_ID
        ));

        require_once __DIR__ . '/../admin/!model/employee.php';
        $employeeObj = new employee();
        $leveNotifList = $employeeObj->getEmployeeLeaveNotification();

        $this->view->updList = $updList;
        $this->view->stepsList = $stepsList;
        $this->view->leveNotifList = $leveNotifList;
    }
}







