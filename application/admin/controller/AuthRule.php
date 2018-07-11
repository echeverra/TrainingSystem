<?php
namespace app\admin\controller;
use app\admin\model\AuthRule as AuthRuleModel;
use think\Loader;

class AuthRule extends Base
{

    public $beforeActionList = [
        'delSonAuthRule' => ['only'=>"del"]
    ];

    public function lis()
    {
        $authRuleModel = new AuthRuleModel();

        if(request()->isPost()) {
            $sorts = input('post.');

            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100 || intval($v)<1 || !(is_numeric($v)&&!strpos($v, '.'))) {
                    $this->error('请输入1到100的正整数');
                }
            }
            foreach ($sorts as $k=>$v) {
                $authRuleModel->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        $authRules = $authRuleModel->getAuthRuleTree();
        $this->assign('auth_rules', $authRules);
        return $this->fetch('lis');
    }

    public function add()
    {
        $authRuleModel = new AuthRuleModel();
        if(request()->isPost()) {
            $data = input('post.');
            $pLevel = $authRuleModel->where('id', $data['pid'])->field('level')->find(); //id为0不存在，说明是顶级权限
            if($pLevel) {
                $data['level'] = $pLevel['level'] + 1;
            }else {
                $data['level'] = 0;
            }

            $validate = Loader::validate('AuthRule');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }

            if($authRuleModel->save($data)) {
                $this->success('添加权限成功', url('auth_rule/lis'));
            }else {
                $this->error('添加权限失败');
            }
        }
        $rules = $authRuleModel->getAuthRuleTree();
        $this->assign('rules', $rules);
        return $this->fetch('add');
    }

    public function edit()
    {
        $authRuleModel = new AuthRuleModel();
        if(request()->isPost()) {
            $data = input('post.');
            $pLevel = $authRuleModel->where('id', $data['pid'])->field('level')->find();
            if($pLevel) {
                $data['level'] = $pLevel['level'] + 1;
            }else {
                $data['level'] = 0;
            }

            $validate = Loader::validate('AuthRule');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            if($authRuleModel->update($data)) {
                $this->success('修改权限成功', url('auth_rule/lis'));
            }else {
                $this->error('修改权限失败');
            }
        }
        $id = input('id');
        $authRule = $authRuleModel->where('id',$id)->find();
        if(!$authRule) {
            $this->error('无法查询到该权限', url('auth_rule/lis'));
        }
        $rules = $authRuleModel->getAuthRuleTree();
        $this->assign(['auth_rule'=>$authRule, 'rules'=>$rules]);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $authRuleModel = new AuthRuleModel();
        $res = $authRuleModel->where(['id'=>$id])->delete();
        if($res) {
            $this->success('删除权限成功', url('auth_rule/lis'));
        }else {
            $this->error('删除权限失败');
        }
    }

    public function delSonAuthRule() {
        $authRuleId = input('id'); //要删除的当前权限的id
        $authRuleModel = new AuthRuleModel();
        $sonid = $authRuleModel->getChildrenId($authRuleId);
        if($sonid) {
            db('auth_rule')->delete($sonid);
        }
    }

}
