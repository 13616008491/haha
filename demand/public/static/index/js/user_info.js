$(document).ready(function (){//样式控制
	var aft_index=$("#user_nav .aft").parent().index();
    $("#user_nav i").css("top",(aft_index+1)*54-54+14+"px");

    $("input[type='radio']").addClass("rdo");


    // $('#content').niceScroll({
    //     cursorcolor: "#479ef8",//#CC0071 光标颜色
    //     cursoropacitymax: 1, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
    //     touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
    //     cursorwidth: "5px", //像素光标的宽度
    //     cursorborder: "0", // 游标边框css定义
    //     cursorborderradius: "5px",//以像素为光标边界半径
    //     autohidemode: false //是否隐藏滚动条
    // });


    //根据选择后的展示不同输入框
    // 使用方法具体参考 用户中心->添加房产->房产按揭情况
    var _disbox,
        _dcode,
        _dvo,
        _ddom;
    $("input[vocode]").click(function(){
        _disbox=$(this);
        _dcode=_disbox.attr("code");
        _dvo=_disbox.attr("vocode");
        _ddom = $("["+_dvo+"="+_dvo+"]");
        _cddom = $("[code="+_dvo+"_box]");

        if(_dcode == "code"){
            _ddom.show();
            $("["+_dvo+"=no]").show();
            _cddom.hide();
        }else if(_dcode == _dvo){
            _ddom.hide();
            $("["+_dvo+"=no]").show();
            _cddom.show();
        }else{
            _ddom.hide();
            $("["+_dvo+"=no]").hide();
        }
    });
    
    //根据下拉选择的值 来展示不同输入框
    var select_check =["holder_type","house_type","loan_type"];

    $("select[name='"+select_check[0]+"']").on("change",function(){
        var jz = $("select[name='"+select_check[0]+"'] option:selected").val();
        var zz = $("[check_select="+select_check[0]+"]").attr("code");
        if(jz==zz){
            $("[check_select="+select_check[0]+"]").show();
        }else{
            $("[check_select="+select_check[0]+"]").hide();
        }
    });
    $("select[name='"+select_check[1]+"']").on("change",function(){
        var jz = $("select[name='"+select_check[1]+"'] option:selected").val();
        var zz = $("[check_select="+select_check[1]+"]").attr("code");
        if(jz==zz){
            $("[check_select="+select_check[1]+"]").show();
        }else{
            $("[check_select="+select_check[1]+"]").hide();
        }
    });
    $("select[name='"+select_check[2]+"']").on("change",function(){
        var jz = $("select[name='"+select_check[2]+"'] option:selected").val();
        var zz = $("[check_select="+select_check[2]+"]").attr("code");
        if(jz==zz){
            $("[check_select="+select_check[2]+"]").show();
        }else{
            $("[check_select="+select_check[2]+"]").hide();
        }
    });
});



/*  个人信息详情js  */
//信用卡js
function credit_card(index,type) {
	var htmldom=$(index);
	htmldom.addClass("aft").siblings("span").removeClass("aft");
	if(htmldom.hasClass("aft-btn")){
		console.log("添加")
		htmldom.parents(".personal-info-page").find(".info-list-tab span").removeClass("aft")
	}else{
		htmldom.parents(".personal-info-page").find(".aft-btn").removeClass("aft")
	}

   //展示信息填写窗口
   (type === undefined)?user_win():loan_user_win('',type);
	
    var url = $(index).attr('data-src'),
        user_credit_card_id = $(index).attr('credit_card_id');
    if(user_credit_card_id == ''){
        $('#title').html('添加信用卡 <i onclick="user_win(\'hide\')">×</i>');
        $('select[name=bank_id]').val('');
        $('input[name=amount]').val('');
        $('input[name=used_amount]').val('');
        $('input[name=user_credit_card_id]').val('');

        return false;
    }
    $('#title').html('修改信用卡 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url,{'user_credit_card_id':user_credit_card_id}, function (data) {
        if(data.status == 'success'){
            $('select[name=bank_id]').val(data.data.bank_id);
            $('input[name=amount]').val(data.data.amount);
            $('input[name=used_amount]').val(data.data.used_amount);
            $('input[name=user_credit_card_id]').val(data.data.user_credit_card_id);
        }else{
            layer.msg(data.msg);
        }

    }, 'json')

}

