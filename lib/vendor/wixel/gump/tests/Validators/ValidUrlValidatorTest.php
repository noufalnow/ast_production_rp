<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW\kd2M6; use zM6Fh; use Exception; use Us2xW\hmYhV; class ValidUrlValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\154\x69\144\x5f\165\x72\x6c"; public function testSuccess($ZAc7f) { $this->assertTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function successProvider() { return [["\x68\x74\164\160\x3a\57\x2f\164\145\163\164\56\x63\157\x6d\57"], ["\150\164\164\x70\x3a\57\x2f\x74\x65\x73\164\x2e\x63\157\155"], ["\x68\x74\164\x70\163\72\57\57\x74\145\x73\164\56\143\x6f\x6d"], ["\164\x63\x70\x3a\57\57\x74\145\163\x74\x2e\x63\157\155"], ["\146\x74\160\72\57\x2f\164\145\163\164\56\143\157\155"]]; } public function testError($ZAc7f) { $this->assertNotTrue($this->validate(self::Kx2r1, $ZAc7f)); } public function errorProvider() { return [["\x65\170\141\x6d\160\154\x65\56\143\x6f\x6d"], ["\164\145\x78\164"]]; } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
