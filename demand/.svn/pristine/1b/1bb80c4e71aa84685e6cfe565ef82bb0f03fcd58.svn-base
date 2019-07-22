<?php
/*
* @开发工具: JetBrains PhpStorm.
* @文件名：BaseCache.class.php
* @类功能: 缓存基类
* @开发者: 陈旭林
* @开发时间： 14-10-28
* @版本：version 1.0
*/
namespace app\common\cache;

class BaseCache{
    //需求收集
    const  DM_PROJECT_LIST = "DM_PROJECT_LIST"; //项目列表
    const  DM_DEMAND_LIST = "DM_DEMAND_LIST"; //项目需求列表
    const  DM_MY_DEMAND_LIST = "DM_MY_DEMAND_LIST"; //我提出的需求列表
    const  DM_DEMAND_SEARCH_LIST = "DM_DEMAND_SEARCH_LIST"; //搜索列表
    //APP统计信息
    const APP_HOME_TOTAL = "APP_HOME_TOTAL"; //贷款通首页统计信息
    const APP_HOME_APPLY = "APP_HOME_APPLY"; //贷款通首页申请列表
    const APP_ADMIN_USER_LIST = "APP_ADMIN_USER_LIST"; //管理后台用户一个月趋势统计信息
    const APP_ADMIN_USER_NUM = "APP_ADMIN_USER_NUM"; //管理后台用户数量统计信息
    const APP_ADMIN_USER_AREA_NUM = "APP_ADMIN_USER_AREA_NUM"; //管理后台用户数量统计信息
    const APP_ADMIN_COMPANY_LIST = "APP_ADMIN_COMPANY_LIST"; //管理后台用户一个月趋势统计信息
    const APP_ADMIN_COMPANY_NUM = "APP_ADMIN_COMPANY_NUM"; //管理后台用户数量统计信息
    const APP_ADMIN_PAY_LIST = "APP_ADMIN_PAY_LIST"; //管理后台用户一个月趋势统计信息
    const APP_ADMIN_PAY_NUM = "APP_ADMIN_PAY_NUM"; //管理后台用户数量统计信息
    const APP_ADMIN_PAY_AREA_NUM = "APP_ADMIN_PAY_AREA_NUM"; //管理后台用户数量统计信息

    //总管理后台数据缓存
    const CD_ADMIN_INFO = "CD_ADMIN_INFO"; //系统管理员详情
    const CD_ADMIN_ROLE_INFO = "CD_ADMIN_ROLE_INFO"; //系统管理员角色详情
    const CD_ADMIN_ROLE_MENU_INFO = "CD_ADMIN_ROLE_MENU_INFO"; //系统管理员角色菜单详情
    const CD_ADMIN_AUTHORITY_MODEL = "CD_ADMIN_AUTHORITY_MODEL";//系统菜单
    const CD_ADMIN_AUTHORITY_MENU = "CD_ADMIN_AUTHORITY_MENU";//系统菜单

    const CD_USER_INFO = "CD_USER_INFO";//用户信息

    const CD_CHAT_CLIENT_ID = "CD_CHAT_CLIENT_ID";//聊天 client_id=>user_id
    const CD_CHAT_USER_ID = "CD_CHAT_USER_ID";//聊天 user_id=>client_id
    const CD_WE_CHAT_ACCESS_TOKEN = "CD_WE_CHAT_ACCESS_TOKEN";//微信接口access token
    const CD_MINI_TOKEN = "CD_MINI_TOKEN";//小程序登录token


    const CD_CARD_INFO = "CD_CARD_INFO"; //名片信息
    const CD_CARD_CUSTOMER_INFO = "CD_CARD_CUSTOMER_INFO"; //名片客户关系信息
    const CD_CARD_STYLE_INFO = "CD_CARD_STYLE_INFO"; //名片模板信息

    const CD_GOODS_LIST = "CD_GOODS_LIST"; //商品列表
    const CD_GOODS_INFO = "CD_GOODS_INFO"; //商品详情

