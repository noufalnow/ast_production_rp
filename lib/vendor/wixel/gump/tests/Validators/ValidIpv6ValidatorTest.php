<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2xw\kd2M6; use zm6FH; use Exception; use Us2XW\HMyhV; class ValidIpv6ValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\x6c\151\x64\x5f\151\160\166\66"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\x32\60\x30\61\72\x30\x64\x62\x38\72\70\x35\141\63\x3a\x30\x38\144\x33\72\x31\63\x31\x39\72\x38\x61\x32\x65\72\60\x33\67\x30\72\x37\x33\63\x34"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\x32\x30\x30\x31\73\60\144\142\x38\73\x38\65\x61\63\73\x30\70\x64\x33\x3b\61\x33\61\71\x3b\70\141\x32\x65\x3b\x30\63\67\x30\x3b\x37\x33\x33\64"], ["\60\x2c\60\54\x30\x2c\x30"], ["\62\65\66\56\60\56\60\56\x30"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
