<?php
class comp_department extends db_table {
	protected $_table = "core_comp_department";
	protected $_pkey = "cmpdept_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}

	public function getCompDeptPair($cond = array()) {
		
		$this->query ( "select cmpdept_id,dept_name from $this->_table
			left join core_department as dept ON dept.dept_id = core_comp_department.cmpdept_dept_id and dept.deleted = 0"
				 );
		if (!empty ( $cond ['cmpdept_comp_id'] ))
			$this->_where [] = "cmpdept_comp_id =:cmpdept_comp_id";
		
		
		$this->_order [] = 'dept_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getCompDeptList($cond = array()) {

		$this->paginate ( 'select comp_name,dept_name ', "from $this->_table 
				left join core_company as comp ON comp.comp_id = core_comp_department.cmpdept_comp_id and comp.deleted = 0
				left join core_department as dept ON dept.dept_id = core_comp_department.cmpdept_dept_id and dept.deleted = 0
				" );
		
		$this->_order [] = 'comp_name ASC';
		$this->_order [] = 'dept_name ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function chekDept($post){
		
		$cond ['cmpdept_dept_id'] = $post['dept'];
		$cond ['cmpdept_comp_id'] = $post['company'];
		
		
		$this->query ( "select * from $this->_table" );
		$this->_where [] = "cmpdept_dept_id= :cmpdept_dept_id";
		$this->_where [] = "cmpdept_comp_id= :cmpdept_comp_id";
		
		return parent::fetchAll ( $cond );
		
	}

}


