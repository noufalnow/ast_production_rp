<?php
class collectiondet extends db_table {
	protected $_table = "mis_collection_det";
	protected $_pkey = "cdet_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		
		$this->_where [] = "cdet_coll_id= :cdet_coll_id";
		
		return parent::update ( $data, $cond );
	}
	
	public function getCollectionExpDet($cond) {
		$this->query ( "select $this->_table.* ,
				bill.*
				from $this->_table
				left join mis_bill as bill on bill.bill_id = $this->_table.cdet_bill_id and bill.deleted = 0 and bill.bill_cancellation_status = 0
				" );

		$this->_where [] = "cdet_coll_id= :cdet_coll_id";
			
		return parent::fetchAll( $cond );
	}
	
	public function getCollectionDetByApproval($cond) {
		$this->query ( "select $this->_table.cdet_id,cdet_bill_id
				from $this->_table
				" );
			$this->_where [] = "cdet_status= :cdet_status";
			
			if (! empty ( $cond ['cdet_coll_id_exclude'] ))
				$this->_where [] = "cdet_coll_id <> :cdet_coll_id_exclude";
			
			return parent::fetchPair( $cond );
	}
	
	
	public function getAllCollectionDetForExp($cond) {
		$this->query ( "select 
				to_char(coll_paydate,'DD-MM-YYYY') as coll_paydate,
				cdet_amt_topay,	cdet_amt_paid,	cdet_amt_dis,	cdet_amt_bal, coll_remarks,
				file_id,
				file_actual_name ||'.'||file_exten as file_label
				from  
				$this->_table
				left join mis_collection as coll on coll.coll_id = cdet_coll_id and coll.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = coll.coll_id and files.deleted = 0 AND files.file_type =".DOC_TYPE_PAY );
		
		$this->_where [] = "cdet_status= :cdet_status";
		$this->_where [] = "cdet_bill_id= :cdet_bill_id";
		
		$this->_order [] = 'coll_id DESC';
				
		return parent::fetchAll( $cond );
	}
		
	
	public function getCollectionDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.* 
				from $this->_table 
				" );
		if (! empty ( $cond ['cdet_id'] ))
			$this->_where [] = "cdet_id= :cdet_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getCollectionDetById($id){
		return parent::getById ($id);
	}
	
	public function getCollectionById($id){
		return parent::getById ($id);
	}
	
	public function deleteCollection($id) {
		return parent::delete ( $id );
	}
	
	public function deleteCollDetByExpId($cond=array()) {
		
		$this->_where [] = "cdet_coll_id= :cdet_coll_id";
		return parent::deleteByCond( $cond);
	}
	
}


