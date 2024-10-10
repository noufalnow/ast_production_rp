<?php
class vehicle extends db_table {
	protected $_table = "mis_vehicle";
	protected $_pkey = "vhl_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getVehiclePair($cond = array()) {
		$this->query ( "select vhl_id,vhl_no from $this->_table" );
		$this->_order [] = 'vhl_no ASC';
		
		return parent::fetchPair ( $cond );
	}
	public function getVehiclePaginate($cond){
		
		$this->paginate ( "select $this->_table.*, 
				comp.comp_disp_name,
				type.type_name,
                man.vman_name
				", "from $this->_table 
				left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0
				left join mis_vehicle_type as type on type.type_id = $this->_table.vhl_type and type.deleted = 0
				left join mis_vehicle_man as man on man.vman_id = $this->_table.vhl_man and man.deleted = 0


				" );
		
		if (!empty ( $cond ['f_vhlno'] ))
			$this->_where [] = "vhl_no like '%' || :f_vhlno || '%'";
		
		if (!empty ( $cond ['f_model'] ))
			$this->_where [] = "cast(vhl_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$this->_where [] = "vhl_company = :f_company";
		
		if (! empty ( $cond ['f_type'] ))
			$this->_where [] = "vhl_type = :f_type";
		
		
		$this->_order [] = 'vhl_comm_status ASC, type.type_name ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getVehicleDet($cond) {
		$this->query ( "select $this->_table.*, 
				cust_name,
				emp_fname ||' '||emp_mname||' '||emp_lname as emp_name,
				comp.comp_name,
				type.type_name,
                 man.vman_name
				from $this->_table 
				left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0
				left join mis_vehicle_type as type on type.type_id = $this->_table.vhl_type and type.deleted = 0
				left join mis_employee as emp on emp.emp_id = $this->_table.vhl_employed and emp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.vhl_vendor and cust.deleted = 0
				left join mis_vehicle_man as man on man.vman_id = $this->_table.vhl_man and man.deleted = 0
				" );
		if (! empty ( $cond ['vhl_id'] ))
			$this->_where [] = "vhl_id= :vhl_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getVehicleDetById($id){
		return parent::getById ($id);
	}
	
	public function getVehicleById($id){
		return parent::getById ($id);
	}
	
	public function deleteVehicle($id) {
		return parent::delete ( $id );
	}
	
	public function getVehicleReport($cond=array()){		
		@$cond = array_filter ( $cond );
		if (!empty ( $cond ['f_vhlno'] ))
			$where [] = "vhl_no like '%' || :f_vhlno || '%'";
		
		if (!empty ( $cond ['f_model'] ))
			$where [] = "cast(vhl_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "vhl_company = :f_company";
		
		if (! empty ( $cond ['vhl_comm_status'] ))
			$where [] = "vhl_comm_status = :vhl_comm_status";
			
		if (! empty ( $cond ['f_type'] ))
			$where [] = "vhl_type = :f_type";
		
		if (! empty ( $cond ['f_monthpick'] )){
			$monthYear = explode('/',$cond ['f_monthpick']);
			$where [] = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' )";
			unset($cond ['f_monthpick']);
		}
			
		$where [] = ' mis_vehicle.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "SELECT *,
				emp_fname ||' '||emp_mname||' '||emp_lname as emp_name,
				cust_name,
                man.vman_name
				FROM mis_vehicle
				left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0
				left join mis_vehicle_type as type on type.type_id = $this->_table.vhl_type and type.deleted = 0
				left join mis_employee as emp on emp.emp_id = $this->_table.vhl_employed and emp.deleted = 0
				left join mis_customer as cust on cust.cust_id = $this->_table.vhl_vendor and cust.deleted = 0
                left join mis_vehicle_man as man on man.vman_id = $this->_table.vhl_man and man.deleted = 0
				$where
				ORDER BY type.type_name ASC" );
		
		return parent::fetchQuery ( $cond );
	}
		
		
		public function getVehicleDocReport($cond = array()){
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_vhlno'] ))
			$where [] = "vhl_no like '%' || :f_vhlno || '%'";
		
		if (! empty ( $cond ['f_model'] ))
			$where [] = "cast(vhl_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "vhl_company = :f_company";
		
		if (! empty ( $cond ['f_type'] ))
			$where [] = "vhl_type = :f_type";
		
		if (! empty ( $cond ['f_monthpick'] )) {
			if ($cond ['f_monthpick'] == 'past') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format ( 'Y' ) . "')) ";
			} else if ($cond ['f_monthpick'] == 'exp') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM doc_expiry_month) <= '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format ( 'Y' ) . "')) ";
			} else {
				$monthYear = explode ( '/', $cond ['f_monthpick'] );
				$where [] = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' )";
			}
			unset ( $cond ['f_monthpick'] );
		}
		
		$where [] = ' mis_vehicle.deleted = 0 ';
		// $where [] = ' mis_vehicle.emp_status = 1 ';
		// $where [] = ' doc_type !=2';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
				SELECT *
				FROM mis_vehicle
				left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0
				left join mis_vehicle_type as type on type.type_id = $this->_table.vhl_type and type.deleted = 0
				left join mis_vehicle_man as man on man.vman_id = $this->_table.vhl_man and man.deleted = 0
				INNER JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
				          doc_remarks,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_ref_type = " . DOC_TYPE_VHCL . "
									AND deleted = 0
									GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
									LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
									AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_vehicle.vhl_id
									LEFT JOIN core_files as files on files.file_ref_id = propdocs.doc_id and files.deleted = 0 AND files.file_type IN(4)
									$where
									ORDER BY doc_type,vhl_no DESC,doc_expiry_month ASC" );
		
		// q()
		
		return parent::fetchQuery ( $cond );
	}
		
		
		public function getVehDocExpiryReport($cond = array()){
			$monthYear = explode('/',$cond ['f_monthpick']);
			
			if ($cond ['f_monthpick'] == 'past') {
				$date = new DateTime ();
				$where = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format ( 'Y' ) . "')) ";
			} else {
				$monthYear = explode ( '/', $cond ['f_monthpick'] );
				$where  = " (EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' ) ";
			}
			unset ( $cond ['f_monthpick'] );
			
			$this->query ( "SELECT count(doc_type) AS COUNT,
					case
					when doc_type = 301 then 'Mulkia'
					when doc_type = 302 then 'PDO'
					when doc_type = 303 then 'Fitness'
					when doc_type = 304 then 'IVMS'
					when doc_type = 305 then 'Insurance'
					when doc_type = 306 then 'Mun.Certificate'
					
					END as doc_type
					FROM mis_vehicle
					INNER JOIN
					(SELECT doc_type,
					doc_ref_id,
					doc_expiry_date AS doc_expiry_month
					FROM
					(SELECT max(doc_id) AS mdoc_id
					FROM mis_documents
					WHERE doc_ref_type = 4
					AND deleted = 0
					GROUP BY doc_type,
					doc_ref_type,
					doc_ref_id)max_group
					LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
					AND docs.deleted = 0) AS vehpdocs ON vehpdocs.doc_ref_id = mis_vehicle.vhl_id
					WHERE
					$where
					AND mis_vehicle.deleted = 0
					GROUP BY doc_type" );
					
					return parent::fetchQuery ( $cond );
		}
		
		
		public function getVehicleExpReport($cond = array()){
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_vhlno'] ))
			$where [] = "vhl_no like '%' || :f_vhlno || '%'";
		
		if (! empty ( $cond ['f_model'] ))
			$where [] = "cast(vhl_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "vhl_company = :f_company";

		if (! empty ( $cond ['f_cat'] ))
			$where [] = "vhl_comm_status = :f_cat";
		
		if (! empty ( $cond ['f_type'] ))
			$where [] = "vhl_type = :f_type";
		
		if (! empty ( $cond ['f_monthpick'] )) {
			if ($cond ['f_monthpick'] == 'past') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM exp_billdt) < '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM exp_billdt) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM exp_billdt) < '" . $date->format ( 'Y' ) . "')) ";
			} else if ($cond ['f_monthpick'] == 'exp') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM exp_billdt) <= '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM exp_billdt) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM exp_billdt) < '" . $date->format ( 'Y' ) . "')) ";
			} else {
				$monthYear = explode ( '/', $cond ['f_monthpick'] );
				$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			}
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		
		$where [] = ' mis_vehicle.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
						SELECT vhl_id,
							   exp_id,	
						       vhl_no,
						       vhl_fileno ,
						       vhl_type ,
						       vhl_model,
						       vhl_company,
						       vhl_comm_status,
						       vtype.type_name,
						       expref.eref_id,
						       expref.eref_amount,
						       expense.exp_vendor,
						       expense.exp_company,
						       expense.exp_company,
						       expense.exp_details,
						       expense.exp_pay_mode,
						       to_char(exp_billdt,'DD/MM/YYYY') as exp_billdt,
							   comp.comp_disp_name,
							   pcat.cat_name as pcat,
							   scat.cat_name as scat,
							   ccat.cat_name as ccat,
							   vendor.ven_disp_name,
							   case when exp_pay_mode = 1 then 'Cash'
							   when exp_pay_mode = 2 then 'Credit'
							   end as pay_mod,
                               man.vman_name
						FROM mis_vehicle
						left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0

						LEFT JOIN mis_vehicle_type AS vtype ON vtype.type_id = mis_vehicle.vhl_type
						AND vtype.deleted = 0
						INNER JOIN mis_expense_href AS expref ON expref.eref_main_head_ref = mis_vehicle.vhl_id
						AND expref.deleted = 0
						AND expref.eref_main_head =3
						LEFT JOIN mis_expense AS expense ON expense.exp_id = expref.eref_exp_id
						AND expense.deleted = 0
						AND expense.exp_mainh = 3
						LEFT JOIN core_category AS pcat ON pcat.cat_id = expense.exp_pcat
						AND pcat.cat_type = 1
						AND pcat.deleted = 0
						LEFT JOIN core_category AS scat ON scat.cat_id = expense.exp_scat
						AND scat.cat_type = 2
						AND scat.deleted = 0
						LEFT JOIN core_category AS ccat ON ccat.cat_id = expense.exp_ccat
						AND ccat.cat_type = 3
						AND ccat.deleted = 0
						LEFT JOIN mis_vendor AS vendor ON vendor.ven_id = expense.exp_vendor
						AND vendor.deleted = 0
                        left join mis_vehicle_man as man on man.vman_id = $this->_table.vhl_man and man.deleted = 0
						$where
						ORDER BY vhl_comm_status ASC, type_name ASC,vhl_no ASC" );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getVehicleExpSummary($cond = array()){
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_vhlno'] ))
			$where [] = "vhl_no like '%' || :f_vhlno || '%'";
		
		if (! empty ( $cond ['f_model'] ))
			$where [] = "cast(vhl_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "vhl_company = :f_company";
		
		if (! empty ( $cond ['comm_status'] ))
			$where [] = "vhl_comm_status = :comm_status";
		
		if (! empty ( $cond ['f_type'] ))
			$where [] = "vhl_type = :f_type";
		
		if (! empty ( $cond ['f_monthpick'] )) {
			if ($cond ['f_monthpick'] == 'past') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM exp_billdt) < '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM exp_billdt) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM exp_billdt) < '" . $date->format ( 'Y' ) . "')) ";
			} else if ($cond ['f_monthpick'] == 'exp') {
				$date = new DateTime ();
				$where [] = "((EXTRACT(month FROM exp_billdt) <= '" . $date->format ( 'm' ) . "' AND EXTRACT(year FROM exp_billdt) <= '" . $date->format ( 'Y' ) . "' ) OR
						(EXTRACT(year FROM exp_billdt) < '" . $date->format ( 'Y' ) . "')) ";
			} else {
				$monthYear = explode ( '/', $cond ['f_monthpick'] );
				$where [] = "(EXTRACT(month FROM exp_billdt) = '$monthYear[0]' AND EXTRACT(year FROM exp_billdt) = '$monthYear[1]' )";
			}
			unset ( $cond ['f_monthpick'] );
		}
		
		if (! empty ( $cond ['f_pCatSelect'] ))
			$where [] = "pcat.cat_id = :f_pCatSelect";
		
		if (! empty ( $cond ['f_sCatSelect'] ))
			$where [] = "scat.cat_id = :f_sCatSelect";
		
		if (! empty ( $cond ['f_cCatSelect'] ))
			$where [] = "ccat.cat_id = :f_cCatSelect";
		
		$where [] = ' mis_vehicle.deleted = 0 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "
				SELECT
				SUM(eref_amount) as v_sum,
				vhl_no,
				vhl_comm_status,
				vhl_id

				FROM mis_vehicle
				left join core_company as comp on comp.comp_id = $this->_table.vhl_company and comp.deleted = 0
				LEFT JOIN mis_vehicle_type AS vtype ON vtype.type_id = mis_vehicle.vhl_type
				AND vtype.deleted = 0
				INNER JOIN mis_expense_href AS expref ON expref.eref_main_head_ref = mis_vehicle.vhl_id
				AND expref.deleted = 0
				AND expref.eref_main_head = 3
				LEFT JOIN mis_expense AS expense ON expense.exp_id = expref.eref_exp_id
				AND expense.deleted = 0
				AND expense.exp_mainh = 3
				LEFT JOIN core_category AS pcat ON pcat.cat_id = expense.exp_pcat
				AND pcat.cat_type = 1
				AND pcat.deleted = 0
				LEFT JOIN core_category AS scat ON scat.cat_id = expense.exp_scat
				AND scat.cat_type = 2
				AND scat.deleted = 0
				LEFT JOIN core_category AS ccat ON ccat.cat_id = expense.exp_ccat
				AND ccat.cat_type = 3
				AND ccat.deleted = 0
				LEFT JOIN mis_vendor AS vendor ON vendor.ven_id = expense.exp_vendor
				AND vendor.deleted = 0
				$where
				group BY vhl_comm_status,vhl_no,vhl_id" );
		
		return parent::fetchQuery ( $cond );
	}
}


