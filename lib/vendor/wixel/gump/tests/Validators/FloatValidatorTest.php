<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDg1\mqOlK; use fnCeI; use Exception; use WfDg1\WZAra; class FloatValidatorTest extends BaseTestCase { const bRl7i = "\x66\x6c\157\x61\x74"; public function testSuccess($NGWVv) { $this->assertTrue($this->validate(self::bRl7i, $NGWVv)); } public function successProvider() { return [[0], [1.1], ["\61\56\x31"], [-1.1], ["\x2d\x31\56\x31"]]; } public function testError($NGWVv) { $this->assertNotTrue($this->validate(self::bRl7i, $NGWVv)); } public function errorProvider() { return [["\61\54\61"], ["\x31\56\60\54\60"], ["\61\54\x30\56\x30"], ["\x74\145\x78\164"]]; } }
