<?php
namespace app\common\cache\common;

use app\common\cache\BaseCache;
use app\common\enum\IsDelete;
use app\common\ext\IDb;
use app\common\ext\IRedis;
use app\common\enum\ArticleStatus;

class ISensitiveWordCache extends BaseCache{
    /**
     * @功能：取得缓存数据
     * @开发者：WDD
     * @return array|string
     */
    public static function getInfo(){
        //取得缓存信息
        $info = IRedis::getCallBackString(self::CD_SENSITIVE_WORD,0,function(){

            //实例化对象
            $list = IDb::getInstance('sensitive_word')
                ->setDbWhere(['is_delete'=>IsDelete::No])
                ->sel();
            $data = [];
            foreach ($list as $val){
                $data[$val['word']] = str_pad('',mb_strlen($val['word']),"*");
            }

            //返回值
            return $data;
        },3600);

        //返回值
        return $info;
    }

    /**
     * @功能：删除缓存数据
     * @开发者：WDD
     * @return string
     */
    public static function deleteInfo(){
        return IRedis::delItem(self::CD_SENSITIVE_WORD, 0);
    }

}