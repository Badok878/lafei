<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 游戏模块
 */
class GameController extends HomeController
{
    public $mus = [];
    public $openSet = [
        'types' => [1,16,6,20,43,44],
        'time_at' =>[
            1 => ['start'=>'02:00:00','end' =>'10:00:00','tj' =>'and'],
            16 => ['start'=>'09:00:00','end' =>'22:00:00','tj' =>'or'],
            6 => ['start'=>'09:00:00','end' =>'23:00:00','tj' =>'or'],
            20 => ['start'=>'09:00:00','end' =>'23:57:00','tj' =>'or'],
            43 => ['start'=>'02:00:00','end' =>'09:00:00','tj' =>'and'],
            44 => ['start'=>'08:00:00','end' =>'23:00:00','tj' =>'or'],
        ],
        'titles' => [
            1 => '重庆时时彩投注时间为每日的 09:00 - 次日02:00',
            16 => '江西11选5投注时间为每日的 09:00 - 22:00',
            6 => '广东11选5投注时间为每日的 09:00 - 23:00',
            20 => '北京PK拾投注时间为每日的 09:00 - 23:57',
            43 => '腾讯分分彩投注时间为每日的 09:00 - 次日02:00',
            44 => '山东11选5投注时间为每日的 08:00 - 23:00',
        ]
    ];
    public  $stop_times = [
        '36' => 6,
        '5' => 6,
        '43' => 6,
        '1' => 30,
        '38' => 6,
        '39' => 6,
        '16' => 30,
        '15' => 30,
        '6' => 30,
        '20' => 30,
        '34' => 6,
        '44' => 30,
        '45' => 6,
        '9' => 7200,
        '10' => 7200,
    ];
    public function game($type = null, $groupId = null, $played = null)
    {
        $played = I('played');
        if (I('type')) {
            $this->type = I('type');
        }
        if (I('groupId')) {
            $this->groupId = I('groupId');
        } else {
            // 默认进入三星玩法
            $this->groupId = 6;
        }

        $lastNo = $this->getGameLastNo($this->type);
        //$this->getValue("select data from {$this->prename}data where type={$this->type} and number='{$lastNo['actionNo']}'");
        $return = M('data')->where(array('type' => $this->type, 'number' => $lastNo['actionNo']))->field('data')->find();
        $kjHao  = $return['data'];
        if ($kjHao) {
            $kjHao = explode(',', $kjHao);
        }

        $actionNo   = $this->getGameNo($this->type);
        $types      = $this->getTypes();
        $kjdTime    = $types[$this->type]['data_ftime'];
        $diffTime   = strtotime($actionNo['actionTime']) - $this->time - $kjdTime;
        $kjDiffTime = strtotime($lastNo['actionTime']) - $this->time;
//        $type_arr = array(1,3,12,6,16,20,24);
//        if(in_array($this->type,$type_arr)){
//            $diffTime = $diffTime-$this->stop_time; // 提前 60秒 封停投注
//        }
        $this->assign('type', $this->type);
        $this->assign('groupId', $this->groupId);
        $this->assign('types', $types);
        $this->assign('actionNo', $actionNo);
        $this->assign('lastNo', $lastNo);
        $this->assign('kjHao', $kjHao);
        $this->assign('kjdTime', $kjdTime);
        $this->assign('diffTime', $diffTime);
        $this->assign('kjDiffTime', $kjDiffTime);

        $history = M('data')->where(array('type' => $this->type))->order('time desc,number desc')->limit(4)->field('time, number, data')->select();

        $this->assign('history', $history);

        $groups = $this->getGroups();
        header("Content-type: text/html; charset=utf-8");
//        var_dump($groups);
        $this->assign('groups', $groups);

        $this->getSystemSettings();
        $this->assign('settings', $this->settings);

        $playeds = $this->getPlayeds();
        $this->assign('playeds', $playeds);

        if (!$played) {
            $playeds2 = array();
            $i        = 0;
            foreach ($playeds as $play) {
                if ($play['groupId'] == $this->groupId && $play['enable'] == 1) {
                    $playeds2[$i] = $play;
                    $i++;
                }
            }
            $played = $playeds2[0]['id'];
        }

        //dump($played);
        if ($played) {
            $this->played = $played;
        }

        $this->assign('playedId', $this->played);

        $maxPl = $this->getPl($this->type, $played);
        $this->assign('maxPl', $maxPl);

        //$sql="select * from {$this->prename}bets where uid={$_SESSION['user']['uid']} order by id desc limit 7";
        // $order_list=M('bets')->where(array('uid'=>$this->user['uid']))->limit(7)->order('id desc')->select();
        //
        // $this->assign('order_list', $order_list);

        $this->assign('time', $this->time);

        $this->display();
    }

    final public function group($type, $groupId)
    {
        $this->playeds = $this->getPlayeds();
        $this->type    = $type;
        $this->groupId = $groupId;

        $playeds2 = array();
        $i        = 0;
        foreach ($this->playeds as $play) {
            if ($play['groupId'] == $groupId && $play['enable'] == 1) {
                $playeds2[$i] = $play;
                break;
                $i++;
            }
        }
        //print_r($this->playeds);exit;
        //        var_dump($playeds2);
        $playedId = $playeds2[0]['id'];
        $maxPl    = $this->getPl($type, $playedId);
        $this->assign('maxPl', $maxPl);

        $this->assign('playeds', $this->playeds);
        $this->assign('type', $this->type);
        $this->assign('groupId', $this->groupId);

        $this->display('Game/inc_game_played');
    }

    final public function played($type, $playedId)
    {
        $this->playeds = $this->getPlayeds();
        $data          = $this->playeds[$playedId];
        $this->type    = $type;

        $maxPl = $this->getPl($type, $playedId);
        $this->assign('maxPl', $maxPl);

        $this->groupId = $data['groupId'];
        $this->played  = $playedId;

        $this->assign('type', $type);
        $this->assign('groupId', $this->groupId);
        $this->assign('playedId', $playedId);
        $this->assign('current_played', $data);
        $this->assign('tpl', $data['playedTpl']);
        $this->display("Game/inc_game_content");
    }

