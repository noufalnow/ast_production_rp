<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace us2Xw\UrBQ3; use zM6fh; use Exception; use uS2xW\HMyhv; class NoiseWordsFilterTest extends BaseTestCase { const bw_cL = "\156\157\x69\163\x65\x5f\167\x6f\x72\144\163"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\144\x6f\156\x74\40\x6b\156\157\x77\40\x61\156\171\164\150\151\156\x67\x20\x61\142\157\165\164\x20\x74\x68\141\x74", "\144\157\x6e\x74\40\x6b\x6e\x6f\167\40\x61\x6e\171\x74\x68\x69\x6e\x67"], ["\x6e\157\40\156\x6f\151\163\145\x20\167\157\162\x64\x73", "\156\157\40\156\157\x69\163\145\x20\167\x6f\x72\x64\163"]]; } }
