<?php
class legalcase extends db_table {
    protected $_table = "mis_legal_case";
    protected $_pkey = "lcas_id";
    
    protected function getStatusString($status_field)
    {
        $stsstring = " CASE
                    WHEN $status_field = 1 THEN 'OPEN - DRAFT'
                    WHEN $status_field = 2 THEN 'OPEN - SCHEDULED FOR HEARING'
                    WHEN $status_field = 3 THEN 'OPEN - UNDER REVIEW'
                    WHEN $status_field = 4 THEN 'OPEN - RESOLVED'
                    WHEN $status_field = 5 THEN 'PENDING'
                    WHEN $status_field = 6 THEN 'CLOSED'
                    WHEN $status_field = 7 THEN 'DISMISSED'
                    WHEN $status_field = 8 THEN 'APPEAL FILED'
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

    public function getLegalCasePair($cond = array()) {
        $this->query("select lcas_id, lcas_phone_no || ' - ' || lcas_name || ' (' || lcas_date || ' ' || lcas_time || ')'
                      from $this->_table");
        
        if (!empty($cond['lcas_type']))
            $this->_where[] = "lcas_type = :lcas_type";
        
        $this->_order[] = 'lcas_date DESC';
        
        return parent::fetchPair($cond);
    }

    public function getLegalCasePaginate($cond) {
        $this->paginate("select $this->_table.*, 
                            LEFT(lcas_case, 30) || '...' as lcas_case_trim,
                            case 
                                when lcas_type = 1 then 'RENT DISPUTE'
                                when lcas_type = 2 then 'CHECK RETURN'
                                when lcas_type = 3 then 'ACCIDENT'
                                when lcas_type = 4 then 'OTHERS'
                            end as lcas_type_label,
                            ".$this->getStatusString('lcas_sts')." AS lcas_sts_lbl,
                            to_char(lcas_date,'DD/MM/YYYY') as lcas_date_lbl,
                            emp_fname ||' '||emp_mname||' '||emp_lname as emp_name,

                            files.file_id as fileid

                        ", "from $this->_table

        				LEFT JOIN core_users AS users ON users.user_id = lcas_emp
                        LEFT JOIN mis_employee AS emp ON emp.emp_id = users.user_emp_id	AND users.deleted = 0

                        LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_COM_LCASE . "
                            AND docsrpt.doc_ref_type = " . DOC_TYPE_COM_LCASE . "
                            AND docsrpt.doc_ref_id = lcas_id
                            AND docsrpt.deleted = 0
                         LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_COM_LCASE . " and files.deleted = 0

                        ");

        if (!empty($cond['f_lcas_type']))
            $this->_where[] = "lcas_type = :f_lcas_type";
        
        if (!empty($cond['f_name']))
            $this->_where[] = "LOWER(lcas_party) LIKE '%' || LOWER(:f_name) || '%'";
        
        if (!empty($cond['f_lawer_name']))
            $this->_where[] = "LOWER(lcas_lawer) LIKE '%' || LOWER(:f_lawer_name) || '%'";
        
        if (!empty($cond['f_status']))
            $this->_where[] = "lcas_sts = :f_status";
        
        if (!empty($cond['f_date']))
            $this->_where[] = "lcas_date = TO_DATE(:f_date, 'DD/MM/YYYY')";
    
        if (! empty($cond['f_month'])) {
            $monthYear = explode('/', $cond['f_month']);
            $this->_where[] = " (EXTRACT(month FROM lcas_date) = '$monthYear[0]' AND EXTRACT(year FROM lcas_date) = '$monthYear[1]' )";
            unset($cond['f_month']);
        }

        $this->_order[] = 'lcas_date DESC';
        
        return parent::fetchAll($cond);
    }

    public function getLegalCaseDet($cond) {
        $this->query("select $this->_table.*
                      ", "from $this->_table");
        
        if (!empty($cond['lcas_id']))
            $this->_where[] = "lcas_id = :lcas_id";

        return parent::fetchRow($cond);
    }

    public function getLegalCaseById($id) {
        return parent::getById($id);
    }
    
    
    public function getLegalCaseDetailsById($cond)
    {
        $this->query("select $this->_table.*,
                        files.file_id as fileid,
                        docsrpt.doc_id as docsid
        FROM $this->_table
        LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_COM_LCASE . "
            AND docsrpt.doc_ref_type = " . DOC_TYPE_COM_LCASE . "
            AND docsrpt.doc_ref_id = lcas_id
            AND docsrpt.deleted = 0
         LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_COM_LCASE . " and files.deleted = 0");
        
        $this->_where[] = "lcas_id = :lcas_id";
        
        return parent::fetchRow($cond);
    }

    public function deleteLegalCase($id) {
        return parent::delete($id);
    }
}
