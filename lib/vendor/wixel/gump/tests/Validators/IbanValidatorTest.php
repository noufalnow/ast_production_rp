<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xW\kD2m6; use ZM6fh; use Exception; use US2xW\hMYhv; class IbanValidatorTest extends BaseTestCase { const Kx2r1 = "\151\142\141\x6e"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\106\x52\67\x36\63\x30\x30\60\66\x30\x30\60\60\61\61\62\x33\x34\x35\66\x37\x38\71\x30\61\70\x39"], ["\x45\123\67\71\x32\61\60\x30\x30\70\x31\63\66\x31\x30\61\62\x33\x34\65\x36\x37\70\x39"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\106\x52\67\x36\63\60\x30\60\x36\60\60\60\x30\61\x31\62\63\x34\x35\x36\67\70\71\x30\61\70\x31"], ["\105\x37\71\x32\x31\60\x30\x30\x38\61\63\x36\61\x30\61\x32\x33\64\x35\66\67\x38\71"], ["\164\x65\170\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
