<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFDG1\U5KGH; use fncEi; use Exception; use wFdG1\wzARA; class BasicTagsFilterTest extends BaseTestCase { const zm3Yu = "\142\141\163\151\143\x5f\164\x61\147\163"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\x3c\x73\143\x72\151\160\164\x3e\141\x6c\145\x72\164\50\61\x29\73\74\x2f\163\143\x72\151\x70\x74\76\150\x65\154\154\x6f", "\141\154\145\162\x74\50\61\51\73\x68\x65\154\x6c\157"]]; } }
