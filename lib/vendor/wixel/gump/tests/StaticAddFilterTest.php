<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2Xw; use zm6Fh; use Exception; class StaticAddFilterTest extends BaseTestCase { public function testItThrowsExceptionWhenFilterWithSameNameIsAdded() { goto gRmgu; dbAyL: GUMP::add_filter("\143\165\x73\x74\x6f\x6d", function ($FbAFn, array $kIQbn = []) { return strtoupper($FbAFn); }); goto UU7F7; IxPeV: $this->expectExceptionMessage("\x27\143\x75\x73\164\157\155\47\x20\x66\x69\154\x74\145\x72\x20\151\163\40\141\154\x72\145\141\x64\171\x20\144\x65\x66\151\x6e\145\x64\56"); goto dbAyL; gRmgu: GUMP::add_filter("\143\165\x73\164\x6f\x6d", function ($FbAFn, array $kIQbn = []) { return strtoupper($FbAFn); }); goto GXpCM; GXpCM: $this->expectException(Exception::class); goto IxPeV; UU7F7: } }
