<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2xW\KD2m6; use ZM6FH; use Exception; use Us2XW\HmYhv; use ZYViO as m; class ExactLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\x65\x78\141\x63\x74\137\154\145\156\54\x35", "\303\261\303\xa1\156\x64\xc3\xba")); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\145\170\x61\143\164\137\154\x65\156\x2c\62", "\xc3\xb1\303\xa1\x6e")); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\145\x78\141\x63\x74\x5f\x6c\145\x6e\54\62", "\303\261")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\145\x78\141\x63\164\137\x6c\145\156\54\x32", '')); } }
