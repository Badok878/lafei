// var cluster = require('cluster');
// if (cluster.isMaster) {
	// cluster.fork({coType: 0}); //互联网采集进程
	// cluster.fork({coType: 1}); //本机采集进程
	// cluster.on('exit', function(worker, code, signal) {
		// console.log('----------------[' + (code == 0 ? '互联网采集进程' : '本机采集进程') + ']重启生效----------------');
		// cluster.fork({coType: code});
	// });
// } else {
var played={}, mysql=require('mysql'),
http=require('http'),
url=require('url'),
crypto=require('crypto'),
querystring=require('querystring'),
config=require('./config.js'),
exec=require('child_process').exec,
execPath=process.argv.join(" "),
parse=require('./kj-data/parse-calc-count.js');
global.played={};
require('./String-ext.js');

// 抛出未知出错时处理
process.on('uncaughtException', function(e){
	console.log(e.stack);
});

// 自动重启
	if(config.restartTime[process.env.coType]){
		setTimeout(function() {
			exit();
		}, config.restartTime[process.env.coType] * 1000);
	}

var timers={};		// 任务记时器列表
var encrypt_key='cc40bfe6d972ce96fe3a47d0f7342cb0';


//显示配置文件
//console.log(config);
//读取配置文件并启动委托
getPlayedFun(runTask);


function getPlayedFun(cb){
	//cb 在config.js中定义,秒秒彩\分分彩
	try{
		var client=createMySQLClient();
	}catch(err){
		log(err);
		return;
	}
	
	client.query("select id, ruleFun from gygy_played", function(err, data){
		if(err){
			log('读取玩法配置出错：'+err.message);
		}else{
			data.forEach(function(v){
				played[v.id]=v.ruleFun;
				global.played[v.id]=v.ruleFun;
			});
			
			if(cb) cb();//cb此时为执行委托方法runTask()
		}
	});
	//关闭数据库
	client.end();
}



//debugger  //执行命令：node debug example.js 就可以进入调试模式。
function runTask(){//运行任务
	console.log('runTask注册开始')
	if(config.cp.length) 

	config.cp.forEach(function(conf){//循环从config.js中读取"彩种" {config.cp.foreach}
		console.log('runtask');
		timers[conf.name]={}; // timers任务记时器列表
		timers[conf.name][conf.timer]={timer:null, option:conf}; //在config中 timer:'mmc'
		try{
			console.log(timers)
			console.log(conf)
			if(conf.enable) //run(conf); //调用run函数执行
			//设置run根据配置文件秒数执行
			setInterval(run, config.runEveryTime*1000, conf);
		}catch(err){
			//timers[conf.name][conf.timer].timer=settimeout(run, config.errorsleeptime*1000, conf);
			restartTask(conf, config.errorsleeptime);
		}
	});	
	console.log('timers容器：'+timers);
	console.log('runTask注册完毕')
}
//重启任务，flag为true时重启timers[conf.name]下负载的所有timers[opt.name][opt.timer]的timer
function restartTask(conf, sleep, flag){
	
	if(sleep==undefined||sleep<=0) sleep=config.errorSleepTime;
	
	if(!timers[conf.name]) timers[conf.name]={}; // timers[]任务记时器列表中不存在的话，初始化一个timers[conf.name]
	if(!timers[conf.name][conf.timer]) timers[conf.name][conf.timer]={timer:null,option:conf};//timers[conf.name][conf.timer]不存在则初始化一个任务列表
	
	if(flag){
		var opt;
		for(var t in timers[conf.name]){
			opt=timers[conf.name][t].option;
			clearTimeout(timers[opt.name][opt.timer].timer);
			timers[opt.name][opt.timer].timer=setTimeout(run, sleep*1000, opt);
			log('休眠'+sleep+'秒后从'+opt.source+'采集'+opt.title+'数据...');
		}
	}else{
		clearTimeout(timers[conf.name][conf.timer].timer);// timers[]任务记时器列表
		timers[conf.name][conf.timer].timer=setTimeout(run, sleep*1000, conf);
		log('休眠'+sleep+'秒后从'+conf.source+'采集'+conf.title+'数据...');
	}
}
//时间比较函数
  function IsDoAction(date){
        var oDate = new Date(date);
        var oCurrDate = new Date();
		 
		var timeSpan=(oCurrDate.getTime()-oDate.getTime())/1000; //相差秒数
		//console.log('时间差：'+timeSpan)
         if(timeSpan > 0){
          //  console.log('传入时间已到');
			return true;
        } else {
			
           // console.log('传入时间未到');
			return false;
        }
         
    }
