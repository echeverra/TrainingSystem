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
        $res = $admin->getAdminById($id); //查询不到NULL
        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');
            if(!$data['username']) {
                $this->error('管理员用户名不为空', 'admin/edit');
            }
            if(!$data['password']) {
                $data['password'] = $res['password']; //没填写密码，取原密码
            }else {
                $data['password'] = md5($data['password']);
            }
            $id = $data['id'];
            $res = $admin->updateAdminById($id, $data);
            if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改管理员成功', 'admin/adminList');
            }else {
                $this->error('修改管理员失败', 'admin/edit');
            }
        }
        if (!$res) {
            $this->error('无法查询到该管理员', 'admin/adminList');
        }
        $this->assign('admin', $res);
        return $this->fetch('edit');
    }
}
