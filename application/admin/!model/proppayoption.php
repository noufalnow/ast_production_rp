<?php
class proppayoption extends db_table {
	protected $_table = "mis_property_payoption";
	protected $_pkey = "popt_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getPayOptionById($id) {
		return parent::getById ($id);
	}
	
	public function getPayOptionDetById($cond) {
		$this->query ( "select $this->_table.*,
					  	build.bld_name,
						doc_no,
						-- agr_tenant,
						doc_issue_date,
					    doc_expiry_date,
						agr_paydet,
						prop_fileno,

					    CASE WHEN popt_type = 1 then 'Cash'
						 when popt_type = 2 then 'Cheque'
						 when popt_type = 3 then 'Not Defined'
						END as popt_type_txt,
				

					    CASE WHEN popt_bank = 1 then 'Bank Muscat'
						 when popt_bank = 2 then 'Bank Dhofar'
						 when popt_bank = 3 then 'NBO'
						 when popt_bank = 4 then 'OAB'
						 when popt_bank = 5 then 'HSBC'
						 when popt_bank = 6 then 'FAB'
 						 when popt_bank = 7 then 'Bank Sohar'
 						 when popt_bank = 8 then 'SBI'
						 when popt_bank = 9 then 'Bank of Baroda'
						 when popt_bank = 10 then 'NBA'

						END || '|' || popt_chqno as popt_bank_det,

				to_char(popt_date,'DD/MM/YYYY') as pay_date,
                tnt_full_name as agr_tenant
				from $this->_table 

				LEFT JOIN mis_property AS prop ON prop.prop_id = $this->_table.popt_prop_id and prop.deleted = 0
				LEFT JOIN mis_building AS build ON build.bld_id = prop.prop_building and build.deleted = 0
				INNER JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
						  agr_paydet,
				          doc_remarks,
				          -- agr_tenant,
						  agr_mobile,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month,
                          agr_tnt_id  
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_ref_type = " . DOC_TYPE_PROP . " and doc_type = 201
						AND deleted = 0
						GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
						LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
						AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = prop.prop_id
                        left join mis_tenants as tenants on tenants.tnt_id = propdocs.agr_tnt_id and tenants.deleted = 0

				");
		
		$this->_where [] = "popt_id= :popt_id";
		return parent::fetchRow ( $cond );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}	
	
	public function deletePayOptionByID($id) {
		return parent::delete ( $id );
	}
		
	public function deletePayOpByDocAndPropId($cond=array()){
		$this->_where [] = "popt_doc_id= :popt_doc_id";
		$this->_where [] = "popt_prop_id= :popt_prop_id";
		return parent::deleteByCond( $cond);
	}
	
	public function getPayKeyPairByDocAndProperty($cond = array()) {
		$this->query ( "select popt_id,popt_id from $this->_table" );
		
		$this->_where [] = "popt_prop_id= :popt_prop_id";
		$this->_where [] = "popt_doc_id= :popt_doc_id";
		
		$this->_order [] = 'popt_id ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getPayKeyPairByDocAndPropertyDemand($cond = array()) {
	    $this->query ( "select popt_id,cdmd_id
					from $this->_table
                    INNER JOIN mis_cash_demand AS dmd ON popt_id = dmd.cdmd_ref_id
                        AND dmd.deleted = 0
                    INNER JOIN mis_collection_det AS colldet ON colldet.cdet_bill_id = dmd.cdmd_id AND 
                        colldet.cdet_src_type = 2 and colldet.cdet_status = 2 and colldet.deleted = 0
                    " );
	    
	    $this->_where [] = "popt_prop_id= :popt_prop_id";
	    $this->_where [] = "popt_doc_id= :popt_doc_id";
	    
	    $this->_order [] = 'popt_id ASC';
	    
	    return parent::fetchPair ( $cond );
	}
	
	
	public function getPayOptiosByDocAndProperty($cond = array()) {
		
		$this->query ( "select $this->_table.*,
					to_char(popt_date,'DD/MM/YYYY') as pay_date,
                    cdmd_id,
                    cdet_id
					from $this->_table 
                    LEFT JOIN mis_cash_demand AS dmd ON popt_id = dmd.cdmd_ref_id
                        AND dmd.deleted = 0
                    LEFT JOIN mis_collection_det AS colldet ON colldet.cdet_bill_id = dmd.cdmd_id AND 
                        colldet.cdet_src_type = 2 and colldet.cdet_status = 2 and colldet.deleted = 0" );
		
		$this->_where [] = "popt_prop_id= :popt_prop_id";
		$this->_where [] = "popt_doc_id= :popt_doc_id";

		
		$this->_order [] = 'popt_id ASC';
		
		
		return parent::fetchAll ( $cond );
	}
	
	public function getTenantPayments($cond = array()) {

	    $this->query ( "SELECT popt_amount,
                               (cdmd_total-cdmd_credit_amt) AS collected,
                               popt_doc_id,
                               to_char(cdmd_date,'DD/MM/YYYY') as cdmd_date
                        FROM mis_property_payoption
                        LEFT JOIN mis_cash_demand AS dmd ON popt_id = dmd.cdmd_ref_id
                        AND dmd.deleted = 0" );
	    
	    $this->_where [] = "popt_doc_id = :popt_doc_id";
	    
	    $this->_order [] = 'popt_id DESC';
	    
	    return parent::fetchAll ( $cond );
	}
	
}


