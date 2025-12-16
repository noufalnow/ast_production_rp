<?php
class Aclactionsaccess extends db_table {
	protected $_table = "cnfg_acl_actions_access";
	protected $_pkey = "aacc_id";
	public function add($data) {
	    $this->_nolog = true;
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
	    $this->_nolog = true;
		return parent::update ( $data, $cond );
	}
	public function getActionsAccessPair($cond = array()) {
		$this->query ( "select aacc_id,module_name from $this->_table" );
		$this->_order [] = 'module_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	public function getActionsAccessList($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		return parent::fetchAll ( $cond );
	}
	public function getActionsAccessBy($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		if (! empty ( $cond ['aacc_id'] ))
			$this->_where [] = "aacc_id= :aacc_id";
		
		return parent::fetchAll ( $cond );
	}
	public function getActionsAccessById($id) {
		return parent::getById ( $id );
	}
	public function deleteActionsAccess($id) {
	    $this->_nolog = true;
		return parent::delete ( $id );
	}	
	

	public function getActionsRoleDetails($cond)
	{
		$this->query ( "select $this->_table.*
				from $this->_table " );
		$this->_where [] = "aacc_action_id= :aacc_action_id";
		$this->_where [] = "aacc_role_id= :aacc_role_id";
		$this->_where [] = "aacc_role_type= :aacc_role_type";
		
		return parent::fetchRow ( $cond );

	}
	

	public function getActionsRoleDetailsByRoles($cond)
	{
		$resultArray = array ();
		
		$this->query ( "select $this->_table.*
				from $this->_table " );
		$this->_where [] = "aacc_role_type= :aacc_role_type";
		$this->_where [] = "aacc_role_id= :aacc_role_id";
		
		$result = parent::fetchAll ( $cond );
		foreach($result as $action)
			$resultArray[$action['aacc_action_id']] = array(
					"access"=>$action['aacc_access_status'],
			);
	
			return $resultArray;
		
	}
	
	
	public function deleteActionsAccessByUser($cond=array()){
	    $this->_nolog = true;
	    $cond['aacc_role_type'] = '2';
	    $this->_where [] = "aacc_role_id= :aacc_role_id";
	    $this->_where [] = "aacc_role_type= :aacc_role_type";
	    return parent::deleteByCond( $cond);
	}
	
	
	
}