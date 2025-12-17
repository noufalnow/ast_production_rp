<?php
class propstatus extends db_table {
	protected $_table = "mis_property_status";
	protected $_pkey = "psts_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	public function getStatusById($id) {
		return parent::getById ($id);
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}	
	
	public function deleteDocument($id) {
		return parent::delete ( $id );
	}
	
	public function getPropertyStatusDet($cond) {
		$this->query ( "select $this->_table.*,
				building.bld_name,
				atchprop.prop_no as atach_prop_no,
				atchprop.prop_fileno as atach_file_no,
				property.prop_no,
				property.prop_fileno 
				from $this->_table
				left join mis_projects as property on property.prop_id = $this->_table.psts_prop_id and property.deleted = 0
				left join mis_building as building on building.bld_id = property.prop_building and building.deleted = 0
				left join mis_projects as atchprop on atchprop.prop_id = $this->_table.psts_attach_prop and atchprop.deleted = 0
				" );
		if (! empty ( $cond ['psts_prop_id'] ))
			$this->_where [] = "psts_prop_id= :psts_prop_id";
		
		$this->_order [] = "psts_id DESC";
		
		return parent::fetchRow ( $cond );
	}
	
}


