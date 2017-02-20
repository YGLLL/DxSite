<?php
namespace cn\atd3\adapter;

use suda\core\Query;

// 用户中心
// 用户中心适配器：
// 用于适配其他用户中心的操作（exp:dz）

class DefaultAdapter implements \cn\atd3\UserCenterAdapter
{
    const REG_EMAIL='/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
    const REG_NAME='/^[\w\x{4e00}-\x{9aff}]{4,13}$/u';

     //-------------------
    //   用户基本操作
    //-------------------
    // 数据检验
    public static function checkNameExist(string $name):bool
    {
        return Query::where('user', 'id', 'LOWER(name) = LOWER(:name)', ['name'=>$name])->fetch()?true:false;
    }
    
    // 数据效验
    public static function checkNameFormat(string $name):bool
    {
         return preg_match(REG_NAME, $name);
    }
    public static function checkEmailFormat(string $email):bool
    {
        return preg_match(REG_EMAIL, $email);
    }
    public static function checkPassword(string $name, string $password):bool
    {
          if ($fetch=Query::where('user', ['password'], ['name'=>$name])->fetch()) {
            if (password_verify($password, $fetch['password'])) {
                return true;
            }
        }
        return false;
    }
    public static function checkEmailavailable(int $uid):bool
    {
        return Query::where('user', 'id', ['id'=>$uid, 'available'=>true])->fetch()?true:false;
    }
    public static function setEmailAvailable(array $uid, bool $available=true):bool
    {
        return Query::updata('user', ['available'=>$available], ['id'=>$uid])->fetch()?true:false;
    }

    // 基本操作
    public static function addUser(string $name, string $password, string  $email, int $group, string $ip):int
    {
        return Query::insert('user', [ 
            'name'=>$name,
            'password'=> password_hash($password,PASSWORD_DEFAULT),
            'group'=>$group,
            'available'=>false,
            'email'=>$email,
            'ip'=>$ip,
            ]);
    }
    public static function editUser(int $uid, string $name, string $password, string $email, int $group):bool
    {
    }
    public static function deleteUser(array $uidarray):bool
    {
    }
    public static function getUser(int $page, int $counts):array
    {
    }
    public static function getUserById(array $uid):array
    {
    }
    public static function getUserByName(array $names):array
    {
    }
    public static function getUserByEmail(array $emails):array
    {
    }

    // 数据转换
    public static function id2name(array $ids):array
    {
    }
    public static function name2id(array $names):array
    {
    }
    public static function email2id(array $email):array
    {
    }
    public static function id2email(array $ids):array
    {
    }

    // 权限操作
    public static function getUserPermission(array $uidarray):array
    {
    }
    public static function setUserPermission(array $uid, array $Permission):bool
    {
    }

    //-------------------
    //   分组操作
    //-------------------
    public static function getGroupPermission(array $groups):array
    {
    }
    public static function setGroupPermission(array $groups, array $Permission):bool
    {
    }
    public static function addGroup(string $gname, string $comments, array $Permission):int
    {
    }
    public static function deleteGroup(array $groups):bool
    {
    }

    public static function gid2name(array $ids):array
    {
    }
    public static function gname2id(array $names):int
    {
    }
    public static function getGroupByID(array $ids):array
    {
    }
    public static function getGroup(int $page, int $count):array
    {
    }
}
