<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\u5Kgh; use fncEi; use Exception; use wfdG1\WzaRa; class BooleanFilterTest extends BaseTestCase { const zm3Yu = "\x62\x6f\x6f\x6c\145\x61\156"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x31", true], [1, true], [true, true], ["\x79\x65\x73", true], ["\157\156", true], ['', false], ["\x6e\x6f", false], [null, false], [false, false]]; } }
