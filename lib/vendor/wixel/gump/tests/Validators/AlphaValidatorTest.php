<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\MqoLk; use FnCeI; use Exception; use WfdG1\WzaRA; class AlphaValidatorTest extends BaseTestCase { public function testSuccess() { $this->assertTrue($this->validate("\141\154\x70\x68\141", "\165\163\145\162\x6e\x61\155\x65")); } public function testError() { $this->assertNotTrue($this->validate("\141\x6c\160\x68\x61", "\150\x65\x6c\x6c\157\40\52\50\136\x2a\x5e\52\46")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\141\154\160\150\x61", '')); } }
