<?php
//判断参数是否正确
error_reporting(E_ALL);
$requestBody = file_get_contents("php://input");
if (empty($requestBody)) {
    die('send fail');
}

//解析Git服务器通知过来的JSON信息
$content = json_decode($requestBody, true);

//若是主分支且提交数大于0
if (($content['ref']=='refs/heads/master') && ($content['total_commits_count'] > 0) && ($content['password'] == md5('purong'))) {
    //获取代码
    shell_exec("cd /home/wwwroot/purong.com/");
    shell_exec("git pull origin develop");
    shell_exec("chown -R www:www /home/wwwroot/purong.com/");

    //写日志文件
    file_put_contents("git_hook_log.txt", "同步成功！", FILE_APPEND);
}else{
    //写日志文件
    file_put_contents("git_hook_log.txt", "返回信息错误！", FILE_APPEND);
}