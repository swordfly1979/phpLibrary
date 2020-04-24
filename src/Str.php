<?php

namespace swordfly1979;

class Str
{
    /**
     * 浏览器友好的变量输出
     * @param mixed $vars 要输出的变量
     * @return void
     */
    public static function dump(...$vars)
    {
        ob_start();
        var_dump(...$vars);

        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

        if (PHP_SAPI == 'cli') {
            $output = PHP_EOL . $output . PHP_EOL;
        } else {
            if (!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, ENT_SUBSTITUTE);
            }
            $output = '<pre>' . $output . '</pre>';
        }

        echo $output;
    }
    /*
    * 生成随机字符串
    * @param int $length 生成随机字符串的长度
    * param int $guize 随机字符串包含的字符，如包含多个累加即可（1数字，2为小写字母，4大写字母，8特殊字符）
    * $length生成的随机字符串长度
    */
    public static function random_str($length = 32, $guize = 1)
    {
        $str_int = '0123456789876543210';
        $str_str = 'abcdefghijklmnopqrstuvwxyz';
        $str_special = '$#&@()-[]^~';
        $str = '';
        if ($guize & 1) {
            $str .= $str_int;
        }
        if ($guize & 2) {
            $str .= $str_str;
        }
        if ($guize & 4) {
            $str .= strtoupper($str_str);
        }
        if ($guize & 8) {
            $str .= $str_special;
        }
        while (strlen($str) < $length) {
            $str .= str_shuffle($str);
        }
        return substr(str_shuffle($str), 0, $length);
    }

    /** 
     * 判断字符串是否符合手机号码格式 
     * 移动号段: 134,135,136,137,138,139,147,150,151,152,157,158,159,170,178,182,183,184,187,188 
     * 联通号段: 130,131,132,145,155,156,170,171,175,176,185,186 
     * 电信号段: 133,149,153,170,173,177,180,181,189 
     * @param str $mobile 要验证的手机号码
     * @param int $eccurate 是否精确验证
     * @return int
     */
    public static function verify_mobile($mobile = '', $accurate = 0)
    {
        $telRegex = "/^1\d{10}$/";
        if ($accurate != 0) {
            $telRegex = "/$13[0-9]|14[5,7,9]|15[^4]|18[0-9]|17[0,1,3,5,6,7,8]\\d{8}$/"; // "[1]"代表第1位为数字1，"[358]"代表第二位可以为3、5、8中的一个，"\\d{9}"代表后面是可以是0～9的数字，有9位。 
        }
        return preg_match($telRegex, $mobile);
    }

    /**
     * 是否邮箱格式
     * @param str $email　要验证的邮箱
     */
    public static function verify_email($email = '')
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/';
        return preg_match($regex, $email);
    }
}
