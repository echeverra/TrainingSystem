<?php
namespace app\index\controller;

class ImgList extends Base
{
    public function imgList()
    {
        return $this->fetch('imgList');
    }
}
