<?php
namespace security;

/**
* 发帖防水墙模块
*/
class Waterwall
{
    public static function post(string $message)
    {
        return true; // 防水墙模块
    }
}
