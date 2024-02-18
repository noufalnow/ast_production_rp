<?php

class tenants extends db_table
{

    protected $_table = "mis_tenants";

    protected $_pkey = "tnt_id";

    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getTenantsPair($cond = array())
    {
        $this->query("select tnt_id,tnt_full_name from $this->_table");
        $this->_order[] = 'tnt_full_name ASC';

        return parent::fetchPair($cond);
    }

    public function getTenantsPaginate($cond)
    {
        $this->paginate("select $this->_table.*,
                        CASE WHEN tnt_agr_type = 1 THEN 'Company'
                        ELSE 'Individual' END AS tnt_agr_type,
                        CASE WHEN tnt_expat = 1 THEN 'National'
                        ELSE 'Expart' END AS tnt_expat", "from $this->_table");
        
        if (! empty($cond['tnt_full_name']))
            $this->_where[] = "lower(tnt_full_name) like '%' || lower(:tnt_full_name) || '%'";
        if (! empty($cond['tnt_phone']))
            $this->_where[] = "lower(tnt_phone) like '%' || lower(:tnt_phone) || '%'";
        if (! empty($cond['tnt_comp_name']))
            $this->_where[] = "lower(tnt_comp_name) like '%' || lower(:tnt_comp_name) || '%'";
        if (! empty($cond['tnt_tele']))
            $this->_where[] = "lower(tnt_tele) like '%' || lower(:tnt_tele) || '%'";
        if (! empty($cond['tnt_id_no']))
            $this->_where[] = "lower(tnt_id_no) like '%' || lower(:tnt_id_no) || '%'";
        if (! empty($cond['tnt_crno']))
            $this->_where[] = "lower(tnt_crno) like '%' || lower(:tnt_crno) || '%'";
        
        if (! empty($cond['tnt_agr_type']))
            $this->_where[] = "tnt_agr_type= :tnt_agr_type";
        if (! empty($cond['tnt_expat']))
            $this->_where[] = "tnt_expat= :tnt_expat";

        $this->_order[] = 'tnt_full_name ASC';

        return parent::fetchAll($cond);
    }

    public function getTenantsDet($cond)
    {
        $this->query("select $this->_table.*,
                        tnt_agr_type as tnt_agr_type_id,
                        tnt_expat as tnt_expat_id,
                        CASE WHEN tnt_agr_type = 1 THEN 'Company'
                        ELSE 'Individual' END AS tnt_agr_type,
                        CASE WHEN tnt_expat = 1 THEN 'National'
                        ELSE 'Expart' END AS tnt_expat,
                        idfiles.file_id as idfile,
                        crfiles.file_id as crfile,
                        docsid.doc_id as docsid,
                        docscr.doc_id as docscr
				from $this->_table 
                LEFT JOIN mis_documents AS docsid ON docsid.doc_type = ".DOC_TYPE_TNT_ID."
                            AND docsid.doc_ref_type = ".DOC_TYPE_TNT."
							AND docsid.doc_ref_id = tnt_id
							AND docsid.deleted = 0
                LEFT JOIN mis_documents AS docscr ON docscr.doc_type = ".DOC_TYPE_TNT_CR."
                            AND docscr.doc_ref_type = ".DOC_TYPE_TNT."
                            AND docscr.doc_ref_id = tnt_id
							AND docscr.deleted = 0
                LEFT JOIN core_files as idfiles on idfiles.file_ref_id = docsid.doc_id and idfiles.file_type = ".DOC_TYPE_TNT." and idfiles.deleted = 0
                LEFT JOIN core_files as crfiles on crfiles.file_ref_id = docscr.doc_id and crfiles.file_type = ".DOC_TYPE_TNT." and crfiles.deleted = 0
                ");
               
        if (! empty($cond['tnt_id']))
            $this->_where[] = "tnt_id= :tnt_id";

        return parent::fetchRow($cond);
    }

    public function getTenantsDetById($id)
    {
        return parent::getById($id);
    }
}


