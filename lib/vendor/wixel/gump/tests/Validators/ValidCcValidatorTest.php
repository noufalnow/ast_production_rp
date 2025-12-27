<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2Xw\KD2M6; use zm6FH; use Exception; use Us2xw\HmyhV; use zYvIO as m; class ValidCcValidatorTest extends BaseTestCase { const Kx2r1 = "\166\x61\x6c\x69\x64\137\x63\x63"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\65\x31\x30\65\x31\x30\x35\x31\x30\x35\61\x30\x35\x31\x30\x30"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\x35\x31\60\x35\61\60\65\61\60\65\x31\60\x35\x31\x30\61"], ["\61\62\x31\x32\x31\62\x31\62\x31\x32\x31\x32\x31\x32\61\62"], ["\x74\145\x78\164"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
