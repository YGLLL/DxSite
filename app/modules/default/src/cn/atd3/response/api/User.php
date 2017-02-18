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
        // params if had
        $method=$request->get()->method('method');
        // param values array
        $value=array('method'=>$request->get()->method('method'),);
        // display json code 
        return $this->json(['helloworld'=>'Hello,World!', 'value'=>$value]);
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
