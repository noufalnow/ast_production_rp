<?php
require_once 'files.php';
class documets extends db_table {
	protected $_table = "mis_documents";
	protected $_pkey = "doc_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	public function getDocumentById($id) {
		return parent::getById ($id);
	}
	
	public function getDocuments($cond = array()) {
	
		$this->query ( "select *,
                tnt_full_name as agr_tenant,
                tnt_phone as agr_mobile,
                payments.collected,
                payments.to_collect
                from $this->_table 
                left join mis_tenants as tenants on tenants.tnt_id = agr_tnt_id and tenants.deleted = 0
				left join core_files as files on files.file_ref_id = $this->_table.doc_id and files.deleted = 0
		        left join ( SELECT ROUND(SUM(popt_amount)) AS to_collect,
                     ROUND(SUM((cdmd_total-cdmd_credit_amt))) AS collected,
                     payop.popt_doc_id
                FROM mis_property_payoption AS payop
                LEFT JOIN mis_cash_demand AS dmd ON payop.popt_id = dmd.cdmd_ref_id and dmd.deleted = 0
                WHERE payop.deleted = 0 
                GROUP BY popt_doc_id ) payments ON  payments.popt_doc_id = $this->_table.doc_id"
		    
		    );
		
		$this->_where [] = "doc_type= :doc_type";
		$this->_where [] = "doc_ref_type= :doc_ref_type";
		
		if (! empty ( $cond ['doc_ref_id'] ))
		  $this->_where [] = "doc_ref_id= :doc_ref_id";
		
	  if (! empty ( $cond ['agr_tnt_id'] ))
	      $this->_where [] = "agr_tnt_id= :agr_tnt_id";
		
		$this->_where [] = "file_type= :doc_ref_type";
		
		$this->_order [] = 'doc_id DESC';

	
		return parent::fetchAll ( $cond );
	}
	
	
	public function getCommonDocuments($cond = array()) {
		
		$this->query ( "select *,
						to_char(doc_issue_date,'DD/MM/YYYY') as start_date,
						to_char(doc_expiry_date,'DD/MM/YYYY') as end_date
						from $this->_table
				left join core_files as files on files.file_ref_id = $this->_table.doc_id and files.deleted = 0" );
		
		$cond['doc_ref_type'] = DOC_TYPE_COM;
		$this->_where [] = "doc_ref_type= :doc_ref_type";
		
		$this->_where [] = "doc_ref_id= :doc_ref_id";
		$this->_where [] = "doc_type= :doc_type";
		$this->_where [] = "file_type= :doc_type";
		
		
		$this->_order [] = 'doc_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	
	public function getDocumentsByType($cond = array()) {
		
		$this->query ( "select * from $this->_table " );
		
		$this->_where [] = "doc_type = :doc_type";
		$this->_where [] = "doc_ref_type = :doc_ref_type";
		$this->_where [] = "doc_ref_id = :doc_ref_id";
		
		if (! empty ( $cond ['doc_id_exclude'] ))
			$this->_where [] = "doc_id <> :doc_id_exclude";

		$this->_order [] = 'doc_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getDocumentDetails($cond = array()) {
	
		$this->query ( "select *,
				files.file_id
				from $this->_table
				left join core_files as files on files.file_ref_id = $this->_table.doc_id and files.deleted = 0" );
	
		$this->_where [] = "doc_id= :doc_id";	
		$this->_where [] = "files.file_type= :doc_ref_type"; //error fix delete file withouout type 
		$this->_order [] = 'doc_id DESC';

		return parent::fetchRow( $cond );
	}
	

	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	
	public function getTopDocumentsByRef($cond){
	
		$this->query (
				"SELECT *,
                tnt_full_name as agr_tenant
				FROM
				  (SELECT max(doc_id) AS mdoc_id
				   FROM mis_documents
				   WHERE doc_ref_type = :doc_ref_type
				     AND doc_ref_id = :doc_ref_id
					 AND deleted = 0
				   GROUP BY doc_type)max_group
				LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id AND docs.deleted = 0
				left join mis_tenants as tenants on tenants.tnt_id = docs.agr_tnt_id and tenants.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = docs.doc_id and files.file_type= :doc_ref_type and files.deleted = 0
				order by doc_type asc, file_id ASC" );
	
		//v(parent::dbug($cond));
		return parent::fetchQuery($cond);
	}
	
	public function deleteDocument($id) {
		return parent::delete ( $id );
	}
	
	
	public function getDocumentsPair($cond = array()) {
		
		$this->query ( "
				SELECT doc_id , concat_ws(' - ',doc_no, doc_remarks, to_char(doc_issue_date,'DD/MM/YYYY'), to_char(doc_expiry_date,'DD/MM/YYYY') ) AS doc_det
				from $this->_table
				" );
		
		$this->_where [] = "doc_type= :doc_type";
		$this->_where [] = "doc_ref_type= :doc_ref_type";
		
		if (! empty ( $cond ['doc_ref_id'] ))
			$this->_where [] = "doc_ref_id = :doc_ref_id";
		
		$this->_order [] = 'doc_id DESC';
		
		return parent::fetchPair( $cond );
	}
	

	
}


