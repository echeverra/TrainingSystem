<?php
namespace app\admin\model;

use think\Model;
use think\captcha\Captcha;

class Admin extends Model
{

    public function addAdmin($data) {

        $adminData = [];
        $adminData['username'] = $data['username'];
        $adminData['password'] = md5($data['password']);

        if($this->save($adminData)) {

            $groupAccessData = [];
            $groupAccessData['uid'] = $this->id;  //save admin后 id会保存在this中
            $groupAccessData['group_id'] = $data['group_id'];

            if(db('auth_group_access')->insert($groupAccessData)) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }

    }

    //管理员登录验证
    public function login($data) {
        $username = $data['username'];
        $password = $data['password'];
        $code = $data['code'];

        if(empty($username)) return -3;
        if(empty($password)) return -4;
        if(empty($code)) return -5;

        $captcha = new Captcha();
        if(!$captcha->check($code)) {
            return -6;
        }

        $admin = db('admin')->where('username', $username)->find();

        if($admin) {
            if(md5($password) == $admin['password']) {
                session('id', $admin['id']);
                session('username', $admin['username']);
                return 1; // success
            }else {
                return -2; //密码错误
            }
        }else {
            return -1; //用户不存在
        }
    }


}