//保单js
function policy(index,type) {
    $("#img_img_url").attr("src","/static/index/images/sample_map_bao.png");

    var htmldom=$(index);
	htmldom.addClass("aft").siblings("span").removeClass("aft");
	if(htmldom.hasClass("aft-btn")){
		console.log("添加")
		htmldom.parents(".personal-info-page").find(".info-list-tab span").removeClass("aft")
	}else{
		htmldom.parents(".personal-info-page").find(".aft-btn").removeClass("aft")
	}

    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

    var url = $(index).attr('data-src'),
        user_policy_id = $(index).attr('user_policy_id');
    if(user_policy_id == ''){
        $('#title').html('添加保单 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=user_policy_id]').val('');
        $('input[name=company_name]').val('');
        $('input[name=insured_name]').val('');
        $('input[name=start_time]').val('');
        $('input[name=pay_age_limit]').val('');
        $('input[name=insured_name]').val('');
        $('select[name=pay_type]').val('');
        $('select[name=policy_type]').val('');
        $('input[name=pay_money]').val('');
        $('input[name=img_url]').val('');
        $('#user_policy_submit .img_url').val('');

        return false;
    }
    $('#title').html('修改保单 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url,{'user_policy_id':user_policy_id}, function (data) {
        if(data.status == 'success'){
            $('input[name=user_policy_id]').val(data.data.user_policy_id);
            $('input[name=company_name]').val(data.data.company_name);
            $('input[name=insured_name]').val(data.data.insured_name);
            $('input[name=start_time]').val(data.data.start_time);
            $('input[name=pay_age_limit]').val(data.data.pay_age_limit);
            $('input[name=insured_name]').val(data.data.insured_name);
            $('select[name=pay_type]').val(data.data.pay_type);
            $('select[name=policy_type]').val(data.data.policy_type);
            $('input[name=pay_money]').val(data.data.pay_money);
            $('input[name=img_url]').val(data.data.img_url);
            $("#img_policy_url").attr('src', data.data.img_url)
            if(data.data.img_url) {
                $("#img_img_url").attr('src', data.data.img_url)
                $('#user_policy_submit .img_url').val(data.data.img_url);
            }
            return false
        }else{
            layer.msg(data.msg);
        }

    }, 'json')
}


