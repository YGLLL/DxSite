<?php
namespace cn\atd3;

class User
{
    public static $uc;
    public function __construct($uc)
    {
        if ($uc) {
            self::$uc=$uc;
        } else {
            self::$uc=new UserCenter;
        }
    }

    public function checkNameExist(string $name):bool
    {
        return self::$uc->checkNameExist($name);
    }

    public static function checkEmailExist(string $email):bool
    {
        return self::$uc->checkEmailExist($email);
    }

    public static function getFaildTimes():bool
    {
        return Session::set('faild_times', 0);
    }

    public function getUserId()
    {
        if (Token::has('user')) {
            $token=base64_decode(Token::get('user'));
            if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
                if ($uid= self::$uc->tokenAvailable(intval($match[1]), $match[2])) {
                    return  intval($uid['user']);
                }
            }
        }
        return 0;
    }
}
