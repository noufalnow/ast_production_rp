<?php

class property extends db_table
{

    protected $_table = "mis_projects";

    protected $_pkey = "project_id";

    public function add($data)
    {
        return parent::insert($data);
    }

    public function getPropetyPair($cond = array())
    {
        $this->query("
            SELECT project_id, project_fileno
            FROM $this->_table
        ");

        $this->_order[] = 'project_fileno ASC';

        return parent::fetchPair($cond);
    }

    public function getPlotOptions($cond = [])
    {}

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getPropertyPaginate($cond = [])
    {
        $this->paginate("
        SELECT $this->_table.*,
               CASE
                   WHEN project_status = 1 THEN 'Planning'
                   WHEN project_status = 2 THEN 'Active'
                   WHEN project_status = 3 THEN 'On Hold'
                   WHEN project_status = 4 THEN 'Completed'
                   WHEN project_status = 5 THEN 'Cancelled'
               END AS project_status_label,
               customer.cust_name
        ", "
        FROM $this->_table
        LEFT JOIN mis_customer AS customer
               ON customer.cust_id = $this->_table.project_client_id
               AND customer.deleted = 0
    ");

        /* Project Code */
        if (! empty($cond['f_propno']))
            $this->_where[] = "project_code LIKE '%' || :f_propno || '%'";

        /* File No */
        if (! empty($cond['f_fileno']))
            $this->_where[] = "LOWER(project_fileno) LIKE '%' || LOWER(:f_fileno) || '%'";

        /* Project Name (optional – keep if form has it) */
        if (! empty($cond['f_propname']))
            $this->_where[] = "project_name LIKE '%' || :f_propname || '%'";

        /* Category */
        if (! empty($cond['f_prop_cat']))
            $this->_where[] = "project_category = :f_prop_cat";

        /* Status */
        if (! empty($cond['f_prop_status']))
            $this->_where[] = "project_status = :f_prop_status";

        /* Customer (ID-based filter – CORRECT) */
        if (! empty($cond['f_customer']))
            $this->_where[] = "project_client_id = :f_customer";

        $this->_order[] = '
        customer.cust_name ASC,
        project_fileno ASC,
        project_code ASC
    ';

        return parent::fetchAll($cond);
    }

    public function getPropertyDet($cond)
    {
        $this->query("
            SELECT $this->_table.*, customer.cust_name
            FROM $this->_table
            LEFT JOIN mis_customer AS customer
                   ON customer.cust_id = $this->_table.project_client_id
                   AND customer.deleted = 0
        ");

        if (! empty($cond['project_id']))
            $this->_where[] = "project_id = :project_id";

        if (! empty($cond['prop_fileno']))
            $this->_where[] = "LOWER(project_fileno) = LOWER(:prop_fileno)";

        if (! empty($cond['ex_prop_id']))
            $this->_where[] = "project_id != :ex_prop_id";

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
    {}

    public function getPropertyReport($cond = array())
    {
        @$cond = array_filter($cond);

        if (! empty($cond['f_propno']))
            $where[] = "project_code LIKE '%' || :f_propno || '%'";

        if (! empty($cond['f_propname']))
            $where[] = "project_name LIKE '%' || :f_propname || '%'";

        if (! empty($cond['f_prop_cat']))
            $where[] = "project_category = :f_prop_cat";

        if (! empty($cond['f_prop_status']))
            $where[] = "project_status = :f_prop_status";

        $where[] = 'mis_projects.deleted = 0';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("
            SELECT *,
                   CASE 
                       WHEN project_status = 1 THEN 'Planning'
                       WHEN project_status = 2 THEN 'Active'
                       WHEN project_status = 3 THEN 'On Hold'
                       WHEN project_status = 4 THEN 'Completed'
                       WHEN project_status = 5 THEN 'Cancelled'
                   END AS project_status_label
            FROM mis_projects
            LEFT JOIN mis_customer AS customer
                   ON customer.cust_id = mis_projects.project_client_id
                   AND customer.deleted = 0
            $where
            ORDER BY project_fileno ASC
        ");

        return parent::fetchQuery($cond);
    }

    public function getPropertyDocReport($cond = array())
    {
        @$cond = array_filter($cond);

        if (! empty($cond['f_propno']))
            $where[] = "project_code LIKE '%' || :f_propno || '%'";

        if (! empty($cond['f_prop_cat']))
            $where[] = "project_category = :f_prop_cat";

        if (! empty($cond['f_prop_type']))
            $where[] = "project_type = :f_prop_type";

        $where[] = 'mis_projects.deleted = 0';
        $where = ' WHERE ' . implode(' AND ', $where);

        $this->query("
            SELECT project_id,
                   project_code,
                   project_category,
                   project_type,
                   project_fileno,
                   customer.cust_name,
                   propdocs.*
            FROM mis_projects
            LEFT JOIN mis_customer AS customer
                   ON customer.cust_id = mis_projects.project_client_id
                   AND customer.deleted = 0
            INNER JOIN
              (
                SELECT doc_id,
                       doc_type,
                       doc_ref_id,
                       doc_no,
                       doc_desc,
                       doc_remarks,
                       to_char(doc_apply_date,'DD/MM/YYYY') AS doc_apply_date,
                       to_char(doc_issue_date,'DD/MM/YYYY') AS doc_issue_date,
                       to_char(doc_expiry_date,'DD/MM/YYYY') AS doc_expiry_date,
                       doc_expiry_date AS doc_expiry_month
                FROM
                  (
                    SELECT max(doc_id) AS mdoc_id
                    FROM mis_documents
                    WHERE doc_ref_type = " . DOC_TYPE_PROJECT . "
                      AND deleted = 0
                    GROUP BY doc_type, doc_ref_type, doc_ref_id
                  ) max_group
                LEFT JOIN mis_documents docs ON docs.doc_id = max_group.mdoc_id
                AND docs.deleted = 0
              ) AS propdocs ON propdocs.doc_ref_id = mis_projects.project_id
            $where
            ORDER BY project_fileno ASC, doc_expiry_month ASC
        ");

        return parent::fetchQuery($cond);
    }

    public function getPropertyPayReport($cond = array())
    {}

    public function getRentDrillDownYears($cond = array())
    {}

    public function getRentDrillDownMonthByYear($cond = array())
    {}

    public function getPropDocExpiryReport($cond = array())
    {
        $monthYear = explode('/', $cond['f_monthpick']);

        if ($cond['f_monthpick'] == 'past') {
            $date = new DateTime();
            $where = "((EXTRACT(month FROM doc_expiry_month) < '" . $date->format('m') . "' 
                       AND EXTRACT(year FROM doc_expiry_month) <= '" . $date->format('Y') . "') 
                       OR (EXTRACT(year FROM doc_expiry_month) < '" . $date->format('Y') . "'))";
        } else {
            $where = "(EXTRACT(month FROM doc_expiry_month) = '$monthYear[0]' 
                       AND EXTRACT(year FROM doc_expiry_month) = '$monthYear[1]')";
        }

        $this->query("
            SELECT count(doc_type) AS count,
                   doc_type
            FROM mis_projects
            INNER JOIN
              (
                SELECT doc_type,
                       doc_ref_id,
                       doc_expiry_date AS doc_expiry_month
                FROM
                  (
                    SELECT max(doc_id) AS mdoc_id
                    FROM mis_documents
                    WHERE doc_ref_type = " . DOC_TYPE_PROJECT . "
                      AND deleted = 0
                    GROUP BY doc_type, doc_ref_type, doc_ref_id
                  ) max_group
                LEFT JOIN mis_documents docs ON docs.doc_id = max_group.mdoc_id
                AND docs.deleted = 0
              ) propdocs ON propdocs.doc_ref_id = mis_projects.project_id
            WHERE $where
              AND mis_projects.project_status = 2
              AND mis_projects.deleted = 0
            GROUP BY doc_type
        ");

        return parent::fetchQuery($cond);
    }
    
    public function getProjectsPair($cond = array()) {
        $this->query ( "select project_id,project_name from $this->_table" );
        if(!empty($cond['project_client_id']))
            $this->_where[]  = "project_client_id = :project_client_id";  
        $this->_order [] = 'project_name ASC';
        
        return parent::fetchPair ( $cond );
    }

    public function getTenantsReport($cond = array())
    {}

    public function getDashsummary($cond = [])
    {}

    public function getDashsummaryGraph($cond = [])
    {}

    public function getFinancialExpense($cond = [])
    {}

    public function getFinancialRevenue($cond = [])
    {}
    
}