//车辆js
function car(index,type) {
    $("#img_licence_url").attr("src","/static/index/images/sample_map_xing.png");

    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

	var htmldom=$(index);
	htmldom.addClass("aft").siblings("span").removeClass("aft");
	if(htmldom.hasClass("aft-btn")){
		htmldom.parents(".personal-info-page").find(".info-list-tab span").removeClass("aft")
	}else{
		htmldom.parents(".personal-info-page").find(".aft-btn").removeClass("aft")
	}
	
    var url = $(index).attr('data-src'),
        user_car_id = $(index).attr('user_car_id');
    $(index).addClass("aft").siblings("span").removeClass("aft");


    if(user_car_id == ''){
        $('#title').html('添加车辆 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=user_car_id]').val('');
        $('select[name=holder_type]').val('');
        $('select[name=car_brand_id]').val('');
        $('select[name=car_model_id]').html('<option value="">请选择...</option>');
        $('select[name=car_model_id]').val('');
        $('select[name=car_config_id]').html('<option value="">请选择...</option>');
        $('select[name=car_config_id]').val('');
        $('input[name=run_mileage]').val('');
        $('input[name=car_money]').val('');
        $('input[name=buy_time]').val('');
        $("input[name=car_pay_type]").attr("checked",false);
        $("input[name=is_mortgage]").attr("checked",false);
        $('input[name=installment_loan_money]').val('');
        $('input[name=installment_loan_cycle]').val('');
        $('input[name=installment_month_repay]').val('');
        $('input[name=installment_repay_count]').val('');
        $('input[name=installment_handling_bank]').val('');
        $('input[name=mortgage_start_time]').val('');
        $('input[name=mortgage_end_time]').val('');
        $('input[name=mortgage_money]').val('');
        $('input[name=full_use_year]').val('');
        $('input[name=holder_name]').val('');
        $('#user_car_submit .licence_url').attr('src','');

        return false;
    }
    $('#title').html('修改车辆 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url,{'user_car_id':user_car_id}, function (data) {

        console.log(data);
        //数据初始化
        var  model_html = '';
        var  config_html = '';

        if(data.status == 'success'){
            var  model = data.data.car_model_list;
            var  config = data.data.car_config_list;
            var  data = data.data.user_car_info;
            $('input[name=user_car_id]').val(data.user_car_id);
            (data.holder_type==$("[check_select=holder_type]").attr("code"))?$("[check_select=holder_type]").show():$("[check_select=holder_type]").hide();
            $('select[name=holder_type]').val(data.holder_type);
            $('select[name=car_brand_id]').val(data.car_brand_id);

            if(data.licence_url) {
                $("#img_licence_url").attr('src', data.licence_url);
                $("#img_car_url").attr('src', data.licence_url);
            }

            //车辆型号
            $('select[name=car_model_id]').html('<option value="">请选择...</option>');
            for(var i in model){

                if(model[i].car_brand_id==data.car_brand_id){
                    var  model_checked = '';
                    if(data.car_model_id == model[i].car_model_id){
                        model_checked ="selected";
                    };


                    model_html=model_html+'<option '+model_checked+' value='+model[i].car_model_id +'>'+model[i].car_model_name+'</option>';
                }
            }
            $('select[name=car_model_id]').append(model_html);

            //车辆配置
            $('select[name=car_config_id]').html('<option value="">请选择...</option>');
            for(var i in config){
                if(config[i].car_model_id==data.car_model_id && config[i].car_brand_id==data.car_brand_id){
                    var  config_checked = '';
                    (data.car_config_id == config[i].car_config_id) ? config_checked ="selected":[];

                    config_html=config_html+'<option '+config_checked+' value='+config[i].car_config_id+'>'+config[i].car_config_name+'</option>';
                }
            }
            $('select[name=car_config_id]').append(config_html);
            $('input[name=run_mileage]').val(data.run_mileage);
            $('input[name=car_money]').val(data.car_money);
            $('input[name=buy_time]').val(data.buy_time);
            $("input[name=car_pay_type][value='"+data.pay_type+"']").attr("checked",true).click();
            $('input[name=installment_loan_money]').val(data.installment_loan_money);
            $('input[name=installment_loan_cycle]').val(data.installment_loan_cycle);
            $('input[name=installment_month_repay]').val(data.installment_month_repay);
            $('input[name=installment_repay_count]').val(data.installment_repay_count);
            $('input[name=installment_handling_bank]').val(data.installment_handling_bank);
            $("input[name=is_mortgage][value='"+data.is_mortgage+"']").attr("checked",true).click();
            $('input[name=mortgage_start_time]').val(data.mortgage_start_time);
            $('input[name=mortgage_end_time]').val(data.mortgage_end_time);
            $('input[name=mortgage_money]').val(data.mortgage_money);
            $('input[name=full_use_year]').val(data.full_use_year);
            $('input[name=holder_name]').val(data.holder_name);
            $('input[name=licence_url]').val(data.licence_url);
            $('#user_car_submit .licence_url').attr('src',data.licence_url);
            return false
        }else{
            layer.msg(data.msg);
        }

    }, 'json')
}


