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
                         * require_once __DIR__ . "/../acl/!model/Aclactions.php";
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

                        require_once __DIR__ . "/../acl/!model/Aclactions.php";
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
        require_once __DIR__ . '/../admin/!model/documents.php';
        $docs = new documets();

        require_once __DIR__ . '/../admin/!model/notification.php';
        $notification = new notification();

        $notifStatus = $notification->getNotificationStatus();

        if (empty($notifStatus['notif_id'])) {

            // a($notifStatus);

            $notif = $notification->getNotificationReport();

            $style = 'style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top;  text-align: center; border: 1px solid #9999999c ;background-color: white; padding: .3em .3em;"';
            $lstyle = 'style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top;  text-align: left; border: 1px solid #9999999c ;background-color: white; padding: .3em .3em;"';

            // s($notif);

            if (count($notif["upd_list"]) > 0) {
                $updhtml .= " <tr>";
                $updhtml .= " <th $style> Head </th>";
                $updhtml .= " <th $style> Title </th>";
                $updhtml .= " <th $style> Note </th>";
                $updhtml .= " <th $style> Ref:</th>";
                $updhtml .= " <th $style> Employee</th>";
                $updhtml .= " <th $style> Expiry Date</th>";
                $updhtml .= " </tr>";
                foreach ($notif["upd_list"] as $upd) {
                    $updhtml .= " <tr>";
                    $updhtml .= " <td $style> " . ucwords(strtolower($upd['txt_type'])) . "</td>";
                    $updhtml .= " <td $style> " . ucwords(strtolower($upd['upd_title'])) . "</td>";
                    $updhtml .= " <td $style> " . ucwords(strtolower($upd['upd_note'])) . "</td>";
                    $updhtml .= " <td $style> " . ucwords(strtolower($upd['ref_name'])) . "</td>";
                    $updhtml .= " <td $style> " . ucwords(strtolower($upd['user_name'])) . "</td>";
                    $updhtml .= " <td $style> " . $upd['upd_enddttime'] . "</td>";
                    $updhtml .= " </tr>";
                }
            }

            if (count($notif["all_docslist"]) > 0) {
                $alldochtml .= " <tr>";
                $alldochtml .= " <th $style> Document Type </th>";
                $alldochtml .= " <th $style> Company </th>";
                $alldochtml .= " <th $style> Doc. No. </th>";
                $alldochtml .= " <th $style> Doc. Desc.</th>";
                $alldochtml .= " <th $style> Remarks</th>";
                $alldochtml .= " <th $style> Expiry Date</th>";
                $alldochtml .= " </tr>";
                foreach ($notif["all_docslist"] as $alldoc) {
                    $alldochtml .= " <tr>";
                    $alldochtml .= " <td $style> " . ucwords(strtolower($alldoc['doc_type_name'])) . "</td>";
                    $alldochtml .= " <td $style> " . ucwords(strtolower($alldoc['comp_name'])) . "</td>";
                    $alldochtml .= " <td $style> " . $alldoc['doc_no'] . "</td>";
                    $alldochtml .= " <td $style> " . ucwords(strtolower($alldoc['doc_desc'])) . "</td>";
                    $alldochtml .= " <td $style> " . ucwords(strtolower($alldoc['doc_remarks'])) . "</td>";
                    $alldochtml .= " <td $style> " . $alldoc['end_date'] . "</td>";
                    $alldochtml .= " </tr>";
                }
            }

            if (count($notif["emp_docslist"]) > 0) {
                $emphtml .= " <tr>";
                $emphtml .= " <th $style> File No. </th>";
                $emphtml .= " <th $style> Employee Name </th>";
                $emphtml .= " <th $style> Designation </th>";
                $emphtml .= " <th $style> Doc. Type</th>";
                $emphtml .= " <th $style> Doc. No.</th>";
                $emphtml .= " <th $style> Doc. Description</th>";
                $emphtml .= " <th $style> Expiry Date</th>";
                $emphtml .= " </tr>";
                foreach ($notif["emp_docslist"] as $empdoc) {
                    $emphtml .= " <tr>";
                    $emphtml .= " <td $style> " . $empdoc['emp_fileno'] . "</td>";
                    $emphtml .= " <td $lstyle> " . ucwords(strtolower($empdoc['emp_name'])) . "</td>";
                    $emphtml .= " <td $style> " . ucwords(strtolower($empdoc['desig_name'])) . "</td>";
                    $emphtml .= " <td $style> " . ucwords(strtolower($empdoc['doc_type_name'])) . "</td>";
                    $emphtml .= " <td $style> " . $empdoc['doc_no'] . "</td>";
                    $emphtml .= " <td $style> " . ucwords(strtolower($empdoc['doc_desc'])) . "</td>";
                    $emphtml .= " <td $style> " . $empdoc['doc_expiry_date'] . "</td>";
                    $emphtml .= " </tr>";
                }
            }

            if (count($notif["prop_docslist"]) > 0) {
                $prophtml .= " <tr>";
                $prophtml .= " <th $style> File No. </th>";
                $prophtml .= " <th $style> Tenant Name </th>";
                $prophtml .= " <th $style> Building </th>";
                $prophtml .= " <th $style> Doc. Type</th>";
                $prophtml .= " <th $style> Doc. No.</th>";
                $prophtml .= " <th $style> Amount</th>";
                $prophtml .= " <th $style> Phone</th>";
                $prophtml .= " <th $style> Expiry Date</th>";
                $prophtml .= " </tr>";
                foreach ($notif["prop_docslist"] as $propdoc) {
                    $prophtml .= " <tr>";
                    $prophtml .= " <td $style> " . $propdoc['prop_fileno'] . "</td>";
                    $prophtml .= " <td $lstyle> " . ucwords(strtolower($propdoc['tnt_full_name'])) . "</td>";
                    $prophtml .= " <td $style> " . ucwords(strtolower($propdoc['bld_name'])) . "</td>";
                    $prophtml .= " <td $style> " . ucwords(strtolower($propdoc['doc_type_name'])) . "</td>";
                    $prophtml .= " <td $style> " . ucwords(strtolower($propdoc['doc_no'])) . "</td>";
                    $prophtml .= " <td $style> " . $propdoc['agr_amount'] . "</td>";
                    $prophtml .= " <td $style> " . ucwords(strtolower($propdoc['tnt_phone'])) . "</td>";
                    $prophtml .= " <td $style> " . $propdoc['doc_expiry_date'] . "</td>";
                    $prophtml .= " </tr>";
                }
            }

            if (count($notif["vhl_docslist"]) > 0) {
                $vehhtml .= " <tr>";
                $vehhtml .= " <th $style> Plate No. </th>";
                $vehhtml .= " <th $style> Vehicle </th>";
                $vehhtml .= " <th $style> Model </th>";
                $vehhtml .= " <th $style> Doc. Type</th>";
                $vehhtml .= " <th $style> Doc. No.</th>";
                $vehhtml .= " <th $style> Description</th>";
                $vehhtml .= " <th $style> Remarks</th>";
                $vehhtml .= " <th $style> Expiry Date</th>";
                $vehhtml .= " </tr>";
                foreach ($notif["vhl_docslist"] as $vhlpdoc) {
                    $vehhtml .= " <tr>";
                    $vehhtml .= " <td $style> " . $vhlpdoc['vhl_no'] . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['type_name'])) . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['vhl_model'])) . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['doc_type_name'])) . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['doc_no'])) . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['doc_desc'])) . "</td>";
                    $vehhtml .= " <td $style> " . ucwords(strtolower($vhlpdoc['doc_remarks'])) . "</td>";
                    $vehhtml .= " <td $style> " . $vhlpdoc['doc_expiry_date'] . "</td>";
                    $vehhtml .= " </tr>";
                }
            }

            $message = '<!doctype html>
                            <html lang="en">
                              <head>
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                <title>Simple Transactional Email</title>
                                <style media="all" type="text/css">
                            @media all {
                              .btn-primary table td:hover {
                                background-color: #f0e8eb !important;
                              }
                            
                              .btn-primary a:hover {
                                background-color: #ec0867 !important;
                                border-color: #ec0867 !important;
                              }
                            }
                            @media only screen and (max-width: 640px) {
                              .main p,
                            .main td,
                            .main span {
                                font-size: 16px !important;
                              }
                            
                              .wrapper {
                                padding: 8px !important;
                              }
                            
                              .content {
                                padding: 0 !important;
                              }
                            
                              .container {
                                padding: 0 !important;
                                padding-top: 8px !important;
                                width: 100% !important;
                              }
                            
                              .main {
                                border-left-width: 0 !important;
                                border-radius: 0 !important;
                                border-right-width: 0 !important;
                              }
                            
                              .btn table {
                                max-width: 100% !important;
                                width: 100% !important;
                              }
                            
                              .btn a {
                                font-size: 16px !important;
                                max-width: 100% !important;
                                width: 100% !important;
                              }
                            }
                            @media all {
                              .ExternalClass {
                                width: 100%;
                              }
                            
                              .ExternalClass,
                            .ExternalClass p,
                            .ExternalClass span,
                            .ExternalClass font,
                            .ExternalClass td,
                            .ExternalClass div {
                                line-height: 100%;
                              }
                            
                              .apple-link a {
                                color: inherit !important;
                                font-family: inherit !important;
                                font-size: inherit !important;
                                font-weight: inherit !important;
                                line-height: inherit !important;
                                text-decoration: none !important;
                              }
                            
                              #MessageViewBody a {
                                color: inherit;
                                text-decoration: none;
                                font-size: inherit;
                                font-family: inherit;
                                font-weight: inherit;
                                line-height: inherit;
                              }
                            }
                            </style>
                              </head>
                              <body style="font-family: Helvetica, sans-serif; -webkit-font-smoothing: antialiased; font-size: 16px; line-height: 1.3; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #f4f5f6; margin: 0; padding: 0;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f4f5f6; width: 100%;" width="100%" bgcolor="#f4f5f6">
                                  <tr>
                                    <td class="container" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; max-width: 600px; padding: 0; padding-top: 24px; width: 600px; margin: 0 auto;" width="600" valign="top">
                                      <div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 90%; padding: 0;">
                            
                                        <!-- START CENTERED WHITE CONTAINER -->
                                        <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border: 1px solid #eaebed; border-radius: 16px; width: 100%;" width="100%">
                            
                                          <!-- START MAIN CONTENT AREA -->
                                          <tr>
                                            <td class="wrapper" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; box-sizing: border-box; padding: 24px;" valign="top">
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">Hi there</p>
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">Monthly Reminder Email from AST Global</p>
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                Below are the reminders for dates falling in the month of   ' . date('F, Y') . '</p>
                                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                                                      <table role="presentation" border="0" cellpadding="4" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tbody>
                                                        ' . $updhtml . '
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                            
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                Below are the attached documents in various category, those expiry dates falling in the month of   ' . date('F, Y') . '</p>
                                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                                                      <table role="presentation" border="0" cellpadding="4" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tbody>
                                                        ' . $alldochtml . '
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                            
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                Below are the Employee related documents, those expiry dates falling in the month of   ' . date('F, Y') . '</p>                  
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                                                      <table role="presentation" border="0" cellpadding="4" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tbody>
                                                        ' . $emphtml . '
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                            
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                Below are the Property related documents, those expiry dates falling in the month of   ' . date('F, Y') . '</p> 
                                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                                                      <table role="presentation" border="0" cellpadding="4" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tbody>
                                                        ' . $prophtml . '
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                            
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                Below are the Vehicle related documents, those expiry dates falling in the month of   ' . date('F, Y') . '</p> 
                                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%; min-width: 100%;" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td align="left" style="font-family: Helvetica, sans-serif; font-size: 16px; vertical-align: top; padding-bottom: 16px;" valign="top">
                                                      <table role="presentation" border="0" cellpadding="4" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                                        <tbody>
                                                        ' . $vehhtml . '
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">
                                                This is a automatic system generated email, based on the data available in the system on ' . date('l, F j, Y H:i') . '</p>
                                              <p style="font-family: Helvetica, sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 16px;">Good luck! Hope it works.</p>
                                            </td>
                                          </tr>
                                          <!-- END MAIN CONTENT AREA -->
                                          </table>
                                        <!-- START FOOTER -->
                                        <div class="footer" style="clear: both; padding-top: 24px; text-align: center; width: 100%;">
                                          <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                                            <tr>
                                              <td class="content-block" style="font-family: Helvetica, sans-serif; vertical-align: top; color: #9a9ea6; font-size: 16px; text-align: center;" valign="top" align="center">
                                                <span class="apple-link" style="color: #9a9ea6; font-size: 16px; text-align: center;">AST Global</span>
                                                <br> cSol Management Information System
                                              </td>
                                            </tr>
                                            <tr>
                                            </tr>
                                          </table>
                                        </div>
                                        <!-- END FOOTER -->
                                        <!-- END CENTERED WHITE CONTAINER --></div>
                                    </td>
                                  </tr>
                                </table>
                              </body>
                            </html>';

            // d($message);

            if(($_SERVER['SERVER_NAME'] !='localhost'))
            {
                if (send_email($message, 'md@astglobal.om', 'info@astglobal.om')) {
                    
                    
                    //ff
                    
                    $notification->add(['notif_month'=>date('Y-m-d'), 'notif_email'=>'{}', 'notif_content'=>$message, 'notif_status'=>true]);
                    
                }
            }
        }
        
       
        


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

        $expList['Past'] = array(
            $expCout[0]['doc_type'] => $expCout[0]['count'],
            $expCout[1]['doc_type'] => $expCout[1]['count'],
            $expCout[2]['doc_type'] => $expCout[2]['count'],
            $expCout[3]['doc_type'] => $expCout[3]['count'],
            $expCout[4]['doc_type'] => $expCout[4]['count'],
            $expCout[5]['doc_type'] => $expCout[5]['count'],
            $expCout[6]['doc_type'] => $expCout[6]['count'],
            $expCout[7]['doc_type'] => $expCout[7]['count'],
            $expCout[8]['doc_type'] => $expCout[8]['count'],
            $expCout[9]['doc_type'] => $expCout[9]['count'],
            $expCout[10]['doc_type'] => $expCout[10]['count'],
            $expCout[11]['doc_type'] => $expCout[11]['count'],
            $expCout[12]['doc_type'] => $expCout[12]['count']
        );
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

        $expList[$date->format('F')] = array(
            $expCout[0]['doc_type'] => $expCout[0]['count'],
            $expCout[1]['doc_type'] => $expCout[1]['count'],
            $expCout[2]['doc_type'] => $expCout[2]['count'],
            $expCout[3]['doc_type'] => $expCout[3]['count'],
            $expCout[4]['doc_type'] => $expCout[4]['count'],
            $expCout[5]['doc_type'] => $expCout[5]['count'],
            $expCout[6]['doc_type'] => $expCout[6]['count'],
            $expCout[7]['doc_type'] => $expCout[7]['count'],
            $expCout[8]['doc_type'] => $expCout[8]['count'],
            $expCout[9]['doc_type'] => $expCout[9]['count'],
            $expCout[10]['doc_type'] => $expCout[10]['count'],
            $expCout[11]['doc_type'] => $expCout[11]['count'],
            $expCout[12]['doc_type'] => $expCout[12]['count']
        );
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

        $expList[$date->format('F')] = array(
            $expCout[0]['doc_type'] => $expCout[0]['count'],
            $expCout[1]['doc_type'] => $expCout[1]['count'],
            $expCout[2]['doc_type'] => $expCout[2]['count'],
            $expCout[3]['doc_type'] => $expCout[3]['count'],
            $expCout[4]['doc_type'] => $expCout[4]['count'],
            $expCout[5]['doc_type'] => $expCout[5]['count'],
            $expCout[6]['doc_type'] => $expCout[6]['count'],
            $expCout[7]['doc_type'] => $expCout[7]['count'],
            $expCout[8]['doc_type'] => $expCout[8]['count'],
            $expCout[9]['doc_type'] => $expCout[9]['count'],
            $expCout[10]['doc_type'] => $expCout[10]['count'],
            $expCout[11]['doc_type'] => $expCout[11]['count'],
            $expCout[12]['doc_type'] => $expCout[12]['count']
        );

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

        $expList[$date->format('F')] = array(
            $expCout[0]['doc_type'] => $expCout[0]['count'],
            $expCout[1]['doc_type'] => $expCout[1]['count'],
            $expCout[2]['doc_type'] => $expCout[2]['count'],
            $expCout[3]['doc_type'] => $expCout[3]['count'],
            $expCout[4]['doc_type'] => $expCout[4]['count'],
            $expCout[5]['doc_type'] => $expCout[5]['count'],
            $expCout[6]['doc_type'] => $expCout[6]['count'],
            $expCout[7]['doc_type'] => $expCout[7]['count'],
            $expCout[8]['doc_type'] => $expCout[8]['count'],
            $expCout[9]['doc_type'] => $expCout[9]['count'],
            $expCout[10]['doc_type'] => $expCout[10]['count'],
            $expCout[11]['doc_type'] => $expCout[11]['count'],
            $expCout[12]['doc_type'] => $expCout[12]['count']
        );
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

        foreach ($expList as $tkey => $temp) {
            $expList[$tkey] = array_filter($temp);

            if (! isset($temp['passport']))
                $expList[$tkey]['passport'] = 0;
            if (! isset($temp['visa']))
                $expList[$tkey]['visa'] = 0;
            if (! isset($temp['id']))
                $expList[$tkey]['id'] = 0;
            if (! isset($temp['license']))
                $expList[$tkey]['license'] = 0;

            if (! isset($temp['insurance']))
                $expList[$tkey]['insurance'] = 0;
            if (! isset($temp['pdolicense']))
                $expList[$tkey]['pdolicense'] = 0;
            if (! isset($temp['pdopassport']))
                $expList[$tkey]['pdopassport'] = 0;
            if (! isset($temp['h2scard']))
                $expList[$tkey]['h2scard'] = 0;

            if (! isset($temp['oxypassport']))
                $expList[$tkey]['oxypassport'] = 0;
            if (! isset($temp['oxylicense']))
                $expList[$tkey]['oxylicense'] = 0;
            if (! isset($temp['oxyh2s']))
                $expList[$tkey]['oxyh2s'] = 0;
            if (! isset($temp['workContract']))
                $expList[$tkey]['workContract'] = 0;
        }

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

        $stepsObj = new ticketssteps();
        $stepsList = $stepsObj->getTktAndStepsByUser(array(
            'user_id' => USER_ID
        ));

        $this->view->updList = $updList;
        $this->view->stepsList = $stepsList;
    }
}







