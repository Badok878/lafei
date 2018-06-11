<?php
/**
 * Created by PhpStorm.
 * Author: Hon <2275604210@qq.com>
 * Date: 2017/12/28 0028
 * Time: 7:35
 */

namespace Home\Controller\Service;
use Home\Service\Order;
use think\Log;
use think\Session;

/**
 * jq支付类
 * Class GfbPay
 * @package app\index\service
 */
class JqPay
{
    public static $order_no = '';
    public static $order_amount = '';
    public static $agentUrl = '';

    protected static $merchantID = '291657'; //商户id
    protected static $url = 'http://user.sdecpay.com/';
//    protected static $url = 'https://user.ecpay.cn/';
    protected static $keyCode = 'qKxWjKibscavHPB48aOyXqlENY9GBylUcnbMqvBSnwoJMHVY9h6W038KumrlpltFBEQHhmFcOad1D5xJbS1cUPfyrSZ4ftQnpQeBbJPTy2tNPymOdIn1JYDsyVqYYIOl';

    public static function getParams ($data)
    {
        $notifyurl = 'http://pay.suyingz.com:8111/callback2.php';
        $rechargeId = $data['order_sn']; //订单号
        $amount = $data['amount']; //交易金额
        $actionIP = get_client_ip(); //用户浏览器IP

        //商户代码（merId）
        $merId = self::$merchantID;
        //商户系统生成的订单号
        $dealOrder = $rechargeId;
        //支付金额，保留两个小数位
        $dealFee	= number_format($amount,2);;
        //订单支付结果同步返回地址
        $dealReturn = $notifyurl;//'http://'.$_SERVER['HTTP_HOST'];
        //订单支付结果异步返回地址
        $dealNotify = $notifyurl;
        //生成签名
        $dealSignure=sha1($merId.$dealOrder.$dealFee.$dealReturn.self::$keyCode);
        //获得表单传过来的数据
        $def_url  = '<br />';
        $def_url  .= '<form id="ifrm" method="post" action="http://pay.suyingz.com:8111/gopay.php"  >';
        $def_url .= '	<input type = "hidden" name = "merId"	value = "'.$merId.'">';
        $def_url .= '	<input type = "hidden" name = "dealOrder" 				value = "'.$dealOrder.'">';
        $def_url .= '	<input type = "hidden" name = "dealFee" 			value = "'.$dealFee.'">';
        $def_url .= '	<input type = "hidden" name = "dealSignure"			value = "'.$dealSignure.'">';
        $def_url .= '	<input type = "hidden" name = "dealReturn"			value = "'.$dealReturn.'">';
        $def_url .= '	<input type = "hidden" name = "dealNotify"			value = "'.$dealNotify.'">';
        $def_url .= '</form>';
        $def_url .= "<script> document.getElementById('ifrm').submit();</script>";
        return $def_url;
    }
}