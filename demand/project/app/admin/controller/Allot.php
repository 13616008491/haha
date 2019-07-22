<?php
/*
 * @开发工具: JetBrains PhpStorm.
 * @文件名：SystemController.class.php
 * @类功能: 需求管理
 * @开发者: cxl
 * @开发时间： 15-10-22
 * @版本：version 1.0
 */
namespace app\admin\controller;

use app\common\controller\AdminBaseController;
use app\common\enum\DemandStatus;
use app\common\enum\IsDelete;
use app\common\enum\IsRead;
use app\common\enum\PriorityLevel;
use app\common\enum\ProposeStatus;
use app\common\ext\IDb;
use app\common\ext\IRequest;
use app\common\ext\IWeChat;
use app\common\enum\HtmlEnumValue;
use app\common\enum\WeChatInformType;

class Allot extends AdminBaseController {
    /**
     * @功能：我的需求列表
     * @开发者：szg
     */
    public function demand_propose_list(){
        //接收参数
        $demand_id = IRequest::get('demand_id');
        $project_id = IRequest::get('project_id');
        $demand_describe = IRequest::get('demand_describe');
        $demand_status = IRequest::get('demand_status');
        $priority_level = IRequest::get('priority_level');

        //取得登录管理员编号
        $admin_id = get_login_user_id();

        //取得项目记录
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_list = IDb::getInstance('project')->setDbFiled("project_id,project_name")->setDbWhere($project_where)->sel();
        if ($project_list === false) {
            error('取得项目列表失败！');
        }

        //设置查询条件
        $demand_where['pt.propose_status'] = ProposeStatus::OPEN;
        $demand_where['dd.user_id'] = $admin_id;
//        $demand_where['dd.demand_status'] = DemandStatus::IDENT;
        if (!empty($demand_id)) $demand_where['dd.demand_id'] = $demand_id;
        if (!empty($demand_describe))$demand_where['dd.demand_describe'] = array('like', "%{$demand_describe}%");
        if (!empty($project_id)) $demand_where['dd.project_id'] = $project_id;
        if (!empty($demand_status)) $demand_where['dd.demand_status'] = $demand_status;
        if (!empty($priority_level)) $demand_where['dd.priority_level'] = $priority_level;

        //取得数据
        $demand_list = IDb::getInstance('demand as dd')
            ->setDbFiled("dd.*,pt.project_name")
            ->setDbJoin("project as pt", "dd.project_id=pt.project_id")
            ->setDbWhere($demand_where)
            ->setDbOrder('dd.demand_id desc')
            ->pag();

        //判断数据是否正常
        if ($demand_list === false) {
            error('获取需求列表有误！');
        }

        //页面赋值
        $this->assign('project_list', $project_list);
        $this->assign('demand_list', $demand_list);
        $this->assign('demand_id', $demand_id);
        $this->assign('project_id', $project_id);
        $this->assign('demand_describe', $demand_describe);
        $this->assign('demand_status', $demand_status);
        $this->assign('priority_level', $priority_level);

        //页面显示
        return view();
    }

    /**
     * @功能：添加需求
     * @开发者：szg
     */
    public function demand_add(){
        //查询条件
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_list = IDb::getInstance('project')->setDbWhere($project_where)->setDbOrder('project_id desc')->sel();
        if($project_list === false){
            error("获取项目列表错误！");
        }

        //模板渲染
        $this->assign('project_list',$project_list);

        //渲染模板输出
        return view();
    }

