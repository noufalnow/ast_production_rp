<?php
class expense extends db_table {
	protected $_table = "mis_expense";
	protected $_pkey = "exp_id";
	
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getExpenseById($id) {
		return parent::getById ($id);
	}
	
	public function getExpenseDetailsById($cond = array()) {
		$this->query ( 
			"select $this->_table.*,
			comp.comp_disp_name,
			pcat.cat_name as pcat,
			scat.cat_name as scat,
			ccat.cat_name as ccat,
			vendor.ven_name,
			case when exp_pay_mode = 1 then 'Cash'
			when exp_pay_mode = 2 then 'Credit'
			end as pay_mod,
			case when exp_mainh = 1 then 'Employee'
			when exp_mainh = 2 then 'Property'
			when exp_mainh = 3 then 'Vehicle'
			when exp_mainh = 4 then 'Port Operation'
			end as main_head,
			to_char(exp_app_date,'DD/MM/YYYY hh24:mi:ss') as app_dttime,
			to_char(exp_billdt,'DD/MM/YYYY') as exp_billdt,
			file_actual_name ||'.'||file_exten as file_label,
			files.file_id,

 			cf_id,
       		concat_ws(' - ', to_char(cb_date,'DD/MM/YYYY'), cb_debit_note, cb_debit) AS cb_det,
       		concat_ws(' - ', to_char(cf_dttime,'DD/MM/YYYY'), emp_fname, cf_note, cf_amount) AS cash_flow

			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = ".DOC_TYPE_EXP." and files.deleted = 0
			LEFT JOIN mis_cash_flow AS c_flow on c_flow.deleted = 0 and c_flow.cf_id  = $this->_table.exp_cash_flow
			LEFT JOIN mis_employee AS emp ON c_flow.cf_assigned = emp.emp_id	AND emp.deleted = 0
			LEFT JOIN mis_cash_book AS cbook ON cbook.cb_id = c_flow.cf_cb_id AND cbook.deleted = 0

			" );
				
		$this->_where [] = "exp_id = :exp_id";
			
		return parent::fetchRow( $cond );
	}
	
	public function getExpenseVendorPair($cond = array()) {
		$this->query ( "select exp_id,exp_id as selection from $this->_table" );
		
		if (! empty ( $cond ['exclude'] )){
			$where [] = "exp_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		$this->_where [] = "exp_vendor = :f_selVendor";
		$this->_where [] = "exp_pay_mode = :f_mode";
		$this->_order [] = 'exp_id ASC';
		
		if (! empty ( $cond ['exp_pstatus'] ))
			$this->_where [] = "exp_pstatus = :exp_pstatus";
			
		return parent::fetchPair ( $cond );
	}
	
	public function getExpenseAmountPair($cond = array()) {
		$this->query ( "select exp_id,exp_credit_amt as selection from $this->_table" );
		
		if (! empty ( $cond ['exclude'] )){
			$where [] = "exp_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		$this->_where [] = "exp_vendor = :f_selVendor";
		$this->_where [] = "exp_pay_mode = :f_mode";
		
		if (! empty ( $cond ['exp_pstatus'] ))
			$this->_where [] = "exp_pstatus = :exp_pstatus";
		
		$this->_order [] = 'exp_id ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getExpenseBillPair($cond = array()) {
	    $this->query ( "select exp_id, 'EXP/' || exp_id ||' # '||  exp_amount ||' # '|| LEFT(ven_name, 10)  || '..' as explabel from $this->_table
                        left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
                        " );
	    	    

        $this->_where [] = "exp_mainh = :exp_mainh";
        
        $cond['exp_pcat'] = 4; //maintanace
        $this->_where [] = "exp_pcat = :exp_pcat";
        
        
        
        $this->_order [] = 'exp_id DESC';
        
        return parent::fetchPair ( $cond );
	}
	
	
	public function geExpensePaginate($cond = array()) {
		$this->paginate ( "select $this->_table.*,
				comp.comp_disp_name,
				pcat.cat_name as pcat,
				scat.cat_name as scat,
				ccat.cat_name as ccat,
				vendor.ven_name,
				files.file_id,
				files.file_exten,
				case when exp_pay_mode = 1 then 'Cash'
				when exp_pay_mode = 2 then 'Credit'
				end as pay_mod,
				case when exp_mainh = 1 then 'Employee'
				when exp_mainh = 2 then 'Property'
				when exp_mainh = 3 then 'Vehicle'
				when exp_mainh = 4 then 'Port Operation'
				end as main_head,
				cb_id,
                to_char(exp_billdt,'DD/MM/YYYY') as exp_disp_date
				", "from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = ".DOC_TYPE_EXP." and files.deleted = 0

				left join mis_cash_book as cbook on cbook.cb_exp_id = exp_id and cbook.cb_type = ".CASH_BOOK_PER." and cbook.cb_type_ref = ".USER_ID." 
					and cbook.deleted= 0
	
				 " );
		
		
		if (! empty ( $cond ['f_refno'] ))
			$this->_where [] = "
					(lower(exp_refno) like '%' || lower(:f_refno) || '%')";
		
		if (! empty ( $cond ['f_particulers'] ))
			$this->_where [] = "
				(lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		
		if (! empty ( $cond ['f_selVendor'] ))
			$this->_where [] = "vendor.ven_id = :f_selVendor";
		
		if (! empty ( $cond ['f_company'] ))
			$this->_where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$this->_where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$this->_where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$this->_where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$this->_where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_mode'] ))
			$this->_where [] = "exp_pay_mode = :f_mode";
		
		if (! empty ( $cond ['f_expid'] ))
			$this->_where [] = "exp_id = :f_expid";
		
		if (! empty ( $cond ['f_status'] )){
			if($cond ['f_status'] == '2'){
				$this->_where [] = "exp_app_status IS NULL";
				unset($cond ['f_status']);
			}
			else		
				$this->_where [] = "exp_app_status = :f_status";
			
		}
		//debugging only 
		//$this->_where [] = "files.file_id IS NULL and exp_app_status = 1";
		
		$this->_order [] = 'exp_id DESC';
		
		//$this->_order [] = 'emp_uname DESC';
		//$db= new db_table();
		//$$db->dbug($cond);
		
		return parent::fetchAll ( $cond );
	}
	
	public function getExpenseDet($cond) {
		$this->query ( "select $this->_table.*,
				files.file_id,
				files.file_exten,
				files.file_actual_name,
				cbook.cb_id,
				cbook.cb_credit,
				cb_type_ref,
				cf_approve
				from $this->_table
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = ".DOC_TYPE_EXP." and files.deleted = 0
				LEFT JOIN mis_cash_book as cbook on cbook.cb_exp_id = $this->_table.exp_id and cbook.cb_type = ".CASH_BOOK_PER." 
				LEFT JOIN mis_cash_flow AS cf ON cf.cf_id = exp_cash_flow and cf.deleted= 1
				-- and cbook.cb_type_ref = ".USER_ID."
				and cbook.deleted = 0
			" );
		if (! empty ( $cond ['exp_id'] ))
			$this->_where [] = "exp_id= :exp_id";
			
			return parent::fetchRow ( $cond );
	}
	
	public function getExpenseFileNo($cond) {
		$this->query (
				"SELECT sum(total_count) AS total_count,
				    sum(type_count) AS type_count
				FROM
				  (SELECT count(*) total_count,
				                   CASE
				                       WHEN exp_pay_mode = :exp_pay_mode THEN COUNT(exp_pay_mode)
				                   END AS type_count
				   FROM mis_expense
				   where exp_id <=:exp_id	
				   GROUP BY exp_pay_mode) AS tcount"
				);
		
		return parent::fetchQuery($cond);
	}
	
	public function geExpenseReport($cond = array()) {
		@$cond = array_filter ( $cond );
		
		
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "
					(lower(exp_refno) like '%' || lower(:f_refno) || '%')";
		
		if (! empty ( $cond ['f_particulers'] ))
			$where [] = "
				(lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		
		if (! empty ( $cond ['f_selVendor'] ))
			$where [] = "vendor.ven_id = :f_selVendor";
				
		if (! empty ( $cond ['f_company'] ))
			$where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_amount'] ))
			$where [] = "exp_amount = :f_amount";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if(! empty ( $cond ['f_mode'])  && $cond ['f_mode'] ==3)
		{
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
		}
		
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";
		
		if (! empty ( $cond ['exp_pstatus'] ))
			$where [] = "exp_pstatus = :exp_pstatus";
		
		$joinType  = ' LEFT ';
		if (! empty ( $cond ['f_mrefs'] ) && is_array($cond ['f_mrefs'])){
			$mhRef = implode(",", $cond ['f_mrefs']);
			unset( $cond ['f_mrefs']);
			$joinType = ' INNER ';
		}
		else if (! empty ( $cond ['f_mrefs'] ) ){
		    $mhRef =  $cond ['f_mrefs'];
		    unset( $cond ['f_mrefs']);
		    $joinType = ' INNER ';
		}
		else
			$mhRef = 'NULL';	
		
		$p_joinType  = ' LEFT ';
		if (! empty ( $cond ['f_building'] )){
			$mhprop = $cond ['f_building'] ;
			unset( $cond ['f_building']);
			$p_joinType= ' INNER ';
		}
		else
			$mhprop= 'NULL';
			
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
		
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		if (! empty ( $cond ['exclude'] )){
			$where [] = "exp_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		
		if (! empty ( $cond ['f_export'] ))
			$where [] = "(exp_export = :f_export OR exp_export = 3)";
		
		if (! empty ( $cond ['exp_vat_option'] ))
		    $where [] = "(exp_vat_option = :exp_vat_option)";
		
        if (! empty($cond['f_status'])) {
            if ($cond['f_status'] == '2') {
                $where[] = "exp_app_status IS NULL";
                unset($cond['f_status']);
            } else
                $where[] = "exp_app_status = :f_status";
        }
		
			
			
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		if($sort=='date')
		    $sortSql  = " ORDER BY $this->_table.exp_billdt ASC ";
		else 
		    $sortSql  = " ORDER BY concat(pcat.cat_name , scat.cat_name , ccat.cat_name) ASC,$this->_table.exp_billdt ASC, ven_disp_name ASC  ";
		
		//$this->query (  );
		
		//$this->_order [] = 'concat(pcat.cat_name , scat.cat_name , ccat.cat_name) ASC,ven_disp_name ASC, $this->_table.exp_billdt ASC ';
		
		 //$db= new db_table();
		 //$$db->dbug($cond);
		 
		
		$this->paginate("SELECT $this->_table.*,
				to_char(exp_billdt,'DD/MM/YYYY') as exp_billdt,
				comp.comp_disp_name,
				pcat.cat_name as pcat,
				scat.cat_name as scat,
				ccat.cat_name as ccat,
				vendor.ven_disp_name,
                vendor.ven_vat_no,
				files.file_id,
				files.file_exten,
				case when exp_pay_mode = 1 then 'Cash'
				when exp_pay_mode = 2 then 'Credit'
				end as pay_mod,
				case when exp_mainh = 1 then 'Employee'
				when exp_mainh = 2 then 'Property'
				when exp_mainh = 3 then 'Vehicle'
				when exp_mainh = 4 then 'Port Operation'
				end as main_head,
				mref.*,
				concat(pcat.cat_id,scat.cat_id,ccat.cat_id,'_',files.file_id,'.',files.file_exten) as file_name", "
		    
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
		    
				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
							from  mis_expense_href
							where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
							group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id
		    
				$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
								           eref_exp_id
								FROM mis_expense_href
								JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
								AND eref_main_head = 2
								AND prop.deleted = 0
								AND prop.prop_building = $mhprop
								WHERE mis_expense_href.deleted = 0
								  AND eref_status = 1
								GROUP BY eref_exp_id
				)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id
		    
		    
		    
				JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where  ");
		
		return parent::fetchQueryPaginate ( $cond ,$sortSql);
	}
	
	public function getPaymentExpDet($cond, $sort='') {
		
			$where [] = "exp_vendor= :f_selVendor";
			
			if (! empty ( $cond ['exp_pay_mode'] ))
				$where [] = "exp_pay_mode= :exp_pay_mode";
			
 			//$this->_where [] = "pdet_pay_id= :pdet_pay_id";
 			
			if (! empty ( $cond ['f_mode'] ))
				$where [] = "exp_pay_mode = :f_mode";
 						 			
			if (! empty ( $cond ['exp_pstatus'] ))
				$where [] = "exp_pstatus = :exp_pstatus";

			if (! empty ( $cond ['pdet_status'] ))
				$where [] = "pdet_status = :pdet_status";
						
			if (! empty ( $cond ['exclude'] )){
				$where [] = "exp_id NOT IN (".$cond ['exclude'].")";
				unset($cond ['exclude']);
			}

 			$where [] = ' mis_expense.deleted = 0 ';
 			$where = ' WHERE ' . implode ( ' AND ', $where );
 			
 			
 			if($sort=='date')
 			    $sortSql  = " ORDER BY $this->_table.exp_billdt ASC ";
 			else
 			    $sortSql  = " ORDER BY exp_id ASC  ";
 			        
 			
 			$this->query ( "select $this->_table.* ,
 					paydet.*,
					to_char(exp_billdt,'DD/MM/YYYY') as exp_billdt,
					case when exp_mainh = 1 then 'Employee'
					when exp_mainh = 2 then 'Property'
					when exp_mainh = 3 then 'Vehicle'
					when exp_mainh = 4 then 'Port Operation'
					end as main_head,
					comp.comp_disp_name,
					files.file_id
 					from $this->_table
 					JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = ".DOC_TYPE_EXP." and files.deleted = 0
 					left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
 					left join mis_payment_det as paydet on paydet.pdet_exp_id = $this->_table.exp_id and paydet.deleted = 0
 					and pdet_pay_id= :pdet_pay_id $where $sortSql ");
					
			return parent::fetchQuery( $cond );
	}
	

	
	
	public function geExpenseReportSummary($cond = array()) {
		@$cond = array_filter ( $cond );
		
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "
				(lower(exp_refno) like '%' || lower(:f_refno) || '%')";
		
		if (! empty ( $cond ['f_particulers'] ))
			$where [] = "
				(lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		
		if (! empty ( $cond ['f_selVendor'] ))
			$where [] = "vendor.ven_id = :f_selVendor";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_amount'] ))
			$where [] = "exp_amount = :f_amount";
		
		if(! empty ( $cond ['f_mode'])  && $cond ['f_mode'] ==3)
		{
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
			$sumSelect = "SUM(exp_credit_amt) as sum ";
		}
		else 
			$sumSelect = "SUM(exp_amount) as sum ";
			
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";

		$joinType  = ' LEFT ';
		
		if (is_array($cond ['f_mrefs']) && ! empty ( $cond ['f_mrefs'] )){
		    $mhRef = implode(",", $cond ['f_mrefs']);
		    unset( $cond ['f_mrefs']);
		    $joinType = ' INNER ';
		    
		    $sumSelect = "SUM(mref.mref_sum) as sum ";
		}
		else if (! empty ( $cond ['f_mrefs'] )){
			$mhRef = $cond ['f_mrefs'];
			unset( $cond ['f_mrefs']);
			$joinType = ' INNER ';
			
			$sumSelect = "SUM(mref.mref_sum) as sum ";
		}
		else
			$mhRef = 'NULL';
		
		$p_joinType  = ' LEFT ';
		if (! empty ( $cond ['f_building'] )){
			$mhprop = $cond ['f_building'] ;
			unset( $cond ['f_building']);
			$p_joinType= ' INNER ';
			
			$sumSelect = "SUM(mref_prop.mref_sum) as sum ";
		}
		else
			$mhprop= 'NULL';
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
			
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		if (! empty ( $cond ['f_export'] ))
			$where [] = "(exp_export = :f_export OR exp_export = 3)";
		
			
		if (! empty($cond['f_status'])) {
		    if ($cond['f_status'] == '2') {
		        $where[] = "exp_app_status IS NULL";
		        unset($cond['f_status']);
		    } else
		        $where[] = "exp_app_status = :f_status";
		}
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
			SELECT 			
			$sumSelect,
			comp.comp_disp_name as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0

			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id 
						from  mis_expense_href 
						where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
						group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id 

			$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
							           eref_exp_id
							FROM mis_expense_href
							JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
							AND eref_main_head = 2
							AND prop.deleted = 0
							AND prop.prop_building = $mhprop
							WHERE mis_expense_href.deleted = 0
							  AND eref_status = 1
							GROUP BY eref_exp_id
			)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id 

			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where  group by comp.comp_disp_name" );
				
		$result['comp'] =  parent::fetchQuery ( $cond );
		
		$this->query ( "
				SELECT 			
				$sumSelect,
				case when exp_mainh = 1 then 'Employee'
				when exp_mainh = 2 then 'Property'
				when exp_mainh = 3 then 'Vehicle'
				when exp_mainh = 4 then 'Port Operation'
				end as label_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0

				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id 
							from  mis_expense_href 
							where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
							group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id 

				$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
								           eref_exp_id
								FROM mis_expense_href
								JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
								AND eref_main_head = 2
								AND prop.deleted = 0
								AND prop.prop_building = $mhprop
								WHERE mis_expense_href.deleted = 0
								  AND eref_status = 1
								GROUP BY eref_exp_id
				)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id 

				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where  group by exp_mainh" );
		
		$result['head'] =parent::fetchQuery ( $cond );
		
		$this->query ( "
				SELECT 			
				$sumSelect,
				pcat.cat_name || '<br>' || scat.cat_name || '<br>' || ccat.cat_name as label_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0

				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id 
							from  mis_expense_href 
							where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
							group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id 

				$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
								           eref_exp_id
								FROM mis_expense_href
								JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
								AND eref_main_head = 2
								AND prop.deleted = 0
								AND prop.prop_building = $mhprop
								WHERE mis_expense_href.deleted = 0
								  AND eref_status = 1
								GROUP BY eref_exp_id
				)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id 

				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where  group by ccat.cat_name,scat.cat_name,pcat.cat_name order by pcat.cat_name || ' ' || scat.cat_name || ' ' || ccat.cat_name" );
		
		$result['cat'] =parent::fetchQuery ( $cond );
		
		/*$this->query ( "
				SELECT
				$sumSelect,
				pcat.cat_name as label_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
				
				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
				from  mis_expense_href
				where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
				group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id
				
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where  group by pcat.cat_name order by pcat.cat_name" );
		
		$result['cat'] =parent::fetchQuery ( $cond );*/
		
		$this->query ( "
				SELECT 			
				$sumSelect,
				vendor.ven_disp_name as label_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0

				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id 
							from  mis_expense_href 
							where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
							group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id 

				$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
								           eref_exp_id
								FROM mis_expense_href
								JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
								AND eref_main_head = 2
								AND prop.deleted = 0
								AND prop.prop_building = $mhprop
								WHERE mis_expense_href.deleted = 0
								  AND eref_status = 1
								GROUP BY eref_exp_id
				)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id 

				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where  group by vendor.ven_disp_name order by vendor.ven_disp_name" );
		
		$result['ven'] =parent::fetchQuery ( $cond );
		
		$this->query ( "
				SELECT 			
				$sumSelect,
				case when exp_pay_mode = 1 then 'Cash'
				when exp_pay_mode = 2 then 'Credit'
				end as label_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0

				$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id 
							from  mis_expense_href 
							where eref_main_head_ref IN(".$mhRef.") and deleted = 0 and eref_status = 1
							group by eref_exp_id
				)as mref on mref.eref_exp_id = $this->_table.exp_id 

				$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
								           eref_exp_id
								FROM mis_expense_href
								JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
								AND eref_main_head = 2
								AND prop.deleted = 0
								AND prop.prop_building = $mhprop
								WHERE mis_expense_href.deleted = 0
								  AND eref_status = 1
								GROUP BY eref_exp_id
				)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id 

				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where group by label_name" );
		
		$result['total'] =parent::fetchQuery ( $cond );
		//s($result['total']);
		return $result;
	}
	
	public function geExpenseReportSummaryPlot($cond = array()) {
		@$cond = array_filter ( $cond );
		
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "
				(lower(exp_refno) like '%' || lower(:f_refno) || '%')";
		
		if (! empty ( $cond ['f_particulers'] ))
			$where [] = "
				(lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		
		if (! empty ( $cond ['f_selVendor'] ))
			$where [] = "vendor.ven_id = :f_selVendor";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_mode'] ) && $cond ['f_mode'] == 3) {
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
			$sumSelect = "SUM(exp_credit_amt) as sum ";
		} else
			$sumSelect = "SUM(exp_amount) as sum ";
		
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";
		
		$joinType = ' LEFT ';
		if (! empty ( $cond ['f_mrefs'] )) {
			$mhRef = implode ( ",", $cond ['f_mrefs'] );
			unset ( $cond ['f_mrefs'] );
			$joinType = ' INNER ';
			
			$sumSelect = "SUM(mref_sum) as sum ";
		} else
			$mhRef = 'NULL';
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
		
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
			SELECT
			$sumSelect,
			comp.comp_disp_name as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where  group by comp.comp_disp_name" );
		
		$result ['comp'] = parent::fetchQuery ( $cond );
		
		$this->query ( "
			SELECT
			$sumSelect,
			case when exp_mainh = 1 then 'Employee'
			when exp_mainh = 2 then 'Property'
			when exp_mainh = 3 then 'Vehicle'
			when exp_mainh = 4 then 'Port Operation'
			end as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where  group by exp_mainh" );
		
		$result ['head'] = parent::fetchQuery ( $cond );
		
		$this->query ( "
			SELECT
			$sumSelect,
			pcat.cat_name as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where
			group by pcat.cat_name
			--	group by ccat.cat_name ,scat.cat_name,pcat.cat_name
			order by pcat.cat_name -- || ' ' || scat.cat_name || ' ' || ccat.cat_name" );
		
	$result ['cat'] = parent::fetchQuery ( $cond );
		
		$this->query ( "
			SELECT
			$sumSelect,
			vendor.ven_disp_name as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where  group by vendor.ven_disp_name order by sum ASC" );

		$result ['ven'] = parent::fetchQuery ( $cond );
		
		$this->query ( "
			SELECT
			$sumSelect,
			case when exp_pay_mode = 1 then 'Cash'
			when exp_pay_mode = 2 then 'Credit'
			end as label_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where group by label_name" );
		
		$result ['total'] = parent::fetchQuery ( $cond );
		// s($result['total']);
		return $result;
	}
	public function geExpenseReportSummaryPlotCredit($cond = array()) {
		@$cond = array_filter ( $cond );
		
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "
        (lower(exp_refno) like '%' || lower(:f_refno) || '%')";
			
			if (! empty ( $cond ['f_particulers'] ))
				$where [] = "
        (lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		if (! empty ( $cond ['f_selVendor'] ))
			$where [] = "vendor.ven_id = :f_selVendor";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_mode'] ) && $cond ['f_mode'] == 3) {
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
			$sumSelect = "SUM(exp_credit_amt) as sum ";
		} else
			$sumSelect = "SUM(exp_amount) as sum ";
		
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";
		
		$joinType = ' LEFT ';
		if (! empty ( $cond ['f_mrefs'] )) {
			$mhRef = implode ( ",", $cond ['f_mrefs'] );
			unset ( $cond ['f_mrefs'] );
			$joinType = ' INNER ';
			
			$sumSelect = "SUM(mref_sum) as sum ";
		} else
			$mhRef = 'NULL';
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
		
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
															
			$this->query ( "
					SELECT
					$sumSelect,
					vendor.ven_disp_name as label_name
					from $this->_table
					left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
					left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
					left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
					left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
					left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
					
					$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
					from  mis_expense_href
					where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
					group by eref_exp_id
					)as mref on mref.eref_exp_id = $this->_table.exp_id
					
					LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
					$where  group by vendor.ven_disp_name order by vendor.ven_disp_name" );
			
			$result ['ven'] = parent::fetchQuery ( $cond );
			
			$this->query ( "
					SELECT
					$sumSelect,
					case when exp_pay_mode = 1 then 'Cash'
					when exp_pay_mode = 2 then 'Credit'
					end as label_name
					from $this->_table
					left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
					left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
					left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
					left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
					left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
					
					$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
					from  mis_expense_href
					where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
					group by eref_exp_id
					)as mref on mref.eref_exp_id = $this->_table.exp_id
					
					LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
					$where group by label_name" );
			
			$result ['total'] = parent::fetchQuery ( $cond );
			// s($result['total']);
			return $result;
	}
	
	
	public function getExpenseVendorReport($cond=array()){
		
		if (! empty ( $cond ['ven_id'] ))
			$where [] = "exp_vendor = :ven_id";
			
			$where [] = ' mis_expense.exp_app_status = 1 ';
			$where [] = ' mis_expense.exp_pstatus <>1 ';
			$where [] = ' mis_expense.deleted = 0 ';
			$where = ' WHERE ' . implode ( ' AND ', $where );
			
			$this->query (
					"SELECT mis_expense.*,
					to_char(exp_billdt,'DD/MM/YYYY') as exp_disp_date,
					vendor.ven_name,
					comp.comp_disp_name
					FROM mis_expense
					LEFT JOIN mis_vendor AS vendor ON vendor.ven_id = mis_expense.exp_vendor
					AND vendor.deleted = 0
					left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
					$where
					ORDER BY ven_name asc, exp_billdt ASC,exp_id DESC" );
					
					return parent::fetchQuery($cond);
					
	}
	
	
	public function geExpenseCatWiseReportSummary($cond = array()) {
		@$cond = array_filter ( $cond );
		
		if (! empty ( $cond ['f_mode'] ) && $cond ['f_mode'] == 3) {
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
			$sumSelect = "SUM(exp_credit_amt) as sum ";
		} else
			$sumSelect = "SUM(exp_amount) as sum ";
	
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";
		

		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
		
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
																													
		$this->query ( "
				SELECT
				$sumSelect,
				pcat.cat_name as pcat,
				scat.cat_name as scat,
				ccat.cat_name as ccat,

				pcat.cat_id as pcat_id,
				scat.cat_id as scat_id,
				ccat.cat_id as ccat_id,

				case when exp_mainh = 1 then 'Employee'
				when exp_mainh = 2 then 'Property'
				when exp_mainh = 3 then 'Vehicle'
				when exp_mainh = 4 then 'Port Operation'
				end as main_head_txt,
                exp_mainh       

				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
				left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
				left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
				left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
				left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
				
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
				$where
				group by exp_mainh, ccat.cat_name,ccat_id ,scat.cat_name,scat_id,pcat.cat_name,pcat_id
				order by exp_mainh ASC, pcat.cat_name  ASC, scat.cat_name ASC, ccat.cat_name ASC" );
				
				return parent::fetchQuery ( $cond );

	}
	
	public function geExpenseExport($cond = array()) {
		@$cond = array_filter ( $cond );
		
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "
					(lower(exp_refno) like '%' || lower(:f_refno) || '%')";
		
		if (! empty ( $cond ['f_particulers'] ))
			$where [] = "
				(lower(exp_details) like '%' || lower(:f_particulers) || '%')";
		
		if (! empty ( $cond ['f_selVendor'] ))
			$where [] = "vendor.ven_id = :f_selVendor";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "comp.comp_id = :f_company";
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		if (! empty ( $cond ['f_amount'] ))
			$where [] = "exp_amount = :f_amount";
		
		if (! empty ( $cond ['f_mainhead'] ))
			$where [] = "exp_mainh = :f_mainhead";
		
		if (! empty ( $cond ['f_mode'] ) && $cond ['f_mode'] == 3) {
			$cond ['f_mode'] = 2;
			$cond ['exp_pstatus'] = 1;
			$where [] = "exp_pstatus <> :exp_pstatus";
		}
		
		if (! empty ( $cond ['f_mode'] ))
			$where [] = "exp_pay_mode = :f_mode";
		
		if (! empty ( $cond ['exp_pstatus'] ))
			$where [] = "exp_pstatus = :exp_pstatus";
		
		$joinType = ' LEFT ';
		if (! empty ( $cond ['f_mrefs'] )) {
			$mhRef = implode ( ",", $cond ['f_mrefs'] );
			unset ( $cond ['f_mrefs'] );
			$joinType = ' INNER ';
		} else
			$mhRef = 'NULL';
		
		$p_joinType = ' LEFT ';
		if (! empty ( $cond ['f_building'] )) {
			$mhprop = $cond ['f_building'];
			unset ( $cond ['f_building'] );
			$p_joinType = ' INNER ';
		} else
			$mhprop = 'NULL';
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
			$where [] = "exp_billdt >= :f_dtfrom";
		
		if (! empty ( $cond ['f_dtto'] ))
			$where [] = "exp_billdt <= :f_dtto";
		
		if (! empty ( $cond ['exclude'] )) {
			$where [] = "exp_id NOT IN (" . $cond ['exclude'] . ")";
			unset ( $cond ['exclude'] );
		}
		
		if (! empty ( $cond ['exp_export'] ))
			$where [] = "(exp_export = :exp_export OR exp_export = 3)";
		
		$where [] = ' mis_expense.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
			SELECT files.file_id,
			concat(pcat.cat_id,scat.cat_id,ccat.cat_id,'_',files.file_id,'.',files.file_exten) as file_name
			from $this->_table
			left join core_company as comp on comp.comp_id = $this->_table.exp_company and comp.deleted = 0
			left join core_category as pcat on pcat.cat_id = $this->_table.exp_pcat and pcat.cat_type = 1 and pcat.deleted = 0
			left join core_category as scat on scat.cat_id = $this->_table.exp_scat and scat.cat_type = 2 and scat.deleted = 0
			left join core_category as ccat on ccat.cat_id = $this->_table.exp_ccat and ccat.cat_type = 3 and ccat.deleted = 0
			left join mis_vendor as vendor on vendor.ven_id = $this->_table.exp_vendor and vendor.deleted = 0
			
			$joinType join (select sum (eref_amount) as mref_sum, eref_exp_id
			from  mis_expense_href
			where eref_main_head_ref IN(" . $mhRef . ") and deleted = 0 and eref_status = 1
			group by eref_exp_id
			)as mref on mref.eref_exp_id = $this->_table.exp_id
			
			$p_joinType join (SELECT SUM (eref_amount) AS mref_sum,
			eref_exp_id
			FROM mis_expense_href
			JOIN mis_property AS prop ON eref_main_head_ref = prop.prop_id
			AND eref_main_head = 2
			AND prop.deleted = 0
			AND prop.prop_building = $mhprop
			WHERE mis_expense_href.deleted = 0
			AND eref_status = 1
			GROUP BY eref_exp_id
			)as mref_prop on mref_prop.eref_exp_id = $this->_table.exp_id
			
			
			
			JOIN core_files as files on files.file_ref_id = $this->_table.exp_id and files.file_type = " . DOC_TYPE_EXP . " and files.deleted = 0
			$where  ORDER BY concat(pcat.cat_name , scat.cat_name , ccat.cat_name) ASC,$this->_table.exp_billdt ASC, ven_disp_name ASC " );


		
		//$db= new db_table();
		//$$db->dbug($cond);

																		
		

		
		return parent::fetchQueryPair( $cond );
	}
	
	
	public function getVehicleExpenseByCompany($cond=[]){
	    $cond=array_filter($cond);
	    
	    if ($cond['f_monthpick'] == '') {
	        $expDate = " AND TO_CHAR(COALESCE( exp.exp_billdt, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(COALESCE( pay.pay_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        
	    } else {
	        $expDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(exp.exp_billdt, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(pay.pay_paydate, 'YYYY-MM')";
	    }
	    
	    if ($cond['f_company'] != '') {
	        $company = " WHERE combined.comp_id = ".$cond['f_company'] . " ";
	    }
	    
	    unset($cond['f_company']);
	    unset($cond['f_monthpick']);
	    
	    
	    $this->query("

                SELECT 
                    combined.ref_source,
                    combined.comp_id,
                    combined.category,
                    SUM(combined.amount) AS total_amount,
                    combined.exp_details
                FROM (
                    -- First subquery
                    SELECT 
                        'Cash Bills' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(exp.exp_amount) AS amount,
                        string_agg(exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM 
                        mis_expense exp
                    LEFT JOIN 
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN 
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN 
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE 
                        exp.exp_mainh = 3
                        AND exp.exp_pay_mode = '1'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                        $expDate
                    GROUP BY 
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
                
                    UNION ALL
                
                    -- Second subquery
                    SELECT 
                        'Credit Payments' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(paydet.pdet_amt_paid) AS amount,
                        string_agg( DISTINCT pay_file_no || '-' || pay_amount || '-' || exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM 
                        mis_payment_det paydet
                    JOIN 
                        mis_payment AS pay ON pay.pay_id = paydet.pdet_pay_id
                        AND pay.deleted = 0
                    LEFT JOIN 
                        mis_expense exp ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    LEFT JOIN 
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN 
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN 
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE 
                        paydet.pdet_status = 2
                        AND paydet.deleted = 0
                        AND exp.exp_mainh = 3
                        $payDate    
                    GROUP BY 
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
                ) AS combined

                $company

                GROUP BY 
                    ref_source,combined.comp_id, combined.category ,combined.exp_details
                ORDER BY 
                    combined.comp_id, combined.category;


                ");
                unset($cond['f_company']);
                return parent::fetchQuery($cond);
                
	}
	
	
	public function getPropertyExpenseByCompany($cond=[]){
	    $cond=array_filter($cond);
	    
	    if ($cond['f_monthpick'] == '') {
	        $expDate = " AND TO_CHAR(COALESCE( exp.exp_billdt, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(COALESCE( pay.pay_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        
	    } else {
	        $expDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(exp.exp_billdt, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(pay.pay_paydate, 'YYYY-MM')";
	    }
	    
	    if ($cond['f_company'] != '') {
	        $company = " WHERE combined.comp_id = ".$cond['f_company'] . " ";
	    }
	    
	    unset($cond['f_company']);
	    unset($cond['f_monthpick']);
	    
	    
	    $this->query("
	        
                SELECT
                    combined.ref_source,
                    combined.comp_id,
                    combined.category,
                    SUM(combined.amount) AS total_amount,
                    combined.exp_details
                FROM (
                    -- First subquery
                    SELECT
                        'Cash Bills' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(exp.exp_amount) AS amount,
                        string_agg(exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM
                        mis_expense exp
                    LEFT JOIN
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE
                        exp.exp_mainh = 2
                        AND exp.exp_pay_mode = '1'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                        $expDate
                    GROUP BY
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
	        
                    UNION ALL
	        
                    -- Second subquery
                    SELECT
                        'Credit Payments' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(paydet.pdet_amt_paid) AS amount,
                        string_agg( DISTINCT pay_file_no || '-' || pay_amount || '-' || exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM
                        mis_payment_det paydet
                    JOIN
                        mis_payment AS pay ON pay.pay_id = paydet.pdet_pay_id
                        AND pay.deleted = 0
                    LEFT JOIN
                        mis_expense exp ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    LEFT JOIN
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE
                        paydet.pdet_status = 2
                        AND paydet.deleted = 0
                        AND exp.exp_mainh = 2
                        $payDate
                    GROUP BY
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
                ) AS combined
	        
                $company
	        
                GROUP BY
                    ref_source,combined.comp_id, combined.category ,combined.exp_details
                ORDER BY
                    combined.comp_id, combined.category;
	        
	        
                ");
                unset($cond['f_company']);
                return parent::fetchQuery($cond);
                
	}
	
	
	public function getOtherExpenseByCompany($cond=[]){
	    $cond=array_filter($cond);
	    
	    if ($cond['f_monthpick'] == '') {
	        $expDate = " AND TO_CHAR(COALESCE( exp.exp_billdt, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(COALESCE( pay.pay_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	        
	    } else {
	        $expDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(exp.exp_billdt, 'YYYY-MM')";
	        $payDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(pay.pay_paydate, 'YYYY-MM')";
	    }
	    
	    if ($cond['f_company'] != '') {
	        $company = " WHERE combined.comp_id = ".$cond['f_company'] . " ";
	    }
	    
	    unset($cond['f_company']);
	    unset($cond['f_monthpick']);
	    
	    
	    $this->query("
	        
                SELECT
                    combined.ref_source,
                    combined.comp_id,
                    combined.category,
                    SUM(combined.amount) AS total_amount,
                    combined.exp_details
                FROM (
                    -- First subquery
                    SELECT
                        'Cash Bills' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(exp.exp_amount) AS amount,
                        string_agg(exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM
                        mis_expense exp
                    LEFT JOIN
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE
                        exp.exp_mainh NOT IN (2, 3)
                        AND exp.exp_pay_mode = '1'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                        $expDate
                    GROUP BY
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
	        
                    UNION ALL
	        
                    -- Second subquery
                    SELECT
                        'Credit Payments' AS ref_source,
                        exp.exp_company AS comp_id,
                        SUM(paydet.pdet_amt_paid) AS amount,
                        string_agg( DISTINCT pay_file_no || '-' || pay_amount || '-' || exp_file_no || '-' || exp_amount, ',</br> ') AS exp_details,
                        pcat.cat_name || '-' || scat.cat_name || '-' || ccat.cat_name AS category
                    FROM
                        mis_payment_det paydet
                    JOIN
                        mis_payment AS pay ON pay.pay_id = paydet.pdet_pay_id
                        AND pay.deleted = 0
                    LEFT JOIN
                        mis_expense exp ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    LEFT JOIN
                        core_category AS pcat ON pcat.cat_id = exp.exp_pcat
                        AND pcat.cat_type = 1
                        AND pcat.deleted = 0
                    LEFT JOIN
                        core_category AS scat ON scat.cat_id = exp.exp_scat
                        AND scat.cat_type = 2
                        AND scat.deleted = 0
                    LEFT JOIN
                        core_category AS ccat ON ccat.cat_id = exp.exp_ccat
                        AND ccat.cat_type = 3
                        AND ccat.deleted = 0
                    WHERE
                        paydet.pdet_status = 2
                        AND paydet.deleted = 0
                        AND exp.exp_mainh NOT IN (2, 3)
                        $payDate
                    GROUP BY
                        exp.exp_company, pcat.cat_name, scat.cat_name, ccat.cat_name
                ) AS combined
	        
                $company
	        
                GROUP BY
                    ref_source,combined.comp_id, combined.category ,combined.exp_details
                ORDER BY
                    combined.comp_id, combined.category;
	        
	        
                ");
                unset($cond['f_company']);
                return parent::fetchQuery($cond);
                
	}
	
	
	
	

	
	
	
}


