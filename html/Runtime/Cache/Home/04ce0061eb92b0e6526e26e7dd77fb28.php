<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<META name="renderer" content="webkit">
<META charset="utf-8">
<META http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<META name="viewport" content="width=device-width, initial-scale=1.0">

    <title>在线充值－<?php echo S('WEB_NAME');?></title>


<link href="/Public/Home/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/Home/css/bootstrap-slider.all.css" rel="stylesheet">

<link href="/Public/Home/css/nifty.min.css" rel="stylesheet">
<link href="/Public/Home/css/font-awesome.min.css" rel="stylesheet">
<link href="/Public/Home/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="/Public/Home/css/switchery.min.css" rel="stylesheet">
<link href="/Public/Home/css/css.min.css" rel="stylesheet">
<link href="/Public/Home/css/withdraw.css" rel="stylesheet">
<link href="/Public/Home/css/comm.min.css" rel="stylesheet">
<link href="/Public/Home/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/Public/Home/css/dataTables.responsive.min.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Home/css/select2.min.css">
<meta name="GENERATOR" content="MSHTML 11.00.9600.16428">

<!-- IE浏览器时需要以下js支持 -->

<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>

	
    <link href="/Public/Home/css/chongzhi.css" rel="stylesheet">

	<!-- /头部 -->
	

    <!--<script src="/Public/Home/js/jquery.min.js"></script>-->
    <script src="/Public/Home/js/jquery.min.js"></script>

    <script src="/Public/Home/js/bootstrap.min.js"></script>


    <script src="/Public/Home/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/Public/Home/js/bootstrap-datetimepicker.zh-CN.min.js"></script>
    <script src="/Public/Home/js/nifty.min.js"></script>
    <script src="/Public/Home/js/switchery.min.js"></script>
    <script src="/Public/Home/js/md5.min.js"></script>
    <script src="/Public/Home/js/common.min.js"></script>
    <script src="/Public/Home/js/dataTables.min.js"></script>
    <script src="/Public/Home/js/dataTables.bootstrap.min.js"></script>
    <script src="/Public/Home/js/dataTables.responsive.min.js"></script>
    <script src="/Public/Home/js/bootbox.min.js"></script>

    <script src="/Public/Home/js/layer/layui/layui.js"></script>
    <script src="/Public/Home/js/layer/laydate/laydate.js"></script>
    <link rel="stylesheet" href="/Public/Home/js/layer/layui/css/layui.css">

    <script type="text/javascript">
        $(document).ready(function () {
            $("body").click(
                function toggle() {
                    $("div#demo-set", window.top.document).removeClass("open");
                    $(".mega-dropdown", window.top.document).removeClass("open");
                    $(".dropdown", window.top.document).removeClass("open");
                    $(".lskj").fadeOut(200);
                }
            );
        });
        $('.form_datetime').datetimepicker({
            autoclose: 1,
            todayBtn: 0,
            minView: 2,
            language: 'zh-CN',
            format: 'yyyy-mm-dd hh:ii'
        });
        $('.form_datetime').focus(function () {
            this.blur();
        });
    </script>




</head>

