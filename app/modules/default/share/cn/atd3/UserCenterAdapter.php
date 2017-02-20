<?php
namespace cn\atd3;

interface UserCenterAdapter
{
    //-------------------
    //   用户基本操作
    //-------------------
    // 数据检验
    public static function checkNameExist(string $name):bool;
    public static function checkEmailExist(string $email):bool;
    public static function checkNameFormat(string $name):bool;
    public static function checkEmailFormat(string $email):bool;
    public static function checkPassword(string $name, string $password):bool;
    public static function checkEmailAvailable(int $uid):bool;
    public static function setEmailAvailable(array $uid, bool $available=true):bool;

    // 基本操作
    public static function addUser(string $name, string $password, string  $email, int $group, string $ip):int;
    public static function editUser(int $uid, string $name, string $password, string $email, int $group):bool;
    public static function deleteUser(array $uidarray):bool;
    public static function getUser(int $page, int $counts):array;
    public static function getUserById(array $uid):array;
    public static function getUserByName(array $names):array;
    public static function getUserByEmail(array $emails):array;

    // 数据转换
    public static function id2name(array $ids):array;
    public static function name2id(array $names):array;
    public static function email2id(array $email):array;
    public static function id2email(array $ids):array;

    // 权限操作
    public static function getUserPermission(int $uid):array;
    public static function setUserPermission(int $id, array $permissions):bool;

    //-------------------
    //   分组操作
    //-------------------
    public static function getGroupPermission(array $groups):array;
    public static function setGroupPermission(array $groups, array $Permission):bool;
    public static function addGroup(string $gname, string $comments, array $Permission):int;
    public static function deleteGroup(array $groups):bool;

    public static function gid2name(array $ids):array;
    public static function gname2id(array $names):int;
    public static function getGroupByID(array $ids):array;
    public static function getGroup(int $page, int $count):array;

    //------------------
    //  客户端分配操作
    //------------------
}
