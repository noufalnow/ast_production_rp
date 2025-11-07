<?php

class vehicletype extends db_table
{

    protected $_table = "mis_vehicle_type";

    protected $_pkey = "type_id";

    public function getVehiclePair($cond = array())
    {
        $this->query("select type_id,type_name from $this->_table");
        $this->_order[] = 'type_name ASC';

        return parent::fetchPair($cond);
    }
    
    public function getCommercialVehiclePair($cond = array())
    {
        $this->query("select type_id,type_name from $this->_table
                      join mis_vehicle  as veh on veh.vhl_type = type_id and veh.vhl_comm_status = 2
            ");
        $this->_order[] = 'type_name ASC';
        
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


    public function getVhltypePaginate($cond)
    {

        // $cond ['Vhltype_type'] = 1;
        $this->paginate("select $this->_table.* ", "from $this->_table");

        if (! empty($cond['f_code']))
            $this->_where[] = " (type_name)  like '%' || :f_code || '%' ";

        if (! empty($cond['f_name']))
            $this->_where[] = "type_name like '%' || :f_name || '%'";


        $this->_order[] = 'type_name ASC';

        return parent::fetchAll($cond);
    }

    public function getVhltypeDet($cond)
    {
        $this->query("select $this->_table.*
				from $this->_table ");
        if (! empty($cond['type_id']))
            $this->_where[] = "type_id= :type_id";

        return parent::fetchRow($cond);
    }

    public function getVhltypeDetById($id)
    {
        return parent::getById($id);
    }

    public function getVhltypeById($id)
    {
        return parent::getById($id);
    }
}


