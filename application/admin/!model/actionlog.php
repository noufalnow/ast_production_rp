<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 class actionlog extends db_table { protected $_table = "\x63\x6f\162\x65\137\141\x63\164\x69\157\x6e\137\x6c\157\x67"; protected $_pkey = "\141\x6c\157\147\x5f\x69\144"; public function add($DMTMf) { $this->_nolog = true; return parent::insert($DMTMf); } }
