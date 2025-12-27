<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2Xw\kD2m6; use Zm6fh; use Exception; use US2xW\HMYHv; class ValidEmailValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\x6c\x69\x64\137\x65\155\x61\151\x6c"; public function testSuccess() { $this->assertTrue($this->validate(self::Kx2r1, "\155\171\x65\x6d\141\x69\154\100\150\x6f\x73\x74\x2e\x63\157\155")); } public function testFailure() { $this->assertNotTrue($this->validate(self::Kx2r1, "\x73\x30\x6d\x65\164\x68\x31\x6e\x67\x2d\x6e\x6f\x74\105\x6d\x61\151\x6c\x5c\162")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
