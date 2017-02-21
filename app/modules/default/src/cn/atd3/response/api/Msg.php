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
use cn\atd3\Api;
use cn\atd3\ApiException;
use cn\atd3\Token;

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
        $action=$request->get()->action;
        $help=array(
            'send',
            'list',
            'delete',
            'get'
        );
        try {
            // param values array
            $data=$request->isJson()?new Value($request->json()):($request->isPost()?$request->post():$request->get());
            switch ($action) {
                
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
