<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xw\kD2M6; use Zm6fh; use Exception; use Us2XW\HMYhv; class AlphaNumericValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\154\x70\150\141\137\156\x75\x6d\145\162\151\143", "\165\x73\x65\x72\x6e\x61\x6d\145\61\62\63")); } public function testError() { $this->assertNotTrue($this->validate("\141\x6c\x70\150\x61\137\156\165\x6d\145\162\151\143", "\x68\145\x6c\154\157\40\52\50\136\x2a\136\x2a\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\160\150\x61\x5f\156\165\x6d\145\x72\151\143", '')); } }
