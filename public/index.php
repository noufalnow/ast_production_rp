<?php
$_mimeTypes = array(
        //'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
		'css' => 'css',
		'js' => 'js',
		'gif' => 'gif',
		'eot' => 'eot',
		'woff' => 'woff',
		'ttf' => 'ttf',
		'woff2' => 'woff2',

);

$_path         = $_SERVER['REQUEST_URI'];
$_extension    = pathinfo($_path, PATHINFO_EXTENSION);
$_canonicalUrl = 'http://' . $_SERVER['HTTP_HOST'] .$_path;
@$_type         = $_mimeTypes[$_extension];

$_file = ltrim($_path,'//');


//if under some folder check access

// Verify that the file exists and is readable, or send 404
if (is_readable($_file) && $_type ) {
    header('Content-Type: ' . $_type);
    header('Link <' . $_canonicalUrl . '>; rel="canonical"');
    readfile(realpath($_file));
    exit(0);
}



error_reporting ('0');

ini_set ( 'display_errors', 0 );
//echo "<pre>";

//print_r($_SERVER);



//print_r($_data);
//development
include "../lib/templator/handler.php";
//live
//include "../../c19nri/lib/templator/handler.php";


_run($_SERVER['REQUEST_URI']);



?>