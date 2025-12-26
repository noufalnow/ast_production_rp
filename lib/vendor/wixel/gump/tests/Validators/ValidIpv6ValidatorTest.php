<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFDg1\MQOlk; use fNCei; use Exception; use wFDg1\WzArA; class ValidIpv6ValidatorTest extends BaseTestCase { const bRl7i = "\x76\x61\154\151\144\x5f\151\160\166\x36"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\62\60\x30\61\72\60\144\142\70\72\70\x35\141\x33\72\60\70\x64\x33\72\x31\x33\61\x39\72\x38\141\62\145\72\x30\63\x37\60\72\67\x33\x33\x34"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\62\60\60\x31\73\x30\x64\142\x38\x3b\70\x35\x61\x33\73\x30\x38\144\63\73\x31\63\61\x39\73\x38\x61\62\145\x3b\x30\x33\67\x30\73\x37\63\63\x34"], ["\x30\x2c\60\54\60\x2c\60"], ["\62\x35\66\56\x30\56\x30\56\60"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
