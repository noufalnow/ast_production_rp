<?php
class Aclcontrolleraccess extends db_table {
	protected $_table = " cnfg_acl_controllers_access";
	protected $_pkey = "cacc_id";
	public function add($data) {
	    $this->_nolog = true;
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
	    $this->_nolog = true;
		return parent::update ( $data, $cond );
	}
	public function getControllerAccessPair($cond = array()) {
		$this->query ( "select cacc_id,module_name from $this->_table" );
		$this->_order [] = 'module_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	public function getControllerAccessList($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		return parent::fetchAll ( $cond );
	}
	public function getControllerAccessBy($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		if (! empty ( $cond ['cacc_id'] ))
			$this->_where [] = "cacc_id= :cacc_id";
		
		return parent::fetchAll ( $cond );
	}
	public function getControllerAccessById($id) {
		return parent::getById ( $id );
	}
	public function deleteControllerAccess($id) {
	    $this->_nolog = true;
		return parent::delete ( $id );
	}
	

	public function getControllerRoleDetails($cond)
	{
	        
		$this->query ( "select $this->_table.*
				from $this->_table " );
		$this->_where [] = "cacc_controller_id= :cacc_controller_id";
		$this->_where [] = "cacc_role_id= :cacc_role_id";
		$this->_where [] = "cacc_role_type= :cacc_role_type";
		return parent::fetchRow ( $cond );
	}
	
	public function getControllerRoleDetailsByRoles($cond)
	{
		$resultArray = array ();
		
		$this->query ( "select $this->_table.*
				from $this->_table " );
		$this->_where [] = "cacc_role_type= :cacc_role_type";
		$this->_where [] = "cacc_role_id= :cacc_role_id";
		
		$result = parent::fetchAll ( $cond );
		foreach ( $result as $modules )
			$resultArray [$modules['cacc_controller_id']] = array (
					"create" => $modules['cacc_create_status'],
					"view" => $modules['cacc_view_status'],
					"update" => $modules['cacc_update_status'],
					"del" => $modules['cacc_delete_status'] 
			);
		
		return $resultArray;
	}
	
	
	public function deleteControllerAccessByUser($cond=array()){
	    $this->_nolog = true;
	    $cond['cacc_role_type'] = '2';
	    $this->_where [] = "cacc_role_id= :cacc_role_id";
	    $this->_where [] = "cacc_role_type= :cacc_role_type";
	    return parent::deleteByCond( $cond);
	}
	
}