<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Wfdg1\mQOLk; use fNCei; use Exception; use WfDG1\wZARA; class ValidJsonStringValidatorTest extends BaseTestCase { const bRl7i = "\166\x61\x6c\x69\x64\x5f\152\163\157\x6e\137\163\164\162\x69\156\x67"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\x7b\175"], ["\x7b\x22\164\x65\x73\x74\x69\156\x67\42\72\x20\x74\162\x75\x65\x7d"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\173\x7d\x7d"], ["\173\164\145\x73\x74\72\164\x72\x75\x65\175"], ["\173\x22\x74\x65\x73\164\42\72\x74\145\x78\164\175"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
