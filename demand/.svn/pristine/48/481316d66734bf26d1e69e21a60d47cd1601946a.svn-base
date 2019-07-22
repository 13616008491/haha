<?php
/*
* @开发工具: JetBrains PhpStorm.
* @文件名: IDb.class.php
* @类功能: 数据库操作
* @开发者: zc
* @开发时间: 2014-10-29
* @版本：version 1.0
*/
namespace app\common\ext;

use think\Config;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\Request;

class IDb extends Db{
    //变量定义
    private $db_table = null;
    private $db_join = null;
    private $db_data = null;
    private $db_where = null;
    private $db_filed = null;
    private $db_order = null;
    private $db_group = null;
    private $db_limit = null;
    private static $instance = null;
    private static $module = null;
    private static $db_sql = null;
    private static $db_msg = null;

    /**
     * @功能 构造函数
     * @param string $db_table 表名称（不含前缀）
     * @return $this
     */
    public static function getInstance($db_table=null){
        //创建自身对象
        if(empty(self::$instance)) {
            self::$instance = new IDb();
        }

        //判断表名称是否存在
        if(!empty($db_table)) {
            //设置表名称
            self::$instance = self::$instance->setDbTable($db_table);
        }

        //返回值
        return self::$instance;
    }

    /**
     * @功能：设置表名称
     * @开发者：cxl
     * @return string
     */
    public static function getDbLastSql(){
        if(is_object(self::$module)) {
            return self::$module->getLastSql();
        }else{
            return false;
        }
    }

    /**
     * @功能：设置表名称
     * @param string $db_table 表名称（不含前缀）
     * @开发者：cxl
     * @return $this
     */
    public function setDbTable($db_table){
        //设置表名称
        $this->db_table = $db_table;

        //返回对象
        return $this;
    }

    /**
     * @功能：设置表关联
     * @param string $db_table 表名称（不含前缀）
     * @param string $db_where 关联条件
     * @param string $type 关联类型
     * @开发者：cxl
     * @return $this
     */
    public function setDbJoin($db_table,$db_where,$type='inner'){
        //设置表关联
        $this->db_join[] = array($db_table,$db_where,$type);

        //返回对象
        return $this;
    }

    /**
     * @功能：设置操作数据
     * @param array $db_data 设置数据
     * @开发者：cxl
     * @return $this
     */
    public function setDbData($db_data){
        //设置操作数据
        $this->db_data = $db_data;

        //返回对象
        return $this;
    }

    /**
     * @功能：设置查询条件
     * @param mixed $db_where 设置查询条件
     * @开发者：cxl
     * @return $this
     */
    public function setDbWhere($db_where){
        //设置查询条件
        $this->db_where = $db_where;

        //返回对象
        return $this;
    }

    /**
     * @功能：设置字段
     * @param mixed $db_filed 设置字段
     * @开发者：cxl
     * @return $this
     */
    public function setDbFiled($db_filed){
        //设置字段
        $this->db_filed = $db_filed;

        //返回对象
        return $this;
    }

    /**
     * @功能：排序条件
     * @param mixed $db_order 排序条件
     * @开发者：cxl
     * @return $this
     */
    public function setDbOrder($db_order){
        //排序条件
        $this->db_order = $db_order;

        //返回对象
        return $this;
    }

    /**
     * @功能：分组条件
     * @param mixed $db_group 分组条件
     * @开发者：cxl
     * @return $this
     */
    public function setDbGroup($db_group){
        //排序条件
        $this->db_group = $db_group;

        //返回对象
        return $this;
    }

    /**
     * @功能：设置查询记录数量
     * @param mixed $db_limit 设置查询记录数量
     * @param mixed $row 设置查询记录数量
     * @开发者：cxl
     * @return $this
     */
    public function setDbLimit($db_limit,$row=null){
        //设置查询记录数量
        if(is_null($row)) {
            $this->db_limit = $db_limit;
        }else {
            $this->db_limit = "{$db_limit},{$row}";
        }

        //返回对象
        return $this;
    }

