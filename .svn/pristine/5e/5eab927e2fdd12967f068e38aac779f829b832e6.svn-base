<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Home\Controller\Service\JqPay;
use Home\Controller\Service\Pay;
use Home\Controller\Service\YiTongPay;
use Home\Controller\Service\ZbPay;
use Think\Log;

/**
 * 空模块，主要用于显示404页面，请不要删除
 */
class RechargeController extends HomeController
{

    /**
     * 显示充值页面
     * @return [type] [description]
     */
    public function index()
    {
        $rechargeMin1 = M('params')->where(array('id'=>5))->find();
        $rechargeMax1 = M('params')->where(array('id'=>6))->find();
        $cashMin1 = M('params')->where(array('id'=>34))->find();
        $cashMax1 = M('params')->where(array('id'=>35))->find();
        $this->assign('bank_check', array('min'=>$rechargeMin1['value'],'max'=>$rechargeMax1['value']));
        $this->assign('code_check', array('min'=>$cashMin1['value'],'max'=>$cashMax1['value']));

        $pay_buin = M('paybusiness')->where(array('enable'=>0))->order('sort')->select();
        $this->assign('paybusiness', $pay_buin);

        $isShow = (count($pay_buin)==1&&$pay_buin[0]['id']==6)?'false':'true';
        $direct_pay = (count($pay_buin)==1&&$pay_buin[0]['id']==6)?'weixin':'direct_pay';
        $this->assign('isShow', $isShow);
        $this->assign('direct_pay', $direct_pay);
        if(!empty($pay_buin)){
            $this->assign('def_id',$pay_buin[0]['id']);
        }
        
        $user = M('members')->where('uid=' . $this->user['uid'])->find();
        if ($user['is_test'] == 1){
            $this->error('此账号无此权限');
        }

        if (IS_POST) {
            $pay_type = I('def-w-label');
            if ($pay_type == 5||$pay_type == 8) {
                //智付
                $service_type = $_POST['payLinks'];
                if ($service_type == 'direct_pay') {
                    //网银
                    $this->zfwy($_POST);
                } else {
                    //支付宝和微信
                    $this->zfpay($_POST);
                }
            } elseif ($pay_type == 4) {
                exit('通道3维护中');
                //中云支付
                $this->zhongyun($_POST);
            } elseif ($pay_type == 3) {
                //收米支付
                $this->shoumi($_POST);
            } elseif ($pay_type == 6) {
                $service_type = $_POST['payLinks'];
                if($service_type=='weixin'){
                    //九霄支付(微信)
                    $_POST['service'] = 'weixin.scan';
                    $_POST['msg'] = '微信支付';
                }else if($service_type=='alipay'){
                    //九霄支付(支付宝 )
                    $_POST['service'] = 'alipay.scan';
                    $_POST['msg'] = '支付宝支付';
                }else{
                    exit('此功能维护中，请选择微信或者支付宝');
                }
                $this->jiuxiao($_POST);
            }else if($pay_type == 7){
                $service_type = $_POST['payLinks'];
                $this->zesheng($_POST);
                /*if($service_type=='weixin' || $service_type=='alipay'){
                    $this->zesheng($_POST);
                }else{
                    $this->redirect('zs_pay',array('money'=>$_POST['amount']));
//                    exit('此功能维护中，请选择微信或者支付宝');
                }*/
            }else if($pay_type == 9){
                $this->yibaoQrcode($_POST);
            }else if($pay_type == 10){
                $this->getZfwy($_POST);
            }else if($pay_type==104){
                $this->zbzf($_POST);
            }else if($pay_type==105){
                $this->jqzf($_POST);
            }else{
                $this->error('支付类型有误');
            }
//              curl('http://api.shoumipay.com/gatepay.do?P_UserId=d7f643dc-da60-42b0-bbe8-550d4ce4df8a&P_OrderId=' . $P_OrderId . '&P_FaceValue=' . $P_FaceValue . '&P_ChannelId=' . $P_ChannelId . '&P_SDKVersion=3.1.3&P_RequestType=0&P_Subject=充值&P_PostKey=' . $P_PostKey . '&P_CustormId＝' . $P_CustormId . '&P_Description=余额充值&P_Notify_URL=http://127.0.0.1/lafei/html/index.php?s=/home/recharge/p_result', '1', '页面跳转中...');
            $this->display();
        } else {
            $orderNo = date('Ymd') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $this->assign('orderNo', $orderNo);
            $this->display();
        }
    }

