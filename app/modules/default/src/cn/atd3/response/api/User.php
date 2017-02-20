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
    public function onRequest(Request $request)
    {
        // params if had
        $action=$request->get()->action;
        
        // var_dump($action,$request->get());
        $uc=new UserCenter;
        //$uc->addUser(time(),'PASSWORD_BCRYPT','dxkite2@email.com'.time(),0,$request->ip())
        switch ($action) {
            case 'checkname':
                return $this->json([
                    'add'=>$uc->addUser(time(),'PASSWORD_BCRYPT','dxkite2@email.com'.time(),0,$request->ip()),
                    'return'=>$uc->checkNameExist($request->get()->name),
                    'id2name'=>$uc->id2name([6,7,8,9,10]),
                    'set'=>$uc->setUserPermission(10,['create']),
                    'get'=>$uc->getUserPermission(10),
                    // 'addClient'=>$uc->addClient(time(),'官方令牌'),
                    'listClient'=>$uc->listClient(),
                    'createToken'=>$uc->createToken(1,1,'c7b04d1534f1ed7bb9241cf5fe6ea11e',$request->ip()),
                    ]
                );
                break;

            default: // display json code
                return $this->json(['helloworld'=>'Hello,World!']);
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
