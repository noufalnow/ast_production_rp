<?php
class customerController extends mvc {
	public function addAction() {
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/customer.php';
		$formRender = true;
		$form = new form ();
		$contact = new contacts ();
		$customer = new customer ();
		
		$form->addElement ( 'customer', 'Customer/Company', 'text', 'required' );
		$form->addElement ( 'name', 'Name(C/o) ', 'text', '' );
		$form->addElement ( 'house', 'Address', 'text', '' );
		$form->addElement ( 'street1', 'Street Address 1', 'text', '' );
		$form->addElement ( 'street2', 'Street Address 2', 'text', '' );
		$form->addElement ( 'place', 'Place Name ', 'text', '' );
		$form->addElement ( 'locality', 'Locality', 'text', 'alpha_space' );
		$form->addElement ( 'zip', 'Zip Code', 'text', '', 'numeric' );
		$form->addElement ( 'phone', 'Phone No', 'text', '' );
		$form->addElement ( 'mobile', 'Mobile No', 'text', 'required|numeric' );
		$form->addElement ( 'remarks', 'Remarks', 'text', 'alpha_space' );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
					$data = array (
							'cust_name' => $valid ['customer'],
							'cust_remarks' => $valid ['remarks'] 
					);
					$custId = $customer->add ( $data );
					
					if ($custId) {
						$data = array (
								'con_ref_type' => CONT_TYPE_CUST,
								'con_ref_id' => $custId,
								'con_type' => '4', // customer type contacts
								'con_name' => $valid ['name'],
								'con_house' => $valid ['house'],
								'con_street1' => $valid ['street1'],
								'con_place' => $valid ['place'],
								'con_locality' => $valid ['locality'],
								'con_zip_code' => $valid ['zip'],
								'con_phone' => $valid ['phone'],
								'con_mobile' => $valid ['mobile'] 
						
						);
						
						$contactId = $contact->add ( $data );
						
						$feedback = 'Customer details added successfully';
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
		
		$this->view->form = $form;
	}
	public function editAction() {
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/customer.php';
		$formRender = true;
		$form = new form ();
		
		$customer = new customer ();
		$contact = new contacts ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$custId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $custId)
			die ( 'tampered' );
		
		$form->addElement ( 'customer', 'Customer/Company', 'text', 'required' );
		$form->addElement ( 'name', 'Name(C/o) ', 'text', '' );
		$form->addElement ( 'house', 'Address', 'text', '' );
		$form->addElement ( 'street1', 'Street Address 1', 'text', '' );
		$form->addElement ( 'street2', 'Street Address 2', 'text', '' );
		$form->addElement ( 'place', 'Place Name ', 'text', '' );
		$form->addElement ( 'locality', 'Locality', 'text', 'alpha_space' );
		$form->addElement ( 'zip', 'Zip Code', 'text', '', 'numeric' );
		$form->addElement ( 'phone', 'Phone No', 'text', '' );
		$form->addElement ( 'mobile', 'Mobile No', 'text', 'required|numeric' );
		$form->addElement ( 'remarks', 'Remarks', 'text', 'alpha_space' );
		$customerDetails = $customer->getCustomerDet ( array (
				'cust_id' => $custId 
		) );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
					$data = array (
							'cust_name' => $valid ['customer'],
							'cust_remarks' => $valid ['remarks'] 
					);
					$custModified = $customer->modify ( $data, $custId );
					
					if ($custModified) {
						$data = array (
								'con_name' => $valid ['name'],
								'con_house' => $valid ['house'],
								'con_street1' => $valid ['street1'],
								'con_place' => $valid ['place'],
								'con_locality' => $valid ['locality'],
								'con_zip_code' => $valid ['zip'],
								'con_phone' => $valid ['phone'],
								'con_mobile' => $valid ['mobile'] 
						
						);
						
						$modifyContact = $contact->modify ( $data, $customerDetails ['con_id'] );
						
						$feedback = 'Customer details added successfully';
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
		else {
			$form->customer->setValue ( $customerDetails ['cust_name'] );
			$form->name->setValue ( $customerDetails ['con_name'] );
			$form->house->setValue ( $customerDetails ['con_house'] );
			$form->street1->setValue ( $customerDetails ['con_street1'] );
			$form->place->setValue ( $customerDetails ['con_place'] );
			$form->locality->setValue ( $customerDetails ['con_locality'] );
			$form->zip->setValue ( $customerDetails ['con_zip_code'] );
			$form->phone->setValue ( $customerDetails ['con_phone'] );
			$form->mobile->setValue ( $customerDetails ['con_mobile'] );
			$form->remarks->setValue ( $customerDetails ['cust_remarks'] );
		}
		
		$this->view->form = $form;
	}
	public function listAction() {
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/customer.php';
		
		$form = new form ();
		
		$form->addElement ( 'f_code', 'Code', 'text', 'alpha_space' );
		$form->addElement ( 'f_customer', 'Customer/Company', 'text', 'alpha_space' );
		$form->addElement ( 'f_name', 'Name(C/o) ', 'text', 'alpha_space' );
		$form->addElement ( 'f_house', 'Address', 'text', 'alpha_space' );
		
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			$form->reset ();
			unset ( $_GET );
		}
		
		$filter_class = 'btn-primary';
		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				$where = array (
						'f_code' => @$valid ['f_code'],
						'f_customer' => @$valid ['f_customer'],
						'f_name' => @$valid ['f_name'],
						'f_house' => @$valid ['f_house'] 
				);
			}
			$filter_class = 'btn-info';
		}
		
		$customerObj = new customer ();
		
		// s($where);
		
		$customerList = $customerObj->getCustomerPaginate ( @$where );
		
		// s($customerList);
		
		$offset = $customerObj->_voffset;
		$this->view->customerList = $customerList;
		$this->view->form = $form;
		$this->view->customerObj = $customerObj;
		$this->view->filter_class = $filter_class;
	}
	public function deleteAction() {
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/customer.php';
		$formRender = true;
		
		$customer = new customer ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$decCustId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decCustId)
			die ( 'tampered' );
		
		$customerDetail = $customer->getCustomerDet ( array (
				'cust_id' => $decCustId 
		) );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				$delete = $customer->deleteCustomer ( $decCustId );
				if ($delete) {
					
					require_once __DIR__ . '/../admin/!model/contacts.php';
					$contact = new contacts ();
					$contact->deleteContact ( $customerDetail ['con_id'] );
					 $this->view->NoViewRender = true;
					$success = array (
							'feedback' => 'The customer has been deleted successfully from the system  .' 
					);
					$_SESSION ['feedback'] = 'The customer has been deleted successfully from the system ';
					$success = json_encode ( $success );
					die ( $success );
				}
			}
		}
	}
	public function viewAction() {
		//$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/customer.php';
		$formRender = true;
		
		$customer = new customer ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$decCustId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decCustId)
			die ( 'tampered' );
		
		$customerDetail = $customer->getCustomerDet ( array (
				'cust_id' => $decCustId 
		) );
		
		$this->view->customerDetail = $customerDetail;
		$this->view->decCustId = $decCustId;
	}
}
