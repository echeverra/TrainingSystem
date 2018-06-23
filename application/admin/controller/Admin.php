<?php
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;

class Admin extends Base
{
    public function adminList()
    {
        $admin = new AdminModel();
        $adminRes = $admin->getAdmin();
        $this->assign("adminRes", $adminRes);
        return $this->fetch('adminList');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input('post.');
            $admin = new AdminModel();
            $res = $admin->addAdmin($data);

            if($res) {
                $this->success('添加管理员成功！', 'adminList');
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
        $adminRes = $admin->getAdminById($id); //查询不到NULL
        if (!$adminRes) {
            $this->error('无法查询到该管理员', url('admin/adminList'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');
            $res = $admin->updateAdminById($data, $adminRes);

            if($res == -1) {
                $this->error('管理员用户名不能为空');
            }else if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改管理员成功', url('admin/adminList'));
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
        $res = $admin->deleteAdminById($id);
        if($res) {
            $this->success('删除管理员成功', url('admin/adminList'));
        }else {
            $this->error('删除管理员失败');
        }
    }


    public function logout() {
        session(null);
        $this->success('退出系统成功', url('login/login'));
    }
}
