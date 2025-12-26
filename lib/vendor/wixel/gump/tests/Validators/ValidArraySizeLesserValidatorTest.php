<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1\mQOLk; use fnceI; use Exception; use wfdG1\wZaRA; class ValidArraySizeLesserValidatorTest extends BaseTestCase { const bRl7i = "\166\x61\154\151\x64\x5f\x61\x72\x72\x61\x79\x5f\163\x69\x7a\x65\x5f\x6c\145\163\163\x65\162\x2c\63"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, [1, 2, 3])); } public function testWhenLesserIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, [1, 2])); } public function testWhenGreaterIsFailure() { $this->assertNotTrue($this->validate(self::bRl7i, [1, 2, 3, 4])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
