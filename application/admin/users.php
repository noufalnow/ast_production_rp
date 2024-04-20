<?php
class usersController extends mvc {
	public function indexAction() {
		
// 		/$this->view->breadcrumb(array('default/default/dashboard'	=>'Dashboard','Users'));
		
		$form = new form ();
		$roll = array (
				1 => "Admin",
		        2 => "Director",
				3 => "Manager",
    		    4 => "Accountant",
    		    5 => "Sales",
    		    6 => "Purchase",
    		    7 => "Maintenance",
		        8 => "Office Assistant",
		);
		$form->addElement ( 'f_fname', 'First Name ', 'text','' );
		$form->addElement ( 'f_lname', 'Last Name ', 'text','' );
		$form->addElement ( 'f_uname', 'User Name ', 'text','' );
		$form->addElement ( 'f_desig', 'Role privilege', 'select','',array('options'=>$roll) );
		
		$_GET = array_filter ( $_GET );
		
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			$form->reset ();
			unset ( $_GET );
		}
		
		$filter_class = 'btn-primary';
		if(isset($_GET) && count($_GET)>0){
			$valid = $form->vaidate($_GET);
			
			
			
			$valid = $valid[0];
			if($valid==true){
				$where = array('user_fname'=>@$valid['f_fname'],
						'user_lname'=>@$valid['f_lname'],
						'user_uname'=>@$valid['f_uname'],
						'user_desig'=>@$valid['f_desig']
				);
			}
			$filter_class = 'btn-info';
		}
		include '!model/user.php';
		