//房产js
function house(index,type) {
    $("#img_licence_url").attr("src","/static/index/images/sample_map_fang.png");

	var htmldom=$(index);
	htmldom.addClass("aft").siblings("span").removeClass("aft");
	if(htmldom.hasClass("aft-btn")){
		htmldom.parents(".personal-info-page").find(".info-list-tab span").removeClass("aft")
	}else{
		htmldom.parents(".personal-info-page").find(".aft-btn").removeClass("aft")
	}

	//展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

    var url = $(index).attr('data-src'),
      user_house_id = $(index).attr('user_house_id');
    $(index).addClass("aft").siblings("span").removeClass("aft");
    if(user_house_id == ''){
        $('#title').html('添加房产 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=user_house_id]').val('');
        $('select[name=holder_type]').val('');
        $('input[name=holder_name]').val('');
        $('select[name=mortgage_num]').val('');
        $('select[name=house_type]').val('');
        $('input[name=house_type_name]').val('');
        $('select[name=house_address]').val('');
        $('input[name=address]').val('');
        $('input[name=area]').val('');
        $('input[name=price]').val('');
        $("input[name=house_pay_type]").attr("checked",false);
        $("input[name=is_mortgage]").attr("checked",false);
        $('input[name=installment_loan_money]').val('');
        $('input[name=installment_loan_cycle]').val('');
        $('input[name=installment_month_repay]').val('');
        $('input[name=installment_repay_count]').val('');
        $('input[name=full_service_life]').val('');
        $('input[name=installment_handling_bank]').val('');
        $('input[name=mortgage_start_time]').val('');
        $('input[name=mortgage_end_time]').val('');
        $('input[name=mortgage_money]').val('');
        $('input[name=licence_url]').val('');
        $('#three_add').val('');
        $('#three_add_val').val('');
        $('input[name=age]').val('');
        $('#user_house_submit .house_licence_url').attr('src','');
        return false;
    }

    $('#title').html('修改房产 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url,{'user_house_id':user_house_id}, function (data) {
        if(data.status == 'success'){
            var data = data.data;

            birth_add.init({
                id:'three_add',
                valid:'three_add_val',
                url:area_url,
                this_fun:'birth_add',
                default:{
                    province:data.province_id,
                    city:data.city_id,
                    area:data.area_id,
                },
            });
            if(data.licence_url) {
                $("#img_licence_url").attr('src', data.licence_url);
                $("#img_house_url").attr('src', data.licence_url);
            }

            $('input[name=licence_url]').val(data.licence_url);
            $('input[name=user_house_id]').val(data.user_house_id);
            $('input[name=address]').val(data.address);
            $('input[name=area]').val(data.area);
            $('input[name=age]').val(data.age);
            $('input[name=price]').val(data.price);
            $('input[name=full_service_life]').val(data.full_service_life);
            (data.holder_type==$("[check_select=holder_type]").attr("code"))?$("[check_select=holder_type]").show():$("[check_select=holder_type]").hide();
            $('select[name=holder_type]').val(data.holder_type);
            (data.house_type==$("[check_select=house_type]").attr("code"))?$("[check_select=house_type]").show():$("[check_select=house_type]").hide();
            $('select[name=house_type]').val(data.house_type);
            $('select[name=mortgage_num]').val(data.mortgage_num);
            $("input[name=house_pay_type][value='"+data.pay_type+"']").attr("checked",true).click();
            $('input[name=installment_loan_money]').val(data.installment_loan_money);
            $('input[name=installment_loan_cycle]').val(data.installment_loan_cycle);
            $('input[name=installment_month_repay]').val(data.installment_month_repay);
            $('input[name=installment_repay_count]').val(data.installment_repay_count);
            $('input[name=installment_handling_bank]').val(data.installment_handling_bank);
            $("input[name=is_mortgage][value='"+data.is_mortgage+"']").attr("checked",true).click();
            $('input[name=mortgage_start_time]').val(data.mortgage_start_time);
            $('input[name=mortgage_end_time]').val(data.mortgage_end_time);
            $('input[name=mortgage_money]').val(data.mortgage_money);
            $('input[name=holder_name]').val(data.holder_name);
            $('input[name=house_type_name]').val(data.house_type_name);
            $('#user_house_submit .house_licence_url').attr('src',data.licence_url);
            return false
        }else{
            layer.msg(data.msg);
        }

    }, 'json')
}

//用户贷款信息
function user_loan(index,type) {
	
    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

    var url = $(index).attr('data-src'),
        user_loan_id = $(index).attr('user_loan_id');
    if (user_loan_id == '') {
        $('#title').html('添加贷款 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=user_loan_id]').val('');
        $('select[name=loan_type]').val('');
        $('input[name=loan_type_name]').val('');
        $('input[name=loan_money]').val('');
        $('input[name=loan_cycle]').val('');
        $('input[name=repay_amount]').val('');
        $('input[name=loan_time]').val('');
        $('input[name=settle_time]').val('');
        return false;
    }
    $('#title').html('修改贷款 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url, {'user_loan_id': user_loan_id}, function (data) {
        if (data.status == 'success') {
            $('input[name=user_loan_id]').val(data.data.user_loan_id);
            (data.data.loan_type==$("[check_select=loan_type]").attr("code"))?$("[check_select=loan_type]").show():$("[check_select=loan_type]").hide();
            $('select[name=loan_type]').val(data.data.loan_type);
            $('input[name=loan_type_name]').val(data.data.loan_type_name);
            $('input[name=loan_money]').val(data.data.loan_money);
            $('input[name=loan_cycle]').val(data.data.loan_cycle);
            $('input[name=repay_amount]').val(data.data.repay_amount);
            $('input[name=loan_time]').val(data.data.loan_time);
            $('input[name=settle_time]').val(data.data.settle_time);
            $("input[name=is_overdue][value='"+data.data.is_overdue+"']").attr("checked",true).click();
            return false
        } else {
            layer.msg(data.msg);
        }
    }, 'json');
}


