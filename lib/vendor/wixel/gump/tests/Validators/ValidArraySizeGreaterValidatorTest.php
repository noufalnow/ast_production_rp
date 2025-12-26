<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfdg1\MqOLk; use FNCeI; use Exception; use WFdG1\WzaRA; class ValidArraySizeGreaterValidatorTest extends BaseTestCase { const bRl7i = "\x76\x61\x6c\x69\144\137\141\162\x72\141\171\x5f\163\x69\172\x65\137\x67\x72\145\141\164\x65\162\54\63"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, [1, 2, 3])); } public function testWhenGreaterIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, [1, 2, 3, 4])); } public function testWhenLesserIsFailure() { $this->assertNotTrue($this->validate(self::bRl7i, [1, 2])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