function run(conf){//运行获取彩种信息,然后提交开奖号码到数据库
	log(conf)
	if(timers[conf.name][conf.timer].timer)  clearTimeout(timers[conf.name][conf.timer].timer); // timers[]任务记时器列表

	log('开始从'+conf.source+'采集'+conf.title+'数据');
	var option=JSON.stringify(conf.option);
	log(option)
	var sReq = http.request(conf.option, function (sRes) {
    sRes.setEncoding('utf8');
    var appResult = "";
    sRes.on('data', function (body) {
    console.log('成功')
	var result=JSON.parse(body)
	console.log(result)
	//返回结果时间对象
	console.log(result.actionTime)
	if(IsDoAction(result.actionTime))
	{
		//时间比较成功
		console.log('时间到')
		
		
			try{
				 var dreq = http.request(conf.actionoption, function (dres) {
				dres.setEncoding('utf8');
				var appresult = "";
				 dres.on('data', function (chunk) {
				 console.log('任务执行成功返回')
				 // console.log(chunk);
				 // console.log('-------------------------');
				 // console.log(conf);
		         var data="";
				 
				 	try{
							try{
								console.log('解析:'+chunk)
								
								data=conf.parse(chunk);
						
							}catch(err){
								console.log('解析'+conf.title+'数据出错：'+err)
								throw('解析'+conf.title+'数据出错：'+err);
								
							}
				
							

							try{
								console.log('submitData:'+data)
							submitData(data, conf);//将开奖号码写入数据库中
							}catch(err){
								console.log(err);
								throw('提交出错：'+err);
							}
				
				}catch(err){
					log('运行出错3：%s，休眠%f秒'.format(err, config.errorSleepTime));
					restartTask(conf, config.errorSleepTime);
				}
			
			
			
				});
				dres.on('end', function () {
				  //io.emit('app data receiving', json.parse(appresult));

				});
			  });

			  dreq.on('error', function (e) {
				console.log('problem with request: ' + e.message);
			  });
			  dreq.end();
			}
			catch(err){
				console.log('处理正文失败')
			}
			 
				// try{
				// try{
					// //data=onparse[conf.name](data);
					// //data=conf.parse(data);
				// }catch(err){
					// //throw('解析'+conf.title+'数据出错：'+err);
				// }
				
				// //console.log(data);

				// try{
					// //submitdata(data, conf);//将开奖号码写入数据库中
				// }catch(err){
					// //console.log(err);
					// //throw('提交出错：'+err);
				// }
				
			// }catch(err){
				// //log('运行出错3：%s，休眠%f秒'.format(err, config.errorsleeptime));
				// //restarttask(conf, config.errorsleeptime);
			// }
	}else{
		console.log('时间未到')
		restartTask(conf, config.errorSleepTime);
	}

	});


    sRes.on('end', function () {
      //io.emit('app data receiving', JSON.parse(appResult));

    });

	});
	sReq.on('error', function (e) {
			console.log('run中http--res出现err')
			log(err);
			//restarttask(conf, config.errorsleeptime);

	
    });
	sReq.on('timeout', function(err){
		console.log('run中http请求timeout')
		log('从'+conf.source+'采集'+conf.title+'数据超时');
	    //restarttask(conf, config.errorsleeptime);
	});
	
	sReq.on("error", function(err){
		// 一般网络出问题会引起这个错
		console.log('run中http请求error')
		log(err);
		//restarttask(conf, config.errorsleeptime);
		
	});
	
  	sReq.end();

   //JSON.parse用于从一个字符串中解析出json对象
		//option.path+='?'+(new Date()).getTime();
	    //option 是一个对象
	// http.request(conf.option, function(error, response, body){// function(res)回调函数,
	  // if (!error && response.statusCode == 200) {
		  // console.log('成功')
      
      // }else{
	   // console.log('异常')
   // }
		// var data="";
		// res.on("data", function(_data){
			// console.log('request请求参数为')
			// console.log(_data.tostring());
			// data+=_data.tostring();
		// });
		// res.on("end", function(){
			// console.log('请求结果为'+res)
			// try{
				// try{
					// //data=onparse[conf.name](data);
					// //data=conf.parse(data);
				// }catch(err){
					// //throw('解析'+conf.title+'数据出错：'+err);
				// }
				
				// //console.log(data);

				// try{
					// //submitdata(data, conf);//将开奖号码写入数据库中
				// }catch(err){
					// //console.log(err);
					// //throw('提交出错：'+err);
				// }
				
			// }catch(err){
				// //log('运行出错3：%s，休眠%f秒'.format(err, config.errorsleeptime));
				// //restarttask(conf, config.errorsleeptime);
			// }
			
		// });
		
		// res.on("error", function(err){
		// consolo.log('run中http--res出现err')
			// log(err);
			// //restarttask(conf, config.errorsleeptime);

		// });
		
	// })
	// .on('timeout', function(err){
		// console.log('run中http请求timeout')
		// log('从'+conf.source+'采集'+conf.title+'数据超时');
		// // restarttask(conf, config.errorsleeptime);
	// }).on("error", function(err){
		// // 一般网络出问题会引起这个错
		// console.log('run中http请求error')
		// log(err);
		// //restarttask(conf, config.errorsleeptime);
		
	// }).end();
	
}


