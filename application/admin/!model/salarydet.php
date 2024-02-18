<?php

class salarydet extends db_table
{

    protected $_table = "mis_salary_det";

    protected $_pkey = "sdet_id";

    public function add($data)
    {
        $this->_nolog = true;
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        $this->_nolog = true;
        $this->_where[] = "sdet_sal_id= :sdet_sal_id";
        $this->_where[] = "sdet_emp_id= :sdet_emp_id";

        return parent::update($data, $cond);
    }

    public function getSalaryDetList($cond)
    {
        $this->query("select $this->_table.*
							from $this->_table
                            left join mis_employee as emp on emp.emp_id = sdet_emp_id and emp.deleted= 0
				");

        if (! empty($cond['f_company']))
            $this->_where[] = "cmpdept_comp_id = :f_company";
        if (! empty($cond['f_dept']))
            $this->_where[] = "cmpdept_id = :f_dept";
        if (! empty($cond['f_desig']))
            $this->_where[] = "desig_id = :f_desig";
        if (! empty($cond['f_natonality']))
            $this->_where[] = "emp_nationality = :f_natonality";

        $this->_where[] = "sdet_sal_id= :sdet_sal_id";
        $this->_order[] = "sdet_group ASC";

        return parent::fetchAll($cond);
    }

    public function getsalaryDet($cond)
    {
        $this->query("select $this->_table.* ,
				contact.* 
				from $this->_table 
				");
        if (! empty($cond['sdet_id']))
            $this->_where[] = "sdet_id= :sdet_id";

        return parent::fetchRow($cond);
    }

    public function getsalaryDetById($id)
    {
        return parent::getById($id);
    }

    public function getsalaryById($id)
    {
        return parent::getById($id);
    }

    public function deletesalary($id)
    {
        $this->_nolog = true;
        return parent::delete($id);
    }

    public function deletePayDetByExpId($cond = array())
    {
        $this->_nolog = true;
        $this->_where[] = "sdet_sal_id= :sdet_sal_id";
        return parent::deleteByCond($cond);
    }

    public function getEmpSalIdPair($cond = array())
    {
        $this->query("select sdet_emp_id,sdet_id as selection from $this->_table");

        $this->_where[] = "sdet_sal_id = :sdet_sal_id";
        $this->_order[] = 'sdet_id ASC';

        return parent::fetchPair($cond);
    }
}


