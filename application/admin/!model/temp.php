<?php
class temp extends db_table {
	protected $_table = "temp_files";
	protected $_pkey = "temp_id";
	
	public function add($data) {
		return parent::insert ( $data );
	}
	public function modify($data, $cond) {
		return parent::update ( $data, $cond );
	}
	public function getFileById($id) {
		return parent::getById ($id);
	}
	public function deleteFile($id) {
		return parent::delete ( $id );
	}
	
	
	public function getAll5($cond = array()) {

		ini_set('max_execution_time', 0);	
	    
	    $this->query ( "SELECT * from temp_files where (type <> 5 and type is NOT NULL);");
	    
	    $all5 =  parent::fetchQuery ( $cond );
	    
	    foreach($all5 as $fives){
	        
	        $src = "..".DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$fives['temp_old'];
	        $dest = "..".DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$fives['temp_new'];
	        
	        //echo $src ."<br>";
	        
	        if (file_exists($src)) {
	            if (! rename($src, $dest)) {
	                echo $src . "<br>";
	            }
	            
	            echo ++ $i . "<br>";
	            
	        }
	        else{
	            echo "Not found ".$fives['id'] ."<br>";
	        }
	        
	    }
	    die();
	    
	    
	}
	
	public function getAllNon5($cond = array()) {
	    
	    $this->query ( "SELECT * from temp_files  where (type <> 5 and type is NOT NULL);");
	    
	    return parent::fetchQuery ( $cond );
	}

}

/*function uploadFiles($type,$refId,$upload){
	$explode = explode('.', $upload['name']);
	$fileObj = new files();
	$insert = $fileObj->add(array(
			'file_type'=>$type,
			'file_ref_id'=>$refId,
			'file_actual_name'=>$explode['0'],
			'file_exten'=>$explode['1'],
			'file_size'=>$upload['size']));
	$path = $type == DOC_TYPE_EXP ? 'uploads/expense':'uploads';
	$crypt=new encryption();
	$fileName = $crypt->semiencode($insert);
	return move_uploaded_file($upload['tmp_name'], realpath(dirname(__FILE__) . '/../../' . $path).'/'.$fileName);	
}

function deleteFile($file_id){
	$fileObj = new files();
	$fileDet = $fileObj->getFileById($file_id);
	
	$crypt=new encryption();
	$fileName = $crypt->semiencode($file_id);

	$path = $fileDet['file_type']== DOC_TYPE_EXP ? 'uploads/expense':'uploads';	
	$fileSource = realpath(dirname(__FILE__) . '/../../'. $path ).'/'.$fileName;
	if (file_exists($fileSource)) {
		rename($fileSource, $fileSource."_deleted");	
	}
	sleep(2);
	return true;
}*/






