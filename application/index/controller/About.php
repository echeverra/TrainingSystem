<?php
namespace app\index\controller;

class About extends Base
{
    public function about()
    {
        return $this->fetch('about');
    }
}
