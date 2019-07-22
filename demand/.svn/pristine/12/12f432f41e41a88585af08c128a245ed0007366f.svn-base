//单选美化
$("input[type='radio']").addClass("rdo");


//企业图片上传
var company_img = new img_tpl();
company_img.init({
    file_id:'file_company_url',
    src:"#img_company_url",
    this_fun:'company_img',
    file_size:1,//限制文件大小 单位:MB
    file_type:'jpg/png/jpeg',
    folder:'user/',
    callback_fun:function(e){
        $("#company_url").val(e);
    }
});
//房产图片上传
var house_img = new img_tpl();
house_img.init({
    file_id:'file_house_url',
    src:"#img_house_url",
    this_fun:'house_img',
    file_size:1,//限制文件大小 单位:MB
    file_type:'jpg/png/jpeg',
    folder:'user/',
    callback_fun:function(e){
        $("#house_url").val(e);
    }
});
//保单图片上传
var policy_img = new img_tpl();
policy_img.init({
    file_id:'file_policy_url',
    src:"#img_policy_url",
    this_fun:'policy_img',
    file_size:1,//限制文件大小 单位:MB
    file_type:'jpg/png/jpeg',
    folder:'user/',
    callback_fun:function(e){
        $("#policy_url").val(e);
    }
});
//行驶证图片上传
var car_img = new img_tpl();
car_img.init({
    file_id:'file_car_url',
    src:"#img_car_url",
    this_fun:'car_img',
    file_size:1,//限制文件大小 单位:MB
    file_type:'jpg/png/jpeg',
    folder:'user/',
    callback_fun:function(e){
        $("#car_url").val(e);
    }
});




// 完善个人信息
function loan_user_win(type,win_type){
    
    //获取浏览器高度
    var win_height = $(window).height();
    
    if(type=='hide'){
        $(".user-info-mask").hide();
        $(".user-info-win").hide();
        $(".nicescroll-rails").remove();
        $("body").css("overflow","auto");
    }else{
        var _type;

        switch(win_type){
            case 'company':
                _type=win_type;
            break;
            case 'house':
                _type=win_type;
            break;
            case 'credit':
                _type=win_type;
            break;
            case 'policy':
                _type=win_type;
            break;
            case 'car':
                _type=win_type;
            break;
            case 'loan':
                _type=win_type;
            break;
            case 'assure':
                _type=win_type;
            break;
            case 'shop':
                _type=win_type;
            break;

        }
        

        //窗口大小位置控制
        $(".user-info-win[type='"+_type+"']").css("height",win_height-200+"px");
        $(".user-info-win[type='"+_type+"']").css("margin-top",(win_height-$(".user-info-win[type='"+_type+"']").height())/2/2+"px");
        $(".user-info-win[type='"+_type+"'] #content").css("height",($(".user-info-win[type='"+_type+"']").height()-160)+"px");
        $(".user-info-win[type='"+_type+"'] #content input[type='text']").val("");

        $(".user-info-mask[type='"+_type+"']").show();
        $(".user-info-win[type='"+_type+"']").show();
        $("#ascrail2000").show();
        $("#ascrail2000").css("background-color","#eee");
        $("body").css("overflow","hidden");
        
        //滚动条
        // $("#content[type='"+_type+"']").niceScroll({
        //     cursorcolor: "#479ef8",//#CC0071 光标颜色
        //     cursoropacitymax: 1, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
        //     touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
        //     cursorwidth: "5px", //像素光标的宽度
        //     cursorborder: "0", // 游标边框css定义
        //     cursorborderradius: "5px",//以像素为光标边界半径
        //     autohidemode: false //是否隐藏滚动条
        // });
    }



}

