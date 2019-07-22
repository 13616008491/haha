<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：ICredit.class.php
 * @类功能: 信用卡相关
 * @开发者: cxl
 * @开发时间： 15-10-22
 * @版本：version 1.0
 */
namespace app\admin\ext;

use app\common\cache\user\IUserCache;
use app\common\controller\AdminBaseController;
use app\common\enum\BorrowStatus;
use app\common\enum\IsDelete;
use app\common\enum\IsNewest;
use app\common\ext\IDb;
use app\common\ext\IRequest;
use app\common\ext\IUrl;

class IBorrow extends AdminBaseController {

    /**
     * @功能：借款申请列表
     * @param $borrow_status string 信用卡状态
     * @param $view string 显示模板
     * @开发者：cxl
     * @return string
     */
    public function borrow_list($borrow_status = null,$view = "borrow_apply_list"){
        //接收参数
        $user_id = IRequest::get("user_id");
        $nick = IRequest::get("nick");
        $phone = IRequest::get("phone");
        $repayment_mode = IRequest::get("repayment_mode");
        $sort = IRequest::get('sort');

        //判断是否取得参数值
        if(empty($borrow_status)) $borrow_status = IRequest::get('borrow_status');

        //设置查询条件
        $where = array();
        if(!empty($user_id)) $where['ur.user_id'] = $user_id;
        if(!empty($repayment_mode)) $where['pb.repayment_mode'] = $repayment_mode;
        if(!empty($nick)) $where['ur.nick'] = array("like","%{$nick}%");
        if(!empty($phone)) $where['ur.phone'] = array("like","%{$phone}%");
        if(!empty($borrow_status)) $where['pb.borrow_status'] = $borrow_status;

        //取得排序条件
        $default = "borrow_id desc";
        $sort = $this->sort(
            //排序条件
            $sort,array(
                "borrow_money"=>"borrow_money",
                "interest_money"=>"interest_money",
                "borrow_rate"=>"borrow_rate",
                "borrow_day"=>"borrow_day",
                "apply_time"=>"apply_time",
                "audit_time"=>"audit_time",
                "borrow_time"=>"borrow_time",
                "complete_time"=>"complete_time",
                "default"=> $default
            )
        );

        //取得管理员详情
        $borrow_list = IDb::getInstance('p2p_borrow as pb')
            ->setDbFiled("pb.*,ur.user_id,ur.nick,ur.phone")
            ->setDbJoin("user as ur","ur.user_id=pb.user_id")
            ->setDbWhere($where)
            ->setDbOrder($sort)
            ->pag();

        //页面赋值
        $this->assign("user_id",$user_id);
        $this->assign("nick",$nick);
        $this->assign("phone",$phone);
        $this->assign("borrow_status",$borrow_status);
        $this->assign("repayment_mode",$repayment_mode);
        $this->assign("borrow_list",$borrow_list);

        //渲染模板输出
        return view($view);
    }

