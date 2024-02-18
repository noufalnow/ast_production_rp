<?php
class ticketscat extends db_table {
	protected $_table = "mis_tickets_cat";
	protected $_pkey = "tcat_id";
	

	public function getCategoryPair($cond = array()) {
		$this->query ( "select tcat_id,tcat_name from $this->_table" );
		
		if (! empty ( $cond ['tcat_type'] ))
			$this->_where [] = "tcat_type = :tcat_type";
		
		if (! empty ( $cond ['tcat_parent'] ))
			$this->_where [] = "tcat_parent = :tcat_parent";
			
		$this->_order [] = 'tcat_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getCategoryByName($cond = array()){
		
		$this->query ( "select * from $this->_table" );
		
		if (! empty ( $cond ['tcat_name'] ))
			$this->_where [] = "tcat_name= :tcat_name";
		
		if (! empty ( $cond ['tcat_type'] ))
			$this->_where [] = "tcat_type = :tcat_type";
		
		if (! empty ( $cond ['tcat_parent'] ))
			$this->_where [] = "tcat_parent = :tcat_parent";
			
			return parent::fetchRow ( $cond );
	}

}