    /**
     * @功能：需求保存
     * 开发者：szg
     */
    public function demand_add_submit(){
        //接收参数
        $project_id = IRequest::get("project_id",IRequest::NOT_EMPTY,"请选择项目！");
        $demand_describe= IRequest::get("demand_describe",IRequest::NOT_EMPTY,"需求描述不能为空！");
        $demand_photo_url= IRequest::get("demand_photo_url");
        $priority_level= IRequest::get("priority_level",IRequest::NOT_EMPTY,"请选择优先级！");
        $remark = IRequest::get("remark");

        //判断等级
        $priority_describe = null;
        if($priority_level == PriorityLevel::URGENCYEST){
            $priority_describe = IRequest::get("priority_describe",IRequest::NOT_EMPTY,"特急说明不能为空！");
        }

        //取得项目信息
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_where['project_id'] = $project_id;
        $project_info = IDb::getInstance("project")->setDbFiled("charge_user_id,project_name")->setDbWhere($project_where)->row();
        if(empty($project_info)){
            error("取得项目信息失败，项目已被删除或关闭！");
        }

        //取得用户信息
        $user_id = get_login_user_id();

        //开启实物处理
        IDb::dbStartTrans();

        //新增保存
        $demand_data['user_id'] = $user_id;
        $demand_data['project_id'] = $project_id;
        $demand_data['demand_describe'] = $demand_describe;
        $demand_data['demand_photo_list'] = implode(",",$demand_photo_url);
        $demand_data['priority_level'] = $priority_level;
        $demand_data['priority_describe'] = $priority_describe;
        $demand_data['demand_status'] = DemandStatus::IDENT;
        $demand_data['remark'] = $remark;
        $demand_data['propose_time'] = get_date_time();
        $demand_id = IDb::getInstance("demand")->setDbData($demand_data)->add();
        if($demand_id === false){
            IDb::dbRollback();
            error("添加需求失败！");
        }

        //添加操作日志
        $demand_log_data['demand_id'] = $demand_id;
        $demand_log_data['user_id']= $user_id;
        $demand_log_data['operate_note']= "需求提交完成！";
        $demand_log_data['operate_time']= get_date_time();
        $demand_log_add = IDb::getInstance("demand_log")->setDbData($demand_log_data)->add();
        if($demand_log_add === false){
            IDb::dbRollback();
            error("修改需求修改日志失败！");
        }

        //提交实物
        IDb::dbCommit();

        //操作成功开始发送对应通知
        $where['user_id'] = $user_id;
        $admin_info = IDb::getInstance('user')
            ->setDbFiled("if(real_name is null,nickname,real_name) as real_name")
            ->setDbWhere($where)
            ->row();

        //设置发送通知
        $project_replenish = str_pad($project_id,6,"0",STR_PAD_LEFT);
        $demand_replenish = str_pad($demand_id,6,"0",STR_PAD_LEFT);

        $sendText = $admin_info['real_name'].'，提交了新的需求请审核；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
        $sendData=array($sendText,get_date_time());
        IWeChat::sendInform($project_info['charge_user_id'],WeChatInformType::Submission,$sendData,$demand_id,$sendText);

        //画面跳转
        $this->success("操作成功",Url("demand_propose_list"));
    }

    /**
     * @功能：需求进度日志
     * 开发者：szg
     */
    public function demand_task(){
        //接收参数
        $demand_id = IRequest::get('demand_id',IRequest::NOT_EMPTY,"需求编号不能为空！");

        //取得需求数据
        $task_where['demand_id'] = $demand_id;
        $task_list = IDb::getInstance("task as tk")
            ->setDbJoin("user as ur","tk.user_id = ur.user_id")
            ->setDbWhere($task_where)
            ->pag();

        //设置layout
        $this->layout("layout_empty");

        //模板渲染
        $this->assign('task_list',$task_list);

        //渲染模板输出
        return view();
    }

    /**
     * @功能：需要分配的需求列表
     * @开发者：szg
     */
    public function demand_distribution_list(){
        //接收参数
        $demand_id = IRequest::get('demand_id');
        $project_id = IRequest::get('project_id');
        $demand_describe = IRequest::get('demand_describe');
        $priority_level = IRequest::get('priority_level');

        //取得登录管理员编号
        $admin_id = get_login_user_id();

        //取得项目记录
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_where['charge_user_id'] = $admin_id;
        $project_list = IDb::getInstance('project')->setDbFiled("project_id,project_name")->setDbWhere($project_where)->sel();
        if($project_list === false){
            error('取得项目列表失败！');
        }

        //设置查询条件
        $demand_where['pt.propose_status'] = ProposeStatus::OPEN;
        $demand_where['pt.charge_user_id'] = $admin_id;
        $demand_where['dd.demand_status'] = DemandStatus::IDENT;
        if(!empty($demand_id))$demand_where['dd.demand_id'] = $demand_id;
        if(!empty($project_id))$demand_where['dd.project_id'] =$project_id;
        if (!empty($demand_describe))$demand_where['dd.demand_describe'] = array('like', "%{$demand_describe}%");
        if(!empty($priority_level))$demand_where['dd.priority_level'] = $priority_level;

        //取得数据
        $demand_list = IDb::getInstance('demand as dd')
            ->setDbFiled("dd.*,pt.project_name,if(ur.real_name is null,ur.nickname,ur.real_name) as real_name,ur.phone")
            ->setDbJoin("project as pt","dd.project_id=pt.project_id")
            ->setDbJoin("user as ur","dd.user_id=ur.user_id")
            ->setDbWhere($demand_where)
            ->setDbOrder('dd.demand_id desc')
            ->pag();

        //判断数据是否正常
        if($demand_list === false){
            error('获取需求列表有误！');
        }

        //页面赋值
        $this->assign('project_list', $project_list);
        $this->assign('demand_list', $demand_list);
        $this->assign('demand_id', $demand_id);
        $this->assign('project_id', $project_id);
        $this->assign('demand_describe', $demand_describe);
        $this->assign('priority_level', $priority_level);

        //页面显示
        return view();
    }

