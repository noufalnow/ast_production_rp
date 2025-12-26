<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Wfdg1; use fNcei; use Exception; class StaticAddValidatorTest extends BaseTestCase { public function testItThrowsExceptionWhenValidatorWithSameNameIsAdded() { goto B_uzI; XOHZq: GUMP::add_validator("\143\x75\163\164\157\x6d", function ($XrxK4, $NGWVv, array $QTxIX = []) { return $NGWVv[$XrxK4] === "\157\x6b"; }, "\105\x72\162\157\162\40\x6d\x65\x73\163\141\147\x65"); goto Owu4L; DagBY: $this->expectExceptionMessage("\47\x63\165\163\164\x6f\x6d\x27\40\166\x61\x6c\151\144\141\x74\x6f\x72\x20\x69\163\x20\x61\x6c\162\x65\x61\144\x79\x20\144\145\x66\151\x6e\x65\144\x2e"); goto XOHZq; S0LvR: $this->expectException(Exception::class); goto DagBY; B_uzI: GUMP::add_validator("\x63\165\x73\164\157\155", function ($XrxK4, $NGWVv, array $QTxIX = []) { return $NGWVv[$XrxK4] === "\157\153"; }, "\x45\x72\x72\157\x72\x20\155\145\x73\163\141\147\145"); goto S0LvR; Owu4L: } }
