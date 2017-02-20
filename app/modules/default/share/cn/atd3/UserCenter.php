<?php
namespace cn\atd3;

use suda\core\Query;

// 用户中心
// 用户中心适配器：
// 用于适配其他用户中心的操作（exp:dz）

class UserCenter implements \cn\atd3\UserCenterAdapter
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

    public static function checkEmailExist(string $email):bool
    {
        return Query::where('user', 'id', 'LOWER(email) = LOWER(:email)', ['email'=>$email])->fetch()?true:false;
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
            'password'=> password_hash($password, PASSWORD_DEFAULT),
            'group'=>$group,
            'available'=>false,
            'email'=>$email,
            'ip'=>$ip,
            ]);
    }

    public static function editUser(int $uid, string $name, string $password, string $email, int $group):bool
    {
        $sets=[];
        if ($name) {
            $sets['name']=$name;
        }
        if ($password) {
            $sets['password']=password_hash($password, PASSWORD_DEFAULT);
        }
        if ($email) {
            $sets['email']=$email;
        }
        if ($group) {
            $sets['group']=$group;
        }
        return Query::updata('user', $sets, ['id'=>$uid])->fetch()?true:false;
    }

    public static function deleteUser(array $uidarray):bool
    {
        return Query::delete('user', ['id'=>$uidarray]);
    }

    public static function getUser(int $page, int $counts):array
    {
        return ($fetch=Query::where('user', ['id', 'name', 'email', 'available', 'ip'], '1', [], [$page, $count])->fetchAll())?$fetch:[];;
    }

    public static function getUserById(array $uid):array
    {
        return ($fetch=Query::where('user', ['id', 'name', 'email', 'available', 'ip'], ['id'=>$uidarray])->fetchAll())?$fetch:[];;
    }

    public static function getUserByName(array $names):array
    {
        return ($fetch=Query::where('user', ['id', 'name', 'email', 'available', 'ip'], ['name'=>$names])->fetchAll())?$fetch:[];;
    }

    public static function getUserByEmail(array $emails):array
    {
        return ($fetch=Query::where('user', ['id', 'name', 'email', 'available', 'ip'], ['email'=>$emails])->fetchAll())?$fetch:[];;
    }

    // 数据转换
    public static function id2name(array $ids):array
    {
        return ($fetch=Query::where('user', ['id', 'name'], ['id'=>$ids])->fetchAll())?$fetch:[];;
    }
    public static function name2id(array $names):array
    {
        return ($fetch=Query::where('user', ['id', 'name'], ['names'=>$names])->fetchAll())?$fetch:[];;
    }

    public static function email2id(array $email):array
    {
        return ($fetch=Query::where('user', [ 'id', 'email'], ['email'=>$email])->fetchAll())?$fetch:[];;
    }
    public static function id2email(array $ids):array
    {
        return ($fetch=Query::where('user', ['id', 'email'], ['id'=>$ids])->fetchAll())?$fetch:[];;
    }

    // 权限操作
    public static function getUserPermission(int $uid):array
    {
        // 获取权限
        if ($fetch=Query::select('user_group', 'auths', ' JOIN `#{user}` ON `#{user}`.`id` = :id  WHERE `user` = :id  or `#{user_group}`.`id` =`#{user}`.`group` LIMIT 1;', ['id'=>$id])->fetch()) {
            return ($auths=json_decode($fetch['auths']))?$auths:[];
        }
        return [];
    }

    public static function setUserPermission(int $id, array $permissions):bool
    {
        try {
            Query::begin();
            $older=self::getPermission($id);
            if ($older===false) {
                $older=[];
            }
            $permissions=array_merge($older, $permissions);
            if ($fetch=Query::where('user_group', 'id', ['user'=>$id])->fetch()) {
                Query::update('user_group', ['auths'=>json_encode($permissions)], ['id'=>$fetch['id']]);
            } else {
                Query::insert('user_group', ['auths'=>json_encode($permissions), 'user'=>$id, 'name'=>'User:'.self::id2name($id)]);
            }
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return true;
    }

    //-------------------
    //   分组操作
    //-------------------
    public static function getGroupPermission(int $group):array
    {
        // 获取权限
        if ($fetch=Query::select('user_group', 'auths', ['id'=>$group])->fetchAll()) {
            return ($auths=json_decode($fetch['auths']))?$auths:[];
        }
        return [];
    }

    public static function setGroupPermission(array $groups, array $permissions):bool
    {
        try {
            Query::begin();
            $older=self::getPermission($id);
            if ($older===false) {
                $older=[];
            }
            $permissions=array_merge($older, $permissions);
            Query::update('user_group', ['auths'=>json_encode($permissions)], ['id'=>$groups]);
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return true;
    }

    public static function addGroup(string $gname, array $Permission):int
    {
        return Query::insert('user_group', ['name'=>$gname, 'auths'=>json_encode($permissions)]);
    }

    public static function deleteGroup(array $groups):bool
    {
        return Query::delete('user_group', ['id'=>$groups])->fetch();
    }

    public static function gid2name(array $ids):array
    {
        return ($fetch=Query::select('user_group', ['id', 'name'], ['id'=>$ids])->fetchAll())?$fetch:[];
    }

    public static function gname2id(array $names):int
    {
        return ($fetch=Query::select('user_group', ['id', 'name'], ['name'=>$names])->fetchAll())?$fetch:[];
    }
    public static function getGroupByID(array $ids):array
    {
    }
    public static function getGroup(int $page, int $count):array
    {
    }
}
