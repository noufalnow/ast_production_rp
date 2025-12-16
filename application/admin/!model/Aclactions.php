<?php class Aclactions extends db_table {
	protected $_table = "cnfg_acl_actions";
	protected $_pkey = "action_id";
	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getActionsPair($cond = array()) {
		$this->query ( "select action_id,action_name from $this->_table 
				left join cnfg_acl_controllers as controller on controller.controller_id = $this->_table.action_controller_id and controller.deleted = 0" );
		$this->_where [] = "controller_module_id= :controller_module_id";
		$this->_where [] = "action_controller_id= :action_controller_id";
		$this->_order [] = 'action_name ASC';
		return parent::fetchPair ( $cond );
	}
	public function getActionsList($cond) {
		$this->query ( "select $this->_table.*
				from $this->_table " );
		return parent::fetchAll ( $cond );
	}
	public function getActionsBy($cond) {
		$this->query ( "select $this->_table.*
				from $this->_table " );
		if (! empty ( $cond ['action_controller_id'] ))
			$this->_where [] = "action_controller_id= :action_controller_id";
			$this->_where [] = " 	action_resource != :action_resource";
			$cond['action_resource']  = 3;
			return parent::fetchAll ( $cond );
	}
	public function getActionsById($id) {
		return parent::getById ( $id );
	}
	public function deleteActions($id) {
		return parent::delete ( $id );
	}
	
	public function getModuleActionsAction($cond)
	{
		$actionsArray = array ();
		$this->query ( "select action_id,action_name,controller.controller_id,controller.controller_name,module.module_id,module.module_name
				 from $this->_table 
				 left join cnfg_acl_controllers as controller on controller.controller_id = $this->_table.action_controller_id and controller.deleted = 0
				 left join cnfg_acl_modules as module on controller.controller_module_id = module.module_id and module.deleted = 0
				" );
		return parent::fetchAll ( $cond );
	}
	
	public function getModuleActionsActionByName($cond)
	{
		$actionsArray = array ();
		$this->query ( "action_id','controller.controller_id','module.module_id
				from $this->_table
				left join cnfg_acl_controllers as controller on controller.controller_id = $this->_table.action_controller_id and controller.deleted = 0
				left join cnfg_acl_modules as module on controller.controller_module_id = module.module_id and module.deleted = 0
				" );
		
		if (! empty ( $cond ['action_name'] ))
			$this->_where [] = "action_name= :action_name";
		
		if (! empty ( $cond ['action_name'] ))
			$this->_where [] = "controller.controller_name= :controller_name";
		
		if (! empty ( $cond ['action_name'] ))
			$this->_where [] = "module.module_name= :module_name";
		
		return parent::fetchRow( $cond );
	}
	
	
	public function getAllPermissionForTheRoll($cond)
	{
		$resultArray = array();
		
		$this->query ( "select aacc_id,aacc_access_status,action_id,action_name,controller.controller_id,controller.controller_name,module.module_id,module.module_name
				from $this->_table
				
				left join cnfg_acl_actions_access as actionacc on actionacc.aacc_action_id = $this->_table.action_id and actionacc.deleted = 0
				left join cnfg_acl_controllers as controller on controller.controller_id = $this->_table.action_controller_id and controller.deleted = 0
				left join cnfg_acl_modules as module on controller.controller_module_id = module.module_id and module.deleted = 0
				
				" );
		
		$this->_where [] = "((aacc_role_type= :aacc_role_type and aacc_role_id= :aacc_role_id and aacc_access_status= :aacc_access_status) OR (action_resource = :action_resource))";
		
		$cond['aacc_access_status'] = 2 ;
		$cond['action_resource'] = 3 ;
		
		$result = parent::fetchAll ( $cond );
		foreach($result as $action){
			//$resultArray_id[$action['module_id']][$action['controller_id']][$action['action_id']] = $action['aacc_access_status'];
			$resultArray_name[$action['module_name']."/".$action['controller_name']."/".$action['action_name'] ]=$action['module_id']."_".$action['controller_id']."_".$action['action_id'];
			
		}
		
		//$resultArray[id_batch] = $resultArray_id;
		//$resultArray[name_batch] = $resultArray_name;
		$resultArray = $resultArray_name;
		
		return $resultArray;
	}
}


