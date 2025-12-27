<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xW\urBq3; use zM6Fh; use Exception; use uS2xw\hMYHv; class RmpunctuationFilterTest extends BaseTestCase { const bw_cL = "\x72\155\160\x75\x6e\x63\164\165\141\x74\x69\x6f\x6e"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\150\x65\154\154\157\x3f\41\x3a\73", "\x68\x65\x6c\154\x6f"]]; } }
