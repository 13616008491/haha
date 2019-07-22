/*
 * @Author: zj 
 * @Date: 2018-05-02 12:01:10 
 * @Last Modified by: zj
 * @Last Modified time: 2018-05-31 13:51:59
 * @依赖 : layerui
 */

/*  输入框校验插件
 */

/*  重要变量：e  （介绍）
 *  e.type:当前输入框的类型（密码，手机号，邮箱等等。。。）
 *  e.val:当前输入框的值
 *  e.code:当前类型的正则表达式
 *  e.this_obj:当前的dom对象（jq的dom对象）
 */
$(function(){
    var check = {

        //程序入口
        init:function(){

            var c_type ;
            var this_obj;
            //给元素添加监控事件
            $('.z-check').on('input propertychange',function(){
                c_type = {
                    type:$(this).attr("check"),
                    val:$(this).find('input').val()
                };
                this_obj =this;
                //调用code匹配方法
                check.check_type(c_type,this_obj);
            });
            
        }(),

        //code匹配函数
        check_type:function(e,obj){
            var code ;
            var type = e.type;

            //匹配对应code
            switch(type)
            {
                case 'PHONE':
                    code = /^1[34578]\d{9}$/;
                    break;
                case 'PWD':
                    code = /^\d{6}$/;
                    break;
            }

            //给对象重新赋值
            e = {
                type:e.type,
                val:e.val,
                code:code,
                this_obj:obj
            };

            //判断有无正则开始对应路由
            (e.code === undefined)?check.main(e):check.base(e);
        },

        //其他正则
        code_all:{

            //数字
            is_nbm :/^[0-9]*$/

        },
        
        //不需要正则的路由
        main:function(e){
            (e.type == 'ISPWD')?check.ispwd(e):[];
            (e.type == 'CHAR')?check.char(e):[];
            (e.type == 'RANGEM')?check.range_money(e):[];
            (e.type == 'RANGEP')?check.range_period(e):[];
            (e.type == 'NUB')?check.nub(e):[];
        },

        //主要路由函数
        base:function(e){
            console.log(e.code.test(e.val));
            //判断是否符合code。
            if(e.val == '' || e.code.test(e.val) == false){

                //路由到指定方法，展示不同提示。
                switch(e.type)
                {
                    case 'PHONE':
                        check.phone(e);
                        break;
                    case 'PWD':
                        check.pwd(e);
                        break;
                    default:
                }

            }else{

                //在是密码的情况下避免成立后没有再储存密码。
                (e.type == 'PWD')?check.preser =e.val:[];
                //密码成立后清除离焦事件
                (e.type == 'PWD')?$(e.this_obj).unbind("change"):[];

                //回调提示正确
                this.check_tips(e,'<i class="iconfont icon-duihao"></i>','#479ef8');
            }
        },

        //回调事件（此处依赖的是layer.tips）此处的内容可以随意换成其他的提示插件  或自定义。
        // e:系统变量。(具体详情看当前文档最上面)
        // content:校验结果的中文提示。
        // color:颜色。（十六进制）
        check_tips: function(e,content,color){
            var _color;
            if(color === undefined){
                _color="#fa5d5d";
            }else{
                _color=color;
            }
            layer.tips(content,e.this_obj, {
                tips: [2, _color],
                time: 4000
            });
        },

        //手机号
        phone:function(e){
            var tips_tetx='';
            var tips_color="#fa5d5d";
            var tips_start=0;
            if(e.val == ''){
                tips_tetx = "手机号码不能为空";
                tips_start=1;
            }else if(check.code_all.is_nbm.test(e.val) == false){
                tips_tetx = "请输入数字";
                tips_start=1;
            }else if(e.val.length == 11 && e.code.test(e.val) == false){
                tips_tetx = "请输入以13、14、15、17、18开头的手机号码";
                tips_start=1;
            }else if(e.val.length>11){
                tips_tetx = "请输入11位手机号码";
                tips_start=1;
            }
            if(tips_start == 1){
                this.check_tips(e,tips_tetx);
            }
        },

        //密码
        pwd:function(e){
            if(e.val.length>=6&&e.val.length<=18){
                $(e.this_obj).unbind("change");
                //储存用户输入的密码
                check.preser =e.val;
            }else{
                if(e.val.length<6){
                    $(e.this_obj).on("change",function(){
                        check.check_tips(e,'密码不能小于6位数');
                    });
                }else{
                    $(e.this_obj).unbind("change");
                    this.check_tips(e,'密码不能大于18位数');
                }
            }
            if(e.val == ''){
                this.preser='';
                layer.closeAll('tips');
            }
        },

        //储存密码
        preser:'',

        //确定密码
        ispwd:function(e){
            if(e.val.length == check.preser.length && e.val != check.preser){
                this.check_tips(e,'两次密码不一致，请重试。');
            }else if(e.val.length > check.preser.length){
                if(check.preser.length !=0){
                    this.check_tips(e,'两次密码不一致，请重试。');
                }else{
                    this.check_tips(e,'请先输入密码。');
                }
            }else if(e.val == check.preser){
                if(e.val != ''){
                    this.check_tips(e,'<i class="iconfont icon-duihao"></i>','#479ef8');
                }
            }
        },

        //字符长度
        char:function(e){
            if(e.val.length>$(e.this_obj).attr('length')){
                this.check_tips(e,'请输入'+$(e.this_obj).attr('length')+'位数的验证码');
            }
        },

        //贷款额度范围
        range_money:function(e){
            if(parseInt(e.val)>parseInt(max_loan_money)||parseInt(e.val)<parseInt(min_loan_money)||e.val.length==0){
                if($(e.this_obj).attr("name")=="small"){
                    this.check_tips(e,'请输入正确的贷款额度('+min_loan_money+'-'+max_loan_money+'元)');
                }else{
                    this.check_tips(e,'请输入正确的贷款额度('+min_loan_money+'-'+max_loan_money+'万元)');
                }
            }else{
                this.check_tips(e,'<i class="iconfont icon-duihao"></i>','#479ef8');
            }
        },

        //贷款期限范围
        range_period:function(e){
            if(parseInt(e.val)>parseInt(max_period)||parseInt(e.val)<parseInt(min_period)||e.val.length==0){
                this.check_tips(e,'请输入正确的贷款期限('+min_period+'-'+max_period+'个月)');
            }else{
                this.check_tips(e,'<i class="iconfont icon-duihao"></i>','#479ef8');
            }
        },


        //只能数字
        temp_val:'',//临时常量
        nub:function(e){
            if(this.code_all.is_nbm.test(e.val)==false){
                $(e.this_obj).find("input[type=text]").val(this.temp_val);
            }else{
                this.temp_val = e.val;
            }
        }
        
    };
});
