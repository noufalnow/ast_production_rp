<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1\u5KgH; use fncei; use Exception; use WFdG1\WzARa; class RmpunctuationFilterTest extends BaseTestCase { const zm3Yu = "\162\x6d\x70\x75\x6e\x63\x74\165\x61\164\151\157\156"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x68\145\x6c\154\x6f\77\41\72\x3b", "\150\145\154\154\157"]]; } }
