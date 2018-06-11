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
class NumController extends HomeController{
    //没有任何方法，直接执行HomeController的_empty方法
    //请不要删除该控制器


    /**
     * 查号
     */
    public function queryNum()
    {
        $type = I('type');
        $number = I('number');
        $rows = I('rows');
        $map['type'] = $type;
        if(trim(@$number)){
            $map['number'] = trim($number);
        }
        $rows = intval($rows) > 0 ? intval($rows) : 30 ;
        $data = M('data')->where($map)->limit($rows)->order('id desc')->select();
        if(!$data) $data = array();
        $this->ajaxReturn($data,'JSON');
    }

}
