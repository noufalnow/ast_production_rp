<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDg1; use FNCeI; use Exception; use EX1ZK as m; class ConstructorTest extends BaseTestCase { public function testItSetsDefaultLanguageProperty() { $KBWiO = new GUMP(); $this->assertEquals("\x65\x6e", self::getPrivateField($KBWiO, "\x6c\x61\x6e\x67")); } public function testItSetsLanguagePropertyWhenSet() { $KBWiO = new GUMP("\145\x73"); $this->assertEquals("\145\x73", self::getPrivateField($KBWiO, "\x6c\141\x6e\147")); } public function testItThrowsExceptionWhenLanguageFileDoesntExist() { goto ngBbq; Fnj_c: $KBWiO = new GUMP("\x65\x73"); goto moqEw; O_3jR: $this->expectExceptionMessage("\47\145\x73\x27\40\x6c\141\156\x67\165\x61\x67\145\40\151\x73\x20\x6e\x6f\164\x20\163\165\160\x70\x6f\x72\164\145\x64\x2e"); goto Fnj_c; er1os: $this->expectException(Exception::class); goto O_3jR; ngBbq: $this->helpersMock->shouldReceive("\146\151\154\x65\137\x65\170\x69\x73\x74\163")->once()->andReturnFalse(); goto er1os; moqEw: } }
