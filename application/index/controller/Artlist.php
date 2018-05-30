<?php
namespace app\index\controller;

class ArtList extends Base
{
    public function artList()
    {
//        echo '111';die;
        return $this->fetch('artList');
    }
}
