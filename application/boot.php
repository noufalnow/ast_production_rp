<?php

// wixel/gump
// require 'vendor/autoload.php';

// ini_set('session.cookie_secure', true);
// ini_set('session.cookie_httponly', true);
// ini_set('session.use_only_cookies', true);
// ini_set('session.use_strict_mode', true);
session_start();
if (file_exists(__DIR__ . '/../../../formatter/init.php'))
    require_once __DIR__ . '/../../../formatter/init.php';

if (isset($_SESSION['user_id']))
    define('USER_ID', $_SESSION['user_id']);

if (isset($_SESSION['user_type']))
    define('USER_GROUP', $_SESSION['user_type']);

if (isset($_SESSION['ubr_branch']))
    define('USER_BRANCHID', $_SESSION['ubr_branch']);
define('APPURL', "http://" . $_SERVER['HTTP_HOST'] . '/');
define('UPLOADSURL', "http://" . $_SERVER['HTTP_HOST'] . "/uploads/");
define('IMAGEURL', "http://" . $_SERVER['HTTP_HOST'] . "/default/default/displayimg/ref/");
define('PAGINATION_LIMIT', 25);
// require_once __DIR__ . '/../lib/gump.class.php';
require_once __DIR__ . '/../lib/form.php';
require_once __DIR__ . '/../lib/db_table.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_mail($message)
{
    
    print_r((array)$message); die();
    
    if (defined('USER_ID')) {

        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/SMTP.php';
        require __DIR__ . '/../lib/vendor/autoload.php';
        $mail = new PHPMailer(true);
        try {

            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = 'creativeboard.net'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'info@creativeboard.net'; // SMTP username
            $mail->Password = 'huIV)p4BVRqG'; // SMTP password

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Recipients
            $mail->setFrom('info@creativeboard.net', 'Mailer');
            $mail->addAddress('info@creativeboard.net', 'Noufal S'); // Add a recipient
            $mail->addAddress('noufalnow@gmail.com'); // Name is optional
            $mail->addReplyTo('info@creativeboard.net', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'CSOL LIVE EXEPTION';
            $mail->Body = $message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                $view['view'] = '<div class="modal-content" id="modal-target"><div class="dynamic-popup" id="dynamic-popup"><!--  -->
            	<div class="modal-header">
            		<button type="button" class="close" data-dismiss="modal">×</button>
            		<h4 class="modal-title">Something went wrong!!</h4>
            	</div>
            	<div class="modal-body">
            		<div class="row mb-4" style="margin-top: 15px;">
                        <p style="margin-top: 5px; margin-bottom: 5px;">
                        	<img width="300" src="http://' . $_SERVER['HTTP_HOST'] . '/images/not_available.png" alt="ast" style="padding-top: 6px;  padding-left: 0;">
                        </p>
            
                    </div>	
            	</div>
            	<div class="modal-footer popup-footer">
            		<button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            	</div>
            </div>
            </div>';

                echo json_encode($view);
                die();
            } else
                header("Location:" . APPURL . "default/default/popupclose");

            die();
        } catch (Exception $e) {
            echo $message;
        }
    }
}

define('CONT_TYPR', time());

function objm($s1, $s2)
{
    if ($s2 == '')
        require_once __DIR__ . "/!model/$s1" . '.php';
    else
        require_once __DIR__ . "/$s2/!model/$s1" . '.php';
    return new $s1();
}

define('DF_DB', 'Y-m-d H:i:s');
define('DFS_DB', 'Y-m-d');
define('DF_DD', 'd/m/Y');
define('DFF_DB', 'Y-m-d H:i:s T');

// define constants:
define('DOC_TYPE_MEM', 1);
define('DOC_TYPE_MEM_PHOTO', 100);

// define constants: documents type
define('CAT_TYPE_CONS', 1);
define('CAT_TYPE_VHLS', 2);

// define constants: documents type
define('CONT_TYPE_VENDR', 5);

// define constants: address_type
define('CONT_TYPE_COMP', 1);
define('CONT_TYPE_EMP', 2);
define('CONT_TYPE_PROP', 3);
define('CONT_TYPE_CUST', 4);

// define constants: documents type
define('DOC_TYPE_COMP', 1);
define('DOC_TYPE_EMP', 2);
define('DOC_TYPE_PROP', 3);
define('DOC_TYPE_VHCL', 4);
define('DOC_TYPE_EXP', 5);
define('DOC_TYPE_EXP_UPD', 5001);



define('DOC_TYPE_COM', 2000);
define('DOC_TYPE_COM_EMP', 2001);
define('DOC_TYPE_COM_PROP', 2002);
define('DOC_TYPE_COM_VHL', 2003);
define('DOC_TYPE_COM_AGR', 2004);
define('DOC_TYPE_COM_SAL', 2005);
define('DOC_TYPE_COM_VEN', 2006);
define('DOC_TYPE_COM_COMP', 2007);

define('DOC_TYPE_BILL', 6);
define('DOC_TYPE_PAY', 7);
define('DOC_TYPE_COLL', 8);
define('DOC_TYPE_TKT', 9);
define('DOC_TYPE_TKT_ACT', 10);

