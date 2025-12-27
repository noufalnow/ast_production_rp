<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW\Kd2m6; use zm6fh; use Exception; use us2xW\hmyhv; class ValidJsonStringValidatorTest extends BaseTestCase { const Kx2r1 = "\166\x61\x6c\151\144\137\152\x73\157\x6e\137\x73\164\162\151\156\x67"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\173\175"], ["\x7b\x22\164\145\x73\x74\x69\x6e\147\x22\x3a\40\164\x72\165\145\x7d"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\173\175\x7d"], ["\173\164\x65\163\164\x3a\x74\162\165\x65\175"], ["\173\x22\x74\145\x73\164\x22\x3a\x74\145\170\164\x7d"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
