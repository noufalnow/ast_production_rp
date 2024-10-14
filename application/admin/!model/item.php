<?php
class item extends db_table {
	protected $_table = "mis_item";
	protected $_pkey = "item_id";
	

	public function add($data) {
		return parent::insert ( $data );
	}
	
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	
	public function getItemPair($cond = array()) {
		
		$this->query ( "select item_id,item_code || ' - ' ||item_name|| ' (' ||item_unit|| ' - ' ||item_price || ')' from $this->_table" );
		
		if (! empty ( $cond ['item_type'] ))
			$this->_where [] = "item_type= :item_type";
		
		$this->_order [] = 'item_name ASC';
	
		return parent::fetchPair ( $cond );
	}
	
	
	public function getSrvItemPair($cond = array()) {
		
		$this->query ( "select item_id,item_code || ' - ' ||item_name from $this->_table" );
		
		if (! empty ( $cond ['item_type'] ))
			$this->_where [] = "item_type= :item_type";
			
			$this->_order [] = 'item_name ASC';
			
			return parent::fetchPair ( $cond );
	}
	
		
	public function getItemsPaginate($cond){
		
		
		//$cond ['item_type'] = 1;

		
		$this->paginate ( "select $this->_table.*,
				case when item_type = 1 then 'Invoice Items'
					 when item_type = 2 then 'Service Items'
				end as item_type,vhl_no
				", "from $this->_table
				left join mis_vehicle as vehl on vehl.vhl_id = $this->_table.item_vehicle and vehl.deleted = 0" );
		
		
		if (! empty ( $cond ['item_type'] ))
			$this->_where [] = "item_type= :item_type";
		
		if (!empty ( $cond ['f_code'] ))
			$this->_where [] = " (item_code)  like '%' || :f_code || '%' ";
		
		if (!empty ( $cond ['f_name'] ))
			$this->_where [] = "item_name like '%' || :f_name || '%'";
		
		if (!empty ( $cond ['f_remarks'] ))
			$this->_where [] = "item_remarks like '%' || :f_remarks || '%'";
		
		if (!empty ( $cond ['f_price'] ))
			$this->_where [] = "item_price::text like '%' || :f_price || '%'";
		
		
		if (! empty ( $cond ['f_type'] ))
		    $this->_where [] = "item_type= :f_type";
		
	    if (! empty ( $cond ['f_vehicle'] ))
	        $this->_where [] = "item_vehicle= :f_vehicle";
		
					
		$this->_order [] = 'item_code ASC';
		
		return parent::fetchAll ( $cond );
	}
	
	public function getItemDet($cond) {
		$this->query ( "select $this->_table.* 
				from $this->_table " );
		if (! empty ( $cond ['item_id'] ))
			$this->_where [] = "item_id= :item_id";
		
		return parent::fetchRow ( $cond );
	}
	
	public function getItemDetById($id){
		return parent::getById ($id);
	}
	
	public function getItemById($id){
		return parent::getById ($id);
	}
	
	public function deleteItem($id) {
		return parent::delete ( $id );
	}
	
}


