<?php
class employeepay extends db_table {
	protected $_table = "mis_employee_pay";
	protected $_pkey = "pay_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getPayDetailsByEmployee($cond){
		$this->query ( "select * from $this->_table" );
		
		$this->_where [] = "pay_emp_id= :pay_emp_id";	
		$this->_order [] = 'pay_id DESC';
		return parent::fetchAll ( $cond );
	}
	
	public function getEmployeePayById($id) {
		return parent::getById ($id);
	}
	
	public function getEmployeePay($cond){
		$this->query ( "select * from $this->_table" );
	
		$this->_where [] = "pay_emp_id= :pay_emp_id";
		$this->_order [] = 'pay_id DESC';
		//$this->_limit = 1;
		return parent::fetchRow( $cond );
	
	}
	
	

	
}




