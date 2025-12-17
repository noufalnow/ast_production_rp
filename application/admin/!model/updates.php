<?php
class updates extends db_table {
	protected $_table = "core_updates";
	protected $_pkey = "upd_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getUpdatePaginate($cond){
		
		$this->paginate ( "select $this->_table.*,
						case when upd_type = 1 then 'Employee' 
						 when upd_type = 2 then 'Property' 
						 when upd_type = 3 then 'Vehicle' 
						 when upd_type = 4 then 'Invoice' 
						 when upd_type = 5 then 'Company' 
						end as txt_type, 
						case when upd_type = 1 then

						emp_fname ||' '||emp_mname||' '||emp_lname
						when upd_type = 2 then prop_fileno 
						when upd_type = 3 then vhl_no
						when upd_type = 4 then 'AST/00' || bill_id::text
						when upd_type = 5 then upd_title
						end as ref_name,
		
						case 
						 when upd_priority = 1 then 'Low' 
						 when upd_priority = 2 then 'Medium' 
						 when upd_priority = 3 then 'High'
						 when upd_priority = 4 then 'Urgent' 
						end as upd_priority, 

						case when upd_status = 1 then 'Open' 
						 when upd_status = 100 then 'Closed' 
						end as upd_status_label, 

						user_fname ||' '||	user_lname as user_name,
						to_char(upd_dttime,'DD/MM/YYYY') as upd_dttime,
						to_char(upd_enddttime,'DD/MM/YYYY') as upd_enddttime,
						to_char(core_updates.t_created,'DD/MM/YYYY HH24:MI:SS') as dt_created
						", "from $this->_table 
						left join core_users as users on users.user_id  = upd_reported and users.deleted = 0

						left join mis_employee as employee on employee.emp_id  = upd_type_refid and employee.deleted = 0 and upd_type = 1
						left join mis_projects as poperty on poperty.prop_id  = upd_type_refid and poperty.deleted = 0 and upd_type = 2
						left join mis_vehicle as vehicle on vehicle.vhl_id  = upd_type_refid and vehicle.deleted = 0 and upd_type = 3
						left join mis_bill as bill on bill.bill_id  = upd_type_refid and bill.deleted = 0 and upd_type = 4 and bill.bill_cancellation_status = 0
						" );
		
		if (! empty ( $cond ['upd_type'] ))
			$this->_where [] = "upd_type= :upd_type";
		
			//$cond ['upd_status'] = 100;
			//$this->_where [] = "upd_status <> :upd_status";
			
			$this->_order [] = 'upd_status asc, core_updates.upd_dttime ASC, upd_id DESC';
			
			return parent::fetchAll ( $cond );
	}

	
	public function getUpdateListByTypeAndRef($cond = array())
	{
		$this->query ( "select *,
						user_fname ||' '||	user_lname as user_name,
						to_char(upd_dttime,'DD/MM/YYYY') as upd_dttime,
						to_char(upd_enddttime,'DD/MM/YYYY') as upd_enddttime,
						to_char(core_updates.t_created,'DD/MM/YYYY HH24:MI:SS') as dt_created
						from $this->_table
						left join core_users as users on users.user_id  = upd_reported and users.deleted = 0
						" );
		$this->_where [] = "upd_type= :upd_type";
		$this->_where [] = "upd_type_refid= :upd_type_refid";
		$this->_order [] = "upd_id desc"; 
		
		return parent::fetchAll ( $cond );
		
	}
	
	public function getPendingUpdatesByUser($cond = array())
	{
	    
	    return [];
		$this->query ( "select
				to_char(upd_dttime,'DD/MM/YYYY') as upd_dttime,
				to_char(upd_enddttime,'DD/MM/YYYY') as upd_enddttime,
				upd_note,
				case when upd_type = 1 then
				emp_fname ||' '||emp_mname||' '||emp_lname
				when upd_type = 2 then prop_fileno 
				when upd_type = 3 then vhl_no
				when upd_type = 4 then 'AST/00' || bill_id::text
				when upd_type = 5 then upd_title
				end as ref_name
				from $this->_table
				left join mis_employee as employee on employee.emp_id  = upd_type_refid and employee.deleted = 0 and upd_type = 1
				left join mis_projects as poperty on poperty.prop_id  = upd_type_refid and poperty.deleted = 0 and upd_type = 2
				left join mis_vehicle as vehicle on vehicle.vhl_id  = upd_type_refid and vehicle.deleted = 0 and upd_type = 3
				left join mis_bill as bill on bill.bill_id  = upd_type_refid and bill.deleted = 0 and upd_type = 4 and bill.bill_cancellation_status = 0
				" );
		
		$cond ['upd_status'] = 100;
		$this->_where [] = "upd_status <> :upd_status";
		$this->_where [] = "upd_enddttime  IS NOT NULL";
				
		$this->_where [] = "upd_assign= :upd_assign";
		
		$this->_order [] = "upd_id desc";
		
		return parent::fetchAll ( $cond );
	}
	
	public function getOpenUpdatesCount($cond = array())
	{
		$cond['upd_status'] = 1;
		$this->query ( "select count(*) from $this->_table" );	
		$this->_where [] = "upd_status= :upd_status";
		return parent::fetchRow( $cond );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getUpdateById($id) {
		return parent::getById ($id);
	}

}