    /**
     * @功能：需求详情
     * @开发者：szg
     */
    public function demand_info(){
        //接收参数
        $demand_id = IRequest::get('demand_id',IRequest::NOT_EMPTY,"需求编号不能为空！");

        //取得需求数据
        $demand_where['dd.demand_id'] = $demand_id;
        $demand_info = IDb::getInstance("demand as dd")
            ->setDbJoin("project as pt","dd.project_id = pt.project_id")
            ->setDbJoin("user as ur","pt.charge_user_id = ur.user_id")
            ->setDbWhere($demand_where)
            ->row();

        //判断数据是否存在
        if(empty($demand_info)){
            error("取得需求信息失败！");
        }

        //将图片转成数组
        $demand_info['demand_photo_list'] = explode(",",$demand_info['demand_photo_list']);
        $demand_info['design_list'] = explode(",",$demand_info['design_list']);
        $demand_info['development_list'] = explode(",",$demand_info['development_list']);
        $demand_info['test_list'] = explode(",",$demand_info['test_list']);

        //设置layout
        $this->layout("layout_empty");

        //模板渲染
        $this->assign('demand_id',$demand_id);
        $this->assign('demand_info',$demand_info);

        //渲染模板输出
        return view();
    }

    /**
     * @功能：需求审核
     * @开发者：szg
     */
    public function demand_auditing(){
        //接收参数
        $demand_id = IRequest::get('demand_id',IRequest::NOT_EMPTY,"需求编号不能为空！");

        //取得需求信息
        $demand_where['dd.demand_id'] = $demand_id;
        $demand_info = IDb::getInstance('demand as dd')
            ->setDbFiled("dd.*,project_name")
            ->setDbJoin("project as pt","dd.project_id = pt.project_id")
            ->setDbWhere($demand_where)
            ->row();

        //判断请求数据是否失败
        if ($demand_info === false){
            error('获取需求详情失败');
        }

        //判断状态
        if($demand_info['demand_status'] != DemandStatus::IDENT){
            error("需求已经审核不能重复审核！");
        }

        //取得管理员信息
        $user_where['an.is_delete'] = IsDelete::No;
        $user_list = IDb::getInstance('admin as an')
            ->setDbFiled("an.admin_type,ur.user_id,ur.nickname,ur.real_name")
            ->setDbJoin("user as ur","an.user_id=ur.user_id")
            ->setDbWhere($user_where)
            ->sel();

        //判断请求数据是否失败
        if ($user_list === false){
            error('获取需求详情失败2');
        }

        //整理数据
        $user_array = array();
        foreach($user_list as $item){
            if(empty($item['real_name'])){
                $user_array[$item['admin_type']][] = array("user_id" => $item['user_id'], "name" =>$item['nickname']);
            }else {
                $user_array[$item['admin_type']][] = array("user_id" => $item['user_id'], "name" =>$item['real_name']);
            }
        }

        //模板渲染
        $this->assign('demand_id',$demand_id);
        $this->assign('demand_info',$demand_info);
        $this->assign('user_list',$user_array);

        //渲染模板输出
        return view();
    }

