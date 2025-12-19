<?php

class expense_line extends db_table
{

    protected $_table = "mis_expense_line";

    protected $_pkey = "exdtline_id";

    /* ---------- BASIC CRUD ---------- */
    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getExpenseLineById($id)
    {
        return parent::getById($id);
    }

    /**
     * OLD METHOD (kept for backward compatibility)
     * NOTE: Do NOT use this for add/edit anymore
     */
    public function fetchExpenseLineByTypeAndId($cond = [])
    {
        $this->query("select * from $this->_table");

        $this->_where[] = "exdtline_date_id = :exdtline_date_id";
        $this->_where[] = "exdtline_entity_id = :exdtline_entity_id";

        return parent::fetchRow($cond);
    }

    /*
     * ============================================================
     * NEW METHODS – SOURCE EXP SAFE (USE THESE)
     * ============================================================
     */

    /**
     * Fetch single line by Date + Entity + Source Expense
     */
    public function getLineByDateEntityAndSource($cond = [])
    {
        $this->query("select * from $this->_table");

        if (! empty($cond['exdtline_date_id']))
            $this->_where[] = "exdtline_date_id = :exdtline_date_id";

        if (! empty($cond['exdtline_entity_id']))
            $this->_where[] = "exdtline_entity_id = :exdtline_entity_id";

        if (! empty($cond['source_exp_id']))
            $this->_where[] = "source_exp_id = :source_exp_id";


        return parent::fetchRow($cond);
    }

    /**
     * Fetch all lines contributed by ONE expense
     * Used before EDIT to reverse old values
     */
    public function getLinesBySourceExpense($cond = [])
    {
        $this->query("select * from $this->_table");

        if (! empty($cond['source_exp_id']))
            $this->_where[] = "source_exp_id = :source_exp_id";


        return parent::fetchAll($cond);
    }

    /**
     * Reverse a single expense line (subtract its contribution)
     */
    public function reverseLine($line)
    {
        return $this->modify([
            'exdtline_amount' => $line['exdtline_amount'] * - 1
        ], [
            'exdtline_id' => $line['exdtline_id']
        ]);
    }

    /**
     * Cleanup zero-amount lines after reversal
     */
    public function cleanupZeroLines($expenseId)
    {
        $this->_where[] = "source_exp_id = :source_exp_id";
        $this->_where[] = "exdtline_amount = 0";
        return parent::deleteByCond(['source_exp_id'=>$expenseId]);
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

        if (! empty($cond['expdt_id']))
            $this->_where[] = "l.exdtline_date_id = :expdt_id";

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

        if (! empty($cond['date_from']))
            $this->_where[] = "d.expdt_date >= :date_from";

        if (! empty($cond['date_to']))
            $this->_where[] = "d.expdt_date <= :date_to";


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

        if (! empty($cond['date_from']))
            $this->_where[] = "d.expdt_date >= :date_from";

        if (! empty($cond['date_to']))
            $this->_where[] = "d.expdt_date <= :date_to";


        $this->_group[] = "p.expent_id, p.expent_name";
        $this->_order[] = "p.expent_name ASC";

        return parent::fetchAll($cond);
    }
}
