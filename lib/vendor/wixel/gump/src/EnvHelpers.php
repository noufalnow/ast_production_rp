<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 declare (strict_types=1); namespace FnCEI; class EnvHelpers { public static function functionExists($MNo5x) { return function_exists($MNo5x); } public static function date($BAj1h, $pa9iA = null) { goto ne_L0; EYxCa: vMNca: goto eH_et; eH_et: return date($BAj1h, $pa9iA); goto mpyJC; ne_L0: if (!($pa9iA === null)) { goto vMNca; } goto tUZ3a; tUZ3a: $pa9iA = time(); goto EYxCa; mpyJC: } public static function checkdnsrr($oBIO6, $wZHLW = null) { return checkdnsrr($oBIO6, $wZHLW); } public static function gethostbyname($NGYI0) { return gethostbyname($NGYI0); } public static function file_get_contents($IfSk8, $izvVH = false, $Cidin = null, $oCyVo = 0, $VwW77 = null) { return file_get_contents($IfSk8, $izvVH, $Cidin, $oCyVo, $VwW77); } public static function file_exists($IfSk8) { return file_exists($IfSk8); } }
