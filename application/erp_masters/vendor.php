<?php
class vendorController extends mvc {
	public function addAction() {
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$formRender = true;
		$form = new form ();
		$contact = new contacts ();
		$vendor = new vendor ();
		
		$form->addElement ( 'vendor', 'Vendor/Company', 'text', 'required' );
		$form->addElement ( 'name', 'Name(C/o) ', 'text', '' );
		$form->addElement ( 'vatNo', 'VAT No.', 'text', '' );
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
							'ven_name' => $valid ['vendor'],
							'ven_remarks' => $valid ['remarks'] ,
							'ven_type' => 1,
					        'ven_vat_no' => $valid ['vatNo'],
					);
					$venId = $vendor->add ( $data );
					
					if ($venId) {
						$data = array (
								'con_ref_type' => CONT_TYPE_VENDR,
								'con_ref_id' => $venId,
								'con_type' => '5', // vendor type contacts
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
						
						$feedback = 'Vendor details added successfully';
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
		
		$this->view->form  = $form;
	}
	public function editAction() {
		$this->view->response('ajax');
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$formRender = true;
		$form = new form ();
		
		$vendor = new vendor ();
		$contact = new contacts ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$venId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $venId)
			die ( 'tampered' );
		
		$form->addElement ( 'vendor', 'Vendor/Company', 'text', 'required' );
		$form->addElement ( 'name', 'Name(C/o) ', 'text', '' );
		$form->addElement ( 'vatNo', 'VAT No.', 'text', '' );
		$form->addElement ( 'house', 'Address', 'text', '' );
		$form->addElement ( 'street1', 'Street Address 1', 'text', '' );
		$form->addElement ( 'street2', 'Street Address 2', 'text', '' );
		$form->addElement ( 'place', 'Place Name ', 'text', '' );
		$form->addElement ( 'locality', 'Locality', 'text', 'alpha_space' );
		$form->addElement ( 'zip', 'Zip Code', 'text', '', 'numeric' );
		$form->addElement ( 'phone', 'Phone No', 'text', '' );
		$form->addElement ( 'mobile', 'Mobile No', 'text', 'required|numeric' );
		$form->addElement ( 'remarks', 'Remarks', 'text', 'alpha_space' );
		$vendorDetails = $vendor->getVendorDet ( array (
				'ven_id' => $venId 
		) );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
					$data = array (
							'ven_name' => $valid ['vendor'],
							'ven_remarks' => $valid ['remarks'],
					        'ven_vat_no' => $valid ['vatNo'],
					);
					$venModified = $vendor->modify ( $data, $venId );
					
					if ($venModified) {
						
						if ($vendorDetails ['con_id']) {
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
							$modifyContact = $contact->modify ( $data, $vendorDetails ['con_id'] );
						} else {
							$data = array (
									'con_ref_type' => CONT_TYPE_VENDR,
									'con_ref_id' => $venId,
									'con_type' => '5', // vendor type contacts
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
						}
						
						$feedback = 'Vendor details added successfully';
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
			$form->vendor->setValue ( $vendorDetails ['ven_name'] );
			$form->vatNo->setValue ( $vendorDetails ['ven_vat_no'] );
			$form->name->setValue ( $vendorDetails ['con_name'] );
			$form->house->setValue ( $vendorDetails ['con_house'] );
			$form->street1->setValue ( $vendorDetails ['con_street1'] );
			$form->place->setValue ( $vendorDetails ['con_place'] );
			$form->locality->setValue ( $vendorDetails ['con_locality'] );
			$form->zip->setValue ( $vendorDetails ['con_zip_code'] );
			$form->phone->setValue ( $vendorDetails ['con_phone'] );
			$form->mobile->setValue ( $vendorDetails ['con_mobile'] );
			$form->remarks->setValue ( $vendorDetails ['ven_remarks'] );
		}
		$this->view->form  = $form;
	}
	public function listAction() {
		
		require_once __DIR__ . '/../admin/!model/contacts.php';
		require_once __DIR__ . '/../admin/!model/vendor.php';

		$form = new form ();
		
		$form->addElement ( 'f_code', 'Code', 'text', 'alpha_space' );
		$form->addElement ( 'f_vendor', 'Vendor/Company', 'text', 'alpha_space' );
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
						'f_vendor' => @$valid ['f_vendor'],
						'f_name' => @$valid ['f_name'],
						'f_house' => @$valid ['f_house'] 
				);
			}
			$filter_class = 'btn-info';
		}
		
		$vendorObj = new vendor ();
		
		// s($where);
		
		$vendorList = $vendorObj->getVendorPaginate ( @$where );
		
		// s($vendorList);
		
		$offset = $vendorObj->_voffset;
		$this->view->vendorList = $vendorList;
		$this->view->vendorObj = $vendorObj;
		$this->view->form  = $form;
		$this->view->filter_class = $filter_class;
	}
	public function viewAction() {
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$formRender = true;
		
		$vendor = new vendor ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$decVenId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decVenId)
			die ( 'tampered' );
		
		$vendorDetail = $vendor->getVendorDet ( array (
				'ven_id' => $decVenId 
		) );
		$this->view->vendorDetail = $vendorDetail;
		$this->view->decVenId = $decVenId;
		
	}
}
