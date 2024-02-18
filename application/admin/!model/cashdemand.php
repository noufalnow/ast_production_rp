<?php
class cashdemand extends db_table {
	protected $_table = "mis_cash_demand";
	protected $_pkey = "cdmd_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getContactById($id) {
		return parent::getById ($id);
	}
	
	public function deleteContact($id) {
		return parent::delete ( $id );
	}
	
	
	
	public function getPendingDemandList($cond) {
		if (! empty ( $cond ['cdet_coll_id'] ))
			$where [] = "((colldet.cdet_coll_id = :cdet_coll_id) OR (colldet.cdet_coll_id IS NULL))";
		
		$where [] = "((colldet.cdet_src_type = :cdet_src_type) OR (colldet.cdet_src_type IS NULL))";
		
		if (! empty ( $cond ['cdmd_pstatus'] ))
			$where [] = " $this->_table.cdmd_pstatus = :cdmd_pstatus ";
		
		$where [] = " ((cdet_id NOT IN
					    (SELECT cdet_id
					     FROM mis_collection_det
					     WHERE deleted=0
					       AND cdet_status = 1
					       AND cdet_src_type = 2 ".(empty ( $cond ['cdet_coll_id']) ? " " : " AND cdet_coll_id <> :cdet_coll_id ")." )) OR (cdet_id IS NULL)) ";
		
		$where [] = " $this->_table.deleted = 0 ";
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "select $this->_table.*,
				colldet.*				from $this->_table
				left join mis_collection_det as colldet on colldet.cdet_bill_id = $this->_table.cdmd_id and colldet.deleted = 0
				and cdet_src_type=:cdet_src_type
				$where
				" );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getPendingDemandIdList($cond) {
		if (! empty ( $cond ['cdet_coll_id'] ))
			$where [] = "((colldet.cdet_coll_id = :cdet_coll_id) OR (colldet.cdet_coll_id IS NULL))";
		
		$where [] = "((colldet.cdet_src_type = :cdet_src_type) OR (colldet.cdet_src_type IS NULL))";

		if (! empty ( $cond ['cdmd_pstatus'] ))
			$where [] = " $this->_table.cdmd_pstatus = :cdmd_pstatus ";
		
		$where [] = " ((cdet_id NOT IN
					    (SELECT cdet_id
					     FROM mis_collection_det
					     WHERE deleted=0
					       AND cdet_status = 1
					       AND cdet_src_type = 2 ".(empty ( $cond ['cdet_coll_id']) ? " " : " AND cdet_coll_id <> :cdet_coll_id ")." )) OR (cdet_id IS NULL)) ";
		
		$where [] = " $this->_table.deleted = 0 ";
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "select $this->_table.cdmd_id,cdmd_id
				from $this->_table
				left join mis_collection_det as colldet on colldet.cdet_bill_id = $this->_table.cdmd_id and colldet.deleted = 0
				and cdet_src_type=:cdet_src_type
				$where
				" );
		
		return parent::fetchQueryPair ( $cond );
	}
	
	public function getPendingDemandAmountList($cond) {
		if (! empty ( $cond ['cdet_coll_id'] ))
			$where [] = "((colldet.cdet_coll_id = :cdet_coll_id) OR (colldet.cdet_coll_id IS NULL))";
		
		$where [] = "((colldet.cdet_src_type = :cdet_src_type) OR (colldet.cdet_src_type IS NULL))";
		
		if (! empty ( $cond ['cdmd_pstatus'] ))
			$where [] = " $this->_table.cdmd_pstatus = :cdmd_pstatus ";
			
		$where [] = " ((cdet_id NOT IN
					    (SELECT cdet_id
					     FROM mis_collection_det
					     WHERE deleted=0
					       AND cdet_status = 1
					       AND cdet_src_type = 2 ".(empty ( $cond ['cdet_coll_id']) ? " " : " AND cdet_coll_id <> :cdet_coll_id ")." )) OR (cdet_id IS NULL)) ";
		
		$where [] = " $this->_table.deleted = 0 ";
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "select $this->_table.cdmd_id,cdmd_credit_amt
				from $this->_table
				left join mis_collection_det as colldet on colldet.cdet_bill_id = $this->_table.cdmd_id and colldet.deleted = 0
				and cdet_src_type=:cdet_src_type
				$where
				" );
		
		return parent::fetchQueryPair ( $cond );
	}
	
	public function getContactByRefId($cond) {
		$this->query ( "select $this->_table.*
				from $this->_table
				" );
		$this->_where [] = "cdmd_type	= :cdmd_type";
		$this->_where [] = "cdmd_ref_id	= :cdmd_ref_id";
		
		return parent::fetchRow ( $cond );
	}

	
}


