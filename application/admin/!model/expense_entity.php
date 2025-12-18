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

    public function getById($id)
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

        $this->_where[] = "deleted = 0";
        $this->_where[] = "active = 1";
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

        $this->_where[] = "deleted = 0";
        $this->_order[] = "expent_name ASC";

        return parent::fetchAll($cond);
    }

    /* ---------- DETAIL ---------- */
    public function getEntityDet($cond = [])
    {
        $this->query("select * from $this->_table");

        if (! empty($cond['expent_id']))
            $this->_where[] = "expent_id = :expent_id";

        $this->_where[] = "deleted = 0";

        return parent::fetchRow($cond);
    }
}
