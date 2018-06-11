<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Log;

/**
 * 空模块，主要用于显示404页面，请不要删除
 */
class TeamController extends HomeController
{

    /*游戏记录*/
    final public function record()
    {
        $this->getTypes();
        $this->getPlayeds();
        $this->assign('types', $this->types);
        $this->assign('playeds', $this->playeds);

        $this->search();

        if (!I('get.')) {
            $this->display('Team/record');
        } else {
            $this->display('Team/record');
        }

    }

    final public function search()
    {
        $para = I('get.');
        $pageSize = $para['PageSize'] > 10 ? $para['PageSize'] : 10;
        $this->getTypes();
        $this->getPlayeds();
        $this->assign('types', $this->types);
        $this->assign('playeds', $this->playeds);

        $where = array();
        // 用户名限制
        if ($para['username'] && $para['username'] != '用户名') {
            // 按用户名查找时
            // 只要符合用户名且是自己所有下级的都可查询
            // 用户名用模糊方式查询
            if (mb_strlen($para['username']) > 20){
                $this->assign('data',[]);
                return;
            }
            $where['gygy_members.username'] = array('like', "%" . I('username') . "%");
            //$where['parents']  = array('like', "%," . $this->user['uid'] . ",%");
        }
        //用户类型限制
        switch ($para['utype']) {
            case 1:
                //我自己
                $map['gygy_members.uid'] = $this->user['uid'];
                break;
            case 2:
                //直属下线
                $map['gygy_members.parentId'] = $this->user['uid'];
                break;
            case 3:
                // 所有下级
                $map['gygy_members.parents'] = array('like', '%,' . $this->user['uid'] . ',%');
                break;
            default:
                //所有人
                $map['gygy_members.parents'] = array('like', "%," . $this->user['uid'] . ",%");
                $map['gygy_members.uid']     = $this->user['uid'];
                $map['_logic']  = 'or';
                break;
        }

        $where['_complex'] = $map;
//        $u_model = M('members')->field('uid,username')->where($where);
////        $u_page = $this->getPageInfo($u_model,$pageSize);
//        $userList          = $u_model->where($where)/*->limit($u_page->firstRow,$u_page->listRows)*/->select();
//        $userData = array();
//        $userStr = "";
//        foreach ($userList as $user) {
//            $userStr                = $userStr . $user['uid'] . ',';
//            $userData[$user['uid']] = $user;
//        }

//        $where = array();
        // 彩种限制
        if ($para['type']) {
            $where['b.type'] = $para['type'];
        }

        // 时间限制
        if ($para['fromTime'] && $para['toTime']) {
            $where['b.actionTime'] = array('between', array(strtotime($para['fromTime']), strtotime($para['toTime'])));
        } elseif ($para['fromTime']) {
            $where['b.actionTime'] = array('egt', strtotime($para['fromTime']));
        } elseif ($para['toTime']) {
            $where['b.actionTime'] = array('elt', strtotime($para['toTime']));
        } else {
            if ($GLOBALS['fromTime'] && $GLOBALS['toTime']) {
                $where['b.actionTime'] = array('between', array($GLOBALS['fromTime'], $GLOBALS['toTime']));
            }
        }
        $today = strtotime(date('Y-m-d'));
        if($para['select_days']==1){
            //今天
            $where['b.actionTime'] = array('between', array($today, $today+86399));
        }
        if($para['select_days']==2){
            //昨天
            $where['b.actionTime'] = array('between', array($today-86400, $today-1));
        }
        if($para['select_days']==3){
            //前天
            $where['b.actionTime'] = array('between', array($today-86400*2, $today-86401));
        }

        // 投注状态限制
        if ($para['state']) {
            switch ($para['state']) {
                case 1:
                    // 已派奖
                    $where['b.zjCount'] = array('gt', 0);
                    break;
                case 2:
                    // 未中奖
                    $where['b.zjCount']   = 0;
                    $where['b.lotteryNo'] = array('neq', '');
                    $where['b.isDelete']  = 0;

                    break;
                case 3:
                    // 未开奖
                    $where['b.lotteryNo'] = array('eq', '');
                    break;
                case 4:
                    // 追号
                    $where['b.zhuiHao'] = 1;
                    break;
                case 5:
                    // 撤单
                    $where['b.isDelete'] = 1;
                    break;
            }
        }

        //单号
        if ($para['betId'] && $para['betId'] != '输入单号') {
            if (strlen($para['betId']) > 20){
                $this->assign('data',[]);
                return;
            }
            $where['b.wjorderId'] = $para['betId'];
        }
//        $where['uid'] = array('in', $userStr);
        $b_model = M('members')->field('b.id,b.wjorderId,b.uid,b.username,
                        b.type,b.playedId,b.actionNo,b.beiShu,b.mode,b.lotteryNo,
                        b.isDelete,b.zjCount,b.bonus,b.actionNum,b.fpEnable,b.actionTime')
                        ->join('gygy_bets b on b.uid=gygy_members.uid')
                        ->where($where);
        $b_page = $this->listsPage($b_model,$pageSize);
        $betList      = $b_model->where($where)->field('b.id,b.wjorderId,b.uid,b.username,
                        b.type,b.playedId,b.actionNo,b.beiShu,b.mode,b.lotteryNo,
                        b.isDelete,b.zjCount,b.bonus,b.actionNum,b.fpEnable,b.actionTime')
                            ->join('gygy_bets b on b.uid=gygy_members.uid')
                            ->order('b.actionTime desc')
                            ->limit($b_page->firstRow,$b_page->listRows)
                            ->select();
//        dump($b_model->getLastSql());exit;
        $this->assign('data',$betList);
//        $this->recordList($betList,$pageSize);
    }

    final public function searchRecord()
    {
        $this->search();
        $this->display('Team/record-list');
    }

    /*盈亏报表*/
    final public function report()
    {

        $this->reportSearch();
        if (!I('get.')) {
            $this->display('Team/report');
        } else {
            $this->display('Team/report-list');
        }

    }
    final public function searchReport()
    {
        $this->reportSearch();
        $this->display('Team/report-list');
    }
    final public function reportSearch()
    {
        $para = I('get.');
        $uid = $this->user['uid'];
        if(isset($para['toTime'])){
            $toTime = strtotime($para['toTime']);
        }else{
            $toTime = strtotime(date('Ymd',time()));
        }
        if(isset($para['fromTime'])){
            $fromTime = strtotime($para['fromTime']);
        }else{
            $fromTime = strtotime(date('Ymd',time()));
        }

        if($toTime-$fromTime>=86400*31){
            echo '<font color="red" size="15">时间区间过大</font>';exit();
        }
        $all = array();
        /*$m = M('members')->where("uid = {$uid} or parentId = {$uid}")->select();
        $where = " (m.uid = {$uid} or m.parentId = {$uid})";
        $sql = " SELECT ";
        $sql .= " m.uid, ";
        $sql .= " m.username, ";
        $sql .= " m.type, ";
        $sql .= " sum(r.coin) as coin,";
        $sql .= " sum(r.rechargeAmount) as rechargeAmount,";
        $sql .= " sum(r.cashAmount) as cashAmount,";
        $sql .= " sum(r.betAmount) as betAmount,";
        $sql .= " sum(r.zjAmount) as zjAmount,";
        $sql .= " sum(r.fanDianAmount) as fanDianAmount,";
        $sql .= " sum(r.brokerageAmount) as brokerageAmount,";
        $sql .= " sum(r.zyk) as zyk";
        $sql .= " FROM gygy_members m";
        $sql .= " left join gygy_coin_log_report r";
        $sql .= " on m.uid=r.uid";
        $sql .= " where {$where} and r.actionTime>=$fromTime and r.actionTime<=$toTime";
        $sql .= " group by m.uid";
        */
        $sql = "SELECT * FROM (
SELECT 
 m.username,
 m.uid,
 if(m.type=1,'代理','会员') AS type,
  sum(r.coin) AS coin,
	sum(r.rechargeAmount) AS rechargeAmount,
	sum(r.cashAmount) AS cashAmount,
	sum(r.betAmount) AS betAmount,
	sum(r.zjAmount) AS zjAmount,
	sum(r.fanDianAmount) AS fanDianAmount,
	sum(r.brokerageAmount) AS brokerageAmount,
	sum(r.zyk) AS zyk
FROM gygy_members as m LEFT JOIN gygy_coin_log_report as r ON r.uid=m.uid AND r.actionTime BETWEEN {$fromTime} AND {$toTime}
WHERE m.uid = {$uid} 
GROUP BY m.uid
UNION
SELECT  * FROM 
(SELECT
        mm.username,
         mm.uid,
        if(mm.type=1,'代理','会员') AS type,
        sum(r2.coin) AS coin,
        sum(r2.rechargeAmount) AS rechargeAmount,
        sum(r2.cashAmount) AS cashAmount,
        sum(r2.betAmount) AS betAmount,
        sum(r2.zjAmount) AS zjAmount,
        sum(r2.fanDianAmount) AS fanDianAmount,
        sum(r2.brokerageAmount) AS brokerageAmount,
        sum(r2.zyk) AS zyk
     FROM gygy_members mm 
    LEFT JOIN (
    SELECT
        m.uid,
        m.username,
        m.parentId,
        m.parents,
        r.coin,
        r.rechargeAmount,
        r.cashAmount,
        r.betAmount,
        r.zjAmount,
        r.fanDianAmount,
        r.brokerageAmount,
        r.zyk
    FROM
        gygy_members AS m
    LEFT JOIN gygy_coin_log_report AS r ON m.uid = r.uid
    WHERE
        FIND_IN_SET({$uid}, m.parents) AND m.uid <> {$uid}
    AND r.actionTime BETWEEN {$fromTime} AND {$toTime}
    ) as r2
    ON FIND_IN_SET(mm.uid,r2.parents)
    WHERE mm.parentId = {$uid}
    GROUP BY mm.uid
    ORDER BY mm.username asc) t
) r1 ";
        $Model = new \Think\Model();
//        $data = $Model->query("CALL sp_getCoins({$uid},{$fromTime},{$toTime})");
        $data = $Model->query($sql);
        foreach ($data as $item=>$sub) {
            $all['coin'] += $sub['coin'];
            $all['rechargeAmount'] += $sub['rechargeAmount'];
            $all['cashAmount'] += $sub['cashAmount'];
            $all['betAmount'] += $sub['betAmount'];
            $all['zjAmount'] += $sub['zjAmount'];
            $all['fanDianAmount'] += $sub['fanDianAmount'];
            $all['brokerageAmount'] += $sub['brokerageAmount'];
            $all['zyk'] += $sub['zyk'];

            $data[$item]['coin'] = floatval($sub['coin']);
            $data[$item]['rechargeAmount'] = floatval($sub['rechargeAmount']);
            $data[$item]['cashAmount'] = floatval($sub['cashAmount']);
            $data[$item]['betAmount'] = floatval($sub['betAmount']);
            $data[$item]['zjAmount'] = floatval($sub['zjAmount']);
            $data[$item]['fanDianAmount'] = floatval($sub['fanDianAmount']);
            $data[$item]['brokerageAmount'] = floatval($sub['brokerageAmount']);
            $data[$item]['zyk'] = floatval($sub['zyk']);
        }
        /*$dataList = array();
        foreach ($m as $item=>$m_sub) {
            $cur = array();
            $cur['username'] = $m_sub['username'];
            $cur['type'] = $m_sub['type']==1?'代理':'会员';
            $cur['rechargeAmount'] = 0;
            $cur['cashAmount'] = 0;
            $cur['betAmount'] = 0;
            $cur['zjAmount'] = 0;
            $cur['fanDianAmount'] = 0;
            $cur['brokerageAmount'] = 0;
            $cur['zyk'] = 0;
            foreach ($data as $item=>$r_sub) {
                if($r_sub['uid']==$m_sub['uid']){
//                    $cur['coin'] = $r_sub['coin'];
                    $cur['rechargeAmount'] = $r_sub['rechargeAmount'];
                    $cur['cashAmount'] = $r_sub['cashAmount'];
                    $cur['betAmount'] = $r_sub['betAmount'];
                    $cur['zjAmount'] = $r_sub['zjAmount'];
                    $cur['fanDianAmount'] = $r_sub['fanDianAmount'];
                    $cur['brokerageAmount'] = $r_sub['brokerageAmount'];
                    $cur['zyk'] = $r_sub['zyk'];
                }
            }
            array_push($dataList,$cur);
        }*/
        $this->assign('data',$data);
        //团队
        $this->assign('all', $all);
        $this->assign('para', $para);
        $this->assign('user', $this->user);
    }

    //会员管理
    final public function member()
    {
        //dump(I('get.'));
        $this->memberSearch();
        if (!I('get.')) {
            $this->display('Team/member');
        } else {
            $this->display('Team/member-list');
        }

    }
    final public function searchMember()
    {
        $this->memberSearch();
        $this->display('Team/member-list');
    }

    final public function memberSearch()
    {
        $utype = I('utype');
        $pageSize = I("PageSize") > 10 ? I("PageSize") : 10;
        switch ($utype) {
            case 1:
                // 我自己
                $where['uid'] = $this->user['uid'];
                //  $where['username'] = array('like', "%" . I('username') . "%");
                break;
            case 2:
                // 直属下级
                $uid = $this->user['uid'];
                if (I('uid')) {
                    $uid = I('uid');
                }
                $where['parentId'] = $uid;
                break;
            case 3:
                // 所有下级
//                $where['parents'] = array('like', "%" . $this->user['uid'] . ",%");
                $uid = $this->user['uid'];
                if (I('uid')) {
                    $uid = I('uid');
                }
                $where['_string'] = "find_in_set({$uid},parents)";
                break;
            case 4:
                if(I('uid')){
                    $where['uid'] =  I('uid');
                }
                break;
            default:
                //所有人
                $where['uid'] = $this->user['uid'];
                /*            $where['parents'] = array('like', "%," . $this->user['uid'] . ",%");
                             $where['uid']     = $this->user['uid'];
                            $where['_logic']  = 'or';*/
                break;

        }
        if (I('username') && I('username') != '用户名' && ($utype == 3 || $utype == 2)) {
            if (mb_strlen(I('username')) > 20){
                $this->assign('user', []);
                return;
            }

            // 按用户名查找时
            // 只要符合用户名且是自己所有下级的都可查询
            // 用户名用模糊方式查询
            $where['username'] = array('like', "%" . I('username') . "%");

        }
        // dump($where);
        $page = $this->listsPage(M('members')->where($where),$pageSize);
        $userList = M('members')->where($where)
            ->limit($page->firstRow,$page->listRows)
            ->order('username')
            ->select();
        //dump($userList);
        foreach ($userList as $k => $v){
            // 查询下级会员最高返点
            $nextMax = M('members')->where('parentId='.$v['uid'])->order('fanDian desc')->find();
            if (!$nextMax){
                $userList[$k]['nextMaxFandian'] = 0;
            }else{
                $userList[$k]['nextMaxFandian'] = $nextMax['fanDian']+0.1;
            }

            // 查询团队余额
            $map            = array();
//            $map['parents'] = array('like', '%' . $v['uid'] . '%');
            $map['_string'] = "find_in_set({$v['uid']},parents)";
            $userList[$k]['team_balance'] = M('members')->where($map)->sum('coin');
        }
        //dump($userList);
        $this->assign('utype',$utype);
//        $this->recordList($userList, $pageSize);
        $this->assign('data', $userList);
       // dump($this->user);
        $this->assign('user', $this->user);
    }

    final public function userUpdate()
    {

        $user = M('members')->find(I('id'));
        $this->assign('userData', $user);

        $parentData = M('members')->find($user['parentId']);

        if ($userData['parentId']) {
            $parentData = $parentData;
        } else {
            $this->getSystemSettings();
            $parentData['fanDian']    = $this->settings['fanDianMax'];
            $parentData['fanDianBdw'] = $this->settings['fanDianBdwMax'];
        }
        $sonFanDianMax = M('members')->where(array('isDelete' => 0, 'parentId' => I('uid')))->field('max(fanDian) sonFanDian, max(fanDianBdw) sonFanDianBdw')->find();

        $this->assign('parentData', $parentData);
        $this->assign('sonFanDianMax', $sonFanDianMax);
        $this->display('Team/update-menber');
    }

    final public function userUpdateed()
    {
        if (I('fanDian') < 0) {
            $this->error('返点不能小于0');
        }

        $user = M('members')->where(array('username' => I('username')))->find();
        if ($this->user['uid'] != $user['parentId']) {
            $this->error('不是你的直属下级，不可以修改');
        }

        if ($this->user['fanDian'] <= I('fanDian')) {
            $this->error('返点不可以大于上级');
        }

        $sonFanDianMax = M('members')->where(array('isDelete' => 0, 'parentId' => $user['uid']))->field('max(fanDian) sonFanDian, max(fanDianBdw) sonFanDianBdw')->find();

        if ($sonFanDianMax['sonFanDian']) {
            if ($sonFanDianMax['sonFanDian'] >= I('fanDian')) {
                $this->error('返点不可以小于直属下级' . $sonFanDianMax['sonFanDian']);
            }

        }

        $data['uid']     = $user['uid'];
        $data['fanDian'] = I('fanDian');
        $data['type']    = I('type');

        if (M('members')->save($data)) {
            $this->success('修改成功', U('Team/member'));
        } else {
            $this->error('修改失败');
        }
    }

    final public function chongzhiMember(){
        $data = M('params')->where('name="switchCharge"')->find();
        if(isset($data['value']) && !$data['value']) {
            $this->error('充值网络异常，请稍后再试！');
        }
        //转账次数限制
        if(!$this->zzcs()){
            $this->error("每天只能转账一次");
        }
        //转账限制
        if(!$this->zzxz()){
            $this->error("投注金额必须超过充值金额的30%");
        }
        $me = M('members')->where(array('uid' => $this->user['uid']))->find();
        $menewmoney = $me['coin'] - I('money');
        if($menewmoney < 0){
            $this->error('余额不足，请先充值余额');
            exit();
        }

        if(empty($me['coinPassword']) || ($me['coinPassword']!= think_ucenter_md5(I('zjmm'), UC_AUTH_KEY))){
            $this->error('资金密码未设置或不正确');
            exit();
        }

        // 银行卡验证
        $bankno = I('bankno');
        if($this->user['is_test'] != 1){
            if(empty($bankno)){
                $this->error('验证银行卡号不能为空！');
            }
            $banks = M('member_bank')->where(array('uid'=>$this->user['uid'],'enable'=> 1,'account'=>$bankno))->count();
            if($banks == 0){
                $this->error('验证银行卡号不正确！');
            }
        }

        if(I('type') == 0){
            $user = M('members')->where(array('uid' => I('userid')))->find();
            $p_arr = explode(',',$user['parents']);
            if (!in_array($this->user['uid'],$p_arr)) {
                $this->error('不是你的直属下级，不可以充值');
                exit();
            }
        }else{
            $username = I('username');
            if(empty($username)){
                $this->error('请输入转出接收账号');
                exit();
            }else{
                $user = M('members')->where(array('username' => I('username')))->find();
                if(empty($user)){
                    $this->error('转出接收账号有误，请确认账号');
                    exit();
                }else if($this->user['type']==0&&$user['type']==0){
                    //会员之间不允许转账
                    $this->error('会员之间不允许转账');
                    exit();
                }else{
                    if(!in_array($user['uid'],explode(',',$me['parents'])) || ($me['uid']==$user['uid'])){
                        $this->error('不是你的上级，不可以转账');
                        exit();

                    }
                }
            }
        }
        $usernewmoney = $user['coin'] + I('money');
        $data1['uid']   = $me['uid'];
        $data1['coin']  = $menewmoney;
        $data2['uid']   = $user['uid'];
        $data2['coin']  = $usernewmoney;
        //if (M('members')->save($data1)) {
        $model = new \Think\Model();
        $model->startTrans();
        $isSuc1 = $this->addCoin(array(
            'uid'       => $me['uid'],
            'coin'      => "-".I('money'),
            'liqType'   => 200,
            'info' => "资金转移到用户：".$user['username'],
        ));
        if($isSuc1 == true){
            $isSuc2 = $this->addCoin(array(
                'uid'       => $user['uid'],
                'coin'      => I('money'),
                'liqType'   => 201,
                'info' => "由用户转入资金：".$me['username'],
            ));
        }else{
            Log::record('扣除资金失败');
            $model->rollback();
            $this->error('操作失败');exit;
        }
        if($isSuc2 == true){
            $rechage = array(
                'uid'=>$user['uid'],
                'username'=>$user['username'],
                'rechargeId'=>$this->getRechId(),
                'amount'=>I('money'),
                'actionIP'=>$this->ip(true),
                'actionTime'=>$this->time,
                'state'=>'3',
                'info' => '来自下级'.$me['username'].'的转账:'.I('money').'元'
            );
            $isSuc3 = M('member_recharge')->add($rechage);
            if (!$isSuc3) {
                Log::record('给下级充值资金失败.');
                $model->rollback();
                $this->error('操作失败');
            }
            $model->commit();
            $this->success('操作成功', U('Team/member'));
        }else{
            Log::record('给下级充值资金失败');
            $model->rollback();
            $this->error('操作失败');
        }
    }

    final private function getRechId()
    {
        $rechargeId = $this->guid();
        if (M('member_recharge')->where(array('rechargeId' => $rechargeId))->find()) {
            getRechId();
        } else {
            return $rechargeId;
        }
    }

    function guid() {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8);
        $uuid .= substr($chars,8,4);
        $uuid .= substr($chars,12,4);
        $uuid .= substr($chars,16,4);
        $uuid .= substr($chars,20,12);
        return $uuid;
    }

