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
 * 空模块，主要用于显示404页面，请不要删除
 */
class CashController extends HomeController
{
    public function index()
    {
        if(date('H')>=2&&date('H')<9){
            $this->error('已过提现时间，请明天9点再发起提款');
        }
        $m = M('member_bank');

        $list = $m
            ->join(array('gygy_bank_list on gygy_bank_list.id=gygy_member_bank.bankId'))
            ->where(array('enable' => 1, 'uid' => $this->user['uid']))
            ->field("gygy_member_bank.*,gygy_bank_list.id bankId,gygy_bank_list.name")
            ->select();
        $map['uid'] = $this->user['uid'];
        $coin = M('Members')->where($map)->field('username,coin')->find();
        $this->assign('coin', $coin);
        $this->assign('bank', $list);

        $time = strtotime(date('Y-m-d', $this->time));
        $cash = M('member_cash')->where(array('actionTime' => array('egt', $time), 'uid' => $this->user['uid']))->field('count(*) as count')->find();
        $this->assign('cashTimes',3-$cash['count']);

        $this->display();
    }

    public function cash()
    {
        if(empty(I('UserBankId'))){
            $this->error('请选择银行卡');
        }
        $user = M('members')->find($this->user['uid']);
		//dump($user);exit;
		if(!I('UserBankId')){
			$this->error('未绑定银行卡无法提现');
		}
        if ($user['is_test'] == 1){
            $this->error('此账号无此权限');
        }
        if ($user['coinPassword'] != think_ucenter_md5(I('FundsPass'), UC_AUTH_KEY)) {
            $this->error('资金密码不正确');
        }

        if ($user['coin'] < intval(I('TikuanMoney'))) {
            $this->error('你账户资金不足');
        }

        $amount = I('TikuanMoney');
        $amountGroup = [];
        if ($amount > 50000) {
            $amountGroup = $this->amountGroup($amount);
        }

        // 查询最大提现次数与已经提现次数
        $time = strtotime(date('Y-m-d', $this->time));
        $cash = M('member_cash')->where(array('actionTime' => array('egt', $time), 'uid' => $this->user['uid']))->field('count(*) as count')->find();
        $grade = M('member_level')->where(array('level' => $this->user['grade']))->field('maxToCashCount')->find();
        if ($times = $cash['count']) {
            //$cashTimes=$grade['maxToCashCount'];
            $cashTimes = $this->settings['cashTimes'];
            if ($times >= $cashTimes) {
                $this->error('对不起，今天你提现次数已达到最大限额，请明天再来');
            }
        }

        //增加黑客修改提现金额为负数不合法的判断
        if (I('TikuanMoney') < 100) {
            $this->error('提现金额不得低于100元');
        }
        if (I('TikuanMoney') > 100000){
            $this->error('单次提现金额不能大于10万');
        }

        $amount = I('TikuanMoney', '', 'intval');
        if ($amount < $this->settings['cashMin'] || $amount > $this->settings['cashMax']) {
            $this->error('提现金额必须介于' . $this->settings['cashMin'] . '和' . $this->settings['cashMax'] . '之间');
        }

        //提示时间检查
        $baseTime = strtotime(date('Y-m-d ', $this->time) . '06:00');
        $fromTime = strtotime(date('Y-m-d ', $this->time) . $this->settings['cashFromTime'] . ':00');
        $toTime = strtotime(date('Y-m-d ', $this->time) . $this->settings['cashToTime'] . ':00');
        //if($toTime<$baseTime) $toTime.=24*3600;
        if (($fromTime > $toTime && $this->time < $fromTime && $this->time > $toTime)
            || ($fromTime < $toTime && ($this->time < $fromTime || $this->time > $toTime))
        ) {
            $this->error("提现时间：从" . $this->settings['cashFromTime'] . "到" . $this->settings['cashToTime']);
        }

        //近2天来的消费判断
        $cashAmout = 0;
        $rechargeAmount = 0;
        $rechargeTime = strtotime('00:00');
//        if ($this->settings['cashMinAmount']&&$this->user['type']!=1) {
        /*if ($this->settings['cashMinAmount']) {
            $cashMinAmount = $this->settings['cashMinAmount'] / 100;

            $gRs = M('member_recharge')->where(array('uid' => $this->user['uid'], 'state' => array('in', '1,2,9'), 'isDelete' => 0, 'rechargeTime' => array('egt', $rechargeTime)))->field('sum(case when rechargeAmount>0 then rechargeAmount else amount end) as rechargeAmount')->find();
            if ($gRs) {
                $rechargeAmount = $gRs["rechargeAmount"] * $cashMinAmount;
            }

            if ($rechargeAmount) {
                //近2天来消费总额
                $bet = M('bets')->where(array('actionTime' => array('egt', $rechargeTime), 'uid' => $this->user['uid'], 'isDelete' => 0, 'lotteryNo' => array('neq', '')))->field('sum(mode*beiShu*actionNum) as betAmout')->find();
                $betAmout = $bet['betAmout'];
                if (floatval($betAmout) < floatval($rechargeAmount)) {
                    if(floatval($gRs["rechargeAmount"])<=0&&$this->user['type']==1){
                        //代理号，如果没有充值过，提现不限制
                    }else{
                        $this->error("消费满" . $this->settings['cashMinAmount'] . "%才能提现");
                    }
                }
            }
        }
        */
        /////近2天来的消费判断结束
        $memberBankId = I('UserBankId');
        $bank = M('member_bank')->where(array('uid' => $this->user['uid'], 'id' => $memberBankId))->select();

        if (empty($bank)) {
            $this->error('未绑定银行卡无法提现');
        }
        if ($bank[0]['actionTime'] + 8 * 60 * 60 > time()) {
            $this->error('该银行卡添加不足8小时，不能用于提现');
        }

        // 检查充值投注总额有没有到充值总额的30%
//        $rechargeTotal = M('member_recharge')->where(array('uid' => $this->user['uid'], 'state' => array('in', '1,2,9'), 'isDelete' => 0))->sum('amount');
        // if ($user['type'] != 1){

            $gRs = M('member_recharge')
                ->where(array('uid' => $this->user['uid'], 'state' => array('in', '1,2,9'), 'isDelete' => 0))
                ->field('sum(case when rechargeAmount>0 then rechargeAmount else amount end) as rechargeAmount')->find();
            $rechargeTotal = $gRs["rechargeAmount"];
            if ($rechargeTotal > 0) {
    //            $betAmount = M('bets')->where(array('uid' => $this->user['uid'], 'isDelete' => 0, 'lotteryNo' => array('neq', '')))->sum(mode * beiShu * actionNum);
                $betAmount = $user['scoreTotal'];
                if ($betAmount < ($rechargeTotal * 0.3)) {
                    $this->error('投注金额小于充值金额的30%，不能提现');
                }
            }else{
                //如果是代理号 没有充值过 可以提现流水
                if($user['type'] != 1){
                   /* $coin_log = M('coin_log')->where(array('uid' => $this->user['uid'], 'liqType' => 201))->find();
                    if(empty($coin_log)){
                        $this->error('还未充值，不能提现');
                    }*/
                    $this->error('还未充值，不能提现');
                }
            }
        // }
        $para['username'] = $bank[0]['username'];
        $para['account'] = $bank[0]['account'];
        $para['amount'] = I('TikuanMoney');
        $para['bankId'] = $bank[0]['bankId'];
        $para['memberBankId'] = $bank[0]['id'];
        $para['actionTime'] = $this->time;
        $para['uid'] = $this->user['uid'];

        M()->startTrans();
        // 插入提现请求表
        if ($lastid = M('member_cash')->add($para)) {
            // 流动资金
            $return = $this->addCoin(array(
                'coin' => 0 - $para['amount'],
                'fcoin' => $para['amount'],
                'uid' => $para['uid'],
                'liqType' => 106,

                'info' => "提现[$lastid]资金冻结",
                'extfield0' => $lastid,
            ));

            // 提现金额分账记录
            if (!empty($amountGroup)) {
                $cashSplit = [];
                foreach ($amountGroup as $amount) {
                    $row['cashId'] = $lastid;
                    $row['uid'] = $this->user['uid'];
                    $row['actionTime'] = $this->time;
                    $row['splitAmount'] = $amount;
                    $row['bankId'] = $bank[0]['bankId'];
                    $row['account'] = $bank[0]['account'];
                    $row['username'] = $bank[0]['username'];
                    $row['state'] = 1;

                    $cashSplit[] = $row;
                }
                $splitRet = M('member_cash_split')->addAll($cashSplit);
                if (!$splitRet) {
                    M()->rollback(); //不成功，则回滚
                    $this->error('提交提现请求出错');
                }
            }

            if ($return) {
                M()->commit(); //成功则提交
                $this->success('申请提现成功，提现将在10分钟内到账，如未到账请联系在线客服。', U('team/cashRecord'));
            } else {
                M()->rollback(); //不成功，则回滚
                $this->error('提交提现请求出错');
            }
        }
    }

