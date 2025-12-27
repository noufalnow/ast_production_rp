<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW; use zM6FH; use Exception; class StaticSetFieldNameTest extends BaseTestCase { public function testSetFieldNamesStaticCall() { goto QaAQj; QaAQj: $L_BZ7 = ["\x74\145\x73\164\137\x6e\x75\x6d\x62\x65\162" => "\x54\x65\x73\x74\x20\x4e\x75\x6d\x2e"]; goto JZm2L; H9HJa: $this->gump->validate(["\164\145\x73\164\x5f\156\165\x6d\142\x65\162" => "\x31\61\61"], ["\164\x65\x73\x74\137\156\x75\155\142\145\x72" => "\x61\154\x70\x68\x61"]); goto YqPYS; wGAsJ: $this->assertEquals(["\164\145\x73\164\x5f\x6e\165\x6d\142\145\x72" => "\124\150\145\40\124\x65\x73\164\x20\x4e\165\155\x2e\x20\x66\151\x65\x6c\x64\x20\155\x61\171\40\x6f\x6e\154\x79\x20\x63\157\156\164\x61\151\x6e\40\x6c\x65\x74\164\145\x72\x73"], $this->gump->get_errors_array()); goto bdbyK; JZm2L: GUMP::set_field_names($L_BZ7); goto H9HJa; YqPYS: $this->assertEquals($L_BZ7, self::getPrivateField(GUMP::class, "\146\x69\x65\x6c\144\x73")); goto wGAsJ; bdbyK: } }
