<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2XW\uRBq3; use zM6Fh; use Exception; use US2Xw\HMYhV; class SlugFilterTest extends BaseTestCase { const bw_cL = "\x73\154\165\147"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\164\x65\163\x74\x20\163\x70\x61\143\x65\56\41\100\140\176\73\x3a\x2f\x5c\x3c\x3e", "\x74\x65\163\164\x2d\x73\x70\x61\143\x65"], ["\164\x65\163\164", "\x74\x65\x73\164"], ["\x54\x65\163\164\x20\143\x61\163\145", "\164\x65\163\x74\x2d\x63\x61\x73\145"]]; } }
