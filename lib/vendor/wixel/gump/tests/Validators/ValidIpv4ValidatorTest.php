<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfdg1\mQolK; use FNcei; use Exception; use wFdG1\WzarA; class ValidIpv4ValidatorTest extends BaseTestCase { const bRl7i = "\166\141\x6c\x69\x64\137\151\160\x76\x34"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\x31\x32\67\x2e\60\56\x30\56\x31"], ["\x31\56\x31\56\x31\x2e\x31"], ["\62\65\x35\x2e\62\65\65\x2e\x32\65\x35\x2e\x32\65\x35"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\62\x30\x30\61\x3a\60\144\142\70\72\x38\x35\141\x33\72\60\70\144\x33\72\61\x33\x31\x39\72\x38\141\x32\145\72\x30\x33\x37\60\x3a\67\63\63\64"], ["\60\54\x30\x2c\60\54\60"], ["\62\65\66\x2e\x30\56\x30\56\x30"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
