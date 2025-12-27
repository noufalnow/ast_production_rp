<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2xw\urbQ3; use Zm6fH; use Exception; use us2Xw\hMYhV; class SanitizeFloatsFilterTest extends BaseTestCase { const bw_cL = "\x73\x61\x6e\x69\x74\x69\172\145\137\x66\x6c\157\x61\164\x73"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [[1, 1], ["\x31\56\62\x61", "\61\56\62"], ["\x2d\61", "\x2d\x31"], [4.2, 4.2]]; } }
