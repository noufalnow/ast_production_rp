<?php
class cashbook extends db_table {
	protected $_table = "mis_cash_book";
	protected $_pkey = "cb_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getCashBookPair($cond = array()) {
		$this->query ( "select cb_id,cb_code || ' - ' ||cb_name from $this->_table" );
		$this->_order [] = 'cb_name ASC';
	
		return parent::fetchPair ( $cond );
	}
	
	
	
	public function getCashBooksPaginate($cond){
		
		$this->paginate ( "select $this->_table.*,
					to_char(cb_date,'DD/MM/YYYY') as cb_dip_dt,
					exp_details,
					pay_remarks,
					countset.*",
					"from $this->_table
					left join mis_expense as exp on exp.exp_id = cb_exp_id and exp.deleted = 0 and $this->_table.cb_exp_type = 1
					left join mis_payment as pay on pay.pay_id = cb_exp_id and pay.deleted = 0 and $this->_table.cb_exp_type = 2
					left join (
						select 
						count (*) as cfcount,
 						COALESCE(SUM(CASE WHEN cf_approve = 1  THEN 1 ELSE 0 END), 0) AS pcount,
  						COALESCE(SUM(CASE WHEN cf_approve = 2  THEN 1 ELSE 0 END), 0) AS acount,
						
						cf_cb_id from mis_cash_flow where deleted = 0 group by cf_cb_id) as countset on countset.cf_cb_id =   cb_id 

					" );
		
		if (!empty ( $cond ['f_code'] ))
			$this->_where [] = " cast(cb_id AS text)  like '%' || :f_code || '%'";
		
		if (!empty ( $cond ['f_CashBook'] ))
			$this->_where [] = "cb_name like '%' || :f_CashBook || '%'";
		
		if (!empty ( $cond ['f_name'] ))
			$this->_where [] = "con_name like '%' || :f_name || '%'";
		
		if (!empty ( $cond ['f_house'] ))
			$this->_where [] = "con_house like '%' || :f_house || '%'";
		
		if (! empty ( $cond ['cb_type_ref'] ))
			$this->_where [] = "cb_type_ref = :cb_type_ref";
		
		$this->_order [] = 'cb_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getCashBooksReport($cond){
		$this->query( "select 
				$this->_table.*,
				exp_id,
				exp_app_status,
				' | ' || exp_refno as exp_refno,
				case when cflow.cf_cb_id IS NOT NULL THEN cflow.cf_cb_id
					 when cflow_pay.cf_cb_id IS NOT NULL THEN cflow_pay.cf_cb_id
				end as cf_cb_id, 

				case when exp_cash_flow IS NOT NULL THEN exp_cash_flow
					 when pay_cash_flow IS NOT NULL THEN pay_cash_flow
				end as cb_id_ref, 

				case when exp_cash_flow IS NOT NULL THEN cflow.cf_note
					 when pay_cash_flow IS NOT NULL THEN cflow_pay.cf_note
				end as cf_ref_note, 

				cflow.cf_note,
				cflow_pay.cf_note,

				case when cflow.cf_cb_id IS NOT NULL THEN
				' | ' || concat_ws(' - ', to_char(cb_cflow.cb_date,'DD/MM/YYYY'), cb_cflow.cb_debit, cb_cflow.cb_debit_note) 
					when cflow_pay.cf_cb_id IS NOT NULL THEN
				' | ' || concat_ws(' - ', to_char(cb_cflow_pay.cb_date,'DD/MM/YYYY'), cb_cflow_pay.cb_debit, cb_cflow_pay.cb_debit_note)
				end as cf_cb_details,
				user_fname ||' '||	user_lname as user_name,
				$this->_table.cb_type ||'_'|| $this->_table.cb_type_ref  as cb_type_group,
				to_char($this->_table.cb_date,'DD/MM/YYYY') as cb_dip_dt,
				pcat.cat_name ||'-'|| scat.cat_name ||'-'|| ccat.cat_name as category_name,
				exp_details,
				pay_remarks
				from $this->_table
				left join mis_expense as exp on exp.exp_id = cb_exp_id and exp.deleted = 0 and $this->_table.cb_exp_type = 1
				left join mis_payment as pay on pay.pay_id = cb_exp_id and pay.deleted = 0 and $this->_table.cb_exp_type = 2
				left join core_category as pcat on pcat.cat_id = exp.exp_pcat and pcat.cat_type = 2 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = exp.exp_scat and scat.cat_type = 3 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = exp.exp_ccat and ccat.cat_type = 4 and ccat.deleted = 0
				left join core_users as users on users.user_id  = 
								(case when cb_type_ref = 1999 then 19 else cb_type_ref end)  
								and cb_type = 2 and users.deleted = 0
				left join mis_cash_flow as cflow on cflow.cf_id = exp_cash_flow and cflow.deleted = 0
				left join mis_cash_book as cb_cflow on cb_cflow.cb_id = cflow.cf_cb_id and cb_cflow.deleted = 0

				left join mis_cash_flow as cflow_pay on cflow_pay.cf_id = pay_cash_flow and cflow_pay.deleted = 0
				left join mis_cash_book as cb_cflow_pay on cb_cflow_pay.cb_id = cflow_pay.cf_cb_id and cb_cflow_pay.deleted = 0

				" );
		
		if (! empty ( $cond ['f_code'] ))
			$this->_where [] = " cast(cb_id AS text)  like '%' || :f_code || '%'";
		
		if (! empty ( $cond ['f_CashBook'] ))
			$this->_where [] = "cb_name like '%' || :f_CashBook || '%'";
		
		if (! empty ( $cond ['f_name'] ))
			$this->_where [] = "con_name like '%' || :f_name || '%'";
		
		if (! empty ( $cond ['f_house'] ))
			$this->_where [] = "con_house like '%' || :f_house || '%'";
		
		/*if($_SESSION ['user_type']<>6 && $_SESSION ['user_type']<>99){
			$cond ['cb_type_ref'] = USER_ID;
			$this->_where [] = "cb_type_ref = :cb_type_ref";
		}*/
		
		if (! empty ( $cond ['cb_type_ref_ex'] ))
			$this->_where [] = "$this->_table.cb_type_ref <> :cb_type_ref_ex";
		
		if (! empty ( $cond ['cb_type_ref'] ))
			$this->_where [] = "$this->_table.cb_type_ref = :cb_type_ref";
		
		$this->_order [] = "$this->_table.cb_type_ref ASC, cb_id ASC";
		
		return parent::fetchAll( $cond );
	}
	
	public function getCashSummary($cond=[]) {
		$this->query ( "
				SELECT sum(cb_debit) as debits,sum(cb_credit) as credits,
					'RO ' || (sum(cb_debit) - sum(cb_credit)) as balance,
					user_fname ||' '|| user_lname AS user_name,
					cb_type_ref 
				FROM mis_cash_book
				LEFT JOIN mis_expense AS exp ON exp.exp_id = cb_exp_id
				AND exp.deleted = 0
				AND mis_cash_book.cb_exp_type = 1
				LEFT JOIN mis_payment AS pay ON pay.pay_id = cb_exp_id
				AND pay.deleted = 0
				AND mis_cash_book.cb_exp_type = 2
				LEFT JOIN core_category AS pcat ON pcat.cat_id = exp.exp_pcat
				AND pcat.cat_type = 2
				AND pcat.deleted = 0
				LEFT JOIN core_category AS scat ON scat.cat_id = exp.exp_scat
				AND scat.cat_type = 3
				AND scat.deleted = 0
				LEFT JOIN core_category AS ccat ON ccat.cat_id = exp.exp_ccat
				AND ccat.cat_type = 4
				AND ccat.deleted = 0
				INNER JOIN core_users AS users ON users.user_id = cb_type_ref
				AND cb_type = 2
				AND users.deleted = 0
				WHERE cb_type_ref <> 19
				AND mis_cash_book.deleted = 0
				group by cb_type_ref,user_fname ||' '|| user_lname" );
			
			return parent::fetchQuery( $cond );
	}
	

	public function getCashBookDet($cond) {
		$this->query ( "select $this->_table.* ,
				contact.* 
				from $this->_table " );
		if (! empty ( $cond ['cb_id'] ))
			$this->_where [] = "cb_id= :cb_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getCashBookDetById($id){
		return parent::getById ($id);
	}
	
	public function getCashBookDetByCbId($cond){
		
		$this->query( 
				"select
				$this->_table.*,
				to_char(cb_date,'DD/MM/YYYY') as cb_dip_dt,
				case 
					when cb_src= 1 then 'Income'
					when cb_src= 2 then 'Owners Fund'
					when cb_src= 3 then 'Loan'
				end as source_type,
				case 
					when cb_src= 1 then cust.cust_name ||' ' ||coll_amount
					else cb_src_det
				end as source_details

				
				from $this->_table
				left join mis_collection as coll on coll.coll_id = $this->_table.cb_src_inc and coll.deleted = 0 and $this->_table.cb_src = 2
				left join mis_customer as cust on cust.cust_id = coll.coll_cust and cust.deleted = 0				
				");
		
		if (! empty ( $cond ['cb_id'] ))
			$this->_where [] = "cb_id = :cb_id";
		
		
		return parent::fetchRow($cond);
	}
	
	public function getCashBookById($id){
		return parent::getById ($id);
	}
	
	public function deleteCashBook($id) {
		return parent::delete ( $id );
	}
	
}