//用户担保信息
function user_assure(index,type) {
    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

    var url = $(index).attr('data-src'),
        user_assure_id = $(index).attr('user_assure_id');
    if (user_assure_id == '') {
        $('#title').html('添加担保 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=user_assure_id]').val('');
        $('input[name=assure_money]').val('');
        $('input[name=assure_cycle]').val('');
        $('input[name=assure_time]').val('');
        $('input[name=settle_time]').val('');
        $('input[name=is_overdue]').attr("checked",false);
        return false;
    }
    $('#title').html('修改担保 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url, {'user_assure_id': user_assure_id}, function (data) {
        if (data.status == 'success') {
            $('input[name=user_assure_id]').val(data.data.user_assure_id);
            $('input[name=assure_money]').val(data.data.assure_money);
            $('input[name=assure_cycle]').val(data.data.assure_cycle);
            $('input[name=assure_time]').val(data.data.assure_time);
            $('input[name=settle_time]').val(data.data.settle_time);
            $("input[name=is_overdue][value='"+data.data.is_overdue+"']").attr("checked",true);
            return false
        } else {
            layer.msg(data.msg);
        }
    }, 'json');
}

//公司信息
function company(index, type) {
    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);

    var url = $(index).attr('data-src'),
        user_company_id = $(index).attr('user_company_id');
    
    (type === undefined)?$('#title').html('修改公司 <i onclick="user_win(\'hide\')">×</i>'):$('#title').html('修改公司 <i onclick="loan_user_win(\'hide\')">×</i>');
    $.post(url,{'user_company_id':user_company_id}, function (data) {
        if(data.status == 'success'){
            var data = data.data;

            birth_add.init({
                id:'three_add',
                valid:'three_add_val',
                url:area_url,
                this_fun:'birth_add',
                default:{
                    province:data.province_id,
                    city:data.city_id,
                    area:data.area_id,
                },
            });
            $("#img_licence_url").attr('src', data.licence_url)
            $("#img_house_url").attr('src', data.licence_url)
            $("input[name=user_company_id]").val(data.user_company_id)

            $('input[name=company_name]').val(data.company_name);
            $('input[name=law_man]').val(data.law_man);
            $('input[name=licence_num]').val(data.licence_num);
            $('input[name=manager]').val(data.manager);
            $('input[name=phone]').val(data.phone);
            $('input[name=tel]').val(data.tel);
            $('input[name=register_capital]').val(data.register_capital);
            $('input[name=register_date]').val(data.register_date);
            $('input[name=hold_ratio]').val(data.hold_ratio);
            $('input[name=employee_num]').val(data.employee_num);
            $('input[name=flow_in]').val(data.flow_in);
            $('input[name=flow_out]').val(data.flow_out);
            $('input[name=website_url]').val(data.website_url);
            $('input[name=license_address]').val(data.license_address);
            $('input[name=office_address]').val(data.office_address);
            if(data.licence_url){
                $('#img_company_url').attr('src',data.licence_url);
            }else {
                $('#img_company_url').attr('src','/static/index/images/sample_map_ying.png');
            }
            $('#company_url').val(data.licence_url);
            $('select[name=company_type]').val(data.company_type);
            return false
        }else{
            layer.msg(data.msg);
        }

    }, 'json')
}


//删除用户信息
function delete_info(this_id){
    event.stopPropagation();
    //询问框
    layer.confirm('是否确认删除该条记录？', {
        btn: ['确定删除','取消'], //按钮
        icon:3
    }, function(){
        var url =$(this_id).attr('data-src');
        var delete_id = $(this_id).attr('delete-id');
        $.post(url,{delete_id:delete_id},function(data){
            //判断信息是否取得成功
            if(data.status == "success"){
                layer.msg(data.msg);
                location.reload();
            }else{
                //提示信息
                layer.msg(data.msg);

                //返回值
                return false;

            }
        },"json");

    }, function(){

    });
};

//展示更多tab
function more_tab(obj){
	var htmldom = $(obj).parents(".personal-info-page").find(".info-list-tab");
	
	if($(obj).attr("is_star")=="n"){
		$(obj).html("收起");
		$(obj).attr("is_star","y");
		htmldom.css({
			"height":"auto",
			"white-space":"normal",
			"box-shadow":"-5px 5px 10px #eee"
		})
	}else{
		$(obj).html("更多");
		$(obj).attr("is_star","n");
		htmldom.css({
			"height":"45",
			"white-space":"normal",
			"box-shadow":"none"
		})
	}
	
}


