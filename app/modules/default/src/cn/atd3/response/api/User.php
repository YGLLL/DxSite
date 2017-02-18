<?php
namespace cn\atd3\response\api;

// use namespace
use suda\core\Request;
// database query
use suda\core\Query;
// site cookie
use suda\core\Cookie;
// site session
use suda\core\Session;
use cn\atd3\Api;

/**
* visit url /api/user[/{method}] as GET method to run this class.
* you call use _I('user_api',Array) to create path.
* @template: default:api/user.tpl.html
* @name: user_api
* @url: /api/user[/{method}]
* @param: method:string,
*/
class User extends \suda\core\Response
{
    public function onRequest(Request $request)
    {
        return $this->json(self::userApi($request));
    }

    // pretest router
    public function onPreTest($router):bool
    {
        return true;
    }

    // action when error
    public function onPreTestError($router)
    {
        echo 'onPreTestError';
        return true;
    }
    public function userApi($request)
    {
        $method=$request->get()->method('default');
        $data=$request->isJson()?$request->json():$request->post();
        
        switch ($method) {
            case 'check_name':
                return Api::checkCallback($data, ['name'], function ($name) {
                    return \cn\atd3\db\User::checkName($name);
                });
            case 'check_email':
                return Api::checkCallback($data, ['email'], function ($email) {
                    return \cn\atd3\db\User::checkEmail($email);
                });
            case 'id2name':
                return Api::checkCallback($data, ['id'=>['int']], function ($id) {
                    return \cn\atd3\db\User::id2name($id);
                });
            case 'signup':
            return Api::checkCallback($data, ['name', 'passwd', 'email'], function ($name, $passwd, $email) {
                return \cn\atd3\User::signup($name, $email, $passwd);
            });
            case 'signin':
            return Api::checkCallback($data, ['name', 'passwd', 'remember'=>['int', false], 'code'=>['string', '']], function ($name, $passwd, $remember, $code) {
                if (Session::get('signin_fail', 0) > 2) {
                    // 登陆失败次数过多
                    if ($code) {
                        if (Session::get('verify_fail', 0) > 9) {
                            return new api\Error('signinForbidden', 'Forbidden');
                        }
                        if (!VerifyImage::checkCode($code)) {
                            Session::set('verify_fail', Session::get('verify_fail', 0)+ 1, Application::getSetting('verify_fail', 10));
                            return new api\Error('failVerify', 'image verify error');
                        }
                    } else {
                        return new  api\Error('signinFail', 'too much times, need code');
                    }
                }
                if ($return=\cn\atd3\User::signIn($name, $passwd, $remember)) {
                    Session::set('signin_fail', 0);
                    Session::set('verify_fail', 0);
                    return $return;
                } else {
                    Session::set('signin_fail', Session::get('signin_fail', 0)+ 1, Application::getSetting('sigin_fail', 300));
                    return false;
                }
            });
            case 'signout':
                return ['return'=>\cn\atd3\User::signOut()];
            case 'whoami':
                return ['return'=>\cn\atd3\User::getSignInUserId()];
            case 'beat':
                return ['return'=>\cn\atd3\User::heartBeat()];
            case 'baseinfo':
                return ['return'=>\cn\atd3\User::baseInfo()];
            case 'verified':
                return ['return'=> \cn\atd3\User::verified()];
            case 'verifiy_token_dev':
                return ['return'=> \cn\atd3\User::verifyToken()];
            case 'verify_email':
            return Api::checkCallback($request->get(), ['token', 'value', 'id'], function ($token, $value, $id) {
                return \cn\atd3\db\User::verify($id, $token, $value);
            });
            default:
                return ['usage'=>'i dont know!'];
    }
    }
}
