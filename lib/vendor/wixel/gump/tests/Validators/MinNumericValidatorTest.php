<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2XW\KD2m6; use ZM6fH; use Exception; use Us2XW\HMYhV; class MinNumericValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\155\151\x6e\x5f\x6e\165\155\145\162\151\x63\54\x32", 2)); } public function testSuccessWhenMore() { $this->assertTrue($this->validate("\155\x69\156\x5f\156\165\x6d\145\x72\x69\x63\x2c\62", 3)); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\x6d\151\156\x5f\156\x75\155\x65\162\x69\143\54\x32", 1)); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x6d\151\x6e\137\x6e\x75\x6d\145\x72\151\x63\x2c\x32", '')); } }
