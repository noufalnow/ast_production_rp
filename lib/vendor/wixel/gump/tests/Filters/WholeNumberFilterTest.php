<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xW\URBq3; use zM6fh; use Exception; use uS2XW\hMyHV; class WholeNumberFilterTest extends BaseTestCase { const bw_cL = "\x77\x68\157\x6c\145\137\x6e\x75\x6d\142\x65\162"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [[1, 1], ["\55\x31", "\55\61"], [4.2, 4], ["\x30\x34\62", "\64\x32"]]; } }