    /**
     * @功能：需求开始提交
     * @开发者：szg
     */
    public function demand_auditing_submit(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");
        $demand_status = IRequest::get("demand_audit",IRequest::NOT_EMPTY,"请审核需求！");
        $design_list = IRequest::get("design_list");
        $development_list = IRequest::get("development_list");
        $test_list = IRequest::get("test_list");
        $demand_feedback = IRequest::get("demand_feedback");

        //判断开发人员是否为空
        if ($demand_status == DemandStatus::PROCEEDING){
            if(empty($design_list) && empty($development_list)){
                error("设计、开发人员必须其一！");
            }else{
                //设置值
                if(!empty($design_list)) { $design_str = implode(",",$design_list);}else{$design_list=array();}
                if(!empty($development_list)) { $development_str = implode(",",$development_list);}else{$development_list=array();}
                if(!empty($test_list)) { $test_str = implode(",",$test_list);}else{$test_list=array();}
            }
        }

        //判断需求是否已经分配
        $demand_where["dd.demand_id"] = $demand_id;
        $demand_info = IDb::getInstance("demand as dd")
            ->setDbFiled("dd.*,if(ur.real_name is null,ur.nickname,ur.real_name) as real_name")
            ->setDbJoin("user as ur","dd.user_id=ur.user_id")
            ->setDbWhere($demand_where)
            ->row();

        //判断是否被重复分配
        if($demand_info['demand_status'] != DemandStatus::IDENT){
            error("需求已经分配不能重复分配！");
        }

        //判断审核状态
        if($demand_status == DemandStatus::PROCEEDING){
            //判断时间状态
            $start_time = get_date_time();
            $expect_finish_time = IRequest::get("expect_finish_time",IRequest::NOT_EMPTY,"预计需求完成时间不能为空！");
            if(strtotime($expect_finish_time) <= strtotime($start_time)){
                error("预计时间不能小于当前时间！");
            }

            //整理变量
            $demand_data['demand_status'] = DemandStatus::PROCEEDING;
            $demand_data['expect_finish_time'] = $expect_finish_time;
        }else{
            //拒绝
            $demand_data['demand_status'] = DemandStatus::REFUSE;
        }

        //开启实物处理
        IDb::dbStartTrans();

        //修改项目审核状态
        $demand_where = null;
        $demand_where["demand_id"] = $demand_id;
        $demand_data['auditing_time'] = get_date_time();
        $demand_data['demand_feedback'] = $demand_feedback;
        if(!empty($design_str)) $demand_data['design_list'] = $design_str;
        if(!empty($development_str)) $demand_data['development_list'] = $development_str;
        if(!empty($test_str)) $demand_data['test_list'] = $test_str;
        $demand_upd = IDb::getInstance("demand")->setDbWhere($demand_where)->setDbData($demand_data)->upd();
        if($demand_upd === false){
            IDb::dbRollback();
            error("修改项目信息失败！");
        }

        //添加操作日志
        $demand_log_data['demand_id'] = $demand_id;
        $demand_log_data['user_id']= get_login_user_id();
        $demand_log_data['operate_note']= "需求审核完成！";
        $demand_log_data['operate_time']= get_date_time();
        $demand_log_add = IDb::getInstance("demand_log")->setDbData($demand_log_data)->add();
        if($demand_log_add === false){
            IDb::dbRollback();
            error("修改需求修改日志失败！");
        }

        //提交实物
        IDb::dbCommit();

        //获取项目信息
        $project_where['pro.project_id'] = $demand_info['project_id'];
        $project_info = IDb::getInstance('project as pro')
            ->setDbFiled("pro.*,if(ur.real_name is null,ur.nickname,ur.real_name) as real_name")
            ->setDbJoin("user as ur","pro.charge_user_id=ur.user_id")
            ->setDbWhere($project_where)
            ->row();

        //操作成功开始发送对应通知
        $project_replenish = str_pad($demand_info['project_id'],6,"0",STR_PAD_LEFT);
        $demand_replenish = str_pad($demand_id,6,"0",STR_PAD_LEFT);

        //审核失败发送提交需求人
        if ($demand_status == DemandStatus::REFUSE){
            $sendStart = '审核失败';
            $sendText = '您提交的需求审核失败，请查看失败原因；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
            //设置通知数据
            $sendData=array($demand_info['demand_describe'],$sendStart,$sendText);

            //发送通知给用户
            IWeChat::sendInform($demand_info['user_id'],WeChatInformType::State,$sendData,$demand_info['demand_id'],$sendText);
        }

        //审核成功发送通知
        if($demand_status == DemandStatus::PROCEEDING) {
            //整理需要发送的人员名单
            $user_list = array_merge($design_list,$development_list,$test_list);
            $user_list_str = implode(",",$user_list);
            $user_where['user_id'] = array('exp',"in ({$user_list_str})");
            $user_list_info = IDb::getInstance('user')
                ->setDbFiled("user_id,if(real_name is null,nickname,real_name) as real_name")
                ->setDbWhere($user_where)
                ->sel();

            //处理参与人
            $text_str = null;
            foreach ($user_list_info as $key=>$value){
                if (empty($text_str)){
                    $text_str = $value['real_name'];
                }else{
                    $text_str .= ','.$value['real_name'];
                }
            }

            //设置通知数据
            $sendData=array($project_info['project_describe'],$demand_info['demand_describe'],$project_info['real_name'],$text_str,$demand_data['expect_finish_time']);

            //发送提交人设置存入消息表信息
            $notice_describe = '您提交的需求已经通过审核，预计完成时间：'.$demand_data['expect_finish_time'].'；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
            //发送通知给提交人
            IWeChat::sendInform($demand_info['user_id'],WeChatInformType::Received,$sendData,$demand_info['demand_id'], $notice_describe);

            //获取当前审核人名字
           $real_name = IDb::getInstance('user')
                ->setDbFiled("if(real_name is null,nickname,real_name) as real_name")
                ->setDbWhere(array('user_id'=>get_login_user_id()))
                ->row();
            //发送参与人设置存入消息表信息
            $participant_text = '你有新的任务，预计完成时间：'.$demand_data['expect_finish_time'].'；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
            foreach ($user_list_info as $k=>$val){
                //发送通知给参与人
                IWeChat::sendInform($val['user_id'],WeChatInformType::Received,$sendData,$demand_info['demand_id'],$participant_text);
            }
        }

        //画面跳转
        $this->success("操作成功",Url("demand_distribution_list"));
    }

