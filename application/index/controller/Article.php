<?php
namespace app\index\controller;

class Article extends Base
{
    public function article()
    {
        return $this->fetch('article');
    }
}