$(function(){
    //利率计算小贷and银行贷
    var loan_del_fun = {
        init :function(){
            if($("input[name=loan_type]").val() == 1)
            {   //银行贷
                $("#loan_del").html("");
                $("#loan_del").append(  '<tr>'+
                                        '    <td class="loan_ll">月利率：'+total_rate+'%</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                        '    <th>贷款</th>'+
                                        '    <th>最小利息('+min_rate+'%/月)</th>'+
                                        '    <th>最小费用('+min_fee+'%/月)</th>'+
                                        '    <th>一次性('+nonrecurring_rate+'%/月)</th>'+
                                        '</tr>'+
                                        ' <tr>'+
                                        '    <td>'+loan_del_fun.get_money()+'万元/'+loan_del_fun.get_period()+'个月</td>'+
                                        '    <td>'+loan_del_fun.get_min_rate()+'元</td>'+
                                        '    <td>'+loan_del_fun.get_min_fee()+'元</td>'+
                                        '    <td>'+loan_del_fun.get_nonrecurring_rate()+'元</td>'+
                                        '</tr>');
            }else{
                loan_del_fun.get_period();
                //小贷
                $("#loan_del").html("");
                $("#loan_del").append(	'<tr>'+
                                        '    <td class="loan_ll">月利率：'+loan_del_fun.get_rate()+'%</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                        '    <th>到账金额</th>'+
                                        '    <th>利率和费用('+loan_del_fun.get_rate()+'%/月)</th>'+
                                        '</tr>'+
                                        '<tr>'+
                                        '    <td>'+loan_del_fun.get_money()+'元</td>'+
                                        '    <td>'+loan_del_fun.get_small_rate()+'元</td>'+
                                        '</tr>'); 
            }
           
        },
        temp_val:'',
        temp_val_per:'',
        get_money :function(){

            var nub = $("#apply_money").val();
            if(/^[0-9]*$/.test(nub)==false){
                $("#apply_money").val(this.temp_val);
            }else{
                this.temp_val = nub;
            }
            loan_del_fun.money = $("#apply_money").val();

            nub = $("#apply_money").val();
            return	nub;

        },
        get_period :function(){

            var nub = $("#apply_period").val();

            if(/^[0-9]*$/.test(nub)==false){
                $("#apply_period").val(this.temp_val_per);
            }else{
                this.temp_val_per = nub;
            }

            loan_del_fun.period = $("#apply_period").val();

            nub = $("#apply_period").val();

            return	nub;
        },
        money:0,
        period:0,
        //最小利率
        get_min_rate :function(){
            var nub = 0;
            var money = loan_del_fun.money*10000;
            var period = loan_del_fun.period;
            var test = money/period;

            for(var i = 1 ; i <= period ; i++){
                nub = nub + money*(min_rate/100);
                money = money - test;
            }

            nub = nub.toFixed(2);

            return	nub;
        },
        //最小费用
        get_min_fee :function(){

            var nub = 0;
            var money = loan_del_fun.money*10000;
            var period = loan_del_fun.period;
            var test = money / period;
            
            nub = money * (min_fee/100);
            nub =nub * period;

            nub = nub.toFixed(2);

            return	nub;
        },
        //一次性费用
        get_nonrecurring_rate :function(){
            var nub=0;
            var money = loan_del_fun.money*10000;

            nub = money * (nonrecurring_rate/100);

            nub = nub.toFixed(2);

            return	nub;
        },
        //获取利率（小贷）
        get_rate : function(){
            var nub = 0;

            nub = $("#apply_rate").val();

            return  nub;
        },
        //获取利息（小贷）
        get_small_rate :function(){
            var nub =0;
            var money = loan_del_fun.money;
            var period = loan_del_fun.period;

            nub = money*($("#apply_rate").val()/100)*period;

            return nub = nub.toFixed(2);
        }
    };

    //初始化
    if($("input[name=loan_type]").val() == 2){
        $("#apply_rate").val($("#apply_all li").eq(0).attr("key"));
        $("#apply_period").val($("#apply_all li").eq(0).attr("val"));
    }

    //初始化计算一波
    loan_del_fun.init();
   
    //添加监控事件
    $("#apply_money,#apply_period").on('input propertychange',function(){
        //进入计算总程序
        loan_del_fun.init();

    });

});
