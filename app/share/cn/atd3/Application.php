<?php
namespace cn\atd3;

class Application extends \suda\core\Application{
    function onRequest(\suda\core\Request $request ){
        Session::start();
        return true;
    }
}