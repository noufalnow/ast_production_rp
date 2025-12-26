<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdG1\MqoLK; use FNCeI; use Exception; use WFDg1\WZaRA; class AlphaNumericValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\154\160\150\x61\137\x6e\165\155\145\162\151\143", "\165\x73\145\162\x6e\x61\155\x65\61\x32\x33")); } public function testError() { $this->assertNotTrue($this->validate("\141\154\x70\150\x61\x5f\156\x75\x6d\145\x72\151\x63", "\x68\145\x6c\x6c\x6f\40\x2a\x28\136\x2a\136\x2a\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\154\x70\x68\141\x5f\156\x75\155\x65\x72\x69\x63", '')); } }
