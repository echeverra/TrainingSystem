<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Conf as ConfModel;

class Base extends Controller
{
    public function _initialize()
    {
        $confModel = new ConfModel();
        $confs = $confModel->getAllConf();
        $this->assign('confs', $confs);
    }
}
