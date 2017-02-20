<?php
namespace cn\atd3;
use suda\core\Storage;
use suda\core\Request;

/**
* 基于IP的Session
* 区分clientid
*/
class Session
{
    public static function start()
    {
        $path=DATA_DIR.'/session';
        $id=md5(Request::signature().Request::get()->token);
        // _D()->i('s:'.Request::signature().';id:'.$id.';token:'.Request::get()->token.';','Session');
        Storage::mkdirs($path);
        session_id($id);
        session_save_path($path);
        session_name(conf('session.name', 'session'));
        session_cache_limiter(conf('session.limiter', 'private'));
        session_cache_expire(conf('session.expire',0));
        session_start();
    }

    public static function set(string $name, $value)
    {
        $_SESSION[$name]=$value;
        return isset($_SESSION[$name]);
    }

    public static function get(string $name='', $default=null)
    {
        if ($name) {
            return isset($_SESSION[$name])?$_SESSION[$name]:$default;
        } else {
            return $_SESSION;
        }
    }

    public static function has(string $name)
    {
        return isset($_SESSION[$name]);
    }
    public static function destroy()
    {
        session_unset();
    }
}