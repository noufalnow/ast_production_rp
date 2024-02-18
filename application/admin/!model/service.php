<?php
class service extends db_table {
	protected $_table = "mis_vhl_service";
	protected $_pkey = "srv_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getDetById($id) {
		return parent::getById ($id);
	}
	
	public function getDetByVehicleId($cond) {
		
		$this->query ( "select *,
			
					case when srv_type = 1 then 'Major Srv'
						when srv_type = 2 then 'Minor Srv'
					end as srv_type,

					case when srv_wash = 1 then 'No'
						when srv_wash = 2 then 'Yes'
					end as srv_wash,

					case when srv_greese = 1 then 'No'
						when srv_greese = 2 then 'Yes'
					end as srv_greese,

					case when srv_nxt_type = 1 then 'Major Srv'
						when srv_nxt_type = 2 then 'Minor Srv'
					end as srv_nxt_type,

					to_char(srv_date_start,'DD/MM/YYYY') as srv_date_start,
					to_char(srv_date_next,'DD/MM/YYYY') as srv_date_next,

	
				emp.emp_fname ||' '||emp.emp_mname||' '||emp.emp_lname as done_by
 				from $this->_table
				left join mis_employee as emp on emp.emp_id  = srv_done_by and emp.deleted = 0 
				" );
		
		$this->_where [] = "srv_vhl_id= :srv_vhl_id";
		
		$this->_order [] = 'srv_id DESC';
		
		
		return parent::fetchAll ( $cond );
	}
	
	public function getServiceReport($cond) {
		
		$this->query ( "select *,
				
				case when srv_type = 1 then 'Major Srv'
				when srv_type = 2 then 'Minor Srv'
				end as srv_type,
				
				case when srv_wash = 1 then 'No'
				when srv_wash = 2 then 'Yes'
				end as srv_wash,
				
				case when srv_greese = 1 then 'No'
				when srv_greese = 2 then 'Yes'
				end as srv_greese,
				
				case when srv_nxt_type = 1 then 'Major Srv'
				when srv_nxt_type = 2 then 'Minor Srv'
				end as srv_nxt_type,
				
				to_char(srv_date_start,'DD/MM/YYYY') as srv_date_start,
				to_char(srv_date_next,'DD/MM/YYYY') as srv_date_next,
				
				
				emp.emp_fname ||' '||emp.emp_mname||' '||emp.emp_lname as done_by
				from $this->_table
				left join mis_employee as emp on emp.emp_id  = srv_done_by and emp.deleted = 0
				left join mis_vehicle as vhl on vhl.vhl_id  = srv_vhl_id and vhl.deleted = 0
				" );
		
		if (!empty  ( $cond ['srv_vhl_id'] ))
			$this->_where [] = "srv_vhl_id= :srv_vhl_id";
			
		if (!empty  ( $cond ['vhl_comm_status'] ))
			$this->_where [] = "vhl_comm_status= :vhl_comm_status";
		
		$this->_order [] = 'srv_id DESC';
		
		
		return parent::fetchAll ( $cond );
	}

}


