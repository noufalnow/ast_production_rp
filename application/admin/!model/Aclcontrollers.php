<?php
class Aclcontrollers extends db_table {
	protected $_table = "cnfg_acl_controllers";
	protected $_pkey = "controller_id";
	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getControllerPair($cond = array()) {
		$this->query ( "select controller_id,controller_name from $this->_table" );
		
		$this->_where [] = "controller_module_id=:controller_module_id";
		
		$this->_order [] = 'controller_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	public function getControllerList($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		return parent::fetchAll ( $cond );
	}
	public function getControllerBy($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		if (! empty ( $cond ['controller_module_id'] ))
			$this->_where [] = "controller_module_id= :controller_module_id";
		
		return parent::fetchAll ( $cond );
	}
	public function getControllerById($id) {
		return parent::getById ( $id );
	}
	public function deleteController($id) {
		return parent::delete ( $id );
	}
}

	
	