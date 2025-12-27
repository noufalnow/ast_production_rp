<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xw; use ZM6fh; use Exception; class StaticIsValidTest extends BaseTestCase { public function testOnSuccessReturnsTrue() { $AyT6U = GUMP::is_valid(["\164\x65\163\x74" => "\x31\62\63"], ["\164\145\163\164" => "\x6e\165\x6d\x65\162\x69\x63"]); $this->assertTrue($AyT6U); } public function testOnFailureReturnsArrayWithErrors() { $AyT6U = GUMP::is_valid(["\164\145\163\164" => "\141\x73\x64"], ["\164\x65\163\164" => "\x6e\165\155\145\x72\x69\x63"], ["\164\145\163\x74" => ["\x6e\x75\155\x65\x72\x69\143" => "\x7b\146\151\145\154\x64\175\x20\x6d\165\163\164\40\142\145\40\141\x20\156\165\155\x62\145\x72\40\x70\x6c\145\x61\163\145\40\x21\41\41"]]); $this->assertEquals(["\x3c\x73\x70\141\x6e\x20\x63\154\141\163\163\75\x22\x67\x75\155\x70\x2d\x66\151\x65\x6c\x64\42\x3e\124\x65\163\164\x3c\x2f\163\x70\x61\156\x3e\x20\155\165\163\164\x20\142\145\40\141\40\156\165\x6d\142\x65\162\40\x70\154\145\x61\x73\x65\40\41\x21\41"], $AyT6U); } }
