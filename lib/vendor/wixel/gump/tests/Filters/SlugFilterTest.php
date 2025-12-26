<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1\u5kGh; use FNcei; use Exception; use WfDg1\WzaRa; class SlugFilterTest extends BaseTestCase { const zm3Yu = "\163\x6c\165\147"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x74\x65\163\x74\40\x73\160\141\x63\145\56\x21\100\x60\x7e\73\x3a\57\x5c\74\x3e", "\164\x65\x73\164\x2d\163\x70\x61\x63\x65"], ["\x74\145\x73\x74", "\x74\x65\163\164"], ["\124\x65\x73\164\x20\x63\141\163\145", "\x74\145\x73\x74\x2d\x63\141\x73\145"]]; } }