//贷款记录展示更多
function loan_record(obj){
    $(obj).siblings(".loan-record-details").show().parents(".loan-record-list").siblings(".loan-record-list").find(".loan-record-details").hide();
}
//投标方详情弹窗
function tender_from(start){
    if(start == "open"){
        $("#tender_cover").show();
        $("#tender_from").show();
    }else{
        $("#tender_cover").hide();
        $("#tender_from").hide();
    }
}

// 完善个人信息
function user_win(type){
	
	//获取浏览器高度
	var win_height = $(window).height();
	
    if(type=='hide'){
        $("#user_info_mask").hide();
        $("#user_info_win").hide();
        $("#ascrail2000-hr").hide();
        $("#ascrail2000").hide();
        $("body").css("overflow","auto");
    }else{
    	
    	//窗口大小位置控制
    	$("#user_info_win").css("height",win_height-200+"px");
    	$("#user_info_win").css("margin-top",(win_height-$("#user_info_win").height())/2/2+"px");
    	$("#content").css("height",($("#user_info_win").height()-160)+"px");
    	
        $("#user_info_mask").show();
        $("#user_info_win").show();
        $("#ascrail2000").show();
        $("#ascrail2000").css("background-color","#eee");
        $("body").css("overflow","hidden");
    }
}

//网店的添加
function user_shop(index,type) {
    //展示信息填写窗口
    (type === undefined)?user_win():loan_user_win('',type);
    var url = $(index).attr('data-src'),
        user_shop_id = $(index).attr('user_shop_id');
    // $(index).addClass("aft").siblings("span").removeClass("aft");
    (type === undefined)?$('#title').html('修改网店 <i onclick="user_win(\'hide\')">×</i>'):$('#title').html('修改网店 <i onclick="loan_user_win(\'hide\')">×</i>');
    // $("#user_info_mask").show();
    // $("#user_info_win").show();
    // $("#ascrail2000").show();
    // $("body").css("overflow","hidden");
    if(user_shop_id ==''){
        $('#title').html('添加网店 <i onclick="user_win(\'hide\')">×</i>');
        $('input[name=shop_url]').val('');
        $('select[name=business_year]').val('');
        $('input[name=main_category]').val('');
        $('input[name=shop_flow]').val('');
        return false
    }

    // $('#title').html('修改网店 <i onclick="user_win(\'hide\')">×</i>');
    $.post(url,{'user_shop_id':user_shop_id}, function (data) {

        if(data.status == 'success') {
            var data = data.data;
            $('input[name=shop_url]').val(data.shop_url);
            $('input[name=user_shop_id]').val(data.user_shop_id);
            $('select[name=business_year]').val(data.business_year);
            $('input[name=main_category]').val(data.main_category);
            $('input[name=shop_flow]').val(data.shop_flow);
            return false
        }else {
            layer.msg(data.msg);
        }
    }, 'json')
}

//查看投标详情
function bin_info(index) {
    $("#tender_cover").show();
    $("#tender_from").show();
    var url = $(index).attr('data-src'),
        company_admin_tender_id = $(index).attr('company_admin_tender_id');

    $.post(url,{'company_admin_tender_id':company_admin_tender_id}, function (data) {
        if(data.status == 'success') {
            var data = data.data;
            if(data.avatar !=''){
                $('#avater').attr('src',data.avatar);
            }
            $('#company_name').html(data.nick);
            $('#address').html(data.province_name+data.city_name);

            if(data.is_authentication!=2){
                $('#is_compnay').hide();
            }

            if(data.is_real!=2){
                $('#is_real').hide();
            }

            if(data.is_bond!=2){
                $('#is_money').hide();
            }
            $('#bond_money').html(data.bond_money+"元");
            $('#bond_time').html(data.bid_time);
            $('#bond_note').html(data.tender_note);

            $('#pool_bid_status_name').html(data.a_name);

            if(data.business_license_url!=''){
                $('#business_license').attr('src', data.business_license_url);
            }
            if(data.data_url!=false) {
                $('#pool_bid_status_name').attr('class', 'chhBtn');
                $('#pool_bid_status_name').bind('click', function () {
                    layer.confirm('是否确认'+data.a_name+'?', {
                        btn: [data.a_name,'取消'], //按钮
                        icon:3
                    }, function(){
                        bid(data.data_url)

                    }, function(){

                    });

                });
            }
            return false
        }else {
            layer.msg(data.msg);
        }
    }, 'json')
}