    /**
     * @功能：借款详情
     * @开发者：cxl
     * @return string
     */
    public function borrow_info(){
        //接收参数
        $borrow_id = IRequest::get("borrow_id",IRequest::NOT_EMPTY,"借款编号不能为空！");

        //取得借款信息
        $borrow_where['borrow_id'] = $borrow_id;
        $borrow_info = IDb::getInstance('p2p_borrow')->setDbWhere($borrow_where)->row();
        if(empty($borrow_info)){
            error("取得借款信息失败！");
        }

        //取得用户信息
        $user_info = IUserCache::getInfo($borrow_info['user_id']);
        if(empty($user_info)){
            error("取得借款用户信息失败！");
        }

        //判断是否放款
        $repayment_list = array();
        if(in_array($borrow_info['borrow_status'],array(BorrowStatus::BorrowOk,BorrowStatus::RepaymentSub,BorrowStatus::RepaymentOk,BorrowStatus::Overdue,BorrowStatus::OverdueOk))){
            //取得还款记录
            $repayment_where['borrow_id'] = $borrow_id;
            $repayment_list = IDb::getInstance('p2p_repayment')->setDbWhere($repayment_where)->sel();
            if(empty($repayment_list)){
                error("取得借款信息失败！");
            }
        }

        //取得借款用户资料
        $apply_where['user_id'] = $borrow_info['user_id'];
        $apply_list = IDb::getInstance('p2p_apply as pa')
            ->setDbFiled("pa.*,ca1.acronym as lodging_province,ca2.acronym as lodging_city,ca3.acronym as lodging_area,ca4.acronym as work_province,ca5.acronym as work_city,ca6.acronym as work_area")
            ->setDbJoin("com_area as ca1","pa.lodging_province=ca1.area_id","left")
            ->setDbJoin("com_area as ca2","pa.lodging_city=ca2.area_id","left")
            ->setDbJoin("com_area as ca3","pa.lodging_area=ca3.area_id","left")
            ->setDbJoin("com_area as ca4","pa.work_province=ca4.area_id","left")
            ->setDbJoin("com_area as ca5","pa.work_city=ca5.area_id","left")
            ->setDbJoin("com_area as ca6","pa.work_area=ca6.area_id","left")
            ->setDbWhere($apply_where)
            ->setDbOrder("apply_id desc")
            ->sel();

        //判断数据是否存在
        if(empty($apply_list)){
            error("取得借款用户资料申请资料失败！");
        }
        
        //取得借款用户资料
        $borrow_log_where['borrow_id'] = $borrow_id;
        $borrow_log_list = IDb::getInstance('p2p_borrow_log as bl')
            ->setDbFiled("bl.borrow_log_id,bl.operate_note,bl.operate_time,ur.user_id,ur.real,ur.phone,ad.admin_user,ad.real_name")
            ->setDbJoin("user as ur","ur.user_id=bl.user_id","left")
            ->setDbJoin("admin as ad","ad.admin_id=bl.admin_id","left")
            ->setDbWhere($borrow_log_where)
            ->sel();

        //页面赋值
        $this->assign("borrow_info",$borrow_info);
        $this->assign("user_info",$user_info);
        $this->assign("apply_list",$apply_list);
        $this->assign("repayment_list",$repayment_list);
        $this->assign("borrow_log_list",$borrow_log_list);

        //渲染模板输出
        return view("borrow_info");
    }
    /**
     * @功能：申请资料审核
     * @开发者：cxl
     * @return string
     */
    public function apply_audit(){
        //接收参数
        $apply_id = IRequest::get("apply_id",IRequest::NOT_EMPTY,"借款申请资料编号不能为空！");

        //取得借款用户资料
        $apply_where['apply_id'] = $apply_id;
        $apply_info = IDb::getInstance('p2p_apply as pa')
            ->setDbFiled("pa.*,ca1.acronym as lodging_province,ca2.acronym as lodging_city,ca3.acronym as lodging_area,ca4.acronym as work_province,
            ca5.acronym as work_city,ca6.acronym as work_area,rt1.relation_type_name as relation_type_one,rt2.relation_type_name as relation_type_two")
            ->setDbJoin("com_area as ca1","pa.lodging_province=ca1.area_id","left")
            ->setDbJoin("com_area as ca2","pa.lodging_city=ca2.area_id","left")
            ->setDbJoin("com_area as ca3","pa.lodging_area=ca3.area_id","left")
            ->setDbJoin("com_area as ca4","pa.work_province=ca4.area_id","left")
            ->setDbJoin("com_area as ca5","pa.work_city=ca5.area_id","left")
            ->setDbJoin("com_area as ca6","pa.work_area=ca6.area_id","left")
            ->setDbJoin("p2p_relation_type as rt1","pa.relation_type_one=rt1.relation_type_id","left")
            ->setDbJoin("p2p_relation_type as rt2","pa.relation_type_two=rt2.relation_type_id","left")
            ->setDbWhere($apply_where)
            ->setDbOrder("apply_id desc")
            ->row();

        //判断数据是否存在
        if(empty($apply_info)){
            error("取得借款用户申请资料失败！");
        }

        //页面赋值
        $this->assign("apply_id",$apply_id);
        $this->assign("apply_info",$apply_info);

        //渲染模板输出
        return view("apply_audit");
    }

    /**
     * @功能：借款审核
     * @开发者：cxl
     * @return string
     */
    public function borrow_audit(){
        //接收参数
        $borrow_id = IRequest::get("borrow_id",IRequest::NOT_EMPTY,"借款编号不能为空！");

        //取得借款信息
        $borrow_where['borrow_id'] = $borrow_id;
        $borrow_info = IDb::getInstance('p2p_borrow')->setDbWhere($borrow_where)->row();
        if(empty($borrow_info)){
            error("取得借款信息失败！");
        }

        //取得用户信息
        $user_info = IUserCache::getInfo($borrow_info['user_id']);
        if(empty($user_info)){
            error("取得借款用户信息失败！");
        }

        //取得借款用户资料
        $apply_where['user_id'] = $borrow_info['user_id'];
        $apply_list = IDb::getInstance('p2p_apply as pa')
            ->setDbFiled("pa.*,ca1.acronym as lodging_province,ca2.acronym as lodging_city,ca3.acrony   m as lodging_area,ca4.acronym as work_province,ca5.acronym as work_city,ca6.acronym as work_area")
            ->setDbJoin("com_area as ca1","pa.lodging_province=ca1.area_id","left")
            ->setDbJoin("com_area as ca2","pa.lodging_city=ca2.area_id","left")
            ->setDbJoin("com_area as ca3","pa.lodging_area=ca3.area_id","left")
            ->setDbJoin("com_area as ca4","pa.work_province=ca4.area_id","left")
            ->setDbJoin("com_area as ca5","pa.work_city=ca5.area_id","left")
            ->setDbJoin("com_area as ca6","pa.work_area=ca6.area_id","left")
            ->setDbWhere($apply_where)
            ->setDbOrder("apply_id desc")
            ->sel();

        //判断数据是否存在
        if(empty($apply_list)){
            error("取得借款用户资料申请资料失败！");
        }

        //取得借款用户资料
        $borrow_log_where['borrow_id'] = $borrow_id;
        $borrow_log_list = IDb::getInstance('p2p_borrow_log as bl')
            ->setDbFiled("bl.borrow_log_id,bl.operate_note,bl.operate_time,ur.user_id,ur.real,ur.phone,ad.admin_user,ad.real_name")
            ->setDbJoin("user as ur","ur.user_id=bl.user_id","left")
            ->setDbJoin("admin as ad","ad.admin_id=bl.admin_id","left")
            ->setDbWhere($borrow_log_where)
            ->sel();

        //页面赋值
        $this->assign("borrow_id",$borrow_id);
        $this->assign("borrow_info",$borrow_info);
        $this->assign("user_info",$user_info);
        $this->assign("apply_list",$apply_list);
        $this->assign("borrow_log_list",$borrow_log_list);

        //渲染模板输出
        return view("borrow_audit");
    }

    /**
     * @功能：还款计划列表
     * @param $repayment_status string 还款状态
     * @param $view string 显示模板
     * @param $expiration_time_start string 到期开始时间
     * @param $expiration_time_end string 到期结束时间
     * @开发者：cxl
     * @return string
     */
    public function repayment_list($repayment_status = null,$view = "repayment_list",$expiration_time_start=null,$expiration_time_end=null){
        //接收参数
        $user_id = IRequest::get("user_id");
        $nick = IRequest::get("nick");
        $phone = IRequest::get("phone");
        $real = IRequest::get("real");
        $borrow_id = IRequest::get("borrow_id");
        $repayment_mode = IRequest::get("repayment_mode");
        $sort = IRequest::get('sort');

        //判断是否取得参数值
        if(empty($repayment_status)) $repayment_status = IRequest::get('repayment_status');
        if(empty($expiration_time_start)) $expiration_time_start = IRequest::get('expiration_time_start');
        if(empty($expiration_time_end)) $expiration_time_end = IRequest::get('expiration_time_end');

        //设置查询条件
        $where = array();
        if(!empty($user_id)) $where['ur.user_id'] = $user_id;
        if(!empty($nick)) $where['ur.nick'] = array("like","%{$nick}%");
        if(!empty($phone)) $where['ur.phone'] = array("like","%{$phone}%");
        if(!empty($real)) $where['ur.real'] = array("like","%{$real}%");
        if(!empty($borrow_id)) $where['rt.borrow_id'] = $borrow_id;
        if(!empty($repayment_mode)) $where['bw.repayment_mode'] = $repayment_mode;
        if(!empty($repayment_status)) $where['rt.repayment_status'] = array("in",$repayment_status);
        if(!empty($expiration_time_start) && !empty($expiration_time_end)) $where["rt.expiration_time"] = array("between", array($expiration_time_start, $expiration_time_end));
        if(!empty($expiration_time_start) && empty($expiration_time_end)) $where["rt.expiration_time"] = array("egt", $expiration_time_start);
        if(empty($expiration_time_start) && !empty($expiration_time_end)) $where["rt.expiration_time"] = array("elt", $expiration_time_end);

        //取得排序条件
        $default = "rt.borrow_id desc,rt.repayment_sub";
        $sort = $this->sort(
            //排序条件
            $sort,array(
                "total_borrow_money"=>"bw.borrow_money",
                "repayment_money"=>"rt.repayment_money",
                "principal_money"=>"rt.principal_money",
                "interest_money"=>"rt.interest_money",
                "overdue_day"=>"rt.overdue_day",
                "overdue_interest"=>"rt.overdue_interest",
                "default"=> $default
            )
        );

        //取得管理员详情
        $repayment_list = IDb::getInstance('p2p_repayment as rt')
            ->setDbFiled("rt.*,ur.nick,ur.phone,ur.real,bw.repayment_mode,bw.borrow_money as total_borrow_money,bw.interest_money as total_interest_money")
            ->setDbJoin("user as ur","rt.user_id=ur.user_id")
            ->setDbJoin("p2p_borrow as bw","rt.borrow_id=bw.borrow_id")
            ->setDbWhere($where)
            ->setDbOrder($sort)
            ->pag();

        //页面赋值
        $this->assign("user_id",$user_id);
        $this->assign("nick",$nick);
        $this->assign("phone",$phone);
        $this->assign("real",$real);
        $this->assign("borrow_id",$borrow_id);
        $this->assign("repayment_status",$repayment_status);
        $this->assign("repayment_mode",$repayment_mode);
        $this->assign("expiration_time_start",$expiration_time_start);
        $this->assign("expiration_time_end",$expiration_time_end);
        $this->assign("repayment_list",$repayment_list);

        //渲染模板输出
        return view($view);
    }

    /**
     * @功能：还款计划列表详情
     * @开发者：cxl
     * @return string
     */
    public function repayment_info(){
        //接收参数
        $repayment_id = IRequest::get("repayment_id",IRequest::NOT_EMPTY,"还款单编号不能为空！");

        //取得管理员详情
        $where['repayment_id'] = $repayment_id;
        $repayment_info = IDb::getInstance('p2p_repayment as rt')
            ->setDbFiled("rt.*,ur.nick,ur.phone,ur.real,bw.repayment_mode,bw.borrow_money as total_borrow_money,bw.interest_money as total_interest_money")
            ->setDbJoin("user as ur","rt.user_id=ur.user_id")
            ->setDbJoin("p2p_borrow as bw","rt.borrow_id=bw.borrow_id")
            ->setDbWhere($where)
            ->row();

        //页面赋值
        $this->assign("repayment_info",$repayment_info);

        //渲染模板输出
        return view("repayment_info");
    }
}
