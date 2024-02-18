<?php
class rptexpenseController extends mvc {
	public function expensedetailAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/expense.php';
		
		$form = new form ();
		$form->addElement ( 'f_refno', 'Ref No', 'text', '' );
		$form->addElement ( 'f_particulers', 'Particulers', 'text', '' );
		
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$vendorObj = new vendor ();
		$venderList = $vendorObj->getVendorPairFilter ( array (
				'ven_type' => 2 
		) );
		$form->addElement ( 'f_selVendor', 'Vendor', 'select', '', array (
				'options' => $venderList 
		) );
		
		require_once __DIR__ . '/../accounts/!model/category.php';
		$catModelObj = new category ();
		$pCatList = $catModelObj->getCategoryPair ( array (
				'cat_type' => 1 
		) );
		/*
		 * $sCatList = $catModelObj->getCategoryPair(array(
		 * 'cat_type' => 2
		 * ));
		 * $cCatList = $catModelObj->getCategoryPair(array(
		 * 'cat_type' => 3
		 * ));
		 */
		
		$form->addElement ( 'f_pCatSelect', 'Parent Cat', 'select', '', array (
				'options' => $pCatList 
		) );
		$form->addElement ( 'f_sCatSelect', 'Sub Cat', 'select', '', array (
				'options' => $sCatList 
		) );
		$form->addElement ( 'f_cCatSelect', 'Category', 'select', '', array (
				'options' => $cCatList 
		) );
		$form->addElement ( 'f_mainhead', 'Head', 'select', '', array (
				'options' => array (
						1 => "PURCHASE",
						2 => "SALES",
						3 => "LABOUR",
						4 => "PROFIT ACCOUNT",
						5 => "RUNNING EXPENSE",
						6 => "ASSETS",
						7 => "GOVT FEES AND TAXES" 
				) 
		) );
		$form->addElement ( 'f_amount', 'Amount', 'float', 'numeric', '', array (
				'class' => 'fig' 
		) );
		
		$form->addElement ( 'f_period', 'Month/Period', 'radio', '', array (
				'options' => array (
						1 => "Month",
						2 => "Period" 
				) 
		) );
		$form->addElement ( 'f_monthpick', 'Select Month ', 'text', '', '', array (
				'' => 'readonly' 
		) );
		$form->addElement ( 'f_dtfrom', 'Bill Date From', 'text', 'date', '', array (
				'class' => 'date_picker' 
		) );
		$form->addElement ( 'f_dtto', 'Bill Date To', 'text', 'date', '', array (
				'class' => 'date_picker' 
		) );
		
