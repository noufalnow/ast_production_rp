<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2xW\Kd2m6; use Zm6FH; use Exception; use US2xW\Hmyhv; class AlphaValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\x61\154\160\150\141", "\x75\x73\145\162\x6e\141\x6d\145")); } public function testError() { $this->assertNotTrue($this->validate("\x61\154\x70\150\141", "\150\145\x6c\154\157\x20\x2a\50\x5e\52\x5e\x2a\x26")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x61\154\x70\150\x61", '')); } }