//向数据库中写入开奖号码
function submitData(data, conf){
	log('+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
	log('提交从'+conf.source+'采集的'+conf.title+' 第'+data.number+' 数据：'+data.data);
	try{
		var client=mysql.createClient(config.dbinfo);
	}catch(err){
		throw('连接数据库失败');
	}
	data.time=Math.floor((new Date(data.time)).getTime()/1000);
	client.query("insert into gygy_data(type, time, number, data) values(?,?,?,?)", [data.type, data.time, data.number, data.data], function(err, result){
		if(err){
			// 普通出错
			if(err.number==1062){//重复插入
				calcJ(data, true);//计算返奖
				// 数据已经存在
				// 正常休眠
				try{
					sleep=calc[conf.name](data);
					if(sleep<0) sleep=config.errorSleepTime*1000;
				}catch(err){
					restartTask(conf, config.errorSleepTime);
					return;
				}
				log(conf['title']+'第'+data.number+'期数据已经存在数据');
				//重启全部所有任务
				restartTask(conf, sleep/1000, true);
			}else{
				log('运行出错1：'+err.message);
				restartTask(conf, config.errorSleepTime);
			}
		}else if(result){
			setTimeout(calcJ, 500, data);
			// 正常
			try{
				//sleep=calc[conf.name](data);
				sleep = 15000;
			}catch(err){
				log('解析下期数据出错：'+err);
				restartTask(conf, config.errorSleepTime);
				return;
			}
			log('写入'+conf['title']+'第'+data.number+'期数据成功');
			restartTask(conf, sleep/1000, true);
		}else{
			global.log('未知运行出错');
			restartTask(conf, config.errorSleepTime);
		}
	});
	client.end();
}


function requestKj(type,number){
	var option={
		host:config.submit.host,
		path:'%s/%s/%s/%'.format(config.submit.path, type, number)
	}
	
	http.get(config.submit,function(res){
	
	});
}

//创建数据库客户端
function createMySQLClient(){
	try{
		return mysql.createClient(config.dbinfo).on('error', function(err){
			//console.log(err);
			throw('连接数据库失败');
		});
	}catch(err){
		log('连接数据库失败：'+err);
		return false;
	}
}
//方法
function calcJ(data, flag){
	var client=createMySQLClient();
	sql="select * from gygy_bets where type=? and actionNo=? and isDelete=0";
	if(flag) sql+=" and lotteryNo=''";
	
	client.query(sql, [data.type, data.number], function(err, bets){
		if(err){
			//console.log(data);
			//console.log(err.sql);
			console.log("读取投注出错："+err);
		}else{
			var sql, sqls=[];
			sql='call kanJiang(?, ?, ?, ?)';
			//console.log(bets);
			bets.forEach(function(bet){
				var fun;
				
				try{
					fun=parse[played[bet.playedId]];
					console.log(played[bet.playedId]);
					if(typeof fun!='function') throw new Error('算法不是可用的函数');
				}catch(err){
					log('计算玩法[%f]中奖号码算法不可用：%s'.format(bet.playedId, err.message));
					return;
				}
				
				try{
					
					var zjCount=fun(bet.actionData, data.data, bet.weiShu)||0;
					console.log('zjCount:' + zjCount);
				}catch(err){
					log('计算中奖号码时出错：'+err);
					return;
				}
				
				sqls.push(client.format(sql, [bet.id, zjCount, data.data, 'ssc-'+encrypt_key]));

			});
			
			try{
				setPj(sqls, data);
			}catch(err){
				log(err);
			}
		}
	});

	client.end();
}
//方法
function setPj(sqls, data){
	if(sqls.length==0) throw('彩种[%f]第%s期没有投注'.format(data.type, data.number));
	console.log(sqls);
	var client=createMySQLClient();
	if(client==false){
		log('连接数据库出错，休眠%f秒继续...'.format(config.errorSleepTime));
		setTimeout(setPj, config.errorSleepTime*1000, sqls, data);
	}else{
		log('派奖函数');
		client.query(sqls.join(';'), function(err,result){
			
			if(err){
				console.log(err);
			}else{
				log('成功');
			}
		});
		
		client.end();
	}
	
}

// 前台添加数据接口
http.createServer(function(req, res){
	
	log('前台访问'+req.url);
	var data='';
	//res.writeHead(200, {"Content-Type": "text/plain"});
	//res.write('9999');
	//res.end();
	
	req.on('data', function(_data){
		data+=_data;
	}).on('end', function(){
		data=querystring.parse(data);
		var msg={},
			hash=crypto.createHash('md5');
		hash.update(data.key);
		
		//console.log(data);
		if(encrypt_key==hash.digest('hex')){
			delete data.key;
			if(req.url=='/data/add'){
				submitDataInput(data);
			}else if(req.url=='/data/kj'){
				console.log('kj');
				console.log(data);
				calcJ(data, true)
			}
		}else{
			msg.errorCode=1;
			msg.errorMessage='校验不通过';
		}
		
		res.writeHead(200, {"Content-Type": "text/json"});
		res.write(JSON.stringify(msg));
		res.end();
	});
	
}).listen(8801);

//提交数据
function submitDataInput(data){
	log('提交从前台录入第'+data.number+'数据：'+data.data);
	
	try{
		var client=mysql.createClient(config.dbinfo);
	}catch(err){
		throw('连接数据库失败');
	}
	
	data.time=Math.floor((new Date(data.time)).getTime()/1000);
	client.query("insert into gygy_data(type, time, number, data) values(?,?,?,?)", [data.type, data.time, data.number, data.data], function(err, result){
		if(err){
			//console.log(err);
			// 普通出错
			if(err.number==1062){
				// 数据已经存在
				log('第'+data.number+'期数据已经存在数据');

			}else{
				log('运行出错2：'+err.message);
			}
		}else if(result){
			// 正常
			log('写入第'+data.number+'期数据成功');

			// 计算奖品
			//setTimeout(requestKj, 500, data.type, data.number);
			setTimeout(calcJ, 500, data);
		}else{
			global.log('未知运行出错');
		}
	});

	client.end();
}

//}






