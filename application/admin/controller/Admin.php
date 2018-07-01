<?php
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use think\Loader;

class Admin extends Base
{
    public function lis()
    {
        $row = 10;
        $admin = new AdminModel();
        $adminRes = $admin->paginate($row);
        $this->assign("adminRes", $adminRes);
        return $this->fetch('lis');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input('post.');
            $admin = new AdminModel();

            $validate = Loader::validate('Admin');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }

            $data['password'] = md5($data['password']);

            $res = $admin->save($data);
            if($res) {
                $this->success('添加管理员成功！', 'lis');
            }else {
                $this->error('添加管理员失败！');
            }
        }
        return $this->fetch('add');
    }

    public function edit()
    {
        $admin = new AdminModel();
        $id = input('id');
        $adminRes = $admin->find($id); //查询不到NULL
        if (!$adminRes) {
            $this->error('无法查询到该管理员', url('admin/lis'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');

            $validate = Loader::validate('Admin');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            if(!$data['password']) {
                $data['password'] = $adminRes['password']; //没填写密码，取原密码
            }else {
                $data['password'] = md5($data['password']);
            }

            $res = $admin->update($data);
            if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改管理员成功', url('admin/lis'));
            }else {
                $this->error('修改管理员失败');
            }
        }

        $this->assign('admin', $adminRes);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $admin = new AdminModel();
        $res = $admin->where(['id'=>$id])->delete();
        if($res) {
            $this->success('删除管理员成功', url('admin/lis'));
        }else {
            $this->error('删除管理员失败');
        }
    }


    public function logout() {
        session(null);
        $this->success('退出系统成功', url('login/login'));
    }
}
