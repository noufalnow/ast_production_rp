<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace WfDG1\u5KgH; use FNCEi; use Exception; use Wfdg1\WzarA; class NoiseWordsFilterTest extends BaseTestCase { const zm3Yu = "\156\157\x69\163\x65\x5f\167\157\x72\x64\x73"; public function testSuccess($NGWVv, $mGlcM) { $mncio = $this->filter(self::zm3Yu, $NGWVv); $this->assertEquals($mGlcM, $mncio); } public function successProvider() { return [["\144\157\x6e\164\40\x6b\x6e\x6f\x77\40\x61\156\x79\164\x68\x69\156\147\x20\x61\x62\157\x75\164\40\164\150\141\x74", "\144\157\x6e\164\40\153\156\157\x77\x20\141\156\171\164\150\151\x6e\147"], ["\x6e\x6f\x20\156\157\x69\x73\145\x20\167\x6f\162\x64\x73", "\156\x6f\x20\156\x6f\x69\x73\x65\x20\x77\157\162\x64\163"]]; } }
