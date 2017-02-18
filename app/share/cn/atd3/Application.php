<?php
namespace cn\atd3;

use cn\atd3\db\Setting;
use suda\core\Storage;
use suda\core\Request;
use suda\core\Hook;

class Application extends \suda\core\Application
{
    public static $request;
    public static $page;
    public static $session;

    public function onRequest(Request $request)
    {
        Session::start();
        self::$request=Request::getInstance();
        self::checkClient(); // 设置客户端验证
        Plugin::boot();
        return true;
    }


    public static function refreshClient()
    {
        if ($get=conf('client')) {
            $token=self::encodeClient($get['token'], $get['id']);
            Token::set('client', $token, 3600);
            return true;
        }
        return false;
    }
    public static function checkClient()
    {
        if (!Token::has('client')) {
            if (!self::refreshClient()) {
                die('App is not available');
            }
        } elseif ($client=self::getClient()) {
            if (!db\Client::check($client['id'], $client['token'])) {
                if (!self::refreshClient()) {
                    die('client is not available');
                }
            }
        } else {
            die('client is not available');
        }
    }

    public static function getClient()
    {
        return self::decodeClient(Token::get('client'));
    }

    public static function encodeClient(string $token, int $id)
    {
        return base64_encode($token.$id);
    }

    public static function decodeClient(string $code)
    {
        $code=base64_decode($code);
        preg_match('/^([a-zA-Z0-9]{32})(\d+)$/', $code, $match);
        return ['token'=>$match[1],'id'=>intval($match[2])];
    }

    public static function getSetting(string $name, $default=null)
    {
        if ($get=Setting::get($name)) {
            return unserialize($get['value']);
        }
        return $default;
    }

    public static function setSetting(string $name, $value)
    {
        return Setting::set($name, serialize($value));
    }
    
    public static function setBaseSet()
    {
        // 评论审核
        self::setSeting('comment_verify', true);
        // 对话存活时长(每五分钟刷新在线状态)
        self::setSeting('session_alive', 300); // 5 分钟
        self::setSeting('sigin_fail', 300); // 登陆失败刷新
        // 超时登陆 (超时多久后不活动则重新登陆)
        self::setSeting('token_alive', 604800); // 7天
        // 设置 Client Id
        self::setSeting('client_id', conf('client.id',1));
    }

    public static function onShutdown()
    {
        Cache::gc();
    }

    public static function request()
    {
        return self::$request;
    }
}
