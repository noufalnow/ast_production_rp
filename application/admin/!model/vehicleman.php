<?php

class vehicleman extends db_table
{

    protected $_table = "mis_vehicle_man";

    protected $_pkey = "vman_id";

    public function getVManPair($cond = array())
    {
        $this->query("select vman_id,vman_name from $this->_table");
        $this->_order[] = 'vman_name ASC';

        return parent::fetchPair($cond);
    }

    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }


    public function getVhlmanPaginate($cond)
    {

        // $cond ['Vhlvman_type'] = 1;
        $this->paginate("select $this->_table.* ", "from $this->_table");

        if (! empty($cond['f_code']))
            $this->_where[] = " (vman_name)  like '%' || :f_code || '%' ";

        if (! empty($cond['f_name']))
            $this->_where[] = "vman_name like '%' || :f_name || '%'";


        $this->_order[] = 'vman_name ASC';

        return parent::fetchAll($cond);
    }

    public function getVhlmanDet($cond)
    {
        $this->query("select $this->_table.*
				from $this->_table ");
        if (! empty($cond['vman_id']))
            $this->_where[] = "vman_id= :vman_id";

        return parent::fetchRow($cond);
    }

    public function getVhlmanDetById($id)
    {
        return parent::getById($id);
    }

    public function getVhlmanById($id)
    {
        return parent::getById($id);
    }
}


