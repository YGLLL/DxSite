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
        $path=RESOURCE_DIR.'/session';
        Storage::mkdirs($path);
        session_id(md5(Request::signature().Token::get('client')));
        session_save_path($path);
        session_name(conf('session.name', 'token_session'));
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
