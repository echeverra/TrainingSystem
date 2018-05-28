<?php
namespace app\admin\controller;


class Index extends Base
{
    public function index()
    {
//        echo '111';die;
        return $this->fetch('index');
    }
}
