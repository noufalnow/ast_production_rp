<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace US2XW\UrBQ3; use Zm6FH; use Exception; use US2Xw\HMyhV; class SanitizeStringFilterTest extends BaseTestCase { const bw_cL = "\163\141\156\x69\164\x69\172\x65\x5f\163\164\162\151\156\147"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\74\150\x31\76\x48\145\154\154\157\x20\127\157\x72\x6c\x64\x21\x3c\57\150\x31\x3e", "\110\x65\x6c\154\157\x20\x57\x6f\162\x6c\144\x21"]]; } }
