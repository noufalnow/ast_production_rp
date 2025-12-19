<?php
class expense_date extends db_table
{

    protected $_table = "mis_expense_date";

    protected $_pkey = "expdt_id";

    /* ---------- BASIC CRUD ---------- */
    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getExpenseDateById($id)
    {
        return parent::getById($id);
    }

    /* ---------- PAGINATION ---------- */
    public function getExpenseDatePaginate($cond = [])
    {
        $this->paginate("select $this->_table.*", "from $this->_table");

        if (! empty($cond['f_date_from']))
            $this->_where[] = "expdt_date >= :f_date_from";

        if (! empty($cond['f_date_to']))
            $this->_where[] = "expdt_date <= :f_date_to";

        $this->_order[] = "expdt_date DESC";

        return parent::fetchAll($cond);
    }

    /* ---------- DETAIL ---------- */
    public function getExpenseDateDet($cond = [])
    {
        $this->query("select * from $this->_table");

        if (! empty($cond['expdt_id']))
            $this->_where[] = "expdt_id = :expdt_id";

        return parent::fetchRow($cond);
    }
    
    
    public function getExpenseDateByDate($cond = [])
    {
        $this->query("select * from $this->_table");
        
        $this->_where[] = "expdt_date = :expdt_date";
            
        return parent::fetchRow($cond);
    }
}
