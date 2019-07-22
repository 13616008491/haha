<?php
/**
 * @功能：HTML页面枚举数据
 * @文件：StatusColor.class.php
 * @作者：zc
 * @时间：15-10-22 下午4:16
 */
namespace app\common\enum;

class HtmlEnumValue{

    //定义值颜色模板
    public static $enum_color = array(
        1=>"#32CD32", 2=>"#FF0000", 3=>"#B22222",4=>"#FF7F50",5=>"#DAA520", 6=>"#7CFC00",7=>"#FF4500",8=>"#008080",9=>"#2E8B57",10=>"#0000FF",11=>"#0000CD",
        12=>"#778899",13=>"#FF7F50",14=>"#383838", 15=>"#32CD32", 16=>"#FFF000",17=>"#383838",18=>"#FF4500",19=>"#FF0F00",20=>"#32CD32",
    );

    //定义值颜色列表
    public static $enum_value = array(
        //删除状态【1：未删除、2：已删除】
        "is_delete"=> [IsDelete::No=>"启用",IsDelete::Yes=>"禁用"],
        "is_delete_one"=> [IsDelete::No=>"正常代理",IsDelete::Yes=>"停止代理"],
        //是否为超级管理员【1：普通管理员，2：超级管理员】
        "admin_super"=>array(AdminSuper::Super=>"超级管理员",AdminSuper::Ordinary=>"普通管理员"),
        //性别【1、保密；2、男；3、女】
        "sex"=>array(Sex::secrecy=>'保密',Sex::man=>'男',Sex::woman=>'女'),
        //审核状态【1、申请中；2、审核通过；3、审核失败】
        'verify_status'=>array(VerifyStatus::Apply=>'申请中',VerifyStatus::VerifyOK=>'审核通过',VerifyStatus::VerifyNG=>'审核失败'),
        //审核状态【1、申请中；2、审核通过；3、审核失败】
        'verify_status_temp'=>array(VerifyStatus::VerifyOK=>'审核通过',VerifyStatus::VerifyNG=>'审核失败'),
        //字段类型
        'field_type'=>array(FieldType::Img=>'单图上传',/*FieldType::ImgMulti=>'多图上传',FieldType::File=>'单文件上传',*/FieldType::FileMulti=>'多文件上传'),
        //是否【1、否；2、是】
        'is_yes'=>array(IsYes::No=>'否',IsYes::Yes=>'是'),
        //需求提交状态【1：开放提交，2：禁止提交】
        'propose_status'=>array(ProposeStatus::OPEN=>'开放提交', ProposeStatus::CLOSE=>'禁止提交'),
        //需求状态【1：待确定，2：开发中，3：开发完成，待测试，4：已拒绝，5：测试完成，待上线，6：已上线】
        'demand_status'=>array(DemandStatus::IDENT=>'待确定', DemandStatus::PROCEEDING=>'开发中',DemandStatus::DONE=>'开发完成，待测试',DemandStatus::REFUSE=>'已拒绝',DemandStatus::TEST=>'测试完成，待上线',DemandStatus::UPING=>'需求完成，已上线'),
        'weekly_status'=>array(DemandStatus::PROCEEDING=>'开发中',DemandStatus::DONE=>'开发完成',DemandStatus::REFUSE=>'已拒绝',DemandStatus::TEST=>'测试完成',DemandStatus::UPING=>'需求完成'),
        //优先级【1：一般，2：紧急，3：特急】
         'priority_level'=>array(PriorityLevel::NORMAL=>'一般', PriorityLevel::URGENCY=>'紧急',PriorityLevel::URGENCYEST=>'特急'),
        //优先级【2：同意，4：拒绝】
        'demand_audit'=>array(DemandAudit::AGRESS=>'同意', DemandAudit::REFUSE=>'拒绝'),
        //审核状态【2、测试通过；3、测试不通过】
        'test_audit'=>array(TestAudit::TestOk=>'审核通过',TestAudit::REFUSE=>'审核失败'),
        //进度类型【1、设计、2、开发；3、测试；4、运维；5、需求调研；6、技术调研；7、会议；8、管理；9、其他】
        'task_type'=>array(TaskType::Development=>'开发',TaskType::Design=>'设计',TaskType::Test=>'测试',TaskType::Operation=>'运维',TaskType::Demand=>'需求调研',TaskType::Technology=>'技术调研',TaskType::Meeting=>'会议',TaskType::Manage=>'管理',TaskType::Other=>'其他'),
        //是否有效【1：无；2：有】
        'is_enable'=>array(IsUserEnable::No=>'被禁用',IsUserEnable::Yes=>'有效'),
        //是否阅读信息【1：未读、2：已读】
        'notice_read'=>array(IsRead::No=>'未读',IsRead::Yes=>'已读'),
        //是否通知成功【1：失败、2：成功】
        'notice_state'=>array(NoticeState::No=>'失败',NoticeState::Yes=>'成功'),
        //是否历史【1、最新数据；2、历史数据】
        'is_history'=>array(IsHistory::No=>'否',IsHistory::Yes=>'是'),
    );
}