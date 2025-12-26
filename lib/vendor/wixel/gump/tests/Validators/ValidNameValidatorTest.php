<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfdg1\mqoLK; use fNceI; use Exception; use WFDG1\wZARa; class ValidNameValidatorTest extends BaseTestCase { const bRl7i = "\x76\x61\154\x69\144\x5f\156\x61\x6d\x65"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\x46\151\x6c\x69\x73\40\106\x75\x74\163\x61\x72\x6f\x76"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\115\162\x2e\40\x46\151\154\151\163\x20\x46\165\164\163\x61\x72\157\166"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
