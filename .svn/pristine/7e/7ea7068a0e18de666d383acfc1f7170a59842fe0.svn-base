<?php if (!defined('THINK_PATH')) exit();?><div id="tabCon">
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