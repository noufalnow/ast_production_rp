<?php
class expensemhref extends db_table {
	protected $_table = "mis_expense_href";
	protected $_pkey  = "eref_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
		
	public function getBillDetById($id){
		return parent::getById ($id);
	}
	
	public function getBillById($id){
		return parent::getById ($id);
	}
	
	public function deleteExpRefByExpId($cond=array()) {
		
		$this->_where [] = "eref_exp_id= :eref_exp_id";
		return parent::deleteByCond( $cond);
	}
	
	public function getExpRefDet($cond=array()){
		
		$this->query ( "select eref_id,eref_id from $this->_table " );
		$this->_where [] = "eref_exp_id= :eref_exp_id";
		$this->_where [] = "eref_status= :eref_status";
				
		return parent::fetchPair ( $cond );
	}
	
	public function getExpRefIdRef($cond=array()){
		
		$this->query ( "select eref_id,eref_main_head_ref from $this->_table " );
		$this->_where [] = "eref_exp_id= :eref_exp_id";
		$this->_where [] = "eref_status= :eref_status";
		
		return parent::fetchPair ( $cond );
	}
	
	public function getExpRefDetExtended($cond=array()){
		
		$this->query ( "select 
			eref_id,eref_exp_id,eref_main_head_ref,eref_amount,
			emp_fname ||' '||emp_mname||' '||emp_lname as ref_name1,
			cust_name ||' '|| project_name ||' '|| project_code as ref_name2,
			vhl_no as ref_name3
			from $this->_table 
			LEFT JOIN mis_employee as emp on emp.emp_id = $this->_table.eref_main_head_ref and $this->_table.eref_main_head = 1  and emp.deleted= 0
			LEFT JOIN mis_projects as prop on prop.project_id = $this->_table.eref_main_head_ref and $this->_table.eref_main_head = 2 and prop.deleted= 0
			left join mis_customer as customer on customer.cust_id = prop.project_client_id and customer.deleted = 0 
			LEFT JOIN mis_vehicle as vhl on vhl.vhl_id = $this->_table.eref_main_head_ref and $this->_table.eref_main_head = 3 and vhl.deleted= 0
		" );
		
		$this->_where [] = "eref_exp_id= :eref_exp_id";
		$this->_where [] = "eref_status= :eref_status";
		return parent::fetchAll ( $cond );
	}

	public function getExpRefDetAmount($cond=array()){
		
		$this->query ( "select eref_id,eref_amount from $this->_table " );
		$this->_where [] = "eref_exp_id= :eref_exp_id";
		$this->_where [] = "eref_status= :eref_status";
		
		return parent::fetchPair ( $cond );
	}
}?>