    //智付支付
    public function zfpay($data)
    {
        $zf = M('paybusiness')->where(array('id' => $data['def-w-label']))->find();
        include_once 'zhifu/phpqrcode.php';
        include_once 'zhifu/merchant.php';
        $merchant_code      = $zf['business_id']; //商户号，1118004517是测试商户号，调试时要更换商家自己的商户号
        $service_type       = $data['payLinks']; //微信：weixin_scan 支付宝：alipay_scan 智汇宝：zhb_scan
        $notify_url         = $this->getRootUrl() . '/index.php?s=/home/recharge/zf_server'; //$_POST["notify_url"];
        $interface_version  = 'V3.1';
        $client_ip          = '120.237.123.242';
        $sign_type          = 'RSA-S';
        $order_no           = $this->getRechId();
        $order_time         = date('Y-m-d h:m:s');
        $order_amount       = $data['amount'];
        $product_name       = '充值';
        $product_code       = '';
        $product_num        = '';
        $product_desc       = '';
        $extra_return_param = '';
        $extend_param       = '';
        /////////////////////////////   参数组装  /////////////////////////////////
        /**除了sign_type DD4Sign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母

         */
        $signStr = "";
        $signStr = $signStr . "client_ip=" . $client_ip . "&";
        if ($extend_param != "") {
            $signStr = $signStr . "extend_param=" . $extend_param . "&";
        }
        if ($extra_return_param != "") {
            $signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
        }
        $signStr = $signStr . "interface_version=" . $interface_version . "&";
        $signStr = $signStr . "merchant_code=" . $merchant_code . "&";
        $signStr = $signStr . "notify_url=" . $notify_url . "&";
        $signStr = $signStr . "order_amount=" . $order_amount . "&";
        $signStr = $signStr . "order_no=" . $order_no . "&";
        $signStr = $signStr . "order_time=" . $order_time . "&";
        if ($product_code != "") {
            $signStr = $signStr . "product_code=" . $product_code . "&";
        }
        if ($product_desc != "") {
            $signStr = $signStr . "product_desc=" . $product_desc . "&";
        }
        $signStr = $signStr . "product_name=" . $product_name . "&";
        if ($product_num != "") {
            $signStr = $signStr . "product_num=" . $product_num . "&";
        }
        $signStr = $signStr . "service_type=" . $service_type;
/////////////////////////////   RSA-S签名  /////////////////////////////////
        /////////////////////////////////初始化商户私钥//////////////////////////////////////
        $merchant_private_key = openssl_get_privatekey($merchant_private_key);
        openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
        $sign = base64_encode($sign_info);
/////////////////////////  提交参数到DD4微信网关  ////////////////////////
        /**curl方法提交支付参数到DD4微信网关https://api.ddbill.com/gateway/api/weixin，并且获取返回值

         */
        $postdata = array('extend_param' => $extend_param,
            'extra_return_param'             => $extra_return_param,
            'product_code'                   => $product_code,
            'product_desc'                   => $product_desc,
            'product_num'                    => $product_num,
            'merchant_code'                  => $merchant_code,
            'service_type'                   => $service_type,
            'notify_url'                     => $notify_url,
            'interface_version'              => $interface_version,
            'sign_type'                      => $sign_type,
            'order_no'                       => $order_no,
            'client_ip'                      => $client_ip,
            'sign'                           => $sign,
            'order_time'                     => $order_time,
            'order_amount'                   => $order_amount,
            'product_name'                   => $product_name);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $zf['tj_url']); //"https://api.ddbill.com/gateway/api/scanpay"
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //$res=simplexml_load_string($response);
        curl_close($ch);
        Log::record($response);
        $arr = $this->getXml($response);
        if (strtolower($arr['result_code']) != 0) {
            $this->error($arr['resp_desc'].$arr['result_code']);
        } else {
            //预支付订单信息
            $rechage = array(
                'uid'        => $this->user['uid'],
                'username'   => $this->user['username'],
                'rechargeId' => $order_no,
                'amount'     => $order_amount,
                'actionIP'   => $this->ip(true),
                'actionTime' => $this->time,
                'state'      => '0',
            );
            M('member_recharge')->add($rechage);
            echo $response;
        }
    }

    //智付回调
    public function zf_server()
    {
        Log::record('通道一回调成功');
        try{
            include_once "zhifu/merchant.php";
            $merchant_code	= $_POST["merchant_code"];
            $notify_type = $_POST["notify_type"];
            $notify_id = $_POST["notify_id"];
            $interface_version = $_POST["interface_version"];
            $sign_type = $_POST["sign_type"];
            $DD4Sign = base64_decode($_POST["sign"]);
            $order_no = $_POST["order_no"];
            $order_time = $_POST["order_time"];
            $order_amount = $_POST["order_amount"];
            $extra_return_param = $_POST["extra_return_param"];
            $trade_no = $_POST["trade_no"];
            $trade_time = $_POST["trade_time"];
            $trade_status = $_POST["trade_status"];
            $bank_seq_no = $_POST["bank_seq_no"];
/////////////////////////////   参数组装  /////////////////////////////////
            /**
            除了sign_type DD4Sign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
             */
            $signStr = "";
            if($bank_seq_no != ""){
                $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
            }
            if($extra_return_param != ""){
                $signStr = $signStr."extra_return_param=".$extra_return_param."&";
            }
            $signStr = $signStr."interface_version=".$interface_version."&";
            $signStr = $signStr."merchant_code=".$merchant_code."&";
            $signStr = $signStr."notify_id=".$notify_id."&";
            $signStr = $signStr."notify_type=".$notify_type."&";
            $signStr = $signStr."order_amount=".$order_amount."&";
            $signStr = $signStr."order_no=".$order_no."&";
            $signStr = $signStr."order_time=".$order_time."&";
            $signStr = $signStr."trade_no=".$trade_no."&";
            $signStr = $signStr."trade_status=".$trade_status."&";
            $signStr = $signStr."trade_time=".$trade_time;
/////////////////////////////   RSA-S验签  /////////////////////////////////
            $DD4_public_key = openssl_get_publickey($DD4_public_key);
            $flag = openssl_verify($signStr,$DD4Sign,$DD4_public_key,OPENSSL_ALGO_MD5);
            /**
            如果验签返回ture就响应SUCCESS,并处理业务逻辑，如果返回false，则终止业务逻辑。
             */
            if ($flag) {
                $this->updateOrder($order_no, $order_time,$bank='通道一');
            } else {
                Log::record('充值失败,签名验证失败');
                $this->createReqInfo(json_encode($_POST),$order_no,'通道一',1,'充值失败,签名验证失败');
                exit('FAILED');
            }
        }catch (\Exception $e){
            $this->createReqInfo(json_encode($_POST),'','通道一',1,$e->getMessage());
            Log::record($e->getMessage());
        }
        exit('SUCCESS');
    }

    //智付网银
    public function zfwy()
    {
        try{
            //$_SERVER['HTTP_REFERER']. '/index.php?s=/home/recharge/zf_server'
            $data = $_POST;
            include_once "zhifu/merchant.php";

            $zf                = M('paybusiness')->where(array('id' => $data['def-w-label']))->find();
            $merchant_code     = $zf['business_id']; //商户号，1118004517是测试商户号，线上发布时要更换商家自己的商户号！
            $service_type ="direct_pay";
            $interface_version ="V3.0";
            $sign_type ="RSA-S";
            $input_charset = "UTF-8";
            $notify_url =$this->getRootUrl(). '/index.php?s=/home/recharge/zf_server';
            $order_no = $this->getRechId();
            $order_time = date( 'Y-m-d H:i:s' );
            $order_amount = $data['amount'];
            $product_name ="testpay";
            //以下参数为可选参数，如有需要，可参考文档设定参数值
            $return_url ="";
            $pay_type = "";
            $redo_flag = "";
            $product_code = "";
            $product_desc = "";
            $product_num = "";
            $show_url = "";
            $client_ip ="" ;
            $bank_code = "";
            $extend_param = "";
            $extra_return_param = "";

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
            $signStr= "";
            if($bank_code != ""){
                $signStr = $signStr."bank_code=".$bank_code."&";
            }
            if($client_ip != ""){
                $signStr = $signStr."client_ip=".$client_ip."&";
            }
            if($extend_param != ""){
                $signStr = $signStr."extend_param=".$extend_param."&";
            }
            if($extra_return_param != ""){
                $signStr = $signStr."extra_return_param=".$extra_return_param."&";
            }
            $signStr = $signStr."input_charset=".$input_charset."&";
            $signStr = $signStr."interface_version=".$interface_version."&";
            $signStr = $signStr."merchant_code=".$merchant_code."&";
            $signStr = $signStr."notify_url=".$notify_url."&";
            $signStr = $signStr."order_amount=".$order_amount."&";
            $signStr = $signStr."order_no=".$order_no."&";
            $signStr = $signStr."order_time=".$order_time."&";
            if($pay_type != ""){
                $signStr = $signStr."pay_type=".$pay_type."&";
            }
            if($product_code != ""){
                $signStr = $signStr."product_code=".$product_code."&";
            }
            if($product_desc != ""){
                $signStr = $signStr."product_desc=".$product_desc."&";
            }
            $signStr = $signStr."product_name=".$product_name."&";
            if($product_num != ""){
                $signStr = $signStr."product_num=".$product_num."&";
            }
            if($redo_flag != ""){
                $signStr = $signStr."redo_flag=".$redo_flag."&";
            }
            if($return_url != ""){
                $signStr = $signStr."return_url=".$return_url."&";
            }
            $signStr = $signStr."service_type=".$service_type;
            if($show_url != ""){
                $signStr = $signStr."&show_url=".$show_url;
            }

/////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	$sign = base64_encode($sign_info);
            $parmas = array(
                'sign'              => $sign,
                'merchant_code'     => $merchant_code,
                'bank_code'         => $bank_code,
                'order_no'          => $order_no,
                'order_amount'      => $order_amount,
                'service_type'      => $service_type,
                'input_charset'     => $input_charset,
                'notify_url'        => $notify_url,
                'interface_version' => $interface_version,
                'sign_type'         => $sign_type,
                'order_time'        => $order_time,
                'product_name'      => $product_name,
                'client_ip'         => $client_ip,
                'extend_param'      => $extend_param,
                'extra_return_param'      => $extra_return_param,
                'pay_type'      => $pay_type,
                'product_code'      => $product_code,
                'product_desc'      => $product_desc,
                'product_num'      => $product_num,
                'return_url'      => $return_url,
                'show_url'      => $show_url,
                'redo_flag'      => $redo_flag,
            );
            $url = 'https://pay.ddbill.com/gateway?input_charset=UTF-8';
//        $tz  = 'http://tcjkjb.top';
            $output = $this->getHtmlInfo($url,$parmas);
            //获取 orderKey
            $exp = explode('name="orderKey" value="',$output);
            $exp = explode('"',$exp[1]);

            // 微信 pay_id
            $radio = explode('<input type="radio"  value=',$output);
            $wxid = explode(' ',$radio[1]);
            $pay_code = $wxid[1];
            $wx_pay_id = $wxid[0];
            $this->assign('wx_pay_id',$wx_pay_id);
            //pay_code
            $wxcode = explode('id=',$pay_code);
            $wxcode = explode(' ',$wxcode[1]);
            $wxcode = $wxcode[0];
            preg_match('/[a-zA-Z]+/',$wxcode, $matches);
            $wx_pay_code = $matches[0];
            $this->assign('wx_pay_code',$wx_pay_code);
            //微信

            //支付宝  zfb_id
            $zfb_id = explode(' ',$radio[2]);
            $pay_code = $zfb_id[1];
            $zfb_id = $zfb_id[0];
            $this->assign('zfb_pay_id',$zfb_id);
            //pay_code
            $al_code = explode('id=',$pay_code);
            $al_code = explode(' ',$al_code[1]);
            $al_code = $al_code[0];
            preg_match('/[a-zA-Z]+/',$al_code, $matches);
            $al_pay_code = $matches[0];
            $this->assign('zfb_pay_code',$al_pay_code);
            //支付宝

            //预支付订单信息
            $rechage = array(
                'uid'=>$this->user['uid'],
                'username'=>$this->user['username'],
                'rechargeId'=>$order_no,
                'amount'=>$order_amount,
                'actionIP'=>$this->ip(true),
                'actionTime'=>$this->time,
                'state'=>'0'
            );
            M('member_recharge')->add($rechage);
            header("Content-type: text/html; charset=utf-8");
            $this->assign('orderKey',$exp[0]);
            $this->assign('output',$output);
            $this->display('zfwy');
        }catch (\Exception $e){
            Log::record($e->getMessage());
        }
    }

    public function getHtmlInfo($url,$parmas){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host:pay.ddbill.com','Origin:http://www.tcjkjb.top','Upgrade-Insecure-Requests:1','Content-Type:application/x-www-form-urlencoded;charset=UTF-8'));
        curl_setopt ($ch, CURLOPT_REFERER, 'http://www.xfgkjd.cn/index.php?s=/home/recharge/index.html');
//        curl_setopt ($ch, CURLOPT_REFERER, 'http://www.tcjkjb.top/index.php?s=/home/recharge/index.html');
        $User_Agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agent);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($parmas));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $output = str_replace('action="Pay"','action="https://pay.ddbill.com/Pay"',$output);
        $output = str_replace('submitForm_scanPay','local_submitForm_scanPay',$output);
        curl_close($ch);
        return $output;
    }

    //更新订单
    private function updateOrder($orderid, $datetime,$bank='')
    {
        Log::record('回调充值更新开始'.$orderid);
        try{
            $rechage = M('member_recharge')
                ->where(array('rechargeId' => $orderid, 'state' => 0))
                ->find();
            if (empty($rechage)) {
                $this->createReqInfo('',$orderid,$bank,1,'更新订单：充值失败，订单不存在,或者已经充值完成');
                Log::record('充值失败，订单不存在,或者已经充值完成');
                return;
            }else if(time()>=intval($rechage['actionTime'])+1800){
					$this->createReqInfo('订单已经失效',$orderid,$bank,1,'充值失败，订单已经失效');
                    return;
			}
            $uid                  = $rechage['uid'];
            $data['rechargeTime'] = is_string($datetime)?strtotime($datetime):$datetime;
            $data['rechargeAmount'] = $rechage['amount'];
            $data['state']        = 1;
            $data['info']        = $bank;
            $coin                 = M('Members')->where(array('uid' => $uid))->field('coin')->find();
            if ($coin) {
                $member['coin'] = $coin['coin'] + $rechage['amount'];
                M('Members')->where(array('uid' => $uid))->save($member);
            }
            $reg = M('member_recharge')->where(array('rechargeId'=> $orderid))->save($data);
            if ($reg) {
                Log::record($bank.'充值成功');
                $this->createReqInfo(json_encode($data),$orderid,$bank,0,'充值成功');
                //$this->redirect('home/index/index', '充值成功，即将返回主页');
            } else {
                Log::record($bank.'充值失败'.json_encode($data));
                $this->createReqInfo(json_encode($data),$orderid,$bank,1,'数据更新失败');
            }
        }catch (\Exception $e){
            $this->createReqInfo('程序异常',$orderid,$bank,1,$e->getMessage());
            Log::record('回调充值更新失败，'.$e->getMessage());
        }
    }

    public function getXml($str)
    {
        $simple = $str;
        $p      = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);
