<?php

class configController extends mvc
{

    public function setmodulesAction($param = array())
    {

        // $moduleObj = objm('Aclmodules','acl');
        require_once __DIR__ . '/!model/Aclactions.php';
        require_once __DIR__ . '/!model/Aclcontrollers.php';
        require_once __DIR__ . '/!model/Aclmodules.php';
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';
        require_once __DIR__ . '/../admin/!model/user.php';

        // $crypt=new encryption();

        $form = new form();
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

        $form->addElement('usergroup', 'User Group', 'select', 'required', array(
            'options' => $roll
        ), array(
            'id' => 'module_ug',
            'class' => 'ajax_req'
        ));
        $userObj = new user ();
        $userList = $userObj->getUsersPair(array('user_desig'=>$_POST['usergroup']));
        
        $form->addElement('users', 'Users', 'select', '', array(
            'options' => $userList
        ), array(
            'id' => 'module_usr',
            'class' => 'ajax_req'
        ));
        $modulesModelObj = new Aclmodules();
        $modulesList = $modulesModelObj->getModuleList();

        // s($modulesList);

        // direct call from user add
        if (count($param) > 0)
            $_POST = $param;
        

        if (count($_POST) > 0) {
            
            
           

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true || !empty($param['ug_target'])) {

                $moduleAccObject = new Aclmodulesaccess();
                
               

                if ($_POST['users']) {
                    $cond['macc_role_type'] = 2;
                    $cond['macc_role_id'] = $_POST['users'];
                } else {
                    $cond['macc_role_type'] = 1;
                    $cond['macc_role_id'] = $_POST['usergroup'];

                    $userModelObj = new user();
                    $userList = $userModelObj->getUsersByUserGroup(array(
                        'user_desig' => $_POST['usergroup']
                    ));
                }

                $accessList = array();
                
               
                

                if (! empty($param['ug_target'])) {
                    $_POST['id'] = $param['ug_target'];
                    $_POST['type'] = 'module_ug';

                    $accessList = self::getpermissionAction('keys');
                } else {
                    foreach ($_POST as $key => $data) {
                        if (strpos($key, '_') !== false) {

                            $moduleType = explode('_', $key);

                            if (isset($accessList[$moduleType[1]]))
                                array_push($accessList[$moduleType[1]], $moduleType[0]);
                            else
                                $accessList[$moduleType[1]] = array(
                                    $moduleType[0]
                                );
                        }
                    }
                }
                

                
                foreach ($modulesList as $module) {

                    if (is_array($accessList[$module['module_id']]) && in_array('create', $accessList[$module['module_id']]))
                        $accData['macc_create_status'] = 2;
                    else
                        $accData['macc_create_status'] = 1;

                    if (is_array($accessList[$module['module_id']]) && in_array('view', $accessList[$module['module_id']]))
                        $accData['macc_view_status'] = 2;
                    else
                        $accData['macc_view_status'] = 1;

                    if (is_array($accessList[$module['module_id']]) && in_array('update', $accessList[$module['module_id']]))
                        $accData['macc_update_status'] = 2;
                    else
                        $accData['macc_update_status'] = 1;

                    if (is_array($accessList[$module['module_id']]) && in_array('delete', $accessList[$module['module_id']]))
                        $accData['macc_delete_status'] = 2;
                    else
                        $accData['macc_delete_status'] = 1;

                    $cond['macc_module_id'] = $module['module_id'];
                    $accDataI = array_merge($accData, $cond);

                    $moduleAccObject = new Aclmodulesaccess();
                    $moduleAccess = $moduleAccObject->getModuleRoleDetails($cond);

                    if ($moduleAccess['macc_id']) {
                        $moduleStatus = $moduleAccObject->modify($accDataI, $moduleAccess['macc_id']);
                    } else {
                        $moduleStatus = $moduleAccObject->add($accDataI);
                    }

                    if ($cond['macc_role_type'] == 1) {
                        // find all users in the group and set acl
                        if (count($userList) > 0) {

                            foreach ($userList as $user) {

                                $ugCond = $cond;

                                $ugCond['macc_role_type'] = 2;
                                $ugCond['macc_role_id'] = $user['user_id'];

                                $ugAccDataI = array_merge($accData, $ugCond);
                                $moduleAccObject = new Aclmodulesaccess();
                                $moduleAccess = $moduleAccObject->getModuleRoleDetails($ugCond);

                                if ($moduleAccess['macc_id']) {
                                    $moduleStatus = $moduleAccObject->modify($ugAccDataI, $moduleAccess['macc_id']);
                                } else {
                                    $moduleStatus = $moduleAccObject->add($ugAccDataI);
                                }
                            }
                        }
                    }

                    $controllerModelObj = new Aclcontrollers();
                    $controllersList = $controllerModelObj->getControllerBy(array(
                        'controller_module_id' => $module['module_id']
                    ));

                    $controllerAccessModelObj = new Aclcontrolleraccess();

                    foreach ($controllersList as $controller) {

                        // v('con',$controller ['controller_id']); // xml controller lisr error

                        $caAccCon = array(
                            'cacc_controller_id' => $controller['controller_id'],
                            'cacc_role_id' => $accDataI['macc_role_id'],
                            'cacc_role_type' => $accDataI['macc_role_type']
                        );
                        $controllerAccessModelObj = new Aclcontrolleraccess();
                        $controllerAccess = $controllerAccessModelObj->getControllerRoleDetails($caAccCon);

                        $caAccData['cacc_create_status'] = $accData['macc_create_status'];
                        $caAccData['cacc_update_status'] = $accData['macc_update_status'];
                        $caAccData['cacc_view_status'] = $accData['macc_view_status'];
                        $caAccData['cacc_delete_status'] = $accData['macc_delete_status'];

                        $caAccDataI = array_merge($caAccCon, $caAccData);

                        if ($controllerAccess['cacc_id']) {
                            $controllerStatus = $controllerAccessModelObj->modify($caAccDataI, $controllerAccess['cacc_id']);
                        } else {
                            $controllerStatus = $controllerAccessModelObj->add($caAccDataI);
                        }

                        if ($cond['macc_role_type'] == 1) {
                            // find all users in the group and set acl
                            if (count($userList) > 0) {

                                foreach ($userList as $user) {

                                    $ugCaAccCon = array(
                                        'cacc_controller_id' => $controller['controller_id'],
                                        'cacc_role_id' => $user['user_id'],
                                        'cacc_role_type' => 2
                                    );
                                    $controllerAccessModelObj = new Aclcontrolleraccess();
                                    $controllerAccess = $controllerAccessModelObj->getControllerRoleDetails($ugCaAccCon);

                                    $ugCaAccDataI = array_merge($ugCaAccCon, $caAccData);

                                    if ($controllerAccess['cacc_id']) {
                                        $controllerStatus = $controllerAccessModelObj->modify($ugCaAccDataI, $controllerAccess['cacc_id']);
                                    } else {
                                        $controllerStatus = $controllerAccessModelObj->add($ugCaAccDataI);
                                    }
                                }
                            }
                        }

                        $actionsModelObj = new Aclactions();
                        $actionsList = $actionsModelObj->getActionsBy(array(
                            'action_controller_id' => $controller['controller_id']
                        ));

                        $actionsAccessModelObj = new Aclactionsaccess();

                        foreach ($actionsList as $actions) {

                            $acAccCon = array(
                                'aacc_action_id' => $actions['action_id'],
                                'aacc_role_id' => $accDataI['macc_role_id'],
                                'aacc_role_type' => $accDataI['macc_role_type']
                            );
                            $actionsAccessModelObj = new Aclactionsaccess();
                            $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($acAccCon);

                            if ($actions['action_type'] == 1)
                                $actAcsData['aacc_access_status'] = $accData['macc_create_status'];

                            if ($actions['action_type'] == 2)
                                $actAcsData['aacc_access_status'] = $accData['macc_update_status'];

                            if ($actions['action_type'] == 3)
                                $actAcsData['aacc_access_status'] = $accData['macc_view_status'];

                            if ($actions['action_type'] == 4)
                                $actAcsData['aacc_access_status'] = $accData['macc_delete_status'];

                            $actAcsDataI = array_merge($acAccCon, $actAcsData);

                            if ($actionsAccess['aacc_id']) {
                                $actionsAccessModelObj->modify($actAcsDataI, $actionsAccess['aacc_id']);
                            } else {

                                $actionsAccessModelObj->add($actAcsDataI);
                            }

                            if ($cond['macc_role_type'] == 1) {
                                // find all users in the group and set acl
                                if (count($userList) > 0) {

                                    foreach ($userList as $user) {

                                        $ugAcAccCon = array(
                                            'aacc_action_id' => $actions['action_id'],
                                            'aacc_role_id' => $user['user_id'],
                                            'aacc_role_type' => 2
                                        );
                                        $actionsAccessModelObj = new Aclactionsaccess();
                                        $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($ugAcAccCon);

                                        $ugActAcsDataI = array_merge($ugAcAccCon, $actAcsData);

                                        if ($actionsAccess['aacc_id']) {
                                            $actionsAccessModelObj->modify($ugActAcsDataI, $actionsAccess['aacc_id']);
                                        } else {

                                            $actionsAccessModelObj->add($ugActAcsDataI);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /*
             * print_r ( $accessList );
             * echo "<pre>";
             * print_r($_POST);
             * die();
             */
        } else {
            
            if(is_array($_GET) && !empty(array_filter($_GET)))
            {
                $form->usergroup->setValue($_GET['usergroup']);
                $form->users->setValue($_GET['users']);
            }

            /*
             * $empModelObj->emp_id = $employeeId;
             * $employeeDetail = $empModelObj->getEmployeeDetById();
             * if(!$this->validator->checkIsEmpty($employeeDetail->emp_id))
             * {
             * $editEmployeeForm->empFname->setValue($employeeDetail->emp_fname);
             * $editEmployeeForm->empLname->setValue($employeeDetail->emp_lname);
             * $editEmployeeForm->empNationality->setValue($employeeDetail->emp_nationality);
             * $editEmployeeForm->reference->setValue($encryptedEmployeeId);
             * }
             * else
             * {
             * LB_Feedback::seterror('employee_not_exists');
             * }
             *
             */
        }

        $this->view->form = $form;
        $this->view->modulesList = $modulesList;
    }

    public function setcontrollersAction()
    {
        require_once __DIR__ . '/!model/Aclactions.php';
        require_once __DIR__ . '/!model/Aclcontrollers.php';
        require_once __DIR__ . '/!model/Aclmodules.php';
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';
        require_once __DIR__ . '/../admin/!model/user.php';

        $decModuleId = $this->view->decode($this->view->param['ref']);

        if (! $decModuleId)
            die('tampered');
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
        $form = new form();
        $form->addElement('usergroup', 'User Group', 'select', 'required', array(
            'options' => $roll
        ), array(
            'id' => 'controller_ug',
            'class' => 'ajax_req'
        ));
        $userObj = new user ();
        $userList = $userObj->getUsersPair(array('user_desig'=>$_POST['usergroup']));
        
        $form->addElement('users', 'Users', 'select', '', array(
            'options' => $userList
        ), array(
            'id' => 'controller_usr',
            'class' => 'ajax_req'
        ));

        $controllersModelObj = new Aclcontrollers();
        $controllersList = $controllersModelObj->getControllerBy(array(
            'controller_module_id' => $decModuleId
        ));

        // $this->view->modulesList = $controllersList;

        if (count($_POST) > 0) {

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                $controllerAccessModelObj = new Aclcontrolleraccess();

                if ($_POST['users']) {
                    $cond['cacc_role_type'] = 2;
                    $cond['cacc_role_id'] = $_POST['users'];
                } else {
                    $cond['cacc_role_type'] = 1;
                    $cond['cacc_role_id'] = $_POST['usergroup'];

                    $userModelObj = new user();
                    $userList = $userModelObj->getUsersByUserGroup(array(
                        'user_desig' => $_POST['usergroup']
                    ));
                }

                $accessList = array();

                foreach ($_POST as $key => $data) {
                    if (strpos($key, '_') !== false) {

                        $controllerType = explode('_', $key);

                        if (isset($accessList[$controllerType[1]]))
                            array_push($accessList[$controllerType[1]], $controllerType[0]);
                        else
                            $accessList[$controllerType[1]] = array(
                                $controllerType[0]
                            );
                    }
                }

                foreach ($controllersList as $controller) {

                    if (is_array($accessList[$controller['controller_id']]) && in_array('create', $accessList[$controller['controller_id']]))
                        $caAccData['cacc_create_status'] = 2;
                    else
                        $caAccData['cacc_create_status'] = 1;

                    if (is_array($accessList[$controller['controller_id']]) && in_array('view', $accessList[$controller['controller_id']]))
                        $caAccData['cacc_view_status'] = 2;
                    else
                        $caAccData['cacc_view_status'] = 1;

                    if (is_array($accessList[$controller['controller_id']]) && in_array('update', $accessList[$controller['controller_id']]))
                        $caAccData['cacc_update_status'] = 2;
                    else
                        $caAccData['cacc_update_status'] = 1;

                    if (is_array($accessList[$controller['controller_id']]) && in_array('delete', $accessList[$controller['controller_id']]))
                        $caAccData['cacc_delete_status'] = 2;
                    else
                        $caAccData['cacc_delete_status'] = 1;

                    $cond['cacc_controller_id'] = $controller['controller_id'];
                    $caAccDataI = array_merge($caAccData, $cond);

                    $controllerAccessModelObj = new Aclcontrolleraccess();
                    $controllerAccess = $controllerAccessModelObj->getControllerRoleDetails($cond);

                    if ($controllerAccess['cacc_id']) {
                        $controllerStatus = $controllerAccessModelObj->modify($caAccDataI, $controllerAccess['cacc_id']);
                    } else {
                        $controllerStatus = $controllerAccessModelObj->add($caAccDataI);
                    }

                    if ($cond['cacc_role_type'] == 1) {
                        // find all users in the group and set acl
                        if (count($userList) > 0) {

                            foreach ($userList as $user) {

                                $ugCaAccCon = array(
                                    'cacc_controller_id' => $controller['controller_id'],
                                    'cacc_role_id' => $user['user_id'],
                                    'cacc_role_type' => 2
                                );
                                $controllerAccessModelObj = new Aclcontrolleraccess();
                                $controllerAccess = $controllerAccessModelObj->getControllerRoleDetails($ugCaAccCon);

                                $ugCaAccDataI = array_merge($ugCaAccCon, $caAccData);

                                if ($controllerAccess['cacc_id']) {
                                    $controllerStatus = $controllerAccessModelObj->modify($ugCaAccDataI, $controllerAccess['cacc_id']);
                                } else {
                                    $controllerStatus = $controllerAccessModelObj->add($ugCaAccDataI);
                                }
                            }
                        }
                    }

                    $actionsModelObj = new Aclactions();
                    $actionsList = $actionsModelObj->getActionsBy(array(
                        'action_controller_id' => $controller['controller_id']
                    ));

                    $actionsAccessModelObj = new Aclactionsaccess();

                    foreach ($actionsList as $actions) {

                        $acAccCon = array(
                            'aacc_action_id' => $actions['action_id'],
                            'aacc_role_id' => $caAccDataI['cacc_role_id'],
                            'aacc_role_type' => $caAccDataI['cacc_role_type']
                        );
                        $actionsAccessModelObj = new Aclactionsaccess();
                        $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($acAccCon);

                        if ($actions['action_type'] == 1)
                            $actAcsData['aacc_access_status'] = $caAccData['cacc_create_status'];

                        if ($actions['action_type'] == 2)
                            $actAcsData['aacc_access_status'] = $caAccData['cacc_update_status'];

                        if ($actions['action_type'] == 3)
                            $actAcsData['aacc_access_status'] = $caAccData['cacc_view_status'];

                        if ($actions['action_type'] == 4)
                            $actAcsData['aacc_access_status'] = $caAccData['cacc_delete_status'];

                        $actAcsDataI = array_merge($acAccCon, $actAcsData);

                        if ($actionsAccess['aacc_id']) {
                            $actionsAccessModelObj->modify($actAcsDataI, $actionsAccess['aacc_id']);
                        } else {

                            $actionsAccessModelObj->add($actAcsDataI);
                        }

                        if ($cond['cacc_role_type'] == 1) {
                            // find all users in the group and set acl
                            if (count($userList) > 0) {

                                foreach ($userList as $user) {

                                    $ugAcAccCon = array(
                                        'aacc_action_id' => $actions['action_id'],
                                        'aacc_role_id' => $user['user_id'],
                                        'aacc_role_type' => 2
                                    );
                                    $actionsAccessModelObj = new Aclactionsaccess();
                                    $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($ugAcAccCon);

                                    $ugActAcsDataI = array_merge($ugAcAccCon, $actAcsData);

                                    if ($actionsAccess['aacc_id']) {
                                        $actionsAccessModelObj->modify($ugActAcsDataI, $actionsAccess['aacc_id']);
                                    } else {

                                        $actionsAccessModelObj->add($ugActAcsDataI);
                                    }
                                }
                            }
                        }
                    }
                }

                /*
                 * print_r ( $accessList );
                 * echo "<pre>";
                 * print_r($_POST);
                 * die();
                 */
            }
        } else {
            
            
            if(is_array($_GET) && !empty(array_filter($_GET)))
            {
                $form->usergroup->setValue($_GET['usergroup']);
                $form->users->setValue($_GET['users']);
            }

            /*
             * $empModelObj->emp_id = $employeeId;
             * $employeeDetail = $empModelObj->getEmployeeDetById();
             * if(!$this->validator->checkIsEmpty($employeeDetail->emp_id))
             * {
             * $editEmployeeForm->empFname->setValue($employeeDetail->emp_fname);
             * $editEmployeeForm->empLname->setValue($employeeDetail->emp_lname);
             * $editEmployeeForm->empNationality->setValue($employeeDetail->emp_nationality);
             * $editEmployeeForm->reference->setValue($encryptedEmployeeId);
             * }
             * else
             * {
             * LB_Feedback::seterror('employee_not_exists');
             * }
             *
             */
        }

        $this->view->form = $form;
        $this->view->controllersList = $controllersList;
    }

    public function setactionsAction()
    {

        // $moduleObj = objm('Aclmodules','acl');
        require_once __DIR__ . '/!model/Aclactions.php';
        require_once __DIR__ . '/!model/Aclcontrollers.php';
        require_once __DIR__ . '/!model/Aclmodules.php';
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';
        require_once __DIR__ . '/../admin/!model/user.php';

        $form = new form();
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
        $form->addElement('usergroup', 'User Group', 'select', 'required', array(
            'options' => $roll
        ), array(
            'id' => 'action_ug',
            'class' => 'ajax_req'
        ));
        
        
        $userObj = new user ();
        $userList = $userObj->getUsersPair(array('user_desig'=>$_POST['usergroup']));
        
        $form->addElement('users', 'Users', 'select', '', array(
            'options' => $userList
        ), array(
            'id' => 'action_usr',
            'class' => 'ajax_req'
        ));

        $controllerId = $this->view->decode($this->view->param['ref']);

        if (! $controllerId)
            die('tampered');

        $actionsModelObj = new Aclactions();
        $actionsList = $actionsModelObj->getActionsBy(array(
            'action_controller_id' => $controllerId
        ));
        
        $controllerModelObj = new Aclcontrollers();
        $contollerDetails = $controllerModelObj->getControllerById($controllerId);
        $this->view->moduleId = $this->view->encode($contollerDetails['controller_module_id']);

        if (count($_POST) > 0) {

            $valid = $form->vaidate($_POST, $_FILES);
            $valid = $valid[0];
            if ($valid == true) {

                $actionsAccessModelObj = new Aclactionsaccess();

                if ($_POST['users']) {
                    $acAccCon['aacc_role_type'] = 2;
                    $acAccCon['aacc_role_id'] = $_POST['users'];
                } else {
                    $acAccCon['aacc_role_type'] = 1;
                    $acAccCon['aacc_role_id'] = $_POST['usergroup'];

                    $userModelObj = new user();
                    $userList = $userModelObj->getUsersByUserGroup(array(
                        'user_desig' => $_POST['usergroup']
                    ));
                }

                $accessList = array();

                foreach ($_POST as $key => $data) {
                    if (strpos($key, '_') !== false) {

                        $controllerType = explode('_', $key);

                        if (isset($accessList[$controllerType[1]]))
                            array_push($accessList[$controllerType[1]], $controllerType[0]);
                        else
                            $accessList[$controllerType[1]] = array(
                                $controllerType[0]
                            );
                    }
                }

                foreach ($actionsList as $action) {
                    $actionsAccessModelObj = new Aclactionsaccess();

                    $actAcsData = array(); // $acAccCon=array();

                    if (is_array($accessList[$action['action_id']]) && in_array('access', $accessList[$action['action_id']]))
                        $actAcsData['aacc_access_status'] = 2;
                    else
                        $actAcsData['aacc_access_status'] = 1;

                    $acAccCon['aacc_action_id'] = $action['action_id'];

                    $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($acAccCon);

                    $actAcsDataI = array_merge($acAccCon, $actAcsData);

                    if ($actionsAccess['aacc_id']) {
                        $actionsAccessModelObj->modify($actAcsDataI, $actionsAccess['aacc_id']);
                    } else {
                        $actionsAccessModelObj->add($actAcsDataI);
                    }

                    if ($acAccCon['aacc_role_type'] == 1) {

                        if (count($userList) > 0) {

                            foreach ($userList as $user) {
                                $actionsAccessModelObj = new Aclactionsaccess();

                                $ugAcAccCon = array(
                                    'aacc_action_id' => $action['action_id'],
                                    'aacc_role_id' => $user['user_id'],
                                    'aacc_role_type' => 2
                                );

                                $actionsAccess = $actionsAccessModelObj->getActionsRoleDetails($ugAcAccCon);

                                $ugActAcsDataI = array_merge($ugAcAccCon, $actAcsData);

                                if ($actionsAccess['aacc_id']) {
                                    $actionsAccessModelObj->modify($ugActAcsDataI, $actionsAccess['aacc_id']);
                                } else {

                                    $actionsAccessModelObj->add($ugActAcsDataI);
                                }
                            }
                        }
                    }
                }
            }
        }else{
            
            if(is_array($_GET) && !empty(array_filter($_GET)))
            {
                $form->usergroup->setValue($_GET['usergroup']);
                $form->users->setValue($_GET['users']);
            }
            
        }
        $this->view->form = $form;
        $this->view->actionsList = $actionsList;
        $this->view->action_type = array(
            '1' => 'Create',
            2 => 'Update',
            3 => 'View',
            4 => 'Delete'
        );
    }

    public function getpermissionAction($return = null)
    {

        // $moduleObj = objm('Aclmodules','acl');
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';

        $roleDetailsArray = array();

        $Id = $_POST['id'];
        $type = $_POST['type'];

        // s($_POST);

        if ($type == 'module_ug' || $type == 'module_usr') {
            $moduleModelObj = new Aclmodulesaccess();
            if ($type == 'module_ug')
                $moduleCon['macc_role_type'] = 1;
            elseif ($type == 'module_usr')
                $moduleCon['macc_role_type'] = 2;
            $moduleCon['macc_role_id'] = $Id;
            $roleDetailsArray = $moduleModelObj->getModuleRoleDetailsByRoles($moduleCon, $return);
        }

        if ($type == 'controller_ug' || $type == 'controller_usr') {

            // $encryptedModuleId = $this->_getParam ( 'reference' );
            // $moduleId = $this->_helper->decrypt ( $encryptedModuleId );

            $controllerModelObj = new Aclcontrolleraccess();
            if ($type == 'controller_ug')
                $controllerCon['cacc_role_type'] = 1;
            elseif ($type == 'controller_usr')
                $controllerCon['cacc_role_type'] = 2;
            $controllerCon['cacc_role_id'] = $Id;
            $roleDetailsArray = $controllerModelObj->getControllerRoleDetailsByRoles($controllerCon);
        }

        if ($type == 'action_ug' || $type == 'action_usr') {

            // $encryptedModuleId = $this->_getParam ( 'reference' );
            // $moduleId = $this->_helper->decrypt ( $encryptedModuleId );
            $actionsAccessModelObj = new Aclactionsaccess();
            if ($type == 'action_ug')
                $actionsCon['aacc_role_type'] = 1;
            elseif ($type == 'action_usr')
                $actionsCon['aacc_role_type'] = 2;
            $actionsCon['aacc_role_id'] = $Id;
            $roleDetailsArray = $actionsAccessModelObj->getActionsRoleDetailsByRoles($actionsCon);
        }

        if ($return) {
            return $roleDetailsArray;
        }

        echo json_encode($roleDetailsArray);
        exit(0);
    }

    public function resourcesAction()
    {
        require_once __DIR__ . '/!model/Aclactions.php';
        require_once __DIR__ . '/!model/Aclcontrollers.php';
        require_once __DIR__ . '/!model/Aclmodules.php';
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';

        $ar = new addresources();
        $ar->addresourcesAction();
    }
    
    
    public function deleteaccessAction($param = array())
    {
        require_once __DIR__ . '/!model/Aclactionsaccess.php';
        $actionsAccessModelObj = new Aclactionsaccess();
        $actionsAccessModelObj->deleteActionsAccessByUser(['aacc_role_id'=>$param['users']]);
        
        require_once __DIR__ . '/!model/Aclcontrollersaccess.php';
        $controllerAccessModelObj = new Aclcontrolleraccess();
        $controllerAccessModelObj->deleteControllerAccessByUser(['cacc_role_id'=>$param['users']]);
        
        require_once __DIR__ . '/!model/Aclmodulesaccess.php';
        $moduleAccessModelObj = new Aclmodulesaccess();
        $moduleAccessModelObj->deleteModuleAccessByUser(['macc_role_id'=>$param['users']]);
  
    }
}

class addresources
{

    protected $_actionTypes = array(
        'create' => '1',
        'update' => '2',
        'view' => '3',
        'delete' => '4'
    );

    protected $_resourceTypes = array(
        'application' => '1',
        'ajax' => '2',
        'default' => '3'
    );

    protected $_moduleArray = array();

    protected $_controllerArray = array();

    protected $_actionArray = array();

    public function __construct()
    {
        $moduleObj = new Aclmodules();
        $this->_moduleArray = $moduleObj->getModulePair();
    }

    function addresourcesAction()
    {
        // error_reporting(E_ERROR | E_PARSE);
        $acl_file = __DIR__ . '/configs/' . 'acl.xml';

        // d($acl_file);

        if (file_exists($acl_file)) {

            $xmlString = file_get_contents($acl_file);

            $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $aclarray = json_decode($json, TRUE);
            $aclarray = $aclarray['acl'];
        } else
            die($acl_file);

        // print_r ( $aclarray );
        foreach ($aclarray['module'] as $module) {
            self::addModule($module);
            foreach ($module as $con_key => $controller) {
                if (isset($controller['0'])) {
                    if ($con_key == 'controller')
                        foreach ($controller as $controller) {

                            self::addController($controller, $module);
                            foreach ($controller as $act_key => $actions) {
                                if (isset($actions['0'])) {
                                    if ($act_key == 'action')
                                        foreach ($actions as $actions) {
                                            $aclList[] = self::formAclList($module, $controller, $actions);
                                        }
                                } else {
                                    $aclList[] = self::formAclList($module, $controller, $actions);
                                }
                            }
                        }
                } else {

                    self::addController($controller, $module);
                    $actions = $controller['action'];

                    if (isset($actions['0'])) {
                        foreach ($actions as $actions) {
                            $aclList[] = self::formAclList($module, $controller, $actions);
                        }
                    } else {
                        $aclList[] = self::formAclList($module, $controller, $actions);
                    }
                }
            }
        }

        // a ( $aclList );
        // die ();
    }

    function formAclList($module, $controller, $actions)
    {
        $actions['module_name'] = $module['name'];
        $actions['module_label'] = $module['label'];
        $actions['module_desc'] = $module['description'];
        $actions['controller_name'] = $controller['name'];
        $actions['controller_label'] = $controller['label'];
        $actions['controller_desc'] = $controller['description'];

        self::addAction($actions);

        return $actions;
    }

    function addModule($module)
    {
        if (! in_array($module['name'], $this->_moduleArray)) {
            $moduleObj = new Aclmodules();
            $newModule = $moduleObj->add(array(
                'module_name' => $module['name'],
                'module_label' => $module['label'],
                'module_desc' => $module['description']
            ));
            if ($newModule)
                $this->_moduleArray[$newModule] = $module['name'];
        }
    }

    function addController($controller, $module)
    {
        $controllerObj = new Aclcontrollers();
        $cond['controller_module_id'] = array_search($module['name'], $this->_moduleArray);
        $this->_controllerArray = $controllerObj->getControllerPair($cond);

        if (! in_array($controller['name'], $this->_controllerArray)) {
            $controllerObj = new Aclcontrollers();
            $moduleId = array_search($module['name'], $this->_moduleArray);
            if ($moduleId) {
                $newController = $controllerObj->add(array(
                    'controller_module_id' => $moduleId,
                    'controller_name' => $controller['name'],
                    'controller_label' => $controller['label'],
                    'controller_desc' => $controller['description']
                ));
                if ($newController)
                    $this->_controllerArray[$newController] = $controller['name'];
            } else {
                // error
            }
        }
    }

    function addAction($actions)
    {
        $actionsObj = new Aclactions();
        $cond['controller_module_id'] = array_search($actions['module_name'], $this->_moduleArray);
        $cond['action_controller_id'] = array_search($actions['controller_name'], $this->_controllerArray);
        $this->_actionArray = $actionsObj->getActionsPair($cond);

        if (! in_array($actions['name'], $this->_actionArray)) {
            $actionsObj = new Aclactions();
            $controllerId = array_search($actions['controller_name'], $this->_controllerArray);
            if ($controllerId) {
                $newAction = $actionsObj->add(array(
                    'action_controller_id' => $controllerId,
                    'action_name' => $actions['name'],
                    'action_label' => $actions['label'],
                    'action_desc' => $actions['description'],
                    'action_type' => $this->_actionTypes[$actions['type']],
                    'action_resource' => $this->_resourceTypes[$actions['resource']]
                ));
                if ($newAction)
                    $this->_actionArray[$newAction] = $actions['name'];
            } else {
                // error
            }
        }
    }
}

?>
