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
* visit url /verify.png as all method to run this class.
* you call use _I('verify_image',Array) to create path.
* @template: default:image.tpl.html
* @name: verify_image
* @url: /verify.png
* @param: 
*/
class Image extends \suda\core\Response
{
    public function onRequest(Request $request)
    {
        $this->type('png');
        $this->noCache();
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
