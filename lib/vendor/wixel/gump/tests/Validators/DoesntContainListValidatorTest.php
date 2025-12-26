<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfdG1\mqoLK; use FncEi; use Exception; use WfDG1\wZARa; class DoesntContainListValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x64\x6f\x65\163\156\164\137\x63\x6f\x6e\x74\141\x69\156\137\x6c\151\163\164\54\x6f\156\145\x3b\164\167\x6f", "\164\x68\x72\145\145")); } public function testFailure() { $this->assertNotTrue($this->validate("\144\x6f\x65\163\x6e\x74\x5f\143\x6f\156\x74\x61\x69\156\x5f\154\x69\x73\164\x2c\157\156\145\73\x74\x77\157", "\x6f\156\145")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x64\x6f\145\163\156\x74\137\143\x6f\156\164\x61\151\x6e\x5f\x6c\151\163\x74\x2c\x6f\156\x65\x3b\164\167\157", '')); } }
