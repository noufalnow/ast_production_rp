<?php
class designation extends db_table {
	protected $_table = "core_designation";
	protected $_pkey = "desig_id";
	

	public function getDesigPair($cond = array()) {
		$this->query ( "select desig_id,desig_name from $this->_table" );
		$this->_order [] = 'desig_name ASC';
		
		return parent::fetchPair ( $cond );
	}

}


