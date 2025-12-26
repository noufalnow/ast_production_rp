<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFdg1; use fnceI; use Exception; class StaticXssCleanTest extends BaseTestCase { public function testSuccess() { $mncio = GUMP::xss_clean(["\151\x6e\x70\x75\x74" => "\x3c\163\143\162\x69\x70\x74\76\141\x6c\x65\162\x74\50\61\x29\73\x20\x24\50\x22\142\x6f\x64\x79\x22\x29\56\162\x65\155\x6f\x76\x65\50\x29\73\40\74\x2f\x73\x63\x72\x69\160\x74\x3e"]); $this->assertEquals(["\151\156\x70\x75\x74" => "\141\x6c\x65\x72\x74\x28\61\51\x3b\40\44\x28\x26\43\63\64\73\x62\157\x64\171\46\43\63\64\73\x29\x2e\162\145\155\157\x76\145\x28\51\x3b\x20"], $mncio); } }
