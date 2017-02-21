<?php
namespace cn\atd3\response\api;

// use namespace
use suda\core\Request;
// database query
use suda\core\Query;
// site cookie
use suda\core\Cookie;
// site session
use cn\atd3\Session;

use cn\atd3\UserCenter;
use cn\atd3\Api;
use cn\atd3\ApiException;
use cn\atd3\Token;
use suda\tool\Value;

/**
* visit url /api/user[/{action}] as all method to run this class.
* you call use _I('user_api',Array) to create path.
* @template: default:api/user.tpl.html
* @name: user_api
* @url: /api/user[/{action}]
* @param: action:string,
*/
class User extends \suda\core\Response
{
    protected $client;
    protected $token;
    protected $uc;
    protected $request;
    public function onRequest(Request $request)
    {
        $this->uc=new UserCenter;
        $this->request=$request;
        if ($request->get()->client && $request->get()->token) {
            if (!$this->uc->checkClient($request->get()->client, $request->get()->token)) {
                return $this->json(['error'=>'client is not available!']);
            }
            $this->client=$request->get()->client;
            $this->token=$request->get()->token;
        } else {
            return $this->json(['error'=>'no api client!']);
        }
        // params if had
        $action=$request->get()->action;
        $help=[
            'url'=>_I('user_api', ['action'=>'usage_action', 'token'=>'token_str', 'client'=>'client_id']),
            'usage'=>[
                'needcode'=>[
                    'comments'=>'查询是否需要验证码。'
                ],
                'signup'=>[
                    'params'=> ['email'=>'string', 'name'=>'string', 'passwd'=>'string', 'code'=>['string', null]],
                    'comments'=>"注册用户,是否需要验证码通过needcode确认。用户名可使用任意字母、数字、下划线和中文，长度不大于13个字符。",
                ],
                'signin'=>[
                    'params'=>['name'=>'string', 'passwd'=>'string', 'code'=>['string', null],'remember'=>['bool',false]],
                    'comments'=>'用户登陆',
                ],
                'checkname'=>[
                    'params'=> ['name'=>'string'],
                    'comments'=>"验证ID是否存在",
                ],
                'checkemail'=>[
                    'params'=> ['email'=>'string'],
                    'comments'=>"验证邮箱是否存在",
                ],
                'info'=>[
                    'comments'=>"获取用户信息",
                ],
                'token'=>[
                    'params'=>['token', 'value'],
                    'comments'=>"验证值（邮箱等）",
                ],
                'beat'=>[
                    'params'=>['token'],
                    'comments'=>"心跳一次",
                ],
            ],
        ];
        try {
            // param values array
            $data=$request->isJson()?new Value($request->json()):($request->isPost()?$request->post():$request->get());
            switch ($action) {
                // 需要验证码
                case 'needcode': return $this->json(['return'=>Session::get('need_code', false)]);
                // 验证姓名
                case 'checkname': Api::check($data, ['name']);return $this->json(['return'=>$this->uc->checkNameExist($data->name)]);
                // 验证邮箱
                case 'checkemail': Api::check($data, ['email']);return $this->json(['return'=>$this->uc->checkEmailExist($data->email)]);
                // 注册
                case 'signup':Api::check($data, ['email', 'name', 'passwd', 'code'=>['string', null]]); return $this->json(self::signup($data->name, $data->email, $data->passwd, $data->code));
                // 登陆
                case 'signin':Api::check($data, ['name'=>'string', 'passwd'=>'string', 'code'=>['string', null], 'remember'=>['bool', false]]); return $this->json(self::signin($data->name, $data->passwd, $data->code));
                // 退出登陆
                case 'signout':return $this->json(['return'=>self::signout()]);
                // 登陆用户
                case 'info':return $this->json(['return'=>self::getUserInfo()]);
                // 验证邮箱
                case 'token':Api::check($data, ['token', 'value']);return $this->json(['return'=>self::available($data->token, $data->value)]);
                // 更新Token
                case 'beat':Api::check($data, ['token']);return $this->json(['return'=>self::beat($data->token)]);
                // 设置用户
                case 'set': Api::check($data, ['email'=>['string', ''], 'avatar'=>['string', '']]);return $this->json(['return'=>self::set($data->email(''), $data->avatar(''))]);
                // 默认输出
                default:return $this->json($help);
            }
        } catch (ApiException $e) {
            return $this->json($e);
        } catch (\Exception $e) {
            return $this->json([ 'Exception'=>$e->getMessage()]);
        }
    }

