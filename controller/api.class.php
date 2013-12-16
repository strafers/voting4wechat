<?php
if (!defined('IN'))
    die('bad request');
include_once (AROOT . 'controller' . DS . 'app.class.php');

define('LR_API_SYSTEM_EXCEPTION', 10000);
define('LR_API_TOKEN_ERROR', 10001);
define('LR_API_DB_EMPTY_RESULT', 10002);
define('LR_API_DB_EMPTY_DATA', 10003);
define('LR_API_ARGS_ERROR', 10004);
define('LR_API_PARAM_ILLEG', 10005);

define('LR_API_USER_VOTE_MAX', 10006);
define('LR_API_VOTE_WORK_EXIST', 10007);
define('LR_API_WORK_NOT_EXIST', 10008);


class apiController extends appController
{

    function __construct()
    {
        parent::__construct();

        //$this->check_token();
    }

    /**
     * 获取排行前十的项目
     *
     * @param string
     * @return array
     */
    public function get_top_vote()
    {
        $data = get_top_vote_works();
        if(is_array($data))
            return self::send_result($data);
        else
            return self::send_error(LR_API_DB_EMPTY_DATA, '空数据');

    }

    /**
     * 发起一个投票
     *
     * @param string openid,workid
     * @return array
     */

    public function do_vote()
    {
        $openId = z(t(v('openid')));
        $workId = z(t(v('workid')));

        //检测请求参数是否为空
        if (!not_empty($openId) || !not_empty($workId))
            return self::send_error(LR_API_DB_EMPTY_RESULT, '请求的参数不能为空');

        //判断用户投的项目是否存在
        $workExist = work_is_exist($workId);
        if(!$workExist)
            return self::send_error(LR_API_WORK_NOT_EXIST, '您好，您输入的展位编号有误，请确认后重新输入。');

        //检测用户投票数是否大于10
        $voteCount = get_user_vote_count($openId);
        if($voteCount>=10)
            return self::send_error(LR_API_USER_VOTE_MAX, '抱歉，您当前剩余的票数为0，无法完成此次投票');

        //检测用户是否已经为该项目投过票
        $bResult = vote_work_exist($openId,$workId);
        if($bResult)
            return self::send_error(LR_API_VOTE_WORK_EXIST, '您已经为该展位的团队投过票了');

        //进行投票操作
        $bResult = false;

        //插入log表
        if($bResult = insert_vote($openId,$workId)){
            //插入work表
            if($bResult = update_work_vote($workId)){
                $voteCount = get_user_vote_count($openId);
                return self::send_result($voteCount);
            }
        }
        if(!$bResult)
            return self::send_error(LR_API_SYSTEM_EXCEPTION, '抱歉，系统发生未知故障！');

    }

    /**
     * 获取用户剩余票数
     *
     * @param string openid,workid
     * @return array
     */
    function get_user_vote_count()
    {
        $openId = z(t(v('openid')));

        //检测请求参数是否为空
        if (!not_empty($openId))
            return self::send_error(LR_API_DB_EMPTY_RESULT, '请求的参数不能为空');

        $voteCount = get_user_vote_count($openId);

        return self::send_result($voteCount);
    }

    /**
     * 进行抽奖
     *
     * @param string
     * @return array
     */
    public function rand_lucky()
    {
        $lucky = rand_lucky_man();
        if(is_array($lucky)){
            //获取中奖者信息
            $user = get_weixin_user_info($lucky["openId"]);

            //标示中奖者
            $bResult = update_luck_man($lucky["openId"],$user["nickname"]);

            //推送中奖信息
            $msg = c("weixin_lucky_msg");

            if(c("wxPush")){
                $bResult = send_weixin_msg($lucky["openId"],$msg);
            }

            if ($bResult) {
                return self::send_result($user["nickname"]);
            }else{
                return self::send_error(LR_API_SYSTEM_EXCEPTION, '抱歉，系统发生未知故障！');
            }
        }
        else{
            return self::send_error(LR_API_SYSTEM_EXCEPTION, '抱歉，系统发生未知故障！');
        }
    }

    /**
     * 拿到中奖者名单
     *
     * @param string
     * @return array
     */
    public function lucky_list()
    {
        $luckyList = get_lucky_list();
        if(is_array($luckyList)){
            return self::send_result($luckyList);
        }
        else{
            return self::send_error(LR_API_DB_EMPTY_DATA, '空数据');
        }
    }

    /*
    * ignore
    */
    private function check_token()
    {
        //TODO api调用来源验证
    }

    /*
    * ignore
    */
    public static function send_error($number, $msg)
    {
        $obj = array();
        $obj['err_code'] = intval($number);
        $obj['err_msg'] = $msg;
        if (g('API_EMBED_MODE') == 1)
            return json_encode($obj);
        else {
            header('Content-type: application/json');
            die(json_encode($obj));
        }
    }

    /*
    * ignore
    */
    public static function send_result($data)
    {
        $obj = array();
        $obj['err_code'] = 0;
        $obj['err_msg'] = 'success';
        $obj['data'] = $data;

        if (g('API_EMBED_MODE') == 1)
            return json_encode($obj);
        else {
            header('Content-type: application/json');
            die(json_encode($obj));
        }
    }
}
