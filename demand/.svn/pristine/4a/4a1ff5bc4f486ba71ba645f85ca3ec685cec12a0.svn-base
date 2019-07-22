<?php

namespace app\common\ext;

class ICryptAES
{
    private $secret_key = '2vomsmdbhj2n2djq'; //密钥
    private $iv = 'irm6ol0edvme2jzv'; //初始向量

    public function getIv(){
        return $this->iv;
    }

    public function getSecretKey(){
        return $this->secret_key;
    }

    /**
    * 加密方法
    * @param string $str
    * @return string
    */
    public function encrypt($str) {
        //AES, 128 CBC模式加密数据
        $str = $this->pkcs5Pad($str);
//        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND); //生成iv
        $encrypt_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->secret_key, $str, MCRYPT_MODE_CBC, $this->iv);
        return $encrypt_str;
    }

    /**
     * 加密方法，加密后base64编码
     * @param string $str
     * @return string
     */
    public function encryptWithBase64Encode($str){
        $encrypt_data = $this->encrypt($str);
        return base64_encode($encrypt_data);
    }

    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    public function decrypt($str) {
        //AES, 128 CBC模式加密数据
//        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
        $encrypt_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->secret_key, $str, MCRYPT_MODE_CBC, $this->iv);
        $encrypt_str = $this->pkcs5Unpad($encrypt_str);
        return $encrypt_str;
    }

    /**
     * 解密方法，解密前先base64解码
     * @param string $str
     * @return string
     */
    public function decryptAfterBase64Decode($str){
        $str = base64_decode($str);
        return $this->decrypt($str);
    }

    /**
     * 填充算法
     * @param string $text
     * @return string
     */
    public function pkcs5Pad($text)
    {
        $blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 移去填充算法
     * @param string $text
     * @return string
     */
    public function pkcs5Unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }
}