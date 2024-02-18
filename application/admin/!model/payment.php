<?php
class payment extends db_table {
	protected $_table = "mis_payment";
	protected $_pkey = "pay_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	
	public function getPaymentPaginate($cond){
		
		$this->paginate ( "select $this->_table.* ,
				to_char(pay_paydate,'DD/MM/YYYY') as pay_date,
				vendor.ven_name ,
				bill_det
				", "from $this->_table 
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.pay_vendor and vendor.deleted = 0
				left join (
					SELECT pdet_pay_id,
					       array_to_string(array_agg(exp_file_no || ' - ' || pdet_amt_paid), ',<br>') AS bill_det
					FROM mis_payment_det
					LEFT JOIN mis_expense AS exp ON exp.exp_id = pdet_exp_id
					AND exp.deleted = 0
					WHERE mis_payment_det.deleted = 0
					GROUP BY pdet_pay_id
				) as pay_details on pay_details.pdet_pay_id = $this->_table.pay_id 
				" );
		
		if (! empty ( $cond ['f_selVendor'] ))
			$this->_where [] = "pay_vendor= :f_selVendor";
			
		$this->_order [] = 'pay_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getPaymentExpDet($cond) {
		$this->query ( "select $this->_table.* ,
				paydet.* 
				from $this->_table 
				left join mis_payment_det as paydet on paydet.pdet_pay_id = $this->_table.pay_id and paydet.deleted = 0		
				left join mis_expense as expens on expens.exp_id = paydet.pdet_exp_id and expens.deleted = 0			
				" );
		if (! empty ( $cond ['pay_id'] ))
			$this->_where [] = "pay_id= :pay_id";
		
		return parent::fetchAll( $cond );
	}
	
	public function getPaymentDetById($id){
		return parent::getById ($id);
	}
	
	public function getPaymentDetByPaymentId($cond) {
		$this->query ( "select * 
				from $this->_table
				LEFT JOIN mis_cash_book as cbook on cbook.cb_exp_id = $this->_table.pay_id and cbook.cb_type = ".CASH_BOOK_PER."
				-- and cbook.cb_type_ref = ".USER_ID."
				and cb_exp_type = 2	and cbook.deleted = 0
				" );
		if (! empty ( $cond ['pay_id'] ))
			$this->_where [] = "pay_id= :pay_id";
			
			return parent::fetchRow( $cond );
	}
	

	public function getPaymentById($id){
		return parent::getById ($id);
	}
	
	public function getPaymentDetByPayId($cond){
		
		$this->query ( "select $this->_table.* ,
				files.file_id,
				vendor.ven_name, 
				file_actual_name||'.'||file_exten as file_name
				from $this->_table
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.pay_vendor and vendor.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.pay_id and files.file_type = " . DOC_TYPE_PAY . " and files.deleted = 0
					" );
		$this->_where [] = "pay_id= :pay_id";
		
		return parent::fetchRow($cond);
	}
	
	public function getPaymentAdviceByPayId($cond){
		
		$this->query ( "select $this->_table.* ,
				files.file_id,
				file_actual_name||'.'||file_exten as file_name
				from $this->_table
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.pay_id and files.file_type = " . DOC_TYPE_PAY . " and files.deleted = 0
					" );
		$this->_where [] = "pay_id= :pay_id";
		
		return parent::fetchRow($cond);
	}
	
	
	public function getPaymentReport($cond=array()){
		$cond = array_filter($cond);
				
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM pay_paydate) = '$monthYear[0]' AND EXTRACT(year FROM pay_paydate) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		if (! empty ( $cond ['f_selVendor'] )){
			$where [] = " pay_vendor= :f_selVendor ";
			//unset ( $cond ['f_selVendor'] );
		}
			
			$where [] = " mis_payment.pay_app_status = 1 ";
			
			$where [] = ' mis_payment.deleted = 0 ';
			$where = ' WHERE ' . implode ( ' AND ', $where );
			
			$this->query( "select $this->_table.* ,
					to_char(pay_paydate,'DD/MM/YYYY') as pay_date,
					vendor.ven_name,
					bill_det 
					from $this->_table
					left join mis_vendor as vendor on vendor.ven_id = $this->_table.pay_vendor and vendor.deleted = 0
					left join (
						SELECT pdet_pay_id,
						       array_to_string(array_agg(exp_file_no || ' - ' || pdet_amt_paid), ',<br>') AS bill_det
						FROM mis_payment_det
						LEFT JOIN mis_expense AS exp ON exp.exp_id = pdet_exp_id
						AND exp.deleted = 0
						WHERE mis_payment_det.deleted = 0
						GROUP BY pdet_pay_id
					) as pay_details on pay_details.pdet_pay_id = $this->_table.pay_id 
					" .$where . " order by pay_paydate DESC");
			
			
			return parent::fetchQuery ( $cond );
	}
	
	public function deletePayment($id) {
		return parent::delete ( $id );
	}
	
}


