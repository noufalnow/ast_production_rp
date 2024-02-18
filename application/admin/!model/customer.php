<?php
class customer extends db_table {
	protected $_table = "mis_customer";
	protected $_pkey = "cust_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getCustomerPair($cond = array()) {
		$this->query ( "select cust_id,cust_name from $this->_table" );
		$this->_order [] = 'cust_name ASC';
	
		return parent::fetchPair ( $cond );
	}
	
	
	
	public function getCustomerPaginate($cond){
		
		$this->paginate ( "select $this->_table.* ,
				contact.* 
				", "from $this->_table 
				left join mis_contacts as contact on contact.con_ref_id = $this->_table.cust_id and contact.con_ref_type = ".CONT_TYPE_CUST." and contact.con_type  = 4 and contact.deleted = 0
				" );
		
		if (!empty ( $cond ['f_code'] ))
			$this->_where [] = " cast(cust_id AS text)  like '%' || :f_code || '%'";
		
		if (!empty ( $cond ['f_customer'] ))
			$this->_where [] = "lower(cust_name) like '%' || lower(:f_customer) || '%'";
		
		if (!empty ( $cond ['f_name'] ))
			$this->_where [] = "lower(con_name) like '%' || lower(:f_name) || '%'";
		
		if (!empty ( $cond ['f_house'] ))
			$this->_where [] = "con_house like '%' || :f_house || '%'";
			
		$this->_order [] = 'cust_name ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getCustomerDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.* 
				from $this->_table 
				left join mis_contacts as contact on contact.con_ref_id = $this->_table.cust_id and contact.con_ref_type = ".CONT_TYPE_CUST." and contact.con_type  = 4 and contact.deleted = 0
				
				" );
		if (! empty ( $cond ['cust_id'] ))
			$this->_where [] = "cust_id= :cust_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getCustomerDetById($id){
		return parent::getById ($id);
	}
	
	public function getCustomerById($id){
		return parent::getById ($id);
	}
	
	public function deleteCustomer($id) {
		return parent::delete ( $id );
	}
	
	public function getBillByCustomer($cond=array()){
		
		$this->query (
				"SELECT mis_customer.cust_name,
				       con_phone,
				       con_mobile,
				       credit_amt,
						to_char(dmin,'DD-MM-YYYY') as dmin, 
						to_char(dmax,'DD-MM-YYYY') as dmax, 
				       cust_id
				FROM mis_customer
				LEFT JOIN
				  (SELECT SUM(bill_credit_amt) AS credit_amt,
				          bill_customer_id
				   FROM mis_bill
					WHERE mis_bill.bill_app_status = 1 AND mis_bill.bill_pstatus = 2
				     AND mis_bill.deleted = 0 and mis_bill.bill_cancellation_status = 0
				   GROUP BY bill_customer_id) AS bill_sum ON bill_sum.bill_customer_id = mis_customer.cust_id
				LEFT JOIN
				  (SELECT min(bill_date) AS dmin,
				          bill_customer_id
				   FROM mis_bill
					WHERE mis_bill.bill_app_status = 1 AND mis_bill.bill_pstatus = 2
				     AND mis_bill.deleted = 0 and mis_bill.bill_cancellation_status = 0
				   GROUP BY bill_customer_id) AS bill_min_dt ON bill_min_dt.bill_customer_id = mis_customer.cust_id
				LEFT JOIN
				  (SELECT max(bill_date) AS dmax,
				          bill_customer_id
				   FROM mis_bill
					WHERE mis_bill.bill_app_status = 1 AND mis_bill.bill_pstatus = 2
				     AND mis_bill.deleted = 0 and mis_bill.bill_cancellation_status = 0
				   GROUP BY bill_customer_id) AS bill_max_dt ON bill_max_dt.bill_customer_id = mis_customer.cust_id
				LEFT JOIN mis_contacts AS contact ON contact.con_ref_id = mis_customer.cust_id and contact.deleted = 0
				AND contact.con_ref_type = 4
				AND contact.con_type = 4
				AND contact.deleted = 0
				WHERE mis_customer.deleted = 0
				  AND credit_amt > 0
				order by cust_name ASC
				" );
				
				return parent::fetchQuery($cond);
		
	}
	
}


