<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2Xw\kd2M6; use ZM6FH; use Exception; use uS2XW\hmYhv; class ValidIpv4ValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\154\x69\x64\x5f\151\160\166\64"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\x31\62\x37\56\x30\x2e\x30\56\61"], ["\61\x2e\61\x2e\61\x2e\61"], ["\62\x35\x35\56\x32\x35\x35\x2e\x32\65\x35\x2e\62\x35\x35"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\62\60\60\x31\72\x30\x64\142\70\x3a\70\x35\x61\x33\x3a\60\x38\x64\x33\x3a\x31\x33\x31\71\72\x38\x61\62\x65\72\60\x33\x37\60\72\x37\63\63\x34"], ["\x30\54\60\54\60\x2c\x30"], ["\62\x35\x36\56\x30\56\60\56\x30"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
