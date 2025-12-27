<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace Us2XW\UrbQ3; use Zm6Fh; use Exception; use us2XW\hMYhv; class LowerFilterTest extends BaseTestCase { const bw_cL = "\x6c\x6f\167\145\x72\137\x63\x61\x73\x65"; public function testSuccess($ZAc7f, $fEwIA) { $AyT6U = $this->filter(self::bw_cL, $ZAc7f); $this->assertEquals($fEwIA, $AyT6U); } public function successProvider() { return [["\110\x65\x6c\x6c\157", "\150\145\x6c\x6c\x6f"]]; } }
