<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW\kd2m6; use Zm6fh; use Exception; use uS2xW\hMYHV; use zyVIo as m; class MinLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\x6d\x69\x6e\137\x6c\x65\156\x2c\x35", "\303\261\xc3\241\156\144\xc3\272")); } public function testSuccessWhenMore() { $this->assertTrue($this->validate("\x6d\x69\156\x5f\x6c\x65\x6e\54\62", "\303\xb1\303\241\x6e")); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\x6d\x69\x6e\x5f\x6c\x65\x6e\54\62", "\303\261")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x6d\x69\156\137\x6c\145\x6e\54\x32", '')); } }
