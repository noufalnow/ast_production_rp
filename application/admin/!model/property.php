<?php

class property extends db_table
{

    protected $_table = "mis_property";

    protected $_pkey = "prop_id";

    public function add($data)
    {
        return parent::insert($data);
    }

    public function getPropetyPair($cond = array())
    {
        $this->query("select prop_id, prop_fileno from $this->_table 
						left join mis_building as building on building.bld_id = $this->_table.prop_building and building.deleted = 0");

        $this->_order[] = 'bld_name ASC, prop_no asc';

        return parent::fetchPair($cond);
    }

    public function getPlotOptions($cond = [])
    {
        $this->query("SELECT building.bld_name,
						       prop_cat,
						       coalesce(count(CASE WHEN prop_status =1 THEN 1 END),0) AS vacant,
						       coalesce(count(CASE WHEN (prop_status = 2 OR prop_status = 4) THEN 1 END),0) AS agreement
						FROM mis_property
						LEFT JOIN mis_building AS building ON building.bld_id = mis_property.prop_building
						AND building.deleted = 0
						WHERE mis_property.deleted = 0
						  AND prop_cat IN (1,2)
						  AND prop_status IN (1,2,4)
						GROUP by(building.bld_name) ,
						      prop_cat
						ORDER BY (building.bld_name)");
        return parent::fetchQuery($cond);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getPropertyPaginate($cond = [])
    {
        $this->paginate("select $this->_table.*,
				case when  prop_status = 1 and prop_building_type IS NULL then 'Vacant'
					 when  prop_status = 2 and prop_building_type IS NULL then 'Agreement'
					 when  prop_status = 3 and prop_building_type IS NULL then 'Maintenance'
					 when  prop_status = 4 and prop_building_type IS NULL then 'Under Other Agreement'
				end as property_status,
				building.bld_name,
				tnt_full_name as agr_tenant,
                tnt_comp_name 
				", "from $this->_table 
				left join mis_building as building on building.bld_id = $this->_table.prop_building and building.deleted = 0
				LEFT JOIN
				  (SELECT doc_id,
				          -- agr_tenant,
						  doc_ref_id,
						  doc_remarks,
						  agr_paydet,
                          agr_tnt_id
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_type = 201
				        AND deleted = 0
				      GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
				   LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
				   AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
				   left join mis_tenants as tenants on tenants.tnt_id = propdocs.agr_tnt_id and tenants.deleted = 0
				");

        if (! empty($cond['f_propno']))
            $this->_where[] = "prop_no like '%' || :f_propno || '%'";

        if (! empty($cond['f_fileno']))
            $this->_where[] = "lower(prop_fileno) like '%' || lower(:f_fileno) || '%'";

        if (! empty($cond['f_propname']))
            $this->_where[] = "prop_name like '%' || :f_propname || '%'";

        if (! empty($cond['f_building']))
            $this->_where[] = "prop_building = :f_building";

        if (! empty($cond['f_prop_cat'])) {
            if ($cond['f_prop_cat'] == 3) {
                $this->_where[] = "prop_building_type = 1";
                unset($cond['f_prop_cat']);
            } else
                $this->_where[] = "prop_cat = :f_prop_cat";
        }

        if (! empty($cond['f_tenant']))
            $this->_where[] = "
					  (((lower(tnt_full_name) like '%' || lower(:f_tenant) || '%'))
					OR((lower(doc_remarks) like '%' || lower(:f_tenant) || '%')) 
					OR((lower(prop_remarks) like '%' || lower(:f_tenant) || '%')) 
					OR((lower(agr_paydet) like '%' || lower(:f_tenant) || '%'))) ";

        if (! empty($cond['f_prop_status']))
            $this->_where[] = "prop_status = :f_prop_status";

        $this->_order[] = 'building.bld_name ASC, prop_fileno ASC, prop_no ASC';

        // $db= new db_table();
        // $$db->dbug($cond);

        return parent::fetchAll($cond);
    }

    public function getPropertyDet($cond)
    {
        $this->query("select $this->_table.*, 
				building.bld_name
				from $this->_table 
				left join mis_building as building on building.bld_id = $this->_table.prop_building and building.deleted = 0
				");
        if (! empty($cond['prop_id']))
            $this->_where[] = "prop_id= :prop_id";

        if (! empty($cond['prop_fileno']))
            $this->_where[] = "lower(prop_fileno)= lower(:prop_fileno)";

        if (! empty($cond['ex_prop_id']))
            $this->_where[] = "prop_id!= :ex_prop_id";

        return parent::fetchRow($cond);
    }

    public function getPropertyDetById($id)
    {
        return parent::getById($id);
    }

    public function getPropertyById($id)
    {
        return parent::getById($id);
    }

    public function deleteProperty($id)
    {
        return parent::delete($id);
    }

    public function getPropertyMeter($cond = array())
    {
        $this->query("SELECT prop_id,
				       prop_no,
				       prop_fileno,
				       prop_cat,
				       prop_type,
				       prop_level,
				       prop_elec_meter,
				       prop_water,
				       prop_elec_account,
				       prop_elec_recharge,
				       prop_account,
					   prop_building_type,	
				       CASE
				           WHEN prop_status = 1
				                AND prop_building_type IS NULL THEN 'Vacant'
				           WHEN prop_status = 2
				                AND prop_building_type IS NULL THEN 'Agreement'
				           WHEN prop_status = 3
				                AND prop_building_type IS NULL THEN 'Maintenance'
				           WHEN prop_status = 4
				                AND prop_building_type IS NULL THEN 'Under Other Agreement'
				       END AS property_status
				FROM mis_property
				where mis_property.deleted = 0
				order by prop_fileno");

        // v($this->_qry);

        return parent::fetchQuery($cond);
    }

    public function getPropertyReport($cond = array())
    {
        @$cond = array_filter($cond);
        if (! empty($cond['f_propno']))
            $where[] = "prop_no like '%' || :f_propno || '%'";

        if (! empty($cond['f_propname']))
            $where[] = "prop_name like '%' || :f_propname || '%'";

        if (! empty($cond['f_building']))
            $where[] = "prop_building = :f_building";

        if (! empty($cond['f_prop_cat']))
            $where[] = "prop_cat = :f_prop_cat";

        if (! empty($cond['f_prop_status']))
            $where[] = "prop_status = :f_prop_status";

        if (! empty($cond['f_monthpick'])) {
            $monthYear = explode('/', $cond['f_monthpick']);
            $where[] = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' )";
            unset($cond['f_monthpick']);
        }

        $where[] = ' mis_property.deleted = 0 ';
        $where[] = ' mis_property.prop_building_type IS NULL ';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("SELECT *,
				case when  prop_status = 1 and prop_building_type IS NULL then 'Vacant'
					 when  prop_status = 2 and prop_building_type IS NULL then 'Agreement'
					 when  prop_status = 3 and prop_building_type IS NULL then 'Maintenance'
					 when  prop_status = 4 and prop_building_type IS NULL then 'Under Other Agreement'
				end as property_status,
                tnt_full_name as agr_tenant
				FROM mis_property
				LEFT JOIN mis_building as building on building.bld_id = $this->_table.prop_building and building.deleted = 0
				LEFT JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
				          doc_remarks,
						  agr_mobile,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month,
                          agr_tnt_id
				   FROM
				     (SELECT max(doc_id) AS mdoc_id
				      FROM mis_documents
				      WHERE doc_ref_type = " . DOC_TYPE_PROP . "
				        AND deleted = 0
				      GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
				   LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
				   AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
                   left join mis_tenants as tenants on tenants.tnt_id = propdocs.agr_tnt_id and tenants.deleted = 0
				$where
				Order by prop_building ASC, prop_level ASC, prop_fileno ASC");

        // v($this->_qry);

        return parent::fetchQuery($cond);
    }

    public function getPropertyDocReport($cond = array())
    {
        @$cond = array_filter($cond);

        if (! empty($cond['f_propno']))
            $where[] = "prop_no like '%' || :f_propno || '%'";

        if (! empty($cond['f_building']))
            $where[] = "prop_building = :f_building";

        if (! empty($cond['f_prop_cat']))
            $where[] = "prop_cat = :f_prop_cat";

        if (! empty($cond['f_prop_type']))
            $where[] = "prop_type = :f_prop_type";

        if (! empty($cond['f_monthpick'])) {
            if ($cond['f_monthpick'] == 'past') {
                $date = new DateTime();
                $where[] = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format('m') . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "')) ";
            } else if ($cond['f_monthpick'] == 'exp') {
                $date = new DateTime();
                $where[] = "((EXTRACT(month FROM doc_expiry_month) <= '" . $date->format('m') . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "')) ";
            } else {
                $monthYear = explode('/', $cond['f_monthpick']);
                $where[] = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' )";
            }
            unset($cond['f_monthpick']);
        }

        $where[] = ' mis_property.deleted = 0 ';
        $where[] = ' mis_property.prop_status =2 ';
        // $where [] = ' mis_property.emp_status = 1 ';
        // $where [] = ' doc_type !=2';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("SELECT prop_id,
				       prop_no,
				       prop_building,
				       prop_cat,
				       prop_type,
				       prop_level,
				       propdocs.*,
					   files.file_id,
					   files.file_exten,
					   build.bld_name,
					   prop_fileno,
                       tnt_full_name as agr_tenant
				FROM mis_property
				LEFT JOIN mis_building AS build ON build.bld_id = mis_property.prop_building
				AND build.deleted = 0
				INNER JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
				          doc_remarks,
				          -- agr_tenant,
						  agr_mobile,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month,
						  agr_amount,
                          agr_tnt_id
		   FROM
		     (SELECT max(doc_id) AS mdoc_id
		      FROM mis_documents
		      WHERE doc_ref_type = " . DOC_TYPE_PROP . "
							AND deleted = 0
							GROUP BY doc_type,doc_ref_type,doc_ref_id)max_group
							LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
							AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
                            left join mis_tenants as tenants on tenants.tnt_id = propdocs.agr_tnt_id and tenants.deleted = 0
							LEFT JOIN core_files as files on files.file_ref_id = propdocs.doc_id and files.deleted = 0 AND files.file_type IN(3)
							$where
							ORDER BY doc_type,prop_building,prop_cat DESC,doc_expiry_month ASC");

        // q()

        return parent::fetchQuery($cond);
    }

    public function getPropertyPayReport($cond = array())
    {
        @$cond = array_filter($cond);

        if (! empty($cond['f_propno']))
            $where[] = "(prop_no like '%' || :f_propno || '%' OR prop_fileno like '%' || :f_propno || '%')";

        if (! empty($cond['f_building']))
            $where[] = "prop_building = :f_building";

        if (! empty($cond['f_prop_cat']))
            $where[] = "prop_cat = :f_prop_cat";

        if (! empty($cond['f_prop_type']))
            $where[] = "prop_type = :f_prop_type";

        if (! empty($cond['f_monthpick'])) {
            $monthYear = explode('/', $cond['f_monthpick']);
            $whereDate = " AND (EXTRACT(month FROM proppay.popt_date) = '$monthYear[0]' AND EXTRACT(year FROM proppay.popt_date) = '$monthYear[1]' )";
            unset($cond['f_monthpick']);
        }

        if (! empty($cond['f_tenant']))
            $where[] = "
					  (((lower(tnt_full_name) like '%' || lower(:f_tenant) || '%'))
					OR((lower(doc_remarks) like '%' || lower(:f_tenant) || '%'))
					OR((lower(prop_remarks) like '%' || lower(:f_tenant) || '%'))
					OR((lower(agr_paydet) like '%' || lower(:f_tenant) || '%'))) ";

        if (! empty($cond['f_date']))
            $where[] = "proppay.popt_date = :f_date";

        if (! empty($cond['f_status']))
            if ($cond['f_status'] == 1)
                $where[] = "cdmd_pstatus = :f_status";
            else
                $where[] = "(cdmd_pstatus = :f_status OR cdmd_pstatus ISNULL)";

        $where[] = ' mis_property.deleted = 0 ';
        $where[] = ' mis_property.prop_status <> 3 ';

        // 201 doc_type
        // $where [] = ' mis_property.emp_status = 1 ';
        // $where [] = ' doc_type !=2';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("SELECT prop_id,
				       prop_no,
				       prop_building,
				       prop_cat,
				       prop_type,
				       prop_level,
					   prop_fileno,
				       propdocs.*,
					   proppay.*,
					   prop_status,
					   cdmd_pstatus,

					   case when prop_account = 8  then 'BA' when prop_account = 9 then 'BA' end as prop_account,

					    CASE WHEN popt_type = 1 then 'Cash'
						 when popt_type = 2 then 'Cheque'
						 when popt_type = 3 then 'Not Defined'
						 when prop_status = 1 then 'Vacant'	
						END as popt_type,
						popt_type as popt_type_id,

					    CASE WHEN popt_bank = 1 then 'Bank Muscat'
						 when popt_bank = 2 then 'Bank Dhofar'
						 when popt_bank = 3 then 'NBO'
						 when popt_bank = 4 then 'OAB'
						 when popt_bank = 5 then 'HSBC'
						 when popt_bank = 6 then 'FAB'
 						 when popt_bank = 7 then 'Bank Sohar'
 						 when popt_bank = 8 then 'SBI'
						 when popt_bank = 9 then 'Bank of Baroda'
						 when popt_bank = 10 then 'NBA'

						END || '<br>' || popt_chqno as popt_bank_det,

					   to_char(proppay.popt_date,'DD/MM/YYYY') as popt_date,
					   files.file_id,
					   files.file_exten,
					   build.bld_name,
                       tnt_full_name as agr_tenant,
                       cdet_id,
                       cdmd_id,
                       COALESCE(COALESCE(proppay.popt_amount, 0) - COALESCE(colldet.cdet_amt_paid, 0), 0) AS due_amount  
				FROM mis_property
				LEFT JOIN mis_building AS build ON build.bld_id = mis_property.prop_building
				AND build.deleted = 0
				INNER JOIN
				  (SELECT doc_id,
				          doc_type,
				          doc_ref_type,
				          doc_ref_id,
				          doc_no,
				          doc_desc,
						  agr_paydet,
				          doc_remarks,
						  agr_mobile,
						  to_char(doc_apply_date,'DD/MM/YYYY') as doc_apply_date,
						  to_char(doc_issue_date,'DD/MM/YYYY') as doc_issue_date,
						  to_char(doc_expiry_date,'DD/MM/YYYY') as doc_expiry_date,
						  doc_expiry_date as doc_expiry_month,
                          agr_tnt_id
				        FROM
				        mis_documents 
                        WHERE doc_ref_type = " . DOC_TYPE_PROP . " and doc_type = 201
						AND deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
                        left join mis_tenants as tenants on tenants.tnt_id = propdocs.agr_tnt_id and tenants.deleted = 0
						LEFT JOIN core_files as files on files.file_ref_id = propdocs.doc_id and files.deleted = 0 AND files.file_type IN(3)
						INNER JOIN mis_property_payoption as proppay on proppay.popt_doc_id = propdocs.doc_id and proppay.deleted = 0 $whereDate
						
						LEFT JOIN mis_cash_demand AS dmd ON dmd.cdmd_type = " . CASHDMD_TYP_PROP . "
						AND dmd.cdmd_ref_id = proppay.popt_id
						AND dmd.cdmd_oth_id = proppay.popt_doc_id
						AND dmd.deleted = 0
                        LEFT JOIN mis_collection_det as colldet on colldet.cdet_src_type = 2 AND colldet.cdet_bill_id = dmd.cdmd_id and colldet.deleted = 0

						$where
						ORDER BY proppay.popt_date ASC, prop_fileno ASC");
        
						//d($this->_qry);

        return parent::fetchQuery($cond);
    }

    public function getPropDocExpiryReport($cond = array())
    {
        $monthYear = explode('/', $cond['f_monthpick']);

        if ($cond['f_monthpick'] == 'past') {
            $date = new DateTime();
            $where = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format('m') . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "')) ";
        } else {
            $monthYear = explode('/', $cond['f_monthpick']);
            $where = " (EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' ) ";
        }
        unset($cond['f_monthpick']);

        $this->query("SELECT count(doc_type) AS COUNT,
                       CASE
                           WHEN doc_type = 201 THEN 'Agreement'
                           WHEN doc_type = 202 THEN 'Fire'
                           WHEN doc_type = 203 THEN 'Insurance'
                       END AS doc_type
                FROM mis_property
                INNER JOIN
                  (SELECT doc_type,
                          doc_ref_id,
                          doc_expiry_date AS doc_expiry_month,
                          agr_tnt_id  
                   FROM
                     (SELECT max(doc_id) AS mdoc_id
                      FROM mis_documents
                      WHERE doc_ref_type = 3
                        AND deleted = 0
                      GROUP BY doc_type,
                               doc_ref_type,
                               doc_ref_id)max_group
                   LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
                   AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
				WHERE
				$where
				AND mis_property.prop_status = 2
				AND mis_property.deleted = 0
				GROUP BY doc_type");

        return parent::fetchQuery($cond);
    }

    public function getTenantsReport($cond = array())
    {
        @$cond = array_filter($cond);

        if (! empty($cond['f_propno']))
            $where[] = "prop_no like '%' || :f_propno || '%'";

        if (! empty($cond['f_building']))
            $where[] = "prop_building = :f_building";

        if (! empty($cond['f_tenants']))
            $where[] = "agr_tnt_id = :f_tenants";

        if (! empty($cond['f_prop_cat']))
            $where[] = "prop_cat = :f_prop_cat";

        if (! empty($cond['f_prop_type']))
            $where[] = "prop_type = :f_prop_type";

        if (! empty($cond['f_monthpick'])) {
            if ($cond['f_monthpick'] == 'past') {
                $date = new DateTime();
                $where[] = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format('m') . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "')) ";
            } else if ($cond['f_monthpick'] == 'exp') {
                $date = new DateTime();
                $where[] = "((EXTRACT(month FROM doc_expiry_month) <= '" . $date->format('m') . "' AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "' ) OR
						(EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "')) ";
            } else {
                $monthYear = explode('/', $cond['f_monthpick']);
                $where[] = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]' )";
            }
            unset($cond['f_monthpick']);
        }

        $where[] = ' mis_property.deleted = 0 ';
        $where[] = ' mis_property.prop_status =2 ';
        // $where [] = ' mis_property.emp_status = 1 ';
        // $where [] = ' doc_type !=2';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("SELECT prop_id,
                               prop_no,
                               prop_building,
                               prop_cat,
                               prop_type,
                               prop_level,
                               propdocs.*,
                               files.file_id,
                               files.file_exten,
                               build.bld_name,
                               prop_fileno,
                               tnt_full_name AS agr_tenant,
                               agr_tnt_id 
                        FROM mis_property
                        LEFT JOIN mis_building AS build ON build.bld_id = mis_property.prop_building
                        AND build.deleted = 0
                        INNER JOIN
                          (SELECT doc_id,
                                  doc_type,
                                  doc_ref_type,
                                  doc_ref_id,
                                  doc_no,
                                  doc_desc,
                                  doc_remarks,
                                  agr_mobile,
                                  to_char(doc_apply_date,'DD/MM/YYYY') AS doc_apply_date,
                                  to_char(doc_issue_date,'DD/MM/YYYY') AS doc_issue_date,
                                  to_char(doc_expiry_date,'DD/MM/YYYY') AS doc_expiry_date,
                                  doc_expiry_date AS doc_expiry_month,
                                  agr_amount,
                                  agr_tnt_id
                           FROM mis_documents
                           WHERE doc_ref_type = 3
                             AND doc_type = 201
                             AND deleted = 0 ) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
                        LEFT JOIN mis_tenants AS tenants ON tenants.tnt_id = propdocs.agr_tnt_id
                        AND tenants.deleted = 0
                        LEFT JOIN core_files AS files ON files.file_ref_id = propdocs.doc_id
                        AND files.deleted = 0
                        AND files.file_type IN(3)
                        $where
                        ORDER BY tnt_full_name ASC,
                                 doc_type,
                                 prop_building,
                                 prop_cat DESC,
                                 doc_expiry_month ASC");

        // q()

        return parent::fetchQuery($cond);
    }

    public function getDashsummary($cond = [])
    {
        if ($cond['Month'] == 'pre')
            $where = "  WHERE COALESCE(popt.year_month, paid.year_month) = TO_CHAR(CURRENT_DATE - INTERVAL '1 MONTH', 'YYYY-MM') ";
        else
            $where = " WHERE     COALESCE(popt.year_month, paid.year_month) = TO_CHAR(CURRENT_DATE, 'YYYY-MM') ";

        // $where = " WHERE COALESCE(popt.year_month, dmd.year_month) = '2024-09' ";
        unset($cond);

        $this->query("
                            SELECT COALESCE(popt.year_month, paid.year_month, coll.year_month) AS year_month,
                                   COALESCE(popt.total_amount, 0) AS total_amount,
                                   COALESCE(paid.paid_amount, 0) AS paid_amount,
                                   COALESCE(coll.total_collection, 0) AS total_collection,
                                   COALESCE(totexp.total_combined, 0) AS total_expenditure
                            FROM
                              (SELECT TO_CHAR(popt_date, 'YYYY-MM') AS year_month,
                                      SUM(popt_amount) AS total_amount
                               FROM mis_property_payoption AS payop
                               WHERE payop.deleted = 0
                               GROUP BY TO_CHAR(popt_date, 'YYYY-MM')) AS popt
                            FULL OUTER JOIN
                              (SELECT TO_CHAR(coll.coll_paydate, 'YYYY-MM') AS year_month, 
                                       SUM(cdet_amt_paid) AS paid_amount
                                FROM mis_collection AS coll
                                LEFT JOIN mis_collection_det AS coldet ON coll.coll_id = coldet.cdet_coll_id
                                    AND coldet.deleted = 0
                                    AND coldet.cdet_src_type = 2
                                WHERE coll.coll_src_type = 2
                                  AND coll.coll_app_status = 1
                                  AND coll.deleted = 0  
                                GROUP BY TO_CHAR(coll.coll_paydate, 'YYYY-MM')) AS paid ON popt.year_month = paid.year_month
                            FULL OUTER JOIN
                              (SELECT TO_CHAR(coll_paydate, 'YYYY-MM') AS year_month,
                                      SUM(coll_amount) AS total_collection
                               FROM mis_collection
                               WHERE coll_app_status = '1'
                                 AND coll_src_type = '1' AND deleted=0
                               GROUP BY TO_CHAR(coll_paydate, 'YYYY-MM')) AS coll ON COALESCE(popt.year_month, paid.year_month) = coll.year_month
                            FULL OUTER JOIN (
                            SELECT COALESCE(exp.year_month, pay.year_month) AS year_month,
                                   (COALESCE(exp.total_expense, 0) + COALESCE(pay.total_crpayment, 0)) AS total_combined
                            FROM
                              (SELECT TO_CHAR(exp_billdt, 'YYYY-MM') AS year_month,
                                      SUM(exp_amount) AS total_expense
                               FROM mis_expense
                               WHERE exp_pay_mode = '1'
                                 AND exp_app_status = '1' AND deleted = 0
                               GROUP BY TO_CHAR(exp_billdt, 'YYYY-MM')) AS exp
                            FULL OUTER JOIN
                              (SELECT TO_CHAR(pay_paydate, 'YYYY-MM') AS year_month,
                                      SUM(pay_amount) AS total_crpayment
                               FROM mis_payment
                               WHERE pay_app_status = '1' and deleted = 0
                               GROUP BY TO_CHAR(pay_paydate, 'YYYY-MM')) AS pay ON exp.year_month = pay.year_month
                            ORDER BY year_month
                            
                             ) AS totexp ON COALESCE(popt.year_month, paid.year_month) = totexp.year_month

                    $where
                    ORDER BY 
                        year_month;");

        // d($this->_qry);

        return parent::fetchQuery($cond);
    }

    public function getDashsummaryGraph($cond = [])
    {
        $this->query("WITH date_range AS (
                        SELECT TO_CHAR(current_date, 'YYYY-MM') AS current_month,
                               TO_CHAR(current_date - INTERVAL '11 months', 'YYYY-MM') AS eleven_months_ago
                    )
                    SELECT COALESCE(popt.year_month, paid.year_month, coll.year_month) AS year_month,
                           COALESCE(popt.total_amount, 0) AS total_amount,
                           COALESCE(paid.paid_amount, 0) AS paid_amount,
                           COALESCE(COALESCE(popt.total_amount, 0) - COALESCE(paid.paid_amount, 0), 0) AS due_amount,
                           COALESCE(coll.total_collection, 0) AS bill_collection,
                           COALESCE(totexp.total_combined, 0) AS total_expenditure
                    FROM
                      (SELECT TO_CHAR(popt_date, 'YYYY-MM') AS year_month,
                              SUM(popt_amount) AS total_amount
                       FROM mis_property_payoption AS payop
                       WHERE payop.deleted = 0
                       GROUP BY TO_CHAR(popt_date, 'YYYY-MM')) AS popt


                    FULL OUTER JOIN
                     (SELECT TO_CHAR(coll.coll_paydate, 'YYYY-MM') AS year_month, 
                           SUM(cdet_amt_paid) AS paid_amount
                    FROM mis_collection AS coll
                    LEFT JOIN mis_collection_det AS coldet ON coll.coll_id = coldet.cdet_coll_id
                        AND coldet.deleted = 0
                        AND coldet.cdet_src_type = 2
                    WHERE coll.coll_src_type = 2
                      AND coll.coll_app_status = 1
                      AND coll.deleted = 0  
                    GROUP BY TO_CHAR(coll.coll_paydate, 'YYYY-MM')) AS paid ON popt.year_month = paid.year_month

                    FULL OUTER JOIN
                      (SELECT TO_CHAR(coll_paydate, 'YYYY-MM') AS year_month,
                              SUM(coll_amount) AS total_collection
                       FROM mis_collection
                       WHERE coll_app_status = '1'
                         AND coll_src_type = '1' AND deleted = 0
                       GROUP BY TO_CHAR(coll_paydate, 'YYYY-MM')) AS coll ON COALESCE(popt.year_month, paid.year_month) = coll.year_month
                    FULL OUTER JOIN (
                    SELECT COALESCE(exp.year_month, pay.year_month) AS year_month,
                           (COALESCE(exp.total_expense, 0) + COALESCE(pay.total_crpayment, 0)) AS total_combined
                    FROM
                      (SELECT TO_CHAR(exp_billdt, 'YYYY-MM') AS year_month,
                              SUM(exp_amount) AS total_expense
                       FROM mis_expense
                       WHERE exp_pay_mode = '1'
                         AND exp_app_status = '1' AND deleted = 0
                       GROUP BY TO_CHAR(exp_billdt, 'YYYY-MM')) AS exp
                    FULL OUTER JOIN
                      (SELECT TO_CHAR(pay_paydate, 'YYYY-MM') AS year_month,
                              SUM(pay_amount) AS total_crpayment
                       FROM mis_payment
                       WHERE pay_app_status = '1' AND deleted = 0
                       GROUP BY TO_CHAR(pay_paydate, 'YYYY-MM')) AS pay ON exp.year_month = pay.year_month
                    ORDER BY year_month
                    ) AS totexp ON COALESCE(popt.year_month, paid.year_month) = totexp.year_month
                    CROSS JOIN date_range
                    WHERE COALESCE(popt.year_month, paid.year_month, coll.year_month) 
                          BETWEEN eleven_months_ago AND current_month
                    ORDER BY year_month;");
        return parent::fetchQuery($cond);
    }

    public function getFinancialExpense($cond = [])
    {
        if ($cond['f_monthpick'] == '') {
            $expDate = " AND TO_CHAR(COALESCE( exp.exp_billdt, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
            $payDate = " AND TO_CHAR(COALESCE( pay.pay_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
            
        } else {
            $expDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(exp.exp_billdt, 'YYYY-MM')";
            $payDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(pay.pay_paydate, 'YYYY-MM')";
            unset($cond['f_monthpick']);
        }
        
        if ($cond['f_company'] != '') {
            $company = " WHERE comp_id = ".$cond['f_company'] . " ";
            unset($cond['f_company']);
        }
        
        
        
        
        $this->query("
                SELECT ref_source,
                       comp_id,
                       SUM(amount) AS total_expense
                FROM (
                    -- Property Expenses
                    SELECT 'Property' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(exp.exp_amount) AS amount
                    FROM mis_expense exp
                    WHERE exp.exp_mainh = 2
                      AND exp.exp_pay_mode = '1'
                      AND exp.deleted = 0
                      AND exp.exp_app_status = '1'
                      $expDate  
                    GROUP BY exp.exp_company
                        
                    UNION ALL
                        
                    -- Vehicle Expenses
                    SELECT 'Vehicle' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(exp.exp_amount) AS amount
                    FROM mis_expense exp
                    WHERE exp.exp_mainh = 3
                      AND exp.exp_pay_mode = '1'
                      AND exp.deleted = 0
                      AND exp.exp_app_status = '1'
                      $expDate
                    GROUP BY exp.exp_company
                        
                    UNION ALL
                        
                    -- Other Expenses (excluding property and vehicle)
                    SELECT 'Other' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(exp.exp_amount) AS amount
                    FROM mis_expense exp
                    WHERE exp.exp_mainh NOT IN (2, 3)
                      AND exp.exp_pay_mode = '1'
                      AND exp.deleted = 0
                      AND exp.exp_app_status = '1'
                      $expDate
                    GROUP BY exp.exp_company
                        
                    UNION ALL
                        
                    -- Property Payment (Paid through payment details)
                    SELECT 'Property' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(paydet.pdet_amt_paid) AS amount
                    FROM mis_payment_det paydet
                    JOIN mis_payment as pay on pay.pay_id = paydet.pdet_pay_id AND pay.deleted = 0
                    LEFT JOIN mis_expense exp
                        ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    WHERE paydet.pdet_status = 2
                      AND paydet.deleted = 0
                      AND exp.exp_mainh = 2
                      $payDate  
                    GROUP BY exp.exp_company
                        
                    UNION ALL
                        
                    -- Vehicle Payment (Paid through payment details)
                    SELECT 'Vehicle' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(paydet.pdet_amt_paid) AS amount
                    FROM mis_payment_det paydet
                    JOIN mis_payment as pay on pay.pay_id = paydet.pdet_pay_id AND pay.deleted = 0
                    LEFT JOIN mis_expense exp
                        ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    WHERE paydet.pdet_status = 2
                      AND paydet.deleted = 0
                      AND exp.exp_mainh = 3
                      $payDate  
                    GROUP BY exp.exp_company
                        
                    UNION ALL
                        
                    -- Other Payment (Paid through payment details)
                    SELECT 'Other' AS ref_source,
                           exp.exp_company AS comp_id,
                           SUM(paydet.pdet_amt_paid) AS amount
                    FROM mis_payment_det paydet
                    JOIN mis_payment as pay on pay.pay_id = paydet.pdet_pay_id AND pay.deleted = 0
                    LEFT JOIN mis_expense exp
                        ON exp.exp_id = paydet.pdet_exp_id
                        AND exp.exp_pay_mode = '2'
                        AND exp.deleted = 0
                        AND exp.exp_app_status = '1'
                    WHERE paydet.pdet_status = 2
                      AND paydet.deleted = 0
                      AND exp.exp_mainh NOT IN (2, 3)
                      $payDate  
                    GROUP BY exp.exp_company
                ) AS combined_data
                $company        
                GROUP BY ref_source, comp_id
                ORDER BY comp_id, ref_source;
            ");
                unset($cond['f_company']);
        return parent::fetchQuery($cond);
        
    }

    public function getFinancialRevenue($cond = [])
    {
        if ($cond['f_monthpick'] == '') {
            $collDate = " AND TO_CHAR(COALESCE(coll.coll_paydate, CURRENT_DATE), 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')";
        } else {
            $collDate = " AND TO_CHAR(TO_DATE('".$cond['f_monthpick']."', 'MM/YYYY'), 'YYYY-MM') = TO_CHAR(coll.coll_paydate, 'YYYY-MM')";
            unset($cond['f_monthpick']);
        }
        
        if ($cond['f_company'] != '') {
            $bldComp = " AND build.bld_comp = ".$cond['f_company'] . " ";
            $billComp = " AND bill.bill_company = ".$cond['f_company'] . " ";
            unset($cond['f_company']);
        }
        
        $this->query("
                    SELECT 'Property' AS ref_source,
                           build.bld_comp AS comp_id,
                           SUM(cdet_amt_paid) AS total_income
                    FROM mis_collection AS coll
                    LEFT JOIN mis_collection_det AS coldet ON coll.coll_id = coldet.cdet_coll_id
                        AND coldet.deleted = 0
                        AND coldet.cdet_src_type = 2
                    LEFT JOIN mis_cash_demand AS dmd ON dmd.cdmd_id = coldet.cdet_bill_id
                        AND dmd.deleted = 0
                    LEFT JOIN mis_documents AS doc ON doc.doc_id = dmd.cdmd_oth_id
                        AND doc.doc_ref_type = 3
                        AND doc.doc_type = 201
                    LEFT JOIN mis_property prop ON prop.prop_id = doc.doc_ref_id
                    LEFT JOIN mis_building AS build ON build.bld_id = prop.prop_building
                    WHERE coll.coll_src_type = 2
                      AND coll.coll_app_status = 1
                      AND coll.deleted = 0  
                      $collDate
                      $bldComp
                    GROUP BY build.bld_comp
                            
                    UNION ALL
                            
                    SELECT 'Vehicle' AS ref_source,
                           bill.bill_company AS comp_id,
                           SUM(cdet_amt_paid) AS total_amount
                    FROM mis_collection AS coll
                    LEFT JOIN mis_collection_det AS coldet ON coll.coll_id = coldet.cdet_coll_id
                        AND coldet.deleted = 0
                    LEFT JOIN mis_bill AS bill ON bill.bill_id = coldet.cdet_bill_id
                        AND bill.deleted = 0
                    WHERE coll.deleted = 0
                      AND coll.coll_app_status = 1
                      AND coll.coll_src_type = 1
                      $collDate
                      $billComp  
                    GROUP BY bill.bill_company
                ");
        
                unset($cond['f_company']);
        
                //d($this->_qry);
      
      return parent::fetchQuery($cond);
      
        
    }
}
