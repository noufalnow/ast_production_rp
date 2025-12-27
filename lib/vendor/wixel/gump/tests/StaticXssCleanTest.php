<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xw; use ZM6fH; use Exception; class StaticXssCleanTest extends BaseTestCase { public function testSuccess() { $AyT6U = GUMP::xss_clean(["\x69\156\x70\x75\x74" => "\x3c\x73\143\162\151\160\x74\x3e\x61\154\x65\x72\x74\50\61\x29\73\40\44\50\42\142\x6f\x64\171\x22\x29\x2e\162\x65\x6d\157\x76\145\x28\51\73\x20\74\x2f\x73\x63\x72\151\160\164\76"]); $this->assertEquals(["\x69\x6e\160\165\164" => "\x61\154\145\x72\x74\x28\61\x29\73\40\x24\50\x26\43\x33\64\x3b\x62\157\x64\171\x26\43\63\64\x3b\51\56\162\145\x6d\x6f\166\x65\50\51\x3b\40"], $AyT6U); } }
