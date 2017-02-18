<?php
namespace db;

use Query;
use Request;

class User
{
    /**
     * 验证邮箱
     */
    public static function checkEmail(string $email):bool
    {
        return Query::where('user', 'id', 'LOWER(email) = LOWER(:email)', ['email'=>$email])->fetch()?true:false;
    }

    public static function checkName(string $name):bool
    {
        return Query::where('user', 'id', 'LOWER(name) = LOWER(:name)', ['name'=>$name])->fetch()?true:false;
    }
    
    public static function count()
    {
        return Query::count('user');
    }

    public static function signUp(string $name, string $email, string $password, int $client, string $client_token, string $value='')
    {
        try {
            Query::begin();
            $id=Query::insert('user', ['name'=>$name, 'password'=>password_hash($password, PASSWORD_DEFAULT), 'email'=>$email]);
            $token=Token::verifyCreate($id, $client, $client_token, $value);
            $token['user_id']=$id;
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return $token;
    }

    public static function signIn(string $name, string $password, int $client, string $client_token)
    {
        $token=false;
        try {
            Query::begin();
            if ($fetch=Query::where('user', ['password', 'id'], ['name'=>$name])->fetch()) {
                if (password_verify($password, $fetch['password'])) {
                    $token=Token::create($fetch['id'], $client, $client_token);
                    $token['user_id']=$fetch['id'];
                }
            }
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return $token;
    }

    public static function signOut(int $token_id, string $token)
    {
        return Token::delete($token_id, $token);
    }
    
    public static function isSignin(int $token_id, string $token)
    {
        return Token::verify($token_id, $token);
    }

    // 心跳刷新
    public static function heartBeat(int $token_id, string $token)
    {
        return Token::refresh($token_id, $token);
    }
    /**
    * @self:id
    */
    public static function setAvatar(int $id, int $resource_id)
    {
        return Query::update('user', ['avatar'=>$resource_id], ['id'=>$id]);
    }
    /**
    * @Auth:admin
    */
    public static function setGroup(int $id, int $group)
    {
        return Query::update('user', ['group'=>$group], ['id'=>$id]);
    }

    public static function hasPermission(int $id,$names)
    {
        $names=is_array($names)?$names:[$names];
        if ($get=self::getPermission($id)) {
            foreach ($names as $name) {
                if (!in_array($name,$get)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    
    public static function getPermission(int $id)
    {
        // 获取权限
        if ($fetch=Query::select('user_group', 'auths', ' JOIN `#{user}` ON `#{user}`.`id` = :id  WHERE `user` = :id  or `#{user_group}`.`id` =`#{user}`.`group` LIMIT 1;', ['id'=>$id])->fetch()) {
            return ($auths=json_decode($fetch['auths']))?$auths:false;
        }
        return false;
    }

    public static function setPermission(int $id,$permissions)
    {
        $permissions=is_array($permissions)?$permissions:[$permissions];
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

    public static function verified(int $id)
    {
        return Query::where('user', 'id', ['id'=>$id, 'verify_email'=>1])->fetch();
    }

    public static function verify(int $id,string $token,string $value){
        if($user=Token::verifyValue($id,$token,$value)){
            return  Query::update('user',['verify_email'=>1],['id'=>$user]);
        }
        return false;
    }

    public static function id2name(int $id)
    {
        return ($get=Query::where('user', ['name'], ['id'=>$id])->fetch())?$get['name']:'';
    }

    public static function get(int $id)
    {
        return ($get=Query::where('user', ['id', 'name', 'email', 'group', 'verify_email', 'avatar'], ['id'=>$id])->fetch()) ? $get  : false;
    }

    public static function verifyMail(int $id,string $code){

    }
}