    /**
     * @功能：需要分配的需求列表
     * @开发者：szg
     */
    public function demand_develop_list(){
        //接收参数
        $demand_id = IRequest::get('demand_id');
        $project_id = IRequest::get('project_id');
        $demand_describe = IRequest::get('demand_describe');
        $demand_status = IRequest::get('demand_status');
        $priority_level = IRequest::get('priority_level');

        //取得登录管理员编号
        $admin_id = get_login_user_id();
        $demand_status_array = array(DemandStatus::IDENT,DemandStatus::REFUSE,DemandStatus::UPING);
        $demand_status_list = array(
            array("status_id"=>DemandStatus::PROCEEDING,"status_name"=>HtmlEnumValue::$enum_value['demand_status'][DemandStatus::PROCEEDING]),
            array("status_id"=>DemandStatus::DONE,"status_name"=>HtmlEnumValue::$enum_value['demand_status'][DemandStatus::DONE]),
            array("status_id"=>DemandStatus::TEST,"status_name"=>HtmlEnumValue::$enum_value['demand_status'][DemandStatus::TEST]),
            array("status_id"=>DemandStatus::UPING,"status_name"=>HtmlEnumValue::$enum_value['demand_status'][DemandStatus::UPING])
        );

        //取得项目记录
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_list = IDb::getInstance('project')->setDbFiled("project_id,project_name")->setDbWhere($project_where)->sel();
        if($project_list === false){
            error('取得项目列表失败！');
        }

        //设置查询条件
        $demand_where['pt.propose_status'] = ProposeStatus::OPEN;
        $demand_where['_string_'] = "find_in_set('{$admin_id}',design_list) || find_in_set('{$admin_id}',development_list) || find_in_set('{$admin_id}',test_list)";
        if(!empty($demand_status))$demand_where['dd.demand_status'] = array("not in",$demand_status_array);
        if(!empty($demand_id))$demand_where['dd.demand_id'] = $demand_id;
        if(!empty($project_id))$demand_where['dd.project_id'] =$project_id;
        if (!empty($demand_describe))$demand_where['dd.demand_describe'] = array('like', "%{$demand_describe}%");
        if(!empty($demand_status))$demand_where['dd.demand_status'] = $demand_status;
        if(!empty($priority_level))$demand_where['dd.priority_level'] = $priority_level;

        //取得数据
        $demand_list = IDb::getInstance('demand as dd')
            ->setDbFiled("dd.*,pt.project_name,if(ur.real_name is null,ur.nickname,ur.real_name) as real_name,ur.phone")
            ->setDbJoin("project as pt","dd.project_id=pt.project_id")
            ->setDbJoin("user as ur","dd.user_id=ur.user_id")
            ->setDbWhere($demand_where)
            ->setDbOrder('dd.demand_id desc')
            ->pag();

        //判断数据是否正常
        if($demand_list === false){
            error('获取需求列表有误！');
        }

        //页面赋值
        $this->assign('project_list', $project_list);
        $this->assign('demand_list', $demand_list);
        $this->assign('demand_id', $demand_id);
        $this->assign('project_id', $project_id);
        $this->assign('demand_describe', $demand_describe);
        $this->assign('demand_status', $demand_status);
        $this->assign('demand_status_list', $demand_status_list);
        $this->assign('priority_level', $priority_level);

        //页面显示
        return view();
    }

    /**
     * @功能：填写日志
     * @开发者：szg
     */
    public function demand_task_write(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");

        //取得用户默认信息
        $admin_where["admin_id"] = get_login_admin_id();
        $admin_info = IDb::getInstance("admin")->setDbFiled("admin_type")->setDbWhere($admin_where)->row();
        if(empty($admin_info['admin_type'])){
            error("取得管理员类型失败！");
        }

        //页面赋值
        $this->assign('demand_id', $demand_id);
        $this->assign('admin_type', $admin_info['admin_type']);

        //页面显示
        return view();
    }

    /**
     * @功能：填写日志
     * @开发者：szg
     */
    public function demand_task_write_submit(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");
        $task_type = IRequest::get("task_type",IRequest::NOT_EMPTY,"进度类型不能为空！");
        $rate_describe = IRequest::get("rate_describe",IRequest::NOT_EMPTY,"进度概要不能为空！");
        $rate_percent = IRequest::get("rate_percent");
        $start_time = IRequest::get("start_time",IRequest::NOT_EMPTY,"开始时间不能为空！");
        $end_time = IRequest::get("end_time",IRequest::NOT_EMPTY,"结束时间不能为空！");

        //判断进度比例
        $rate_percent = intval($rate_percent);
        if($rate_percent <= 0 || $rate_percent > 100){
            error("进度百分百错误，必要大于0并且小于等于100！");
        }

        //填写进度信息
        $task_data["demand_id"] = $demand_id;
        $task_data["user_id"] = get_login_user_id();
        $task_data["task_type"] = $task_type;
        $task_data["rate_describe"] = $rate_describe;
        $task_data["rate_percent"] = $rate_percent;
        $task_data["start_time"] = $start_time;
        $task_data["end_time"] = $end_time;
        $task_data["create_time"] = get_date_time();
        $task_id = IDb::getInstance("task")->setDbData($task_data)->add();
        if($task_id === false){
            error("添加进度失败！");
        }

        //画面跳转
        $this->success("操作成功",Url("demand_develop_list"));
    }