    final public function addMember()
    {
        //print_r($this->getMyUserCount());
        $this->display('Team/add-member');
    }

    final public function insertMember()
    {
        $username = I('username');
        $password = I('password');
        if (!$username . trim() || !$password . trim()) {
            $this->error('用户名或密码不能为空');
        }

        if (!preg_match("/^[0-9a-zA-Z]{6,15}$/", I('username'))) {
            $this->error('用户名只能由英文和数字组成，长度6-15个字符');
        }

        if (M('members')->where(array('username' => I('username')))->find()) {
            $this->error('用户' . I('username') . '已经存在');
        }

        if (I('fanDian') < 0) {
            $this->error('返点不能小于0');
        }

        if ($this->user['fanDian'] <= I('fanDian')) {
            $this->error('返点不可以大于上级');
        }

        $para['parentId'] = $this->user['uid'];
        $para['parents']  = $this->user['parents'];
        $para['is_test']  = $this->user['is_test'];
        $para['password'] = think_ucenter_md5(I('password'), UC_AUTH_KEY);
        $para['coinPassword']=$para['password'];
        $para['username'] = I('username');
        $para['qq']       = I('qq');
        $para['type']     = I('type');
        $para['regIP']    = $this->ip(true);
        $para['regTime']  = $this->time;

        if (!$para['nickname']) {
            $para['nickname'] = $para['username'];
        }

        if (!$para['name']) {
            $para['name'] = $para['username'];
        }

        // 查检返点设置
        if ($para['fanDian'] = floatval(I('fanDian'))) {
            $this->getSystemSettings();
            if ($para['fanDian'] % $this->settings['fanDianDiff']) {
                $this->error(sprintf('返点只能是%.1f%的倍数', $this->settings['fanDianDiff']));
            }

        } else {
            $para['fanDian'] = 0;
        }

        M()->startTrans();
        if ($lastid = M('members')->add($para)) {
            if (M('members')->save(array('uid' => $lastid, 'parents' => $this->user['parents'] . ',' . $lastid))) {
                M()->commit(); //成功则提交
                $this->success('添加会员成功', U('team/member'));
            }
        }

        M()->rollback(); //不成功，则回滚
        $this->error('添加会员失败');

    }

