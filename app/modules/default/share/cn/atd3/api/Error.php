<?php
namespace api;

class Error extends \base\Value
{
    public function __construct(string $name, string $message)
    {
        parent::__construct(['error'=>$name, 'message'=>$message]);
    }
}
