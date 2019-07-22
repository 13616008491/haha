/*
 * @Author: zj 
 * @Date: 2018-04-28 13:52:24 
 * @Last Modified by:   zj 
 * @Last Modified time: 2018-04-28 13:52:24 
 */
 /**
 * @功能：获取tab菜单Ajax请求的数据,并设置对应缓存（可关闭）。
 * @开发者：zj
 * 2018-3-27
 */

/*使用方法
 * 1.实例化函数   例： var k = new ListAjax()
 * 2.调用主体函数并传递参数 
 * 
 * 		例：k.credit({
 * 
 * 				//储存tab菜单盒子的id
 * 				tabId:'',
 * 
 * 				//tab菜单筛选器标签（传空值默认为a标签）
 * 				可以传空值但必须存在
 * 				tab_filter:'',
 * 
 * 				//接口
 * 				port:'',
 * 
 * 				//请求参数的属性名
 * 				在html里给标签添加的自定义属性名
 * 				buteType:'',
 * 
 * 				//实例化的对象名称
 * 				此处为k
 * 				this_fun:'',
 * 
 * 				//tab菜单选中样式
 * 				curr_class:'',
 * 
 * 				//循环模板的函数
 * 				此属性需传递一个方法（循环插入html模板的方法）
 * 				必须定义一个形参用于接收请求返回的数据。
 * 				moudle_fun:function(data){
 * 	
 * 				},
 * 				
 * 				/是否开启缓存功能
 *				is_cache:''
 * 
 * 			});
 * 
 */



function ListAjax(){
	
	//创建参数对象
   var configure = new Object();
   
   //请求得到的数据
   var getData = new Object();
   
   //设置对象初值
   this.configure = {
   	
   		//储存tab菜单盒子的id
   		tabId:'',
   		
   		//tab菜单筛选器标签（传空值默认为a标签）
        tab_filter:'',
   		
   		//储存循环体储存盒子的id
   		boxId:'',
   		
   		//循环体
   		moudle:'',
   		
   		//接口
   		port:'',
   		
   		//参数的属性名
   		buteType:'',
   		
   		//实例化的对象名称
   		this_fun:'',
   		
   		//选中样式
   		curr_class:'',
   		
   		//循环模板的函数
		moudle_fun:'',
		
   		//是否开启缓存功能
   		//不传(默认true)
		is_cache:''
		
   };
   
   //主要函数(信用卡列表)
　　this.credit = function(obj){
		
		//匹配筛选器
		(obj.tab_filter===undefined) ? obj.tab_filter='a' :this.configure.tab_filter=obj.tab_filter;
		
		//保存接收到的参数
		this.configure = obj;
		
		//储存所有tab按钮
		var tabBtns=$("#"+this.configure.tabId+" "+this.configure.tab_filter);
		
		//给每个tab按钮添加事件
		for(var i=0;i<tabBtns.length;i++){
			tabBtns.eq(i).attr("onclick",this.configure.this_fun+".getType("+i+")");
		};
		
　　};
	
	//取得参数并发送请求
	this.getType = function(index){
		
		//实例化函数
		var fun = this.configure.moudle_fun;
		
		//获取参数并进行拆分
		var listType=$("#"+this.configure.tabId+" "+this.configure.tab_filter).eq(index).attr(this.configure.buteType),
			data_list = listType.split(':'),
			data_key = data_list[0],
			data_value = data_list[1];
		
		//参数合并为对象
		var para = new Object();
		para[data_key+""]=data_value;
		
		//缓存参数
		var sess_str,sess_key;
		sess_key = JSON.stringify(para);
		
		//改变选择样式
		$("#"+this.configure.tabId+" "+this.configure.tab_filter).eq(index).addClass(this.configure.curr_class).siblings().removeClass(this.configure.curr_class);
		
		//是否开启缓存功能
		if(this.configure.is_cache === undefined){
			
			//判断是否有缓存数据
			if(sessionStorage.getItem(sess_key)!=null){
				
				//缓存数据赋值给容器
				getData = JSON.parse(sessionStorage.getItem(sess_key));
				
				//调用生成模板方法
				fun(getData);
				
			}else{
				//ajax请求
				$.post(this.configure.port,para, function (data) {
					
					//请求成功
					if(data.status="success"){
						
						//数据赋值给容器
						getData = data.data;
						
						//调用生成模板方法
						fun(getData);
						
						//设置缓存
						sess_str = JSON.stringify(getData);
						sessionStorage.setItem(sess_key,sess_str);
					};
					
		        }, "json");
			};
			
		}else{
			
			//ajax请求
			$.post(this.configure.port,para, function (data) {
				
				//请求成功
				if(data.status="success"){
					
					//数据赋值给容器
					getData = data.data;
					
					//调用生成模板方法
					fun(getData);
					
				};
				
	        }, "json");
			
		};
	};
	
};

