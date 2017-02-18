<?php
namespace cn\atd3\api;

class Error extends \suda\tool\Value
{
    public function __construct(string $name, string $message)
    {
        parent::__construct(['error'=>$name, 'message'=>$message]);
    }
}