    // 注册
    protected function signup($name, $email, $passwd, $code)
    {
        if (!$this->uc->checkNameFormat($name)) {
            return new ApiException('nameFormatError', 'You need a right format');
        }
        if (!$this->uc->checkEmailFormat($email)) {
            return new ApiException('emailFormatError', 'You need a right format');
        }
        // 需要验证码却未设置
        if (is_null($code) &&Session::get('need_code', false)) {
            return new ApiException('lackCodeError', 'You need send a  code');
        }
        // ip首次注册不需要验证码
        if (!Session::get('need_code', false)) {
            $id=$this->uc->addUser($name, $passwd, $email, 0, $this->request->ip());
            if ($id) {
                Session::set('need_code', true);
            }
            $get=$this->uc->createToken($id, $this->client, $this->token, $this->request->ip(), 'Code');
            Token::set('user', $token= base64_encode($get['id'].'.'.$get['token']));
            return ['return'=>['uid'=>$id,'token'=>$token]];
        }
        // 二次注册需要验证码
        elseif (Session::get('need_code', false)&& \cn\atd3\VerifyImage::checkCode($code)) {
            $id=$this->uc->addUser($name, $passwd, $email, 0, $this->request->ip());
            $get=$this->uc->createToken($id, $this->client, $this->token, $this->request->ip(), 'Code');
            Token::set('user', $token= base64_encode($get['id'].'.'.$get['token']));
            return ['return'=>['uid'=>$id,'token'=>$token]];
        } else {
            Session::set('need_code', true);
            return new ApiException('codeError', 'You send a error human code');
        }
    }

    protected function signin(string $name, string $passwd, $code, $remember=false)
    {
        if (!$this->uc->checkNameFormat($name)) {
            return new ApiException('nameFormatError', 'You need a right format');
        }
        // 需要验证码却未设置
        if (is_null($code) && Session::set('faild_times', 0)) {
            return new ApiException('lackCodeError', 'You need send a code');
        }
        // ip首次注册不需要验证码
        if (Session::get('faild_times', 0)===0) {
            if ($id=$this->uc->checkPassword($name, $passwd)) {
                $get=$this->uc->createToken($id, $this->client, $this->token, $this->request->ip());
                Token::set('user', base64_encode($get['id'].'.'.$get['token'].'.'.$get['value']), 3600)->session(!$remember)->httpOnly();
                Session::set('faild_times', 0);
                return ['return'=> true];
            } else {
                Session::set('faild_times', Session::get('faild_times', 0)+1);
                return ['return'=> true];
            }
        }
        // 二次注册需要验证码
        elseif (Session::get('faild_times', 0)>=3 && \cn\atd3\VerifyImage::checkCode($code)) {
            if ($id=$this->uc->checkPassword($name, $passwd)) {
                $get=$this->uc->createToken($id, $this->client, $this->token, $this->request->ip());
                Session::set('faild_times', 0);
                Token::set('user', base64_encode($get['id'].'.'.$get['token'].'.'.$get['value']), 3600)->session(!$remember)->httpOnly();
                return ['return'=> true];
            }
            return ['return'=> true];
        } else {
            Session::set('need_code', true);
            return new ApiException('codeError', 'You send a error human code');
        }
    }
    protected function getUserInfo()
    {
        if (Token::has('user')) {
            $token=base64_decode(Token::get('user'));
            if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
                if ($uid=$this->uc->tokenAvailable(intval($match[1]), $match[2])) {
                    return $this->uc->getUserById([intval($uid['user'])]);
                }
            }
        }
        return 0;
    }
    protected function signout()
    {
        return $this->uc->deleteToken($this->client, $this->token);
    }

    protected function beat(string $token)
    {
        $token=base64_decode($token);
        if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
            if (!isset($match[3])) {
                return false;
            }
            $get=$this->uc->refreshToken(intval($match[1]), $this->client, $this->token, $match[3]);
            $token=base64_encode($get['id'].'.'.$get['token'].'.'.$get['value']);
            return ['token'=>$token,'time'=>$get['time']];
        }
        return false;
    }

    protected function available(string $token, string $value)
    {
        $token=base64_decode($token);
        if (preg_match('/^(\d+)[.]([a-zA-Z0-9]{32})(?:[.]([a-zA-Z0-9]{32}))?$/', $token, $match)) {
            if ($uid=$this->uc->verifyTokenValue(intval($match[1]), $match[2], $value)) {
                return $this->uc->setEmailAvailable([intval($uid)]);
            }
        }
        return false;
    }

    protected function set(string $email, string $avatar)
    {
        if ($email && !$this->uc->checkEmailFormat($email)) {
            return new ApiException('emailFormatError', 'You need a right format');
        }
        if ($user=self::getUserInfo()) {
            $user=array_shift($user);
            $get=$this->uc->createToken($user['id'], $this->client, $this->token, $this->request->ip(), 'Code');
            Token::set('user', $token= base64_encode($get['id'].'.'.$get['token']));
            return ['edit'=>$this->uc->editUser($user['id'], '', '',  $email, 0, 0, '', $avatar),'token'=>$token];
        }
        return false;
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
}
