<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Wfdg1\U5KgH; use fnCEI; use Exception; use wfdg1\WZaRa; class SanitizeNumbersFilterTest extends BaseTestCase { const zm3Yu = "\x73\x61\156\x69\164\x69\x7a\145\x5f\156\165\x6d\x62\145\x72\163"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [[1, 1], ["\x31\x2e\62\141", 12], ["\x2d\61", "\55\x31"], [4.2, 42]]; } }
