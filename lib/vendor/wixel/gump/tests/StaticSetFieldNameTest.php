<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfdG1; use fNCei; use Exception; class StaticSetFieldNameTest extends BaseTestCase { public function testSetFieldNamesStaticCall() { goto FyU6G; zA242: $this->gump->validate(["\164\x65\163\164\137\x6e\165\155\x62\145\162" => "\x31\x31\x31"], ["\x74\x65\x73\x74\137\x6e\x75\155\x62\145\162" => "\141\154\x70\x68\x61"]); goto nQO7v; mYQd3: $this->assertEquals(["\x74\145\163\164\x5f\x6e\x75\155\x62\x65\x72" => "\x54\150\x65\x20\124\145\163\x74\x20\116\165\x6d\56\x20\x66\151\x65\x6c\x64\x20\155\x61\x79\40\157\156\154\x79\40\x63\157\156\x74\x61\151\156\40\154\x65\x74\164\x65\x72\163"], $this->gump->get_errors_array()); goto XG8f_; vyiZ2: GUMP::set_field_names($Rc6hS); goto zA242; nQO7v: $this->assertEquals($Rc6hS, self::getPrivateField(GUMP::class, "\146\151\x65\154\x64\x73")); goto mYQd3; FyU6G: $Rc6hS = ["\x74\x65\x73\x74\137\x6e\x75\155\x62\x65\x72" => "\x54\x65\163\164\x20\x4e\165\155\56"]; goto vyiZ2; XG8f_: } }
