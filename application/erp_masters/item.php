<?php
class itemController extends mvc {
	public function addAction() {
		$this->view->response('ajax');
		include __DIR__ . '/../admin/!model/item.php';
		$formRender = true;
		$form = new form ();
		$item = new item ();
		
		$form->addElement ( 'code', 'Item Code', 'text', 'required|alpha_space' );
		$form->addElement ( 'name', 'Item Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'unit', 'Unit Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'remarks', 'Remarks', 'text', 'alpha_space' );
		$form->addElement ( 'price', 'Price', 'float', 'required|numeric' );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
					$data = array (
							'item_code' => $valid ['code'],
							'item_name' => $valid ['name'],
							'item_unit' => $valid ['unit'],
							'item_remarks' => $valid ['remarks'],
							'item_price' => $valid ['price'] 
					);
					$itemId = $item->add ( $data );
					
					if ($itemId) {
						
						$feedback = 'Item details added successfully';
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
		include __DIR__ . '/../admin/!model/item.php';
		$formRender = true;
		$item = new item ();
		$form = new form ();
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
		$itemId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $itemId)
			die ( 'tampered' );
		
		$form->addElement ( 'code', 'Item Code', 'text', 'required|alpha_space' );
		$form->addElement ( 'name', 'Item Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'unit', 'Unit Name ', 'text', 'required|alpha_space' );
		$form->addElement ( 'remarks', 'Remarks', 'text', 'alpha_space' );
		$form->addElement ( 'price', 'Price', 'float', 'required|numeric' );
		$form->addElement('type', 'Type', 'select', 'required', array(
		    'options' => array(
		        1 => 'Invoice Item',
		        2 => 'Service Item'
		    )
		));
		
		$itemDetails = $item->getItemDetById ( $itemId );
		
		if ($_POST) {
			if (! isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest') {
				die ( '---' ); // exit script outputting json data
			} else {
				
				$valid = $form->vaidate ( $_POST, $_FILES );
				$valid = $valid [0];
				if ($valid == true) {
					
					$data = array (
							'item_code' => $valid ['code'],
							'item_name' => $valid ['name'],
							'item_unit' => $valid ['unit'],
							'item_remarks' => $valid ['remarks'],
							'item_price' => $valid ['price'] ,
					        'item_type' => $valid['type']
					);
					
					$modifyItem = $item->modify ( $data, $itemDetails ['item_id'] );
					
					$feedback = 'Item details Updated successfully';
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
			$form->code->setValue ( $itemDetails ['item_code'] );
			$form->name->setValue ( $itemDetails ['item_name'] );
			$form->unit->setValue ( $itemDetails ['item_unit'] );
			$form->remarks->setValue ( $itemDetails ['item_remarks'] );
			$form->price->setValue ( $itemDetails ['item_price'] );
			$form->type->setValue($itemDetails['item_type']);
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
				$delete = $customer->deleteItem ( $decCustId );
				if ($delete) {
					
					$_SESSION ['feedback'] = 'The customer has been deleted successfully from the system ';
					$success = json_encode ( $success );
					die ( $success );
				}
			}
		}
	}
	public function listAction() {
		
	    include __DIR__ . '/../admin/!model/item.php';

		
		$form = new form ();
		
		
		$form->addElement ( 'f_code', 'Code', 'text', 'alpha_space' );
		$form->addElement ( 'f_name', 'Name', 'text', 'alpha_space' );
		$form->addElement ( 'f_remarks', 'Description', 'text', 'alpha_space' );
		$form->addElement ( 'f_price', 'Price', 'text', 'alpha_space' );
		
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
						'f_name' => @$valid ['f_name'],
						'f_remarks' => @$valid ['f_remarks'],
						'f_price' => @$valid ['f_price'] 
				);
			}
			$filter_class = 'btn-info';
		}
		
		$itemObj = new item ();
		
		// s($where);
		
		$ItemsList = $itemObj->getItemsPaginate ( @$where );
		
		$this->view->ItemsList= $ItemsList;
		$this->view->form = $form;
		$this->view->itemObj= $itemObj;
	}
	public function viewAction() {
		$this->view->response ( 'ajax' );
		include __DIR__ . '/../admin/!model/item.php';
		
		$itemObj = new item ();
		
		$decItemId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decItemId)
			die ( 'tampered' );
		
		$itemDetail = $itemObj->getItemDetById ( $decItemId );
		
		$this->view->itemDetail = $itemDetail;
		
	}
	
	public function srvaddAction()
	{
	    $this->view->response ( 'ajax' );
	    include __DIR__ . '/../admin/!model/item.php';
	    $form = new form();
	    $item = new item();
	    
	    $form->addElement('code', 'Item Code', 'text', 'required|alpha_space');
	    $form->addElement('name', 'Item Name ', 'text', 'required|alpha_space');
	    $form->addElement('remarks', 'Remarks', 'text', 'alpha_space');
	    
	    if ($_POST) {
	        if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	            die('---'); // exit script outputting json data
	        } else {
	            
	            $valid = $form->vaidate($_POST, $_FILES);
	            $valid = $valid[0];
	            if ($valid == true) {
	                
	                $data = array(
	                    'item_code' => $valid['code'],
	                    'item_name' => $valid['name'],
	                    'item_remarks' => $valid['remarks'],
	                    'item_type' => 2
	                );
	                $itemId = $item->add($data);
	                
	                if ($itemId) {
	                    
	                    $feedback = 'Item details added successfully';
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
	
}
