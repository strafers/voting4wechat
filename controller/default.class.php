<?php
if (!defined('IN')) die('bad request');
include_once (AROOT . 'controller' . DS . 'app.class.php');

class defaultController extends appController
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $params = array();
        $data = array();

        if ($content = send_request('get_top_vote', $params)) {
            $jsonData = json_decode($content, 1);
            if ($jsonData['err_code'] == 0) {
                $data['showData'] = $jsonData['data'];
            } else {
                //
            }
        }
         $data['js'] = array(
            'jquery.min.js',
            'jquery.enumerable.js',
            'jquery.tufte-graph.js',
            'raphael.js'
         );
         $data['css'] = array(
            'style.css'
         );
         render($data,'web');
    }

    function rand_api()
    {
        $params = array();
        $data = array();

        if ($content = send_request('rand_lucky', $params)) {
            $jsonData = json_decode($content, 1);
            if ($jsonData['err_code'] == 0) {
                die(json_encode($jsonData['data']));
            } else {
                //
            }
        }
    }

    function rand_page()
    {
        //这里开启页面的Basic 认证
        //$this -> check_superadmin();

        $params = array();
        $data = array();
        if ($content = send_request('lucky_list', $params)) {
            $jsonData = json_decode($content, 1);
            if ($jsonData['err_code'] == 0) {
                if(is_array($jsonData["data"]))
                    $data["luckyList"] = $jsonData["data"];
                else
                    $data["luckyList"] = array();
            } else {
                //
            }
        }

        $data['js'] = array(
            'jquery.min.js'
        );
        $data['css'] = array(
           'style.css'
        );

        render($data,'web','rand');
    }

    function user_list()
    {
        $user = file("data/wxName.txt");
        die(json_encode($user));
    }

    function test()
    {

    }

}