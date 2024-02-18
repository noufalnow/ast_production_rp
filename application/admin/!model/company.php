<?php
class company extends db_table {
	protected $_table = "core_company";
	protected $_pkey = "comp_id";
	

	public function getCompanyPair($cond = array()) {
		$this->query ( "select comp_id,comp_disp_name from $this->_table" );
		$this->_order [] = 'comp_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getCompanyList($cond = array()) {
		
		$this->paginate ( "select *,
				file_actual_name||'.'||file_exten as file_name,
				case 
				when doc_type = 1 then 'CR CERTIFICATE'
				when doc_type = 2 then 'CR ID CARD'
				when doc_type = 3 then 'SIGNATORY'
				when doc_type = 4 then 'ID CARD 1'
				when doc_type = 5 then 'ID CARD 2'

				when doc_type = 61 then 'PINK CERTIFICATE 1'
				when doc_type = 62 then 'PINK CERTIFICATE 2'
				when doc_type = 63 then 'PINK CERTIFICATE 3'
				when doc_type = 64 then 'PINK CERTIFICATE 4'
				when doc_type = 65 then 'PINK CERTIFICATE 5'

				end as doc_type
				"				
				, "from $this->_table
				LEFT JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
				          doc_remarks,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_ref_type = " . DOC_TYPE_COMP . "
						AND deleted = 0
						GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
						LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
						AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = core_company.comp_id
				LEFT JOIN core_files as files on files.file_ref_id = propdocs.doc_id and files.deleted = 0 AND files.file_type IN(" . DOC_TYPE_COMP . ")

			" );
		
		$this->_order [] = 'comp_name ASC';
		
		return parent::fetchAll ( $cond );
	}

}


