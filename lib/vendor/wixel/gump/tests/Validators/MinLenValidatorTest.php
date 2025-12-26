<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFdG1\MQoLK; use fnCei; use Exception; use WFDg1\wzarA; use EX1zk as m; class MinLenValidatorTest extends BaseTestCase { public function testSuccessWhenEqual() { $this->assertTrue($this->validate("\155\x69\x6e\137\154\x65\x6e\x2c\x35", "\303\xb1\303\xa1\156\144\xc3\272")); } public function testSuccessWhenMore() { $this->assertTrue($this->validate("\x6d\151\x6e\137\154\x65\x6e\x2c\62", "\303\xb1\303\241\156")); } public function testErrorWhenLess() { $this->assertNotTrue($this->validate("\x6d\x69\x6e\137\x6c\x65\x6e\54\x32", "\303\xb1")); } public function testWhenInputIsEmptyAndNotRequiredIsSuccess() { $this->assertTrue($this->validate("\x6d\151\x6e\137\154\x65\x6e\x2c\62", '')); } }
