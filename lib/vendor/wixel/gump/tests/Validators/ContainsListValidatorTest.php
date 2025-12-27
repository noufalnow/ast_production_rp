<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xw\KD2M6; use Zm6fh; use Exception; use US2xW\hmYhv; class ContainsListValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\143\157\156\164\141\x69\156\163\137\154\x69\x73\x74\x2c\x6f\156\145\73\164\167\157", "\x6f\x6e\145")); } public function testFailure() { $this->assertNotTrue($this->validate("\x63\x6f\x6e\164\141\151\156\163\x5f\x6c\x69\x73\164\54\157\156\x65\73\164\x77\x6f", "\60")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\143\157\x6e\x74\141\x69\x6e\x73\137\x6c\151\163\x74\x2c\157\156\x65\x3b\x74\x77\x6f", '')); } }
