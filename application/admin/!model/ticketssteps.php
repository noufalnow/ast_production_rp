<?php
class ticketssteps extends db_table {
	protected $_table = "mis_tickets_steps";
	protected $_pkey = "stp_id";
	

	public function getStepsPair($cond = array()) {
		$this->query ( "select stp_id,stp_steps from $this->_table" );
			
		$this->_where [] = "stp_ticket_id= :stp_ticket_id";
		$this->_order [] = 'stp_id ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getTktRefIdRef($cond=array()){
		
		$this->query ( "select *,
						to_char(stp_dttime,'DD/MM/YYYY') as stp_dttime_disply,
						empassign.emp_fname ||' '||empassign.emp_mname||' '||empassign.emp_lname as steps_by 
						from $this->_table
						left join mis_employee as empassign on empassign.emp_id  = stp_by and empassign.deleted = 0 	
						" );
		$this->_where [] = "stp_ticket_id= :stp_ticket_id";
		$this->_order [] = 'stp_id ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getTktAndStepsByUser($cond=array()){
		
		$this->query ( "select stp_steps,
				to_char(stp_dttime,'DD/MM/YYYY') as stp_dttime_disply,
				tkt_details
				from $this->_table
				left join mis_tickets as tickets on tickets.tkt_id  = stp_ticket_id and tickets.deleted = 0 AND ( tickets.tkt_status <> 99 OR tickets.tkt_status  IS NULL)
				left join core_users as users on users.user_emp_id  = $this->_table.stp_by and users.deleted = 0
				LEFT JOIN mis_tickets_actions as actions on  actions.act_steps = stp_id and actions.deleted = 0
				" );
		
		$this->_where [] = "users.user_id= :user_id";
		$this->_where [] = "actions.act_steps IS NULL";
				
		$this->_order [] = 'stp_id ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	
	public function deleteTicketStepsByTktId($cond=array()) {
		
		$this->_where [] = "stp_ticket_id= :stp_ticket_id";
		return parent::deleteByCond( $cond);
	}
	
	public function getStepsByName($cond = array()){
		
		$this->query ( "select * from $this->_table" );
			
		return parent::fetchRow ( $cond );
	}

}


