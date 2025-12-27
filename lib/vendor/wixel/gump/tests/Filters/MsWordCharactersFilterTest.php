<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2xw\Urbq3; use ZM6fH; use Exception; use uS2xW\HmyHV; class MsWordCharactersFilterTest extends BaseTestCase { const bw_cL = "\155\x73\x5f\167\x6f\x72\144\x5f\143\150\x61\x72\x61\x63\x74\x65\162\x73"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\xe2\x80\x9c\x4d\x79\40\161\x75\x6f\164\x65\342\200\235", "\x22\x4d\x79\x20\161\165\157\164\145\x22"], ["\xe2\200\230\115\171\40\161\x75\x6f\x74\145\342\200\x99", "\x27\115\x79\40\161\165\x6f\x74\x65\47"], ["\xe2\x80\x93\x20\x61\156\144\x20\x74\150\x65\156", "\x2d\40\x61\x6e\x64\40\x74\x68\x65\x6e"], ["\101\156\x64\40\x61\164\40\x74\150\145\40\x65\156\x64\xe2\x80\xa6", "\x41\156\x64\40\x61\x74\40\x74\150\145\x20\x65\x6e\x64\x2e\x2e\x2e"]]; } }
