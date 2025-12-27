<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW\Kd2M6; use Zm6Fh; use Exception; use Us2Xw\HMyhv; class StreetAddressValidatorTest extends BaseTestCase { const Kx2r1 = "\x73\x74\x72\145\x65\164\137\x61\144\x64\x72\x65\163\163"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\x36\40\101\x76\157\156\x64\141\x6e\x73\x20\122\157\141\x64"], ["\x43\x61\x6c\x6c\x65\40\115\x65\144\x69\164\x65\x72\162\xc3\241\156\x65\x6f\x20\62"], ["\143\x2f\115\145\x64\x69\x74\x65\162\x72\xc3\241\x6e\145\x6f\x20\62"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\101\166\x6f\x6e\x64\141\x6e\163\40\x52\x6f\141\144"], ["\164\145\170\x74"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
