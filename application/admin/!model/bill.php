<?php
class bill extends db_table {
	protected $_table = "mis_bill";
	protected $_pkey = "bill_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getBillPaginate($cond){
		
		$this->paginate ( "select $this->_table.*,
				to_char(bill_date,'DD/MM/YYYY') as bill_disp_date, 
				to_char(bill_rev_date,'DD/MM/YYYY') as bill_review_date, 
				to_char(bill_month,'Month-YYYY') as bill_month, 
				comp.comp_disp_name,
				cust.cust_name,
				cdet_bill_id,
				itm_name,
                vhl_status,
                rev_count
				", "from $this->_table 
				left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.bill_customer_id and cust.deleted = 0
				left join mis_collection_det as collbill on collbill.cdet_bill_id = $this->_table.bill_id and collbill.cdet_src_type = 1 and collbill.deleted = 0
				
				LEFT JOIN
				  (SELECT count(*) as rev_count, 
                    brev_bill_id 
                    FROM mis_bill_revenue
                    where deleted = 0
                    group by brev_bill_id
				   ) AS billrev on billrev.brev_bill_id = mis_bill.bill_id

                LEFT JOIN
				  (SELECT mis_bill_det.bdet_bill_id ,
				          array_to_string(array_agg(item_name), ', ') AS itm_name,
                   CASE 
                        WHEN COUNT(item_vehicle) != COUNT(item_name) THEN 'vhl_missing'
                   END AS vhl_status
				   FROM mis_bill_det
				   LEFT JOIN mis_item AS item ON item.item_id = mis_bill_det.bdet_item
				   AND item.deleted = 0
				   JOIN
				     (SELECT bdet_bill_id,MAX (bdet_update_sts)AS sts_max
				      FROM mis_bill_det
				      WHERE deleted = 0
				      GROUP BY mis_bill_det.bdet_bill_id) AS max_status ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
				   AND max_status.sts_max = bdet_update_sts
				   WHERE mis_bill_det.deleted=0
				   GROUP BY mis_bill_det.bdet_bill_id) AS item_det ON item_det.bdet_bill_id = mis_bill.bill_id " );
		
		if (! empty ( $cond ['f_monthpick'] )) {
			$monthYear = explode ( '/', $cond ['f_monthpick'] );
			$this->_where [] = "(EXTRACT(month FROM bill_month) = '$monthYear[0]' AND EXTRACT(year FROM bill_month) = '$monthYear[1]' )";
			unset ( $cond ['f_monthpick'] );
		}
						
		if (! empty ( $cond ['f_refno'] ))
			$this->_where [] = "lower(bill_refno) like '%' || lower(:f_refno) || '%'";
		
		if (! empty ( $cond ['f_billno'] ))
			$this->_where [] = "(( bill_id::text LIKE '%' || :f_billno || '%'  ) OR ( bill_book_no::text LIKE '%' || :f_billno || '%'  ))";
		
		if (! empty ( $cond ['f_paymode'] ))
			if( $cond ['f_paymode'] ==3){
				$this->_where [] = "bill_pstatus = :f_paymode";
				$cond ['f_paymode'] = 1;
				}
			else 
				$this->_where [] = "bill_mode = :f_paymode";
		
		if (! empty ( $cond ['f_customer'] ))
			$this->_where [] = "bill_customer_id = :f_customer";
		
		if (! empty ( $cond ['f_company'] ))
			$this->_where [] = "bill_company = :f_company";
		
		if (!empty ( $cond ['f_item'] ))
			$this->_where [] = "
			(lower(itm_name) like '%' || lower(:f_item ::text) || '%')";
		
		if (!empty ( $cond ['f_location'] ))
			$this->_where [] = "
			(lower(bill_location) like '%' || lower(:f_location) || '%')";
			
		
		$this->_order [] = 'bill_id DESC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getBillInfo($cond) {
		
		//$cond['bill_cancellation_status'] = 0;
		//$this->_where [] = "bill_cancellation_status= :bill_cancellation_status";
		
		$this->query ( "select $this->_table.*, 
				to_char(bill_date,'DD/MM/YYYY') as bill_disp_date,
				comp.comp_name,
				cust.*,
				custcon.*,
				files.file_id,
				files.file_actual_name||'.'||files.file_exten as file_name,
				doc_no || ' - ' || doc_remarks || ' - (' || to_char(doc_issue_date,'DD/MM/YYYY')|| ' - ' || to_char(doc_expiry_date,'DD/MM/YYYY') || ')' as agr_det,
				wofiles.file_id as agr_file
				from $this->_table 
				left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.bill_customer_id and cust.deleted = 0
				left join mis_contacts as custcon on custcon.con_ref_id = cust.cust_id and custcon.deleted = 0 and  custcon.con_type = 4 and custcon.con_ref_type  = ".CONT_TYPE_CUST."
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.bill_id and files.file_type = ".DOC_TYPE_BILL." and files.deleted = 0
				LEFT JOIN mis_documents as doc on doc.doc_id = $this->_table.bill_wo and doc.deleted = 0
				LEFT JOIN core_files as wofiles on wofiles.file_ref_id = $this->_table.bill_wo and wofiles.file_type = ".DOC_TYPE_COM_AGR." and wofiles.deleted = 0
				" );
		if (! empty ( $cond ['bill_id'] ))
			$this->_where [] = "bill_id= :bill_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getBillDetById($id){
		return parent::getById ($id);
	}
	
	public function getBillById($id){
		return parent::getById ($id);
	}
	
	public function deleteBill($id) {
		return parent::delete ( $id );
	}
	
	public function getBillReport($cond=array()){
		
		$cond['bill_cancellation_status'] = 0;
		$this->_where [] = "bill_cancellation_status= :bill_cancellation_status";
		
		
		$this->query ( "select $this->_table.*, 
				to_char(bill_date,'DD/MM/YYYY') as bill_billdt,
				comp.comp_disp_name,
				cust.cust_name
				from $this->_table
				left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.bill_customer_id and cust.deleted = 0
				" );
		
		if (! empty ( $cond ['exclude'] )){
			$this->_where [] = "bill_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		if (! empty ( $cond ['bill_mode'] ))
			$this->_where [] = "bill_mode = :bill_mode";
		
		if (! empty ( $cond ['bill_pstatus'] ))
			$this->_where [] = "bill_pstatus = :bill_pstatus";
		
		if (! empty ( $cond ['f_selCustomer'] ))
			$this->_where [] = "bill_customer_id = :f_selCustomer";
		
		if (! empty ( $cond ['bill_app_status'] ))
			$this->_where [] = "bill_app_status = :bill_app_status";
		
		$this->_order [] = 'bill_id ASC';
		
		return parent::fetchAll( $cond );
				
		
	}
	
	public function getBillCustomerPair($cond = array()) {
		
		$cond['bill_cancellation_status'] = 0;
		$this->_where [] = "bill_cancellation_status= :bill_cancellation_status";
		
		$this->query ( "select bill_id,bill_id as selection from $this->_table" );
		
		if (! empty ( $cond ['exclude'] )){
			$this->_where [] = "bill_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		$this->_where [] = "bill_customer_id = :f_selCustomer";
		$this->_where [] = "bill_mode = :bill_mode";
		
		if (! empty ( $cond ['bill_pstatus'] ))
			$this->_where [] = "bill_pstatus = :bill_pstatus";

		if (! empty ( $cond ['bill_app_status'] ))
			$this->_where [] = "bill_app_status = :bill_app_status";
	
		$this->_order [] = 'bill_id ASC';
			
		return parent::fetchPair ( $cond );
	}
	
	public function getBillAmountPair($cond = array()) {
		
		$cond['bill_cancellation_status'] = 0;
		$this->_where [] = "bill_cancellation_status= :bill_cancellation_status";
		
		$this->query ( "select bill_id,bill_credit_amt as selection from $this->_table" );
		
		if (! empty ( $cond ['exclude'] )){
			$this->_where [] = "bill_id NOT IN (".$cond ['exclude'].")";
			unset($cond ['exclude']);
		}
		
		$this->_where [] = "bill_customer_id = :f_selCustomer";
		$this->_where [] = "bill_mode = :bill_mode";
		
		if (! empty ( $cond ['bill_pstatus'] ))
			$this->_where [] = "bill_pstatus = :bill_pstatus";

		if (! empty ( $cond ['bill_app_status'] ))
			$this->_where [] = "bill_app_status = :bill_app_status";
		
		$this->_order [] = 'bill_id ASC';
			
		return parent::fetchPair ( $cond );
	}
	
	public function getCollectionBillDet($cond) {
		
		$cond['bill_cancellation_status'] = 0;
		$where [] = " bill_cancellation_status= :bill_cancellation_status ";
		
		$where []= "bill_customer_id = :f_selCustomer";
		$where []= "bill_mode = :bill_mode";
		
		if (! empty ( $cond ['bill_pstatus'] ))
			$where [] = "bill_pstatus = :bill_pstatus";
		
		if (! empty ( $cond ['cdet_status'] ))
			$where [] = "cdet_status = :cdet_status";
		
		if (! empty ( $cond ['exclude'] )) {
			$where [] = "bill_id NOT IN (" . $cond ['exclude'] . ")";
			unset ( $cond ['exclude'] );
		}
		
		if (! empty ( $cond ['bill_app_status'] ))
			$where [] = "bill_app_status = :bill_app_status";
		
		$where [] = ' mis_bill.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "select $this->_table.* ,
						to_char(bill_date,'DD/MM/YYYY') as bill_billdt,
						colldet.*,
						comp.comp_disp_name
						from $this->_table
						left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
						left join mis_collection_det as colldet on colldet.cdet_bill_id = $this->_table.bill_id and colldet.deleted = 0 and cdet_src_type = 1
						and cdet_coll_id= :cdet_coll_id " . $where . " order by bill_id ASC" );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getCollectedBillDet($cond) {
		
		$cond['bill_cancellation_status'] = 0;
		$where [] = " bill_cancellation_status= :bill_cancellation_status ";
		
		$where [] = "bill_customer_id = :f_selCustomer";
		
		if (! empty ( $cond ['bill_pstatus'] ))
			$where [] = "bill_pstatus = :bill_pstatus";
		
		if (! empty ( $cond ['cdet_status'] ))
			$where [] = "cdet_status = :cdet_status";
				
		$where [] = ' mis_bill.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "select $this->_table.* ,
						to_char(bill_date,'DD/MM/YYYY') as bill_billdt,
						colldet.*,
						comp.comp_disp_name
						from $this->_table
						left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
						inner join mis_collection_det as colldet on colldet.cdet_bill_id = $this->_table.bill_id and colldet.deleted = 0 and cdet_src_type = 1
						and cdet_coll_id= :cdet_coll_id " . $where . " order by bill_id ASC" );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getBillPendingReport($cond=array()){
		
		$cond['bill_cancellation_status'] = 0;
		$where [] = " mis_bill.bill_cancellation_status= :bill_cancellation_status ";
					
		if (! empty ( $cond ['cust_id'] ))
			$where [] = "bill_customer_id = :cust_id";
			
		$where [] = ' mis_bill.bill_app_status = 1 ';
		$where [] = ' mis_bill.bill_pstatus = 2 ';
		$where [] = ' mis_bill.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query (
				"SELECT mis_bill.*,
						to_char(bill_rev_date,'DD/MM/YYYY') as rev_date, 
						to_char(bill_date,'DD/MM/YYYY') as bill_disp_date, 
						to_char(bill_month,'Month-YYYY') as bill_month, 
						to_char(bill_rev_date + interval '60' day,'DD/MM/YYYY') as bill_due_date, 
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 0 then bill_credit_amt end as due_amount,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 15 then bill_credit_amt end as due_amount_15,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 30 then bill_credit_amt end as due_amount_30,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 45 then bill_credit_amt end as due_amount_45,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 60 then bill_credit_amt end as due_amount_60,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 90 then bill_credit_amt end as due_amount_90,
				       case when (date_part('day', now()-(bill_rev_date + interval '60' day))) > 120 then bill_credit_amt end as due_amount_120,
				       cust.cust_name,
					   comp.comp_disp_name,
				       con_phone,
				       con_mobile,
					   bill_remarks
				FROM mis_bill
				LEFT JOIN mis_customer AS cust ON cust.cust_id = mis_bill.bill_customer_id
				AND cust.deleted = 0
				left join core_company as comp on comp.comp_id = $this->_table.bill_company and comp.deleted = 0
				LEFT JOIN mis_contacts AS contact ON contact.con_ref_id = cust.cust_id
				AND contact.deleted = 0
				AND contact.con_ref_type = 4
				AND contact.con_type = 4
				AND contact.deleted = 0
				$where
				ORDER BY cust_name asc, mis_bill.bill_month ASC, bill_rev_date ASC" );
		
		return parent::fetchQuery($cond);
		
	}
	public function getAllBillReport($cond=array()){
	    if(is_array($cond))
		@$cond = array_filter ( $cond );
		
		$cond['bill_cancellation_status'] = 0;
		$where [] = " mis_bill.bill_cancellation_status= :bill_cancellation_status ";
					
		if (! empty ( $cond ['f_monthpick'] )) {
				$monthYear = explode ( '/', $cond ['f_monthpick'] );
				$where [] = "(EXTRACT(month FROM bill_month) = '$monthYear[0]' AND EXTRACT(year FROM bill_month) = '$monthYear[1]' )";
				unset ( $cond ['f_monthpick'] );
			}
		if (! empty ( $cond ['f_refno'] ))
			$where [] = "lower(bill_refno) like '%' || lower(:f_refno) || '%'";
		
		if (! empty ( $cond ['f_billno'] ))
			$where [] = "(( bill_id::text LIKE '%' || :f_billno || '%'  ) OR ( bill_book_no::text LIKE '%' || :f_billno || '%'  ))";
		
		if (! empty ( $cond ['f_paymode'] ))
			if ($cond ['f_paymode'] == 3) {
				$where [] = "bill_pstatus = :f_paymode";
				$cond ['f_paymode'] = 1;
			} 
			elseif ($cond ['f_paymode'] == 4) {
				$where [] = "bill_credit_amt > 0";
				unset($cond ['f_paymode']);
			}else
				$where [] = "bill_mode = :f_paymode";
		
		if (! empty ( $cond ['f_customer'] ))
			$where [] = "bill_customer_id = :f_customer";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "bill_company = :f_company";
		
		if (! empty ( $cond ['f_item'] ))
			$where [] = "
			(lower(itm_name) like '%' || lower(:f_item ::text) || '%')";
		
		if (! empty ( $cond ['f_location'] ))
			$where [] = "(lower(bill_location) like '%' || lower(:f_location) || '%')";
			
		$where [] = ' mis_bill.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query (
				"SELECT mis_bill.*,
				       to_char(bill_date,'DD/MM/YYYY') AS bill_disp_date,
				       to_char(bill_rev_date,'DD/MM/YYYY') AS bill_review_date,
				       to_char(bill_month,'Mon-YYYY') AS bill_month,
				       comp.comp_disp_name,
				       cust.cust_name,
				       -- cdet_bill_id,
				       itm_name
				FROM mis_bill
				LEFT JOIN core_company AS comp ON comp.comp_id = mis_bill.bill_company
				AND comp.deleted = 0
				LEFT JOIN mis_customer AS cust ON cust.cust_id = mis_bill.bill_customer_id
				AND cust.deleted = 0
				-- LEFT JOIN mis_collection_det AS collbill ON collbill.cdet_bill_id = mis_bill.bill_id
				-- AND collbill.cdet_src_type = 1
				-- AND collbill.deleted = 0
				LEFT JOIN
				  (SELECT mis_bill_det.bdet_bill_id ,
				          array_to_string(array_agg(concat(item_name,'-',bdet_qty,'-',bdet_amt)), ';<br>  
				   ') AS itm_name
				   FROM mis_bill_det
				   LEFT JOIN mis_item AS item ON item.item_id = mis_bill_det.bdet_item
				   AND item.deleted = 0
				   JOIN
				     (SELECT bdet_bill_id,MAX (bdet_update_sts)AS sts_max
				      FROM mis_bill_det
				      WHERE deleted = 0
				      GROUP BY mis_bill_det.bdet_bill_id) AS max_status ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
				   AND max_status.sts_max = bdet_update_sts
				   WHERE mis_bill_det.deleted=0
				   GROUP BY mis_bill_det.bdet_bill_id) AS item_det ON item_det.bdet_bill_id = mis_bill.bill_id
				$where
				ORDER BY bill_id DESC" );
				
			return parent::fetchQuery($cond);
		}
		
		
		public function getInvoiceDetByBilllId($cond=[]){
		    
		    $this->query ( "SELECT bdet_item,
                               bdet_id,
                               item_name,
                               item_code,
                               bdet_qty ,
                               bdet_amt,
                               ROUND(bdet_qty * bdet_amt, 3) AS total_amt,
                               bill_id,
                               vhl_no,
                               comp_name,
                               comp_disp_name,
                               vhl_id
                        FROM  mis_bill 
		        
                        LEFT JOIN mis_bill_det AS billdet ON billdet.bdet_bill_id = bill_id
                        AND billdet.deleted = 0
		        
    				   JOIN
    				     (SELECT bdet_bill_id,MAX (bdet_update_sts)AS sts_max
    				      FROM mis_bill_det
    				      WHERE deleted = 0
    				      GROUP BY mis_bill_det.bdet_bill_id) AS max_status ON max_status.bdet_bill_id = billdet.bdet_bill_id
    				   AND max_status.sts_max = bdet_update_sts
		        
		        
                        LEFT JOIN mis_item AS item ON item.item_id = billdet.bdet_item
                        AND item.deleted = 0
                        AND item.item_type = 1
                        LEFT JOIN mis_vehicle AS veh ON veh.vhl_id = item.item_vehicle
                        AND veh.deleted = 0
                        LEFT JOIN core_company AS comp ON comp.comp_id = veh.vhl_company
                        AND comp.deleted = 0" );
		    
		    
	    
		    $this->_where [] = "bill_id= :bill_id";
		    
		    $this->_order [] = "bill_id ASC";
		    
		    
		    return parent::fetchAll($cond);
		}
		

	
	
}?>