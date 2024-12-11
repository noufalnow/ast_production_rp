<?php

class calllogfollow extends db_table
{

    protected $_table = "mis_call_log_follow";

    protected $_pkey = "cflo_id";
    
    protected function getStatusString($status_field)
    {
        $stsstring = " CASE
                    -- Positive Resolutions
                    WHEN $status_field = 1 THEN 'SATISFIED'
                    WHEN $status_field = 2 THEN 'RESOLVED'
                    WHEN $status_field = 3 THEN 'INFORMATION PROVIDED'
                    
                    -- Negative Outcomes
                    WHEN $status_field = 4 THEN 'NOT AVAILABLE NOW'
                    WHEN $status_field = 5 THEN 'NOT INTERESTED'
                    WHEN $status_field = 6 THEN 'DISCONNECTED'
                    WHEN $status_field = 7 THEN 'INCORRECT NUMBER'
                    WHEN $status_field = 8 THEN 'CUSTOMER UNREACHABLE'
                    
                    -- Follow-up Scenarios
                    WHEN $status_field = 9 THEN 'NEED FOLLOWUP'
                    WHEN $status_field = 10 THEN 'FOLLOWUP SCHEDULED'
                    WHEN $status_field = 11 THEN 'FOLLOWUP COMPLETED'
                    WHEN $status_field = 12 THEN 'RESCHEDULED'
                    WHEN $status_field = 13 THEN 'REQUEST FOR CALLBACK'
                    WHEN $status_field = 14 THEN 'CALL DROPPED'
                    
                    -- Escalation Scenarios
                    WHEN $status_field = 15 THEN 'ESCALATED'
                    WHEN $status_field = 16 THEN 'SUPERVISOR INVOLVED'
                    WHEN $status_field = 17 THEN 'ESCALATION REQUIRED'
                    
                    -- Action Pending or Deferred
                    WHEN $status_field = 18 THEN 'ACTION PENDING'
                    WHEN $status_field = 19 THEN 'WAITING FOR APPROVAL'
                    WHEN $status_field = 20 THEN 'UNDER NEGOTIATION'
                    WHEN $status_field = 21 THEN 'DEFERRED'
                    WHEN $status_field = 22 THEN 'PENDING RESPONSE'
                    WHEN $status_field = 23 THEN 'ACTION INITIATED'
                    WHEN $status_field = 24 THEN 'NO FURTHER ACTION'
                    
                    -- Miscellaneous
                    WHEN $status_field = 25 THEN 'NOT APPLICABLE'
                    WHEN $status_field = 26 THEN 'COMPLAINT LOGGED'
                    WHEN $status_field = 27 THEN 'CLOSED WITHOUT ACTION'
                    WHEN $status_field = 28 THEN 'LEAD GENERATED'
                    WHEN $status_field = 29 THEN 'QUERY RESOLVED'
                    WHEN $status_field = 30 THEN 'CALL COMPLETED'
                    
                    ELSE 'Unknown'
                END ";
        
        return $stsstring;
    }

    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getFollowPairs($cond = array())
    {
        $this->query("SELECT cflo_id, cflo_log || ' (' || cflo_date || ' - ' || cflo_time || ')' FROM $this->_table");

        if (! empty($cond['cflo_clog_id'])) {
            $this->_where[] = "cflo_clog_id = :cflo_clog_id";
        }

        $this->_order[] = 'cflo_date DESC, cflo_time DESC';
        return parent::fetchPair($cond);
    }

    public function getFollowsAll($cond)
    {
        $this->query("SELECT $this->_table.*,

                            ".$this->getStatusString('cflo_sts')." AS cflo_sts_lbl,
                            to_char(cflo_date,'DD/MM/YYYY') as cflo_date_lbl,
                            TO_CHAR(cflo_date + cflo_time, 'DD/MM/YYYY HH:MI AM') AS cflo_date_time_lbl,
                            emp_fname ||' '||emp_mname||' '||emp_lname as emp_name

                    FROM $this->_table
        			LEFT JOIN core_users AS users ON users.user_id = cflo_emp
                    LEFT JOIN mis_employee AS emp ON emp.emp_id = users.user_emp_id
                    ");

        $this->_where[] = "cflo_clog_id = :cflo_clog_id";

        $this->_order[] = 'cflo_date DESC, cflo_time DESC';
        return parent::fetchAll($cond);
    }

    public function getFollowDetails($cond)
    {
        $this->query("SELECT $this->_table.* FROM $this->_table");

        if (! empty($cond['cflo_id'])) {
            $this->_where[] = "cflo_id = :cflo_id";
        }

        return parent::fetchRow($cond);
    }

    public function getFollowById($id)
    {
        return parent::getById($id);
    }

    public function deleteFollow($id)
    {
        return parent::delete($id);
    }
}
