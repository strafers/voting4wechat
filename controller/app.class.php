<?php
if (!defined('IN'))
    die('bad request');
include_once (CROOT . 'controller' . DS . 'core.class.php');

class appController extends coreController
{
    function __construct()
    {
        // 载入默认的
        parent::__construct();

  //       if( g('c') != 'api' )
		// {
		// 	// set session time
		// 	//session_set_cookie_params( c('session_time') );
		// 	@session_start();
		// }
    }

    function check_superadmin()
    {
        $username="admin";
        $password="123456";
        if ($_SERVER['PHP_AUTH_USER']!= $username || $_SERVER['PHP_AUTH_PW'] != $password)
        {
            header('WWW-Authenticate: Basic realm="Hellotianma Login"');
            header('HTTP/1.1 401 Unauthorized');
            die('用户名或者密码无效，无法登录！');
        }
    }

}


?>