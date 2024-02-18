<?php

class building extends db_table
{

    protected $_table = "mis_building";

    protected $_pkey = "bld_id";
    
    
    public function add($data) {
        return parent::insert ( $data );
    }
    
    public function modify($data, $cond) {
        return parent::update ( $data, $cond );
    }

    public function getBuildingPair($cond = array())
    {
        $this->query("select bld_id,bld_name from $this->_table");
        $this->_order[] = 'bld_name ASC';

        return parent::fetchPair($cond);
    }

    public function getBuildingPaginate($cond)
    {
        $this->paginate("select $this->_table.*", "from $this->_table");


        if (! empty($cond['f_bld_name']))
            $this->_where [] = "lower(bld_name) like '%' || lower(:f_bld_name) || '%'";
        if (! empty($cond['f_bld_no']))
            $this->_where [] = "lower(bld_no) like '%' || lower(:f_bld_no) || '%'";
        $this->_order[] = 'bld_name ASC';

        return parent::fetchAll($cond);
    }

    public function getBuildingDet($cond) {
        $this->query ( "select $this->_table.*
				from $this->_table " );
        if (! empty ( $cond ['bld_id'] ))
            $this->_where [] = "bld_id= :bld_id";
            
            return parent::fetchRow ( $cond );
    }
    
    public function getBuildingDetById($id){
        return parent::getById ($id);
    }
}


