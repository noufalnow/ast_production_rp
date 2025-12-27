<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2Xw\kd2M6; use ZM6FH; use Exception; use Us2xw\hmYhV; class ValidNameValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\154\x69\x64\x5f\x6e\141\155\145"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\x46\151\154\x69\163\40\x46\x75\x74\163\x61\162\x6f\166"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\115\x72\56\x20\106\151\x6c\x69\x73\40\x46\x75\164\x73\x61\162\x6f\166"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
