<?php
class category extends db_table {
	protected $_table = "core_category";
	protected $_pkey = "cat_id";
	

	public function getCategoryPair($cond = array()) {
		$this->query ( "select cat_id,cat_name from $this->_table" );
		
		if (! empty ( $cond ['cat_type'] ))
			$this->_where [] = "cat_type = :cat_type";
		
		if (! empty ( $cond ['cat_parent'] ))
			$this->_where [] = "cat_parent = :cat_parent";
			
		
		$this->_order [] = 'cat_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getCategoryByName($cond = array()){
		
		$this->query ( "select * from $this->_table" );
		
		if (! empty ( $cond ['cat_name'] ))
			$this->_where [] = "cat_name= :cat_name";
		
		if (! empty ( $cond ['cat_type'] ))
			$this->_where [] = "cat_type = :cat_type";
		
		if (! empty ( $cond ['cat_parent'] ))
			$this->_where [] = "cat_parent = :cat_parent";
			
			return parent::fetchRow ( $cond );
	}

}


