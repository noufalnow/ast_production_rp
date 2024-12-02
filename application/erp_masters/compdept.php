<?php
class compdeptController extends mvc {
	public function addAction() {
		$form = new form ();
		
		__DIR__ . '/../../admin/!model/company.php';
		$compModelObj = new company ();
		
		$compList = $compModelObj->getCompanyPair ();
		
		__DIR__ . '/../../admin/!model/department.php';
		$deptModelObj = new department ();
		$deptList = $deptModelObj->getDeptPair ();
		
		$form->addElement ( 'company', 'Company', 'select', 'required', array (
				'options' => $compList 
		) );
		$form->addElement ( 'dept', 'Department', 'select', 'required', array (
				'options' => $deptList 
		) );
		
		if (isset ( $_POST ) && count ( $_POST ) > 0) {
			$valid = $form->vaidate ( $_POST, $_FILES );
			$valid = $valid [0];
			if ($valid == true) {
				__DIR__ . '/../../admin/!model/comp_department.php';
				$compDepetObj = new comp_department ();
				$chekDept = $compDepetObj->chekDept ( $valid );
				if (count ( $chekDept ) > 0) {
					$form->dept->setError ( "Selected department already selected to the company" );
				} else {
					$data = array (
							'cmpdept_dept_id' => $valid ['dept'],
							'cmpdept_comp_id' => $valid ['company'] 
					);
					$insert = $compDepetObj->add ( $data );
					if ($insert) {
						$_SESSION ['feedback'] = 'Company department details added successfully';
						header ( "Location:list.php" );
					}
				}
			}
		}
		$this->view->form = $form;
	}
	public function editAction() {
		$this->view->response ( 'ajax' );
		__DIR__ . '/../../admin/!model/user.php';
		$formRender = true;
		
		$form = new form ();
		$user = new user ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		//$crypt = new encryption ();
		//$decUserId = $crypt->decode ( $ref );
		
		if (! $decUserId)
			die ( 'tampered' );
		
		$form->addElement ( 'fname', 'First Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'lname', 'Last Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'uname', 'User Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'desig', 'Designation', 'select', 'required', array (
				'options' => array (
						"" => "Select Designation",
						1 => "Admin",
						2 => "Manager",
						3 => "User" 
				) 
		) );
		$form->addElement ( 'dept', 'Department', 'select', 'required', array (
				'options' => array (
						"" => "Select Department",
						1 => "Head Office",
						2 => "Construction" 
				) 
		) );
		$form->addElement ( 'status', 'Status', 'radio', 'required', array (
				'options' => array (
						1 => "Enable",
						2 => "Disable" 
				) 
		) );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					__DIR__ . '/../../admin/!model/user.php';
					$chekUname = $user->getUser ( array (
							'user_uname' => $valid ['uname'],
							'ex_user_id' => $decUserId 
					) );
					if ($chekUname ['user_id']) {
						$form->uname->setError ( "User name already selected by other user" );
					} else {
						$data = array (
								'user_fname' => $valid ['fname'],
								'user_lname' => $valid ['lname'],
								'user_uname' => $valid ['uname'],
								'user_desig' => $valid ['desig'],
								'user_dept' => $valid ['dept'],
								'user_status' => $valid ['status'] 
						);
						
						$update = $user->modify ( $data, $decUserId );
						if ($update) {
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
			}
		} else {
			$userDetail = $user->getUserById ( $decUserId );
			
			$form->fname->setValue ( $userDetail ['user_fname'] );
			$form->lname->setValue ( $userDetail ['user_lname'] );
			$form->uname->setValue ( $userDetail ['user_uname'] );
			$form->desig->setValue ( $userDetail ['user_desig'] );
			$form->dept->setValue ( $userDetail ['user_dept'] );
			$form->status->setValue ( $userDetail ['user_status'] );
		}
		$this->view->form = $form;
	}
	public function listAction() {
		__DIR__ . '/../../admin/!model/comp_department.php';
		
		$compDepetObj = new comp_department ();
		$compDeptList = $compDepetObj->getCompDeptList ();
		$offset = $compDepetObj->_voffset;
	}
}
