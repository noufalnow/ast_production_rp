<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW; use Zm6Fh; use Exception; class StaticAddValidatorTest extends BaseTestCase { public function testItThrowsExceptionWhenValidatorWithSameNameIsAdded() { goto YqquE; kA3oS: $this->expectExceptionMessage("\47\x63\x75\x73\164\x6f\155\x27\40\x76\141\x6c\x69\x64\141\164\157\x72\x20\x69\x73\40\141\154\162\145\x61\x64\x79\x20\x64\x65\x66\151\156\145\144\56"); goto NpTFZ; YqquE: GUMP::add_validator("\143\x75\163\164\157\155", function ($wZCta, $ZAc7f, array $kIQbn = []) { return $ZAc7f[$wZCta] === "\157\x6b"; }, "\105\x72\x72\157\162\40\155\145\x73\163\141\x67\145"); goto TX6wo; NpTFZ: GUMP::add_validator("\143\165\x73\x74\157\155", function ($wZCta, $ZAc7f, array $kIQbn = []) { return $ZAc7f[$wZCta] === "\x6f\153"; }, "\105\162\x72\x6f\162\40\155\145\163\x73\x61\x67\x65"); goto w1Rb5; TX6wo: $this->expectException(Exception::class); goto kA3oS; w1Rb5: } }
