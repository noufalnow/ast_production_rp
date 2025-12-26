<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdG1\mQoLK; use fNCeI; use Exception; use wFdg1\wzara; class IbanValidatorTest extends BaseTestCase { const bRl7i = "\x69\142\141\156"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\106\122\x37\66\x33\60\x30\x30\66\x30\60\60\x30\x31\x31\62\63\64\65\x36\67\70\71\60\61\x38\x39"], ["\105\x53\67\71\x32\61\60\x30\60\x38\x31\x33\66\x31\x30\61\62\x33\64\65\x36\x37\70\x39"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\x46\122\67\66\63\x30\60\x30\66\60\60\60\x30\x31\x31\62\x33\x34\x35\x36\67\x38\71\60\61\70\x31"], ["\105\67\x39\x32\61\x30\x30\x30\70\x31\x33\x36\61\60\x31\62\x33\x34\x35\66\x37\70\71"], ["\x74\145\x78\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
