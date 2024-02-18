<?php
class bankaccount extends db_table {
	protected $_table = "core_bank_account";
	protected $_pkey = "acnt_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getAccountDetails($cond = array()) {
	
		$this->query ( "select acnt_id,acnt_disp_name
		from $this->_table 
		" );
				
		$this->_order [] = 'acnt_disp_name DESC';

	
		return parent::fetchPair( $cond );
	}
	
	public function getAccountList($cond = array()) {
		
		$this->query ( "select *,
				case when acnt_type=1 then 'Company'
					 when acnt_type=2 then 'Personal'	
				end as account_disp_type,
				comp_disp_name
				from $this->_table
				left join core_company as comp on comp_id = acnt_company and comp.deleted = 0
				" );
		
		$this->_order [] = 'acnt_disp_name DESC';
		
		
		return parent::fetchAll( $cond );
	}
	
	
	

	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getContactById($id) {
		return parent::getById ($id);
	}
	
	public function getContactsByRef($cond){
		$this->query ( "select * from $this->_table" );
	
		$this->_where [] = "acnt_ref_type= :acnt_ref_type";
		$this->_where [] = "acnt_ref_id= :acnt_ref_id";
		
		$this->_order [] = 'acnt_id DESC';
		return parent::fetchRow( $cond );
	
	}
	public function deleteContact($id) {
		return parent::delete ( $id );
	}
	
	

	
}


