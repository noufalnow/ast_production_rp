<?php
class loginlog extends db_table {
	protected $_table = "core_login_log";
	protected $_pkey = "log_id";
	
	public function add($data) {
	    $this->_nolog = true;
		return parent::insert ( $data );
	}

}


