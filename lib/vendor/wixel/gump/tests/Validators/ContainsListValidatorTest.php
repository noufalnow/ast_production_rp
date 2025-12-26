<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDG1\mQOlk; use FnceI; use Exception; use wfDG1\wzARA; class ContainsListValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\143\157\x6e\x74\141\x69\x6e\163\x5f\154\151\163\164\x2c\157\x6e\145\73\164\167\x6f", "\x6f\x6e\145")); } public function testFailure() { $this->assertNotTrue($this->validate("\143\157\156\164\x61\x69\x6e\x73\137\x6c\151\x73\164\x2c\157\x6e\x65\73\x74\x77\157", "\60")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\143\x6f\x6e\x74\141\x69\x6e\x73\137\x6c\x69\x73\x74\54\157\156\145\73\x74\167\x6f", '')); } }
