<?php
class buildingController extends mvc {
	public function addAction() {
		$this->view->response('ajax');
		include __DIR__ . '/../admin/!model/building.php';
		$formRender = true;
		$form = new form ();
		$building = new building ();
		
		$form->addElement ( 'bld_name', 'Building Name', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_no', 'No. ', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_area', 'Area', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_block_no', 'Block', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_plot_no', 'Plot', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_way', 'Way', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_street', 'Street', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_block', 'Block', 'text', 'required|alpha_space' );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
				    $data = array (
				        'bld_name' => $valid ['bld_name'],
				        'bld_no' => $valid ['bld_no'],
				        'bld_area' => $valid ['bld_area'],
				        'bld_block_no' => $valid ['bld_block_no'],
				        'bld_plot_no' => $valid ['bld_plot_no'] ,
				        'bld_way' => $valid['bld_way'],
				        'bld_street' => $valid['bld_street'],
				        'bld_block' => $valid['bld_block'],
				    );
					$buildingId = $building->add ( $data );
					
					if ($buildingId) {
						
						$feedback = 'Building details added successfully';
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
		include __DIR__ . '/../admin/!model/building.php';
		$building = new building ();
		$form = new form ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$buildingId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $buildingId)
			die ( 'tampered' );
		
		$form->addElement ( 'bld_name', 'Building Name', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_no', 'No. ', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_area', 'Area', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_block_no', 'Block', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_plot_no', 'Plot', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_way', 'Way', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_street', 'Street', 'text', 'required|alpha_space' );
		$form->addElement ( 'bld_block', 'Block', 'text', 'required|alpha_space' );
		
		$buildingDetails = $building->getBuildingDetById ( $buildingId );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
				    				
					$data = array (
							'bld_name' => $valid ['bld_name'],
							'bld_no' => $valid ['bld_no'],
							'bld_area' => $valid ['bld_area'],
							'bld_block_no' => $valid ['bld_block_no'],
							'bld_plot_no' => $valid ['bld_plot_no'] ,
					        'bld_way' => $valid['bld_way'],
    					    'bld_street' => $valid['bld_street'],
    					    'bld_block' => $valid['bld_block'],
					);
					
					$modifyBuilding = $building->modify ( $data, $buildingDetails ['bld_id'] );
					
					$feedback = 'Building details Updated successfully';
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
		else { 		    
		    $form->bld_name->setValue ( $buildingDetails ['bld_name'] );
		    $form->bld_no->setValue ( $buildingDetails ['bld_no'] );
		    $form->bld_area->setValue ( $buildingDetails ['bld_area'] );
		    $form->bld_block_no->setValue ( $buildingDetails ['bld_block_no'] );
		    $form->bld_plot_no->setValue ( $buildingDetails ['bld_plot_no'] );
		    $form->bld_way->setValue($buildingDetails['bld_way']);
		    $form->bld_street->setValue($buildingDetails['bld_street']);
		    $form->bld_block->setValue($buildingDetails['bld_block']);
		}
		
		$this->view->form = $form;
	}
	public function deleteAction() {
		$this->view->response('ajax');
		include __DIR__ . '/../admin/!model/customer.php';
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
				$delete = $customer->deleteBuilding ( $decCustId );
				if ($delete) {
					
					$_SESSION ['feedback'] = 'The customer has been deleted successfully from the system ';
					$success = json_encode ( $success );
					die ( $success );
				}
			}
		}
	}
	public function listAction() {
		
	    include __DIR__ . '/../admin/!model/building.php';

		
		$form = new form ();
		
		
		$form->addElement ( 'f_bld_name', 'Name', 'text', 'alpha_space' );
		$form->addElement ( 'f_bld_no', 'No.', 'text', 'alpha_space' );
		
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
						'f_bld_name' => @$valid ['f_bld_name'],
						'f_bld_no' => @$valid ['f_bld_no'],
				);
			}
			$filter_class = 'btn-info';
		}
		
		$buildingObj = new building();
		
		$this->view->BuildingList= $buildingObj->getBuildingPaginate( @$where );;
		$this->view->form = $form;
		$this->view->buildingObj= $buildingObj;
	}
	public function viewAction() {
		$this->view->response ( 'ajax' );
		include __DIR__ . '/../admin/!model/building.php';
		
		$buildingObj = new building ();
		
		$decBuildingId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decBuildingId)
			die ( 'tampered' );
		
		$buildingDetail = $buildingObj->getBuildingDetById ( $decBuildingId );
		
		$this->view->buildingDetail = $buildingDetail;
		
	}

	
}
