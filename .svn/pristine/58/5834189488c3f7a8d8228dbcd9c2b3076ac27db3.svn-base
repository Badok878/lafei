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
class WufencaiController extends HomeController
{
    //没有任何方法，直接执行HomeController的_empty方法
    //请不要删除该控制器

    /**
     * 获取信息页面
     */
    public function info5()
    {
        $lastNo = $this->getGameNo(14);
        $flag   = 1; //开奖按钮
        $kjdata = ''; //开奖号码
        $kjtime = date('Y-m-d H:m:s');

        $data     = M('params')->where(array('name' => 'wufencai'))->find();
        $wufencai = $data['value'];

        $this->assign('lastNo', $lastNo);
        $this->assign('wufencai', $wufencai);
        $this->assign('flag', $flag);

        $data               = array();
        $data['actionNo']   = $lastNo['actionNo'];
        $data['orderID']   = $wufencai;
        $data['actionTime'] = $lastNo['actionTime'];
        $data['flag']       = $flag;
        $this->ajaxReturn($data, 'JSON');
        //$this->display();
    }

    public function info2()
    {
        $lastNo = $this->getGameNo(34);
        $flag   = 1; //开奖按钮
        $kjdata = ''; //开奖号码
        $kjtime = date('Y-m-d H:m:s');

        $data     = M('params')->where(array('name' => 'wufencai'))->find();
        $wufencai = $data['value'];

        $this->assign('lastNo', $lastNo);
        $this->assign('wufencai', $wufencai);
        $this->assign('flag', $flag);

        $data               = array();
        $data['actionNo']   = $lastNo['actionNo'];
        $data['orderID']   = $wufencai;
        $data['actionTime'] = $lastNo['actionTime'];
        $data['flag']       = $flag;
        $this->ajaxReturn($data, 'JSON');
        //$this->display();
    }
    public function info1()
    {
        $lastNo = $this->getGameNo(5);
        $flag   = 1; //开奖按钮
        $kjdata = ''; //开奖号码
        $kjtime = date('Y-m-d H:m:s');

        $data     = M('params')->where(array('name' => 'wufencai'))->find();
        $wufencai = $data['value'];
//
        //        $this->assign('lastNo',$lastNo);
        //        $this->assign('wufencai',$wufencai);
        //        $this->assign('flag',$flag);

        $data               = array();
        $data['actionNo']   = $lastNo['actionNo'];
        $data['orderID']   = $wufencai;
        $data['actionTime'] = $lastNo['actionTime'];
        $data['flag']       = $flag;
        $this->ajaxReturn($data, 'JSON');
//        $this->display();
    }
    public function info0()
    {
// 36=秒秒彩ID
        $lastNo = $this->getGameNo(36);
        $flag   = 1; //开奖按钮
        $kjdata = ''; //开奖号码
        $kjtime = date('Y-m-d H:m:s');

        $data     = M('params')->where(array('name' => 'wufencai'))->find();
        $wufencai = $data['value'];

        $this->assign('lastNo', $lastNo);
        $this->assign('wufencai', $wufencai);
        $this->assign('flag', $flag);

        $data               = array();
        $data['actionNo']   = $lastNo['actionNo'];
        $data['orderID']   = $wufencai;
        $data['actionTime'] = $lastNo['actionTime'];
        $data['flag']       = $flag;
        $this->ajaxReturn($data, 'JSON');
        //$this->display();
    }
    public function getinfo()
    {
        // 36=秒秒彩ID
        $type   = I('type', '', 'intval');
        $lastNo = $this->getGameNo($type);///getGameNo  getGameLastNo
        $flag   = 1; //开奖按钮
        $kjdata = ''; //开奖号码
        $kjtime = date('Y-m-d H:m:s');

        $data     = M('params')->where(array('name' => 'wufencai'))->find();
        $wufencai = $data['value'];

        $this->assign('lastNo', $lastNo);
        $this->assign('wufencai', $wufencai);
        $this->assign('flag', $flag);

        $data               = array();
        $data['actionNo']   = $lastNo['actionNo'];
        $data['orderID']   = $wufencai;
        $data['actionTime'] = $lastNo['actionTime'];
        $data['flag']       = $flag;
        $this->ajaxReturn($data, 'JSON');
        //$this->display();
    }
    public function getbet()
    {

        $typeid = I('type', '', 'intval');

        $playedGroup = I('playedGroup', '', 'intval');
       // print_r($playedGroup);
        $playedId = I('playedId', '', 'intval');

        $jiangjin = M('played')->where(array('id' => $playedId))->field('bonusProp, bonusPropBase')->find();
        //$playeds          = D('played')->where(array('type' => $type['type'], 'groupId' => $playedGroup, '' => $playedId))->find();
        $bets             = D('bets')->order('orderId desc')->limit(1)->select();
       // $jiangjin         = $playeds['bonusPropBase'];

        $users            = D('members')->where(array('uid' => $this->user['uid']))->find();
        $orderId          = floatval($bets[0]['orderId']) + 1;
        $fanDian          = $jiangjin['bonusPropBase']. '-' . $users['fanDian'] . '%';
        $data['jiangjin'] = $jiangjin['bonusProp'];
        $data['orderId']  = $orderId;
        $data['fanDian']  = $fanDian;

        $this->ajaxReturn($data, 'JSON');
    }

    public function getTopParentId(){
        $username = I('username');
        $obj = M('members')->where(array('username'=>$username))->find();
        if(empty($obj)){
            $this->ajaxReturn(array('parent'=>''), 'JSON');
        }else{
            $arr = explode(',',$obj['parents']);
            if(count($arr)>1){
                $parent = $arr[1];
            }else{
                $parent = '';
            }
            $this->ajaxReturn(array('parent'=>$parent), 'JSON');
        }
    }

    public function hasChild(){
        $u1 = I('user1');
        $u2 = I('user2');
        $obj = M('members')->where(array('username'=>$u1))->find();
        $obj2 = M('members')->where(array('username'=>$u2))->find();
        if(empty($obj)||empty($obj2)){
            $this->ajaxReturn(array(false), 'JSON');
        }else{
            $arr = explode(',',$obj2['parents']);
            $arr_new = array();
            foreach($arr as $key=>$v){
                $arr_new[$v] = 1;
            }
            if(isset($arr_new[$obj['uid']])){
                $this->ajaxReturn(array(true), 'JSON');
            }else{
                $this->ajaxReturn(array(false), 'JSON');
            }
        }
    }
	public function getCurNo(){
		$type = I('type', '', 'intval');
		$actionNo = $this->getGameNo($type);
		$lastNo = $this->getGameLastNo($type);
		$number = D('data')->where(array('type' => $type, 'number' => $lastNo['actionNo']))->find();
		
		$data['lastNo'] = $lastNo['actionNo'];
		$data['Number'] = $number['data'];
		$data['ThisNo'] = $actionNo['actionNo'];
		$data['NowTime'] = date('Y-m-d H:i:s', $this->time);
		// $actionNo = $this->getGameNo($type);
		$data['StopTime'] =date('Y-m-d H:i:s', strtotime($actionNo['stopTime']));
		$this->ajaxReturn($data, 'JSON');
		
	}
	

}
