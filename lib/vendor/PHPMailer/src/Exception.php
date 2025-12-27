<?php
/*   __________________________________________________
    |  ##CreativeSol Management Information System##   |
    |__________________________________________________|
*/
 namespace PHPMailer\PHPMailer; class Exception extends \Exception { public function errorMessage() { return "\x3c\163\x74\x72\157\x6e\147\76" . htmlspecialchars($this->getMessage(), ENT_COMPAT | ENT_HTML401) . "\74\57\x73\x74\162\157\156\147\76\x3c\142\162\40\57\76\xa"; } }
