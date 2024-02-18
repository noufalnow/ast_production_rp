<?php
class actionlog extends db_table {
	protected $_table = "core_action_log";
	protected $_pkey = "alog_id";
	
	public function add($data) {
	    $this->_nolog = true;
		return parent::insert ( $data);
	}

}