    // 提现金额分账
    private function amountGroup($amount)
    {
        if ($amount < 50000) {
            return [];
        }

        $amountGroup = [];
        $surplusAmount = $amount;
        $i = 0;
        while ($surplusAmount > 50000) {
            $randAmount = rand(40000, 49999);
            $amountGroup[$i] = $randAmount;
            $surplusAmount = $surplusAmount - $randAmount;
            $i++;
        }
        $amountGroup[$i] = $surplusAmount;

        return $amountGroup;
    }

    //没有任何方法，直接执行HomeController的_empty方法
    //请不要删除该控制器
    /*
public final function index(){

$bank = M('member_bank')->where(array('enable'=>1,'uid'=>$this->user['uid']))->select();
$bankList = M('bank_list')->where(array('isDelete'=>0))->field('id as lid,name')->select();
$bankList2=array();
foreach($bankList as $b)
{
$bankList2[$b['lid']]=$b;
}
foreach($bank as $key=>$b){
if($bbb=$bankList2[$b['bankId']])
{
$bank[$key] = array_merge($b,$bbb);
}
}

$this->assign('bank',$bank);

$grade = M('member_level')->where(array('level'=>$this->user['grade']))->field('maxToCashCount')->find();
$this->assign('maxToCashCount',$grade['maxToCashCount']);

$this->display();
}

/**
 * 提现申请

public final function cash(){

$this->getSystemSettings();

$user = M('members')->find($this->user['uid']);
if($user['coinPassword']!=think_ucenter_md5(I('coinpwd'), UC_AUTH_KEY)) $this->error('资金密码不正确');
if($user['coin']<intval(I('amount'))) $this->error('你账户资金不足');

// 查询最大提现次数与已经提现次数
$time=strtotime(date('Y-m-d', $this->time));
$cash = M('member_cash')->where(array('actionTime'=>array('egt',$time), 'uid'=>$this->user['uid']))->field('count(*) as count')->find();
$grade = M('member_level')->where(array('level'=>$this->user['grade']))->field('maxToCashCount')->find();

if($times=$cash['count']){
//$cashTimes=$grade['maxToCashCount'];
$cashTimes=$this->settings['cashTimes'];
if($times>=$cashTimes) $this->error('对不起，今天你提现次数已达到最大限额，请明天再来');
}

//增加黑客修改提现金额为负数不合法的判断
if(I('amount')<1)
$this->error('提现金额不得低于1元');
$amount = I('amount','','intval');
if($amount<$this->settings['cashMin'] || $amount>$this->settings['cashMax'])
$this->error('提现金额必须介于'.$this->settings['cashMin'].'和'.$this->settings['cashMax'].'之间');

//提示时间检查
$baseTime=strtotime(date('Y-m-d ',$this->time).'06:00');
$fromTime=strtotime(date('Y-m-d ',$this->time).$this->settings['cashFromTime'].':00');
$toTime=strtotime(date('Y-m-d ',$this->time).$this->settings['cashToTime'].':00');
//if($toTime<$baseTime) $toTime.=24*3600;
if(($fromTime>$toTime && $this->time < $fromTime && $this->time > $toTime)
|| ($fromTime<$toTime && ($this->time < $fromTime || $this->time > $toTime))) $this->error("提现时间：从".$this->settings['cashFromTime']."到".$this->settings['cashToTime']);

//近2天来的消费判断
$cashAmout=0;
$rechargeAmount=0;
$rechargeTime=strtotime('00:00');
if($this->settings['cashMinAmount']){
$cashMinAmount=$this->settings['cashMinAmount']/100;

$gRs = M('member_recharge')->where(array('uid'=>$this->user['uid'], 'state'=>array('in','1,2,9'), 'isDelete'=>0 , 'rechargeTime'=>array('egt',$rechargeTime)))->field('sum(case when rechargeAmount>0 then rechargeAmount else amount end) as rechargeAmount')->find();
if($gRs){
$rechargeAmount=$gRs["rechargeAmount"]*$cashMinAmount;
}

if($rechargeAmount){
//近2天来消费总额
$bet = M('bets')->where(array('actionTime'=>array('egt',$rechargeTime), 'uid'=>$this->user['uid'], 'isDelete'=>0, 'lotteryNo'=>array('neq','')))->field('sum(mode*beiShu*actionNum) as betAmout')->find();
$betAmout=$bet['betAmout'];
if(floatval($betAmout)<floatval($rechargeAmount)) $this->error("消费满".$this->settings['cashMinAmount']."%才能提现");
}

}/////近2天来的消费判断结束

$bank = M('member_bank')->where(array('uid'=>$this->user['uid'], 'id'=>I('id')))->select();
//dump(M('member_bank')->getLastSql());
if(!$bank)
$this->error('提现银行卡不存在');
$para['username']=$bank[0]['username'];
$para['account']=$bank[0]['account'];
$para['amount']=I('amount');
$para['bankId']=$bank[0]['bankId'];
$para['actionTime']=$this->time;
$para['uid']=$this->user['uid'];

M()->startTrans();
// 插入提现请求表
if($lastid = M('member_cash')->add($para))
{
// 流动资金
$return = $this->addCoin(array(
'coin'=>0-$para['amount'],
'fcoin'=>$para['amount'],
'uid'=>$para['uid'],
'liqType'=>106,

'info'=>"提现[$lastid]资金冻结",
'extfield0'=>$lastid
));

if($return)
{
M()->commit();//成功则提交
$this->success('申请提现成功，提现将在10分钟内到账，如未到账请联系在线客服。');

}

M()->rollback();//不成功，则回滚
$this->error('提交提现请求出错');
}

/**
 * 提现结果

public final function result(){

$cash = M('member_cash')->where(array('state'=>1))->field('count(id) as count')->find();

$this->assign('txcount',$cash['count']);
$this->assign('settings',$this->getSystemSettings());
$this->display();
}

//提现详单
public final function info(){
$cashInfo = M('member_cash')->where(array('id'=>I('id')))->find();
$bankInfo = M('member_bank')->where(array('uid'=>$rechargeInfo['uid']))->find();
$list = M('bank_list')->order('id')->select();

$bankList = array();
if($list) foreach($list as $var){
$bankList[$var['id']]=$var;
}

$this->assign('cashInfo',$cashInfo);
$this->assign('bankInfo',$bankInfo);
$this->assign('bankList',$bankList);

$this->display('Cash/cash-info');
}*/
}
