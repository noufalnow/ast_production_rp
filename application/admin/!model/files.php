<?php
class files extends db_table {
    protected $_table = "core_files";
    protected $_pkey = "file_id";
    
    
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
    
}

function uploadFiles($type,$refId,$upload=[]){
    $explode = explode('.', $upload['name']);
    $fileObj = new files();
    $insert = $fileObj->add(array(
        'file_type'=>$type,
        'file_ref_id'=>$refId,
        'file_actual_name'=>$explode['0'],
        'file_exten'=>$explode['1'],
        'file_size'=>$upload['size']));
    $path = $type == DOC_TYPE_EXP ? 'uploads'.DIRECTORY_SEPARATOR.'expense':'uploads';
    
    $viewbase = new viewbase;
    $fileName = $viewbase->semiencode($insert);
    
    //return move_uploaded_file($upload['tmp_name'], realpath(dirname(__FILE__) . '/../../' . $path).'/'.$fileName);
    return move_uploaded_file($upload['tmp_name'], "..".DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$fileName);
    
}

function deleteFile($file_id){
    $fileObj = new files();
    $fileDet = $fileObj->getFileById($file_id);
    
    $viewbase = new viewbase;
    $fileName = $viewbase->semiencode($file_id);
    
    $path = $fileDet['file_type']== DOC_TYPE_EXP ? 'uploads/expense':'uploads';
    $fileSource = realpath(dirname(__FILE__) . '/../../'. $path ).'/'.$fileName;
    if (file_exists($fileSource)) {
        rename($fileSource, $fileSource."_deleted");
    }
    //sleep(2);
    return true;
}