    const CD_COM_AUTHORITY_MODEL = 'CD_COM_AUTHORITY_MODEL'; //系统菜单
    const CD_COM_AUTHORITY_MENU = 'CD_COM_AUTHORITY_MENU';
    const CD_COM_INFO = 'CD_COM_INFO'; //公司详情
    const CD_ARTICLE_LIST = 'CD_ARTICLE_LIST'; //文章资讯列表
    const CD_ARTICLE_INFO =  "CD_ARTICLE_INFO";//文章资讯详情
    const CD_USER_TOTAL =  "CD_USER_TOTAL";//首页客户统计
    const CD_CARD_OPERATION_TOTAL =  "CD_CARD_OPERATION_TOTAL";//首页名片操作记录统计
    const CD_DATA_USER_TOTAL =  "CD_DATA_USER_TOTAL";//首页每天客户增加数量统计
    const CD_ZB_USER_TOTAL =  "CD_ZB_USER_TOTAL";//首页客户占比统计


    const CD_SENSITIVE_WORD =  "CD_SENSITIVE_WORD";//敏感词

    //共通数据缓存
    const ZZ_AREA_INFO = "ZZ_AREA_INFO"; //行政区域详情
    const ZZ_AREA_LIST = "ZZ_AREA_LIST"; //下层行政区域列表
    const ZZ_AREA_ALL_LIST = "ZZ_AREA_ALL_LIST";//行政区域列表
    const ZZ_AREA_LOCATION_LIST = "ZZ_AREA_LOCATION_LIST"; //定位行政区域列表
    const ZZ_AREA_LOCATION_INDEX_LIST = "ZZ_AREA_LOCATION_INDEX_LIST"; //定位行政区域列表
    const ZZ_PROTOCOL_INFO = "ZZ_PROTOCOL_INFO";//用户协议详情
    const ZZ_AD_LOCATION_INFO = "ZZ_AD_LOCATION_INFO";//广告位详情
    const ZZ_AD_LOCATION_LIST = "ZZ_AD_LOCATION_LIST";//广告位列表
    const ZZ_AD_LIST = "ZZ_AD_LIST";//广告列表
    const ZZ_CONFIG_INFO = "ZZ_CONFIG_INFO";//配置信息
    const ZZ_CONFIG_LIST = "ZZ_CONFIG_LIST";//配置信息列表
    const ZZ_ANNOUNCEMENT_LIST = "ZZ_ANNOUNCEMENT_LIST";//系统公告列表
    const ZZ_NAVIGATION_LIST = "ZZ_NAVIGATION_LIST";//首页导航菜单列表
    const ZZ_SPECIAL_LIST = "ZZ_SPECIAL_LIST";//专题列表

    //代理商数据缓存
    const AGENT_INFO = "AGENT_INFO";//代理商信息
    const AGENT_ADMIN_INFO = "AGENT_ADMIN_INFO";//代理商业务员信息
    const AGENT_ADMIN_LIST = "AGENT_ADMIN_LIST";//代理商业务员列表
    const AGENT_PACKAGE_INFO = "AGENT_PACKAGE_INFO";//代理商套餐信息
    const AGENT_PACKAGE_LIST = "AGENT_PACKAGE_LIST";//代理商套餐列表
    const AGENT_LEVEL_INFO = "AGENT_LEVEL_INFO";//代理商等级信息
    const AGENT_LEVEL_LIST = "AGENT_LEVEL_LIST";//代理商等级列表
    const AGENT_ADMIN_ROLE_INFO = "AGENT_ADMIN_ROLE_INFO"; //系统管理员角色详情
    const AGENT_ADMIN_ROLE_MENU_INFO = "AGENT_ADMIN_ROLE_MENU_INFO"; //系统管理员角色菜单详情
    const AGENT_ADMIN_AUTHORITY_MODEL = "AGENT_ADMIN_AUTHORITY_MODEL";//系统菜单
    const AGENT_ADMIN_AUTHORITY_MENU = "AGENT_ADMIN_AUTHORITY_MENU";//系统菜单
    const AGENT_USER_TOTAL = 'AGENT_USER_TOTAL'; //握旗用户录入统计
    const AGENT_APPLY_TOTAL = 'AGENT_APPLY_TOTAL'; //工单统计
    const AGENT_BROKERAGE_TOTAL = 'AGENT_BROKERAGE_TOTAL'; //业绩统计
    const AGENT_BROKERAGE_ALL = 'AGENT_BROKERAGE_ALL'; //总业绩统计

