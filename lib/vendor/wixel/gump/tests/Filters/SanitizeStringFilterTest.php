<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wfDG1\U5KgH; use fNCeI; use Exception; use wfDg1\WzaRa; class SanitizeStringFilterTest extends BaseTestCase { const zm3Yu = "\163\141\156\151\x74\x69\x7a\145\x5f\163\164\x72\151\156\x67"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x3c\150\61\x3e\x48\145\x6c\154\x6f\40\127\157\162\154\x64\41\74\57\x68\x31\x3e", "\x48\x65\x6c\x6c\x6f\40\x57\x6f\x72\x6c\144\41"]]; } }
