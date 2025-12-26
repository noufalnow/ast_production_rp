<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFDG1\U5kgH; use fNCei; use Exception; use WfdG1\wZaRa; class MsWordCharactersFilterTest extends BaseTestCase { const zm3Yu = "\155\x73\137\167\157\162\144\137\143\x68\141\162\x61\143\x74\145\x72\x73"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\342\200\x9c\115\171\x20\x71\165\x6f\164\x65\xe2\200\235", "\x22\x4d\x79\40\x71\165\x6f\x74\145\x22"], ["\342\x80\230\x4d\x79\x20\x71\x75\157\x74\145\xe2\x80\231", "\47\115\x79\x20\161\165\x6f\x74\145\47"], ["\342\x80\x93\40\141\x6e\144\40\x74\150\x65\156", "\x2d\40\x61\x6e\144\40\x74\150\x65\x6e"], ["\x41\x6e\x64\40\x61\164\40\164\150\x65\x20\145\x6e\x64\xe2\x80\246", "\x41\x6e\x64\40\141\x74\x20\164\x68\x65\x20\145\156\x64\x2e\56\x2e"]]; } }
