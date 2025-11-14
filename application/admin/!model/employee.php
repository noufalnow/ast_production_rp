<?php
class employee extends db_table {
	protected $_table = "mis_employee";
	protected $_pkey = "emp_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function getEmployee($cond) {
		
		$this->query ( "select $this->_table.*, 
				comdept.cmpdept_comp_id,comdept.cmpdept_dept_id,
				comp.comp_name,
				dept.dept_name,
				desig.desig_name,
				desig.desig_id,
				case when empstatus.sts_type = 1 then 'On Leave' 
				 when empstatus.sts_type = 3 then 'Resigned' 
				 when empstatus.sts_type = 4 then 'Terminated' 
				end as emp_mstatus,
				empstatus.sts_remarks,
				empstatus.sts_apply_date,
				empstatus.sts_approval_date,
				empstatus.sts_start_date,
				to_char(sts_start_date,'DD/MM/YYYY') as sts_start_date,
				to_char(sts_end_date,'DD/MM/YYYY') as sts_end_date,
				empstatus.sts_type,
				empcontract.emc_status
				from $this->_table 
				left join core_comp_department as comdept on comdept.cmpdept_id = $this->_table.emp_comp_dept and comdept.deleted = 0
				left join core_company as comp on comp.comp_id = comdept.cmpdept_comp_id and comp.deleted = 0
				left join core_department as dept on dept.dept_id = comdept.cmpdept_dept_id and dept.deleted = 0
				left join core_designation as desig on desig.desig_id = $this->_table.emp_desig and desig.deleted = 0
				left join (SELECT max(sts_id) AS max_status_id,sts_emp_id
				   FROM mis_employee_status
				   WHERE deleted = 0
				   GROUP BY sts_emp_id) max_status on max_status.sts_emp_id = $this->_table.emp_id
				left join mis_employee_status as empstatus on empstatus.sts_id = max_status.max_status_id and empstatus.deleted = 0 

				left join (SELECT max(emc_id) AS max_contract_id,emc_emp_id
				   FROM mis_emp_contract
				   WHERE deleted = 0
				   GROUP BY emc_emp_id) max_contract on max_contract.emc_emp_id = $this->_table.emp_id
				left join mis_emp_contract as empcontract on empcontract.emc_id = max_contract.max_contract_id and empcontract.deleted = 0 

				" );
		if (!empty  ( $cond ['emp_id'] ))
			$this->_where [] = "emp_id= :emp_id";

		if (!empty ( $cond ['ex_emp_id'] ))
			$this->_where [] = "emp_id!= :ex_emp_id";
		
		if (!empty  ( $cond ['emp_no'] ))
			$this->_where [] = "emp_no= :emp_no";

		return parent::fetchRow ( $cond );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getEmployeeById($id) {
		return parent::getById ($id);
	}

	public function deleteEmployee($id) {
		return parent::delete ( $id );
	}

	public function getEmployeesPaginate($cond = array()) {
		
		$this->paginate ( "select $this->_table.*, 
				comdept.cmpdept_comp_id,comdept.cmpdept_dept_id,
				comp.comp_disp_name,
				dept.dept_name,
				desig.desig_name,
				desig.desig_id,
				case when empstatus.sts_type = 1 then 'On Leave' 
				 when empstatus.sts_type = 3 then 'Resigned' 
				 when empstatus.sts_type = 4 then 'Terminated' 
				end as emp_mstatus
				", "from $this->_table 
				left join core_comp_department as comdept on comdept.cmpdept_id = $this->_table.emp_comp_dept and comdept.deleted = 0
				left join core_company as comp on comp.comp_id = comdept.cmpdept_comp_id and comp.deleted = 0
				left join core_department as dept on dept.dept_id = comdept.cmpdept_dept_id and dept.deleted = 0
				left join core_designation as desig on desig.desig_id = $this->_table.emp_desig and desig.deleted = 0
				left join (SELECT max(sts_id) AS max_status_id,sts_emp_id
				   FROM mis_employee_status
				   WHERE deleted = 0
				   GROUP BY sts_emp_id) max_status on max_status.sts_emp_id = $this->_table.emp_id
				left join mis_employee_status as empstatus on empstatus.sts_id = max_status.max_status_id and empstatus.deleted = 0 "
				);
		if (!empty ( $cond ['f_fileno'] ))
			$this->_where [] = "
					(lower(emp_fileno) like '%' || lower(:f_fileno) || '%')";

		if (!empty ( $cond ['f_name'] ))
			$this->_where [] = "((lower(emp_fname) like '%' || lower(:f_name) || '%' 
					OR lower(emp_mname) like '%' || lower(:f_name) || '%' 
					OR lower(emp_lname) like '%' || lower(:f_name) || '%' 
					)OR
					(lower(emp_fname)||' '||lower(emp_mname)||' '||lower(emp_lname) like '%' || lower(:f_name) || '%'))";
		
