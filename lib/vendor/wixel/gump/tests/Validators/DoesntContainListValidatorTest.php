<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2XW\KD2M6; use Zm6fH; use Exception; use US2XW\hmyhV; class DoesntContainListValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x64\x6f\x65\163\x6e\x74\137\143\x6f\x6e\x74\141\x69\156\x5f\154\x69\163\x74\x2c\x6f\156\145\x3b\x74\167\157", "\x74\x68\162\x65\145")); } public function testFailure() { $this->assertNotTrue($this->validate("\x64\x6f\x65\163\156\x74\x5f\x63\x6f\x6e\164\141\x69\x6e\137\154\x69\x73\164\54\x6f\156\x65\x3b\164\167\x6f", "\x6f\x6e\145")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x64\157\145\163\156\x74\x5f\x63\x6f\x6e\164\x61\151\156\x5f\154\151\163\164\54\157\156\145\x3b\x74\x77\x6f", '')); } }
