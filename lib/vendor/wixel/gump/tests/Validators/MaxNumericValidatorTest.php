<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\mqolk; use fncEi; use Exception; use wfdG1\wZaRA; class MaxNumericValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\x6d\x61\170\x5f\x6e\x75\x6d\x65\162\x69\143\54\x32", 2)); } public function testSuccessWhenLess() { $this->assertTrue($this->validate("\155\141\x78\137\x6e\165\155\x65\x72\x69\143\54\x32", 1)); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\x6d\x61\x78\137\156\x75\x6d\145\162\151\x63\x2c\x32", 3)); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\155\141\x78\137\156\x75\155\145\162\151\x63\x2c\x32", '')); } }
