<?php
/**
 * Created by PhpStorm.
 * User: zengwenjie
 * Date: 2018/12/6
 * Time: 11:10
 */

namespace app\api\controller;


use app\common\cache\demand\IDemandCache;
use app\common\cache\demand\IMyDemandCache;
use app\common\cache\user\IUserCache;
use app\common\controller\DemandBaseController;
use app\common\ext\IDb;
use app\common\enum\IsDelete;
use app\common\enum\PriorityLevel;
use app\common\ext\IWeChat;

class Organization extends DemandBaseController{
    /**
     * @功能：获取组织架构
     * @开发者：WDD
     */
    public function get_organization(){
        $org_id  = self::get_data('org_id');
        if (empty($org_id)){
            $org_id = 0;
        }

        $organize_where['parent_id'] = $org_id;
        $organize_where['is_delete'] = IsDelete::No;
        $organize_list = IDb::getInstance('organize')->setDbWhere($organize_where)->sel();
        if ($organize_list === false){
            //返回错误
            self::set_code(self::ERR_0003);
            self::set_msg("获取组织失败！");
            self::send();
        }
        foreach ($organize_list as $key=>$value){
            //判断是否还有下级
            $where['parent_id'] = $value['org_id'];
            $where['is_delete'] = IsDelete::No;
            $organize_info = IDb::getInstance('organize')->setDbWhere($where)->row();
            if (empty($organize_info)){
                $organize_list[$key]['below'] = 0;
            }else{
                $organize_list[$key]['below'] = 1;
            }

            //查找职位
            $job_list = IDb::getInstance('job')->setDbWhere(array('org_id'=>$value['org_id']))->sel();
            if (empty($job_list)){
                $job_list = array();
            }
            $organize_list[$key]['job_list'] = $job_list;
        }

        self::set_code(self::SUCCESS);
        self::set_msg("成功！");
        self::set_value("organize_list",$organize_list);
        self::send();
    }


    /**
     * @功能：提交组织信息
     * @开发者：曾文杰
     */
    public function set_organization(){
        $user_id  = self::$uid;
        $org_id  = self::get_data('org_id',self::NOT_EMPTY,"组织编号不能为空");
        $job_id  = self::get_data('job_id',self::NOT_EMPTY,"职位编号不能为空");
        $name  = self::get_data('real_name',self::NOT_EMPTY,"姓名不能为空");
        $phone  = self::get_data('phone',self::PHONE,"手机号不能为空或格式不正确！");
        $user_where['user_id'] =$user_id;
        $user_info = IDb::getInstance('user')->setDbWhere($user_where)->row();
        //开始事物
        IDb::dbStartTrans();
        if (!empty($user_info)){

            $user_data['real_name']=$name;
            $user_data['phone']=$phone;
            $result = IDb::getInstance('user')
                ->setDbWhere(['user_id'=>$user_info['user_id']])
                ->setDbData($user_data)
                ->upd();
            if($result === false){
                self::set_code(self::WARNING_0002);
                self::set_msg('添加组织架构失败');
                self::send();
            }
            IUserCache::deleteInfo(self::$uid);
        }else{
            self::set_code(self::ERR_0001);
            self::set_msg("用户不存在！");
            self::send();
        }
        $relation_where['user_id'] =$user_id;
        $relation_where['org_id'] =$org_id;
        $relation_where['job_id'] =$job_id;
        $relation_info = IDb::getInstance('organize_relation')->setDbWhere($relation_where)->row();
        if (!empty($relation_info)){
            IDb::dbRollback();
            self::set_code(self::ERR_0001);
            self::set_msg("该组织结构已存在！");
            self::send();
        }
        $relation_data['user_id'] = $user_id;
        $relation_data['org_id'] = $org_id;
        $relation_data['job_id'] = $job_id;
        $relation_data['create_time'] = get_date_time();
        $relation_data['is_delete'] = IsDelete::No;
        $relation_id = IDb::getInstance('organize_relation')->setDbData($relation_data)->add();
        if(!$relation_id){
            IDb::dbRollback();
            self::set_code(self::WARNING_0003);
            self::set_msg('添加组织架构失败');
            self::send();
        }
        //提交事物处理
        IDb::dbCommit();
        self::set_code(self::SUCCESS);
        self::set_msg("成功！");
        self::set_value("relation_id",$relation_id);
        self::send();
    }

}