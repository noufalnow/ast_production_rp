<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WFdg1; use FnCeI; use Exception; class StaticFilterInputTest extends BaseTestCase { public function testStaticFilterInputCall() { $mncio = GUMP::filter_input(["\x6f\x74\150\x65\x72" => "\164\145\x78\x74"], ["\x6f\x74\150\145\x72" => "\165\160\x70\x65\x72\137\x63\x61\x73\x65"]); $this->assertEquals(["\x6f\164\x68\x65\162" => "\124\105\x58\124"], $mncio); } }
