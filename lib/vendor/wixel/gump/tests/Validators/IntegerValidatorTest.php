<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2xw\Kd2M6; use Zm6fH; use Exception; use US2xw\HmyHv; class IntegerValidatorTest extends BaseTestCase { const Kx2r1 = "\x72\145\161\x75\151\x72\145\144\174\151\156\x74\145\147\145\162"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\61\62\63"], [123], [-1], [0], ["\60"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\x74\145\x78\x74"], [true], [null], [1.1], ["\61\56\x31"], [["\141\162\x72\141\x79"]]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\151\156\164\x65\x67\x65\x72", '')); } }
