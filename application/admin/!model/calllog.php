<?php
class calllog extends db_table {
    protected $_table = "mis_call_log";
    protected $_pkey = "clog_id";
    
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
    

    public function add($data) {
        return parent::insert($data);
    }

    public function modify($data, $cond) {
        return parent::update($data, $cond);
    }

    public function getCallLogPair($cond = array()) {
        $this->query("select clog_id, clog_phone_no || ' - ' || clog_name || ' (' || clog_date || ' ' || clog_time || ')'
                      from $this->_table");
        
        if (!empty($cond['clog_type']))
            $this->_where[] = "clog_type = :clog_type";
        
        $this->_order[] = 'clog_date DESC';
        
        return parent::fetchPair($cond);
    }

    public function getCallLogsPaginate($cond) {
        $this->paginate("select $this->_table.*, 
                            case 
                                when clog_type = 1 then 'FLAT'
                                when clog_type = 2 then 'SHOP'
                                when clog_type = 3 then 'EQUIPMENTS'
                                when clog_type = 4 then 'OTHERS'
                            end as clog_type_label,

                            ".$this->getStatusString('clog_sts_for')." AS clog_sts_for_lbl,
                            ".$this->getStatusString('clog_sts_cur')." AS clog_sts_cur_lbl,
                            to_char(clog_date,'DD/MM/YYYY') as clog_date_lbl,
                            TO_CHAR(clog_date + clog_time, 'DD/MM/YYYY HH:MI AM') AS clog_date_time_lbl,
                            emp_fname ||' '||emp_mname||' '||emp_lname as emp_name

                        ", "from $this->_table

        				LEFT JOIN core_users AS users ON users.user_id = clog_emp
                        LEFT JOIN mis_employee AS emp ON emp.emp_id = users.user_emp_id

        				AND users.deleted = 0
                        ");
        
        if (!empty($cond['f_logtype']))
            $this->_where[] = "clog_type = :f_logtype";
        
        if (!empty($cond['f_action']))
            $this->_where[] = "clog_sts_cur = :f_action";
        
        if (!empty($cond['f_status']))
            $this->_where[] = "clog_sts = :f_status";

        if (!empty($cond['f_phone_no']))
            $this->_where[] = "clog_phone_no LIKE '%' || :f_phone_no || '%'";

        if (!empty($cond['f_name']))
            $this->_where[] = "LOWER(clog_name) LIKE '%' || LOWER(:f_name) || '%'";

        if (!empty($cond['f_date']))
            $this->_where[] = "clog_date = TO_DATE(:f_date, 'DD/MM/YYYY')";
        
        if (! empty($cond['f_month'])) {
            $monthYear = explode('/', $cond['f_month']);
            $this->_where[] = " (EXTRACT(month FROM clog_date) = '$monthYear[0]' AND EXTRACT(year FROM clog_date) = '$monthYear[1]' )";
            unset($cond['f_month']);
        }

        $this->_order[] = 'clog_date DESC';
        
        return parent::fetchAll($cond);
    }

    public function getCallLogDet($cond) {
        $this->query("select $this->_table.*
                      ", "from $this->_table");
        
        if (!empty($cond['clog_id']))
            $this->_where[] = "clog_id = :clog_id";

        return parent::fetchRow($cond);
    }

    public function getCallLogById($id) {
        return parent::getById($id);
    }

    public function deleteCallLog($id) {
        return parent::delete($id);
    }
}
