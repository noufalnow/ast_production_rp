<?php
class tickets extends db_table {
	protected $_table = "mis_tickets";
	protected $_pkey = "tkt_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	
	public function getTicketsPaginate($cond){
		
		$this->paginate ( "select $this->_table.* ,
				LPAD(tkt_id::text, 5, '0') as ticket_no,
				to_char(tkt_dttime_strt,'DD/MM/YYYY') as tkt_date,
				case when tkt_priority = 1 then 'Low'
					 when tkt_priority = 2 then 'Medium'
					 when tkt_priority = 3 then 'High'
					 when tkt_priority = 4 then 'Emergency'
				end as tkt_priority,
				empto.emp_fname ||' '||empto.emp_mname||' '||empto.emp_lname as asigned_to,

				case when tktstatus.act_status = 1 then 'Open'
				 when tktstatus.act_status = 2 then 'Progress'
				 when tktstatus.act_status = 3 then 'On Hold'
				 when tktstatus.act_status = 4 then 'Pending for Approval'
				 when tktstatus.act_status = 5 then 'Escalated'
				 when tktstatus.act_status = 99 then 'Closed' end as action_status


				", "from $this->_table 
				left join mis_employee as empto on empto.emp_id  = tkt_assign and empto.deleted = 0 

				left join (SELECT max(act_id) AS max_status_id,act_ticket_id
				   FROM mis_tickets_actions
				   WHERE deleted = 0
				   GROUP BY act_ticket_id) max_status on max_status.act_ticket_id = $this->_table.tkt_id
				left join mis_tickets_actions as tktstatus on tktstatus.act_id = max_status.max_status_id and tktstatus.deleted = 0 

	
				" );
		
		if (! empty ( ltrim($cond ['f_ticketno'],'0') ))
			$this->_where [] = "tkt_id= :f_ticketno";
			
		$this->_order [] = 'tkt_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getTicketsDet($cond) {
		$this->query ( "select $this->_table.*,
						LPAD(tkt_id::text, 5, '0') as ticket_no,
						files.file_id,
						files.file_actual_name || '.' || files.file_exten as file_name,
						case when tkt_priority = 1 then 'Low'
							 when tkt_priority = 2 then 'Medium'
							 when tkt_priority = 3 then 'High'
							 when tkt_priority = 4 then 'Emergency'
						end as tkt_priority_label,
						empto.emp_fname ||' '||empto.emp_mname||' '||empto.emp_lname as asigned_to,
						to_char(tkt_dttime_strt,'DD/MM/YYYY') as tkt_start_date,
						to_char(tkt_dttime_end,'DD/MM/YYYY') as tkt_end_date,

						case when tkt_mainhead = 1 then 'Employee'
						when tkt_mainhead = 2 then 'Property'
						when tkt_mainhead = 3 then 'Vehicle'
						end as main_head,
						tcat_name
	
						from $this->_table 
						LEFT JOIN core_files as files on files.file_ref_id = $this->_table.tkt_id and files.file_type = ".DOC_TYPE_TKT." and files.deleted = 0
						left join mis_tickets_cat  as cat on cat.tcat_id =  $this->_table.tkt_cat and cat.deleted = 0 
						left join mis_employee as empto on empto.emp_id  = tkt_assign and empto.deleted = 0 
				" );
			
		$this->_where [] = "tkt_id= :tkt_id";
		
		return parent::fetchRow( $cond );
	}
	
	public function getTicketsDetById($id){
		return parent::getById ($id);
	}
	
	public function getTicketsById($id){
		return parent::getById ($id);
	}
	
	public function deleteTickets($id) {
		return parent::delete ( $id );
	}
	
}


