<?php
namespace cn\atd3;

class User
{
    public static $uc;
    public function __construct(string $select='default')
    {
        $name='cn\\atd3\\adapter\\'.ucfirst($select).'Adapter';
        self::$uc=new $name;
    }

    public function checkNameExist(string $name):bool
    {
        return self::$uc->checkNameExist($name);
    }

    public static function checkEmailExist(string $email):bool
    {
        return self::$uc->checkEmailExist($email);
    }

    public static function getFaildTimes():bool{
        return Session::set('faild_times', 0);
    }
}
