<?php


class Upload
{
    const STATE_VERIFY=0;
    const STATE_PUBLISH=1;
    const STATE_PRIVATE=2;
    const STATE_DELETE=3;

    /**
    * 检验文件是否存在于服务器上
    */
    public static function check(string $md5)
    {
        return ($get=Query::where('upload_data','id',['hash'=>$md5])->fetch())?$get['id']:false;
    }

    
}
