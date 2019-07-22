/*
 * @Author: zj 
 * @Date: 2018-05-08 17:18:47 
 * @Last Modified by: zj
 * @Last Modified time: 2018-05-23 14:51:01
 * 
 *   带*号的为必传项
 *   *  file_id ：input的id 
 *      src : 把链接返回并赋值给 指定元素 可以id 也可以class（"#test",".test"）
 *   *  this_fun : 实例化的方法名
 *      file_name ： 文件名 (不传的话将默认使用当前时间生成文件名)
 *   *  folder ： 上传到指定文件夹
 *      callback_fun : 上传成功后回调的事件
 *   *  file_size : 文件大小(限制文件最大的大小 单位：MB)
 *   *  file_type : 文件类型(可上传的文件后缀名  多个值用/隔开) 例: "jpg/png/jpeg"
 *     
 * 
 *   
 * [重要]需要在引入插件前引入官方SDK   <script src="http://gosspublic.alicdn.com/aliyun-oss-sdk-4.4.4.min.js"></script>
 * 
 */

function img_tpl() {

    this.oss_prar = new OSS.Wrapper({
        region : "oss-cn-shanghai",
        accessKeyId : 'LTAIvvJ4jf6OzVeT',
        accessKeySecret : '9eRD8ScDTk9AQlkCMTUXXc856xcoOb',
        bucket :'purong'
    });
    this.prop ="";
    this.init = function(e){
        //检验调用时参数的完整性
        (e.file_id === undefined || e.this_fun === undefined || e.folder === undefined || e.file_size === undefined || e.file_type === undefined)?console.log("oss上传调用失败：参数不完整，具体参数请参考插件原文件头部注释。"): this.prop=e;
        //参数齐全给元素添加事件
        (this.prop != "")?$("#"+this.prop.file_id).attr("onchange",this.prop.this_fun+".is_img()"):[];
    };

    //卡文件类型
    this.is_img = function (){

        //获取文件
        var file = $("#"+this.prop.file_id)[0].files[0];

        //获取文件相关属性
        var file_name =file.name;
        var file_type;
        var file_size =file.size;
       
        //方法实例化
        var upload_file = this.upload_file;
        var tips = this.tips;
        
        //取得后缀名
        file_name = file_name.split(".");
        file_type = file_name[file_name.length-1];

        //参数本地化
        var size = this.prop.file_size;
        size = size*1024;
        var type = this.prop.file_type;
        type = type.split("/");
        
        //保存文件类型  用于提示
        var _stype="";

        //判断类型是否符合要求
        _is_type=false;

        //判断文件类型
        for(var i=0; i< type.length ;i++){

            //拼接文件类型 用于提示，
            _stype =_stype+ "."+type[i]+"/";
            if(file_type == type[i]){
                _is_type=true;
            }

        }
        //文件类型符合的情况下再判断文件大小
        if(_is_type){
            if(file_size/1024 < size){

                //全都符合   调用上传方法
                this.upload_file(file);
            }else{
                tips("","图片大小不能超过1M!");
            }
        }else{
            tips("","请选择"+_stype+"类型的文件！");
        }
    };

    //上传
    this.upload_file = function (e){
         //方法实例化
         var tips = this.tips;


        var val= $("#"+this.prop.file_id).val();

        //文件有多个点时取最后一个点
        var suffix = val.split(".");
        
        suffix ="."+suffix[suffix.length-1];

        if(this.prop.file_name === undefined){
            var obj=this.timestamp();  // 这里是生成文件名(根据时间)
        }else{
            var obj=this.prop.file_name;  // 这里是生成文件名(根据传的值)
        }
        var storeAs = this.prop.folder+obj+suffix;  //命名空间+文件名+后缀名
        //console.log(' => ' + storeAs);

        //实例化参数
        var prop = this.prop;
        //实例化回调方法
        var fun = this.prop.callback_fun;

        this.oss_prar.multipartUpload(storeAs, e).then(function (result) {
            //console.log(result); //--->返回对象
            //console.log(); //--->返回链接
            result.url = 'http://purong.oss-cn-shanghai.aliyuncs.com/'+result.name;
            //console.log(result.url); //--->返回链接
            this.data_url=result.url; //--->返回链接
            if(prop.src != undefined){
                console.log("已赋值src");
                $(prop.src).attr("src",result.url);
            }
            if(result.name != ""){
                console.log("oss上传成功");
                tips("","上传成功");
                fun(result.url);
            }
        }).catch(function (err) {
            console.log("上传失败："+err);
        }); 
    };

    //生成文件名
    this.timestamp = function () {
        var time = new Date();  
        var y = time.getFullYear();  
        var m = time.getMonth()+1;  
        var d = time.getDate();  
        var h = time.getHours();  
        var mm = time.getMinutes();  
        var s = time.getSeconds();  
        
        //console.log(y);
    
        return ""+y+this.add0(m)+this.add0(d)+this.add0(h)+this.add0(mm)+this.add0(s);  
    };

    //补零
    this.add0 = function(m) {
        return m<10?'0'+m : m;  
    };

    //回调事件  此处的内容可以随意换成其他的提示插件  或自定义。
    this.tips = function(e,content,color){
        layer.msg(content);
    },

    //储存返回的链接
    this.data_url='';

    //返回链接
    this.cess = function(){
        return this.data_url;
    };

}