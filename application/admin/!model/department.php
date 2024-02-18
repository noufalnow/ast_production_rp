<?php
class department extends db_table {
	protected $_table = "core_department";
	protected $_pkey = "dept_id";
	

	public function getDeptPair($cond = array()) {
		$this->query ( "select dept_id,dept_name from $this->_table" );
		$this->_order [] = 'dept_name ASC';
		
		return parent::fetchPair ( $cond );
	}

}


