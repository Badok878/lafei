var mysql=require('mysql');
var parse=require('./kj-data/parse-calc-count.js');
var played={};

// 彩票开奖配置
exports.cp=[
	 
	{
		title:'秒秒彩',
		source:'官网0',
		name:'mmc',
		enable:true,
		timer:'mmc', 
		option:{//时间执行配置
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info0', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		actionoption:{//任务执行配置
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info0', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		parse:function(str){
			// console.log('开始parse解析'+str)
			console.log('----------------------开始parse解析-------------------------------------------')
			try{
				return getFromWANJINYULE(str,36);//36 为秒秒彩的id编号  这个过程"一个期"只能被执行一次,如果重复,派奖也重复
			}catch(err){
				throw('秒秒彩解析数据不正确');
			}
		}
	},
	
   {
		title:'分分彩',
		source:'官网1',
		name:'ffc',
		enable:true,
		timer:'ffc', 
		option:{
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info1', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		actionoption:{//任务执行配置
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info1', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				 "user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},		
		
		parse:function(str){
			try{
				return getFromWANJINYULE(str,5);
			}catch(err){
				throw('分分彩解析数据不正确');
			}
		}
	},////////////
	
	{
		title:'二分彩',
		source:'官网',
		name:'efssc',
		enable:true,
		timer:'efssc', 
		option:{
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info2', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		actionoption:{//任务执行配置
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info2', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				 "user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},				
		
		parse:function(str){
			try{
				return getFromWANJINYULE(str,34);
			}catch(err){
				throw('二分彩解析数据不正确');
			}
		}
	},////////////		
	
	{
		title:'五分彩',
		source:'官网',
		name:'qtllc',
		enable:true,
		timer:'qtllc', 
		option:{
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info5', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		actionoption:{//任务执行配置
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/info5', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				 "user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},			
		parse:function(str){
			try{
				return getFromWANJINYULE(str,14);
			}catch(err){
				throw('5分彩解析数据不正确');
			}
		}
	},////////////
 	
	{
		title:'韩国1.5分彩(接口)',
		source:'接口',
		name:'hgsscjk',
		enable:true,
		timer:'hgsscjk',

		option:{
			hostname:'yhyl678.com',
			port: 80,
			timeout:10000,
			method: 'GET',
			path: '/index.php?s=/home/wufencai/infohgc', //'/time.json'//  // Info0 定义在D:\webroot\lafei\Application\Home\Controller\WufencaiController.class.php 
			headers:{
				"user-agent": "mozilla/5.0 (windows; u; windows nt 5.2) applewebkit/525.13 (khtml, like gecko) chrome/0.2.149.27 safari/525.13"
			 }
		},
		actionoption:{//任务执行配置
			host:"f.apiplus.cn",
			timeout:50000,
			path: '/krkeno.xml',
			headers:{
				"User-Agent": "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)"
			 }
		},					

		parse:function(str){
			try {
				var exp = /<row expect="(\d{7})" opencode="((\d{1,2},){19}\d{1,2})" opentime="([a-z0-9\-\: ]+)"\/>/;
				var match = str.match(exp);
				var units = match[2].split(',');
				var index = -1;
				var data = '';
				for (var a=0;a<5;a++) {
					var num = 0;
					for (var b=0;b<4;b++) {
						index += 1;
						num += parseInt(units[index]);
					}
					num = num.toString().substr(-1);
					data += num + ',';
				}
				data = data.substr(0, data.length - 1);
				var time = new Date();
				time.setTime((new Date(match[3])).getTime());
				return {
					type: 35,
					time: time,
					number: match[1],
					data: data,
				};
			} catch(e) {
				throw('韩国1.5分彩数据解析错误');
			}
		}
	}
	
];

// 出错时等待 15
exports.errorSleepTime=3; //等待5秒

// 时间监听执行间隔 
exports.runEveryTime=10; //等待5秒

// 重启时间间隔，以小时为单位，0为不重启
//exports.restartTime=0.4;
//exports.restartTime={0};
exports.restartTime = {
	0: 300, //采集互联网进程5分钟重启一次
	1: 60, //采集本机进程1分钟重启一次
};

exports.submit={
	host:'192.168.21.10',
	path:'/wjadmin.php/dataSource/kj'
}

exports.dbinfo={
	host:'192.168.21.10',
	user:'root',
	password:'lafeiroot',
	database:'lafei1'

}

global.log=function(log){
	var date=new Date();
	console.log('['+date.toDateString() +' '+ date.toLocaleTimeString()+'] '+log)
}
function getFromXJFLCPWeb(str, type){
	str=str.substr(str.indexOf('<strong>时时彩</strong>'), 300).replace(/[\r\n]+/g,''); 
	
	if(!str) throw new Error('数据不正确');
	var number = str.substr(str.indexOf('第')+1,10);
	
	var myDate = new Date();
	var year = myDate.getFullYear();       //年   
    var month = myDate.getMonth() + 1;     //月   
    var day = myDate.getDate();            //日
	if(month < 10) month="0"+month;
	if(day < 10) day="0"+day;
	var mytime=year + "-" + month + "-" + day + " " +myDate.toLocaleTimeString();
	
	str=str.substr(str.indexOf('ballWrap')+10, 200).replace(/[^0-9]/ig,"");
	//console.log(str);    
	var data= str.split('').join(',');
	//console.log('期号：%s，开奖时间：%s，开奖数据：%s', number, mytime, data);
	
	try{
		var data={
			type:type,
			time:mytime,
			number:number.replace(/^(\d{8})(\d{2})$/, '$1-$2'),
			data:data
		};
		//console.log(data);
		return data;
	}catch(err){
		throw('解析数据失败');
	}
}


function getFromCaileleWeb(str, type, slen){
	if(!slen) slen=580;
	str=str.substr(str.indexOf('<p class="cz_name_period">')+26,slen);
	var mynumber = str.substr(0,10);
	mynumber = mynumber.substr(0,8) + "-0" + mynumber.substr(8);
	var mytime = str.substr(str.indexOf('<span>')+6,16);
	
	var text = str.substr(str.indexOf('red_ball')+10,200);
	text = text.replace(/[^0-9]/ig,""); 
	//console.log(text);
	
	//var mynumber = str
	var reg=/<td.*?>(\d+)<\/td>[\s\S]*?<td.*?>([\d\- \:]+)<\/td>[\s\S]*?<td.*?>((?:[\s\S]*?<span class="red_ball">\d+<\/span>){3,5})\s*<\/td>/,
	match=str.match(reg);
	
	
		
	try{
		var data={
			type:type,
			time:mytime,
			number:mynumber,
			data:text.substr(0,2) + ","+text.substr(2,2) + ","+text.substr(4,2) + ","+text.substr(6,2) + ","+text.substr(8,2)
		}
		
		
		
		//console.log(data);
		return data;
	}catch(err){
		throw('解析数据失败');
	}
}

function getFrom360CP(str, type){

	str=str.substr(str.indexOf('<em class="red" id="open_issue">'),380);
	//console.log(str);
	var reg=/[\s\S]*?(\d+)<\/em>[\s\S].*?<ul id="open_code_list">((?:[\s\S]*?<li class=".*?">\d+<\/li>){3,5})[\s\S]*?<\/ul>/,
	match=str.match(reg);
	var myDate = new Date();
	var year = myDate.getFullYear();       //年   
    var month = myDate.getMonth() + 1;     //月   
    var day = myDate.getDate();            //日
	if(month < 10) month="0"+month;
	if(day < 10) day="0"+day;
	var mytime=year + "-" + month + "-" + day + " " +myDate.toLocaleTimeString();
	//console.log(match);
	if(match.length>1){
		if(match[1].length==6) match[1]=year+match[1].replace(/(\d{4})(\d{2})/,'$1-0$2');
		if(match[1].length==7) match[1]=year+match[1].replace(/(\d{4})(\d{3})/,'$1-$2');
		if(match[1].length==8) match[1]='20'+match[1].replace(/(\d{6})(\d{2})/,'$1-0$2');
		if(match[1].length==9) match[1]='20'+match[1].replace(/(\d{6})(\d{2})/,'$1-$2');
		if(match[1].length==10) match[1]=match[1].replace(/(\d{8})(\d{2})/,'$1-0$2');
		var mynumber=match[1].replace(/(\d{8})(\d{3})/,'$1-$2');
		
		try{
			var data={
				type:type,
				time:mytime,
				number:mynumber
			}
			
			reg=/<li class=".*?">(\d+)<\/li>/g;
			data.data=match[2].match(reg).map(function(v){
				var reg=/<li class=".*?">(\d+)<\/li>/;
				return v.match(reg)[1];
			}).join(',');
			
			//console.log(data);
			return data;
		}catch(err){
			throw('解析数据失败');
		}
	}
}

function getFromPK10(str, type){

	str=str.substr(str.indexOf('<div class="lott_cont">'),350).replace(/[\r\n]+/g,'');
    //console.log(str);
	var reg=/<tr class=".*?">[\s\S]*?<td>(\d+)<\/td>[\s\S]*?<td>(.*)<\/td>[\s\S]*?<td>([\d\:\- ]+?)<\/td>[\s\S]*?<\/tr>/,
	match=str.match(reg);
	if(!match) throw new Error('数据不正确');
	//console.log(match);
	try{
		var data={
			type:type,
			time:match[3],
			number:match[1],
			data:match[2]
		};
		//console.log(data);
		return data;
	}catch(err){
		throw('解析数据失败');
	}
	
}

function getFromK8(str, type){

	str=str.substr(str.indexOf('<div class="lott_cont">'),450).replace(/[\r\n]+/g,'');
    //console.log(str);
	var reg=/<tr class=".*?">[\s\S]*?<td>(\d+)<\/td>[\s\S]*?<td>(.*)<\/td>[\s\S]*?<td>(.*)<\/td>[\s\S]*?<td>([\d\:\- ]+?)<\/td>[\s\S]*?<\/tr>/,
	match=str.match(reg);
	if(!match) throw new Error('数据不正确');
	//console.log(match);
	try{
		var data={
			type:type,
			time:match[4],
			number:match[1],
			data:match[2]+'|'+match[3]
		};
		//console.log(data);
		return data;
	}catch(err){
		throw('解析数据失败');
	}
	
}

function getFrom360CPK3(str, type){

	str=str.substr(str.indexOf('<em class="red" id="open_issue">'),380);
	//console.log(str);
	var reg=/[\s\S]*?(\d+)<\/em>[\s\S].*?<ul id="open_code_list">((?:[\s\S]*?<li class=".*?">\d+<\/li>){3,5})[\s\S]*?<\/ul>/,
	match=str.match(reg);
	var myDate = new Date();
	var year = myDate.getFullYear();       //年   
    var month = myDate.getMonth() + 1;     //月   
    var day = myDate.getDate();            //日
	if(month < 10) month="0"+month;
	if(day < 10) day="0"+day;
	var mytime=year + "-" + month + "-" + day + " " +myDate.toLocaleTimeString();
	//console.log(match);
	match[1]=match[1].replace(/(\d{4})(\d{2})/,'$1-0$2');
		
		try{
			var data={
				type:type,
				time:mytime,
				number:year+match[1]
			}
			
			reg=/<li class=".*?">(\d+)<\/li>/g;
			data.data=match[2].match(reg).map(function(v){
				var reg=/<li class=".*?">(\d+)<\/li>/;
				return v.match(reg)[1];
			}).join(',');
			
			//console.log(data);
			return data;
		}catch(err){
			throw('解析数据失败');
		}
}

function getFromCJCPWeb(str, type){

	//console.log(str);
	str=str.substr(str.indexOf('<table class="qgkj_table">'),1200);
	
	//console.log(str);
	
	var reg=/<tr>[\s\S]*?<td class=".*">(\d+).*?<\/td>[\s\S]*?<td class=".*">([\d\- \:]+)<\/td>[\s\S]*?<td class=".*">((?:[\s\S]*?<input type="button" value="\d+" class=".*?" \/>){3,5})[\s\S]*?<\/td>/,
	match=str.match(reg);
	
	//console.log(match);
	
	if(!match) throw new Error('数据不正确');
	try{
		var data={
			type:type,
			time:match[2],
			number:match[1].replace(/(\d{8})(\d{2})/,'$1-0$2')
		}
		
		reg=/<input type="button" value="(\d+)" class=".*?" \/>/g;
		data.data=match[3].match(reg).map(function(v){
			var reg=/<input type="button" value="(\d+)" class=".*?" \/>/;
			return v.match(reg)[1];
		}).join(',');
		
		//console.log(data);
		return data;
	}catch(err){
		throw('解析数据失败');
	}
	
}

var wfFullData;
var efFullData;
var wfGaiLv=100;
var wfNo='0';
var efNo='0';
//自主彩开奖
function getFromWANJINYULE(str, type){
	console.log(type+'开始自主开奖')
	try{
		var client=mysql.createClient(exports.dbinfo);
	}catch(err){
		throw('连接数据库失败');
	}			

	var jd = JSON.parse(str);
	
	var match = [];
	match[1] = jd.actionNo;
	match[2] = jd.wufencai;
	match[3] = jd.actionTime;
	match[4] = 1;

	wfGaiLv=match[2];

	console.log("读取盈亏率成功："+wfGaiLv);
	if( wfNo!=match[1] && efNo!=match[1]){
		sql="select * from gygy_bets where type=? and actionNo=? and isDelete=0 and lotteryNo=''";
		client.query(sql, [type, match[1]], function(err, bets){
			if(err){
				log("读取投注出错："+err);
			}else{					
				var myDate = new Date();
				var year = myDate.getFullYear();       //年   
				var month = myDate.getMonth() + 1;     //月   
				var day = myDate.getDate();            //日
				if(month < 10) month="0"+month;
				if(day < 10) day="0"+day;
				var mytime=year + "-" + month + "-" + day + " " +myDate.toLocaleTimeString(); 
				var mydata=GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9);
				var yingLv = GetRandomNum(0,100);//获取从0-100 随机数,做为开奖方式的概率
				
				//控制盈亏													
				for(var go=0;go<1000000;go++){
					if(yingLv>wfGaiLv){//如果开奖概率大于{wfGaiLv=后台设置的开奖概率},则随机开号,小于的话{平台必须赢}
						go=1000000; //结束for
						console.log("概率:"+yingLv +" > "+wfGaiLv+" ，随机开号");
					}else{
						console.log("概率:"+yingLv +" <= "+wfGaiLv+" ，必赢开号"); 
						go=go+1;							
						var all=0;
						var win=0;
						bets.forEach(function(bet){//forEach枚举计算每一笔投注返奖金额
							var fun;
									
							try{
								fun=parse[global.played[bet.playedId]];
								if(typeof fun!='function') throw new Error('算法不是可用的函数');
							}catch(err){
								log('计算玩法[%f]中奖号码算法不可用：%s'.format(bet.playedId, err.message));
								return;
							}
									
							try{
								var zjCount=fun(bet.actionData, mydata, bet.weiShu)||0;//计算中奖金额
							}catch(err){
								log('计算中奖号码时出错：'+err);
								return;
							}
							win+=bet.bonusProp * zjCount * bet.beiShu * bet.mode/2;//返奖总额  bet.mode=2分   bet.mode/2=1分
							all+=bet.mode*bet.beiShu*bet.actionNum;                //投注总额  bet.mode(模式)*bet.beiShu(倍数)*bet.actionNum(注数)=投注总额
						});
						console.log("投注总额："+all + "   返奖总额：" +win);
						if(all>win || bets.length==0){
							go=1000000;						//投注总额>返奖总额,则结束for				
						}
						else//投注总额<返奖总额,则重新生成"开奖号码"
							mydata=GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9)+','+GetRandomNum(0,9);
					}
					
					if(!match) throw new Error('数据不正确');
					if(parseInt(match[4])==1){
						try{
								var data={
									type:type,
									time:mytime,
									number:match[1],
									data:mydata
								}
								
								if(type==14)
								{
									wfFullData=data;
									wfNo=match[1];
								}
								else
								{
									efFullData=data;
									efNo=match[1];
								}
							}
							catch(err){
								throw('解析数据失败');
						}
				   }
				}		
			} 				
		});
	}
	
	client.end();
	if(type==14)
	{
		return wfFullData;
	}
	else
	{
		return efFullData;
	}
	
}

//随机数 GetRandomNum(0,9);   
function GetRandomNum(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
}    
