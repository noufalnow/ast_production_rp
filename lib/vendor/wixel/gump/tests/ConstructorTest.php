<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW; use zM6fH; use Exception; use ZYviO as m; class ConstructorTest extends BaseTestCase { public function testItSetsDefaultLanguageProperty() { $Irkve = new GUMP(); $this->assertEquals("\x65\156", self::getPrivateField($Irkve, "\x6c\141\x6e\147")); } public function testItSetsLanguagePropertyWhenSet() { $Irkve = new GUMP("\145\x73"); $this->assertEquals("\145\x73", self::getPrivateField($Irkve, "\154\141\x6e\x67")); } public function testItThrowsExceptionWhenLanguageFileDoesntExist() { goto y2mhs; FErMj: $Irkve = new GUMP("\145\163"); goto UtMBM; oTy4W: $this->expectException(Exception::class); goto HTPdE; HTPdE: $this->expectExceptionMessage("\x27\145\163\x27\40\x6c\x61\x6e\x67\165\141\x67\145\x20\151\163\40\156\x6f\x74\40\x73\x75\x70\160\x6f\x72\x74\145\144\x2e"); goto FErMj; y2mhs: $this->helpersMock->shouldReceive("\x66\x69\154\x65\x5f\145\170\x69\163\164\x73")->once()->andReturnFalse(); goto oTy4W; UtMBM: } }
