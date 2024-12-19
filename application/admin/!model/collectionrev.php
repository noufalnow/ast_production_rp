<?php
class collectionrev extends db_table {
    protected $_table = "mis_collection_revenue";
    protected $_pkey = "rev_id";
    
    public function add($data) {
        return parent::insert($data);
    }
    
    public function modify($data, $cond) {
        $this->_where[] = "rev_coll_id = :rev_coll_id";
        return parent::update($data, $cond);
    }
    
    public function getRevenuePairType1($cond = array())
    {
        $this->query("select rev_group_id, rev_revenue from $this->_table ");

        $this->_where[] = "rev_type = 1";
        
        $this->_where[] = "rev_coll_id = :rev_coll_id";
        
        return parent::fetchPair($cond);
    }
    
    
    public function getRevenueListPairType2($cond = array())
    {
        $this->query("select rev_group_id, rev_revenue from $this->_table ");
        
        $this->_where[] = "rev_type = 2";
        
        $this->_where[] = "rev_coll_id = :rev_coll_id";
        
        return parent::fetchPair($cond);
    }
    
    
    public function getRevenueListType2($cond = array())
    {
        $this->query("select * from $this->_table ");
        
        $this->_where[] = "rev_type = 2";
        
        $this->_where[] = "rev_coll_id = :rev_coll_id";
        
        return parent::fetchAll($cond);
    }
    
    
    public function deleteByCollectionId($cond=array()){
        $this->_where [] = "rev_coll_id= :rev_coll_id";
        return parent::deleteByCond( $cond);
    }
    
    
    
    public function getRevenueDetails($cond) {
        $this->query("SELECT $this->_table.*
                      FROM $this->_table
                      LEFT JOIN mis_collection AS coll ON coll.coll_id = $this->_table.rev_coll_id AND coll.deleted = 0
                      LEFT JOIN mis_bill AS bill ON bill.bill_id = $this->_table.rev_bill_id AND bill.deleted = 0");
        
        $this->_where[] = "rev_coll_id = :rev_coll_id";
        return parent::fetchAll($cond);
    }
    
    public function getRevenueByGroup($cond) {
        $this->query("SELECT $this->_table.rev_group_id, SUM($this->_table.rev_revenue) AS total_revenue
                      FROM $this->_table
                      WHERE $this->_table.deleted = 0
                      GROUP BY $this->_table.rev_group_id");
        
        if (!empty($cond['rev_group_id'])) {
            $this->_where[] = "rev_group_id = :rev_group_id";
        }
        
        return parent::fetchAll($cond);
    }
    
    public function getRevenueById($id) {
        return parent::getById($id);
    }
    
    public function deleteRevenue($id) {
        return parent::delete($id);
    }
    
    public function deleteRevenueByCondition($cond = array()) {
        if (!empty($cond['rev_coll_id'])) {
            $this->_where[] = "rev_coll_id = :rev_coll_id";
        }
        return parent::deleteByCond($cond);
    }
    
    public function getRevenueWithDetails($cond) {
        $this->query("SELECT
                        $this->_table.*,
                        coll.coll_paydate,
                        bill.bill_oribill_amt
                      FROM $this->_table
                      LEFT JOIN mis_collection AS coll ON coll.coll_id = $this->_table.rev_coll_id AND coll.deleted = 0
                      LEFT JOIN mis_bill AS bill ON bill.bill_id = $this->_table.rev_bill_id AND bill.deleted = 0");
                        
                        if (!empty($cond['rev_coll_id'])) {
                            $this->_where[] = "rev_coll_id = :rev_coll_id";
                        }
                        
                        return parent::fetchAll($cond);
    }
}
