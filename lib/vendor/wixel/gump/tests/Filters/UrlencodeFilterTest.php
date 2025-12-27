<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2Xw\URbq3; use zm6FH; use Exception; use US2xW\hMyHv; class UrlencodeFilterTest extends BaseTestCase { const bw_cL = "\x75\x72\154\x65\x6e\x63\157\x64\145"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\150\x74\164\160\163\x3a\x2f\57\167\167\x77\x2e\144\x6f\155\141\x69\x6e\xc3\x85\303\205\56\143\x6f\x6d", "\150\x74\164\x70\x73\45\63\x41\x25\62\106\45\62\106\167\167\167\56\144\x6f\155\x61\x69\x6e\45\x43\x33\x25\70\65\x25\x43\x33\x25\70\x35\56\x63\x6f\x6d"]]; } }
