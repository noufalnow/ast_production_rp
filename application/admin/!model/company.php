<?php
class company extends db_table {
	protected $_table = "core_company";
	protected $_pkey = "comp_id";
	

	public function getCompanyPair($cond = array()) {
		$this->query ( "select comp_id,comp_disp_name from $this->_table" );
		$this->_order [] = 'comp_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getCompanyNamePair($cond = array()) {
	    $this->query ( "select comp_id,comp_name || ' [' || comp_disp_name || ']'  from $this->_table" );
	    $this->_order [] = 'comp_name ASC';
	    
	    return parent::fetchPair ( $cond );
	}
	
	public function getCompanyList($cond = array()) {
	    
	    
	    $this->_pager = ' WITH latestdocuments AS
  ( SELECT wdoc.doc_dyn_ver,
           max(wdoc.doc_id) AS latest_doc_id
   FROM mis_documents wdoc
   WHERE wdoc.doc_dyn_ver IS NOT NULL
   GROUP BY wdoc.doc_dyn_ver )

SELECT COUNT(*) AS count
FROM core_company
LEFT JOIN
  (SELECT *
   FROM mis_documents docs
   WHERE ( (docs.doc_id IN ( SELECT latestdocuments.latest_doc_id
           FROM latestdocuments)) OR ( (docs.doc_dyn_ver IS NULL) AND (NOT (docs.doc_id IN ( SELECT latestdocuments.doc_dyn_ver
           FROM latestdocuments)))))
     AND docs.doc_ref_type = 1
     AND docs.deleted = 0) AS vdocs ON vdocs.doc_ref_id = core_company.comp_id
LEFT JOIN core_files AS files ON files.file_ref_id = vdocs.doc_id
AND files.deleted = 0
AND files.file_type IN (1)
WHERE core_company.deleted = 0;
';
	    
		
                       $this->query ( " WITH latestdocuments AS
                          ( SELECT wdoc.doc_dyn_ver,
                                   max(wdoc.doc_id) AS latest_doc_id
                           FROM mis_documents wdoc
                           WHERE wdoc.doc_dyn_ver IS NOT NULL
                           GROUP BY wdoc.doc_dyn_ver )
                            SELECT *,
                           file_actual_name || '.' || file_exten AS file_name,
                                                      doc_id,
                                                      doc_type,
                                                      doc_ref_type,
                                                      doc_ref_id,
                                                      doc_no,
                                                      doc_desc,
                                                      doc_remarks,
                                                      to_char(doc_apply_date, 'DD/MM/YYYY') AS doc_apply_date,
                                                      to_char(doc_issue_date, 'DD/MM/YYYY') AS doc_issue_date,
                                                      to_char(doc_expiry_date, 'DD/MM/YYYY') AS doc_expiry_date,
                                                      doc_expiry_date AS doc_expiry_month,
                                                      doc_dyn_label,
                                                      doc_dyn_no,
                                                      doc_dyn_ver
                            FROM core_company
                            LEFT JOIN
                              (SELECT *
                               FROM mis_documents docs
                               WHERE ( (docs.doc_id IN ( SELECT latestdocuments.latest_doc_id
                                       FROM latestdocuments)) OR ( (docs.doc_dyn_ver IS NULL) AND (NOT (docs.doc_id IN ( SELECT latestdocuments.doc_dyn_ver
                                       FROM latestdocuments)))))
                                 AND docs.doc_ref_type = 1
                                 AND docs.deleted = 0) AS vdocs ON vdocs.doc_ref_id = core_company.comp_id
                            LEFT JOIN core_files AS files ON files.file_ref_id = vdocs.doc_id
                            AND files.deleted = 0
                            AND files.file_type IN(1)
                            WHERE core_company.deleted = 0
                            " );
		
		$this->_order [] = 'comp_name ASC';
		
		//return parent::fetchAll ( $cond );
		return parent::fetchQueryPaginate ( $cond ," ORDER BY comp_name ASC ");
	}

}


