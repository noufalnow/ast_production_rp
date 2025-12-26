<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdG1\U5KgH; use FNcei; use Exception; use wFdg1\WzaRA; class UrlencodeFilterTest extends BaseTestCase { const zm3Yu = "\x75\162\x6c\x65\x6e\x63\157\x64\x65"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\150\x74\x74\160\163\72\57\57\167\x77\x77\x2e\x64\157\x6d\x61\151\156\xc3\x85\303\205\x2e\143\x6f\x6d", "\x68\164\x74\160\x73\45\63\x41\45\x32\106\45\62\x46\167\167\x77\x2e\144\157\155\141\x69\156\45\103\x33\x25\70\65\x25\x43\63\45\x38\65\56\x63\x6f\x6d"]]; } }
