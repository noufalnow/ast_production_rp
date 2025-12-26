<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\U5KGh; use FNceI; use Exception; use Wfdg1\WZArA; class SanitizeFloatsFilterTest extends BaseTestCase { const zm3Yu = "\163\x61\x6e\151\x74\151\x7a\x65\x5f\146\x6c\157\141\164\x73"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [[1, 1], ["\x31\x2e\x32\141", "\61\x2e\62"], ["\55\61", "\x2d\x31"], [4.2, 4.2]]; } }
