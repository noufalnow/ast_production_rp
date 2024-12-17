<?php
//@todo in array binding
class db_table {
	public $_objectRef = [ ];
	public $_dbArray = array ();
	protected $_db;
	protected $_table;
	protected $_pkey;
	protected $_where;
	protected $_group;
	protected $_order;
	protected $_limit;
	protected $_offset;
	protected $_qry;
	protected $_pager;
	protected $_pager_page;
	protected $_pages;
	protected $_nolog = false;
	protected $_data = array ();
	private $_param = array ();
	static $d = 0;
	
	public $_vlimit;
	public $_voffset;
	public $_pageholder = 'page';
	public $_pagelimit = PAGINATION_LIMIT;
	
	public function __construct() {
		$host = "localhost";
		//$username = "postgres";
		//$password = "postgres";
		
		$username = "ws2019@usr";
		$password = "ws2019@pwd";
		
		//User: astglesp_db_csol_ast
		//Database: astglesp_db_csol_ast
		
		//o8%1$?]z;=;+d$24j{
		
		$dbname = "db_csol_ast"; //db_csol_ast
		$port = 5432;
		
		/*$username = "dbadmin";
		$password = "dbadmin";
		$port = 3306;
		
		$dbname = "creath6g_csol_covidhd";
		$username = "dbadmin";
		$password = "DbAdmin@123";
		$port = 3306;*/
		
		//vhost 6002
		
		$this->_dbArray ['db_name'] = $dbname;
		$this->_dbArray ['db_user'] = $username;
		$this->_dbArray ['db_pwd'] = $password;
		$this->_dbArray ['db_host'] = $host;
		
		try {
			//$this->_db = new PDO ( 'mysql:host=' . $host . ';port=' . $port . '; dbname=' . $dbname . ';charset=utf8', $username, $password , array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->_db = new PDO ( 'pgsql:host=' . $host . ';port=' . $port . '; dbname=' . $dbname . '', $username, $password );
			$this->_db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			//print "Error!: " . $e->getMessage () . "<br/>";
			send_mail($e->getMessage ());
			die ();
		}
		self::$d = false;
	}
	protected function setObjectRef(&$ref) {
		// add to global arry or class
		$this->_objectRef [] = &$ref;
	}
	protected function query($qry) {
		$this->_qry = $qry;
	}
	protected function paginate($select, $qry) {
		$this->_pager = "SELECT count(*) as count " . $qry;
		$this->_qry = $select . " " . $qry;
	}
	protected function insert($data,$isLog='') {
		$tdata = $data;
		$data ["t_created"] = date_format ( new DateTime (), 'Y-m-d H:i:s' );
		$data ["u_created"] = USER_ID;
		
		$field = ' (' . implode ( ',', array_keys ( $data ) ) . ') ';
		$bind = ' (:' . implode ( ',:', array_keys ( $data ) ) . ') ';
		
		$this->_qry = "INSERT INTO $this->_table  $field VALUES $bind";
		$stmt = $this->_db->prepare ( $this->_qry );
		self::resetVariables();
		
		$insert =  self::commit ( $stmt, '', $data );
		if($insert)
			if(!$this->_nolog)
				userActivityLog(1,$insert,$tdata,$this->_table);
		return $insert;
	}
	protected function blkinsert($mi = 0,$biDataStr = '',$biData=array()) {
		
		$biDataStr = rtrim($biDataStr,',');
		$this->_qry = "INSERT INTO $this->_table  $biDataStr";
		$stmt = $this->_db->prepare ( $this->_qry );
		
		for($bic = 0; $bic<$mi; $bic++){
			$stmt->bindValue(':data_doc_id'.$bic, $biData['data_doc_id'][$bic] );
			$stmt->bindValue(':data_template_field'.$bic, $biData['data_template_field'][$bic] );
			$stmt->bindValue(':data_value'.$bic,$biData['data_value'][$bic] );
		}
		
		$this->_db->beginTransaction ();
		try {
			$result = $stmt->execute ();
			$this->_db->commit ();
			return $result;
		} catch ( PDOException $e ) {
			$this->_db->rollBack ();
			$this->_db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			exception_handler($e,$stmt->queryString,$data);
		}
	
		
	}
	
