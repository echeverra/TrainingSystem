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
        //获取所有后台配置项
        $this->assign('confs', $confs);
    }

    public function ajaxReturn($code, $data, $msg='') {
        return json(['code'=>$code, 'data'=>$data, 'msg'=>$msg]);
    }
}
