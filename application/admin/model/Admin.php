<?php
namespace app\admin\model;

use think\Model;
use think\captcha\Captcha;

class Admin extends Model
{

    //添加管理员
    public function addAdmin($data) {
        if(empty($data) || !is_array($data)) {
            return false;
        }

        if($data['password']) {
            $data['password'] = md5($data['password']);
        }

        if($this->save($data)) {
            return true;
        }else {
            return false;
        }

    }

    //查询所有管理员
    public function getAdmin() {
        $pageRow = 3;
        $res = $this->paginate($pageRow);
        return $res;
    }

    //通过id查询管理员
    public function getAdminById($id) {
        $res = db('admin')->where(['id'=>$id])->find();
        return $res;
    }

    //通过id更新管理员
    public function updateAdminById($data, $adminRes) {

        if(!$data['username']) {
            return -1; //管理员用户名为空
        }

        if(!$data['password']) {
            $data['password'] = $adminRes['password']; //没填写密码，取原密码
        }else {
            $data['password'] = md5($data['password']);
        }

        $id = $data['id'];
        $res = db('admin')->where('id', $id)->update($data);

        return $res;
    }

    //通过id删除管理员
    public function deleteAdminById($id) {

        $res = db('admin')->where('id', $id)->delete();
        return $res;

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
