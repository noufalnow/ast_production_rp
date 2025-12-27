<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xW\kd2m6; use zm6fh; use Exception; use US2xW\HMyhV; class AlphaSpaceValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\x6c\x70\150\x61\137\x73\x70\141\x63\145", "\x6d\171\40\165\163\145\162\156\141\x6d\x65")); } public function testError() { $this->assertNotTrue($this->validate("\x61\154\x70\x68\141\137\163\x70\x61\x63\x65", "\150\x65\x6c\154\157\x20\52\50\x5e\52\136\x2a\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\160\150\141\x5f\x73\x70\x61\x63\145", '')); } }
