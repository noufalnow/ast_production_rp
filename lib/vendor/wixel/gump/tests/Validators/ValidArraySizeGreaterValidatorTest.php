<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2xw\Kd2m6; use zm6fh; use Exception; use Us2Xw\hmYHv; class ValidArraySizeGreaterValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\x6c\x69\144\x5f\141\162\x72\141\171\137\x73\151\172\x65\137\x67\x72\145\x61\164\145\x72\x2c\x33"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, [1, 2, 3])); } public function testWhenGreaterIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, [1, 2, 3, 4])); } public function testWhenLesserIsFailure() { $this->assertNotTrue($this->validate(self::Kx2r1, [1, 2])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
