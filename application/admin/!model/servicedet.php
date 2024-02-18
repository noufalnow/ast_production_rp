<?php
class servicedet extends db_table {
	protected $_table = "mis_vhl_srv_det";
	protected $_pkey = "sdt_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getDetById($id) {
		return parent::getById ($id);
	}
	
	public function getDetByServiceId($cond) {
		
		$this->query ( "select *,
				item_name as srv_item,
				emp.emp_fname ||' '||emp.emp_mname||' '||emp.emp_lname as done_by
 				from $this->_table
				left join mis_employee as emp on emp.emp_id  = sdt_done_by and emp.deleted = 0 
			    left join mis_item as item on item.item_id  = sdt_item and item.deleted = 0 
				
				" );
		
		$this->_where [] = "sdt_srv_id= :sdt_srv_id";
		
		$this->_order [] = 'sdt_id DESC';
		
		
		return parent::fetchAll ( $cond );
	}
	
	
	public function getDetByServiceIdPairs($cond){
		
		$this->query ( "select sdt_id,sdt_id
				 		from $this->_table" );
		
		$this->_where [] = "sdt_srv_id= :sdt_srv_id";
		
		$this->_order [] = 'sdt_id DESC';
		
		
		return parent::fetchPair( $cond );
		
	}
	
	public function deleteServiceItemById($id) {
		return parent::delete ( $id );
	}
	
	public function deleteServiceItemByserviceId($cond) {
		
		$this->_where [] = "sdt_srv_id= :sdt_srv_id";
		return parent::deleteByCond( $cond);
	}

}


