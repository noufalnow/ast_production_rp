<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDg1\MQOlK; use fncEI; use Exception; use wFDG1\WZarA; class AlphaNumericDashValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\154\x70\x68\141\x5f\156\x75\x6d\145\x72\x69\x63\x5f\x64\x61\x73\150", "\x4d\171\137\x75\163\145\x72\156\141\x6d\145\x2d\x77\x69\164\x68\x5f\144\x61\163\x68\61\x32\x33")); } public function testError() { $this->assertNotTrue($this->validate("\x61\x6c\160\150\x61\x5f\156\165\155\145\x72\x69\143\x5f\144\x61\x73\150", "\150\145\x6c\154\x6f\40\52\x28\136\52\x5e\52\46")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x61\154\x70\150\x61\x5f\x6e\165\x6d\x65\162\x69\x63\x5f\144\141\x73\x68", '')); } }
