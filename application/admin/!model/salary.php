<?php
class salary extends db_table {
	protected $_table = "mis_salary";
	protected $_pkey = "sal_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	
	public function getSalaryPaginate($cond){
		
		$this->paginate ( "select $this->_table.* ,
				to_char(sal_paydate,'Month') as sal_month,
				to_char(sal_paydate,'YYYY') as sal_Year
				", "from $this->_table " );
		
		if (! empty ( $cond ['f_selVendor'] ))
			$this->_where [] = "sal_vendor= :f_selVendor";
			
		$this->_order [] = 'sal_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getSalaryDet($cond) {
		$this->query ( "select $this->_table.* from $this->_table " );
		$this->_where [] = "sal_id= :sal_id";
		
		return parent::fetchRow( $cond );
	}
	
	public function getSalaryDetById($id){
		return parent::getById ($id);
	}
	
	public function getSalaryById($id){
		return parent::getById ($id);
	}
	
	public function deleteSalary($id) {
		return parent::delete ( $id );
	}
	
}


