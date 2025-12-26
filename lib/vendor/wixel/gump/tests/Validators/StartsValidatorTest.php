<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1\mQOLk; use FNCeI; use Exception; use wFdG1\wzaRa; class StartsValidatorTest extends BaseTestCase { const bRl7i = "\163\164\141\x72\x74\163\x2c\x74\x65\163"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\164\145\x73\x74"], ["\164\x65\x73\164\151\x6e\147"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\x74\164\145\163"], ["\x74\163\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
