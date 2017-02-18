<?php
namespace cn\atd3\response;

// use namespace
use suda\core\Request;
// database query
use suda\core\Query;
// site cookie
use suda\core\Cookie;
// site session
use suda\core\Session;

/**
* visit url /verify-code as GET method to run this class.
* you call use _I('verify_code',Array) to create path.
* @template: default:image_verify.tpl.html
* @name: verify_code
* @url: /verify-code
* @param: 
*/
class ImageVerify extends \suda\core\Response
{
    public function onRequest(Request $request)
    {
        $this->type('png');
        (new \cn\atd3\VerifyImage)->create();       
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
