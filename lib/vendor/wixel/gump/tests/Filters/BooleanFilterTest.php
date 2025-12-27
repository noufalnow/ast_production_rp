<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xw\uRBq3; use zM6fH; use Exception; use US2Xw\hMyhV; class BooleanFilterTest extends BaseTestCase { const bw_cL = "\142\157\x6f\x6c\x65\x61\156"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\61", true], [1, true], [true, true], ["\171\x65\x73", true], ["\157\156", true], ['', false], ["\x6e\157", false], [null, false], [false, false]]; } }
