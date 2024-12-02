<?php
class reportsController extends mvc {
	public function itemlistAction() {
		require_once __DIR__ . '/../manage/!model/item.php';
		
		$ref = filter_input ( INPUT_GET, 'ref', FILTER_UNSAFE_RAW );
		
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
		$itemList = $itemObj->getItemsReport ();
		
		$this->view->itemsList = $itemList;
		
		$this->view->form = $form;
		
		$offset = $itemObj->_voffset;
	}
	public function billbycustomerAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../admin/!model/customer.php';
		
		$custObj = new customer ();
		
		$billList = $custObj->getBillByCustomer ( @$where );
		
		$this->view->billList = $billList;
	}
	public function billcustoutstandingAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/bill.php';
		
		$decCustId = $this->view->decode ( $this->view->param ['ref'] );
		
		if (! $decCustId)
			die ( 'tampered' );
		
		$billObj = new bill ();
		$billList = $billObj->getBillPendingReport ( array (
				'cust_id' => $decCustId 
		) );
		
		$this->view->billList = $billList;
	}
	public function billlistingAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/bill.php';
		require_once __DIR__ . '/../admin/!model/customer.php';
		
		$form = new form ();
		
		require_once __DIR__ . '/../accounts/!model/company.php';
		$compModelObj = new company ();
		$compList = $compModelObj->getCompanyPair ();
		$form->addElement ( 'f_company', 'Company', 'select', '', array (
				'options' => $compList 
		) );
		
		$customerObj = new customer ();
		$customerList = $customerObj->getCustomerPair ();
		
		$form->addElement ( 'f_customer', 'Customer', 'select', '', array (
				'options' => $customerList 
		) );
		$form->addElement ( 'f_refno', 'Reference No', 'text', '' );
		$form->addElement ( 'f_billno', 'Bill No', 'text', '' );
		$form->addElement ( 'f_paymode', 'Payment Type', 'select', '', array (
				'options' => array (
						1 => "Cash",
						2 => "Credit",
						3 => "Paid",
						4 => "Pending" 
				) 
		) );
		$form->addElement('f_period', 'Month/ Period', 'radio', '', array(
				'options' => array(
						1 => "Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
						2 => "Period"
				)
		));
		
		$form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
				'' => 'readonly'
		));
		$form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		$form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		
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
						'f_company' => @$valid ['f_company'],
						'f_customer' => @$valid ['f_customer'],
						'f_refno' => @$valid ['f_refno'],
						'f_paymode' => @$valid ['f_paymode'],
						'f_monthpick' => @$valid ['f_monthpick'],
						'f_billno' => @$valid ['f_billno'],
						'f_item' => @$valid ['f_item'],
						'f_location' => @$valid ['f_location'] 
				);
				
				if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
					$billdtfrom = date_format($billdtfrom, DFS_DB);
					$where['f_dtfrom'] = $billdtfrom;
					$title.='Date starting from : '. $valid['f_dtfrom'];
				}
				if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
					$billdtto = date_format($billdtto, DFS_DB);
					$where['f_dtto'] = $billdtto;
					$title.=' Date upto : '. $valid['f_dtto'];
				}
			}
			$filter_class = 'btn-info';
		}
		
		$billObj = new bill ();
		
		$billList = $billObj->getAllBillReport ( @$where );
		
		$this->view->title = ' List of Invoices ' . $title;
		
		$this->view->filter_class = $filter_class;
		$this->view->billList = $billList;
		$this->view->form = $form;
		$this->view->refno = $refno;
	}
	public function billoutstandingAction() {
		$this->view->response ( 'window' );
		
		require_once __DIR__ . '/../accounts/!model/bill.php';
		$billObj = new bill ();
		
		$billList = $billObj->getBillPendingReport ( array () );
		
		$this->view->billList = $billList;
	}
	public function paymentcollectionAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/collection.php';
		
		$decCustId = $this->view->decode ( $this->view->param ['ref'] );
		
		$form = new form ();
		
		$form->addElement('f_period', 'Month/ Period', 'radio', '', array(
				'options' => array(
						1 => "Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
						2 => "Period"
				)
		));
		
		$form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
				'' => 'readonly'
		));
		$form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		$form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		
		require_once __DIR__ . '/../admin/!model/customer.php';
		$customerObj = new customer ();
		$customerList = $customerObj->getCustomerPair ();
		$form->addElement ( 'f_selCustomer', 'Customer', 'select', '', array (
				'options' => $customerList 
		) );
		
		$collObj = new collection ();
		$date = new DateTime ();
		

		
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			
			$form->reset ();
			unset ( $_GET );
			$title = "";
			$decCustId='';
			
		} elseif ($_GET ['f_monthpick'] <> "") {
		
			$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
		}
		
		$filter_class = 'btn-primary';
		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				$where = array (
						'f_selCustomer' => @$valid ['f_selCustomer'],
						'f_monthpick' => @$valid ['f_monthpick'] 
				);
				
				if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
					$billdtfrom = date_format($billdtfrom, DFS_DB);
					$where['f_dtfrom'] = $billdtfrom;
					$title.='Date starting from : '. $valid['f_dtfrom'];
				}
				if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
					$billdtto = date_format($billdtto, DFS_DB);
					$where['f_dtto'] = $billdtto;
					$title.=' Date upto : '. $valid['f_dtto'];
				}
				
			}
			$filter_class = 'btn-info';
			if ($_GET ['f_monthpick'] != '') {
				$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
				$month = date_format ( $date, 'F-Y' );
				$title = 'for the month - ' . $month;
			}
		} else {
			

		}
		
		if ($decCustId) {
			$where = array (
					'f_selCustomer' => $decCustId
			);
			$form->f_selCustomer->setValue ( $decCustId );
			$filter_class = 'btn-info';
		}
		
		$collList = $collObj->getPaymentcollection ( @$where );
		
		$title = ' Payment Collection Report ' . $title;
		
		$this->view->filter_class = $filter_class;
		$this->view->collList = $collList;
		$this->view->form = $form;
		$this->view->title = $title;
	}
	public function dailyreportAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/bill.php';
		$billObj = new bill ();		
		
		$billList = $billObj->getAllBillReport ( array (
				'bill_date' => $this->view->param ['ref'],
		) );
		
		$billItemList = $billObj->getBillItemReport( array (
				'bill_date' => $this->view->param ['ref']
		) );
		
		require_once __DIR__ . '/../accounts/!model/collection.php';
		$collObj = new collection ();
		$collList = $collObj->getPaymentcollection ( array (
				'coll_paydate' => $this->view->param ['ref'] 
		) );
		
		
		require_once __DIR__ . '/../admin/!model/customer.php';
		$custObj = new customer ();
		$customerDue = $custObj->getBillByCustomer( array (
				'coll_paydate' => $this->view->param ['ref']
		));
		$this->view->customerDue= $customerDue;
		
		$reportDate = DateTime::createFromFormat(DFS_DB, $this->view->param ['ref']);
		$this->view->reportDate= date_format($reportDate, DF_DD);
		
		
		$this->view->billItemList= $billItemList;
		$this->view->billList = $billList;
		$this->view->collList = $collList;
	}
	
	public function monthlyreportAction() {
		
		$this->view->response ( 'window' );
		
		$form = new form ();	
		$form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
				'' => 'readonly'
		));
		
		require_once __DIR__ . '/../admin/!model/customer.php';
		$customerObj = new customer ();
		$customerList = $customerObj->getCustomerPair ();
		
		$form->addElement ( 'f_customer', 'Customer', 'select', '', array (
				'options' => $customerList
		) );
		
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			
			$form->reset ();
			unset ( $_GET );
			$title = "";
			
		}
		

		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				
				
				if ($_GET ['f_monthpick'] != '') {
					$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
					$month = date_format ( $date, 'F-Y' );
					$this->view->title = 'For The Month - ' . $month;
				}
				
				require_once __DIR__ . '/../accounts/!model/bill.php';
				$billObj = new bill ();
				
				$billList = $billObj->getAllBillReport ( array (
						'f_customer' => $valid['f_customer'],
						'f_monthpick' => $valid['f_monthpick'],
				) );
				
				$billItemList = $billObj->getBillItemReport( array (
						'f_customer' => $valid['f_customer'],
						'f_monthpick' => $valid['f_monthpick'],
				) );
				
				require_once __DIR__ . '/../accounts/!model/collection.php';
				$collObj = new collection ();
				$collList = $collObj->getPaymentcollection ( array (
						'f_customer' => $valid['f_customer'],
						'f_monthpick' => $valid['f_monthpick'],
				) );
				
				
			}
		}
		
		
		

		
		$reportDate = DateTime::createFromFormat(DFS_DB, $this->view->param ['ref']);
		$this->view->reportDate= date_format($reportDate, DF_DD);
		
		
		$this->view->billItemList= $billItemList;
		$this->view->billList = $billList;
		$this->view->collList = $collList;
		$this->view->form = $form;
	}
	
	public function purchasebyvendorAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../admin/!model/vendor.php';
		
		$venObj = new vendor();
		
		$purchaseList = $venObj->getPurchaseByVendor( @$where );
		
		$this->view->purchaseList= $purchaseList;
	}
	
	public function purchaselistingAction() {/*
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/purchase.php';
		require_once __DIR__ . '/../admin/!model/vendor.php';
		
		$form = new form ();
		
		require_once __DIR__ . '/../accounts/!model/company.php';
		$compModelObj = new company ();
		$compList = $compModelObj->getCompanyPair ();
		$form->addElement ( 'f_company', 'Company', 'select', '', array (
				'options' => $compList
		) );
		
		$venObj = new vendor();
		$vendorList = $venObj->getVendorPair(array('ven_type'=>1));
		
		$form->addElement ( 'f_customer', 'Vendor', 'select', '', array (
				'options' => $vendorList
		) );
		$form->addElement ( 'f_refno', 'Reference No', 'text', '' );
		$form->addElement ( 'f_purchaseno', 'GRN No', 'text', '' );
		$form->addElement ( 'f_paymode', 'Payment Type', 'select', '', array (
				'options' => array (
						1 => "Cash",
						2 => "Credit",
						3 => "Paid",
						4 => "Pending"
				)
		) );
		$form->addElement('f_period', 'Month/ Period', 'radio', '', array(
				'options' => array(
						1 => "Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
						2 => "Period"
				)
		));
		
		$form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
				'' => 'readonly'
		));
		$form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		$form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		
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
						'f_company' => @$valid ['f_company'],
						'f_customer' => @$valid ['f_customer'],
						'f_refno' => @$valid ['f_refno'],
						'f_paymode' => @$valid ['f_paymode'],
						'f_monthpick' => @$valid ['f_monthpick'],
						'f_purchaseno' => @$valid ['f_purchaseno'],
						'f_item' => @$valid ['f_item'],
						'f_location' => @$valid ['f_location']
				);
				
				if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
					$billdtfrom = date_format($billdtfrom, DFS_DB);
					$where['f_dtfrom'] = $billdtfrom;
					$title.='Date starting from : '. $valid['f_dtfrom'];
				}
				if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
					$billdtto = date_format($billdtto, DFS_DB);
					$where['f_dtto'] = $billdtto;
					$title.=' Date upto : '. $valid['f_dtto'];
				}
			}
			$filter_class = 'btn-info';
		}
		
		$purchaseObj = new purchase();
		
		$purchaseList = $purchaseObj->getAllPurchaseReport( @$where );
		
		$this->view->title = ' List of Purchases ' . $title;
		
		$this->view->filter_class = $filter_class;
		$this->view->purchaseList= $purchaseList;
		$this->view->form = $form;
		$this->view->refno = $refno;
		*/
	}
	
	public function vendorpaymentsAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/payment.php';
		
		$decVenId = $this->view->decode ( $this->view->param ['ref'] );
		
		$form = new form ();
		
		$form->addElement('f_period', 'Month/ Period', 'radio', '', array(
				'options' => array(
						1 => "Month&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
						2 => "Period"
				)
		));
		
		$form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
				'' => 'readonly'
		));
		$form->addElement('f_dtfrom', 'Bill Date From', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		$form->addElement('f_dtto', 'Bill Date To', 'text', 'date', '', array(
				'class' => 'date_picker'
		));
		
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$venObj = new vendor();
		$venList = $venObj->getVendorPair(array('ven_type'=>1));
		$form->addElement ( 'f_selCustomer', 'Vendor', 'select', '', array (
				'options' => $venList
		) );
		
		$paymentObj = new payment();
		$date = new DateTime ();
		
		
		
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			
			$form->reset ();
			unset ( $_GET );
			$title = "";
			$decCustId='';
			
		} elseif ($_GET ['f_monthpick'] <> "") {
			
			$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
		}
		
		$filter_class = 'btn-primary';
		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				$where = array (
						'f_selCustomer' => @$valid ['f_selCustomer'],
						'f_monthpick' => @$valid ['f_monthpick']
				);
				
				if (! empty($valid['f_dtfrom']) && $valid['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat(DF_DD, $valid['f_dtfrom']);
					$billdtfrom = date_format($billdtfrom, DFS_DB);
					$where['f_dtfrom'] = $billdtfrom;
					$title.='Date starting from : '. $valid['f_dtfrom'];
				}
				if (! empty($valid['f_dtto']) && $valid['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat(DF_DD, $valid['f_dtto']);
					$billdtto = date_format($billdtto, DFS_DB);
					$where['f_dtto'] = $billdtto;
					$title.=' Date upto : '. $valid['f_dtto'];
				}
				
			}
			$filter_class = 'btn-info';
			if ($_GET ['f_monthpick'] != '') {
				$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
				$month = date_format ( $date, 'F-Y' );
				$title = 'for the month - ' . $month;
			}
		} else {
			
			
		}
		
		if ($decVenId) {
			$where = array (
					'f_selCustomer' => $decVenId
			);
			$form->f_selCustomer->setValue ( $decVenId);
			$filter_class = 'btn-info';
		}
		
		$paymentList = $paymentObj->getPayments( @$where );
		
		$title = ' Payment Report ' . $title;
		
		$this->view->filter_class = $filter_class;
		$this->view->paymentList= $paymentList;
		$this->view->form = $form;
		$this->view->title = $title;
	}
	
}