define('DOC_TYPE_TNT', 11); //id=>1101, cr=>1102
define('DOC_TYPE_TNT_ID', 1101);
define('DOC_TYPE_TNT_CR', 1102);

define('DOC_IMG_EMP', 100);
define('DOC_IMG_VHL', 1001);
define('DOC_IMG_PROP', 1002);

define('CASH_BOOK_COMP', 1);
define('CASH_BOOK_PER', 2);

// Update type

define('UPD_TYP_EMP', 1);
define('UPD_TYP_PROP', 2);
define('UPD_TYP_VHL', 3);
define('UPD_TYP_INV', 4);
define('UPD_TYP_COMP', 5);

// Cash Demand Type

define('CASHDMD_TYP_PROP', 1);

function accx($link = '', $html = '')
{
    if (isset($_SESSION['user_acl'][$link]) || $_SESSION['EVNT_DEVP'])
        echo $html;
}

function x($param = array(), $alt = '')
{

    // s( $_SESSION ['user_acl'] );
    // s($param);
    /*
     * if($param['branch'] && !in_array($param['branch'], $_SESSION['user_branches'])) //not sure imlpementation hence commented for mh 29/01/2022
     * return;
     */
    if (isset($_SESSION['user_acl'][$param['link']]) || $_SESSION['EVNT_DEVP']) {

        // if(true){

        if (! is_array($param['0']))
            $param['0'] = [];

        if (is_array($param['ref'])) {
            foreach ($param['ref'] as $refk => $refval)
                $ref .= "/$refk/$refval";
        } elseif ($param['ref'])
            $ref .= "/ref/" . $param['ref'];

        $link = APPURL . $param['link'] . $ref;

        if ($param['0']['param'] == 'opener') {
            return '<a href="javascript:;" onclick="getOpener (' . "'" . 'report' . "'" . ',' . "'" . $link . "'" . ');" >' . $param['label'] . '</i></a>';
        } elseif ($param[0]['param'] == 'wide_opener') {
            return '<a href="javascript:;" onclick="getOpener (' . "'" . 'wide' . "'" . ',' . "'" . $link . "'" . ');" >' . $param['label'] . '</i></a>';
        }

        if (@in_array($param[0]['exten'], array(
            'png',
            'jpeg',
            'jpg'
        )))
            return "<a href = '" . $link . "' class='lightbox' >" . $param['label'] . "</a>";
        elseif (@in_array($param[0]['exten'], array(
            'pdf'
        ))) {

            // s($param);

            return '<a href="javascript:;" onclick="getOpener (' . "'" . 'report' . "'" . ',' . "'" . $link . "'" . ');" ><i class="fas fa-search"></i></a>';
        } else
            return "<a " . @$param[0]['param'] . "  href = '" . $link . "'" . @$param['aatr'] . ">" . $param['label'] . "</a>";
    } elseif ($alt)
        return $alt;
}

function href($param = array(), $alt = '')
{
    if ($param['branch'] && $param['branch'] != USER_BRANCHID)
        return;

    if (isset($_SESSION['user_acl'][$param['link']])) {
        return true;
    }
}

function lx($param = array(), $alt = '')
{
    if (! is_array($param['0']))
        $param['0'] = [];

    if (is_array($param['ref'])) {
        foreach ($param['ref'] as $refk => $refval)
            $ref .= "/$refk/$refval";
    } elseif ($param['ref'])
        $ref .= "/ref/" . $param['ref'];

    $link = APPURL . $param['link'] . $ref;

    return "<a href = '" . $link . "' " . @$param[0]['param'] . " " . @$param['aatr'] . " >" . $param['label'] . "</a>";
}

function lb($param = array(), $alt = '')
{
    if (is_array($param['ref'])) {
        foreach ($param['ref'] as $refk => $refval)
            $ref .= "/$refk/$refval";
    } elseif ($param['ref'])
        $ref .= "/ref/" . $param['ref'];

    $link = APPURL . $param['link'] . $ref;

    if ($param[0]['param'] == 'opener') {
        return '<a href="javascript:;" onclick="getOpener (' . "'" . 'report' . "'" . ',' . "'" . $link . "'" . ');" >' . $param['label'] . '</i></a>';
    } elseif ($param[0]['param'] == 'wide_opener') {
        return '<a href="javascript:;" onclick="getOpener (' . "'" . 'wide' . "'" . ',' . "'" . $link . "'" . ');" >' . $param['label'] . '</i></a>';
    }

    if (@in_array($param[0]['exten'], array(
        'png',
        'jpeg',
        'jpg'
    )))
        return "<a href = '" . $link . "' class='lightbox' >" . $param['label'] . "</a>";
    elseif (@in_array($param[0]['exten'], array(
        'pdf'
    ))) {

        // s($param);

        return '<a href="javascript:;" onclick="getOpener (' . "'" . 'report' . "'" . ',' . "'" . $link . "'" . ');" ><i class="fas fa-search"></i></a>';
    } else
        return "<a " . @$param[0]['param'] . "  href = '" . $link . "'" . @$param['aatr'] . ">" . $param['label'] . "</a>";
}

