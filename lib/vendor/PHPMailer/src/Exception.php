<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace PHPMailer\PHPMailer; class Exception extends \Exception { public function errorMessage() { return "\x3c\x73\164\162\157\156\x67\x3e" . htmlspecialchars($this->getMessage(), ENT_COMPAT | ENT_HTML401) . "\74\x2f\163\x74\162\157\156\147\x3e\x3c\x62\162\40\x2f\x3e\12"; } }
