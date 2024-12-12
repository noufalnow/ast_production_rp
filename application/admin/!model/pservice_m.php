<?php
class pservice_m extends db_table {
    protected $_table = "mis_property_service";
    protected $_pkey = "psvs_id";

    /**
     * Add a new property service record
     */
    public function add($data) {
        return parent::insert($data);
    }

    public function getDetById($cond)
    {
        $this->query("select $this->_table.*,
                        files.file_id as fileid,
                        docsrpt.doc_id as docsid
        FROM $this->_table
        LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_PROP_SRV . "
            AND docsrpt.doc_ref_type = " . DOC_TYPE_PROP_SRV . "
            AND docsrpt.doc_ref_id = psvs_id
            AND docsrpt.deleted = 0
         LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_PROP_SRV . " and files.deleted = 0");

        $this->_where[] = "psvs_id = :psvs_id";
        
        return parent::fetchRow($cond);
    }
    
    
    /**
     * Modify an existing property service record
     */
    public function modify($data, $cond) {
        return parent::update($data, $cond);
    }

    /**
     * Get paginated list of property services with additional details
     */
    public function getPropertyServicePaginate($cond) {
        $this->paginate("SELECT $this->_table.*,
                to_char(psvs_date, 'DD/MM/YYYY') AS psvs_disp_date,
                to_char(psvs_srv_date, 'DD/MM/YYYY') AS psvs_srv_disp_date,
                emp.emp_fname ||' '||emp.emp_mname||' '||emp.emp_lname AS employee_name,
				case when psvs_type = 1 then 'Electrical'
    				when psvs_type = 2 then 'Plumbing'
    				when psvs_type = 3 then 'Painting'
    				when psvs_type = 4 then 'Other'
				end as psvs_type_lbl,

				case when psvs_feedback = 1 then 'BAD'
    				when psvs_feedback = 2 then 'AVERAGE'
    				when psvs_feedback = 3 then 'GOOD'
    				when psvs_feedback = 4 then 'VERY GOOD'
                    when psvs_feedback = 5 then 'EXCELLENT'
				end as psvs_fb_lbl,
                bld_name,

                prop_fileno,
                files.file_id as fileid,
                docsrpt.doc_id as docsid",
            "FROM $this->_table
            LEFT JOIN mis_employee AS emp ON emp.emp_id = $this->_table.psvs_emp AND emp.deleted = 0
            LEFT JOIN mis_property AS prop ON psvs_prop_id = prop.prop_id
            LEFT JOIN mis_building AS build ON prop.prop_building = build.bld_id
            LEFT JOIN mis_documents AS docsrpt ON docsrpt.doc_type = " . DOC_TYPE_PROP_SRV . "
                AND docsrpt.doc_ref_type = " . DOC_TYPE_PROP_SRV . "
                AND docsrpt.doc_ref_id = psvs_id
                AND docsrpt.deleted = 0
             LEFT JOIN core_files as files on files.file_ref_id = docsrpt.doc_id and files.file_type = " . DOC_TYPE_PROP_SRV . " and files.deleted = 0"
            );

        if (!empty($cond['f_date_range'])) {
            $dateRange = explode(' - ', $cond['f_date_range']);
            $this->_where[] = "psvs_date BETWEEN :f_date_start AND :f_date_end";
            $cond['f_date_start'] = $dateRange[0];
            $cond['f_date_end'] = $dateRange[1];
            unset($cond['f_date_range']);
        }

        if (!empty($cond['psvs_emp'])) {
            $this->_where[] = "psvs_emp = :psvs_emp";
        }
        
        if (!empty($cond['prop_building'])) {
            $this->_where[] = "prop_building = :prop_building";
        }
        

        if (!empty($cond['psvs_complaint_no'])) {
            $this->_where[] = "lower(psvs_complaint_no) LIKE '%' || lower(:psvs_complaint_no) || '%'";
        }

        if (!empty($cond['psvs_prop_id'])) {
            $this->_where[] = "psvs_prop_id = :psvs_prop_id";
        }
        
        if (!empty($cond['psvs_type'])) {
            $this->_where[] = "psvs_type = :psvs_type";
        }
        
        if (! empty($cond['f_monthpick'])) {
            $monthYear = explode('/', $cond['f_monthpick']);
            $this->_where[] = " (EXTRACT(month FROM psvs_srv_date) = '$monthYear[0]' AND EXTRACT(year FROM psvs_srv_date) = '$monthYear[1]' )";
            unset($cond['f_monthpick']);
        }
              

        $this->_order[] = 'psvs_id DESC';
        return parent::fetchAll($cond);
    }

    /**
     * Get details of a single property service
     */
    public function getPropertyServiceInfo($cond) {
        $this->query("SELECT $this->_table.*,
                to_char(psvs_date, 'DD/MM/YYYY') AS psvs_disp_date,
                to_char(psvs_srv_date, 'DD/MM/YYYY') AS psvs_srv_disp_date,
                emp.emp_name AS psvs_emp_name
            FROM $this->_table
            LEFT JOIN employee AS emp ON emp.emp_id = $this->_table.psvs_emp AND emp.deleted = 0");

        if (!empty($cond['psvs_id'])) {
            $this->_where[] = "psvs_id = :psvs_id";
        }

        return parent::fetchRow($cond);
    }

    /**
     * Delete (soft-delete) a property service record
     */
    public function deletePropertyService($id) {
        return parent::update(['deleted' => 1, 't_deleted' => date('Y-m-d H:i:s')], ['psvs_id' => $id]);
    }

    /**
     * Fetch pair (for dropdowns or selections)
     */
    public function getServicePair($cond = []) {
        $this->query("SELECT psvs_id, psvs_complaint_no AS selection FROM $this->_table WHERE deleted = 0");

        if (!empty($cond['f_type'])) {
            $this->_where[] = "psvs_type = :f_type";
        }

        $this->_order[] = 'psvs_complaint_no ASC';
        return parent::fetchPair($cond);
    }

    /**
     * Get report of services
     */
    public function getServiceReport($cond = []) {
        $this->query("SELECT $this->_table.*,
                to_char(psvs_date, 'DD/MM/YYYY') AS psvs_disp_date,
                to_char(psvs_srv_date, 'DD/MM/YYYY') AS psvs_srv_disp_date,
                emp.emp_name AS psvs_emp_name
            FROM $this->_table
            LEFT JOIN employee AS emp ON emp.emp_id = $this->_table.psvs_emp AND emp.deleted = 0");

        if (!empty($cond['f_date_range'])) {
            $dateRange = explode(' - ', $cond['f_date_range']);
            $this->_where[] = "psvs_date BETWEEN :f_date_start AND :f_date_end";
            $cond['f_date_start'] = $dateRange[0];
            $cond['f_date_end'] = $dateRange[1];
            unset($cond['f_date_range']);
        }

        if (!empty($cond['f_emp'])) {
            $this->_where[] = "psvs_emp = :f_emp";
        }

        if (!empty($cond['f_complaint_no'])) {
            $this->_where[] = "lower(psvs_complaint_no) LIKE '%' || lower(:f_complaint_no) || '%'";
        }

        $this->_order[] = 'psvs_id ASC';
        return parent::fetchQuery($cond);
    }
}
?>
