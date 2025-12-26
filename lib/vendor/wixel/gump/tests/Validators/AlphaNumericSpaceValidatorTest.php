<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\MQOlk; use fnCEI; use Exception; use wfDG1\WzarA; class AlphaNumericSpaceValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\154\160\150\x61\137\x6e\165\155\x65\x72\151\x63\137\x73\x70\x61\143\x65", "\x6d\x79\40\x75\163\x65\162\x6e\141\155\145\61\62\63")); } public function testError() { $this->assertNotTrue($this->validate("\141\154\160\150\141\137\156\165\x6d\145\162\x69\x63\x5f\163\160\141\143\x65", "\x68\x65\x6c\x6c\157\40\52\x28\136\x2a\x5e\x2a\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\x70\x68\141\137\156\x75\x6d\145\x72\x69\143\x5f\163\x70\x61\x63\145", '')); } }