    private function getPl($type = null, $played = null)
    {
        $guoTypes = M('type')->cache(true, 10 * 60, 'xcache')
			->where(array('isDelete' => 0,'is_official' => 1))
			->order('sort')->field('id')
			->select();
		$guoTypes = empty($guoTypes) ? $this->guoTypes : array_column($guoTypes, 'id');
        if(in_array($type, $guoTypes)){
            $data = M('played')->where(array('id' => $played))->field('guo_prop as bonusProp,guo_prop_base as bonusPropBase')->find();
        }else{
            $data = M('played')->where(array('id' => $played))->field('bonusProp, bonusPropBase')->find();
        }

        return $data;
    }

    // 加载玩法介绍信息
    final public function playTips($playedId)
    {
        $this->playeds = $this->getPlayeds();

        $this->assign('playeds', $this->playeds);
        $this->assign('playedId', $playedId);
        $this->display('Game/inc_game_tips');
    }

    //验证是否开启投注
    final public function checkBuy()
    {
        $actionNo = "";

        $this->settings = $this->getSystemSettings();
        if ($this->settings['switchBuy'] == 0) {
            $actionNo['flag'] = 1;
        }

        $this->ajaxReturn($actionNo, 'JSON');
    }

    final public function getNo($type)
    {
        $actionNo = $this->getGameNo($type);

        if ($type == 1 && $actionNo['actionTime'] == '00:00') {
            $actionNo['actionTime'] = strtotime($actionNo['actionTime']) + 24 * 3600;
        } else {
            $actionNo['actionTime'] = strtotime($actionNo['actionTime']);
        }

        $this->ajaxReturn($actionNo, 'JSON');
        //echo json_encode($actionNo);
    }

