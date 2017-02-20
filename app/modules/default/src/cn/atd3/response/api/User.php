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

use cn\atd3\UserCenter;
use cn\atd3\Api;
use cn\atd3\ApiException;
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
    protected $client_id;
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
        } else {
            return $this->json(['error'=>'no api client!']);
        }
        // params if had
        $action=$request->get()->action;
            
        try {
            // param values array
            $data=$request->isJson()?new Value($request->json()):($request->isPost()?$request->post():$request->get());
            switch ($action) {
                // 验证姓名
                case 'checkname': Api::check($data, ['name']);return $this->json(['return'=>$this->uc->checkNameExist($data->name)]);
                // 验证邮箱
                case 'checkemail': Api::check($data, ['email']);return $this->json(['return'=>$this->uc->checkEmailExist($data->email)]);
                // 注册
                case 'signup':Api::check($data, ['email', 'name', 'passwd', 'code']);return self::signup($data->name, $data->email, $data->passwd, $data->code);
                // 默认输出
                default:return $this->json(['default'=>'nothing', 'data'=>$data]);
            }
        } catch (ApiException $e) {
            return $this->json($e);
        } catch (\Exception $e) {
            return $this->json([ 'Exception'=>$e->getMessage()]);
        }
    }
    protected function signup($name, $email, $passwd, $data)
    {
        if (\cn\atd3\VerifyImage::checkCode($data)) {
            return $this->json(['return'=>$this->uc->addUser($name, $email, $passwd, 0, $this->request->ip())]);
        } else {
            return$this->json(new ApiException('codeError', 'You send a error human code'));
        }
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
