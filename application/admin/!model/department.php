<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 class department extends db_table { protected $_table = "\143\157\162\x65\x5f\x64\145\160\141\162\x74\155\145\x6e\164"; protected $_pkey = "\144\x65\160\x74\137\x69\144"; public function getDeptPair($EP_zH = array()) { goto RjlqA; ZJLPs: return parent::fetchPair($EP_zH); goto lU0Cm; RjlqA: $this->query("\x73\145\x6c\145\143\164\x20\144\x65\x70\164\137\151\x64\54\144\x65\160\164\x5f\x6e\141\155\145\40\x66\x72\x6f\x6d\40{$this->_table}"); goto ZXFJe; ZXFJe: $this->_order[] = "\x64\145\x70\x74\x5f\x6e\141\155\x65\x20\101\x53\103"; goto ZJLPs; lU0Cm: } }
