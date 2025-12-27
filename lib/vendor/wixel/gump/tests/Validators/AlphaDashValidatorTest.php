<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace uS2XW\KD2M6; use zm6Fh; use Exception; use Us2xW\HMyHv; class AlphaDashValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\x6c\x70\150\x61\137\144\141\163\x68", "\x6d\x79\137\x75\x73\145\x72\156\141\x6d\x65\x2d\167\x69\164\x68\137\x64\x61\163\150")); } public function testError() { $this->assertNotTrue($this->validate("\141\154\x70\x68\141\x5f\x64\141\163\150", "\x68\x65\x6c\x6c\157\61\x32\63")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\x6c\x70\150\x61\137\x64\x61\x73\150", '')); } }
