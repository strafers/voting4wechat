<?php
define(LOG_TABLE, 'cxg_votelog');
define(USER_TABLE, 'cxg_weixiner');
define(WORKS_TABLE, 'cxg_works');
define(PHONE_TABLE, 'cxg_phone');
/**
 * log 投票记录数据表操作函数
 *
 */
function vote_work_exist($openId,$workId)
{
	$sqlArr = array();
	$sqlArr['where'] = "`openId` = '$openId' and `workId` = '$workId'";
	$data = select(LOG_TABLE, $sqlArr);
	return (is_array($data)&&$data["openId"] == $openId) ? true : false;
}

function insert_vote($openId,$workId)
{
	$dataArr = array();
	$dataArr['openId'] = $openId;
	$dataArr['workId'] = $workId;
	$dataArr["creatTime"] = date("Y-m-d H:i:s");

	$bRusult = insert(LOG_TABLE, $dataArr);
	return ($bRusult) ? true : false;
}
function get_user_vote_count($openId)
{
	$sqlArr['param'] = "count(*)";
	$sqlArr['where'] = "`openId` = '$openId'";
	$data = select(LOG_TABLE, $sqlArr);
	if(is_array($data))
		return $data["count(*)"];
}


function get_lucky_list()
{
	$sqlArr = array();
	$sqlArr['param'] = "weixinName";
	$sqlArr['where'] = "`isLucky` = '1' and `weixinName` != ''";
	$sqlArr['order'] = "`luckyTime` DESC";
	$data = select(LOG_TABLE, $sqlArr,1);
	if(!empty($data))
		return unique_arr($data);
	else
		return false;
}
function rand_lucky_man()
{
	do{
		//随机拿一条
		$sql = "SELECT * FROM `cxg_votelog` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `cxg_votelog`)-(SELECT MIN(id) FROM `cxg_votelog`))+(SELECT MIN(id) FROM `cxg_votelog`)) AS id) AS t2 WHERE t1.isLucky = 0 and t1.id >= t2.id ORDER BY t1.id LIMIT 1";
		$data = get_one($sql);
	}while(is_lucky_man($data["openId"]));

	return $data;
}

function is_lucky_man($openId)
{
	$sqlArr = array();
	$sqlArr['where'] = "`openId` = '$openId' and `isLucky` = '1'";
	$data = select(LOG_TABLE, $sqlArr);

	if(is_array($data)&&$data["openId"] == $openId)
	{
		$user = get_weixin_user_info($openId);
		$bResult = update_luck_man($data["openId"],$user["nickname"]);
		return true;
	}else{
		return false;
	}
}

function update_luck_man($openId,$weixinName)
{
	$bRusult = false;
	$dataArr = array();

	$dataArr["weixinName"] = $weixinName;
	$dataArr["isLucky"] = "1";
	$dataArr["luckyTime"] = date("Y-m-d H:i:s");

    $condition = "`openId` = '$openId'"; //条件
    $bRusult = update(LOG_TABLE, $dataArr, $condition);

	return $bRusult;
}

/**
 * work 作品数据表操作函数
 *
 */
function work_is_exist($workId)
{
	$sqlArr = array();
	$sqlArr['where'] = "`workId` = '$workId'";
	$data = select(WORKS_TABLE, $sqlArr);
	return (is_array($data)&&$data["workId"] == $workId) ? true : false;
}

function get_work_vote_count($workId)
{
	$sqlArr['param'] = "voteCount";
	$sqlArr['where'] = "`workId` = '$workId'";
	$data = select(WORKS_TABLE, $sqlArr);
	if(is_array($data))
		return $data['voteCount'];
}

function update_work_vote($workId)
{
	$bRusult = false;
	$dataArr = array();

	$oldCount = get_work_vote_count($workId);
	$dataArr["voteCount"] = $oldCount + 1;

    $condition = "`workId` = '$workId'"; //条件
    $bRusult = update(WORKS_TABLE, $dataArr, $condition);

	return $bRusult;
}

function get_top_vote_works($num=10)
{
	$sqlArr = array();
	$sqlArr['where'] = "`isShow` = '1'";
	$sqlArr['order'] = "`voteCount` DESC";
	$sqlArr['limit'] = "$num";
	$data = select(WORKS_TABLE, $sqlArr,1);
	if(is_array($data))
		return $data;
}
/**
 * phone 作品随机数据表操作函数
 *
 */

function get_phone_list($num = 50)
{
	$sqlArr = array();
	$sqlArr['param'] = "phoneNum";
	$sqlArr['limit'] = "$num";
	$sqlArr['order'] = "`id` DESC";
	$data = select(PHONE_TABLE, $sqlArr,1);
	if(is_array($data))
		return $data;
}

function rand_lucky_phone()
{
	//随机拿一条
	$sql = "SELECT * FROM `cxg_phone` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `cxg_phone`)-(SELECT MIN(id) FROM `cxg_phone`))+(SELECT MIN(id) FROM `cxg_phone`)) AS id) AS t2 WHERE t1.isLucky = 0 and t1.id >= t2.id ORDER BY t1.id LIMIT 1";
	$data = get_one($sql);

	return $data;
}

function update_luck_phone($phone)
{
	$bRusult = false;
	$dataArr = array();

	$dataArr["isLucky"] = "1";

    $condition = "`phoneNum` = '$phone'"; //条件
    $bRusult = update(PHONE_TABLE, $dataArr, $condition);

	return $bRusult;
}
?>
