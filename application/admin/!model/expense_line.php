<?php
class expense_line extends db_table
{
    protected $_table = "mis_expense_line";
    protected $_pkey  = "exdtline_id";
    
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
    
    /* ---------- LINES BY DATE ---------- */
    
    public function getLinesByDate($cond = [])
    {
        $this->query("
            select
                l.*,
                e.expent_name,
                e.expent_type,
                e.expent_unit
            from mis_expense_line l
            join mis_expense_entity e on e.expent_id = l.exdtline_entity_id
        ");
        
        if (!empty($cond['expdt_id']))
            $this->_where[] = "l.exdtline_date_id = :expdt_id";
            
            $this->_where[] = "l.deleted = 0";
            $this->_order[] = "e.expent_name ASC";
            
            return parent::fetchAll($cond);
    }
    
    /* ---------- REPORT: ENTITY-WISE SUM ---------- */
    
    public function getEntityWiseSum($cond = [])
    {
        $this->query("
            select
                e.expent_id,
                e.expent_name,
                sum(l.exdtline_amount) as total_amount,
                sum(l.exdtline_qty) as total_qty
            from mis_expense_line l
            join mis_expense_entity e on e.expent_id = l.exdtline_entity_id
            join mis_expense_date d on d.expdt_id = l.exdtline_date_id
        ");
        
        if (!empty($cond['date_from']))
            $this->_where[] = "d.expdt_date >= :date_from";
            
            if (!empty($cond['date_to']))
                $this->_where[] = "d.expdt_date <= :date_to";
                
                $this->_where[] = "l.deleted = 0";
                
                $this->_group[] = "e.expent_id, e.expent_name";
                $this->_order[] = "e.expent_name ASC";
                
                return parent::fetchAll($cond);
    }
    
    /* ---------- REPORT: CATEGORY-WISE SUM ---------- */
    
    public function getCategoryWiseSum($cond = [])
    {
        $this->query("
            select
                p.expent_id as category_id,
                p.expent_name as category_name,
                sum(l.exdtline_amount) as total_amount
            from mis_expense_line l
            join mis_expense_entity e on e.expent_id = l.exdtline_entity_id
            join mis_expense_entity p on p.expent_id = e.parent_expent_id
            join mis_expense_date d on d.expdt_id = l.exdtline_date_id
        ");
        
        if (!empty($cond['date_from']))
            $this->_where[] = "d.expdt_date >= :date_from";
            
            if (!empty($cond['date_to']))
                $this->_where[] = "d.expdt_date <= :date_to";
                
                $this->_where[] = "l.deleted = 0";
                
                $this->_group[] = "p.expent_id, p.expent_name";
                $this->_order[] = "p.expent_name ASC";
                
                return parent::fetchAll($cond);
    }
}
