<?php
/**
* 网站的用户操作接口
*/
class User
{
    public static function signUp(string $name, string $email, string $passwd)
    {
        // 获取网站操作权限
        $client=Mongci::getClient();
        // 生成6位邮箱验证码
        $code=substr(base64_encode(md5('5246-687261-5852-6C'+time())), 0, 6);
        if ($get=db\User::signUp($name, $email, $passwd, $client['id'], $client['token'], $code)) {
            Token::set('user', base64_encode($get['id'].'.'.$get['token']));
            return $get['user_id'];
        }
        return false;
    }

    public static function signIn(string $name, string $passwd, bool $remember=false)
    {
        // 获取网站操作权限
        $client=Mongci::getClient();
        if ($get=db\User::signIn($name, $passwd, $client['id'], $client['token'])) {
            Token::set('user', base64_encode($get['id'].'.'.$get['token'].'.'.$get['value']), 3600)->session(!$remember)->httpOnly();
            return $get['user_id'];
        }
        return false;
    }

    public static function signOut()
    {
        if (Token::has('user')) {
            $token=base64_decode(Token::get('user'));
            if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
                db\User::signOut(intval($match[0]), $match[1]);
                Cookie::set('user', '', 0);
            }
        }
        return true;
    }

    public static function getSignInUserId()
    {
        if (Token::has('user')) {
            self::heartBeat();
            $token=base64_decode(Token::get('user'));
            if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
                if ($uid=db\Token::verify(intval($match[1]), $match[2])) {
                    return intval($uid['user']);
                }
            }
        }
        return 0;
    }

    public static function heartBeat()
    {
        if (Token::has('user')) {
            $token=base64_decode(Token::get('user'));
            if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
                if ($uid=db\Token::verify(intval($match[1]), $match[2])) {
                    return intval($uid);
                } elseif (isset($match[3])) {
                    // 获取网站操作权限
                    $client=Mongci::getClient();
                    // 一次心跳
                    if ($get=db\Token::refresh(intval($match[1]), intval($client['id']), $client['token'], $match[3])) {
                        Token::set('user', base64_encode($get['id'].'.'.$get['token'].'.'.$get['value']), 3600)->httpOnly();
                        return intval(db\Token::verify($get['id'], $get['token']));
                    }
                }
            }
        }
        return 0;
    }

    public static function baseInfo()
    {
        $id=self::getSignInUserId();
        if ($id) {
            return db\User::get($id);
        }
        return false;
    }
    public static function verified()
    {
        $id=self::getSignInUserId();
        if ($id) {
            return db\User::verified($id);
        }
        return false;
    }
    public static function verifyToken()
    {
        $id=self::getSignInUserId();
        if ($id) {
            $code=substr(base64_encode(md5('5246-687261-5852-6C'+time())), 0, 6);
            // 获取网站操作权限
            $client=Mongci::getClient();
            if ($token=db\Token::verifyCreate($id, $client['id'], $client['token'], $code)) {
                return $token;
            }
        }
        return false;
    }
}