    //信贷公司数据缓存
    const COMPANY_INFO = "COMPANY_INFO";//信贷公司信息
    const COMPANY_LIST = "COMPANY_LIST";//信贷公司信息
    const COMPANY_ADMIN_INFO = "COMPANY_ADMIN_INFO";//信贷公司业务员信息
    const COMPANY_ADMIN_LIST = "COMPANY_ADMIN_LIST";//信贷公司业务员列表
    const COMPANY_ADMIN_RECOMMEND_LIST = "COMPANY_ADMIN_RECOMMEND_LIST";//信贷经理推荐列表
    const COMPANY_PACKAGE_INFO = "COMPANY_PACKAGE_INFO";//信贷公司套餐信息
    const COMPANY_PACKAGE_LIST = "COMPANY_PACKAGE_LIST";//信贷公司套餐列表
    const COMPANY_PACKAGE_AD_INFO = "COMPANY_PACKAGE_AD_INFO";//信贷公司套餐信息
    const COMPANY_PACKAGE_AD_LIST = "COMPANY_PACKAGE_AD_LIST";//信贷公司套餐列表
    const COMPANY_LEVEL_INFO = "COMPANY_LEVEL_INFO";//信贷公司等级信息
    const COMPANY_LEVEL_LIST = "COMPANY_LEVEL_LIST";//信贷公司等级列表
    const COMPANY_BOND_INFO = "COMPANY_BOND_INFO";//招标宝套餐详情
    const COMPANY_BOND_LIST = "COMPANY_BOND_LIST";//招标宝套餐列表
    const COMPANY_BOND_PACKAGE_INFO = "COMPANY_BOND_PACKAGE_INFO";//信贷公司招标宝套餐信息
    const COMPANY_BOND_PACKAGE_LIST = "COMPANY_BOND_PACKAGE_LIST";//信贷公司招标宝套餐列表
    const COMPANY_BOND_RECHARGE_INFO = "COMPANY_BOND_RECHARGE_INFO";//信贷公司招标宝保证金充值套餐信息
    const COMPANY_BOND_RECHARGE_LIST = "COMPANY_BOND_RECHARGE_LIST";//信贷公司招标宝保证金充值套餐列表

    //政府资讯信息缓存
    const ZZ_ARTICLE_INFO =  "ZZ_ARTICLE_INFO";//政府资讯信息缓存
    const ZZ_ARTICLE_LIST =  "ZZ_ARTICLE_LIST";//政府资讯信息列表
    const ZZ_ARTICLE_PUSH_LIST =  "ZZ_ARTICLE_PUSH_LIST";//政府资讯信息列表
    const ZZ_ARTICLE_LIST_PAGE =  "ZZ_ARTICLE_LIST_PAGE";//政府资讯列表分页
    const ZZ_ARTICLE_HEAD_LIST =  "ZZ_ARTICLE_HEAD_LIST";//政府资讯头部信息列表
    const ZZ_ARTICLE_RECOMMEND_INFO =  "ZZ_ARTICLE_RECOMMEND_INFO";//政府资讯推荐信息缓存
    const ZZ_ARTICLE_RECOMMEND_LIST =  "ZZ_ARTICLE_RECOMMEND_LIST";//政府资讯推荐信息列表
    const ZZ_ARTICLE_CATEGORY_INFO =  "ZZ_ARTICLE_CATEGORY_INFO";//政府资讯分类信息缓存
    const ZZ_ARTICLE_CATEGORY_CODE =  "ZZ_ARTICLE_CATEGORY_CODE";//政府资讯分类信息缓存
    const ZZ_ARTICLE_CATEGORY_LIST =  "ZZ_ARTICLE_CATEGORY_LIST";//政府资讯分类信息列表
    const ZZ_ARTICLE_SEARCH_LIST =  "ZZ_ARTICLE_SEARCH_LIST";//政府资讯搜索列表
    const ZZ_ARTICLE_TAG_INFO =  "ZZ_ARTICLE_TAG_INFO";//政府资讯信息标签详情缓存
    const ZZ_ARTICLE_TAG_LIST =  "ZZ_ARTICLE_TAG_LIST";//政府资讯信息标签列表缓存

