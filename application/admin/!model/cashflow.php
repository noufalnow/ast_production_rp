<?php
class cashflow extends db_table {
	protected $_table = "mis_cash_flow";
	protected $_pkey = "cf_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getCashFlowById($id) {
		return parent::getById ($id);
	}
	
	public function getCashFlowPair($cond = array()) {
		$this->query ( "select cf_id, concat_ws(' - ', to_char(cf_dttime,'DD/MM/YYYY'), emp_fname, cf_note, cf_amount) as cash_flow
					 from $this->_table
					 left join mis_employee as emp on $this->_table.cf_assigned = emp.emp_id and emp.deleted = 0
					 " );
		$this->_order [] = 'cf_id DESC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getCashFlowKeyPair($cond = array()) {
		$this->query ( "select cf_id, cf_id
						from $this->_table " );
		$this->_where [] = "cf_cb_id= :cf_cb_id";
		$this->_order [] = 'cf_id ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getApprovedKeyPair($cond = array()) {
		
		$cond['cf_approve'] = 2;
		
		$this->query ( "select cf_id, cf_id
				from $this->_table " );
		$this->_where [] = "cf_cb_id= :cf_cb_id";
		$this->_where [] = "cf_approve= :cf_approve";
		$this->_order [] = 'cf_id ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}	
	
	public function deleteDocument($id) {
		return parent::delete ( $id );
	}
		
	public function deleteByCashBook($cond=array()){
		$this->_where [] = "cf_cb_id= :cf_cb_id";
		return parent::deleteByCond( $cond);
	}
	
	public function getCashFlowByCasbookId($cond = array()) {
		
		$this->query ( "select $this->_table.*,
						progress.*,
						round(cf_amount,3) as cf_amount, 
						round(coalesce((pro_amount/cf_amount)*100,0),2) as pro_percentage,
						to_char(cf_dttime,'DD/MM/YYYY') as cdet_dip_dt,
						emp_fname as emp_name
						from $this->_table 
						left join mis_employee as emp on $this->_table.cf_assigned = emp.emp_id and emp.deleted = 0
						LEFT JOIN (
						SELECT ccf_id ,
						       SUM(f_sum) as pro_amount
						FROM
						  (SELECT exp_cash_flow AS ccf_id,
						          SUM(exp_amount) AS f_sum
						   FROM mis_expense
						   WHERE deleted = 0
						   GROUP BY exp_cash_flow
						   UNION ALL SELECT pay_cash_flow AS ccf_id,
						                    SUM(pay_amount) AS f_sum
						   FROM mis_payment
						   WHERE deleted = 0
						   GROUP BY pay_cash_flow) AS ff_pro
						GROUP BY ccf_id
						) as progress on progress.ccf_id = $this->_table.cf_id
						");
		
		$this->_where [] = "$this->_table.cf_cb_id = :cf_cb_id";
		$this->_order [] = "$this->_table.cf_amount DESC";
		
		return parent::fetchAll ( $cond );
	}
	
	
	public function getCashFlowReferenceCasbookId($cond = array()) {
		
		$this->query ( "
						SELECT mis_cash_flow.cf_id,
						       pay_cash_flow,
						       pay_remarks,
						       pay_amount,
						       cf_amount,
						       to_char(cf_dttime,'DD/MM/YYYY') AS cdet_dip_dt,
						       payfile.file_id
						FROM mis_cash_flow
						LEFT JOIN mis_payment AS pay ON mis_cash_flow.cf_id = pay.pay_cash_flow
						AND pay.deleted = 0 -- and pay.pay_app_status = 1
						LEFT JOIN core_files AS payfile ON payfile.file_ref_id = pay.pay_id
						AND payfile.file_type = ".DOC_TYPE_PAY."
						AND payfile.deleted = 0
						WHERE cf_id= :cf_id
						  AND mis_cash_flow.deleted = 0
						UNION ALL
						SELECT mis_cash_flow.cf_id,
						       exp_cash_flow,
						       exp_details,
						       exp_amount,
						       cf_amount,
						       to_char(cf_dttime,'DD/MM/YYYY') AS cdet_dip_dt,
						       expfile.file_id
						FROM mis_cash_flow
						LEFT JOIN mis_expense AS exp ON mis_cash_flow.cf_id = exp.exp_cash_flow
						AND exp.deleted = 0 -- and exp.exp_app_status = 1
						LEFT JOIN core_files AS expfile ON expfile.file_ref_id = exp.exp_id
						AND expfile.file_type = ".DOC_TYPE_EXP."
						AND expfile.deleted = 0
						WHERE cf_id= :cf_id
						  AND mis_cash_flow.deleted = 0
						ORDER BY cf_id ASC
				" );
		return parent::fetchQuery( $cond );
	}
	
	public function deleteById($id) {
		return parent::delete ( $id );
	}
	
}


