<?php
class collection extends db_table {
	protected $_table = "mis_collection";
	protected $_pkey = "coll_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data,$cond);
	}
	
	
	public function getCollectionPair($cond=[]) {
		$this->query ( "select $this->_table.coll_id,cust.cust_name ||' ' ||coll_amount
				from $this->_table
				left join mis_customer as cust on cust.cust_id = $this->_table.coll_cust and cust.deleted = 0
				" );
		
			//$cond['coll_status'] = 1;
		   	$this->_where [] = "coll_app_status = 1";
		   	
		   	$this->_order [] = 'coll_id DESC';
		   	
			return parent::fetchPair($cond);
	}
	
	
	public function getCollectionPaginate($cond=[]){
		
		$join = " LEFT ";
		$pay_amount = '';
		
		if (! empty ($cond['f_building'] )){
			$join = '';
			
			$build = '  JOIN mis_property_payoption AS prop_pay1 on prop_pay1.popt_id = dmd.cdmd_ref_id and prop_pay1.deleted= 0
						JOIN mis_property as prop on prop.prop_id = prop_pay1.popt_prop_id and prop.deleted = 0 and prop.prop_building = ' .$cond['f_building'] . ' ';
		}
			
		if (! empty ($cond['f_property'] ))
		{
			$join = '';
			
		    //$prop = ' JOIN mis_property_payoption AS prop_pay on prop_pay.popt_id = dmd.cdmd_ref_id and prop_pay.deleted= 0 and prop_pay.popt_prop_id = ' .$cond['f_property'] . ' ';
		    $prop = ' join mis_documents as agreement         on agreement.doc_id = dmd.cdmd_oth_id and agreement.deleted= 0 
            AND agreement.doc_ref_type = 3 
            AND agreement.doc_type = 201
            AND agreement.doc_ref_id = ' .$cond['f_property'] . ' ';
		    
		    $pay_amount = ' cashdmd.bill_paid as coll_amount, ';
		}
		
		 unset($cond['f_building']);
		 unset($cond['f_property']);
		
		$this->paginate ( "select $this->_table.* ,
				to_char(coll_paydate,'DD/MM/YYYY') as coll_date,
					  cashdmd.cdmd_note,
					  cashdmd.bill_no,
                      cashdmd.bill_paid,
                      $pay_amount
					  cashdmd.cdmd_narr,	
					  cust.cust_name
					", "from $this->_table 
					left join mis_customer as cust on cust.cust_id = $this->_table.coll_cust and cust.deleted = 0
					$join join (SELECT cdet_coll_id,
					       string_agg(cdmd_note,',') AS cdmd_note,
						   string_agg(SUBSTRING (cdmd_narration,0, 50) || '..',',') AS cdmd_narr,
	       				   array_to_string(array_agg('AST/00' || bill_id || '-' || cdet_amt_paid ), ', ') AS bill_no,
                           array_to_string(array_agg(cdet_amt_paid), ', ') AS bill_paid
					FROM mis_collection_det
					LEFT JOIN mis_cash_demand AS dmd ON dmd.cdmd_id= mis_collection_det.cdet_bill_id
					AND dmd.deleted = 0
					AND mis_collection_det.cdet_src_type=2
					LEFT JOIN mis_bill AS bill ON bill.bill_id= mis_collection_det.cdet_bill_id
					AND bill.deleted = 0
					AND mis_collection_det.cdet_src_type=1

					$prop

					$build

					WHERE mis_collection_det.deleted = 0
					GROUP BY cdet_coll_id) as cashdmd on cashdmd.cdet_coll_id = $this->_table.coll_id

				" );
		
		if (! empty ($cond['f_selCustomer'] ))
			$this->_where [] = "cust_id = :f_selCustomer";
		
		if (! empty ($cond['f_type'] ))
			$this->_where [] = "coll_src_type = :f_type";
			
		$this->_order [] = 'coll_id DESC';
		
		return parent::fetchAll ($cond);
	}
	
	public function getCollectionExpDet($cond=[]) {
		$this->query ( "select $this->_table.* ,
				paydet.* 
				from $this->_table 
				left join mis_collection_det as paydet on paydet.pdet_coll_id = $this->_table.coll_id and paydet.deleted = 0		
				left join mis_expense as expens on expens.exp_id = paydet.pdet_exp_id and expens.deleted = 0			
				" );
		if (! empty ($cond['coll_id'] ))
			$this->_where [] = "coll_id= :coll_id";
		
		return parent::fetchAll($cond);
	}
	
	public function getCollectionDetById($id){
		return parent::getById ($id);
	}

	public function getCollectionById($id){
		return parent::getById ($id);
	}
	
	public function getCollectionDetByPayId($cond=[]){
		
		$this->query ( "select $this->_table.* ,
                to_char(coll_paydate,'DD/MM/YYYY') as coll_date_lbl,
                case 
                    when coll_coll_mode = 1 THEN 'Cash'
                    when coll_coll_mode = 2 THEN 'Cheque'
                end as coll_mode_lbl,
            
				files.file_id,
				file_actual_name||'.'||file_exten as file_name,
                case when coll_cust = '-1' then 'AST Global Managed Properties' 
                     else cust.cust_name end as cust_name
				from $this->_table
				left join mis_customer as cust on cust.cust_id = $this->_table.coll_cust and cust.deleted = 0
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.coll_id and files.file_type = " . DOC_TYPE_COLL . " and files.deleted = 0
					" );
		$this->_where [] = "coll_id= :coll_id";
		
		return parent::fetchRow($cond);
	}
	
	
	public function getCollectionBillDetByCollId($cond=[]){
	    
	    $this->query ( "SELECT bdet_item,
                               bdet_id, 
                               item_name,
                               item_code,
                               bdet_qty ,
                               bdet_amt,
                               ROUND(bdet_qty * bdet_amt, 3) AS total_amt,
                               CASE
                                   WHEN bill_oribill_amt = 0 THEN 0
                                   ELSE ROUND((cdet_amt_paid / bill_oribill_amt) * (bdet_qty * bdet_amt), 3)
                               END AS revenue_share, 
                               bill_id,
                               vhl_no,
                               comp_name,
                               comp_disp_name,
                               vhl_id 
                        FROM mis_collection 
                        LEFT JOIN mis_collection_det AS colldet ON coll_id = colldet.cdet_coll_id
                        AND colldet.deleted = 0
                        JOIN mis_bill AS bill ON bill.bill_id = colldet.cdet_bill_id
                        AND bill.deleted = 0
                        LEFT JOIN mis_bill_det AS billdet ON billdet.bdet_bill_id = bill.bill_id
                        AND billdet.deleted = 0
                        LEFT JOIN mis_item AS item ON item.item_id = billdet.bdet_item
                        AND item.deleted = 0
                        AND item.item_type = 1
                        LEFT JOIN mis_vehicle AS veh ON veh.vhl_id = item.item_vehicle
                        AND veh.deleted = 0
                        LEFT JOIN core_company AS comp ON comp.comp_id = veh.vhl_company
                        AND comp.deleted = 0" );
	    $this->_where [] = "coll_id= :coll_id";
	    
	    $this->_order [] = "bill_id ASC";
	    
	    
	    return parent::fetchAll($cond);
	}
	
	
	
	
	public function getPaymentcollection($cond=array()){
		$cond = array_filter($cond);
		
		$join = " LEFT ";
		
		if (! empty ($cond['f_building'] )){
			$join = '';
			$build = '  JOIN mis_property_payoption AS prop_pay1 on prop_pay1.popt_id = dmd.cdmd_ref_id and prop_pay1.deleted= 0
						JOIN mis_property as prop on prop.prop_id = prop_pay1.popt_prop_id and prop.deleted = 0 and prop.prop_building = ' .$cond['f_building'] . ' ';
		}
		
		if (! empty ($cond['f_property'] ))
		{
			$join = '';
			//$prop = ' JOIN mis_property_payoption AS prop_pay on prop_pay.popt_id = dmd.cdmd_ref_id and prop_pay.deleted= 0 and prop_pay.popt_prop_id = ' .$cond['f_property'] . ' ';
			
			$prop = ' join mis_documents as agreement         on agreement.doc_id = dmd.cdmd_oth_id and agreement.deleted= 0
            AND agreement.doc_ref_type = 3
            AND agreement.doc_type = 201
            AND agreement.doc_ref_id = ' .$cond['f_property'] . ' ';
			
			$pay_amount = ' cashdmd.bill_paid as coll_amount, ';
		}
		
		unset($cond['f_building']);
		unset($cond['f_property']);
		
		
		if (! empty ($cond['f_monthpick'] )) {
			$monthYear = explode ( '/',$cond['f_monthpick'] );
			$where [] = "(EXTRACT(month FROM coll_paydate) = '$monthYear[0]' AND EXTRACT(year FROM coll_paydate) = '$monthYear[1]' )";
			unset ($cond['f_monthpick'] );
		}
		if (! empty ($cond['f_selCustomer'] ))
			$where [] = "coll_cust = :f_selCustomer";
		
		if (! empty ($cond['f_type'] ))
			$where [] = "coll_src_type = :f_type";
		
		$where [] = " mis_collection.coll_app_status = 1 ";
		
		$where [] = ' mis_collection.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query( "select $this->_table.* ,
				to_char(coll_paydate,'DD/MM/YYYY') as coll_date,
				cust.cust_name,
				cashdmd.cdmd_note,
				cashdmd.bill_no,
                $pay_amount
			    cashdmd.cdmd_narr	
				from $this->_table
				left join mis_customer as cust on cust.cust_id = $this->_table.coll_cust and cust.deleted = 0
				$join join (SELECT cdet_coll_id,
				       string_agg(cdmd_note,',') AS cdmd_note,
					   string_agg(SUBSTRING (cdmd_narration,0, 50) || '..',',') AS cdmd_narr,
       				   array_to_string(array_agg('AST/00' || bill_id || '-' || cdet_amt_paid ), ', ') AS bill_no,
                       array_to_string(array_agg(cdet_amt_paid), ', ') AS bill_paid
				FROM mis_collection_det
				LEFT JOIN mis_cash_demand AS dmd ON dmd.cdmd_id= mis_collection_det.cdet_bill_id
				AND dmd.deleted = 0
				AND mis_collection_det.cdet_src_type=2
				LEFT JOIN mis_bill AS bill ON bill.bill_id= mis_collection_det.cdet_bill_id
				AND bill.deleted = 0
				AND mis_collection_det.cdet_src_type=1

				$prop

				$build

				WHERE  mis_collection_det.deleted = 0
				GROUP BY cdet_coll_id) as cashdmd on cashdmd.cdet_coll_id = $this->_table.coll_id

				" .$where . " order by coll_paydate DESC");
		

		return parent::fetchQuery ($cond);
	}
	
	
	public function getPaymentcollectionByInvoice($cond=array()){
		$cond = array_filter($cond);
		
		$where [] = " cdet_bill_id = :cdet_bill_id ";
		$where [] = " mis_collection.coll_status = 1 ";
		$where [] = ' mis_collection.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query( "select $this->_table.* ,
				to_char(coll_paydate,'DD/MM/YYYY') as coll_date,
				colldet.*,
				files.file_id,
				file_actual_name||'.'||file_exten as file_name
				from $this->_table
				left join mis_collection_det as colldet on colldet.cdet_coll_id = $this->_table.coll_id and colldet.deleted = 0 and cdet_src_type = 1
				LEFT JOIN core_files as files on files.file_ref_id = $this->_table.coll_id and files.file_type = " . DOC_TYPE_COLL . " and files.deleted = 0
				" .$where . " order by coll_paydate DESC");
		
		return parent::fetchQuery ($cond);
	}
	
	
	public function deleteCollection($id) {
		return parent::delete ( $id );
	}
	
	
	public function getVehicleRevenueByCompany($cond=[]){
	    
	    $this->query ( "SELECT vhl_no,
                        SUM(rev_revenue) AS total_amount,
                        string_agg(coll_file_no || '-' || coll_amount, ',</br> ') AS inc_details
                        FROM mis_collection 
                        LEFT JOIN mis_collection_revenue AS vhlrev ON vhlrev.rev_coll_id = coll_id
                        AND vhlrev.deleted = 0
                        LEFT JOIN mis_vehicle AS vhl ON vhl.vhl_id = vhlrev.rev_vhl_id
                        AND vhl.deleted = 0" );
	    
	    $this->_where [] = "mis_collection.coll_app_status= 1";
	    $this->_where [] = "mis_collection.coll_src_type= 1";    
	    $this->_where [] = "vhl_company= :vhl_company";
	        
	    if (! empty ($cond['f_monthpick'] )) {
	        //$monthYear = explode ( '/',$cond['f_monthpick'] );
	        //$this->_where [] = "(EXTRACT(month FROM coll_paydate) = '$monthYear[0]' AND EXTRACT(year FROM coll_paydate) = '$monthYear[1]' )";
	        unset ($cond['f_monthpick'] );
	    }
	    else {
	        $this->_where [] = " AND TO_CHAR(COALESCE(mis_collection.coll_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	    }
	    
	    
	    $this->_group [] = "vhl_id";
	    
	    $this->_order [] = "vhl_no ASC";
	    
	    
	    return parent::fetchAll($cond);
	}
	
	
	public function getPropertyRevenueByBuilding($cond=[]){
	    
	    $this->query ( "SELECT bld_name,
                           SUM(cdet_amt_paid) AS total_income,
                           string_agg(coll_file_no || '-' || coll_amount, ',</br> ') AS inc_details
                    FROM mis_collection
                    LEFT JOIN mis_collection_det AS coldet ON coll_id = coldet.cdet_coll_id
                        AND coldet.deleted = 0
                        AND coldet.cdet_src_type = 2
                    LEFT JOIN mis_cash_demand AS dmd ON dmd.cdmd_id = coldet.cdet_bill_id
                        AND dmd.deleted = 0
                    LEFT JOIN mis_documents AS doc ON doc.doc_id = dmd.cdmd_oth_id
                        AND doc.doc_ref_type = 3
                        AND doc.doc_type = 201
                    LEFT JOIN mis_property prop ON prop.prop_id = doc.doc_ref_id
                    LEFT JOIN mis_building AS build ON build.bld_id = prop.prop_building" );
	    
	    $this->_where [] = "mis_collection.coll_app_status= 1";
	    $this->_where [] = "mis_collection.coll_src_type= 2";
	    $this->_where [] = "build.bld_comp= :bld_comp";
	    
	    if (! empty ($cond['f_monthpick'] )) {
	        $monthYear = explode ( '/',$cond['f_monthpick'] );
	        $this->_where [] = "(EXTRACT(month FROM coll_paydate) = '$monthYear[0]' AND EXTRACT(year FROM coll_paydate) = '$monthYear[1]' )";
	        unset ($cond['f_monthpick'] );
	    }
	    else {
	        $this->_where [] = " AND TO_CHAR(COALESCE(mis_collection.coll_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
	    }
	    
	    
	    $this->_group [] = "build.bld_name";
	    
	    $this->_order [] = "build.bld_name ASC";
	    
	    
	    return parent::fetchAll($cond);
	}
	
}