    //店铺信息缓存列表
    const ZZ_SHOP_INFO =  "ZZ_SHOP_INFO";//店铺信息缓存
    const ZZ_SHOP_CATEGORY_INFO =  "ZZ_SHOP_CATEGORY_INFO";//店铺分类信息缓存
    const ZZ_SHOP_CATEGORY_LIST =  "ZZ_SHOP_CATEGORY_LIST";//店铺分类信息列表
    const ZZ_GOODS_INFO =  "ZZ_GOODS_INFO";//商品详情
    const ZZ_GOODS_LIST =  "ZZ_GOODS_LIST";//商品信息列表
    const ZZ_SHOP_GOODS_LIST =  "ZZ_SHOP_GOODS_LIST";//店铺商品信息列表
    const ZZ_SHOP_SEARCH_HOT_LIST =  "ZZ_SHOP_SEARCH_HOT_LIST";//店铺热门搜索列表
    const ZZ_SHOP_SEARCH_LIST =  "ZZ_SHOP_SEARCH_LIST";//店铺搜索列表
    const ZZ_SHOP_GOODS_RECOMMEND_LIST =  "ZZ_SHOP_GOODS_RECOMMEND_LIST";//店铺商品推荐信息列表

    //高新申报项目信息缓存
    const ZZ_DECLARE_DISCOUNT_TYPE_INFO =  "ZZ_DECLARE_DISCOUNT_TYPE_INFO";//高新申报项目扶持类型详情缓存
    const ZZ_DECLARE_DISCOUNT_TYPE_LIST =  "ZZ_DECLARE_DISCOUNT_TYPE_LIST";//高新申报项目扶持类型列表缓存
    const ZZ_DECLARE_MATERIAL_TYPE_INFO =  "ZZ_DECLARE_MATERIAL_TYPE_INFO";//高新申报项目材料类型详情缓存
    const ZZ_DECLARE_MATERIAL_TYPE_LIST =  "ZZ_DECLARE_MATERIAL_TYPE_LIST";//高新申报项目材料类型列表缓存
    const ZZ_DECLARE_CONDITION_INFO =  "ZZ_DECLARE_CONDITION_INFO";//高新申报项目属性详情缓存
    const ZZ_DECLARE_CONDITION_LIST =  "ZZ_DECLARE_CONDITION_LIST";//高新申报项目属性列表缓存
    const ZZ_DECLARE_CONDITION_SEARCH_LIST =  "ZZ_DECLARE_CONDITION_SEARCH_LIST";//高新申报项目属性搜索列表缓存
    const ZZ_DECLARE_CONDITION_VALUE_INFO =  "ZZ_DECLARE_CONDITION_VALUE_INFO";//高新申报项目属性值详情缓存
    const ZZ_DECLARE_CONDITION_VALUE_LIST =  "ZZ_DECLARE_CONDITION_VALUE_LIST";//高新申报项目属性值列表缓存
    const ZZ_DECLARE_RECOMMEND_LIST =  "ZZ_DECLARE_RECOMMEND_LIST";//高新申报项目推荐缓存
    const ZZ_DECLARE_TAG_INFO =  "ZZ_DECLARE_TAG_INFO";//高新申报项目标签详情缓存
    const ZZ_DECLARE_TAG_LIST =  "ZZ_DECLARE_CONDITION_VALUE_LIST";//高新申报项目标签列表缓存

    //车辆相关信息缓存
    const CAR_BRAND_INFO = "CAR_BRAND_INFO";//车辆品牌信息缓存
    const CAR_BRAND_LIST = "CAR_BRAND_LIST";//车辆品牌列表缓存
    const CAR_MODEL_INFO = "CAR_MODEL_INFO";//车辆型号信息缓存
    const CAR_MODEL_LIST = "CAR_MODEL_LIST";//车辆型号列表缓存
    const CAR_CONFIG_INFO = "CAR_CONFIG_INFO";//车辆配置信息缓存
    const CAR_CONFIG_LIST = "CAR_CONFIG_LIST";//车辆配置列表缓存

