<?php
class ticketsactions extends db_table {
	protected $_table = "mis_tickets_actions";
	protected $_pkey = "act_id";
	

	public function getActionListByTicket($cond = array()) {
		$this->query ( "select 
						empby.emp_fname ||' '||empby.emp_mname||' '||empby.emp_lname as action_by,
						empesc.emp_fname ||' '||empesc.emp_mname||' '||empesc.emp_lname as escalated_to,
						to_char(act_dttime,'DD/MM/YYYY') as action_on,
						case when act_status = 1 then 'Open'
						 when act_status = 2 then 'Progress'
						 when act_status = 3 then 'On Hold'
						 when act_status = 4 then 'Pending for Approval'
						 when act_status = 5 then 'Escalated'
						 when act_status = 99 then 'Closed' end as action_status,
						 steps.stp_steps,
						$this->_table.* from $this->_table
						left join mis_employee as empby on empby.emp_id  = act_by and empby.deleted = 0 	
						left join mis_employee as empesc on empesc.emp_id  = act_escalate and empesc.deleted = 0 	
						left join mis_tickets_steps as steps on steps.stp_ticket_id  = act_steps and steps.deleted = 0 		
						" );
		
		if (! empty ( $cond ['act_ticket_id'] ))
			$this->_where [] = "act_ticket_id = :act_ticket_id";
					
		$this->_order [] = 'act_id DESC';
		
		return parent::fetchAll( $cond );
	}
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getActionsByName($cond = array()){
		
		$this->query ( "select * from $this->_table" );
		
		if (! empty ( $cond ['act_name'] ))
			$this->_where [] = "act_name= :act_name";
		
		if (! empty ( $cond ['act_type'] ))
			$this->_where [] = "act_type = :act_type";
		
		if (! empty ( $cond ['act_parent'] ))
			$this->_where [] = "act_parent = :act_parent";
			
			return parent::fetchRow ( $cond );
	}

}


