<?php
namespace app\admin\controller;


class Login extends Base
{
    public function login()
    {
        return $this->fetch('login');
    }
}
