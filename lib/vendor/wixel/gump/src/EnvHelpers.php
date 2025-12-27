<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 declare (strict_types=1); namespace ZM6Fh; class EnvHelpers { public static function functionExists($LA5N5) { return function_exists($LA5N5); } public static function date($Eh7Cr, $Dm0b3 = null) { goto kcZq5; f0Nso: $Dm0b3 = time(); goto OproS; W4CyJ: return date($Eh7Cr, $Dm0b3); goto YS9GL; OproS: hq33M: goto W4CyJ; kcZq5: if (!($Dm0b3 === null)) { goto hq33M; } goto f0Nso; YS9GL: } public static function checkdnsrr($gn7JK, $SLAaL = null) { return checkdnsrr($gn7JK, $SLAaL); } public static function gethostbyname($pOZmo) { return gethostbyname($pOZmo); } public static function file_get_contents($NMAqJ, $TLjhJ = false, $yGOqj = null, $bFCCx = 0, $phrfP = null) { return file_get_contents($NMAqJ, $TLjhJ, $yGOqj, $bFCCx, $phrfP); } public static function file_exists($NMAqJ) { return file_exists($NMAqJ); } }
