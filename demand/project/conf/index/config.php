<?php
//应用设置 （login.*    控制器.方法）

return [
    'AUTH_MODULE' => [
        'intention.intention_list', //意向列表
        'intention.intention_small_list', //意向列表
        'intention.intention_loan_list', //意向列表
        'user.*', //用户列表
        'credit.credit_apply', //信用卡申请
        'company.*',  //用户企业信息
        'user_policy.*', //用户保单信息
        'user_assure.*', //用户担保信息
        'user_house.*',  //用户房产信息
        'user_loan.*',  //用户贷款信息
        'user_car.*',  //用户车辆信息
        'user_credit_card.*', //用户信用卡信息
        'user_shop.*', //用户网店信息
        'user_tender.*' //用户招标信息
        ],  //需要权限模块方法  * 代表所有的方法 ['login.reg']

    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 =>  APP_PATH . 'index' . DS . 'view'  . DS . '404.html'
    ],

    // 异常页面的模板文件
    'dispatch_error_tmpl' =>  APP_PATH . 'index' . DS . 'view'  . DS . '404.html'
];


