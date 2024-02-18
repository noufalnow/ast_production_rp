<?php
class user extends db_table {
	protected $_table = "core_users";
	protected $_pkey = "user_id";
	
	/*
	 * public function _construct(){
	 *
	 * parent::setTable($this->_table);
	 * parent::setObjectRef($this);
	 *
	 * }
	 */
	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		
		// if(isset($cond['userName']))
		// $this->_where [] = "user_uname = :userName";
		
		// $this->_where[] = "user_id= :user_id";
		return parent::update ( $data, $cond );
	}
	public function getUserById($id) {
		return parent::getById ($id);
	}
	public function getUser($cond) {
		$cond ['user_status'] = 1;
		
		$this->query ( "select $this->_table.* from $this->_table" );
		if (isset ( $cond ['user_uname'] ))
			$this->_where [] = "user_uname= :user_uname";
		if (isset ( $cond ['user_id'] ))
			$this->_where [] = "user_id= :user_id";
		
		if (isset ( $cond ['ex_user_id'] ))
			$this->_where [] = "user_id!= :ex_user_id";
		
		$this->_where [] = "user_status= :user_status";
		
		return parent::fetchRow ( $cond );
	}
	
	// group/order/limit/offset
	public function getUsers($cond = array()) {
		$cond ['user_status'] = 1;
		
		$this->query ( "select * from $this->_table" );
		$this->_where [] = "user_uname= :user_uname";
		$this->_where [] = "user_status= :user_status";
		$this->_group [] = 'user_id';
		$this->_group [] = 'user_uname';
		$this->_order [] = 'user_id ASC';
		$this->_order [] = 'user_uname DESC';
		
		$this->_limit = 3;
		$this->_offset = 0;
		
		return parent::fetchAll ( $cond );
	}
	public function deleteUser($id) {
		return parent::delete ( $id );
	}
	public function deleteUserByName($cond) {
		$this->_where [] = "user_uname= :userName";
		
		return parent::deleteByCond ( $cond );
	}
	public function getUsersPair($cond) {
		$cond ['user_status'] = 1;
		$this->query ( "select user_id,user_fname ||  ' ' ||user_lname from $this->_table" );
		
		if (!empty ( $cond ['user_desig'] ))
			$this->_where [] = "user_desig= :user_desig";
		
		$this->_where [] = "user_status= :user_status";
		
		$this->_order [] = 'user_id ASC';
		$this->_order [] = 'user_uname DESC';
		
		// $this->_limit = 5;
		// $this->_offset = 2;
		
		return parent::fetchPair ( $cond );
	}
	
	public function getUsersNamePair($cond) {
		$cond ['user_status'] = 1;
		$this->query ( "select user_id,CONCAT(user_fname,' ', user_lname) from $this->_table" );
		
		if (! empty ( $cond ['user_desig'] ))
			$this->_where [] = "user_desig= :user_desig";
		
		$this->_where [] = "user_status= :user_status";
		
		$this->_order [] = 'user_id ASC';
		$this->_order [] = 'user_uname DESC';
		
		return parent::fetchPair ( $cond );
	}
	
	
	public function getUsersPaginate($cond = array()) {
		// $cond ['user_status'] = 1;
		
		$this->paginate ( 'select * ', "from $this->_table " );
		
		if (!empty ( $cond ['user_fname'] ))
			$this->_where [] = "user_fname like '%' || :user_fname || '%' OR user_lname like '%' || :user_fname || '%'";
		if (!empty ( $cond ['user_uname'] ))
			$this->_where [] = "user_uname like '%' || :user_uname || '%'";
		if (!empty ( $cond ['user_desig'] ))
			$this->_where [] = "user_desig = :user_desig";
		
		if (!empty ( $cond ['u_created'] ))
			$this->_where [] = "u_created = :u_created";
		
		$this->_order [] = 'user_fname ASC';
		// $this->_order [] = 'user_uname DESC';
		
		return parent::fetchAll ( $cond );
	}
	public function getCount($cond) {
		$cond ['user_status'] = 1;
		
		$this->query ( "select count(*) from $this->_table" );
		// $this->_where[] = "user_id= :user_id";
		$this->_where [] = "user_status= :user_status";
		// $this->_group [] = 'user_id';
		$this->_group [] = 'user_uname';
		
		return parent::fetchResult ( $cond );
	}
	
	public function getUsersByUserGroup($cond = array()) {
		
		$this->query ( "select user_id from $this->_table" );
		$this->_where [] = "user_desig= :user_desig";
		
		return parent::fetchAll ( $cond );
	}
	
	public function getUserBranchId($cond = array()) {
		
		$this->query ( "select user_id from $this->_table
				-- join dm_usr_branch  as ubranch on ubranch.ubr_user = user_id and ubranch.deleted =0 and ubr_init_branch IS NOT NULL
				" );
		
		if (!empty ( $cond ['ubr_branch'] ))
			$this->_where [] = "ubr_branch = :ubr_branch";
		
		else if (!empty ( $cond ['user_id'] ))
			$this->_where [] = "user_id = :user_id";
		
		if (!empty ( $cond ['ubr_init_user'] ))
			$this->_where [] = "ubr_init_user = :ubr_init_user";
		
		return parent::fetchRow( $cond );
	}
}


