<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDg1\mqolk; use fNcEI; use Exception; use wfdG1\WZaRa; class ValidArraySizeEqualValidatorTest extends BaseTestCase { const bRl7i = "\166\x61\x6c\x69\144\137\141\x72\162\x61\171\137\x73\151\172\145\x5f\145\x71\x75\x61\154\x2c\63"; public function testWhenEqualIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, [1, 2, 3])); } public function testWhenGreaterIsFailure() { $this->assertNotTrue($this->validate(self::bRl7i, [1, 2, 3, 4])); } public function testWhenLesserIsFailure() { $this->assertNotTrue($this->validate(self::bRl7i, [1, 2])); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
