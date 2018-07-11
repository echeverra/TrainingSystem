<?php
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use app\admin\model\AuthGroup as AuthGroupModel;
use think\Loader;

class Admin extends Base
{
    public function lis()
    {
        $row = 10;
        $adminModel = new AdminModel();
        $admins = $adminModel->paginate($row);

        $auth = new Auth();
        foreach($admins as $k=>$v) {
            $_groupTitle = $auth->getGroups($v['id']);
            //判断处理没有群组的用户
            if($_groupTitle) {
                $groupTitle = $_groupTitle[0]['title'];
                $v['groupTitle'] = $groupTitle;
            }else {
                $v['groupTitle'] = '';
            }

        }
        $this->assign("admins", $admins);
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


            if($admin->addAdmin($data)) {
                $this->success('添加管理员成功！', 'lis');
            }else {
                $this->error('添加管理员失败！');
            }
        }

        $authGroupModel = new AuthGroupModel();
        $authGroups = $authGroupModel->select();

        $this->assign('auth_groups', $authGroups);
        return $this->fetch('add');
    }

    public function edit()
    {
        $adminModel = new AdminModel();
        $id = input('id');
        $admin = $adminModel->find($id); //查询不到NULL
        if (!$admin) {
            $this->error('无法查询到该管理员', url('admin/lis'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');

            $validate = Loader::validate('Admin');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            $adminData = [];
            $adminData['username'] = $data['username'];
            $adminData['id'] = $data['id'];

            if(!$data['password']) {
                $adminData['password'] = $admin['password']; //没填写密码，取原密码
            }else {
                $adminData['password'] = md5($data['password']);
            }

            $authGroupAccessAddRes = db('auth_group_access')->where('uid', $id)->update(['group_id'=>$data['group_id']]);
            $adminAddRes = $adminModel->update($adminData);

            if(($adminAddRes !== false) && ($authGroupAccessAddRes !== false) ) {
               $this->success('修改管理员成功', url('admin/lis'));
            }else {
                $this->error('修改管理员失败');
            }
        }
        $authGroupModel = new AuthGroupModel();
        $authGroups = $authGroupModel->select();
        $authGroupAccess = db('auth_group_access')->where('uid', $id)->find();
        $this->assign(['admin'=>$admin, 'auth_groups'=>$authGroups, 'authGroupAccess'=>$authGroupAccess]);
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
