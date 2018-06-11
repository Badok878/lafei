<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <title><?php echo ($types[$type]['title']); ?></title>
    <meta content="no-cache" http-equiv="Pragma">
    <link href="/Public/Home/css/comm.min.css" rel="stylesheet" type="text/css">
    <link href="/Public/Home/css/game/play.css" rel="stylesheet" type="text/css">
    <link href="/Public/Home/css/game/waves.css" rel="stylesheet" type="text/css">
    <link href="/Public/Home/css/game/animate.css" rel="stylesheet" type="text/css">
    <link href="/Public/Home/css/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css">
    <?php if($type==20){ ?>
    <link href="/Public/Home/css/play_pk10.min.css" rel="stylesheet" type="text/css">
    <?php } ?>
    <?php if($type==24){ ?>
    <link href="/Public/Home/css/k8.min.css" rel="stylesheet" type="text/css">
    <?php } ?>
    <style type="text/css">
    .compressprogroupmask {
        display: none;
    }
    
    .compressprogroup {
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -230px;
        height: 180px;
        margin-top: -90px;
    }
    
    body .demo-class .layui-layer-title {
        background: #f37032;
        color: #fff;
        border: none;
    }
    
    body .demo-class .layui-layer-btn a {
        background: #f37032;
        color: #fff;
        border: none;
    }
    
    body .demo-class .layui-layer-btn .layui-layer-btn1 {
        background: #fff;
    }
    </style>
    <script type="text/javascript" src="/Public/Home/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/function.js"></script>
    <script type="text/javascript" src="/Public/Home/js/layer/layer.js"></script>
    <script type="text/javascript" src="/Public/Home/js/game.js?ver=20160902"></script>
    <script type="text/javascript" src="/Public/Home/js/jquery-ui-1.8.23.custom.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/jquery.simplemodal.src.js"></script>
</head>

<body>
    <div class="compressprogroupmask" style="background-color:rgba(0,0,0,.7);position:fixed;left:0;top:0;width:100%;height:100%; z-index:99999999">
        <div id="j-progress-div1" class="compressprogroup" style="width:460px; height:160px;color:#ed6c44;font-size:20px; text-align:center;background-color:#f4efec;border-radius:10px; padding-top:20px;box-shadow:0px 0px 10px rgba(0,0,0,.6)">
            <img src="/Public/Home/images/game/ling.gif" style="margin-right:10px;">
            <p style="padding-top:10px;">
                超大文本正在压缩上传中(进度：<span id="j-show-progress">0</span>%)
            </p>
        </div>
        <div id="j-progress-div2" class="compressprogroup" style="width:460px; height:160px;color:#999;font-size:20px; text-align:center;background-color:#f4efec;border-radius:10px; padding-top:20px;box-shadow:0px 0px 10px rgba(0,0,0,.6)">
            <img src="/Public/Home/images/game/ok.png" style="margin-right:10px; height:85px"> →
            <img src="/Public/Home/images/game/ling.gif" style="margin-left:10px;">
            <p style="padding-top:10px; color:#ed6c44;font-size:24px;">
                压缩完成，正在上传服务器。
            </p>
        </div>
    </div>
    <div id="rightcon">
        <div class="linfo">
            <div class="linfoleft animated flipInY">
                <img alt="" src="/Public/Home/images/game/<?php echo ($types[$type]['type']); ?>.png">
                <div><?php echo ($types[$type]['title']); ?></div>
            </div>
            <div class="linfomiddle animated flipInY">
                <span><strong><span id="current_issue"><?php echo ($actionNo['actionNo']); ?></span></strong>期
                <label>投注剩余时间</label>
                </span>
                <span id="count_down">00&nbsp;00&nbsp;00</span>
            </div>
            <div class="linforight animated flipInY">
                <div class="lskj animated flipInY" id="historylot" style="display: none;">
                    <div class="lskjtitle">
	历史开奖
