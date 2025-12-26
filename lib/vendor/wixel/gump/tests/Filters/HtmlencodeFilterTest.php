<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFDG1\U5KGh; use FnCEI; use Exception; use wFdG1\WZArA; class HtmlencodeFilterTest extends BaseTestCase { const zm3Yu = "\x68\164\x6d\x6c\145\x6e\x63\x6f\144\145"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\111\163\x20\120\145\x74\x65\x72\40\x3c\x73\x6d\x61\x72\164\x3e\x20\46\40\x66\165\x6e\156\x79", "\111\x73\40\120\x65\x74\x65\162\40\x26\43\x36\60\73\163\x6d\141\x72\164\46\x23\66\x32\x3b\x20\x26\43\x33\x38\73\x20\146\165\x6e\156\x79"]]; } }
