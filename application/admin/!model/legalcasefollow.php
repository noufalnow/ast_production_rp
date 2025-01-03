<?php

class legalcasefollow extends db_table
{

    protected $_table = "mis_legal_case_follow";

    protected $_pkey = "lcflo_id";
    
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
        $this->query("SELECT lcflo_id, lcflo_log || ' (' || lcflo_date || ' - ' || lcflo_time || ')' FROM $this->_table");

        if (! empty($cond['lcflo_lcas_id'])) {
            $this->_where[] = "lcflo_lcas_id = :lcflo_lcas_id";
        }

        $this->_order[] = 'lcflo_date DESC, lcflo_time DESC';
        return parent::fetchPair($cond);
    }

    public function getFollowsAll($cond)
    {
        $this->query("SELECT $this->_table.*,

                            ".$this->getStatusString('lcflo_sts')." AS lcflo_sts_lbl,
                            to_char(lcflo_date,'DD/MM/YYYY') as lcflo_date_lbl,
                            emp_fname ||' '||emp_mname||' '||emp_lname as emp_name,

                    files.file_id as fileid

                    FROM $this->_table
        			LEFT JOIN core_users AS users ON users.user_id = lcflo_emp
                    LEFT JOIN mis_employee AS emp ON emp.emp_id = users.user_emp_id

                    LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_COM_LCASE_UPD . "
                        AND docsrpt.doc_ref_type = " . DOC_TYPE_COM_LCASE_UPD . "
                        AND docsrpt.doc_ref_id = lcflo_id
                        AND docsrpt.deleted = 0
                     LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_COM_LCASE_UPD . " and files.deleted = 0

                    ");

        $this->_where[] = "lcflo_lcas_id = :lcflo_lcas_id";

        $this->_order[] = 'lcflo_date DESC';
        return parent::fetchAll($cond);
    }

    public function getFollowDetails($cond)
    {
        $this->query("SELECT $this->_table.*,files.file_id as fileid,
                        docsrpt.doc_id as docsid
        FROM $this->_table
        LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_COM_LCASE_UPD . "
            AND docsrpt.doc_ref_type = " . DOC_TYPE_COM_LCASE_UPD . "
            AND docsrpt.doc_ref_id = lcflo_id
            AND docsrpt.deleted = 0
         LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_COM_LCASE_UPD . " and files.deleted = 0");
        
        $this->_where[] = "lcflo_id = :lcflo_id";
        
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
