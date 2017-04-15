<?php
namespace cn\atd3\response;

use suda\core\{Session,Cookie,Request,Query};

/**
* visit url /signup as all method to run this class.
* you call use u('signup',Array) to create path.
* @template: default:signup.tpl.html
* @name: signup
* @url: /signup
* @param: 
*/
class Signup extends \suda\core\Response
{
    public function onRequest(Request $request)
    {
        // params if had
        ;
        // param values array
        $value=array();
        // display template
        return $this->display('user:signup', ['title'=>'Welcome to use Suda!','helloworld'=>'Hello,World!', 'value'=>$value]);
    }
}
