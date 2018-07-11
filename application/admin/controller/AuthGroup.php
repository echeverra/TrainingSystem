<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;
use think\Loader;

class AuthGroup extends Base
{

    public function lis() {
        $authGroupModel = new AuthGroupModel();
        $auth_groups = $authGroupModel->select();

        $this->assign('auth_groups', $auth_groups);
        return $this->fetch('lis');
    }

    public function add() {

        if(request()->isPost()) {
            $data = input('post.');

            if(isset($data['status'])) {  //是否存在status字段，存在说明传值'on'
                $data['status'] = 1;
            }else {
                $data['status'] = 0;
            }

            if(isset($data['rules'])) {  //判断是否选中权限checkbox
                $data['rules'] = implode(',', $data['rules']);
            }else {
                $data['rules'] = '';
            }

            $authGroupModel = new AuthGroupModel();

            $validate = Loader::validate('AuthGroup');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }

            if($authGroupModel->save($data)) {
                $this->success('添加用户组成功', url('auth_group/lis'));
            }else {
                $this->error('添加用户组失败');
            }
        }

        $authRuleModel = new AuthRuleModel();
        $authRules = $authRuleModel->getAuthRuleTree();

        $this->assign('auth_rules', $authRules);
        return $this->fetch('add');
    }

    public function edit() {
        $id = input('id');
        $authGroupModel = new AuthGroupModel();

        if(request()->isPost()) {
            $data = input('post.');

            if(isset($data['status'])) {  //是否存在status字段，存在说明传值'on'
                $data['status'] = 1;
            }else {
                $data['status'] = 0;
            }

            if(isset($data['rules'])) {  //判断是否选中权限checkbox
                $data['rules'] = implode(',', $data['rules']);
            }else {
                $data['rules'] = '';
            }

            $validate = Loader::validate('AuthGroup');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            if($authGroupModel->update($data)) {
                $this->success('修改用户组成功', url('auth_group/lis'));
            }else {
                $this->error('修改用户组失败');
            }
        }
        $authRuleModel = new AuthRuleModel();
        $authRules = $authRuleModel->getAuthRuleTree();
        $auth_group = $authGroupModel->find($id);

        $this->assign(['auth_group'=>$auth_group, 'auth_rules'=>$authRules]);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $authGroupModel = new AuthGroupModel();
        if($authGroupModel->where('id', $id)->delete()) {
            $this->success('删除用户组成功', url('auth_group/lis'));
        }else {
            $this->error('删除用户组失败');
        }
    }
}
