<?php
class empstatus extends db_table {
	protected $_table = "mis_employee_status";
	protected $_pkey = "sts_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	public function getStatusById($id) {
		return parent::getById ($id);
	}
	
	public function getStatusByStatusId($cond) {
	    
	    $this->query ( "select mis_employee_status.*,
                        files.file_id as fileid,
                        docsrpt.doc_id as docsid
                        from $this->_table

                        LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_EMP_LVE . "
                            AND docsrpt.doc_ref_type = " . DOC_TYPE_EMP_LVE . "
                            AND docsrpt.doc_ref_id = sts_id
                            AND docsrpt.deleted = 0
                         LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_EMP_LVE . " and files.deleted = 0
             
                        " );
	    
	    $this->_where [] = "sts_id = :sts_id";
	    
	    return parent::fetchRow($cond);
	    
	}
	
	
	
	public function getEmpStatusByEmpId($cond=array()) {
		
		$this->query ( "select *,
				case when sts_type = 1 then 'On Leave' 
				 when sts_type = 2 then 'Re Join' 
				 when sts_type = 3 then 'Resigned' 
				 when sts_type = 4 then 'Terminated' 
				end as emp_mstatus,
				to_char(sts_start_date,'DD/MM/YYYY') as sts_start_date,
				to_char(sts_end_date,'DD/MM/YYYY') as sts_end_date,
				to_char(sts_apply_date,'DD/MM/YYYY') as sts_apply_date,
				to_char(sts_approval_date,'DD/MM/YYYY') as sts_approval_date,
                files.file_id as fileid,
                docsrpt.doc_id as docsid				

				from $this->_table

                LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_EMP_LVE . "
                    AND docsrpt.doc_ref_type = " . DOC_TYPE_EMP_LVE . "
                    AND docsrpt.doc_ref_id = sts_id
                    AND docsrpt.deleted = 0
                 LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_EMP_LVE . " and files.deleted = 0



				" );
		
		if (! empty ( $cond ['sts_emp_id'] ))
			$this->_where [] = "sts_emp_id = :sts_emp_id";

		$this->_order [] = 'sts_id DESC';
			
		return parent::fetchAll($cond);
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getTopSatatusByEmp($cond){
		$this->query (
				"SELECT *
				FROM
				  (SELECT max(sts_id) AS msts_id
				   FROM mis_employee_status
				   WHERE sts_emp_id = :sts_emp_id
					 AND deleted = 0
				   GROUP BY sts_type)max_group
				LEFT JOIN mis_employee_status AS status ON status.sts_id = max_group.msts_id AND status.deleted = 0" );
	
		return parent::fetchQuery($cond);
	}
	
	public function getLeaveSummary($cond){
	    
	    
	    $this->query (
	        'SELECT with_next.sts_emp_id,
    	    (emp_fname || \' \' || emp_mname || \' \' || emp_lname) AS emp_name,
            to_char(with_next.sts_start_date,\'DD/MM/YYYY\') as leave_start,
            to_char(with_next.sts_end_date,\'DD/MM/YYYY\') as leave_end,
            to_char(status.sts_start_date,\'DD/MM/YYYY\') as date_return,
    	    (with_next.sts_end_date::date - with_next.sts_start_date::date) AS days_sanctioned,
    	    (status.sts_start_date::date - with_next.sts_start_date::date) AS days_taken,
            upper(with_next.sts_remarks) as sts_remarks
    	    FROM
    	    (SELECT sts_emp_id,
    	        sts_id,
    	        sts_type,
    	        sts_start_date,
    	        sts_end_date,
                sts_remarks,
    	        lead(sts_id) over (
    	            ORDER BY sts_emp_id ASC, sts_id ASC) AS sts_next
    	        FROM mis_employee_status
    	        WHERE sts_type IN (1,
    	            2)
    	        ORDER BY sts_emp_id ASC, sts_id ASC) with_next
    	        LEFT JOIN mis_employee_status AS status ON with_next.sts_next = status.sts_id AND status.sts_type = 2
    	        LEFT JOIN mis_employee AS emp ON emp.emp_id = with_next.sts_emp_id
    	        WHERE with_next.sts_type = 1
                AND with_next.sts_emp_id =:sts_emp_id');
	    
	    return parent::fetchQuery($cond);
  
	}
	
	public function deleteDocument($id) {
		return parent::delete ( $id );
	}
	
}


