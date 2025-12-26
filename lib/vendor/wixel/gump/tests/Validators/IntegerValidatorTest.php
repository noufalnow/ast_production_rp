<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Wfdg1\mQoLk; use fnceI; use Exception; use wFDg1\Wzara; class IntegerValidatorTest extends BaseTestCase { const bRl7i = "\x72\x65\161\x75\151\162\x65\x64\x7c\151\x6e\x74\145\147\x65\162"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [["\61\62\x33"], [123], [-1], [0], ["\60"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\x74\x65\170\x74"], [true], [null], [1.1], ["\x31\56\x31"], [["\x61\162\162\141\171"]]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x69\x6e\164\x65\147\145\x72", '')); } }
