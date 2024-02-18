<?php
class vendor extends db_table {
	protected $_table = "mis_vendor";
	protected $_pkey = "ven_id";
	
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	

	public function getVendorPair($cond = array()) {
		$this->query ( "select ven_id,ven_name from $this->_table ");
		$this->_where [] = "ven_id IN (SELECT DISTINCT exp_vendor
								FROM mis_expense
								WHERE exp_pay_mode = 2
								and deleted = 0)
								";
		$this->_order [] = 'ven_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getVendorPairFilter($cond = array()) {
		$this->query ( "select ven_id,ven_name from $this->_table ");
		$this->_order [] = 'ven_name ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getVendorByName($cond = array()){
		$this->query ( "select * from $this->_table" );
		if (isset ( $cond ['ven_name'] ))
			$this->_where [] = "ven_name= :ven_name";
		
		return parent::fetchRow ( $cond );
	}
	
	
	public function getVendorDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.*
				from $this->_table
				left join mis_contacts as contact on contact.con_ref_id = $this->_table.ven_id and contact.con_ref_type = ".CONT_TYPE_VENDR." and contact.con_type  = 5 and contact.deleted = 0
				" );
		if (! empty ( $cond ['ven_id'] ))
			$this->_where [] = "ven_id= :ven_id";
			
			return parent::fetchRow ( $cond );
	}
	
	public function getVendorPaginate($cond){
				
		$this->paginate ( "select $this->_table.* ,
				contact.*
				", "from $this->_table
				left join mis_contacts as contact on contact.con_ref_id = $this->_table.ven_id and contact.con_ref_type = " . CONT_TYPE_VENDR . " and contact.con_type  = 5 and contact.deleted = 0
				" );
		
		if (! empty ( $cond ['f_code'] ))
			$this->_where [] = " cast(ven_id AS text)  like '%' || :f_code || '%'";
		
		if (! empty ( $cond ['f_vendor'] ))
			$this->_where [] = "lower(ven_name) like '%' || lower(:f_vendor) || '%'";
		
		if (! empty ( $cond ['f_name'] ))
			$this->_where [] = "con_name like '%' || :f_name || '%'";
		
		if (! empty ( $cond ['f_house'] ))
			$this->_where [] = "con_house like '%' || :f_house || '%'";
		
		$this->_order [] = 'ven_name ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getBillByVendor($cond=array()){
		
		$this->query (
				"SELECT mis_vendor.ven_name,
				       con_phone,
				       con_mobile,
				       credit_amt,
						to_char(dmin,'DD-MM-YYYY') as dmin,
						to_char(dmax,'DD-MM-YYYY') as dmax,
				       ven_id
				FROM mis_vendor
				LEFT JOIN
				  (SELECT SUM(exp_credit_amt) AS credit_amt,
				          exp_vendor
				   FROM mis_expense
					WHERE mis_expense.exp_app_status = 1 AND mis_expense.exp_pstatus  <>1
				     AND mis_expense.deleted = 0
				   GROUP BY exp_vendor) AS exp_sum ON exp_sum.exp_vendor = mis_vendor.ven_id
				LEFT JOIN
				  (SELECT min(exp_billdt) AS dmin,
				          exp_vendor
				   FROM mis_expense
					WHERE mis_expense.exp_app_status = 1 AND mis_expense.exp_pstatus  <>1
				     AND mis_expense.deleted = 0
				   GROUP BY exp_vendor) AS exp_min_dt ON exp_min_dt.exp_vendor = mis_vendor.ven_id
				LEFT JOIN
				  (SELECT max(exp_billdt) AS dmax,
				          exp_vendor
				   FROM mis_expense
					WHERE mis_expense.exp_app_status = 1 AND mis_expense.exp_pstatus  <>1
				     AND mis_expense.deleted = 0
				   GROUP BY exp_vendor) AS exp_max_dt ON exp_max_dt.exp_vendor = mis_vendor.ven_id

				LEFT JOIN mis_contacts AS contact ON contact.con_ref_id = mis_vendor.ven_id and contact.deleted = 0
				AND contact.con_ref_type = 5
				AND contact.con_type = 5
				AND contact.deleted = 0

				WHERE mis_vendor.deleted = 0
				  AND credit_amt > 0
				order by credit_amt ASC
				" );
		
		return parent::fetchQuery($cond);
		
	}

}