    /**
     * @功能：开发完成
     * @开发者：szg
     */
    public function development_complete(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");

        //判断项目是否存在
        $test_list = array();
        $demand_where["demand_id"] = $demand_id;
        $demand_info = IDb::getInstance("demand")->setDbFiled("test_list,demand_describe,project_id")->setDbWhere($demand_where)->row();
        if(!empty($demand_info['test_list'])){
            $test_list = explode(",",$demand_info['test_list']);//设置测试人员变化
        }

        //开启事物处理
        IDb::dbStartTrans();

        //取得需求信息
        $demand_where["demand_id"] = $demand_id;
        $demand_data['development_time'] = get_date_time();
        $demand_data['demand_status'] = DemandStatus::DONE;
        $demand_upd = IDb::getInstance("demand")->setDbWhere($demand_where)->setDbData($demand_data)->upd();
        if($demand_upd === false){
            IDb::dbRollback();
            error("项目开发完成操作失败！");
        }

        //添加操作日志
        $demand_log_data['demand_id'] = $demand_id;
        $demand_log_data['user_id']= get_login_user_id();
        $demand_log_data['operate_note']= "需求设计开发完成！";
        $demand_log_data['operate_time']= get_date_time();
        $demand_log_add = IDb::getInstance("demand_log")->setDbData($demand_log_data)->add();
        if($demand_log_add === false){
            IDb::dbRollback();
            error("修改需求修改日志失败！");
        }

        //提交实物
        IDb::dbCommit();

        //操作成功开始发送对应通知
        $project_replenish = str_pad($demand_info['project_id'],6,"0",STR_PAD_LEFT);
        $demand_replenish = str_pad($demand_id,6,"0",STR_PAD_LEFT);
        $sendStart = '项目需求开发完成，请抓紧测试；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
        $sendData= array($demand_info['demand_describe'],'开发完成时间:'.$demand_data['development_time'],$sendStart);

        //发送通知
        foreach($test_list as $item) {
            IWeChat::sendInform($item, WeChatInformType::Complete, $sendData, $demand_id,$sendStart);
        }

        //画面跳转
        $this->success("操作成功",Url("demand_develop_list"));
    }

    /**
     * @功能：测试完成
     * @开发者： szg
     */
    public function test_complete(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");

        //判断项目是否存在
        $demand_where["demand_id"] = $demand_id;
        $demand_info = IDb::getInstance("demand")->setDbFiled("project_id,demand_describe")->setDbWhere($demand_where)->row();
        if(empty($demand_info['project_id'])){
            error("取得项目失败！");
        }

        //取得项目负责人
        $project_where["project_id"] = $demand_info['project_id'];
        $project_info = IDb::getInstance("project")->setDbFiled("charge_user_id")->setDbWhere($project_where)->row();
        if(empty($project_info['charge_user_id'])){
            error("取得项目负责人失败！");
        }

        //开启事物处理
        IDb::dbStartTrans();

        //取得需求信息
        $demand_where["demand_id"] = $demand_id;
        $demand_data['test_time'] = get_date_time();
        $demand_data['demand_status'] = DemandStatus::TEST;
        $demand_upd = IDb::getInstance("demand")->setDbWhere($demand_where)->setDbData($demand_data)->upd();
        if($demand_upd === false){
            IDb::dbRollback();
            error("项目测试完成操作失败！");
        }

        //添加操作日志
        $demand_log_data['demand_id'] = $demand_id;
        $demand_log_data['user_id']= get_login_user_id();
        $demand_log_data['operate_note']= "需求测试完成！";
        $demand_log_data['operate_time']= get_date_time();
        $demand_log_add = IDb::getInstance("demand_log")->setDbData($demand_log_data)->add();
        if($demand_log_add === false){
            IDb::dbRollback();
            error("修改需求修改日志失败！");
        }

        //提交实物
        IDb::dbCommit();

        //操作成功开始发送对应通知
        $project_replenish = str_pad($demand_info['project_id'],6,"0",STR_PAD_LEFT);
        $demand_replenish = str_pad($demand_id,6,"0",STR_PAD_LEFT);
        $sendStart = '项目需求测试完成，请确认后上线；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
        $sendData= array($demand_info['demand_describe'],'测试完成时间:'.$demand_data['test_time'],$sendStart);
        IWeChat::sendInform($project_info['charge_user_id'], WeChatInformType::Complete, $sendData, $demand_id,$sendStart);

        //画面跳转
        $this->success("操作成功",Url("demand_develop_list"));
    }

