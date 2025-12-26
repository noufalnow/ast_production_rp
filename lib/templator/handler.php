<?php

function _run($_req)
{
    $_data = explode('?', $_req);

    $_data = explode('/', $_data[0]);

    date_default_timezone_set('Asia/Muscat');
    error_reporting('0');
    // strtotime('2019-04-28 08:00:00');
    define('CONT_TYPEC', 1556424000);

    $_request = array();
    $_param = array();

    $appstruct = array();

    // echo "from ren <br>";

    $_i = 1;
    $j = 0;

    $_default['module'] = 'default';
    $_default['controller'] = 'default';
    $_default['action'] = 'index';

    $_login['module'] = 'default';
    $_login['controller'] = 'default';
    $_login['action'] = 'login';

    $_request = $_default;

    if (! empty($_data[$_i])) {
        $_request['module'] = filter_var($_data[$_i], FILTER_UNSAFE_RAW);
        $_i ++;
    }

    if (! empty($_data[$_i])) {
        $_request['controller'] = filter_var($_data[$_i], FILTER_UNSAFE_RAW);
        $_i ++;
    }

    if (! empty($_data[$_i])) {
        $_request['action'] = filter_var($_data[$_i], FILTER_UNSAFE_RAW);
        $_i ++;
    }

    include __DIR__ . "/../../application/boot.php";

    /*
     * session_start();
     * s( $_SESSION);/*
     * unset($_SESSION['user_name']);
     * session_destroy();
     * //header("Location: login.php");
     * exit;
     */

    /* -------------- */
    // CONT_TYPR > CONT_TYPEC ? $x = 0 : $x = 1;
    // if (! $x)
    // return $x;
    /* -------------- */

    if ($_request === $_default)
        if (isset($_SESSION['user_name'])) {
            header("Location:" . APPURL . "default/default/dashboard");
        }

    if ($_request['module'] === 'login') {
        $_request['module'] = 'default';
        $_request['controller'] = 'default';
        $_request['action'] = 'login';
    }
    // s ( $_SESSION, $_request, $_default );
    if (! isset($_SESSION['user_name'])) {

        /**
         * log non authenticated access *
         */

        $log = array(
            date(DFF_DB, $_SERVER['REQUEST_TIME']),
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['HTTP_REFERER']
        );
        logger('nonauth', $log);
        // a ( $_SESSION, $_request, $_default,$_login);

        if ($_request === $_login || $_request['module'] === 'login') {
            $_request['module'] = 'default';
            $_request['controller'] = 'default';
            $_request['action'] = 'login';
        } elseif ($_request['module'] === 'analysis') {
            $_request['module'] = 'default';
            $_request['controller'] = 'default';
            $_request['action'] = 'analysis';
        } elseif ($_request !== $_default) {

            header("Location:" . APPURL);
        }
    } /*elseif ($_SESSION['user_id'] == 1) { 

        $_SESSION['EVNT_DEVP'] = TRUE;

        define('_REQUEST', $_request['module'] . "/" . $_request['controller'] . "/" . $_request['action']);
        $log = array(
            date(DFF_DB, $_SERVER['REQUEST_TIME']),
            $_SESSION['user_name'] . '-' . $_SESSION['user_log_id'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['HTTP_REFERER']
        );
        logger('authosised', $log);

        // v('Admin access');
        
    } */ else {

        // check here or on bbefore from the globel list whether the request exist

        if (isset($_SESSION['user_acl'][$_request['module'] . '/' . $_request['controller'] . '/' . $_request['action']])) {

            define('_REQUEST', $_request['module'] . "/" . $_request['controller'] . "/" . $_request['action']);

            /**
             * log authosised access *
             */
            // s($_SERVER);

            $log = array(
                date(DFF_DB, $_SERVER['REQUEST_TIME']),
                $_SESSION['user_name'] . '-' . $_SESSION['user_log_id'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['REQUEST_URI'],
                $_SERVER['HTTP_USER_AGENT'],
                $_SERVER['HTTP_REFERER']
            );
            logger('authosised', $log);
        } else {

            $log = array(
                date(DFF_DB, $_SERVER['REQUEST_TIME']),
                $_SESSION['user_name'] . '-' . $_SESSION['user_log_id'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['REQUEST_URI'],
                $_SERVER['HTTP_USER_AGENT'],
                $_SERVER['HTTP_REFERER']
            );
            logger('nonauthosised', $log);

            header("Location:" . APPURL . "default/default/popupclose");
            return; // @todo access denied page

        /**
         * log not authosised access *
         */
        }
    }

    if (count($_GET) > 0)
        foreach ($_GET as $key => $value) {
            $_param[$key] = $value;
        }

    if (count($_POST) > 0)
        foreach ($_POST as $key => $value) {
            $_param[$key] = $value;
        }

    if (! empty($_data[$_i])) {
        $_param[$_data[$_i]] = filter_var($_data[$_i + 1], FILTER_UNSAFE_RAW);
        $_GET[$_data[$_i]] = $_param[$_data[$_i]];
        $_i ++;
        $_i ++;

    }

    if (! empty($_data[$_i])) {
        $_param[$_data[$_i]] = filter_var($_data[$_i + 1], FILTER_UNSAFE_RAW);
        $_GET[$_data[$_i]] = $_param[$_data[$_i]];
        $_i ++;
        $_i ++;
    }

    if (! empty($_data[$_i])) {
        $_param[$_data[$_i]] = filter_var($_data[$_i + 1], FILTER_UNSAFE_RAW);
        $_GET[$_data[$_i]] = $_param[$_data[$_i]];
        $_i ++;
        $_i ++;
    }
    
    if (! empty($_data[$_i])) {
        $_param[$_data[$_i]] = filter_var($_data[$_i + 1], FILTER_UNSAFE_RAW);
        $_GET[$_data[$_i]] = $_param[$_data[$_i]];
        $_i ++;
        $_i ++;
    }
    
    if (! empty($_data[$_i])) {
        $_param[$_data[$_i]] = filter_var($_data[$_i + 1], FILTER_UNSAFE_RAW);
        $_GET[$_data[$_i]] = $_param[$_data[$_i]];
        $_i ++;
        $_i ++;
    }
    
    

    include __DIR__ . "/../../application/" . $_request['module'] . "/" . $_request['controller'] . ".php";

    $_controller = $_request['controller'] . 'Controller';
    $_controller = new $_controller();
    $_controller->view->param = $_param;
    $_phtml = "/../../application/" . $_request['module'] . '/' . $_request['controller'] . '/' . $_request['action'];
    $_action = $_request['action'] . 'Action';
    $_controller->$_action();

    // if (! $_controller->view->NoViewRender){
    $_controller->show($_phtml);
    // }
}

function viewAction($resource = array())
{
    $_controller = $resource['controller'] . 'Controller';

    // if (!class_exists($_controller)) {
    require_once __DIR__ . "/../../application/" . $resource['module'] . "/" . $resource['controller'] . ".php";

    // }
    $_controller = new $_controller();

    $_controller->view->param = $resource['param'];
    $_controller->view->response('VA');
    $_phtml = "/../../application/" . $resource['module'] . '/' . $resource['controller'] . '/' . $resource['action'];
    $_action = $resource['action'] . 'Action';
    $_controller->$_action();

    $_controller->show($_phtml);
}

function logger($file, $log)
{
    $fp = fopen(__DIR__ . '/../../logs/' . $file . '.csv', 'a');
    fputcsv($fp, $log);
    fclose($fp);
}

class mvc
{

    public $_val = "common for mvc";

    public $form;

    public $view;

    public $NoViewRender;
    
    public $template;

    public function __construct()
    {
        $this->view = new view();
        $_POST = $this->sanitize($_POST);
    }

    public function show($action)
    {
        // $this->view->crypt = new encryption ();
        $this->view->show($action);
    }

    private function sanitize($struct)
    {
        $gumpf = new GUMP();
        return $gumpf->sanitize($struct);
    }
}

class view extends viewbase
{

    public $form;

    public $crypt;

    public $response;

    public function response($response = '')
    {
        if ($this->response == '') {
            $this->response = $response;
        }
    }

    public function show($action)
    {
        if (! $this->NoViewRender) {
            if ($this->response == 'login') {
                require_once __DIR__ . "/../../application/mask/login/headder.php";
            } else if ($this->response == 'window') {
                require_once __DIR__ . "/../../application/mask/sidebar/popheadder.php";
            } else if ($this->response == 'mheadder') {
                require_once __DIR__ . "/../../application/mask/sidebar/mheadder.php"; // added for mh no footer added
            } elseif ($this->response == 'ajax') {} elseif ($this->response == 'plot') {
                require_once __DIR__ . "/../../application/mask/visualizer/headder.php";
            } elseif (! $this->response) {
                require_once __DIR__ . "/../../application/mask/sidebar/new_headder.php";
            }

            
            if($this->template)
                $action = __DIR__ . $action ."/". $this->template . ".phtml";
            else    
                $action = __DIR__ . $action . ".phtml";
            
             
            // check readable then include

            if (! file_exists($action)) {
                echo "No File";
            }

            //require $action;
            if ($this->response == 'ajax') {
                $view['view'] = '';
                // if no redirect
                if (empty($this->url)) {
                    try {
                        ob_start();
                        require $action;
                        $view['view'] = ob_get_clean();
                    } catch (\Throwable $e) {
                        ob_end_clean();
                        exception_handler($e);
                    }
                    ob_end_flush();
                }
                $view['status'] = $this->status;
                $view['feedback'] = $this->feedback;
                $view['url'] = $this->url;
                $view['ref'] = $this->ref;
                $view['target'] = $this->target;
                echo json_encode($view);
                die();
            } else {
                echo $this->breadcrumbList;
                include $action;
            }
            if ($this->response == 'window') {
                require_once __DIR__ . "/../../application/mask/sidebar/pop_footer.php";
            } elseif ($this->response == 'ajax') {} elseif ($this->response == 'plot') {
                require_once __DIR__ . "/../../application/mask/sidebar/footer.php";
            } elseif (! $this->response) {
                require_once __DIR__ . "/../../application/mask/sidebar/new_footer.php";
            }
        } else {

            if ($this->response == 'ajax') {
                $view['status'] = $this->status;
                $view['feedback'] = $this->feedback;
                $view['url'] = $this->url;
                $view['ref'] = $this->ref;
                $view['target'] = $this->target;
                echo json_encode($view);
                die();
            }
        }
    }
}

class viewbase
{

    private $lkey = "198464531574923619132654";

    private $rkey = "561982317921683535161316";

    private function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array(
            '+',
            '/',
            '='
        ), array(
            '-',
            '$',
            ''
        ), $data);
        return $data;
    }

    private function safe_b64decode($string)
    {
        $data = str_replace(array(
            '-',
            '$'
        ), array(
            '+',
            '/'
        ), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value)
    {
        if(!empty($value))
        return self::encoder($value, $this->lkey) . "_" . self::encoder($value, $this->rkey);
    }

    public function semiencode($value)
    {
        if(!empty($value))
        return self::encoder($value, $this->lkey);
    }

    public function decode($value)
    {
        if(!empty($value)){
        $parts = explode("_", $value);
        $decode = self::decoder($parts[0], $this->lkey);
        if ($decode == self::decoder($parts[1], $this->rkey))
            return $decode;
        else
            false;
        }
    }

    private function encoder($string, $key)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $key;
        $secret_iv = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    private function decoder($string, $key)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $key;
        $secret_iv = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }

    /*
     * private function encoder1($value, $key) {
     * if (! $value) {
     * return false;
     * }
     * $text = $value;
     * $iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
     * $iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
     * $crypttext = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv );
     * return trim ( $this->safe_b64encode ( $crypttext ) );
     * }
     * private function decoder1($value, $key) {
     * if (! $value) {
     * return false;
     * }
     * $crypttext = $this->safe_b64decode ( $value );
     * $iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
     * $iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
     * $decrypttext = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv );
     *
     * return trim ( $decrypttext );
     * }
     */
    public function breadcrumb($crumb = array())
    {
        $crumbHtml = ('<ul id="crumbs" style="margin-left: 3%; padding-top: 0px; margin-bottom: 0px;">');
        foreach ($crumb as $link => $label) {
            if ($link)
                $crumbHtml .= ('<li><a href="' . APPURL . $link . '">' . $label . '</a></li>');
            else
                $crumbHtml .= ('<li>' . $label . '</li>');
        }
        $crumbHtml .= ('</ul>');
        $this->breadcrumbList = $crumbHtml;
    }
}

function userActivityLog($type = '', $refId = "", $logdata = '', $tbl = '')
{
    require_once __DIR__ . "/../../application/admin/!model/actionlog.php";
    $actionLogObj = new actionlog();
    $data['alog_user_log_id'] = $_SESSION['user_log_id'];
    $data['alog_module'] = _REQUEST;
    $data['alog_table'] = $tbl;
    if ($logdata)
        $data['alog_data'] = json_encode($logdata); // "{".@implode(',',$logdata)."}";
    if ($type)
        $data['alog_action'] = $type;
    if ($refId)
        $data['alog_ref'] = $refId;
    $actionLogObj->add($data);
}

?>