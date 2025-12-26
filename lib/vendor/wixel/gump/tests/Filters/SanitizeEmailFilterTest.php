<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Wfdg1\U5Kgh; use FnCEi; use Exception; use wFDG1\wZArA; class SanitizeEmailFilterTest extends BaseTestCase { const zm3Yu = "\163\141\156\x69\164\x69\x7a\145\137\145\x6d\141\x69\154"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x74\145\x73\164\x22\xc2\xba\100\145\x6d\x61\151\x6c\x2e\x63\157\x6d", "\x74\x65\x73\164\100\145\x6d\141\151\x6c\x2e\x63\157\x6d"]]; } }
