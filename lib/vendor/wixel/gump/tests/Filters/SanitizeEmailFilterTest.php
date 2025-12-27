<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW\uRBq3; use zM6FH; use Exception; use us2xW\HMYhV; class SanitizeEmailFilterTest extends BaseTestCase { const bw_cL = "\x73\x61\x6e\x69\x74\x69\x7a\145\x5f\145\155\141\151\154"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\164\145\x73\164\x22\302\272\x40\x65\x6d\x61\151\x6c\56\x63\157\155", "\164\x65\163\x74\100\145\155\141\151\x6c\x2e\x63\157\155"]]; } }
