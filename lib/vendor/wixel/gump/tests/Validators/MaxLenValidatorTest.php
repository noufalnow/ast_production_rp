<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDG1\mQOlK; use fnCeI; use Exception; use wFdG1\wzarA; use eX1Zk as m; class MaxLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\155\141\x78\137\x6c\x65\x6e\x2c\65", "\xc3\261\xc3\xa1\156\x64\303\272")); } public function testSuccessWhenLess() { $this->assertTrue($this->validate("\x6d\x61\x78\137\154\145\x6e\54\x32", "\303\xb1")); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\x6d\141\170\137\x6c\145\x6e\54\62", "\xc3\xb1\xc3\xa1\156")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\155\x61\170\x5f\154\x65\x6e\54\62", '')); } }
