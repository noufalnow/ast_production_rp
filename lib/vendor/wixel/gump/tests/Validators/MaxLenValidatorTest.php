<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW\kd2m6; use zM6Fh; use Exception; use us2xw\hmyHV; use ZYvIo as m; class MaxLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\155\141\170\137\154\x65\x6e\54\65", "\303\261\303\241\x6e\144\303\xba")); } public function testSuccessWhenLess() { $this->assertTrue($this->validate("\155\141\170\137\154\145\156\x2c\62", "\303\261")); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\155\x61\170\x5f\x6c\x65\x6e\x2c\62", "\xc3\261\xc3\xa1\156")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\155\x61\170\137\x6c\x65\156\54\x32", '')); } }
