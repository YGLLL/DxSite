<?php
/**
*   三次握手   非机器人验证
*   请求认证  ----------------->    服
*            <-----ID----------    务
*   获取认证  ------ID--------->    器 
*            <-----DATA----------       
*   提交验证  -----ID:DATA------>
*            <-------------------
*             redirect or result
*/

namespace db;

use Query;

class Verify
{
                                                                                       
    public static function create(int $alive,string $data)
    {
        return Query::insert('verify',['alive'=>time()+$alive,'data'=>$data]);
    }

    public static function get(int $id)
    {
        // 获取储存的验证数据
        return ($get=Query::where('verify', ['data'], '`id`= :id AND `alive` > UNIX_TIMESTAMP() ',['id'=>$id])->fetch()) ? $get['data'] : ( self::delete($id) && false);
    }
    
    public static function delete(int $id){
        return Query::delete('verify',['id'=>$id]);
    }

    public static function update(int $id,string $data){
       return Query::update('verify',['id'=>$id,'alive'=>time()+$alive,'data'=>$data]); 
    }

}