</div>
<div class="lskjbox">
	<table class="tbl" id="haomalist">
	<tbody>
	<tr>
		<th>
			期号
		</th>
		<th>
			中奖号码
		</th>
	</tr>
	<?php foreach($history as $var) { ?>
	<tr>
		<td>
			<?php echo ($var['number']); ?>
		</td>
		<td>
			<?php echo ($var['data']); ?>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
                </div>
                <div>
                    <strong>第 <small id="last_issue"><?php echo ($lastNo['actionNo']); ?></small> 期</strong>开奖号码
                </div>
                <div>
                    <?php if($type==20){ ?>
                    <div id="showcodebox" class="gct_r_nub6" ctype="pk10">
                        <div class="gr_c gr_c<?php echo ($kjHao[0]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[1]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[2]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[3]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[4]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[5]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[6]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[7]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[8]); ?>" flag="normal"></div>
                        <div class="gr_c gr_c<?php echo ($kjHao[9]); ?>" flag="normal"></div>
                    </div>
                    <?php }else if($type==24){ ?>
                    <div id="showcodebox" class="gct_r_nub" ctype="k8">
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[0]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[1]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[2]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[3]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[4]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[5]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[6]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[7]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[8]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[9]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[10]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[11]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[12]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[13]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[14]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[15]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[16]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[17]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[18]); ?></div>
                        <div class="gr_s gr_s020" flag="normal"><?php echo ($kjHao[19]); ?></div>
                    </div>
                    <?php } else if($type==46){ ?>
                    <div id="showcodebox" class="gct_r_nub">
                        <div class="gr_s gr_s<?php echo ($kjHao[0]); ?>" flag="normal">
                        </div>
                    </div>
                    <?php } else if($type==10 || $type==9){ ?>
                    <div id="showcodebox" class="gct_r_nub">
                        <div class="gr_s gr_s<?php echo ($kjHao[0]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[1]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[2]); ?>" flag="normal">
                        </div>
                    </div>
                    <?php } else { ?>

                    <div id="showcodebox" class="gct_r_nub">
                        <div class="gr_s gr_s<?php echo ($kjHao[0]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[1]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[2]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[3]); ?>" flag="normal">
                        </div>
                        <div class="gr_s gr_s<?php echo ($kjHao[4]); ?>" flag="normal">
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div>
                    <?php if($type==6 || $type==15 || $type==16 || $type==38 || $type==39) { ?>
                    <!--<a href="<?php echo U('game/zoushitu_11x5?id='.$type.'&issuecount=30');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>-->
                    <a href="<?php echo U('trend/chart?type='.$type.'&issuecount=30&type_name='.$types[$type]['title'].'&mod=five');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>
                    <?php }elseif($type==24) { ?>
                    <a href="<?php echo U('game/zoushitu_kl8?id='.$type.'&issuecount=30');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>
                    <?php }elseif($type==20) { ?>
                    <a href="<?php echo U('game/zoushitu_pk10?id='.$type.'&issuecount=30');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>
                    <?php }elseif($type==46) { ?>
                    <a href="<?php echo U('trend/chart_yhlf?id='.$type.'&issuecount=30');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>
                    <?php } else { ?>
                    <a href="<?php echo U('trend/chart?type='.$type.'&issuecount=30&type_name='.$types[$type]['title'].'&mod=five');?>" class="hmzs" target="_blank"><img alt="" src="/Public/Home/images/game/zs.png">号码走势</a>
                    <?php } ?>
                    <a href="javascript:void(0)" class="jrkj"><img alt="" src="/Public/Home/images/game/ls.png">历史开奖</a>
                </div>
            </div>
        </div>
        <div class="game_rc">
            <form>
                <div class="gm_con">
                    <div class="gm_con_to">
                        <div class="tz_body">
                            <div class="unit">
                                <div class="unit_title">
                                    <div class="ut_l">
                                    </div>
                                    <div id="tabbar-div-s2" class="u_tab_div">
                                        <?php if($_COOKIE['mode']){ $mode=$_COOKIE['mode']; }else{ $mode=2.00; } $i=0; $groups2 = array(); foreach($groups as $group) { if($group['type']==$types[$type]['type']) { $groups2[$i] = $group; $i++; } } if($groups2) foreach($groups2 as $key=>$group){ ?>
                                        <?php if(($group['id'] != 67) AND ($group['id'] != 70)): ?><span data="<?php echo U('game/group?type='.$type.'&groupId='.$group['id']);?>" onclick="selectGroup(this)" class="<?php if($group['id']==$groupId) echo 'tab-front';else echo 'tab-back'; ?>">
                                                <span class="tabbar-left"></span><span class="content"><?php echo ($group['groupName']); ?></span>
                                                <span class="tabbar-right"></span>
                                            </span><?php endif; ?>
                                        <?php } ?>
                                    </div>
                                    <div class="ut_r">
                                    </div>
                                </div>
                                <span id="played-span">
                                <div id="tabCon">
	<div class="tabcon_n">									
		<ul id="tabbar-div-s3">
			<li class="tz_li">
				<span class="tz_title"></span>
				<?php $playeds2 = array(); $i=0; foreach($playeds as $play) { if($play['groupId']==$groupId && $play['enable']==1) { $playeds2[$i] = $play; $i++; } } if(!$playeds2) {echo '<div style="height:150px;margin-top:50px;text-align:center;color:#f00">暂无玩法</div>';return;} if(!$playedId) $playedId=$playeds2[0]['id']; if($playeds2) foreach($playeds2 as $played){ if($playedId==$played['id']){ $tpl=$played['playedTpl']; $current_played=$played; } if($played['enable']){ ?>
				<div class="<?php if($played['id']==$playedId) echo 'act';else echo 'back'; ?>" data="<?php echo U('game/played?type='.$type.'&playedId='.$played['id']);?>" data-id="<?php echo ($played['id']); ?>" onclick="selectPlayed(this)">
					<span class="method-tab-front" id="smalllabel_0_0"><?php echo ($played['name']); ?></span>
				</div>
				<?php }} ?>
			</li>										
		</ul>
	</div>
</div>
<span id="played-content">
	<div class="clear">
</div>
<div class="tbn_top">
	<h5 id="lt_desc"><?php echo ($current_played['simpleInfo']); ?></h5>
	<?php if($type != 20 ): ?><span id="lt_example" class="methodexample showexample"></span>
		<span id="lt_help" class="methodhelp showexample"></span><?php endif; ?>
	<div class="clear">
	</div>
</div>
<div class="game_eg hide" id="lt_examples_div">
	<div class="cont"><?=$current_played["example"]?></div>
</div>
<div class="game_eg hide" id="lt_helps_div">
	<div class="cont"><strong>说明：</strong><br /><?=$current_played["info"]?></div>
</div>
<div class="clear">
</div>
<div class="tbn_cen">
	<div class="tbn_c_ft">
	</div>
	<div class="tbn_c_s">
		<div id="lt_selector">
			<?php  require MODULE_PATH.'View/default/Game/game-played/'.$tpl.'.html'; ?>
			<div class="c">
			</div>
		</div>
		<div id="random_sel_button" class="random_sel_button">
		</div>
		<div class="clear">
		</div>
	</div>
	<div class="tbn_c_fb">
	</div>
</div>
</span>
                            </span>
                            </div>
                            <div class="clear">
                            </div>
                        </div>
                        <div class="clear">
                        </div>
                        <div class="tzn_body">
                            <div class="tzn_b_n">
                                <div class="tbn_b_top">
                                    <div class="tbn_bt_sel">
                                        共
                                        <strong>
                                    <span id="lt_sel_nums" class="n">0</span>
                                    </strong> 注, 共
                                        <strong>
                                    <span id="lt_sel_money" class="n">0.00</span>
                                    </strong> 元,
                                        <span id="reducetime" class="changetime" title="减少1倍">
                                        －
                                    </span>
                                        <input id="lt_sel_times" type="number" class="bei" name="lt_sel_times" size="4" type="TEXT" value="1">
                                        <span id="plustime" class="changetime" title="增加1倍">＋</span> 倍
                                        <select name="lt_sel_modes" id="lt_sel_modes">
                                            <?php if($settings['yuan_mode']) echo '<option value="2">2元</option>'; ?>
                                            <?php if($settings['jiao_mode']) echo '<option value="0.2">2角</option>'; ?>
                                            <?php if($settings['fen_mode']) echo '<option value="0.02">2分</option>'; ?>
                                            <?php if($settings['li_mode']) echo '<option value="0.002">2厘</option>'; ?>
                                        </select>
                                        <span id="lt_sel_prize">奖金:
                                    <select name="lt_sel_dyprize" id="lt_sel_dyprize" data-bet-count="<?=$settings['betMaxCount']?>" data-bet-zj-amount="<?=$settings['betMaxZjAmount']?>" max="<?=$_SESSION['user']['fanDian']?>" maxfd="<?=$settings['fanDianMax']?>" fan-dian="<?=$user['fanDian']?>" game-fan-dian-bdw="<?=$settings['fanDianBdwMax']?>" fan-dian-bdw="<?=$user['fanDianBdw']?>" min="0">
                                        <option selected="selected" value="1950|0">1950</option>
                                    </select>
                                    </span>
                                    </div>
<!--                                     <div id="lt_bet_immediate" class="g_submit waves-effect " style="background-color:#f37032">
                                        <span>一键投注</span>
                                    </div> -->
									<button id="lt_bet_immediate" type="button" class="btn btn-defaultss g_submit waves-effect" style="background-color:#f37032">一键投注</button>
									<button id="lt_sel_insert" type="button" class="btn btn-info g_submit waves-effect" onclick="gameActionAddCode(2)" style="margin-left:20px;">添加投注</button>
							
                                   <!--  <div id="lt_sel_insert" class="g_submit waves-effect " onclick="gameActionAddCode(2)">
                                        <span>添加</span>
                                    </div> -->
                                </div>
                                <div class="tbn_bot">
                                    <!-- <div class="newtab">
                                        <div class="wrap">
                                            <ul id="tab_t">
                                                <li class="act">我的方案</li>
                                                <li>我的追号</li>
                                            </ul>
                                            <div id="tab_c">
                                                <div id="order-history">
                                                    <table class="tbl" id="tbproject">
    <tbody>
        <tr>
            <th style="display:none;">
                编号
            </th>
            <th>
                投注时间
            </th>
            <th>
                期号
            </th>
            <th>
                彩种
            </th>
            <th>
                玩法
            </th>
            <th>
                模式
            </th>
            <th>
                倍数
            </th>
            <th>
                投注内容
            </th>
            <th>
                投注金额
            </th>
            <th>
                开奖号码
            </th>
            <th>
                中奖金额
            </th>
            <th>
                状态
            </th>
        </tr>
        <?php foreach($order_list as $var){ ?>
        <tr>
            <td style='display:none;'>
                <a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo ($var["wjorderId"]); ?></a>
            </td>
            <td>
                <a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo (date("H:i",$var["actionTime"])); ?></a>
            </td>
            <td><?php echo ($var["actionNo"]); ?></td>
            <td><?php echo ($var["typename"]); ?></td>
            <td><?php echo ($var["playedname"]); ?></td>
            <?php if($var['mode']==2) echo '<td>元</td>'; ?>
            <?php if($var['mode']==0.2) echo '<td>角</td>'; ?>
            <?php if($var['mode']==0.02) echo '<td>分</td>'; ?>
            <?php if($var['mode']==0.002) echo '<td>厘</td>'; ?>
            <td><?php echo ($var["beiShu"]); ?></td>
            <td style="max-width: 200px;overflow:hidden;text-overflow:ellipsis;white-space: nowrap; " title="<?php echo ($var["actionData"]); ?>"><?php echo ($var["actionData"]); ?></td>
            <td><?php echo ($var['beiShu']*$var['mode']*$var['actionNum']); ?>元</td>
            <td><?php echo ($var["lotteryNo"]); ?></td>
            <td><?php echo ($var["bonus"]); ?></td>
            <td>
                <?php if($var['isDelete']==1){ echo '
                    <label class="gray">已撤单</label>'; }elseif($var['lotteryNo'] ==='' ){ echo '
                    <label class="gray">未开奖</label>'; }elseif($var['zjCount']){ echo '
                    <label class="red">已中奖</label>'; }else{ echo '
                    <label class="gray">未中奖</label>'; } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

                                                </div>
                                                <div class="hide">
                                                    <table class="tbl" id="tbtrace">
                                                        <tbody>
                                                            <tr>
                                                                <th>
                                                                    编号
                                                                </th>
                                                                <th>
                                                                    未/已完期
                                                                </th>
                                                                <th>
                                                                    状态
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="tbn_b_bot">
                                        <div class="tbn_bb_l">
                                            <div class="tbn_bb_ln">
                                                <h4>

                                                    <div class="fl" style="height:100%">
                                                        <span id="lt_cf_help" class="icons_q1">&nbsp;&nbsp;&nbsp;</span>
                                                                                                            投注项:
                                                        <span id="lt_cf_count">0</span>
                                                    </div>
                                                    <div class="fr">
                                                        <input id="lt_cf_clear_2" class="tbn_clear" name="" value="清空全部" type="button"><span id="lt_cf_clear" style="cursor:pointer;" >全部清除</span>
                                                        <script>
                                                            $(function(){
                                                                $('#lt_cf_clear_2').bind('click', gameActionRemoveCode);
                                                            })
                                                        </script>
                                                    </div>

                                            </h4>
                                                <div class="tz_tab_list_box">
                                                    <table id="lt_cf_content" class="tz_tab_list" border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr class="nr">
                                                                <td class="tl_li_l" width="4">
                                                                </td>
                                                                <td class="noinfo" colspan="6">
                                                                    暂无投注项
                                                                </td>
                                                                <td class="tl_li_rn" width="4">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                                </div>
                                <table class="czinfotbl">
                                    <tbody>
                                        <tr>
                                            <td style="width: 300px;">
                                                总注数:
                                                <span id="lt_cf_nums" class="r">0</span> 注 总金额:
                                                <span id="lt_cf_money" class="r">0</span> 元
                                            </td>
                                            <td style="text-align:right;">
                                                <input id="lt_trace_if" autocomplete="off" name="lt_trace_if" value="yes" type="checkbox"><span id="lt_trace_if2">我要追号</span>
                                            </td>
                                            <td style="text-align:center;">
                                                <div id="lt_buy" class="g_submit waves-effect " style='margin-left:350px;'>
                                                    <span>马上投注</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="upload-file" style="display:none;">
                                    <input name="lottery-file" id="lottery-file" type="file">
                                </div>
                                <div class="newtab">
                                    <script language="javascript">
                                    $(function() {
                                        $('#tab_t li').click(function() {

                                            $(this).addClass("act").siblings().removeClass('act'); //removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除；
                                            $("#tab_c .tab-panel").eq($('#tab_t li').index(this)).show().siblings().hide();
                                        });
                                    })
                                    </script>
                                    <div class="wrap">
                                        <ul id="tab_t">
                                            <li class="act">
                                                我的方案</li>
                                            <li>我的追号</li>
                                        </ul>
                                        <div id="tab_c">
                                            <div id="order-history" class="tab-panel" style="display: block;">
                                                <table class="tbl" id="tbproject">
    <tbody>
        <tr>
            <th style="display:none;">
                编号
            </th>
            <th>
                投注时间
            </th>
            <th>
                期号
            </th>
            <th>
                彩种
            </th>
            <th>
                玩法
            </th>
            <th>
                模式
            </th>
            <th>
                倍数
            </th>
            <th>
                投注内容
            </th>
            <th>
                投注金额
            </th>
            <th>
                开奖号码
            </th>
            <th>
                中奖金额
            </th>
            <th>
                状态
            </th>
        </tr>
        <?php foreach($order_list as $var){ ?>
        <tr>
            <td style='display:none;'>
                <a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo ($var["wjorderId"]); ?></a>
            </td>
            <td>
                <a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo (date("H:i",$var["actionTime"])); ?></a>
            </td>
            <td><?php echo ($var["actionNo"]); ?></td>
            <td><?php echo ($var["typename"]); ?></td>
            <td><?php echo ($var["playedname"]); ?></td>
            <?php if($var['mode']==2) echo '<td>元</td>'; ?>
            <?php if($var['mode']==0.2) echo '<td>角</td>'; ?>
            <?php if($var['mode']==0.02) echo '<td>分</td>'; ?>
            <?php if($var['mode']==0.002) echo '<td>厘</td>'; ?>
            <td><?php echo ($var["beiShu"]); ?></td>
            <td style="max-width: 200px;overflow:hidden;text-overflow:ellipsis;white-space: nowrap; " title="<?php echo ($var["actionData"]); ?>"><?php echo ($var["actionData"]); ?></td>
            <td><?php echo ($var['beiShu']*$var['mode']*$var['actionNum']); ?>元</td>
            <td><?php echo ($var["lotteryNo"]); ?></td>
            <td><?php echo ($var["bonus"]); ?></td>
            <td>
                <?php if($var['isDelete']==1){ echo '
                    <label class="gray">已撤单</label>'; }elseif($var['lotteryNo'] ==='' ){ echo '
                    <label class="gray">未开奖</label>'; }elseif($var['zjCount']){ echo '
                    <label class="red">已中奖</label>'; }else{ echo '
                    <label class="gray">未中奖</label>'; } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

                                            </div>
                                            <div id='order-zhuihao' class="tab-panel" style="display: none;">
                                                <table class="tbl" id="tbproject">
	<tbody>
		<tr>
			<th style="display:none;">
				编号
			</th>
			<th>
				投注时间
			</th>
			<th>
				期号
			</th>

			<th>
				彩种
			</th>
			<th>
				玩法
			</th>
			<th>
				模式
			</th>
			<th>
				倍数
			</th>
			<th>
				投注内容
			</th>
			<th>
				投注金额
			</th>
			<th>
				开奖号码
			</th>
			<th>
				中奖金额
			</th>
			<th>
				状态
			</th>
		</tr>

		<?php foreach($order_list as $var){ ?>
		<tr>
			<td style='display:none;'>
				<a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo ($var["wjorderId"]); ?></a>
			</td>
			<td>

				<a href="javascript:" title="查看投注详情" class="blue" rel="projectinfo" action="<?php echo U('record/betInfo2?id='.$var['id']);?>" data-value="<?php echo ($var['id']); ?>"><?php echo (date("H:i",$var["actionTime"])); ?></a>
			</td>
			<td><?php echo ($var["actionNo"]); ?></td>
			<td><?php echo ($var["typename"]); ?></td>
			<td><?php echo ($var["playedname"]); ?></td>
			<?php if($var['mode']==2) echo '<td>元</td>'; ?>
			<?php if($var['mode']==0.2) echo '<td>角</td>'; ?>
			<?php if($var['mode']==0.02) echo '<td>分</td>'; ?>
			<?php if($var['mode']==0.002) echo '<td>厘</td>'; ?>
			<td><?php echo ($var["beiShu"]); ?></td>
			<td  style="max-width: 200px;overflow:hidden;text-overflow:ellipsis;white-space: nowrap; " title="<?php echo ($var["actionData"]); ?>"><?php echo ($var["actionData"]); ?></td>

			<td><?php echo ($var['beiShu']*$var['mode']*$var['actionNum']); ?>元</td>
			<td><?php echo ($var["lotteryNo"]); ?></td>
			<td><?php echo ($var["bonus"]); ?></td>
			<td>
				<?php if($var['isDelete']==1){ echo '<label class="gray">已撤单</label>'; }elseif(!$var['lotteryNo']){ echo '<label class="gray">未开奖</label>'; }elseif($var['zjCount']){ echo '<label class="red">已中奖</label>'; }else{ echo '<label class="red">未中奖</label>'; } ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="lt_trace_box" class="trace_box animated" style="display: none">
        <div class="tabcon_n animated zoomIn" id="zh">
            <div class="zhtitle">
                追号设置
            </div>
            <div class="nl_lt">
            </div>
            <div class="nl_rt">
            </div>
            <div class="nl_lb">
            </div>
            <div class="nl_rb">
            </div>
            <div class="unit1">
                <div class="unit_title2">
                    <div id="tab02" class="u_tab_div">
                        <div class="bd">
                            <div id="general_txt_100" class="bd2">
                                <table class="tabbar-div-s3" width="100%">
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="bl3p">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear">
                    </div>
                </div>
                <div class="clear">
                </div>
                <div class="zhgen">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <p>
                                        追号期数：
                                        <select id="lt_trace_qissueno" autocomplete="off">
                                            <option value="">全部</option>
                                            <option value="5">5期</option>
                                            <option value="10" selected="selected">10期</option>
                                            <option value="15">15期</option>
                                            <option value="20">20期</option>
                                            <option value="25">25期</option>
                                        </select> &nbsp;&nbsp;
                                        <span style="display:none">
                                <input name="lt_trace_stop" id="lt_trace_stop" value="yes" type="checkbox"><span>中奖后停止追号</span>
                                        </span>
                                    </p>
                                    <p>
                                        追号计划：
                                        <span id="lt_trace_labelhtml">
                            <span id="lt_margin_html">起始倍数&nbsp;<input type="number" autocomplete="off" name="lt_trace_times_margin" id="lt_trace_times_margin" value="1" size="3" type="text">&nbsp;&nbsp;</span><span id="lt_sametime_html" style="display:none;">起始倍数&nbsp;<input name="lt_trace_times_same" id="lt_trace_times_same" value="1" size="3" type="text"></span>
                                        <span id="lt_difftime_html" style="display:none;">隔&nbsp;<input name="lt_trace_diff" id="lt_trace_diff" value="1" size="3" type="text">&nbsp;期&nbsp;&nbsp;倍 × <input name="lt_trace_times_diff" id="lt_trace_times_diff" value="2" size="3" type="text"></span>&nbsp;&nbsp;追号期数&nbsp;
                                        <input type="number" autocomplete="off" name="lt_trace_count_input" id="lt_trace_count_input" style="width:36px" value="10" size="3" type="text">
                                        <input id="lt_trace_money" name="lt_trace_money" value="0" type="hidden">
                                        <input value="120" id="lt_trace_alcount" type="hidden">
                                        </span>
                                    </p>
                                </td>
                                <td rowspan="2" valign="bottom" align="right">
                                    <div id="lt_trace_ok" class="jihuabtn">
                                        <span>生成计划</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="lt_trace_issues" class="zhlist">
                    <table id="lt_trace_issues_table" width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr id="tr_trace_20160430-003" style="display: none">
                                <td class="r1">
                                    <input name="lt_trace_issues[]" value="20160430-003" type="checkbox">
                                </td>
                                <td>
                                    20160430-003
                                </td>
                                <td class="nosel">
                                    <input name="lt_trace_times_20160430-003" class="r2" value="0" disabled="disabled" type="text">倍
                                </td>
                                <td>
                                    ￥<span id="lt_trace_money_20160430-003">0.00</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="zhbottom">
                    <div id="qxzh" class="g_submit qxzh waves-effect ">
                        <span onclick="javascript: $('#lt_trace_box').css('display', 'none');">关闭</span>
                    </div>
                    <div id="lt_buy_trace" class="g_submit waves-effect ">
                        <span>马上投注</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="wanjinDialog"></div>
    <script type="text/javascript">
    $(function() {
        $("body").click(
            function toggle() {
                $("div#demo-set", window.top.document).removeClass("open");
                $(".mega-dropdown", window.top.document).removeClass("open");
                $(".dropdown", window.top.document).removeClass("open");
                $(".lskj").fadeOut(200);
            }
        );

        //开奖倒计时

        <?php
 if(!$kjHao){ ?>
        loadKjData();
        <?php } ?>
        setTimeout(gameKanJiangDataC, 1000, <?=$diffTime?>);
        //未来期
        futureNo('<?php echo ($actionNo['
            actionNo ']); ?>', '<?php echo ($type); ?>');
    });

    var game = {
            type: <?=json_encode($type)?>,
            played: <?=json_encode($played)?>,
            groupId: <?=json_encode($groupId)?>
        },
        user = "<?=$_SESSION['user']['username']?>",
        aflag = <?=json_encode($$_SESSION['user']['admin']==1)?>;
    </script>
</body>

</html>