	protected function blkupdate($mi = 0,$biDataStr = '',$biData=array()) {
		
		$biDataStr = rtrim($biDataStr,',');
		$this->_qry = "INSERT INTO $this->_table  $biDataStr ON DUPLICATE KEY
						UPDATE data_id =
						VALUES(data_id),data_doc_id=
						VALUES(data_doc_id),data_template_field=
						VALUES(data_template_field),data_value=
						VALUES(data_value)";
		$stmt = $this->_db->prepare ( $this->_qry );
		
		for($bic = 0; $bic<$mi; $bic++){
			$stmt->bindValue(':data_id'.$bic, $biData['data_id'][$bic] );
			$stmt->bindValue(':data_doc_id'.$bic, $biData['data_doc_id'][$bic] );
			$stmt->bindValue(':data_template_field'.$bic, $biData['data_template_field'][$bic] );
			$stmt->bindValue(':data_value'.$bic,$biData['data_value'][$bic] );
		}
		
		
		$this->_db->beginTransaction ();
		try {
			$result = $stmt->execute ();
			$this->_db->commit ();
			return $result;
		} catch ( PDOException $e ) {
			$this->_db->rollBack ();
			$this->_db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			exception_handler($e,$stmt->queryString,$data);
		}
		
		
	}
	
	protected function update($data,$cond) {
			// @$cond = array_filter($cond);
		if (empty ( $cond )) {
			die ( 'No update condition provided' );
			return false;
		}
		if (! is_array ( $cond )) {
			$updateRef = $cond;
			$this->_where [] = $this->_table . "." . $this->_pkey . "= :" . $this->_pkey;
			$temp [$this->_pkey] = $cond;
			$cond = $temp;
			$cond ['deleted'] = 0;
			$updateType = 2;
			$tempWhere  = $this->_where; //keeping copy getById will reset all
			$existingData = $this->getById ( $updateRef, array_keys ( $data ) );
			$this->_where = $tempWhere;
			if ($existingData === $data)
				return '~';
			else {
				foreach ( $data as $ekey => $eval ) {
					if ($data [$ekey] !== $existingData [$ekey])
						$updateData [$ekey] = $eval;
				}
				$data = $updateData;
			}
		} else {
			$cond ['deleted'] = 0;
			if (count ( $this->_where ) == 0)
				throw new Exception("No update condition defined");
			$updateType = 22;
		}
		$tdata = $data;
		$data ['t_modified'] = date_format ( new DateTime (), 'Y-m-d H:i:s' );
		$data ['u_modified'] = USER_ID;
		
		//$field = ' (' . implode ( ',', array_keys ( $data ) ) . ') ';
		//$bind = ' (:' . implode ( ',:', array_keys ( $data ) ) . ') ';
		
		foreach ($data as $dtk=>$dtv)
			$set .= $dtk.' = :'.$dtk.' ,';
		
		$set = rtrim($set,' ,');
	
		
		$this->_qry = "UPDATE $this->_table SET $set";
		$this->_where [] = $this->_table . '.deleted = :deleted';
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		//v($this->_qry);
		//self::dbug( array_merge ( $data, $cond ));
		
		$stmt = $this->_db->prepare ( $this->_qry );
		self::resetVariables ();
		
		$param = array_merge ( $data, $cond );
		
		$update = self::commit ( $stmt, 'update', $param );
		if ($update)
		    if(!$this->_nolog)
			     userActivityLog ( $updateType, @$updateRef, $tdata, $this->_table );
		return $update;
	}
	protected function getById($id,$select='') {
		if (empty ( $id ))
			return false;
		
		$cond [$this->_pkey] = $id;
		$cond ['deleted'] = 0;
		$cond ['limit'] = 1;
		
		if($select){
			$select = implode(',', $select);
		}
		else
			$select = '*';
		
		$this->_qry = "SELECT $select from $this->_table WHERE ";
		$this->_qry .= $this->_table . "." . $this->_pkey . "= :" . $this->_pkey . " AND " . $this->_table . ".deleted = :deleted";
		$this->_qry .= " limit :limit";

		$stmt= $this->execute($cond);
		self::resetVariables();
		
		
		$result = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		return @$result ['0'];
	}
	protected function fetchRow($cond) {
	    
	    if(!is_array($cond))
	        $cond = [];
	        
        @$cond= array_filter($cond, function($value) {
            return ($value !== null && $value !== false && $value !== '');
        });
   
		$cond ['deleted'] = 0;
		$cond ['limit'] = 1;
		
		$this->_where [] = $this->_table . '.deleted = :deleted';
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		if (! empty ( $this->_group ))
			$this->_qry .= ' GROUP BY ' . implode ( ',', $this->_group );
		if (! empty ( $this->_order ))
			$this->_qry .= ' ORDER BY ' . implode ( ',', $this->_order );
		
		
		$this->_qry .= " limit :limit";
				
		//self::dbug($cond);
		
		$stmt= $this->execute($cond);
		self::resetVariables();
		
		$result = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		return @$result ['0'];
	}
	protected function fetchAll($cond=array()) {
		//@$cond = array_filter($cond);
	    
	    if(!is_array($cond))
	        $cond = [];

		@$cond= array_filter($cond, function($value) {
			return ($value !== null && $value !== false && $value !== '');
		});
		$cond ['deleted'] = 0;
		$this->_where [] = $this->_table . '.deleted = :deleted';
		
		if (! empty ( $this->_pager )) {
			
			$this->_pager .= ' WHERE ' . implode ( ' AND ', $this->_where );
			
			if (! empty ( $this->_group ))
				$this->_pager .= ' GROUP BY ' . implode ( ',', $this->_group );
				
			$this->_db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$stmt = $this->_db->prepare ( $this->_pager);
		
			$stmt->execute ( $cond );
			$count = $stmt->fetch ( PDO::FETCH_COLUMN );
			
			$this->_pages = ceil ( $count / $this->_pagelimit );
			
			if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest')
				$input_type = INPUT_POST;
			else 
				$input_type = INPUT_GET;
			
			$this->_pager_page = min ( $this->_pages, filter_input ( $input_type, $this->_pageholder, FILTER_VALIDATE_INT, array (
					'options' => array (
							'default' => 1,
							'min_range' => 1 
					) 
			) ) );
			
			$this->_limit = $this->_pagelimit;
			$this->_offset = ($this->_pager_page - 1) * $this->_pagelimit;
		
			if ($this->_offset < 0)
				$this->_offset = 0;
			
		}
		
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		if (! empty ( $this->_group ))
			$this->_qry .= ' GROUP BY ' . implode ( ',', $this->_group );
		if (! empty ( $this->_order ))
			$this->_qry .= ' ORDER BY ' . implode ( ',', $this->_order );
		
		if ($this->_limit) {
			$cond ['limit'] = $this->_limit;
			$this->_qry .= " limit :limit";
		}
		
		if ($this->_offset) {
			$cond ['offset'] = $this->_offset;
			$this->_qry .= " offset :offset";
		}
		
		//self::dbug($cond);
		
		
		$stmt= $this->execute($cond);
		self::resetVariables();
		
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	protected function fetchQueryPaginate($cond, $sort='') {
		@$cond= array_filter($cond, function($value) {
			return ($value !== null && $value !== false && $value !== '');
		});
		//@$cond = array_filter ( $cond );
		
		
		if (! empty ( $this->_pager )) {
			
			$this->_db->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
			$stmt = $this->_db->prepare ( $this->_pager );
			
			$stmt->execute ( $cond );
			$count = $stmt->fetch ( PDO::FETCH_COLUMN );
			
			$this->_pages = ceil ( $count / $this->_pagelimit );
			
			if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest')
				$input_type = INPUT_POST;
			else
				$input_type = INPUT_GET;
			
			$this->_pager_page = min ( $this->_pages, filter_input ( $input_type, $this->_pageholder, FILTER_VALIDATE_INT, array (
					'options' => array (
							'default' => 1,
							'min_range' => 1 
					) 
			) ) );
			
			$this->_limit = $this->_pagelimit;
			$this->_offset = ($this->_pager_page - 1) * $this->_pagelimit;
			
			if ($this->_offset < 0)
				$this->_offset = 0;
		}
		
		
		if (! empty ( $sort )) {
		    $this->_qry .= $sort;
		}
		
		
		if ($this->_limit) {
			$cond ['limit'] = $this->_limit;
			$this->_qry .= " limit :limit";
		}
		
		if ($this->_offset) {
			$cond ['offset'] = $this->_offset;
			$this->_qry .= " offset :offset";
		}
		
		//die($this->_qry);
		
		
		$stmt = $this->execute ( $cond );
		self::resetVariables();
		
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	
	protected function fetchResult($cond) {
		@$cond= array_filter($cond, function($value) {
			return ($value !== null && $value !== false && $value !== '');
		});
		//@$cond = array_filter($cond);
		$cond ['deleted'] = 0;
		
		$this->_where [] = $this->_table . '.deleted = :deleted';
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		if (! empty ( $this->_group ))
			$this->_qry .= ' GROUP BY ' . implode ( ',', $this->_group );
		if (! empty ( $this->_order ))
			$this->_qry .= ' ORDER BY ' . implode ( ',', $this->_order );
		
		if ($this->_limit) {
			$cond ['limit'] = $this->_limit;
			$this->_qry .= " limit :limit";
		}
		
		if ($this->_offset) {
			$cond ['offset'] = $this->_offset;
			$this->_qry .= " offset :offset";
		}
		
		//self::dbug($cond);
		
		$stmt= $this->execute($cond);
		self::resetVariables();
		
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//return $stmt->fetch ( PDO::FETCH_COLUMN );
	}
	protected function fetchPair($cond) {
		@$cond= array_filter($cond, function($value) {
			return ($value !== null && $value !== false && $value !== '');
		});
		//@$cond = array_filter($cond);
		$cond ['deleted'] = 0;
		
		$this->_where [] = $this->_table . '.deleted = :deleted';
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		if (! empty ( $this->_group ))
			$this->_qry .= ' GROUP BY ' . implode ( ',', $this->_group );
		if (! empty ( $this->_order ))
			$this->_qry .= ' ORDER BY ' . implode ( ',', $this->_order );
		
		if ($this->_limit) {
			$cond ['limit'] = $this->_limit;
			$this->_qry .= " limit :limit";
		}
		
		if ($this->_offset) {
			$cond ['offset'] = $this->_offset;
			$this->_qry .= " offset :offset";
		}

		//self::dbug($cond);
		
		$stmt= $this->execute($cond);
		self::resetVariables();
		
		return $stmt->fetchAll ( PDO::FETCH_KEY_PAIR );
	}
	protected function delete($id) {
		if (empty ( $id )) {
			die ( 'No delete condition provided' );
			return false;
		}
		
		$this->_where = array ();
		$data ['t_deleted'] = date_format ( new DateTime (), 'Y-m-d H:i:s' );
		$data ['u_deleted'] = USER_ID;
		$data ['deleted'] = 1;
		
		//$field = ' (' . implode ( ',', array_keys ( $data ) ) . ') ';
		//$bind = ' (:' . implode ( ',:', array_keys ( $data ) ) . ') ';
		
		$this->_where [] = $this->_table . "." . $this->_pkey . "= :" . $this->_pkey;
		$cond [$this->_pkey] = $id;
		
		
		foreach ($data as $dtk=>$dtv)
			$set .= $dtk.' = :'.$dtk.' ,';
		$set = rtrim($set,' ,');
		$this->_qry = "UPDATE $this->_table SET $set";

		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		$stmt = $this->_db->prepare ( $this->_qry );
		self::resetVariables();
		$param = array_merge($data,$cond);
		$delete =  self::commit ( $stmt, 'update',$param );
		if($delete)
			userActivityLog(3,$id,'',$this->_table);
		return $delete;
	}
	protected function deleteByCond($cond) {
		@$cond= array_filter($cond, function($value) {
			return ($value !== null && $value !== false && $value !== '');
		});
		//@$cond = array_filter($cond);
		if (empty ( $cond )) {
			die ( 'No delete condition provided' );
			return false;
		}
		
		$data ['t_deleted'] = date_format ( new DateTime (), 'Y-m-d H:i:s' );
		$data ['u_deleted'] = USER_ID;
		$data ['deleted'] = 1;
		
		//$field = ' (' . implode ( ',', array_keys ( $data ) ) . ') ';
		//$bind = ' (:' . implode ( ',:', array_keys ( $data ) ) . ') ';
		
		foreach ($data as $dtk=>$dtv)
			$set .= $dtk.' = :'.$dtk.' ,';
			$set = rtrim($set,' ,');
		$this->_qry = "UPDATE $this->_table SET $set";
			
		$this->_qry .= ' WHERE ' . implode ( ' AND ', $this->_where );
		
		$stmt = $this->_db->prepare ( $this->_qry );
		self::resetVariables();
		
		$param = array_merge($data,$cond);
		$delete =  self::commit ( $stmt, 'update',$param );
		if($delete)
			userActivityLog(33,'','',$this->_table);
		return $delete;
			
	}
	
	protected function fetchQuery($cond) {
		$stmt= $this->execute($cond);
		//self::dbug($cond);
		self::resetVariables();
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//return @$result ['0'];
	}
	
	protected function fetchQueryPair($cond) {
		//self::dbug($cond);
		$stmt= $this->execute($cond);
		self::resetVariables();
		
		$stmt= $this->execute($cond);
		return $stmt->fetchAll ( PDO::FETCH_KEY_PAIR );
		//return @$result ['0'];
	}

	protected function resetVariables($param = false) {
		$cond = array ();
		$this->_where = array ();
		$this->_group = array ();
		$this->_order = array ();
		
		$this->_vlimit = $this->_limit;
		$this->_voffset = $this->_offset;
		
		//$this->_limit = '';
		//$this->_offset = '';
		//$this->_pager_page = '';
	}
	private function formWhere($stmt, $cond = '') {
		$stmt .= " WHERE ";
		if ($cond) {
			if (is_array ( $cond )) {
				$cond ['deleted'] = 0;
				foreach ( $cond as $k => $v ) {
					$where [] = $this->_table . "." . $k . "=:" . $k;
				}
				$stmt .= implode ( ' AND ', $where );
				
				return array (
						$stmt,
						$cond 
				);
			}
			$cond ['deleted'] = 0;
			return array (
					$stmt . $cond . " AND " . $this->_table . ".deleted = :deleted",
					$cond 
			);
		}
		$cond ['deleted'] = 0;
		return array (
				$stmt . $this->_table . ".deleted = :deleted",
				$cond 
		);
	}
	protected function dbug($data) {
	    
	    s ($data);
	    
		foreach ( $data as $k => $v ) {
			$data [$k] = $v;
			$bind [] = ":" . $k;
		}
		/*$bind[] = ':deleted';
		$data['deleted'] = '0';*/
		

		
		s($this->_where);
		
		if(!empty($data))
		$stmt = str_replace ( $bind, $data, $this->_qry );
		else
		    $stmt = $this->_qry;
		    
		r ( $stmt );
		
		//$this->_where [] = $this->_table . '.deleted = :deleted';
		$where  = ' WHERE ' . implode ( ' AND ', $this->_where );
		if(!empty($data))
		$stmt = str_replace ( $bind, $data, $where);
		else
		    $stmt = $this->_qry;
		v ( $stmt );
	}
	public function pagination($target = "") {
		try {
	
			$pages = $this->_pages;
			$page = $this->_pager_page;
			
			$countLimit = 3;
			$vcount = $countLimit;
			$paginate = 1;
			
			$tmpcount = 1;
			
			$paginator [$page] = $page;
			if ($pages > 0)
				for($i = 1; $i <= $vcount; $i ++) {
					
					$tmpcount ++;
					
					if ($page - $i > 0) {
						$paginator [$page - $i] = $page - $i;
						$paginate ++;
					} else
						$vcount ++;
					
					if ($paginate >= $countLimit)
						break;
					
					if ($page + $i <= $pages) {
						$paginator [$page + $i] = $page + $i;
						$paginate ++;
					} else
						$vcount ++;
					
					if ($paginate >= $countLimit)
						break;
					
					if ($tmpcount > $countLimit)
						break;
				}
			sort ( $paginator );
			$middleLinks = '';
			
			if($target==''){
				$ptype= "submit";
				$pclass= "";
			}else{
				$ptype= 'button" ptarget="'.$target;
				$pclass= "livepost";
			}
			
			$prevlink = ($page > 1) ? '<li>
					<button class="pagen-acvive-btn pagen-first '.$pclass.'" type="'.$ptype.'" value="1" name="'.$this->_pageholder.'">&laquo;&nbsp;First</button>
					<button class="pagen-acvive-btn '.$pclass.'" type="'.$ptype.'" value="' . ($page - 1) . '" name="'.$this->_pageholder.'">&lsaquo; Pre</button></li>
					' : '<li class=""><a href="javascript:void(0);" style="color: gray;"><button class="pagen-inacvive-btn pagen-first">&laquo;&nbsp;First<div></a></li> 
						 <li class=""><a href="javascript:void(0);" style="color: gray;"><button class="pagen-inacvive-btn">&lsaquo; Pre<div></a></li>';
			$nextlink = ($page < $pages) ? '<li>
					<button class="pagen-acvive-btn '.$pclass.'" type="'.$ptype.'" value="' . ($page + 1) . '" name="'.$this->_pageholder.'">Next &rsaquo;</button></li><li>
					<button class="pagen-acvive-btn pagen-last '.$pclass.'" type="'.$ptype.'" value="' . $pages . '" name="'.$this->_pageholder.'">Last &raquo;</button></li>
					' : '<li class=""><a href="javascript:void(0);" style="color: gray;"><button class="pagen-inacvive-btn">Next &rsaquo;<div></a></li> 
						 <li class=""><a href="javascript:void(0);" style="color: gray;"><button class="pagen-inacvive-btn pagen-last">Last &raquo;<div></a></li>';
			if (count ( $paginator ) > 0)
				foreach ( $paginator as $key => $value )
					if ($value != $page)
						$middleLinks .= '<li><button class="pagen-acvive-btn '.$pclass.'" type="'.$ptype.'" value="' . $value . '" name="'.$this->_pageholder.'">' . $value . '</button> </li>';
					else
						$middleLinks .= '<li class="disabled" ><button class="pagen-inacvive-btn pagen-current '.$pclass.'" type="'.$ptype.'">' . $value . '</button></li>';
			
				 echo '<input id="page_'.$target.'" type="hidden" name =  "'.$this->_pageholder.'" value = "' . $this->_pager_page. '" >
				 <div class="pagination-wrapper">
				 <ul class="pagination m-b-5" style="margin-top: 10px;">', $prevlink, '    ' . $middleLinks . '   ', $nextlink, '			</ul>
				 </div>




';
			
			
			
			
			
		} catch ( Exception $e ) {
			exception_handler($e);
		}
	}
	private function formWhereClause($where) {
		if (is_array ( $where )) {
			$whereString = '';
			
			foreach ( $where as $columns => $values ) {
				$whereString .= ' AND ' . $db->quoteInto ( " $columns = ? ", $values );
			}
			$where = substr ( $whereString, 5 );
		}
		
		return $where;
	}
	private function commit($statement=null, $type = '', $data=null) {
		$this->_db->beginTransaction ();
		try {

			$result = $statement->execute ( $data );
			if (! $type == 'update')
				$result = $this->_db->lastInsertId ( $this->_table . '_' . $this->_pkey . '_seq' );
			$this->_db->commit ();
			return $result;
		} catch ( PDOException $e ) {
			$this->_db->rollBack ();
			$this->_db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
			exception_handler($e,$statement->queryString,$data);
		}
	}
	
	private function execute($cond){
		try {
			$this->_db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$stmt = $this->_db->prepare ( $this->_qry );
			$stmt->execute ( $cond );
			self::resetVariables ();
			return $stmt;
		} catch ( PDOException $e ) {
			//print_r($this->_where);
			$this->_db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			exception_handler ( $e, $statement->queryString, $cond);
		}
	}
	
}
?>
