<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace wFdG1; use FNCei; use Exception; class StaticAddFilterTest extends BaseTestCase { public function testItThrowsExceptionWhenFilterWithSameNameIsAdded() { goto n6RnP; tVAjT: GUMP::add_filter("\x63\165\x73\164\157\155", function ($VHpfx, array $QTxIX = []) { return strtoupper($VHpfx); }); goto Xx6pN; n6RnP: GUMP::add_filter("\143\165\163\x74\x6f\x6d", function ($VHpfx, array $QTxIX = []) { return strtoupper($VHpfx); }); goto Wd2gX; Wd2gX: $this->expectException(Exception::class); goto y10NY; y10NY: $this->expectExceptionMessage("\x27\143\x75\x73\164\x6f\x6d\x27\40\146\151\154\x74\x65\x72\x20\151\x73\x20\141\x6c\x72\145\x61\x64\x79\40\144\x65\146\x69\156\145\x64\x2e"); goto tVAjT; Xx6pN: } }
