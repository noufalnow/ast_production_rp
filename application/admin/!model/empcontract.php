<?php
class empcontract extends db_table {
	protected $_table = "mis_emp_contract";
	protected $_pkey = "emc_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getDetById($id) {
		return parent::getById ($id);
	}
	
	public function getDetByEmployeeId($cond) {
		
		$this->query ( "select *,
				to_char(emc_date_start,'DD/MM/YYYY') as emc_date_start,
				to_char(emc_date_end,'DD/MM/YYYY') as emc_date_end
				from $this->_table
				left join mis_employee as emp on emp.emp_id = $this->_table.emc_emp_id and emp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.emc_cust_id and cust.deleted = 0		
				left join mis_vehicle as vhl on vhl.vhl_id = $this->_table.emc_vhl_id and vhl.deleted = 0			
				" );
		
		$this->_where [] = "emc_emp_id= :emc_emp_id";
		
		$this->_order [] = 'emc_id DESC';
		
		
		return parent::fetchAll ( $cond );
	}
	
	
	public function getVehicleContractReport($cond){
		
		
		@$cond = array_filter ( $cond );
		
		$where [] = " mis_vehicle.deleted = 0 ";
		$where [] = " mis_vehicle.vhl_comm_status = 2 ";
		//$where [] = " emc_cust_id IS NOT NULL ";
		
		if (! empty ( $cond ['f_vhlno'] ))
			$where [] = " vhl_no like '%' || :f_vhlno || '%' ";
		if (! empty ( $cond ['f_type'] ))
			$where [] = " vhl_type = :f_type ";
		if (! empty ( $cond ['f_name'] ))
			$where [] = " ((lower(emp_fname) like '%' || lower(:f_name) || '%'
					OR lower(emp_mname) like '%' || lower(:f_name) || '%'
					OR lower(emp_lname) like '%' || lower(:f_name) || '%'
					)OR
					(lower(emp_fname)||' '||lower(emp_mname)||' '||lower(emp_lname) like '%' || lower(:f_name) || '%')) ";
		if (! empty ( $cond ['f_customer'] ))
			$where [] = " emc_cust_id = :f_customer ";
		if (! empty ( $cond ['f_status'] ))
			$where [] = " emc_status = :f_status ";
		
		if (! empty ( $cond ['vhl_id'] )){
			$where [] = " vhl_id = :vhl_id ";
			$order  = " ORDER BY sts_end_date DESC,  emc_status DESC,  emc_id DESC ";
		}
		else 
			$order  = " ORDER BY emc_cust_id,type_id,vhl_id,emc_id DESC ";
							
		$where = ' WHERE ' . implode ( ' AND ', $where );
				
		$this->query (
				"SELECT emc_id,
				vhl_no,
				type.type_name,
				cust_name,
				to_char(emc_date_start,'DD/MM/YYYY') AS sts_start_date,
				to_char(emc_date_end,'DD/MM/YYYY') AS sts_end_date,
				emc_status,
				emc_cust_id,
				emc_project,
				emc_location,
				CASE
				WHEN emc_status = 1 THEN emc_note
				WHEN emc_status = 2 THEN emc_note2
				END AS emc_note,
				concat(emp_fname,' ',emp_mname,' ', emp_lname) AS emp_name,
				CASE
				WHEN emc_status = 1 THEN 'Ongoing'
				WHEN emc_status = 2 THEN 'Completed'
				END AS status
				FROM mis_vehicle
				LEFT JOIN mis_emp_contract AS vcon ON vcon.emc_vhl_id = vhl_id
				AND vcon.deleted = 0
				LEFT JOIN mis_employee AS emp ON emp.emp_id = emc_emp_id
				AND emp.deleted = 0
				LEFT JOIN mis_customer AS cust ON cust.cust_id = emc_cust_id
				AND cust.deleted = 0
				LEFT JOIN mis_vehicle_type AS TYPE ON TYPE.type_id = vhl_type
				AND TYPE.deleted = 0
				
				$where
				$order"
				);
		
		
		return parent::fetchQuery($cond);
	}

}


