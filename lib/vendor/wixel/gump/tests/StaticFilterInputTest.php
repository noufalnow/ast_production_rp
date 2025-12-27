<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2xw; use zm6fh; use Exception; class StaticFilterInputTest extends BaseTestCase { public function testStaticFilterInputCall() { $AyT6U = GUMP::filter_input(["\x6f\164\150\145\x72" => "\x74\x65\170\164"], ["\157\x74\x68\x65\162" => "\165\x70\x70\x65\162\137\143\141\163\145"]); $this->assertEquals(["\x6f\x74\150\145\x72" => "\x54\105\130\x54"], $AyT6U); } }
