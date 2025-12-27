<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xw\Kd2m6; use zM6Fh; use Exception; use uS2xW\HMyhv; class MaxNumericValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\155\x61\170\137\x6e\165\155\145\x72\x69\143\x2c\62", 2)); } public function testSuccessWhenLess() { $this->assertTrue($this->validate("\x6d\141\170\137\x6e\x75\155\x65\x72\x69\143\x2c\62", 1)); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\155\x61\x78\137\156\165\x6d\145\x72\151\x63\x2c\62", 3)); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\155\x61\170\x5f\156\165\x6d\145\x72\x69\143\x2c\x32", '')); } }
