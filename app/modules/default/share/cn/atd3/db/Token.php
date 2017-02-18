<?php
namespace db;

use Query;
use Request;

class Token
{
    // 生成令牌
    protected static function generate(int $user, string $tokenname)
    {
        static $mis='5246-687261-5852-6C';
        return md5('DXCore-'.$user.'-'.microtime(true).'-'.$mis.'-'.$tokenname);
    }

    // 创建令牌
    public static function create(int $user, int $client, string $client_token, string $value=null)
    {
        // 客户端可用
        if ($get=Client::check($client, $client_token)) {
            // 存在同名Token则更新
            if ($fetch=Query::where('token', ['id', 'value'], '`user`=:user AND `client`=:client AND `expire` > UNIX_TIMESTAMP()  AND LENGTH(`value`) = 32', ['user'=>$user, 'client'=>$client])->fetch()) {
                return self::refresh($fetch['id'], $client, $client_token, $fetch['value']);
            } else { // 创建新Token
                $verify=self::generate($user, $client);
                if (is_null($value)) {
                    $value=self::generate($user, $verify);
                }
                $time=time();
                $token=Query::insert('token', ['user'=>$user, 'token'=>$verify, 'time'=>$time, 'ip'=>Request::ip(), 'client'=>$client, 'expire'=>$time + $get['beat'], 'value'=>$value]);
                return ['id'=>$token,'token'=>$verify,'time'=>$time,'value'=>$value];
            }
        }
        return false;
    }

    // 刷新过期时间
    public static function refresh(int $id, int $client, string $client_token, string $value, string $refresh=null)
    {
        if ($get=Client::check($client, $client_token)) {
            $new =self::generate($id, $value);
            if (is_null($refresh)) {
                $refresh=self::generate($id, $new);
            }
            if (Query::update('token', 'expire = :time , token=:new_token,value=:refresh', 'id=:id AND UNIX_TIMESTAMP() < `time` + :alive AND value = :value ', ['id'=>$id, 'value'=>$value, 'new_token'=>$new, 'refresh'=>$refresh, 'time'=>time() + $get['beat'], 'alive'=>$get['alive']])) {
                return  ['id'=>$id, 'token'=>$new, 'time'=>time() + $get['beat'] ,'value'=>$refresh];
            }
        }
        return false;
    }

    // 验证令牌值
    public static function verifyValue(int $id, string $token, string $value)
    {
        return ($user=Query::where('token', 'user', '`id` =:id AND `expire` > UNIX_TIMESTAMP() AND LOWER(token) = LOWER(:token) AND `value` =:value', ['id'=>$id, 'token'=>$token, 'value'=>$value])->fetch())?$user['user']:false;
    }

    // 验证令牌是否过期
    public static function verify(int $id, string $token)
    {
        return Query::where('token', 'user', 'id =:id AND `expire` > UNIX_TIMESTAMP() AND LOWER(token) = LOWER(:token) ', ['id'=>$id, 'token'=>$token ])->fetch();
    }
        // 创建令牌
    public static function verifyCreate(int $user, int $client, string $client_token, string $value)
    {
        // 客户端可用
        if ($get=Client::check($client, $client_token)) {
            // 已存在Token则更新值
            if ($fetch=Query::where('token', ['id', 'value'], '`user`=:user AND `client`=:client AND `expire` > UNIX_TIMESTAMP()  AND LENGTH(`value`) < 32', ['user'=>$user, 'client'=>$client])->fetch()) {
                return self::refresh($fetch['id'], $client, $client_token,$fetch['value'],$value);
            } else { // 创建新Token
                $verify=self::generate($user, $client);
                $time=time();
                $token=Query::insert('token', ['user'=>$user, 'token'=>$verify, 'time'=>$time, 'ip'=>Request::ip(), 'client'=>$client, 'expire'=>$time + $get['alive'], 'value'=>$value]);
                return ['id'=>$token,'token'=>$verify,'time'=>$time,'value'=>$value];
            }
        }
        return false;
    }
    // 删除令牌
    public static function delete(int $id, string $token)
    {
        return Query::update('token', '`expire`=UNIX_TIMESTAMP()', ['id'=>$id, 'token'=>$token]);
    }
}
