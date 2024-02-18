<?php
class vehicletype extends db_table {
	protected $_table = "mis_vehicle_type";
	protected $_pkey = "type_id";
	

	public function getVehiclePair($cond = array()) {
		$this->query ( "select type_id,type_name from $this->_table" );
		$this->_order [] = 'type_name ASC';
		
		return parent::fetchPair ( $cond );
	}

}


