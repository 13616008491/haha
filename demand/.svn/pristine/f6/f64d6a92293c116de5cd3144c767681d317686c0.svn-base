/* @Author: zj @Date: 2018-04-28 13:52:24 @Last Modified by:   zj @Last Modified time: 2018-04-28 13:52:24 */
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
function ListAjax(){var configure={};var getData={};this.configure={tabId:'',tab_filter:'',boxId:'',moudle:'',port:'',buteType:'',this_fun:'',curr_class:'',moudle_fun:'',is_cache:''};this.credit=function(obj){(obj.tab_filter===undefined)?obj.tab_filter='a':this.configure.tab_filter=obj.tab_filter;this.configure=obj;var tabBtns=$("#"+this.configure.tabId+" "+this.configure.tab_filter);for(var i=0;i<tabBtns.length;i+=1){tabBtns.eq(i).attr("onclick",this.configure.this_fun+".getType("+i+")")}};this.getType=function(index){var fun=this.configure.moudle_fun;var listType=$("#"+this.configure.tabId+" "+this.configure.tab_filter).eq(index).attr(this.configure.buteType),data_list=listType.split(':'),data_key=data_list[0],data_value=data_list[1];var para={};para[data_key+""]=data_value;var sess_str,sess_key;sess_key=JSON.stringify(para);$("#"+this.configure.tabId+" "+this.configure.tab_filter).eq(index).addClass(this.configure.curr_class).siblings().removeClass(this.configure.curr_class);if(this.configure.is_cache===undefined){if(sessionStorage.getItem(sess_key)!=null){getData=JSON.parse(sessionStorage.getItem(sess_key));fun(getData)}else{$.post(this.configure.port,para,function(data){if(data.status="success"){getData=data.data;fun(getData);sess_str=JSON.stringify(getData);sessionStorage.setItem(sess_key,sess_str)}},"json")}}else{$.post(this.configure.port,para,function(data){if(data.status="success"){getData=data.data;fun(getData)}},"json")}}};