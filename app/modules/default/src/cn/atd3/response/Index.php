<?php
namespace cn\atd3\response;
use suda\core\Request;

class Index extends \suda\core\Response {
    public function onRequest(Request $request){
        $this->display('default:helloworld',['helloworld'=>'Hello,World!']);
    }
}