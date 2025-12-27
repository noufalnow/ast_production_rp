<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW\uRbq3; use zM6Fh; use Exception; use uS2xw\hMyHv; class SanitizeNumbersFilterTest extends BaseTestCase { const bw_cL = "\x73\141\x6e\151\164\151\172\145\137\x6e\165\x6d\x62\145\162\x73"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [[1, 1], ["\61\x2e\x32\141", 12], ["\x2d\61", "\x2d\61"], [4.2, 42]]; } }
