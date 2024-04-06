<?php
class paymentdet extends db_table {
	protected $_table = "mis_payment_det";
	protected $_pkey = "pdet_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		
		$this->_where [] = "pdet_pay_id= :pdet_pay_id";
		
		return parent::update ( $data, $cond );
	}
	
	public function getPaymentExpDet($cond) {
		$this->query ( "select $this->_table.* ,
                    to_char(exp_billdt,'DD/MM/YYYY') as exp_billdt_dd,
					case when exp_mainh = 1 then 'Employee'
					when exp_mainh = 2 then 'Property'
					when exp_mainh = 3 then 'Vehicle'
					when exp_mainh = 4 then 'Port Operation'
					end as main_head,
					expens.*,
					comp.comp_disp_name,
					files.file_id
 					from $this->_table
				left join mis_expense as expens on expens.exp_id = $this->_table.pdet_exp_id and expens.deleted = 0
				left join core_company as comp on comp.comp_id = expens.exp_company and comp.deleted = 0
			    LEFT JOIN core_files as files on files.file_ref_id = expens.exp_id and files.file_type = ".DOC_TYPE_EXP." and files.deleted = 0
				" );

		$this->_where [] = "pdet_pay_id= :pdet_pay_id";
			
		return parent::fetchAll( $cond );
	}
	
	public function getPaymentDetByApproval($cond) {
		$this->query ( "select $this->_table.pdet_id,pdet_exp_id
				from $this->_table
				" );
			$this->_where [] = "pdet_status= :pdet_status";
			
			if (! empty ( $cond ['pdet_pay_id_exclude'] ))
				$this->_where [] = "pdet_pay_id <> :pdet_pay_id_exclude";
			
			return parent::fetchPair( $cond );
	}
	
	
	public function getAllPaymentDetForExp($cond) {
		$this->query ( "select 
				to_char(pay_paydate,'DD-MM-YYYY') as pay_paydate,
				pdet_amt_topay,	pdet_amt_paid,	pdet_amt_dis,	pdet_amt_bal, pay_remarks,
				file_id,
				file_actual_name ||'.'||file_exten as file_label
				from  
				$this->_table
				left join mis_payment as pay on pay.pay_id = pdet_pay_id and pay.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = pay.pay_id and files.deleted = 0 AND files.file_type =".DOC_TYPE_PAY );
		
		$this->_where [] = "pdet_status= :pdet_status";
		$this->_where [] = "pdet_exp_id= :pdet_exp_id";
		
		$this->_order [] = 'pay_id DESC';
				
		return parent::fetchAll( $cond );
	}
		
	
	public function getPaymentDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.* 
				from $this->_table 
				" );
		if (! empty ( $cond ['pdet_id'] ))
			$this->_where [] = "pdet_id= :pdet_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getPaymentDetById($id){
		return parent::getById ($id);
	}
	
	public function getPaymentById($id){
		return parent::getById ($id);
	}
	
	public function deletePayment($id) {
		return parent::delete ( $id );
	}
	
	public function deletePayDetByExpId($cond=array()) {
		
		$this->_where [] = "pdet_pay_id= :pdet_pay_id";
		return parent::deleteByCond( $cond);
	}
	
}


