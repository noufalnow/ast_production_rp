<?php
class ticketsmhref extends db_table {
	protected $_table = "mis_tickets_href";
	protected $_pkey  = "tref_id";
	

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
	
	public function deleteTicketRefByTktId($cond=array()) {
		
		$this->_where [] = "tref_tkt_id= :tref_tkt_id";
		return parent::deleteByCond( $cond);
	}
	
	public function getTktRefDet($cond=array()){
		
		$this->query ( "select tref_id,tref_id from $this->_table " );
		$this->_where [] = "tref_tkt_id= :tref_tkt_id";
			
		return parent::fetchPair ( $cond );
	}
	
	public function getTktRefIdRef($cond=array()){
		
		$this->query ( "select * from $this->_table " );
		$this->_where [] = "tref_tkt_id= :tref_tkt_id";
		
		return parent::fetchAll( $cond );
	}
	
	public function getTktRefDetExtended($cond=array()){
		
		$this->query ( "select 
			tref_id,tref_tkt_id,tref_main_head_ref,
			emp_fname ||' '||emp_mname||' '||emp_lname as ref_name1,
			bld_name as ref_name2,
			prop_fileno ref_fileno2,
			vhl_no as ref_name3
			from $this->_table 
			LEFT JOIN mis_employee as emp on emp.emp_id = $this->_table.tref_main_head_ref and $this->_table.tref_main_head = 1  and emp.deleted= 0
			LEFT JOIN mis_property as prop on prop.prop_id = $this->_table.tref_main_head_ref and $this->_table.tref_main_head = 2 and prop.deleted= 0
			left join mis_building as building on building.bld_id = prop.prop_building and building.deleted = 0 and building.deleted= 0
			LEFT JOIN mis_vehicle as vhl on vhl.vhl_id = $this->_table.tref_main_head_ref and $this->_table.tref_main_head = 3 and vhl.deleted= 0
		" );
		
		$this->_where [] = "tref_tkt_id= :tref_tkt_id";
		return parent::fetchAll ( $cond );
	}

	public function getTktRefDetAmount($cond=array()){
		
		$this->query ( "select tref_id,tref_amount from $this->_table " );
		$this->_where [] = "tref_tkt_id= :tref_tkt_id";
		$this->_where [] = "tref_status= :tref_status";
		
		return parent::fetchPair ( $cond );
	}
}?>