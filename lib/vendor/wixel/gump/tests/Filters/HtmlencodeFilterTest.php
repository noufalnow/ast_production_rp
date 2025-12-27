<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2xW\UrBq3; use zM6Fh; use Exception; use US2xw\hmyHV; class HtmlencodeFilterTest extends BaseTestCase { const bw_cL = "\150\x74\155\x6c\145\156\x63\157\144\x65"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\111\163\40\120\145\164\x65\x72\x20\x3c\x73\155\x61\x72\164\76\40\x26\40\146\x75\156\156\171", "\x49\163\x20\120\145\x74\x65\162\x20\x26\x23\66\x30\73\163\x6d\x61\x72\x74\46\43\66\62\x3b\x20\x26\x23\x33\70\73\40\146\x75\x6e\x6e\171"]]; } }