    /**
     * @功能：提交上线
     * @开发者： szg
     */
    public function online_complete(){
        //接收参数
        $demand_id = IRequest::get("demand_id",IRequest::NOT_EMPTY,"需求编号不能为空！");

        //判断项目是否存在
        $demand_where["demand_id"] = $demand_id;
        $demand_info = IDb::getInstance("demand")->setDbFiled("user_id,demand_describe,project_id")->setDbWhere($demand_where)->row();
        if(empty($demand_info['user_id'])){
            error("取得需求提出人失败！");
        }

        //开启事物处理
        IDb::dbStartTrans();

        //取得需求信息
        $demand_where["demand_id"] = $demand_id;
        $demand_data['finish_time'] = get_date_time();
        $demand_data['demand_status'] = DemandStatus::UPING;
        $demand_upd = IDb::getInstance("demand")->setDbWhere($demand_where)->setDbData($demand_data)->upd();
        if($demand_upd === false){
            IDb::dbRollback();
            error("项目测试完成操作失败！");
        }

        //添加操作日志
        $demand_log_data['demand_id'] = $demand_id;
        $demand_log_data['user_id']= get_login_user_id();
        $demand_log_data['operate_note']= "需求已上线！";
        $demand_log_data['operate_time']= get_date_time();
        $demand_log_add = IDb::getInstance("demand_log")->setDbData($demand_log_data)->add();
        if($demand_log_add === false){
            IDb::dbRollback();
            error("修改需求修改日志失败！");
        }

        //提交实物
        IDb::dbCommit();

        //操作成功开始发送对应通知
        $project_replenish = str_pad($demand_info['project_id'],6,"0",STR_PAD_LEFT);
        $demand_replenish = str_pad($demand_id,6,"0",STR_PAD_LEFT);

        $sendStart = '项目需求已经上线，上线时间：'.get_date_time().'；（项目编号：'.$project_replenish.'，需求编号：'.$demand_replenish.'）';
        $sendData= array($demand_info['demand_describe'],'测试完成时间:'.$demand_data['finish_time'],$sendStart);
        IWeChat::sendInform($demand_info['user_id'], WeChatInformType::Complete, $sendData, $demand_id,$sendStart);

        //画面跳转
        $this->success("操作成功",Url("demand_develop_list"));
    }

    /**
     * @功能：项目进度日志
     * @开发者：szg
     */
    public function demand_task_list(){
        //接收参数
        $demand_id = IRequest::get('demand_id');
        $project_id = IRequest::get('project_id');
        $demand_describe = IRequest::get('demand_describe');
        $demand_status = IRequest::get('demand_status');
        $priority_level = IRequest::get('priority_level');

        //取得登录管理员编号
        $user_id = get_login_user_id();

        //取得项目记录
        $project_where['propose_status'] = ProposeStatus::OPEN;
        $project_list = IDb::getInstance('project')->setDbFiled("project_id,project_name")->setDbWhere($project_where)->sel();
        if ($project_list === false) {
            error('取得项目列表失败！');
        }

        //设置查询条件
        $task_where['tk.user_id'] = $user_id;
        if (!empty($demand_id)) $task_where['dd.demand_id'] = $demand_id;
        if (!empty($demand_describe))$task_where['dd.demand_describe'] = array('like', "%{$demand_describe}%");
        if (!empty($project_id)) $task_where['dd.project_id'] = $project_id;
        if (!empty($demand_status)) $task_where['dd.demand_status'] = $demand_status;
        if (!empty($priority_level)) $task_where['dd.priority_level'] = $priority_level;

        //取得数据
        $demand_list = IDb::getInstance('task as tk')
            ->setDbFiled("tk.*,pt.project_name,dd.demand_describe")
            ->setDbJoin("demand as dd", "dd.demand_id=tk.demand_id","left")
            ->setDbJoin("project as pt", "dd.project_id=pt.project_id","left")
            ->setDbWhere($task_where)
            ->setDbOrder('tk.task_id desc')
            ->pag();

        //判断数据是否正常
        if ($demand_list === false) {
            error('获取需求列表有误！');
        }

        //页面赋值
        $this->assign('project_list', $project_list);
        $this->assign('demand_list', $demand_list);
        $this->assign('demand_id', $demand_id);
        $this->assign('project_id', $project_id);
        $this->assign('demand_describe', $demand_describe);
        $this->assign('demand_status', $demand_status);
        $this->assign('priority_level', $priority_level);

        //页面显示
        return view();
    }

    /**
     * @功能：填写日志
     * @开发者：szg
     */
    public function demand_task_add(){
        //取得用户默认信息
        $admin_where["admin_id"] = get_login_admin_id();
        $admin_info = IDb::getInstance("admin")->setDbFiled("admin_type")->setDbWhere($admin_where)->row();
        if(empty($admin_info['admin_type'])){
            error("取得管理员类型失败！");
        }

        //页面赋值
        $this->assign('admin_type', $admin_info['admin_type']);

        //页面显示
        return view();
    }

