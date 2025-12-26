<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1\u5KGh; use fnCEi; use Exception; use WfDG1\WZaRa; class LowerFilterTest extends BaseTestCase { const zm3Yu = "\154\x6f\167\x65\162\x5f\143\141\163\x65"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\110\x65\154\x6c\157", "\150\145\x6c\x6c\x6f"]]; } }
