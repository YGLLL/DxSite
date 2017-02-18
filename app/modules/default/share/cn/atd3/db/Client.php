<?php
namespace cn\atd3\db;

use Query;

class Client
{
    const ACTIVE=1;//可活动的
    const FREEZE=0;//禁用的
    public static function create(string  $name, string $description='官方令牌',int $beat=60,int $alive=3600, int $state=self::ACTIVE)
    {
        $token=md5(microtime(true));
        $id=Query::insert('token_client', ['name'=>$name, 'description'=>$description, 'time'=>time(),'beat'=>$beat,'alive'=>$alive,'token'=>$token, 'state'=>$state]);
        return ['id'=>$id,'token'=>$token];
    }

    public static function setState(int $id, int $state)
    {
        return Query::update('token_client', ['state'=>$state], ['id'=>$id]);
    }
    public static function get(int $id){
        return ($get=Query::where('token_client', '*', ['id'=>$id])->fetch())?$get:false;
    }
    public static function list(int $state=null,int $page=1,int $per_page=10)
    {
        if (is_null($state)) {
            return Query::where('token_client')->fetchAll();
        }
        return Query::where('token_client', '*', ['state'=>$state],[$page,$per_page])->fetchAll();
    }

    public static function check(int $id, string $token)
    {
        return Query::where('token_client', ['id','alive','beat'], ['id'=>$id, 'token'=>$token, 'state'=>self::ACTIVE])->fetch();
    }
}