    /**
     * @功能：填写日志
     * @开发者：szg
     */
    public function demand_task_add_submit(){
        //接收参数
        $task_type = IRequest::get("task_type",IRequest::NOT_EMPTY,"进度类型不能为空！");
        $rate_describe = IRequest::get("rate_describe",IRequest::NOT_EMPTY,"进度概要不能为空！");
        $rate_percent = IRequest::get("rate_percent");
        $start_time = IRequest::get("start_time",IRequest::NOT_EMPTY,"开始时间不能为空！");
        $end_time = IRequest::get("end_time",IRequest::NOT_EMPTY,"结束时间不能为空！");

        //判断进度比例
        $rate_percent = intval($rate_percent);
        if($rate_percent <= 0 || $rate_percent > 100){
            error("进度百分百错误，必要大于0并且小于等于100！");
        }

        //填写进度信息
        $task_data["user_id"] = get_login_user_id();
        $task_data["task_type"] = $task_type;
        $task_data["rate_describe"] = $rate_describe;
        $task_data["rate_percent"] = $rate_percent;
        $task_data["start_time"] = $start_time;
        $task_data["end_time"] = $end_time;
        $task_data["create_time"] = get_date_time();
        $task_id = IDb::getInstance("task")->setDbData($task_data)->add();
        if($task_id === false){
            error("添加进度失败！");
        }

        //画面跳转
        $this->success("操作成功",Url("demand_task_list"));
    }

    /**
     * @功能：发送给我的信息
     * @开发者：szg
     */
    public function demand_notice_list(){
        //接收参数
        $demand_id = IRequest::get('demand_id');
        $demand_describe = IRequest::get('demand_describe');
        $notice_describe = IRequest::get('notice_describe');
        $notice_read = IRequest::get('notice_read');
        $notice_state = IRequest::get('notice_state');

        //取得登录管理员编号
        $user_id = get_login_user_id();

        //设置查询条件
        $notice_where['ne.user_id'] = $user_id;
        if (!empty($demand_id)) $notice_where['ne.demand_id'] = $demand_id;
        if (!empty($demand_describe))$notice_where['dd.demand_describe'] = array('like', "%{$demand_describe}%");
        if (!empty($notice_describe))$notice_where['ne.notice_describe'] = array('like', "%{$notice_describe}%");
        if (!empty($notice_read)) $notice_where['ne.notice_read'] = $notice_read;
        if (!empty($notice_state)) $notice_where['ne.notice_state'] = $notice_state;

        //取得数据
        $notice_list = IDb::getInstance('notice as ne')
            ->setDbFiled("ne.*,dd.demand_describe")
            ->setDbJoin("demand as dd", "dd.demand_id=ne.demand_id","left")
            ->setDbWhere($notice_where)
            ->setDbOrder('ne.notice_id desc')
            ->pag();

        //判断数据是否正常
        if ($notice_list === false) {
            error('获取消息列表有误！');
        }

        //页面赋值
        $this->assign('notice_list', $notice_list);
        $this->assign('demand_id', $demand_id);
        $this->assign('demand_describe', $demand_describe);
        $this->assign('notice_describe', $notice_describe);
        $this->assign('notice_read', $notice_read);
        $this->assign('notice_state', $notice_state);

        //页面显示
        return view();
    }

    /**
     * @功能：发送给我的信息需求
     * @开发者：szg
     */
    public function demand_notice_info(){
        //接收参数
        $notice_id = IRequest::get('notice_id',IRequest::NOT_EMPTY,"项目编号不能为空！");

        //设置查询条件
        $notice_where['ne.user_id'] =get_login_user_id();
        $notice_where['ne.notice_id'] = $notice_id;

        //取得数据
        $notice_info = IDb::getInstance('notice as ne')
            ->setDbFiled("ne.*,dd.demand_describe,pt.project_id,pt.project_name")
            ->setDbJoin("demand as dd", "dd.demand_id=ne.demand_id","left")
            ->setDbJoin("project as pt", "pt.project_id=dd.project_id","left")
            ->setDbWhere($notice_where)
            ->setDbOrder('ne.notice_id desc')
            ->row();

        //判断数据是否正常
        if ($notice_info === false) {
            error('获取消息详情有误！');
        }

        //修改数据状态
        $notice_data['notice_read'] = IsRead::Yes;
        $notice_upd = IDb::getInstance("notice as ne")->setDbWhere($notice_where)->setDbData($notice_data)->upd();
        if($notice_upd === false){
            error("修改消息状态失败！");
        }

        //页面赋值
        $this->assign('notice_info', $notice_info);

        //页面显示
        return view();
    }
}
