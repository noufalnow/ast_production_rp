<?php
class Aclmodules extends db_table {
	protected $_table = "cnfg_acl_modules";
	protected $_pkey = "module_id";
	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getModulePair($cond = array()) {
		$this->query ( "select module_id,module_name from $this->_table" );
		$this->_order [] = 'module_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	public function getModuleList($cond=array()) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		return parent::fetchAll ( $cond );
	}
	public function getModuleBy($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		if (! empty ( $cond ['module_id'] ))
			$this->_where [] = "module_id= :module_id";
		
		return parent::fetchAll ( $cond );
	}
	public function getModuleById($id) {
		return parent::getById ( $id );
	}
	public function deleteModule($id) {
		return parent::delete ( $id );
	}
}