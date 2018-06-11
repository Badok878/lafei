<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<META name="renderer" content="webkit">
<META charset="utf-8">
<META http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<META name="viewport" content="width=device-width, initial-scale=1.0">
<title>系统公告－<?php echo S('WEB_NAME');?></title>

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

	
<style>
        .form-inline {
            margin-bottom: 10px;
            margin-left: 10px;
        }
        ul.tongzhilist {
            height: 100%;
            overflow: auto;
            padding: 0;
            margin: 0;
        }
            ul.tongzhilist li {
                list-style: none;
                margin: 0;
                padding: 10px;
                border-bottom: 1px #eee solid;
                cursor: pointer;
            }
                ul.tongzhilist li strong {
                    display: block;
                    font-size: 16px;
                    font-weight: normal;
                    padding-bottom: 5px;
                    padding-left: 5px;
                }
                ul.tongzhilist li span {
                    margin-left: 30px;
                    color: #999;
                }
                ul.tongzhilist li p {
                    padding-left: 30px;
                    padding-right: 5px;
                }
        i.orangestar {
            color: orangered;
            margin-right: 10px;
        }
        i.graystar {
            color: #ccc;
            margin-right: 10px;
        }
        ul.tongzhilist li span i {
            margin-right: 5px;
        }
        ::-webkit-scrollbar-track {
            background-color: #eae0db;
        }
        ::-webkit-scrollbar {
            width: 12px;
            border-radius: 10px;
            background-color: #b7b0ad;
        }
        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background-color: #b7b0ad;
        }
        .tongzhileft {
            border-top: 1px #eee solid;
            border-bottom: 1px #eee solid;
            border-left: 1px #eee solid;
            padding: 0;
        }
        .tongzhiright {
            border-top: 1px #eee solid;
            border-right: 1px #eee solid;
            border-bottom: 1px #eee solid;
            padding-right: 0;
        }
        .tongzhiview {
            padding: 20px;
            padding-left: 10px;
            padding-top: 10px;
            overflow: auto;
            height: 100%;
        }
        .tongzhiviewtitle {
            font-size: 16px;
            padding-bottom: 10px;
            font-weight: bold;
            color: #666;
            text-align: center;
        }
        .tongzhiviewinfo {
            text-align: center;
            color: #999;
            padding-bottom: 10px;
        }
        .tongzhiviewtxt {
            padding-top: 20px;
            font-size: 21px;
            padding-left: 20px;
            padding-right: 20px;
        }
            .tongzhiviewtxt p {
                font-size: 21px;
            }
        li.tongzhilistthis {
            background-color: #fbf6f3;
            border-left: 3px #ed6c44 solid;
            box-sizing: border-box;
        }
        label.wd {
            float: right;
            background-color: #ffb230;
            color: #fff;
            font-size: 11px;
            padding-left: 2px;
            padding-right: 2px;
            border-radius: 2px;
            font-family: 宋体;
        }
        label.yd {
            float: right;
            background-color: #63befa;
            color: #fff;
            font-size: 11px;
            padding-left: 2px;
            padding-right: 2px;
            border-radius: 2px;
            font-family: 宋体;
        }
</style>

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
	




	
<script src="/Public/Home/js/dataTables.min.js"></script>
<script src="/Public/Home/js/dataTables.bootstrap.min.js"></script>
<script src="/Public/Home/js/dataTables.responsive.min.js"></script>
<script src="/Public/Home/js/bootbox.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		var bodyheight = jQuery(window).height() - 100;
		$(".tongzhileft,.tongzhiright").css("height", bodyheight);
		$("ul.tongzhilist li").click(
		  function toggle() {
			  $("ul.tongzhilist li").removeClass("tongzhilistthis");
			  $(this).addClass("tongzhilistthis");
			  //document.location.href = $(this).attr('href');
			  $('.tongzhiright').load($(this).attr('href'));
		  }
		);	
	});
	$(window).resize(function () {
		var bodyheight = jQuery(window).height() - 100;
		$(".tongzhileft,.tongzhiright").css("height", bodyheight);
	})

</script>

	<!-- 主体 -->
	
<div class="effect mainnav-lg mainnav-fixed navbar-fixed footer-fixed" id="container">
	<div id="page-content">
		<!--<form id="notice" role="form" action="/notice/notice" method="post">-->
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title"><img alt="" src="/Public/Home/images/main/titleiconf.jpg">通知公告</h3>
				</div>
				<div class="panel-body" style="padding: 0px 8px 3px 7px;">
					<div class="tongzhi row">
						<div class="tongzhiright col-sm-12">
							<div class="tongzhiview">
								<div class="tongzhiviewtitle">
									<?php echo ($info['title']); ?>
								</div>
								<div class="tongzhiviewinfo">
									<span>发布时间：<?php echo date('Y/m/d H:i:s',$info['addTime']);?></span>
								</div>
								<div class="tongzhiviewtxt">
									<?php echo nl2br($info['content']);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input name="__RequestVerificationToken" type="hidden" value="CfDJ8Ktgxm2FAlNEsoI7VoMQlWlAMlHOQpT2zoNzZZCIO-A_P9U8P7nISaHtKqdRIQzDjdE3crtwjeggFou41nsnFLUu7beumKW4Zz5XrxY7s0Q-BSXFqMF3RtYcNRzwuai135NAPq1B09rJIVxXW_0IkGLVDW9uBV5q0fYezLaY2AgZLBB6oCzVjgxQTw8-_42bfw">
		<!--</form>-->
	</div>
</div>


	<!-- /主体 -->

	<!-- 底部 -->
	


	<!-- /底部 -->
</body>

</html>