		if (! empty ( $cond ['f_company'] ))
			$this->_where [] = "cmpdept_comp_id = :f_company";
		
		if (! empty ( $cond ['f_dept'] ))
			$this->_where [] = "cmpdept_id = :f_dept";
		
		if (! empty ( $cond ['f_desig'] ))
			$this->_where [] = "desig_id = :f_desig";
		
		if (! empty ( $cond ['f_status'] ))
			$this->_where [] = "empstatus.sts_type = :f_status";
		else {
			//$this->_where [] = "max_status.max_status_id IS NULL";
			$this->_where [] = ' mis_employee.emp_status = 1 ';
		}
		
		if (! empty ( $cond ['f_natonality'] ))
			$this->_where [] = "emp_nationality = :f_natonality";
		
		$this->_order [] = 'emp_fname ASC';
		// $this->_order [] = 'emp_uname DESC';
		
		return parent::fetchAll ( $cond );
	}
	public function getCount($cond) {
		$cond ['emp_status'] = 1;
		
		$this->query ( "select count(*) from $this->_table" );
		// $this->_where[] = "emp_id= :emp_id";
		$this->_where [] = "emp_status= :emp_status";
		// $this->_group [] = 'emp_id';
		$this->_group [] = 'emp_uname';
		
		return parent::fetchResult ( $cond );
	}
	
	public function getEmployeesReport($cond = array()){
		// data pair function below reflect the changes
		@$cond = array_filter($cond);
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );

		$this->query (
				"SELECT emp_id,
					       emp_no,
					       emp_fileno,
					       emp_mobileno,
					       emp_fname,
					       emp_mname,
					       emp_lname,
					       emp_nationality,
					       to_char(emp_dob,'DD/MM/YYYY') as emp_dob,
						   to_char(emp_doj,'DD/MM/YYYY') as emp_doj,
					       emp_comp_dept,
					       emp_desig,
					       emp_status,
					       emp_bank,
					       emp_branch,
					       emp_accountno,
					       comdept.cmpdept_comp_id,
					       comdept.cmpdept_dept_id,
					       comp.comp_disp_name,
					       dept.dept_name,
					       desig.desig_name,
					       desig.desig_id,
					       pays.*
					FROM mis_employee
					LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
					AND comdept.deleted = 0
					LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
					AND comp.deleted = 0
					LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
					AND dept.deleted = 0
					LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
					AND desig.deleted = 0
					LEFT JOIN
					  (SELECT pay_total,
					          max_group.pay_emp_id
					   FROM
					     (SELECT max(pay_id) AS mpay_id,
					             pay_emp_id
					      FROM mis_employee_pay
					      WHERE deleted = 0
					      GROUP BY pay_emp_id)max_group
					   LEFT JOIN mis_employee_pay AS pay ON pay.pay_id = max_group.mpay_id
					   AND pay.deleted = 0) pays ON pays.pay_emp_id = mis_employee.emp_id
					$where 
					ORDER BY comp.comp_disp_name ASC, emp_fileno ASC" );
		
		return parent::fetchQuery($cond);
		
	}
	
	
	public function getEmpContractReport($cond = array()){
			// data pair function below reflect the changes
		@$cond = array_filter ( $cond );

		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		$where [] = ' mis_employee.emp_desig IN (8,9) ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query (
				"SELECT emp_id,
				emp_fileno,
				emp_fname,
				emp_mname,
				emp_lname,
				emp_desig,
				emp_status,
				comp.comp_disp_name,
				dept.dept_name,
				desig.desig_name,
				desig.desig_id,
				vhl_no,
				cust_name,
				emc_project,
				emc_location,
				to_char(emc_date_start,'DD/MM/YYYY') as emc_date_start,
				emc_date_end,
				emc_note,
				sts_type,
				case 
				 when empstatus.sts_type = 1 then 'On Leave'
				 when emc_status = 1 then 'On contract' 
				 when emc_status = 2 then 'Free/Available' 
				end as con_status,
				case when emc_status = 2 then to_char(emc_date_end,'DD/MM/YYYY')  else to_char(emc_date_start,'DD/MM/YYYY')
				end as emc_date_start,
				emc_status
				FROM mis_employee
				LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
				AND comdept.deleted = 0
				LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
				AND comp.deleted = 0
				LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
				AND dept.deleted = 0
				LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
				AND desig.deleted = 0

				left join (SELECT max(emc_id) AS max_contract_id,emc_emp_id
				   FROM mis_emp_contract
				   WHERE deleted = 0
				   GROUP BY emc_emp_id) max_contract on max_contract.emc_emp_id = $this->_table.emp_id
				left join mis_emp_contract as empcontract on empcontract.emc_id = max_contract.max_contract_id and empcontract.deleted = 0 

				left join mis_customer as cust on cust.cust_id = empcontract.emc_cust_id and cust.deleted = 0		
				left join mis_vehicle as vhl on vhl.vhl_id = empcontract.emc_vhl_id and vhl.deleted = 0	

				left join (SELECT max(sts_id) AS max_status_id,sts_emp_id
				   FROM mis_employee_status
				   WHERE deleted = 0
				   GROUP BY sts_emp_id) max_status on max_status.sts_emp_id = $this->_table.emp_id
				left join mis_employee_status as empstatus on empstatus.sts_id = max_status.max_status_id and empstatus.deleted = 0

				$where
				ORDER BY con_status ASC NULLS FIRST,cust_name ASC " );
				
				return parent::fetchQuery($cond);
	}
	
	public function getEmployeesSalaryList($cond = array(),$setOrder=NULL){
			// data pair function below reflect the changes
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
		
		if (! empty ( $cond ['sdet_group_exclude'] ))
			$where [] = "sdet_group <> :sdet_group_exclude";
				
		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		//$where [] = ' sdet_sal_id = :sdet_sal_id '; 	/** Salary update issue on new employee fix condition is added within the join statement*/
		
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		if($setOrder)
			//$order = " Order by sdet_category ASC,sdet_group ASC, emp_bank_id ASC, emp_fileno ASC ";
			$order = " Order by sdet_category ASC,emp_bank_id ASC";
		else 
			$order = " ORDER BY sdet_group ASC, emp_fileno ASC ";
		
		$this->query ( "SELECT emp_id,
						       emp_no,
						       emp_fileno,
						       emp_mobileno,
						       emp_fname,
						       emp_mname,
						       emp_lname,
						       emp_nationality,
						       to_char(emp_dob,'DD/MM/YYYY') AS emp_dob,
						       to_char(emp_doj,'DD/MM/YYYY') AS emp_doj,
						       emp_comp_dept,
						       emp_desig,
						       emp_status,
						       emp_bank,
						       emp_branch,
						       emp_accountno,
							   emp_bank_id,
						       comdept.cmpdept_comp_id,
						       comdept.cmpdept_dept_id,
						       comp.comp_disp_name,
						       dept.dept_name,
						       desig.desig_name,
						       desig.desig_id,
						       pays.*,
							   saldet.*,
       						   emp_status_month.*,
								case when empstatus.sts_type = 1 then 'On Leave' 
								 when empstatus.sts_type = 3 then 'Resigned' 
								 when empstatus.sts_type = 4 then 'Terminated' 
								end as emp_mstatus
						FROM mis_employee
						LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
						AND comdept.deleted = 0

						LEFT JOIN mis_salary_det AS saldet ON saldet.sdet_emp_id = mis_employee.emp_id
						AND saldet.deleted = 0 AND saldet.sdet_sal_id = :sdet_sal_id
						LEFT JOIN mis_salary as sal ON sal.sal_id = saldet.sdet_sal_id and sal.deleted = 0
						LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
						AND comp.deleted = 0
						LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
						AND dept.deleted = 0
						LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
						AND desig.deleted = 0

						LEFT JOIN
						  (SELECT DISTINCT sts_emp_id AS status_emp
						   FROM mis_employee_status
						   LEFT JOIN mis_salary_det AS saldet ON saldet.sdet_emp_id = mis_employee_status.sts_emp_id
						   AND saldet.deleted = 0
						   AND saldet.sdet_sal_id = :sdet_sal_id
						   LEFT JOIN mis_salary AS sal ON sal.sal_id = saldet.sdet_sal_id
						   AND sal.deleted = 0
						   WHERE mis_employee_status.deleted = 0
						     AND ((date_part('month',sal.sal_paydate) = date_part('month',sts_start_date)
						           AND (date_part('year',sal.sal_paydate) = date_part('year',sts_start_date)))
						          OR (date_part('month',sal.sal_paydate) = date_part('month',sts_end_date)
						              AND (date_part('year',sal.sal_paydate) = date_part('year',sts_end_date)))
						          OR (sal.sal_paydate >= sts_start_date
						              AND sal.sal_paydate <= sts_end_date))) emp_status_month ON emp_status_month.status_emp = mis_employee.emp_id


						left join (SELECT max(sts_id) AS max_status_id,sts_emp_id
						   FROM mis_employee_status
						   WHERE deleted = 0
						   GROUP BY sts_emp_id) max_status on max_status.sts_emp_id = $this->_table.emp_id
						left join mis_employee_status as empstatus on empstatus.sts_id = max_status.max_status_id and empstatus.deleted = 0 
						
						LEFT JOIN
						  (SELECT pay_total,
						          max_group.pay_emp_id
						   FROM
						     (SELECT max(pay_id) AS mpay_id,
						             pay_emp_id
						      FROM mis_employee_pay
						      WHERE deleted = 0
						      GROUP BY pay_emp_id)max_group
						   LEFT JOIN mis_employee_pay AS pay ON pay.pay_id = max_group.mpay_id
						   AND pay.deleted = 0) pays ON pays.pay_emp_id = mis_employee.emp_id".
							$where.	$order );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getEmployeesSalaryReport($cond = array(),$setOrder=NULL){
			// data pair function below reflect the changes
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
		
		if (! empty ( $cond ['sdet_group_exclude'] ))
			$where [] = "sdet_group <> :sdet_group_exclude";
		
		// if (! empty ( $cond ['sdet_sal_id'] ))
		// $where [] = "sdet_sal_id = :sdet_sal_id";
		
		$where [] = ' mis_employee.deleted = 0 ';
		//$where [] = ' mis_employee.emp_status = 1 ';   Show all previous employee status
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		if ($setOrder)
			// $order = " Order by sdet_category ASC,sdet_group ASC, emp_bank_id ASC, emp_fileno ASC ";
			$order = " Order by sdet_category ASC,emp_bank_id ASC";
		else
			$order = " ORDER BY sdet_group ASC, emp_fileno ASC ";
		
		$this->query ( "SELECT emp_id,
						       emp_no,
						       emp_fileno,
						       emp_mobileno,
						       emp_fname,
						       emp_mname,
						       emp_lname,
						       emp_nationality,
						       to_char(emp_dob,'DD/MM/YYYY') AS emp_dob,
						       to_char(emp_doj,'DD/MM/YYYY') AS emp_doj,
						       emp_comp_dept,
						       emp_desig,
						       emp_status,
						       emp_bank,
						       emp_branch,
						       emp_accountno,
							   emp_bank_id,
						       comdept.cmpdept_comp_id,
						       comdept.cmpdept_dept_id,
						       comp.comp_disp_name,
						       dept.dept_name,
						       desig.desig_name,
						       desig.desig_id,
						       pays.*,
							   saldet.*
						FROM mis_employee
						LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
						AND comdept.deleted = 0
												
						INNER JOIN mis_salary_det AS saldet ON saldet.sdet_emp_id = mis_employee.emp_id
						AND saldet.deleted = 0 AND saldet.sdet_sal_id = :sdet_sal_id
												
						LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
						AND comp.deleted = 0
						LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
						AND dept.deleted = 0
						LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
						AND desig.deleted = 0
						LEFT JOIN
						  (SELECT pay_total,
						          max_group.pay_emp_id
						   FROM
						     (SELECT max(pay_id) AS mpay_id,
						             pay_emp_id
						      FROM mis_employee_pay
						      WHERE deleted = 0
						      GROUP BY pay_emp_id)max_group
						   LEFT JOIN mis_employee_pay AS pay ON pay.pay_id = max_group.mpay_id
						   AND pay.deleted = 0) pays ON pays.pay_emp_id = mis_employee.emp_id" . $where . $order );
		
		return parent::fetchQuery ( $cond );
	}
	
	
	
	public function getEmployeesReportPair($cond = array()){
		@$cond = array_filter ( $cond );
		
		//if (! empty ( $cond ['sdet_sal_id'] ))
			//$where [] = "sdet_sal_id = :sdet_sal_id";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "SELECT emp_id,emp_id
			FROM mis_employee
			LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
			AND comdept.deleted = 0
			LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
			AND comp.deleted = 0
			LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
			AND dept.deleted = 0
			LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
			AND desig.deleted = 0

			LEFT JOIN mis_salary_det AS saldet ON saldet.sdet_emp_id = mis_employee.emp_id
			AND saldet.deleted = 0 AND saldet.sdet_sal_id = :sdet_sal_id

			LEFT JOIN
			(SELECT pay_total,
			max_group.pay_emp_id
			FROM
			(SELECT max(pay_id) AS mpay_id,
			pay_emp_id
			FROM mis_employee_pay
			WHERE deleted = 0
			GROUP BY pay_emp_id)max_group
			LEFT JOIN mis_employee_pay AS pay ON pay.pay_id = max_group.mpay_id
			AND pay.deleted = 0) pays ON pays.pay_emp_id = mis_employee.emp_id
			$where
							ORDER BY sdet_group ASC, emp_fileno ASC " );
		
		return parent::fetchQueryPair( $cond );
	}
	
	
	public function getEmployeesReportPayPair($cond = array()){
		@$cond = array_filter ( $cond );
		
		//if (! empty ( $cond ['sdet_sal_id'] ))
			//$where [] = "sdet_sal_id = :sdet_sal_id";
		
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "SELECT emp_id,pay_total
									FROM mis_employee
									LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
									AND comdept.deleted = 0
									LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
									AND comp.deleted = 0
									LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
									AND dept.deleted = 0
									LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
									AND desig.deleted = 0

									LEFT JOIN mis_salary_det AS saldet ON saldet.sdet_emp_id = mis_employee.emp_id
									AND saldet.deleted = 0 AND saldet.sdet_sal_id = :sdet_sal_id

									LEFT JOIN
									(SELECT pay_total,
									max_group.pay_emp_id
									FROM
									(SELECT max(pay_id) AS mpay_id,
									pay_emp_id
									FROM mis_employee_pay
									WHERE deleted = 0
									GROUP BY pay_emp_id)max_group
									LEFT JOIN mis_employee_pay AS pay ON pay.pay_id = max_group.mpay_id
									AND pay.deleted = 0) pays ON pays.pay_emp_id = mis_employee.emp_id
									$where
							ORDER BY sdet_group ASC, emp_fileno ASC " );
		
		return parent::fetchQueryPair ( $cond );
	}
	
	/*
	 * Salary update issue on new employee fix // taking salary details from employee instead of sal_det
	 */

    public function getSalaryDetList($cond)
    {
        $this->query("select $this->_table.emp_id,saldet.*
							from $this->_table
                            left join mis_salary_det as saldet on emp_id = saldet.sdet_emp_id and saldet.deleted= 0 AND sdet_sal_id= :sdet_sal_id
				");

        if (! empty($cond['f_company']))
            $this->_where[] = "cmpdept_comp_id = :f_company";
        if (! empty($cond['f_dept']))
            $this->_where[] = "cmpdept_id = :f_dept";
        if (! empty($cond['f_desig']))
            $this->_where[] = "desig_id = :f_desig";
        if (! empty($cond['f_natonality']))
            $this->_where[] = "emp_nationality = :f_natonality";

        //$this->_where[] = "sdet_sal_id= :sdet_sal_id";
        $this->_where[] = ' mis_employee.emp_status = 1 ';
        $this->_order[] = "sdet_group ASC";

        return parent::fetchAll($cond);
    }
    
    
    public function getEmployeeLeaveNotification($cond=[])
    {
        
        if(!empty($cond["is_mail"]))
        {
            $andmail = " AND ((DATE_PART('day', now() - sts_start_date) > 120
                        AND sts_notif_120 = 0)
                       OR (DATE_PART('day', now() - sts_start_date) > 170
                           AND sts_notif_170 = 0)) ";
            unset($cond["is_mail"]);
        }
        
        $this->query("
                SELECT  
                    emp_id,
                    emp_fileno,
                    empstatus.sts_id,
                    DATE_PART('day', now() - sts_start_date) AS days_on_status,
                    TRIM(BOTH ' ' FROM 
                        COALESCE(emp_fname, '') || ' ' || 
                        COALESCE(emp_mname, '') || ' ' || 
                        COALESCE(emp_lname, '')
                    ) AS full_name,
			       comp.comp_disp_name,
			       dept.dept_name,
			       desig.desig_name,
                    CASE 
                        WHEN DATE_PART('day', now() - sts_start_date) > 170 AND sts_notif_170 = 0 THEN '170NP'
                        WHEN DATE_PART('day', now() - sts_start_date) > 170 AND sts_notif_170 = 1 THEN '170NS'
                        WHEN DATE_PART('day', now() - sts_start_date) > 120 AND sts_notif_120 = 0 THEN '120NP'
                        WHEN DATE_PART('day', now() - sts_start_date) > 120 AND sts_notif_120 = 1 THEN '120NS'
                    END AS status_duration_category,

                    CASE 
                        WHEN DATE_PART('day', now() - sts_start_date) > 170 AND sts_notif_170 = 0 THEN 'More than 170 Days (6 Month Alert)'
                        WHEN DATE_PART('day', now() - sts_start_date) > 120 AND sts_notif_120 = 0 THEN '4 Months completed'
                    END AS notification_text,

                    CASE 
                        WHEN DATE_PART('day', now() - sts_start_date) > 170 THEN 'More than 170 Days (6 Month Alert)'
                        WHEN DATE_PART('day', now() - sts_start_date) > 150 THEN '5 Months completed'
                        WHEN DATE_PART('day', now() - sts_start_date) > 120 THEN '4 Months completed'
                    END AS notification_popup,

                    sts_notif_120,
                    sts_notif_170
                FROM mis_employee
                LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
                AND comdept.deleted = 0
                LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
                AND comp.deleted = 0
                LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
                AND dept.deleted = 0
                LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
                AND desig.deleted = 0
                LEFT JOIN
                  (SELECT MAX(sts_id) AS max_status_id,
                          sts_emp_id
                   FROM mis_employee_status
                   WHERE deleted = 0
                   GROUP BY sts_emp_id) max_status ON max_status.sts_emp_id = mis_employee.emp_id
                LEFT JOIN mis_employee_status AS empstatus ON empstatus.sts_id = max_status.max_status_id
                AND empstatus.deleted = 0
                WHERE mis_employee.deleted = 0
                  AND empstatus.sts_type = 1 
                  AND DATE_PART('day', now() - sts_start_date) > 120
                  $andmail ");
        return parent::fetchQuery($cond);
    }
    
    
    
    
	
	
	public function getEmployeesDocReport($cond = array()){
		@$cond = array_filter ( $cond );
		if (! empty ( $cond ['f_company'] ))
			$where [] = "cmpdept_comp_id = :f_company";
		if (! empty ( $cond ['f_dept'] ))
			$where [] = "cmpdept_id = :f_dept";
		if (! empty ( $cond ['f_desig'] ))
			$where [] = "desig_id = :f_desig";
		if (! empty ( $cond ['f_status'] ))
			$where [] = "emp_status = :f_status";
		if (! empty ( $cond ['f_natonality'] ))
			$where [] = "emp_nationality = :f_natonality";
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
		
		$where [] = ' mis_employee.deleted = 0 ';
		$where [] = ' mis_employee.emp_status = 1 ';
		// $where [] = ' doc_type !=2';
		$where = ' WHERE ' . implode ( ' AND ', $where );
		
		$this->query ( "SELECT emp_id,
				       emp_no,
				       emp_fileno,
				       emp_mobileno,
				       emp_fname,
				       emp_mname,
				       emp_lname,
				       emp_nationality,
					   emp_reg_refno,
					   emp_reg_mulkia,
					   emp_reg_chassis,
				       comp.comp_disp_name,
				       dept.dept_name,
				       desig.desig_name,
				       desig.desig_id,
				       empdocs.*,
					   files.file_id,
					   files.file_exten
				FROM mis_employee
				LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
				AND comdept.deleted = 0
				LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
				AND comp.deleted = 0
				LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
				AND dept.deleted = 0
				LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
				AND desig.deleted = 0
				INNER JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
				          doc_remarks,
				          doc_issue_auth,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_ref_type = " . DOC_TYPE_EMP . " 
				        AND deleted = 0
				      GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
				   LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
				   AND docs.deleted = 0) AS empdocs ON empdocs.doc_ref_id = mis_employee.emp_id
					LEFT JOIN core_files as files on files.file_ref_id = empdocs.doc_id and files.deleted = 0
					AND files.file_type IN(2)
				$where
				ORDER BY doc_type DESC,doc_expiry_month ASC" );
		
		// q()
		
		return parent::fetchQuery ( $cond );
	}
	
	
	public function getEmployeesDocExpiryReport($cond = array()){
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

		$this->query ( "SELECT
                            COALESCE(COUNT(empdocs.doc_type), 0) AS COUNT,
                            doc_types.doc_type,
                            doc_types.doc_type_id
                        FROM 
                            (SELECT 1 AS doc_type_id, 'passport' AS doc_type
                             UNION ALL
                             SELECT 2, 'id'
                             UNION ALL
                             SELECT 3, 'visa'
                             UNION ALL
                             SELECT 4, 'license'
                             UNION ALL
                             SELECT 5, 'insurance'
                             UNION ALL
                             SELECT 6, 'pdolicense'
                             UNION ALL
                             SELECT 7, 'pdopassport'
                             UNION ALL
                             SELECT 8, 'h2scard'
                             UNION ALL
                             SELECT 9, 'oxypassport'
                             UNION ALL
                             SELECT 10, 'oxylicense'
                             UNION ALL
                             SELECT 11, 'oxyh2s'
                             UNION ALL
                             SELECT 12, 'workContract'
                             UNION ALL
                             SELECT 13, 'thirdPartyInsurance'
                             UNION ALL
                             SELECT 14, 'fitnessMedicalReport'
                             UNION ALL
                             SELECT 15, 'opalMedical'
                             UNION ALL
                             SELECT 16, 'opalLC'
                             UNION ALL
                             SELECT 17, 'opalPassport'
                             UNION ALL
                             SELECT 18, 'opalSafetyCertificate') AS doc_types
                        LEFT JOIN 
                            (SELECT 
                                CASE 
                                    WHEN doc_type = 1 THEN 'passport'
                                    WHEN doc_type = 2 THEN 'id'
                                    WHEN doc_type = 3 THEN 'visa'
                                    WHEN doc_type = 4 THEN 'license'
                                    WHEN doc_type = 5 THEN 'insurance'
                                    WHEN doc_type = 6 THEN 'pdolicense'
                                    WHEN doc_type = 7 THEN 'pdopassport'
                                    WHEN doc_type = 8 THEN 'h2scard'
                                    WHEN doc_type = 9 THEN 'oxypassport'
                                    WHEN doc_type = 10 THEN 'oxylicense'
                                    WHEN doc_type = 11 THEN 'oxyh2s'
                                    WHEN doc_type = 12 THEN 'workContract'
                                    WHEN doc_type = 13 THEN 'thirdPartyInsurance'
                                    WHEN doc_type = 14 THEN 'fitnessMedicalReport'
                                    WHEN doc_type = 15 THEN 'opalMedical'
                                    WHEN doc_type = 16 THEN 'opalLC'
                                    WHEN doc_type = 17 THEN 'opalPassport'
                                    WHEN doc_type = 18 THEN 'opalSafetyCertificate'
                                END AS doc_type,
                                doc_type AS doc_type_id,
                                doc_ref_id
                            FROM mis_employee
                            INNER JOIN
                                (SELECT 
                                    doc_type, 
                                    doc_ref_id,
                                    doc_expiry_date AS doc_expiry_month
                                 FROM 
                                    (SELECT MAX(doc_id) AS mdoc_id
                                     FROM mis_documents
                                     WHERE doc_ref_type = 2
                                       AND deleted = 0
                                     GROUP BY doc_type, doc_ref_type, doc_ref_id) AS max_group
                                 LEFT JOIN mis_documents AS docs 
                                     ON docs.doc_id = max_group.mdoc_id
                                    AND docs.deleted = 0) AS empdocs 
                            ON empdocs.doc_ref_id = mis_employee.emp_id
                            WHERE 
                                $where
                                AND mis_employee.deleted = 0
                                AND mis_employee.emp_status = 1) AS empdocs
                        ON doc_types.doc_type_id = empdocs.doc_type_id
                        GROUP BY doc_types.doc_type_id, doc_types.doc_type
                        ORDER BY doc_types.doc_type_id;
                        " );
		
		return parent::fetchQuery ( $cond );
	}
	
	public function getEmployeePair($cond = array()) {
		$this->query ( "select emp_id, emp_fname ||' '||emp_mname||' '||emp_lname as emp_name from $this->_table" );
		
		$cond ['emp_status'] = 1;
		$this->_where [] = "emp_status= :emp_status";
		
		$this->_order [] = 'emp_fname ASC';
		
		return parent::fetchPair ( $cond );
	}
	
	public function getContractEmployeePair($cond = array()) {
	    
	    $this->query("
        select emp_id,
               emp_fname ||' '||emp_mname||' '||emp_lname ||
               CASE WHEN empstatus.sts_type = 1 THEN ' <b>[ON LEAVE] </b>' ELSE '' END
               as emp_name
        from $this->_table
	        
        left join (
            SELECT max(sts_id) AS max_status_id, sts_emp_id
            FROM mis_employee_status
            WHERE deleted = 0
            GROUP BY sts_emp_id
        ) max_status
            on max_status.sts_emp_id = $this->_table.emp_id
	        
        left join mis_employee_status empstatus
            on empstatus.sts_id = max_status.max_status_id
           and empstatus.deleted = 0
    ");
	    
	    // Active employees only
	    $cond['emp_status'] = 1;
	    $this->_where[] = " emp_status = :emp_status ";
	    
	    // REMOVE THIS LINE (we no longer skip leave employees)
	    // $this->_where [] = " empstatus.sts_type NOT IN [1] ";
	    
	    // Restrict designations
	    $this->_where[] = " emp_desig IN (8,9) ";
	    
	    $this->_order[] = 'emp_fname ASC';
	    
	    return parent::fetchPair($cond);
	}
	
	
	
	public function getAllEmployeePair($cond = array()) {
	    $this->query ( "select emp_id, emp_fname ||' '||emp_mname||' '||emp_lname as emp_name from $this->_table" );
	    
	    //$cond ['emp_status'] = 1;
	    //$this->_where [] = "emp_status= :emp_status";
	    
	    $this->_order [] = 'emp_fname ASC';
	    
	    return parent::fetchPair ( $cond );
	}
}


