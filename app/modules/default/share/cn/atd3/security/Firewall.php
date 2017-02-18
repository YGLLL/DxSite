<?php
namespace cn\atd3\security;

use Request;

/**
* 应用防火墙模块
*/
class Firewall
{
    public static function listen(Request $rq)
    {
        return true; // 防火墙关闭
    }
    public static function error()
    {
        echo 'Web Application FireWall';
    }
}
