<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xW\Kd2m6; use ZM6fh; use Exception; use US2Xw\hMYhV; class AlphaNumericDashValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x61\154\160\x68\141\137\156\165\155\145\x72\x69\x63\x5f\144\141\x73\x68", "\115\x79\x5f\165\x73\x65\x72\156\141\x6d\x65\55\x77\x69\x74\150\x5f\x64\x61\x73\150\x31\x32\63")); } public function testError() { $this->assertNotTrue($this->validate("\141\154\x70\x68\141\x5f\156\x75\x6d\145\162\151\143\137\144\141\x73\x68", "\x68\145\154\x6c\157\x20\x2a\x28\136\x2a\136\52\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\154\160\150\x61\137\x6e\x75\x6d\x65\x72\x69\x63\137\x64\141\163\x68", '')); } }
