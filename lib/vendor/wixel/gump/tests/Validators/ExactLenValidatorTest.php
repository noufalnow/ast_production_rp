<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdG1\mqOLk; use FNCeI; use Exception; use WfDG1\wzAra; use EX1zk as m; class ExactLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\145\x78\141\x63\x74\x5f\154\x65\156\54\65", "\303\xb1\xc3\xa1\x6e\x64\303\xba")); } public function testErrorWhenMore() { $this->assertNotTrue($this->validate("\145\x78\x61\143\x74\x5f\154\x65\x6e\54\62", "\xc3\xb1\xc3\241\156")); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\x65\x78\141\x63\164\137\x6c\145\x6e\x2c\x32", "\303\xb1")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x65\170\141\x63\164\x5f\x6c\x65\x6e\x2c\62", '')); } }
