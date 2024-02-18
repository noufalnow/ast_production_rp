<?php
class billdet extends db_table {
	protected $_table = "mis_bill_det";
	protected $_pkey = "bdet_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getVehiclePaginate($cond){
		
		$this->paginate ( "select $this->_table.*, 
				type.type_name
				", "from $this->_table 
				left join mis_vehicle_type as type on type.type_id = $this->_table.bill_type and type.deleted = 0
				" );
		
		if (!empty ( $cond ['f_vhlno'] ))
			$this->_where [] = "bill_no like '%' || :f_vhlno || '%'";
		
		if (!empty ( $cond ['f_model'] ))
			$this->_where [] = "cast(bill_model as text) like '%' || :f_model || '%'";
		
		if (! empty ( $cond ['f_company'] ))
			$this->_where [] = "bill_company = :f_company";
		
		if (! empty ( $cond ['f_type'] ))
			$this->_where [] = "bill_type = :f_type";
		
		
		$this->_order [] = 'type.type_name ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getBillDet($cond) {
		$this->query ( "select $this->_table.*, 
				item.item_name,
				item.item_unit
				from $this->_table 
				left join mis_item as item on item.item_id = $this->_table.bdet_item and item.deleted = 0
				" );
		if (! empty ( $cond ['bdet_bill_id'] ))
			$this->_where [] = "bdet_bill_id= :bdet_bill_id";
		//@possible 0 on condition		
		if (! empty($cond ['bdet_update_sts']) || strlen($cond ['bdet_update_sts']) > 0)
		//if (! empty ( $cond ['bdet_update_sts'] ))
			$this->_where [] = "bdet_update_sts= :bdet_update_sts";
		
		$this->_order [] = 'bdet_id ASC';
		return parent::fetchAll ( $cond );
	}
	
	public function getMaxBillDet($cond) {
		$this->query ( "select MAX(bdet_update_sts) as max_update
				from $this->_table
				" );
		if (! empty ( $cond ['bdet_bill_id'] ))
			$this->_where [] = "bdet_bill_id= :bdet_bill_id";
		return parent::fetchRow( $cond );
	}
	
	
	
	public function getVehicleDetById($id){
		return parent::getById ($id);
	}
	
	public function getVehicleById($id){
		return parent::getById ($id);
	}
	
	public function deleteVehicle($id) {
		return parent::delete ( $id );
	}
	

}?>