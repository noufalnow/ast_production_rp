<?php
class contacts extends db_table {
	protected $_table = "mis_contacts";
	protected $_pkey = "con_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getContacts($cond = array()) {
	
		$this->query ( "select * from $this->_table " );
		
		$this->_where [] = "con_ref_type= :con_ref_type";
		$this->_where [] = "con_ref_id= :con_ref_id";
		
		$this->_order [] = 'con_id DESC';

	
		return parent::fetchAll ( $cond );
	}
	

	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getContactById($id) {
		return parent::getById ($id);
	}
	
	public function getContactsByRef($cond){
		$this->query ( "select * from $this->_table" );
	
		$this->_where [] = "con_ref_type= :con_ref_type";
		$this->_where [] = "con_ref_id= :con_ref_id";
		
		$this->_order [] = 'con_id DESC';
		return parent::fetchRow( $cond );
	
	}
	public function deleteContact($id) {
		return parent::delete ( $id );
	}
	
	

	
}


