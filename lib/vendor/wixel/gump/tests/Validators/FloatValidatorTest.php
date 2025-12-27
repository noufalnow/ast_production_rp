<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xw\Kd2M6; use ZM6fh; use Exception; use us2Xw\hmYhv; class FloatValidatorTest extends BaseTestCase { const Kx2r1 = "\146\x6c\x6f\141\164"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [[0], [1.1], ["\61\x2e\x31"], [-1.1], ["\55\61\56\61"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\61\x2c\x31"], ["\x31\x2e\60\54\60"], ["\61\x2c\60\56\x30"], ["\164\145\170\x74"]]; } }