    /*账变列表*/
    final public function coin()
    {
        $this->coinSearch();
        if (!I('get.')) {
            $this->display('Team/coin');
        } else {
            $this->display('Team/coin-list');
        }

    }
    final public function searchCoin()
    {
        $this->coinSearch();

        $this->display('Team/coin-list');
    }
    final public function coinSearch()
    {
        $this->getTypes();
        $this->getPlayeds();
        $this->assign('types', $this->types);
        $this->assign('playeds', $this->playeds);
        $para  = I('get.');
        $pageSize = $para['PageSize'] > 10 ? $para['PageSize'] : 10;
        $where = array();
        // 用户名限制
        if ($para['username'] && $para['username'] != '用户名') {
            if (mb_strlen($para['username']) > 20){
                $this->assign('data',[]);
                return;
            }
            // 按用户名查找时
            // 只要符合用户名且是自己所有下级的都可查询
            // 用户名用模糊方式查询
            $where['m.username'] = $para['username'];
//            $where['m.parents']  = array('like', "%," . $this->user['uid'] . ",%");
            $where['_string'] = "find_in_set({$this->user['uid']},m.parents)";
        }
        //用户类型限制
        switch ($para['utype']) {
            case 1:
                //我自己
                $map['m.uid'] = $this->user['uid'];
                break;
            case 2:
                //直属下线
                $map['m.parentId'] = $this->user['uid'];
                break;
            case 3:
                // 所有下级
                $where['_string'] = "find_in_set({$this->user['uid']},m.parents)";
                break;
            default:
                //所有人
                $where['_string'] = "find_in_set({$this->user['uid']},m.parents)";
                $map['m.uid']     = $this->user['uid'];
                $map['_logic']  = 'or';
                break;
        }
        $where['_complex'] = $map;
        // 账变类型限制
        if ($para['liqType']) {
            $where['gygy_coin_log.liqType'] = $para['liqType'];
            if ($para['gygy_coin_log.liqType'] == 2) {
                $where['gygy_coin_log.liqType'] = array('between', '2,3');
            }
        }
        $time  = $para['fromTime'];
        if($para&&empty($time)){
            echo '<font color="red" size="15">时间必须选择</font>';exit;
        }else{
            $time = strtotime(date('Y-m-d',strtotime($time)));
            $where['gygy_coin_log.actionTime'] = array('between', array($time, $time+86399));
        }
        $model = M('coin_log')->field('
                    gygy_coin_log.uid,gygy_coin_log.actionTime,gygy_coin_log.liqType,gygy_coin_log.extfield0,gygy_coin_log.extfield1,
                    gygy_coin_log.coin,gygy_coin_log.userCoin,
                    b.id,b.actionNo,b.mode,b.type,b.playedId,b.wjorderId,m.uid,m.username
                ')
                ->join("gygy_bets b on b.id=gygy_coin_log.extfield0")
                ->join("gygy_members m on m.uid=gygy_coin_log.uid")
                ->where($where);
        $page = $this->listsPage($model,$pageSize);
        $data = $model->field('
                    gygy_coin_log.uid,gygy_coin_log.actionTime,gygy_coin_log.liqType,gygy_coin_log.extfield0,gygy_coin_log.extfield1,
                    gygy_coin_log.coin,gygy_coin_log.userCoin,
                    b.id,b.actionNo,b.mode,b.type,b.playedId,b.wjorderId,m.uid,m.username
                ')
                ->join("gygy_bets b on b.id=gygy_coin_log.extfield0")
                ->join("gygy_members m on m.uid=gygy_coin_log.uid")
                ->order('gygy_coin_log.actionTime desc')
                ->where($where)
                ->limit($page->firstRow,$page->listRows)
                ->select();
        $this->assign('data',$data);
    }

    //团队统计
    final public function team()
    {

        $teamAll  = M('members')->where(array('isDelete' => 0, 'parents' => array('like', '%,' . $this->user['uid'] . ',%')))->field('sum(coin) coin, count(uid) count')->find();
        $teamAll2 = M('members')->where(array('isDelete' => 0, 'parentId' => $this->user['uid']))->field('count(uid) count')->find();

        $this->assign('teamAll', $teamAll);
        $this->assign('teamAll2', $teamAll2);
        $this->assign('user', $this->user);
        $this->display('Team/team');
    }

    //提现记录
    final public function cashRecord()
    {
        $this->cashSearch();
        if (!I('get.')) {
            $this->display('Team/cashRecord');
        } else {
            $this->display('Team/cash-list');
        }

    }
    final public function searchCashRecord()
    {
        $this->cashSearch();
        $this->display('Team/cash-list');
    }
    final public function cashSearch()
    {

        $para = I('get.');

        // 用户名限制
        if ($para['username'] && $para['username'] != '用户名') {
            if (mb_strlen($para['username']) > 20){
                $this->assign('data',[]);
                return;
            }
            // 按用户名查找时
            // 只要符合用户名且是自己所有下级的都可查询
            // 用户名用模糊方式查询
            $where['username'] = array('like', "%" . I('username') . "%");
            $where['parents']  = array('like', "%," . $this->user['uid'] . ",%");
        } else {
            //用户类型限制
            switch ($para['utype']) {
                case 1:
                    //我自己
                    $map['uid'] = $this->user['uid'];
                    break;
                case 2:
                    //直属下线
                    $map['parentId'] = $this->user['uid'];
                    break;
                case 3:
                    // 所有下级
                    $map['parents'] = array('like', '%,' . $this->user['uid'] . ',%');
                    break;
                default:
                    //所有人
//                    $map['parents'] = array('like', "%," . $this->user['uid'] . ",%");
                    $map['uid']     = $this->user['uid'];
//                    $map['_logic']  = 'or';
                    break;
            }
        }

        $where['_complex'] = $map;
        $userList          = M('members')->field('uid,username')->where($where)->select();

        $userData = array();
        foreach ($userList as $user) {
            $userStr                = $userStr . $user['uid'] . ',';
            $userData[$user['uid']] = $user;
        }

        $where = array();

        // 时间限制
        if ($para['fromTime'] && $para['toTime']) {
            $where['actionTime'] = array('between', array(strtotime($para['fromTime']), strtotime($para['toTime'])));
        } elseif ($para['fromTime']) {
            $where['actionTime'] = array('egt', strtotime($para['fromTime']));
        } elseif ($para['toTime']) {
            $where['actionTime'] = array('elt', strtotime($para['toTime']));
        } else {
            if ($GLOBALS['fromTime'] && $GLOBALS['toTime']) {
                $where['actionTime'] = array('between', array($GLOBALS['fromTime'], $GLOBALS['toTime']));
            }
        }
        $pageSize = $para['PageSize'] > 10 ? $para['PageSize'] : 10;
        $where['uid'] = array('in', $userStr);
        $model = M('member_cash')->field('id,uid,actionTime,amount,account,username,state,bankId,info')->where($where);
        $page = $this->listsPage($model,$pageSize);
        $cashList     = $model->where($where)->limit($page->firstRow,$page->listRows)->order('id desc')->select();

//        $cashcount    = M('member_cash')->where(array('state' => 1))->select();


        $i = 0;
        foreach ($cashList as $cash) {
            $data[$i] = array_merge($cash, $userData[$cash['uid']]);
            $i++;
        }

        $bankList = M('bank_list')->field('id,name')->where(array('isDelete' => 0))->order('id')->select();
        $bankData = array();
        foreach ($bankList as $bank) {
            $bankData[$bank['id']] = $bank;
        }
        $this->assign('bankData', $bankData);
//        $this->assign('cashcount', $cashcount);
//        $this->recordList($data,$pageSize);
        $this->assign('data',$data);
    }

    //充值记录
    final public function rechargeRecord()
    {
        $this->rechargeSearch();
        if (!I('get.')) {
            $this->display('Team/rechargeRecord');
        } else {
            $this->display('Team/recharge-list');
        }
    }
    final public function searchrechargeRecord()
    {
        $this->rechargeSearch();
        $this->display('Team/recharge-list');
    }
    final public function rechargeSearch()
    {
        $para = I('get.');
        $pageSize = $para['PageSize'] > 10 ? $para['PageSize'] : 10;
        // 用户名限制
        if ($para['username'] && $para['username'] != '用户名') {
            if (mb_strlen($para['username']) > 20){
                $this->assign('data',[]);
                return;
            }
            // 按用户名查找时
            // 只要符合用户名且是自己所有下级的都可查询
            // 用户名用模糊方式查询
            $where['username'] = array('like', "%" . I('username') . "%");
            $where['parents']  = array('like', "%," . $this->user['uid'] . ",%");
        } else {
            //用户类型限制
            switch ($para['utype']) {
                case 1:
                    //我自己
                    $map['uid'] = $this->user['uid'];
                    break;
                case 2:
                    //直属下线
                    $map['parentId'] = $this->user['uid'];
                    break;
                case 3:
                    // 所有下级
                    $map['parents'] = array('like', '%,' . $this->user['uid'] . ',%');
                    break;
                default:
                    //所有人
                    /*$map['parents'] = array('like', "%," . $this->user['uid'] . ",%");*/
                    $map['uid']     = $this->user['uid'];
                    /*$map['_logic']  = 'or';*/
                    break;
            }
        }

        $where['_complex'] = $map;
        $userList          = M('members')->field('uid,username')->where($where)->select();

        $userData = array();
        $userStr = "";
        foreach ($userList as $user) {
            $userStr                = $userStr . $user['uid'] . ',';
            $userData[$user['uid']] = $user;
        }

        $where = array();

        // 时间限制
        if ($para['fromTime'] && $para['toTime']) {
            $where['actionTime'] = array('between', array(strtotime($para['fromTime']), strtotime($para['toTime'])));
        } elseif ($para['fromTime']) {
            $where['actionTime'] = array('egt', strtotime($para['fromTime']));
        } elseif ($para['toTime']) {
            $where['actionTime'] = array('elt', strtotime($para['toTime']));
        } else {
            if ($GLOBALS['fromTime'] && $GLOBALS['toTime']) {
                $where['actionTime'] = array('between', array($GLOBALS['fromTime'], $GLOBALS['toTime']));
            }
        }

        $where['uid'] = array('in', $userStr);
        $model = M('member_recharge')->field('id,uid,username,rechargeId,amount,rechargeAmount,mBankId,state,info')->where($where);
        $page = $this->listsPage($model,$pageSize);
        $cashList     = $model->where($where)
                                ->limit($page->firstRow,$page->listRows)
                                ->order('id desc')
                                ->select();
        $i = 0;
        foreach ($cashList as $cash) {
            $data[$i] = array_merge($cash, $userData[$cash['uid']]);
            $i++;
        }

        $bankList = M('bank_list')->field('id,name')->where(array('isDelete' => 0))->order('id')->select();
        $bankData = array();
        foreach ($bankList as $bank) {
            $bankData[$bank['id']] = $bank;
        }
        $this->assign('bankData', $bankData);
        $this->assign('data',$data);
//        $this->recordList($data,$pageSize);
    }

    //推广链接
    final public function linkList()
    {

        $list = M('links')->where(array('uid' => $this->user['uid']))->order('fanDian desc')->select();
        $this->assign('data', $list);
        $this->display('Team/link-list');
    }

    final public function addLink()
    {
        if (IS_POST) {
            //$para=$_POST;
            $para['regIP']   = $this->ip(true);
            $para['regTime'] = $this->time;
            $para['uid']     = $this->user['uid'];
            $para['type']    = I('type', '', 'intval');
            // 查检返点设置
            if ($para['fanDian'] = floatval(I('fanDian'))) {
                if ($para['fanDian'] % $this->settings['fanDianDiff']) {
                    $this->error(sprintf('返点只能是%.1f%的倍数', $this->settings['fanDianDiff']));
                }

            } else {
                $para['fanDian'] = 0;
            }

            if (I('fanDian') >= $this->user['fanDian']) {
                $this->error('下级返点不能大于自己的返点');
            }

            $para['fanDianBdw'] = floatval(I('fanDianBdw'));

            if (M('links')->where(array('uid' => $this->user['uid'], 'fanDian' => $para['fanDian']))->find()) {
                $this->error('此链接已经存在');
            }

            if (M('links')->add($para)) {
                $this->success('添加链接成功', U('Team/linklist'));
            } else {
                $this->error('添加链接失败');
            }

        } else {
            $this->display('Team/add-link');
        }
    }

    /*编辑注册链接*/
    final public function linkUpdate()
    {
        if (IS_POST) {

            // 查检返点设置
            if ($para['fanDian'] = floatval(I('fanDian'))) {
                if ($para['fanDian'] % $this->settings['fanDianDiff']) {
                    $this->error(sprintf('返点只能是%.1f%的倍数', $this->settings['fanDianDiff']));
                }

            } else {
                $para['fanDian'] = 0;
            }

            if (I('fanDian') >= $this->user['fanDian'] || I('fanDianBdw') >= $this->user['fanDianBdw']) {
                $this->error('下级返点不能大于自己的返点');
            }

            $para['fanDianBdw'] = floatval(I('fanDianBdw'));
            $para['lid']        = intval(I('lid'));

            if (!M('links')->where(array('uid' => $this->user['uid'], 'lid' => I('lid')))->find()) {
                $this->error('此链接不存在');
            }

            if (M('links')->save($para)) {
                $this->success('修改链接成功');
            } else {
                $this->error('修改链接失败');
            }

        } else {
            $linkData = M('links')->where(array('lid' => I('lid'), 'uid' => $this->user['uid']))->find();

            if ($linkData['uid']) {
                $parentData = M('members')->field('fanDian, fanDianBdw')->find($this->user['uid']);
            } else {
                $parentData['fanDian']    = $this->settings['fanDianMax'];
                $parentData['fanDianBdw'] = $this->settings['fanDianBdwMax'];
            }

            $this->assign('linkData', $linkData);
            $this->assign('parentData', $parentData);

            $this->display('Team/update-link');
        }

    }

    final public function deletelink()
    {
        if (IS_POST) {
            if (M('links')->where(array('lid' => I('lid'), 'uid' => $this->user['uid']))->delete()) {
                $this->success('删除成功', U('Team/linklist'));
            } else {
                $this->error('删除失败');
            }

        }
    }
    final public function getlink()
    {
        $linkData = M('links')->where(array('lid' => I('lid'), 'uid' => $this->user['uid']))->find();

        if ($linkData['uid']) {
            $parentData = M('members')->field('fanDian, fanDianBdw')->find($this->user['uid']);
        } else {
            $parentData['fanDian']    = $this->settings['fanDianMax'];
            $parentData['fanDianBdw'] = $this->settings['fanDianBdwMax'];
        }

        $this->assign('linkData', $linkData);
        $this->assign('parentData', $parentData);
        $this->display('get-link');
    }

    final public function turnMoney()
    {
        $this->display('Team/turn-money');
    }
    final public function turnRecharge()
    {
        $me = M('members')->find($this->user['uid']);
        //dump($me);
        //dump('--'.think_ucenter_md5(I('coinpwd'), UC_AUTH_KEY));
        if ($me['coinPassword'] != think_ucenter_md5(I('coinpwd'), UC_AUTH_KEY)) {
            $this->error('资金密码不正确');
        }

        if (intval(I('amount')) <= 0) {
            $this->error('转账金额必须大于0');
        }

        if ($me['coin'] - I('amount') < 0) {
            $this->error('您的余额不足');
        }

        $where['username'] = I('username');
        $child             = M('members')->where($where)->find();
        if (!$child) {
            $this->error('此用户不存在');
        }

        if (strpos($child['parents'], ',' . $me['uid'] . ',') === false) {
            $this->error('此用户不是你的下级');
        }

        // 添加本人资金流动日志
        $this->addCoin(array(
            'uid'       => $me['uid'],
            'type'      => 0,
            'liqType'   => 12,
            'info'      => '用户[' . $me['username'] . ']转账给其下级[' . I('username') . ']' . I('amount') . '元',
            'extfield0' => I('amount'),
            'coin'      => -I('amount'),
            'fcoin'     => 0,
        ));

        // 添加下级资金流动日志
        $this->addCoin(array(
            'uid'       => $child['uid'],
            'type'      => 0,
            'liqType'   => 12,
            'info'      => '用户[' . $me['username'] . ']转账给其下级[' . I('username') . ']' . I('amount') . '元',
            'extfield0' => I('amount'),
            'coin'      => I('amount'),
            'fcoin'     => 0,
        ));
        $rechage = array(
            'uid'=>$child['uid'],
            'username'=>I('username'),
            'rechargeId'=>0,
            'amount'=>I('amount'),
            'actionIP'=>$this->ip(true),
            'actionTime'=>$this->time,
            'state'=>'2',
            'info' => '来自上级'.$me['username'].'的转账:'.I('amount').'元'
        );
        M('member_recharge')->add($rechage);

        $this->success('给下级转账成功');
    }
    //充值限制
    public function zzxz(){
        if($this->user['type']==1){
            return true;
        }
        //充值金额
        $gRs = M('member_recharge')->where(array('uid' => $this->user['uid'], 'state' => array('in', '1,2,9'), 'isDelete' => 0))->field('sum(case when rechargeAmount>0 then rechargeAmount else amount end) as rechargeAmount')->find();
        //投注金额
//        $bet = M('bets')->where(array('uid' => $this->user['uid'], 'isDelete' => 0, 'lotteryNo' => array('neq', '')))->field('sum(mode*beiShu*actionNum) as betAmout')->find();
        $user = M('members')->find($this->user['uid']);
        $betAmout = $user['scoreTotal'];
        if(empty($gRs)&&empty($betAmout)){
            return false;
        }else{
            $rechargeAmount = $gRs["rechargeAmount"];
            $bfb = round($betAmout/$rechargeAmount,2)*100;
            if($bfb>=30){
                return true;
            }else{
                return false;
            }
        }
    }
    //转账次数限制
    public function zzcs(){
        //代理商不限制
        if($this->user['type']==1){
            return true;
        }
        $map = array(
            'uid'=>$this->user['uid'],
            'liqType'=>200,
            'actionTime'=>array('egt',strtotime(date('Y-m-d',$this->time))));
        $m = M('coin_log')
            ->where($map)
            ->order('actionTime desc')
            ->find();
        if(empty($m)){
            return true;
        }
        return false;
    }
}
