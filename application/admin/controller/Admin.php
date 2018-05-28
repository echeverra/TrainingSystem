<?php
namespace app\admin\controller;


class Admin extends Base
{
    public function adminList()
    {
        return $this->fetch('adminList');
    }

    public function add()
    {
        return $this->fetch('add');
    }
}
