<?php
namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        if(request()->isPost()) {
            $data = input('post.');
            $admin = new AdminModel();

            $res = $admin->login($data);

            if($res == 1) {
                $this->success('登录成功,跳转中...', 'index/index');
            }else if($res == -1){
                $this->error('用户不存在');
            }else if($res == -2){
                $this->error('密码错误');
            }else if($res == -3){
                $this->error('用户名不能为空');
            }else if($res == -4){
                $this->error('密码不能为空');
            }else if($res == -5) {
                $this->error('验证码不能为空');
            }else if($res == -6) {
                $this->error('验证码错误');
            }
        }

        return $this->fetch('login');
    }
}
