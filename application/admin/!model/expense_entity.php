<?php
class expense_entity extends db_table
{

    protected $_table = "mis_expense_entity";

    protected $_pkey = "expent_id";

    /* ---------- BASIC CRUD ---------- */
    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getEntityById($id)
    {
        return parent::getById($id);
    }

    /* ---------- PAIR (FOR DROPDOWNS) ---------- */
    public function getEntityPair($cond = [])
    {
        $this->query("
            select expent_id, expent_name
            from $this->_table
        ");
        $this->_order[] = "expent_name ASC";

        return parent::fetchPair($cond);
    }

    /* ---------- PAGINATION ---------- */
    public function getEntityPaginate($cond = [])
    {
        $this->paginate("select $this->_table.*", "from $this->_table");

        if (! empty($cond['expent_type']))
            $this->_where[] = "expent_type = :expent_type";

        if (! empty($cond['f_name']))
            $this->_where[] = "expent_name like '%' || :f_name || '%'";

        if (! empty($cond['parent_expent_id']))
            $this->_where[] = "parent_expent_id = :parent_expent_id";

        $this->_order[] = "expent_name ASC";

        return parent::fetchAll($cond);
    }
    
    
    
    
    public function fetchEntityByTypeAndId($cond = [])
    {
        $this->query("select * from $this->_table");
        

         $this->_where[] = "expent_type = :expent_type";
         $this->_where[] = "expent_ref_id = :expent_ref_id";
            

            
            return parent::fetchRow($cond);
    }

    

    /* ---------- DETAIL ---------- */
    public function getEntityDet($cond = [])
    {
        $this->query("select * from $this->_table");

        if (! empty($cond['expent_id']))
            $this->_where[] = "expent_id = :expent_id";



        return parent::fetchRow($cond);
    }
}
