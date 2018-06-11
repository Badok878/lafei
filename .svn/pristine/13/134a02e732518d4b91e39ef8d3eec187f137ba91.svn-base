<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use COM\Page;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller
{

    /* 空操作，用于输出404页面 */
    // public function _empty(){
    //     echo 404; //TODO:完成404页面
    // }
    // TODO: 为了调试方便，暂时注释

    public $memberSessionName = 'session-name';
    public $time;
    public $settings;
    public $prename;
    public $user;

    public $types;
    public $playeds;
    public $groups;

    public  $before_stop_time = 6; //提前3秒关停
    public  $stop_time = 30; //提前30秒关停

    public $guoTypes = [
        1,16,44,6,9,10,20,43
    ];

    protected function _initialize()
    {
        $this->time    = time();
        $this->prename = C('DB_PREFIX');
//        $this->time = intval($_SERVER['REQUEST_TIME']);
        if (session('user_auth_sign2') != data_auth_sign($_SERVER['HTTP_USER_AGENT'])) {
//检测ip信息是否与session中存储的一致，防止session被盗，他人登录
            if ($this->getParams()==false &&
                strpos(__ACTION__, 'login') === false &&
                strpos(__ACTION__, 'register') === false &&
                strpos(__CONTROLLER__, 'wufencai') === false &&
                strpos(__ACTION__, 'verify') === false
            ) {
                //没有登录
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    exit();
                }
                header('location: ' . U('User/login'));
                return;
            }
        }

        $user              = M('Members')->where(['uid' => session('user')['uid']])->find();
        $user['sessionId'] = session('user')['sessionId'];
        session('user', $user);
        $this->user = session('user');
        //更新session
        $data['id']         = $this->user['sessionId'];
        $data['accessTime'] = time();
        M('member_session')->save($data);

        // 同一个用户登录踢出登录
        $curMemberSession = M('member_session')->where("id = {$data['id']}")->find();
        if ($this->getParams()==false && $curMemberSession && $curMemberSession['isOnLine'] != 1) {
            session('user', null);
            session('user_auth_sign2', null);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                exit();
            }
            header('location: ' . U('User/login'));
            return;
        }
        $this->assign('user', $this->user);

        $this->settings = $this->getSystemSettings();
        S('WEB_NAME', $this->settings['webName'], 15 * 60);
        $this->assign('settings', $this->settings);
        $switchWeb = $this->settings['switchWeb'];
        if ($switchWeb && $switchWeb == '1') {

        } else {
            $data = D('params')->where()->select();
            //dump($data[29]['value']);
            $this->assign('settings', $data);
            $this->display('error/close');
            exit();
        }
    }

    //判断过滤
    private function getParams(){
        $url = $_SERVER['QUERY_STRING'];
        $arr = explode('/',$url);
        if(count($arr)>3){
            if($arr[3]=="jx_server"
                ||$arr[3]=="zf_server"
                ||$arr[3]=="zf_server1"
                ||$arr[3]=="zesheng_server"
                ||$arr[3]=="zb_callback"
                ||$arr[3]=="jq_callback"
                ||strpos($url,'home/recharge/payResult')!== false){
                return true;
            }
        }
        return false;
    }

    protected function getTypes()
    {

        $this->types = M('type')->cache(true, 10 * 60, 'xcache')->where(array('isDelete' => 0))->order('sort')->select();
        $data        = array();
        if ($this->types) {
            foreach ($this->types as $var) {
                $data[$var['id']] = $var;
            }
        }

        return $this->types = $data;
    }

    protected function getPlayeds()
    {

        $this->playeds = M('played')->where(array('enable' => 1))->order('sort')->select();
        $data          = array();
        if ($this->playeds) {
            foreach ($this->playeds as $var) {
                $data[$var['id']] = $var;
            }
        }

        return $this->playeds = $data;
    }

    protected function getGroups()
    {

        $this->groups = M('played_group')->cache(true, 10 * 60, 'xcache')->where(array('enable' => 1))->order('sort,id')->select();
        $data         = array();
        if ($this->groups) {
            foreach ($this->groups as $var) {
                $data[$var['id']] = $var;
            }
        }
        return $this->groups = $data;
    }

    /**
     * 获取系统配置
     */
    protected function getSystemSettings()
    {
        $this->settings = array();

        if ($data = M('params')->cache(true, 10 * 60, 'xcache')->where()->select()) {
            foreach ($data as $var) {
                $this->settings[$var['name']] = $var['value'];
            }
        }

        return $this->settings;
    }

    protected function getGameLastNo($type, $time = null)
    {

        if ($time === null) {
            $time = time();
        }

        $kjTime = $this->getTypeFtime($type);
        $atime  = date('H:i:s', $time + $kjTime);
        //$sql="select actionNo, actionTime from {$this->prename}data_time where type=$type and actionTime<='".$atime."' order by actionTime desc limit 1";
        $return = M('data_time')->where(array('type' => $type, 'actionTime' => array('elt', $atime)))->order('actionTime desc')->limit(1)->find();
        if (!$return) {
            //$sql="select actionNo, actionTime from {$this->prename}data_time where type=$type order by actionNo desc limit 1";
            $return = M('data_time')->where(array('type' => $type))->order('actionNo desc')->limit(1)->find();
            $time   = $time - 24 * 3600;
        }

        $types = $this->getTypes();
        foreach ($types as $play) {
            if ($play['id'] == $type) {
                $fun = $play['onGetNoed'];
            }
        }

        if (method_exists($this, $fun)) {
            $this->$fun($return['actionNo'], $return['actionTime'], $time);
        }

        return $return;
    }

    /**
     * 读取将要开奖期号
     *
     * @params $type        彩种ID
     * @params $time        时间，如果没有，当默认当前时间
     * @params $flag        如果为true，则返回最近过去的一期（一般是最近开奖的一期），如果为flase，则是将要开奖的一期
     */
    protected function getGameNo($type, $time = null)
    {

        if ($time === null) {
            $time = $this->time;
        }

        $kjTime = $this->getTypeFtime($type);
        $atime  = date('H:i:s', $time + $kjTime);

        $return = M('data_time')->where(array('type' => $type, 'actionTime' => array('gt', $atime)))->order('actionTime')->limit(1)->find();

        if (!$return) {
            $return = M('data_time')->where(array('type' => $type))->order('actionTime')->limit(1)->find();
            $time   = $time + 24 * 3600;
        }

        $types = $this->getTypes();
        foreach ($types as $play) {
            if ($play['id'] == $type) {
                $fun = $play['onGetNoed'];
            }
        }

        if (method_exists($this, $fun)) {
            $this->$fun($return['actionNo'], $return['actionTime'], $time);
        }

        return $return;
    }

    //获取延迟时间
    protected function getTypeFtime($type)
    {
        if ($type) {
            //$Ftime=$this->getValue("select data_ftime from {$this->prename}type where id = ".$type);
            $data  = M('type')->where(array('id' => $type))->field('data_ftime')->find();
            $Ftime = $data['data_ftime'];
        }
        if (!$Ftime) {
            $Ftime = 0;
        }

        return intval($Ftime);
    }
    //////

    //获取当期时间
    protected function getGameActionTime($type, $old = 0)
    {
        $actionNo = $this->getGameNo($type);

        if ($type == 1 && $actionNo['actionTime'] == '00:00') {
            $actionTime = strtotime($actionNo['actionTime']) + 24 * 3600;
        } else {
            $actionTime = strtotime($actionNo['actionTime']);
        }

        if (!$actionTime) {
            $actionTime = $old;
        }
        // 提前截止开奖数组  重庆时时彩，天津时时彩，新疆时时彩，广东11选5，江西11选5，北京快乐8，和北京PK10
        $actionTime = $this->get2difftime($type,$actionTime);
        $actionTime = $actionTime < 0 ? 0 : $actionTime;
        return $actionTime;
    } /////

    //获取当期期数
    protected function getGameActionNo($type)
    {
        $actionNo = $this->getGameNo($type);
        return $actionNo['actionNo'];
    } /////

    //重庆时时彩
    private function noHdCQSSC(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
        /*}*/
    }

    //分分彩
    private function noHdFFC(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-0120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else{*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
//        }
    }

    //天津时时彩
    private function no0Hd(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
        // dump($actionNo);
    }

    //天津时时彩(new)
    private function no0HdTJSSC(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1001 + $actionNo, 1);
    }

    //广东11选5
    private function no0HdGDSYXW(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    //五分彩
    private function no0HdWFC(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    //新疆时时彩
    private function noxHd(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        if ($actionNo >= 84) {
            $time -= 24 * 3600;
        }
        $num = substr(100 + $actionNo, 1);
        if (mb_strlen($num) < 3) {
            $actionNo = date('Ymd-', $time) . '0' . $num;
        } else {
            $actionNo = date('Ymd-', $time) . $num;
        }
    }

    //新疆时时彩(new)
    private function noxHdXJSSC(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        if ($actionNo >= 84) {
            $time -= 24 * 3600;
        }
        $num = substr(100 + $actionNo, 1);
        if (mb_strlen($num) < 3) {
            $actionNo = date('Ymd-', $time) . '0' . $num;
        } else {
            $actionNo = date('Ymd-', $time) . $num;
        }
    }

    //福彩3D、排列三
    private function pai3(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Yz', $time);
        $actionNo = substr($actionNo, 0, 4) . substr(substr($actionNo, 4) + 994, 1);

        if ($actionTime >= date('Y-m-d H:i:s', $time)) {

        } else {
            $kjTime = $this->getTypeFtime($this->type);
            if (date('Y-m-d H:i:s', time() + $kjTime) < date('Y-m-d 23:59:59', time())) {
                $actionTime = date('Y-m-d 19:30', time() + 24 * 60 * 60);
            } else {
                $actionNo   = $actionNo + 1;
                $actionTime = date('Y-m-d 19:30', time() + 24 * 60 * 60);
            }
        }
    }

    //福彩3D
    private function FCSD(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Yz', $time);
        $actionNo = substr($actionNo, 0, 4) . substr(substr($actionNo, 4) + 994, 1);

        if ($actionTime >= date('Y-m-d H:i:s', $time)) {

        } else {
            $kjTime = $this->getTypeFtime($this->type);
            if (date('Y-m-d H:i:s', time() + $kjTime) < date('Y-m-d 23:59:59', time())) {
                $actionTime = date('Y-m-d 19:30', time() + 24 * 60 * 60);
            } else {
                $actionNo   = $actionNo + 1;
                $actionTime = date('Y-m-d 19:30', time() + 24 * 60 * 60);
            }
        }
    }

    //北京PK10

    private function BJpk10(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = 179 * (strtotime(date('Y-m-d', $time)) - strtotime('2007-11-11')) / 3600 / 24 + $actionNo - 3793-1253;
    }

    //北京快乐8
    public function Kuai8(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = 179 * (strtotime(date('Y-m-d', $time)) - strtotime('2004-09-19')) / 3600 / 24 + $actionNo - 3856;
    }

    // 韩国1.5分彩
    private function hgssc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /*}*/
    }

    /**
     * 重庆11选5
     * @param $actionNo
     * @param $actionTime
     * @param null $time
     */
    private function Cqsyxw(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }
    /**
     * 江西11选5
     * @param $actionNo
     * @param $actionTime
     * @param null $time
     */
    private function Jxsyxw(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    //秒秒彩
    private function mmc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
       /* if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /*}*/
    }
    //东京1.5分彩
    private function jdssc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
       /* }*/
    }
    //台湾5分彩
    private function twwfc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
        // dump($actionNo);
    }
    //30秒11选5
    private function scm(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /*}*/
        // dump($actionNo);
    }
    //一分钟11选5
    private function yfz(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
       /* if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /*}*/
        // dump($actionNo);
    }
    //加拿大3.5分彩
    private function jnd(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        // dump($actionNo);
    }
    //新德里1.5分彩
    private function xdl(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
       /* if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /*}*/
    }
    //腾讯分分彩
    private function txffc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /*if ($actionNo == 0 || $actionNo == 120) {
            //echo 999;
            $actionNo   = date('Ymd-120', $time - 24 * 3600);
            $actionTime = date('Y-m-d 00:00', $time);

        } else {*/
            $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo-1, 1);
       /* }*/
    }
    private function setTimeNo(&$actionTime, &$time = null)
    {
        if (!$time) {
            $time = $this->time;
        }

        $actionTime = date('Y-m-d ', $time) . $actionTime;
    }
    /**
     * 广东快乐十分 no0Hd
     * @param $actionNo
     * @param $actionTime
     * @param null $time
     */
    private function Gdklsf(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    /**
     * 江苏快3
     * @param $actionNo
     * @param $actionTime
     * @param null $time
     */
    private function Jsks(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    /**
     * 二分彩
     * @param $actionNo
     * @param $actionTime
     * @param null $time
     */
    private function Efc(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        $actionNo = date('Ymd-', $time) . substr(1000 + $actionNo, 1);
    }

    /**
     * [hjlf 皇家龙凤]
     * @param  [type] &$actionNo   [description]
     * @param  [type] &$actionTime [description]
     * @param  [type] $time        [description]
     * @return [type]              [description]
     */
    public function hjlf(&$actionNo, &$actionTime, $time = null)
    {
        $this->setTimeNo($actionTime, $time);
        /* if ($actionNo == 0 || $actionNo == 120) {
             //echo 999;
             $actionNo   = date('Ymd-120', $time - 24 * 3600);
             $actionTime = date('Y-m-d 00:00', $time);

         } else {*/
        $actionNo = date('Ymd-', $time) . substr(10000 + $actionNo, 1);
        /* }*/
    }

    /**
     * 用户资金变动
     *
     * 请在一个事务里使用
     */
    protected function addCoin($log)
    {

        if (!isset($log['uid'])) {
            $log['uid'] = $this->user['uid'];
        }

        if (!isset($log['info'])) {
            $log['info'] = '';
        }

        if (!isset($log['coin'])) {
            $log['coin'] = 0;
        }

        if (!isset($log['type'])) {
            $log['type'] = 0;
        }

        if (!isset($log['fcoin'])) {
            $log['fcoin'] = 0;
        }

        if (!isset($log['extfield0'])) {
            $log['extfield0'] = 0;
        }

        if (!isset($log['extfield1'])) {
            $log['extfield1'] = '';
        }

        if (!isset($log['extfield2'])) {
            $log['extfield2'] = '';
        }

        $sql = " call setCoin({$log['coin']}, {$log['fcoin']}, {$log['uid']}, {$log['liqType']}, {$log['type']}, '{$log['info']}', {$log['extfield0']}, '{$log['extfield1']}', '{$log['extfield2']}')";

        //echo $sql;exit;
        $Model = new \Think\Model();
        if ($Model->query($sql) === false) {
            return false;
        } else {
            return true;
        }

        return false;
    }

    /**
     * 获取来访IP地址
     */
    final protected static function ip($outFormatAsLong = false)
    {
        $ip = get_client_ip(1);

        return $ip;
    }

    /**
     * 数据集分页
     * @param array $records 传入的数据集
     */
    public function recordList($records, $count = 15)
    {
        $request = (array) I('request.');
        $total   = $records ? count($records) : 1;
        if (isset($request['r'])) {
            $listRows = (int) $request['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : $count;
        }
        $page = new \COM\Page($total, $listRows, $request);

        $voList = array_slice($records, $page->firstRow, $page->listRows);
        $p      = $page->show();
        $this->assign('data', $voList);
        $this->assign('_page', $p ? $p : '');
    }

    public function listsPage($model,$pageSize){
        $request = (array) I('request.');
        $total   = $model->count();
        if (isset($request['r'])) {
            $listRows = (int) $request['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : $pageSize;
        }
        $page = new \COM\Page($total<1?1:$total, $listRows, $request);
        $p      = $page->webShow();
        $this->assign('_page', $p ? $p : '');
        return $page;
    }

    public function getPageInfo($model,$pageSize){
        $request = (array) I('request.');
        $total   = $model->count();
        if (isset($request['r'])) {
            $listRows = (int) $request['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : $pageSize;
        }
        $page = new \COM\Page($total<1?1:$total, $listRows, $request);
        return $page;
    }

    /**
     * 查询
     */
    // protected function getValue($sql){
    // $param = substr($sql,6,strpos($sql,'from')-6);
    // $param = str_replace(' ','',$param);
    // $Model = new \Think\Model();
    // $return=$Model->query($sql);
    // return $return[0][$param];
    // }

    /**
     * 查询结果
     */
    // protected function getRow($sql){
    // $Model = new \Think\Model();
    // $return=$Model->query($sql);
    // return $return[0];
    // }

    /**
     * 查询结果集
     */
    // protected function Rows($sql){
    // $Model = new \Think\Model();
    // $return=$Model->query($sql);
    // return $return;
    // }
    public function calcDataTime()
    {
        /* $request = I();

        $type = I('type');
        $start = I('start');
        $end = I('end');
        $center = I('center');
        $fenge = I('fenge');
        $fenge1 = I('fenge1');
        echo  "INSERT INTO `lafei1`.`gygy_data_time` ( `type`, `actionNo`, `actionTime`, `stopTime`) VALUES \n";
        $actionNo = 0;
        if($center){
        $shichang1 = $center- $start;
        $ss = $shichang1 * 3600;
        $qushu = $ss/$fenge;
        for ($i=0;$i<$qushu;$i++){
        echo  "(".$type.",".($i+1).",)\n";
        }
        }else{
        $shichang = $end -$start;
        }*/
//        $start;
        //        $end;
        //        $fenge;
        //        $center_at;
        //        $fenge2;
    }

    public function get2difftime($type,$diffTime)
    {
        $type_arr = array(1,3,12,6,16,20,24);
        if(in_array($type,$type_arr)){
            $diffTime = $diffTime-$this->stop_time; // 提前 60秒 封停投注
        }
        return $diffTime;
    }
}