<body>

	<!-- 头部 -->
	




	
    <script src="/Public/Home/js/bootbox.min.js"></script>
    <script type="text/javascript">
        $("#j-query").on("click", function() {
        var me = this;
        $(me).attr('disabled', true);
        $.ajax({
            type: "POST",
            url: '<?php echo U("cash/cash");?>',
            data: {
                amount: $('#TikuanMoney').val(),
                coinpwd: $('#FundsPass').val(),
                id: $('#UserBankId').val()
            },
            dataType: "json",
            global: false,
            success: function(result) {
                $(me).removeAttr('disabled');
                bootbox.alert(result.info);
            },
            error: function(err) {
                var t = err;
            }
        });
        //$("#teambetrecord").submit();
        });
        /*$('#wxcz').on('click',function(){
            var me = this;
            $("#wxcz_select")[0].style.display='block';
        });
        $('#zfbcz').on('click',function(){
            var me = this;
            $("#wxcz_select")[0].style.display='none';
        });
        $('#yhcz').on('click',function(){
            var me = this;
            $("#wxcz_select")[0].style.display='none';
        });*/

        function checkmoney() {
            var min = <?php echo ($bank_check["min"]); ?>;
            var max = <?php echo ($bank_check["max"]); ?>;
            var amount = parseFloat($('#amt').val());
            if (isNaN(amount) || amount < min || amount > max) {
                bootbox.alert('充值金额必须在' + min + '元和' + max + '元之间');
                return false;
            }
        }
    </script>
    <script type="text/javascript">
        var is_change = false;
        var is_show = <?php echo ($isShow); ?>;
        var fl_id = <?php echo ($def_id); ?>;
        $(function() {
            // var i = $('.layui-tab-title li').index();
            //
            // var data = $('.shang_payselect').eq(i).children(0).attr('data-id');
            // $('#def-w-label').attr('value', $('.layui-tab-title li').attr('data-value'));
            //
            // $("#payLinks").attr('value', data);
            $('.layui-tab-title li').click(function(t) {
                is_change = true;
                var i = $(this).index();
                fl_id = $('.layui-tab-title').children().eq(i)[0].id;
                if(fl_id==5||fl_id==8){
                    document.getElementById("recharge").action = "<?php echo U('recharge/zfwy');?>";
                }
                var data = $('.shang_payselect').eq(i).children(0).attr('data-id');
                $('#def-w-label').attr('value', $(this).attr('data-value'));

                $("#payLinks").attr('value', data);
                if(fl_id!=1){
                    $("#layui-tab-content").show();
                    $("#userIpt").hide();
                }
                if(fl_id!=104){
                    $("#zbzf_wy").hide();
                }
                if(fl_id==104){
                    $("#layui-tab-content").hide();
                    $("#userIpt").hide();
                }
                if(fl_id==6){
                    min = <?php echo ($code_check["min"]); ?>;
                    max = <?php echo ($code_check["max"]); ?>;
                }else{
                    min = <?php echo ($bank_check["min"]); ?>;
                    max = <?php echo ($bank_check["max"]); ?>;
                }
                $("#shang_pay_txt").html("单笔最低充值金额为<span>"+min+"</span>元，最高<span>"+max+"</span>元" );
            });
            $(".pay_item").click(function() {
                $(this).addClass('checked').siblings('.pay_item').removeClass('checked');
                var dataid = $(this).attr('data-id');
                $("#payLinks").attr('value', dataid);
                if(dataid == "onlinepay"){
                    min = <?php echo ($bank_check["min"]); ?>;
                    max = <?php echo ($bank_check["max"]); ?>;
                }else{
                    min = <?php echo ($code_check["min"]); ?>;
                    max = <?php echo ($code_check["max"]); ?>;
                }
                $("#shang_pay_txt").html("单笔最低充值金额为<span>"+min+"</span>元，最高<span>"+max+"</span>元" );
            });
        })
    </script>

	<!-- 主体 -->
	
    <?php
 require_once("./mb/Mobaopay.Config.php"); $time = time(); $tradeDate = date("Ymd",$time); ?>

        <div class="effect mainnav-lg mainnav-fixed navbar-fixed footer-fixed" id="container">
            <div id="page-content">

                <form id="recharge" name="recharge" method='POST' action="<?php echo U('recharge/index');?>" role="form" target="_blank">
                    <input size="50" type="hidden" name="payLinks" id="payLinks" value="<?php echo ($direct_pay); ?>" />
                    <input type="hidden" name="def-w-label" id="def-w-label" value="<?php echo ($def_id); ?>">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title"><img alt="" src="/Public/Home/images/main/cz.png">充值</h3>
                        </div>
                        <?php if(session('user.is_test')){ ?>
                        <div class="panel-body chongzhibox">
                                <span>此账号无此权限！</span>
                        </div>
                        <?php }else{ ?>
                        <div class="panel-body chongzhibox">
                            <div class="czbox">
                                <div class="czrow">
                                    <div class="czlabel">
                                        充值金额：
                                    </div>
                                    <div class="cztxt">
                                        <div class="jinebox">
                                            <input name="StyleName" id="StyleName" type="hidden" value="alipayzhaoshang ">
                                            <input name="amount" oninput="if(value>50000)value=50000" min="<?php echo ($code_check["min"]); ?>" max="<?php echo ($bank_check["max"]); ?>" id="amt" type="number" placeholder="请输入充值金额" value="" data-val-required="RechargeMoney 字段是必需的。" data-val-number="The field RechargeMoney must be a number." data-val="true">
                                            元
                                        </div>
                                        <style media="screen">
                                            #shang_pay_txt span {
                                                color: red;
                                                font-size: 22px;
                                            }
                                        </style>
                                        充值金额：
                                        <span id='shang_pay_txt'>单笔最低充值金额为<span><?php echo ($bank_check["min"]); ?></span>元，最高<span><?php echo ($bank_check["max"]); ?></span>元</span>
                                    </div>

                                </div>
                                <div class="layui-tab layui-tab-brief">
                                    <ul class="layui-tab-title">
                                        <li data-value='104' id="104" onclick="zbzf()">
                                            通道一(众宝)
                                        </li>
                                        <li style="display: none" data-value='1' id="1" onclick="showkjzf()">
                                            快捷支付
                                        </li>
                                        <style>
                                            .section{
                                                padding: 10px;
                                                font-size: 17px;
                                                position: relative;
                                                width: 755px;
                                            }
                                        </style>
                                        <script>
                                            function showkjzf(){
                                                $("#layui-tab-content").hide();
                                                $("#zbzf_wy").hide();
                                                $("#userIpt").show();
                                            }
                                            function zbzf(){
                                                fl_id = 104;
                                                $("#layui-tab-content").hide();
                                                $("#userIpt").hide();
                                                $("#zbzf_wy").show();
                                            }
                                        </script>
                                        <?php if(is_array($paybusiness)): $i = 0; $__LIST__ = $paybusiness;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["business_name"] == '智付支付' ): ?><li class="layui-this" data-value='<?php echo ($vo["id"]); ?>' id="<?php echo ($vo["id"]); ?>">
                                                    <?php echo ($vo["business_alias"]); ?>
                                                </li>
                                                <?php else: ?>
                                                <li data-value='<?php echo ($vo["id"]); ?>' id="<?php echo ($vo["id"]); ?>">
                                                    <?php echo ($vo["business_alias"]); ?>
                                                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                    </ul>
                                    <style media="screen">
                                        .radiobox {
                                            width: 16px;
                                            height: 16px;
                                            background: url('/Public/Home/images/pay/radio2.jpg');
                                            display: block;
                                            float: left;
                                            margin: 15px;
                                        }

                                        .checked .radiobox {
                                            background: url('/Public/Home/images/pay/radio1.jpg');
                                        }

                                        .shang_payselect {
                                            text-align: center;
                                            margin: 0 auto;
                                            margin-top: 40px;
                                            cursor: pointer;
                                            height: 60px;
                                        }

                                        .shang_payselect .pay_item {
                                            display: inline-block;
                                            margin-right: 10px;
                                            float: left;
                                        }

                                        .shang_info {
                                            clear: both;
                                        }

                                        .shang_info p,
                                        .shang_info a {
                                            color: #C3C3C3;
                                            text-align: center;
                                            font-size: 12px;
                                            text-decoration: none;
                                            line-height: 2em;
                                        }
                                    </style>

                                    <div class="section" id="userIpt" style="display: none;">
                                        <hr>
                                        <h4>请填写快捷支付信息</h4>
                                        <div class="userInput">开户名（姓名）：<input type="text" placeholder="如:张XX" style="width: 100px;"  id="userNameHF"/> <b style="color: red">*</b></div>
                                        <div class="userInput">银行绑定手机号：<input type="text" placeholder="如:1382222xxxx" style="width: 200px;" id="userPhoneHF"/> <b style="color: red">*</b></div>
                                        <div class="userInput">开户行银行卡号：<input type="text" placeholder="如:622185468971255xxx" style="width: 200px;" id="userAcctNo"/> <b style="color: red">*</b></div>
                                        <div class="userInput">开户行身份证号：<input type="text" placeholder="如:102826198101013xxx" style="width: 200px;" id="quickPayCertNo"/> <b style="color: red">*</b></div>
                                    </div>
                                    <div style="font-size: 20px;display: none" id="zbzf_wy">
                                        &nbsp;&nbsp;选择银行：
                                        <select Name="paytype" style="width:150px;height: 32px;">
                                            <option value="962">中信银行</option>
                                            <option value="963">中国银行</option>
                                            <option value="964">农业银行</option>
                                            <option value="965">建设银行</option>
                                            <option value="967">工商银行</option>
                                            <option value="970">招商银行</option>
                                            <option value="971">邮储银行</option>
                                            <option value="972">兴业银行</option>
                                            <option value="976">上海农村商业银行</option>
                                            <option value="977">浦发银行</option>
                                            <option value="979">南京银行</option>
                                            <option value="980">民生银行</option>
                                            <option value="981">交通银行</option>
                                            <option value="983">杭州银行</option>
                                            <option value="985">广发银行</option>
                                            <option value="986">光大银行</option>
                                            <option value="987">东亚银行</option>
                                            <option value="989">北京银行</option>
                                            <option value="990">平安银行</option>
                                            <option value="991">华夏银行</option>
                                            <option value="992">上海银行</option>
                                            <option value="1000">微信扫码</option>
                                            <option value="1002">微信直连</option>
                                            <option value="1003">支付宝扫码</option>
                                            <option value="1004">支付宝直连</option>
                                            <option value="1005">QQ钱包扫码</option>
                                            <option value="1006">QQ钱包直连</option>
                                            <option value="1007">京东钱包扫码</option>
                                            <option value="1008">京东钱包直连</option>
                                            <option value="1009">银联扫码</option>
                                            <option value="1012">银联在线</option>
                                        </select>
                                    </div>
                                    <div class="layui-tab-content" id="layui-tab-content" style="">
                                        <?php if($isShow == 'true' ): ?><div class="layui-tab-item layui-show">
                                            <div class="shang_payselect">
                                                <div class="pay_item checked" data-id="onlinepay">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/onlinepay.png" alt="网银在线支付" /></span>
                                                </div>
                                                <div class="pay_item" data-id="weixin">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/weixin.png" alt="微信在线支付" /></span>
                                                </div>
                                                <div class="pay_item" data-id="alipay">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/alipay.png" alt="支付宝在线支付" /></span>
                                                </div>
                                            </div>
                                        </div><?php endif; ?>
                                        <?php if($isShow == 'true' ): ?><div class="layui-tab-item">
                                            <else>
                                                <div class="layui-tab-item layui-show"><?php endif; ?>
                                            <div class="shang_payselect">
                                                <div class="pay_item checked" data-id="direct_pay" style="">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/onlinepay.png" alt="网银在线支付" /></span>
                                                </div>
                                                <!--<div class="pay_item" data-id="weixin">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/weixin.png" alt="微信在线支付" /></span>
                                                </div>
                                                <div class="pay_item" data-id="alipay">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/alipay.png" alt="支付宝在线支付" /></span>
                                                </div>-->
                                                <div class="pay_item" data-id="qq">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/qq.png" alt="qq支付" /></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-tab-item">
                                            <div class="shang_payselect">
                                                <div class="pay_item checked" data-id="direct_pay">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/onlinepay.png" alt="网银在线支付" /></span>
                                                </div>
                                                <div class="pay_item" data-id="weixin">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/weixin.png" alt="微信在线支付" /></span>
                                                </div>
                                                <div class="pay_item" data-id="alipay">
                                                    <span class="radiobox"></span>
                                                    <span class="pay_logo"><img src="/Public/Home/images/pay/alipay.png" alt="支付宝在线支付" /></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <script>
                                    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
                                    layui.use('element', function() {
                                        var element = layui.element();

                                        //…
                                    });
                                </script>


                            </div>
                            <div class="czfoot">
                                <div class="czfootbox">
                                    <button class="btn btn-primary" autocomplete="off" type="submit" onclick="return fukuan(this);">
                                        继续下一步
                                    </button>
                                    <script>
                                        var min = <?php echo ($bank_check["min"]); ?>;
                                        var max = <?php echo ($bank_check["max"]); ?>;
                                        function fukuan(me) {
                                            var amount = parseFloat($('#amt').val());
                                            if (isNaN(amount) || amount < min || amount > max) {
                                                bootbox.alert('充值金额必须在' + min + '元和' + max + '元之间');
                                                return false;
                                            }else if((fl_id==5||fl_id==8)&&is_show==true){
                                                document.getElementById("recharge").action = "<?php echo U('recharge/zfwy');?>";
                                            }else if(fl_id==1||fl_id==9&&$("#payLinks").attr('value')!='qq'){
                                                yibao(amount,fl_id);
                                                return false;
                                            }
                                        }
                                        function yibao(amount,fl_id){
                                            var userNameHF,userPhoneHF,userAcctNo,quickPayCertNo,is_kjzf = 0;
                                            if(fl_id == 1){
                                                is_kjzf = 1;
                                                userNameHF = $('#userNameHF').val();
                                                userPhoneHF = $('#userPhoneHF').val();
                                                userAcctNo = $('#userAcctNo').val();
                                                quickPayCertNo = $('#quickPayCertNo').val();
                                                if(!userNameHF){
                                                    alert('开户名（姓名）不能为空！');
                                                    return false;
                                                }
                                                if(!userAcctNo){
                                                    alert('开户行银行卡号不能为空！');
                                                    return false;
                                                }
                                                if(!userPhoneHF){
                                                    alert('银行绑定手机号：不能为空！');
                                                    return false;
                                                }
                                                if(!quickPayCertNo){
                                                    alert('开户行身份证号不能为空！');
                                                    return false;
                                                }
                                            }
                                            if(fl_id==1){
                                                fl_id = 9;
                                            }
                                            $.ajax({
                                                type: "POST",
                                                url: "<?php echo U('recharge/yibao');?>",
                                                data: { amount: amount,fl_id:fl_id,pay_type:'onlinepay',is_kjzf:is_kjzf,
                                                    userNameHF:userNameHF,userAcctNo:userAcctNo,userPhoneHF:userPhoneHF,quickPayCertNo:quickPayCertNo
                                                },
                                                dataType: "json",
                                                global: false,
                                                async: true,
                                                success: function (data) {
                                                    if (data.status==1) {
                                                        var arr = data.info;
                                                        var html = "";
                                                        debugger;
                                                        arr.forEach(function(val){
                                                            html += "<input type = 'hidden' name='"+val.name+"' value='"+val.value+"' />";
                                                        });
                                                        document.getElementById('yibao').innerHTML=html;
                                                        document.yibao.submit();
                                                    }else {
                                                        alert(data.msg);
                                                    }
                                                },
                                                error: null,
                                                cache: false
                                            });
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <input size="50" type="hidden" name="orderNo" id="orderNo" value='<?php echo ($orderNo); ?>' />
                    <input size="50" type="hidden" name="tradeDate" id="tradeDate" value='<?php echo $tradeDate; ?>' />
                    <input size="50" type="hidden" name="merchParam" id="merchParam" value="<?php echo ($user["uid"]); ?>" />
                    <input size="50" type="hidden" name="tradeSummary" id="tradeSummary" value="<?php echo ($user["username"]); ?>" />

                </form>
            <form id="yibao" name='yibao' style='text-align:center;display:none' method=post action='https://cashier.etonepay.com/NetPay/BankSelect.action' target='_blank'>
                <input type=submit value="去充值" style="display: none">
            </form>
            </div>
        </div>



	<!-- /主体 -->

	<!-- 底部 -->
	


	<!-- /底部 -->
</body>

</html>