    //{{{ 投注
    final public function postCode_xxx()
    {
        $m = M('members')->where(array('uid'=>$this->user['uid']))->find();
        if((!empty($m)&&intval($m['is_sleep'])==1)||empty($m)){
            $this->error('网络异常，请重新投注！');
            exit;
        }
        $urlshang = $_SERVER['HTTP_REFERER']; //上一页URL
        $urldan   = $_SERVER['SERVER_NAME']; //本站域名
        if (strpos($urlshang, 'https://') === 0) {
            $urlcheck = substr($urlshang, 8, strlen($urldan));
        } else {
            $urlcheck = substr($urlshang, 7, strlen($urldan));
        }

        if ($urlcheck != $urldan) {
            $this->error('郑重警告：提交数据出错，请重新投注');
        }

        $codes   = I('code');
        $para    = I('para');
        $amount  = 0;
        $fpcount = 1; //飞盘 默认为1
        //print_r($_POST);

        $this->getSystemSettings();
        if ($this->settings['switchBuy'] == 0) {
            $this->error('本平台已经停止购买！');
        }
        if ($this->settings['switchDLBuy'] == 0 && $this->user['type']) {
            $this->error('代理不能买单！');
        }
        if (count($codes) == 0) {
            $this->error('请先选择号码再提交投注');
        }
        //检查时间 期数
        //$ftime=$this->getTypeFtime($para['type']);  //封单时间
        $actionTime = $this->getGameActionTime($para['type']); //当期时间
        $actionNo   = $this->getGameActionNo($para['type']); //当期期数
        $no         = intval(str_replace('-', '', $para['actionNo']));
        $no2        = intval(str_replace('-', '', $actionNo));

        if ($no < $no2) {
            $this->error('投注失败：该期投注时间已过');
        }
        //if($actionTime!=$para['kjTime'])  $this->error('投注失败：你投注第'.$para['actionNo'].'已过购买时间1');
        //if($actionNo!=$para['actionNo'])  $this->error('投注失败：你投注第'.$para['actionNo'].'已过购买时间2');
        //if($actionTime-$ftime<$this->time) $this->error('投注失败：你投注第'.$para['actionNo'].'已过购买时间3');

        // 查检每注的赔率是否正常
        $this->getPlayeds();
        foreach ($codes as $code) {
            $played = $this->playeds[$code['playedId']];
            //检查开启
            if (!$played['enable']) {
                $this->error('游戏玩法组已停,请刷新再投 -1');
            }
            //检查赔率
            $chkBonus = ($played['bonusProp'] - $played['bonusPropBase']) / $this->settings['fanDianMax'] * ($this->user['fanDian'] - $code['fanDian']) + $played['bonusPropBase']; //实际奖金

            if ($code['bonusProp'] > $played['bonusProp']) {
                $this->error('提交数据出错，请重新投注 -1');
            }
            if ($code['bonusProp'] < $played['bonusPropBase']) {
                $this->error('提交数据出错，请重新投注 -2');
            }
            if (intval($chkBonus) != intval($code['bonusProp'])) {
                $this->error('提交数据出错，请重新投注 -3');
            }
            //检查返点
            if (floatval($code['fanDian']) > floatval($this->user['fanDian']) || floatval($code['fanDian']) > floatval($this->settings['fanDianMax'])) {
                $this->error('提交数据出错，请重新投注 -4');
            }
            //检查倍数
            if (intval($code['beiShu']) < 1) {
                $this->error('倍数只能为大于1正整数');
            }
            // 检查注数
            if ($betCountFun = $played['betCountFun']) {
                if ($played['betCountFun'] == 'descar') {
                    if ($code['actionNum'] > Bet::$betCountFun($code['actionData'])) {
                        $this->error('提交数据出错，请重新投注 -5');
                    }
                } elseif ($played['betCountFun'] == 'descar2') {
                    if ($code['actionNum'] < 1) {
                        $this->error('提交数据出错，请重新投注 -6');
                    }
                } else {
                    if ($code['actionNum'] != Bet::$betCountFun($code['actionData'])) {
                        $this->error('提交数据出错，请重新投注 -7' . Bet::$betCountFun($code['actionData']));
                    }
                }
            } ///end

            //防作弊 20150722
            if ($this->types[$code['type']]['type'] != $played['type']) {
                $this->error('提交数据出错，请重新投注2');
            }

            if (strpos($played['name'], "任选") > -1 && $played['type'] == 1) {
                //检查任选的万千百十个位数是否作弊
                if ($code['weiShu'] != 0 && $code['weiShu'] != 3 && $code['weiShu'] != 5 && $code['weiShu'] != 6 && $code['weiShu'] != 7 && $code['weiShu'] != 9 &&
                    $code['weiShu'] != 10 && $code['weiShu'] != 11 && $code['weiShu'] != 19 && $code['weiShu'] != 14 && $code['weiShu'] != 22 &&
                    $code['weiShu'] != 28 && $code['weiShu'] != 12 && $code['weiShu'] != 13 && $code['weiShu'] != 17 && $code['weiShu'] != 18 &&
                    $code['weiShu'] != 20 && $code['weiShu'] != 21 && $code['weiShu'] != 25 && $code['weiShu'] != 26 && $code['weiShu'] != 15 &&
                    $code['weiShu'] != 23 && $code['weiShu'] != 30 && $code['weiShu'] != 29 && $code['weiShu'] != 27) {
                    $this->error('提交数据出错，请重新投注2');
                }

                //任选四复式
                if ($played['id'] == 8) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选三复式
                if ($played['id'] == 14) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 2) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选二复式
                if ($played['id'] == 29) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 3) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选二大小单双
                if ($played['id'] == 44) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 3) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }

                if ($played['id'] == 9) {
                    if ($code['weiShu'] != 15 && $code['weiShu'] != 23 && $code['weiShu'] != 27 && $code['weiShu'] != 29 && $code['weiShu'] != 30) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }

                if ($played['id'] == 15 || $played['id'] == 22 || $played['id'] == 23 || $played['id'] == 24 || $played['id'] == 41) {
                    if ($code['weiShu'] != 7 && $code['weiShu'] != 11 && $code['weiShu'] != 13 && $code['weiShu'] != 14 && $code['weiShu'] != 19 &&
                        $code['weiShu'] != 21 && $code['weiShu'] != 22 && $code['weiShu'] != 25 && $code['weiShu'] != 26 && $code['weiShu'] != 28) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }

                if ($played['id'] == 30 || $played['id'] == 35 || $played['id'] == 36) {
                    if ($code['weiShu'] != 3 && $code['weiShu'] != 5 && $code['weiShu'] != 6 && $code['weiShu'] != 9 && $code['weiShu'] != 10 &&
                        $code['weiShu'] != 12 && $code['weiShu'] != 17 && $code['weiShu'] != 18 && $code['weiShu'] != 20 && $code['weiShu'] != 24) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }
            }

            //11x5 bug
            if (strpos($played['name'], "任选") > -1 && $played['type'] == 2) {
        //$this->error("222");
                if (!strstr($code['actionData'], ' ')) {
                    $this->error('提交数据出错，请重新投注3');
                }
                //检查任选的投注号码是否重复的作弊
                foreach (explode(' ', $code['actionData']) as $d) {
                    str_replace($d, "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，请重新投注3');
                    }
                }
            }
            //11x5 bug
            if (strpos($played['name'], "组选") > -1 && $played['type'] == 2) {
//$this->error("222");
                if (!strstr($code['actionData'], ' ')) {
                    $this->error('提交数据出错，请重新投注3');
                }
                //检查任选的投注号码是否重复的作弊
                foreach (explode(' ', $code['actionData']) as $d) {
                    str_replace($d, "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，请重新投注3');
                    }
                }
            }
        }

        //$iipp=$_SERVER["REMOTE_ADDR"];
        $ip = $this->ip(true);

        if ($para['fpEnable']) {
            $fpcount = 2;
        }

        $para2 = array(
            'actionTime'  => $this->time,
            'actionNo'    => $para['actionNo'],
            'kjTime'      => $actionTime,
            'actionIP'    => $ip,
            'uid'         => $this->user['uid'],
            'username'    => $this->user['username'],
            'serializeId' => uniqid(),
        );

        if ($zhuihao = I('zhuiHao')) {
            $liqType = 102;
            $codes   = array();
            $info    = '追号投注';

            foreach (explode(';', $zhuihao) as $var) {
                list($code['actionNo'], $code['beiShu'], $code['kjTime']) = explode('|', $var);
                $code['kjTime']                                           = strtotime($code['kjTime']);
                $actionNo                                                 = $this->getGameNo($para['type'], $code['kjTime'] - 1);

                if (strtotime($actionNo['actionTime']) - $ftime < $this->time) {
                    $this->error('投注失败：你追号投注第' . $code['actionNo'] . '已过购买时间');
                }
                $codes[] = $code;
                $amount += abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
            }
        } else {
            $liqType = 101;
            $info    = '投注';

            foreach ($codes as $i => $code) {
                $codes[$i] = array_merge($code, $para2);
                $amount += abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
            }
        }

        // 查询用户可用资金
        $user       = M('members')->where(array('uid' => $this->user['uid']))->field('coin')->find(); //$this->getValue("select coin from {$this->prename}members where uid={$_SESSION['user']['uid']}");
        $userAmount = $user['coin'];
        if ($userAmount < $amount) {
            $this->error('您的可用资金不足，是否充值？');
        }

        // 开始事物处理
        $Model = new \Think\Model();
        $Model->startTrans();

        $isBetSuccess  = array();
        $isCoinSuccess = array();
        $i             = 0;
        foreach ($codes as $code) {
            // 插入投注表
            $code['wjorderId'] = $code['type'] . $code['playedId'] . $this->randomkeys(8 - strlen($code['type'] . $code['playedId']));
            $code['actionNum'] = abs($code['actionNum']);
            $code['mode']      = abs($code['mode']);
            $code['beiShu']    = abs($code['beiShu']);
            $amount            = abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
            $isBetSuccess[$i]  = M('bets')->data($code)->add();
            //$this->insertRow($this->prename .'bets', $code);

            // 添加用户资金流动日志
            $isCoinSuccess[$i] = $this->addCoin(array(
                'uid'       => $this->user['uid'],
                'type'      => $code['type'],
                'liqType'   => $liqType,
                'info'      => $info,
                'extfield0' => $isBetSuccess[$i],
                'extfield1' => $para['serializeId'],
                'coin'      => -$amount,
            ));
            $i++;
        }

        $isSuc = true;
        for (; $i >= 0; $i--) {
            if ($isBetSuccess[$i] === false || $isCoinSuccess[$i] === false) {
                $isSuc = false;
                break;
            }
        }

        if ($isSuc) {
            //将投注记录写入文件
            if (!is_dir('./Record/')) {
                mkdir('./Record/');
            }
            $fp         = fopen("./Record/" . $code['username'] . ".txt", "a+");
            $tz_content = $code['wjorderId'] . " 投注内容：" . $code['actionData'] . " 玩法：" . $code['playedId'] . " 元角分：" . $code['mode'] . " 倍数：" . $code['beiShu'] . " 注数：" . $code['actionNum'] . " 时间：" . date('m-d H:i:s', time()) . "\r\n\r\n";
            $flag       = fwrite($fp, $tz_content);
            if (!$flag) {
                $this->error('创建投注记录文件失败');
            }
            fclose($fp);

            $Model->commit(); //成功则提交
            $this->success('投注成功');
        } else {
            $Model->rollback(); //不成功，则回滚
            $this->error('投注失败1111');
        }
    }
    //}}}

    //{{{ 投注
    final public function postCode()
    {
        $m = M('members')->where(array('uid'=>$this->user['uid']))->find();
        if((!empty($m)&&intval($m['is_sleep'])==1)||empty($m)){
            $this->error('网络异常，请重新投注！');
            exit;
        }
   /*      $urlshang = $_SERVER['HTTP_REFERER']; //上一页URL
        $urldan   = $_SERVER['SERVER_NAME']; //本站域名
        if (strpos($urlshang, 'https://') === 0) {
            $urlcheck = substr($urlshang, 8, strlen($urldan));
        } else {
            $urlcheck = substr($urlshang, 7, strlen($urldan));
        }
        if ($urlcheck != $urldan) {
            $this->error('郑重警告：提交数据出错，请重新投注');
        } */

        $codes = I('code');
        $para  = I('para');

        $amount  = 0;
        $fpcount = 1; //飞盘 默认为1
        //print_r($_POST);
        $this->getSystemSettings();
        if ($this->settings['switchBuy'] == 0) {
            $this->error('本平台已经停止购买！');
        }
        if ($this->settings['switchDLBuy'] == 0 && $this->user['type']) {
            $this->error('代理不能买单！');
        }

        if(in_array($para['type'],$this->openSet['types'])){ //北京pk投注时间控制
            $t = time();
            $st = strtotime(date('Y-m-d '.$this->openSet['time_at'][$para['type']]['start'],$t));
            $et = strtotime(date('Y-m-d '.$this->openSet['time_at'][$para['type']]['end'],$t));
            if($this->openSet['time_at'][$para['type']]['tj'] == 'and'){
                if($t > $st && $t < $et){
                    $this->error($this->openSet['titles'][$para['type']]);
                }
            }else{
                if($t < $st || $t > $et){
                    $this->error($this->openSet['titles'][$para['type']]);
                }
            }
        }

        if (count($codes) == 0) {
            $this->error('请先选择号码再提交投注');
        }
        //检查时间 期数
//        $ftime=$this->getTypeFtime($para['type']);  //封单时间

        $actionTime  = $this->getGameActionTime($para['type']); //当期时间
        $actionNo_array = explode('|', $para['actionNo']);
        $actionNo = $this->getGameActionNo($para['type']); //当期期数
        /************************ 之间修改代码勿动 开始****************************/
        $no2      = str_replace('-', '', $actionNo);
        foreach ($actionNo_array as $action_no) {
            $no       = str_replace('-', '', $action_no);
            if ($no < $no2) {
                $this->error('投注失败：该期投注时间已过');
            }
        }
        $time = time() + $this->stop_times[$para['type']];
        if($actionTime  <= $time)  $this->error('投注失败：该期投注时间已过');
        if($para['type'] == 20){
            $this->before_stop_time = 30;
        }
        //当期投注截止时间已过 不准投注
        if($actionTime-$this->before_stop_time < time())  $this->error('投注失败：该期投注时间已过');
        /************************ 之间修改代码勿动 结束****************************/
        if($actionNo!=$para['actionNo'])  $this->error('投注失败：你投注第'.$para['actionNo'].'已过购买时间2');
        //if($actionTime-$ftime<$this->time) $this->error('投注失败：你投注第'.$para['actionNo'].'已过购买时间3');

        // 查检每注的赔率是否正常
        $this->getPlayeds();
        foreach ($codes as $code) {
            //彩种开启关闭判断
            /*if ($this->types[$code['type']]['enable'] != 1) {
                $this->error($this->types[$code['type']]['title'].'已停止投注！');
             }
            $played = $this->playeds[$code['playedId']];
            //检查开启
            if (!$played['enable']) {
                $this->error('游戏玩法组已停,请刷新再投 -1');
            }*/
			
			//彩种开启关闭判断
            $cur_type = $this->types[$code['type']];            
            if ($cur_type['enable'] != 1) {
                $this->error($this->types[$code['type']]['title'].'已停止投注！');
            }
                      
            //检查开启
            $played = $this->playeds[$code['playedId']];  
            if ($cur_type['is_official'] == 1) {
            	if (!$played['is_official_open']) {
	                $this->error('官彩游戏玩法组已停,请刷新再投 -1');
	            }
            }else{
            	if (!$played['enable']) {
	                $this->error('游戏玩法组已停,请刷新再投 -1');
	            }
            }
			
            if(in_array($code['type'],$this->guoTypes)){
                $prop = $played['guo_prop'];
                $propBase = $played['guo_prop_base'];
                $playedMaxCount = $played['gmaxCount'];
            }else{
                $prop = $played['bonusProp'];
                $propBase = $played['bonusPropBase'];
                $playedMaxCount = $played['maxCount'];
            }
            //检查赔率
            $chkBonus = ($prop - $propBase) / $this->settings['fanDianMax'] * ($this->user['fanDian'] - $code['fanDian']) + $propBase; //实际奖金

            if ($code['bonusProp'] > $prop) {
                $this->error('提交数据出错，请重新投注 -1');
            }
            if ($code['bonusProp'] < $propBase) {
                $this->error('提交数据出错，请重新投注 -2');
            }

            if (intval($chkBonus) != intval($code['bonusProp'])) {
                $this->error('提交数据出错，请重新投注 -3');
            }
            //检查返点
            if (floatval($code['fanDian']) > floatval($this->user['fanDian']) || floatval($code['fanDian']) > floatval($this->settings['fanDianMax'])) {
                $this->error('提交数据出错，请重新投注 -4');
            }
            //检查倍数
            if (intval($code['beiShu']) < 1) {
                $this->error('倍数只能为大于1正整数');
            }
            // 检查注数
            if ($betCountFun = $played['betCountFun']) {
                if ($played['betCountFun'] == 'descar') {
                    if ($code['actionNum'] > Bet::$betCountFun($code['actionData'])) {
                        $this->error('提交数据出错，请重新投注 -5');
                    }
                } elseif ($played['betCountFun'] == 'descar2') {
                    if ($code['actionNum'] < 1) {
                        $this->error('提交数据出错，请重新投注 -6');
                    }
                } else {
                    if ($code['actionNum'] != Bet::$betCountFun($code['actionData'])) {
                        $this->error('提交数据出错，请重新投注 -7' . Bet::$betCountFun($code['actionData']));
                    }
                }
            } ///end
            //检查投注注数
//            $this->ZhuShuMax($code['type'],$played,$code['actionNum'],$actionNo,$this->user['uid'],$cur_type,$code['actionData']);
            if(!$this->ZhuShuMax($code['type'],$played,$code['actionNum'],$actionNo,$this->user['uid'],$cur_type,$code['actionData'])){
                return $this->error("超过最大注数");
            }
            //防作弊 20150722
            if ($this->types[$code['type']]['type'] != $played['type']) {
                $this->error('提交数据出错，请重新投注2');
            }

            if (strpos($played['name'], "任选") > -1 && $played['type'] == 1) {
                //检查任选的万千百十个位数是否作弊
                if ($code['weiShu'] != 0 && $code['weiShu'] != 3 && $code['weiShu'] != 5 && $code['weiShu'] != 6 && $code['weiShu'] != 7 && $code['weiShu'] != 9 &&
                    $code['weiShu'] != 10 && $code['weiShu'] != 11 && $code['weiShu'] != 19 && $code['weiShu'] != 14 && $code['weiShu'] != 22 &&
                    $code['weiShu'] != 28 && $code['weiShu'] != 12 && $code['weiShu'] != 13 && $code['weiShu'] != 17 && $code['weiShu'] != 18 &&
                    $code['weiShu'] != 20 && $code['weiShu'] != 21 && $code['weiShu'] != 25 && $code['weiShu'] != 26 && $code['weiShu'] != 15 &&
                    $code['weiShu'] != 23 && $code['weiShu'] != 30 && $code['weiShu'] != 29 && $code['weiShu'] != 27) {
                    $this->error('提交数据出错，请重新投注2');
                }

                //任选四复式
                if ($played['id'] == 8) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选三复式
                if ($played['id'] == 14) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 2) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选二复式
                if ($played['id'] == 29) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 3) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }
                //任选二大小单双
                if ($played['id'] == 44) {
                    str_replace("-", "#", $code['actionData'], $num);
                    if ($num > 3) {
                        $this->error('提交数据出错，请重新投注4');
                    }
                }

                if ($played['id'] == 9) {
                    if ($code['weiShu'] != 15 && $code['weiShu'] != 23 && $code['weiShu'] != 27 && $code['weiShu'] != 29 && $code['weiShu'] != 30) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }

                if ($played['id'] == 15 || $played['id'] == 22 || $played['id'] == 23 || $played['id'] == 24 || $played['id'] == 41) {
                    if ($code['weiShu'] != 7 && $code['weiShu'] != 11 && $code['weiShu'] != 13 && $code['weiShu'] != 14 && $code['weiShu'] != 19 &&
                        $code['weiShu'] != 21 && $code['weiShu'] != 22 && $code['weiShu'] != 25 && $code['weiShu'] != 26 && $code['weiShu'] != 28) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }

                if ($played['id'] == 30 || $played['id'] == 35 || $played['id'] == 36) {
                    if ($code['weiShu'] != 3 && $code['weiShu'] != 5 && $code['weiShu'] != 6 && $code['weiShu'] != 9 && $code['weiShu'] != 10 &&
                        $code['weiShu'] != 12 && $code['weiShu'] != 17 && $code['weiShu'] != 18 && $code['weiShu'] != 20 && $code['weiShu'] != 24) {
                        $this->error('提交数据出错，请重新投注2');
                    }
                }
            }

            if ($played['id'] == 17||$played['id'] == 20) {
                if (mb_strlen($code['actionData']) > 9) {
                    $this->error('单笔投注注数最大不能超过9位');
                }
            }

            //11x5 bug
            if (strpos($played['name'], "任选") > -1 && $played['type'] == 2) {
//$this->error("222");

                if (!strstr($code['actionData'], ' ') && $played['name'] != '任选一') {
                    $this->error('提交数据出错，请重新投注');
                }
                //检查任选的投注号码是否重复的作弊
                foreach (explode(' ', $code['actionData']) as $d) {
                    str_replace($d, "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，1请重新投注3');
                    }
                }
            }
            //11x5 bug
            if (strpos($played['name'], "组选") > -1 && $played['type'] == 2) {
//$this->error("222");
                if (!strstr($code['actionData'], ' ')) {
                    $this->error('提交数据出错，请重新投注3');
                }
                //检查任选的投注号码是否重复的作弊
                foreach (explode(' ', $code['actionData']) as $d) {
                    str_replace($d, "#", $code['actionData'], $num);
                    if ($num > 1) {
                        $this->error('提交数据出错，请重新投注3');
                    }
                }
            }
        }

        //$iipp=$_SERVER["REMOTE_ADDR"];
        $ip = get_client_ip();

        if ($para['fpEnable']) {
            $fpcount = 2;
        }

        $para2 = array(
            'actionTime'  => $this->time,
            'actionNo'    => $para['actionNo'],
            'kjTime'      => $actionTime,
            'actionIP'    => $ip,
            'uid'         => $this->user['uid'],
            'username'    => $this->user['username'],
            'serializeId' => uniqid(),
        );

        if ($zhuihao = I('zhuiHao')) {
            $liqType = 102;
            $info    = '追号投注';

            $beishu_array = explode('|', $para['beishu']);

            $codes_2 = array();
            foreach ($codes as $i => $code) {
                $i = 0;
                foreach ($actionNo_array as $action_no) {
                    $para2 = array(
                        'actionTime'  => $this->time,
                        'actionNo'    => $action_no,
                        'kjTime'      => $actionTime,
                        'actionIP'    => $ip,
                        'uid'         => $this->user['uid'],
                        'username'    => $this->user['username'],
                        'serializeId' => uniqid(),
                    );
                    $code['beiShu']  = $beishu_array[$i];
                    $code['zhuiHao'] = 1;
                    $new_code        = array_merge($code, $para2);
                    array_push($codes_2, $new_code);
                    $amount += abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
                    $i++;
                }
            }
            $codes = $codes_2;
            $codes = array_splice($codes, 1);

            //dump($codes_2);
        } else {
            $liqType = 101;
            $info    = '投注';

            foreach ($codes as $i => $code) {
                $codes[$i] = array_merge($code, $para2);
                $amount += abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
            }
        }

        // 查询用户可用资金
        $user       = M('members')->where(array('uid' => $this->user['uid']))->field('coin')->find(); //$this->getValue("select coin from {$this->prename}members where uid={$_SESSION['user']['uid']}");
        $userAmount = $user['coin'];
        if ($userAmount < $amount) {
            $this->error('您的可用资金不足，是否充值？');
        }

        // 开始事物处理
        $Model = new \Think\Model();
        $Model->startTrans();

        $isBetSuccess  = array();
        $isCoinSuccess = array();
        $i             = 0;
        foreach ($codes as $code) {
            // 插入投注表
            $code['wjorderId'] = $code['type'] . $code['playedId'] . $this->randomkeys(8 - strlen($code['type'] . $code['playedId']));
            $code['actionNum'] = abs($code['actionNum']);
            $code['mode']      = abs($code['mode']);
            $code['beiShu']    = abs($code['beiShu']);
            $code['istest']    = $this->user['is_test']; //添加是不是测试账户标识
            $amount            = abs($code['actionNum'] * $code['mode'] * $code['beiShu'] * $fpcount);
            unset($code['playedName']);
            $isBetSuccess[$i]  = M('bets')->add($code);

//            echo $this->getLastSql();
          // $this->ajaxReturn($isBetSuccess[$i]);

            //$this->insertRow($this->prename .'bets', $code);

            // 添加用户资金流动日志
            $isCoinSuccess[$i] = $this->addCoin(array(
                'uid'       => $this->user['uid'],
                'type'      => $code['type'],
                'liqType'   => $liqType,
                'info'      => $info,
                'extfield0' => $isBetSuccess[$i],
                'extfield1' => $para['serializeId'],
                'coin'      => -$amount,
            ));
            $i++;
        }
     //   $this->ajaxReturn( $isCoinSuccess[$i] );
        $isSuc = true;
        for (; $i >= 0; $i--) {
            if ($isBetSuccess[$i] === false || $isCoinSuccess[$i] === false) {
                $isSuc = false;
                break;
            }
            /*else if($isBetSuccess[$i]){
                if(strpos(strtolower($this->user['username']), 'cswt') === 0){ //委托账户批量投注
                   if(!$Model->query("call sp_EntrustBet({$isBetSuccess[$i]},'{$this->user['username']}')")){
//                       dump($Model->getDbError());
                       $isSuc = false;
                       break;
                   };
                }
            }*/
        }

        if ($isSuc) {
            //将投注记录写入文件
            if (!is_dir('./Record/')) {
                mkdir('./Record/');
            }
            $fp         = fopen("./Record/" . $code['username'] . ".txt", "a+");
            $tz_content = $code['wjorderId'] . " 投注内容：" . $code['actionData'] . " 玩法：" . $code['playedId'] . " 元角分：" . $code['mode'] . " 倍数：" . $code['beiShu'] . " 注数：" . $code['actionNum'] . " 时间：" . date('m-d H:i:s', time()) . "\r\n\r\n";
            $flag       = fwrite($fp, $tz_content);
            if (!$flag) {
                $this->error('创建投注记录文件失败');
            }
            fclose($fp);

            $Model->commit(); //成功则提交
//            if(strpos(strtolower($this->user['username']), 'cswt') === 0){ //委托账户批量投注
            if(in_array(strtolower($this->user['username']),[
                'cswt001','cswt002','cswt003','cswt004','cswt005','cswt006'])){ //委托账户批量投注
                $Model = new \Think\Model();
                foreach ($isBetSuccess as $value){
                    if($value){
//                        $Model->query("call sp_EntrustBet({$value},'{$this->user['username']}')");
                        if($Model->query(" call sp_EntrustBet({$value},'{$this->user['username']}')") === false){
                            $this->error('投注成功，wr账户投注失败！'.($Model->getDbError()));
                            //.($Model->getDbError())
                        }
                    }

                }

//                    var_dump($Model->getDbError());
            }
            $this->success('投注成功');
        } else {
            $Model->rollback(); //不成功，则回滚
            $this->error('投注失败');
        }
        ///////////
    }
    /*protected function ZhuShuMax($curmid,$maxzs,$num,$actionNo,$uid,$played_id)
    {
        if($maxzs < 0 ){
            $this->error('玩法正在升级中...');
            return false;
        }else if($maxzs > 0){
            $this->mus[$curmid][$played_id] = isset($this->mus[$curmid][$played_id]) ? $this->mus[$curmid][$played_id] + $num : $num;
            $num = $this->mus[$curmid][$played_id];
            $betRe = M('bets')->where(['actionNo' => $actionNo,
                'type'=>$curmid,
                'playedId'=>$played_id,
                'uid'=>$uid,
                'isDelete' => 0
            ])->sum('actionNum');
            $num = $num + $betRe;
            if($num > $maxzs){
                $this->error('该玩法当期累计最大投注注数不能超过'.$maxzs.'注');
                return false;
            }
        }
        return true;
    }*/
    protected function ZhuShuMax($curmid,$playedInfo,$num,$actionNo,$uid,$cur_type,$data)
    {
        //查询官方id
//        $gf = Type::where(array('is_official'=>1))->select();
//        $guoTypes = array();
//        foreach ($gf as $key=>$item) {
//            $guoTypes[] = $item['id'];
//        }
        if($cur_type['is_official'] == 1){
            //国彩定位胆位数注数限制
            if($playedInfo['id'] == 37){
                $dataArr = explode(',',$data);
                if($playedInfo['wcount'] > 0 && strlen(str_replace('-','',$dataArr[0])) > $playedInfo['wcount']){
                    $this->error('万位注数不能超过'.$playedInfo['wcount'].'注');
                    return false;
                }
                if($playedInfo['qcount'] > 0 && strlen(str_replace('-','',$dataArr[1])) > $playedInfo['qcount']){
                    $this->error('千位注数不能超过'.$playedInfo['qcount'].'注');
                    return false;
                }
                if($playedInfo['bcount'] > 0 && strlen(str_replace('-','',$dataArr[2])) > $playedInfo['bcount']){
                    $this->error('百位注数不能超过'.$playedInfo['bcount'].'注');
                    return false;
                }
                if($playedInfo['scount'] > 0 && strlen(str_replace('-','',$dataArr[3])) > $playedInfo['scount']){
                    $this->error('十位注数不能超过'.$playedInfo['scount'].'注');
                    return false;
                }
                if($playedInfo['gcount'] > 0 && strlen(str_replace('-','',$dataArr[4])) > $playedInfo['gcount']){
                    $this->error('个位注数不能超过'.$playedInfo['gcount'].'注');
                    return false;
                }

            }
            if($playedInfo['id'] == 96 || $playedInfo['id'] == 265){
                $dataArr = explode(',',$data);
                $t=$dataArr[0];
                if($playedInfo['dy'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dy']){
                    $this->error('冠军位注数不能超过'.$playedInfo['dy'].'注');
                    return false;
                }
                $t=$dataArr[1];
                if($playedInfo['de'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['de']){
                    $this->error('亚军位注数不能超过'.$playedInfo['de'].'注');
                    return false;
                }
                $t=$dataArr[2];
                if($playedInfo['ds'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['ds']){
                    $this->error('第三位注数不能超过'.$playedInfo['ds'].'注');
                    return false;
                }
                $t=$dataArr[3];
                if($playedInfo['dx'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dx']){
                    $this->error('第四位注数不能超过'.$playedInfo['dx'].'注');
                    return false;
                }
                $t=$dataArr[4];
                if($playedInfo['dw'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dw']){
                    $this->error('第五位注数不能超过'.$playedInfo['dw'].'注');
                    return false;
                }
                $t=$dataArr[5];
                if($playedInfo['dl'] > 0 && $dataArr[5] !='-' && count(explode(' ',$dataArr[5])) > $playedInfo['dl']){
                    $this->error('第六位注数不能超过'.$playedInfo['dl'].'注');
                    return false;
                }
                $t=$dataArr[6];
                if($playedInfo['dq'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dq']){
                    $this->error('第七位注数不能超过'.$playedInfo['dq'].'注');
                    return false;
                }
                $t=$dataArr[7];
                if($playedInfo['db'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['db']){
                    $this->error('第八位注数不能超过'.$playedInfo['db'].'注');
                    return false;
                }
                $t=$dataArr[8];
                if($playedInfo['dj'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dj']){
                    $this->error('第九位注数不能超过'.$playedInfo['dj'].'注');
                    return false;
                }
                $t=$dataArr[9];
                if($playedInfo['dsh'] > 0 && $t !='-' && count(explode(' ',$t)) > $playedInfo['dsh']){
                    $this->error('第十位注数不能超过'.$playedInfo['dsh'].'注');
                    return false;
                }

            }
            $maxzs = $playedInfo['gmaxCount'];
        }else{
            $maxzs = $playedInfo['maxCount'];
        }
        if($maxzs < 0 ){
            $this->error('玩法正在升级中...');
            return false;
        }else if($maxzs > 0){
            $played_id = $playedInfo['id'];
            $this->mus[$curmid][$played_id] = isset($this->mus[$curmid][$played_id]) ? $this->mus[$curmid][$played_id] + $num : $num;
            $num = $this->mus[$curmid][$played_id];
            $betRe = M("bets")->where(['actionNo' => $actionNo,
                'type'=>$curmid,
                'playedId'=>$played_id,
                'uid'=>$uid,
                'isDelete' => 0
            ])->sum('actionNum');
            $num = $num + $betRe;
            if($num > $maxzs){
                $this->error('该玩法当期累计最大投注注数不能超过'.$maxzs.'注');
                return false;
            }
        }
        return true;
    }

    /**
     * {{{ ajax撤单
     */
    final public function deleteCode()
    {
        //$this->beginTransaction();

        $Model = new \Think\Model();
        $Model->startTrans();

        $id = I('id');
        //$sql="select * from {$this->prename}bets where id=".$id;
        if (!$data = M('bets')->where(array('id' => I('id')))->find()) {
            $this->error('找不到定单。');
        }
        if ($data['isDelete']) {
            $this->error('这单子已经撤单过了。');
        }
        if ($data['uid'] != $this->user['uid']) {
            $this->error('这单子不是您的，您不能撤单。');
        } // 可考虑管理员能给用户撤单情况
        if ($data['kjTime'] <= $this->time) {
            $this->error('已过开奖期，不能撤单');
        }
        if ($data['lotteryNo']) {
            $this->error('已经开奖，不能撤单');
        }
        if ($data['qz_uid']) {
            $this->error('单子已经被人抢庄，不能撤单');
        }

        // 冻结时间后不能撤单
        $this->getTypes();
        $ftime = $this->getTypeFtime($data['type']);
        if ($data['kjTime'] - $ftime < $this->time) {
            $this->error('这期已经结冻，不能撤单');
        }

        $amount = $data['beiShu'] * $data['mode'] * $data['actionNum'] * intval(($data['fpEnable'] ? '2' : '1'));
        $amount = abs($amount);
        // 添加用户资金变更日志
        $isSuc1 = $this->addCoin(array(
            'uid'       => $data['uid'],
            'type'      => $data['type'],
            'playedId'  => $data['playedId'],
            'liqType'   => 7,
            'info'      => "撤单",
            'extfield0' => $id,
            'coin'      => $amount,
        ));

        // 更改定单为已经删除状态
        $map['isDelete'] = 1;
        $isSuc2          = M('bets')->where('id=' . $id)->save($map);

        if ($isSuc1 !== false && $isSuc2 == true) {
            //将投注记录写入文件
            /*if (!is_dir('./Record/')) {
                mkdir('./Record/');
            }
            $fp         = fopen("./Record/" . $data['username'] . ".txt", "a+");
            $tz_content = $data['wjorderId'] . " 撤单 " . date('m-d H:i:s', time()) . "\r\n\r\n";
            $flag       = fwrite($fp, $tz_content);
            if (!$flag) {
                $this->error('创建投注记录文件失败');
            }
            fclose($fp);*/

            $Model->commit(); //成功则提交
            $this->success('撤单成功');
        } else {
            $Model->rollback(); //不成功，则回滚
            $this->error('撤单失败');
        }
    }
    //}}}

    /**
     * ajax取定单列表
     */
    final public function getOrdered($type = null)
    {

        if (!$this->type) {
            $this->type = $type;
        }
        $pre = $this->prename;

        $sql = "select b.*, (select shortName from  {$pre}type t where t.id=b.type) typename
            ,(select name from {$pre}played p where p.id=b.playedId) playedname
            from {$pre}bets b where uid={$this->user['uid']} and zhuihao=0 order by id desc limit 6";
        //echo $sql;
        $order_list = M()->query($sql);
        //$this->ajaxReturn('order_list', $order_list);
        //dump($order_list);
        //M('bets')->where(array('uid'=>$this->user['uid']))->limit(7)->order('id desc')->select();

        $this->assign('order_list', $order_list);
        $this->assign('time', $this->time);
        $types = $this->getTypes();
        $this->assign('types', $types);
        $playeds = $this->getPlayeds();
        $this->assign('playeds', $playeds);
        //dump($order_list);

        $this->display('Game/inc_game_order');

    }
    /**
     * ajax取定单列表
     */
    final public function getOrderedZhuihao($type = null)
    {

        if (!$this->type) {
            $this->type = $type;
        }
        $pre = $this->prename;

        $sql = "select b.*, (select shortName from  {$pre}type t where t.id=b.type) typename
            ,(select name from {$pre}played p where p.id=b.playedId) playedname
            from {$pre}bets b where uid={$this->user['uid']} and zhuihao=1 order by id desc limit 6";
        //echo $sql;
        $order_list = M()->query($sql);
        //$this->ajaxReturn('order_list', $order_list);
        //dump($order_list);
        //M('bets')->where(array('uid'=>$this->user['uid']))->limit(7)->order('id desc')->select();

        $this->assign('order_list', $order_list);
        $this->assign('time', $this->time);
        $types = $this->getTypes();
        $this->assign('types', $types);
        $playeds = $this->getPlayeds();
        $this->assign('playeds', $playeds);
        //dump($order_list);

        $this->display('Game/inc_game_order_zhuihao');

    }

    //随机函数
    public function randomkeys($length)
    {
        $key      = "";
        $pattern  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pattern1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pattern2 = '0123456789';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)};
        }

        return $key;
    }
}
