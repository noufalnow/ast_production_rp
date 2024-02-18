<?php
class expupdate extends db_table {
	protected $_table = "mis_expense_update";
	protected $_pkey = "eup_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getPaymentExpDet($cond) {
		$this->query ( "select $this->_table.* ,
				expens.*,
				case  
				when eup_type = 3 then 'Cancellation'
				when eup_type = 4 then 'Partial Return'
				when eup_type = 5 then 'Full Return'
				end as tra_type,
				files.file_id,
				files.file_exten,
				to_char(eup_date,'DD/MM/YYYY') as eup_date
				from $this->_table
				left join mis_expense as expens on expens.exp_id = $this->_table.eup_exp_id and expens.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.eup_id and files.file_type = ".DOC_TYPE_EXP_UPD." and files.deleted = 0
				" );

		$this->_where [] = "eup_exp_id= :eup_exp_id";
			
		return parent::fetchRow( $cond );
	}
	
	public function getPaymentDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.* 
				from $this->_table 
				" );
		if (! empty ( $cond ['eup_id'] ))
			$this->_where [] = "eup_id= :eup_id";
		
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
		
		$this->_where [] = "eup_pay_id= :eup_pay_id";
		return parent::deleteByCond( $cond);
	}
	
}


