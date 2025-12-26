<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFdG1\mQOLK; use fNCEI; use Exception; use wFdG1\wzArA; class ValidEmailValidatorTest extends BaseTestCase { const bRl7i = "\166\x61\154\x69\x64\137\145\155\x61\x69\x6c"; public function testSuccess() { $this->assertTrue($this->validate(self::bRl7i, "\x6d\x79\x65\155\141\151\154\x40\150\157\163\x74\x2e\x63\x6f\155")); } public function testFailure() { $this->assertNotTrue($this->validate(self::bRl7i, "\163\60\x6d\145\164\150\x31\156\147\x2d\156\157\x74\x45\x6d\x61\x69\154\x5c\x72")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate(self::bRl7i, '')); } }
