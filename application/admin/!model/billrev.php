<?php

class billrev extends db_table
{

    protected $_table = "mis_bill_revenue";

    protected $_pkey = "brev_id";

    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        $this->_where[] = "brev_bill_id = :brev_bill_id";
        return parent::update($data, $cond);
    }

    public function getRevenuePairType1($cond = array())
    {
        $this->query("select brev_group_id, brev_revenue from $this->_table ");

        $this->_where[] = "brev_type = 1";

        if (! empty($cond['brev_bill_id_in']) && is_array($cond['brev_bill_id_in'])){
            $this->_where[] = "brev_bill_id IN (" . implode(',', $cond['brev_bill_id_in']) . ")";
            unset($cond['brev_bill_id_in']);
        }
        else
            $this->_where[] = "brev_bill_id = :brev_bill_id";

        return parent::fetchPair($cond);
    }

    public function getRevenueListPairType2($cond = array()) // Get it from bil details initilly 
    {
        $this->query("select brev_id, brev_revenue from $this->_table ");

        $cond['brev_type'] = 2;
        
        $this->_where[] = "brev_type = :brev_type";

        if (! empty($cond['brev_bill_id_in']) && is_array($cond['brev_bill_id_in'])){
            $this->_where[] = "brev_bill_id IN (" . implode(',', $cond['brev_bill_id_in']) . ")";
            unset($cond['brev_bill_id_in']);
        }
        else
            $this->_where[] = "brev_bill_id = :brev_bill_id";

        return parent::fetchPair($cond);
    }

    public function getRevenueListType2($cond = array())
    {
        $this->query("select * from $this->_table ");

        $cond['brev_type'] = 2;

        $this->_where[] = "brev_type = :brev_type";

        if (is_array($cond['brev_bill_id_in']) && ! empty(array_filter($cond['brev_bill_id_in']))) {
            $this->_where[] = "brev_bill_id IN (" . implode(',', $cond['brev_bill_id_in']) . ")";
            unset($cond['brev_bill_id_in']);

            return parent::fetchAll($cond);
        } else if (! empty($cond['brev_bill_id'])) {
            $this->_where[] = "brev_bill_id = :brev_bill_id";
            return parent::fetchAll($cond);
        }
    }

    public function deleteByCollectionId($cond = array())
    {
        $this->_where[] = "brev_bill_id= :brev_bill_id";
        return parent::deleteByCond($cond);
    }

    public function getRevenueDetails($cond)
    {
        $this->query("SELECT $this->_table.*
                      FROM $this->_table
                      LEFT JOIN mis_bill AS bill ON bill.bill_id = $this->_table.brev_bill_id AND bill.deleted = 0");

        $this->_where[] = "brev_bill_id = :brev_bill_id";
        return parent::fetchAll($cond);
    }

    public function getRevenueByGroup($cond)
    {
        $this->query("SELECT $this->_table.brev_group_id, SUM($this->_table.brev_revenue) AS total_revenue
                      FROM $this->_table
                      WHERE $this->_table.deleted = 0
                      GROUP BY $this->_table.brev_group_id");

        if (! empty($cond['brev_group_id'])) {
            $this->_where[] = "brev_group_id = :brev_group_id";
        }

        return parent::fetchAll($cond);
    }

    public function getRevenueById($id)
    {
        return parent::getById($id);
    }

    public function deleteRevenue($id)
    {
        return parent::delete($id);
    }

    public function deleteRevenueByCondition($cond = array())
    {
        if (! empty($cond['brev_bill_id'])) {
            $this->_where[] = "brev_bill_id = :brev_bill_id";
        }
        return parent::deleteByCond($cond);
    }

    public function getRevenueWithDetails($cond)
    {
        $this->query("SELECT
                          $this->_table.*,
                          bill.bill_oribill_amt
                          FROM $this->_table
                          LEFT JOIN mis_bill AS bill ON bill.bill_id = $this->_table.brev_bill_id AND bill.deleted = 0");

        if (! empty($cond['brev_bill_id'])) {
            $this->_where[] = "brev_bill_id = :brev_bill_id";
        }

        return parent::fetchAll($cond);
    }
}
