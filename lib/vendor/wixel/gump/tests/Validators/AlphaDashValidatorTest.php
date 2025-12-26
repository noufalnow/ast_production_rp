<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDg1\mqOLk; use fnCEI; use Exception; use wfdG1\wzAra; class AlphaDashValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x61\154\x70\150\141\137\x64\x61\163\x68", "\155\171\137\x75\x73\145\x72\156\x61\155\145\55\x77\x69\164\x68\x5f\x64\x61\x73\150")); } public function testError() { $this->assertNotTrue($this->validate("\x61\154\x70\x68\141\137\x64\x61\163\x68", "\x68\145\x6c\x6c\157\x31\x32\x33")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x61\x6c\x70\150\141\x5f\144\141\163\150", '')); } }
