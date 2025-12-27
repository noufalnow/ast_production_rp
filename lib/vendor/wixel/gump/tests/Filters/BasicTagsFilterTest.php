<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW\uRBq3; use zm6fh; use Exception; use uS2XW\hMyHv; class BasicTagsFilterTest extends BaseTestCase { const bw_cL = "\142\x61\x73\x69\143\137\164\x61\147\x73"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\x3c\x73\143\162\x69\160\164\x3e\141\154\x65\162\x74\50\x31\x29\73\x3c\57\x73\143\x72\x69\x70\164\x3e\150\x65\154\154\157", "\x61\154\x65\162\164\x28\x31\51\x3b\x68\x65\154\x6c\x6f"]]; } }
