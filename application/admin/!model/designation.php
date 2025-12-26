<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 class designation extends db_table { protected $_table = "\x63\x6f\162\145\x5f\x64\145\x73\x69\147\x6e\141\164\151\x6f\156"; protected $_pkey = "\144\145\163\x69\x67\x5f\x69\x64"; public function getDesigPair($EP_zH = array()) { goto Nk80L; Nk80L: $this->query("\163\145\x6c\145\143\164\40\x64\x65\x73\151\x67\x5f\x69\144\54\x64\x65\x73\x69\x67\x5f\x6e\141\155\x65\x20\146\x72\157\x6d\x20{$this->_table}"); goto ELWiN; ELWiN: $this->_order[] = "\144\x65\163\151\x67\137\x6e\x61\x6d\145\x20\x41\x53\103"; goto yFJJg; yFJJg: return parent::fetchPair($EP_zH); goto mbGvf; mbGvf: } }
