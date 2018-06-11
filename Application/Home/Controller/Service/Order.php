<?php
/**
 * Created by PhpStorm.
 * Author: Hon <2275604210@qq.com>
 * Date: 2017/11/28 0028
 * Time: 22:50
 */

namespace Home\Service;


use think\Log;

class Order
{
    public static function updateOrder($orderid, $datetime,$bank='')
    {
        Log::record('回调充值更新开始'.$orderid);
        try{
            $rechage = M('member_recharge')->where(array('rechargeId' => $orderid, 'state' => 0))->find();
            if (empty($rechage)) {
                self::createReqInfo('',$orderid,$bank,1,'更新订单：充值失败，订单不存在,或者已经充值完成');
                Log::record('充值失败，订单不存在,或者已经充值完成');
                return;
            }else if(time()>=intval($rechage['actionTime'])+1800){
                self::createReqInfo('订单已经失效',$orderid,$bank,1,'充值失败，订单已经失效');
                return;
            }
            $uid                  = $rechage['uid'];
            $data['rechargeTime'] = is_string($datetime)?strtotime($datetime):$datetime;
            $data['rechargeAmount'] = $rechage['amount'];
            $data['state']        = 1;
            $data['info']        = $bank;
            $coin                 = M('members')->where(array('uid' => $uid))->field('coin')->find();
            if ($coin) {
                $member['coin'] = $coin['coin'] + $rechage['amount'];
                M('members')->where(array('uid' => $uid))->update($member);
            }
            $reg = M('member_recharge')->where(array('rechargeId'=> $orderid))->update($data);
            if ($reg) {
                Log::record($bank.'充值成功');
                self::createReqInfo(json_encode($data),$orderid,$bank,0,'充值成功');
            } else {
                Log::record($bank.'充值失败'.json_encode($data));
                self::createReqInfo(json_encode($data),$orderid,$bank,1,'数据更新失败');
            }
        }catch (\Exception $e){
            self::createReqInfo('程序异常',$orderid,$bank,1,$e->getMessage());
            Log::record('回调充值更新失败，'.$e->getMessage());
        }
    }

    public static function createReqInfo($req='',$orderid='',$form='',$status=1,$note=''){
        try{
            $data = array(
                'req_data'=>$req,
                'req_date'=>time(),
                'host'=>$_SERVER['HTTP_HOST'],
                'orderid'=>$orderid,
                'form'=>$form,
                'status'=>$status,
                'note'=>$note
            );
            M('rebackinfo')->add($data);
        }catch (\Exception $e){
            Log::record('回调请求数据写入失败');
        }
    }
}