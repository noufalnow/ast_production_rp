<?php
class service extends db_table {
	protected $_table = "mis_vhl_service";
	protected $_pkey = "srv_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getDetById($id) {
		return parent::getById ($id);
	}
	
	public function getDetByVehicleId($cond) {
		
		$this->query ( "select *,
			
					case when srv_type = 1 then 'Major Srv'
						when srv_type = 2 then 'Minor Srv'
					end as srv_type,

					case when srv_wash = 1 then 'No'
						when srv_wash = 2 then 'Yes'
					end as srv_wash,

					case when srv_greese = 1 then 'No'
						when srv_greese = 2 then 'Yes'
					end as srv_greese,

					case when srv_nxt_type = 1 then 'Major Srv'
						when srv_nxt_type = 2 then 'Minor Srv'
					end as srv_nxt_type,

					to_char(srv_date_start,'DD/MM/YYYY') as srv_date_start,
					to_char(srv_date_next,'DD/MM/YYYY') as srv_date_next,

	
				emp.emp_fname ||' '||emp.emp_mname||' '||emp.emp_lname as done_by
 				from $this->_table
				left join mis_employee as emp on emp.emp_id  = srv_done_by and emp.deleted = 0 
				" );
		
		$this->_where [] = "srv_vhl_id= :srv_vhl_id";
		
		$this->_order [] = 'srv_id DESC';
		
		
		return parent::fetchAll ( $cond );
	}
	
	public function getServiceReport($cond=[]) {
		
		$this->query ( "SELECT 
                        mis_vhl_service.*,
                        vhl_no,
                        CASE 
                            WHEN mis_vhl_service.srv_type = 1 THEN 'Major Srv'
                            WHEN mis_vhl_service.srv_type = 2 THEN 'Minor Srv'
                        END AS srv_type_lbl,
                        CASE 
                            WHEN mis_vhl_service.srv_wash = 1 THEN 'No'
                            WHEN mis_vhl_service.srv_wash = 2 THEN 'Yes'
                        END AS srv_wash_lbl,
                        CASE 
                            WHEN mis_vhl_service.srv_greese = 1 THEN 'No'
                            WHEN mis_vhl_service.srv_greese = 2 THEN 'Yes'
                        END AS srv_greese_lbl,
                        CASE 
                            WHEN mis_vhl_service.srv_nxt_type = 1 THEN 'Major Srv'
                            WHEN mis_vhl_service.srv_nxt_type = 2 THEN 'Minor Srv'
                        END AS srv_nxt_type_lbl,
                        TO_CHAR(mis_vhl_service.srv_date_start, 'DD/MM/YYYY') AS srv_date_start_fmt,
                        TO_CHAR(mis_vhl_service.srv_date_next, 'DD/MM/YYYY') AS srv_date_next_fmt,
                        emp.emp_fname || ' ' || emp.emp_mname || ' ' || emp.emp_lname AS done_by,
                        -- Concatenate item details into a single string
                        array_to_string(
                            ARRAY(
    
                            SELECT 
                                    'Item: ' || item.item_name || 
                                    ', Quantity: ' || COALESCE(sdt.sdt_qty, 'N/A') || 
                                    ', Unit: ' || COALESCE(sdt.sdt_unit, 'N/A') || 
                                    ', Done By: ' || COALESCE(
                                      emp2.emp_fname || ' ' || emp2.emp_mname || ' ' || emp2.emp_lname, 
                                      'N/A'
                                    ) || 
                                    ', Note: ' || COALESCE(sdt.sdt_note, 'N/A') || 
                                    ', Price: ' || COALESCE(sdt.sdt_price::TEXT, 'N/A') || 
                                    ', Bill: ' || COALESCE(sdt.sdt_billid::TEXT, 'N/A') 
                                  FROM 
                                    mis_vhl_srv_det AS sdt 
                                    LEFT JOIN mis_item AS item ON item.item_id = sdt.sdt_item 
                                    AND item.deleted = 0 
                                    LEFT JOIN mis_employee AS emp2 ON emp2.emp_id = sdt.sdt_done_by 
                                    AND emp2.deleted = 0 
                                  WHERE 
                                    sdt.sdt_srv_id = mis_vhl_service.srv_id 
                                    AND sdt.deleted = 0

                            ), ' | '
                        ) AS item_details,

                        (
                            SELECT SUM(sdt.sdt_price)
                            FROM mis_vhl_srv_det AS sdt
                            WHERE sdt.sdt_srv_id = mis_vhl_service.srv_id
                            AND sdt.deleted = 0
                        ) AS total_item_price,
                    man.vman_name,
                    type.type_name

                    FROM 
                        mis_vhl_service
                    LEFT JOIN 
                        mis_employee AS emp ON emp.emp_id = mis_vhl_service.srv_done_by AND emp.deleted = 0
                    LEFT JOIN 
                        mis_vehicle AS vhl ON vhl.vhl_id = mis_vhl_service.srv_vhl_id AND vhl.deleted = 0
                    LEFT JOIN mis_vehicle_type as type on type.type_id = vhl.vhl_type and type.deleted = 0
				    LEFT JOIN mis_vehicle_man as man on man.vman_id = vhl.vhl_man and man.deleted = 0" );
		
		$this->_group = ['man.vman_name','type.type_name','mis_vhl_service.srv_id','emp.emp_fname','emp.emp_mname','emp.emp_lname','vhl.vhl_no'];
				
		if (!empty  ( $cond ['f_vhlno'] ))
			$this->_where [] = "srv_vhl_id= :f_vhlno";
		
		if (!empty  ( $cond ['f_type'] ))
		    $this->_where [] = "srv_type= :f_type";
			
		if (!empty  ( $cond ['vhl_comm_status'] ))
			$this->_where [] = "vhl_comm_status= :vhl_comm_status";
		
			
		if (! empty ( $cond ['f_monthpick'] )) {
		    $monthYear = explode ( '/', $cond ['f_monthpick'] );
		    $this->_where [] = "(EXTRACT(month FROM srv_date_start) = '$monthYear[0]' AND EXTRACT(year FROM srv_date_start) = '$monthYear[1]' )";
		    unset($cond ['f_monthpick']);
		}
		
		if (! empty ( $cond ['f_dtfrom'] ))
		    $this->_where [] = "srv_date_start >= :f_dtfrom";
		    
	    if (! empty ( $cond ['f_dtto'] ))
	        $this->_where [] = "srv_date_start <= :f_dtto";
		
		$this->_order [] = 'srv_id DESC';
		
		
		//s($this->_where);
		
		//parent::dbug($cond);
		
		
		return parent::fetchAll ( $cond );
	}

}


