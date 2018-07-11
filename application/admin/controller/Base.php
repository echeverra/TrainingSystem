<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
        if(!session('username') || !session('id')) {
            $this->error('请先登录', 'login/login');
        }

        $auth = new Auth();
        $request = Request::instance();
        $con = $request->controller(); //获取当前控制器
        $action = $request->action(); //获取当前方法
        $name = $con.'/'.$action;
//        $name = strtolower($name);

        //无需权限的操作
        $noCheck = [
            'Index/index',
            'Admin/logout',
        ];

        //权限验证
        if(session('id') != 1) {
            if(!in_array($name, $noCheck)) {
                if(!$auth->check($name, session('id'))) {
                    $this->error('您的权限不够，您是'.$auth->getGroups(session('id'))[0]['title']);
                }
            }
        }
    }

    public function ajaxReturn($code, $data, $msg='') {
        return json(['code'=>$code, 'data'=>$data, 'msg'=>$msg]);
    }
}