		$filter_class = 'btn-primary';
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			$form->reset ();
			unset ( $_GET );
		} else if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				
				$where = array (
						'f_refno' => @$valid ['f_refno'],
						'f_particulers' => @$valid ['f_particulers'],
						'f_selVendor' => @$valid ['f_selVendor'],
						'f_company' => @$valid ['f_company'],
						'f_mainhead' => @$valid ['f_mainhead'],
						'f_pCatSelect' => @$valid ['f_pCatSelect'],
						'f_sCatSelect' => @$valid ['f_sCatSelect'],
						'f_cCatSelect' => @$valid ['f_cCatSelect'],
						'f_mode' => @$valid ['f_mode'],
						'f_amount' => @$valid ['f_amount'],
						'f_building' => @$valid ['f_building'] 
				);
				
				if (! empty ( $valid ['f_dtfrom'] ) && $valid ['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat ( DF_DD, $valid ['f_dtfrom'] );
					$billdtfrom = date_format ( $billdtfrom, DFS_DB );
					$where ['f_dtfrom'] = $billdtfrom;
				}
				if (! empty ( $valid ['f_dtto'] ) && $valid ['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat ( DF_DD, $valid ['f_dtto'] );
					$billdtto = date_format ( $billdtto, DFS_DB );
					$where ['f_dtto'] = $billdtto;
				}
				
				if (! empty ( $valid ['f_monthpick'] ) && $valid ['f_period'] == 1) {
					$where ['f_monthpick'] = $valid ['f_monthpick'];
				}
				
				if (! empty ( $valid ['f_employee'] )) {
					$where ['f_mrefs'] = $valid ['f_employee'];
				} elseif (! empty ( $valid ['f_property'] )) {
					$where ['f_mrefs'] = $valid ['f_property'];
				} elseif (! empty ( $valid ['f_vehicle'] )) {
					$where ['f_mrefs'] = $valid ['f_vehicle'];
				}
			}
			$filter_class = 'btn-info';
		} else if (! isset ( $_GET )) {
			
			$filter_class = 'btn btn-info';
		}
		
		$expObj = new expense ();
		
		$this->view->expenseList = $expObj->geExpenseReport ( @$where );
		$this->view->expenseSummery = $expObj->geExpenseReportSummary ( @$where );
		
		$this->view->filter_class = $filter_class;
		$this->view->form = $form;
	}
	public function expvensummaryAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../admin/!model/vendor.php';
		$vendorObj = new vendor ();
		$this->view->reportlist = $vendorObj->getBillByVendor ( @$where );
	}
	public function expensecategoryAction() {
		$this->view->response ( 'window' );
		require_once __DIR__ . '/../accounts/!model/expense.php';
		$form = new form ();
		
		require_once __DIR__ . '/../accounts/!model/category.php';
		$catModelObj = new category ();
		$pCatList = $catModelObj->getCategoryPair ( array (
				'cat_type' => 1 
		) );
		/*
		 * $sCatList = $catModelObj->getCategoryPair(array(
		 * 'cat_type' => 2
		 * ));
		 * $cCatList = $catModelObj->getCategoryPair(array(
		 * 'cat_type' => 3
		 * ));
		 */
		
		$form->addElement ( 'f_pCatSelect', 'Parent Cat', 'select', '', array (
				'options' => $pCatList 
		) );
		$form->addElement ( 'f_sCatSelect', 'Sub Cat', 'select', '', array (
				'options' => $sCatList 
		) );
		$form->addElement ( 'f_cCatSelect', 'Category', 'select', '', array (
				'options' => $cCatList 
		) );
		$form->addElement ( 'f_mainhead', 'Head', 'select', '', array (
				'options' => array (
						1 => "PURCHASE",
						2 => "SALES",
						3 => "LABOUR",
						4 => "PROFIT ACCOUNT",
						5 => "RUNNING EXPENSE",
						6 => "ASSETS",
						7 => "GOVT FEES AND TAXES" 
				) 
		) );
		
		$form->addElement ( 'f_period', 'Month/Period', 'radio', '', array (
				'options' => array (
						1 => "Month",
						2 => "Period" 
				) 
		) );
		$form->addElement ( 'f_monthpick', 'Select Month ', 'text', '', '', array (
				'' => 'readonly' 
		) );
		$form->addElement ( 'f_dtfrom', 'Bill Date From', 'text', 'date', '', array (
				'class' => 'date_picker' 
		) );
		$form->addElement ( 'f_dtto', 'Bill Date To', 'text', 'date', '', array (
				'class' => 'date_picker' 
		) );
		
		$filter_class = 'btn-primary';
		if (isset ( $_GET ) && $_GET ['clear'] == 'All') {
			$form->reset ();
			unset ( $_GET );
		} else if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				
				$where = array (
						'f_mainhead' => @$valid ['f_mainhead'],
						'f_pCatSelect' => @$valid ['f_pCatSelect'],
						'f_sCatSelect' => @$valid ['f_sCatSelect'],
						'f_cCatSelect' => @$valid ['f_cCatSelect'],
						'f_mode' => @$valid ['f_mode'] 
				);
				
				if (! empty ( $valid ['f_dtfrom'] ) && $valid ['f_period'] == 2) {
					$billdtfrom = DateTime::createFromFormat ( DF_DD, $valid ['f_dtfrom'] );
					$billdtfrom = date_format ( $billdtfrom, DFS_DB );
					$where ['f_dtfrom'] = $billdtfrom;
				}
				if (! empty ( $valid ['f_dtto'] ) && $valid ['f_period'] == 2) {
					$billdtto = DateTime::createFromFormat ( DF_DD, $valid ['f_dtto'] );
					$billdtto = date_format ( $billdtto, DFS_DB );
					$where ['f_dtto'] = $billdtto;
				}
				
				if (! empty ( $valid ['f_monthpick'] ) && $valid ['f_period'] == 1) {
					$where ['f_monthpick'] = $valid ['f_monthpick'];
					$date = date_create_from_format ( DF_DD, '01/' . $_GET ['f_monthpick'] );
					
					$month = date_format ( $date, 'F-Y' );
					$title = ' (' . $month . ")";
				}
			}
			$filter_class = 'btn-info';
		}
		
		$expObj = new expense ();
		
		$this->view->expenseList = $expObj->geExpenseCatWiseReportSummary ( @$where );
		$this->view->filter_class = $filter_class;
		$this->view->form = $form;
		$this->view->title = $title;
	}
	public function balancesheetAction() {
		$this->view->response ( 'window' );
		
		$form = new form ();
		
		$form->addElement ( 'f_dtfrom', 'Bill Date From', 'text', 'date', '', array (
				'class' => '' 
		) );
		
		$filter_class = 'btn-primary';
		
		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				
				if (! empty ( $valid ['f_dtfrom'] )) {
					$title = $valid ['f_dtfrom'];
					$billdtfrom = DateTime::createFromFormat ( DF_DD, $valid ['f_dtfrom'] );
					$billdtfrom = date_format ( $billdtfrom, 'Y-m-d' );
					
					$where ['f_dtfrom'] = $billdtfrom;
					// $where['f_dtto'] = $billdtfrom;
				}
			}
			$filter_class = 'btn-info';
			
			// s($this->view->trialList /* $this->view->purchHistList/*,$this->view->expenseList*/ );
		} else {
			
			$where ['f_dtfrom'] = date_format ( new DateTime (), 'Y-m-d' );
			$billdtfrom = $where ['f_dtfrom'];
			$title = date_format ( new DateTime (), 'd/m/Y' );
			
			$form->f_dtfrom->setValue ( $title );
		}
		
		require_once __DIR__ . '/../accounts/!model/trialhist.php';
		$trialHistObj = new trialhist ();
		$this->view->trialList = $trialHistObj->getTrialHistoryCashBook ( $where );
		
		require_once __DIR__ . '/../accounts/!model/purchhist.php';
		$purchHistObj = new purchhist ();
		$this->view->purchHistList = $purchHistObj->getPurchaseHistoryCashBook ( $where );
		
		$where ['f_dtfrom'] = $billdtfrom;
		$where ['f_dtto'] = $billdtfrom;
		
		require_once __DIR__ . '/../accounts/!model/expense.php';
		$expObj = new expense ();
		$this->view->expenseList = $expObj->getExpHistoryCondition ( @$where );
		
		$this->view->filter_class = $filter_class;
		$this->view->form = $form;
		$this->view->title = $title;
	}
	public function inventoryAction() {
		$this->view->response ( 'window' );
		
		$form = new form ();
		
		$form->addElement ( 'f_dtfrom', 'Date', 'text', 'date', '', array (
				'class' => '' 
		) );
		
		$filter_class = 'btn-primary';
		
		if (is_array($_GET) && count ( array_filter ( $_GET ) ) > 0) {
			$valid = $form->vaidate ( $_GET );
			$valid = $valid [0];
			if ($valid == true) {
				
				if (! empty ( $valid ['f_dtfrom'] )) {
					$title = $valid ['f_dtfrom'];
					$billdtfrom = DateTime::createFromFormat ( DF_DD, $valid ['f_dtfrom'] );
					$billdtfrom = date_format ( $billdtfrom, 'Y-m-d' );
					
					$where ['f_pdtfrom'] = $billdtfrom;
				}
			}
			$filter_class = 'btn-info';
		} else {
			
			$where ['f_pdtfrom'] = date_format ( new DateTime (), 'Y-m-d' );
			$title = date_format ( new DateTime (), 'd/m/Y' );
			
			$form->f_dtfrom->setValue ( $title );
		}
		
		require_once __DIR__ . '/../accounts/!model/purchhist.php';
		$purchHistObj = new purchhist ();
		$this->view->inventoryList = $purchHistObj->getInventoryReport ( $where );
		
		//s($this->view->inventoryList);
		
		$this->view->filter_class = $filter_class;
		$this->view->form = $form;
		$this->view->title = $title;
	}
}