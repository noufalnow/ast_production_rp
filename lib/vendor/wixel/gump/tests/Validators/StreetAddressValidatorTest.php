<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDg1\MQolK; use fncEI; use Exception; use wFDg1\WZaRA; class StreetAddressValidatorTest extends BaseTestCase { const bRl7i = "\163\164\x72\x65\x65\164\x5f\x61\x64\144\162\x65\x73\x73"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\x36\x20\x41\166\x6f\156\144\141\156\x73\40\122\157\141\144"], ["\x43\141\154\x6c\x65\x20\115\145\144\151\x74\x65\162\x72\303\241\x6e\x65\x6f\40\x32"], ["\x63\57\115\x65\144\151\x74\x65\x72\162\xc3\241\156\145\x6f\x20\x32"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\x41\166\x6f\156\144\141\x6e\x73\x20\x52\x6f\x61\144"], ["\x74\145\x78\164"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
