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

use cn\atd3\User as UserCenter;

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

        switch ($action) {
            case 'checkname':
                return $this->json(['return'=>$uc->checkNameExist($request->get()->name)]);
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