function error_handler($errno = null, $errstr = null, $errfile = null, $errline = null, $errcon = null)
{
    if ($errno != 2 && $errno != 8) {
        /**
         * ** error array **
         */
        // echo fa ( array_filter ( $errcon ) );
        // error_log("Error: [$errno] $errstr",1,"someone@example.com","From: webmaster@example.com");
        if (! (error_reporting() & $errno))
            return;
        switch ($errno) {
            case E_WARNING:
            case E_USER_WARNING:
            case E_STRICT:
            case E_NOTICE:
            case E_USER_NOTICE:
                $type = 'warning';
                $fatal = false;
                break;
            default:
                $type = 'fatal error';
                $fatal = true;
                break;
        }
        $trace = array_reverse(debug_backtrace());

        // s($trace);

        $erstring = '';

        array_pop($trace);
        if (php_sapi_name() == 'cli') {
            $erstring .= 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
            foreach ($trace as $item)
                $erstring .= '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
        } else {
            $erstring .= '<p class="error_backtrace">' . "\n";
            $erstring .= '  Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
            $erstring .= '  <ol>' . "\n";
            foreach ($trace as $item)
                $erstring .= '    <li>' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()</li>' . "\n";
            $erstring .= '  </ol>' . "\n";
            $erstring .= '</p>' . "\n";
        }
        if (ini_get('log_errors')) {
            $items = array();
            foreach ($trace as $item)
                $items[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()';
            $message = 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ': ' . join(' | ', $items);
            error_log($message);
            // mail("info@creativeboard.net","cSolAst_Exeptions",$message .'<br>'. $erstring);

            send_mail($erstring);
        }
        echo $erstring;
    }
}

function exception_handler($exception, $query = '', $data = array())
{
    $vv = fa($exception->getMessage());
    $vv .= fa($exception->__toString());
    $vv .= fa($query);
    $vv .= fa($data);
    foreach ($data as $k => $v) {
        $data[$k] = $v;
        $bind[] = ":" . $k;
    }

    if ($data)
        $fString = str_replace($bind, $data, $query);

    $vv .= fa($fString);

    error_reporting(- 1);
    ini_set('display_errors', 'On');
    set_error_handler("var_dump");
    send_mail($vv);

    die();
}

// s(get_defined_vars());
function shutdown_handler()
{
    // $crypt->decode("sdsds");

    /*
     *
     * //s(get_defined_vars());
     * foreach(get_defined_vars() as $k=>$y){
     * if( !in_array( $k,
     * array(
     * '_ENV',
     * '_SESSION',
     * '_COOKIE',
     * 'HTTP_SESSION_VARS',
     * 'HTTP_COOKIE_VARS'
     * )))
     * { $$k=null; unset($$k);}
     * unset($y, $k);
     * }
     */
}

set_error_handler("error_handler");
set_exception_handler("exception_handler");
// register_shutdown_function("shutdown_handler");
// trigger_error("Value must be 1 or below",E_USER_WARNING);

register_shutdown_function("shutdown_function");

function shutdown_function()
{
    $errfile = "unknown file";
    $errno = E_CORE_ERROR;
    $errline = 0;
    $error = error_get_last();

    if ($error['type'] === E_ERROR) {
        $log['type'] = $error["type"];
        $log['file'] = $error["file"];
        $log['line'] = $error["line"];
        $log['message'] = $error["message"];
        $dateTime = new DateTime();
        $log['start'] = $dateTime->format('Y-m-d H:i:s');
        echo fa($log);
    }
}

function fa()
{
    $str = '';
    $arg_list = func_get_args();

    foreach ($arg_list as $arg) {
        $str .= faset($arg);
    }
    return $str;
}

function faset($array = array())
{
    $array = ("<pre><span id='mdbg'><span class='debug_f'>" . print_r($array, true) . "</span></span></pre>");
    return str_replace(array(
        "[",
        "]",
        "=>"
    ), array(
        "<span class='a'><span class='sb'>[</span>",
        "<span class='sb'>]</span></span>",
        "<span class='arrow'>=></span>"
    ), $array);
}

function send_email($message='',$to='',$cc='')
{
    
        
        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../lib/vendor/PHPMailer/src/SMTP.php';
        require __DIR__ . '/../lib/vendor/autoload.php';
        $mail = new PHPMailer(true);
        try {
            
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = 'astglobal.om'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'info@astglobal.om'; // SMTP username
            $mail->Password = 'EotgYWN7b_$Y'; // SMTP password
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
            // Recipients
            $mail->setFrom('info@astglobal.om', 'AST Global');
            
            $mail->addAddress($to); // Name is optional
            if($cc)
                $mail->addCC($cc); // Add a recipient
            
            $mail->addReplyTo('info@astglobal.om', 'Ast Global');
            
            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name
            
            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'AST Global Monthly Remiander Mail';
            $mail->Body = $message;
            $mail->AltBody = $message;
            
            return $mail->send();
        
            } catch (Exception $e) {
                //echo $message;
            }

    }

?>