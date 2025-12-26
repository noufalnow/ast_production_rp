<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdG1\MqOlk; use FNcEI; use Exception; use wfdG1\WzarA; use Ex1Zk as m; class ValidCcValidatorTest extends BaseTestCase { const bRl7i = "\x76\141\x6c\x69\x64\137\143\143"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\65\x31\60\65\x31\x30\x35\61\x30\65\x31\60\x35\61\60\60"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\x35\x31\60\x35\61\x30\65\x31\x30\x35\x31\x30\x35\x31\x30\61"], ["\61\62\61\62\61\62\x31\62\61\62\61\62\x31\62\61\62"], ["\164\145\170\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