    /**
     * @功能 插入数据库
     * @开发者：cxl
     * @return mixed
     */
    public function add(){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_data = $this->db_data;  //判断插入数据是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table) || empty($db_data) || !is_array($db_data)){
            //设置错误信息
            self::$db_msg = "插入数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置模型
            self::$module = Db::name($db_table);

            //插入数据
            $insert_id = self::$module->insertGetId($db_data);
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $insert_id;
    }

    /**
     * @功能 修改数据库
     * @开发者：cxl
     * @return mixed
     */
    public function upd(){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_where = $this->db_where;  //判断修改条件是否存在
        $db_data = $this->db_data;  //判断修改数据是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table) || empty($db_where) || empty($db_data) || !is_array($db_data)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置模型
            self::$module = Db::name($db_table);

            //修改数据
            $update_id = self::$module->where($db_where)->update($db_data);
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $update_id;
    }

    /**
     * @功能 删除数据
     * @开发者：cxl
     * @return mixed
     */
    public function del(){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_where = $this->db_where;  //判断删除条件是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table) || empty($db_where)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置模型
            self::$module = Db::name($db_table);

            //删除数据
            $delete_id = self::$module->where($db_where)->delete();
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $delete_id;
    }

    /**
     * @功能 单条数据查询
     * @param bool $lock 判断是否锁表
     * @开发者：cxl
     * @return mixed
     */
    public function row($lock=false){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_join = $this->db_join;    //判断关联条件是否存在
        $db_filed = $this->db_filed;  //判断查询字段是否存在
        $db_where = $this->db_where;  //判断查询条件是否存在
        $db_group = $this->db_group;  //判断分组条件是否存在
        $db_order = $this->db_order;  //判断排序字段是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置主表名称
            self::$module = Db::name($db_table);

            //判断是否为单表操作
            if(is_array($db_join)){
                //循环设置关联表数据
                foreach($db_join as $item){
                    //判断数据是否正确
                    if(is_array($item) && in_array(count($item),array(2,3))){
                        //判断修正后是否为空
                        if(!is_string($item[1])){
                            //设置错误信息
                            self::$db_msg = "设置关联条件失败！";

                            //写数据库操作错误日志
                            ILog::DbLog(self::$db_msg);

                            //变量初期化
                            $this->dbClear();

                            //返回错误
                            return false;
                        }

                        //判断数组长度
                        if(count($item) == 2){
                            self::$module = self::$module->join($item[0],$item[1]);
                        }else{
                            self::$module = self::$module->join($item[0],$item[1],$item[2]);
                        }
                    }else{
                        //设置错误信息
                        self::$db_msg = "关联表参数错误！";

                        //写数据库操作错误日志
                        ILog::DbLog(self::$db_msg);

                        //变量初期化
                        $this->dbClear();

                        //返回错误
                        return false;
                    }
                }

                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)){
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->where($db_where)->order($db_order)->lock(true)->find();
                        }else{
                            $info = self::$module->where($db_where)->order($db_order)->find();
                        }
                    }else {
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->where($db_where)->order($db_order)->group($db_group)->lock(true)->find();
                        }else {
                            $info = self::$module->where($db_where)->order($db_order)->group($db_group)->find();
                        }
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)){
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->lock(true)->find();
                        }else{
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->find();
                        }
                    }else {
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->group($db_group)->lock(true)->find();
                        }else{
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->group($db_group)->find();
                        }
                    }
                }
            }else{
                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)){
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->where($db_where)->order($db_order)->lock(true)->find();
                        }else{
                            $info = self::$module->where($db_where)->order($db_order)->find();
                        }
                    }else {
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->where($db_where)->order($db_order)->group($db_group)->lock(true)->find();
                        }else{
                            $info = self::$module->where($db_where)->order($db_order)->group($db_group)->find();
                        }
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)){
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->lock(true)->find();
                        }else{
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->find();
                        }
                    }else {
                        //判断是否需要锁表
                        if($lock) {
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->group($db_group)->lock(true)->find();
                        }else{
                            $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->group($db_group)->find();
                        }
                    }
                }
            }
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $info;
    }

    /**
     * @功能 单条数据条数
     * @开发者：cxl
     * @return mixed
     */
    public function con(){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_join = $this->db_join;    //判断关联条件是否存在
        $db_filed = $this->db_filed;  //判断查询字段是否存在
        $db_where = $this->db_where;  //判断查询条件是否存在
        $db_group = $this->db_group;  //判断分组条件是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置主表名称
            self::$module = Db::name($db_table);

            //判断是否为单表操作
            if(is_array($db_join)){
                //循环设置关联表数据
                foreach($db_join as $item){
                    //判断数据是否正确
                    if(is_array($item) && in_array(count($item),array(2,3))){
                        //判断修正后是否为空
                        if(!is_string($item[1])){
                            //设置错误信息
                            self::$db_msg = "设置关联条件失败！";

                            //写数据库操作错误日志
                            ILog::DbLog(self::$db_msg);

                            //变量初期化
                            $this->dbClear();

                            //返回错误
                            return false;
                        }

                        //判断数组长度
                        if(count($item) == 2){
                            self::$module = self::$module->join($item[0],$item[1]);
                        }else{
                            self::$module = self::$module->join($item[0],$item[1],$item[2]);
                        }
                    }else{
                        //设置错误信息
                        self::$db_msg = "关联表参数错误！";

                        //写数据库操作错误日志
                        ILog::DbLog(self::$db_msg);

                        //变量初期化
                        $this->dbClear();

                        //返回错误
                        return false;
                    }
                }

                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)){
                        $info = self::$module->where($db_where)->count();
                    }else {
                        $info = self::$module->where($db_where)->group($db_group)->count();
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)){
                        $info = self::$module->field($db_filed)->where($db_where)->count();
                    }else {
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->count();
                    }
                }
            }else{
                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)){
                        $info = self::$module->where($db_where)->count();
                    }else {
                        $info = self::$module->where($db_where)->group($db_group)->count();
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)){
                        $info = self::$module->field($db_filed)->where($db_where)->count();
                    }else {
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->count();
                    }
                }
            }
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $info;
    }

    /**
     * @功能 列表查询
     * @开发者：cxl
     * @return mixed
     */
    public function sel(){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_join = $this->db_join;    //判断关联条件是否存在
        $db_filed = $this->db_filed;  //判断查询字段是否存在
        $db_where = $this->db_where;  //判断查询条件是否存在
        $db_group = $this->db_group;  //判断分组条件是否存在
        $db_order = $this->db_order;  //判断排序字段是否存在
        $db_limit = $this->db_limit;  //判断记录条数是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        try {
            //设置主表名称
            self::$module = Db::name($db_table);

            //判断是否为单表操作
            if(is_array($db_join)){
                //循环设置关联表数据
                foreach($db_join as $item){
                    //判断数据是否正确
                    if(is_array($item) && in_array(count($item),array(2,3))){
                        //判断修正后是否为空
                        if(!is_string($item[1])){
                            //设置错误信息
                            self::$db_msg = "设置关联条件失败！";

                            //写数据库操作错误日志
                            ILog::DbLog(self::$db_msg);

                            //变量初期化
                            $this->dbClear();

                            //返回错误
                            return false;
                        }

                        //判断数组长度
                        if(count($item) == 2){
                            self::$module = self::$module->join($item[0],$item[1]);
                        }else{
                            self::$module = self::$module->join($item[0],$item[1],$item[2]);
                        }
                    }else{
                        //设置错误信息
                        self::$db_msg = "关联表参数错误！";

                        //写数据库操作错误日志
                        ILog::DbLog(self::$db_msg);

                        //变量初期化
                        $this->dbClear();

                        //返回错误
                        return false;
                    }
                }

                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->where($db_where)->order($db_order)->limit($db_limit)->select();
                    }else{
                        $info = self::$module->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->select();
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->limit($db_limit)->select();
                    }else{
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->select();
                    }
                }
            }else{
                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->where($db_where)->order($db_order)->limit($db_limit)->select();
                    }else{
                        $info = self::$module->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->select();
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->limit($db_limit)->select();
                    }else{
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->select();
                    }
                }
            }
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //返回值
        return $info;
    }

    /**
     * @功能 列表查询
     * @param $page_row int 每页记录条数
     * @param $page_total mixed 总记录数
     * @开发者：cxl
     * @return mixed
     */
    public function pag($page_row=20,$page_total=false){
        //判断参数是否存在
        $db_table = $this->db_table;  //判断表名称是否存在
        $db_join = $this->db_join;    //判断关联条件是否存在
        $db_filed = $this->db_filed;  //判断查询字段是否存在
        $db_where = $this->db_where;  //判断查询条件是否存在
        $db_group = $this->db_group;  //判断分组条件是否存在
        $db_order = $this->db_order;  //判断排序字段是否存在
        $db_limit = $this->db_limit;  //判断记录条数是否存在

        //判断修正后是否为空
        if(empty($db_table) || !is_string($db_table)){
            //设置错误信息
            self::$db_msg = "修改数据参数错误！";

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //添加数据
        self::$module = null;
        try {
            //设置主表名称
            self::$module = Db::name($db_table);

            //判断是否为单表操作
            if(is_array($db_join)){
                //循环设置关联表数据
                foreach($db_join as $item){
                    //判断数据是否正确
                    if(is_array($item) && in_array(count($item),array(2,3))){
                        //判断修正后是否为空
                        if(!is_string($item[1])){
                            //设置错误信息
                            self::$db_msg = "设置关联条件失败！";

                            //写数据库操作错误日志
                            ILog::DbLog(self::$db_msg);

                            //变量初期化
                            $this->dbClear();

                            //返回错误
                            return false;
                        }

                        //判断数组长度
                        if(count($item) == 2){
                            self::$module = self::$module->join($item[0],$item[1]);
                        }else{
                            self::$module = self::$module->join($item[0],$item[1],$item[2]);
                        }
                    }else{
                        //设置错误信息
                        self::$db_msg = "关联表参数错误！";

                        //写数据库操作错误日志
                        ILog::DbLog(self::$db_msg);

                        //变量初期化
                        $this->dbClear();

                        //返回错误
                        return false;
                    }
                }

                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->where($db_where)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }else{
                        $info = self::$module->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }else{
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }
                }
            }else{
                //判断是否选择字段
                if(empty($db_filed)) {
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->where($db_where)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }else{
                        $info = self::$module->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }
                }else{
                    //判断分组条件
                    if(empty($db_group)) {
                        $info = self::$module->field($db_filed)->where($db_where)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }else{
                        $info = self::$module->field($db_filed)->where($db_where)->group($db_group)->order($db_order)->limit($db_limit)->paginate($page_row,$page_total);
                    }
                }
            }
        }catch (Exception $error){
            //设置错误信息
            self::$db_msg = $error->getMessage();
            self::$db_sql = self::$module->getLastSql();

            //写数据库操作错误日志
            ILog::DbLog(self::$db_msg,self::$db_sql);

            //变量初期化
            $this->dbClear();

            //返回错误
            return false;
        }

        //变量初期化
        $this->dbClear();

        //整理数据
        $item = $info->items();//取得数据列表
        $render = $info->render();//取得分页信息

        //设置分页信息
        Config::set('page_html',$render);

        //返回值
        return $item;
    }

    /**
     * @功能 启动事物
     * @开发者：cxl
     */
    public static function dbStartTrans(){
        Db::startTrans();
    }

    /**
     * @功能 用于非自动提交状态下面的查询提交
     * @开发者：cxl
     * @throws PDOException
     */
    public static function dbCommit(){
        Db::commit();
    }

    /**
     * @功能 事务回滚
     * @开发者：cxl
     * @throws PDOException
     */
    public static function dbRollback(){
        Db::rollback();
    }

    /**
     * @功能：变量初期化
     * @开发者：cxl
     * @return $this
     */
    private function dbClear(){
        //设置表名称
        $this->db_table = null;
        $this->db_join = null;
        $this->db_data = null;
        $this->db_where = null;
        $this->db_filed = null;
        $this->db_group = null;
        $this->db_order = null;
        $this->db_limit = null;

        //返回对象
        return $this;
    }

    /**
     * @功能 取得错误信息
     * @开发者：cxl
     * @return string
     */
    public static function getDbError(){
        return self::$db_msg;
    }
}