//        echo "Index array\n";
        //        print_r($index);
        //        echo "\nVals array\n";
        $rst = array(
            'resp_code' => 'fail',
            'resp_desc' => '获取失败',
        );
        foreach ($vals as $key => $val) {
            if (strtolower($val['tag']) == 'resp_code') {
                $rst['resp_code'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'result_code') {
                $rst['result_code'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'resp_desc') {
                $rst['resp_desc'] = $val['value'];
            }
        }
        return $rst;
    }

    //九霄支付
    public function jiuxiao($data){
        try{
            $zf  = M('paybusiness')->where(array('id' => $data['def-w-label']))->find();
            $service = $data['service'];
            $amount = $data['amount'];
            $mch_id = $zf['business_id'];
            $out_trade_no = $this->getRechId();
            $body = '充值';
            $total_fee = $amount*100;
            $mch_create_ip = '127.0.0.1';
            $notify_url = $this->getRootUrl() . '/index.php?s=/home/recharge/jx_server';
            $nonce_str = $this->guid();
            //签名
            $str = "body={$body}&mch_create_ip={$mch_create_ip}&mch_id={$mch_id}";
            $str .= "&nonce_str={$nonce_str}&notify_url={$notify_url}&out_trade_no={$out_trade_no}";
            $str .= "&service={$service}&total_fee={$total_fee}";
            $str .= '&key='.$zf['business_key'];
//            Log::record($str);
            $sign = strtoupper(md5($str));

            $xml  ="<xml><body><![CDATA[{$body}]]></body>";
            $xml .="<mch_create_ip><![CDATA[{$mch_create_ip}]]></mch_create_ip>";
            $xml .="<mch_id><![CDATA[{$mch_id}]]></mch_id>";
            $xml .="<nonce_str><![CDATA[{$nonce_str}]]></nonce_str>";
            $xml .="<notify_url><![CDATA[{$notify_url}]]></notify_url>";
            $xml .="<out_trade_no><![CDATA[{$out_trade_no}]]></out_trade_no>";
            $xml .="<service><![CDATA[{$service}]]></service>";
            $xml .="<sign><![CDATA[{$sign}]]></sign>";
            $xml .="<total_fee><![CDATA[{$total_fee}]]></total_fee>";
            $xml .="</xml>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $zf['tj_url']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));
            curl_setopt($ch,CURLOPT_TIMEOUT,5);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
            $response = curl_exec($ch); // 已经获取到内容，没有输出到页面上。
            curl_close($ch);
            Log::record($response);
            $arrXml = $this->getXml_jx($response);
            if($arrXml['status']==0&&$arrXml['result_code']==0){
                $html  ="<div style='line-height: 100px;text-align:center;width: 300px'>
                        <span style='color:red;font-size:30px'>".$data['msg']."</span>";
                $html .= '<img src='.$arrXml['code_img_url'].'></div>';
                //预支付订单信息
                $rechage = array(
                    'uid'=>$this->user['uid'],
                    'username'=>$this->user['username'],
                    'rechargeId'=>$out_trade_no,
                    'amount'=>$amount,
                    'actionIP'=>$this->ip(true),
                    'actionTime'=>$this->time,
                    'state'=>'0',
                    'info'=>$sign,
                );
                M('member_recharge')->add($rechage);
                echo $html;
            }else{
                $this->error($arrXml['message']);
            }
            exit();
        }catch (\Exception $e){
            Log::record($e->getMessage());
        }
    }

    public function getXml_jx($str)
    {
        $simple = $str;
        $p      = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);
//        echo "Index array\n";
        //        print_r($index);
        //        echo "\nVals array\n";
        $rst = array(
            'status' => '-1',
            'result_code' => '-1',
            'message' => '获取失败',
            'code_img_url' => '--',
            'total_fee' => 0,
            'pay_result' => -1,
            'out_trade_no' => -1,
            'time_end' => 0,
            'sign' => '',
            'trade_type' => '',
            'mch_id' => '',
            'nonce_str' => '',
            'trade_state' => 'fail',
        );
        foreach ($vals as $key => $val) {
            if (strtolower($val['tag']) == 'status') {
                $rst['status'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'message') {
                $rst['message'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'result_code') {
                $rst['result_code'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'code_img_url') {
                $rst['code_img_url'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'out_trade_no') {
                $rst['out_trade_no'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'pay_result') {
                $rst['pay_result'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'total_fee') {
                $rst['total_fee'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'time_end') {
                $rst['time_end'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'sign') {
                $rst['sign'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'trade_type') {
                $rst['trade_type'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'mch_id') {
                $rst['mch_id'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'nonce_str') {
                $rst['nonce_str'] = $val['value'];
            }
            if (strtolower($val['tag']) == 'trade_state') {
                $rst['trade_state'] = strtolower($val['value']);
            }
        }
        return $rst;
    }

    //九霄回调
    public function jx_server(){
        $xmldata = file_get_contents("php://input");
        Log::record('jx开始回调'.$xmldata);
        try{
			$zf  = M('paybusiness')->where(array('id' => 6))->find();
            $business_alias = $zf['business_alias'];
            //回调结果
            $arrXml = $this->getXml_jx($xmldata);
            if($arrXml['status']==0&&$arrXml['result_code']==0){
                $pay_result = $arrXml['pay_result'];
                $out_trade_no = $arrXml['out_trade_no'];
                $service = $arrXml['trade_type'];

                $rechage = M('member_recharge')
                    ->where(array('rechargeId' => $out_trade_no, 'state' => 0))
                    ->find();
                if (empty($rechage)) {
                    Log::record($business_alias.'充值失败，订单不存在,或者已经充值完成');
                    exit('fail');
                    return;
                }
                //验证订单是否支付成功
                $service = str_replace('scan','queryorder',$service);
                if($this->queryJxOrder($out_trade_no,$service)){
                    //更新订单
                    $this->updateOrder($out_trade_no, $arrXml['time_end'],$bank=$zf['business_alias']);
                    Log::record($business_alias.'签名验证成功，支付更新成功');
                }else{
                    Log::record($business_alias.'签名验证失败');
                    $this->createReqInfo($xmldata,$out_trade_no,$business_alias,1,'签名验证失败');
                    exit('fail');
                }
            }else{
                Log::record('通道2支付异常'.$arrXml['message']);
                $this->createReqInfo($xmldata,'',$business_alias,1,'第三方支付状态异常');
                exit('fail');
            }
        }catch (\Exception $e){
            Log::record('jx支付异常'.$e->getMessage());
            $this->createReqInfo($xmldata,'','jx',1,$e->getMessage());
            exit('fail');
        }
        exit('success');
    }

    //九霄订单查询
    public function queryJxOrder($orderId,$trade_type){
        try{
            $zf  = M('paybusiness')->where(array('id' => 6))->find();
            $service = $trade_type;
            $mch_id = $zf['business_id'];
            $out_trade_no = $orderId;
            $nonce_str = $this->guid();
            //签名
            $str = "mch_id={$mch_id}&nonce_str={$nonce_str}";
            $str .= "&out_trade_no={$out_trade_no}&service={$service}";
            $str .= '&key='.$zf['business_key'];
//            Log::record($str);
            $sign = strtoupper(md5($str));

            $xml  ="<xml>";
            $xml .="<mch_id><![CDATA[{$mch_id}]]></mch_id>";
            $xml .="<nonce_str><![CDATA[{$nonce_str}]]></nonce_str>";
            $xml .="<out_trade_no><![CDATA[{$out_trade_no}]]></out_trade_no>";
            $xml .="<service><![CDATA[{$service}]]></service>";
            $xml .="<sign><![CDATA[{$sign}]]></sign>";
            $xml .="</xml>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $zf['tj_url']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));
            curl_setopt($ch,CURLOPT_TIMEOUT,10);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
            $response = curl_exec($ch); // 已经获取到内容，没有输出到页面上。
            curl_close($ch);
//            Log::record($response);
            $arrXml = $this->getXml_jx($response);
            if($arrXml['status']==0&&$arrXml['result_code']==0&&$arrXml['trade_state']=='success'){
                $this->createReqInfo(json_encode($arrXml),$out_trade_no,'jx',0,'第三方订单已经支付完成');
                return true;
            }else{
                $this->createReqInfo(json_encode($arrXml),$out_trade_no,'jx',1,'第三方订单状态未更新');
                return false;
            }
        }catch (\Exception $e){
            Log::record('jx查询订单'.$e->getMessage());
            $this->createReqInfo('jx查询订单异常','','jx',1,'jx查询订单程序异常'.$e->getMessage());
            return false;
        }
    }

    //收米支付
    public function shoumi($data)
    {
        $P_UserId     = $_POST['merchParam'];
        $P_OrderId    = $_POST['orderNo'];
        $P_CustormId  = 'd7f643dc-da60-42b0-bbe8-550d4ce4df8a';
        $P_FaceValue  = $_POST['amount'];
        $P_ChannelId  = $_POST['def-w-label'];
        $P_Notify_URL = '127.0.0.12/index.php';
        $P_PostKey    = md5('d7f643dc-da60-42b0-bbe8-550d4ce4df8a|' . $P_OrderId . '|' . $P_FaceValue . '|' . $P_ChannelId . '|3.1.3|0|zYxHmoFeKPf9gLUh');
        $P_Custorm    = md5('d7f643dc-da60-42b0-bbe8-550d4ce4df8a|zYxHmoFeKPf9gLUh|' . $P_UserId . '');
        $P_CustormId  = $P_UserId . '_' . $P_Custorm;

        $host     = $this->getRootUrl();
        $payLinks = '<form target="_blank" action="http://api.shoumipay.com/gatepay.do?P_UserId=d7f643dc-da60-42b0-bbe8-550d4ce4df8a&P_OrderId=' . $P_OrderId . '&P_FaceValue=' . $P_FaceValue . '&P_ChannelId=' . $P_ChannelId . '&P_SDKVersion=3.1.3&P_RequestType=0&P_Subject=充值&P_PostKey=' . $P_PostKey . '&P_CustormId＝' . $P_CustormId . '&P_Description=测试&P_Result_URL=' . $host . '/index.php?s=/home/recharge/s_server" id="jumplink_1" method="post" name="jumplink_1">充值完毕后，请刷新页面查看</form>';
        $payLinks .= '<script>document.getElementById("jumplink_1").submit();</script>';
        //预支付订单信息
        $rechage = array(
            'uid'        => $this->user['uid'],
            'username'   => $this->user['username'],
            'rechargeId' => $P_OrderId,
            'amount'     => $P_FaceValue,
            'actionIP'   => $this->ip(true),
            'actionTime' => $this->time,
            'state'      => '0',
        );
        M('member_recharge')->add($rechage);
        exit($payLinks);
    }

    public function zhongyun($data)
    {
        $amount = $data['amount'];
        $this->assign('amount', $amount);
//        $this->display('zhongyun');
        $_POST = $data;
        $this->zhongyunPost();
    }

    //中云支付
    public function zhongyunPost()
    {
        try {
            $pay_bankcode = $_POST['yinhang'];
            $pay_amount   = $_POST['amount'];

            $url = $this->getRootUrl();

            $pay_memberid  = 12048; //商户ID
            $pay_orderid   = $this->getRechId(); //订单号
            $pay_amount    = $pay_amount; //交易金额
            $pay_applydate = date("Y-m-d H:i:s"); //订单时间
            $pay_bankcode  = $pay_bankcode; //银行编码
            //$pay_notifyurl = url."/index.php?s=/home/team/rechargerecord.html";   //服务端返回地址
            $pay_notifyurl   = $url . "/index.php?s=/home/recharge/z_server"; //服务端返回地址
            $pay_callbackurl = "http://yhyl678.com/index.php"; //页面跳转返回地址
            $Md5key          = "fzLAQsFr9Q2xtUN6iE1bWVEQe76Zho"; //密钥
            $tjurl           = "http://zf.cnzypay.com/Pay_Index.html"; //提交地址,如有变动请到官网下载最新接口文档

            $requestarray = array(
                "pay_memberid"    => $pay_memberid,
                "pay_orderid"     => $pay_orderid,
                "pay_amount"      => $pay_amount,
                "pay_applydate"   => $pay_applydate,
                "pay_bankcode"    => 'ALIPAY',
                "pay_notifyurl"   => $pay_notifyurl,
                "pay_callbackurl" => $pay_callbackurl,
            );
            ksort($requestarray);
            reset($requestarray);
            $md5str = "";
            foreach ($requestarray as $key => $val) {
                $md5str = $md5str . $key . "=>" . $val . "&";
            }
            $sign                        = strtoupper(md5($md5str . "key=" . $Md5key));
            $requestarray["pay_md5sign"] = $sign;

            $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
            foreach ($requestarray as $key => $val) {
                $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
            }
            // $str = $str . '<input type="submit" value="提交">';
            $str = $str . '</form>';
            $str = $str . '<script>';
            $str = $str . 'document.Form1.submit();';
            $str = $str . '</script>';
            //预支付订单信息
            $rechage = array(
                'uid'        => $this->user['uid'],
                'username'   => $this->user['username'],
                'rechargeId' => $pay_orderid,
                'amount'     => $pay_amount,
                'actionIP'   => $this->ip(true),
                'actionTime' => $this->time,
                'state'      => '0',
            );
            M('member_recharge')->add($rechage);
            exit($str);
        } catch (\Exception $e) {
            Log::record($e->getMessage());
        }
    }

    /**
     * 中云支付回调
     * @return [type] [description]
     */
    public function z_server()
    {
        try {
            Log::record('通道二回调');
            $orderid     = $_REQUEST["orderid"];
            $ReturnArray = array( // 返回字段
                "memberid"   => $_REQUEST["memberid"], // 商户ID
                "orderid"    => $orderid, // 订单号
                "amount"     => $_REQUEST["amount"], // 交易金额
                "datetime"   => $_REQUEST["datetime"], // 交易时间
                "returncode" => $_REQUEST["returncode"],
            );

            $Md5key = "fzLAQsFr9Q2xtUN6iE1bWVEQe76Zho";
            //$sign = $this->md5sign($Md5key, $ReturnArray);

            ///////////////////////////////////////////////////////
            ksort($ReturnArray);
            reset($ReturnArray);
            $md5str = "";
            foreach ($ReturnArray as $key => $val) {
                $md5str = $md5str . $key . "=>" . $val . "&";
            }
            $sign = strtoupper(md5($md5str . "key=" . $Md5key));
            ///////////////////////////////////////////////////////
            if ($sign == $_REQUEST["sign"]) {
                if ($_REQUEST["returncode"] == "00") {
                    $rechage = M('member_recharge')
                        ->where(array('rechargeId' => $orderid, 'state' => 0))
                        ->find();
                    if (empty($rechage)) {
                        Log::record('充值失败，订单不存在'.$orderid);
                        reeturn;
                    }
                    $uid                  = $rechage['uid'];
                    $data['rechargeTime'] = $_REQUEST["datetime"];
                    $data['state']        = 1;
                    $coin                 = M('Members')->where(array('uid' => $uid))->field('coin')->find();
                    if ($coin) {
                        $member['coin'] = $coin['coin'] + $rechage['amount'];
                        M('Members')->where(array('uid' => $uid))->save($member);
                    }

                    if (M('member_recharge')->where(array('rechargeId', $orderid))->save($data)) {
                        $this->redirect('home/index/index', '充值成功，即将返回主页');
                        Log::record('通道1：充值成功');
                    } else {
                        Log::record('通道1：充值失败', json_encode($data));
                        echo "充值失败";
                    }
                } else {
                    Log::record('通道1第三方支付失败');
                }
            } else {
                Log::record('通道1支付认证失败');
            }
        } catch (\Exception $e) {
            Log::record('通道1支付异常.' . $e->getMessage());
            //Log::record($e);
        }
    }

    //收咪回调
    public function s_server()
    {
        try {
            //            if (I('P_Price')) {
            //                $data['amount'] = I('P_Price', 'double');
            //            } else {
            //                $data['amount'] = 0;
            //            }
            $orderid = I('P_OrderId', 'string');
            if (empty($orderid)) {
                Log::record('支付通道2，订单ID没返回');
            }
            $rechage = M('member_recharge')
                ->where(array('rechargeId' => $orderid, 'state' => 0))
                ->find();
            if (empty($rechage)) {
                Log::record('充值失败，订单不存在'.$orderid);
                reeturn;
            }
            $coin = M('Members')->where(array('uid' => $rechage['uid']))->field('coin')->find();
            if ($coin) {
                $member['coin'] = $coin['coin'] + $rechage['amount'];
                M('Members')->where(array('uid' => $rechage['uid']))->save($member);
            }
            $data['rechargeTime'] = $_REQUEST["datetime"];
            $data['state']        = 1;
            if (M('member_recharge')->where(array('rechargeId', $orderid))->save($data)) {
                Log::record('充值成功');
                $this->redirect('home/index/index', '充值成功，即将返回主页');
            } else {
                echo "充值失败";
            }
        } catch (\Exception $e) {
            Log::record('通道二--------支付回调异常');
            Log::record($e->getMessage());
        }
        //$this->redirect();
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

    public function recharge_info()
    {
        echo "string";
    }

    public function getQcode(){
        $type = $_POST['type'];
        $payChannelId = $_POST['payChannelId'];
        $bankCode = $_POST['bankCode'];
        $pay_id = $_POST['pay_id'];
        $params = array(
          'bankCode'=>$bankCode,
          'payChannelId'=>$payChannelId,
          'orderKey'=>$_POST['orderKey'],
          'orderInfo'=>'',
          'chlType'=>'03',
        );
        if($pay_id==10){
            require_once('Service/Pay.php');
            $html = Pay::doPost('https://pay.dinpay.com/Pay',$params);
        }else{
            $html = $this->getHtmlInfo('https://pay.ddbill.com/Pay',$params);
        }
       echo $html;
    }

    private function getRootUrl(){
        $url = $_SERVER['HTTP_REFERER'];
        $arr = explode('/index.php',$url);
        return $arr[0];
    }

    function guid() {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8);
        $uuid .= substr($chars,8,4);
        $uuid .= substr($chars,12,4);
        $uuid .= substr($chars,16,4);
//        $uuid .= substr($chars,20,12);
        return $uuid;
    }

    //创建回调请求数据
    private function createReqInfo($req='',$orderid='',$form='',$status=1,$note=''){
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

    /*20170809 begin*/
    private function qrcode($url='http://www.jihexian.com/',$level=3,$size=4){
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        include_once 'zhifu/phpqrcode.php';
        //生成二维码图片
        //echo $_SERVER['REQUEST_URI'];
        $object = new \QRcode();
        $file = $this->guid().'.png';
        $object->png($url, $file, $errorCorrectionLevel, $matrixPointSize, 2);
        return $file;
    }
    //泽圣
    function zesheng($data){
        try{
            $zf  = M('paybusiness')->where(array('id' => $data['def-w-label']))->find();
            $service = $data['service'];
            $amount = $data['amount'];
            $mch_id = $zf['business_id'];
            $body = '充值';
            $mch_create_ip = '127.0.0.1';
            $merchantCode = $mch_id;
            $outOrderId = $this->getRechId();
            $amount = intval($amount*100);
            $orderCreateTime = date('YmdHis',time());
            $noticeUrl = $this->getRootUrl() . '/index.php?s=/home/recharge/zesheng_server';
            $isSupportCredit = '1';
            $md5Key = $zf['business_key'];
            // 参与签名字段
            $sign_fields1 = Array(
                "merchantCode",
                "outOrderId",
                "amount",
                "orderCreateTime",
                "noticeUrl",
                "isSupportCredit"
            );
            $map1 = Array(
                "merchantCode" => $merchantCode,
                "outOrderId" => $outOrderId,
                "amount" => $amount,
                "orderCreateTime" => $orderCreateTime,
                "noticeUrl" => $noticeUrl,
                "isSupportCredit" => $isSupportCredit
            );
            $sign0 = $this->zesheng_sign_mac($sign_fields1, $map1, $md5Key);
            // 将小写字母转成大写字母
            $sign1 = strtoupper($sign0);
            // 使用方法
            $service_type = $_POST['payLinks'];
            $payChannel = 21;
            $title = "QQ钱包扫码";
            /*$title = "微信";
            if($service_type=='weixin'){
                $payChannel = 21;
            }else if($service_type=='alipay'){
                $payChannel = 30;
                $title = "支付宝";
            }*/
            $post_data1 = array(
                'model' => 'QR_CODE',
                'merchantCode' => $merchantCode,
                'outOrderId' => $outOrderId,
                'deviceNo' => '',
                'amount' => $amount,
                'goodsName' => $body,
                'goodsExplain' => $body,
                'ext' => 'ext',
                'orderCreateTime' => $orderCreateTime,
                'lastPayTime' => $orderCreateTime,
                'noticeUrl' => $noticeUrl,
                'goodsMark' => 'goodsMark',
                'isSupportCredit' => $isSupportCredit,
                'ip' => $mch_create_ip,
                'payChannel' => 31,//21微信，30-支付宝
                'sign' => $sign1
            );
            $res = $this->zesheng_send_post('http://payment.zsagepay.com/scan/entrance.do', $post_data1);
            $arr_res = json_decode($res,true);
            if($arr_res['code']==0){
                //预支付订单信息
                $rechage = array(
                    'uid'=>$this->user['uid'],
                    'username'=>$this->user['username'],
                    'rechargeId'=>$outOrderId,
                    'amount'=>$amount/100,
                    'actionIP'=>$this->ip(true),
                    'actionTime'=>$this->time,
                    'state'=>'0',
                    'info'=>$sign1,
                );
                M('member_recharge')->add($rechage);
                $img = $arr_res['data']['url'];
                $html  ="<div style='line-height: 50px;text-align:center;width: 450px;margin-top: 50px'>
                        <p style='color:#04564f;font-size:28px'>".$title."-支付</p>";
                $html .= '<img src="'.$this->base64EncodeImage($this->qrcode($img,3,5)).'">
                    <p style=\'color:#ff0000;font-size:14px\'>请使用手机QQ扫一扫进行支付，支付完成后刷新本页面即可！</p>
                    </div>';
                echo $html;
                exit();
//                $this->qrcode($img,3,5);
            }else{
                echo $arr_res['msg'];
            }
            exit();
        }catch (\Exception $e){
            Log::record($e->getMessage());
        }
    }
    function base64EncodeImage ($image_file) {
        $base64_image = '';
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
    /*发送数据  */
    function zesheng_send_post($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60
            ) // 超时时间（单位:s）

        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    /* 构建签名原文 */
    function zesheng_sign_src($sign_fields, $map, $md5_key)
    {
        // 排序-字段顺序
        sort($sign_fields);
        $sign_src = "";
        foreach ($sign_fields as $field) {
            $sign_src .= $field . "=" . $map[$field] . "&";
        }
        $sign_src .= "KEY=" . $md5_key;

        return $sign_src;
    }

    /**
     * 计算md5签名  返回的是小写的，后面需转大写
     */
    function zesheng_sign_mac($sign_fields, $map, $md5_key)
    {
        $sign_src = $this->zesheng_sign_src($sign_fields, $map, $md5_key);
        return md5($sign_src);
    }

    function zesheng_server(){
        try{
            $zf  = M('paybusiness')->where(array('id' =>7))->find();
            $md5Key = $zf['business_key'];
            $business_alias = 'zesheng';
            $sign=$_POST["sign"];
            //签名数组
            $sign_fields1 = Array(
                "merchantCode",
                "transType",
                "instructCode",
                "outOrderId",
                "transTime",
                "totalAmount"
            );
            //获取异步通知数据，并赋值给数组
            $out_trade_no = $_POST["outOrderId"];
            $map = Array(
                "merchantCode"=>$_POST["merchantCode"],
                "transType"=>$_POST["transType"],
                "instructCode"=>$_POST["instructCode"],
                "outOrderId"=>$out_trade_no,
                "transTime"=>$_POST["transTime"],
                "totalAmount"=>$_POST["totalAmount"]
            );
            $sign0 = $this->zesheng_sign_mac($sign_fields1, $map, $md5Key);
            // 将小写字母转成大写字母
            $sign1 = strtoupper($sign0);
            //验签
            if($sign === $sign1) {
                echo "{'code':'00'}";
                $rechage = M('member_recharge')
                    ->where(array('rechargeId' => $out_trade_no, 'state' => 0))
                    ->find();
                if (empty($rechage)) {
                    Log::record($business_alias.'充值失败，订单不存在,或者已经充值完成');
                    exit('fail');
                    return;
                }
                //更新订单
                $this->updateOrder($out_trade_no, $_POST["transTime"],$bank=$zf['business_alias']);
                Log::record($business_alias.'签名验证成功，支付更新成功');
            }else {
                echo "{'code':'01'}";
                Log::record('zesheng支付异常'.json_encode($_POST));
                $this->createReqInfo(json_encode($_POST),$out_trade_no,$business_alias,1,'第三方支付状态异常');
                exit('fail');
            }
        }catch (\Exception $e){
            Log::record('zesheng支付异常.' . $e->getMessage());
        }
    }
    /*20170809 end*/
    function zesheng_wy(){
        try{
            $zf  = M('paybusiness')->where(array('id' => 7))->find();
            $amount = $_POST['amount'];
            $merchantCode = $zf['business_id'];
            $outOrderId = $this->getRechId();
            $totalAmount = intval($amount*100);
            $orderCreateTime = date("YmdHis", time() + 3600 * 8);
            // 设置定的最晚支付时间为当前时间后延一天
            $lastPayTime = date("YmdHis", strtotime("+1 days") + 3600 * 8);
            $noticeUrl = $this->getRootUrl() . '/index.php?s=/home/recharge/zesheng_server';
            $md5Key = $zf['business_key'];
            // 参与签名字段
            $sign_fields = Array(
                "merchantCode",
                "outOrderId",
                "totalAmount",
                "orderCreateTime",
                "lastPayTime"
            );
            $map = Array(
                "merchantCode" => $merchantCode,
                "outOrderId" => $outOrderId,
                "totalAmount" => $totalAmount,
                "orderCreateTime" => $orderCreateTime,
                "lastPayTime" => $lastPayTime
            );
            $sign = $this->zesheng_sign_mac($sign_fields, $map, $md5Key);
            // 将小写字母转成大写字母
            $sign = strtoupper($sign);
            $commonUrl = "http://payment.zsagepay.com/";
            $payUrl = $commonUrl . "ebank/pay.do";
            $goodsName = "goodsName";
            $goodsExplain = "goodsExplain";
            $merUrl = "http://192.168.13.160/EHK_PHP/rec.php";
            $bankCode = $_POST['bankCode'];
            $bankCardType = $_POST['bankCardType'];

            //预支付订单信息
            $rechage = array(
                'uid'=>$this->user['uid'],
                'username'=>$this->user['username'],
                'rechargeId'=>$outOrderId,
                'amount'=>$amount,
                'actionIP'=>$this->ip(true),
                'actionTime'=>$this->time,
                'state'=>'0',
                'info'=>$sign,
            );
            M('member_recharge')->add($rechage);
            //提交第三方
            $html = "";
            $html .='<body target=\'_blank\' onLoad="document.zlinepay.submit();" class="center">';
            $html .='	<form type="hidden" name=\'zlinepay\' action='.$payUrl.' method=\'post\' />';
            $html .='	<input type="hidden" name="merchantCode"value='.$merchantCode.' />';
            $html .='	<input type="hidden" name="outOrderId"value='.$outOrderId.' />';
            $html .='	<input type="hidden" name="totalAmount"value='.$totalAmount.' />';
            $html .='	<input type="hidden" name="goodsName"value='.$goodsName.' />';
            $html .='	<input type="hidden" name="goodsExplain"value='.$goodsExplain.' />';
            $html .='	<input type="hidden" name="orderCreateTime"value='.$orderCreateTime.' />';
            $html .='	<input type="hidden" name="lastPayTime"value='.$lastPayTime.' />';
            $html .='	<input type="hidden" name="ext" value="ext" />';
            $html .='	<input type="hidden" name="merUrl"value='.$merUrl.' />';
            $html .='	<input type="hidden" name="noticeUrl"value='.$noticeUrl.' />';
            $html .='	<input type="hidden" name="bankCode" value='.$bankCode.' />';
            $html .='	<input type="hidden" name="bankCardType" value='.$bankCardType.' />';
            $html .='		<input type="hidden" name="sign" value='.$sign.' />';
            $html .='	</form>';
            $html .='</body>';
            echo $html;
            exit();
        }catch (\Exception $e){
            Log::record($e->getMessage());
        }
    }

    public function zbzf($data)
    {
        header('Content-Type: text/html; charset=utf-8;');
        $data['order_sn'] = $this->getRechId();
        $user = $this->user;
        //预支付订单信息
        $rechage = array(
            'uid'=>$user['uid'],
            'username'=>$user['username'],
            'rechargeId'=>$data['order_sn'],
            'amount'=>$data['amount'],
            'actionIP'=>$this->ip(true),
            'actionTime'=>time(),
            'state'=>'0',
            'info'=>'zb预支付'
        );
        M('member_recharge')->add($rechage);
        require_once("Service/ZbPay.php");
        echo ZbPay::getParams($data);
        exit();
    }

    public function zb_callback()
    {
        Log::record('zb通道回调成功=='.json_encode($_REQUEST));
        try{
            $OrderId = urldecode($_REQUEST["orderid"]);		//商户系统传入的orderid
            $Result = urldecode($_REQUEST["result"]);		//订单结果 0：支付成功
            $Amount = urldecode($_REQUEST["amount"]);		//订单金额 单位元
            $SourceAmount = urldecode($_REQUEST["sourceamount"]);		//提交金额 单位元

            $Systemorderid= urldecode($_REQUEST["systemorderid"]);		//此次订单过程中系统内的订单Id
            $Completetime = urldecode($_REQUEST["completetime"]);	//订单时间
            $Sign = urldecode($_REQUEST["sign"]);	//MD5签名

            $Key='d0b85458436b463d966383296a460379';//测试支付密钥，正式环境请更换成您的正式密钥
            $sign_str = "orderid=".$OrderId."&result=".$Result."&amount=".$Amount.
                "&systemorderid=".$Systemorderid."&completetime=".$Completetime."&key=".$Key;

//            echo '<br/>加签源字符串:'.$sign_str;
            $SignLocal = md5($sign_str);
//            echo '<br/>加签字符串:'.$SignLocal;
            $ret2 = ($SignLocal == $Sign);
            Log::record('zb通道回调成功$ret2=='.$ret2);
            if($ret2==1){
                //验签成功
                //建议在此处进行商户的业务逻辑处理
                $this->updateOrder($OrderId, strtotime($Completetime),$bank='zb');
                //注意返回参数中不包括用户的session、cookie
                //如果要正常跳转指定地址，返回应答必须符合规范，参考文档中5.	通知机制
                Log::record('zb成功充值');
            }else{
                Log::record('充值失败,签名验证失败');
                $this->createReqInfo(json_encode($_REQUEST),$OrderId,'zb',1,'充值失败,签名验证失败');
                //验签失败
            }
        }catch (\Exception $e){
            $this->createReqInfo(json_encode($_REQUEST),'','通道四',1,$e->getMessage());
            Log::record($e->getMessage());
            echo 'RespCode=9999|JumpURL=';
        }
        exit();
    }

    public function jqzf($data)
    {
        header('Content-Type: text/html; charset=utf-8;');
        $data['order_sn'] = $this->getRechId();
        $user = $this->user;
        //预支付订单信息
        $rechage = array(
            'uid'=>$user['uid'],
            'username'=>$user['username'],
            'rechargeId'=>$data['order_sn'],
            'amount'=>$data['amount'],
            'actionIP'=>$this->ip(true),
            'actionTime'=>time(),
            'state'=>'0',
            'info'=>'jq预支付'
        );
        M('member_recharge')->add($rechage);
        require_once("Service/JqPay.php");
        echo JqPay::getParams($data);
        exit();
    }

    public function jq_callback()
    {
        Log::record('jq通道回调成功=='.json_encode($_REQUEST));
        try{
            $dealOrder = $_REQUEST['dealOrder'];
//            $dealFee = $_REQUEST['dealFee'];
            $dealState = $_REQUEST['dealState'];
            $dealSignature = $_REQUEST['dealSignure'];
//            $dealId = $_REQUEST['dealId'];
            //生成签名
            $keyCode = "qKxWjKibscavHPB48aOyXqlENY9GBylUcnbMqvBSnwoJMHVY9h6W038KumrlpltFBEQHhmFcOad1D5xJbS1cUPfyrSZ4ftQnpQeBbJPTy2tNPymOdIn1JYDsyVqYYIOl";
            require_once("Service/JqPay.php");
            $strSignature = sha1($dealOrder.$dealState.$keyCode);
            if ( $dealSignature !=$strSignature){
                Log::record('充值失败,签名验证失败');
                $this->createReqInfo(json_encode($_REQUEST),$dealOrder,'jq',1,'充值失败,签名验证失败');
                //验签失败
                return false;
            }else{
                if($dealState=='SUCCESS'){
                    //验签成功
                    Log::record('zb支付回调成功');
                    //建议在此处进行商户的业务逻辑处理
                    $this->updateOrder($dealOrder, time(),$bank='jq');
                }else{
                    Log::record('zb支付失败'.$dealState);
                }
            }
        }catch (\Exception $e){
            $this->createReqInfo(json_encode($_REQUEST),'','jq',1,$e->getMessage());
            Log::record($e->getMessage());
            echo 'RespCode=9999|JumpURL=';
        }
        exit;
    }

    /**yibao*/
    public function yibao(){
        $data = $_POST;
        $order['order_sn'] = $this->getRechId();
        $order['order_amount'] = $data['amount'];
        $order['fl_id'] = $data['fl_id'];
        $user = $this->user;
        //预支付订单信息
        $rechage = array(
            'uid'=>$user['uid'],
            'username'=>$user['username'],
            'rechargeId'=>$order['order_sn'],
            'amount'=>$data['amount'],
            'actionIP'=>$this->ip(true),
            'actionTime'=>time(),
            'state'=>'0',
            'info'=>'yb预支付'
        );
        M('member_recharge')->add($rechage);
        if($data['is_kjzf']){
            $order['userNameHF'] = $data['userNameHF'];
            $order['userAcctNo'] = $data['userAcctNo'];
            $order['userPhoneHF'] = $data['userPhoneHF'];
            $order['quickPayCertNo'] = $data['quickPayCertNo'];
            require_once("Service/YiTongPay.php");
            $param = YiTongPay::quickPay($order,$user['uid']);
        }else {
            $param = $this->get_yb_code($order, array());
        }
        $this->success($param);
    }

    public function yibaoQrcode($data){
        $order['order_sn'] = $this->getRechId();
        $order['order_amount'] = $data['amount'];
        $order['fl_id'] = $data['def-w-label'];
        $order['pay_type'] = $data['payLinks'];
        $user = $this->user;
        //预支付订单信息
        $rechage = array(
            'uid'=>$user['uid'],
            'username'=>$user['username'],
            'rechargeId'=>$order['order_sn'],
            'amount'=>$data['amount'],
            'actionIP'=>$this->ip(true),
            'actionTime'=>time(),
            'state'=>'0',
            'info'=>'yb-qrcode预支付'
        );
        M('member_recharge')->add($rechage);
        $param = $this->get_yb_code($order,array(),$myParams);
        $url = 'https://cashier.etonepay.com/NetPay/BankSelect.action';
        $html = $this->CommonHttpsInfo($url,$myParams);
        $html_arr = explode('codeImg=',$html);
        $base = count($html_arr)>0?$html_arr[1]:"";
        $b_arr = explode('&',$base);
        $base2 = count($b_arr)>0?$b_arr[0]:"";
        echo '<h1 style="margin-left: 20%;color: red;">通道二，qq扫码支付</h1><br/><img style="margin-left: 20%;margin-top: 2%;" src="data:image/png;base64,'.$base2.'" >';
        exit;
    }

    public function CommonHttpsInfo($url,$parmas){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host:cashier.etonepay.com','Origin:http://cashier.etonepay.com','Upgrade-Insecure-Requests:1','Content-Type:application/x-www-form-urlencoded;charset=UTF-8'));
        curl_setopt ($ch, CURLOPT_REFERER, 'http://'.$_SERVER['HTTP_HOST']);
        $User_Agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agent);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($parmas));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 生成支付代码
     * @param array $order 订单信息
     * @param array $payment 支付方式信息
     */
    protected function get_yb_code($order, $payment,&$myParams=array())
    {
        require_once("yb/ascii_class.php");
        $tranAmt = $order['order_amount']*100;
        $merOrderNum = $order['order_sn'];
        $orderInfo = '商户订单';
        $tranDateTime = date('YmdHis');
        $sysTraceNum = $tranDateTime.floor(microtime()*1000); //请求流水号，需要保持唯一
        $userId = ''; //易通支付会员ID代码，可为空
        if(!empty($orderInfo)){
            $orderInfo = strToHex($orderInfo);
        }
        $pay = M('paybusiness')->where(array('id'=>$order['fl_id']))->find();
        $version = "1.0.0";
        $transCode = "8888"; //交易代码
        $merchantId = $pay['business_id'];
        $bussId = $order['pay_type']!='qq'?"883077":"ONL0010"; //业务代码 883077 PC网银883021 手机扫码883022　PC扫码
        $currencyType = "156"; //币种 156=人民币
        $merUrl = 'http://'.$_SERVER['HTTP_HOST']; //页面返回地址
        $backUrl = 'http://'.$_SERVER['HTTP_HOST']."/home/recharge/payResult"; //后台通知地址
        $datakey = $pay['business_key'];
        $txnString = $version."|".$transCode."|".$merchantId."|".$merOrderNum."|".$bussId."|".$tranAmt."|".$sysTraceNum
            ."|".$tranDateTime."|".$currencyType."|".$merUrl."|".$backUrl."|".$orderInfo."|".$userId;
        $signValue = md5($txnString.$datakey);
        $myParams                      = array();
        $myParams['version']     = $version;
        $myParams['transCode']     = $transCode;
        $myParams['merchantId']     = $merchantId;
        $myParams['merOrderNum']     = $merOrderNum;
        $myParams['bussId']     = $bussId;
        $myParams['tranAmt']     = $tranAmt;
        $myParams['sysTraceNum']     = $sysTraceNum;
        $myParams['tranDateTime']     = $tranDateTime;
        $myParams['currencyType']     = $currencyType;
        $myParams['merURL']     = $merUrl;
        $myParams['orderInfo']     = $orderInfo;
        $myParams['bankId']     = $order['pay_type']!='qq'?'':'888880600002900';//主扫：888880600002900 被扫：888880601002900
        $myParams['stlmId']     = '';
        $myParams['userId']     = '';
        $myParams['userIp']     = '';
        $myParams['backURL']     = $backUrl;
        $myParams['reserver1']     = $order['fl_id'];
        $myParams['reserver2']     = '';
        $myParams['reserver3']     = '';
        $myParams['reserver4']     = '';
        $myParams['signValue'] = $signValue;
        $rtn_ar = array();
        while ($obj = each($myParams)) {
            Log::record($obj['key']);
            array_push($rtn_ar,array(
                'name'=>$obj['key'],
                'value'=>$obj['value'],
            ));
        }
        return $rtn_ar;
    }

    public function payResult(){
        try{
            $transCode = $_REQUEST["transCode"];
            $merchantId = $_REQUEST["merchantId"];
            $respCode = $_REQUEST["respCode"];
            $merOrderNum = $_REQUEST["merOrderNum"];
            $bussId = $_REQUEST["bussId"];
            $tranAmt = $_REQUEST["tranAmt"];
            $orderAmt = $_REQUEST["orderAmt"];
            $bankFeeAmt = $_REQUEST["bankFeeAmt"];
            $integralAmt = $_REQUEST["integralAmt"];
            $vaAmt = $_REQUEST["vaAmt"];
            $bankAmt = $_REQUEST["bankAmt"];
            $bankId = $_REQUEST["bankId"];
            $sysTraceNum = $_REQUEST["sysTraceNum"];
            $integralSeq = $_REQUEST["integralSeq"];
            $vaSeq = $_REQUEST["vaSeq"];
            $bankSeq = $_REQUEST["bankSeq"];
            $tranDateTime = $_REQUEST["tranDateTime"];
            $payMentTime = $_REQUEST["payMentTime"];
            $settleDate = $_REQUEST["settleDate"];
            $currencyType = $_REQUEST["currencyType"];
            $orderInfo = $_REQUEST["orderInfo"];
            $userId = $_REQUEST["userId"];
            $orderId = $_REQUEST["orderId"];
            $signValue = $_REQUEST["signValue"];
            $txnString =  $transCode ."|". $merchantId ."|". $respCode ."|". $sysTraceNum ."|". $merOrderNum ."|"
                . $orderId ."|". $bussId ."|". $tranAmt ."|". $orderAmt ."|" .$bankFeeAmt ."|". $integralAmt ."|"
                . $vaAmt ."|". $bankAmt ."|". $bankId ."|". $integralSeq ."|". $vaSeq ."|"
                . $bankSeq ."|". $tranDateTime ."|". $payMentTime ."|". $settleDate ."|". $currencyType ."|". $orderInfo ."|". $userId;
            $pay = M('paybusiness')->where(array('id'=>9))->find();
            $verifySign = $this->verify_Sign($txnString,$signValue,$pay['business_key']);
            if($respCode=='0000'&&$verifySign){
                Log::record('yibao-验证通过');
                $this->updateOrder($merOrderNum, strtotime($payMentTime),$bank='yibao');
                exit('success');
            }else{
                Log::record('yibao-验证失败，$respCode='.$respCode.'===erifySign=='.$verifySign);
                $this->createReqInfo(json_encode($_POST),$merOrderNum,'yibao',1,'充值失败,签名验证失败');
                exit('fail');
            }
        }catch (\Exception $e){
            Log::record('yibao-回调异常',$e->getLine().'-'.$e->getMessage().'===data='.json_encode($_REQUEST));
            exit('fail');
        }
    }

    private function verify_sign($txnString, $signString,$datakey)
    {
        return md5($txnString.$datakey)==$signString;
    }
    /**yibao*/

    //智付网银
    public function getZfwy()
    {
        $data = $_POST;
        try{
            require_once("Service/Pay.php");
            $user = $this->user;
            $output = Pay::bankPay();
            $order_no = Pay::$order_no;
            $order_amount = Pay::$order_amount;
            //获取 orderKey
            $exp = explode('name="orderKey" value="',$output);
            $exp = explode('"',$exp[1]);
            // 微信 pay_id
            $radio = explode('<input type="radio"  value=',$output);
            $wxid = explode(' ',$radio[1]);
            $pay_code = $wxid[1];
            $wx_pay_id = $wxid[0];
            $this->assign('wx_pay_id',$wx_pay_id);
            //pay_code
            $wxcode = explode('id=',$pay_code);
            $wxcode = explode(' ',$wxcode[1]);
            $wxcode = $wxcode[0];
            preg_match('/[a-zA-Z]+/',$wxcode, $matches);
            $wx_pay_code = $matches[0];
            $this->assign('wx_pay_code',$wx_pay_code);
            //微信

            //支付宝  zfb_id
            /*  $zfb_id = explode(' ',$radio[2]);
              $pay_code = $zfb_id[1];
              $zfb_id = $zfb_id[0];*/
            $this->assign('zfb_pay_id','');
            //pay_code
            /*$al_code = explode('id=',$pay_code);
            $al_code = explode(' ',$al_code[1]);
            $al_code = $al_code[0];*/
            preg_match('/[a-zA-Z]+/','', $matches);
//                $al_pay_code = $matches[0];
            $this->assign('zfb_pay_code','');
            //预支付订单信息
            $rechage = array(
                'uid'=>$user['uid'],
                'username'=>$user['username'],
                'rechargeId'=>$order_no,
                'amount'=>$order_amount,
                'actionIP'=>$this->ip(true),
                'actionTime'=>time(),
                'state'=>'0',
                'info'=>'预支付'
            );
            M('member_recharge')->add($rechage);
            header("Content-type: text/html; charset=utf-8");
            $this->assign('orderKey',$exp[0]);
            $this->assign('output',$output);
            $this->assign('pay_id',$data['def-w-label']);
            $this->display('zfwy');exit;
        }catch (\Exception $e){
            Log::record($e->getMessage());
        }
    }

    public function zf_server1()
    {
        require_once("Service/Pay.php");
        return Pay::offlineNotify();
    }
    /*  //没有任何方法，直接执行HomeController的_empty方法
//请不要删除该控制器
final public function index()
{
if (IS_POST) {

if (I('amount') <= 0) {
$this->error('充值金额必须大于0');
}

// 插入提现请求表
unset($para['coinpwd']);
$para['rechargeId'] = $this->getRechId();
$para['actionTime'] = $this->time;
$para['uid']        = $this->user['uid'];
$para['username']   = $this->user['username'];
$para['actionIP']   = $this->ip(true);
$para['mBankId']    = 13;
$para['info']       = '在线支付';
$para['amount']     = intval(I('amount'));

if (M('member_recharge')->add($para)) {

} else {
$this->error('充值订单生产请求出错');
}

$data['rechargeId'] = $para['rechargeId'];
$this->ajaxReturn($data, 'json');
} else {
$banks    = M('member_bank')->where(array('admin' => 1, 'enable' => 1))->select();
$bankList = M('bank_list')->where(array('isDelete' => 0))->select();
$banks2   = array();

$i = 0;
foreach ($banks as $bank) {
foreach ($bankList as $b) {
if ($bank['bankId'] == $b['id']) {
$banks2[$i] = array_merge($bank, $b);
$i++;
}
}
}
///print_r($banks2);exit;
$set = $this->getSystemSettings();
$this->assign('set', $set);
$this->assign('banks', $banks2);
$this->assign('coinPassword', $this->user['coinPassword']);
$this->display();
}
}

/* 进入充值，生产充值订单
final public function recharge()
{
dump(I('amount'));
if (I('amount') <= 0) {
$this->error('充值金额必须大于0');
}

$user = M('members')->find($this->user['uid']);
if ($user['coinPassword'] != think_ucenter_md5(I('coinpwd'), UC_AUTH_KEY)) {
$this->error('资金密码不正确');
} else {
// 插入提现请求表
unset($para['coinpwd']);
$para['rechargeId'] = $this->getRechId();
$para['actionTime'] = $this->time;
$para['uid']        = $this->user['uid'];
$para['username']   = $this->user['username'];
$para['actionIP']   = $this->ip(true);
$para['mBankId']    = I('mBankId');
$para['info']       = '用户充值';
$para['amount']     = intval(I('amount'));

if (M('member_recharge')->add($para)) {

$bank     = M('member_bank')->where(array('admin' => 1, 'enable' => 1, 'bankId' => I('mBankId')))->find();
$bankList = M('bank_list')->where(array('isDelete' => 0))->select();

foreach ($bankList as $b) {
if ($bank['bankId'] == $b['id']) {
$bank = array_merge($bank, $b);
}
}

$this->assign('para', $para);
$this->assign('memberBank', $bank);
$this->display('recharge/recharge');
} else {
$this->error('充值订单生产请求出错');
}
}

}

//充值详单
final public function info()
{
$rechargeInfo = M('member_recharge')->where(array('id' => I('id')))->find();
$bankInfo     = M('member_bank')->where(array('uid' => $rechargeInfo['uid']))->find();
$list         = M('bank_list')->order('id')->select();

$bankList = array();
if ($list) {
foreach ($list as $var) {
$bankList[$var['id']] = $var;
}
}

$this->assign('rechargeInfo', $rechargeInfo);
$this->assign('bankInfo', $bankInfo);
$this->assign('bankList', $bankList);

$this->display('Recharge/recharge-info');
}*/
}