		$userObj = new user ();
		$this->view->usersList = $userObj->getUsersPaginate( @$where );
		$this->view->form = $form;
		$this->view->userObj = $userObj;
		$this->view->roll  = $roll;
		$this->view->filter_class = $filter_class;
	}
	public function addAction() {

	    $this->view->NoViewRender = false;
	    $this->view->response('ajax');
	    
	    require_once __DIR__ . '/../admin/!model/employee.php';
	    $empModelObj = new employee();
	    $empList = $empModelObj->getEmployeePair();
		
		$form = new form ();
			// $roll =array(2=>"Admin",3=>"Projects",4=>"Machineries",5=>"Manager", 6=>"K Admin", 7=>"K Users");
			
		$roll = array (
		    1 => "Admin",
		    2 => "Director",
		    3 => "Manager",
		    4 => "Accountant",
		    5 => "Sales",
		    6 => "Purchase",
		    7 => "Maintenance",
		    8 => "Office Assistant",
		);
		
		$form->addElement ( 'fname', 'First Name ', 'text','required|alpha_space' );
		$form->addElement ( 'lname', 'Last Name ', 'text','required|alpha_space' );
		$form->addElement ( 'uname', 'User Name ', 'text','required|alpha_space', '' , ["autocomplete"=>"new-password"] );
		$form->addElement ( 'password', 'Password', 'password','required' );
		$form->addElement ( 'desig', 'Role', 'select','required',array('options'=>$roll) );
		$form->addElement ( 'status', 'Status', 'radio','required',array('options'=>array(1=>"Enable", 2=>"Disable")) );
		$form->addElement ( 'employee', 'Employee', 'select','required',array('options'=>$empList) );
		$form->addElement ( 'email', 'e-mail ', 'text','required|valid_email' );
		
		if (isset ( $_POST ) && count ( $_POST ) > 0) {
			
			$valid = $form->vaidate ( $_POST, $_FILES );
			
			//print_r($valid);
			
			$valid = $valid [0];
			if ($valid == true) {
				
				require_once __DIR__ . '/../admin/!model/user.php';
				$user = new user ();
				
				$chekUname = $user->getUser(array('user_uname'=>$valid['uname']));
				if($chekUname['user_id']){
					$form->uname->setError("User name already selected by other user");
				}
				else{
					$data = array('user_fname'=>$valid['fname'],
							'user_lname'=>$valid['lname'],
							'user_uname'=>$valid['uname'],
							'user_password'=>password_hash($valid['password'], PASSWORD_DEFAULT),
							'user_desig'=>$valid['desig'],
							'user_status'=>$valid['status'],
					        'user_emp_id' => $valid ['employee'] ==''? NULL : $valid ['employee'],
					);
					$insert = $user->add($data);
					
					if ($insert) {
						
						
						$data = array (
								'ubr_init_branch' => 1,
								'ubr_init_user' => 1,
								'ubr_branch' => $valid ['branch'],
								
						);
						
						require_once __DIR__ . "/../acl/config.php";
						$moduleAcl= new configController();
						
						$moduleAcl->setmodulesAction ( array (
						    'users' => $insert,
						    'ug_target' => $valid ['desig'],
						) );
						
						$feedback = 'User details added successfully  .';
						
						$this->view->NoViewRender = true;
						$success = array (
						    'feedback' => $feedback
						);
						$_SESSION ['feedback'] = $feedback;
						$success = json_encode ( $success );
						die ( $success );
					}
					
				}
		
			}
			else{
				$_SESSION ['error'] = 'User details added error';
			}
		}
		$this->view->form  = $form;
	}
	public function editAction() {
		$this->view->NoViewRender = false;
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/user.php';
		
		require_once __DIR__ . '/../admin/!model/employee.php';
		$empModelObj = new employee();
		$empList = $empModelObj->getEmployeePair();
		
		$form = new form ();
		$user = new user ();

		$decUserId= $this->view->decode ( $this->view->param ['ref'] );
		$userDetail= $user->getUserById ( $decUserId );
		
		if (! $decUserId)
			die ( 'tampered' );
		
		$form = new form ();
		$roll = array (
		    1 => "Admin",
		    2 => "Director",
		    3 => "Manager",
		    4 => "Accountant",
		    5 => "Sales",
		    6 => "Purchase",
		    7 => "Maintenance",
		    8 => "Office Assistant",
		);
			
			$form->addElement ( 'fname', 'First Name ', 'text','required|alpha_space' );
			$form->addElement ( 'lname', 'Last Name ', 'text','required|alpha_space' );
			$form->addElement ( 'desig', 'Role', 'select','required',array('options'=>$roll) );
			$form->addElement ( 'status', 'Status', 'radio','required',array('options'=>array(1=>"Enable", 2=>"Disable")) );
			$form->addElement ( 'employee', 'Employee', 'select','required',array('options'=>$empList) );
			$form->addElement ( 'email', 'e-mail ', 'text','' );
			
			
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				if($_POST['type']==2){
					$form->_rules[uname] = 'required|alpha_space';
					if($_POST['password']!='')
						$form->_rules[password] = 'required|min_len,4';
				}
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					$data = array (
							'user_fname' => $valid ['fname'],
							'user_lname' => $valid ['lname'],
							'user_desig' => $valid ['desig'],
							'user_status' => $valid ['status'],
					        'user_emp_id' => $valid ['employee'] ==''? NULL : $valid ['employee'],
					        'user_email' => $valid ['email'] ==''? NULL : $valid ['email'],
					);
					
					$update = $user->modify ( $data, $decUserId );
					if ($update) {
					    				    
					    if(strcmp( (int) $valid ['desig'] ,$userDetail['user_desig']) !='0')
					    {
    						require_once __DIR__ . "/../acl/config.php";
    						$moduleAcl= new configController();
    						$moduleAcl->deleteaccessAction(array (
    						    'users' => $decUserId
    						));
    						$moduleAcl= new configController();
    						$moduleAcl->setmodulesAction ( array (
    						    'users' => $decUserId,
    						    'ug_target' => $valid ['desig'],
    						) );
					    }
						
						$feedback = 'User details updated successfully  .';
						
						$this->view->NoViewRender = true;
						$success = array (
								'feedback' => $feedback 
						);
						$_SESSION ['feedback'] = $feedback;
						$success = json_encode ( $success );
						die ( $success );
					}
				}
			}
		} else {
			$userDetail = $user->getUserById ( $decUserId );			
			$form->fname->setValue($userDetail['user_fname']);
			$form->lname->setValue($userDetail['user_lname']);
			$form->desig->setValue($userDetail['user_desig']);
			$form->status->setValue($userDetail['user_status']);
			$form->employee->setValue($userDetail['user_emp_id']);
			$form->email->setValue($userDetail['user_email']);
		}
		$this->view->form  = $form;

	}
	public function deleteAction() {
		require_once __DIR__ . '/../admin/!model/user.php';
		$this->view->formRender  = true;
		$this->view->response('ajax');
		
		$form = new form ();
		$user = new user ();
		
		$decUserId= $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decUserId)
			die ( 'tampered' );
		
		$this->view->userDetail= $user->getUserById ( $decUserId );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				$delete = $user->deleteUser ( $decUserId );
				if ($delete) {
					$this->view->formRender  = false;
					$success = array (
							'feedback' => 'The user has been deleted successfully from the system  .' 
					);
					$_SESSION ['feedback'] = 'The user has been deleted successfully from the system ';
					$success = json_encode ( $success );
					die ( $success );
				}
			}
		}
		$this->view->form  = $form;
	}
	public function changepwdAction() {
		require_once __DIR__ . '/../admin/!model/user.php';
		$this->view->formRender  = true;
		$this->view->response('ajax');
		
		$form = new form ();
		$user = new user ();
		
		$decUserId= $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decUserId)
			die ( 'tampered' );
		$this->view->userDetail= $user->getUserById ( $decUserId );
		$form->addElement ( 'oldpassword', 'Old password', 'password', 'required' );
		$form->addElement ( 'password', 'Password', 'password', 'required' );
		$form->addElement ( 'conPassword', 'Confirm password', 'password', 'required' );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				$valid = $form->vaidate ( $_POST );
				$valid = $valid [0];
				if ($valid == true) {
				
					if (password_verify ( $valid ['oldpassword'], $this->view->userDetail['user_password'] )) {
						
						if ($valid ['password'] == $valid ['conPassword']) {
							
							$data = array (
									'user_password' => password_hash ( $valid ['password'], PASSWORD_DEFAULT ) 
							);
							$update = $user->modify ( $data, $decUserId );
							if ($update) {
								$this->view->NoViewRender = true;
								$success = array (
										'feedback' => 'New password updated successfully.' 
								);
								$_SESSION ['feedback'] = 'New password updated successfully';
								$success = json_encode ( $success );
								die ( $success );
							}
						} else {
							$form->password->_error = "Password mismatch";
							$form->conPassword->_error = "Password mismatch";
						}
					} else {
						$form->oldpassword->setError ( "Wrong password" );
					}
				}
			}
		}
		$this->view->form  = $form;
	}
	public function resetAction() {
		require_once __DIR__ . '/../admin/!model/user.php';
		$this->view->response('ajax');
		
		$form = new form ();
		$user = new user ();
		
		$decUserId= $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decUserId)
			die ( 'tampered' );
		$this->view->userDetail= $user->getUserById ( $decUserId );
		$form->addElement ( 'password', 'Password', 'password', 'required' );
		$form->addElement ( 'conPassword', 'Comfirm password', 'password', 'required' );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				$valid = $form->vaidate ( $_POST );
				$valid = $valid [0];
				if ($valid == true) {
					if ($valid ['password'] == $valid ['conPassword']) {
						
						$data = array (
								'user_password' => password_hash ( $valid ['password'], PASSWORD_DEFAULT ) 
						);
						$update = $user->modify ( $data, $decUserId );
						if ($update) {
							$this->view->NoViewRender = true;
							$success = array (
									'feedback' => 'Password reset done successfully .' 
							);
							$_SESSION ['feedback'] = 'Password reset done successfully';
							$success = json_encode ( $success );
							die ( $success );
						}
					} else {
						$form->password->_error = "Password mismatch";
						$form->conPassword->_error = "Password mismatch";
					}
				}
			}
		}
		$this->view->form  = $form;
	}
	public function viewAction() {
		require_once __DIR__ . '/../admin/!model/user.php';
		$this->view->response('ajax');
		
		$user = new user ();
		
		$decUserId= $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decUserId)
			die ( 'tampered' );
		
		$this->view->userDetail= $user->getUserById ( $decUserId );
		
		$this->view->roll = array (
				1 => "Normal Contact",
				2 => "Previlleged Contact",
		);
		$this->view->status = array (
				1 => "Enable",
				2 => "Disable" 
		);
	}
	public function getusersAction() {
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' );
			} else {
				require_once __DIR__.'/!model/user.php';
				$users = new user();
				$usersList = $users->getUsersPair ( array (
						'user_desig' => $_POST ['refId'] 
				) );
				
				$data [] = array (
				    'key' => '',
				    'value' => ' -- Select -- '
				);
				
				if (count ( $usersList ))
					foreach ( $usersList as $key => $val )
						$data [] = array (
								'key' => $key,
								'value' => $val 
						);
				$data = json_encode ( $data );
				die ( $data );
			}
		}
	}
	
	public function branchAction() {}
	
}