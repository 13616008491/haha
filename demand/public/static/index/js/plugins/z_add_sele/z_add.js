/*
 * @Author: zj 
 * @Date: 2018-04-28 13:52:01 
 * @Last Modified by:   zj 
 * @Last Modified time: 2018-04-28 13:52:01 
 */

function selcity(){
	this.configure='';
	this.init = function(e){
		
		//参数储存到当前对象中
		this.configure=e;
		
		//先把数据加载出来(有缓存的话直接读缓存)；判断有无缓存
		if(sessionStorage.getItem("city_lsit_set")!=null){
				this.odata = JSON.parse(sessionStorage.getItem("city_lsit_set"));
		}else{
        	var odata='';
            $.post(this.configure.url, function (data) {
                console.log(data);
                console.log("不带参数");
                var _data = data.data;
                if(data.status == "success"){
                    _data = JSON.stringify(_data);//数据转为josn格式
                    sessionStorage.setItem("city_lsit_set",_data);//写入h5缓存
                }
                else{
                    console.log("请求失败");
                }
            }, 'json');
		}
		
		//如果是tab
		if(this.configure.is_tab==true){
			var inpuid =this.configure.id;
			var pare =this.configure.pare;
			var url =this.configure.url;
			var city = new Array();
			var valid = this.configure.valid;
			$(this.configure.tab+'[name='+this.configure.tabname+']').click(function(){
	            $.post($(this).attr(url),{'user_house_id':$(this).attr(pare)}, function (data) {
	                console.log(data);
	                console.log("带参数");
	                var _data = data.data;
	                if(data.status == "success"){
	                    var textt='';
						for(var i in _data.area_list){
							if(_data.area_list[i].area_id==_data.user_house_list.province_id){
								textt=_data.area_list[i].acronym;
								city.push(_data.area_list[i].area_id);
							}
							if(_data.area_list[i].area_id==_data.user_house_list.city_id){
								textt=textt+'-'+_data.area_list[i].acronym;
								city.push(_data.area_list[i].area_id);
							}
							if(_data.area_list[i].area_id==_data.user_house_list.area_id){
								textt=textt+'-'+_data.area_list[i].acronym;
								city.push(_data.area_list[i].area_id);
							}
						}

						console.log(valid);
						$("#"+inpuid).val(textt);//给输入框赋值
						$("#"+valid).val(city);
						$("#"+valid).val(city);
						city.splice(0,city.length);
	                }
	                else{
	                    console.log("请求失败");
	                }
	            }, 'json');
           	});
        }else{
        	
			$("#"+this.configure.id).attr("onclick",this.configure.this_fun+".html()");
			
        }
		
		
		//是否需要默认值
		if(this.configure.default!=undefined){
            var city = new Array();
			this.odata = JSON.parse(sessionStorage.getItem("city_lsit_set"));
			var _data = this.odata.area_list;
            var valid = this.configure.valid;
			for(var i in _data){
				
				//省
				if(_data[i].area_id == this.configure.default.province){
					$("#"+this.configure.id).val(_data[i].acronym);//给输入框赋值
                    city.push(_data[i].area_id);
				}
				//市
				if(_data[i].area_id == this.configure.default.city){
					$("#"+this.configure.id).val($("#"+this.configure.id).val()+'-'+_data[i].acronym);//给输入框赋值
                    city.push(_data[i].area_id);
				}
				//区
				if(_data[i].area_id == this.configure.default.area){
					$("#"+this.configure.id).val($("#"+this.configure.id).val()+'-'+_data[i].acronym);//给输入框赋值
                    city.push(_data[i].area_id);
				}
				
			}
			console.log(city);
            $("#"+valid).val(city);
            city.splice(0,city.length);
		}
	};
	
	this.odata='';//把数据存到内存中
	this.aft_city= new Array();//当前选择的省市区
	
	//路由
	this.html=function (){
		if($("#z-add-heard a").length<1){
			$("body").append(	'<div class="z-add-selection" id="z-add-selection">'+
								'	<b id="z-add-close">x</b>'+
								'	<div class="z-add-heard" id="z-add-heard">'+
								'	</div>'+
								'	<div class="z-add-box" id="z-add-box">'+
								'	</div>'+
								'</div>');
			this.get_parent();//初始化显示省份
			this.style_handle();//设置样式(对应位置)
		}
	};	
	
	//根据参数返回对应的数据
	this.getdata=function(type,para){
		$("#z-add-close").attr("onclick",this.configure.this_fun+".box_close()");
		
		this.odata = JSON.parse(sessionStorage.getItem("city_lsit_set"));
		
		var edata = new Array();
		
		var _data = this.odata.area_list;
		switch (type){
			case 'parent'://返回省
				for(var i in _data){
					if(_data[i].parent_id == para){
						edata.push(_data[i]);
					}
				}
				break;
			case 'city'://返回市
				for(var i in _data){
					if(_data[i].parent_id == para){
						edata.push(_data[i]);
					}
				}
				break;
			case 'area'://返回区
				for(var i in _data){
					if(_data[i].parent_id == para){
						edata.push(_data[i]);
					}
				}
				break;
		}
		
		return edata;
	};
	
	//获取省份
	this.get_parent=function(){
		var data = this.getdata('parent',0);//取得对应数据
		$("#z-add-box").html("");
		for(var i in data){
			$("#z-add-box").append('<span onclick="'+this.configure.this_fun+'.get_city(this,\'city\','+data[i].area_id+')">'+data[i].acronym+'</span>');
		}
		$("#z-add-heard").append('<a class="aft">请选择</a>');
	};
	
	//获取市
	this.get_city=function(obj,type,para){
		var data = this.getdata(type,para);//取得对应数据
		$("#z-add-box").html("");
		for(var i in data){
			//如果是2级联动直接添加停止事件
			if(this.configure.level == 2){
				$("#z-add-box").append('<span onclick="'+this.configure.this_fun+'.is_all(this,'+data[i].area_id+')">'+data[i].acronym+'</span>');
			}else{
				$("#z-add-box").append('<span onclick="'+this.configure.this_fun+'.get_area(this,\'area\','+data[i].area_id+')">'+data[i].acronym+'</span>');
			}
		}
		
		this.aft_city.push(para);//记录当前选择的城市
		
		$("#"+this.configure.id).val($(obj).text());//给输入框赋值
		
		var temp =$("#z-add-heard").html();
		$("#z-add-heard").html("");
		$("#z-add-heard").append('<a key="'+para+'">'+$(obj).text()+'</a>'+temp);
	};
	
	//获取区
	this.get_area=function(obj,type,para){
		var data = this.getdata(type,para);//取得对应数据
		$("#z-add-box").html("");
		for(var i in data){
			$("#z-add-box").append('<span onclick="'+this.configure.this_fun+'.is_all(this,'+data[i].area_id+')">'+data[i].acronym+'</span>');
		}
		
		this.aft_city.push(para);//记录当前选择的城市
		
		$("#"+this.configure.id).val($("#"+this.configure.id).val()+'-'+$(obj).text());//给输入框赋值
		
		var temp =$("#z-add-heard").html();
		$("#z-add-heard").html("");
		$("#z-add-heard").append('<a key="'+para+'">'+$(obj).text()+'</a>'+temp);
	};
	
	//三级全部选完后
	this.is_all=function(obj,para){
		$("#z-add-selection").remove();
		
		$("#"+this.configure.id).val($("#"+this.configure.id).val()+'-'+$(obj).text());//给输入框赋值
		
		$("#z-add-heard").append('<a key="'+para+'">'+$(obj).text()+'</a>');
		
		this.aft_city.push(para);//记录当前选择的城市
		
		$("#"+this.configure.valid).val("");//要传的val值清空
		$("#"+this.configure.valid).val(this.aft_city);//要传的val值重新赋值
		this.aft_city.splice(0,this.aft_city.length);//记录选择了的城市数据清空
		
		$("#"+this.configure.id).attr("onclick",this.configure.this_fun+".html()");
		//console.log(selcity.aft_city)
	};
	
	//位置控制
	this.style_handle=function(){
		var obj_po= $("#"+this.configure.id).offset();
		//console.log(test)
		$("#z-add-selection").css({
			'top':obj_po.top+40,
			'left':obj_po.left
		});
	};
	
	//关闭
	this.box_close=function(){
		$("#"+this.configure.id).attr("onclick",this.configure.this_fun+".html()");
		$("#z-add-selection").remove();//html节点删除
		$("#"+this.configure.id).val("");//清空展示的值
		$("#"+this.configure.valid).val("");//要传的val值清空
		this.aft_city.splice(0,this.aft_city.length);//记录选择了的城市数据清空
	};
}
