<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xw\KD2m6; use zm6fH; use Exception; use US2XW\hmyhV; class ValidArraySizeEqualValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\x61\154\x69\x64\137\141\x72\x72\141\171\137\163\x69\172\145\137\x65\161\x75\141\154\x2c\x33"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, [1, 2, 3])); } public function testWhenGreaterIsFailure() { $this->assertNotTrue($this->validate(self::Kx2r1, [1, 2, 3, 4])); } public function testWhenLesserIsFailure() { $this->assertNotTrue($this->validate(self::Kx2r1, [1, 2])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
