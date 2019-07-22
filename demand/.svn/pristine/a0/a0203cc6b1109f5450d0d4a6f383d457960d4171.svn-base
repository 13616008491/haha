<?php

namespace app\common\ext\util;
class Oss {
    private $access_id = '';
    private $access_key = '';
    private $endpoint = '';
    private $bucket = '';

    private $time_out = 87600;
    private $file_size_min = 0;
    private $file_size_max = 5 * 1024 * 1024;

    public function __construct($access_id, $access_key, $endpoint, $bucket){
        $this->access_id = $access_id;
        $this->access_key = $access_key;
        $this->endpoint = $endpoint;
        $this->bucket = $bucket;
    }

    public function getUploadSignature($policyBase64){
        return base64_encode(hash_hmac('sha1', $policyBase64, $this->access_key, true));
    }

    public function getUploadPolicy(){
        $time = time();
        $policy = [
            'expiration'=>date('Y-m-d',$time+$this->time_out).'T'.date('H:i:s',$time+$this->time_out).'Z',
            'conditions'=>[
                ["content-length-range", $this->file_size_min, $this->file_size_max]
            ],
        ];

        return base64_encode(json_encode($policy));
    }

}