    //启动页图片缓存
    const START_PHOTO_INFO = "START_PHOTO_INFO";//启动页面信息缓存
    const START_PHOTO_LIST = "START_PHOTO_LIST";//启动页面列表缓存

    //信用卡相关缓存
    const CREDIT_TAG_LIST = "CREDIT_TAG_LIST";//行用卡标签列表缓存
    const CREDIT_TAG_INFO = "CREDIT_TAG_INFO";//信用卡标签信息缓存
    const CREDIT_RECOMMEND_LIST = "CREDIT_RECOMMEND_LIST";//行用卡推荐列表缓存
    const CREDIT_FAVORITE_LIST = "CREDIT_FAVORITE_LIST";//信用卡收藏列表缓存
    const CREDIT_SEARCH_LIST = "CREDIT_SEARCH_LIST";//信用卡搜索列表缓存
    const CREDIT_ORDER_LIST = "CREDIT_ORDER_LIST";//信用卡搜索列表缓存
    const CREDIT_PC_SEARCH_LIST = "CREDIT_PC_SEARCH_LIST";//信用卡pc搜索列表缓存
    const CREDIT_INFO = "CREDIT_INFO";//信用卡详情缓存

    //银行贷相关缓存
    const LOAN_INFO = "LOAN_INFO";//银行贷信息缓存
    const LOAN_SEARCH_LIST = "LOAN_SEARCH_LIST";//银行贷信息缓存
    const LOAN_PC_SEARCH_LIST = "LOAN_PC_SEARCH_LIST";//银行贷信息缓存
    const LOAN_AGENCY_INFO = "LOAN_AGENCY_INFO";//银行贷机构信息缓存
    const LOAN_AGENCY_LIST = "LOAN_AGENCY_LIST";//银行贷机构信息缓存
    const LOAN_TAG_INFO = "LOAN_TAG_INFO";//银行贷标签信息缓存
    const LOAN_TAG_LIST = "LOAN_TAG_LIST";//银行贷标签列表缓存
    const LOAN_RECOMMEND_LIST = "LOAN_RECOMMEND_LIST";//银行贷推荐列表缓存
    const LOAN_FAVORITE_LIST = "LOAN_FAVORITE_LIST";//银行贷收藏列表缓存

    //小贷相关缓存
    const SMALL_AGENCY_INFO = "SMALL_AGENCY_INFO";//小贷机构信息缓存
    const SMALL_AGENCY_LIST = "SMALL_AGENCY_LIST";//小贷机构信息缓存
    const SMALL_TAG_INFO = "SMALL_TAG_INFO";//小贷标签信息缓存
    const SMALL_TAG_LIST = "SMALL_TAG_LIST";//小贷标签列表缓存
    const SMALL_RECOMMEND_LIST = "SMALL_RECOMMEND_LIST";//小贷推荐列表缓存
    const SMALL_FAVORITE_LIST = "SMALL_FAVORITE_LIST";//小贷收藏列表缓存
    const SMALL_INFO = "SMALL_INFO";//小贷信息缓存
    const SMALL_SEARCH_LIST = "SMALL_SEARCH_LIST";//小贷信息缓存

    //用户信息缓存
    const ZZ_CUSTOMER_INFO = "ZZ_CUSTOMER_INFO"; //客户详细信息

    //商标国际分类
    const ZZ_CLASSIFICATION_INFO = "ZZ_CLASSIFICATION_INFO"; //商标国际分类详细信息
    const ZZ_CLASSIFICATION_LIST = "ZZ_CLASSIFICATION_LIST"; //商标国际分类列表

    //专利类型
    const ZZ_PATENT_LIST = "ZZ_PATENT_LIST"; //专利类型列表

    //任务缓存
    const ZZ_TASK_STATUS_INFO = "ZZ_TASK_STATUS_INFO"; //任务运行状态
    const ZZ_TASK_STATUS_LIST = "ZZ_TASK_STATUS_LIST"; //任务列表运行状态
    const ZZ_TASK_STATUS_LOGS = "ZZ_TASK_STATUS_LOGS"; //任务日志

    //拼接key前缀
    public static function getKey($key){
        return config('redis.prefix').$key;
    }
}
