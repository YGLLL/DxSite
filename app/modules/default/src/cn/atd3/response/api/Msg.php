<?php
namespace cn\atd3\response\api;

// use namespace
use suda\core\Request;
// database query
use suda\core\Query;
// site cookie
use suda\core\Cookie;


use cn\atd3\Session;
use cn\atd3\MsgCenter;
use cn\atd3\UserCenter;
use cn\atd3\User;
use cn\atd3\Api;
use cn\atd3\ApiException;
use cn\atd3\Token;
use suda\tool\Value;

/**
* visit url /api/msg[/{action}] as all method to run this class.
* you call use _I('msg_api',Array) to create path.
* @template: default:api/msg.tpl.html
* @name: msg_api
* @url: /api/msg[/{action}]
* @param: action:string,
*/
class Msg extends \suda\core\Response
{
    protected $mc;
    protected $uc;
    protected $uid;
    protected $request;
    public function onRequest(Request $request)
    {
        $this->mc=new MsgCenter;
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
        $this->uid=(new User($this->uc))->getUserId();

        $action=$request->get()->action;
        $help=array(
            'send'=>[
                'params'=> ['message', 'to'=>'int', 'type'=>['int', MsgCenter::TYPE_MESSAGE]],
                'comments'=>'发送私信消息',
            ] ,
            'inbox'=>[
                'params'=> ['type'=>['int', MsgCenter::TYPE_MESSAGE],'page'=>['int',1], 'count'=>['int',10]],
                'comments'=>'获取消息列表',
            ] ,
            'delete'=>[
                'params'=> ['ids'=>'array'],
                'comments'=>'删除消息列表',
            ] ,
        );

        try {
            // param values array
            $data=$request->isJson()?new Value($request->json()):($request->isPost()?$request->post():$request->get());
            
            switch ($action) {
                case 'inbox':
                    if (!$this->uid) {
                        throw new ApiException('NoUserException', 'please Login');
                    }
                    return $this->json(['return'=>$this->mc->inbox($this->uid, $data->type(MsgCenter::TYPE_MESSAGE), $data->page(1), $data->count(10))]);
                case 'send':
                   // todo ：发送信息的类型修改
                    if (!$this->uid) {
                        throw new ApiException('NoUserException', 'please Login');
                    }
                    Api::check($data, ['message', 'to'=>'int', 'type'=>['int', MsgCenter::TYPE_MESSAGE]]);
                    return $this->json(['return'=>$this->mc->send($this->uid, $data->to, $data->type, $data->message)]);
                case 'delete':
                    if (!$this->uid) {
                        throw new ApiException('NoUserException', 'please Login');
                    }
                    Api::check($data, ['ids'=>'array']);
                    return $this->json(['return'=>$this->mc->delete($this->uid, $data->ids)]);
                default:return $this->json($help);
            }
        } catch (ApiException $e) {
            return $this->json($e);
        } catch (\Exception $e) {
            return $this->json([ 'Exception'=>$e->getMessage()]);
        }
        return $this->json($help);
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
