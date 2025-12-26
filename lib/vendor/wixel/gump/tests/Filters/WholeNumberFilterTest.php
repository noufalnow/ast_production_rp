<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDg1\U5KgH; use FnCEi; use Exception; use wFDg1\WzARa; class WholeNumberFilterTest extends BaseTestCase { const zm3Yu = "\167\150\157\154\145\137\156\x75\x6d\142\x65\162"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [[1, 1], ["\55\61", "\55\61"], [4.2, 4], ["\x30\x34\62", "\x34\62"]]; } }
