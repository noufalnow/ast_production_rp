<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\mQoLK; use fncEi; use Exception; use WFdG1\Wzara; class ValidUrlValidatorTest extends BaseTestCase { const bRl7i = "\166\141\x6c\x69\x64\x5f\165\162\x6c"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\150\x74\x74\x70\x3a\x2f\x2f\164\x65\x73\x74\56\143\x6f\x6d\x2f"], ["\150\x74\164\160\x3a\x2f\57\x74\145\163\164\56\x63\157\155"], ["\150\x74\164\160\163\72\x2f\57\164\145\x73\x74\x2e\143\x6f\155"], ["\x74\143\x70\x3a\57\57\164\x65\163\164\56\143\157\155"], ["\146\164\160\72\57\57\164\145\163\x74\x2e\x63\157\155"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\145\170\141\155\160\x6c\145\x2e\x63\x6f\x6d"], ["\164\x65\170\164"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
