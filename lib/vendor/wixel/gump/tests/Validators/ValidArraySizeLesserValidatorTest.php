<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2xW\kd2m6; use Zm6fh; use Exception; use us2xW\HMYHV; class ValidArraySizeLesserValidatorTest extends BaseTestCase { const Kx2r1 = "\x76\141\154\x69\x64\137\141\x72\x72\x61\x79\137\x73\151\172\145\x5f\x6c\145\x73\x73\145\x72\54\63"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, [1, 2, 3])); } public function testWhenLesserIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, [1, 2])); } public function testWhenGreaterIsFailure() { $this->assertNotTrue($this->validate(self::Kx2r1, [1, 2, 3, 4])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::Kx2r1, '')); } }
