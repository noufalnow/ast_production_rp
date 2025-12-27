<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2Xw\kD2M6; use zm6fh; use Exception; use Us2xW\HMyhv; class StartsValidatorTest extends BaseTestCase { const Kx2r1 = "\163\x74\x61\x72\164\x73\x2c\164\x65\163"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\164\x65\x73\164"], ["\164\145\163\x74\x69\156\147"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\x74\x74\x65\163"], ["\164\163\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
