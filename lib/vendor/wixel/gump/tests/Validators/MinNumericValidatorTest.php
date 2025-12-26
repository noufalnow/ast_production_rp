<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\mQolk; use FncEi; use Exception; use WFdG1\wZara; class MinNumericValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\x6d\151\x6e\x5f\156\x75\x6d\x65\162\151\143\x2c\x32", 2)); } public function testSuccessWhenMore() { $this->assertTrue($this->validate("\x6d\151\x6e\137\156\x75\155\x65\x72\151\x63\x2c\x32", 3)); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\155\151\x6e\x5f\156\165\x6d\145\162\x69\x63\x2c\62", 1)); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x6d\151\x6e\x5f\x6e\165\155\145\x72\151\143\54\x32", '')); } }
