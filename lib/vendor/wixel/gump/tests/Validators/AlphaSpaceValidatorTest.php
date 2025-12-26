<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDg1\mqolk; use fNcEI; use Exception; use WFDg1\wZARA; class AlphaSpaceValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\x6c\160\150\x61\137\163\160\141\x63\x65", "\155\x79\40\x75\163\x65\162\156\x61\x6d\145")); } public function testError() { $this->assertNotTrue($this->validate("\141\154\x70\x68\141\x5f\163\160\141\x63\145", "\x68\x65\154\154\x6f\40\52\x28\x5e\x2a\136\52\46")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\x70\x68\x61\137\x73\x70\141\x63\x65", '')); } }
