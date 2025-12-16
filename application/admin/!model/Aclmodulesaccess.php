<?php

class Aclmodulesaccess extends db_table
{

    protected $_table = "cnfg_acl_modules_access";

    protected $_pkey = "macc_id";

    public function add($data)
    {
        $this->_nolog = true;
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        $this->_nolog = true;
        return parent::update($data, $cond);
    }

    public function getModuleAccessPair($cond = array())
    {
        $this->query("select macc_id,module_name from $this->_table");
        $this->_order[] = 'module_name ASC';

        return parent::fetchPair($cond);
    }

    public function getModuleAccessList($cond)
    {
        $this->query("select $this->_table.* 
				from $this->_table ");
        return parent::fetchAll($cond);
    }

    public function getModuleAccessBy($cond)
    {
        $this->query("select $this->_table.* 
				from $this->_table ");
        if (! empty($cond['macc_id']))
            $this->_where[] = "macc_id= :macc_id";

        return parent::fetchAll($cond);
    }

    public function getModuleAccessById($id)
    {
        return parent::getById($id);
    }

    public function deleteModuleAccess($id)
    {
        $this->_nolog = true;
        return parent::delete($id);
    }

    public function getModuleRoleDetails($cond)
    {
        $this->query("select $this->_table.*
				from $this->_table ");
        $this->_where[] = "macc_module_id= :macc_module_id";
        $this->_where[] = "macc_role_type= :macc_role_type";
        $this->_where[] = "macc_role_id= :macc_role_id";

        return parent::fetchRow($cond);
    }

    public function getModuleRoleDetailsByRoles($cond, $returntype = null)
    {
        $resultArray = array();

        $this->query("select $this->_table.*
				from $this->_table ");
        $this->_where[] = "macc_role_type= :macc_role_type";
        $this->_where[] = "macc_role_id= :macc_role_id";

        $result = parent::fetchAll($cond);

        if ($returntype == 'keys')
            foreach ($result as $modules) {
                if ($modules['macc_create_status'] == '2')
                    $resultArray[$modules['macc_module_id']][] = 'create';
                if ($modules['macc_view_status'] == '2')
                    $resultArray[$modules['macc_module_id']][] = 'view';
                if ($modules['macc_update_status'] == '2')
                    $resultArray[$modules['macc_module_id']][] = 'update';
                if ($modules['macc_delete_status'] == '2')
                    $resultArray[$modules['macc_module_id']][] = 'delete';
            }

        else
            foreach ($result as $modules)
                $resultArray[$modules['macc_module_id']] = array(
                    "create" => $modules['macc_create_status'],
                    "view" => $modules['macc_view_status'],
                    "update" => $modules['macc_update_status'],
                    "del" => $modules['macc_delete_status']
                );

        return $resultArray;
    }
    
    public function deleteModuleAccessByUser($cond=array()){
        $this->_nolog = true;
        $cond['macc_role_type'] = '2'; 
        $this->_where [] = "macc_role_id= :macc_role_id";
        $this->_where [] = "macc_role_type= :macc_role_type";
        return parent::deleteByCond( $cond);
    }
    
}