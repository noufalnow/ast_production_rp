<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFdg1; use Fncei; use Exception; class StaticIsValidTest extends BaseTestCase { public function testOnSuccessReturnsTrue() { $mncio = GUMP::is_valid(["\164\145\x73\x74" => "\61\62\x33"], ["\x74\145\163\164" => "\156\x75\x6d\145\162\151\143"]); $this->assertTrue($mncio); } public function testOnFailureReturnsArrayWithErrors() { $mncio = GUMP::is_valid(["\x74\x65\163\164" => "\141\163\144"], ["\x74\145\163\x74" => "\x6e\165\155\145\x72\151\x63"], ["\x74\x65\163\164" => ["\156\165\155\145\x72\151\x63" => "\x7b\146\151\145\x6c\144\x7d\x20\x6d\165\x73\x74\x20\142\145\40\141\40\x6e\165\x6d\142\145\x72\40\x70\154\x65\141\163\x65\40\41\x21\41"]]); $this->assertEquals(["\74\163\x70\x61\x6e\40\143\x6c\x61\163\x73\75\x22\x67\x75\x6d\160\55\x66\151\x65\x6c\144\42\x3e\124\x65\163\x74\x3c\57\163\160\x61\x6e\x3e\40\x6d\165\x73\164\x20\142\145\x20\141\40\x6e\x75\155\142\x65\x72\x20\x70\154\x65\141\x73\145\40\x21\x21\41"], $mncio); } }
