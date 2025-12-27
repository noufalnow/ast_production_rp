<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2xW\KD2m6; use ZM6fH; use Exception; use US2xW\hmyhV; class AlphaNumericSpaceValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x61\x6c\160\150\x61\x5f\x6e\165\x6d\145\x72\151\x63\x5f\x73\x70\141\143\145", "\155\171\40\165\163\145\162\156\x61\x6d\x65\61\62\x33")); } public function testError() { $this->assertNotTrue($this->validate("\x61\154\x70\150\141\x5f\156\x75\x6d\x65\162\x69\x63\137\x73\x70\141\x63\x65", "\150\145\154\154\x6f\x20\52\50\136\52\136\x2a\46")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\160\x68\141\x5f\156\165\155\x65\x72\x69\x63\x5f\x73\160\141\143\145", '')); } }
