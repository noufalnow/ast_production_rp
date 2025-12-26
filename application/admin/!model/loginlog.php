<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 class loginlog extends db_table { protected $_table = "\143\157\162\x65\x5f\x6c\x6f\147\151\156\137\154\157\x67"; protected $_pkey = "\154\157\147\137\x69\144"; public function add($DMTMf) { $this->_nolog = true; return parent::insert($DMTMf); } }
